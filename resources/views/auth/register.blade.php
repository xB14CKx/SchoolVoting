<x-guest-layout>
    <x-slot name="title">CSG Registration</x-slot>

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
            <form class="registration-form" method="POST" action="{{ route('register.register') }}">
                @csrf
                <h1 class="form-title" style="margin-top: 10px;">Registration Form</h1>

                <!-- Name Fields -->
                <div class="form-row">
                    <div class="form-group full-width">
                        <label class="form-label">Name</label>
                        <div class="name-inputs">
                            <input 
                                type="text" 
                                name="first_name" 
                                placeholder="First name" 
                                class="form-input" 
                                value="{{ old('first_name', $student->first_name ?? '') }}" 
                                required 
                                readonly 
                            />
                            @error('first_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <input 
                                type="text" 
                                name="last_name" 
                                placeholder="Last Name" 
                                class="form-input" 
                                value="{{ old('last_name', $student->last_name ?? '') }}" 
                                required 
                                readonly 
                            />
                            @error('last_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <input 
                                type="text" 
                                name="middle_name" 
                                placeholder="Middle Name" 
                                class="form-input" 
                                value="{{ old('middle_name', $student->middle_name ?? '') }}" 
                                readonly 
                            />
                            @error('middle_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Student ID and Email -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">ID Number</label>
                        <input 
                            type="text" 
                            name="student_id" 
                            placeholder="ID Number" 
                            class="form-input" 
                            value="{{ old('student_id', $student->id ?? '') }}" 
                            readonly 
                        />
                        @error('student_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Student Email</label>
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="Email" 
                            class="form-input" 
                            value="{{ old('email', $student->email ?? '') }}" 
                            required 
                            readonly 
                        />
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <!-- Program and Year Level -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Program</label>
                        <select name="program_id" class="form-input" required>
                            @foreach($programs as $program)
                                <option value="{{ $program->program_id }}"
                                    {{ old('program_id', $student->program_id ?? '') == $program->program_id ? 'selected' : '' }}>
                                    {{ $program->program_name }}
                                </option>
                            @endforeach
                        </select>
                        
                        @error('program_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Year Level</label>
                        <select 
                            name="year_level" 
                            class="form-input" 
                            required 
                            readonly
                        >
                            <option value="1st" {{ old('year_level', $student->year_level) == '1st' ? 'selected' : '' }}>1st</option>
                            <option value="2nd" {{ old('year_level', $student->year_level) == '2nd' ? 'selected' : '' }}>2nd</option>
                            <option value="3rd" {{ old('year_level', $student->year_level) == '3rd' ? 'selected' : '' }}>3rd</option>
                            <option value="4th" {{ old('year_level', $student->year_level) == '4th' ? 'selected' : '' }}>4th</option>
                        </select>
                        @error('year_level')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <!-- Contact Number and Date of Birth -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Contact Number</label>
                        <input 
                            type="tel" 
                            name="contact_number" 
                            placeholder="Enter contact number" 
                            class="form-input" 
                            inputmode="numeric" 
                            pattern="[0-9]*" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
                            value="{{ old('contact_number', $student->contact_number ?? '') }}" 
                            required 
                            readonly 
                        />
                        @error('contact_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <input 
                            type="date" 
                            name="date_of_birth" 
                            class="form-input" 
                            value="{{ old('date_of_birth', $student->date_of_birth ?? '') }}" 
                            required 
                            readonly 
                        />
                        @error('date_of_birth')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <!-- Password Fields -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Enter Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="8-12 characters" 
                            class="form-input" 
                            required 
                        />
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            placeholder="8-12 characters" 
                            class="form-input" 
                            required 
                        />
                        @error('password_confirmation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="button-container">
                    <button type="submit" class="register-button">Register</button>
                </div>
            </form>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-guest-layout>
