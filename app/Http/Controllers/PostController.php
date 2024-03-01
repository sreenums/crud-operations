<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    //Display a listing of the posts.
    public function index()
    {   
        $posts = Post::getPostsListWithCommentsCount();
        //$userNames = $posts->pluck('user.name','user.id')->unique();
        $userNames = User::select('id', 'name')
            ->whereIn('id', function ($query) {
                $query->select('user_id')
                    ->from('posts');
                })
            ->distinct()
            ->get();
        return view('posts.posts-home', compact('posts','userNames'));
    
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
        $imageName = Null;
        $validated = $request->validated();
        
        if($request->hasFile('image')){
            $imageName = uniqid().'.'. Request('image')->extension();
            $request->image->move(public_path('images'),$imageName);
        }

        $postRecord = new Post([
            'title' => $validated['title'],
            'user_id' => $validated['author'],
            'content' => $validated['content'],
            'date_published' => $validated['datePublished'],
            'is_active' => Request('checkActive'),
            'image' => $imageName
        ]);

        $postRecord->save();

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
        
        $imageName = NULL;
        $validated = $request->validated();

        if($request->hasFile('image')){
            $imageName = uniqid().'.'. Request('image')->extension();
            $request->image->move(public_path('images'),$imageName);
        }

        $post->fill([
            'title' => $validated['title'],
            'user_id' => $validated['author'],
            'content' => $validated['content'],
            'date_published' => $validated['datePublished'],
            'is_active' => Request('checkActive'),
            'image' => $imageName
        ])->save();

        return back()->withSuccess('Post updated successfully!');
    }

    //Remove the specified post from storage
    public function destroy(Post $post)
    {
        Comment::where('post_id',$post->id)->delete();
        $post->delete();
        return response()->json(['success'=>'Post Deleted Successfully!']);
    }

}
