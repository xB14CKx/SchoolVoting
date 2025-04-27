@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@200;400;700&family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @vite(['resources/css/elect.css'])
@endpush

<x-app-layout>
    <div class="page-container">
        <!-- Background Image -->
        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132"
             alt="CSG Logo"
             class="background-logo" />

        <!-- Header Title and Divider -->
        <div class="content-wrapper">
            <section class="elect-container">
                <div class="elect-header">
                    <h1 class="elect-title">Elect</h1>
                    <div class="admin-actions">
                        <!-- Add admin action buttons or content here if needed -->
                    </div>

                    <!-- School Year Dropdown -->
                    <div class="dropdown">
                        <button class="action-button year-selector-button" id="yearDropdownButton" type="button">
                            <svg class="chevron-icon" width="24" height="24" viewBox="0 0 29 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.07129 8.68604L14.1425 14.4768L21.2137 8.68604" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="button-text">School Year 2025</span>
                        </button>
                        <ul class="dropdown-menu" id="yearDropdown"></ul>
                    </div>
                </div>
                <hr class="elect-divider" />
            </section>

            <!-- Candidate Section -->
            <section class="position-section">
                @foreach ($positions as $position)
                    <div class="position-row">
                        <header class="role-label">{{ $position->name }}</header>
                        <div class="candidates-wrapper">
                            @foreach ($position->candidates as $candidate)
                                <article class="candidate-card">
                                    <figure class="candidate-figure">
                                        <img src="{{ $candidate->image_url ?? 'https://cdn.builder.io/api/v1/image/assets/TEMP/de0659c99b4ca6a15bf7ac5e4731ad3f4af4662d' }}"
                                             alt="{{ $candidate->name }} Profile"
                                             class="profile-image" />
                                        <figcaption class="candidate-details">
                                            <span class="candidate-name">{{ $candidate->name }}</span><br />
                                            <span>{{ $candidate->program }}</span><br />
                                            <span class="candidate-partylist">{{ $candidate->partylist }}</span>
                                        </figcaption>
                                        <button class="vote-button">Vote</button>
                                        <button class="info-button" data-bs-toggle="modal" data-bs-target="#candidateModal"
                                                data-name="{{ $candidate->name }}"
                                                data-program="{{ $candidate->program }}"
                                                data-partylist="{{ $candidate->partylist }}"
                                                data-image="{{ $candidate->image_url ?? 'https://cdn.builder.io/api/v1/image/assets/TEMP/de0659c99b4ca6a15bf7ac5e4731ad3f4af4662d' }}"
                                                data-platform="{{ $candidate->platform ?? 'No platform provided' }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </figure>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </section>

            <!-- Submit Vote Button -->
            <button class="submit-voteButton"><strong>Submit Vote</strong></button>
        </div>
    </div>

    <!-- Candidate Modal -->
    <div class="modal fade" id="candidateModal" tabindex="-1" aria-labelledby="candidateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="candidateModalLabel">Candidate Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="candidate-modal-image-container">
                        <img src="" alt="Candidate Profile" id="modalImage" />
                    </div>
                    <div class="candidate-modal-info">
                        <div class="info-group">
                            <p><strong>Name:</strong> <span id="modalName"></span></p>
                            <p><strong>Program:</strong> <span id="modalProgram"></span></p>
                            <p><strong>Partylist:</strong> <span id="modalPartylist"></span></p>
                        </div>
                        <div class="platform-section">
                            <h4>Platform</h4>
                            <p id="modalPlatform"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // School Year Dropdown
                const dropdownButton = document.getElementById("yearDropdownButton");
                const dropdownMenu = document.getElementById("yearDropdown");

                for (let year = 2025; year <= 2035; year++) {
                    let listItem = document.createElement("li");
                    let link = document.createElement("a");
                    link.className = "dropdown-item";
                    link.textContent = `School Year ${year}`;
                    link.href = "#";
                    link.addEventListener("click", function (e) {
                        e.preventDefault();
                        dropdownButton.querySelector(".button-text").textContent = `School Year ${year}`;
                        dropdownMenu.classList.remove("show");
                    });
                    listItem.appendChild(link);
                    dropdownMenu.appendChild(listItem);
                }

                dropdownButton.addEventListener("click", function (event) {
                    event.stopPropagation();
                    dropdownMenu.classList.toggle("show");
                });

                document.addEventListener("click", function (event) {
                    if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                        dropdownMenu.classList.remove("show");
                    }
                });

                // Modal Population
                document.querySelectorAll('.info-button').forEach(button => {
                    button.addEventListener('click', function () {
                        const modal = document.getElementById('candidateModal');
                        modal.querySelector('#modalName').textContent = this.dataset.name;
                        modal.querySelector('#modalProgram').textContent = this.dataset.program;
                        modal.querySelector('#modalPartylist').textContent = this.dataset.partylist;
                        modal.querySelector('#modalPlatform').textContent = this.dataset.platform;
                        modal.querySelector('#modalImage').src = this.dataset.image;
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
