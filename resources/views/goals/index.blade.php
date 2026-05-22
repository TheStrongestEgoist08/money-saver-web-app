
{{-- Goals Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl md:text-3xl text-gray-800 tracking-tight">
            My Goals
        </h2>
    </x-slot>

    <div
        class="py-8 md:py-10 capitalize"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Filter --}}
            @include('goals.partials.filter')

            {{-- Table --}}
            @include('goals.partials.table')
        </div>
    </div>
</x-app-layout>
