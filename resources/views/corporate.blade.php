@extends('layouts.main')
@section('corporate')
    <section class="ci-page">
        <div class="container">
            <div class="ci-layout">

                <div class="ci-intro font-sans">
                    <p class="ci-kicker ">Executive Staffing</p>
                    <h1>Staff your executives with people you can trust.</h1>
                    <p class="ci-lede">
                        Tell us what your company needs. A member of our team will
                        follow up directly to scope the role, pricing, and
                        verification process — no self-checkout, no guesswork.
                    </p>

                    <ul class="ci-points">
                        <li>
                            <i class="fas fa-shield-halved"></i>
                            <div>
                                <strong>Every candidate is verified</strong>
                                <span>NIN, background check, and guarantors — before anyone is put forward.</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-handshake"></i>
                            <div>
                                <strong>A dedicated point of contact</strong>
                                <span>One person at OurHouseHelp manages your account, not a queue.</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-file-invoice"></i>
                            <div>
                                <strong>Terms built around your company</strong>
                                <span>Pricing and invoicing arranged to fit how you already work.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="ci-form-card rounded-3" style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: box-shadow 1.5s ease-in-out;"  id="shadow-box">

                    @if(session('success'))
                        <div class="ci-success">
                            <i class="fas fa-circle-check"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    @else

                    <h2>Tell us about your company</h2>

                    @if($errors->any())
                        <div class="ci-error-box">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('corporate.submit') }}">
                        @csrf

                        <div class="ci-row">
                            <div class="ci-field">
                                <label>Company Name</label>
                                <input type="text" name="company_name" value="{{ old('company_name') }}" required>
                            </div>
                            <div class="ci-field">
                                <label>Contact Name</label>
                                <input type="text" name="contact_name" value="{{ old('contact_name') }}" required>
                            </div>
                        </div>

                        <div class="ci-row">
                            <div class="ci-field">
                                <label>Work Email</label>
                                <input type="email" name="work_email" value="{{ old('work_email') }}" required>
                            </div>
                            <div class="ci-field">
                                <label>Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="15">
                            </div>
                        </div>

                        <div class="ci-field">
                            <label>How many staff do you need?</label>
                            <input type="number" name="staff_count" min="1" max="500" value="{{ old('staff_count', 1) }}" required>
                        </div>

                        <div class="ci-field">
                            <label>What kind of staff? <span>(select all that apply)</span></label>
                            <div class="ci-checkboxes">
                                @php
                                    $staffTypeOptions = [
                                        'driver' => 'Personal Driver',
                                        'personal_assistant' => 'Executive Assistant',
                                        'housekeeper' => 'Housekeeper',
                                        'chef' => 'Chef',
                                        'nanny' => 'Nanny',
                                        'security' => 'Security',
                                        'other' => 'Other',
                                    ];
                                    $oldTypes = old('staff_types', []);
                                @endphp
                                @foreach($staffTypeOptions as $value => $label)
                                    <label class="ci-checkbox">
                                        <input type="checkbox" name="staff_types[]" value="{{ $value }}"
                                            {{ in_array($value, $oldTypes) ? 'checked' : '' }}>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="ci-field">
                            <label>Preferred Start Date <span>(optional)</span></label>
                            <input type="date" name="preferred_start_date" value="{{ old('preferred_start_date') }}">
                        </div>

                        <div class="ci-field">
                            <label>Anything else we should know? <span>(optional)</span></label>
                            <textarea name="notes" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="ci-submit rounded-3">Send Inquiry</button>

                        <p class="ci-fine-print">
                            We'll only use these details to follow up about your request.
                        </p>
                    </form>

                    @endif
                </div>

            </div>
        </div>
    </section>

    <style>
        :root {
            --ohh-green: #0F6E56;
            --ohh-green-dark: #0B4A3B;
            --ohh-gold: #A9782E;
            --ohh-paper: #FAF6EF;
            --ohh-ink: #16241F;
        }

        .ci-page {
            font-family: 'Work Sans', sans-serif;
            background: var(--ohh-paper);
            color: var(--ohh-ink);
            padding: 72px 0;
        }

        .ci-layout {
            display: grid;
            grid-template-columns: 0.95fr 1.15fr;
            gap: 56px;
            align-items: start;
        }

        .ci-kicker {
            font-size: 13px;
            font-weight: 600;
            color: var(--ohh-gold);
            margin-bottom: 14px;
        }

        .ci-intro h1 {
            font-family: 'Fraunces', serif;
            font-weight: 560;
            font-size: clamp(1.8rem, 3.2vw, 2.5rem);
            line-height: 1.15;
            color: var(--ohh-green-dark);
            margin-bottom: 18px;
        }

        .ci-lede {
            font-size: 16px;
            line-height: 1.65;
            color: #4B5A54;
            margin-bottom: 36px;
            max-width: 46ch;
        }

        .ci-points {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .ci-points li {
            display: flex;
            gap: 16px;
            padding: 18px 0;
            border-top: 1px solid #E4DCC8;
        }

        .ci-points li:first-child {
            border-top: none;
        }

        .ci-points i {
            color: var(--ohh-green);
            font-size: 18px;
            margin-top: 3px;
            flex-shrink: 0;
            width: 22px;
        }

        .ci-points strong {
            display: block;
            font-size: 15px;
            margin-bottom: 3px;
        }

        .ci-points span {
            font-size: 14px;
            color: #5E6A62;
            line-height: 1.5;
        }

        .ci-form-card {
            background: #fff;
            border: 1px solid #E4DCC8;
            border-radius: 4px;
            padding: 40px;
        }

        .ci-form-card h2 {
            font-family: 'Fraunces', serif;
            font-weight: 560;
            font-size: 22px;
            color: var(--ohh-green-dark);
            margin-bottom: 24px;
        }

        .ci-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .ci-field {
            margin-bottom: 18px;
        }

        .ci-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--ohh-ink);
            margin-bottom: 7px;
        }

        .ci-field label span {
            font-weight: 400;
            color: #8A8478;
        }

        .ci-field input[type="text"],
        .ci-field input[type="email"],
        .ci-field input[type="tel"],
        .ci-field input[type="number"],
        .ci-field input[type="date"],
        .ci-field textarea {
            width: 100%;
            border: 1px solid #D8D0BC;
            border-radius: 3px;
            padding: 10px 12px;
            font-size: 15px;
            font-family: 'Work Sans', sans-serif;
            color: var(--ohh-ink);
            background: #FEFDFB;
        }

        .ci-field input:focus,
        .ci-field textarea:focus {
            outline: none;
            border-color: var(--ohh-green);
            box-shadow: 0 0 0 3px rgba(15, 110, 86, 0.1);
        }

        .ci-checkboxes {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .ci-checkbox {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 14px;
            font-weight: 400;
            padding: 8px 10px;
            border: 1px solid #E4DCC8;
            border-radius: 3px;
            cursor: pointer;
        }

        .ci-checkbox input {
            accent-color: var(--ohh-green);
        }

        .ci-submit {
            width: 100%;
            background: var(--ohh-green-dark);
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Work Sans', sans-serif;
            cursor: pointer;
            margin-top: 8px;
        }

        .ci-submit:hover {
            background: var(--ohh-green);
        }

        .ci-fine-print {
            font-size: 12px;
            color: #8A8478;
            text-align: center;
            margin: 14px 0 0;
        }

        .ci-error-box {
            background: #FCEEEE;
            border: 1px solid #E9BEBE;
            color: #8A2E2E;
            border-radius: 3px;
            padding: 14px 18px;
            font-size: 14px;
            margin-bottom: 22px;
        }

        .ci-success {
            text-align: center;
            padding: 40px 20px;
        }

        .ci-success i {
            font-size: 40px;
            color: var(--ohh-green);
            margin-bottom: 16px;
        }

        .ci-success p {
            font-size: 16px;
            color: var(--ohh-ink);
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            .ci-layout {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .ci-form-card {
                padding: 28px 24px;
            }
        }

        @media (max-width: 560px) {
            .ci-row, .ci-checkboxes {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // A micro-timeout ensures the browser registers the initial shadow-sm state first
            setTimeout(() => {
            document.getElementById('shadow-box').style.boxShadow = '0 10px 20px 10px rgba(0, 0, 0, 0.2), 0 4px 6px -4px rgba(0, 0, 0, 0.1)';
            }, 5);
        });
    </script>
@endsection