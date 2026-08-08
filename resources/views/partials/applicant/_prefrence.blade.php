
 <h4 style="margin-bottom: 15px; font-weight: bold;">Please Select Your Preference</h4>

<div class="form-group text-center">
    <label style="font-size: 0.9rem; font-weight: bold;">Applicant Type</label>
    <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-outline-primary {{ $user->applicant_type === 'live-in' ? 'active' : '' }}">
            <input type="radio" name="applicant_type" value="live-in"
                onclick="saveApplicantType('live-in')"
                {{ $user->applicant_type === 'live-in' ? 'checked' : '' }}>
            <span style="font-size: 0.85rem;">Live-In</span>
        </label>
        <label class="btn btn-outline-primary {{ $user->applicant_type === 'live-out' ? 'active' : '' }}">
            <input type="radio" name="applicant_type" value="live-out"
                onclick="saveApplicantType('live-out')"
                {{ $user->applicant_type === 'live-out' ? 'checked' : '' }}>
            <span style="font-size: 0.85rem;">Live-Out</span>
        </label>
        <label class="btn btn-outline-primary {{ $user->applicant_type === 'semi-live-in' ? 'active' : '' }}">
            <input type="radio" name="applicant_type" value="semi-live-in"
                onclick="saveApplicantType('semi-live-in')"
                {{ $user->applicant_type === 'semi-live-in' ? 'checked' : '' }}>
            <span style="font-size: 0.85rem;">Semi Live-In</span>
        </label>
    </div>
</div>

<p style="margin-top: 15px; font-size: 0.9rem; font-weight: bold;">Note:</p>

<h5 style="font-size: 0.95rem; font-weight: bold;">Live-In House Help</h5>
<p style="font-size: 0.85rem; line-height: 1.5;">
    <em>Live-in house help</em> resides with the family, offering 24/7 support for childcare, housekeeping, or elder care—ideal for families needing consistent, on-site assistance.
</p>

<h5 style="font-size: 0.95rem; font-weight: bold;">Live-Out House Help</h5>
<p style="font-size: 0.85rem; line-height: 1.5;">
    <em>Live-out house help</em> commutes daily, providing scheduled support for household tasks without living on the premises—perfect for families seeking flexible help within set hours.
</p>

<script>
    function saveApplicantType(applicantType) {
        fetch("{{ route('applicant.type') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    applicant_type: applicantType
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // For debugging
                if (data.success) {
                    alert("Preference saved successfully!");
                } else {
                    alert("Failed to save preference.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("There was an error saving your preference.");
            });
    }
</script>
