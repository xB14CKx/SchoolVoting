<x-guest-layout>
    @vite(['resources/css/app.css', 'resources/css/reset-password.css', 'resources/js/app.js'])

    <main class="reset-password-page">
        <div class="page-container">
            <!-- Background image -->
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/5143e288166244e6b744336078167795d59d19ba?placeholderIfAbsent=true&apiKey=e0f71086e53c475a8c972a54eb6dce84" class="background-image" alt="Background image" />

            <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-md w-full reset-password-container">
                    <div class="reset-password-header">
                        <h2>Reset Your Password</h2>
                        <p>
                            Enter your email, new password, and confirm your new password to reset your account.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.store') }}" class="reset-password-form">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div class="form-group">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 error-message" />
                        </div>

                        <!-- Password -->
                        <div class="form-group mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 error-message" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 error-message" />
                        </div>

                        <div class="flex items-center justify-center mt-4">
                            <button type="submit">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>
