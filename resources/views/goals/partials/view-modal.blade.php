
{{-- View Goal Modal --}}
<div
    x-show="viewModal"
    x-transition.opacity
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 md:p-6 capitalize"
>

    {{-- Backdrop --}}
    <div
        @click="closeModal()"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
    ></div>

    {{-- Modal --}}
    <div
        x-show="viewModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden max-h-[95vh] flex flex-col"
    >

        {{-- Header --}}
        <div class="px-6 md:px-8 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Goal Details
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    View your savings goal progress.
                </p>
            </div>

            <button
                type="button"
                @click="closeModal()"
                class="w-10 h-10 rounded-2xl hover:bg-gray-100 flex items-center justify-center text-gray-500 transition"
            >
                ✕
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto px-6 md:px-8 py-6 space-y-8">

            {{-- Top Info --}}
            <div class="flex flex-col md:flex-row gap-6">

                {{-- Image --}}
                <div class="flex-shrink-0">
                    <template x-if="selectedGoal?.image">
                        <img
                            :src="'/storage/' + selectedGoal.image"
                            :alt="selectedGoal.goal_name"
                            class="w-full md:w-60 h-60 rounded-3xl object-cover border border-gray-100"
                        >
                    </template>

                    <template x-if="!selectedGoal?.image">
                        <div class="w-full md:w-60 h-60 rounded-3xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-7xl">
                            🎯
                        </div>
                    </template>
                </div>

                {{-- Goal Details --}}
                <div class="flex-1 space-y-5">

                    <div>
                        <h3
                            x-text="selectedGoal?.goal_name"
                            class="text-3xl font-bold text-gray-900"
                        ></h3>

                        <p
                            x-text="selectedGoal?.description || 'No description provided.'"
                            class="mt-3 text-gray-500 leading-relaxed"
                        ></p>
                    </div>

                    {{-- Status --}}
                    <div>
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-2xl text-sm font-semibold"

                            :class="{
                                'bg-emerald-100 text-emerald-700': selectedGoal?.status === 'Completed',
                                'bg-red-100 text-red-700': selectedGoal?.status === 'Cancelled',
                                'bg-rose-100 text-rose-700': selectedGoal?.status === 'Failed',
                                'bg-amber-100 text-amber-700': selectedGoal?.status === 'In Progress'
                            }"
                            x-text="selectedGoal?.status || 'In Progress'"
                        ></span>
                    </div>

                    {{-- Amount Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Saved --}}
                        <div class="p-5 rounded-3xl bg-emerald-50 border border-emerald-100">
                            <p class="text-sm text-emerald-700 font-medium mb-1">
                                Saved Amount
                            </p>

                            <h4
                                class="text-3xl font-bold text-emerald-600"
                                x-text="'₱' + Number(selectedGoal?.saved_amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2})"
                            ></h4>
                        </div>

                        {{-- Target --}}
                        <div class="p-5 rounded-3xl bg-indigo-50 border border-indigo-100">
                            <p class="text-sm text-indigo-700 font-medium mb-1">
                                Target Amount
                            </p>

                            <h4
                                class="text-3xl font-bold text-indigo-600"
                                x-text="'₱' + Number(selectedGoal?.target_amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2})"
                            ></h4>
                        </div>

                    </div>

                    {{-- Deadline --}}
                    <div class="flex items-center gap-3 text-gray-600">
                        <span class="text-2xl">📅</span>

                        <div>
                            <p class="text-sm text-gray-400">
                                Target Date
                            </p>

                            <p
                                class="font-semibold"
                                x-text="
                                    selectedGoal?.target_date
                                        ? new Date(selectedGoal.target_date).toLocaleDateString('en-US', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric'
                                        })
                                        : 'No deadline'
                                "
                            ></p>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Progress --}}
            <div class="space-y-3">

                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-bold text-gray-900">
                        Progress
                    </h4>

                    <span
                        class="text-lg font-bold text-indigo-600"
                        x-text="Math.min(
                            100,
                            ((selectedGoal?.saved_amount || 0) / (selectedGoal?.target_amount || 1)) * 100
                        ).toFixed(0) + '%'"
                    ></span>
                </div>

                <div class="h-4 bg-gray-100 rounded-full overflow-hidden">
                    <div
                        class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-500"
                        :style="`width: ${
                            Math.min(
                                100,
                                ((selectedGoal?.saved_amount || 0) / (selectedGoal?.target_amount || 1)) * 100
                            )
                        }%`"
                    ></div>
                </div>

            </div>

        </div>

        {{-- Footer --}}
        <div class="px-6 md:px-8 py-5 border-t border-gray-100 flex justify-end">
            <button
                type="button"
                @click="closeModal()"
                class="px-6 py-3 rounded-2xl bg-gray-900 hover:bg-black text-white font-semibold transition-all"
            >
                Close
            </button>
        </div>

    </div>
</div>
