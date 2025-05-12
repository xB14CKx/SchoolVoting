<x-app-layout>
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/result.css') }}">
        <style>
            .winner-card {
                border: 2px solid gold;
                background-color: #fff9e6;
            }
            .winner-badge {
                color: gold;
                font-weight: bold;
                font-size: 1.2em;
                margin-bottom: 10px;
            }
        </style>
    @endpush

    <div class="page-container">
        <!-- Background Image -->
        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132"
             alt="CSG Logo"
             class="background-logo" />

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="results-container">
                <div class="results-header">
                    <h1 class="results-title">Election Results</h1>
                    <button class="action-button year-selector-button">
                        <svg class="chevron-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L12 15L18 9" stroke="#1E1E1E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="button-text">School Year {{ $election->year }}</span>
                    </button>
                </div>
                <hr class="results-divider" />
            </section>

            <!-- Dropdown Button -->
            <div class="position-dropdown">
                <button class="position-selector-button" id="positionDropdownButton">
                    <span class="button-text">{{ $selectedPosition }}</span>
                    <svg class="chevron-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 9L12 15L18 9" stroke="#F5F5F5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                <ul class="dropdown-menu hidden" id="positionDropdown">
                    <li data-position="President">President</li>
                    <li data-position="Vice President">Vice President</li>
                    <li data-position="Secretary">Secretary</li>
                    <li data-position="Treasurer">Treasurer</li>
                    <li data-position="Auditor">Auditor</li>
                    <li data-position="Student PIO">Student PIO</li>
                    <li data-position="Business Manager">Business Manager</li>
                </ul>
            </div>

            <!-- Position Title -->
            <h1 class="position-title">{{ $selectedPosition }}</h1>

            <!-- Winner Announcement -->
            @if ($winner)
                <div class="winner-announcement">
                    <h2 class="winner-title">Winner: {{ $winner->candidate->name }}</h2>
                    <p>Total Votes: {{ $winner->votes }} ({{ $winner->percentage }}%)</p>
                </div>
            @else
                <p>No winner determined for this position.</p>
            @endif

            <!-- Candidate Results -->
            <div class="cards-container">
                @if ($results->isEmpty())
                    <p>No candidates found for this position.</p>
                @else
                    @foreach ($results as $result)
                        <div class="candidate-card {{ $winner && $winner->candidate_id == $result->candidate_id ? 'winner-card' : '' }}">
                            @if ($winner && $winner->candidate_id == $result->candidate_id)
                                <p class="winner-badge">Winner!</p>
                            @endif
                            <figure class="candidate-image-container">
                                <img src="{{ $result->candidate->image_url ?? 'https://cdn.builder.io/api/v1/image/assets/TEMP/c47a0b4387f6f7919f9b5473591a2869cb793761' }}"
                                     alt="{{ $result->candidate->name }}"
                                     class="candidate-image" />
                            </figure>
                            <p class="candidate-name">{{ $result->candidate->name }}</p>
                            <p class="candidate-program">{{ $result->candidate->program->name ?? 'N/A' }}</p>
                            <p class="candidate-partylist">{{ $result->candidate->partylist->name ?? 'N/A' }}</p>
                            <p class="candidate-stats">Total Votes: {{ $result->votes }} ({{ $result->percentage }}%)</p>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Statistics -->
            <section class="statistics-container">
                <h2 class="statistics-title">Statistics</h2>
                <p>Total Votes Cast: {{ $totalVotes }}</p>
                <!-- Add charts or additional stats here -->
            </section>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const positionDropdownButton = document.getElementById("positionDropdownButton");
                const positionDropdown = document.getElementById("positionDropdown");
                const positionTitle = document.querySelector(".position-title");

                positionDropdownButton.addEventListener("click", function (event) {
                    event.stopPropagation();
                    positionDropdown.classList.toggle("hidden");
                });

                positionDropdown.querySelectorAll("li").forEach(item => {
                    item.addEventListener("click", function () {
                        const selectedPosition = this.dataset.position;
                        positionTitle.textContent = selectedPosition;
                        positionDropdownButton.querySelector(".button-text").textContent = selectedPosition;
                        positionDropdown.classList.add("hidden");

                        // Update URL with selected position
                        const url = new URL(window.location);
                        url.searchParams.set('position', selectedPosition);
                        window.history.pushState({}, '', url);
                        window.location.reload(); // Reload to fetch new results
                    });
                });

                document.addEventListener("click", function (event) {
                    if (!positionDropdown.contains(event.target) && !positionDropdownButton.contains(event.target)) {
                        positionDropdown.classList.add("hidden");
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
