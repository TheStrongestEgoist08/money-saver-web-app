{{-- Expense Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 tracking-tight">
            My Expenses
        </h2>
    </x-slot>

    <div
        class="py-10"
        x-data="{
            openAdd: false,
            openView: false,
            selected: null,

            search: '',
            filterType: '',
            dateFrom: '',
            dateTo: '',

            showNoResults: false,

            // Watch for filter changes and update no results message
            init() {
                this.$watch(() => [this.search, this.filterType, this.dateFrom, this.dateTo], () => {
                    this.$nextTick(() => {
                        this.updateNoResults();
                    });
                });
            },

            updateNoResults() {
                const cards = document.querySelectorAll('.expense-card');

                let visible = 0;

                cards.forEach(card => {
                    if (card.style.display !== 'none') visible++;
                });

                this.showNoResults = visible === 0 && cards.length > 0;
            },

            clearAllFilters() {
                this.search = '';
                this.filterType = '';
                this.dateFrom = '';
                this.dateTo = '';
            }
         }"
    >

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Modern Pie Chart Section -->
            @include('expenses.partials.graph')

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
                @include('expenses.partials.filter')

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

                <!-- Main Content -->
                <div class="p-8">

                    <!-- Expense Cards -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                        @forelse($expenses as $expense)
                            <div
                                class="expense-card group relative bg-white border border-gray-200 hover:border-blue-200 rounded-3xl p-6 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden"
                                x-show="
                                    (search === '' || '{{ strtolower(addslashes($expense->expense_name)) }}'.includes(search.toLowerCase())) &&
                                    (filterType === '' || '{{ $expense->type }}' === filterType) &&
                                    (!dateFrom || '{{ $expense->created_at->format('Y-m-d') }}' >= dateFrom) &&
                                    (!dateTo || '{{ $expense->created_at->format('Y-m-d') }}' <= dateTo)
                                "
                                x-transition>

                                <!-- Repetitive Emoji Background Pattern -->
                                <div class="absolute inset-0 opacity-10 group-hover:opacity-15 transition-all duration-500 pointer-events-none overflow-hidden">
                                    <div class="absolute inset-0 flex flex-wrap justify-around items-center gap-6 text-5xl rotate-[-8deg]">
                                        @for($i = 0; $i < 12; $i++)
                                            <span class="transition-all duration-700"
                                                style="transform: rotate({{ rand(-25, 25) }}deg);">
                                                @switch($expense->type)
                                                    @case('Food') 🍔 @break
                                                    @case('Groceries') 🛒 @break
                                                    @case('Transportation') 🚕 @break
                                                    @case('Bills') 📄 @break
                                                    @case('Utilities') 💡 @break
                                                    @case('Personal Care') 🧼 @break
                                                    @case('Household') 🧽 @break
                                                    @case('Health') 💊 @break
                                                    @case('Clothing') 👕 @break
                                                    @case('Entertainment') 🎮 @break
                                                    @case('Education') 📚 @break
                                                    @case('Savings') 💰 @break
                                                    @case('Gifts') 🎁 @break
                                                    @case('Maintenance') 🔧 @break
                                                    @case('Subscriptions') 📱 @break
                                                    @case('Others') 📌 @break
                                                    @default 📦
                                                @endswitch
                                            </span>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Main Content -->
                                <div class="relative z-10 flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-3">
                                        <!-- Big Emoji Icon -->
                                        <div class="w-14 h-14 flex items-center justify-center text-5xl bg-white border border-gray-100 rounded-2xl shadow-sm flex-shrink-0">
                                            @switch($expense->type)
                                                @case('Food') 🍔 @break
                                                @case('Groceries') 🛒 @break
                                                @case('Transportation') 🚕 @break
                                                @case('Bills') 📄 @break
                                                @case('Utilities') 💡 @break
                                                @case('Personal Care') 🧼 @break
                                                @case('Household') 🧽 @break
                                                @case('Health') 💊 @break
                                                @case('Clothing') 👕 @break
                                                @case('Entertainment') 🎮 @break
                                                @case('Education') 📚 @break
                                                @case('Savings') 💰 @break
                                                @case('Gifts') 🎁 @break
                                                @case('Maintenance') 🔧 @break
                                                @case('Subscriptions') 📱 @break
                                                @case('Others') 📌 @break
                                                @default 📦
                                            @endswitch
                                        </div>

                                        <div>
                                            <h4 class="font-semibold text-lg text-gray-900 line-clamp-1">
                                                {{ $expense->expense_name }}
                                            </h4>
                                            <p class="text-blue-600 font-medium text-sm">
                                                {{ $expense->type }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <span class="block text-2xl font-bold text-red-600">
                                            ₱{{ number_format($expense->total, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-xs text-gray-500 mb-5 relative z-10">
                                    {{ $expense->created_at->format('M d, Y • h:i A') }}
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
                                            date: '{{ $expense->created_at->format('M d, Y • h:i A') }}'
                                        };
                                        openView = true;
                                    "
                                    class="w-full py-3 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-2xl transition-all group-hover:text-blue-700 relative z-10">
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

                    <!-- No Matching Results -->
                    <div
                        class="text-center py-20"
                        x-show="showNoResults">
                        <div class="text-6xl mb-6 opacity-40">😕</div>
                        <p class="text-xl text-gray-400 font-medium">No matching expenses found</p>
                        <p class="text-gray-500 mt-2">Try changing your search term or filters</p>

                        <button
                            @click="clearAllFilters"
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
