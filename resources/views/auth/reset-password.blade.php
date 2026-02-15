<x-guest-layout>
    <div class="mb-6 text-center lg:text-left">
        <h2 class="text-2xl font-bold text-gray-800">Setup New Password!</h2>
        <p class="text-sm text-gray-500 mt-2">Please enter it below to confirm your Email Address.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address')" class="mb-1 block font-medium text-gray-700" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <x-text-input id="email"
                    class="pl-10 block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm"
                    type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" class="mb-1 block font-medium text-gray-700" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password"
                    class="pl-10 block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm"
                    type="password" name="password" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')"
                class="mb-1 block font-medium text-gray-700" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password_confirmation"
                    class="pl-10 block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-lg shadow-sm"
                    type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mb-6">
            <label class="inline-flex items-center text-sm text-gray-600">
                <input type="checkbox" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary"
                    required checked>
                <span class="ms-2">Agree to the <a href="#" class="underline">Terms & Policy</a></span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button
                class="w-full justify-center bg-primary hover:bg-orange-600 active:bg-orange-700 focus:ring-primary py-3 text-lg">
                {{ __('Update Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>