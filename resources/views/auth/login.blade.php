<x-guest-layout>
    <x-slot name="title">CSG Login</x-slot>

    <!-- Include login.css, SweetAlert2, and app.js -->
    @vite(['resources/css/login.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Background Container -->
    <div class="page-container">
        <!-- Background Image -->
        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132"
             alt="CSG Logo"
             class="background-logo" />

<div class="content-wrapper">

    <div class="login-container">
        <header class="form-header">
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
                       hx-target="body"
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
               hx-target="body"
               hx-swap="outerHTML"
               hx-push-url="true"
               class="register-link">
                Register now
            </a>
        </footer>
    </div>
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
