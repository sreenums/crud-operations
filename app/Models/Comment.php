<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'comment',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Delete a post comment by post ID.
     *
     * @param $postId Id corresponds to the post
     */
    public static function deletePostComment($postId)
    {
        return static::where('post_id',$postId)->delete();
    }
    
}
