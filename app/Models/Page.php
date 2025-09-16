<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::creating(function (Page $page) {
            if (blank($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::updating(function (Page $page) {
            if ($page->isDirty('title')) {
                $page->slug = Str::slug($page->title);
            }
        });
    }
}
