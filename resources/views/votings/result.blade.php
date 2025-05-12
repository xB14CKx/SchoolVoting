@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/result.css') }}">
@endpush

<x-app-layout>
    <div class="page-container">
        <!-- Background Image -->
        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132"
             alt="CSG Logo"
             class="background-logo" />

        <!-- Header Title and Divider -->
        <div class="content-wrapper">
            <section class="results-container">
                <div class="results-header">
                    <h1 class="results-title">Results</h1>
                    <button class="action-button year-selector-button">
                        <svg class="chevron-icon" width="24" height="24" viewBox="0 0 29 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.07129 8.68604L14.1425 14.4768L21.2137 8.68604" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="button-text">School Year 2025</span>
                    </button>
                </div>
                <hr class="results-divider" />
            </section>

            <!-- Dropdown Button -->
            <div class="position-dropdown">
                <button class="position-selector-button" id="positionDropdownButton">
                    <span class="button-text">{{ $defaultPositionName ?? 'President' }}</span>
                    <svg class="chevron-icon" viewBox="0 0 29 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.07129 8.68604L14.1425 14.4768L21.2137 8.68604" stroke="#F5F5F5" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                <ul class="dropdown-menu hidden" id="positionDropdown">
                    @forelse($positions ?? collect() as $position)
                        <li><a href="{{ route('election_results.show', ['election' => $election->election_id, 'position_id' => $position->id]) }}">{{ $position->name }}</a></li>
                    @empty
                        <li>No positions available</li>
                    @endforelse
                </ul>
            </div>

            <!-- Position Title -->
            <div class="position-title-row">
                <h1 class="position-title">{{ $defaultPositionName ?? 'President' }}</h1>
            </div>

            <!-- Candidate Results -->
            <div class="cards-container">
                @php
                    $results = $results ?? old('results', collect()); // Fallback to empty collection if undefined
                    $totalVotes = $results->sum('votes');
                    $winner = $results->sortByDesc('votes')->first();
                @endphp
                @forelse($results as $result)
                    <div class="candidate-card">
                        <figure class="candidate-image-container">
                            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/c47a0b4387f6f7919f9b5473591a2869cb793761"
                                 alt="Profile"
                                 class="candidate-image" />
                        </figure>
                        <p class="candidate-name">{{ $result->candidate->name ?? 'Name' }}</p>
                        <p class="candidate-program">{{ $result->candidate->program ?? 'Program' }}</p>
                        <p class="candidate-partylist">{{ $result->candidate->partylist ?? 'Partylist' }}</p>
                        <p class="candidate-stats">
                            Total Votes: {{ $result->votes }}
                            ({{ $totalVotes > 0 ? number_format(($result->votes / $totalVotes) * 100, 2) : 0 }}%)
                        </p>
                        @if($winner && $result->id === $winner->id)
                            <p class="winner-label" style="color: gold; font-weight: bold;">Winner</p>
                        @endif
                    </div>
                @empty
                    <p>No candidates found for this position.</p>
                @endforelse
            </div>

            <!-- Statistics -->
            <section class="statistics-container">
                <h2 class="statistics-title">Statistics</h2>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const positionDropdownButton = document.getElementById("positionDropdownButton");
        const positionDropdown = document.getElementById("positionDropdown");

        // Toggle dropdown visibility on click/touch
        positionDropdownButton.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();
            positionDropdown.classList.toggle("hidden");
        });

        // Handle dropdown item clicks
        positionDropdown.querySelectorAll("li a").forEach(item => {
            item.addEventListener("click", function (event) {
                event.preventDefault();
                window.location.href = item.getAttribute("href");
                positionDropdown.classList.add("hidden");
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (event) {
            if (!positionDropdownButton.contains(event.target) && !positionDropdown.contains(event.target)) {
                positionDropdown.classList.add("hidden");
            }
        });

        // Ensure touch events work on mobile
        positionDropdownButton.addEventListener("touchstart", function (event) {
            event.preventDefault();
            positionDropdown.classList.toggle("hidden");
        });
    });
    </script>
</x-app-layout>
