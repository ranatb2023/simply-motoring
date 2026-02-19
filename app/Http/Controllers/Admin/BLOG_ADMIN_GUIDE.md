# Blog Admin Controllers Documentation

## Controllers Created

1. **BlogPostController** - Manage blog posts (CRUD + bulk actions)
2. **BlogCategoryController** - Manage categories (CRUD + reordering)
3. **BlogTagController** - Manage tags (CRUD + usage sync)
4. **BlogCommentController** - Moderate comments (approve/spam/trash)

---

## BlogPostController

### Purpose
Complete CRUD management for blog posts with image uploads, SEO fields, and bulk operations.

### Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/blog/posts` | admin.blog.posts.index | List all posts |
| GET | `/admin/blog/posts/create` | admin.blog.posts.create | Create form |
| POST | `/admin/blog/posts` | admin.blog.posts.store | Store new post |
| GET | `/admin/blog/posts/{post}` | admin.blog.posts.show | View post |
| GET | `/admin/blog/posts/{post}/edit` | admin.blog.posts.edit | Edit form |
| PUT/PATCH | `/admin/blog/posts/{post}` | admin.blog.posts.update | Update post |
| DELETE | `/admin/blog/posts/{post}` | admin.blog.posts.destroy | Delete post |
| POST | `/admin/blog/posts/bulk-action` | admin.blog.posts.bulk-action | Bulk actions |

### Features

#### Index (List Posts)
**Query Parameters:**
- `status` - Filter by status (all, draft, published, scheduled, archived)
- `category` - Filter by category ID
- `search` - Search in title and content
- `sort` - Sort column (created_at, title, views_count, etc.)
- `order` - Sort order (asc, desc)

**Returns:**
- Paginated posts (20 per page)
- Categories for filter dropdown

#### Create/Store
**Validation:**
- `title` - Required, max 255 chars
- `slug` - Optional (auto-generated), unique
- `excerpt` - Optional, max 500 chars
- `content` - Required
- `featured_image` - Optional image, max 2MB
- `meta_title` - Optional, max 60 chars
- `meta_description` - Optional, max 160 chars
- `og_image` - Optional image, max 2MB
- `twitter_image` - Optional image, max 2MB
- `status` - Required (draft, published, scheduled, archived)
- `categories` - Required array, min 1
- `primary_category` - Required, must be in categories
- `tags` - Optional array

**Features:**
- Auto-generates slug from title
- Uploads images to `storage/app/public/blog/`
- Auto-sets `published_at` for published posts
- Attaches categories with primary flag
- Attaches tags and updates usage counts
- Generates Schema.org data

#### Update
Same validation as create, plus:
- Deletes old images when new ones uploaded
- Unique slug validation excludes current post

#### Destroy
- Deletes all associated images
- Updates tag usage counts
- Soft deletes post (recoverable)

#### Bulk Actions
**Actions:**
- `publish` - Publish selected posts
- `draft` - Move to draft
- `archive` - Archive posts
- `delete` - Delete posts

**Validation:**
- `action` - Required
- `posts` - Required array of post IDs

---

## BlogCategoryController

### Purpose
Manage blog categories with hierarchy support and SEO fields.

### Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/blog/categories` | admin.blog.categories.index | List categories |
| GET | `/admin/blog/categories/create` | admin.blog.categories.create | Create form |
| POST | `/admin/blog/categories` | admin.blog.categories.store | Store category |
| GET | `/admin/blog/categories/{category}` | admin.blog.categories.show | View category |
| GET | `/admin/blog/categories/{category}/edit` | admin.blog.categories.edit | Edit form |
| PUT/PATCH | `/admin/blog/categories/{category}` | admin.blog.categories.update | Update category |
| DELETE | `/admin/blog/categories/{category}` | admin.blog.categories.destroy | Delete category |
| POST | `/admin/blog/categories/reorder` | admin.blog.categories.reorder | Reorder categories |

### Features

#### Index
- Shows all categories with post count
- Displays parent-child relationships
- Ordered by `order` then `name`

#### Create/Store
**Validation:**
- `name` - Required, max 255 chars
- `slug` - Optional (auto-generated), unique
- `description` - Optional
- `meta_title` - Optional, max 60 chars
- `meta_description` - Optional, max 160 chars
- `og_image` - Optional image, max 2MB
- `parent_id` - Optional, must exist
- `color` - Optional hex color (default: #3B82F6)
- `icon` - Optional icon class
- `order` - Optional integer
- `is_active` - Boolean

**Features:**
- Auto-generates slug
- Sets default color
- Supports nested categories

#### Update
- Prevents category from being its own parent
- Deletes old OG image when new one uploaded

#### Destroy
- Checks if category has posts (prevents deletion)
- Deletes OG image
- Soft deletes category

#### Reorder (AJAX)
**Request:**
```json
{
  "categories": [
    {"id": 1, "order": 0},
    {"id": 2, "order": 1}
  ]
}
```

---

## BlogTagController

### Purpose
Manage blog tags with usage tracking.

### Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/blog/tags` | admin.blog.tags.index | List tags |
| GET | `/admin/blog/tags/create` | admin.blog.tags.create | Create form |
| POST | `/admin/blog/tags` | admin.blog.tags.store | Store tag |
| GET | `/admin/blog/tags/{tag}` | admin.blog.tags.show | View tag |
| GET | `/admin/blog/tags/{tag}/edit` | admin.blog.tags.edit | Edit form |
| PUT/PATCH | `/admin/blog/tags/{tag}` | admin.blog.tags.update | Update tag |
| DELETE | `/admin/blog/tags/{tag}` | admin.blog.tags.destroy | Delete tag |
| POST | `/admin/blog/tags/sync-usage` | admin.blog.tags.sync-usage | Sync usage counts |
| DELETE | `/admin/blog/tags/delete-unused` | admin.blog.tags.delete-unused | Delete unused tags |

### Features

#### Index
- Shows all tags with post count
- Ordered by usage count (most used first)

#### Create/Store
**Validation:**
- `name` - Required, max 255 chars
- `slug` - Optional (auto-generated), unique
- `description` - Optional
- `meta_title` - Optional, max 60 chars
- `meta_description` - Optional, max 160 chars
- `color` - Optional hex color (default: #10B981)
- `is_active` - Boolean

#### Destroy
- Checks if tag has posts (prevents deletion)

#### Sync Usage Counts
- Recalculates `usage_count` for all tags
- Counts published posts only

#### Delete Unused
- Deletes all tags with `usage_count = 0`
- Returns count of deleted tags

---

## BlogCommentController

### Purpose
Moderate blog comments with spam detection and bulk actions.

### Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/blog/comments` | admin.blog.comments.index | List comments |
| GET | `/admin/blog/comments/{comment}` | admin.blog.comments.show | View comment |
| POST | `/admin/blog/comments/{comment}/approve` | admin.blog.comments.approve | Approve comment |
| POST | `/admin/blog/comments/{comment}/spam` | admin.blog.comments.spam | Mark as spam |
| POST | `/admin/blog/comments/{comment}/trash` | admin.blog.comments.trash | Move to trash |
| POST | `/admin/blog/comments/{comment}/restore` | admin.blog.comments.restore | Restore from trash |
| DELETE | `/admin/blog/comments/{comment}` | admin.blog.comments.destroy | Permanently delete |
| POST | `/admin/blog/comments/{comment}/unflag` | admin.blog.comments.unflag | Remove flag |
| POST | `/admin/blog/comments/bulk-action` | admin.blog.comments.bulk-action | Bulk actions |
| DELETE | `/admin/blog/comments/empty-trash` | admin.blog.comments.empty-trash | Empty trash |
| DELETE | `/admin/blog/comments/delete-spam` | admin.blog.comments.delete-spam | Delete all spam |

### Features

#### Index
**Query Parameters:**
- `status` - Filter by status (all, pending, approved, spam, trash)
- `flagged` - Show only flagged comments
- `post` - Filter by post ID
- `search` - Search in content, name, email
- `sort` - Sort column
- `order` - Sort order

**Returns:**
- Paginated comments (20 per page)
- Posts for filter dropdown
- Status counts (pending, approved, spam, trash, flagged)

#### Moderation Actions
- **Approve** - Sets status to 'approved', sets `approved_at`
- **Spam** - Sets status to 'spam'
- **Trash** - Sets status to 'trash'
- **Restore** - Sets status to 'pending'
- **Destroy** - Permanently deletes (force delete)
- **Unflag** - Removes flag

#### Bulk Actions
**Actions:**
- `approve` - Approve selected comments
- `spam` - Mark as spam
- `trash` - Move to trash
- `delete` - Permanently delete

**Validation:**
- `action` - Required
- `comments` - Required array of comment IDs

#### Empty Trash
- Permanently deletes all comments with status 'trash'
- Returns count of deleted comments

#### Delete Spam
- Permanently deletes all comments with status 'spam'
- Returns count of deleted comments

---

## Usage Examples

### Create a Blog Post
```php
// In your admin panel form
<form action="{{ route('admin.blog.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <input type="text" name="title" required>
    <textarea name="content" required></textarea>
    
    <!-- Categories -->
    <select name="categories[]" multiple required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    
    <!-- Primary Category -->
    <select name="primary_category" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    
    <!-- Tags -->
    <select name="tags[]" multiple>
        @foreach($tags as $tag)
            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
        @endforeach
    </select>
    
    <!-- Status -->
    <select name="status" required>
        <option value="draft">Draft</option>
        <option value="published">Published</option>
        <option value="scheduled">Scheduled</option>
    </select>
    
    <button type="submit">Create Post</button>
</form>
```

### Moderate Comments
```blade
<!-- Approve comment -->
<form action="{{ route('admin.blog.comments.approve', $comment) }}" method="POST">
    @csrf
    <button>Approve</button>
</form>

<!-- Mark as spam -->
<form action="{{ route('admin.blog.comments.spam', $comment) }}" method="POST">
    @csrf
    <button>Spam</button>
</form>

<!-- Move to trash -->
<form action="{{ route('admin.blog.comments.trash', $comment) }}" method="POST">
    @csrf
    <button>Trash</button>
</form>
```

### Bulk Actions
```javascript
// JavaScript for bulk actions
const selectedPosts = [1, 2, 3]; // Post IDs

fetch('/admin/blog/posts/bulk-action', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
        action: 'publish',
        posts: selectedPosts
    })
});
```

---

## Security Features

### Authorization
All routes require:
- `auth` middleware - User must be logged in
- `verified` middleware - Email must be verified

### Validation
- All inputs validated
- File uploads limited to 2MB
- Image types validated
- Unique slug constraints

### File Management
- Images stored in `storage/app/public/blog/`
- Old images deleted when updated
- Images deleted when post deleted

### XSS Protection
- All user input escaped in views
- Content sanitized before storage

---

## Next Steps

1. **Create Admin Views** for the controllers
2. **Add Rich Text Editor** (TinyMCE, CKEditor)
3. **Add Image Manager** for uploads
4. **Create Dashboard** with statistics
5. **Add Permissions** (roles: admin, editor, author)
6. **Email Notifications** for comment moderation

See the main documentation for more details!
