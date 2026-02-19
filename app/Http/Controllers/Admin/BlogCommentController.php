<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    /**
     * Display a listing of comments.
     */
    public function index(Request $request)
    {
        $query = BlogComment::with(['post', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by flagged
        if ($request->has('flagged') && $request->flagged) {
            $query->where('is_flagged', true);
        }

        // Filter by post
        if ($request->has('post') && $request->post) {
            $query->where('blog_post_id', $request->post);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('content', 'like', '%' . $request->search . '%')
                    ->orWhere('guest_name', 'like', '%' . $request->search . '%')
                    ->orWhere('guest_email', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $comments = $query->paginate(20);

        // Get posts for filter dropdown
        $posts = BlogPost::orderBy('title')->get();

        // Get comment counts by status
        $statusCounts = [
            'all' => BlogComment::count(),
            'pending' => BlogComment::where('status', 'pending')->count(),
            'approved' => BlogComment::where('status', 'approved')->count(),
            'spam' => BlogComment::where('status', 'spam')->count(),
            'trash' => BlogComment::where('status', 'trash')->count(),
            'flagged' => BlogComment::where('is_flagged', true)->count(),
        ];

        return view('admin.blog.comments.index', compact('comments', 'posts', 'statusCounts'));
    }

    /**
     * Display the specified comment.
     */
    public function show(BlogComment $comment)
    {
        $comment->load(['post', 'user', 'parent', 'replies']);

        return view('admin.blog.comments.show', compact('comment'));
    }

    /**
     * Approve a comment.
     */
    public function approve(BlogComment $comment)
    {
        $comment->approve();

        return back()->with('success', 'Comment approved successfully.');
    }

    /**
     * Mark comment as spam.
     */
    public function spam(BlogComment $comment)
    {
        $comment->markAsSpam();

        return back()->with('success', 'Comment marked as spam.');
    }

    /**
     * Move comment to trash.
     */
    public function trash(BlogComment $comment)
    {
        $comment->trash();

        return back()->with('success', 'Comment moved to trash.');
    }

    /**
     * Restore comment from trash.
     */
    public function restore(BlogComment $comment)
    {
        $comment->status = 'pending';
        $comment->save();

        return back()->with('success', 'Comment restored to pending.');
    }

    /**
     * Permanently delete a comment.
     */
    public function destroy(BlogComment $comment)
    {
        $comment->forceDelete();

        return redirect()->route('admin.blog.comments.index')
            ->with('success', 'Comment permanently deleted.');
    }

    /**
     * Unflag a comment.
     */
    public function unflag(BlogComment $comment)
    {
        $comment->unflag();

        return back()->with('success', 'Comment unflagged.');
    }

    /**
     * Bulk actions for comments.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,spam,trash,delete',
            'comments' => 'required|array|min:1',
            'comments.*' => 'exists:blog_comments,id',
        ]);

        $comments = BlogComment::whereIn('id', $request->comments)->get();

        switch ($request->action) {
            case 'approve':
                $comments->each->approve();
                $message = count($comments) . ' comment(s) approved successfully.';
                break;

            case 'spam':
                $comments->each->markAsSpam();
                $message = count($comments) . ' comment(s) marked as spam.';
                break;

            case 'trash':
                $comments->each->trash();
                $message = count($comments) . ' comment(s) moved to trash.';
                break;

            case 'delete':
                $comments->each->forceDelete();
                $message = count($comments) . ' comment(s) permanently deleted.';
                break;
        }

        return redirect()->route('admin.blog.comments.index')
            ->with('success', $message);
    }

    /**
     * Empty trash (delete all trashed comments).
     */
    public function emptyTrash()
    {
        $count = BlogComment::where('status', 'trash')->forceDelete();

        return redirect()->route('admin.blog.comments.index')
            ->with('success', "{$count} comment(s) permanently deleted from trash.");
    }

    /**
     * Delete all spam comments.
     */
    public function deleteSpam()
    {
        $count = BlogComment::where('status', 'spam')->forceDelete();

        return redirect()->route('admin.blog.comments.index')
            ->with('success', "{$count} spam comment(s) permanently deleted.");
    }
}
