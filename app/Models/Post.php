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
        return static::latest()->with(['user'])->withCount('comments')->paginate(2);
        // $posts = static::latest()->with(['user'])->withCount('comments')->paginate(3);
        // $userNames = $posts->pluck('user.name')->unique();

        // return [
        //     'posts' => $posts,
        //     'userNames' => $userNames,
        // ];
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
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
