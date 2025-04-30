<x-guest-layout>
    <x-slot name="title">Check Eligibility</x-slot>

    @vite(['resources/css/eligibility.css', 'resources/js/app.js'])

    <!-- Inject the eligibility check URL as a global variable -->
    <script>
        window.eligibilityCheckUrl = '{{ route("register.eligibility.check") }}';
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
                        id="student_id"
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
