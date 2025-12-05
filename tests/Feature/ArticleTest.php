<?php

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

describe('guest', function () {
    beforeEach(fn () => $this->assertGuest());

    it('can view published articles with pagination', function () {
        Article::factory()->published()->count(3)->create();

        get(route('articles.index'))
            ->assertOk()
            ->assertViewIs('article.index')
            ->assertViewHas('articles', function ($articles) {
                expect($articles)->toBeInstanceOf(LengthAwarePaginator::class);

                $articles->each(fn ($article) => expect($article)->toBeInstanceOf(Article::class));

                return true;
            });
    });

    it('can view a single article', function () {
        $article = Article::factory()->published()->create();

        get(route('articles.show', $article))
            ->assertOk()
            ->assertViewIs('article.show')
            ->assertViewHas('article', fn ($viewArticle) => $viewArticle->is($article));
    });

    it('show default 404 page when article not found', function () {
        get('/articles/non-existing-slug')
            ->assertNotFound();
    });

    it('show message when no articles are available', function () {
        get(route('articles.index'))
            ->assertOk()
            ->assertSee('No articles are written yet.');
    });
});
