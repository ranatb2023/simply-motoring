<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;

class FixBlogImageAlt extends Command
{
    protected $signature = 'blog:fix-image-alt';

    protected $description = 'Set alt text on specific blog content images (by filename)';

    /**
     * Map of image filename => desired alt text.
     */
    private array $map = [
        'HI1kklPS7UhXOujWNf2FKEWmn9wsBa8UmdlWUekF.png' => 'Automotive service and vehicle maintenance in Doncaster',
        'TUE7ZpdPONGBiFjrvJN9kw49lbMhA1wVEUB1BHRw.png' => 'Professional car servicing and automotive repairs',
        'kQuQbwmPTXvm5W3126Sbul2yrGHgDFJvx5KMovRw.png' => 'Car maintenance and vehicle servicing',
    ];

    public function handle(): int
    {
        $updated = 0;

        foreach (BlogPost::all() as $post) {
            $content = (string) $post->content;
            if ($content === '') {
                continue;
            }

            $original = $content;

            foreach ($this->map as $file => $alt) {
                if (! str_contains($content, $file)) {
                    continue;
                }

                // Update every <img> tag that references this file: replace an
                // existing alt="..." or insert one if it has none.
                $content = preg_replace_callback('/<img\b[^>]*>/i', function ($m) use ($file, $alt) {
                    $tag = $m[0];
                    if (! str_contains($tag, $file)) {
                        return $tag;
                    }

                    $altAttr = 'alt="' . htmlspecialchars($alt, ENT_QUOTES) . '"';

                    if (preg_match('/\balt\s*=\s*"[^"]*"/i', $tag)) {
                        return preg_replace('/\balt\s*=\s*"[^"]*"/i', $altAttr, $tag, 1);
                    }

                    return preg_replace('/<img\b/i', '<img ' . $altAttr, $tag, 1);
                }, $content);
            }

            if ($content !== $original) {
                $post->content = $content;
                $post->save();
                $updated++;
                $this->line("  Updated post #{$post->id}: {$post->title}");
            }
        }

        $this->info("Done. Posts updated: {$updated}.");

        return self::SUCCESS;
    }
}
