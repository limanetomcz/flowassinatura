/**
 * =============================================================================
 * Admin Dashboard - Documents Page JS
 * =============================================================================
 * Purpose:
 *   Enhance the Documents Admin page with:
 *     1. Bootstrap tooltips for better UX on hover elements.
 *     2. Live search to filter documents by title or company in real-time.
 *
 * Features:
 *   • Modular and reusable functions
 *   • Smooth show/hide animations for cards
 *   • Simple, declarative DOM manipulation
 *
 * Dependencies:
 *   • Bootstrap 5 (for tooltips)
 *
 * =============================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // Initialize tooltips and live search when the DOM is fully loaded
    initTooltips();
    initLiveSearch('#document-search', '#documents-row .document-card');
});


/**
 * Function: initTooltips
 * -----------------------------------------------------------------------------
 * Enables Bootstrap tooltips on all elements that have the attribute:
 *   data-bs-toggle="tooltip"
 *
 * Description:
 *   - Finds all matching elements and initializes Bootstrap.Tooltip instances.
 *   - Improves user experience by showing hover hints.
 */
function initTooltips() {
    const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipElements.forEach(el => new bootstrap.Tooltip(el));
}


/**
 * Function: initLiveSearch
 * -----------------------------------------------------------------------------
 * Adds live search functionality to filter document cards based on user input.
 *
 * Parameters:
 *   @param {string} inputSelector - CSS selector for the search input field.
 *   @param {string} cardSelector  - CSS selector for the document cards.
 *
 * Description:
 *   - Filters document cards as the user types.
 *   - Checks for matches in:
 *       • Document title (inside .card-header span)
 *       • Company name (inside .col-md-3)
 *   - Smoothly shows/hides cards depending on match.
 */
function initLiveSearch(inputSelector, cardSelector) {
    const searchInput = document.querySelector(inputSelector);
    const documents = document.querySelectorAll(cardSelector);

    if (!searchInput) return; // Exit if no search input exists

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase().trim();

        documents.forEach(card => {
            // Extract text content from title and company elements
            const titleEl = card.querySelector('.card-header span');
            const companyEl = card.querySelector('.col-md-3');

            const title = titleEl ? titleEl.textContent.toLowerCase() : '';
            const company = companyEl ? companyEl.textContent.toLowerCase() : '';

            // Show card if it matches query, otherwise hide it
            if (title.includes(query) || company.includes(query)) {
                showCard(card);
            } else {
                hideCard(card);
            }
        });
    });
}


/**
 * Helper Function: showCard
 * -----------------------------------------------------------------------------
 * Makes a document card visible with smooth display style.
 *
 * Parameters:
 *   @param {HTMLElement} card - The document card to show.
 */
function showCard(card) {
    card.style.display = 'flex';
    card.style.opacity = '1';
}


/**
 * Helper Function: hideCard
 * -----------------------------------------------------------------------------
 * Hides a document card with a quick fade-out effect.
 *
 * Parameters:
 *   @param {HTMLElement} card - The document card to hide.
 */
function hideCard(card) {
    card.style.opacity = '0';
    setTimeout(() => {
        card.style.display = 'none';
    }, 200);
}
