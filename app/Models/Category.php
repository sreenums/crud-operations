<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'category_master_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function categoryMaster()
    {
        return $this->belongsTo(CategoryMaster::class, 'category_master_id');
    }

    public static function deletePostCategory($postId)
    {
        return static::where('post_id',$postId)->delete();
    }

    public static function createPostCategory($post)
    {
       // return static::where('post_id',$post)->delete();
    }

}
