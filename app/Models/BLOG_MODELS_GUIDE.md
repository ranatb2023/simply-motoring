# Blog Models - Usage Guide

## Models Created

1. **BlogCategory** - Hierarchical category system
2. **BlogTag** - Flexible tagging system
3. **BlogPost** - Main content with SEO features
4. **BlogComment** - User engagement with moderation

---

## Model Relationships

### BlogPost Relationships
```php
$post->author          // User who created the post
$post->categories      // All categories (many-to-many)
$post->primaryCategory() // Get the primary category
$post->tags            // All tags (many-to-many)
$post->comments        // All comments
$post->approvedComments // Only approved comments
```

### BlogCategory Relationships
```php
$category->parent      // Parent category
$category->children    // Child categories
$category->posts       // All posts (many-to-many)
$category->publishedPosts() // Only published posts
$category->primaryPosts()   // Posts where this is primary
```

### BlogTag Relationships
```php
$tag->posts           // All posts (many-to-many)
$tag->publishedPosts() // Only published posts
```

### BlogComment Relationships
```php
$comment->post        // The blog post
$comment->user        // User (if registered)
$comment->parent      // Parent comment (for replies)
$comment->replies     // All replies
$comment->approvedReplies() // Only approved replies
```

---

## Creating a Blog Post

### Basic Post Creation
```php
use App\Models\BlogPost;

$post = BlogPost::create([
    'author_id' => auth()->id(),
    'title' => 'Complete Guide to Car Maintenance',
    'content' => 'Your detailed content here...',
    'excerpt' => 'Short summary of the post',
    'status' => 'draft',
]);

// Slug, reading_time, and excerpt are auto-generated if not provided
```

### Full Post with SEO
```php
$post = BlogPost::create([
    'author_id' => auth()->id(),
    'title' => 'Complete Guide to Car Maintenance',
    'slug' => 'complete-guide-car-maintenance', // Optional, auto-generated
    'excerpt' => 'Learn everything about maintaining your vehicle...',
    'content' => 'Your detailed content here...',
    
    // Featured Image
    'featured_image' => '/images/car-maintenance.jpg',
    'featured_image_alt' => 'Mechanic performing car maintenance',
    'featured_image_caption' => 'Regular maintenance keeps your car running smoothly',
    
    // SEO Meta Tags
    'meta_title' => 'Car Maintenance Guide 2026 | Simply Motoring',
    'meta_description' => 'Expert tips on car maintenance. Learn how to keep your vehicle running smoothly.',
    'meta_keywords' => 'car maintenance, vehicle care, auto repair',
    'focus_keyword' => 'car maintenance',
    
    // Open Graph (Facebook, LinkedIn)
    'og_title' => 'Complete Guide to Car Maintenance',
    'og_description' => 'Expert tips on car maintenance for 2026',
    'og_image' => '/images/car-maintenance-og.jpg',
    
    // Twitter Cards
    'twitter_title' => 'Car Maintenance Guide',
    'twitter_description' => 'Expert maintenance tips for your vehicle',
    'twitter_image' => '/images/car-maintenance-twitter.jpg',
    
    // Publishing
    'status' => 'published',
    'published_at' => now(),
    
    // Settings
    'is_featured' => true,
    'allow_comments' => true,
    'is_indexed' => true,
    'is_followed' => true,
]);
```

### Attach Categories (Multiple)
```php
// Method 1: Using attachCategories helper
$post->attachCategories(
    categoryIds: [1, 2, 5],
    primaryCategoryId: 1  // Maintenance is primary
);

// Method 2: Manual attach with pivot data
$post->categories()->attach([
    1 => ['is_primary' => true],  // Maintenance (primary)
    2 => ['is_primary' => false], // DIY
    5 => ['is_primary' => false], // Guides
]);

// Method 3: Sync (replaces all categories)
$post->categories()->sync([
    1 => ['is_primary' => true],
    2 => ['is_primary' => false],
]);
```

### Attach Tags
```php
// Attach multiple tags
$post->tags()->attach([1, 2, 3, 4]);

// Or sync (replaces all tags)
$post->tags()->sync([1, 2, 3]);

// Detach specific tags
$post->tags()->detach([2, 3]);
```

### Auto-Generate Schema.org Data
```php
$schemaData = $post->generateSchemaData();
$post->schema_data = $schemaData;
$post->save();

// Use in blade template:
// <script type="application/ld+json">
//     {!! json_encode($post->schema_data) !!}
// </script>
```

---

## Querying Posts

### Published Posts
```php
// Get all published posts
$posts = BlogPost::published()->get();

// Published posts ordered by date
$posts = BlogPost::published()
    ->orderBy('published_at', 'desc')
    ->paginate(10);

// Featured published posts
$featured = BlogPost::published()
    ->featured()
    ->limit(3)
    ->get();
```

### Filter by Category
```php
// Posts in specific category
$posts = BlogPost::published()
    ->inCategory('maintenance')
    ->get();

// Posts with category relationship loaded
$posts = BlogPost::published()
    ->with('categories')
    ->get();

// Get primary category for each post
$posts = BlogPost::published()
    ->with(['categories' => function($query) {
        $query->wherePivot('is_primary', true);
    }])
    ->get();
```

### Filter by Tag
```php
// Posts with specific tag
$posts = BlogPost::published()
    ->withTag('brake-service')
    ->get();

// Posts with multiple tags (OR)
$posts = BlogPost::whereHas('tags', function($query) {
    $query->whereIn('slug', ['brake-service', 'maintenance']);
})->get();
```

### Search Posts
```php
// Full-text search
$posts = BlogPost::published()
    ->search('brake maintenance')
    ->get();
```

### Complex Queries
```php
// Published posts in category with tags
$posts = BlogPost::published()
    ->inCategory('maintenance')
    ->with(['tags', 'author', 'categories'])
    ->withCount('comments')
    ->orderBy('views_count', 'desc')
    ->paginate(10);
```

---

## Working with Categories

### Create Category
```php
use App\Models\BlogCategory;

$category = BlogCategory::create([
    'name' => 'Car Maintenance',
    'slug' => 'maintenance', // Optional, auto-generated
    'description' => 'Tips and guides for maintaining your vehicle',
    'meta_title' => 'Car Maintenance Tips | Simply Motoring',
    'meta_description' => 'Expert car maintenance tips and guides',
    'color' => '#3B82F6',
    'icon' => 'wrench',
    'is_active' => true,
]);
```

### Nested Categories
```php
// Create parent category
$parent = BlogCategory::create([
    'name' => 'Services',
    'slug' => 'services',
]);

// Create child category
$child = BlogCategory::create([
    'name' => 'Brake Services',
    'slug' => 'brake-services',
    'parent_id' => $parent->id,
]);

// Get all children
$children = $parent->children;

// Get category path (breadcrumb)
echo $child->path; // "Services > Brake Services"
```

### Query Categories
```php
// Active root categories only
$categories = BlogCategory::active()
    ->root()
    ->orderBy('order')
    ->get();

// Categories with post count
$categories = BlogCategory::withCount('posts')->get();

foreach ($categories as $category) {
    echo "{$category->name}: {$category->posts_count} posts";
}
```

---

## Working with Tags

### Create Tag
```php
use App\Models\BlogTag;

$tag = BlogTag::create([
    'name' => 'Brake Service',
    'slug' => 'brake-service', // Optional, auto-generated
    'description' => 'Posts about brake services and maintenance',
    'color' => '#10B981',
]);
```

### Popular Tags
```php
// Get top 10 popular tags
$popularTags = BlogTag::popular(10)->get();

// Active tags with usage count
$tags = BlogTag::active()
    ->where('usage_count', '>', 0)
    ->orderBy('usage_count', 'desc')
    ->get();
```

### Update Usage Count
```php
// Manually sync usage count
$tag->syncUsageCount();

// Usage count is auto-updated when posts are saved
```

---

## Working with Comments

### Create Comment (Registered User)
```php
use App\Models\BlogComment;

$comment = BlogComment::create([
    'blog_post_id' => $post->id,
    'user_id' => auth()->id(),
    'content' => 'Great article! Very helpful.',
    'status' => 'pending', // Requires moderation
]);
```

### Create Comment (Guest)
```php
$comment = BlogComment::create([
    'blog_post_id' => $post->id,
    'guest_name' => 'John Doe',
    'guest_email' => 'john@example.com',
    'guest_website' => 'https://example.com',
    'content' => 'Thanks for the tips!',
    'status' => 'pending',
]);
```

### Nested Replies
```php
// Reply to a comment
$reply = BlogComment::create([
    'blog_post_id' => $post->id,
    'user_id' => auth()->id(),
    'parent_id' => $comment->id, // Parent comment
    'content' => 'Glad you found it helpful!',
    'status' => 'approved',
]);

// Get all replies
$replies = $comment->replies;
```

### Moderation
```php
// Approve comment
$comment->approve();

// Mark as spam
$comment->markAsSpam();

// Move to trash
$comment->trash();

// Flag for review
$comment->flag();
```

### Query Comments
```php
// Get approved top-level comments for a post
$comments = BlogComment::where('blog_post_id', $post->id)
    ->approved()
    ->topLevel()
    ->with(['replies' => function($query) {
        $query->approved();
    }])
    ->orderBy('created_at', 'desc')
    ->get();

// Get pending comments for moderation
$pending = BlogComment::pending()
    ->with('post')
    ->orderBy('created_at', 'asc')
    ->get();
```

---

## Helper Methods

### BlogPost Helpers
```php
// Check status
$post->isPublished();  // true/false
$post->isScheduled();  // true/false

// Publish post
$post->publish(); // Sets status and published_at

// Increment counters
$post->incrementViews();
$post->incrementShares();

// Get URLs
$post->url;  // Full URL to post

// Get formatted dates
$post->published_date;  // "February 16, 2026"
$post->published_human; // "2 days ago"

// Get counts
$post->comment_count;  // Number of approved comments

// Get primary category
$primaryCategory = $post->primaryCategory();
```

### BlogCategory Helpers
```php
// Get URL
$category->url;  // Full URL to category page

// Get path (breadcrumb)
$category->path;  // "Services > Brake Services"

// Get post count
$category->post_count;  // Number of published posts
```

### BlogTag Helpers
```php
// Get URL
$tag->url;  // Full URL to tag page

// Update usage
$tag->incrementUsage();
$tag->decrementUsage();
$tag->syncUsageCount();
```

### BlogComment Helpers
```php
// Check comment type
$comment->isFromUser();  // true/false
$comment->isFromGuest(); // true/false
$comment->isApproved();  // true/false

// Get commenter info
$comment->commenter_name;  // User name or guest name
$comment->commenter_email; // User email or guest email
$comment->avatar_url;      // Gravatar URL

// Get formatted dates
$comment->created_date;  // "February 16, 2026 at 4:30 PM"
$comment->created_human; // "2 hours ago"

// Get counts
$comment->reply_count;  // Number of approved replies

// Engagement
$comment->like();
$comment->dislike();
```

---

## Blade Template Examples

### Display Post with SEO
```blade
@extends('layouts.app')

@section('title', $post->meta_title)
@section('meta_description', $post->meta_description)

@push('meta')
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $post->og_title }}">
    <meta property="og:description" content="{{ $post->og_description }}">
    <meta property="og:image" content="{{ asset($post->og_image) }}">
    <meta property="og:type" content="{{ $post->og_type }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->twitter_title }}">
    <meta name="twitter:description" content="{{ $post->twitter_description }}">
    <meta name="twitter:image" content="{{ asset($post->twitter_image) }}">
    
    <!-- Canonical -->
    <link rel="canonical" href="{{ $post->canonical_url }}">
    
    <!-- Schema.org -->
    <script type="application/ld+json">
        {!! json_encode($post->schema_data) !!}
    </script>
@endpush

@section('content')
    <article>
        <h1>{{ $post->title }}</h1>
        
        <div class="meta">
            By {{ $post->author->name }} | 
            {{ $post->published_date }} | 
            {{ $post->reading_time }} min read
        </div>
        
        @if($post->featured_image)
            <img src="{{ asset($post->featured_image) }}" 
                 alt="{{ $post->featured_image_alt }}">
        @endif
        
        <div class="content">
            {!! $post->content !!}
        </div>
        
        <!-- Categories -->
        <div class="categories">
            @foreach($post->categories as $category)
                <a href="{{ $category->url }}" 
                   style="background-color: {{ $category->color }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
        
        <!-- Tags -->
        <div class="tags">
            @foreach($post->tags as $tag)
                <a href="{{ $tag->url }}">
                    #{{ $tag->name }}
                </a>
            @endforeach
        </div>
        
        <!-- Comments -->
        @if($post->allow_comments)
            <div class="comments">
                <h3>{{ $post->comment_count }} Comments</h3>
                
                @foreach($post->approvedComments as $comment)
                    <div class="comment">
                        <img src="{{ $comment->avatar_url }}" alt="{{ $comment->commenter_name }}">
                        <strong>{{ $comment->commenter_name }}</strong>
                        <span>{{ $comment->created_human }}</span>
                        <p>{{ $comment->content }}</p>
                        
                        <!-- Replies -->
                        @foreach($comment->approvedReplies as $reply)
                            <div class="reply">
                                <img src="{{ $reply->avatar_url }}" alt="{{ $reply->commenter_name }}">
                                <strong>{{ $reply->commenter_name }}</strong>
                                <p>{{ $reply->content }}</p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </article>
@endsection
```

---

## Next Steps

1. **Create Controllers** for CRUD operations
2. **Create Routes** for blog pages
3. **Create Views** for listing and displaying posts
4. **Add Validation** for forms
5. **Implement Search** functionality
6. **Add Sitemap** generation
7. **Create RSS Feed**
8. **Add Admin Panel** for content management

See the main documentation for more details!
