<x-dashboard-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create an article') }}
            </h2>

            <a href="{{ route('dashboard.articles.index') }}">
                Article Listing
            </a>
        </div>
    </x-slot>

    <div>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque incidunt necessitatibus excepturi qui. Itaque ipsa distinctio odio nulla in quod earum, repellat porro quia est similique natus dolores corrupti quam.
    </div>
</x-dashboard-layout>
