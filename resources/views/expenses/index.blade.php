<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Expenses
        </h2>
    </x-slot>

    <div class="py-12"
         x-data="{ openAdd: false, openView: false, selected: null }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Expense List
                    </h3>

                    <button @click="openAdd = true"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        + Add Expense
                    </button>
                </div>

                <!-- Flash Messages -->
                @if(session('Success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('Success') }}
                    </div>
                @endif

                @if(session('Error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                        {{ session('Error') }}
                    </div>
                @endif

                <!-- Cards -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">

                    @forelse($expenses as $expense)
                        <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">

                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-semibold text-gray-800 truncate">
                                    {{ $expense->expense_name }}
                                </h4>

                                <span class="text-red-500 font-bold">
                                    ₱{{ number_format($expense->total, 2) }}
                                </span>
                            </div>

                            <div class="text-sm text-blue-600 mb-3">
                                {{ $expense->type }}
                            </div>

                            <button
                                @click="
                                    selected = {
                                        name: '{{ $expense->expense_name }}',
                                        type: '{{ $expense->type }}',
                                        quantity: '{{ $expense->quantity ?? 1 }}',
                                        price: '{{ number_format($expense->price, 2) }}',
                                        total: '{{ number_format($expense->total, 2) }}',
                                        description: '{{ $expense->description ?? 'No description' }}',
                                        date: '{{ $expense->created_at->format('M d, Y') }}'
                                    };
                                    openView = true;
                                "
                                class="text-sm text-blue-500 hover:underline">
                                View Details
                            </button>

                        </div>
                    @empty
                        <div class="col-span-3 text-center text-gray-500 py-10">
                            No expenses yet.
                        </div>
                    @endforelse

                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $expenses->links() }}
                </div>

            </div>

        </div>

        <!-- ✅ INCLUDE MODALS -->
        @include('expenses.partials.add-modal')
        @include('expenses.partials.view-modal')

    </div>
</x-app-layout>
