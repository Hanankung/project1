<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
                class="block mt-1 w-full rounded-xl border-emerald-300
                       focus:border-emerald-500 focus:ring-emerald-500"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="block mt-1 w-full rounded-xl border-emerald-300
                       focus:border-emerald-500 focus:ring-emerald-500"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-700 hover:text-emerald-800 rounded-md
                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif>

            <x-primary-button
                class="ms-3 bg-emerald-700 hover:bg-emerald-800 focus:ring-emerald-500 rounded-xl px-5 py-2">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        @if (Route::has('register'))
            <div class="mt-4 text-center">
                <span class="text-gray-600 text-sm">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-emerald-700 hover:text-emerald-800 font-semibold">
                    {{ __('Register here') }}
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
