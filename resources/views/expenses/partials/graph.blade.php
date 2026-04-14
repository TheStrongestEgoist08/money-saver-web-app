
{{-- Graph --}}
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('expenseChart');
        let labels = @json($categoryLabels ?? []);
        let data = @json($categoryAmounts ?? []);

        const total = data.reduce((sum, val) => sum + Number(val), 0);

        // Update total amount
        document.getElementById('total-spent').textContent = '₱' + total.toLocaleString('en-US');

        const chart = new Chart(ctx, {
            type: 'doughnut',
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
