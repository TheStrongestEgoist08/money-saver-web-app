
{{-- Expense Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 tracking-tight">
            My Expenses
        </h2>
    </x-slot>

    <div class="py-10"
         x-data="{
            openAdd: false,
            openView: false,
            selected: null,
            search: '',
            filterType: '',
            dateFrom: '',
            dateTo: ''
         }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl overflow-hidden">

                <!-- Page Header -->
                <div class="px-8 pt-8 pb-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900">Expense List</h3>
                        <p class="text-gray-500 mt-1">Track and manage your daily expenses</p>
                    </div>

                    <button
                        @click="openAdd = true"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-2xl flex items-center gap-2 shadow-lg shadow-blue-200 transition-all active:scale-95">
                        <span class="text-xl leading-none">+</span>
                        Add New Expense
                    </button>
                </div>

                <!-- Filters Bar -->
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="search"
                                    placeholder="Search expense name..."
                                    class="w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 outline-none transition-all">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</div>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <select
                                x-model="filterType"
                                class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 outline-none transition-all">
                                <option value="">All Categories</option>
                                <option value="Food">Food</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Bills">Bills</option>
                                <option value="Clothing">Clothing</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <input
                                type="date"
                                x-model="dateFrom"
                                class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 outline-none transition-all">
                        </div>

                        <!-- Date To -->
                        <div class="flex items-center gap-2">
                            <input
                                type="date"
                                x-model="dateTo"
                                class="flex-1 px-5 py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 outline-none transition-all">
                            <button
                                @click="dateFrom = ''; dateTo = ''"
                                class="px-5 py-3.5 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                <div class="px-8 pt-6">
                    @if(session('Success'))
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3">
                            <span class="text-xl">✅</span>
                            <span>{{ session('Success') }}</span>
                        </div>
                    @endif

                    @if(session('Error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center gap-3">
                            <span class="text-xl">⚠️</span>
                            <span>{{ session('Error') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Main Content Area -->
                <div class="p-8">

                    <!-- Expense Cards -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                        @forelse($expenses as $expense)
                            <div
                                class="group bg-white border border-gray-200 hover:border-blue-200 rounded-3xl p-6 shadow-sm hover:shadow-xl transition-all duration-300"
                                x-show="
                                    (search === '' || '{{ strtolower(addslashes($expense->expense_name)) }}'.includes(search.toLowerCase())) &&
                                    (filterType === '' || '{{ $expense->type }}' === filterType) &&
                                    (!dateFrom || '{{ $expense->created_at->format('Y-m-d') }}' >= dateFrom) &&
                                    (!dateTo || '{{ $expense->created_at->format('Y-m-d') }}' <= dateTo)
                                ">

                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-lg text-gray-900 line-clamp-1">
                                            {{ $expense->expense_name }}
                                        </h4>
                                        <p class="text-blue-600 font-medium text-sm mt-1">
                                            {{ $expense->type }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-2xl font-bold text-red-600">
                                            ₱{{ number_format($expense->total, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-xs text-gray-500 mb-5">
                                    {{ $expense->created_at->format('M d, Y') }}
                                </div>

                                <button
                                    @click="
                                        selected = {
                                            name: '{{ addslashes($expense->expense_name) }}',
                                            type: '{{ addslashes($expense->type) }}',
                                            quantity: '{{ $expense->quantity ?? 1 }}',
                                            price: '{{ number_format($expense->price, 2) }}',
                                            total: '{{ number_format($expense->total, 2) }}',
                                            description: '{{ addslashes($expense->description ?? 'No description provided.') }}',
                                            date: '{{ $expense->created_at->format('M d, Y') }}'
                                        };
                                        openView = true;
                                    "
                                    class="w-full py-3 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-2xl transition-all group-hover:text-blue-700">
                                    View Details →
                                </button>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-20">
                                <div class="text-6xl mb-4 opacity-30">📭</div>
                                <p class="text-gray-400 text-xl">No expenses recorded yet</p>
                                <p class="text-gray-500 mt-2">Click "Add New Expense" to get started</p>
                            </div>
                        @endforelse

                    </div>

                    <!-- No Matching Results Message -->
                    <div
                        class="text-center py-20"
                        x-show="
                            @php echo count($expenses) > 0 @endphp &&
                            document.querySelectorAll('.group[x-show]').length === 0
                        ">
                        <div class="text-6xl mb-6 opacity-40">😕</div>
                        <p class="text-xl text-gray-400 font-medium">No matching expenses found</p>
                        <p class="text-gray-500 mt-2">Try changing your search term or filters</p>

                        <button
                            @click="search = ''; filterType = ''; dateFrom = ''; dateTo = ''"
                            class="mt-6 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-medium transition-colors">
                            Clear All Filters
                        </button>
                    </div>

                </div>

                <!-- Pagination -->
                @if($expenses->hasPages())
                    <div class="px-8 py-6 border-t border-gray-100 bg-gray-50">
                        <div class="flex justify-center">
                            {{ $expenses->links() }}
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <!-- Modals -->
        @include('expenses.partials.add-modal')
        @include('expenses.partials.view-modal')

    </div>
</x-app-layout>
