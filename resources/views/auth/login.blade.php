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
                // Show success message from session (non-HTMX)
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: @json(session('success')),
                        confirmButtonColor: '#f7c603',
                    });
                @endif

                // Show error message from session (non-HTMX)
                @if($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: @json($errors->first()),
                        confirmButtonColor: '#f7c603',
                    });
                @endif

                // HTMX: Only one event listener, handles both error and success
                if (window.htmx) {
                    document.removeEventListener('htmx:afterRequest', window._loginHtmxHandler, true);
                    window._loginHtmxHandler = function (event) {
                        // Only handle login POST requests
                        if (!event.detail.xhr.responseURL.includes('/login')) return;
                        // Error (422)
                        if (event.detail.xhr.status === 422 && event.detail.xhr.response) {
                            let response;
                            try { response = JSON.parse(event.detail.xhr.response); } catch (e) { return; }
                            if (response.error) {
                                // Always show the error, regardless of message
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Login Failed',
                                    text: response.error,
                                    confirmButtonColor: '#f7c603',
                                });
                            }
                        }
                        // Success (200)
                        else if (event.detail.xhr.status === 200 && event.detail.xhr.response) {
                            let response;
                            try { response = JSON.parse(event.detail.xhr.response); } catch (e) { return; }
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.success,
                                    confirmButtonColor: '#f7c603',
                                }).then(() => {
                                    if (response.redirect) {
                                        window.location.href = response.redirect;
                                    }
                                });
                            }
                        }
                    };
                    document.addEventListener('htmx:afterRequest', window._loginHtmxHandler, true);
                }
            </script>
        @endpush
    </x-guest-layout>
