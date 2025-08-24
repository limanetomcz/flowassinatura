/**
 * =============================================================================
 * Admin Dashboard - Document Create JS
 * =============================================================================
 * Purpose:
 *   Enhance the "Create Document" form with drag & drop PDF upload
 *   and dynamic status select icons.
 *
 * Features:
 *   1. Drag & Drop PDF Upload
 *      - Click to open file selector
 *      - Drag & drop support
 *      - PDF-only validation
 *      - Dynamic icon and hover feedback
 *      - Animated file name display
 *
 *   2. Dynamic Status Select
 *      - Updates icon and color according to selected status
 *      - Provides immediate visual feedback
 *
 * Dependencies:
 *   • FontAwesome for icons
 *   • Animate.css (or custom fade-in class) for animation
 *
 * =============================================================================
 */

/* ============================================================================ 
   SECTION 1: Drag & Drop PDF Upload 
============================================================================ */
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file_path');
const fileName = document.getElementById('file-name');
const dropIcon = dropZone.querySelector('i');

// Click opens hidden file input
dropZone.addEventListener('click', () => fileInput.click());

// Drag over: hover effect & icon change
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('hover');
    if (dropIcon) dropIcon.classList.replace('fa-cloud-upload-alt', 'fa-file-pdf');
});

// Drag leave: remove hover effect
dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('hover');
    if (dropIcon) dropIcon.classList.replace('fa-file-pdf', 'fa-cloud-upload-alt');
});

// Drop: assign file to input, validate PDF, show name
dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('hover');
    if (dropIcon) dropIcon.classList.replace('fa-file-pdf', 'fa-cloud-upload-alt');

    if (e.dataTransfer.files.length) {
        const file = e.dataTransfer.files[0];

        if (file.type !== "application/pdf") {
            alert("Only PDF files are allowed!");
            fileInput.value = "";
            fileName.textContent = "";
            return;
        }

        fileInput.files = e.dataTransfer.files;
        fileName.textContent = file.name;
        fileName.classList.add("animate__fadeIn");
    }
});

// Input change: validate PDF, display name
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

    // Initialize icon on load
    updateStatusIcon();

    // Update icon dynamically on change
    statusSelect.addEventListener('change', updateStatusIcon);
});
