
{{-- Reset Password --}}
<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="text-center space-y-1 mb-8">
            <h2 class="text-3xl font-semibold tracking-tight text-zinc-800">Reset Password</h2>
            <p class="text-zinc-600 text-sm">Set your new password to regain access</p>
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-zinc-700" />
            <x-text-input id="email"
                          class="block mt-2 w-full bg-white border border-zinc-300 text-zinc-900 rounded-2xl focus:border-emerald-500 focus:ring-emerald-500 py-3.5 px-5"
                          type="email"
                          name="email"
                          :value="old('email', $request->email)"
                          readonly autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-center" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-zinc-700" />
            <x-text-input id="password"
                          class="block mt-2 w-full bg-white border border-zinc-300 text-zinc-900 rounded-2xl focus:border-emerald-500 focus:ring-emerald-500 py-3.5 px-5"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-center" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-zinc-700" />
            <x-text-input id="password_confirmation"
                          class="block mt-2 w-full bg-white border border-zinc-300 text-zinc-900 rounded-2xl focus:border-emerald-500 focus:ring-emerald-500 py-3.5 px-5"
                          type="password"
                          name="password_confirmation"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-center" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button
                class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl transition-all active:scale-95 shadow-lg shadow-emerald-500/30">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
