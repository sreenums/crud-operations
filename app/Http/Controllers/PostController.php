<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    //Show the form for creating a new post.
    public function create()
    {
        $users = User::getList();

        return view('posts.add', compact('users'));
    }

    //Store a newly created post in storage.
    public function store(StorePostRequest $request)
    {
        // Use PostService to create a new post
        $this->postService->createPost($request);

        return back()->withSuccess('Post added successfully!');
    }

    //Display the specified post
    public function show(Post $post)
    {
        $user = User::find($post->user_id);
        $postStatusText = $post->is_active ? '<font color="green">Active</font>' : '<font color="red">Inactive</font>';

        return view('posts.post-view', compact('post','user','postStatusText'));
    }

    //Show the form for editing the specified post.
    public function edit(Post $post)
    {
        $users = User::getList();

        return view('posts.post-edit', compact('post','users'));
    }

    //Update the specified post in storage.
    public function update(EditPostRequest $request, Post $post)
    {
        // PostService to update the post
        $this->postService->updatePost( $request, $post);

        return back()->withSuccess('Post updated successfully!');
    }

    //Remove the specified post from storage
    public function destroy(Post $post)
    {
        Comment::where('post_id',$post->id)->delete();
        $this->postService->deletePost($post);

        return response()->json(['success'=>'Post Deleted Successfully!']);
    }

}
