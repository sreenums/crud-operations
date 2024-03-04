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

    }

    //For posts search
    public function search(Request $request)
    {
        // Query builder for posts
        $query = Post::query();
        
        //Filtering based on author
        if ($request->has('author') && $request->author != 'all') {
            $query->where('user_id', $request->author);
        }
    
        //Filtering based on status
        if ($request->has('status')) {
            if(request('status') == 1){ 
                $query->where('is_active', 1); 
            }
            elseif(request('status') == 0){ 
                $query->where('is_active', 0); 
            }
        }
    
        //Filtering based on search input
        if ($request->has('search')) {
            $searchTerm = $request->search;
            if(isset($searchTerm)){
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', "%$searchTerm%")
                        ->orWhere('content', 'like', "%$searchTerm%");
                });
            }
        }

        //Filtering based on comments count
        if ($request->has('commentsCount') && isset($request->commentsCount)) {
            $query->having('comments_count', '=', $request->commentsCount);
        }

        // Get the SQL query and bindings
        $sql = $query->toSql();
        $bindings = $query->getBindings();

        // Log the SQL query and bindings
        Log::info("SQL: $sql; Bindings: " . json_encode($bindings));


        // Execute the query
        //$results = $query->latest()->with(['user'])->withCount('comments')->get();
        $posts = $query->latest()->with(['user'])->withCount('comments')->paginate(2);
    
        if ($request->has('page') ) {
            $userNames = User::select('id', 'name')
                ->whereIn('id', function ($query) {
                    $query->select('user_id')
                        ->from('posts');
                    })
                ->distinct()
                ->get();
            return view('posts.posts-home', compact('posts','userNames','request'));
        }

        // Return JSON response with search results including pagination links
        return response()->json([
            'data' => $posts->items(),
            'links' => $posts->appends(request()->query())->links()->toHtml(),
        ]);


        // Return JSON response with search results
        //return response()->json($results);
    }

}
