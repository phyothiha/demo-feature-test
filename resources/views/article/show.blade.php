<x-site-layout>
    <div>
        <h2 class="text-3xl font-semibold mb-2">
            {{ $article->title }}
        </h2>

        <p class="text-sm">
            By <b>{{ $article->user->name }}</b>
        </p>

        <p class="text-sm mt-1">
            Publication Date - <em>{{ $article->created_at->format('F j, Y') }}</em>
        </p>

        <p class="mt-4">
            {!! nl2br(e($article->content)) !!}
        </p>
    </div>
</x-site-layout>
