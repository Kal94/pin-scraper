<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;

class LinkController extends Controller
{
    /*
     * 
     * This method retrieves links from the database based on the tags provided 
     * in the request. It returns the matching links as a JSON response.
     * 
     */
    public function index(Request $request)
    {
        $tags = explode(',', $request->query('tags'));

        // Query the 'links' table in the database for links that match any of the provided tags.
        $links = Link::where(function ($query) use ($tags) {
            foreach ($tags as $tag) {
                $query->where('tags', 'LIKE', "%$tag%");
            }
        })->get();
        
        return response()->json($links);
    }
}