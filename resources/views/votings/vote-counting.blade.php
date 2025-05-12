{{-- Full-page yellow background --}}
<div class="full-yellow-bg"></div>

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
                                $totalVotes = $position['candidates']->sum('votes_count') ?: 1; // Avoid division by zero
                            @endphp
                            @foreach($position['candidates'] as $candidate)
                                @php
                                    $percent = round(($candidate['votes_count'] / $totalVotes) * 100);
                                @endphp
                                <div class="candidate-details" data-candidate-id="{{ $candidate['candidate_id'] }}">
                                    <div class="candidate-row">
                                        <!-- Candidate Image -->
                                        <img src="{{ $candidate['image'] }}" class="candidate-icon" alt="Candidate icon" />
                                        <!-- Progress Bar -->
                                        <div class="progress-wrapper">
                                            <div class="progress-bar-fill" style="width: {{ $percent }}%;">
                                                <span class="progress-percentage">{{ $percent }}%</span>
                                            </div>
                                        </div>
                                        <!-- Vote Count on the right -->
                                        <span class="vote-count" style="font-weight: bold;">{{ number_format($candidate['votes_count']) }} votes</span>
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

<!-- Demographics Pie Chart Section at the bottom -->
<div class="dark-background-container">
    <section class="custom-drop-container">
        <div class="custom-dropdown-wrapper">
            <button class="dropdown-item" id="customDemoBtn">
                <svg class="drop-icon" viewBox="0 0 30 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.37744 7.875L14.7547 13.125L22.1319 7.875" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="dropdown-labelDemo">Demographics</span>
            </button>
            <ul class="custom-dropdown-menu" id="customDemoMenu">
                <li>Program</li>
                <li>Year Level</li>
                <li>Gender</li>
            </ul>
        </div>

        <div class="custom-dropdown-wrapper">
            <button class="dropdown-item" id="customPosiBtn">
                <svg class="down-icon" viewBox="0 0 30 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.37744 7.875L14.7547 13.125L22.1319 7.875" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="dropdown-labelPosi">Position</span>
            </button>
            <ul class="custom-dropdown-menu" id="customPosiMenu">
                <li>President</li>
                <li>Vice President</li>
                <li>Secretary</li>
                <li>Treasurer</li>
                <li>Auditor</li>
                <li>PIO</li>
                <li>Business Manager</li>
            </ul>
        </div>
    </section>

    <!-- Demographics-->
    <div class="background-transparentWrapper">
        <svg
            width="1157"
            height="436"
            viewBox="0 0 1157 436"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            class="background-svg"
        >
            <path
                opacity="0.8"
                d="M0 20C0 8.9543 8.95431 0 20 0H1137C1148.04 0 1157 8.95431 1157 20V416C1157 427.046 1148.04 436 1137 436H19.9999C8.95425 436 0 427.046 0 416V20Z"
                fill="#1E1E1E"
                fill-opacity="0.71"
            ></path>
        </svg>
        <!-- Pie Chart Comparison Container -->
        <div class="pie-charts-comparison">
            <div class="pie-chart-wrapper">
                <canvas id="pieChart1"></canvas>
                <div id="pieChart1Label" class="pie-chart-label"></div>
            </div>
            <div class="pie-chart-wrapper">
                <canvas id="pieChart2"></canvas>
                <div id="pieChart2Label" class="pie-chart-label"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
// Demographics and Position Dropdown
const demoBtn = document.getElementById('customDemoBtn');
const posiBtn = document.getElementById('customPosiBtn');
const demoMenu = document.getElementById('customDemoMenu');
const posiMenu = document.getElementById('customPosiMenu');
const demoBtnLabel = demoBtn.querySelector('.dropdown-labelDemo');
const posiBtnLabel = posiBtn.querySelector('.dropdown-labelPosi');

// Helper to update button label and selection
function setDropdownSelection(menu, btnLabel, value) {
    btnLabel.textContent = value;
    menu.querySelectorAll('li').forEach(li => {
        li.classList.toggle('active', li.textContent.trim() === value);
    });
}

demoBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  demoMenu.classList.toggle('active');
  posiMenu.classList.remove('active');
});

posiBtn.addEventListener('click', (e) => {
  e.stopPropagation();
  posiMenu.classList.toggle('active');
  demoMenu.classList.remove('active');
});

document.addEventListener('click', () => {
  demoMenu.classList.remove('active');
  posiMenu.classList.remove('active');
});

// Diverse color palette for pie charts
const PIE_COLORS = [
    '#f7bd03', '#03a9f7', '#f76e03', '#03f7a9', '#a903f7', '#f703a9', '#03f76e', '#f7a903', '#037cf7', '#7cf703',
    '#e74c3c', '#8e44ad', '#16a085', '#2ecc71', '#f39c12', '#d35400', '#34495e', '#1abc9c', '#c0392b', '#7f8c8d'
];

let selectedDemographic = 'program';
let selectedPosition = 'President';

function getDemographicKey(label) {
    switch (label.toLowerCase()) {
        case 'program': return 'program';
        case 'year level': return 'year_level';
        case 'gender': return 'gender';
        default: return 'program';
    }
}

function getPositionName(label) {
    return label;
}

function updatePieCharts() {
    fetch(`/vote-counting/demographics?position=${encodeURIComponent(selectedPosition)}&demographic=${encodeURIComponent(selectedDemographic)}`)
        .then(res => res.json())
        .then(data => {
            console.log('Pie chart data:', data); // <-- Debug output
            if (data.candidate1) {
                renderPieCharts(
                    data.candidate1.label,
                    data.candidate2 ? data.candidate2.label : '',
                    selectedDemographic,
                    { candidate1: data.candidate1, candidate2: data.candidate2 || { label: '', values: {} } },
                    PIE_COLORS
                );
            } else {
                if (window.pieChart1 && typeof window.pieChart1.destroy === 'function') window.pieChart1.destroy();
                if (window.pieChart2 && typeof window.pieChart2.destroy === 'function') window.pieChart2.destroy();
                document.getElementById('pieChart1Label').innerText = '';
                document.getElementById('pieChart2Label').innerText = '';
            }
        });
}

// Attach event listeners to demographic dropdown
Array.from(demoMenu.querySelectorAll('li')).forEach(li => {
    li.addEventListener('click', function() {
        const value = this.textContent.trim();
        selectedDemographic = getDemographicKey(value);
        setDropdownSelection(demoMenu, demoBtnLabel, value);
        updatePieCharts();
        demoMenu.classList.remove('active');
    });
});

// Attach event listeners to position dropdown
Array.from(posiMenu.querySelectorAll('li')).forEach(li => {
    li.addEventListener('click', function() {
        const value = this.textContent.trim();
        selectedPosition = getPositionName(value);
        setDropdownSelection(posiMenu, posiBtnLabel, value);
        updatePieCharts();
        posiMenu.classList.remove('active');
    });
});

// On page load, set default dropdown labels to the first <li> in each menu
window.addEventListener('DOMContentLoaded', function() {
    setDropdownSelection(demoMenu, demoBtnLabel, demoMenu.querySelector('li').textContent.trim());
    setDropdownSelection(posiMenu, posiBtnLabel, posiMenu.querySelector('li').textContent.trim());
    selectedDemographic = getDemographicKey(demoMenu.querySelector('li').textContent.trim());
    selectedPosition = getPositionName(posiMenu.querySelector('li').textContent.trim());
    updatePieCharts();
});

function renderPieCharts(candidate1, candidate2, demographic, data, colors = PIE_COLORS) {
    const ctx1 = document.getElementById('pieChart1').getContext('2d');
    const ctx2 = document.getElementById('pieChart2').getContext('2d');
    const groups1 = Object.keys(data.candidate1.values);
    const groups2 = data.candidate2 ? Object.keys(data.candidate2.values) : [];

    if (window.pieChart1 && typeof window.pieChart1.destroy === 'function') window.pieChart1.destroy();
    if (window.pieChart2 && typeof window.pieChart2.destroy === 'function') window.pieChart2.destroy();

    window.pieChart1 = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: groups1,
            datasets: [{
                data: groups1.map(g => data.candidate1.values[g]),
                backgroundColor: colors.slice(0, groups1.length),
            }]
        },
        options: {
            plugins: {
                legend: { display: true, labels: { color: 'white' } },
                datalabels: {
                    color: 'white',
                    font: { weight: 'bold', size: 18 },
                    formatter: (value, context) => {
                        const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        if (!value || !total) return '';
                        const percent = Math.round((value / total) * 100);
                        return percent > 0 ? percent + '%' : '';
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    if (data.candidate2 && groups2.length > 0) {
        window.pieChart2 = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: groups2,
                datasets: [{
                    data: groups2.map(g => data.candidate2.values[g]),
                    backgroundColor: colors.slice(0, groups2.length),
                }]
            },
            options: {
                plugins: {
                    legend: { display: true, labels: { color: 'white' } },
                    datalabels: {
                        color: 'white',
                        font: { weight: 'bold', size: 18 },
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            if (!value || !total) return '';
                            const percent = Math.round((value / total) * 100);
                            return percent > 0 ? percent + '%' : '';
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
        document.getElementById('pieChart2Label').innerText = candidate2;
    } else {
        ctx2.clearRect(0, 0, ctx2.canvas.width, ctx2.canvas.height);
        document.getElementById('pieChart2Label').innerText = '';
        window.pieChart2 = null;
    }
    document.getElementById('pieChart1Label').innerText = candidate1;
}

updatePieCharts();
</script>
</x-app-layout>
