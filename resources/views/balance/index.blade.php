
{{-- Balance / Wallets Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 tracking-tight">
            My Wallets
        </h2>
    </x-slot>

    <div class="py-10"
         x-data="{
            openAddWallet: false,
            openAddBalance: false,
            openTransferModal: false,
            selectedWalletId: null
         }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl overflow-hidden">

                <!-- Page Header -->
                <div class="px-8 pt-8 pb-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900">My Wallets</h3>
                        <p class="text-gray-500 mt-1">Manage all your wallets and balances</p>
                    </div>

                    <div class="flex gap-3">
                        <button
                            @click="openAddWallet = true"
                            class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-2xl flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all active:scale-95">
                            <span class="text-xl leading-none">+</span>
                            Wallet
                        </button>

                        <button
                            @click="openTransferModal = true"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-2xl flex items-center gap-2 shadow-lg shadow-blue-200 transition-all active:scale-95">
                            <span class="text-xl leading-none">⇄</span>
                            Transfer
                        </button>
                    </div>
                </div>

                <!-- Wallets List -->
                <div class="p-8">
                    @if($wallets->isEmpty())
                        <div class="text-center py-20 text-gray-400">
                            <p class="text-lg mb-2">You don't have any wallets yet.</p>
                            <p class="text-sm">Create your first wallet to get started.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($wallets as $wallet)
                                <div class="bg-white border border-gray-100 rounded-3xl p-6 hover:shadow-lg transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="font-semibold text-gray-900 text-lg">
                                                {{ $wallet->wallet_name ?? ucfirst($wallet->wallet_type) }}
                                            </p>
                                            <p class="text-sm text-gray-500 capitalize">
                                                {{ $wallet->wallet_type }}
                                            </p>
                                        </div>

                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            {{ $wallet->wallet_type == 'bank' ? 'bg-blue-100 text-blue-700' :
                                            ($wallet->wallet_type == 'e-wallet' ? 'bg-purple-100 text-purple-700' : 'bg-emerald-100 text-emerald-700') }}">
                                            {{ $wallet->wallet_type }}
                                        </span>
                                    </div>

                                    <div class="mt-6">
                                        <p class="text-emerald-400 text-sm font-medium tracking-widest uppercase">
                                            Balance
                                        </p>
                                        <p class="text-4xl font-bold tracking-tighter text-gray-900">
                                            ₱{{ number_format($wallet->balance, 2) }}
                                        </p>
                                    </div>

                                    <div class="mt-6 flex gap-3">
                                        <button
                                            @click="selectedWalletId = {{ $wallet->id }}; openAddBalance = true"
                                            class="flex-1 py-3 text-sm font-semibold border border-emerald-200 hover:bg-emerald-50 text-emerald-700 rounded-2xl transition-colors">
                                            + Add Money
                                        </button>

                                        <!-- Delete Button -->
                                        <form action="{{ route('user.wallet.destroy', $wallet) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this wallet?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="px-5 py-3 text-sm font-semibold border border-red-200 hover:bg-red-50 text-red-600 rounded-2xl transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Modals -->
        @include('balance.partials.add-wallet')
        @include('balance.partials.transfer-balance')
        @include('balance.partials.add-balance')
    </div>
</x-app-layout>
