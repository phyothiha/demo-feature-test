<x-site-layout>

    <div class="mb-4 space-y-4">
        @foreach ($articles as $article)
            <div>
                <h2 class="text-3xl font-semibold mb-2">
                    {{ $article->title }}
                </h2>

                <p class="text-sm">
                    By <b>{{ $article->user->name }}</b>

                    @if (auth()->check() && (auth()->id() === $article->user_id))
                        <span class="bg-yellow-300 text-slate-600 px-2 py-1 inline-block">
                            (You)
                        </span>
                    @endif
                </p>

                <p class="mt-4 mb-6">
                    {{ Str::limit($article->content, 250) }}
                </p>

                <a href="{{ route('articles.show', $article) }}" class="text-blue-500 hover:underline">
                    Read More #{{ $article->id }}
                </a>
            </div>
        @endforeach
    </div>

    {{ $articles->links() }}

</x-site-layout>
