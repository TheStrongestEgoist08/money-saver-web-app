
{{-- Cancel Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Payment Cancelled</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto bg-white shadow-xl rounded-3xl p-10 text-center">
            <div class="mb-8">
                <div class="mx-auto w-24 h-24 bg-red-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2z" />
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-3">Payment Cancelled</h1>
            <p class="text-gray-600 mb-10">
                No charges were made. You can try again anytime.
            </p>

            <a href="{{ route('premium.choose') }}"
               class="block w-full py-4 bg-gray-900 hover:bg-black text-white font-semibold rounded-2xl transition mb-4">
                Try Again
            </a>

            <a href="{{ route('user.dashboard') }}"
               class="block text-gray-500 hover:text-gray-700">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
