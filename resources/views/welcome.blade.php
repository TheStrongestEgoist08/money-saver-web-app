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

            {{-- Logo - Only icon on mobile --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-emerald-500/30">
                    ₱
                </div>
                <div class="hidden sm:block">
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-emerald-700">
                        PesoFlow
                    </h1>
                    <p class="text-xs text-zinc-500 -mt-1">
                        Smart Spending Better Saving
                    </p>
                </div>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-2 sm:gap-3">
                    @auth
                        <a href="{{ url('/user/dashboard') }}"
                           class="px-5 py-2.5 sm:px-6 sm:py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition-all shadow-lg shadow-emerald-500/20 text-sm sm:text-base">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2.5 rounded-2xl text-zinc-600 hover:text-emerald-700 transition text-sm sm:text-base font-medium">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                           class="px-4 py-2.5 sm:px-6 sm:py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition-all shadow-lg shadow-emerald-500/20 active:scale-95 text-sm sm:text-base">
                            Get Started
                        </a>
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    {{-- Hero Section --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-lime-300 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                {{-- Left Side --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium mb-8 mx-auto lg:mx-0">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Track expenses. Manage wallets. Achieve goals.
                    </div>

                    <h1 class="text-5xl lg:text-6xl xl:text-7xl font-black tracking-tight leading-none">
                        Smart Spending.
                        <span class="text-emerald-600">Better Saving.</span>
                    </h1>

                    <p class="mt-8 text-lg lg:text-xl text-zinc-600 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Manage your expenses, track multiple wallets, set financial goals,
                        and generate insightful reports — all in one powerful platform.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 mt-10 justify-center lg:justify-start">
                        @guest
                            <a href="{{ route('register') }}"
                               class="px-9 py-5 rounded-3xl bg-emerald-600 hover:bg-emerald-700 text-white text-lg font-semibold transition-all active:scale-95 shadow-2xl shadow-emerald-500/30">
                                Start Saving Now
                            </a>
                        @endguest

                        <a href="#features"
                           class="px-9 py-5 rounded-3xl border-2 border-emerald-200 hover:border-emerald-500 text-emerald-700 font-semibold transition-all">
                            Explore Features
                        </a>
                    </div>
                </div>

                {{-- Right Side --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-white border border-emerald-100 rounded-3xl p-6 hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-3xl mb-4">
                            📊
                        </div>
                        <h3 class="font-semibold text-xl mb-2">Expense Management</h3>
                        <p class="text-zinc-600 text-sm leading-relaxed">
                            Add, categorize & track your daily expenses easily.
                        </p>
                    </div>

                    <div class="bg-white border border-emerald-100 rounded-3xl p-6 hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-3xl mb-4">
                            👛
                        </div>
                        <h3 class="font-semibold text-xl mb-2">Wallet Management</h3>
                        <p class="text-zinc-600 text-sm leading-relaxed">
                            Manage multiple wallets in one place with real-time balance.
                        </p>
                    </div>

                    <div class="bg-white border border-emerald-100 rounded-3xl p-6 hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-3xl mb-4">
                            🎯
                        </div>
                        <h3 class="font-semibold text-xl mb-2">Goals Management</h3>
                        <p class="text-zinc-600 text-sm leading-relaxed">
                            Set savings goals and monitor your progress.
                        </p>
                    </div>

                    <div class="bg-white border border-emerald-100 rounded-3xl p-6 hover:shadow-xl transition-shadow">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-3xl mb-4">
                            🤖
                        </div>
                        <h3 class="font-semibold text-xl mb-2">AI Assistant</h3>
                        <p class="text-zinc-600 text-sm leading-relaxed">
                            Get smart financial insights and guidance.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-24 bg-emerald-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-4xl font-black tracking-tight">
                    Everything You Need To Manage Your Finances
                </h2>
                <p class="text-zinc-600 text-lg mt-5">
                    Built to help students, workers, and everyday users track expenses,
                    manage wallets, and achieve financial goals.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-[32px] p-8 shadow-lg border border-emerald-100">
                    <div class="text-5xl mb-6">📊</div>
                    <h3 class="text-2xl font-bold mb-4">Expense Management</h3>
                    <p class="text-zinc-600 leading-relaxed">
                        Easily add, edit, categorize, and monitor your daily expenses.
                    </p>
                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-lg border border-emerald-100">
                    <div class="text-5xl mb-6">👛</div>
                    <h3 class="text-2xl font-bold mb-4">Wallet Management</h3>
                    <p class="text-zinc-600 leading-relaxed">
                        Organize and track multiple wallets with real-time balance updates.
                    </p>
                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-lg border border-emerald-100">
                    <div class="text-5xl mb-6">🎯</div>
                    <h3 class="text-2xl font-bold mb-4">Goals & Reports</h3>
                    <p class="text-zinc-600 leading-relaxed">
                        Set savings goals, track progress, and generate detailed financial reports.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Video Ads Section --}}
    <section class="py-24 bg-emerald-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-4xl font-black tracking-tight text-zinc-900">
                    Our Latest Ads
                </h2>
                <p class="text-zinc-600 mt-4 text-lg">
                    Watch our promotional videos and discover why PesoFlow is the smartest way to manage your money.
                </p>
            </div>

            <div class="max-w-5xl mx-auto relative group">
                <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-emerald-100">
                    <video
                        id="adVideo"
                        class="w-full aspect-video"
                        autoplay
                        muted
                        loop
                        playsinline>

                        <!-- Replace with your actual ad video -->
                        <source src="YOUR_VIDEO_AD_URL_HERE.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <!-- Video Controls Overlay -->
                <div class="absolute bottom-6 right-6 flex gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <!-- Mute Button -->
                    <button onclick="toggleMute()"
                            id="muteBtn"
                            class="bg-white/90 hover:bg-white shadow-lg p-4 rounded-2xl text-emerald-700 transition">
                        <span id="muteIcon" class="text-2xl">🔇</span>
                    </button>

                    <!-- Fullscreen Button -->
                    <button onclick="toggleFullscreen()"
                            id="fullscreenBtn"
                            class="bg-white/90 hover:bg-white shadow-lg p-4 rounded-2xl text-emerald-700 transition">
                        <span id="fullscreenIcon" class="text-2xl">⛶</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- Premium Plans Section --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black tracking-tight text-gray-900">
                    Upgrade to Premium
                </h2>
                <p class="text-xl text-zinc-600 mt-4 max-w-2xl mx-auto">
                    Get an ad-free experience and unlock your AI Companion.
                    Choose the plan that works best for you.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                <!-- Weekly -->
                <div class="bg-white border border-zinc-200 rounded-3xl p-8 text-center hover:shadow-xl transition-all">
                    <h3 class="text-2xl font-bold text-gray-800">Weekly</h3>
                    <div class="my-6">
                        <span class="text-5xl font-bold">₱99</span>
                        <span class="text-gray-500">/week</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8 text-gray-700">
                        <li class="flex items-center gap-2">✔ No Ads</li>
                        <li class="flex items-center gap-2">✔ AI Companion Access</li>
                    </ul>
                    <form method="POST" action="{{ route('premium.upgrade') }}">
                        @csrf
                        <input type="hidden" name="plan" value="week">
                        <button class="w-full bg-zinc-900 text-white py-4 rounded-2xl hover:bg-black transition font-semibold">
                            Choose Weekly
                        </button>
                    </form>
                </div>

                <!-- Monthly (Featured) -->
                <div class="bg-white border-2 border-emerald-600 rounded-3xl p-8 text-center shadow-2xl relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-emerald-600 text-white text-sm px-6 py-1 rounded-full font-medium">
                        MOST POPULAR
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Monthly</h3>
                    <div class="my-6">
                        <span class="text-5xl font-bold">₱199</span>
                        <span class="text-gray-500">/month</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8 text-gray-700">
                        <li class="flex items-center gap-2">✔ No Ads</li>
                        <li class="flex items-center gap-2">✔ AI Companion Access</li>
                    </ul>
                    <form method="POST" action="{{ route('premium.upgrade') }}">
                        @csrf
                        <input type="hidden" name="plan" value="month">
                        <button class="w-full bg-emerald-600 text-white py-4 rounded-2xl hover:bg-emerald-700 transition font-semibold">
                            Choose Monthly
                        </button>
                    </form>
                </div>

                <!-- Quarterly -->
                <div class="bg-white border border-zinc-200 rounded-3xl p-8 text-center hover:shadow-xl transition-all">
                    <h3 class="text-2xl font-bold text-gray-800">Quarterly</h3>
                    <div class="my-6">
                        <span class="text-5xl font-bold">₱499</span>
                        <span class="text-gray-500">/3 months</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8 text-gray-700">
                        <li class="flex items-center gap-2">✔ No Ads</li>
                        <li class="flex items-center gap-2">✔ AI Companion Access</li>
                    </ul>
                    <form method="POST" action="{{ route('premium.upgrade') }}">
                        @csrf
                        <input type="hidden" name="plan" value="quarter">
                        <button class="w-full bg-zinc-900 text-white py-4 rounded-2xl hover:bg-black transition font-semibold">
                            Choose Quarterly
                        </button>
                    </form>
                </div>

                <!-- Yearly -->
                <div class="bg-white border border-zinc-200 rounded-3xl p-8 text-center hover:shadow-xl transition-all">
                    <h3 class="text-2xl font-bold text-gray-800">Yearly</h3>
                    <div class="my-6">
                        <span class="text-5xl font-bold">₱1,499</span>
                        <span class="text-gray-500">/year</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8 text-gray-700">
                        <li class="flex items-center gap-2">✔ No Ads</li>
                        <li class="flex items-center gap-2">✔ AI Companion Access</li>
                    </ul>
                    <form method="POST" action="{{ route('premium.upgrade') }}">
                        @csrf
                        <input type="hidden" name="plan" value="year">
                        <button class="w-full bg-zinc-900 text-white py-4 rounded-2xl hover:bg-black transition font-semibold">
                            Choose Yearly
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="bg-gradient-to-r from-emerald-600 to-lime-500 rounded-[40px] p-12 text-white shadow-2xl">
                <h2 class="text-4xl lg:text-5xl font-black leading-tight">
                    Start Building Better Financial Habits Today
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
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <div>
                <h3 class="font-black text-emerald-700 text-2xl">
                    PesoFlow
                </h3>
                <p class="text-zinc-500 text-sm">
                    Smart Spending Better Saving
                </p>
            </div>

            <p class="text-zinc-500 text-xs sm:text-sm">
                © {{ date('Y') }} PesoFlow. Built for smarter financial growth.
            </p>
        </div>
    </footer>

    <script>
        const video = document.getElementById('adVideo');
        const muteBtn = document.getElementById('muteBtn');
        const muteIcon = document.getElementById('muteIcon');
        const fullscreenBtn = document.getElementById('fullscreenBtn');
        const fullscreenIcon = document.getElementById('fullscreenIcon');

        function toggleMute() {
            video.muted = !video.muted;

            if (video.muted) {
                muteIcon.textContent = '🔇';
            } else {
                muteIcon.textContent = '🔊';
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                video.requestFullscreen().catch(err => {
                    console.error(`Error attempting to enable fullscreen: ${err.message}`);
                });
                fullscreenIcon.textContent = '⤢'; // Exit icon
            } else {
                document.exitFullscreen();
                fullscreenIcon.textContent = '⛶';
            }
        }

        // Update icon when fullscreen changes
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                fullscreenIcon.textContent = '⛶';
            }
        });
    </script>
</body>
</html>
