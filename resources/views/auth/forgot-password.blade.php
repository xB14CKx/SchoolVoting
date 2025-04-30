<x-guest-layout>
    <!-- Container for the Forgot Password Form -->
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-100 via-indigo-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white shadow-xl rounded-lg p-8 transform transition-all hover:shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-gray-900">Forgot Your Password?</h2>
                <p class="mt-2 text-sm text-gray-600">
                    {{ __('No problem. Just let us know your email address, and we’ll email you a password reset link to set a new one.') }}
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6 text-center text-sm text-green-600 font-medium" :status="session('status')" />

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        placeholder="Enter your email address"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-center">
                    <x-primary-button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg shadow-md transition duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Back to Login Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline transition duration-200">
                    {{ __('Back to Login') }}
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
