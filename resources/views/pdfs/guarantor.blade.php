<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f8f8f8;
    }

    .content {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
        border-bottom: 2px solid #007BFF;
        padding-bottom: 10px;
    }

    .section {
        margin-bottom: 15px;
    }

    .section strong {
        width: 150px;
        display: inline-block;
    }

    img {
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-top: 5px;
    }

    .footer {
        margin-top: 30px;
        text-align: center;
    }
</style>

<div class="content">
    <div class="header">Confirmation of Guarantor Use</div>

    <p>Dear {{ $guarantor->first_name }},</p>

    <p>Thank you once again for your support. I truly appreciate your willingness to assist me during this important
        phase of my life. Please feel free to reach out if you have any questions or need further information.</p>

    <p><strong>Guarantor Details:</strong></p>
    <div class="section">
        <strong>First Name:</strong> {{ $guarantor->first_name }}<br>
        <strong>Last Name:</strong> {{ $guarantor->last_name }}<br>
        <strong>Email:</strong> {{ $guarantor->email ?? 'N/A' }}<br>
        <strong>Phone:</strong> {{ $guarantor->phone }}<br>
        <strong>Relationship:</strong> {{ $guarantor->relationship }}<br>
        <strong>Occupation:</strong> {{ $guarantor->occupation }}<br>
        <strong>Address:</strong> {{ $guarantor->address }}
    </div>


    <div class="section">
        <div>
            <strong>Passport Photo:</strong>
            <img src="{{ isset($guarantor->passport_path) ? asset('build/assets/image/passports/' . $guarantor->passport_path) : asset('build/assets/image/slider1.jpg') }}"
                width="100" height="100">
        </div>
        <div>
            <strong>ID Card:</strong>
            <img src="{{ isset($guarantor->signature_path) ? asset('build/assets/image/signatures/' . $guarantor->signature_path) : asset('build/assets/image/slider1.jpg') }}"
                width="100" height="50">
        </div>
    </div>

    @if ($guarantor->signature_path)
        <img id="profile-photo-preview"
            src="{{ asset('build/assets/image/profile_photo/' . Auth::user()->profile_photo_path) }}"
            alt="Profile Photo" class="img-thumbnail" style="width: 150px; height: 150px;">
    @else
        <img id="profile-photo-preview" src="{{ asset('build/assets/image/slider1.jpg') }}" alt="Profile Photo"
            class="img-thumbnail" style="width: 150px; height: 150px;">
    @endif


    <p>Thank you once again for your support.<br><br>
        Sincerely,<br>
        {{ $applicant->first_name }} {{ $applicant->last_name }}<br>
        Phone: {{ $applicant->phone }}
    </p>

    <div class="section">
        <strong>Our House Help Contact:</strong><br>
        Email: info@ourhousehelp.com<br>
        Phone 1: +234 916 7696 525<br>
        Phone 2: +234 706 6663 868<br>
        Address: Block A, House 4, Plot 822, Da Akun Adawel Street, Akhnaton Estate, After Dunamis Church, Area 1 Durumi
        District, Abuja.
    </div>

    <div class="footer">
        <p>Date: {{ now()->format('F j, Y') }}</p>
    </div>
</div>
