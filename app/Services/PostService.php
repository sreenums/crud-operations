<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\PostRepository;

class PostService
{
    protected $postRepository;
    
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Create new post
     * 
     * @param $request Form Request
     */
    public function createPost($request)
    {   
        // Create post data
        $postData = $this->getPostCreateData($request);
        
        $post = $this->postRepository->createPost($postData);

        //Save categories
        foreach ($request->input('categories', []) as $categoryId) {
            Category::create([
                'post_id' => $post->id,
                'category_master_id' => $categoryId
            ]);
        }

    }

    /**
     * Generate the post data
     * 
     * @param $request Form data
     */
    public function getPostCreateData($request)
    {   
        
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = uniqid().'.'. $request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $imageName);
        }

        return [
            'title' => $request->title,
            'user_id' => $request->author,
            'content' => $request->content,
            'date_published' => $request->datePublished,
            'is_active' => $request->checkActive,
            'image' => $imageName
        ];

    }

    public function updatePost($request, $post)
    {
        // image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = uniqid().'.'. $request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $imageName);
        }
        
        // Update post data
        $postData = [
            'title' => $request->title,
            'user_id' => $request->author,
            'content' => $request->content,
            'date_published' => $request->datePublished,
            'is_active' => $request->checkActive,
        ];

        if (isset($imageName)) {
            $postData['image'] = $imageName;
        }

        $post = $this->postRepository->updatePost($post, $postData);

        // Get the list of categories from the request
        $newCategoryIds = $request->input('categories', []);

        // Delete categories that are not in the new list
        Category::where('post_id', $post->id)
            ->whereNotIn('category_master_id', $newCategoryIds)
            ->delete();

        // Save or update categories
        foreach ($newCategoryIds as $categoryId) {
            Category::updateOrCreate(
                [
                    'post_id' => $post->id,
                    'category_master_id' => $categoryId
                ],
                [
                    'post_id' => $post->id,
                    'category_master_id' => $categoryId
                ]
            );
        }

        return $post;

    }

    public function deletePost($post)
    {
        return $this->postRepository->deletePost($post);
    }

    public function getPostsListWithCommentsCount($request)
    {
        $posts = $this->postRepository->getPostsListWithCommentsCount();

        return $posts;
    }

    public function FilterPost($request, $posts)
    {

        if ($request->has('search')) {
            $searchTerm = $request->search['value'];
            if(isset($searchTerm)){
                $posts->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', "%$searchTerm%")
                        ->orWhere('content', 'like', "%$searchTerm%");
                });
            }
        }

        if ($request->has('author') && $request->author != 'all') {
            $posts->where('user_id', $request->author);
        }

        //Filtering based on status
        if ($request->has('status')) {
            if(request('status') == 1){ 
                $posts->where('is_active', 1); 
            }
            elseif(request('status') == 0){ 
                $posts->where('is_active', 0); 
            }
        }

        //Filtering based on comments count
        if ($request->has('commentsCount') && isset($request->commentsCount)) {
            $posts->having('comments_count', '=', $request->commentsCount);
        }


        // Sorting based on ID column
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderDirection = $request->order[0]['dir'];
            $orderColumnName = $request->columns[$orderColumnIndex]['data'];

            if ($orderColumnName === 'id') {
                $posts->orderBy('id', $orderDirection);
            }
        }

        return $posts;
    }

    /**
     * Format data for data table
     */
    public function formatDataTable($posts)
    {
        return $posts->map(function($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'user_id' => $post->user->name,
                'date_published' => $post->published_at_formatted,
                'comments_count' => $post->comments_count,
                'is_active' => $post->status_text,
            ];
        });
    }

}