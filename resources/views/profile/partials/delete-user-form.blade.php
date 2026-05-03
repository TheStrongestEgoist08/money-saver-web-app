
{{-- Delete Account --}}
<section class="bg-white rounded-3xl p-10 max-w-xl mx-auto">
    <header class="mb-10 text-center">
        <h2 class="text-3xl font-semibold text-gray-900 tracking-tight">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-3 text-gray-600 text-[15.5px] leading-relaxed max-w-md mx-auto">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div class="flex justify-center">
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-8 py-4 text-base font-semibold rounded-2xl shadow-lg shadow-red-500/20 hover:shadow-xl transition-all duration-200 hover:-translate-y-0.5"
        >
            {{ __('Delete Account') }}
        </x-danger-button>
    </div>

    <!-- Modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-10">
            <form method="post" action="{{ route('user.profile.destroy') }}" class="space-y-8">
                @csrf
                @method('delete')

                <div class="text-center">
                    <h2 class="text-2xl font-semibold text-gray-900">
                        {{ __('Are you sure you want to delete your account?') }}
                    </h2>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        {{ __('This action is irreversible. All your data will be permanently deleted.') }}
                    </p>
                </div>

                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-2 block w-full rounded-2xl border border-gray-200 focus:border-red-500 focus:ring-4 focus:ring-red-100 py-4 px-6 text-[15.5px] transition-all duration-200 shadow-sm"
                        placeholder="{{ __('Enter your password to confirm') }}"
                        required
                    />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-4 pt-6">
                    <x-secondary-button
                        x-on:click="$dispatch('close')"
                        class="px-8 py-3.5 rounded-2xl font-medium transition-all"
                    >
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="px-8 py-3.5 rounded-2xl shadow-lg shadow-red-500/30">
                        {{ __('Yes, Delete My Account') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>
</section>
