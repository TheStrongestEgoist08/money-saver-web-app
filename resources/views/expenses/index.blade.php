
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
            dateTo: '',

            showNoResults: false,

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
         }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Modern Pie Chart Section -->
            <div class="mb-12 bg-white border border-gray-100 rounded-3xl p-6 md:p-8 shadow-xl shadow-gray-100/70">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900">Overall Expense Distribution</h3>
                        <p class="text-gray-500 mt-1">Breakdown by category</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs uppercase tracking-widest text-gray-500">Total Spent</p>
                        <p id="total-spent" class="text-3xl font-bold text-gray-900">₱0</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

                    <!-- Pie Chart - Vertically Centered -->
                    <div class="lg:col-span-7 flex items-center justify-center min-h-[420px]">
                        <div class="relative w-full max-w-[380px] aspect-square">
                            <canvas id="expenseChart"></canvas>
                        </div>
                    </div>

                    <!-- Legend with Progress Bars -->
                    <div class="lg:col-span-5">
                        <h4 class="font-medium text-gray-700 mb-5 text-lg">Categories</h4>
                        <div id="custom-legend" class="space-y-5 max-h-[520px] overflow-y-auto pr-2 custom-scroll"></div>
                    </div>
                </div>
            </div>

            <!-- Main Expense List Card -->
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
                                class="expense-card group bg-white border border-gray-200 hover:border-blue-200 rounded-3xl p-6 shadow-sm hover:shadow-xl transition-all duration-300"
                                x-show="
                                    (search === '' || '{{ strtolower(addslashes($expense->expense_name)) }}'.includes(search.toLowerCase())) &&
                                    (filterType === '' || '{{ $expense->type }}' === filterType) &&
                                    (!dateFrom || '{{ $expense->created_at->format('Y-m-d') }}' >= dateFrom) &&
                                    (!dateTo || '{{ $expense->created_at->format('Y-m-d') }}' <= dateTo)
                                "
                                x-transition>

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('expenseChart');
            let labels = @json($categoryLabels ?? []);
            let data = @json($categoryAmounts ?? []);

            const total = data.reduce((sum, val) => sum + Number(val), 0);

            // Update total amount
            document.getElementById('total-spent').textContent = '₱' + total.toLocaleString('en-US');

            const chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16',
                            '#22c55e', '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9',
                            '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#d946ef',
                            '#ec4899', '#f43f5e', '#64748b'
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 5,
                        hoverOffset: 18
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.96)',
                            titleColor: '#f1f5f9',
                            bodyColor: '#e2e8f0',
                            padding: 16,
                            titleFont: { size: 15, weight: '600' },
                            bodyFont: { size: 14 },
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const percent = total ? ((value / total) * 100).toFixed(1) : 0;
                                    return ` ₱${value.toLocaleString('en-US')} (${percent}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1300,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Custom Legend with Progress Bars
            function createCustomLegend() {
                const legendContainer = document.getElementById('custom-legend');
                legendContainer.innerHTML = '';

                if (labels.length === 0) {
                    legendContainer.innerHTML = `
                        <div class="text-center py-12 text-gray-400">
                            No expense data to display
                        </div>
                    `;
                    return;
                }

                labels.forEach((label, index) => {
                    const value = Number(data[index]);
                    const percent = total ? ((value / total) * 100).toFixed(1) : 0;
                    const color = chart.data.datasets[0].backgroundColor[index];

                    const itemHTML = `
                        <div class="bg-white border border-gray-100 rounded-3xl p-5 hover:shadow-sm transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-2xl flex-shrink-0 shadow" style="background-color: ${color}"></div>
                                    <span class="font-semibold text-gray-800">${label}</span>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-xl text-gray-900">₱${value.toLocaleString('en-US')}</div>
                                    <div class="text-sm text-gray-500">${percent}%</div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                <div
                                    class="h-full rounded-full transition-all duration-700"
                                    style="background-color: ${color}; width: ${percent}%">
                                </div>
                            </div>
                        </div>
                    `;

                    legendContainer.innerHTML += itemHTML;
                });
            }

            createCustomLegend();
        });
    </script>

    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: #d1d5db;
            border-radius: 20px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background-color: #9ca3af;
        }
    </style>
</x-app-layout>
