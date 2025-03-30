@push('styles')
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">

@endpush

<x-app-layout>
    
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

  <!-- School Year 2025 -->
  <div class="dropdown">
  <button class="action-button year-selector-button" id="yearDropdownButton">
      <svg class="chevron-icon" width="24" height="24" viewBox="0 0 29 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M7.07129 8.68604L14.1425 14.4768L21.2137 8.68604" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
      </svg>
      <span class="button-text">School Year 2025</span>
  </button>
  <ul class="dropdown-menu hidden" id="yearDropdown">
  </ul>
  </div>

  </div>   
  <hr class="admin-divider" />
  </section>

  <!-- Candidate Card Template (hidden) -->
<template id="candidateCardTemplate">
  <article class="candidate-card">
    <!-- The three-dot button -->
    <button class="more-options-button" aria-label="More options">
      <svg class="more-icon" width="20" height="16" ...> 
        <!-- same three-dot path as before --> 
      </svg>
    </button>

    <!-- Dropdown for edit/delete -->
    <div class="options-menu hidden">
      <button class="option-button">Edit</button>
      <button class="option-button">Delete</button>
    </div>

    <figure class="candidate-figure">
      <img 
        src="https://cdn.builder.io/api/v1/image/...someImage..."
        class="candidate-image"
        alt="Candidate"
      />
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
  <div class="  section">

    <div class="position-wrapper">
      <h2 class="position-title">President</h2>
      
      <div class="candidate-grid" id="presidentCandidates">
            <button 
          class="add-candidate-button" 
          data-position="presidentCandidates"
        >
          <img
            src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
            class="add-candidate-icon"
            alt="plus/add"
          />
        </button>
      </div>
    </div>
  

  <!-- Vice President -->
<div class="position-wrapper">
  <h2 class="position-title">Vice President</h2>

  <div class="candidate-grid" id="vicePresidentCandidates">

  <button 
    class="add-candidate-button" 
    data-position="vicePresidentCandidates"
  >
    <img
    src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
    class="add-candidate-icon"
    alt="plus/add"
    />
  </button>
</div>
</div>

  <!-- Secretary -->
  <div class="position-wrapper">
    <h2 class="position-title">Secretary</h2>
  
    <div class="candidate-grid" id="secretaryCandidates">
  
    <button 
      class="add-candidate-button" 
      data-position="secretaryCandidates"
    >
      <img
      src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
      class="add-candidate-icon"
      alt="plus/add"
      />
    </button>
  </div>
  </div>

    <!-- Treasurer -->
    <div class="position-wrapper">
      <h2 class="position-title">Treasurer</h2>
    
      <div class="candidate-grid" id="treasurerCandidates">
    
      <button 
        class="add-candidate-button" 
        data-position="treasurerCandidates"
      >
        <img
        src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
        class="add-candidate-icon"
        alt="plus/add"
        />
      </button>
    </div>
    </div>

      <!-- Auditor -->
    <div class="position-wrapper">
      <h2 class="position-title">Auditor</h2>
    
      <div class="candidate-grid" id="auditorCandidates">
    
      <button 
        class="add-candidate-button" 
        data-position="auditorCandidates"
      >
        <img
        src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
        class="add-candidate-icon"
        alt="plus/add"
        />
      </button>
    </div>
    </div>

      <!-- Public Information Officer -->
    <div class="position-wrapper">
      <h2 class="position-title">Student PIO</h2>
    
      <div class="candidate-grid" id="PIOCandidates">
    
      <button 
        class="add-candidate-button" 
        data-position="PIOCandidates"
      >
        <img
        src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
        class="add-candidate-icon"
        alt="plus/add"
        />
      </button>
    </div>
    </div>

      <!-- Business Manager -->
    <div class="position-wrapper">
      <h2 class="position-title">Business Manager</h2>
    
      <div class="candidate-grid" id="businessManagerCandidates">
    
      <button 
        class="add-candidate-button" 
        data-position="businessManagerCandidates"
      >
        <img
        src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/d104a5502307640106c3586dc9010ea9a04b4473?placeholderIfAbsent=true"
        class="add-candidate-icon"
        alt="plus/add"
        />
      </button>
    </div>
    </div>

  </section>

  </div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {

    const dropdownButton = document.getElementById("yearDropdownButton");
    const dropdownMenu = document.getElementById("yearDropdown");

    for (let year = 2025; year <= 2035; year++) {
      let listItem = document.createElement("li");
      listItem.textContent = `School Year ${year}`;
      listItem.addEventListener("click", function () {
        dropdownButton.querySelector(".button-text").textContent = `School Year ${year}`;
        dropdownMenu.classList.remove("show");
      });
      dropdownMenu.appendChild(listItem);
    }

    dropdownButton.addEventListener("click", function (event) {
      event.stopPropagation();
      dropdownMenu.classList.toggle("show");
    });

    document.addEventListener("click", function (event) {
      if (!dropdownButton.contains(event.target)) {
        dropdownMenu.classList.remove("show");
      }
    });

    document.addEventListener("click", function (event) {
    // Check if a "more-options-button" was clicked (or one of its children)
    const moreButton = event.target.closest(".more-options-button");
    if (moreButton) {
      // Find the candidate card that contains this button
      const card = moreButton.closest(".candidate-card");
      if (card) {
        const menu = card.querySelector(".options-menu");
        if (menu) {
          // Toggle the menu visibility
          menu.classList.toggle("hidden");
        }
      }
    } else {
      // Click outside: close all open options menus
      document.querySelectorAll(".options-menu").forEach(menu => {
        menu.classList.add("hidden");
      });
    }
  });

    function createCandidateCard() {
  const cardHTML = `
    <article class="candidate-card">
      <button class="more-options-button" aria-label="More options" onclick="toggleOptions()">
        <svg class="more-icon" width="20" height="16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M9.70312 12.9345C9.2584 12.9345 8.87769 12.8078 8.56099 12.5545C8.24429 12.3012 8.08594 11.9967 8.08594 11.641C8.08594 11.2853 8.24429 10.9808 8.56099 10.7275C8.87769 10.4742 9.2584 10.3476 9.70312 10.3476C10.1479 10.3476 10.5286 10.4742 10.8453 10.7275C11.162 10.9808 11.3203 11.2853 11.3203 11.641C11.3203 11.9967 11.162 12.3012 10.8453 12.5545C10.5286 12.8078 10.1479 12.9345 9.70312 12.9345ZM9.70312 9.05415C9.2584 9.05415 8.87769 8.9275 8.56099 8.6742C8.24429 8.4209 8.08594 8.1164 8.08594 7.7607C8.08594 7.40501 8.24429 7.10051 8.56099 6.84721C8.87769 6.59391 9.2584 6.46726 9.70312 6.46726C10.1479 6.46726 10.5286 6.59391 10.8453 6.84721C11.162 7.10051 11.3203 7.40501 11.3203 7.7607C11.3203 8.1164 11.162 8.4209 10.8453 8.6742C10.5286 8.9275 10.1479 9.05415 9.70312 9.05415ZM9.70312 5.17381C9.2584 5.17381 8.87769 5.04716 8.56099 4.79386C8.24429 4.54056 8.08594 4.23606 8.08594 3.88036C8.08594 3.52466 8.24429 3.22016 8.56099 2.96686C8.87769 2.71356 9.2584 2.58691 9.70312 2.58691C10.1479 2.58691 10.5286 2.71356 10.8453 2.96686C11.162 3.22016 11.3203 3.52466 11.3203 3.88036C11.3203 4.23606 11.162 4.54056 10.8453 4.79386C10.5286 5.04716 10.1479 5.17381 9.70312 5.17381Z" fill="#1D1B20"></path>
        </svg>
      </button>
      <div class="options-menu hidden">
        <button class="option-button">Edit</button>
        <button class="option-button">Delete</button>
      </div>
      <figure class="candidate-figure">
        <img 
          src="https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/567fdf519eb08658d3207d7508f0b1db1ca7b3a2"
          class="candidate-image"
          alt="Candidate">
        <figcaption class="candidate-details">
          Name<br>Program<br>Partylist
        </figcaption>
      </figure>
    </article>
  `;
  const wrapper = document.createElement('div');
  wrapper.innerHTML = cardHTML.trim();
  return wrapper.firstElementChild;
}

    // (B) Find all plus buttons and attach click event
    const addButtons = document.querySelectorAll('.add-candidate-button');
    addButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        // Which container to append?
        const targetId = btn.getAttribute('data-position');
        const container = document.getElementById(targetId);

        // Create a new card
        const newCard = createCandidateCard();

        // Append to the correct candidate-grid
        container.insertBefore(newCard, btn);
        ;
      });
    });
  });
</script>

</x-app-layout>
