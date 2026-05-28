
{{-- Login Form --}}
<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="text-center space-y-1 mb-8">
            <h2 class="text-3xl font-semibold tracking-tight text-zinc-800">Welcome Back</h2>
            <p class="text-zinc-600 text-sm">Log in to continue saving smarter</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mt-2 text-center" :status="session('status')" />

        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-center" />

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-zinc-700" />
            <x-text-input id="email"
                          class="block mt-2 w-full bg-white border border-zinc-300 text-zinc-900 rounded-2xl focus:border-emerald-500 focus:ring-emerald-500 py-3.5 px-5"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required autofocus autocomplete="username" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-zinc-700" />

            <x-text-input id="password"
                          class="block mt-2 w-full bg-white border border-zinc-300 text-zinc-900 rounded-2xl focus:border-emerald-500 focus:ring-emerald-500 py-3.5 px-5"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center mt-4">
            <label for="remember_me" class="inline-flex items-center text-zinc-700">
                <input id="remember_me" type="checkbox"
                       class="rounded border-zinc-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                       name="remember">
                <span class="ms-2 text-sm">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-600 hover:text-emerald-700 transition font-medium"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button
                class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl transition-all active:scale-95 shadow-lg shadow-emerald-500/30">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
