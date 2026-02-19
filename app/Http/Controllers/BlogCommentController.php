<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class BlogCommentController extends Controller
{
    /**
     * Store a new comment.
     */
    public function store(Request $request, $postSlug)
    {
        $post = BlogPost::where('slug', $postSlug)
            ->published()
            ->firstOrFail();

        // Check if comments are allowed
        if (!$post->allow_comments) {
            return back()->with('error', 'Comments are not allowed on this post.');
        }

        // Rate limiting: 5 comments per minute
        $key = 'comment-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Too many comments. Please try again in {$seconds} seconds.");
        }

        // Validation rules differ for authenticated vs guest users
        $rules = [
            'content' => 'required|string|min:3|max:2000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ];

        if (!Auth::check()) {
            // Guest user validation
            $rules['guest_name'] = 'required|string|max:100';
            $rules['guest_email'] = 'required|email|max:255';
            $rules['guest_website'] = 'nullable|url|max:255';
        }

        $validated = $request->validate($rules);

        // Check if parent comment exists and belongs to this post
        if ($request->parent_id) {
            $parentComment = BlogComment::where('id', $request->parent_id)
                ->where('blog_post_id', $post->id)
                ->firstOrFail();
        }

        // Create the comment
        $commentData = [
            'blog_post_id' => $post->id,
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => $this->getCommentStatus(),
        ];

        if (Auth::check()) {
            $commentData['user_id'] = Auth::id();
        } else {
            $commentData['guest_name'] = $validated['guest_name'];
            $commentData['guest_email'] = $validated['guest_email'];
            $commentData['guest_website'] = $validated['guest_website'] ?? null;
        }

        $comment = BlogComment::create($commentData);

        // Increment rate limiter
        RateLimiter::hit($key, 60); // 60 seconds

        // Determine success message
        $message = $comment->status === 'approved'
            ? 'Your comment has been posted successfully!'
            : 'Your comment has been submitted and is awaiting moderation.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'comment' => $comment
            ]);
        }

        return back()->with('success', $message)->with('comment_id', $comment->id);
    }

    /**
     * Like a comment.
     */
    public function like(Request $request, $commentId)
    {
        $comment = BlogComment::findOrFail($commentId);

        // Rate limiting: 10 likes per minute
        $key = 'like-' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json(['error' => 'Too many requests'], 429);
        }

        // Check if user already liked this comment (using session)
        $sessionKey = 'liked_comment_' . $commentId;
        if (session()->has($sessionKey)) {
            return response()->json([
                'error' => 'You have already liked this comment',
            ], 400);
        }

        $comment->like();
        session()->put($sessionKey, true);
        RateLimiter::hit($key, 60);

        return response()->json([
            'success' => true,
            'likes_count' => $comment->likes_count,
        ]);
    }

    /**
     * Dislike a comment.
     */
    public function dislike($commentId)
    {
        $comment = BlogComment::findOrFail($commentId);

        // Rate limiting
        $key = 'dislike-' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json(['error' => 'Too many requests'], 429);
        }

        // Check if user already disliked this comment
        $sessionKey = 'disliked_comment_' . $commentId;
        if (session()->has($sessionKey)) {
            return response()->json([
                'error' => 'You have already disliked this comment',
            ], 400);
        }

        $comment->dislike();
        session()->put($sessionKey, true);
        RateLimiter::hit($key, 60);

        return response()->json([
            'success' => true,
            'dislikes_count' => $comment->dislikes_count,
        ]);
    }

    /**
     * Flag a comment as inappropriate.
     */
    public function flag($commentId)
    {
        $comment = BlogComment::findOrFail($commentId);

        // Check if user already flagged this comment
        $sessionKey = 'flagged_comment_' . $commentId;
        if (session()->has($sessionKey)) {
            return response()->json([
                'error' => 'You have already flagged this comment',
            ], 400);
        }

        $comment->flag();
        session()->put($sessionKey, true);

        return response()->json([
            'success' => true,
            'message' => 'Comment has been flagged for review',
        ]);
    }

    /**
     * Delete own comment (authenticated users only).
     */
    public function destroy($commentId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'You must be logged in to delete comments.');
        }

        $comment = BlogComment::findOrFail($commentId);

        // Check if user owns this comment
        if ($comment->user_id !== Auth::id()) {
            return back()->with('error', 'You can only delete your own comments.');
        }

        // Soft delete the comment
        $comment->delete();

        return back()->with('success', 'Your comment has been deleted.');
    }

    /**
     * Determine comment status based on user and settings.
     */
    protected function getCommentStatus()
    {
        // If user is authenticated, auto-approve
        if (Auth::check()) {
            return 'approved';
        }

        // For guest comments, require moderation
        // You can add more sophisticated logic here:
        // - Check if guest email has approved comments before
        // - Use spam detection service
        // - Check against blacklist

        return 'pending';
    }

    /**
     * Check if content might be spam (basic implementation).
     */
    protected function isSpam($content, $email = null)
    {
        // Basic spam detection
        $spamKeywords = [
            'viagra',
            'cialis',
            'casino',
            'poker',
            'lottery',
            'click here',
            'buy now',
            'limited time',
            'act now',
        ];

        $contentLower = strtolower($content);

        foreach ($spamKeywords as $keyword) {
            if (str_contains($contentLower, $keyword)) {
                return true;
            }
        }

        // Check for excessive links
        $linkCount = substr_count($contentLower, 'http');
        if ($linkCount > 3) {
            return true;
        }

        // Check for excessive caps
        $capsCount = preg_match_all('/[A-Z]/', $content);
        $totalChars = strlen($content);
        if ($totalChars > 0 && ($capsCount / $totalChars) > 0.5) {
            return true;
        }

        return false;
    }
}
