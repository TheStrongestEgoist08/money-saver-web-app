
{{-- Add Expense Modal --}}
<div
    x-show="openAdd"
    class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-md z-50">

    <div @click.away="openAdd = false"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-gray-100">

        <!-- Gradient Header -->
        <div class="h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

        <div class="px-7 py-6">
            <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">New Expense</h2>
            <p class="text-gray-500 text-sm mt-1">Fill in the details below</p>
        </div>

        <form method="POST" action="{{ route('user.expenses.add') }}" class="px-7 pb-8 space-y-5">
            @csrf

            <div>
                <input
                    type="text"
                    name="expense_name"
                    placeholder="Expense name"
                    class="w-full px-5 py-4 bg-gray-50 border border-transparent focus:border-blue-400 focus:ring-4 focus:ring-blue-100 rounded-2xl outline-none text-base transition-all placeholder-gray-400"
                    required>
            </div>

            <div>
                <select
                    name="type"
                    class="w-full px-5 py-4 bg-gray-50 border border-transparent focus:border-blue-400 focus:ring-4 focus:ring-blue-100 rounded-2xl outline-none text-base transition-all"
                    required>
                    <option value="">Select category</option>
                    <option value="Food">🍔 Food</option>
                    <option value="Transportation">🚕 Transportation</option>
                    <option value="Bills">📄 Bills</option>
                    <option value="Clothing">👕 Clothing</option>
                    <option value="Entertainment">🎮 Entertainment</option>
                    <option value="Others">📌 Others</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <input
                        type="number"
                        name="quantity"
                        placeholder="Quantity"
                        class="w-full px-5 py-4 bg-gray-50 border border-transparent focus:border-blue-400 focus:ring-4 focus:ring-blue-100 rounded-2xl outline-none transition-all">
                </div>
                <div>
                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        placeholder="Price (₱)"
                        class="w-full px-5 py-4 bg-gray-50 border border-transparent focus:border-blue-400 focus:ring-4 focus:ring-blue-100 rounded-2xl outline-none transition-all"
                        required>
                </div>
            </div>

            <div>
                <textarea
                    name="description"
                    placeholder="Add a short description..."
                    rows="3"
                    class="w-full px-5 py-4 bg-gray-50 border border-transparent focus:border-blue-400 focus:ring-4 focus:ring-blue-100 rounded-2xl outline-none resize-none transition-all placeholder-gray-400"
                ></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button
                    type="button"
                    @click="openAdd = false"
                    class="flex-1 py-4 text-gray-700 font-medium bg-gray-100 hover:bg-gray-200 rounded-2xl transition-all active:scale-95">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="flex-1 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                    Save Expense
                </button>
            </div>
        </form>
    </div>
</div>
