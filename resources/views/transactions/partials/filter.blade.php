
{{-- Transaction Filters --}}
<div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-6 md:p-8">
    <form
        method="GET"
        class="grid grid-cols-1 md:grid-cols-4 gap-4"
    >
        {{-- Search --}}
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-gray-600 mb-2 block">
                Search
            </label>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search transaction..."
                class="w-full rounded-2xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 px-5 py-3">
        </div>

        {{-- Type --}}
        <div>
            <label class="text-sm font-medium text-gray-600 mb-2 block">
                Type
            </label>

            <select name="type" class="w-full rounded-2xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 px-5 py-3">
                <option value="">All Types</option>

                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>
                    Income
                </option>

                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>
                    Expense
                </option>

                <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>
                    Transfer
                </option>
            </select>
        </div>

        {{-- Actions --}}
        <div class="flex items-end gap-3">
            <button type="submit"
                    class="flex-1 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-2xl px-5 py-3 transition-all shadow-lg shadow-emerald-100">
                Filter
            </button>

            <a href=""
                class="px-5 py-3 rounded-2xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                Reset
            </a>
        </div>
    </form>
</div>
