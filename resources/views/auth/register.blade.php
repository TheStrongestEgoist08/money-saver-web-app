
{{-- Register Page --}}
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="text-center space-y-1 mb-6">
            <h2 class="text-2xl font-semibold tracking-tight text-white">Create Account</h2>
            <p class="text-zinc-400 text-sm">Start saving smarter today</p>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-zinc-300" />
            <x-text-input id="name"
                          class="block mt-2 w-full bg-zinc-950 border-zinc-800 text-white rounded-2xl focus:border-emerald-500 focus:ring-emerald-500"
                          type="text"
                          name="name"
                          :value="old('name')"
                          required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-400" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-zinc-300" />
            <x-text-input id="email"
                          class="block mt-2 w-full bg-zinc-950 border-zinc-800 text-white rounded-2xl focus:border-emerald-500 focus:ring-emerald-500"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-zinc-300" />

            <x-text-input id="password"
                          class="block mt-2 w-full bg-zinc-950 border-zinc-800 text-white rounded-2xl focus:border-emerald-500 focus:ring-emerald-500"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-zinc-300" />

            <x-text-input id="password_confirmation"
                          class="block mt-2 w-full bg-zinc-950 border-zinc-800 text-white rounded-2xl focus:border-emerald-500 focus:ring-emerald-500"
                          type="password"
                          name="password_confirmation"
                          required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-zinc-400 hover:text-white transition"
               href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button
                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl transition-all active:scale-95 shadow-lg shadow-emerald-600/30">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
