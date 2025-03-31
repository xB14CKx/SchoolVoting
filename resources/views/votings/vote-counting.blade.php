@push('styles')

<link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap"
      rel="stylesheet"
    />
<link rel="stylesheet" href="{{ asset('css/vote-counting.css') }}">

@endpush

<x-app-layout>

  <section class="vote-counting-container">
  <img
  src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132" 
    class="background-image"
      alt="Background"
    />
  <div class="content-wrapper">

    <h1 class="title" style="margin-top: -30px; ">Vote Counting</h1>
    <hr class="divider" />

    <div class="position-section">
      <h2 class="position-title">President</h2>
    
      <article class="candidate-comparison">
        <div class="candidates-wrapper">
          <div class="candidate-details">
            <div class="candidate-row">
              <!-- Candidate Image -->
              <img
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/205aecb3f128ed6a4015ef62879ab49738d894d5?placeholderIfAbsent=true"
                class="candidate-icon"
                alt="Candidate 1 icon"
              />
    
              <!-- Progress Bar + Vote Count -->
              <div class="progress-wrapper">
                <div class="progress-bar-fill">
                  <span class="progress-percentage">70%</span>
                </div>
                <span class="vote-count">1,225 votes</span>
              </div>
            </div>
    
            <!-- Name -->
            <p class="candidate-name">
              Candidate 1<br />
              Program<br/>
              Partylist
            </p>
          </div>
        </div>
      </article>
    </div>
    
</div>
</section>
</x-app-layout>
