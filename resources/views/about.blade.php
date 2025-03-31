<!-- resources/views/votings/about.blade.php -->
<x-guest-layout>
  <x-slot name="title">About</x-slot>

  @vite(['resources/css/about.css', 'resources/js/app.js'])

  <main class="about-page">
      <div class="page-container">
          <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/5143e288166244e6b744336078167795d59d19ba?placeholderIfAbsent=true&apiKey=e0f71086e53c475a8c972a54eb6dce84" class="background-image" alt="Background image" />

          <br>
          <h1 class="page-title">ABOUT</h1>
          <hr>
          <br>

          <section class="content-section">
              <div class="content-columns">
                  <section class="content-column">
                      <article class="system-description">
                          The
                          <span class="college-name">College of Computing Education</span>
                          <span class="system-name">CSG Election System</span>
                          is a digital platform ensuring fair, transparent, and efficient student elections. It eliminates manual vote counting, reduces errors, and prevents fraud. Secure voter authentication, anonymity, and real-time tallying uphold electoral integrity. With a user-friendly interface, students can easily vote, fostering active participation in college leadership.
                          <br />
                      </article>
                  </section>

                  <section class="content-column">
                      <article class="rules-regulations">
                          <h2 class="rules-title">Rules and Regulations for Voting</h2>
                          <br />
                          <br />
                          <h3 class="rule-heading">Eligibility:</h3>
                          <p class="rule-text">
                              Only officially enrolled College of Computing Education students can vote. Voter identity must be verified before access to the ballot.
                          </p>
                          <br />
                          <br />
                          <h3 class="rule-heading">One-Person, One-Vote:</h3>
                          <p class="rule-text">
                              Each student may vote only once per election. Duplicate votes are automatically prevented.
                          </p>
                          <br />
                          <br />
                          <h3 class="rule-heading">Voting Period:</h3>
                          <p class="rule-text">
                              Votes are only accepted within the designated timeframe; late submissions are not allowed.
                          </p>
                          <br />
                          <br />
                          <h3 class="rule-heading">Confidentiality:</h3>
                          <p class="rule-text">
                              Votes remain anonymous to ensure fairness and prevent bias.
                          </p>
                          <br />
                          <br />
                          <h3 class="rule-heading">Fair Play:</h3>
                          <p class="rule-text">
                              Vote manipulation (coercion, bribery, or tampering) is strictly prohibited and may lead to disqualification or legal action.
                          </p>
                          <br />
                          <br />
                          <h3 class="rule-heading">Final Results:</h3>
                          <p class="rule-text">
                              Election results are system-generated, final, and cannot be changed.
                          </p>
                      </article>
                  </section>
              </div>
          </section>
      </div>
  </main>
</x-guest-layout>