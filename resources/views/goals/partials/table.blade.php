
{{-- Goals Table --}}
<div
    class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl overflow-hidden"
    x-data="{
        createModal: false,
        viewModal: false,
        addMoneyModal: false,

        selectedGoal: null,

        openViewModal(goal) {
            this.selectedGoal = goal
            this.viewModal = true
        },

        openAddMoneyModal(goal) {
            this.selectedGoal = goal
            this.addMoneyModal = true
        },

        closeModal() {
            this.viewModal = false
            this.addMoneyModal = false

            setTimeout(() => {
                this.selectedGoal = null
            }, 200)
        },
    }"
>
    {{-- Header --}}
    <div class="px-6 md:px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Goals
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Track your savings goals and progress.
            </p>
        </div>

        <button
            type="button"
            @click="createModal = true"
            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-2xl flex items-center gap-2 shadow-lg shadow-blue-200 transition-all active:scale-95"
        >
            + Goal
        </button>
    </div>

    @if ($goals->count() > 0)
        {{-- Desktop Table --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Goal
                        </th>
                        <th class="px-6 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Target Date
                        </th>
                        <th class="px-6 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Progress
                        </th>
                        <th class="px-6 md:px-8 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Saved
                        </th>
                        <th class="px-6 md:px-8 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Target
                        </th>
                        <th class="px-6 md:px-8 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 md:px-8 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach ($goals as $goal)
                        <tr class="hover:bg-gray-50 transition-all duration-200">

                            {{-- Goal Info --}}
                            <td class="px-6 md:px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    @if ($goal->image)
                                        <img src="{{ asset('storage/' . $goal->image) }}"
                                             alt="{{ $goal->goal_name }}"
                                             class="w-12 h-12 rounded-2xl object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-2xl">
                                            🎯
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $goal->goal_name }}</h4>
                                        @if ($goal->description)
                                            <p class="text-xs text-gray-500 line-clamp-1">{{ Str::limit($goal->description, 60) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Target Date --}}
                            <td class="px-6 md:px-8 py-5 whitespace-nowrap">
                                <div class="text-sm text-gray-700">
                                    {{ $goal->target_date ? \Carbon\Carbon::parse($goal->target_date)->format('M d, Y') : 'No deadline' }}
                                </div>
                            </td>

                            {{-- Progress --}}
                            <td class="px-6 md:px-8 py-5">
                                <div class="w-full max-w-[180px]">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-medium text-gray-600">{{ $goal->progress }}%</span>
                                        @if ($goal->isCompleted())
                                            <span class="text-emerald-600 font-medium">Completed</span>
                                        @endif
                                    </div>
                                    <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-300"
                                             style="width: {{ $goal->progress }}%"></div>
                                    </div>
                                </div>
                            </td>

                            {{-- Saved Amount --}}
                            <td class="px-6 md:px-8 py-5 text-right whitespace-nowrap">
                                <span class="text-lg font-bold text-emerald-600">
                                    ₱{{ number_format($goal->saved_amount, 2) }}
                                </span>
                            </td>

                            {{-- Target Amount --}}
                            <td class="px-6 md:px-8 py-5 text-right whitespace-nowrap">
                                <span class="text-lg font-semibold text-gray-700">
                                    ₱{{ number_format($goal->target_amount, 2) }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 md:px-8 py-5 text-center">
                                <span
                                    class="inline-flex items-center px-4 py-1.5 rounded-2xl text-sm font-medium
                                    {{
                                        $goal->status === 'Completed'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : ($goal->status === 'Cancelled'
                                                ? 'bg-red-100 text-red-700'
                                                : ($goal->status === 'Failed'
                                                    ? 'bg-rose-100 text-rose-700'
                                                    : 'bg-blue-100 text-blue-700'))
                                    }}"
                                >
                                    {{ $goal->status ?? 'In Progress' }}
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="px-6 md:px-8 py-5 text-right whitespace-nowrap">
                                <button
                                    @click='openViewModal(@json($goal))'
                                    class="px-5 py-2.5 rounded-2xl bg-gray-900 text-white text-sm font-medium hover:bg-black transition-all duration-200"
                                >
                                    View
                                </button>

                                <button
                                    @click='openAddMoneyModal(@json($goal))'
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-500 text-white text-sm font-semibold hover:from-emerald-600 hover:to-green-600 active:scale-95 transition-all duration-200 shadow-lg shadow-emerald-200/60">
                                    Add
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="lg:hidden divide-y divide-gray-100">
            @foreach ($goals as $goal)
                <div class="p-6 space-y-5 hover:bg-gray-50 transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            @if ($goal->image)
                                <img src="{{ asset('storage/' . $goal->image) }}"
                                     alt="{{ $goal->goal_name }}"
                                     class="w-14 h-14 rounded-2xl object-cover">
                            @else
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-3xl">
                                    🎯
                                </div>
                            @endif>

                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">{{ $goal->goal_name }}</h3>
                                <p class="text-sm text-gray-500">
                                    {{ $goal->target_date ? \Carbon\Carbon::parse($goal->target_date)->format('M d, Y') : 'No deadline' }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xl font-bold text-emerald-600">
                                ₱{{ number_format($goal->saved_amount, 2) }}
                            </div>
                            <div class="text-xs text-gray-400">of ₱{{ number_format($goal->target_amount, 2) }}</div>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div>
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-medium">{{ $goal->progress }}% Complete</span>
                            @if ($goal->isCompleted())
                                <span class="text-emerald-600 font-semibold">✓ Completed</span>
                            @endif
                        </div>
                        <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all"
                                 style="width: {{ $goal->progress }}%"></div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span
                            class="inline-flex items-center px-4 py-1.5 rounded-2xl text-sm font-medium
                            {{
                                $goal->status === 'Completed'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : ($goal->status === 'Cancelled'
                                        ? 'bg-red-100 text-red-700'
                                        : ($goal->status === 'Failed'
                                            ? 'bg-rose-100 text-rose-700'
                                            : 'bg-blue-100 text-blue-700'))
                            }}"
                        >
                            {{ $goal->status ?? 'In Progress' }}
                        </span>

                        <div>
                            <button
                                @click='openViewModal(@json($goal))'
                                class="px-5 py-2.5 rounded-2xl bg-gray-900 text-white text-sm font-medium hover:bg-black transition-all duration-200"
                            >
                                View
                            </button>

                            <button
                                @click='openAddMoneyModal(@json($goal))'
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-500 text-white text-sm font-semibold hover:from-emerald-600 hover:to-green-600 active:scale-95 transition-all duration-200 shadow-lg shadow-emerald-200/60">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        {{-- Empty State --}}
        <div class="p-16 text-center">
            <div class="text-7xl mb-6 opacity-75">
                🎯
            </div>

            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                No Goals Found
            </h3>

            <p class="text-gray-500 max-w-xs mx-auto">
                Create your first savings goal to start tracking your progress.
            </p>
        </div>
    @endif

    @include('goals.partials.create-modal')
    @include('goals.partials.view-modal')
    @include('goals.partials.add-money-modal')
</div>
