
{{-- User Information Section --}}
<section class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg mx-auto">
    <header class="mb-8">
        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-900 tracking-tight">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-3 text-gray-600 text-[15px] leading-relaxed">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('user.profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <div class="space-y-8">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-2 block w-full rounded-2xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 py-3.5 px-5 text-[15px] sm:text-[15.5px] transition-all duration-200"
                    :value="old('name', $user->name)"
                    required
                    autofocus
                    autocomplete="name"
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    class="mt-2 block w-full rounded-2xl border border-gray-200 bg-gray-50 py-3.5 px-5 text-[15px] sm:text-[15.5px] text-gray-500 cursor-not-allowed transition-all duration-200"
                    :value="old('email', $user->email)"
                    readonly
                    autocomplete="username"
                />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p class="mt-4 text-sm text-amber-700 flex items-center gap-2">
                        <span class="inline-block w-2 h-2 bg-amber-500 rounded-full"></span>
                        {{ __('Your email address is unverified.') }}
                        <button
                            form="send-verification"
                            class="underline hover:text-amber-800 font-medium transition-colors ml-1"
                        >
                            {{ __('Re-send verification email') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-emerald-600 font-medium flex items-center gap-2">
                            <span>✓</span>
                            {{ __('A new verification link has been sent.') }}
                        </p>
                    @endif
                @endif
            </div>
        </div>

        <div class="pt-4 flex items-center gap-4">
            <x-primary-button class="px-8 py-3.5 sm:px-10 sm:py-4 bg-gray-900 hover:bg-gray-950 active:bg-black text-white font-semibold rounded-2xl text-base transition-all duration-200">
                {{ __('Save Changes') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2800)"
                    class="inline-flex items-center gap-2 text-emerald-600 text-sm font-semibold"
                >
                    <span class="text-lg">✓</span>
                    {{ __('Saved') }}
                </p>
            @endif
        </div>
    </form>
</section>
