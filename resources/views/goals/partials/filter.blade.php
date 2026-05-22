
{{-- Goals Filter --}}
<form
    class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl p-6 md:p-8"
    method="get"
    action="{{ route('user.goals.filter') }}"
>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Search -->
        <div>
            <label class="text-sm font-medium text-gray-600 mb-2 block">
                Search
            </label>

            <div class="relative">
                <input
                    type="text"
                    x-model="search"
                    placeholder="Search goal..."
                    class="w-full pl-11 pr-5 py-3 bg-white border-gray-200 rounded-2xl focus:border-emerald-500 focus:ring-emerald-500 outline-none transition-all">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</div>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600 mb-2 block">
                Type
            </label>

            <select name="status" class="w-full rounded-2xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 px-5 py-3">
                <option value="">All Status</option>

                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                    Completed
                </option>

                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>

                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                    Failed
                </option>
            </select>
        </div>

        {{-- Actions --}}
        <div class="flex items-end gap-3">
            <button
                type="reset"
                class="flex-1 px-5 py-3 rounded-2xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all active:scale-95"
            >Reset</button>

            <button
                type="submit"
                class="flex-1 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-2xl px-5 py-3 transition-all shadow-lg shadow-emerald-100 active:scale-95"
            >Apply</button>
        </div>
    </div>
</form>
