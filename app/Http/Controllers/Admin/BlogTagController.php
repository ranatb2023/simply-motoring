<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogTagController extends Controller
{
    /**
     * Display a listing of tags.
     */
    public function index()
    {
        $tags = BlogTag::withCount('posts')
            ->orderBy('usage_count', 'desc')
            ->orderBy('name')
            ->get();

        return view('admin.blog.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create()
    {
        return view('admin.blog.tags.create');
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Remvoed strict unique check here for slug to handle it manually
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'is_active' => 'boolean',
        ]);

        $name = $request->name;
        $slug = $request->slug ?: Str::slug($name);

        // Check if tag exists
        $tag = BlogTag::where('slug', $slug)->first();

        if ($tag) {
            // If request wants JSON, return the existing tag
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'tag' => $tag,
                    'message' => 'Tag already exists and selected.'
                ]);
            }

            // For normal requests, maybe redirect with error or success?
            return redirect()->route('admin.blog.tags.index')
                ->with('warning', 'Tag already exists.');
        }

        $validated = $request->all();
        $validated['slug'] = $slug;

        $tag = BlogTag::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'tag' => $tag,
                'message' => 'Tag created successfully.'
            ]);
        }

        return redirect()->route('admin.blog.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Display the specified tag.
     */
    public function show(BlogTag $tag)
    {
        $tag->load([
            'posts' => function ($query) {
                $query->with('author')->latest()->limit(10);
            }
        ]);

        return view('admin.blog.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit(BlogTag $tag)
    {
        return view('admin.blog.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, BlogTag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_tags,slug,' . $tag->id,
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'is_active' => 'boolean',
        ]);

        $tag->update($validated);

        return redirect()->route('admin.blog.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified tag.
     */
    public function destroy(BlogTag $tag)
    {
        // Check if tag has posts
        if ($tag->posts()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete tag with existing posts. Please remove the tag from posts first.'
            ]);
        }

        $tag->delete();

        return redirect()->route('admin.blog.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }

    /**
     * Sync usage counts for all tags.
     */
    public function syncUsageCounts()
    {
        $tags = BlogTag::all();

        foreach ($tags as $tag) {
            $tag->syncUsageCount();
        }

        return redirect()->route('admin.blog.tags.index')
            ->with('success', 'Tag usage counts synced successfully.');
    }

    /**
     * Bulk delete unused tags.
     */
    public function deleteUnused()
    {
        $count = BlogTag::where('usage_count', 0)->delete();

        return redirect()->route('admin.blog.tags.index')
            ->with('success', "{$count} unused tag(s) deleted successfully.");
    }
    /**
     * Perform bulk actions on selected tags.
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $selectedIds = $request->input('selected_ids', []);

        if (empty($selectedIds)) {
            return back()->withErrors(['message' => 'No tags selected.']);
        }

        if ($action === 'delete') {
            $tags = BlogTag::whereIn('id', $selectedIds)->get();
            $deletedCount = 0;
            $skippedCount = 0;

            foreach ($tags as $tag) {
                // Check if tag has posts
                if ($tag->posts()->count() > 0) {
                    $skippedCount++;
                    continue; // Skip deletion
                }

                $tag->delete();
                $deletedCount++;
            }

            $message = "$deletedCount tags deleted successfully.";
            if ($skippedCount > 0) {
                $message .= " $skippedCount tags were skipped because they contain posts.";
            }

            return redirect()->route('admin.blog.tags.index')
                ->with('success', $message);
        }

        return back()->withErrors(['message' => 'Invalid action selected.']);
    }
}
