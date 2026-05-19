{{-- User Transactions Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl md:text-3xl text-gray-800 tracking-tight">
            Transactions
        </h2>
    </x-slot>

    <div
        class="py-8 md:py-10"
        x-data="transactionModal()"
    >

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Filters --}}
            @include('transactions.partials.filter')

            {{-- Transactions --}}
            <div class="space-y-6">
                @forelse ($transactions as $type => $dates)
                    @foreach ($dates as $date => $items)
                        <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl overflow-hidden">
                            {{-- Group Header --}}
                            <div class="px-6 md:px-8 py-5 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 capitalize">
                                        {{ $type }}
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                                    </p>
                                </div>

                                <div class="bg-emerald-50 text-emerald-700 px-4 py-2 rounded-2xl text-sm font-semibold">
                                    {{ count($items) }} Transactions
                                </div>
                            </div>

                            {{-- Table --}}
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 md:px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                Time
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
                                        @foreach ($items as $transaction)
                                            <tr class="hover:bg-gray-50 transition-all">
                                                {{-- Time --}}
                                                <td class="px-6 md:px-8 py-5 whitespace-nowrap">
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        {{ $transaction->created_at->format('h:i A') }}
                                                    </div>
                                                </td>

                                                {{-- Description --}}
                                                <td class="px-6 md:px-8 py-5">
                                                    <div class="flex items-start gap-4">
                                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center
                                                            {{ $transaction->type == 'income'
                                                                ? 'bg-emerald-100 text-emerald-600'
                                                                : ($transaction->type == 'expense'
                                                                    ? 'bg-red-100 text-red-600'
                                                                    : 'bg-indigo-100 text-indigo-600') }}">

                                                            @if ($transaction->type == 'income')
                                                                ⬇
                                                            @elseif ($transaction->type == 'expense')
                                                                ⬆
                                                            @else
                                                                ⇄
                                                            @endif
                                                        </div>

                                                        <div>
                                                            <h4 class="font-semibold text-gray-900">
                                                                {{ ucfirst($transaction->type) }}
                                                            </h4>

                                                            <p class="text-sm text-gray-500 mt-1">
                                                                {{ $transaction->description ?: 'No description provided.' }}
                                                            </p>
                                                        </div>
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
                                                    <span class="text-lg font-bold {{ $transaction->type === 'Expense' ? 'text-red-600' : 'text-emerald-600' }}">
                                                        {{ $transaction->type === 'Expense' ? '-' : '+' }}₱{{ number_format($transaction->amount, 2) }}
                                                    </span>
                                                </td>

                                                {{-- Action --}}
                                                <td class="px-6 md:px-8 py-5 text-right whitespace-nowrap">
                                                    <button
                                                        @click='openModal(@json($transaction))'
                                                        class="px-5 py-2.5 rounded-2xl bg-gray-900 text-white text-sm font-medium hover:bg-black transition-all"
                                                    >View</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 p-16 text-center">
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
                @endforelse
            </div>

            {{-- Pagination --}}
            <div>
                {{ $transactions->links() }}
            </div>
        </div>

        {{-- Modal --}}
        @include('transactions.partials.view-modal')
    </div>

    <script>
        function transactionModal() {
            return {
                show: false,
                transaction: {},

                openModal(transaction) {
                    this.transaction = transaction;
                    this.show = true;
                },

                formatDate(date) {
                    return new Date(date).toLocaleString('en-PH', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: 'numeric',
                        minute: '2-digit',
                    });
                }
            }
        }
    </script>
</x-app-layout>
