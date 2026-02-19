<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::published()
            ->with(['categories', 'tags', 'author'])
            ->withCount('comments');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->inCategory($request->category);
        }

        // Filter by tag
        if ($request->has('tag') && $request->tag) {
            $query->withTag($request->tag);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'trending':
                $query->orderBy('shares_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            default: // latest
                $query->orderBy('published_at', 'desc');
                break;
        }

        $posts = $query->paginate(12);

        // Get sidebar data
        $sidebarData = $this->getSidebarData();
        $categories = $sidebarData['categories'];
        $featuredPosts = $sidebarData['featuredPosts'];
        $popularTags = $sidebarData['popularTags'];

        return view('blog.index', compact(
            'posts',
            'featuredPosts',
            'popularTags',
            'categories'
        ));
    }

    /**
     * Display a single blog post.
     */
    public function show($categorySlug, $postSlug)
    {
        $post = BlogPost::where('slug', $postSlug)
            ->published()
            ->with([
                'categories',
                'tags',
                'author',
                'faqs',
                'approvedComments' => function ($query) {
                    $query->with(['user', 'approvedReplies.user'])
                        ->orderBy('created_at', 'desc');
                }
            ])
            ->firstOrFail();

        // Verify the category matches
        $category = $post->categories()->where('slug', $categorySlug)->first();
        if (!$category) {
            abort(404);
        }

        // Increment views (only once per session)
        if (!session()->has('viewed_post_' . $post->id)) {
            $post->incrementViews();
            session()->put('viewed_post_' . $post->id, true);
        }

        // Get related posts (same category or tags)
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->whereHas('categories', function ($q) use ($post) {
                    $q->whereIn('blog_categories.id', $post->categories->pluck('id'));
                })
                    ->orWhereHas('tags', function ($q) use ($post) {
                        $q->whereIn('blog_tags.id', $post->tags->pluck('id'));
                    });
            })
            ->with(['categories', 'author'])
            ->limit(3)
            ->get();

        // Get previous and next posts
        $previousPost = BlogPost::published()
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = BlogPost::published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        // Get sidebar data
        $sidebarData = $this->getSidebarData();
        $categories = $sidebarData['categories'];
        $featuredPosts = $sidebarData['featuredPosts'];
        $popularTags = $sidebarData['popularTags'];

        return view('blog.show', compact(
            'post',
            'category',
            'relatedPosts',
            'previousPost',
            'nextPost',
            'categories',
            'featuredPosts',
            'popularTags'
        ));
    }

    /**
     * Display posts by category.
     */
    public function category($categorySlug)
    {
        $category = BlogCategory::where('slug', $categorySlug)
            ->active()
            ->firstOrFail();

        $posts = BlogPost::published()
            ->inCategory($categorySlug)
            ->with(['categories', 'tags', 'author'])
            ->withCount('comments')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Get child categories if any
        $childCategories = $category->children()
            ->active()
            ->withCount('posts')
            ->get();

        return view('blog.category', compact('category', 'posts', 'childCategories'));
    }

    /**
     * Display posts by tag.
     */
    public function tag($tagSlug)
    {
        $tag = BlogTag::where('slug', $tagSlug)
            ->active()
            ->firstOrFail();

        $posts = BlogPost::published()
            ->withTag($tagSlug)
            ->with(['categories', 'tags', 'author'])
            ->withCount('comments')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Get related tags (tags that appear with this tag)
        $relatedTags = BlogTag::whereHas('posts', function ($query) use ($tag) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('blog_tags.id', $tag->id);
            });
        })
            ->where('id', '!=', $tag->id)
            ->popular(5)
            ->get();

        return view('blog.tag', compact('tag', 'posts', 'relatedTags'));
    }

    /**
     * Alternative single post route (without category).
     */
    public function post($postSlug)
    {
        $post = BlogPost::where('slug', $postSlug)
            ->published()
            ->published()
            ->with(['categories', 'tags', 'author', 'faqs'])
            ->firstOrFail();

        return $this->showWithoutCategory($post);
    }

    /**
     * Show post without category (fallback).
     */
    protected function showWithoutCategory($post)
    {
        // Load relationships
        $post->load([
            'categories',
            'tags',
            'author',
            'faqs',
            'approvedComments' => function ($query) {
                $query->with(['user', 'approvedReplies.user'])
                    ->orderBy('created_at', 'desc');
            }
        ]);

        // Increment views
        if (!session()->has('viewed_post_' . $post->id)) {
            $post->incrementViews();
            session()->put('viewed_post_' . $post->id, true);
        }

        // Get related posts (same category or tags)
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->whereHas('categories', function ($q) use ($post) {
                    $q->whereIn('blog_categories.id', $post->categories->pluck('id'));
                })
                    ->orWhereHas('tags', function ($q) use ($post) {
                        $q->whereIn('blog_tags.id', $post->tags->pluck('id'));
                    });
            })
            ->with(['categories', 'author'])
            ->limit(3)
            ->get();

        // Get previous and next posts
        $previousPost = BlogPost::published()
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = BlogPost::published()
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        // Get sidebar data
        $sidebarData = $this->getSidebarData();
        $categories = $sidebarData['categories'];
        $featuredPosts = $sidebarData['featuredPosts'];
        $popularTags = $sidebarData['popularTags'];

        $category = $post->primary_category;

        return view('blog.show', compact(
            'post',
            'category',
            'relatedPosts',
            'previousPost',
            'nextPost',
            'categories',
            'featuredPosts',
            'popularTags'
        ));
    }

    /**
     * Search blog posts.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $query = $request->get('q');

        $posts = BlogPost::published()
            ->search($query)
            ->with(['categories', 'tags', 'author'])
            ->withCount('comments')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('blog.search', compact('posts', 'query'));
    }

    /**
     * Increment share count for a post.
     */
    public function share($postSlug)
    {
        $post = BlogPost::where('slug', $postSlug)->firstOrFail();
        $post->incrementShares();

        return response()->json([
            'success' => true,
            'shares_count' => $post->shares_count,
        ]);
    }

    /**
     * Get common sidebar data.
     */
    protected function getSidebarData()
    {
        $featuredPosts = Cache::remember('blog.featured', 3600, function () {
            return BlogPost::published()
                ->featured()
                ->with(['categories', 'author'])
                ->limit(3)
                ->get();
        });

        $popularTags = Cache::remember('blog.popular_tags', 3600, function () {
            return BlogTag::popular(10)->get();
        });

        $categories = Cache::remember('blog.categories', 3600, function () {
            return BlogCategory::active()
                ->root()
                ->withCount('posts')
                ->orderBy('name')
                ->get();
        });

        return compact('featuredPosts', 'popularTags', 'categories');
    }
}
