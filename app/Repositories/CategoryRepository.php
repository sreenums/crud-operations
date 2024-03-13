<?php
namespace App\Repositories;

use App\Models\CategoryPost;

class CategoryRepository
{
    protected $model;

    public function __construct(CategoryPost $postCategory)
    {
        $this->model = $postCategory;
    }

    /**
     * Creation of categories for a post
     * 
     * @param $post - Post object
     * @param $categoryIds - List of selected categories from $request.
     */
    public function createPostCategory($post, $categoryIds)
    {
        foreach ($categoryIds as $categoryId) {
            $post->categories()->attach($categoryId);
        }
        
    }

    /**
     * Update or save Categories
     * 
     * @param $post - Post object
     * @param $selectedCategoryIds - array of category Ids
     */
    public function saveOrUpdateCategories($post, $selectedCategoryIds)
    {
        $post->categories()->sync($selectedCategoryIds);
    }

    /**
     * Delete category for a post
     * 
     * @param $postId - Id corresponds to the post
     */
    public function deletePostCategory($postId)
    {
        return $this->model->where('post_id',$postId)->delete();
    }
    
}