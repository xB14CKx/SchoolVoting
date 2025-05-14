<x-guest-layout>
    @vite(['resources/css/app.css', 'resources/css/forget-password.css', 'resources/js/app.js'])

    <main class="forgot-password-page">
        <div class="page-container">
            <!-- Background image -->
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/5143e288166244e6b744336078167795d59d19ba?placeholderIfAbsent=true&apiKey=e0f71086e53c475a8c972a54eb6dce84" class="background-image" alt="Background image" />

            <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-md w-full forgot-password-container">
                    <div class="forgot-password-header">
                        <h2>Forgot Your Password?</h2>
                        <p>
                            No problem. Just let us know your email address, and we’ll email you a password reset link to set a new one.
                        </p>
                    </div>

                    <div class="mb-6 text-center text-sm text-green-600 font-medium" role="alert">
                        @if (session('status'))
                            {{ session('status') }}
                        @endif
                    </div>

                    <form method="POST" action="{{ route('password.email') }}" class="forgot-password-form">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="Enter your email address"
                            />
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex items-center justify-center">
                            <button type="submit">
                                Email Password Reset Link
                            </button>
                        </div>
                    </form>

                    <div class="back-to-login">
                        <a href="{{ route('login') }}">
                            Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-guest-layout>
