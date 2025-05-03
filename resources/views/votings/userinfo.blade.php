@push('styles')
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/userInfo.css'])


    @endpush

<x-app-layout>    


    <div class="page-container">
        <!-- Background Image -->
        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132" alt="CSG Logo" class="background-logo" />

        <div class="content-wrapper">

        <!-- Header Title -->
        <section class="user-info-container">
            <h1 class="user-info-title">User Information</h1>
            <hr class="user-info-divider" />
        </section>

        <!-- Profile Section -->
        <main class="profile-container">
                <div class="profile-image-wrapper">
                    <img src="{{ $student->image ? asset('storage/' . $student->image) : asset('images/pfp.png') }}" alt="Profile" class="profile-image" id="profileImagePreview" />
                    <!-- Upload Image Button -->
                    <div class="upload-image-btn-wrapper">
                        <button type="button" class="upload-image-btn" data-bs-toggle="modal" data-bs-target="#uploadImageModal">
                            <i class="fa-solid fa-upload"></i> &nbsp;Upload Image
                        </button>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="form-container userinfo-redesign">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="student_number" class="form-label">Student Number</label>
                            <input type="text" id="student_number" class="form-input" value="{{ old('id', $student->id ?? '') }}" readonly />
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-input" value="{{ old('email', $student->email ?? '') }}" readonly />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" id="first_name" class="form-input" value="{{ old('first_name', $student->first_name ?? '') }}" readonly />
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" id="last_name" class="form-input" value="{{ old('last_name', $student->last_name ?? '') }}" readonly />
                        </div>
                        <div class="form-group">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" id="middle_name" class="form-input" value="{{ old('middle_name', $student->middle_name ?? '') }}" readonly />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_of_birth" class="form-label">Birth Date</label>
                            <input type="date" id="date_of_birth" class="form-input" value="{{ old('date_of_birth', $student->date_of_birth ?? '') }}" readonly />
                        </div>
                        <div class="form-group">
                            <label for="gender" class="form-label">Sex</label>
                            <div class="input-edit-wrapper">
                                <input type="text" id="gender" class="form-input" value="{{ old('gender', $student->sex ?? '') }}" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="program" class="form-label">Program</label>
                            <input type="text" id="program" class="form-input" value="{{ old('program', $student->program->program_name ?? '') }}" readonly />
                        </div>
                        <div class="form-group">
                            <label for="year_level" class="form-label">Year</label>
                            <input type="text" id="year_level" class="form-input" value="{{ old('year_level', $student->year_level ?? '') }}" readonly />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <div class="input-edit-wrapper">
                                <input type="text" id="contact_number" class="form-input" value="{{ old('contact_number', $student->contact_number ?? '') }}" readonly />
                                <button type="button" class="edit-btn" title="Edit Contact Number" tabindex="-1"><i class="fa fa-edit"></i></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-edit-wrapper">
                                <input type="password" id="password" class="form-input" value="{{ old('password', $user->password ?? '') }}" readonly />
                                <button type="button" class="edit-btn" id="openUpdatePasswordModal" title="Edit Password" tabindex="-1"><i class="fa fa-edit"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
        </main>
    </div>
</div>

<!-- Upload Image Modal OUTSIDE of any form -->
<div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="uploadProfileImageForm" action="{{ route('student.uploadProfileImage') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="uploadImageModalLabel">Upload Profile Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="text-align:center;">
          <img id="modalImagePreview" src="{{ $student->image ? asset('storage/' . $student->image) : asset('images/pfp.png') }}" alt="Preview" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 16px;" />
          <input type="file" name="profile_image" id="profileImageInput" accept="image/*" style="display:none;" onchange="previewProfileImage(event)">
          <br>
          <button type="button" class="btn btn-secondary mt-2" onclick="document.getElementById('profileImageInput').click();">Choose Image</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function previewProfileImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('modalImagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>

</x-app-layout>
