<x-guest-layout>
    <x-slot name="title">CSG Login</x-slot>

    <!-- Include login.css for this page -->
        @vite(['resources/css/login.css', 'resources/js/app.js'])

    <!-- Background Image -->
    <div class="content-container">
        <figure class="image-container">
            <img
                src="{{ asset('images/csg_logo.png') }}"
                class="responsive-image"
                alt="CSG Background"
            />
        </figure>
    </div>

    <!-- Login Form -->
    <div class="login-container">
        <header class="form-header">
            <h1 class="login-title">Login</h1>
        </header>

        <!-- Form with HTMX -->
        <form class="form-fields"
              method="POST"
              hx-post="{{ route('login') }}"
              hx-target="main"
              hx-swap="outerHTML">
            @csrf
            <div class="input-field">
                <input type="email" name="email" placeholder="Email" aria-label="Email" value="{{ old('email') }}" required />
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="input-field">
                <input type="password" name="password" placeholder="Password" aria-label="Password" required />
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <section class="form-actions">
                <button type="submit" class="sign-in-button">Sign In</button>
                <div class="remember-section">
                    <label class="remember-wrapper">
                        <input type="checkbox" name="remember" class="checkbox" aria-label="Remember me" />
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}"
                       hx-get="{{ route('password.request') }}"
                       hx-target="main"
                       hx-swap="outerHTML"
                       hx-push-url="true"
                       class="forgot-password-link">
                        Forgot Password?
                    </a>
                </div>
            </section>
        </form>

        <footer class="register-section">
            <p class="no-account">No account yet?</p>
            <a href="{{ route('register.form') }}"
               hx-get="{{ route('register.form') }}"
               hx-target="main"
               hx-swap="outerHTML"
               hx-push-url="true"
               class="register-link">
                Register now
            </a>
        </footer>
    </div>
</x-guest-layout>