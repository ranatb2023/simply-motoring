<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'featured_image_alt',
        'featured_image_caption',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'focus_keyword',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'schema_data',
        'status',
        'published_at',
        'scheduled_at',
        'reading_time',
        'views_count',
        'shares_count',
        'is_featured',
        'allow_comments',
        'is_indexed',
        'is_followed',
        'template',
        'order',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'schema_data' => 'array',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'is_indexed' => 'boolean',
        'is_followed' => 'boolean',
        'reading_time' => 'integer',
        'views_count' => 'integer',
        'shares_count' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title if not provided
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }

            // Calculate reading time if content exists
            if ($post->content && !$post->reading_time) {
                $post->reading_time = $post->calculateReadingTime($post->content);
            }

            // Auto-generate excerpt if not provided
            if (empty($post->excerpt) && $post->content) {
                $post->excerpt = Str::limit(strip_tags($post->content), 200);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }

            // Recalculate reading time if content changed
            if ($post->isDirty('content')) {
                $post->reading_time = $post->calculateReadingTime($post->content);
            }
        });

        // Update tag usage counts when post is saved
        static::saved(function ($post) {
            if ($post->wasRecentlyCreated || $post->wasChanged('status')) {
                $post->tags->each->syncUsageCount();
            }
        });
    }

    /**
     * Get the author of the post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get all categories for this post (many-to-many).
     */
    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category', 'blog_post_id', 'blog_category_id')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    /**
     * Get the primary category.
     */
    public function getPrimaryCategoryAttribute()
    {
        return $this->categories()->wherePivot('is_primary', true)->first();
    }

    /**
     * Get all tags for this post (many-to-many).
     */
    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag', 'blog_post_id', 'blog_tag_id')
            ->withTimestamps();
    }

    /**
     * Get all comments for this post.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id');
    }

    /**
     * Get all faqs for this post.
     */
    public function faqs()
    {
        return $this->hasMany(Faq::class, 'blog_post_id');
    }

    /**
     * Get approved comments only.
     */
    public function approvedComments()
    {
        return $this->comments()->where('status', 'approved')->whereNull('parent_id');
    }

    /**
     * Scope to get only published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to get only draft posts.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope to get only scheduled posts.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
            ->where('scheduled_at', '>', now());
    }

    /**
     * Scope to get only featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to search posts by keyword.
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->whereFullText(['title', 'content', 'excerpt'], $keyword);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeInCategory($query, $categorySlug)
    {
        return $query->whereHas('categories', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    /**
     * Scope to filter by tag.
     */
    public function scopeWithTag($query, $tagSlug)
    {
        return $query->whereHas('tags', function ($q) use ($tagSlug) {
            $q->where('slug', $tagSlug);
        });
    }

    /**
     * Check if the post is published.
     */
    public function isPublished()
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    /**
     * Check if the post is scheduled.
     */
    public function isScheduled()
    {
        return $this->status === 'scheduled' && $this->scheduled_at > now();
    }

    /**
     * Publish the post.
     */
    public function publish()
    {
        $this->status = 'published';
        $this->published_at = now();
        $this->save();
    }

    /**
     * Increment views count.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment shares count.
     */
    public function incrementShares()
    {
        $this->increment('shares_count');
    }

    /**
     * Calculate reading time in minutes.
     */
    protected function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / 200); // Average reading speed: 200 words/minute
        return max(1, $minutes); // Minimum 1 minute
    }

    /**
     * Get the URL for this post.
     */
    public function getUrlAttribute()
    {
        return route('blog.post', $this->slug);
    }

    /**
     * Get meta title or fallback to title.
     */
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    /**
     * Get meta description or fallback to excerpt.
     */
    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: Str::limit($this->excerpt, 160);
    }

    /**
     * Get Open Graph title or fallback to meta title.
     */
    public function getOgTitleAttribute($value)
    {
        return $value ?: $this->meta_title;
    }

    /**
     * Get Open Graph description or fallback to meta description.
     */
    public function getOgDescriptionAttribute($value)
    {
        return $value ?: $this->meta_description;
    }

    /**
     * Get Open Graph image or fallback to featured image.
     */
    public function getOgImageAttribute($value)
    {
        return $value ?: $this->featured_image;
    }

    /**
     * Get Twitter title or fallback to OG title.
     */
    public function getTwitterTitleAttribute($value)
    {
        return $value ?: $this->og_title;
    }

    /**
     * Get Twitter description or fallback to OG description.
     */
    public function getTwitterDescriptionAttribute($value)
    {
        return $value ?: $this->og_description;
    }

    /**
     * Get Twitter image or fallback to OG image.
     */
    public function getTwitterImageAttribute($value)
    {
        return $value ?: $this->og_image;
    }

    /**
     * Get the canonical URL or fallback to post URL.
     */
    public function getCanonicalUrlAttribute($value)
    {
        return $value ?: $this->url;
    }

    /**
     * Get formatted published date.
     */
    public function getPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('F j, Y') : null;
    }

    /**
     * Get human-readable published date.
     */
    public function getPublishedHumanAttribute()
    {
        return $this->published_at ? $this->published_at->diffForHumans() : null;
    }

    /**
     * Get the comment count.
     */
    public function getCommentCountAttribute()
    {
        return $this->comments()->where('status', 'approved')->count();
    }

    /**
     * Generate Schema.org Article structured data.
     */
    public function generateSchemaData()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $this->title,
            'description' => $this->meta_description,
            'image' => $this->featured_image ? url($this->featured_image) : null,
            'datePublished' => $this->published_at?->toIso8601String(),
            'dateModified' => $this->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $this->author->name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => url('/images/logo.png'),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $this->url,
            ],
        ];
    }

    /**
     * Attach categories with primary flag.
     */
    public function attachCategories(array $categoryIds, $primaryCategoryId = null)
    {
        $syncData = [];

        foreach ($categoryIds as $categoryId) {
            $syncData[$categoryId] = [
                'is_primary' => $categoryId == $primaryCategoryId,
            ];
        }

        $this->categories()->sync($syncData);
    }
}
