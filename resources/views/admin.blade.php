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
</div>

   <!-- Add Candidate Modal -->
   <div class="modal fade" id="addCandidateModal" tabindex="-1" aria-labelledby="addCandidateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content p-4">
        <div class="modal-header">
          <h5 class="modal-title" id="addCandidateLabel">Add Candidate</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Image Upload -->

          <h6>Image Upload</h6>
          <br>
          <div class="mb-3 text-center">
            <div id="imagePreview" class="mb-2">
              <img src="#" alt="Preview" id="previewImg" class="img-thumbnail d-none" width="150">
            </div>
            <input class="form-control" type="file" id="candidateImage" accept="image/*">
          </div>
          <hr />

  
          <!-- Position + Partylist -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="candidatePosition" class="form-label">Position</label>
            <input type="text" class="form-control" id="candidatePosition" readonly>
          </div>
          <div class="col-md-6">
            <label for="candidatePartylist" class="form-label">Partylist</label>
              <select class="form-select" id="candidatePartylist">
                <option disabled selected>Choose a partylist</option>
                @foreach ($partylists as $party)
                  <option value="{{ $party->partylist_id }}">{{ $party->partylist_name }}</option>
                @endforeach
              </select>
          </div>
        </div>
        <br>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="candidateLastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="candidateLastName">
          </div>
          <div class="col-md-4">
            <label for="candidateFirstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="candidateFirstName">
          </div>
          <div class="col-md-3">
            <label for="candidateMiddleName" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="candidateMiddleName">
          </div>
        </div>

        <br>
        <!-- Year Level + Program -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="candidateYearLevel" class="form-label">Year Level</label>
            <select class="form-select" id="candidateYearLevel">
              <option value="1st">1st Year</option>
              <option value="2nd">2nd Year</option>
              <option value="3rd">3rd Year</option>
              <option value="4th">4th Year</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="candidateProgram" class="form-label">Program</label>
            <select class="form-select" id="candidateProgram">
              @foreach ($programs as $program)
                <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
              @endforeach
            </select>
            
          </div>
        </div>
        <br>
        
        <div class="modal-footer">
          <br>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="saveCandidateBtn">Save Candidate</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Candidate Modal -->
<div class="modal fade" id="editCandidateModal" tabindex="-1" aria-labelledby="editCandidateLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-4">
      <div class="modal-header">
        <h5 class="modal-title" id="editCandidateLabel">Edit Candidate</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Image Upload -->
        <h6>Image Upload</h6>
        <br>
        <div class="mb-3 text-center">
          <div id="editImagePreview" class="mb-2">
            <img src="#" alt="Preview" id="editPreviewImg" class="img-thumbnail d-none" width="150">
          </div>
          <input class="form-control" type="file" id="editCandidateImage" accept="image/*">
        </div>
        <hr />

        <!-- Position + Partylist -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="editCandidatePosition" class="form-label">Position</label>
            <input type="text" class="form-control" id="editCandidatePosition" readonly>
          </div>
          <div class="col-md-6">
            <label for="editCandidatePartylist" class="form-label">Partylist</label>
            <select class="form-select" id="editCandidatePartylist">
              <option disabled selected>Choose a partylist</option>
              @foreach ($partylists as $party)
                <option value="{{ $party->partylist_id }}">{{ $party->partylist_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <br>
        <div class="row mb-3">
          <div class="col-md-4">
            <label for="editCandidateLastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="editCandidateLastName">
          </div>
          <div class="col-md-4">
            <label for="editCandidateFirstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="editCandidateFirstName">
          </div>
          <div class="col-md-4">
            <label for="editCandidateMiddleName" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="editCandidateMiddleName">
          </div>
        </div>

        <br>
        <!-- Year Level + Program -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="editCandidateYearLevel" class="form-label">Year Level</label>
            <select class="form-select" id="editCandidateYearLevel">
              <option value="1st">1st Year</option>
              <option value="2nd">2nd Year</option>
              <option value="3rd">3rd Year</option>
              <option value="4th">4th Year</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="editCandidateProgram" class="form-label">Program</label>
            <select class="form-select" id="editCandidateProgram">
              @foreach ($programs as $program)
                <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="updateCandidateBtn">Update Candidate</button>
      </div>
    </div>
  </div>
</div>


<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const candidateStoreUrl = "{{ route('candidates.store') }}";
    const candidates = @json($candidates);
    let currentEditingCandidateId = null;

    document.addEventListener("DOMContentLoaded", function () {
        // Initialize existing candidates
        candidates.forEach(candidate => {
            const container = document.getElementById(getPositionContainerId(candidate.position.name));
            if (container) {
                const card = createCandidateCard(candidate);
                container.insertBefore(card, container.querySelector('.add-candidate-button'));
            }
        });

        // Check card limits for all positions after initialization
        const positionIds = [
            'presidentCandidates',
            'vicePresidentCandidates',
            'secretaryCandidates',
            'treasurerCandidates',
            'auditorCandidates',
            'PIOCandidates',
            'businessManagerCandidates'
        ];
        positionIds.forEach(id => checkCardLimit(id));

        // Add event listeners for delete buttons
        document.querySelectorAll('.option-button').forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.candidate-card');
                const candidateId = card.dataset.candidateId;
                
                if (this.textContent === 'Delete') {
                    if (confirm('Are you sure you want to delete this candidate?')) {
                        fetch(`/candidates/${candidateId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    try {
                                        return Promise.reject(JSON.parse(text));
                                    } catch (e) {
                                        return Promise.reject(new Error(text));
                                    }
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                card.remove();
                                // Update the card count and enable/disable add button
                                const container = card.closest('.candidate-grid');
                                checkCardLimit(container.id);
                            } else {
                                alert(data.message || 'Failed to delete candidate');
                            }
                        })

                    }
                }
            });
        });

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

        document.querySelectorAll('.more-options-button').forEach(button => {
        button.addEventListener('click', function (event) {
          event.stopPropagation(); 
          const card = button.closest(".candidate-card");
          const menu = card.querySelector(".options-menu");
          document.querySelectorAll(".options-menu").forEach(menu => menu.classList.add("hidden"));
          if (menu) {
            menu.classList.toggle("hidden");
          }
        });
      });

      document.addEventListener("click", function () {
        document.querySelectorAll(".options-menu").forEach(menu => menu.classList.add("hidden"));
      });


        function createCandidateCard(candidateData) {
            const cardHTML = `
                <article class="candidate-card" data-candidate-id="${candidateData.id}">
                    <button class="more-options-button" aria-label="More options">
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
                            src="${candidateData.image ? '{{ asset('storage/') }}/' + candidateData.image : 'https://cdn.builder.io/api/v1/image/assets/aa78da9d1a8c4ca2babcebcf463f7106/567fdf519eb08658d3207d7508f0b1db1ca7b3a2'}"
                            class="candidate-image"
                            alt="Candidate">
                        <figcaption class="candidate-details">
                            ${candidateData.last_name}, ${candidateData.first_name} ${candidateData.middle_name ? candidateData.middle_name.charAt(0) + '.' : ''}<br>
                            ${candidateData.program.program_name}<br>
                            ${candidateData.partylist.partylist_name}
                        </figcaption>
                    </figure>
                </article>
            `;
            const wrapper = document.createElement('div');
            wrapper.innerHTML = cardHTML.trim();
            return wrapper.firstElementChild;
        }

        function getPositionContainerId(positionName) {
            const map = {
                "President": "presidentCandidates",
                "Vice President": "vicePresidentCandidates",
                "Secretary": "secretaryCandidates",
                "Treasurer": "treasurerCandidates",
                "Auditor": "auditorCandidates",
                "Student PIO": "PIOCandidates",
                "Business Manager": "businessManagerCandidates"
            };
            return map[positionName] || null;
        }

        // ========== Modal Trigger ==========
        const addButtons = document.querySelectorAll('.add-candidate-button');

        addButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                console.log("Add button clicked"); // <-- Add this
                const targetId = btn.getAttribute('data-position');
                const container = document.getElementById(targetId);
                

                const currentCards = container.querySelectorAll('.candidate-card').length;
                if (currentCards >= 2) {
                    alert('You can only add up to 2 candidates for this position.');
                    return;
                }

                const positionName = getPositionTitle(targetId);

                // Update modal fields
                document.getElementById("candidatePosition").value = positionName;
                document.getElementById("addCandidateLabel").textContent = `Add Candidate for ${positionName}`;
                document.getElementById("candidateImage").value = '';
                document.getElementById("previewImg").classList.add("d-none");

                const modal = new bootstrap.Modal(document.getElementById("addCandidateModal"));
                modal.show();
            });
        });

        function checkCardLimit(containerId) {
            const container = document.getElementById(containerId);
            const addBtn = container.querySelector('.add-candidate-button');
            const cardCount = container.querySelectorAll('.candidate-card').length;

            if (cardCount >= 2) {
                addBtn.style.visibility = 'hidden';
                addBtn.style.pointerEvents = 'none';
            } else {
                addBtn.style.visibility = 'visible';
                addBtn.style.pointerEvents = 'auto';
            }
        }

        document.getElementById("candidateImage").addEventListener("change", function () {
            const file = this.files[0];
            const preview = document.getElementById("previewImg");

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove("d-none");
                };
                reader.readAsDataURL(file);
            }
        });

        function getPositionTitle(id) {
            const map = {
                presidentCandidates: "President",
                vicePresidentCandidates: "Vice President",
                secretaryCandidates: "Secretary",
                treasurerCandidates: "Treasurer",
                auditorCandidates: "Auditor",
                PIOCandidates: "Student PIO",
                businessManagerCandidates: "Business Manager"
            };
            return map[id] || "Position";
        }

        function getPositionId(positionName) {
            const map = {
                "President": 1,
                "Vice President": 2,
                "Secretary": 3,
                "Treasurer": 4,
                "Auditor": 5,
                "Student PIO": 6,
                "Business Manager": 7
            };
            return map[positionName] || null;
        }

        // ========== Modal Submission ==========
        document.getElementById('saveCandidateBtn').addEventListener('click', function () {
            console.log("Save Candidate button clicked");
            const positionName = document.getElementById("candidatePosition").value;
            const positionId = getPositionId(positionName);

            const formData = new FormData();
            const imageFile = document.getElementById("candidateImage").files[0];
            if (imageFile) {
                formData.append('image', imageFile);
            }

            formData.append('position_id', positionId);
            formData.append('partylist_id', document.getElementById("candidatePartylist").value);
            formData.append('first_name', document.getElementById("candidateFirstName").value);
            formData.append('last_name', document.getElementById("candidateLastName").value);
            formData.append('middle_name', document.getElementById("candidateMiddleName").value);
            formData.append('year_level', document.getElementById("candidateYearLevel").value);
            formData.append('program_id', document.getElementById("candidateProgram").value);

            fetch(candidateStoreUrl, {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            return Promise.reject(JSON.parse(text));
                        } catch (e) {
                            return Promise.reject(new Error(text));
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById("addCandidateModal"));
                    modal.hide();
                    location.reload();
                } else {
                    alert("Failed to add candidate: " + (data.message || "Unknown error"));
                }
            })
            .catch(error => {
                console.error("Error submitting candidate:", error);
                alert("Error submitting candidate: " + (error.message || "Unknown error"));
            });
        });

        // Add event listeners for edit buttons
        document.querySelectorAll('.option-button').forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.candidate-card');
                const candidateId = card.dataset.candidateId;
                
                if (this.textContent === 'Edit') {
                    currentEditingCandidateId = candidateId;
                    const candidate = candidates.find(c => c.id == candidateId);
                    
                    if (candidate) {
                        // Populate the edit modal with candidate data
                        document.getElementById("editCandidatePosition").value = candidate.position.name;
                        document.getElementById("editCandidatePartylist").value = candidate.partylist_id;
                        document.getElementById("editCandidateFirstName").value = candidate.first_name;
                        document.getElementById("editCandidateLastName").value = candidate.last_name;
                        document.getElementById("editCandidateMiddleName").value = candidate.middle_name;
                        document.getElementById("editCandidateYearLevel").value = candidate.year_level;
                        document.getElementById("editCandidateProgram").value = candidate.program_id;
                        
                        // Set the current image preview
                        const previewImg = document.getElementById("editPreviewImg");
                        if (candidate.image) {
                            previewImg.src = '{{ asset('storage/') }}/' + candidate.image;
                            previewImg.classList.remove("d-none");
                        } else {
                            previewImg.classList.add("d-none");
                        }

                        // Show the edit modal
                        const editModal = new bootstrap.Modal(document.getElementById("editCandidateModal"));
                        editModal.show();
                    }
                }
            });
        });

        // Handle image preview in edit modal
        document.getElementById("editCandidateImage").addEventListener("change", function () {
            const file = this.files[0];
            const preview = document.getElementById("editPreviewImg");

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove("d-none");
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle update candidate
        document.getElementById('updateCandidateBtn').addEventListener('click', function () {
            const formData = new FormData();
            const imageFile = document.getElementById("editCandidateImage").files[0];
            
            if (imageFile) {
                formData.append('image', imageFile);
            }

            formData.append('partylist_id', document.getElementById("editCandidatePartylist").value);
            formData.append('first_name', document.getElementById("editCandidateFirstName").value);
            formData.append('last_name', document.getElementById("editCandidateLastName").value);
            formData.append('middle_name', document.getElementById("editCandidateMiddleName").value);
            formData.append('year_level', document.getElementById("editCandidateYearLevel").value);
            formData.append('program_id', document.getElementById("editCandidateProgram").value);

            fetch(`/candidates/${currentEditingCandidateId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            return Promise.reject(JSON.parse(text));
                        } catch (e) {
                            return Promise.reject(new Error(text));
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById("editCandidateModal"));
                    modal.hide();
                    location.reload();
                } else {
                    alert("Failed to update candidate: " + (data.message || "Unknown error"));
                }
            })
            .catch(error => {
                console.error("Error updating candidate:", error);
                alert("Error updating candidate: " + (error.message || "Unknown error"));
            });
        });
    });
</script>
</x-app-layout>
