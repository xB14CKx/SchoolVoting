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
        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132" alt="CSG Logo" class="background-logo" />
    
<!-- Header Title and Divider -->
    <section class="admin-container">
            <div class="admin-header">
                <h1 class="admin-title">Admin</h1>
      <div class="admin-actions">
      
      <!-- Add Candidate -->
      <button class="action-button add-candidate-button">
      <svg class="add-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M11 17H13V13H17V11H13V7H11V11H7V13H11V17ZM12 22C10.6167 22 9.31667 21.7417 8.1 21.225C6.88333 20.6917 5.825 19.975 4.925 19.075C4.025 18.175 3.30833 17.1167 2.775 15.9C2.25833 14.6833 2 13.3833 2 12C2 10.6167 2.25833 9.31667 2.775 8.1C3.30833 6.88333 4.025 5.825 4.925 4.925C5.825 4.025 6.88333 3.31667 8.1 2.8C9.31667 2.26667 10.6167 2 12 2C13.3833 2 14.6833 2.26667 15.9 2.8C17.1167 3.31667 18.175 4.025 19.075 4.925C19.975 5.825 20.6833 6.88333 21.2 8.1C21.7333 9.31667 22 10.6167 22 12C22 13.3833 21.7333 14.6833 21.2 15.9C20.6833 17.1167 19.975 18.175 19.075 19.075C18.175 19.975 17.1167 20.6917 15.9 21.225C14.6833 21.7417 13.3833 22 12 22ZM12 20C14.2333 20 16.125 19.225 17.675 17.675C19.225 16.125 20 14.2333 20 12C20 9.76667 19.225 7.875 17.675 6.325C16.125 4.775 14.2333 4 12 4C9.76667 4 7.875 4.775 6.325 6.325C4.775 7.875 4 9.76667 4 12C4 14.2333 4.775 16.125 6.325 17.675C7.875 19.225 9.76667 20 12 20Z"
          fill="#1D1B20" ></path>
      </svg>
      <span class="button-add-text">Add Candidate</span>
    </button>

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
    </div>
            <hr class="admin-divider" />
        </section>
    
    <!-- Candidate Card -->
    <article class="position-card">
  <header class="position-title">Position</header>
  <section class="card-container">

  <!-- More Options Button -->
  <button class="more-options-button" aria-label="More options" onclick="toggleOptions()">
  <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg" class="more-icon">
    <path d="M9.70312 12.9345C9.2584 12.9345 8.87769 12.8078 8.56099 12.5545C8.24429 12.3012 8.08594 11.9967 8.08594 11.641C8.08594 11.2853 8.24429 10.9808 8.56099 10.7275C8.87769 10.4742 9.2584 10.3476 9.70312 10.3476C10.1479 10.3476 10.5286 10.4742 10.8453 10.7275C11.162 10.9808 11.3203 11.2853 11.3203 11.641C11.3203 11.9967 11.162 12.3012 10.8453 12.5545C10.5286 12.8078 10.1479 12.9345 9.70312 12.9345ZM9.70312 9.05415C9.2584 9.05415 8.87769 8.9275 8.56099 8.6742C8.24429 8.4209 8.08594 8.1164 8.08594 7.7607C8.08594 7.40501 8.24429 7.10051 8.56099 6.84721C8.87769 6.59391 9.2584 6.46726 9.70312 6.46726C10.1479 6.46726 10.5286 6.59391 10.8453 6.84721C11.162 7.10051 11.3203 7.40501 11.3203 7.7607C11.3203 8.1164 11.162 8.4209 10.8453 8.6742C10.5286 8.9275 10.1479 9.05415 9.70312 9.05415ZM9.70312 5.17381C9.2584 5.17381 8.87769 5.04716 8.56099 4.79386C8.24429 4.54056 8.08594 4.23606 8.08594 3.88036C8.08594 3.52466 8.24429 3.22016 8.56099 2.96686C8.87769 2.71356 9.2584 2.58691 9.70312 2.58691C10.1479 2.58691 10.5286 2.71356 10.8453 2.96686C11.162 3.22016 11.3203 3.52466 11.3203 3.88036C11.3203 4.23606 11.162 4.54056 10.8453 4.79386C10.5286 5.04716 10.1479 5.17381 9.70312 5.17381Z" fill="#1D1B20"></path>
  </svg>
</button>

<!-- Dropdown Menu for More Options -->
<div class="options-menu hidden" id="optionsMenu">
  <button class="option-button">Edit</button>
  <button class="option-button">Delete</button>
</div>

    <figure class="profile-figure">
      <img
        src="https://cdn.builder.io/api/v1/image/assets/TEMP/cca30afecbe8b6cbb3d70323647f22c936231444"
        alt="Profile"
        class="profile-image"
      />
    </figure>
    <div class="profile-info">
      <p class="info-line">Name</p>
      <p class="info-line">Program</p>
      <p class="info-line">Partylist</p>
    </div>
  </section>
</article>
    </div>

<!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// More Options Dropdown 
  function toggleOptions() {
    let menu = document.getElementById("optionsMenu");
    menu.classList.toggle("hidden");
}

document.addEventListener("click", function (event) {
    let menu = document.getElementById("optionsMenu");
    let button = document.querySelector(".more-options-button");

    if (!menu.contains(event.target) && !button.contains(event.target)) {
        menu.classList.add("hidden");
    }
});

// School Year Dropdown
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
});
</script>
</x-app-layout>
