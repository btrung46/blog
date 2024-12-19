<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $featuredPosts = Cache::remember("featuredPosts",60*4, function () {
            return Post::published()->featured()->with('categories')->latest('published_at')->take(3)->get();
        });
        $latestPosts = Cache::remember("latestPosts",60*4, function () {
            return Post::published()->featured()->with('categories')->latest('published_at')->take(6)->get();
        });
        return view("home",[
            "featuredPosts" => $featuredPosts,
            "latestPosts" =>$latestPosts,
        ]);
    }
}
