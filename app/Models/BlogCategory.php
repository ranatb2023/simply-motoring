<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'parent_id',


        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',

    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from name if not provided
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id')->orderBy('name');
    }

    /**
     * Get all posts in this category (many-to-many).
     */
    public function posts()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_category', 'blog_category_id', 'blog_post_id')
            ->withPivot('is_primary')
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
     * Get posts where this is the primary category.
     */
    public function primaryPosts()
    {
        return $this->posts()->wherePivot('is_primary', true);
    }

    /**
     * Scope to get only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only root categories (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get the full category path (for breadcrumbs).
     */
    public function getPathAttribute()
    {
        $path = collect([$this->name]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }

        return $path->implode(' > ');
    }

    /**
     * Get the URL for this category.
     */
    public function getUrlAttribute()
    {
        return route('blog.category', $this->slug);
    }

    /**
     * Get meta title or fallback to name.
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->name;
    }

    /**
     * Get meta description or fallback to description.
     */
    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: Str::limit($this->description, 160);
    }

    /**
     * Get the post count for this category.
     */
    public function getPostCountAttribute()
    {
        return $this->posts()->where('status', 'published')->count();
    }
}
