
{{-- Transaction Table --}}
<div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl overflow-hidden">
    {{-- Header --}}
    <div class="px-6 md:px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">
                Transaction History
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                View all your recent wallet transactions.
            </p>
        </div>

        <div class="bg-indigo-50 text-indigo-700 px-5 py-2 rounded-2xl text-sm font-semibold">
            {{ $transactions->count() }} Total Transactions
        </div>
    </div>

    @if ($transactions->count() > 0)
        {{-- Desktop Table --}}
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Date & Time
                        </th>

                        <th class="px-6 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Type
                        </th>

                        <th class="px-6 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Description
                        </th>

                        <th class="px-6 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Wallet
                        </th>

                        <th class="px-6 md:px-8 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>

                        <th class="px-6 md:px-8 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach ($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-all duration-200">

                            {{-- Date --}}
                            <td class="px-6 md:px-8 py-5 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $transaction->created_at->format('F d, Y') }}
                                </div>

                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $transaction->created_at->format('h:i A') }}
                                </div>
                            </td>

                            {{-- Type --}}
                            <td class="px-6 md:px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg
                                        {{ strtolower($transaction->type) == 'balance added' || strtolower($transaction->type) == 'wallet created'
                                            ? 'bg-emerald-100 text-emerald-600'
                                            : (strtolower($transaction->type) == 'expense' || strtolower($transaction->type) == 'wallet deleted'
                                                ? 'bg-red-100 text-red-600'
                                                : 'bg-indigo-100 text-indigo-600') }}"
                                    >
                                        @if (strtolower($transaction->type) == 'balance added' || strtolower($transaction->type) == 'wallet created')
                                            ✚
                                        @elseif (strtolower($transaction->type) == 'expense' || strtolower($transaction->type) == 'wallet deleted')
                                            ⛔
                                        @else
                                            ⇄
                                        @endif
                                    </div>

                                    <div>
                                        <h4 class="font-semibold text-gray-900 capitalize">
                                            {{ $transaction->type }}
                                        </h4>

                                        <p class="text-xs text-gray-500 mt-1">
                                            Transaction Type
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Description --}}
                            <td class="px-6 md:px-8 py-5">
                                <div class="max-w-md">
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ $transaction->description ?: 'No description provided.' }}
                                    </p>
                                </div>
                            </td>

                            {{-- Wallet --}}
                            <td class="px-6 md:px-8 py-5 whitespace-nowrap">
                                <span class="px-4 py-2 rounded-2xl bg-indigo-50 text-indigo-700 text-sm font-medium">
                                    {{ $transaction->wallet?->wallet_name ?? 'N/A' }}
                                </span>
                            </td>

                            {{-- Amount --}}
                            <td class="px-6 md:px-8 py-5 text-right whitespace-nowrap">
                                <span class="text-lg font-bold
                                    {{ strtolower($transaction->type) === 'expense'
                                        ? 'text-red-600'
                                        : 'text-emerald-600' }}">

                                    {{ strtolower($transaction->type) === 'expense' ? '-' : '+' }}
                                    ₱{{ number_format($transaction->amount, 2) }}
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="px-6 md:px-8 py-5 text-right whitespace-nowrap">
                                <button
                                    @click='openModal(@json($transaction))'
                                    class="px-5 py-2.5 rounded-2xl bg-gray-900 text-white text-sm font-medium hover:bg-black transition-all duration-200"
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="lg:hidden divide-y divide-gray-100">
            @foreach ($transactions as $transaction)
                <div class="p-5 space-y-4 hover:bg-gray-50 transition-all duration-200">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-lg
                                {{ strtolower($transaction->type) == 'balance added' || strtolower($transaction->type) == 'wallet created'
                                    ? 'bg-emerald-100 text-emerald-600'
                                    : (strtolower($transaction->type) == 'expense' || strtolower($transaction->type) == 'wallet deleted'
                                        ? 'bg-red-100 text-red-600'
                                        : 'bg-indigo-100 text-indigo-600') }}"
                            >
                                @if (strtolower($transaction->type) == 'balance added' || strtolower($transaction->type) == 'wallet created')
                                    ✚
                                @elseif (strtolower($transaction->type) == 'expense' || strtolower($transaction->type) == 'wallet deleted')
                                    ⛔
                                @else
                                    ⇄
                                @endif
                            </div>

                            <div>
                                <h3 class="font-bold text-gray-900 capitalize">
                                    {{ $transaction->type }}
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $transaction->created_at->format('F d, Y • h:i A') }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-lg font-bold
                                {{ strtolower($transaction->type) === 'expense'
                                    ? 'text-red-600'
                                    : 'text-emerald-600' }}">

                                {{ strtolower($transaction->type) === 'expense' ? '-' : '+' }}
                                ₱{{ number_format($transaction->amount, 2) }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $transaction->description ?: 'No description provided.' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="px-4 py-2 rounded-2xl bg-indigo-50 text-indigo-700 text-sm font-medium">
                            {{ $transaction->wallet?->wallet_name ?? 'N/A' }}
                        </span>

                        <button
                            @click='openModal(@json($transaction))'
                            class="px-5 py-2.5 rounded-2xl bg-gray-900 text-white text-sm font-medium hover:bg-black transition-all duration-200"
                        >
                            View
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="p-16 text-center">
            <div class="text-7xl mb-5">
                📄
            </div>

            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                No Transactions Found
            </h3>

            <p class="text-gray-500">
                No transaction records available yet.
            </p>
        </div>
    @endif
</div>
