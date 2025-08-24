/**
 * =============================================================================
 * Admin Dashboard - Document Edit JS
 * =============================================================================
 */

/* ============================================================================ 
   SECTION 1: Drag & Drop PDF Upload
============================================================================ */
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file_path');
const fileName = document.getElementById('file-name');
const dropIcon = dropZone.querySelector('i');

// Inicializa o nome do arquivo atual, se existir
document.addEventListener('DOMContentLoaded', () => {
    if (fileName.textContent.trim() !== '') {
        fileName.classList.add("animate__fadeIn");
    }
});

/**
 * Click Event
 */
dropZone.addEventListener('click', () => fileInput.click());

/**
 * Drag Over Event
 */
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('hover');
    if (dropIcon) dropIcon.classList.replace('fa-cloud-upload-alt', 'fa-file-pdf');
});

/**
 * Drag Leave Event
 */
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('hover');
    if (dropIcon) dropIcon.classList.replace('fa-file-pdf', 'fa-cloud-upload-alt');
});

/**
 * Drop Event
 */
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('hover');
    if (dropIcon) dropIcon.classList.replace('fa-file-pdf', 'fa-cloud-upload-alt');

    if (e.dataTransfer.files.length) {
        const file = e.dataTransfer.files[0];

        // Validate PDF
        if (file.type !== "application/pdf") {
            alert("Only PDF files are allowed!");
            fileInput.value = "";
            fileName.textContent = "";
            return;
        }

        // Assign file and display name
        fileInput.files = e.dataTransfer.files;
        fileName.textContent = file.name;
        fileName.classList.add("animate__fadeIn");
    }
});

/**
 * File Input Change Event
 */
fileInput.addEventListener('change', () => {
    if (fileInput.files.length) {
        const file = fileInput.files[0];

        if (file.type !== "application/pdf") {
            alert("Only PDF files are allowed!");
            fileInput.value = "";
            fileName.textContent = "";
            return;
        }

        fileName.textContent = file.name;
        fileName.classList.add("animate__fadeIn");
    }
});

/* ============================================================================ 
   SECTION 2: Dynamic Status Select
============================================================================ */
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('status');
    const statusIcon = document.getElementById('status-icon').querySelector('i');

    function updateStatusIcon() {
        const selectedOption = statusSelect.options[statusSelect.selectedIndex];
        const iconClass = selectedOption.getAttribute('data-icon');
        const colorClass = selectedOption.getAttribute('data-color');

        // Clear previous classes
        statusIcon.className = '';

        // Apply new icon and color
        statusIcon.classList.add(...iconClass.split(' '), ...colorClass.split(' '));
    }

    // Initialize icon on page load
    updateStatusIcon();

    // Update dynamically when selection changes
    statusSelect.addEventListener('change', updateStatusIcon);
});
