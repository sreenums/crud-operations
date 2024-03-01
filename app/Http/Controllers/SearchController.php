<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{

    //For posts search
    public function search(Request $request)
    {
        // Query builder for posts
        $query = Post::query();
        
        //Filtering based on request parameters
        if ($request->has('author') && $request->author != 'all') {
            $query->where('user_id', $request->author);
        }
    
        if ($request->has('status')) {
            if(request('status') == 1){ 
                $query->where('is_active', 1); 
            }
            elseif(request('status') == 0){ 
                $query->where('is_active', 0); 
            }
        }
    
        if ($request->has('search')) {
            $searchTerm = $request->search;
            if(isset($searchTerm)){
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', "%$searchTerm%")
                        ->orWhere('content', 'like', "%$searchTerm%");
                });
            }
        }


        // Get the SQL query and bindings
        $sql = $query->toSql();
        $bindings = $query->getBindings();

        // Log the SQL query and bindings
        Log::info("SQL: $sql; Bindings: " . json_encode($bindings));

    
        // Execute the query
        $results = $query->get();
    
        // Return JSON response with search results
        return response()->json($results);
    }

}
