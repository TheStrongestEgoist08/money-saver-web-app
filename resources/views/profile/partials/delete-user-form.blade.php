
{{-- Delete Account --}}
<section class="bg-white rounded-3xl p-8 max-w-xl mx-auto">
    <header class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">
            {{ __('Delete Account') }}
        </h2>
        <p class="mt-3 text-gray-600 leading-relaxed">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-3.5 text-base font-semibold rounded-2xl shadow-md hover:shadow-lg transition-all"
    >
        {{ __('Delete Account') }}
    </x-danger-button>

    <!-- Modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-8">
            <form method="post" action="{{ route('user.profile.destroy') }}" class="space-y-6">
                @csrf
                @method('delete')

                <h2 class="text-2xl font-bold text-gray-900">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="text-gray-600">
                    {{ __('This action is irreversible. All your data will be permanently deleted.') }}
                </p>

                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1.5 block w-full rounded-2xl border border-gray-200 focus:border-red-500 focus:ring-red-200 py-3.5 px-5 text-[15.5px]"
                        placeholder="{{ __('Enter your password to confirm') }}"
                        required
                    />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <x-secondary-button
                        x-on:click="$dispatch('close')"
                        class="px-6 py-3 rounded-2xl"
                    >
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="px-6 py-3 rounded-2xl">
                        {{ __('Yes, Delete My Account') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>
</section>
