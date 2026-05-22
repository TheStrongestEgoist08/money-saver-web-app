
{{-- Create Goal Modal --}}
<div
    x-show="createModal"
    x-transition.opacity
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 md:p-6"
>

    {{-- Backdrop --}}
    <div
        @click="createModal = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
    ></div>

    {{-- Modal --}}
    <div
        x-show="createModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col"
    >

        {{-- Header --}}
        <div class="px-6 md:px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Create Goal
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Set a savings target and track your progress.
                </p>
            </div>

            <button
                type="button"
                @click="createModal = false"
                class="w-10 h-10 rounded-2xl hover:bg-gray-100 flex items-center justify-center text-gray-500 transition"
            >
                ✕
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto px-6 md:px-8 py-6">

            <form
                action="{{ route('user.goals.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf

                {{-- Goal Name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Goal Name
                    </label>

                    <input
                        type="text"
                        name="goal_name"
                        placeholder="Example: Buy New Laptop"
                        class="w-full rounded-2xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        required
                    >
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Write something about this goal..."
                        class="w-full rounded-2xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm resize-none"
                    ></textarea>
                </div>

                {{-- Amounts --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Target Amount --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Target Amount
                        </label>

                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                ₱
                            </span>

                            <input
                                type="number"
                                step="0.01"
                                min="1"
                                name="target_amount"
                                placeholder="0.00"
                                class="w-full pl-10 rounded-2xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                required
                            >
                        </div>
                    </div>

                    {{-- Saved Amount --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Initial Saved Amount
                        </label>

                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                ₱
                            </span>

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="saved_amount"
                                value="0"
                                placeholder="0.00"
                                class="w-full pl-10 rounded-2xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            >
                        </div>
                    </div>

                </div>

                {{-- Target Date --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Target Date
                    </label>

                    <input
                        type="date"
                        name="target_date"
                        class="w-full rounded-2xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                    >
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Goal Image
                    </label>

                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="w-full rounded-2xl border border-dashed border-gray-300 p-4 text-sm text-gray-500 file:mr-4 file:px-4 file:py-2 file:rounded-xl file:border-0 file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 transition"
                    >
                </div>

                {{-- Footer --}}
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">

                    <button
                        type="reset"
                        @click="createModal = false"
                        class="px-6 py-3 rounded-2xl border border-gray-200 text-gray-700 font-medium hover:bg-gray-100 transition-all"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold shadow-lg shadow-blue-200 transition-all active:scale-95"
                    >
                        Create Goal
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
