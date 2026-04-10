<div x-show="openAdd"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

    <div @click.away="openAdd = false"
         class="bg-white rounded-lg p-6 w-full max-w-md">

        <h2 class="text-lg font-bold mb-4">Add Expense</h2>

        <form method="POST" action="{{ route('user.expenses.add') }}">
            @csrf

            <input type="text" name="expense_name" placeholder="Name"
                class="w-full mb-3 rounded border-gray-300" required>

            <select name="type" class="w-full mb-3 rounded border-gray-300" required>
                <option value="">Type</option>
                <option>Food</option>
                <option>Transportation</option>
                <option>Bills</option>
                <option>Clothing</option>
                <option>Entertainment</option>
                <option>Others</option>
            </select>

            <input type="number" name="quantity" placeholder="Quantity (optional)"
                class="w-full mb-3 rounded border-gray-300">

            <input type="number" step="0.01" name="price" placeholder="Price"
                class="w-full mb-3 rounded border-gray-300" required>

            <textarea name="description" placeholder="Description"
                class="w-full mb-3 rounded border-gray-300"></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" @click="openAdd = false"
                    class="px-4 py-2 bg-gray-400 text-white rounded">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>
