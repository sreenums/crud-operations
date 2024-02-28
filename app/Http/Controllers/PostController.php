<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {   
        $posts = Post::getPostsListWithComments();
        return view('posts.posts-home', compact('posts'));
    }

    public function create()
    {
        $users = User::getUsersList();
        return view('posts.add', compact('users'));
    }

    public function store(Request $request)
    {
        $imageName = Null;
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|max:255',
            'content' => 'required|string|max:1000',
            'datePublished' => 'required',
        ]);
        
        $title = Request('title');
        $userId = Request('author');
        $content = Request('content');
        $datePublished = Request('datePublished');
        $active = Request('checkActive');
        if(Request('image')){
            $imageName = uniqid().'.'. Request('image')->extension();
            $request->image->move(public_path('images'),$imageName);
        }

        $postRecord = new Post();
        $postRecord->title = $title;
        $postRecord->user_id = $userId;
        $postRecord->content = $content;
        if($imageName){  
            $postRecord->image = $imageName;  
        }
        $postRecord->date_published = $datePublished;
        $postRecord->is_active = $active;
        $postRecord->save();

        return back()->withSuccess('Post added successfully!');
    }

    public function show(Post $post)
    {
        $user = User::find($post->user_id);
        $postStatusText = $post->is_active ? '<font color="green">Active</font>' : '<font color="red">Inactive</font>';
        return view('posts.post-view', compact('post','user','postStatusText'));
    }

    public function edit(Post $post)
    {
        $users = User::getUsersList();
        return view('posts.post-edit', compact('post','users'));
    }

    public function update(Request $request, Post $post)
    {
        
        $imageName = NULL;
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|max:255',
            'content' => 'required|string|max:1000',
            'datePublished' => 'required',
            'author' => 'required',
        ]);

        $title = Request('title');
        $userId = Request('author');
        $content = Request('content');
        $datePublished = Request('datePublished');
        $active = Request('checkActive');
        if(Request('image')){
            $imageName = uniqid().'.'. Request('image')->extension();
            $request->image->move(public_path('images'),$imageName);
        }

        $post->title = $title;
        $post->user_id = $userId;
        $post->content = $content;
        $post->date_published = $datePublished;
        $post->is_active = $active;
        if($imageName){
            $post->image = $imageName;
        }

        $post->save();
        return back()->withSuccess('Post updated successfully!');

    }

    public function destroy(Post $post)
    {
        Comment::where('post_id',$post->id)->delete();
        $post->delete();
        return response()->json(['success'=>'Post Deleted Successfully!']);
    }

    public function addComment(Request $request, $postId)
    {
        $comment = $request->comment;
        
        $comentRecord = new Comment();
        $comentRecord->post_id = $postId;
        $comentRecord->comment = $comment;

        $comentRecord->save();
        return back()->withSuccess('Comment added successfully!');
    }
}
