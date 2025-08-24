/**
 * =============================================================================
 * Admin Dashboard - Signatures Page JS
 * =============================================================================
 * Purpose:
 *   Enhance the Signatures page in the admin dashboard by providing:
 *     1. Bootstrap tooltips on hover elements.
 *     2. Fade-in animations for signature cards with staggered effect.
 *
 * Features:
 *   • Modular, reusable, and declarative code
 *   • Smooth visual feedback for cards
 *   • Easy to maintain and extend
 *
 * Dependencies:
 *   • Bootstrap 5 (for tooltips)
 *
 * =============================================================================
 */

console.log('Signatures page loaded');

/**
 * DOMContentLoaded Event
 * -----------------------------------------------------------------------------
 * Initializes tooltips and applies fade-in animation to all signature cards
 * after the DOM has fully loaded.
 */
document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------------
    // Bootstrap Tooltips
    // -------------------------------------------------------------------------
    const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(tooltipTriggerEl => {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // -------------------------------------------------------------------------
    // Fade-In Animation for Signature Cards
    // -------------------------------------------------------------------------
    const cards = document.querySelectorAll('.signature-card');
    cards.forEach((card, index) => {
        // Initial hidden state
        card.style.opacity = 0;
        card.style.transform = 'translateY(20px)';

        // Apply transition with staggered delay
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = 1;
            card.style.transform = 'translateY(0)';
        }, index * 100); // stagger animation by 100ms per card
    });
});
