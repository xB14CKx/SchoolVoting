{{-- resources/views/eligibility.blade.php --}}

@push('styles')
<link rel="stylesheet" href="{{ asset('css/eligibility.css') }}">

<script src="https://unpkg.com/htmx.org@2.0.4" integrity="sha384-HGfztofotfshcF7+8n44JQL2oJmowVChPTg48S+jvZoztPfvwD79OC/LTtG6dMp+" crossorigin="anonymous"></script>
@endpush

@if(request()->header('HX-Request'))
    <link rel="stylesheet" href="{{ asset('css/eligibility.css') }}">
@endif

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
            <form class="eligibility-form" method="POST" action="{{ route('eligibility.check') }}">
              @csrf
              <label for="student-id" class="form-label">ID Number</label>
              <div class="input-wrapper">
                <input type="text" id="student-id" name="student_id" placeholder="Student Number" class="student-input" value="{{ old('student_id') }}">
                @error('student_id')
                    <div class="message error">{{ $message }}</div>
                @enderror
              </div>
              <button type="submit" class="check-button">CHECK ELIGIBILITY</button>
            </form>
            @if (session('success'))
                <div class="message success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="message error">{{ session('error') }}</div>
            @endif
          </div>
        </section>
    </div>
</x-guest-layout>