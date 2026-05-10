
<!-- Transfer Balance Modal -->
<div
    x-data="{
        fromWalletId: null,
        toWalletId: null,
        amount: null,
        wallets: @js($wallets->mapWithKeys(fn($w) => [$w->id => $w->balance])),

        get maxAmount() {
            if (!this.fromWalletId) return 0;
            return this.wallets[this.fromWalletId] || 0;
        }
    }"
    x-show="openTransferModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60"
    style="display: none;">

    <div @click.away="openTransferModal = false"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

        <div class="px-8 pt-6 pb-4 border-b">
            <h3 class="text-2xl font-semibold text-gray-900">Transfer Balance</h3>
        </div>

        <form method="POST" action="{{ route('user.wallets.transfer') }}">
            @csrf

            <div class="p-8 space-y-6">

                <!-- From Wallet -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Wallet</label>
                    <select
                        name="from_wallet_id"
                        x-model="fromWalletId"
                        required
                        class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:border-blue-500 outline-none">
                        <option value="">Select source wallet...</option>
                        @foreach($wallets as $wallet)
                            <option
                                value="{{ $wallet->id }}"
                                :disabled="toWalletId == {{ $wallet->id }}">
                                {{ $wallet->wallet_name ?? ucfirst($wallet->wallet_type) }}
                                — ₱{{ number_format($wallet->balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- To Wallet -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Wallet</label>
                    <select
                        name="to_wallet_id"
                        x-model="toWalletId"
                        required
                        class="w-full px-5 py-4 border border-gray-200 rounded-2xl focus:border-blue-500 outline-none">
                        <option value="">Select destination wallet...</option>
                        @foreach($wallets as $wallet)
                            <option
                                value="{{ $wallet->id }}"
                                :disabled="fromWalletId == {{ $wallet->id }}">
                                {{ $wallet->wallet_name ?? ucfirst($wallet->wallet_type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Amount to Transfer
                        <span x-show="fromWalletId" class="text-emerald-600 font-medium">
                            (Max: ₱<span x-text="Number(maxAmount).toLocaleString('en-PH', {minimumFractionDigits: 2})"></span>)
                        </span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 text-2xl font-medium">₱</span>
                        <input
                            type="number"
                            name="amount"
                            step="0.01"
                            min="0.01"
                            :max="maxAmount"
                            x-model.number="amount"
                            placeholder="0.00"
                            required
                            class="w-full pl-12 pr-6 py-5 border border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none text-3xl font-semibold">
                    </div>
                </div>
            </div>

            <div class="p-6 border-t flex gap-3">
                <button
                    type="button"
                    @click="openTransferModal = false; fromWalletId = null; toWalletId = null"
                    class="flex-1 py-4 text-gray-600 font-medium rounded-2xl hover:bg-gray-100 transition-colors">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl transition-all">
                    Transfer Now
                </button>
            </div>
        </form>
    </div>
</div>
