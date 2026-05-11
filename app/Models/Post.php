<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'is_published',
        'published_at',
        'slug',
        'views',
        'featured_image',
        'excerpt',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    //create slug automatically from title
    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title) . '-' . Str::random(6);
            }
        });
    }
}
