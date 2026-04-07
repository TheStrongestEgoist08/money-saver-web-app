
{{-- Login Form --}}
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div class="text-center space-y-1 mb-6">
            <h2 class="text-2xl font-semibold tracking-tight text-white">Welcome Back</h2>
            <p class="text-zinc-400 text-sm">Log in to continue saving smarter</p>
        </div>

        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-center" />

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-zinc-300" />
            <x-text-input id="email"
                          class="block mt-2 w-full bg-zinc-950 border-zinc-800 text-white rounded-2xl focus:border-emerald-500 focus:ring-emerald-500"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-zinc-300" />

            <x-text-input id="password"
                          class="block mt-2 w-full bg-zinc-950 border-zinc-800 text-white rounded-2xl focus:border-emerald-500 focus:ring-emerald-500"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center mt-4">
            <label for="remember_me" class="inline-flex items-center text-zinc-300">
                <input id="remember_me" type="checkbox"
                       class="rounded border-zinc-700 text-emerald-600 shadow-sm focus:ring-emerald-500"
                       name="remember">
                <span class="ms-2 text-sm text-zinc-300">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-zinc-400 hover:text-white transition"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button
                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl transition-all active:scale-95 shadow-lg shadow-emerald-600/30">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
