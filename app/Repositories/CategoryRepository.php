<?php
namespace App\Repositories;

use App\Models\Category;

class PostRepository
{
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function createCategory($postId, $categoryIds)
    {
        foreach ($categoryIds as $categoryId) {
            Category::create([
                'post_id' => $postId,
                'category_master_id' => $categoryId
            ]);
        }
    }
    
}