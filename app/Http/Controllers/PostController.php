<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Show the form for creating a new post.
     * 
     */
    public function create()
    {
        $users = User::getList();
        $categories = Category::getCategoryList();
        return view('posts.add', compact('users','categories'));
    }

    /**
     * Store a newly created post in storage.
     * 
     * @param $request form request data
     */
    public function store(StorePostRequest $request)
    {     
        // Use PostService to create a new post
        $this->postService->createPost($request);

        return back()->withSuccess('Post added successfully!');
    }

    /**
     * Display the specified post
     * 
     * @param $post - Post object
     */
    public function show(Post $post)
    {
        $post = $this->postService->loadWithUserAndCategory($post);
        $postStatusText = $post->is_active ? '<font color="green">Active</font>' : '<font color="red">Inactive</font>';
        
        return view('posts.post-view', compact('post','postStatusText'));
    }

    /**
     * Show the form for editing the specified post.
     * 
     * @param $post - Post object
     */
    public function edit(Post $post)
    {
        
        $post = $this->postService->loadWithUserAndCategory($post);
        $selectedCategoryIds = $post->categories->pluck('category_id');

        // Get list of authors and categories
        $users = User::getList();
        $categories = Category::getCategoryList();

        return view('posts.post-edit', compact('post','users','categories','selectedCategoryIds'));
    }

    /**
     * Update the specified post in storage.
     * 
     * @param $request form request data
     * @param $post - Post object
     */
    public function update(EditPostRequest $request, Post $post)
    {
        // PostService to update the post
        $this->postService->updatePost( $request, $post);

        return back()->withSuccess('Post updated successfully!');
    }

    /**
     * Remove the specified post from storage
     * 
     * @param $post - Post object
     */
    public function destroy(Post $post)
    {
        $this->postService->deletePost($post);

        return response()->json(['success'=>'Post Deleted Successfully!']);
    }

}
