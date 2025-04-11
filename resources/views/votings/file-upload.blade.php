@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/file-upload.css'])
@endpush

<x-app-layout>
    <div class="page-container">
        <!-- Background Image -->
        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/fe992e701c7edc01644f69af503f11ed319f8132"
             alt="CSG Logo"
             class="background-logo" />

        <!-- Header Title and Divider -->
        <div class="content-wrapper">
            <section class="upload-container">
                <div class="upload-header">
                    <h1 class="upload-title">File Upload</h1>
                    <div class="upload-actions">
                        <!-- School Year Dropdown -->
                        <div class="dropdown">
                            <button class="action-button year-selector-button" id="yearDropdownButton">
                                <svg class="chevron-icon" width="24" height="24" viewBox="0 0 29 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.07129 8.68604L14.1425 14.4768L21.2137 8.68604" stroke="#1E1E1E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="button-text">School Year 2025</span>
                            </button>
                            <ul class="dropdown-menu hidden" id="yearDropdown"></ul>
                        </div>
                    </div>
                </div>
                <hr class="upload-divider" />

                <!-- File Upload/Generate -->
                <section class="file-upload-container">
                    <form id="uploadForm"
                          hx-post="/upload"
                          hx-target="#studentTableBody"
                          hx-swap="innerHTML"
                          hx-encoding="multipart/form-data"
                          hx-on::after-request="if (event.detail.successful) {
                              document.getElementById('fileInput').value = '';
                              document.getElementById('fileName').value = 'No file chosen';
                              alert('File uploaded successfully!');
                          } else {
                              alert(event.detail.xhr.responseText ? JSON.parse(event.detail.xhr.responseText).message : 'Error uploading file');
                          }">
                        @csrf
                        <div class="upload-panel">
                            <input type="file" name="file" id="fileInput" class="d-none" accept=".csv,.xlsx,.xls">
                            <button type="button" class="upload-button" onclick="document.getElementById('fileInput').click()">
                                <strong>Upload a File</strong>
                            </button>
                            <div class="input-controls">
                                <div class="file-input-wrapper">
                                    <input type="text" class="file-input" id="fileName" placeholder="No file chosen" readonly />
                                </div>
                                <button type="submit" class="generate-button">Generate Table</button>
                            </div>
                        </div>
                    </form>
                </section>

                <!-- Student Data Table -->
                <div class="table-wrapper mt-4">
                    <div class="table-scroll">
                        <table class="table table-bordered table-hover table-striped custom-table mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Program</th>
                                    <th>Year Level</th>
                                    <th>Contact Number</th>
                                    <th>Date of Birth</th>
                                </tr>
                            </thead>
                            <tbody id="studentTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- JS -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
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

                // File input handling
                const fileInput = document.getElementById('fileInput');
                const fileNameDisplay = document.getElementById('fileName');

                fileInput.addEventListener('change', function() {
                    fileNameDisplay.value = this.files[0]?.name || 'No file chosen';
                });
            });
        </script>
    @endpush
</x-app-layout>
