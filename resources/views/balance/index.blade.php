
{{-- Balance Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 tracking-tight">
            My Balance
        </h2>
    </x-slot>

    <div class="py-10"
         x-data="{
            openAddBalance: false
         }">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-xl shadow-gray-100/50 rounded-3xl overflow-hidden">

                <!-- Page Header -->
                <div class="px-8 pt-8 pb-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900">Current Balance</h3>
                        <p class="text-gray-500 mt-1">Manage your total available balance</p>
                    </div>

                    <button
                        @click="openAddBalance = true"
                        class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold rounded-2xl flex items-center gap-2 shadow-lg shadow-emerald-200 transition-all active:scale-95">
                        <span class="text-xl leading-none">+</span>
                        Add Balance
                    </button>
                </div>

                <!-- Balance Display -->
                <div class="p-8">
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white rounded-3xl p-10 text-center shadow-inner">
                        <p class="text-emerald-400 text-sm font-medium tracking-widest uppercase mb-2">
                            Available Balance
                        </p>
                        <p class="text-6xl font-bold tracking-tighter">
                            ₱{{ number_format($userBalance, 2) }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Add Balance Modal -->
        @include('balance.partials.add-balance')

    </div>
</x-app-layout>
