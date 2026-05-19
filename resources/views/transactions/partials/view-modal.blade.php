
{{-- View Modal --}}
<div
    x-show="show"
    x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
    style="display:none;"
>
    <div @click.away="show = false" class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl">
        {{-- Header --}}
        <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Transaction Details
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Complete transaction information
                </p>
            </div>

            <button @click="show = false" class="w-10 h-10 rounded-2xl hover:bg-gray-100 text-gray-500 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">
                        Type
                    </p>

                    <h3 class="text-lg font-bold text-gray-900 capitalize" x-text="transaction.type">
                    </h3>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">
                        Amount
                    </p>

                    <h3 class="text-lg font-bold text-emerald-600"
                        x-text="'₱' + Number(transaction.amount).toLocaleString('en-PH', {minimumFractionDigits: 2})">
                    </h3>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">
                        Wallet
                    </p>

                    <h3 class="text-lg font-bold text-gray-900" x-text="transaction.wallet?.name ?? 'N/A'">
                    </h3>
                </div>

                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">
                        Date
                    </p>

                    <h3 class="text-lg font-bold text-gray-900" x-text="formatDate(transaction.created_at)">
                    </h3>
                </div>
            </div>

            {{-- Description --}}
            <div class="bg-gray-50 rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">
                    Description
                </p>

                <p class="text-gray-700 leading-relaxed" x-text="transaction.description || 'No description provided.'"></p>
            </div>

            {{-- Metadata --}}
            <template x-if="transaction.metadata">
                <div class="bg-gray-50 rounded-2xl p-5">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-4">
                        Metadata
                    </p>
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap overflow-auto" x-text="JSON.stringify(transaction.metadata, null, 2)"></pre>
                </div>
            </template>
        </div>
    </div>
</div>
