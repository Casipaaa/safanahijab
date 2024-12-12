<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="bg-white dark:bg-[#3e2723] p-6 rounded-lg shadow-lg">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" class="text-[#3e2723] dark:text-white" />
            <x-text-input id="username"
                class="block mt-1 w-full border-gray-300 dark:border-[#6d4c41] rounded-lg shadow-sm dark:bg-[#5d4037] text-gray-900 dark:text-white focus:ring-[#6d4c41]"
                type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-[#3e2723] dark:text-white" />
            <x-text-input id="password"
                class="block mt-1 w-full border-gray-300 dark:border-[#6d4c41] rounded-lg shadow-sm dark:bg-[#5d4037] text-gray-900 dark:text-white focus:ring-[#6d4c41]"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 dark:border-[#6d4c41] text-[#6d4c41] shadow-sm focus:ring-[#6d4c41]"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-300">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('register'))
                <a class="underline text-sm text-[#3e2723] dark:text-[#d7ccc8] hover:text-[#6d4c41] dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6d4c41]"
                    href="{{ route('register') }}">
                    {{ __('Don\'t have an account?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 bg-[#6d4c41] hover:bg-[#5d4037] focus:ring-[#6d4c41] text-white">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
