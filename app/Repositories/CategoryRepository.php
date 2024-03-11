<?php
namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    /**
     * Creation of categories for a post
     * 
     * @param $postId - Id corresponds to the post 
     * @param $categoryIds - List of selected categories from $request.
     */
    public function createCategory($postId, $categoryIds)
    {
        foreach ($categoryIds as $categoryId) {
            $this->model->create([
                'post_id' => $postId,
                'category_master_id' => $categoryId
            ]);
        }
    }

    /**
     * Delete categories not selected during edit
     * 
     * @param $postId - Id corresponds to the post, 
     * @param $newCategoryIds - Id of new categories from $request
     */
    public function deleteCategoriesNotInList($postId, $newCategoryIds)
    {
        $this->model->where('post_id', $postId)
            ->whereNotIn('category_master_id', $newCategoryIds)
            ->delete();
    }

    /**
     * Update or save Categories
     * 
     * @param $postId - Id corresponds to the post
     * @param $newCategoryIds - array of category Ids
     */
    public function saveOrUpdateCategories($postId, $newCategoryIds)
    {
        foreach ($newCategoryIds as $categoryId) {
            $this->model->updateOrCreate(
                [
                    'post_id' => $postId,
                    'category_master_id' => $categoryId
                ],
                [
                    'post_id' => $postId,
                    'category_master_id' => $categoryId
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