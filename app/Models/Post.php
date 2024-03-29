<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'content',
        'image',
        'date_published',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getPostsListWithCommentsCount()
    {
        return static::with(['user'])->withCount('comments')->latest();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function category()
    {
        return $this->hasMany(CategoryPost::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_posts');
    }

    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    protected function getPublishedAtFormattedAttribute()
    {
        $publishedAt = $this->attributes['date_published'];
        return Carbon::parse($publishedAt)->format('d-m-Y');
    }
}
