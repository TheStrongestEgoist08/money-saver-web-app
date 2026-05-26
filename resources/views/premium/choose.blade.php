<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Choose Premium Plan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">
                    Unlock Premium Experience
                </h1>
                <p class="text-xl text-gray-600 max-w-xl mx-auto">
                    Get an ad-free experience and access to your AI Companion.
                    Choose your preferred duration below.
                </p>
            </div>

            <!-- Plans -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                <!-- Weekly -->
                <div class="bg-white border rounded-3xl p-8 text-center hover:shadow-lg transition">
                    <h3 class="text-2xl font-bold text-gray-800">Weekly</h3>

                    <div class="my-6">
                        <span class="text-5xl font-bold">₱99</span>
                        <span class="text-gray-500">/week</span>
                    </div>

                    <ul class="text-left space-y-2 mb-8 text-gray-700">
                        <li>✔ No Ads</li>
                        <li>✔ AI Companion Access</li>
                    </ul>

                    <form method="POST" action="{{ route('premium.upgrade') }}">
                        @csrf
                        <input type="hidden" name="plan" value="week">
                        <button class="w-full bg-gray-900 text-white py-3 rounded-2xl hover:bg-black transition">
                            Choose Weekly
                        </button>
                    </form>
                </div>

                <!-- Monthly (Featured) -->
                <div class="bg-white border-2 border-green-600 rounded-3xl p-8 text-center shadow-xl relative">

                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-green-600 text-white text-sm px-6 py-1 rounded-full">
                        MOST POPULAR
                    </div>

                    <h3 class="text-2xl font-bold text-gray-800">Monthly</h3>

                    <div class="my-6">
                        <span class="text-5xl font-bold">₱199</span>
                        <span class="text-gray-500">/month</span>
                    </div>

                    <ul class="text-left space-y-2 mb-8 text-gray-700">
                        <li>✔ No Ads</li>
                        <li>✔ AI Companion Access</li>
                    </ul>

                    <form method="POST" action="{{ route('premium.upgrade') }}">
                        @csrf
                        <input type="hidden" name="plan" value="month">
                        <button class="w-full bg-green-600 text-white py-3 rounded-2xl hover:bg-green-700 transition">
                            Choose Monthly
                        </button>
                    </form>
                </div>

                <!-- Quarterly -->
                <div class="bg-white border rounded-3xl p-8 text-center hover:shadow-lg transition">
                    <h3 class="text-2xl font-bold text-gray-800">Quarterly</h3>

                    <div class="my-6">
                        <span class="text-5xl font-bold">₱499</span>
                        <span class="text-gray-500">/3 months</span>
                    </div>

                    <ul class="text-left space-y-2 mb-8 text-gray-700">
                        <li>✔ No Ads</li>
                        <li>✔ AI Companion Access</li>
                    </ul>

                    <form method="POST" action="{{ route('premium.upgrade') }}">
                        @csrf
                        <input type="hidden" name="plan" value="quarter">
                        <button class="w-full bg-gray-900 text-white py-3 rounded-2xl hover:bg-black transition">
                            Choose Quarterly
                        </button>
                    </form>
                </div>

                <!-- Yearly -->
                <div class="bg-white border rounded-3xl p-8 text-center hover:shadow-lg transition">

                    <h3 class="text-2xl font-bold text-gray-800">Yearly</h3>

                    <div class="my-6">
                        <span class="text-5xl font-bold">₱1,499</span>
                        <span class="text-gray-500">/year</span>
                    </div>

                    <ul class="text-left space-y-2 mb-8 text-gray-700">
                        <li>✔ No Ads</li>
                        <li>✔ AI Companion Access</li>
                    </ul>

                    <form method="POST" action="{{ route('premium.upgrade') }}">
                        @csrf
                        <input type="hidden" name="plan" value="year">
                        <button class="w-full bg-gray-900 text-white py-3 rounded-2xl hover:bg-black transition">
                            Choose Yearly
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
