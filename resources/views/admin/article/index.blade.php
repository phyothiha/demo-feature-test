<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Articles') }}
            </h2>

            <a href="{{ route('dashboard.articles.create') }}">
                Create new
            </a>
        </div>
    </x-slot>

    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-lg border border-default border-b-0 mb-4">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="bg-neutral-secondary-soft border-b border-default">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Published At
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($articles as $article)
                    <tr class="odd:bg-neutral-primary even:bg-neutral-secondary-soft border-b border-default">
                        <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                            {{ $article->id }}
                        </th>
                        <td class="px-6 py-4">
                            <a href="{{ route('articles.show', $article->slug) }}" class="font-medium text-blue-500 hover:underline">
                                {{ $article->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            {{ $article->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('dashboard.articles.edit', $article->id) }}" class="font-medium text-yellow-500 hover:underline">
                                Edit
                            </a>
                            <form action="{{ route('dashboard.articles.destroy', $article->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="text-red-500">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        {{ $articles->links() }}
    </div>

</x-dashboard-layout>
