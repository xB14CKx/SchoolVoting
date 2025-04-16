@push('styles')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/elect.css" />

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
                    <div class="admin-actions"></div>

<!-- School Year 2025 -->
  <div class="dropdown">
    <button class="action-button year-selector-button" id="yearDropdownButton">
        <span class="button-text">School Year 2025</span>
    </button>
    <ul class="dropdown-menu hidden" id="yearDropdown">
    </ul>
  </div>

    </div>
    <hr class="elect-divider" />
    </section>

    <!-- Position Sections -->
    @php
        $positions = [
            'President',
            'Vice President',
            'Secretary',
            'Treasurer',
            'Auditor',
            'Student PIO',
            'Business Manager'
        ];

        // Helper function to format program name
        function formatProgramName($programName) {
            if (str_contains($programName, 'BS in ')) {
                // Handle BS programs
                $words = explode(' ', $programName);

                // Check if it's a program with a major/specialization
                if (str_contains($programName, '–')) {
                    [$mainProgram, $major] = explode('–', $programName);
                    $mainWords = array_filter(explode(' ', trim($mainProgram)), function($word) {
                        return strtolower($word) !== 'and';
                    });
                    $initials = 'BS' . implode('', array_map(fn($word) => substr($word, 0, 1), array_slice($mainWords, 2)));

                    // Special handling for BSEMC
                    if (str_contains($mainProgram, 'Entertainment and Multimedia Computing')) {
                        return 'BSEMC - ' . trim($major);
                    }
                    return $initials . ' - ' . trim($major);
                } else {
                    // Regular BS program without major
                    $filteredWords = array_filter($words, fn($word) => strtolower($word) !== 'and');
                    $initials = 'BS' . implode('', array_map(fn($word) => substr($word, 0, 1), array_slice($filteredWords, 2)));
                    return $initials;
                }
            } elseif (str_contains($programName, 'Bachelor of Multimedia Arts')) {
                return 'BMA'; // Special case for Multimedia Arts
            } elseif (str_contains($programName, 'Bachelor of ')) {
                // Handle other Bachelor programs
                $name = str_replace('Bachelor of ', 'B', $programName);
                $words = array_filter(explode(' ', $name), fn($word) => strtolower($word) !== 'and');
                return 'B' . implode('', array_map(fn($word) => substr($word, 0, 1), $words));
            }
            return $programName;
        }
    @endphp

    @foreach($positions as $position)
        @php
            $positionCandidates = $candidates->where('position.name', $position);
        @endphp

        @if($positionCandidates->count() > 0)
            <div class="position-row">
                <header class="role-label">{{ $position }}</header>
                <div class="candidates-wrapper">
                    @foreach($positionCandidates as $candidate)
                        <article class="candidate-card">
                            <figure class="candidate-figure">
                                <button class="info-button"
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#candidateInfoModal"
                                        data-candidate-id="{{ $candidate->id }}"
                                        data-candidate-name="{{ $candidate->last_name }}, {{ $candidate->first_name }} {{ $candidate->middle_name ? substr($candidate->middle_name, 0, 1) . '.' : '' }}"
                                        data-position="{{ $position }}"
                                        data-partylist="{{ $candidate->partylist->partylist_name }}"
                                        data-year-level="{{ $candidate->year_level }}"
                                        data-program="{{ formatProgramName($candidate->program->program_name) }}"
                                        data-platform="{{ $candidate->platform ?? 'No platform provided.' }}"
                                        data-image="{{ $candidate->image ? asset('storage/' . $candidate->image) : 'https://cdn.builder.io/api/v1/image/assets/TEMP/de0659c99b4ca6a15bf7ac5e4731ad3f4af4662d' }}">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                <img
                                    src="{{ $candidate->image ? asset('storage/' . $candidate->image) : 'https://cdn.builder.io/api/v1/image/assets/TEMP/de0659c99b4ca6a15bf7ac5e4731ad3f4af4662d' }}"
                                    alt="{{ $candidate->first_name }} {{ $candidate->last_name }}"
                                    class="profile-image"
                                />
                                <figcaption class="candidate-details">
                                    <div class="candidate-name">{{ $candidate->last_name }}, {{ $candidate->first_name }} {{ $candidate->middle_name ? substr($candidate->middle_name, 0, 1) . '.' : '' }}</div>
                                    <div class="candidate-partylist">{{ $candidate->partylist->partylist_name }}</div>
                                    <div>{{ $candidate->year_level }} Year</div>
                                    <div>{{ formatProgramName($candidate->program->program_name) }}</div>
                                </figcaption>
                                <button class="vote-button" data-candidate-id="{{ $candidate->id }}">Vote</button>
                            </figure>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    <!-- Submit Vote Button -->
    <button class="submit-voteButton">
        <strong>Submit Vote</strong>
    </button>

    </div>


<div class="modal fade" id="candidateInfoModal" tabindex="-1" aria-labelledby="candidateInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="candidateInfoLabel">Candidate Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Left side - Image -->
                    <div class="col-md-5">
                        <div class="candidate-modal-image-container">
                            <img id="modalCandidateImage" src="" alt="Candidate" class="img-fluid rounded">
                        </div>
                    </div>
                    <!-- Right side - Information -->
                    <div class="col-md-7">
                        <div class="candidate-modal-info">
                            <h3 id="modalCandidateName" class="mb-3"></h3>
                            <div class="info-group">
                                <p><strong>Position:</strong> <span id="modalPosition"></span></p>
                                <p><strong>Partylist:</strong> <span id="modalPartylist"></span></p>
                                <p><strong>Year Level:</strong> <span id="modalYearLevel"></span></p>
                                <p><strong>Program:</strong> <span id="modalProgram"></span></p>
                            </div>
                            <div class="platform-section mt-4">
                                <h4>Platform:</h4>
                                <p id="modalPlatform">Platform content will be displayed here.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            const candidateInfoModal = document.getElementById('candidateInfoModal');

            if (candidateInfoModal) {
                console.log('Modal element found');
                candidateInfoModal.addEventListener('show.bs.modal', function (event) {
                    console.log('Modal show event triggered');
                    const button = event.relatedTarget;
                    console.log('Button data:', {
                        image: button.getAttribute('data-image'),
                        name: button.getAttribute('data-candidate-name'),
                        position: button.getAttribute('data-position'),
                        partylist: button.getAttribute('data-partylist'),
                        yearLevel: button.getAttribute('data-year-level'),
                        program: button.getAttribute('data-program'),
                        platform: button.getAttribute('data-platform')
                    });

                    try {
                        document.getElementById('modalCandidateImage').src = button.getAttribute('data-image');
                        document.getElementById('modalCandidateName').textContent = button.getAttribute('data-candidate-name');
                        document.getElementById('modalPosition').textContent = button.getAttribute('data-position');
                        document.getElementById('modalPartylist').textContent = button.getAttribute('data-partylist');
                        document.getElementById('modalYearLevel').textContent = button.getAttribute('data-year-level');
                        document.getElementById('modalProgram').textContent = button.getAttribute('data-program');
                        document.getElementById('modalPlatform').textContent = button.getAttribute('data-platform');
                    } catch (error) {
                        console.error('Error updating modal content:', error);
                    }
                });
            } else {
                console.error('Modal element not found');
            }
        });
    </script>
</x-app-layout>
