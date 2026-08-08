<h3 class="welcome-text">Welcome {{ Auth::user()->name }}!</h3>
<p class="instructions-text">Please read our terms and conditions carefully and accept the conditions to work with us.</p>

<div class="applicant-terms">
    <h3 class="section-title">Applicant Terms & Conditions</h3>
    <p class="intro-text">Welcome to Our Team!</p>
    <p class="intro-text">
        Please read these Terms and Conditions carefully. By agreeing to work with us, you acknowledge and accept the conditions outlined below.
    </p>

    <h4 class="subsection-title">1. Introduction</h4>
    <p class="subsection-text">
        These Terms and Conditions apply to all individuals working with us. By accepting an offer to work with us or using our services, you agree to comply with these terms. If you disagree with any part of these terms, please inform us immediately.
    </p>

    <h4 class="subsection-title">2. Employee Obligations</h4>
    <p class="subsection-text">As an employee, you agree to:</p>
    <ul class="list-text">
        <span>Provide accurate and complete information.</span><br>
        <span>Keep your personal and employment details updated.</span><br>
        <span>Maintain the confidentiality of any login credentials or sensitive information.</span><br>
        <span>Use our services and resources responsibly and not for any unlawful purpose.</span><br>
    </ul>

    <h4 class="subsection-title">3. Privacy Policy</h4>
    <p class="subsection-text">
        We value your privacy. Please review our <a href="{{ route('applicantprivacy') }}" target="_blank">Privacy Policy</a> to understand how we collect, use, and safeguard your personal information.
    </p>

    <h4 class="subsection-title">4. Limitation of Liability</h4>
    <p class="subsection-text">
        We will not be held liable for any indirect, incidental, or consequential damages arising from your use of our services. We strive to ensure a safe working environment, but we cannot be responsible for external factors outside our control.
    </p>

    <h4 class="subsection-title">5. Modifications to Terms</h4>
    <p class="subsection-text">
        We reserve the right to modify these Terms and Conditions at any time. Any updates will be effective immediately upon being posted on this page. Continued use of our services after changes indicates acceptance of the new terms.
    </p>

    <h4 class="subsection-title">6. Termination</h4>
    <p class="subsection-text">
        We may suspend or terminate access to services without prior notice in cases of policy violations or any breach of these terms.
    </p>

    <h4 class="subsection-title">7. Governing Law</h4>
    <p class="subsection-text">
        These Terms and Conditions are governed by the laws of [Your Jurisdiction]. Any disputes arising from these terms will be subject to the exclusive jurisdiction of the courts in [Your Jurisdiction].
    </p>

    <h4 class="subsection-title">8. Contact Us</h4>
    <p class="subsection-text">
        For any questions or concerns regarding these terms, please do not hesitate to <a href="/#contact" target="_blank">contact us</a>.
    </p>
</div>


