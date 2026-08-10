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
