
{{-- Dashboard --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 tracking-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Welcome & Balance -->
                <div class="lg:col-span-12">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h3 class="text-2xl md:text-3xl font-semibold text-gray-900">
                                Good morning, {{ auth()->user()?->name ?? 'User' }} 👋
                            </h3>
                            <p class="text-gray-500 mt-2 text-base md:text-lg">Here's what's happening with your money today</p>
                        </div>

                        <a href="{{ route('user.balance') }}"
                           class="px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-2xl flex items-center gap-3 hover:from-emerald-700 hover:to-teal-700 transition-all text-sm md:text-base">
                            <span>💰</span>
                            <span>View Full Balance</span>
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="lg:col-span-4">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-6 md:p-8 h-full">
                        <div class="flex items-center justify-between mb-6">
                            <p class="text-gray-500 font-medium text-sm md:text-base">Current Balance</p>
                            <span class="text-2xl">💵</span>
                        </div>
                        <p class="text-4xl md:text-5xl font-bold text-gray-900 tracking-tighter">
                            ₱{{ number_format($userBalance ?? 0, 2) }}
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-6 md:p-8 h-full">
                        <div class="flex items-center justify-between mb-6">
                            <p class="text-gray-500 font-medium text-sm md:text-base">Expenses This Month</p>
                            <span class="text-2xl">📊</span>
                        </div>
                        <p class="text-4xl md:text-5xl font-bold text-red-600 tracking-tighter">
                            ₱{{ number_format($totalExpensesThisMonth ?? 0, 2) }}
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-6 md:p-8 h-full">
                        <div class="flex items-center justify-between mb-6">
                            <p class="text-gray-500 font-medium text-sm md:text-base">Total Transactions</p>
                            <span class="text-2xl">🔢</span>
                        </div>
                        <p class="text-4xl md:text-5xl font-bold text-gray-900 tracking-tighter">
                            {{ $totalTransactions ?? 0 }}
                        </p>
                    </div>
                </div>

                <!-- Top Expense Highlight -->
                <div class="lg:col-span-12">
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-100 shadow-xl shadow-gray-100/50 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-12 h-12 md:w-14 md:h-14 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center text-3xl">
                                🔥
                            </div>
                            <div>
                                <p class="text-red-600 font-semibold text-base md:text-lg">Highest Expense This Month</p>
                                <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">
                                    {{ $topExpenseCategory ?? 'No expenses yet' }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-gray-500 text-sm">Total Spent</p>
                            <p class="text-3xl md:text-4xl font-bold text-red-600 tracking-tighter">
                                ₱{{ number_format($topExpenseAmount ?? 0, 2) }}
                            </p>
                        </div>

                        <a href="{{ route('user.expenses') }}"
                           class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-2xl transition-all whitespace-nowrap text-sm md:text-base">
                            View All Expenses
                        </a>
                    </div>
                </div>

                <!-- Expense Breakdown - Pie Chart (Like Expense Page) -->
                <div class="lg:col-span-12">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                            <div>
                                <h3 class="text-2xl font-semibold text-gray-900">Expense Breakdown</h3>
                                <p class="text-gray-500 mt-1">This Month • By Category</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs uppercase tracking-widest text-gray-500">Total Spent</p>
                                <p id="total-spent" class="text-3xl font-bold text-gray-900">₱0</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

                            <!-- Pie Chart -->
                            <div class="lg:col-span-7 flex items-center justify-center min-h-[380px]">
                                <div class="relative w-full max-w-[340px] md:max-w-[380px] aspect-square">
                                    <canvas id="expenseChart"></canvas>
                                </div>
                            </div>

                            <!-- Custom Legend -->
                            <div class="lg:col-span-5">
                                <h4 class="font-medium text-gray-700 mb-5 text-lg">Categories</h4>
                                <div id="custom-legend" class="space-y-5 max-h-[420px] overflow-y-auto pr-2 custom-scroll"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="lg:col-span-12">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl overflow-hidden">

                        <div class="px-6 md:px-8 pt-8 pb-5 border-b flex justify-between items-center">
                            <h3 class="text-xl md:text-2xl font-semibold text-gray-900">Recent Expenses</h3>
                            <a href="{{ route('user.expenses') }}"
                               class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2 text-sm md:text-base">
                                View All →
                            </a>
                        </div>

                        <div class="p-6 md:p-8">
                            @forelse($recentExpenses ?? [] as $expense)
                                <div class="flex justify-between items-center py-4 border-b last:border-0">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-gray-100 rounded-2xl flex items-center justify-center text-xl flex-shrink-0">
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
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 truncate">{{ $expense->expense_name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $expense->created_at->format('M d, Y • h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-red-600 whitespace-nowrap">
                                        -₱{{ number_format($expense->total, 2) }}
                                    </p>
                                </div>
                            @empty
                                <div class="text-center py-16 text-gray-400">
                                    <div class="text-5xl mb-4">📭</div>
                                    <p>No expenses yet</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="px-6 md:px-8 py-6 bg-gray-50 border-t flex justify-center">
                            <a href="{{ route('user.expenses') }}"
                               class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-2xl transition text-sm md:text-base">
                                + Add New Expense
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('expenseChart');
            const labels = @json($categoryLabels ?? []);
            const data = @json($categoryAmounts ?? []);

            const total = data.reduce((sum, val) => sum + Number(val), 0);

            // Update total spent
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
                        borderWidth: 6,
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

            // Custom Legend (same style as Expense page)
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
