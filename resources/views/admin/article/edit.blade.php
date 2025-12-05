<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Currently editing "{{ $article->title }}"
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.articles.index') }}">
                    Article Listing
                </a>

                <a href="{{ route('dashboard.articles.index') }}">
                    Create new
                </a>
            </div>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('dashboard.articles.update', $article->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label for="title" class="block mb-2.5 text-sm font-medium text-heading">Title</label>
            <input type="title" id="title" class="bg-slate-secondary-medium border border-slate-200 text-heading text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" name="title" value="{{ old('title', $article->title) }}" />

            @error('title')
                <p class="mt-1 text-sm text-red-500"><span class="font-medium">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="slug" class="block mb-2.5 text-sm font-medium text-heading">Slug</label>
            <input type="slug" id="slug" class="bg-slate-secondary-medium border border-slate-200 text-heading text-sm rounded-lg focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs" name="slug" value="{{ old('slug', $article->slug) }}" />

            @error('slug')
                <p class="mt-1 text-sm text-red-500"><span class="font-medium">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="mb-5">
            <label for="content" class="block mb-2.5 text-sm font-medium text-heading">Content</label>
            <textarea id="content" rows="4" class="bg-slate-secondary-medium border border-slate-200 text-heading text-sm rounded-lg focus:ring-brand focus:border-brand block w-full p-3.5 shadow-xs" placeholder="Write your thoughts here..." name="content">{{ old('content', $article->content) }}</textarea>
            @error('content')
                <p class="mt-1 text-sm text-red-500"><span class="font-medium">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button type="submit" class="text-white bg-indigo-500 box-border border border-transparent hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-600 shadow-xs font-medium leading-5 rounded-lg text-sm px-4 py-2 focus:outline-none w-[80px] text-center">
            Save
        </button>
    </form>
</x-dashboard-layout>
