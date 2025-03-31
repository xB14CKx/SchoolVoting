{{-- resources/views/registration.blade.php --}}

<x-guest-layout>

    {{-- Include Vite assets (CSS and JS) --}}
    @vite(['resources/css/registration.css', 'resources/js/app.js'])

    <!-- BACKGROUND COLOR -->
    <div class="registration"></div>
    
    <!-- CSG LOGO -->
    <div class="content-wrapper">
        <figure class="image-container">
            <img src="{{ asset('images/csglogo_nobg.png') }}" alt="My Logo" class="csg-logo" />
        </figure>
        
        <!-- REGISTRATION CONTAINER -->
        <main class="form-container">
            <form class="registration-form" method="POST" action="{{ route('register') }}">
                @csrf
                <h1 class="form-title" style="margin-top: 10px;">Registration Form</h1>
                
                <div class="form-row">
                    <div class="form-group full-width">
                        <label class="form-label">Name</label>
                        <div class="name-inputs">
                            <input type="text" name="first_name" placeholder="First name" class="form-input" value="{{ old('first_name', $student->first_name ?? '') }}" required />
                            <input type="text" name="last_name" placeholder="Last Name" class="form-input" value="{{ old('last_name', $student->last_name ?? '') }}" required />
                            <input type="text" name="middle_initial" placeholder="M.I" class="form-input" value="{{ old('middle_initial', $student->middle_initial ?? '') }}" />
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">ID Number</label>
                        <input type="text" name="student_id" placeholder="ID Number" class="form-input" value="{{ old('student_id', $student->id ?? '') }}" readonly />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Student Email</label>
                        <input type="email" name="email" placeholder="Email" class="form-input" value="{{ old('email', $student->email ?? '') }}" required />
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Program</label>
                        <input type="text" name="program" placeholder="Program" class="form-input" value="{{ old('program', $student->program ?? '') }}" required />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Year Level</label>
                        <input type="number" name="year_level" placeholder="Year Level" class="form-input" min="1" max="5" value="{{ old('year_level', $student->year_level ?? '') }}" required />
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <input type="tel" name="contact_number" placeholder="Enter contact number" class="form-input" inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ old('contact_number', $student->contact_number ?? '') }}" required />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-input" value="{{ old('date_of_birth', $student->date_of_birth ?? '') }}" required />
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Enter Password</label>
                        <input type="password" name="password" placeholder="6-12 characters" class="form-input" required />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="6-12 characters" class="form-input" required />
                    </div>
                </div>
                
                <div class="button-container">
                    <button type="submit" class="register-button">Register</button>
                </div>
            </form>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-guest-layout>
