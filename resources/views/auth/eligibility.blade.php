<x-guest-layout>
    <x-slot name="title">Check Eligibility</x-slot>

    @vite(['resources/css/eligibility.css', 'resources/js/app.js'])

    <!-- Inject the eligibility check URL as a global variable -->
    <script>
        window.eligibilityCheckUrl = '{{ route("register.eligibility.check") }}';

        // Re-initialize SweetAlert2 listeners after HTMX swaps content
        document.body.addEventListener('htmx:afterSwap', function(event) {
            const requestUrl = event.detail.xhr.responseURL;
            const eligibilityCheckUrl = window.eligibilityCheckUrl;

            // Only process responses from the eligibility check endpoint
            if (!requestUrl.includes(eligibilityCheckUrl)) {
                console.log('Skipping SweetAlert2 for non-eligibility request:', requestUrl);
                return;
            }

            const response = event.detail.xhr.response;
            console.log('Raw HTMX Response:', response);

            // Check if the response contains an error message (indicating an error case)
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = response;
            const errorElement = tempDiv.querySelector('#error-message-data');

            if (errorElement) {
                // Error case: Show error SweetAlert2 popup
                console.log('Error detected in response:', errorElement.dataset.error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorElement.dataset.error,
                    confirmButtonText: 'OK'
                }).then(() => {
                    const form = document.querySelector('.eligibility-form');
                    if (form) {
                        form.reset();
                    }
                });
            } else {
                // Success case: Show success SweetAlert2 popup
                console.log('Success case, showing success popup');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'You are eligible! Loading registration form...',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Perform additional actions if needed (e.g., navigate or load new content)
                });
            }
        });
    </script>

    <!-- Hidden element to store the error message for JavaScript -->
    @if (isset($error))
        <div id="error-message-data" style="display: none;" data-error="{{ $error }}"></div>
    @endif

    <div class="eligibility"></div>
    <div class="content-container" id="main-content">
        <div class="image-container">
            <img src="{{ asset('images/csglogo_nobg.png') }}" alt="Centered Image" class="responsive-image">
        </div>

        <section class="eligibility-container">
            <div class="eligibility-wrapper" id="eligibility-container">
                <h2 class="eligibility-title">Check Eligibility</h2>

                @if (session('error'))
                    <div class="message error">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="message success">
                        {{ session('success') }}
                    </div>
                @endif

                <form class="eligibility-form"
                      method="POST"
                      action="{{ route('register.eligibility.check') }}"
                      hx-post="{{ route('register.eligibility.check') }}"
                      hx-target="body"
                      hx-swap="outerHTML">
                    @csrf
                    <label for="student-id" class="form-label">ID Number</label>
                    <div class="input-wrapper">
                        <input type="number"
                               id="student-id"
                               name="student_id"
                               placeholder="Student Number"
                               class="student-input"
                               value="{{ $student_id ?? old('student_id') }}"
                               required>
                        @error('student_id')
                            <div class="message error">
                                {{ $message }}
                            </div>
                        @enderror
                        <div id="error-message" class="message error" style="display: none;"></div>
                    </div>
                    <button type="submit" class="check-button">CHECK ELIGIBILITY</button>
                </form>
            </div>
        </section>
    </div>
</x-guest-layout>
