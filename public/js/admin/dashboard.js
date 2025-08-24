// -----------------------------------------------------------------------------
// Admin Dashboard Script
// -----------------------------------------------------------------------------
// Purpose:
//   • Enhance the admin dashboard UX by logging user info and animating counters.
//   • Make statistics more dynamic and visually engaging.
//
// Features:
//   1. Logs the currently authenticated user's email.
//   2. Animates number counters from 0 to their target values.
//
// Usage:
//   • Include this script on the admin dashboard page.
// -----------------------------------------------------------------------------

// -----------------------------------------------------------------------------
// Section: User Logging
// -----------------------------------------------------------------------------
// Purpose:
//   • Display which admin user has loaded the dashboard for debugging and
//     confirmation purposes.
//
// Element/Behavior:
//   • Logs the authenticated user's email to the console.
// -----------------------------------------------------------------------------
console.log('Admin dashboard loaded for', '{{ auth()->user()->email }}');

// -----------------------------------------------------------------------------
// Section: Animated Counters
// -----------------------------------------------------------------------------
// Purpose:
//   • Smoothly animate numerical statistics from 0 to their target values.
//
// Elements:
//   • All DOM elements with class "counter" and a "data-target" attribute.
//
// Behavior:
//   1. On DOMContentLoaded, find all counter elements.
//   2. For each counter, increment its value step by step until it reaches
//      the target value.
//   3. Update frequency: every 20ms.
//   4. Ensures the final displayed value exactly matches the target.
//
// Example HTML:
//   <span class="counter" data-target="150">0</span>
// -----------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.counter');

    counters.forEach(counter => {
        // ---------------------------------------------------------------------
        // Function: updateCount
        // Purpose:
        //   • Increment the counter value smoothly.
        // Behavior:
        //   • Calculate step size as target/100 for smoothness.
        //   • Use setTimeout recursively for animation.
        //   • Stop when the target is reached and ensure exact value.
        // ---------------------------------------------------------------------
        const updateCount = () => {
            const target = +counter.getAttribute('data-target'); // Final goal
            const count = +counter.innerText;                    // Current value
            const increment = Math.ceil(target / 100);           // Step size

            if (count < target) {
                counter.innerText = count + increment;
                setTimeout(updateCount, 20); // Update every 20ms for smooth effect
            } else {
                counter.innerText = target; // Ensure exact target
            }
        };

        // Start the counter animation
        updateCount();
    });
});
