/*
 * Bootstrap 5.3.3 JS bundle (includes Popper) for the Blade layouts.
 *
 * Replaces a mix of CDN loads - Bootstrap 4.5.2/4.6.2/5.1.3/5.3.0/5.3.1/5.3.3,
 * two standalone Popper builds and jQuery slim - several of which were loaded
 * together on the same page.
 *
 * Exposed on window because inline scripts in the Blade views construct
 * components directly (e.g. new bootstrap.Modal(...)).
 */

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

/*
 * Keep content clear of the fixed navbar.
 *
 * style.css pins .navbar with position:fixed and compensates with a hardcoded
 * `body { padding-top: 60px }`. The header is taller than that at most widths -
 * the logo alone is ~130px - so the first section of every public page was
 * sliding underneath it. The offset also changes as the nav wraps on narrow
 * screens, which no single fixed value can cover.
 *
 * Measuring the rendered header avoids guessing. Only applies when the navbar
 * really is fixed, so the dashboard layouts (whose navbar sits inside the
 * scrolling content area) are untouched.
 */
function offsetFixedNavbar() {
    var nav = document.querySelector('.navbar');
    if (!nav) return;

    if (window.getComputedStyle(nav).position !== 'fixed') {
        return;
    }

    var height = nav.offsetHeight;
    if (height > 0) {
        document.body.style.paddingTop = height + 'px';
    }
}

window.addEventListener('DOMContentLoaded', offsetFixedNavbar);
window.addEventListener('load', offsetFixedNavbar);

var resizeTimer;
window.addEventListener('resize', function () {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(offsetFixedNavbar, 150);
});
