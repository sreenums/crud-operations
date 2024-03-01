<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    //Add Comment for a post
    public function addComment(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required',
        ]);
        
        $comentRecord = new Comment([
            'post_id' => $postId,
            'comment' => $request->comment,
        ]);

        $comentRecord->save();
        
        return back()->withSuccess('Comment added successfully!');
    }

    public function showComments($postId)
    {
        $comments = Comment::where('post_id',$postId)->get();
        return response()->json(['comments' => $comments]);
    }
}
