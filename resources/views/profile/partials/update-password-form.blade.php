
{{-- Update Password --}}
<section class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg mx-auto">
    <header class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900 tracking-tight">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-3 text-gray-600 text-[15px] leading-relaxed">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-8" id="password-form">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-2 block w-full rounded-2xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 py-3.5 px-5 text-[15px] sm:text-[15.5px] transition-all duration-200"
                autocomplete="current-password"
                required
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-2 block w-full rounded-2xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 py-3.5 px-5 text-[15px] sm:text-[15.5px] transition-all duration-200"
                autocomplete="new-password"
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,25}"
                title="8-25 characters with uppercase, lowercase, number & special character."
                onkeyup="checkPasswordMatch()"
                required
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-2 block w-full rounded-2xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 py-3.5 px-5 text-[15px] sm:text-[15.5px] transition-all duration-200"
                autocomplete="new-password"
                onkeyup="checkPasswordMatch()"
                required
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            <p id="password-match-message" class="mt-1 text-sm hidden"></p>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button
                id="submit-btn"
                class="px-8 py-3.5 sm:px-10 sm:py-4 text-white font-semibold rounded-2xl text-base transition-all duration-200 disabled:cursor-not-allowed"
            >
                {{ __('Save Changes') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="inline-flex items-center gap-2 text-emerald-600 text-sm font-semibold"
                >
                    <span class="text-lg">✓</span>
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

<script>
function checkPasswordMatch() {
    const password = document.getElementById('update_password_password').value;
    const confirmPassword = document.getElementById('update_password_password_confirmation').value;
    const message = document.getElementById('password-match-message');
    const submitBtn = document.getElementById('submit-btn');

    if (confirmPassword === '') {
        message.classList.add('hidden');
        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-gray-400', 'hover:bg-gray-400');
        submitBtn.classList.add('bg-gray-900', 'hover:bg-gray-950');
        return;
    }

    if (password === confirmPassword) {
        // Match - Active button
        message.textContent = "✓ Passwords match";
        message.classList.remove('hidden', 'text-red-600');
        message.classList.add('text-emerald-600');

        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-gray-400', 'hover:bg-gray-400');
        submitBtn.classList.add('bg-gray-900', 'hover:bg-gray-950');
    } else {
        // No match - Disabled button
        message.textContent = "✕ Passwords do not match";
        message.classList.remove('hidden', 'text-emerald-600');
        message.classList.add('text-red-600');

        submitBtn.disabled = true;
        submitBtn.classList.remove('bg-gray-900', 'hover:bg-gray-950');
        submitBtn.classList.add('bg-gray-400', 'hover:bg-gray-400');
    }
}
</script>
