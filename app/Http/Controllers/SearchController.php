<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $posts = Post::getPostsListWithCommentsCount();

            // Total records before filtering
            $totalRecords = $posts->count();
            
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


            // Get the SQL query and bindings
            $sql = $posts->toSql();
            $bindings = $posts->getBindings();

            // Log the SQL query and bindings
            Log::info("SQL: $sql; Bindings: " . json_encode($bindings));


            // Total records after filtering
            $filteredRecords = $posts->count();
            $posts = $posts->skip($request->input('start'))->take($request->input('length'))->get();

            // Format data for DataTable
            $formattedData = $posts->map(function($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'user_id' => $post->user->name,
                    'date_published' => $post->published_at_formatted,
                    'comments_count' => $post->comments_count,
                    'is_active' => $post->status_text,
                ];
            });

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
