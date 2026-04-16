<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl md:text-3xl text-gray-800 tracking-tight">Expense Reports</h2>
    </x-slot>

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8">

            {{-- Summary Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6" id="summaryCards">
                <!-- Populated by JS -->
            </div>

            {{-- Mobile-Friendly One-Row Filters --}}
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100">
                <div class="px-5 md:px-8 py-6 bg-gray-50 border-b">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">

                        <!-- Search -->
                        <div>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="searchInput"
                                    placeholder="Search expense..."
                                    class="w-full pl-11 pr-4 py-4 md:py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all text-base">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">🔍</div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div>
                            <select id="typeSelect" class="w-full px-5 py-4 md:py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all text-base">
                                <option value="">All Categories</option>
                                <option value="Food">🍔 Food</option>
                                <option value="Groceries">🛒 Groceries</option>
                                <option value="Transportation">🚕 Transportation</option>
                                <option value="Bills">📄 Bills</option>
                                <option value="Utilities">💡 Utilities</option>
                                <option value="Personal Care">🧼 Personal Care</option>
                                <option value="Household">🧽 Household</option>
                                <option value="Health">💊 Health</option>
                                <option value="Clothing">👕 Clothing</option>
                                <option value="Entertainment">🎮 Entertainment</option>
                                <option value="Education">📚 Education</option>
                                <option value="Savings">💰 Savings</option>
                                <option value="Gifts">🎁 Gifts</option>
                                <option value="Maintenance">🔧 Maintenance</option>
                                <option value="Subscriptions">📱 Subscriptions</option>
                                <option value="Others">📌 Others</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <input
                                type="text"
                                id="dateRange"
                                placeholder="Date range"
                                class="w-full px-5 py-4 md:py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 outline-none transition-all text-base">
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3">
                            <button onclick="clearAllFilters()"
                                    class="flex-1 px-5 py-4 md:py-3.5 text-sm font-medium text-gray-600 hover:text-red-600 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all">
                                Clear
                            </button>
                            <button onclick="loadReports()"
                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-semibold py-4 md:py-3.5 rounded-2xl transition-all shadow-md">
                                Apply
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white shadow-xl rounded-3xl p-5 md:p-8 border border-gray-100">
                <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4 md:mb-6">Expenses by Category</h3>
                <div class="h-[320px] md:h-[400px]">
                    <canvas id="expenseBarChart"></canvas>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100">
                <div class="px-5 md:px-8 py-5 border-b bg-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h3 class="text-lg font-semibold text-gray-800">All Expenses</h3>
                    <span id="resultCount" class="text-sm text-gray-500"></span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100" id="expenseTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-5 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-5 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Expense</th>
                                <th class="px-5 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Description</th>
                                <th class="px-5 md:px-8 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-sm" id="tableBody">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script>
    let expenseChart;
    let datePicker;

    document.addEventListener('DOMContentLoaded', () => {
        datePicker = flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
        });

        loadReports();
    });

    function clearAllFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('typeSelect').value = '';
        if (datePicker) datePicker.clear();
        loadReports();
    }

    function loadReports() {
        const formData = new FormData();

        const search = document.getElementById('searchInput').value.trim();
        const type = document.getElementById('typeSelect').value;
        const dateRange = document.getElementById('dateRange').value;

        if (search) formData.append('search', search);
        if (type) formData.append('type', type);
        if (dateRange) {
            const dates = dateRange.split(' to ');
            if (dates[0]) formData.append('date_from', dates[0]);
            if (dates[1]) formData.append('date_to', dates[1]);
        }

        document.getElementById('tableBody').innerHTML = `
            <tr><td colspan="5" class="text-center py-16">
                <div class="animate-spin w-8 h-8 border-4 border-indigo-200 border-t-indigo-600 rounded-full mx-auto"></div>
            </td></tr>`;

        fetch("{{ route('user.reports.filter') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            updateSummaryCards(data.expenses);
            updateTable(data.expenses);
            updateHorizontalBarChart(data.summary);
        })
        .catch(err => {
            console.error(err);
            alert('Failed to load data.');
        });
    }

    function updateSummaryCards(expenses) {
        const total = expenses.reduce((sum, exp) => sum + parseFloat(exp.total || 0), 0);
        const count = expenses.length;
        const avg = count ? total / count : 0;

        document.getElementById('summaryCards').innerHTML = `
            <div class="bg-white shadow-xl rounded-3xl p-5 md:p-6 text-center">
                <p class="text-gray-500 text-sm">Total Spent</p>
                <p class="text-3xl md:text-4xl font-bold text-gray-900 mt-1">₱${total.toLocaleString('en-PH', {minimumFractionDigits: 2})}</p>
            </div>
            <div class="bg-white shadow-xl rounded-3xl p-5 md:p-6 text-center">
                <p class="text-gray-500 text-sm">Transactions</p>
                <p class="text-3xl md:text-4xl font-bold text-gray-900 mt-1">${count}</p>
            </div>
            <div class="bg-white shadow-xl rounded-3xl p-5 md:p-6 text-center">
                <p class="text-gray-500 text-sm">Average</p>
                <p class="text-3xl md:text-4xl font-bold text-gray-900 mt-1">₱${avg.toLocaleString('en-PH', {minimumFractionDigits: 2})}</p>
            </div>
        `;
    }

    function updateTable(expenses) {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';
        document.getElementById('resultCount').textContent = `${expenses.length} records`;

        if (expenses.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="py-16 text-center text-gray-500">No expenses found.</td></tr>`;
            return;
        }

        expenses.forEach(exp => {
            const row = `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 md:px-8 py-4 whitespace-nowrap">${new Date(exp.created_at).toLocaleDateString('en-PH')}</td>
                    <td class="px-5 md:px-8 py-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-2xl bg-indigo-100 text-indigo-700">${exp.type}</span>
                    </td>
                    <td class="px-5 md:px-8 py-4 font-medium">${exp.expense_name}</td>
                    <td class="px-5 md:px-8 py-4 text-gray-600 hidden sm:table-cell">${exp.description ? exp.description.substring(0, 50) + '...' : '-'}</td>
                    <td class="px-5 md:px-8 py-4 text-right font-semibold">₱${parseFloat(exp.total).toLocaleString('en-PH', {minimumFractionDigits: 2})}</td>
                </tr>`;
            tbody.innerHTML += row;
        });
    }

    function updateHorizontalBarChart(summary) {
        const ctx = document.getElementById('expenseBarChart');
        if (expenseChart) expenseChart.destroy();

        expenseChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: summary.map(s => s.type),
                datasets: [{
                    label: 'Total Amount Spent',
                    data: summary.map(s => parseFloat(s.total_amount)),
                    backgroundColor: '#6366f1',
                    borderColor: '#4f46e5',
                    borderWidth: 2,
                    borderRadius: 12,
                    barThickness: 35,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                        position: 'bottom',
                        labels: { boxWidth: 14, padding: 15, font: { size: 13 } }
                    }
                },
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });
    }
</script>
