{{-- resources/views/eligibility.blade.php --}}

@push('styles')
<link rel="stylesheet" href="{{ asset('css/eligibility.css') }}">
@endpush

<x-guest-layout>
    <!-- BACKGROUND COLOR -->
    <div class="eligibility"></div>

    <!-- MAIN CONTENT CONTAINER (Sidebar + eligibility form) -->
    <div class="content-container">

      <!-- ELIGIBILITY CONTENT -->
        <div class="image-container">
          <img src="{{ asset('images/csglogo_nobg.png') }}" alt="Centered Image" class="responsive-image">
        </div>

        <section class="eligibility-container">
          <div class="eligibility-wrapper">
            <h2 class="eligibility-title">Check Eligibility</h2>
            <form class="eligibility-form">
              <label for="student-id" class="form-label">ID Number</label>
              <div class="input-wrapper">
                <input type="text" id="student-id" placeholder="Student Number" class="student-input">
              </div>
              <button type="submit" class="check-button">CHECK ELIGIBILITY</button>
            </form>
          </div>
        </section>
    </div>
</x-guest-layout>
