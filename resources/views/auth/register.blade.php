<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="bg-white dark:bg-[#3e2723] p-6 rounded-lg shadow-lg">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" class="text-[#3e2723] dark:text-[#d7ccc8]" />
            <x-text-input id="username" class="block mt-1 w-full border-gray-300 dark:border-[#6d4c41] rounded-lg shadow-sm dark:bg-[#5d4037] text-gray-900 dark:text-white focus:ring-[#6d4c41]" 
                type="text" 
                name="username" 
                :value="old('username')" 
                required 
                autofocus 
                autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-[#3e2723] dark:text-[#d7ccc8]" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 dark:border-[#6d4c41] rounded-lg shadow-sm dark:bg-[#5d4037] text-gray-900 dark:text-white focus:ring-[#6d4c41]" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-[#3e2723] dark:text-[#d7ccc8]" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 dark:border-[#6d4c41] rounded-lg shadow-sm dark:bg-[#5d4037] text-gray-900 dark:text-white focus:ring-[#6d4c41]" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-[#3e2723] dark:text-[#d7ccc8] hover:text-[#6d4c41] dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6d4c41]" 
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 bg-[#6d4c41] hover:bg-[#5d4037] text-white focus:ring-[#6d4c41]">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
