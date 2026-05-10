
<!-- Add New Wallet Modal -->
<div
    x-show="openAddWallet"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
    style="display: none;">

    <div @click.away="openAddWallet = false"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

        <!-- Header -->
        <div class="px-8 pt-6 pb-4 border-b">
            <h3 class="text-2xl font-semibold text-gray-900">Add New Wallet</h3>
        </div>

        <form method="POST" action="{{ route('user.wallet.newWallet') }}">
            @csrf

            <div class="p-8 space-y-6">
                <!-- Wallet Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Wallet Name (Optional)</label>
                    <input
                        type="text"
                        name="wallet_name"
                        placeholder="e.g. Main Wallet, GCash, BPI Savings"
                        class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none">
                </div>

                <!-- Wallet Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Wallet Type</label>
                    <select
                        name="wallet_type"
                        required
                        class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none">
                        <option value="wallet">Physical Wallet</option>
                        <option value="e-wallet">E-Wallet (GCash, PayMaya, etc.)</option>
                        <option value="bank">Bank Account</option>
                    </select>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t flex gap-3">
                <button
                    type="button"
                    @click="openAddWallet = false"
                    class="flex-1 py-4 text-gray-600 font-medium rounded-2xl hover:bg-gray-100 transition-colors">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="flex-1 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl transition-all">
                    Create Wallet
                </button>
            </div>
        </form>
    </div>
</div>
