<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::where('is_published', true)
            ->latest('published_at')
            ->paginate(9);

        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        abort_unless($article->is_published, 404);

        $article->increment('views');

        $related = Article::where('is_published', true)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'related'));
    }
}
