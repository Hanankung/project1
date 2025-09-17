<section>
    <header>
        <h2 class="text-lg font-medium text-red-700">Delete Account</h2>
        <p class="mt-1 text-sm text-red-700/80">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="mt-6 rounded-xl">
        {{ __('Delete Account') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-text-input id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 rounded-xl border-red-300 focus:border-red-500 focus:ring-red-500"
                    placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->get('userDeletion')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 rounded-xl">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
