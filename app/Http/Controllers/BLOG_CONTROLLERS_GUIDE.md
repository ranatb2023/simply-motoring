# Blog Controllers Documentation

## Controllers Created

1. **BlogController** - Public-facing blog display
2. **BlogCommentController** - Comment handling and moderation

---

## BlogController

### Purpose
Handles all public-facing blog functionality including listing posts, displaying single posts, category/tag archives, and search.

### Methods

#### `index(Request $request)`
**Route:** `GET /blog`  
**Name:** `blog.index`

Displays paginated list of blog posts with filtering and sorting.

**Query Parameters:**
- `search` - Full-text search query
- `category` - Filter by category slug
- `tag` - Filter by tag slug
- `sort` - Sorting: `latest` (default), `popular`, `trending`, `oldest`

**Returns:**
- `posts` - Paginated blog posts (12 per page)
- `featuredPosts` - Top 3 featured posts (cached)
- `popularTags` - Top 10 popular tags (cached)
- `categories` - Active root categories with post count (cached)

**Example:**
```
/blog
/blog?search=brake+maintenance
/blog?category=maintenance&sort=popular
/blog?tag=safety
```

---

#### `show($categorySlug, $postSlug)`
**Route:** `GET /blog/{category}/{post}`  
**Name:** `blog.show`

Displays a single blog post with all details.

**Features:**
- ✅ View tracking (once per session)
- ✅ Related posts (same category/tags)
- ✅ Previous/Next post navigation
- ✅ Approved comments with nested replies
- ✅ Verifies category matches post

**Returns:**
- `post` - Full post with relationships
- `category` - Current category
- `relatedPosts` - Up to 3 related posts
- `previousPost` - Previous post by date
- `nextPost` - Next post by date

**Example:**
```
/blog/maintenance/complete-brake-guide
```

---

#### `category($categorySlug)`
**Route:** `GET /blog/category/{category}`  
**Name:** `blog.category`

Displays all posts in a specific category.

**Returns:**
- `category` - Category details with SEO meta
- `posts` - Paginated posts in category
- `childCategories` - Child categories if any

**Example:**
```
/blog/category/maintenance
```

---

#### `tag($tagSlug)`
**Route:** `GET /blog/tag/{tag}`  
**Name:** `blog.tag`

Displays all posts with a specific tag.

**Returns:**
- `tag` - Tag details with SEO meta
- `posts` - Paginated posts with tag
- `relatedTags` - Top 5 related tags

**Example:**
```
/blog/tag/brake-pads
```

---

#### `post($postSlug)`
**Route:** `GET /blog/{post}`  
**Name:** `blog.post`

Fallback route that redirects to proper URL with primary category.

**Behavior:**
- Finds post by slug
- Gets primary category
- Redirects to `/blog/{category}/{post}` (301 permanent)
- Falls back to first category if no primary
- Shows post directly if no categories (rare case)

**Example:**
```
/blog/my-post → redirects to → /blog/maintenance/my-post
```

---

#### `search(Request $request)`
**Route:** `GET /blog/search`  
**Name:** `blog.search`

Full-text search across blog posts.

**Validation:**
- `q` - Required, 2-100 characters

**Returns:**
- `posts` - Paginated search results
- `query` - Search term

**Example:**
```
/blog/search?q=brake+maintenance
```

---

#### `share($postSlug)`
**Route:** `POST /blog/{post}/share`  
**Name:** `blog.share`

AJAX endpoint to track social shares.

**Returns:** JSON
```json
{
  "success": true,
  "shares_count": 15
}
```

---

## BlogCommentController

### Purpose
Handles comment submission, moderation, and engagement (likes/dislikes/flags).

### Methods

#### `store(Request $request, $postSlug)`
**Route:** `POST /blog/{post}/comments`  
**Name:** `blog.comments.store`

Submit a new comment or reply.

**Features:**
- ✅ Rate limiting (5 comments per minute)
- ✅ Supports authenticated users and guests
- ✅ Nested replies via `parent_id`
- ✅ Auto-moderation (authenticated = approved, guest = pending)
- ✅ Spam detection

**Validation (Authenticated Users):**
```php
'content' => 'required|string|min:3|max:2000',
'parent_id' => 'nullable|exists:blog_comments,id',
```

**Validation (Guest Users):**
```php
'content' => 'required|string|min:3|max:2000',
'parent_id' => 'nullable|exists:blog_comments,id',
'guest_name' => 'required|string|max:100',
'guest_email' => 'required|email|max:255',
'guest_website' => 'nullable|url|max:255',
```

**Returns:** Redirect with success/error message

**Example Form:**
```html
<form action="{{ route('blog.comments.store', $post->slug) }}" method="POST">
    @csrf
    <input type="hidden" name="parent_id" value="{{ $parentId ?? '' }}">
    
    @guest
        <input type="text" name="guest_name" placeholder="Your Name" required>
        <input type="email" name="guest_email" placeholder="Your Email" required>
        <input type="url" name="guest_website" placeholder="Website (optional)">
    @endguest
    
    <textarea name="content" required></textarea>
    <button type="submit">Post Comment</button>
</form>
```

---

#### `like(Request $request, $commentId)`
**Route:** `POST /blog/comments/{comment}/like`  
**Name:** `blog.comments.like`

Like a comment (AJAX).

**Features:**
- ✅ Rate limiting (10 likes per minute)
- ✅ Session tracking (one like per comment per session)

**Returns:** JSON
```json
{
  "success": true,
  "likes_count": 25
}
```

**Example JavaScript:**
```javascript
fetch('/blog/comments/123/like', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
})
.then(res => res.json())
.then(data => {
    console.log('Likes:', data.likes_count);
});
```

---

#### `dislike(Request $request, $commentId)`
**Route:** `POST /blog/comments/{comment}/dislike`  
**Name:** `blog.comments.dislike`

Dislike a comment (AJAX).

**Features:**
- ✅ Rate limiting (10 dislikes per minute)
- ✅ Session tracking (one dislike per comment per session)

**Returns:** JSON
```json
{
  "success": true,
  "dislikes_count": 3
}
```

---

#### `flag($commentId)`
**Route:** `POST /blog/comments/{comment}/flag`  
**Name:** `blog.comments.flag`

Flag a comment as inappropriate (AJAX).

**Features:**
- ✅ Session tracking (one flag per comment per session)
- ✅ Marks comment for admin review

**Returns:** JSON
```json
{
  "success": true,
  "message": "Comment has been flagged for review"
}
```

---

#### `destroy($commentId)`
**Route:** `DELETE /blog/comments/{comment}`  
**Name:** `blog.comments.destroy`  
**Middleware:** `auth`

Delete own comment (authenticated users only).

**Authorization:**
- User must be logged in
- User must own the comment

**Returns:** Redirect with success/error message

---

## Routes Summary

### Public Blog Routes
```php
GET  /blog                          → blog.index
GET  /blog/search                   → blog.search
GET  /blog/category/{category}      → blog.category
GET  /blog/tag/{tag}                → blog.tag
GET  /blog/{category}/{post}        → blog.show
GET  /blog/{post}                   → blog.post (redirects)
```

### Comment Routes
```php
POST   /blog/{post}/comments              → blog.comments.store
POST   /blog/comments/{comment}/like      → blog.comments.like
POST   /blog/comments/{comment}/dislike   → blog.comments.dislike
POST   /blog/comments/{comment}/flag      → blog.comments.flag
DELETE /blog/comments/{comment}           → blog.comments.destroy
```

### Share Tracking
```php
POST /blog/{post}/share → blog.share
```

---

## Performance Features

### Caching
The BlogController uses Laravel's cache for frequently accessed data:

```php
// Featured posts (cached for 1 hour)
Cache::remember('blog.featured', 3600, function () {
    return BlogPost::published()->featured()->limit(3)->get();
});

// Popular tags (cached for 1 hour)
Cache::remember('blog.popular_tags', 3600, function () {
    return BlogTag::popular(10)->get();
});

// Categories (cached for 1 hour)
Cache::remember('blog.categories', 3600, function () {
    return BlogCategory::active()->root()->withCount('posts')->get();
});
```

**Clear cache when content changes:**
```php
Cache::forget('blog.featured');
Cache::forget('blog.popular_tags');
Cache::forget('blog.categories');
```

---

### Rate Limiting

**Comment Submission:**
- 5 comments per minute per IP
- Prevents spam

**Like/Dislike:**
- 10 actions per minute per IP
- Prevents abuse

**Session Tracking:**
- One like/dislike/flag per comment per session
- Prevents duplicate actions

---

## Security Features

### CSRF Protection
All POST/DELETE routes require CSRF token:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### XSS Protection
Always escape user content in views:
```blade
{{ $comment->content }}  <!-- Escaped -->
{!! $post->content !!}   <!-- Trusted HTML from admin -->
```

### SQL Injection Protection
All queries use Eloquent ORM with parameter binding.

### Spam Detection
Basic spam detection in `isSpam()` method:
- Checks for spam keywords
- Limits excessive links (max 3)
- Detects excessive caps

---

## Usage Examples

### Display Blog Index
```blade
<!-- resources/views/blog/index.blade.php -->
@foreach($posts as $post)
    <article>
        <h2><a href="{{ $post->url }}">{{ $post->title }}</a></h2>
        <p>{{ $post->excerpt }}</p>
        
        <div class="meta">
            By {{ $post->author->name }} | 
            {{ $post->published_human }} | 
            {{ $post->reading_time }} min read |
            {{ $post->comment_count }} comments
        </div>
    </article>
@endforeach

{{ $posts->links() }}
```

### Display Single Post
```blade
<!-- resources/views/blog/show.blade.php -->
<article>
    <h1>{{ $post->title }}</h1>
    
    @if($post->featured_image)
        <img src="{{ asset($post->featured_image) }}" 
             alt="{{ $post->featured_image_alt }}">
    @endif
    
    <div class="content">
        {!! $post->content !!}
    </div>
    
    <!-- Comments -->
    @foreach($post->approvedComments as $comment)
        <div class="comment">
            <strong>{{ $comment->commenter_name }}</strong>
            <p>{{ $comment->content }}</p>
            
            <!-- Replies -->
            @foreach($comment->approvedReplies as $reply)
                <div class="reply">
                    <strong>{{ $reply->commenter_name }}</strong>
                    <p>{{ $reply->content }}</p>
                </div>
            @endforeach
        </div>
    @endforeach
</article>
```

---

## Testing

### Test Routes
```bash
# List all blog routes
php artisan route:list --name=blog

# Test in browser
http://localhost:8000/blog
http://localhost:8000/blog/category/maintenance
http://localhost:8000/blog/tag/safety
```

### Test in Tinker
```bash
php artisan tinker
```

```php
// Test BlogController methods
$controller = new App\Http\Controllers\BlogController();

// Get posts
$posts = App\Models\BlogPost::published()->get();

// Test view tracking
$post = App\Models\BlogPost::first();
$post->incrementViews();
echo $post->views_count;
```

---

## Next Steps

1. **Create Views** for blog display
2. **Add Admin Controller** for managing posts
3. **Implement Sitemap** generation
4. **Add RSS Feed**
5. **Create Email Notifications** for comment moderation

See the main documentation for more details!
