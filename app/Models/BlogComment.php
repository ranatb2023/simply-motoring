<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'blog_post_id',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_website',
        'content',
        'parent_id',
        'status',
        'approved_at',
        'likes_count',
        'dislikes_count',
        'ip_address',
        'user_agent',
        'is_flagged',
        'is_indexed',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'likes_count' => 'integer',
        'dislikes_count' => 'integer',
        'is_flagged' => 'boolean',
        'is_indexed' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically capture IP and user agent
        static::creating(function ($comment) {
            if (empty($comment->ip_address)) {
                $comment->ip_address = request()->ip();
            }
            if (empty($comment->user_agent)) {
                $comment->user_agent = request()->userAgent();
            }
        });
    }

    /**
     * Get the post this comment belongs to.
     */
    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    /**
     * Get the user who wrote this comment (if registered).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for nested replies).
     */
    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment.
     */
    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get approved replies only.
     */
    public function approvedReplies()
    {
        return $this->replies()->where('status', 'approved');
    }

    /**
     * Scope to get only approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get only pending comments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get only spam comments.
     */
    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    /**
     * Scope to get only top-level comments (no parent).
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Check if comment is from a registered user.
     */
    public function isFromUser()
    {
        return !is_null($this->user_id);
    }

    /**
     * Check if comment is from a guest.
     */
    public function isFromGuest()
    {
        return is_null($this->user_id);
    }

    /**
     * Check if comment is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Approve the comment.
     */
    public function approve()
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->save();
    }

    /**
     * Mark as spam.
     */
    public function markAsSpam()
    {
        $this->status = 'spam';
        $this->save();
    }

    /**
     * Move to trash.
     */
    public function trash()
    {
        $this->status = 'trash';
        $this->save();
    }

    /**
     * Flag the comment for review.
     */
    public function flag()
    {
        $this->is_flagged = true;
        $this->save();
    }

    /**
     * Unflag the comment.
     */
    public function unflag()
    {
        $this->is_flagged = false;
        $this->save();
    }

    /**
     * Increment likes count.
     */
    public function like()
    {
        $this->increment('likes_count');
    }

    /**
     * Increment dislikes count.
     */
    public function dislike()
    {
        $this->increment('dislikes_count');
    }

    /**
     * Get the commenter's name (user or guest).
     */
    public function getCommenterNameAttribute()
    {
        return $this->user ? $this->user->name : $this->guest_name;
    }

    /**
     * Get the commenter's email (user or guest).
     */
    public function getCommenterEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->guest_email;
    }

    /**
     * Get the commenter's avatar URL.
     */
    public function getAvatarUrlAttribute()
    {
        $email = $this->commenter_email;
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=mp&s=80";
    }

    /**
     * Get formatted created date.
     */
    public function getCreatedDateAttribute()
    {
        return $this->created_at->format('F j, Y \a\t g:i A');
    }

    /**
     * Get human-readable created date.
     */
    public function getCreatedHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the reply count.
     */
    public function getReplyCountAttribute()
    {
        return $this->replies()->where('status', 'approved')->count();
    }
}
