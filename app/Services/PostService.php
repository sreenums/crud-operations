<?php


namespace App\Services;

use App\Http\Requests\EditPostRequest;
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
        
        return $this->postRepository->createPost($postData);
    }


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

    // public function getPostCreateData($request)
    // {  
    //     return [
    //         'title' => $request->title,
    //         'user_id' => $request->author,$validated['author'],
    //         'content' => $request->content,$validated['content'],
    //         'date_published' => $request->datePublished,$validated['datePublished'],
    //         'is_active' => $request->checkActive,$request->input('checkActive'),
    //         'image' => $imageName
    //     ];

    // }

    public function updatePost($request, $post)
    {
        $validated = $request->validated();

        // image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = uniqid().'.'. $request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $imageName);
        }
        
        // Update post data
        $postData = [
            'title' => $validated['title'],
            'user_id' => $validated['author'],
            'content' => $validated['content'],
            'date_published' => $validated['datePublished'],
            'is_active' => $request->input('checkActive')
        ];

        if (isset($imageName)) {
            $postData['image'] = $imageName;
        }

        return $this->postRepository->updatePost($post, $postData);
    }

    public function deletePost($post)
    {
        return $this->postRepository->deletePost($post);
    }

}