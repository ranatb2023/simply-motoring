<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'usage_count',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'usage_count' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from name if not provided
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Get all posts with this tag (many-to-many).
     */
    public function posts()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tag', 'blog_tag_id', 'blog_post_id')
            ->withTimestamps();
    }

    /**
     * Get published posts only.
     */
    public function publishedPosts()
    {
        return $this->posts()
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc');
    }

    /**
     * Scope to get only active tags.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get popular tags (by usage count).
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->where('usage_count', '>', 0)
            ->orderBy('usage_count', 'desc')
            ->limit($limit);
    }

    /**
     * Increment the usage count.
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Decrement the usage count.
     */
    public function decrementUsage()
    {
        if ($this->usage_count > 0) {
            $this->decrement('usage_count');
        }
    }

    /**
     * Sync usage count with actual post count.
     */
    public function syncUsageCount()
    {
        $this->usage_count = $this->posts()->where('status', 'published')->count();
        $this->save();
    }

    /**
     * Get the URL for this tag.
     */
    public function getUrlAttribute()
    {
        return route('blog.tag', $this->slug);
    }

    /**
     * Get meta title or fallback to name.
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->name . ' - Blog Posts';
    }

    /**
     * Get meta description or fallback to description.
     */
    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: "Browse all blog posts tagged with {$this->name}";
    }
}
