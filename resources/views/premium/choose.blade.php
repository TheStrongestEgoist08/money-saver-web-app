

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Choose Premium Plan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">
                    Unlock Full AI Power
                </h1>
                <p class="text-xl text-gray-600 max-w-lg mx-auto">
                    Get intelligent financial advice, smart predictions, and ad-free experience.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto px-4">

                <!-- Weekly Plan -->
                <div class="bg-white border border-gray-200 rounded-3xl p-8 hover:border-green-400 transition-all duration-300">
                    <div class="text-center">
                        <span class="inline-block px-5 py-2 bg-gray-100 text-gray-600 text-sm font-semibold rounded-2xl mb-6">Starter</span>
                        <h3 class="text-3xl font-bold text-gray-800">Weekly</h3>

                        <div class="mt-6 mb-8">
                            <span class="text-6xl font-bold">₱129</span>
                            <span class="text-gray-500 text-lg">/week</span>
                        </div>

                        <ul class="space-y-4 text-left mb-10">
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span>AI Financial Suggestions</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span>Basic Insights & Analysis</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span><strong>No Ads</strong></span>
                            </li>
                        </ul>

                        <form action="{{ route('premium.upgrade') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" value="week">
                            <button type="submit"
                                    class="w-full py-4 bg-gray-900 hover:bg-black text-white font-semibold rounded-2xl transition-all">
                                Choose Weekly
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Monthly Plan - Most Popular -->
                <div class="bg-white border-2 border-green-600 rounded-3xl p-8 relative scale-105 shadow-xl">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-green-600 text-white text-sm font-bold px-8 py-2 rounded-2xl">
                        MOST POPULAR
                    </div>

                    <div class="text-center">
                        <span class="inline-block px-5 py-2 bg-green-100 text-green-700 text-sm font-semibold rounded-2xl mb-6">Recommended</span>
                        <h3 class="text-3xl font-bold text-gray-800">Monthly</h3>

                        <div class="mt-6 mb-8">
                            <span class="text-6xl font-bold">₱249</span>
                            <span class="text-gray-500 text-lg">/month</span>
                        </div>

                        <ul class="space-y-4 text-left mb-10">
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span>Full AI Access</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span>Smart Budget Predictions</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span>Advanced Reports</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span><strong>No Ads</strong></span>
                            </li>
                        </ul>

                        <form action="{{ route('premium.upgrade') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" value="month">
                            <button type="submit"
                                    class="w-full py-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-2xl transition-all">
                                Choose Monthly
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Quarterly Plan -->
                <div class="bg-white border border-gray-200 rounded-3xl p-8 hover:border-green-400 transition-all duration-300">
                    <div class="text-center">
                        <span class="inline-block px-5 py-2 bg-amber-100 text-amber-700 text-sm font-semibold rounded-2xl mb-6">Save 13%</span>
                        <h3 class="text-3xl font-bold text-gray-800">Quarterly</h3>

                        <div class="mt-6 mb-8">
                            <span class="text-6xl font-bold">₱649</span>
                            <span class="text-gray-500 text-lg">/3 months</span>
                        </div>

                        <ul class="space-y-4 text-left mb-10">
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span>Everything in Monthly</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span>Priority AI Responses</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span><strong>No Ads</strong></span>
                            </li>
                        </ul>

                        <form action="{{ route('premium.upgrade') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" value="quarter">
                            <button type="submit"
                                    class="w-full py-4 bg-gray-900 hover:bg-black text-white font-semibold rounded-2xl transition-all">
                                Choose Quarterly
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Yearly Plan -->
                <div class="bg-white border border-gray-200 rounded-3xl p-8 hover:border-green-400 transition-all duration-300">
                    <div class="text-center">
                        <span class="inline-block px-5 py-2 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-2xl mb-6">Best Value</span>
                        <h3 class="text-3xl font-bold text-gray-800">Yearly</h3>

                        <div class="mt-6 mb-8">
                            <span class="text-6xl font-bold">₱1,999</span>
                            <span class="text-gray-500 text-lg">/year</span>
                        </div>

                        <ul class="space-y-4 text-left mb-10">
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span>All Premium Features</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span>Save ₱989 per year</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-green-500 mt-0.5">✔</span>
                                <span><strong>No Ads Forever</strong></span>
                            </li>
                        </ul>

                        <form action="{{ route('premium.upgrade') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan" value="year">
                            <button type="submit"
                                    class="w-full py-4 bg-gray-900 hover:bg-black text-white font-semibold rounded-2xl transition-all">
                                Choose Yearly
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
