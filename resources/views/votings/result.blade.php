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
                    <h1 class="results-title">Election Results</h1>
                    <button class="action-button year-selector-button">
                        <svg class="chevron-icon" width="24" height="24" viewBox="0 0 29 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.07129 8.68604L14.1425 14.4768L21.2137 8.68604" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="button-text">School Year 2025</span>
                    </button>
                </div>
                <hr class="results-divider" />
            </section>

            <!-- Display Winners for Each Position -->
            @if(isset($winnersByPosition) && !empty($winnersByPosition))
                @foreach($winnersByPosition as $position => $data)
                    <h1 class="position-title">{{ $position }}</h1>
                    <div class="cards-container">
                        <div class="candidate-card">
                            <figure class="candidate-image-container">
                                <img src="{{ $data['candidate']->image ? asset('storage/' . $data['candidate']->image) : 'https://cdn.builder.io/api/v1/image/assets/TEMP/c47a0b4387f6f7919f9b5473591a2869cb793761' }}"
                                     alt="Profile"
                                     class="candidate-image" />
                            </figure>
                            <p class="candidate-name">{{ $data['candidate']->first_name }} {{ $data['candidate']->last_name }}</p>
                            <p class="candidate-program">{{ $data['candidate']->program->program_name ?? 'Unknown Program' }}</p>
                            <p class="candidate-partylist">{{ $data['candidate']->partylist->partylist_name ?? 'Unknown Partylist' }}</p>
                            <p class="candidate-stats">
                                Total Votes: {{ $data['votes'] }}
                                ({{ $data['totalVotes'] > 0 ? number_format(($data['votes'] / $data['totalVotes']) * 100, 2) : 0 }}%)
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="cards-container">
                    <p>No election results available.</p>
                </div>
            @endif

            <!-- Statistics -->
            <section class="statistics-container">
                <h2 class="statistics-title">Statistics</h2>
                <p>Total Votes Cast: {{ $totalVotes ?? 0 }}</p>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
