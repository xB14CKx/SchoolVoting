{{-- resources/views/eligibility.blade.php --}}

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
                <form class="eligibility-form" method="POST" action="{{ route('eligibility.check') }}"
                    hx-post="{{ route('eligibility.check') }}" 
                    hx-target="body" 
                    hx-swap="outerHTML"
                    hx-push-url="true">
                    @csrf
                    <label for="student-id" class="form-label">ID Number</label>
                    <div class="input-wrapper">
                        <input type="number" id="student-id" name="student_id" placeholder="Student Number" class="student-input" required>
                        <div id="error-message" class="message error" style="display: none;"></div>
                    </div>
                    <button type="submit" class="check-button">CHECK ELIGIBILITY</button>
                </form>
            </div>
        </section>
    </div>
</x-guest-layout>