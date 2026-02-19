<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BlogPostController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['author', 'categories', 'tags'])
            ->withCount('comments');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('blog_categories.id', $request->category);
            });
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $posts = $query->paginate(20);

        // Get categories for filter dropdown
        $categories = BlogCategory::active()->orderBy('name')->get();

        return view('admin.blog.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = BlogCategory::active()->orderBy('name')->get();
        $tags = BlogTag::active()->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('admin.blog.posts.create', compact('categories', 'tags', 'users'));
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'author_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'featured_image_alt' => 'nullable|string|max:255',
            'featured_image_caption' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'focus_keyword' => 'nullable|string|max:100',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|image|max:2048',
            'twitter_title' => 'nullable|string|max:60',
            'twitter_description' => 'nullable|string|max:160',
            'twitter_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'scheduled_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'is_indexed' => 'boolean',
            'is_followed' => 'boolean',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:blog_categories,id',
            'primary_category' => 'required|exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string|max:500',
            'faqs.*.answer' => 'required_with:faqs|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('content'));
        }

        $validated = $validator->validated();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog/images', 'public');
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')
                ->store('blog/og-images', 'public');
        }

        // Handle Twitter image upload
        if ($request->hasFile('twitter_image')) {
            $validated['twitter_image'] = $request->file('twitter_image')
                ->store('blog/twitter-images', 'public');
        }

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle published_at based on status
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Create the post
        $categories = $validated['categories'];
        $primaryCategory = $validated['primary_category'];
        $tags = $validated['tags'] ?? [];

        // Process comma-separated tags
        if ($request->filled('tag_input')) {
            $tagNames = explode(',', $request->input('tag_input'));
            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                if (empty($tagName))
                    continue;

                $tag = BlogTag::firstOrCreate(
                    ['name' => $tagName],
                    ['slug' => Str::slug($tagName)]
                );

                if (!in_array($tag->id, $tags)) {
                    $tags[] = $tag->id;
                }
            }
        }

        $faqs = $validated['faqs'] ?? [];
        unset($validated['categories'], $validated['primary_category'], $validated['tags'], $validated['faqs']);

        // Process content images
        $validated['content'] = $this->processContentImages($validated['content']);

        $post = BlogPost::create($validated);

        // Attach categories
        $post->attachCategories($categories, $primaryCategory);

        // Attach tags
        if (!empty($tags)) {
            $post->tags()->attach($tags);
            // Update tag usage counts
            $post->tags->each->syncUsageCount();
        }

        // Save FAQs
        if (!empty($faqs)) {
            foreach ($faqs as $faqData) {
                if (!empty($faqData['question']) && !empty($faqData['answer'])) {
                    $post->faqs()->create($faqData);
                }
            }
        }

        // Generate schema data
        $post->schema_data = $post->generateSchemaData();
        $post->save();

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified post.
     */
    public function show(BlogPost $post)
    {
        $post->load(['author', 'categories', 'tags', 'comments', 'faqs']);

        return view('admin.blog.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(BlogPost $post)
    {
        $post->load('faqs');
        $categories = BlogCategory::active()->orderBy('name')->get();
        $tags = BlogTag::active()->orderBy('name')->get();

        // Get current primary category
        $primaryCategory = $post->categories()
            ->wherePivot('is_primary', true)
            ->first();

        $users = User::orderBy('name')->get();

        return view('admin.blog.posts.edit', compact('post', 'categories', 'tags', 'primaryCategory', 'users'));
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, BlogPost $post)
    {
        $validator = Validator::make($request->all(), [
            'author_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $post->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'featured_image_alt' => 'nullable|string|max:255',
            'featured_image_caption' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'canonical_url' => 'nullable|url',
            'focus_keyword' => 'nullable|string|max:100',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|image|max:2048',
            'twitter_title' => 'nullable|string|max:60',
            'twitter_description' => 'nullable|string|max:160',
            'twitter_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,scheduled,archived',
            'published_at' => 'nullable|date',
            'scheduled_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'is_indexed' => 'boolean',
            'is_followed' => 'boolean',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:blog_categories,id',
            'primary_category' => 'required|exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string|max:500',
            'faqs.*.answer' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            // dd($validator->errors()); // DEBUGGING: Show errors
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('content'));
        }

        $validated = $validator->validated();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('blog/images', 'public');
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            if ($post->og_image) {
                Storage::disk('public')->delete($post->og_image);
            }
            $validated['og_image'] = $request->file('og_image')
                ->store('blog/og-images', 'public');
        }

        // Handle Twitter image upload
        if ($request->hasFile('twitter_image')) {
            if ($post->twitter_image) {
                Storage::disk('public')->delete($post->twitter_image);
            }
            $validated['twitter_image'] = $request->file('twitter_image')
                ->store('blog/twitter-images', 'public');
        }

        // Handle published_at based on status
        if ($validated['status'] === 'published' && empty($post->published_at)) {
            $validated['published_at'] = now();
        }

        // Update the post
        $categories = $validated['categories'];
        $primaryCategory = $validated['primary_category'];
        $tags = $validated['tags'] ?? [];

        // Process comma-separated tags
        if ($request->filled('tag_input')) {
            $tagNames = explode(',', $request->input('tag_input'));
            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName);
                if (empty($tagName))
                    continue;

                $tag = BlogTag::firstOrCreate(
                    ['name' => $tagName],
                    ['slug' => Str::slug($tagName)]
                );

                if (!in_array($tag->id, $tags)) {
                    $tags[] = $tag->id;
                }
            }
        }

        $faqs = $validated['faqs'] ?? [];
        unset($validated['categories'], $validated['primary_category'], $validated['tags'], $validated['faqs']);

        // Process content images
        $validated['content'] = $this->processContentImages($validated['content']);

        // dd('About to update', count($faqs), substr($validated['content'], 0, 500));
        $post->update($validated);

        // Sync categories
        $post->attachCategories($categories, $primaryCategory);

        // Sync tags
        $post->tags()->sync($tags);
        // Update tag usage counts
        BlogTag::whereIn('id', $tags)->get()->each->syncUsageCount();

        // Sync FAQs (Delete all and recreate)
        $post->faqs()->delete();
        if (!empty($faqs)) {
            foreach ($faqs as $faqData) {
                if (!empty($faqData['question']) && !empty($faqData['answer'])) {
                    $post->faqs()->create($faqData);
                }
            }
        }

        // Regenerate schema data
        $post->schema_data = $post->generateSchemaData();
        $post->save();

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified post.
     */
    public function destroy(BlogPost $post)
    {
        // Delete images
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        if ($post->og_image) {
            Storage::disk('public')->delete($post->og_image);
        }
        if ($post->twitter_image) {
            Storage::disk('public')->delete($post->twitter_image);
        }

        // Update tag usage counts before deleting
        $post->tags->each->syncUsageCount();

        $post->delete();

        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Bulk actions for posts.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,draft,archive,delete',
            'posts' => 'required|array|min:1',
            'posts.*' => 'exists:blog_posts,id',
        ]);

        $posts = BlogPost::whereIn('id', $request->posts)->get();

        switch ($request->action) {
            case 'publish':
                foreach ($posts as $post) {
                    $post->publish();
                }
                $message = count($posts) . ' post(s) published successfully.';
                break;

            case 'draft':
                $posts->each(function ($post) {
                    $post->update(['status' => 'draft']);
                });
                $message = count($posts) . ' post(s) moved to draft.';
                break;

            case 'archive':
                $posts->each(function ($post) {
                    $post->update(['status' => 'archived']);
                });
                $message = count($posts) . ' post(s) archived.';
                break;

            case 'delete':
                $posts->each->delete();
                $message = count($posts) . ' post(s) deleted successfully.';
                break;
        }

        return redirect()->route('admin.blog.posts.index')
            ->with('success', $message);
    }

    /**
     * Process content images (convert base64 to file storage).
     */
    private function processContentImages($content)
    {
        // Fix HTML-encoded double src attributes (e.g. src="src=&quot;...&quot;")
        $content = preg_replace('/src=["\']src=&quot;([^&]+)&quot;["\']/', 'src="$1"', $content);

        // Fix double src attributes if they exist (legacy fix) - Run this FIRST to ensure base64 extraction works
        $content = preg_replace('/src=["\']src=["\']([^"\']+)["\']["\']/', 'src="$1"', $content);

        $content = preg_replace_callback(
            '/(src=["\'])data:image\/([^;]+);base64,([^"\']+)(["\'])/i',
            function ($matches) {
                $quoteStart = $matches[1];
                $extension = $matches[2];
                $base64Data = $matches[3];
                $quoteEnd = $matches[4];

                $imageData = base64_decode($base64Data);
                $fileName = 'blog/content-images/' . Str::random(40) . '.' . $extension;

                Storage::disk('public')->put($fileName, $imageData);

                return $quoteStart . Storage::url($fileName) . $quoteEnd;
            },
            $content
        );

        return $content;
    }
}
