<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Support\ImageVariants;


class Post extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'thumbnail',
        'status',
        'published_at',
        'is_featured',
        'is_pinned',
        'excerpt',
        'content',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    // relationships
    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }

    // scopes
    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    // hooks
    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (blank($post->user_id) && auth()->check()) {
                $post->user_id = auth()->id();
            }
            if (blank($post->slug) && filled($post->title)) {
                $post->slug = Str::slug($post->title);
            }
            if ($post->status === 'published' && blank($post->published_at)) {
                $post->published_at = now();
            }
        });


        static::updating(function (Post $post) {
            if ($post->isDirty('title')) {
                $post->slug = Str::slug($post->title);
            }
            if ($post->status === 'published' && blank($post->published_at)) {
                $post->published_at = now();
            }
        });

        static::saved(function (Post $post): void {
            $col = 'thumbnail'; // kamu sudah memakai kolom ini
            $path = $post->{$col} ?? null;

            if (!$path)
                return;
            if (!Storage::disk('public')->exists($path))
                return;

            try {
                // generate 3 ukuran + webp
                ImageVariants::generate($path, 'public', [
                    'thumb' => 1280,
                    'small' => 200,
                    'middle' => 400,
                ], 82);

                \Log::info("Image variants generated for: {$path}");
            } catch (\Throwable $e) {
                \Log::warning("Variants failed: " . $e->getMessage());
            }
        });
    }
    public function getPermalinkAttribute(): ?string
    {
        if (blank($this->published_at)) {
            return null;
        }

        return route('posts.show', [
            'bulan' => $this->published_at->format('m'),
            'tahun' => $this->published_at->format('Y'),
            'slug' => $this->slug,
        ]);
    }

    public function getUrlAttribute(): string
    {
        $bulan = optional($this->published_at)->format('m'); // "01".."12"
        $tahun = optional($this->published_at)->format('Y'); // "2025"

        return route('posts.show', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'slug' => $this->slug,
        ]);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }
    public function getThumbUrlAttribute(): string
    {
        return $this->thumbnail
            ? Storage::disk('public')->exists($this->thumbnail)
            : asset('images/placeholder-640x360.jpg'); // siapkan file placeholder ini
    }
    public function views()
    {
        return $this->hasMany(PostView::class);
    }
}
