<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;
use function Pest\Laravel\{actingAs, assertDatabaseHas, delete, get, post, put};

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

uses(RefreshDatabase::class);

describe('guest', function () {
    // beforeEach(fn () => $this->assertGuest());

    // INDEX
    it('should return main articles page', function () {
        get(route('articles.index'))
            ->assertOk()
            ->assertViewIs('article.index');
    });

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

    it('contain specific data for articles listing', function () {
        $articles = Article::factory()->published()->count(3)->create();

        get(route('articles.index'))
            ->assertOk()
            ->assertSeeText($articles[0]['title'])
            ->assertSeeText(Str::limit($articles[0]['content'], 250))
            ->assertSeeText($articles[0]['user']['name'])
            ->assertSeeText($articles[0]['created_at']->format('F j, Y'))
            ->assertSeeText('Read More');
    });

    it('should not show unpublished articles', function () {
        Article::factory()->published()->count(5)->create();

        $unpublishedArticle = Article::factory()->unpublished()->create();

        get(route('articles.index'))
            ->assertOk()
            ->assertViewHas('articles', function ($articles) use ($unpublishedArticle) {
                expect($articles->pluck('id')->toArray())
                    ->not
                    ->toContain($unpublishedArticle->id);

                return true;
            });
    });

    it('read more link should redirect correctly using article slug', function () {
        $articles = Article::factory()->published()->count(5)->create();

        get(route('articles.index'))
            ->assertOk()
            ->assertSee('/articles/' . $articles[0]->slug);
    });

    // SHOW
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
            ->assertSeeText('No articles are written yet.');
    });

    it('contain specific data for single article view', function () {
        $article = Article::factory()->published()->create();

        get(route('articles.show', $article))
            ->assertOk()
            ->assertSeeText($article->title)
            ->assertSeeText($article->content)
            ->assertSeeText($article->user->name)
            ->assertSeeText($article->created_at->format('F j, Y'));
    });
});

describe('auth', function () {
    // INDEX - Dashboard articles list
    it('can access dashboard articles page', function () {
        $user = User::factory()->create();

        actingAs($user)
            ->get(route('dashboard.articles.index'))
            ->assertOk()
            ->assertViewIs('admin.article.index')
            ->assertViewHas('articles');
    });

    it('can view own articles including trashed ones in dashboard', function () {
        $user = User::factory()->create();
        $ownArticle = Article::factory()->for($user)->published()->create();
        $trashedArticle = Article::factory()->for($user)->published()->trashed()->create();
        $otherUserArticle = Article::factory()->create();

        actingAs($user)
            ->get(route('dashboard.articles.index'))
            ->assertOk()
            ->assertViewHas('articles', function ($articles) use ($ownArticle, $trashedArticle, $otherUserArticle) {
                $articleIds = $articles->pluck('id')->toArray();

                expect($articleIds)
                    ->toContain($ownArticle->id)
                    ->toContain($trashedArticle->id)
                    ->not->toContain($otherUserArticle->id);

                return true;
            });
    });

    // CREATE - Show create form
    it('can access create article page', function () {
        $user = User::factory()->create();

        actingAs($user)
            ->get(route('dashboard.articles.create'))
            ->assertOk()
            ->assertViewIs('admin.article.create');
    });

    // STORE - Create new article
    it('can create a new article', function () {
        $user = User::factory()->create();

        $articleData = [
            'title' => 'New Test Article',
            'content' => 'This is the content of the test article.',
        ];

        actingAs($user)
            ->post(route('dashboard.articles.store'), $articleData)
            ->assertRedirect(route('dashboard.articles.index'))
            ->assertSessionHas('success', 'Article created successfully.');

        assertDatabaseHas('articles', [
            'title' => 'New Test Article',
            'content' => 'This is the content of the test article.',
            'user_id' => $user->id,
        ]);
    });

    it('validates required fields when creating article', function () {
        $user = User::factory()->create();

        actingAs($user)
            ->post(route('dashboard.articles.store'), [])
            ->assertSessionHasErrors(['title', 'content']);
    });

    // EDIT - Show edit form
    it('can access edit page for own article', function () {
        $user = User::factory()->create();
        $article = Article::factory()->for($user)->published()->create();

        actingAs($user)
            ->get(route('dashboard.articles.edit', $article))
            ->assertOk()
            ->assertViewIs('admin.article.edit')
            ->assertViewHas('article', fn ($viewArticle) => $viewArticle->is($article));
    });

    it('cannot access edit page for other user article', function () {
        $user = User::factory()->create();
        $otherArticle = Article::factory()->published()->create();

        actingAs($user)
            ->get(route('dashboard.articles.edit', $otherArticle))
            ->assertForbidden();
    });

    // UPDATE - Update existing article
    it('can update own article', function () {
        $user = User::factory()->create();
        $article = Article::factory()->for($user)->published()->create();

        $updatedData = [
            'title' => 'Updated Title',
            'slug' => 'updated-slug',
            'content' => 'Updated content for the article.',
        ];

        actingAs($user)
            ->put(route('dashboard.articles.update', $article), $updatedData)
            ->assertRedirect(route('dashboard.articles.index'))
            ->assertSessionHas('success', 'Article updated successfully.');

        assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
            'slug' => 'updated-slug',
            'content' => 'Updated content for the article.',
        ]);
    });

    it('cannot update other user article', function () {
        $user = User::factory()->create();
        $otherArticle = Article::factory()->create();

        actingAs($user)
            ->put(route('dashboard.articles.update', $otherArticle), [
                'title' => 'Trying to update',
                'slug' => 'trying-to-update',
                'content' => 'Should not work',
            ])
            ->assertForbidden();

        expect($otherArticle->refresh()->title)->not->toBe('Trying to update');
    });

    it('validates required fields when updating article', function () {
        $user = User::factory()->create();
        $article = Article::factory()->for($user)->published()->create();

        actingAs($user)
            ->put(route('dashboard.articles.update', $article), [])
            ->assertSessionHasErrors(['title', 'slug', 'content']);
    });

    // DESTROY - Delete article
    it('can delete own article', function () {
        $user = User::factory()->create();
        $article = Article::factory()->for($user)->published()->create();

        actingAs($user)
            ->delete(route('dashboard.articles.destroy', $article))
            ->assertRedirect(route('dashboard.articles.index'))
            ->assertSessionHas('success', 'Article deleted successfully.');

        expect($article->refresh()->trashed())->toBeTrue();
    });

    it('cannot delete other user article', function () {
        $user = User::factory()->create();
        $otherArticle = Article::factory()->create();

        actingAs($user)
            ->delete(route('dashboard.articles.destroy', $otherArticle))
            ->assertForbidden();

        expect($otherArticle->refresh()->trashed())->toBeFalse();
    });

    // Middleware tests
    it('requires authentication to access dashboard articles', function () {
        get(route('dashboard.articles.index'))
            ->assertRedirect(route('login'));
    });

    it('requires authentication to create article', function () {
        post(route('dashboard.articles.store'), [
            'title' => 'Test',
            'content' => 'Test content',
        ])
            ->assertRedirect(route('login'));
    });
});
