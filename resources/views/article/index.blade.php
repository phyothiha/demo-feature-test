<x-site-layout>

    <div class="space-y-6">
        @foreach ($articles as $article)
            <div>
                <h2 class="text-3xl font-semibold mb-2">
                    {{ $article->title }}
                </h2>

                <p class="text-sm flex items-center gap-2">
                    <span>
                        By <b>{{ $article->user->name }}</b>
                    </span>

                    @if (auth()->check() && (auth()->id() === $article->user_id))
                        <span class="bg-indigo-200 text-slate-800 px-3 py-0.5 inline-block rounded-lg text-xs">
                            (You)
                        </span>
                    @endif
                </p>

                <p class="text-sm mt-1">
                    Publication Date - <em>{{ $article->created_at->format('F j, Y') }}</em>
                </p>

                <p class="mt-4 mb-6">
                    {{ Str::limit($article->content, 250) }}
                </p>



                <a href="{{ route('articles.show', $article) }}" class="text-blue-500 hover:underline">
                    Read More #{{ $article->id }}
                </a>
            </div>
        @endforeach

        {{ $articles->links() }}
    </div>
</x-site-layout>
