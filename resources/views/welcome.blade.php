
{{-- Landing Page --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'PesoFlow') }} - Smart Spending Better Saving</title>
    <link rel="icon" type="image/svg+xml" href='data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><rect width="36" height="36" rx="18" fill="%23fff"/><path fill="%23027e04" d="M14.18 13.8V16h9.45a5.26 5.26 0 0 0 .08-.89 4.72 4.72 0 0 0-.2-1.31zM14.18 19.7h5.19a4.28 4.28 0 0 0 3.5-1.9H14.18zM19.37 10.51H14.18V12h8.37a4.21 4.21 0 0 0-3.18-1.49zM17.67 2a16 16 0 1 0 16 16A16 16 0 0 0 17.67 2zm10.5 15.8H25.7a6.87 6.87 0 0 1-6.33 4.4H14.18v6.54a1.25 1.25 0 1 1-2.5 0V17.8H8.76a.9.9 0 1 1 0-1.8h2.92V13.8H8.76a.9.9 0 1 1 0-1.8h2.92V9.26A1.25 1.25 0 0 1 12.93 8h6.44a6.84 6.84 0 0 1 6.15 4h2.65a.9.9 0 0 1 0 1.8H26.09a6.91 6.91 0 0 1 .12 1.3 6.8 6.8 0 0 1-.06.9h2a.9.9 0 0 1 0 1.8z"/></svg>'>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>

<body class="bg-white text-zinc-900 min-h-screen overflow-x-hidden">

    {{-- Navigation --}}
    <header class="border-b border-emerald-100 bg-white/90 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-emerald-500/30">
                    ₱
                </div>

                <div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-emerald-700">
                        PesoFlow
                    </h1>
                    <p class="text-xs text-zinc-500 -mt-1 hidden sm:block">
                        Smart Spending Better Saving
                    </p>
                </div>
            </div>

            {{-- Navigation Buttons --}}
            @if (Route::has('login'))
                <nav class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <a href="{{ url('/user/dashboard') }}"
                           class="px-5 py-2.5 sm:px-6 sm:py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition-all shadow-lg shadow-emerald-500/20 text-sm sm:text-base">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2.5 sm:px-5 sm:py-3 rounded-2xl text-zinc-600 hover:text-emerald-700 transition text-sm sm:text-base font-medium">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                           class="px-5 py-2.5 sm:px-7 sm:py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition-all shadow-lg shadow-emerald-500/20 active:scale-95 text-sm sm:text-base">
                            Get Started
                        </a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    {{-- Hero Section --}}
    <section class="relative overflow-hidden">
        {{-- Background Decorations --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-lime-300 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                {{-- Left Content --}}
                <div>
                    <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium mb-8">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Track expenses. Manage wallets. Achieve goals.
                    </div>

                    <h1 class="text-5xl lg:text-7xl font-black tracking-tight leading-none">
                        Smart Spending.
                        <span class="text-emerald-600">
                            Better Saving.
                        </span>
                    </h1>

                    <p class="mt-8 text-lg lg:text-xl text-zinc-600 leading-relaxed max-w-xl">
                        Manage your expenses, track multiple wallets, set financial goals,
                        and generate insightful reports — all in one powerful platform.
                    </p>

                    {{-- Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 mt-10">
                        @guest
                            <a href="{{ route('register') }}"
                               class="px-9 py-5 rounded-3xl bg-emerald-600 hover:bg-emerald-700 text-white text-lg font-semibold transition-all active:scale-95 shadow-2xl shadow-emerald-500/30 text-center">
                                Start Saving Now
                            </a>
                        @endguest

                        <a href="#features"
                           class="px-9 py-5 rounded-3xl border-2 border-emerald-200 hover:border-emerald-500 text-emerald-700 font-semibold transition-all text-center">
                            Explore Features
                        </a>
                    </div>

                    {{-- Features --}}
                    <div class="grid grid-cols-2 gap-5 mt-12">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center">
                                📊
                            </div>

                            <div>
                                <p class="font-semibold">
                                    Expense Management
                                </p>

                                <p class="text-sm text-zinc-500">
                                    Add, categorize & track expenses
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center">
                                👛
                            </div>

                            <div>
                                <p class="font-semibold">
                                    Wallet Management
                                </p>

                                <p class="text-sm text-zinc-500">
                                    Multiple wallets in one place
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center">
                                🎯
                            </div>

                            <div>
                                <p class="font-semibold">
                                    Goals Management
                                </p>

                                <p class="text-sm text-zinc-500">
                                    Set and monitor savings goals
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center">
                                🤖
                            </div>

                            <div>
                                <p class="font-semibold">
                                    AI Financial Assistant
                                </p>

                                <p class="text-sm text-zinc-500">
                                    Smart insights & guidance
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Card --}}
                <div class="relative">
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-emerald-200 rounded-full blur-3xl opacity-60"></div>

                    <div class="relative bg-white border border-emerald-100 rounded-[40px] shadow-2xl p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <p class="text-zinc-500 text-sm">
                                    Total Savings
                                </p>

                                <h2 class="text-4xl font-black text-emerald-600">
                                    ₱12,450
                                </h2>
                            </div>

                            <div class="w-14 h-14 rounded-3xl bg-emerald-100 flex items-center justify-center text-2xl">
                                💰
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="p-5 rounded-3xl bg-emerald-50 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">
                                        Emergency Fund
                                    </p>
                                    <p class="text-sm text-zinc-500">
                                        75% Completed
                                    </p>
                                </div>

                                <span class="text-emerald-600 font-bold">
                                    ₱7,500
                                </span>
                            </div>

                            <div class="p-5 rounded-3xl bg-lime-50 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">
                                        Wallet Balance
                                    </p>
                                    <p class="text-sm text-zinc-500">
                                        Multiple accounts
                                    </p>
                                </div>

                                <span class="text-lime-600 font-bold">
                                    Active
                                </span>
                            </div>

                            <div class="p-5 rounded-3xl bg-emerald-50 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">
                                        Financial Reports
                                    </p>
                                    <p class="text-sm text-zinc-500">
                                        Custom date range
                                    </p>
                                </div>
                                <span class="text-emerald-600 font-bold">
                                    Generate
                                </span>
                            </div>
                        </div>

                        <div class="mt-8 p-5 rounded-3xl bg-gradient-to-r from-emerald-600 to-lime-500 text-white">
                            <p class="text-sm opacity-90">
                                Financial Quote
                            </p>

                            <h3 class="text-2xl font-bold leading-tight mt-2">
                                “Know where your money goes.
                                Grow where your future flows.”
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-24 bg-emerald-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-4xl font-black tracking-tight">
                    Everything You Need To
                    Manage Your Finances
                </h2>

                <p class="text-zinc-600 text-lg mt-5">
                    Built to help students, workers, and everyday users
                    track expenses, manage wallets, and achieve financial goals.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-[32px] p-8 shadow-lg border border-emerald-100">
                    <div class="text-5xl mb-6">📊</div>

                    <h3 class="text-2xl font-bold mb-4">
                        Expense Management
                    </h3>

                    <p class="text-zinc-600 leading-relaxed">
                        Easily add, edit, categorize, and monitor your daily expenses.
                    </p>
                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-lg border border-emerald-100">
                    <div class="text-5xl mb-6">👛</div>

                    <h3 class="text-2xl font-bold mb-4">
                        Wallet Management
                    </h3>

                    <p class="text-zinc-600 leading-relaxed">
                        Organize and track multiple wallets with real-time balance updates.
                    </p>
                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-lg border border-emerald-100">
                    <div class="text-5xl mb-6">🎯</div>

                    <h3 class="text-2xl font-bold mb-4">
                        Goals & Reports
                    </h3>

                    <p class="text-zinc-600 leading-relaxed">
                        Set savings goals, track progress, and generate detailed financial reports.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="bg-gradient-to-r from-emerald-600 to-lime-500 rounded-[40px] p-12 text-white shadow-2xl">

                <h2 class="text-4xl lg:text-5xl font-black leading-tight">
                    Start Building Better
                    Financial Habits Today
                </h2>

                <p class="mt-6 text-lg text-emerald-50 max-w-2xl mx-auto">
                    Secure authentication, premium subscription via PayMongo,
                    AI assistant, and powerful financial tools — all in PesoFlow.
                </p>

                @guest
                    <a href="{{ route('register') }}"
                       class="inline-block mt-10 px-10 py-5 rounded-3xl bg-white text-emerald-700 font-bold text-lg hover:scale-105 transition-all">
                        Create Free Account
                    </a>
                @endguest

            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-emerald-100 py-10 bg-white">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="font-black text-emerald-700 text-2xl">
                    PesoFlow
                </h3>

                <p class="text-zinc-500 text-sm">
                    Smart Spending Better Saving
                </p>
            </div>

            <p class="text-zinc-500 text-sm">
                © {{ date('Y') }} PesoFlow. Built for smarter financial growth.
            </p>
        </div>
    </footer>
</body>
</html>
