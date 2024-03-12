<?php
namespace App\Repositories;

use App\Models\PostCategory;
class CategoryRepository
{
    protected $model;

    public function __construct(PostCategory $postCategory)
    {
        $this->model = $postCategory;
    }

    /**
     * Creation of categories for a post
     * 
     * @param $postId - Id corresponds to the post 
     * @param $categoryIds - List of selected categories from $request.
     */
    public function createPostCategory($postId, $categoryIds)
    {
        foreach ($categoryIds as $categoryId) {
            $this->model->create([
                'post_id' => $postId,
                'category_id' => $categoryId
            ]);
        }
    }

    /**
     * Update or save Categories
     * 
     * @param $postId - Id corresponds to the post
     * @param $selectedCategoryIds - array of category Ids
     */
    public function saveOrUpdateCategories($postId, $selectedCategoryIds)
    {
        // Delete categories not selected during edit
        $this->model->where('post_id', $postId)
            ->whereNotIn('category_id', $selectedCategoryIds)
            ->delete();

        // Save or update categories
        foreach ($selectedCategoryIds as $categoryId) {
            $this->model->updateOrCreate(
                [
                    'post_id' => $postId,
                    'category_id' => $categoryId
                ]
            );
        }
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