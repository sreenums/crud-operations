<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\PostRepository;
use App\Repositories\CategoryRepository;


class PostService
{
    protected $postRepository;
    protected $categoryRepository;
    
    public function __construct(PostRepository $postRepository,CategoryRepository $categoryRepository)
    {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
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

        // Save categories
        $this->categoryRepository->createPostCategory($post, $request->input('categories', []));

    }

    /**
     * Generate the post data
     * 
     * @param $request Form data
     */
    public function getPostCreateData($request)
    {   
        
        $imageName = $this->imageUpload($request);

        return [
            'title' => $request->title,
            'user_id' => $request->author,
            'content' => $request->content,
            'date_published' => $request->datePublished,
            'is_active' => $request->checkActive,
            'image' => $imageName
        ];

    }

    /**
     * Uploads an image from the request to the server.
     *
     * @param datatype $request description
     * @return string
     */
    public function imageUpload($request)
    {
        if ($request->hasFile('image')) {
            $imageName = uniqid().'.'. $request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $imageName);
            return $imageName;
        }
    }

    /**
     * Update post data
     *
     * @param $request from form data
     * @param $post Post object
     */
    public function updatePost($request, $post)
    {
        // Update post data
        $postData = [
            'title' => $request->title,
            'user_id' => $request->author,
            'content' => $request->content,
            'date_published' => $request->datePublished,
            'is_active' => $request->checkActive,
        ];

        // image upload
        $imageName = $this->imageUpload($request);
        if (isset($imageName)) {
            $postData['image'] = $imageName;
        }

        $post = $this->postRepository->updatePost($post, $postData);

        // Get the list of categories from the request
        $selectedCategoryIds = $request->input('categories', []);

        // Save or update categories
        $this->categoryRepository->saveOrUpdateCategories($post, $selectedCategoryIds);

        return $post;

    }

    /**
     * Delete a post.
     *
     * @param $post The post to be deleted
     */   
    public function deletePost($post)
    {
        return $this->postRepository->deletePost($post);
    }

    /**
     * Get the list of posts with the count of comments for each post.
     *
     * @param mixed $request 
     * @return mixed
     */
    public function getPostsListWithCommentsCount()
    {
        return $this->postRepository->getPostsListWithCommentsCount();
    }

    /**
     * Filter and return the posts based on the given request parameters.
     *
     * @param Request $request The request object containing search, author, status, commentsCount, and order parameters
     * @param Posts $posts The collection of posts to be filtered
     * @return Posts The filtered collection of posts
     */
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

    public function deletePostCategory($postId)
    {
        return $this->categoryRepository->deletePostCategory($postId);
    }

    public function loadWithUserAndCategory($post)
    {
        return $this->postRepository->loadWithUserAndCategory($post);
    }   

}