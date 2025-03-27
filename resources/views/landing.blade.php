{{-- resources/views/landing.blade.php --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/Landing.css') }}">

<script src="https://unpkg.com/htmx.org@2.0.4" integrity="sha384-HGfztofotfshcF7+8n44JQL2oJmowVChPTg48S+jvZoztPfvwD79OC/LTtG6dMp+" crossorigin="anonymous"></script>
@endpush

@if(request()->header('HX-Request'))
<link rel="stylesheet" href="{{ asset('css/Landing.css') }}">
@endif


<x-guest-layout>
    <x-slot name="title">CSG Election System</x-slot>

    <div class="landing">
        {{-- Background images (optional) --}}
    </div>

    <!-- Main Content Wrapper -->
<div id="main-content">
    <div class="row_one">
        <div class="second_bg"></div>
        <div class="row">
            <div class="landing_row">
                <img src="{{ asset('images/csg_logo.png') }}" alt="My Logo" class="csg-logo" />
                <div class="landing_column">
                    <h1 class="landing_title ui heading size-headingxs">
                        CSG ELECTION<br><span class="system_word">SYSTEM</span>
                    </h1>
                    <div class="landing_registration-row">
                        <p class="landing_registration-text ui text size-textxs">First time voter?</p>
                        <a href="{{ route('eligibility') }}" 
                        hx-get="{{ route('eligibility') }}"
                        hx-target="body"
                        hx-swap="outerHTML"
                        hx-push-url="true"
                        class="landing_registration-link ui text size-textxs">
                        Check your eligibility here!
                     </a>
                     
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
</x-guest-layout>
