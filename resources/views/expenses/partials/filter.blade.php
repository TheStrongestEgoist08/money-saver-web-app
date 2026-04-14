<!-- Filters Bar -->
<div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

        <!-- Search -->
        <div>
            <div class="relative">
                <input
                    type="text"
                    x-model="search"
                    placeholder="Search expense name..."
                    class="w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 outline-none transition-all">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">🔍</div>
            </div>
        </div>

        <!-- Category Filter -->
        <div>
            <select
                x-model="filterType"
                class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 outline-none transition-all">
                <option value="">All Categories</option>
                <option value="Food">🍔 Food</option>
                <option value="Groceries">🛒 Groceries</option>
                <option value="Transportation">🚕 Transportation</option>
                <option value="Bills">📄 Bills</option>
                <option value="Utilities">💡 Utilities</option>
                <option value="Personal Care">🧼 Personal Care</option>
                <option value="Household">🧽 Household</option>
                <option value="Health">💊 Health</option>
                <option value="Clothing">👕 Clothing</option>
                <option value="Entertainment">🎮 Entertainment</option>
                <option value="Education">📚 Education</option>
                <option value="Savings">💰 Savings</option>
                <option value="Gifts">🎁 Gifts</option>
                <option value="Maintenance">🔧 Maintenance</option>
                <option value="Subscriptions">📱 Subscriptions</option>
                <option value="Others">📌 Others</option>
            </select>
        </div>

        <!-- Flatpickr Date Range -->
        <div class="flex items-center gap-2">
            <input
                x-ref="datePicker"
                type="text"
                placeholder="Select date or range..."
                class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-blue-400 focus:ring-4 focus:ring-blue-100 outline-none transition-all">

            <button
                @click="clearAllFilters"
                class="px-6 py-3.5 text-sm font-medium text-gray-500 hover:text-red-600 transition-colors whitespace-nowrap">
                Clear All
            </button>
        </div>
    </div>
</div>
