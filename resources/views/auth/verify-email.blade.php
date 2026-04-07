
{{-- Verify Email Address --}}
<x-guest-layout>
    <div class="mb-4 text-sm text-zinc-400 text-center">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    <div class="text-center space-y-1 mb-6">
        <h2 class="text-2xl font-semibold tracking-tight text-white">Verify Your Email</h2>
        <p class="text-zinc-400 text-sm">Check your inbox and confirm your account</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-emerald-400 text-center">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-4">
        <!-- Resend Verification -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button
                    class="w-full flex justify-center items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-2xl transition-all active:scale-95 shadow-lg shadow-emerald-600/30">
                    {{ __('Send Verification') }}
                </x-primary-button>
            </div>
        </form>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-2xl transition-all active:scale-95 shadow-lg shadow-red-600/30">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
