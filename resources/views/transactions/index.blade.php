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
            @include('transactions.partials.table')

            {{-- Pagination --}}
            <div>{{ $transactions->links() }}</div>
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
