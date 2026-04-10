
<!-- Add Balance Modal -->
<div
    x-show="openAddBalance"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
    style="display: none;">

    <div @click.away="openAddBalance = false"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

        <!-- Modal Header -->
        <div class="px-8 pt-6 pb-4 border-b">
            <h3 class="text-2xl font-semibold text-gray-900">Add Balance</h3>
        </div>

        <form method="POST" action="{{ route('user.balance.add') }}">
            @csrf

            <div class="p-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Amount to Add</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 text-2xl font-medium">₱</span>
                        <input
                            type="number"
                            name="amount"
                            step="0.01"
                            min="1"
                            placeholder="0.00"
                            required
                            class="w-full pl-12 pr-6 py-5 border border-gray-200 rounded-2xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none text-3xl font-semibold">
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t flex gap-3">
                <button
                    type="button"
                    @click="openAddBalance = false"
                    class="flex-1 py-4 text-gray-600 font-medium rounded-2xl hover:bg-gray-100 transition-colors">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="flex-1 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl transition-all">
                    Add Balance
                </button>
            </div>
        </form>
    </div>
</div>
