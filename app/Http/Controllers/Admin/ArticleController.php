<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = auth()->user()
            ->articles()
            ->withTrashed()
            ->latest()
            ->paginate();

        return view('admin.article.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.article.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();

        $article = Article::create($validated);

        return redirect()->route('dashboard.articles.index')->with('success', 'Article created successfully.');
    }

    public function show(Article $article)
    {
        return view('article.show', compact('article'));
    }

    public function edit(Article $article)
    {
        Gate::authorize('update', $article);

        return view('admin.article.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:articles',
            'content' => 'required|string',
        ]);

        if (isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $article->update($validated);

        return redirect()->route('dashboard.articles.index')->with('success', 'Article updated successfully.');
    }

    // Force Delete
    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);

        $article->forceDelete();

        return redirect()->route('dashboard.articles.index')->with('success', 'Article permanently deleted successfully.');
    }

    public function restore(Article $article)
    {
        Gate::authorize('update', $article);

        $article->restore();

        return redirect()->route('dashboard.articles.index')->with('success', 'Article re-published successfully.');
    }

    public function delete(Article $article)
    {
        Gate::authorize('update', $article);

        $article->delete();

        return redirect()->route('dashboard.articles.index')->with('success', 'Article un-published successfully.');
    }
}
