
{{-- Transaction Details Modal --}}
<div
    x-show="show"
    x-transition
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 p-4 capitalize backdrop-blur-md"
    style="display: none;"
    x-init="
        $watch('show', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        })
    "
>
    <div class="absolute inset-0" @click="show = false"></div>

    <!-- Modal Content -->
    <div
        @click.away="show = false"
        class="bg-white rounded-3xl w-full max-w-2xl max-h-[92vh] flex flex-col shadow-2xl relative z-10 overflow-hidden"
    >
        {{-- Header --}}
        <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Transaction Details</h2>
                <p class="text-sm text-gray-500 mt-1">Complete transaction information</p>
            </div>

            <button
                @click="show = false"
                class="w-10 h-10 rounded-2xl hover:bg-gray-100 text-gray-500 transition-all flex items-center justify-center"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>
            </button>
        </div>

        {{-- Scrollable Body --}}
        <div class="flex-1 p-8 overflow-y-auto space-y-6">

            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Type</p>
                    <h3 class="text-lg font-bold text-gray-900 capitalize" x-text="transaction.type"></h3>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Amount</p>
                    <h3 class="text-lg font-bold text-emerald-600"
                        x-text="'₱' + Number(transaction.amount).toLocaleString('en-PH', {minimumFractionDigits: 2})">
                    </h3>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Wallet</p>
                    <h3 class="text-lg font-bold text-gray-900" x-text="transaction.wallet?.wallet_name ?? 'N/A'"></h3>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Date</p>
                    <h3 class="text-lg font-bold text-gray-900" x-text="formatDate(transaction.created_at)"></h3>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-gray-50 rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Description</p>
                <p class="text-gray-700 leading-relaxed" x-text="transaction.description || 'No description provided.'"></p>
            </div>

            <!-- ==================== EXPENSE DETAILS ==================== -->
            <template x-if="transaction.type === 'Expense' && transaction.metadata">
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 rounded-3xl p-6">
                    <div class="flex justify-between items-center mb-5 border-b border-emerald-200 pb-4">
                        <p class="text-xs uppercase tracking-widest text-emerald-700 font-medium">Expense Details</p>
                        <span class="font-bold text-emerald-600 text-lg"
                            x-text="'₱' + Number(transaction.metadata.total_amount).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                    </div>

                    <template x-if="transaction.metadata.expenses?.length">
                        <div class="space-y-4">

                            <!-- Header Row -->
                            <div class="flex items-center text-xs uppercase tracking-widest text-gray-500 font-medium border-b border-emerald-200 pb-2">
                                <div class="flex-1">Item</div>
                                <div class="w-16 text-center">Qty</div>
                                <div class="w-28 text-right">Price</div>
                                <div class="w-28 text-right">Total</div>
                            </div>

                            <!-- Expense Items -->
                            <template x-for="(expense, i) in transaction.metadata.expenses" :key="i">
                                <div class="flex items-center text-sm border-b border-emerald-100 pb-3 last:border-b-0 last:pb-0">

                                    <!-- Item Name -->
                                    <div class="flex-1 font-medium text-gray-800">
                                        <span x-text="expense.expense_name"></span>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="w-16 text-center text-gray-600">
                                        <span x-text="expense.quantity"></span>
                                    </div>

                                    <!-- Price -->
                                    <div class="w-28 text-right text-gray-600">
                                        <span x-text="'₱' + Number(expense.price).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                                    </div>

                                    <!-- Total -->
                                    <div class="w-28 text-right font-bold text-emerald-600">
                                        <span x-text="'₱' + Number(expense.total).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- Receipt Footer -->
                    <div class="mt-6 pt-5 border-t border-emerald-200 flex justify-between items-center text-base">
                        <span class="font-semibold text-gray-700">TOTAL</span>
                        <span class="font-bold text-emerald-600"
                            x-text="'₱' + Number(transaction.metadata.total_amount).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                    </div>
                </div>
            </template>

            <!-- ==================== TRANSFER DETAILS ==================== -->
            <template x-if="transaction.type === 'Transfer' && transaction.metadata">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-3xl p-6">

                    <!-- Header -->
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                            <span class="text-2xl">🔄</span>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-blue-600 font-semibold">WALLET TRANSFER</p>
                            <p class="text-lg font-bold text-gray-800">Money Moved Successfully</p>
                        </div>
                    </div>

                    <!-- From → To Grid (3 Columns) -->
                    <div class="grid grid-cols-3 items-center bg-white rounded-2xl p-6 border border-gray-100">

                        <!-- FROM -->
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">FROM</p>
                            <p class="font-semibold text-gray-800 text-xl"
                               x-text="transaction.metadata.from_wallet"></p>
                        </div>

                        <!-- Arrow -->
                        <div class="flex justify-center text-4xl text-blue-400">
                            →
                        </div>

                        <!-- TO -->
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">TO</p>
                            <p class="font-semibold text-gray-800 text-xl"
                               x-text="transaction.metadata.to_wallet"></p>
                        </div>
                    </div>

                    <!-- Transferred Amount -->
                    <div class="mt-6 pt-6 border-t border-blue-100 flex justify-between items-center">
                        <span class="text-gray-600 font-medium">Transferred Amount</span>
                        <span class="text-2xl font-bold text-emerald-600"
                            x-text="'₱' + Number(transaction.amount).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>
                    </div>
                </div>
            </template>

        </div>
    </div>
</div>
