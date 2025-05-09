@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet" />
    @vite(['resources/css/vote-counting.css', 'resources/js/app.js'])
@endpush

@push('scripts')
    @vite(['resources/js/echo.js', 'resources/js/vote-counting.js'])
@endpush

<x-app-layout>
<section class="vote-counting-container">
    <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132" class="background-image" alt="Background" />
    <div class="content-wrapper">
        <h1 class="title" style="margin-top: -30px;">Vote Counting</h1>
        <hr class="divider" />
        @foreach($positions as $position)
            @if($position['candidates']->count() > 0)
                <div class="position-section" data-position-id="{{ $position['position_id'] }}">
                    <h2 class="position-title">{{ $position['position_name'] }}</h2>
                    <article class="candidate-comparison">
                        <div class="candidates-wrapper">
                            @php
                                $maxVotes = $position['candidates']->max('votes_count') ?: 1;
                            @endphp
                            @foreach($position['candidates'] as $candidate)
                                <div class="candidate-details" data-candidate-id="{{ $candidate['candidate_id'] }}">
                                    <div class="candidate-row">
                                        <!-- Candidate Image -->
                                        <img src="{{ $candidate['image'] }}" class="candidate-icon" alt="Candidate icon" />
                                        <!-- Progress Bar -->
                                        <div class="progress-wrapper">
                                            <div class="progress-bar-fill" style="width:{{ ($candidate['votes_count']/$maxVotes)*100 }}%;">
                                                <span class="progress-percentage">{{ round(($candidate['votes_count']/$maxVotes)*100) }}%</span>
                                            </div>
                                        </div>
                                        <!-- Vote Count on the right -->
                                        <span class="vote-count">{{ number_format($candidate['votes_count']) }} votes</span>
                                    </div>
                                    <!-- Name and Credentials -->
                                    <p class="candidate-name">
                                        <strong>
                                            {{ $candidate['last_name'] }}, {{ $candidate['first_name'] }}
                                            @if(!empty($candidate['middle_name']))
                                                {{ strtoupper(substr(trim($candidate['middle_name']), 0, 1)) }}.
                                            @endif
                                        </strong><br />
                                        {{ $candidate['partylist'] }}<br />
                                        @php
                                            $programName = $candidate['program'];
                                            $acronym = $programName;
                                            if (str_starts_with($programName, 'BS in ')) {
                                                $words = explode(' ', $programName);
                                                if (str_contains($programName, '–')) {
                                                    [$mainProgram, $major] = array_map('trim', explode('–', $programName));
                                                    $mainWords = array_filter(explode(' ', $mainProgram), fn($w) => strtolower($w) !== 'and');
                                                    $initials = collect($mainWords)->slice(2)->map(fn($w) => $w[0])->implode('');
                                                    if (str_contains($mainProgram, 'Entertainment and Multimedia Computing')) {
                                                        $acronym = 'BSEMC - ' . $major;
                                                    } else {
                                                        $acronym = 'BS' . $initials . ' - ' . $major;
                                                    }
                                                } else {
                                                    $filteredWords = array_filter($words, fn($w) => strtolower($w) !== 'and');
                                                    $initials = collect($filteredWords)->slice(2)->map(fn($w) => $w[0])->implode('');
                                                    $acronym = 'BS' . $initials;
                                                }
                                            } elseif (str_starts_with($programName, 'Bachelor of Multimedia Arts')) {
                                                $acronym = 'BMA';
                                            } elseif (str_starts_with($programName, 'Bachelor of ')) {
                                                $acronym = str_replace('Bachelor of ', 'B', $programName);
                                                $words = array_filter(explode(' ', $acronym), fn($w) => strtolower($w) !== 'and');
                                                $acronym = collect($words)->map(fn($w) => $w[0])->implode('');
                                            }
                                        @endphp
                                        {{ $acronym }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </article>
                </div>
            @endif
        @endforeach
    </div>
</section>
</x-app-layout>
