<x-guest-layout>
    <x-slot name="title">Check Eligibility</x-slot>

    @vite(['resources/css/eligibility.css', 'resources/js/app.js'])

    <div class="eligibility"></div>
    <div class="content-container">
        <div class="image-container">
            <img src="{{ asset('images/csglogo_nobg.png') }}" alt="Centered Image" class="responsive-image">
        </div>

        <section class="eligibility-container">
            <div class="eligibility-wrapper" id="eligibility-container">
                <h2 class="eligibility-title">Check Eligibility</h2>

                <!-- Display error or success messages -->
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
                               value="{{ old('student_id') }}" 
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