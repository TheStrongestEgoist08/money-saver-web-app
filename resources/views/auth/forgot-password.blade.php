
{{-- Forgot Password Form --}}
<x-guest-layout>
    <div class="mb-6 text-sm text-zinc-600 text-center leading-relaxed">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div class="text-center space-y-1 mb-8">
            <h2 class="text-3xl font-semibold tracking-tight text-zinc-800">Reset Password</h2>
            <p class="text-zinc-600 text-sm">Enter your email to receive reset link</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-center text-emerald-600" :status="session('status')" />
        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-center" />

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-zinc-700" />
            <x-text-input id="email"
                          class="block mt-2 w-full bg-white border border-zinc-300 text-zinc-900 rounded-2xl focus:border-emerald-500 focus:ring-emerald-500 py-3.5 px-5"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required autofocus />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full flex justify-center items-center px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl transition-all active:scale-95 shadow-lg shadow-emerald-500/30">
                {{ __('Send Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
