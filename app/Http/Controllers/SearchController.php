<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            //$posts = Post::getPostsListWithCommentsCount();
            $posts = $this->postService->getPostsListWithCommentsCount($request);

            // Total records before filtering
            $totalRecords = $posts->count();
            
            $posts = $this->postService->FilterPost($request, $posts);

            // Total records after filtering
            $filteredRecords = $posts->count();
            $posts = $posts->skip($request->input('start'))->take($request->input('length'))->get();

            /**
             * Format for datatable
             * 
             */
            $formattedData = $this->postService->formatDataTable($posts);

            // Return JSON response with data and counts
            return response()->json([
                'data' => $formattedData,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
            ]);
        }
        
        //$posts = Post::getPostsListWithCommentsCount();
        $userNames = User::getPostsUserNames();
        //return view('posts.posts-home', compact('posts','userNames'));
        return view('posts.posts-home', compact('userNames'));
    }

}
