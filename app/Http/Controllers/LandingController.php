<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class LandingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //Ambil data artikel
        $articles = Article::query();

        //Jika terdapat query parameter category
        if ($request->query('category')) {
            //Filter artikel berdasarkan category
            $articles->whereHas('category', function ($query) use ($request) {
                $query->where('slug', $request->query('category'));
            });
        }
        //select * from articles join category on articles.category_id = category_id

        //Sort artikel berdasarkan published_at dan paginate 7 item per halaman
        $articles = $articles?->orderBy('published_at', 'desc')->paginate(7);

        //Ambil artikel pertama dari hasil query diatas
        $featured = $articles?->shift();
        return view('landing', compact('articles', 'featured'));
    }
}
