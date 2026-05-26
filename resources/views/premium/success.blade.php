
{{-- Success Page --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Payment Successful</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto bg-white shadow-xl rounded-3xl p-10 text-center">
            <div class="mb-8">
                <div class="mx-auto w-24 h-24 bg-green-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <h1 class="text-4xl font-bold text-gray-900 mb-3">Thank You!</h1>
            <p class="text-gray-600 text-lg mb-8">
                Your payment was successful. You now have access to all AI features.
            </p>

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 mb-8 text-left">
                <p class="text-sm text-gray-500">You are now a Premium member</p>
                <p class="text-green-600 font-medium">Enjoy ad-free experience and smart AI tools</p>
            </div>

            <a href="{{ route('user.suggestions') }}"
               class="block w-full py-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-2xl transition">
                Go to AI Assistant
            </a>

            <a href="{{ route('user.dashboard') }}"
               class="block mt-4 text-gray-500 hover:text-gray-700">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
