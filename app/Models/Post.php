<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getPostsListWithComments()
    {
        return static::latest()->with(['user'])->with('comments')->get();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    protected function getStatusTextAttribute()
    {
        return $this->is_active ? '<font color="green">Active</font>' : '<font color="red">Inactive</font>';
    }

    protected function getPublishedAtFormattedAttribute()
    {
        $publishedAt = $this->attributes['date_published'];
        return Carbon::parse($publishedAt)->format('d-m-Y');
    }
}
