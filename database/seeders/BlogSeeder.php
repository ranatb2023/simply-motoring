<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            [
                'name' => 'Maintenance',
                'slug' => 'maintenance',
                'description' => 'Car maintenance tips and guides',
                'meta_title' => 'Car Maintenance Tips | Simply Motoring',
                'meta_description' => 'Expert car maintenance tips and guides to keep your vehicle running smoothly',
                'color' => '#3B82F6',

                'is_active' => true,
            ],
            [
                'name' => 'Brake Services',
                'slug' => 'brake-services',
                'description' => 'Everything about brake services and maintenance',
                'meta_title' => 'Brake Services | Simply Motoring',
                'meta_description' => 'Complete guide to brake services, repairs, and maintenance',
                'color' => '#EF4444',

                'is_active' => true,
            ],
            [
                'name' => 'DIY Guides',
                'slug' => 'diy-guides',
                'description' => 'Do-it-yourself car maintenance guides',
                'meta_title' => 'DIY Car Guides | Simply Motoring',
                'meta_description' => 'Step-by-step DIY guides for car maintenance and repairs',
                'color' => '#10B981',

                'is_active' => true,
            ],
            [
                'name' => 'Video Tutorials',
                'slug' => 'video-tutorials',
                'description' => 'Video guides for car maintenance',
                'meta_title' => 'Video Tutorials | Simply Motoring',
                'meta_description' => 'Watch our video tutorials for car maintenance and repairs',
                'color' => '#F59E0B',

                'is_active' => true,
            ],
            [
                'name' => 'News & Updates',
                'slug' => 'news-updates',
                'description' => 'Latest automotive news and updates',
                'meta_title' => 'Automotive News | Simply Motoring',
                'meta_description' => 'Stay updated with the latest automotive news and industry updates',
                'color' => '#8B5CF6',

                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            BlogCategory::create($categoryData);
        }

        // Create tags
        $tags = [
            ['name' => 'Brake Pads', 'slug' => 'brake-pads', 'color' => '#EF4444'],
            ['name' => 'Oil Change', 'slug' => 'oil-change', 'color' => '#F59E0B'],
            ['name' => 'Safety', 'slug' => 'safety', 'color' => '#10B981'],
            ['name' => 'Tutorial', 'slug' => 'tutorial', 'color' => '#3B82F6'],
            ['name' => 'Beginner Friendly', 'slug' => 'beginner-friendly', 'color' => '#8B5CF6'],
            ['name' => 'Advanced', 'slug' => 'advanced', 'color' => '#EC4899'],
            ['name' => 'Quick Tips', 'slug' => 'quick-tips', 'color' => '#14B8A6'],
            ['name' => 'Seasonal', 'slug' => 'seasonal', 'color' => '#F97316'],
        ];

        foreach ($tags as $tagData) {
            BlogTag::create($tagData);
        }

        // Get first user (or create one)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@simplyMotoring.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Create sample blog posts
        $posts = [
            [
                'title' => 'Complete Guide to Brake Maintenance',
                'excerpt' => 'Learn everything you need to know about maintaining your vehicle\'s brake system for optimal safety and performance.',
                'content' => '<p>Your brake system is one of the most critical safety components of your vehicle. Regular maintenance ensures your brakes work properly when you need them most.</p><h2>Why Brake Maintenance Matters</h2><p>Properly maintained brakes can mean the difference between a close call and a serious accident. Regular inspections and timely replacements keep you and your passengers safe.</p><h2>Signs Your Brakes Need Attention</h2><ul><li>Squealing or grinding noises</li><li>Vibration when braking</li><li>Longer stopping distances</li><li>Brake warning light on dashboard</li></ul><h2>Recommended Maintenance Schedule</h2><p>We recommend having your brakes inspected every 12,000 miles or once a year, whichever comes first.</p>',
                'featured_image' => '/images/blog/brake-maintenance.jpg',
                'featured_image_alt' => 'Mechanic inspecting brake pads',
                'meta_title' => 'Complete Brake Maintenance Guide 2026 | Simply Motoring',
                'meta_description' => 'Expert guide to brake maintenance. Learn when to replace brake pads, how to inspect your brakes, and keep your vehicle safe.',
                'focus_keyword' => 'brake maintenance',
                'categories' => [1, 2], // Maintenance, Brake Services
                'primary_category' => 2, // Brake Services
                'tags' => [1, 3, 4], // Brake Pads, Safety, Tutorial
                'is_featured' => true,
            ],
            [
                'title' => 'How to Change Your Oil in 10 Minutes',
                'excerpt' => 'Quick and easy guide to changing your vehicle\'s oil at home. Save money and learn a valuable skill.',
                'content' => '<p>Changing your oil is one of the easiest maintenance tasks you can do yourself. With the right tools and this guide, you\'ll be done in 10 minutes.</p><h2>What You\'ll Need</h2><ul><li>New oil filter</li><li>Correct grade of oil</li><li>Oil drain pan</li><li>Wrench set</li><li>Funnel</li></ul><h2>Step-by-Step Instructions</h2><p>Follow these simple steps to change your oil like a pro...</p>',
                'featured_image' => '/images/blog/oil-change.jpg',
                'featured_image_alt' => 'DIY oil change process',
                'meta_title' => 'How to Change Your Oil in 10 Minutes | DIY Guide',
                'meta_description' => 'Learn how to change your car\'s oil quickly and easily with our step-by-step guide. Save money on maintenance.',
                'focus_keyword' => 'oil change',
                'categories' => [1, 3], // Maintenance, DIY Guides
                'primary_category' => 3, // DIY Guides
                'tags' => [2, 4, 5, 7], // Oil Change, Tutorial, Beginner Friendly, Quick Tips
                'is_featured' => true,
            ],
            [
                'title' => 'Winter Car Preparation Checklist',
                'excerpt' => 'Get your vehicle ready for winter with our comprehensive checklist. Stay safe in cold weather conditions.',
                'content' => '<p>Winter driving presents unique challenges. Prepare your vehicle with this essential checklist to stay safe all season long.</p><h2>Essential Winter Preparations</h2><ul><li>Check battery health</li><li>Inspect tire tread and pressure</li><li>Test antifreeze levels</li><li>Replace wiper blades</li><li>Check heating system</li></ul>',
                'featured_image' => '/images/blog/winter-prep.jpg',
                'featured_image_alt' => 'Car prepared for winter driving',
                'meta_title' => 'Winter Car Preparation Checklist | Simply Motoring',
                'meta_description' => 'Complete winter car preparation checklist. Learn how to winterize your vehicle for safe cold weather driving.',
                'focus_keyword' => 'winter car preparation',
                'categories' => [1, 5], // Maintenance, News & Updates
                'primary_category' => 1, // Maintenance
                'tags' => [3, 7, 8], // Safety, Quick Tips, Seasonal
                'is_featured' => false,
            ],
        ];

        foreach ($posts as $postData) {
            $categories = $postData['categories'];
            $primaryCategory = $postData['primary_category'];
            $tags = $postData['tags'];

            unset($postData['categories'], $postData['primary_category'], $postData['tags']);

            $post = BlogPost::create(array_merge($postData, [
                'author_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays(rand(1, 30)),
                'allow_comments' => true,
                'is_indexed' => true,
                'is_followed' => true,
            ]));

            // Attach categories
            $post->attachCategories($categories, $primaryCategory);

            // Attach tags
            $post->tags()->attach($tags);

            // Generate and save schema data
            $post->schema_data = $post->generateSchemaData();
            $post->save();

            // Update tag usage counts
            $post->tags->each->syncUsageCount();
        }

        $this->command->info('Blog seeder completed successfully!');
        $this->command->info('Created: ' . count($categories) . ' categories');
        $this->command->info('Created: ' . count($tags) . ' tags');
        $this->command->info('Created: ' . count($posts) . ' blog posts');
    }
}
