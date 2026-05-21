
{{-- Transaction Filters --}}
<div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-6 md:p-8">
    <form
        method="GET"
        class="grid grid-cols-1 md:grid-cols-2 gap-4"
        action="{{ route('user.transactions.filter') }}"
    >
        {{-- Type --}}
        <div>
            <label class="text-sm font-medium text-gray-600 mb-2 block">
                Type
            </label>

            <select name="type" class="w-full rounded-2xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 px-5 py-3">
                <option value="">All Types</option>

                <option value="Balance Added" {{ request('type') == 'Balance Added' ? 'selected' : '' }}>
                    Balance Added
                </option>

                <option value="Expense" {{ request('type') == 'Expense' ? 'selected' : '' }}>
                    Expense
                </option>

                <option value="Transfer" {{ request('type') == 'Transfer' ? 'selected' : '' }}>
                    Transfer
                </option>

                <option value="Wallet Created" {{ request('type') == 'Wallet Created' ? 'selected' : '' }}>
                    Wallet Created
                </option>

                <option value="Wallet Deleted" {{ request('type') == 'Wallet Deleted' ? 'selected' : '' }}>
                    Wallet Deleted
                </option>
            </select>
        </div>

        {{-- Actions --}}
        <div class="flex items-end gap-3">
            <button
                type="clear"
                class="flex-1 px-5 py-3 rounded-2xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all"
            >Reset</button>

            <button
                type="submit"
                class="flex-1 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-2xl px-5 py-3 transition-all shadow-lg shadow-emerald-100"
            >Apply</button>
        </div>
    </form>
</div>
