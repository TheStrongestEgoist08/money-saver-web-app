
{{-- Guest Layout --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MoneySaver') }} - Save Smarter</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-white antialiased bg-zinc-950">

        <div class="min-h-screen flex flex-col justify-center items-center p-6 bg-zinc-950">

            <div class="flex justify-center mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-emerald-600 rounded-3xl flex items-center justify-center text-3xl font-bold shadow-lg">
                        ₱
                    </div>
                    <span class="font-semibold text-3xl tracking-tighter">MoneySaver</span>
                </div>
            </div>

            <!-- Main Card -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-zinc-900 border border-zinc-800 shadow-2xl overflow-hidden sm:rounded-3xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
