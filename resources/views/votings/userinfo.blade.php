@push('styles')
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/userInfo.css') }}">

    @endpush

<x-app-layout>    


    <div class="page-container">
        <!-- Background Image -->
        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132" alt="CSG Logo" class="background-logo" />

        <!-- Header Title -->
        <section class="user-info-container">
            <h1 class="user-info-title">User Information</h1>
            <hr class="user-info-divider" />
        </section>

        <!-- Profile Section -->
        <main class="profile-container">
            <section class="profile-wrapper">
                <img src="images/pfp.png" alt="Profile" class="profile-image" />
                
                <!-- User Information Form -->
                <form class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" placeholder="First name" class="form-input" aria-label="First name" />
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Email" class="form-input" aria-label="Email" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" placeholder="Middle Initial" class="form-input" aria-label="Middle Initial" />
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Program" class="form-input" aria-label="Program" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" placeholder="Last Name" class="form-input" aria-label="Last Name" />
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Student Number" class="form-input" aria-label="Student Number" />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <input type="date" placeholder="Birth Date" class="form-input" aria-label="Birth Date" />
                        </div>
                        <div class="form-group">
                            <input type="number" placeholder="Year" class="form-input" aria-label="Year" min="1" max="5" />
                        </div>
                    </div>
                </form>
            </section>
        </main>

        <!-- Candidates Section -->
        <section class="candidate-container">
            <h1 class="candidate-title">My Candidates</h1>
        </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</x-app-layout>
