<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = BlogCategory::withCount('posts')
            ->with('parent')

            ->orderBy('name')
            ->get();

        return view('admin.blog.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $parentCategories = BlogCategory::whereNull('parent_id')
            ->active()
            ->orderBy('name')
            ->get();

        return view('admin.blog.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        // Auto-generate slug from name if not provided
        if (!$request->filled('slug') && $request->filled('name')) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'is_active' => 'boolean',
        ]);

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')
                ->store('blog/category-images', 'public');
        }

        $category = BlogCategory::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'category' => $category,
                'message' => 'Category created successfully.'
            ]);
        }

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(BlogCategory $category)
    {
        $category->load([
            'posts' => function ($query) {
                $query->with('author')->latest()->limit(10);
            },
            'children'
        ]);

        return view('admin.blog.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(BlogCategory $category)
    {
        $parentCategories = BlogCategory::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->active()
            ->orderBy('name')
            ->get();

        return view('admin.blog.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, BlogCategory $category)
    {
        // Auto-generate slug from name if not provided
        if (!$request->filled('slug') && $request->filled('name')) {
            $request->merge(['slug' => Str::slug($request->name)]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:160',
            'og_image' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'is_active' => 'boolean',
        ]);

        // Prevent category from being its own parent
        if ($validated['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'A category cannot be its own parent.']);
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            if ($category->og_image) {
                Storage::disk('public')->delete($category->og_image);
            }
            $validated['og_image'] = $request->file('og_image')
                ->store('blog/category-images', 'public');
        }

        $category->update($validated);

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(BlogCategory $category)
    {
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return back()->withErrors([
                'error' => 'Cannot delete category with existing posts. Please reassign or delete the posts first.'
            ]);
        }

        // Delete OG image
        if ($category->og_image) {
            Storage::disk('public')->delete($category->og_image);
        }

        $category->delete();

        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Perform bulk actions on selected categories.
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $selectedIds = $request->input('selected_ids', []);

        if (empty($selectedIds)) {
            return back()->withErrors(['message' => 'No categories selected.']);
        }

        if ($action === 'delete') {
            $categories = BlogCategory::whereIn('id', $selectedIds)->get();
            $deletedCount = 0;
            $skippedCount = 0;

            foreach ($categories as $category) {
                // Check if category has posts
                if ($category->posts()->count() > 0) {
                    $skippedCount++;
                    continue; // Skip deletion
                }

                // Delete OG image
                if ($category->og_image) {
                    Storage::disk('public')->delete($category->og_image);
                }

                $category->delete();
                $deletedCount++;
            }

            $message = "$deletedCount categories deleted successfully.";
            if ($skippedCount > 0) {
                $message .= " $skippedCount categories were skipped because they contain posts.";
            }

            return redirect()->route('admin.blog.categories.index')
                ->with('success', $message);
        }

        return back()->withErrors(['message' => 'Invalid action selected.']);
    }
}
