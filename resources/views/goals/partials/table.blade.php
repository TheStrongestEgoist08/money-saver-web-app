
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
            setTimeout(() => this.selectedGoal = null, 200)
        },
    }"
>
    @include('goals.partials.create-modal')
    @include('goals.partials.view-modal')
    @include('goals.partials.add-money-modal')

    {{-- Header --}}
    <div class="px-6 md:px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Goals</h2>
            <p class="text-sm text-gray-500 mt-1">Track your savings goals and progress.</p>
        </div>

        <button
            type="button"
            @click="createModal = true"
            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-2xl flex items-center gap-2 shadow-lg shadow-blue-200 transition-all active:scale-95 whitespace-nowrap"
        >
            + New Goal
        </button>
    </div>

    @if ($goals->count() > 0)

        {{-- Desktop & Tablet Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-5/12">Goal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Target Date</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Saved</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Target</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($goals as $goal)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                @if ($goal->image)
                                    <img src="{{ asset('storage/' . $goal->image) }}"
                                         alt="{{ $goal->goal_name }}"
                                         class="w-12 h-12 rounded-2xl object-cover flex-shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-2xl flex-shrink-0">
                                        🎯
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-semibold text-gray-900 truncate">{{ $goal->goal_name }}</h4>
                                    @if ($goal->description)
                                        <p class="text-xs text-gray-500 line-clamp-2 mt-0.5">{{ $goal->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-700">
                            {{ $goal->target_date ? \Carbon\Carbon::parse($goal->target_date)->format('M d, Y') : 'No deadline' }}
                        </td>
                        <td class="px-6 py-5">
                            <div class="w-full max-w-[160px] mx-auto">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-medium text-gray-600">{{ $goal->progress }}%</span>
                                </div>
                                <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-300"
                                         style="width: {{ $goal->progress }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right whitespace-nowrap font-bold text-emerald-600">
                            ₱{{ number_format($goal->saved_amount, 2) }}
                        </td>
                        <td class="px-6 py-5 text-right whitespace-nowrap text-gray-700">
                            ₱{{ number_format($goal->target_amount, 2) }}
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-2xl text-sm font-medium
                                {{ $goal->status === 'completed' ? 'bg-emerald-100 text-emerald-700' :
                                  ($goal->status === 'cancelled' ? 'bg-red-100 text-red-700' :
                                  ($goal->status === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700')) }}">
                                {{ ucfirst($goal->status ?? 'Active') }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-center gap-2">
                                <button @click='openViewModal(@json($goal))'
                                    class="px-5 py-2.5 rounded-2xl bg-gray-900 text-white text-sm font-medium hover:bg-black transition-all">
                                    View
                                </button>

                                @if ($goal->status === 'active')
                                    <button @click='openAddMoneyModal(@json($goal))'
                                        class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-500 text-white text-sm font-semibold hover:from-emerald-600 hover:to-green-600 transition-all">
                                        Add
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden divide-y divide-gray-100">
            @foreach ($goals as $goal)
            <div class="p-6 space-y-6">

                <!-- Goal Header -->
                <div class="flex gap-4">
                    @if ($goal->image)
                        <img src="{{ asset('storage/' . $goal->image) }}"
                            alt="{{ $goal->goal_name }}"
                            class="w-14 h-14 rounded-2xl object-cover flex-shrink-0">
                    @else
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-3xl flex-shrink-0">
                            🎯
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-lg text-gray-900 leading-tight">{{ $goal->goal_name }}</h3>
                        @if ($goal->description)
                            <p class="text-sm text-gray-500 line-clamp-2 mt-1">{{ $goal->description }}</p>
                        @endif
                    </div>
                </div>

                <!-- Amounts & Date -->
                <div class="flex justify-between items-end">
                    <div>
                        <div class="text-xs text-gray-500">Target Date</div>
                        <div class="text-sm font-medium text-gray-700">
                            {{ $goal->target_date ? \Carbon\Carbon::parse($goal->target_date)->format('M d, Y') : 'No deadline' }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-bold text-emerald-600">
                            ₱{{ number_format($goal->saved_amount, 2) }}
                        </div>
                        <div class="text-xs text-gray-400">
                            of ₱{{ number_format($goal->target_amount, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Progress -->
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium">{{ $goal->progress }}% Complete</span>
                        @if ($goal->isCompleted())
                            <span class="text-emerald-600 font-semibold">✓ Completed</span>
                        @endif
                    </div>
                    <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-300"
                            style="width: {{ $goal->progress }}%"></div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-2xl text-sm font-medium
                        {{ $goal->status === 'completed' ? 'bg-emerald-100 text-emerald-700' :
                        ($goal->status === 'cancelled' ? 'bg-red-100 text-red-700' :
                        ($goal->status === 'failed' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700')) }}">
                        {{ ucfirst($goal->status ?? 'Active') }}
                    </span>
                </div>

                <!-- Fixed Mobile Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button
                        @click='openViewModal(@json($goal))'
                        class="flex-1 py-3.5 rounded-2xl bg-gray-900 text-white text-sm font-medium hover:bg-black transition-all active:scale-[0.97]">
                        View
                    </button>

                    @if ($goal->status === 'active')
                        <button
                            @click='openAddMoneyModal(@json($goal))'
                            class="flex-1 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-500 text-white text-sm font-semibold hover:from-emerald-600 hover:to-green-600 transition-all active:scale-[0.97]">
                            Add
                        </button>
                    @endif
                </div>

            </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="p-16 text-center">
            <div class="text-7xl mb-6 opacity-75">🎯</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No Goals Yet</h3>
            <p class="text-gray-500 max-w-xs mx-auto">Create your first savings goal to start tracking your progress.</p>
        </div>
    @endif
</div>
