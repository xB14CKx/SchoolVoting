@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@200;400;700&family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="{{ asset('resources/css/sweetalert-custom.css') }}">
@vite(['resources/css/elect.css', 'resources/js/app.js'])
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

        <!-- Error Message Container -->
        @if (!$isElectionOpen)
        <div id="electionStatusMessage" class="alert alert-danger" role="alert">
            This election is not currently open for voting.
        </div>
        @endif

        <!-- Candidate Section -->
@php
function formatProgram($programName) {
    if (str_contains($programName, 'BS in ')) {
        $words = explode(' ', $programName);
        if (str_contains($programName, '–')) {
            [$main, $major] = explode('–', $programName);
            $mainWords = array_filter(explode(' ', trim($main)), fn($w) => strtolower($w) !== 'and');
            $initials = 'BS' . implode('', array_map(fn($w) => substr($w,0,1), array_slice($mainWords,2)));
            if (str_contains($main, 'Entertainment and Multimedia Computing')) {
                return 'BSEMC - ' . trim($major);
            }
            return $initials . ' - ' . trim($major);
        }
        $filtered = array_filter($words, fn($w) => strtolower($w) !== 'and');
        return 'BS' . implode('', array_map(fn($w)=>substr($w,0,1), array_slice($filtered,2)));
    } elseif (str_contains($programName,'Bachelor of Multimedia Arts')) {
        return 'BMA';
    } elseif (str_contains($programName,'Bachelor of ')) {
        $name = str_replace('Bachelor of ','B',$programName);
        $words = array_filter(explode(' ',$name), fn($w)=>strtolower($w) !== 'and');
        return 'B' . implode('', array_map(fn($w)=>substr($w,0,1), $words));
    }
    return $programName;
}
@endphp

<section class="position-section">
    @foreach ($positions as $position)
        @if ($position->candidates->count() > 0)
        <div class="position-wrapper">
            <header class="role-label" style="margin-bottom:1rem;">
                {{ $position->position_name }}
            </header>
            <div class="candidate-grid">
                @foreach ($position->candidates as $candidate)
                    <article class="candidate-card">
                        <figure class="candidate-figure">
                            <button class="info-button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#candidateModal"
                                    data-candidate-id="{{ $candidate->candidate_id }}"
                                    data-candidate-name="{{ $candidate->last_name }}, {{ $candidate->first_name }}{{ $candidate->middle_name ? ' '.substr($candidate->middle_name,0,1).'.' : '' }}"
                                    data-position="{{ $position->position_name }}"
                                    data-partylist="{{ $candidate->partylist->partylist_name ?? '' }}"
                                    data-year-level="{{ $candidate->year_level }}"
                                    data-program="{{ formatProgram($candidate->program->program_name ?? '') }}"
                                    data-platform="{{ $candidate->platform ?? 'No platform provided' }}"
                                    data-image="{{ $candidate->image ? asset('storage/' . $candidate->image) : 'https://cdn.builder.io/api/v1/image/assets/TEMP/de0659c99b4ca6a15bf7ac5e4731ad3f4af4662d' }}">
                                    <i class="fas fa-info-circle"></i>
                            </button>
                            <img src="{{ $candidate->image ? asset('storage/' . $candidate->image) : 'https://cdn.builder.io/api/v1/image/assets/TEMP/de0659c99b4ca6a15bf7ac5e4731ad3f4af4662d' }}"
                            alt="{{ $candidate->first_name }} {{ $candidate->last_name }} Profile"
                            class="profile-image"/>
                            <figcaption class="candidate-details">
                                <div class="candidate-name">
                                    {{ $candidate->last_name }}, {{ $candidate->first_name }}{{ $candidate->middle_name ? ' '.substr($candidate->middle_name,0,1).'.' : '' }}
                                </div>
                                <div class="candidate-partylist">
                                    {{ $candidate->partylist->partylist_name ?? '' }}
                                </div>
                                <div>{{ $candidate->year_level }} Year</div>
                                <div>{{ formatProgram($candidate->program->program_name ?? '') }}</div>
                            </figcaption>
                            <button class="vote-button"
                                data-candidate-id="{{ $candidate->candidate_id }}"
                                data-position-id="{{ $position->position_id }}"
                                data-position-name="{{ $position->position_name }}"
                                data-candidate-first-name="{{ $candidate->first_name }}"
                                data-candidate-middle-name="{{ $candidate->middle_name }}"
                                data-candidate-last-name="{{ $candidate->last_name }}"
                                data-partylist="{{ $candidate->partylist->partylist_name ?? '' }}"
                                @if (!$isElectionOpen) disabled @endif>
                                Vote
                            </button>
                        </figure>
                    </article>
                @endforeach
            </div> {{-- /candidate-grid --}}
        </div>     {{-- /position-wrapper --}}
        @endif
    @endforeach
</section>

<!-- Submit Vote Button -->
<button class="submit-voteButton" data-election-id="{{ $election->election_id }}" @if (!$isElectionOpen) disabled @endif><strong>Submit Vote</strong></button>

</div>
</div>

<!-- Vote Review Modal -->
<div class="modal fade" id="voteReviewModal" tabindex="-1" aria-labelledby="voteReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title fw-bold" id="voteReviewModalLabel">Review Your Votes</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="voteReviewList" class="vote-review-list"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="finalSubmitVoteBtn">Submit</button>
        </div>
      </div>
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
                <div class="row">
                    <div class="col-md-5">
                        <div class="candidate-modal-image-container">
                            <img id="modalCandidateImage" src="" alt="Candidate" class="img-fluid rounded">
                        </div>
                    </div>
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
                                <p id="modalPlatform"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- JS -->
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const isElectionOpen = @json($isElectionOpen);

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

            const votes = {}; // { position_id: { candidate_id, candidate_name, partylist, position_name } }

            document.querySelectorAll('.vote-button').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (!isElectionOpen) return;

                    const positionId = this.dataset.positionId;
                    const candidateId = this.dataset.candidateId;
                    const candidateName = `${this.dataset.candidateFirstName} ${this.dataset.candidateMiddleName ? this.dataset.candidateMiddleName.charAt(0) + '.' : ''} ${this.dataset.candidateLastName}`;
                    const partylist = this.dataset.partylist;
                    const positionName = this.dataset.positionName;

                    // If already voted, cancel
                    if (this.classList.contains('btn-cancel')) {
                        delete votes[positionId];
                        this.textContent = 'Vote';
                        this.classList.remove('btn-cancel', 'btn-danger');
                        this.classList.add('btn-primary');
                        // Enable other vote buttons for this position
                        document.querySelectorAll(`.vote-button[data-position-id="${positionId}"]`).forEach(b => {
                            b.disabled = false;
                            b.classList.remove('btn-disabled');
                        });
                        return;
                    }

                    // Vote for this candidate
                    votes[positionId] = {
                        candidate_id: candidateId,
                        candidate_first_name: this.dataset.candidateFirstName,
                        candidate_middle_name: this.dataset.candidateMiddleName,
                        candidate_last_name: this.dataset.candidateLastName,
                        partylist: partylist,
                        position_name: positionName
                    };
                    // Set this button to Cancel, disable others
                    document.querySelectorAll(`.vote-button[data-position-id="${positionId}"]`).forEach(b => {
                        if (b === this) {
                            b.textContent = 'Cancel';
                            b.classList.add('btn-cancel', 'btn-danger');
                            b.classList.remove('btn-primary');
                        } else {
                            b.disabled = true;
                            b.classList.add('btn-disabled');
                        }
                    });
                });
            });

            // Submit Vote Button
            document.querySelector('.submit-voteButton').addEventListener('click', function () {
                if (!isElectionOpen) return;

                if (Object.keys(votes).length === 0) {
                    document.dispatchEvent(new CustomEvent('vote:noVotes'));
                    return;
                }

                // Build review list
                const reviewList = document.getElementById('voteReviewList');
                reviewList.innerHTML = '';
                Object.values(votes).forEach((vote, idx, arr) => {
                    let middleInitial = vote.candidate_middle_name ? ` ${vote.candidate_middle_name.charAt(0)}.` : '';
                    let formattedName = `${vote.candidate_first_name}${middleInitial} ${vote.candidate_last_name}`;

                    const div = document.createElement('div');
                    div.className = 'vote-review-item py-3';
                    div.innerHTML = `
                        <div class="vote-review-position mb-1">
                            <span class="fw-bold fs-5">${vote.position_name}</span>
                        </div>
                        <div class="vote-review-candidate">
                            <span class="fw-bold">${formattedName}</span>
                            ${vote.partylist ? `<span> of <span class="fw-bold">${vote.partylist}</span></span>` : ''}
                        </div>
                    `;
                    reviewList.appendChild(div);
                    if (idx < arr.length - 1) {
                        const hr = document.createElement('hr');
                        hr.className = 'my-2 vote-review-divider';
                        reviewList.appendChild(hr);
                    }
                });

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('voteReviewModal'));
                modal.show();
            });

            // Final Submit
            document.getElementById('finalSubmitVoteBtn').addEventListener('click', function () {
                if (!isElectionOpen) return;

                const modal = bootstrap.Modal.getInstance(document.getElementById('voteReviewModal'));
                modal.hide();

                const electionId = document.querySelector('.submit-voteButton').dataset.electionId;
                const voteData = {};
                Object.entries(votes).forEach(([positionId, vote]) => {
                    voteData[positionId] = vote.candidate_id;
                });

                console.log('Submitting votes:', { election_id: electionId, votes: voteData });

                fetch('{{ route("votes.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        election_id: electionId,
                        votes: voteData
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! Status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        // Clear votes and reset buttons
                        Object.keys(votes).forEach(positionId => {
                            delete votes[positionId];
                            document.querySelectorAll(`.vote-button[data-position-id="${positionId}"]`).forEach(b => {
                                b.textContent = 'Vote';
                                b.classList.remove('btn-cancel', 'btn-danger', 'btn-disabled');
                                b.classList.add('btn-primary');
                                b.disabled = false;
                            });
                        });

                        document.dispatchEvent(new CustomEvent('vote:submit', {
                            detail: { success: true, message: 'Your votes have been recorded successfully.' }
                        }));
                    } else {
                        document.dispatchEvent(new CustomEvent('vote:submit', {
                            detail: { success: false, message: data.message }
                        }));
                    }
                })
                .catch(error => {
                    console.error('Vote submission error:', error);
                    document.dispatchEvent(new CustomEvent('vote:submit', {
                        detail: { success: false, error: `Failed to submit votes: ${error.message}` }
                    }));
                });
            });

            // Modal Population
            document.querySelectorAll('.info-button').forEach(btn => {
                btn.addEventListener('click', function () {
                    const m = document.getElementById('candidateModal');
                    m.querySelector('#modalCandidateImage').src = this.dataset.image;
                    m.querySelector('#modalCandidateName').textContent = this.dataset.candidateName;
                    m.querySelector('#modalPosition').textContent = this.dataset.position;
                    m.querySelector('#modalPartylist').textContent = this.dataset.partylist;
                    m.querySelector('#modalYearLevel').textContent = this.dataset.yearLevel;
                    m.querySelector('#modalProgram').textContent = this.dataset.program;
                    m.querySelector('#modalPlatform').textContent = this.dataset.platform;
                });
            });
        });
    </script>
@endpush
</x-app-layout>
