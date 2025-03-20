{{-- resources/views/landing.blade.php --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/Landing.css') }}">
@endpush
<x-guest-layout>
    <!-- Optional: Pass a "title" slot if your guest-layout supports it -->
    <x-slot name="title">CSG Election System</x-slot>

    <!-- Background Section (if needed) -->
    <div class="landing">
        {{-- Uncomment if you have background images in public/images --}}
        {{-- <img src="{{ asset('images/yellowlines1.png') }}" class="yellowbg_one" alt="Yellow Background"> --}}
        {{-- <img src="{{ asset('images/yellowlines2.png') }}" class="second_bg" alt="Second Background"> --}}
    </div>

    <!-- Main Content -->
    <div class="row_one">
        <div class="second_bg"></div>
            <div class="row">
                <!-- Front Content -->
                <div class="landing_row">
                    <img src="{{ asset('images/csg_logo.png') }}" alt="My Logo" class="csg-logo" />
                    <div class="landing_column">
                        <h1 class="landing_title ui heading size-headingxs">
                            CSG ELECTION<br><span class="system_word">SYSTEM</span>
                        </h1>
                        <div class="landing_registration-row">
                            <p class="landing_registration-text ui text size-textxs">No account yet?</p>
                            <a href="{{ route('register') }}" class="landing_registration-link ui text size-textxs">Register Here!</a>
                        </div>
                    </div>
                </div>
            </div> <!-- End of row -->
        </div> <!-- End of row_one -->
    </div>
</x-guest-layout>
