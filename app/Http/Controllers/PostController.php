<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {   
        $posts = Post::latest()->with(['user'])->get();
        return view('posts.posts-home', compact('posts'));
    }

    public function create()
    {
        $users = User::orderBy('name')->where('id', '<>', 1)->get(); //To avoid admin user
        return view('posts.add', compact('users'));
    }

    public function store(Request $request)
    {
        $imageName = Null;
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|max:255',
            'content' => 'required|string|max:255',
            'datePublished' => 'required',
        ]);

        $title = Request('title');
        $userId = Request('author');
        $content = Request('content');
        if(Request('image')){
        $imageName = time().'.'. Request('image')->extension();
        $request->image->move(public_path('images'),$imageName);
        }
        $datePublished = Request('datePublished');
        $active = Request('checkActive');

        $postRecord = new Post();
        $postRecord->title = $title;
        $postRecord->user_id = $userId;
        $postRecord->content = $content;
        if($imageName){  $postRecord->image = $imageName;  }
        $postRecord->date_published = $datePublished;
        $postRecord->is_active = $active;
        $postRecord->save();

        return back()->withSuccess('Post added successfully!');
    }

    public function show(Post $post)
    {
        //
    }

    public function edit(Post $post)
    {
        //
    }

    public function update(Request $request, Post $post)
    {
        //
    }

    public function destroy(Post $post)
    {
        //
    }
}
