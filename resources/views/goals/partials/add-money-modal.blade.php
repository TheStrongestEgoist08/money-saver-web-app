
{{-- Add Money Modal --}}
<div
    x-show="addMoneyModal"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
    style="display: none;"
    x-cloak
>
    <div
        @click.outside="closeModal()"
        class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden"
    >
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-xl text-gray-900">Add Money to Goal</h3>
            <button
                @click="closeModal()"
                class="text-gray-400 hover:text-gray-600 transition-colors"
            >
                ✕
            </button>
        </div>

        <form
            id="addMoneyForm"
            method="POST"
            action="{{ route('user.goals.add-money') }}"
            class="p-6 space-y-6"
        >
            @csrf

            <input type="hidden" name="goal_id" :value="selectedGoal?.id">

            <!-- Goal Info -->
            <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-2xl">
                <div class="flex-shrink-0">
                    <template x-if="selectedGoal?.image">
                        <img :src="`/storage/${selectedGoal.image}`"
                             class="w-14 h-14 rounded-2xl object-cover">
                    </template>
                    <template x-else>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-3xl">
                            🎯
                        </div>
                    </template>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-lg" x-text="selectedGoal?.goal_name"></p>
                    <p class="text-sm text-gray-500">
                        Current: <span class="font-medium text-emerald-600" x-text="`₱${Number(selectedGoal?.saved_amount).toLocaleString('en-PH', {minimumFractionDigits: 2})}`"></span>
                    </p>
                </div>
            </div>

            <!-- Amount Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Amount to Add (₱)
                </label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">₱</div>
                    <input
                        type="number"
                        name="amount"
                        id="amount"
                        step="0.01"
                        min="1"
                        :max="Number(selectedGoal?.target_amount) - Number(selectedGoal?.saved_amount)"
                        required
                        class="w-full pl-9 pr-4 py-4 text-2xl font-semibold border border-gray-200 rounded-2xl focus:border-emerald-500 focus:ring-emerald-500 outline-none"
                        placeholder="0.00"
                        x-on:input="if (parseFloat(this.value) > (Number(selectedGoal?.target_amount) - Number(selectedGoal?.saved_amount))) {
                            this.value = (Number(selectedGoal?.target_amount) - Number(selectedGoal?.saved_amount)).toFixed(2);
                        }"
                    >
                </div>
                <p class="text-xs text-gray-500 mt-1.5">
                    Maximum allowed:
                    <span class="font-medium text-emerald-600"
                          x-text="`₱${(Number(selectedGoal?.target_amount) - Number(selectedGoal?.saved_amount)).toLocaleString('en-PH', {minimumFractionDigits: 2})}`">
                    </span>
                </p>
            </div>

            <!-- Remaining -->
            <div class="text-sm text-gray-500 bg-amber-50 p-4 rounded-2xl">
                <span class="font-medium">Remaining to target:</span>
                <span class="font-semibold text-gray-700"
                      x-text="`₱${(Number(selectedGoal?.target_amount) - Number(selectedGoal?.saved_amount)).toLocaleString('en-PH', {minimumFractionDigits: 2})}`">
                </span>
            </div>

            <div class="flex gap-3 pt-4">
                <button
                    type="button"
                    @click="closeModal()"
                    class="flex-1 py-4 font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-2xl transition-colors"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="flex-1 py-4 font-semibold text-white bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 rounded-2xl transition-all active:scale-95 shadow-lg shadow-emerald-200"
                >
                    Add Money
                </button>
            </div>
        </form>
    </div>
</div>
