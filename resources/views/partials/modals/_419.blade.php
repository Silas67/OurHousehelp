<!-- In your layout file (e.g., resources/views/layouts/app.blade.php) -->
<div id="csrfExpiredModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; text-align: center;">
    <div style="background: white; margin: 15% auto; padding: 20px; width: 50%; border-radius: 8px;">
        <h2>Session Expired</h2>
        <p>Your session has expired. Please refresh the page to continue.</p>
        <button onclick="location.reload()" style="padding: 10px 20px; background-color: green; color: white; border: none; cursor: pointer;">
            Refresh Page
        </button>
    </div>
</div>


<script>
    document.addEventListener('ajaxError', function(event, xhr) {
        if (xhr.status === 419) { // Check if it's a CSRF token mismatch
            document.getElementById('csrfExpiredModal').style.display = 'block';
        }
    });

    // Alternatively, handle for forms or manual AJAX calls
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                fetch(form.action, {
                    method: form.method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: new FormData(form),
                }).catch(error => {
                    if (error.response && error.response.status === 419) {
                        event.preventDefault();
                        document.getElementById('csrfExpiredModal').style.display =
                            'block';
                    }
                });
            });
        });
    });
</script>
