
{{-- User Information Section --}}
<section class="bg-white rounded-3xl p-8 max-w-xl mx-auto">
    <header class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-2 text-gray-600">
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
                    class="mt-1.5 block w-full rounded-2xl border border-gray-200 focus:border-blue-600 focus:ring-blue-200 py-3.5 px-5 text-[15.5px]"
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
                    class="mt-1.5 block w-full rounded-2xl border border-gray-200 bg-gray-100 py-3.5 px-5 text-[15.5px] text-gray-500"
                    :value="old('email', $user->email)"
                    readonly
                    autocomplete="username"
                />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p class="mt-3 text-sm text-amber-700">
                        {{ __('Your email address is unverified.') }}
                        <button
                            form="send-verification"
                            class="underline hover:text-amber-900 font-medium transition-colors"
                        >
                            {{ __('Re-send verification email') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 font-medium">
                            {{ __('A new verification link has been sent.') }}
                        </p>
                    @endif
                @endif
            </div>
        </div>

        <div class="pt-4">
            <x-primary-button class="px-8 py-3.5 bg-gray-900 hover:bg-black text-white font-semibold rounded-2xl text-base shadow-md transition-all">
                {{ __('Save Changes') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="inline ml-4 text-emerald-600 text-sm font-medium"
                >
                    ✓ {{ __('Saved') }}
                </p>
            @endif
        </div>
    </form>
</section>
