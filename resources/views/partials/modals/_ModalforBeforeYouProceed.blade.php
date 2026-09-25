{{-- =========================================================
    COMPANY-MANAGED: BEFORE YOU PROCEED MODAL
========================================================= --}}
@php
    /*
     * This page runs before the ServiceRequest exists, so we cannot
     * use $serviceRequest->management_subscription_fee here.
     *
     * The upfront company-managed payment is:
     * - ₦30,000 when annual registration is still due
     * - ₦20,000 when annual registration is already active
     */
    $hasActiveManagementSubscription =
        method_exists(auth()->user(), 'hasActiveCompanyManagementSubscription')
            ? auth()->user()->hasActiveCompanyManagementSubscription()
            : false;

    $companyRegistrationFee = 10000;
    $companyVerificationFee = 20000;

    $companyInitialPayment = $hasActiveManagementSubscription
        ? $companyVerificationFee
        : ($companyRegistrationFee + $companyVerificationFee);
@endphp

<div
    class="modal fade"
    id="companyManagedBeforeProceedModal"
    tabindex="-1"
    aria-labelledby="companyManagedBeforeProceedModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content ohh-company-modal">

            <div class="ohh-company-modal-header">

                <div class="ohh-company-modal-icon">
                    <i class="fas fa-shield-heart"></i>
                </div>

                <div>
                    <span class="ohh-company-modal-kicker">
                        COMPANY-MANAGED SERVICE
                    </span>

                    <h3 id="companyManagedBeforeProceedModalLabel">
                        Before You Proceed
                    </h3>
                </div>

            </div>


            <div class="ohh-company-modal-body">

                <p class="ohh-company-modal-intro">
                    You are choosing to have OurHouseHelp manage your staff.
                    Before we start matching you, here's what you should know.
                </p>


                <div class="ohh-company-initial-payment">

                    <span>
                        Initial Payment
                    </span>

                    <strong>
                        ₦{{ number_format($companyInitialPayment) }}
                    </strong>

                </div>


                <div class="ohh-company-breakdown">

                    @unless($hasActiveManagementSubscription)

                        <div class="ohh-company-breakdown-row">

                            <div>
                                <i class="fas fa-id-card"></i>
                                <span>Annual Registration</span>
                            </div>

                            <strong>
                                ₦{{ number_format($companyRegistrationFee) }}
                            </strong>

                        </div>

                    @endunless


                    <div class="ohh-company-breakdown-row">

                        <div>
                            <i class="fas fa-user-shield"></i>
                            <span>Staff Verification</span>
                        </div>

                        <strong>
                            ₦{{ number_format($companyVerificationFee) }}
                        </strong>

                    </div>

                </div>


                @if($hasActiveManagementSubscription)

                    <div class="ohh-company-explanation">

                        <i class="fas fa-circle-info"></i>

                        <p>
                            Your Annual Registration is already active, so you
                            won't pay the registration fee again. Your initial
                            payment is for thoroughly verifying your selected
                            staff and their guarantors.
                        </p>

                    </div>

                @else

                    <div class="ohh-company-explanation">

                        <i class="fas fa-circle-info"></i>

                        <p>
                            Your Annual Registration covers your account for
                            12 months. Staff Verification covers a thorough
                            check of your selected staff and their guarantors.
                        </p>

                    </div>

                @endif


                <div class="ohh-company-balance-note">

                    <i class="fas fa-calendar-check"></i>

                    <span>
                        The remaining booking balance is due when your staff
                        resumes work.
                    </span>

                </div>


                <p class="ohh-company-review-note">
                    Please review the details carefully before continuing.
                </p>

            </div>


            <div class="ohh-company-modal-footer">

                <button
                    type="button"
                    class="ohh-company-continue-btn"
                    id="confirmCompanyManagedBtn">

                    I Understand &amp; Continue

                    <i class="fas fa-arrow-right"></i>

                </button>

            </div>

        </div>

    </div>

</div>


<style>
     /* =========================================================
    COMPANY-MANAGED BEFORE PROCEED MODAL
 ========================================================= */

 .ohh-company-modal {
     overflow: hidden;
     border: 0;
     border-radius: 20px;

     box-shadow:
         0 25px 70px rgba(20, 36, 30, .18);
 }

 .ohh-company-modal-header {
     display: flex;
     align-items: center;
     gap: 13px;

     padding: 25px 27px 20px;

     background:
         linear-gradient(
             135deg,
             #F2FAF7,
             #FFFFFF
         );

     border-bottom: 1px solid #E5EBE7;
 }

 .ohh-company-modal-icon {
     width: 45px;
     height: 45px;

     display: flex;
     align-items: center;
     justify-content: center;

     flex: 0 0 45px;

     border-radius: 13px;

     background: #E4F2EC;
     color: #0F6E56;

     font-size: 17px;
 }

 .ohh-company-modal-kicker {
     display: block;

     margin-bottom: 3px;

     color: #A9782E;

     font-size: 9px;
     font-weight: 700;

     letter-spacing: .14em;
 }

 .ohh-company-modal-header h3 {
     margin: 0;

     color: #18352D;

     font-family: Georgia, serif;

     font-size: 23px;
     font-weight: 500;
 }

 .ohh-company-modal-body {
     padding: 23px 27px 20px;
 }

 .ohh-company-modal-intro {
     margin: 0 0 18px;

     color: #707B75;

     font-size: 11px;
     line-height: 1.7;
 }

 .ohh-company-initial-payment {
     display: flex;
     flex-direction: column;
     align-items: center;

     padding: 16px;

     margin-bottom: 17px;

     border: 1px solid #DDE8E2;
     border-radius: 13px;

     background: #F7FAF8;

     text-align: center;
 }

 .ohh-company-initial-payment span {
     color: #78847E;

     font-size: 10px;
 }

 .ohh-company-initial-payment strong {
     margin-top: 3px;

     color: #0F6E56;

     font-size: 27px;
     font-weight: 700;
 }

 .ohh-company-breakdown {
     display: flex;
     flex-direction: column;

     gap: 9px;

     margin-bottom: 16px;
 }

 .ohh-company-breakdown-row {
     display: flex;
     justify-content: space-between;
     align-items: center;

     gap: 15px;

     padding: 10px 11px;

     border-radius: 9px;

     background: #FAFCFB;
 }

 .ohh-company-breakdown-row > div {
     display: flex;
     align-items: center;

     gap: 8px;

     color: #66746D;

     font-size: 11px;
 }

 .ohh-company-breakdown-row i {
     color: #0F6E56;

     font-size: 11px;
 }

 .ohh-company-breakdown-row strong {
     color: #33453D;

     font-size: 11px;
 }

 .ohh-company-explanation {
     display: flex;
     align-items: flex-start;

     gap: 9px;

     margin-bottom: 11px;
     padding: 11px 12px;

     border-radius: 10px;

     background: #F3F8F5;

     color: #68766F;
 }

 .ohh-company-explanation i {
     margin-top: 2px;

     color: #0F6E56;

     font-size: 11px;
 }

 .ohh-company-explanation p {
     margin: 0;

     font-size: 10px;
     line-height: 1.6;
 }

 .ohh-company-balance-note {
     display: flex;
     align-items: center;

     gap: 8px;

     padding: 10px 12px;

     border: 1px solid #E9DFC9;
     border-radius: 10px;

     background: #FCF8EF;

     color: #786D58;

     font-size: 10px;
     line-height: 1.5;
 }

 .ohh-company-balance-note i {
     color: #A9782E;

     font-size: 11px;
 }

 .ohh-company-review-note {
     margin: 13px 0 0;

     color: #9AA29E;

     font-size: 9px;

     text-align: center;
 }

 .ohh-company-modal-footer {
     padding: 0 27px 25px;
 }

 .ohh-company-continue-btn {
     width: 100%;

     min-height: 46px;

     display: flex;
     align-items: center;
     justify-content: center;

     gap: 9px;

     border: 0;
     border-radius: 10px;

     background: #0F6E56;
     color: #fff;

     font-size: 12px;
     font-weight: 700;

     transition: .2s ease;
 }

 .ohh-company-continue-btn:hover {
     background: #0B4A3B;

     transform: translateY(-1px);

     box-shadow:
         0 8px 18px rgba(15, 110, 86, .15);
 }

 .ohh-company-continue-btn i {
     font-size: 10px;
 }


 @media (max-width: 575.98px) {

     .ohh-company-modal-header {
         padding: 21px 19px 17px;
     }

     .ohh-company-modal-body {
         padding: 19px;
     }

     .ohh-company-modal-footer {
         padding: 0 19px 20px;
     }

     .ohh-company-modal-header h3 {
         font-size: 21px;
     }

     .ohh-company-initial-payment strong {
         font-size: 24px;
     }

 }
</style>