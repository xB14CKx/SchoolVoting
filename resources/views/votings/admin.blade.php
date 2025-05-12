@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
@endpush

<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="page-container">
        <!-- Background Image -->
        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132"
             alt="CSG Logo"
             class="background-logo" />

        <!-- Header Title and Divider -->
        <div class="content-wrapper">
            <section class="admin-container">
                <div class="admin-header">
                    <h1 class="admin-title">Admin</h1>
                    <div class="admin-actions"></div>

                    <!-- School Year Dropdown -->
                    <div class="dropdown">
                        <button class="action-button year-selector-button" id="yearDropdownButton">
                            <svg class="chevron-icon" width="24" height="24" viewBox="0 0 29 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.07129 8.68604L14.1425 14.4768L21.2137 8.68604" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span class="button-text">School Year 2025</span>
                        </button>
                        <ul class="dropdown-menu hidden" id="yearDropdown"></ul>
                    </div>
                </div>
                <hr class="admin-divider" />
            </section>

            <!-- Candidate Card Template (hidden) -->
            <template id="candidateCardTemplate">
                <article class="candidate-card">
                    <button class="more-options-button" aria-label="More options">
                        <svg class="more-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="5" r="2" fill="#1D1B20"/>
                            <circle cx="12" cy="12" r="2" fill="#1D1B20"/>
                            <circle cx="12" cy="19" r="2" fill="#1D1B20"/>
                        </svg>
                    </button>
                    <div class="options-menu hidden">
                        <button class="option-button">Edit</button>
                        <button class="option-button">Delete</button>
                    </div>
                    <figure class="candidate-figure">
                        <img src="" class="candidate-image" alt="Candidate">
                        <figcaption class="candidate-details">
                            Name
                            <br />
                            Program
                            <br />
                            Partylist
                        </figcaption>
                    </figure>
                </article>
            </template>

            <!-- Card Details -->
            <section class="ballot-container">
                <div class="ballot-content">
                    <div class="section">
                        <div class="position-wrapper">
                            <h2 class="position-title">President</h2>
                            <div class="candidate-grid" id="presidentCandidates">
                                <button class="add-candidate-button" data-position="presidentCandidates">
                                    <img src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
                                         class="add-candidate-icon"
                                         alt="plus/add" />
                                </button>
                            </div>
                        </div>

                        <!-- Vice President -->
                        <div class="position-wrapper">
                            <h2 class="position-title">Vice President</h2>
                            <div class="candidate-grid" id="vicePresidentCandidates">
                                <button class="add-candidate-button" data-position="vicePresidentCandidates">
                                    <img src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
                                         class="add-candidate-icon"
                                         alt="plus/add" />
                                </button>
                            </div>
                        </div>

                        <!-- Secretary -->
                        <div class="position-wrapper">
                            <h2 class="position-title">Secretary</h2>
                            <div class="candidate-grid" id="secretaryCandidates">
                                <button class="add-candidate-button" data-position="secretaryCandidates">
                                    <img src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
                                         class="add-candidate-icon"
                                         alt="plus/add" />
                                </button>
                            </div>
                        </div>

                        <!-- Treasurer -->
                        <div class="position-wrapper">
                            <h2 class="position-title">Treasurer</h2>
                            <div class="candidate-grid" id="treasurerCandidates">
                                <button class="add-candidate-button" data-position="treasurerCandidates">
                                    <img src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
                                         class="add-candidate-icon"
                                         alt="plus/add" />
                                </button>
                            </div>
                        </div>

                        <!-- Auditor -->
                        <div class="position-wrapper">
                            <h2 class="position-title">Auditor</h2>
                            <div class="candidate-grid" id="auditorCandidates">
                                <button class="add-candidate-button" data-position="auditorCandidates">
                                    <img src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
                                         class="add-candidate-icon"
                                         alt="plus/add" />
                                </button>
                            </div>
                        </div>

                        <!-- PIO -->
                        <div class="position-wrapper">
                            <h2 class="position-title">PIO</h2>
                            <div class="candidate-grid" id="PIOCandidates">
                                <button class="add-candidate-button" data-position="PIOCandidates">
                                    <img src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
                                         class="add-candidate-icon"
                                         alt="plus/add" />
                                </button>
                            </div>
                        </div>

                        <!-- Business Manager -->
                        <div class="position-wrapper">
                            <h2 class="position-title">Business Manager</h2>
                            <div class="candidate-grid" id="businessManagerCandidates">
                                <button class="add-candidate-button" data-position="businessManagerCandidates">
                                    <img src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
                                         class="add-candidate-icon"
                                         alt="plus/add" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Open Election button -->
            <button class="open-electionButton {{ $currentElection->status === 'Open' ? 'close-election' : '' }}" id="electionActionButton">
                <strong>{{ $currentElection->status === 'Open' ? 'Close Election' : 'Open Election' }}</strong>
            </button>

            <!-- Open Election Modal -->
            <div class="modal fade" id="openElectionModal" tabindex="-1" aria-labelledby="openElectionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="openElectionLabel">Open Election</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to open the election for this school year?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmOpenElection">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Close Election Modal -->
            <div class="modal fade" id="closeElectionModal" tabindex="-1" aria-labelledby="closeElectionLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="closeElectionLabel">Close Election</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to close the election for this school year?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmCloseElection">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Candidate Modal -->
            <div class="modal fade" id="addCandidateModal" tabindex="-1" aria-labelledby="addCandidateLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content p-4">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCandidateLabel">Add Candidate</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Student ID Search -->
                            <div class="mb-3">
                                <label for="studentIdSearch" class="form-label">Student ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="studentIdSearch" placeholder="Enter Student ID">
                                    <button class="btn btn-outline-secondary" type="button" id="searchStudentBtn">Search</button>
                                </div>
                                <div id="studentSearchFeedback" class="form-text text-danger d-none"></div>
                            </div>

                            <!-- Image Upload -->
                            <h6>Image Upload</h6>
                            <br>
                            <div class="mb-3 text-center">
                                <div id="imagePreview" class="mb-2">
                                    <img src="#" alt="Preview" id="previewImg" class="img-thumbnail d-none" width="150">
                                </div>
                                <input class="form-control" type="file" id="candidateImage" accept="image/*">
                            </div>
                            <hr />

                            <!-- Position + Partylist -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="candidatePosition" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="candidatePosition">
                                </div>
                                <div class="col-md-6">
                                    <label for="candidatePartylist" class="form-label">Partylist</label>
                                    <select class="form-select" id="candidatePartylist" readonly>
                                        <option disabled selected>Choose a partylist</option>
                                        @foreach ($partylists as $party)
                                            <option value="{{ $party->partylist_id }}">{{ $party->partylist_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="candidateLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="candidateLastName">
                                </div>
                                <div class="col-md-4">
                                    <label for="candidateFirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="candidateFirstName">
                                </div>
                                <div class="col-md-3">
                                    <label for="candidateMiddleName" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" id="candidateMiddleName">
                                </div>
                            </div>
                            <br>
                            <!-- Year Level + Program -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="candidateYearLevel" class="form-label">Year Level</label>
                                    <select class="form-select" id="candidateYearLevel">
                                        <option value="1st">1st Year</option>
                                        <option value="2nd">2nd Year</option>
                                        <option value="3rd">3rd Year</option>
                                        <option value="4th">4th Year</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="candidateProgram" class="form-label">Program</label>
                                    <select class="form-select" id="candidateProgram">
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <!-- Platform -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="candidatePlatform" class="form-label">Platform</label>
                                    <textarea class="form-control" id="candidatePlatform" rows="4" placeholder="Enter candidate's platform"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="saveCandidateBtn">Save Candidate</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Candidate Modal -->
            <div class="modal fade" id="editCandidateModal" tabindex="-1" aria-labelledby="editCandidateLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content p-4">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCandidateLabel">Edit Candidate</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Student ID (non-editable) -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editCandidateStudentId" class="form-label">Student ID</label>
                                    <input type="text" class="form-control" id="editCandidateStudentId" readonly>
                                </div>
                            </div>
                            <!-- Image Upload (editable) -->
                            <h6>Image Upload</h6>
                            <br>
                            <div class="mb-3 text-center">
                                <div id="editImagePreview" class="mb-2">
                                    <img src="#" alt="Preview" id="editPreviewImg" class="img-thumbnail d-none" width="150">
                                </div>
                                <input class="form-control" type="file" id="editCandidateImage" accept="image/*">
                            </div>
                            <hr />
                            <!-- Position + Partylist -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editCandidatePosition" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="editCandidatePosition" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="editCandidatePartylist" class="form-label">Partylist</label>
                                    <select class="form-select" id="editCandidatePartylist">
                                        <option disabled selected>Choose a partylist</option>
                                        @foreach ($partylists as $party)
                                            <option value="{{ $party->partylist_id }}">{{ $party->partylist_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="editCandidateLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="editCandidateLastName" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="editCandidateFirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="editCandidateFirstName" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="editCandidateMiddleName" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" id="editCandidateMiddleName" readonly>
                                </div>
                            </div>
                            <br>
                            <!-- Year Level + Program -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editCandidateYearLevel" class="form-label">Year Level</label>
                                    <select class="form-select" id="editCandidateYearLevel" disabled>
                                        <option value="1st">1st Year</option>
                                        <option value="2nd">2nd Year</option>
                                        <option value="3rd">3rd Year</option>
                                        <option value="4th">4th Year</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editCandidateProgram" class="form-label">Program</label>
                                    <select class="form-select" id="editCandidateProgram" disabled>
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Platform (editable) -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="editCandidatePlatform" class="form-label">Platform</label>
                                    <textarea class="form-control" id="editCandidatePlatform" rows="4" placeholder="Enter candidate's platform"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="updateCandidateBtn">Update Candidate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const candidateStoreUrl = "{{ route('candidates.store') }}";
            const candidatesByYearUrl = "{{ route('candidates.byYear', ['year' => ':year']) }}";
            const electionOpenUrl = "{{ route('elections.open', ['election' => $currentElection->election_id]) }}";
            const electionCloseUrl = "{{ route('elections.close', ['election' => $currentElection->election_id]) }}";
            const candidates = @json($candidates);
            let currentEditingCandidateId = null;

            document.addEventListener("DOMContentLoaded", function () {
                // Initialize existing candidates
                candidates.forEach(candidate => {
                    const container = document.getElementById(getPositionContainerId(candidate.position.position_name));
                    if (container) {
                        const card = createCandidateCard(candidate);
                        container.insertBefore(card, container.querySelector('.add-candidate-button'));
                    }
                });

                // Check card limits for all positions after initialization
                const positionIds = [
                    'presidentCandidates',
                    'vicePresidentCandidates',
                    'secretaryCandidates',
                    'treasurerCandidates',
                    'auditorCandidates',
                    'PIOCandidates',
                    'businessManagerCandidates'
                ];
                positionIds.forEach(id => checkCardLimit(id));

                // Election open/close button logic
                const electionActionButton = document.getElementById('electionActionButton');
                const openElectionModal = new bootstrap.Modal(document.getElementById('openElectionModal'));
                const closeElectionModal = new bootstrap.Modal(document.getElementById('closeElectionModal'));
                let electionStatus = "{{ $currentElection->status }}";

                // Set initial button state
                function updateButtonState(status) {
                    electionStatus = status;
                    electionActionButton.innerHTML = `<strong>${status === 'Open' ? 'Close Election' : 'Open Election'}</strong>`;
                    if (status === 'Open') {
                        electionActionButton.classList.add('close-election');
                    } else {
                        electionActionButton.classList.remove('close-election');
                    }
                }

                // Initialize button state
                updateButtonState(electionStatus);

                // Handle button click to show appropriate modal
                electionActionButton.addEventListener('click', function() {
                    if (electionStatus === 'Open') {
                        closeElectionModal.show();
                    } else {
                        openElectionModal.show();
                    }
                });

                // Handle confirm open election
                document.getElementById('confirmOpenElection').addEventListener('click', function() {
                    fetch(electionOpenUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateButtonState('Open');
                            openElectionModal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message || 'Election opened successfully.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to open election.',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error opening election:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: `Error opening election: ${error.message || 'Unknown error'}`,
                            confirmButtonText: 'OK'
                        });
                    });
                });

                // Handle confirm close election
                document.getElementById('confirmCloseElection').addEventListener('click', function() {
                    fetch(electionCloseUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateButtonState('Closed');
                            closeElectionModal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message || 'Election closed successfully.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to close election.',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error closing election:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: `Error closing election: ${error.message || 'Unknown error'}`,
                            confirmButtonText: 'OK'
                        });
                    });
                });

                // Attach event listeners for more-options-button
                function attachMoreOptionsListeners() {
                    document.querySelectorAll('.more-options-button').forEach(button => {
                        button.removeEventListener('click', handleMoreOptionsClick); // Prevent duplicates
                        button.addEventListener('click', handleMoreOptionsClick);
                    });
                }

                function handleMoreOptionsClick(event) {
                    event.stopPropagation();
                    const card = this.closest('.candidate-card');
                    const menu = card.querySelector('.options-menu');
                    // Close all other menus
                    document.querySelectorAll('.options-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    // Toggle the current menu
                    menu.classList.toggle('hidden');
                }

                // Close all menus when clicking outside
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.more-options-button') && !event.target.closest('.options-menu')) {
                        document.querySelectorAll('.options-menu').forEach(menu => {
                            menu.classList.add('hidden');
                        });
                    }
                });

                // Attach event listeners for option buttons (Edit/Delete)
                function attachOptionButtonListeners() {
                    document.querySelectorAll('.option-button').forEach(button => {
                        button.removeEventListener('click', handleOptionButtonClick); // Prevent duplicates
                        button.addEventListener('click', handleOptionButtonClick);
                    });
                }

                function handleOptionButtonClick() {
                    const card = this.closest('.candidate-card');
                    const candidateId = card.dataset.candidateId;

                    if (this.textContent === 'Delete') {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'Do you want to delete this candidate?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`/candidates/${candidateId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        return response.text().then(text => {
                                            try {
                                                return Promise.reject(JSON.parse(text));
                                            } catch (e) {
                                                return Promise.reject(new Error(text));
                                            }
                                        });
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        const container = card.closest('.candidate-grid');
                                        card.remove();
                                        checkCardLimit(container.id);
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Deleted',
                                            text: 'Candidate deleted successfully.',
                                            confirmButtonText: 'OK'
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: data.message || 'Failed to delete candidate.',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error("Error deleting candidate:", error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Error deleting candidate: ' + (error.message || 'Unknown error'),
                                        confirmButtonText: 'OK'
                                    });
                                });
                            }
                        });
                    } else if (this.textContent === 'Edit') {
                        currentEditingCandidateId = candidateId;
                        const candidate = candidates.find(c => c.candidate_id == candidateId);
                        if (candidate) {
                            setEditCandidateFields(candidate);
                            const previewImg = document.getElementById("editPreviewImg");
                            if (candidate.image) {
                                previewImg.src = '{{ asset('storage/') }}/' + candidate.image;
                                previewImg.classList.remove("d-none");
                            } else {
                                previewImg.classList.add("d-none");
                            }
                            const editModal = new bootstrap.Modal(document.getElementById("editCandidateModal"));
                            editModal.show();
                        }
                    }
                }

                // Initial attachment of listeners
                attachMoreOptionsListeners();
                attachOptionButtonListeners();

                // Modal Trigger for Add Candidate
                const addButtons = document.querySelectorAll('.add-candidate-button');
                addButtons.forEach(btn => {
                    btn.addEventListener('click', function () {
                        const targetId = btn.getAttribute('data-position');
                        const container = document.getElementById(targetId);
                        const currentCards = container.querySelectorAll('.candidate-card').length;
                        if (currentCards >= 2) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Limit Reached',
                                text: 'You can only add up to 2 candidates for this position.',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }
                        const positionName = getPositionTitle(targetId);
                        document.getElementById("candidatePosition").value = positionName;
                        document.getElementById("addCandidateLabel").textContent = `Add Candidate for ${positionName}`;
                        document.getElementById("candidateImage").value = '';
                        document.getElementById("previewImg").classList.add("d-none");
                        const modal = new bootstrap.Modal(document.getElementById("addCandidateModal"));
                        modal.show();
                    });
                });

                function checkCardLimit(containerId) {
                    const container = document.getElementById(containerId);
                    const addBtn = container.querySelector('.add-candidate-button');
                    const cardCount = container.querySelectorAll('.candidate-card').length;
                    if (cardCount >= 2) {
                        addBtn.style.visibility = 'hidden';
                        addBtn.style.pointerEvents = 'none';
                    } else {
                        addBtn.style.visibility = 'visible';
                        addBtn.style.pointerEvents = 'auto';
                    }
                }

                document.getElementById("candidateImage").addEventListener("change", function () {
                    const file = this.files[0];
                    const preview = document.getElementById("previewImg");
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            preview.src = e.target.result;
                            preview.classList.remove("d-none");
                        };
                        reader.readAsDataURL(file);
                    }
                });

                document.getElementById("editCandidateImage").addEventListener("change", function () {
                    const file = this.files[0];
                    const preview = document.getElementById("editPreviewImg");
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            preview.src = e.target.result;
                            preview.classList.remove("d-none");
                        };
                        reader.readAsDataURL(file);
                    }
                });

                function createCandidateCard(candidateData) {
                    let programName = candidateData.program.program_name;
                    if (programName.includes('BS in ')) {
                        const words = programName.split(' ');
                        if (programName.includes('–')) {
                            const [mainProgram, major] = programName.split('–').map(part => part.trim());
                            const mainWords = mainProgram.split(' ').filter(word => word.toLowerCase() !== 'and');
                            const initials = mainWords.slice(2).map(word => word.charAt(0)).join('');
                            if (mainProgram.includes('Entertainment and Multimedia Computing')) {
                                programName = 'BSEMC - ' + major;
                            } else {
                                programName = 'BS' + initials + ' - ' + major;
                            }
                        } else {
                            const filteredWords = words.filter(word => word.toLowerCase() !== 'and');
                            const initials = filteredWords.slice(2).map(word => word.charAt(0)).join('');
                            programName = 'BS' + initials;
                        }
                    } else if (programName.includes('Bachelor of Multimedia Arts')) {
                        programName = 'BMA';
                    } else if (programName.includes('Bachelor of ')) {
                        programName = programName.replace('Bachelor of ', 'B');
                        const words = programName.split(' ').filter(word => word.toLowerCase() !== 'and');
                        const initials = words.map(word => word.charAt(0)).join('');
                        programName = initials;
                    }

                    const cardHTML = `
                        <article class="candidate-card" data-candidate-id="${candidateData.candidate_id}">
                            <button class="more-options-button" aria-label="More options">
                                <svg class="more-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="5" r="2" fill="#1D1B20"/>
                                    <circle cx="12" cy="12" r="2" fill="#1D1B20"/>
                                    <circle cx="12" cy="19" r="2" fill="#1D1B20"/>
                                </svg>
                            </button>
                            <div class="options-menu hidden">
                                <button class="option-button">Edit</button>
                                <button class="option-button">Delete</button>
                            </div>
                            <figure class="candidate-figure">
                                <img src="${candidateData.image ? '{{ asset('storage/') }}/' + candidateData.image : 'https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/567fdf519eb08658d3207d7508f0b1db1ca7b3a2'}"
                                     class="candidate-image"
                                     alt="Candidate">
                                <figcaption class="candidate-details">
                                    <div class="candidate-name">${candidateData.last_name}, ${candidateData.first_name} ${candidateData.middle_name ? candidateData.middle_name.charAt(0) + '.' : ''}</div>
                                    <div class="candidate-partylist">${candidateData.partylist.partylist_name}</div>
                                    <div>${candidateData.year_level} Year</div>
                                    <div>${programName}</div>
                                </figcaption>
                            </figure>
                        </article>
                    `;
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = cardHTML.trim();
                    const card = wrapper.firstElementChild;
                    // Attach listeners to the new card
                    card.querySelector('.more-options-button').addEventListener('click', handleMoreOptionsClick);
                    card.querySelectorAll('.option-button').forEach(button => {
                        button.addEventListener('click', handleOptionButtonClick);
                    });
                    return card;
                }

                function getPositionContainerId(positionName) {
                    const map = {
                        "President": "presidentCandidates",
                        "Vice President": "vicePresidentCandidates",
                        "Secretary": "secretaryCandidates",
                        "Treasurer": "treasurerCandidates",
                        "Auditor": "auditorCandidates",
                        "PIO": "PIOCandidates",
                        "Business Manager": "businessManagerCandidates"
                    };
                    return map[positionName] || null;
                }

                function getPositionTitle(id) {
                    const map = {
                        presidentCandidates: "President",
                        vicePresidentCandidates: "Vice President",
                        secretaryCandidates: "Secretary",
                        treasurerCandidates: "Treasurer",
                        auditorCandidates: "Auditor",
                        PIOCandidates: "PIO",
                        businessManagerCandidates: "Business Manager"
                    };
                    return map[id] || "Position";
                }

                function getPositionId(positionName) {
                    const map = {
                        "President": 1,
                        "Vice President": 2,
                        "Secretary": 3,
                        "Treasurer": 4,
                        "Auditor": 5,
                        "PIO": 6,
                        "Business Manager": 7
                    };
                    return map[positionName] || null;
                }

                // Function to clear all candidate grids
                function clearCandidateGrids() {
                    const grids = [
                        'presidentCandidates',
                        'vicePresidentCandidates',
                        'secretaryCandidates',
                        'treasurerCandidates',
                        'auditorCandidates',
                        'PIOCandidates',
                        'businessManagerCandidates'
                    ];
                    grids.forEach(gridId => {
                        const container = document.getElementById(gridId);
                        const addButton = container.querySelector('.add-candidate-button');
                        container.innerHTML = '';
                        container.appendChild(addButton);
                        checkCardLimit(gridId);
                    });
                }

                // Function to fetch candidates by year and update grids
                function fetchCandidatesByYear(year) {
                    const url = candidatesByYearUrl.replace(':year', year);
                    fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            clearCandidateGrids();
                            candidates.length = 0; // Clear the candidates array
                            data.candidates.forEach(candidate => {
                                candidates.push(candidate); // Update the candidates array
                                const container = document.getElementById(getPositionContainerId(candidate.position.position_name));
                                if (container) {
                                    const card = createCandidateCard(candidate);
                                    container.insertBefore(card, container.querySelector('.add-candidate-button'));
                                }
                            });
                            positionIds.forEach(id => checkCardLimit(id));
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'No candidates found for the selected year.',
                                confirmButtonText: 'OK'
                            });
                            clearCandidateGrids();
                            candidates.length = 0; // Clear the candidates array
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching candidates:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error fetching candidates: ' + (error.message || 'Unknown error'),
                            confirmButtonText: 'OK'
                        });
                        clearCandidateGrids();
                        candidates.length = 0; // Clear the candidates array
                    });
                }

                // School Year Dropdown
                const dropdownButton = document.getElementById("yearDropdownButton");
                const dropdownMenu = document.getElementById("yearDropdown");
                // Load from localStorage or use default
                let schoolYears = JSON.parse(localStorage.getItem('schoolYears')) || [2025, 2026, 2027, 2028, 2029, 2030];

                function saveSchoolYears() {
                    localStorage.setItem('schoolYears', JSON.stringify(schoolYears));
                }

                function renderSchoolYearDropdown() {
                    dropdownMenu.innerHTML = '';
                    schoolYears.forEach(year => {
                        let listItem = document.createElement("li");
                        listItem.textContent = `School Year ${year}`;
                        listItem.addEventListener("click", function () {
                            dropdownButton.querySelector(".button-text").textContent = `School Year ${year}`;
                            dropdownMenu.classList.remove("show");
                            fetchCandidatesByYear(year);
                        });
                        dropdownMenu.appendChild(listItem);
                    });
                    // Add button to increment year
                    let addBtn = document.createElement("button");
                    addBtn.textContent = "+ Add School Year";
                    addBtn.className = "add-school-year-btn";
                    addBtn.onclick = function(e) {
                        e.stopPropagation(); // Prevent dropdown from closing
                        const lastYear = schoolYears[schoolYears.length - 1];
                        schoolYears.push(lastYear + 1);
                        // Persist to localStorage
                        localStorage.setItem('schoolYears', JSON.stringify(schoolYears));
                        renderSchoolYearDropdown();
                        dropdownMenu.classList.add("show"); // Keep dropdown open
                    };
                    dropdownMenu.appendChild(addBtn);
                    // Make dropdown scrollable if more than 6 years
                    if (schoolYears.length > 6) {
                        dropdownMenu.style.maxHeight = '240px';
                        dropdownMenu.style.overflowY = 'auto';
                    } else {
                        dropdownMenu.style.maxHeight = '';
                        dropdownMenu.style.overflowY = '';
                    }
                }

                // Load schoolYears from localStorage if available
                const storedYears = localStorage.getItem('schoolYears');
                if (storedYears) {
                    try {
                        const parsed = JSON.parse(storedYears);
                        if (Array.isArray(parsed) && parsed.length > 0) {
                            schoolYears = parsed;
                        }
                    } catch (e) {}
                }

                renderSchoolYearDropdown();

                dropdownButton.addEventListener("click", function (event) {
                    event.stopPropagation();
                    dropdownMenu.classList.toggle("show");
                });

                document.addEventListener("click", function (event) {
                    if (!dropdownButton.contains(event.target)) {
                        dropdownMenu.classList.remove("show");
                    }
                });

                // Handle candidate addition
                document.getElementById('saveCandidateBtn').addEventListener('click', function () {
                    const platform = document.getElementById("candidatePlatform").value;
                    const formData = new FormData();
                    const imageFile = document.getElementById("candidateImage").files[0];
                    if (imageFile) {
                        formData.append('image', imageFile);
                    }
                    formData.append('position_id', getPositionId(document.getElementById("candidatePosition").value));
                    formData.append('partylist_id', document.getElementById("candidatePartylist").value);
                    formData.append('first_name', document.getElementById("candidateFirstName").value);
                    formData.append('last_name', document.getElementById("candidateLastName").value);
                    formData.append('middle_name', document.getElementById("candidateMiddleName").value);
                    formData.append('year_level', document.getElementById("candidateYearLevel").value);
                    formData.append('program_id', document.getElementById("candidateProgram").value);
                    formData.append('platform', platform);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                    const modal = bootstrap.Modal.getInstance(document.getElementById("addCandidateModal"));

                    fetch(candidateStoreUrl, {
                        method: "POST",
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Candidate added successfully.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    popup: 'swal2-popup-custom'
                                }
                            }).then(() => {
                                // Fetch candidates for the current year after adding a new candidate
                                const currentYear = dropdownButton.querySelector(".button-text").textContent.replace('School Year ', '');
                                fetchCandidatesByYear(currentYear);
                            });
                        } else {
                            modal.hide();
                            // Remove modal backdrop to prevent overlap
                            const backdrop = document.querySelector('.modal-backdrop');
                            if (backdrop) backdrop.remove();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to add candidate: ' + (data.message || 'Unknown error'),
                                confirmButtonText: 'OK',
                                customClass: {
                                    popup: 'swal2-popup-custom'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error submitting candidate:", error);
                        modal.hide();
                        // Remove modal backdrop to prevent overlap
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) backdrop.remove();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error submitting candidate: ' + (error.message || 'Unknown error'),
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal2-popup-custom'
                            }
                        });
                    });
                });

                // Handle update candidate
                document.getElementById('updateCandidateBtn').addEventListener('click', function () {
                    const formData = new FormData();
                    const imageFile = document.getElementById("editCandidateImage").files[0];
                    if (imageFile) {
                        formData.append('image', imageFile);
                    }
                    formData.append('partylist_id', document.getElementById("editCandidatePartylist").value);
                    formData.append('first_name', document.getElementById("editCandidateFirstName").value);
                    formData.append('last_name', document.getElementById("editCandidateLastName").value);
                    formData.append('middle_name', document.getElementById("editCandidateMiddleName").value);
                    formData.append('year_level', document.getElementById("editCandidateYearLevel").value);
                    formData.append('program_id', document.getElementById("editCandidateProgram").value);
                    formData.append('platform', document.getElementById("editCandidatePlatform").value);
                    formData.append('_method', 'PUT');
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                    fetch(`/candidates/${currentEditingCandidateId}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById("editCandidateModal"));
                            modal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Candidate updated successfully.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Fetch candidates for the current year after updating a candidate
                                const currentYear = dropdownButton.querySelector(".button-text").textContent.replace('School Year ', '');
                                fetchCandidatesByYear(currentYear);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to update candidate: ' + (data.message || 'Unknown error'),
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error updating candidate:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error updating candidate: ' + (error.message || 'Unknown error'),
                            confirmButtonText: 'OK'
                        });
                    });
                });

                // Student ID Search
                const studentIdInput = document.getElementById('studentIdSearch');
                const searchStudentBtn = document.getElementById('searchStudentBtn');
                const studentSearchFeedback = document.getElementById('studentSearchFeedback');

                const candidateFields = {
                    firstName: document.getElementById('candidateFirstName'),
                    middleName: document.getElementById('candidateMiddleName'),
                    lastName: document.getElementById('candidateLastName'),
                    program: document.getElementById('candidateProgram'),
                    yearLevel: document.getElementById('candidateYearLevel')
                };

                function setCandidateFields(data, lock) {
                    candidateFields.firstName.value = data.first_name || '';
                    candidateFields.middleName.value = data.middle_name || '';
                    candidateFields.lastName.value = data.last_name || '';
                    candidateFields.program.value = data.program_id || '';
                    candidateFields.yearLevel.value = data.year_level || '';
                    // Always keep these fields non-editable
                    candidateFields.firstName.readOnly = true;
                    candidateFields.middleName.readOnly = true;
                    candidateFields.lastName.readOnly = true;
                    if (candidateFields.program) candidateFields.program.disabled = true;
                    if (candidateFields.yearLevel) candidateFields.yearLevel.disabled = true;
                    // Only enable partylist, image, and platform
                    document.getElementById('candidatePartylist').disabled = !lock ? false : true;
                    document.getElementById('candidateImage').disabled = !lock ? false : true;
                    document.getElementById('candidatePlatform').readOnly = lock ? true : false;
                }

                const addCandidateModal = document.getElementById('addCandidateModal');
                addCandidateModal.addEventListener('show.bs.modal', function () {
                    setCandidateFields({}, true);
                    document.getElementById('candidatePartylist').disabled = true;
                    document.getElementById('candidateImage').disabled = true;
                    document.getElementById('candidatePlatform').readOnly = true;
                });

                searchStudentBtn.addEventListener('click', function() {
                    const studentId = studentIdInput.value.trim();
                    if (!studentId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Input Required',
                            text: 'Please enter a Student ID.',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    fetch(`/students/search/${studentId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.success) {
                                setCandidateFields(data.student, false); // Only enable image, partylist, platform
                                studentSearchFeedback.classList.add('d-none');
                                document.getElementById('candidatePartylist').disabled = false;
                                document.getElementById('candidateImage').disabled = false;
                                document.getElementById('candidatePlatform').readOnly = false;
                            } else {
                                setCandidateFields({}, true);
                                studentSearchFeedback.textContent = 'Student not found.';
                                studentSearchFeedback.classList.remove('d-none');
                                document.getElementById('candidatePartylist').disabled = true;
                                document.getElementById('candidateImage').disabled = true;
                                document.getElementById('candidatePlatform').readOnly = true;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Not Found',
                                    text: 'Student not found.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(() => {
                            setCandidateFields({}, true);
                            studentSearchFeedback.textContent = 'Error searching student.';
                            studentSearchFeedback.classList.remove('d-none');
                            document.getElementById('candidatePartylist').disabled = true;
                            document.getElementById('candidateImage').disabled = true;
                            document.getElementById('candidatePlatform').readOnly = true;
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error searching student.',
                                confirmButtonText: 'OK'
                            });
                        });
                });

                function setEditCandidateFields(candidate) {
                    document.getElementById("editCandidateStudentId").value = candidate.student_id || '';
                    document.getElementById("editCandidatePosition").value = candidate.position.position_name;
                    document.getElementById("editCandidatePartylist").disabled = false;
                    document.getElementById("editCandidateFirstName").value = candidate.first_name;
                    document.getElementById("editCandidateFirstName").readOnly = true;
                    document.getElementById("editCandidateLastName").value = candidate.last_name;
                    document.getElementById("editCandidateLastName").readOnly = true;
                    document.getElementById("editCandidateMiddleName").value = candidate.middle_name;
                    document.getElementById("editCandidateMiddleName").readOnly = true;
                    document.getElementById("editCandidateYearLevel").value = candidate.year_level;
                    document.getElementById("editCandidateYearLevel").disabled = true;
                    document.getElementById("editCandidateProgram").value = candidate.program_id;
                    document.getElementById("editCandidateProgram").disabled = true;
                    document.getElementById("editCandidatePlatform").value = candidate.platform || '';
                    document.getElementById("editCandidatePlatform").readOnly = false;
                    document.getElementById("editCandidateImage").disabled = false;
                }
            });
        </script>
    @endpush
</x-app-layout>
