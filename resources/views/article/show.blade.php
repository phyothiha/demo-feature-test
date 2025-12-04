<x-site-layout>
    <div>
        <h2 class="text-3xl font-semibold mb-2">
            {{ $article->title }}
        </h2>

        <p class="text-sm">
            By <b>{{ $article->user->name }}</b>
        </p>

        <p class="mt-4 mb-6">
            {!! nl2br(e($article->content)) !!}
        </p>
    </div>
</x-site-layout>
