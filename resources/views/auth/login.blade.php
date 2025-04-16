<x-guest-layout>
    <x-slot name="title">CSG Login</x-slot>

    <!-- Include login.css, SweetAlert2, and app.js -->
    @vite(['resources/css/login.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Background Container -->
    <div class="content-container">
        <figure class="image-container">
            <img
                src="{{ asset('images/csg_logo.png') }}"
                class="responsive-image"
                alt="CSG Logo"
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
            </div>
            <div class="input-field">
                <input type="password" name="password" placeholder="Password" aria-label="Password" required />
            </div>

            <section class="form-actions">
                <button type="submit" class="sign-in-button">Sign In</button>
                <div class="forgot-password-section">
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

    <!-- JavaScript for SweetAlert2 Error Handling -->
    @push('scripts')
        <script>
            document.addEventListener('htmx:afterRequest', function (event) {
                if (event.detail.xhr.status === 422 && event.detail.xhr.response) {
                    const response = JSON.parse(event.detail.xhr.response);
                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: response.error,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#f7c603',
                        });
                    }
                }
            });
        </script>
    @endpush
</x-guest-layout>
