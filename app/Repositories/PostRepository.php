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

    public function createPost($data)
    {
        return $this->model->create($data);
    }

    public function updatePost($post, $data)
    {
        $post->update($data);
        return $post;
    }

    public function deletePost($post)
    {
        return $post->delete();
    }

}