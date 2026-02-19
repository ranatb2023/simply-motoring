# Blog System Testing Guide

## Running the Seeder

To populate your blog with sample data for testing:

```bash
# Run the blog seeder
php artisan db:seed --class=BlogSeeder
```

This will create:
- ✅ 5 categories (Maintenance, Brake Services, DIY Guides, Video Tutorials, News & Updates)
- ✅ 8 tags (Brake Pads, Oil Change, Safety, Tutorial, etc.)
- ✅ 3 sample blog posts with full SEO data

---

## Testing in Tinker

Open Laravel Tinker to test your models:

```bash
php artisan tinker
```

### Test Queries

```php
// Get all published posts
$posts = App\Models\BlogPost::published()->get();

// Get a post with relationships
$post = App\Models\BlogPost::with(['categories', 'tags', 'author'])->first();

// Display post details
echo $post->title;
echo $post->meta_title;
echo $post->url;
echo $post->reading_time . ' min read';

// Get primary category
$primary = $post->primaryCategory();
echo $primary->name;

// Get all categories
foreach($post->categories as $category) {
    echo $category->name . ' (' . $category->color . ')';
}

// Get all tags
foreach($post->tags as $tag) {
    echo '#' . $tag->name;
}

// Search posts
$results = App\Models\BlogPost::published()->search('brake')->get();

// Get posts in category
$posts = App\Models\BlogPost::published()->inCategory('maintenance')->get();

// Get posts with tag
$posts = App\Models\BlogPost::published()->withTag('safety')->get();

// Get popular tags
$tags = App\Models\BlogTag::popular(5)->get();

// Get category with post count
$categories = App\Models\BlogCategory::withCount('posts')->get();
foreach($categories as $cat) {
    echo "{$cat->name}: {$cat->posts_count} posts";
}
```

---

## Creating Test Data Manually

### Create a Category
```php
$category = App\Models\BlogCategory::create([
    'name' => 'Test Category',
    'description' => 'This is a test category',
    'color' => '#3B82F6',
    'is_active' => true,
]);
```

### Create a Tag
```php
$tag = App\Models\BlogTag::create([
    'name' => 'Test Tag',
    'color' => '#10B981',
]);
```

### Create a Post
```php
$post = App\Models\BlogPost::create([
    'author_id' => 1,
    'title' => 'Test Blog Post',
    'content' => 'This is test content...',
    'status' => 'published',
    'published_at' => now(),
]);

// Attach categories
$post->attachCategories([1, 2], 1);

// Attach tags
$post->tags()->attach([1, 2, 3]);
```

---

## Verifying Database

Check if tables were created:

```bash
# Connect to your database and run:
php artisan db:show

# Or check specific tables:
php artisan db:table blog_posts
php artisan db:table blog_categories
php artisan db:table blog_tags
php artisan db:table blog_comments
```

---

## Common Issues & Solutions

### Issue: Seeder fails with "User not found"
**Solution:** Create a user first:
```bash
php artisan tinker
```
```php
App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
]);
```

### Issue: Foreign key constraint error
**Solution:** Make sure migrations ran in correct order:
```bash
php artisan migrate:fresh
php artisan migrate
```

### Issue: Slug already exists
**Solution:** Clear existing data:
```bash
php artisan migrate:fresh --seed
```

---

## Next Steps After Testing

1. **Create Controllers**
   ```bash
   php artisan make:controller BlogController
   php artisan make:controller Admin/BlogAdminController --resource
   ```

2. **Create Routes**
   - Add routes to `routes/web.php`

3. **Create Views**
   - `resources/views/blog/index.blade.php`
   - `resources/views/blog/show.blade.php`
   - `resources/views/blog/category.blade.php`
   - `resources/views/blog/tag.blade.php`

4. **Test SEO**
   - View page source
   - Check meta tags
   - Validate Schema.org markup at schema.org/validator

---

## Sample Routes to Add

```php
// Public blog routes
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{category}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/{category}/{post}', [BlogController::class, 'show'])->name('show');
    Route::get('/{post}', [BlogController::class, 'post'])->name('post');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('blog', BlogAdminController::class);
    Route::resource('blog-categories', BlogCategoryController::class);
    Route::resource('blog-tags', BlogTagController::class);
});
```

---

## Useful Artisan Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Generate sitemap (after implementing)
php artisan blog:generate-sitemap

# Sync tag usage counts
php artisan blog:sync-tag-counts
```
