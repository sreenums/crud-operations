<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    protected $model;

    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    /**
     * For creating a new post
     * 
     * @param $data - form post data
     */
    public function createPost($data)
    {
        return $this->model->create($data);
    }

    /**
     * Update form post datas
     * 
     * @param $post - Post object
     * @param $data - form post data
     */
    public function updatePost($post, $data)
    {
        $post->update($data);
        return $post;
    }

    /**
     * Delete a post
     * 
     * @param $post - Post object
     */
    public function deletePost($post)
    {
        return $post->delete();
    }

    /**
     * Get all posts with the count of comments for each post and the users list.
     */
    public static function getPostsListWithCommentsCount()
    {
        return Post::with(['user'])->withCount('comments');
    }

    /**
     * Load post with categories
     * 
     * @param $post - Post object
     */
    public function loadWithCategory($post)
    {
        return $post->load('categories');
    }
}