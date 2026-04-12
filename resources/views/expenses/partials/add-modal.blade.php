
{{-- Add Expense Modal --}}
<div
    x-show="openAdd"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-md p-4"
    x-data="{
        resetForm() {
            this.expenses = [{ expense_name: '', type: '', quantity: 1, price: '', description: '' }];
        },

        closeModal() {
            this.resetForm();
            openAdd = false;
        }
    }"
>

    <div @click.away="closeModal()"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-auto overflow-hidden"
         style="max-height: 90vh;">

        <!-- Header -->
        <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex-shrink-0">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">New Expense</h2>
                    <p class="text-sm text-gray-500">Add multiple at once</p>
                </div>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('user.expenses.add') }}"
            class="flex flex-col"
            style="max-height: calc(90vh - 140px);"
            x-data="{
                expenses: [{ expense_name: '', type: '', quantity: 1, price: '', description: '' }],

                addExpense() {
                    this.expenses.push({ expense_name: '', type: '', quantity: 1, price: '', description: '' })
                },

                removeExpense(index) {
                    if (this.expenses.length > 1) this.expenses.splice(index, 1);
                },

                getTotal() {
                    return this.expenses.reduce((sum, item) => {
                        return sum + ((parseFloat(item.quantity) || 1) * (parseFloat(item.price) || 0));
                    }, 0).toFixed(2);
                }
            }"
            @submit="handleSubmit"
        >
            @csrf

            <!-- Scrollable Content Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-5 custom-scroll">

                <template x-for="(expense, index) in expenses" :key="index">
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">

                        <div class="flex justify-between mb-4">
                            <span class="font-medium text-gray-700">Expense #<span x-text="index + 1"></span></span>
                            <button type="button"
                                    @click="removeExpense(index)"
                                    :disabled="expenses.length === 1"
                                    class="text-red-500 hover:text-red-600 text-sm">Remove</button>
                        </div>

                        <div class="space-y-4">
                            <input
                                type="text"
                                x-model="expense.expense_name"
                                :name="`expenses[${index}][expense_name]`"
                                placeholder="What did you spend on?"
                                class="w-full px-4 py-3.5 bg-white border border-gray-200 focus:border-blue-500 rounded-2xl text-base"
                                required>

                            <select
                                x-model="expense.type"
                                :name="`expenses[${index}][type]`"
                                class="w-full px-4 py-3.5 bg-white border border-gray-200 focus:border-blue-500 rounded-2xl text-base"
                                required>
                                <option value="">Select Category</option>
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

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input
                                        type="number"
                                        min="1"
                                        x-model.number="expense.quantity"
                                        :name="`expenses[${index}][quantity]`"
                                        class="w-full px-4 py-3.5 bg-white border border-gray-200 focus:border-blue-500 rounded-2xl">
                                </div>
                                <div>
                                    <input
                                        type="number"
                                        step="0.01"
                                        x-model="expense.price"
                                        :name="`expenses[${index}][price]`"
                                        placeholder="0.00"
                                        class="w-full px-4 py-3.5 bg-white border border-gray-200 focus:border-blue-500 rounded-2xl"
                                        required>
                                </div>
                            </div>

                            <textarea
                                x-model="expense.description"
                                :name="`expenses[${index}][description]`"
                                placeholder="Short description (optional)"
                                rows="2"
                                class="w-full px-4 py-3.5 bg-white border border-gray-200 focus:border-blue-500 rounded-2xl resize-none"></textarea>
                        </div>

                        <div class="mt-4 text-right text-sm text-gray-600">
                            Subtotal: <span class="font-medium text-gray-900"
                            x-text="`₱${((parseFloat(expense.quantity)||1) * (parseFloat(expense.price)||0)).toFixed(2)}`"></span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Fixed Bottom Section -->
            <div class="p-6 border-t border-gray-100 flex-shrink-0 space-y-4 bg-white">
                <button
                    type="button"
                    @click="addExpense()"
                    class="w-full py-3.5 border-2 border-dashed border-blue-300 hover:border-blue-400 text-blue-600 font-medium rounded-2xl flex items-center justify-center gap-2">
                    + Add Another Expense
                </button>

                <div class="bg-blue-50 rounded-2xl p-4 flex justify-between items-center">
                    <span class="font-medium text-gray-700">Total</span>
                    <span class="text-2xl font-bold text-blue-700" x-text="`₱${getTotal()}`"></span>
                </div>

                <div class="flex gap-3">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="flex-1 py-4 bg-gray-100 hover:bg-gray-200 rounded-2xl font-medium">Cancel</button>
                    <button
                        type="submit"
                        class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl">
                        Save All
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
