
{{-- View Expense Modal --}}
<div
    x-show="openView"
    class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-md z-50">

    <div @click.away="openView = false"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-gray-100">

        <!-- Gradient Header -->
        <div class="h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

        <div class="px-7 py-6">
            <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Expense Details</h2>
        </div>

        <div class="px-7 pb-8 space-y-6">
            <div class="space-y-5">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 font-medium">Name</span>
                    <span class="font-semibold text-gray-900 text-lg" x-text="selected?.name"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 font-medium">Category</span>
                    <span class="font-semibold text-gray-900" x-text="selected?.type"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 font-medium">Quantity</span>
                    <span class="font-semibold text-gray-900" x-text="selected?.quantity ?? '—'"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 font-medium">Unit Price</span>
                    <span class="font-semibold text-gray-900">₱<span x-text="selected?.price"></span></span>
                </div>

                <!-- Total Highlight -->
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100 rounded-2xl p-5 mt-6">
                    <div class="flex justify-between items-end">
                        <span class="text-emerald-700 font-medium">Total Amount</span>
                        <span class="text-3xl font-bold text-emerald-600">₱<span x-text="selected?.total"></span></span>
                    </div>
                </div>
            </div>

            <div>
                <span class="text-gray-500 font-medium block mb-2">Description</span>
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 min-h-[90px] text-gray-700 leading-relaxed">
                    <span x-text="selected?.description || 'No description provided.'"></span>
                </div>
            </div>

            <div class="flex justify-between text-sm pt-2">
                <span class="text-gray-500">Date Added</span>
                <span class="font-medium text-gray-900" x-text="selected?.date"></span>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-7 py-6 border-t bg-gray-50 rounded-b-3xl">
            <button
                @click="openView = false"
                class="w-full py-4 bg-gray-900 hover:bg-black text-white font-semibold rounded-2xl transition-all active:scale-95">
                Close
            </button>
        </div>
    </div>
</div>
