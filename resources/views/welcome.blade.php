
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SaveSmart') }} - Save Money Smarter</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>

<body class="bg-zinc-950 text-white min-h-screen">
    <!-- Navigation -->
    <header class="border-b border-zinc-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 sm:py-6 flex justify-between items-center">

            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-600 rounded-2xl flex items-center justify-center text-lg sm:text-2xl font-bold">₱</div>
                <span class="font-semibold text-xl sm:text-3xl tracking-tighter truncate">MoneySaver</span>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-2 sm:gap-4 flex-wrap justify-end">
                    @auth
                        <a href="{{ url('/user/dashboard') }}" class="px-3 sm:px-6 py-2 sm:py-3 bg-emerald-600 hover:bg-emerald-700 font-medium rounded-2xl transition text-sm sm:text-base whitespace-nowrap">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-2 sm:px-6 py-2 sm:py-3 text-zinc-400 hover:text-white transition text-sm sm:text-base whitespace-nowrap">
                            Log in
                        </a>

                        <a href="{{ route('register') }}" class="px-3 sm:px-8 py-2 sm:py-3 bg-emerald-600 hover:bg-emerald-700 font-semibold rounded-2xl transition active:scale-95 text-sm sm:text-base whitespace-nowrap">
                            Start Now
                        </a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <!-- Section -->
    <section class="max-w-5xl mx-auto px-6 pt-16 pb-20 text-center">
        <div class="max-w-2xl mx-auto space-y-8">

            <div class="inline-flex items-center gap-2 bg-zinc-900 border border-emerald-800 px-5 py-2 rounded-3xl text-sm text-emerald-400">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Simple Savings for Everyone
            </div>

            <h1 class="text-5xl lg:text-6xl font-semibold leading-tight tracking-tighter">
                Save money without the stress
            </h1>

            <p class="text-xl text-zinc-400">
                Easily track your expenses, set realistic saving goals, and watch your money grow every day.
                Made simple for real life.
            </p>

            <!-- Main CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6">
                @guest
                    <a href="{{ route('register') }}"
                       class="px-10 py-5 bg-emerald-600 hover:bg-emerald-700 text-lg font-semibold rounded-3xl transition-all active:scale-95 shadow-lg shadow-emerald-600/30">
                        Get Started Now
                    </a>
                @endguest

                <a href="#how-it-works"
                   class="px-10 py-5 border border-zinc-700 hover:border-zinc-500 text-lg font-medium rounded-3xl transition-all">
                    Learn How It Works
                </a>
            </div>

            <div class="flex justify-center gap-8 text-sm text-zinc-400 pt-4">
                <div class="flex items-center gap-2">
                    <span class="text-emerald-500">✔</span>
                    Secure & Private
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-emerald-500">✔</span>
                    No hidden fees
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works - Simple & Informative -->
    <section id="how-it-works" class="bg-zinc-900 py-20">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-semibold mb-3">How MoneySaver Works</h2>
                <p class="text-zinc-400">Just 3 simple steps to start saving smarter</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-zinc-950 border border-zinc-800 rounded-3xl p-8 text-center">
                    <div class="w-12 h-12 bg-emerald-600/10 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">1</div>
                    <h3 class="text-xl font-semibold mb-3">Track Every Peso</h3>
                    <p class="text-zinc-400">
                        Quickly add your daily expenses and income.
                        See where your money is going in real-time.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-zinc-950 border border-zinc-800 rounded-3xl p-8 text-center">
                    <div class="w-12 h-12 bg-emerald-600/10 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">2</div>
                    <h3 class="text-xl font-semibold mb-3">Set Saving Goals</h3>
                    <p class="text-zinc-400">
                        Create goals like "Emergency Fund" or "New Phone".
                        We'll help you stay on track.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-zinc-950 border border-zinc-800 rounded-3xl p-8 text-center">
                    <div class="w-12 h-12 bg-emerald-600/10 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">3</div>
                    <h3 class="text-xl font-semibold mb-3">Watch Your Money Grow</h3>
                    <p class="text-zinc-400">
                        Get monthly summaries and celebrate your progress.
                        Saving becomes easier and more rewarding.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Simple Trust Footer -->
    <footer class="py-12 border-t border-zinc-800">
        <div class="max-w-5xl mx-auto px-6 text-center text-zinc-500 text-sm">
            Simple • Secure • Built for everyone
        </div>
    </footer>
</body>

</html>
