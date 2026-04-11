
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
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h3 class="text-3xl font-semibold text-gray-900">
                                Good morning, {{ auth()->user()?->name ?? 'User' }} 👋
                            </h3>
                            <p class="text-gray-500 mt-2 text-lg">Here's what's happening with your money today</p>
                        </div>

                        <a href="{{ route('user.balance') }}"
                           class="px-7 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-2xl flex items-center gap-3 hover:from-emerald-700 hover:to-teal-700 transition-all">
                            <span>💰</span>
                            <span>View Full Balance</span>
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="lg:col-span-4">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-8 h-full">
                        <div class="flex items-center justify-between mb-6">
                            <p class="text-gray-500 font-medium">Current Balance</p>
                            <span class="text-2xl">💵</span>
                        </div>
                        <p class="text-5xl font-bold text-gray-900 tracking-tighter">
                            ₱{{ number_format($userBalance ?? 0, 2) }}
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-8 h-full">
                        <div class="flex items-center justify-between mb-6">
                            <p class="text-gray-500 font-medium">Expenses This Month</p>
                            <span class="text-2xl">📊</span>
                        </div>
                        <p class="text-5xl font-bold text-red-600 tracking-tighter">
                            ₱{{ number_format($totalExpensesThisMonth ?? 0, 2) }}
                        </p>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-8 h-full">
                        <div class="flex items-center justify-between mb-6">
                            <p class="text-gray-500 font-medium">Total Transactions</p>
                            <span class="text-2xl">🔢</span>
                        </div>
                        <p class="text-5xl font-bold text-gray-900 tracking-tighter">
                            {{ $totalTransactions ?? 0 }}
                        </p>
                    </div>
                </div>

                <!-- Top Expense Highlight -->
                <div class="lg:col-span-12">
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-100 shadow-xl shadow-gray-100/50 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center text-3xl">
                                🔥
                            </div>
                            <div>
                                <p class="text-red-600 font-semibold text-lg">Highest Expense This Month</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">
                                    {{ $topExpenseCategory ?? 'No expenses yet' }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-gray-500 text-sm">Total Spent</p>
                            <p class="text-4xl font-bold text-red-600 tracking-tighter">
                                ₱{{ number_format($topExpenseAmount ?? 0, 2) }}
                            </p>
                        </div>

                        <a href="{{ route('user.expenses') }}"
                           class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-2xl transition-all whitespace-nowrap">
                            View All Expenses
                        </a>
                    </div>
                </div>

                <!-- Expense Breakdown - Bar Chart -->
                <div class="lg:col-span-12">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Expense Breakdown by Category (This Month)</h3>
                        <div class="h-96">
                            <canvas id="expenseChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="lg:col-span-12">
                    <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl overflow-hidden">

                        <div class="px-8 pt-8 pb-5 border-b flex justify-between items-center">
                            <h3 class="text-2xl font-semibold text-gray-900">Recent Expenses</h3>
                            <a href="{{ route('user.expenses') }}"
                               class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2">
                                View All →
                            </a>
                        </div>

                        <div class="p-8">
                            @forelse($recentExpenses ?? [] as $expense)
                                <div class="flex justify-between items-center py-4 border-b last:border-0">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-gray-100 rounded-2xl flex items-center justify-center text-xl">
                                            @switch($expense->type)
                                                @case('Food') 🍔 @break
                                                @case('Transportation') 🚕 @break
                                                @case('Bills') 📄 @break
                                                @case('Clothing') 👕 @break
                                                @case('Entertainment') 🎮 @break
                                                @case('Others') 📌 @break
                                                @default 📦
                                            @endswitch
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $expense->expense_name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $expense->created_at->format('M d, Y • h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-red-600">
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

                        <div class="px-8 py-6 bg-gray-50 border-t flex justify-center">
                            <a href="{{ route('user.expenses') }}"
                               class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-2xl transition">
                                + Add New Expense
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('expenseChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($categoryLabels ?? []),
                    datasets: [{
                        label: 'Amount Spent',
                        data: @json($categoryAmounts ?? []),
                        backgroundColor: [
                            '#ef4444', '#f59e0b', '#10b981', '#3b82f6',
                            '#8b5cf6', '#ec4899', '#64748b'
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        borderRadius: 8,
                        barThickness: 55,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString('en-US');
                                },
                                font: { size: 14 },
                                padding: 10
                            },
                            title: {
                                display: true,
                                text: 'Amount (₱)',
                                font: { size: 15, weight: '600' },
                                color: '#374151',
                                padding: { top: 10, bottom: 10 }
                            },
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { size: 14 },
                                padding: 12
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'start',
                            labels: {
                                boxWidth: 15,
                                padding: 20,
                                font: { size: 15 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => ' ₱' + context.raw.toLocaleString('en-US')
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
