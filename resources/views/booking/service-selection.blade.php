@extends('layouts.usermain')

@section('booking-service-selection')
<div class="row justify-content-center">
    <div class="col-lg-10">
            <!-- Progress Steps -->
            <div class="d-flex justify-content-center mb-4">
                <div class="booking-steps">
                    <span class="step active">1. Select Service</span>
                    <span class="step">2. Staff Matching</span>
                    <span class="step">3. Choose Plan</span>
                    <span class="step">4. Payment</span>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Book a Service</h4>
                </div>
                <div class="card-body p-4">

                    <form action="{{ route('booking.save-service-selection') }}" method="POST" id="bookingForm">

                        @csrf

                        <!-- Services Selection -->
                        <div class="mb-4">
                           <h5 class="fw-bold mb-3"><i class="fas fa-concierge-bell me-2"></i>What service do you need?</h5>
                            <div class="row g-3">
                                @foreach($services as $service)
                                <div class="col-md-4 col-6">
                                    <div class="form-check service-card p-3 border rounded h-100">
                                        <input class="form-check-input service-checkbox" type="checkbox"
                                        name="services[]" value="{{ $service->id }}"
                                        id="service_{{ $service->id }}"
                                        data-cost="{{ $service->base_cost }}"
                                        {{ in_array($service->id, old('services', [])) ? 'checked' : '' }}>

                                        <label class="form-check-label w-100" for="service_{{ $service->id }}">
                                            <div class="text-center">
                                                <i class="{{ $service->icon_class ?? 'fas fa-broom' }} fa-2x mb-2 text-primary"></i>
                                                <div class="fw-bold">{{ $service->service_name }}</div>
                                                <small class="text-muted" style="font-size: 12px;">{{ $service->about_service }}</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('services')
                             <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Live-in / Live-out Selection -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-home me-2"></i>Service Type</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="applicant_type"
                                        value="live-out" id="liveOut" {{ old('applicant_type', 'live-out') === 'live-out' ? 'checked' : '' }}>
                                        
                                        <label class="form-check-label" for="liveOut">
                                            <strong>Live-Out</strong> <br>
                                            <small class="text-muted">Staff comes and goes daily</small>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="applicant_type"
                                            value="semi-live-in" id="semiLiveIn" {{ old('applicant_type') === 'semi-live-in' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="semiLiveIn">
                                            <strong>Semi Live-In</strong> <br>
                                            <small class="text-muted">Staff works 5-6 days, goes home on off days</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-map-marker-alt me-2"></i>Your Location</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <select name="state" class="form-select" required>
                                        <option value="">Select State</option>
                                        @foreach($nigerianStates as $state)
                                            <option value="{{ $state }}" {{ old('state') == $state ? 'selected' : '' }}>
                                                {{ $state }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Area / City <span class="text-danger">*</span></label>
                                    <select name="city" id="citySelect" class="form-select" required>
                                        <option value="">Select a state first</option>
                                    </select>
                                    @error('city')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Full Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control"
                                        placeholder="Street address" value="{{ old('address') }}" required>
                                    @error('address')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- PREFERENCES -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Preferences</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="religion" class="form-select">
                                        <option value="">Religion (Optional)</option>
                                        <option>Christian</option>
                                        <option>Muslim</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select name="age_range" class="form-select">
                                        <option value="">Age Range (Optional)</option>
                                        <option>18-25</option>
                                        <option>26-35</option>
                                        <option>36-45</option>
                                        <option>46+</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <!-- Scheduling -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-calendar-alt me-2"></i>Schedule</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Preferred Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="preferred_start_date" class="form-control" min="{{ date('Y-m-d') }}" value="{{ old('preferred_start_date') }}" required>
                                    @error('preferred_start_date')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Duration <span class="text-danger">*</span></label>
                                    <select name="duration_weeks" class="form-select" id="durationSelect" required>
                                        <option value="">Select Duration</option>
                                        <option value="1" {{ old('duration_weeks') == 1 ? 'selected' : '' }}>One-off (single visit)</option>
                                        <option value="4" {{ old('duration_weeks') == 4 ? 'selected' : '' }}>4 weeks (1 month)</option>
                                        <option value="8" {{ old('duration_weeks') == 8 ? 'selected' : '' }}>8 weeks (2 months) — 5% off</option>
                                        <option value="12" {{ old('duration_weeks') == 12 ? 'selected' : '' }}>12 weeks (3 months) — 8% off</option>
                                    </select>
                                    @error('duration_weeks')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Apartment Type -->
                        @if($apartments->count() > 0)
                            <div class="mb-4 d-none" id="apartmentSection">
                                <h5 class="fw-bold mb-3">Apartment Type</h5>
                                <select name="apartment_type" class="form-select">
                                    <option value="">Select apartment type</option>
                                    @foreach($apartments as $apt)
                                        <option value="{{ $apt->id }}" data-cost="{{ $apt->cost }}">
                                            {{ $apt->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Package Selection -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-box me-2"></i>Select Package</h5>
                            <select name="package" class="form-select" id="packageSelect" required>
                                <option value="">Choose a package</option>
                                @foreach($packages as $pkg)
                                    <option value="{{ $pkg->id }}" data-cost="{{ $pkg->cost }}"
                                        {{ old('package') == $pkg->id ? 'selected' : '' }}>
                                        {{ $pkg->name }} - {{ $pkg->days_per_week }} days/week
                                    </option>
                                @endforeach
                            </select>
                            @error('package')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>      

                        <!-- Service Days & Working Hours -->
                        <div class="mb-4" id="serviceDaysSection">
                            <h5 class="fw-bold mb-3"><i class="fas fa-calendar-alt me-2"></i>Schedule <span class="text-muted fw-normal" style="font-size:13px;">(optional — you can agree with staff after matching)</span></h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Preferred Service Days</label>
                                    <div class="d-flex flex-wrap gap-2 mt-1">
                                        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input service-day-check" type="checkbox"
                                                    name="service_days[]" value="{{ $day }}"
                                                    id="day{{ $day }}"
                                                    {{ is_array(old('service_days')) && in_array($day, old('service_days')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="day{{ $day }}">{{ substr($day, 0, 3) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Preferred Working Hours</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label small text-muted">Start Time</label>
                                            <input type="time" name="working_hour_start" class="form-control"
                                                value="{{ old('working_hour_start', '08:00') }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small text-muted">End Time</label>
                                            <input type="time" name="working_hour_end" class="form-control"
                                                value="{{ old('working_hour_end', '17:00') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                        <!-- Management Plan Selection -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3"><i class="fas fa-shield-alt me-2"></i>How would you like your staff managed?</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="management-plan-card p-3 border rounded h-100" id="planCard_client">
                                        <div class="form-check">
                                            <input class="form-check-input management-plan-radio" type="radio"
                                                name="management_plan" value="client-managed"
                                                id="plan_client" data-cost="0" checked>
                                            <label class="form-check-label w-100" for="plan_client">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <div class="fw-bold">I'll manage the staff myself</div>
                                                        <small class="text-muted d-block mt-1">You handle scheduling, communication and any issues directly with the staff.</small>
                                                        <ul class="small text-muted mt-2 ps-3 mb-0">
                                                            <li>Direct communication with staff</li>
                                                            <li>You handle scheduling</li>
                                                            <li>You resolve disputes yourself</li>
                                                        </ul>
                                                    </div>
                                                    <span class="badge bg-success ms-2">Free</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="management-plan-card p-3 border rounded h-100" id="planCard_company">
                                        <div class="form-check">
                                            <input class="form-check-input management-plan-radio" type="radio"
                                                name="management_plan" value="company-managed"
                                                id="plan_company" data-cost="5000">
                                            <label class="form-check-label w-100" for="plan_company">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <div class="fw-bold">Managed by OurHouseHelp</div>
                                                        <small class="text-muted d-block mt-1">We handle everything — scheduling, monitoring, replacements and dispute resolution.</small>
                                                        <ul class="small text-muted mt-2 ps-3 mb-0">
                                                            <li>Dedicated account manager</li>
                                                            <li>Free staff replacements</li>
                                                            <li>Quality monitoring & mediation</li>
                                                            <li>Emergency support</li>
                                                        </ul>
                                                    </div>
                                                    <span class="badge bg-primary ms-2"></span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('management_plan')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Cost Summary -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">

                                {{-- Company Managed: Full cost breakdown --}}
                                <div id="companyManagedCost">
                                    <h5 class="fw-bold mb-3">Estimated Service Cost</h5>
                                    <div class="row">
                                        <div class="col-6">Base Service Cost:</div>
                                        <div class="col-6 text-end fw-bold" id="baseCostDisplay">₦0</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">Extra Services:</div>
                                        <div class="col-6 text-end" id="extraCostDisplay">₦0</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">Package Cost:</div>
                                        <div class="col-6 text-end" id="packageCostDisplay">₦0</div>
                                    </div>
                                    <div class="row d-none">
                                        <div class="col-6">Platform Fee:</div>
                                        <div class="col-6 text-end" id="serviceChargeDisplay">₦0</div>
                                    </div>
                                    <div class="row d-none" id="managementPlanRow">
                                        <div class="col-6 text-success">Platform Management Fee:</div>
                                        <div class="col-6 text-end text-success" id="managementPlanCostDisplay">₦0</div>
                                    </div>
                                    <div class="row d-none" id="discountRow">
                                        <div class="col-6 text-success">Duration Discount:</div>
                                        <div class="col-6 text-end text-success" id="discountDisplay">₦0</div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6 fw-bold">Total to Pay OurHouseHelp:</div>
                                        <div class="col-6 text-end fw-bold text-primary fs-5" id="grandTotalDisplay">₦0</div>
                                    </div>
                                    <small class="text-muted">* Includes staff salary + 20% management fee, minus any duration discount</small>
                                </div>

                                {{-- Client Managed: Staff salary with breakdown --}}
                                <div id="clientManagedCost" style="display:none;">
                                    <h5 class="fw-bold mb-3">Staff Salary Summary</h5>
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-white rounded border mb-2">
                                        <div>
                                            <div class="fw-bold">Monthly Salary to Pay Staff</div>
                                            <small class="text-muted" id="salaryDaysLabel">Select a package to see salary</small>
                                        </div>
                                        <div class="fs-3 fw-bold text-success" id="staffSalaryDisplay">₦0/month</div>
                                    </div>
                                    <div id="salaryBreakdown"></div>
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        You pay this directly to your staff every month. No payment to OurHouseHelp beyond your booking credit.
                                    </small>
                                </div>

                            </div>
                        </div>

                        <!-- Urgent Request Banner -->
                   
                        <div style="display: flex; align-items:center; flex-direction:column; margin-bottom:10px;">
                            <strong>Need Staff Urgently?</strong>
                            <p class="mb-2" style="font-size: 12px">Skip the full booking form. Submit an urgent request and our team will contact you immediately.</p>
                            <a href="{{ route('booking.urgent-request') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-bolt me-1"></i>I Need Staff Urgently
                            </a>
                            <small class="d-block mt-1 text-muted">Or call us directly: <strong>08126237436</strong></small>
                        </div>     

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn">
                                <i class="fas fa-search me-2"></i>Find Staff for Me
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .booking-steps {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .booking-steps .step {
        padding: 8px 16px;
        background: #e9ecef;
        border-radius: 20px;
        font-size: 14px;
        color: #666;
    }
    .booking-steps .step.active {
        background: #0d6efd;
        color: white;
        font-weight: bold;
    }
    .management-plan-card {
        cursor: pointer;
        transition: all 0.2s;
    }
    .management-plan-card:hover {
        border-color: #0d6efd !important;
        background: #f8f9ff;
    }
    .management-plan-card.border-primary {
        border-width: 2px !important;
    }
    .service-card {
        border: 1px solid #ddd;
        cursor: pointer;
        transition: 0.2s;
    }
    .service-card:hover {
        border-color: #0d6efd !important;
        background: #f8f9ff;
    }
    .service-card .form-check-input:checked ~ .form-check-label {
        color: #0d6efd;
    }
    .service-card:has(.form-check-input:checked) {
        border-color: #0d6efd !important;
        background: #e8f0fe;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('bookingForm');
        const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
        const packageSelect = document.getElementById('packageSelect');

        // State-based city/area filtering
        const stateCities = {
            'Abia': ['Aba', 'Umuahia', 'Ohafia', 'Arochukwu', 'Isiala Ngwa', 'Osisioma'],
            'Adamawa': ['Yola', 'Jimeta', 'Mubi', 'Numan', 'Ganye', 'Madagali'],
            'Akwa Ibom': ['Uyo', 'Eket', 'Ikot Ekpene', 'Oron', 'Abak', 'Ikot Abasi'],
            'Anambra': ['Awka', 'Onitsha', 'Nnewi', 'Ekwulobia', 'Aguata', 'Ihiala'],
            'Bauchi': ['Bauchi', 'Azare', 'Misau', 'Jama\'are', 'Katagum', 'Dass'],
            'Bayelsa': ['Yenagoa', 'Ogbia', 'Brass', 'Nembe', 'Sagbama', 'Ekeremor'],
            'Benue': ['Makurdi', 'Gboko', 'Otukpo', 'Katsina-Ala', 'Vandeikya', 'Oturkpo'],
            'Borno': ['Maiduguri', 'Biu', 'Bama', 'Dikwa', 'Gubio', 'Konduga'],
            'Cross River': ['Calabar', 'Ikom', 'Ogoja', 'Obudu', 'Ugep', 'Akamkpa'],
            'Delta': ['Asaba', 'Warri', 'Sapele', 'Ughelli', 'Agbor', 'Effurun', 'Ozoro'],
            'Ebonyi': ['Abakaliki', 'Afikpo', 'Onueke', 'Ezzamgbo', 'Ishielu', 'Ohaukwu'],
            'Edo': ['Benin City', 'Auchi', 'Ekpoma', 'Uromi', 'Irrua', 'Sabongida-Ora'],
            'Ekiti': ['Ado-Ekiti', 'Ikere-Ekiti', 'Oye-Ekiti', 'Ikole-Ekiti', 'Ijero-Ekiti', 'Efon-Alaaye'],
            'Enugu': ['Enugu', 'Nsukka', 'Agbani', 'Oji River', 'Udi', 'Awgu'],
            'FCT': ['Asokoro', 'Maitama', 'Garki', 'Wuse', 'Wuse 2', 'Gwarinpa', 'Jabi', 'Utako', 'Kubwa', 'Lugbe', 'Karu', 'Nyanya', 'Mpape', 'Lifecamp', 'Katampe', 'Durumi', 'Gudu', 'Apo', 'Lokogoma', 'Galadimawa', 'Dawaki', 'Bwari', 'Kurudu', 'Karmo', 'Idu', 'Gwagwalada', 'Kuje', 'Abaji', 'Kwali', 'Dei-Dei', 'Dutse', 'Jikwoyi', 'Orozo', 'Pyakasa', 'Zuba', 'Kaura', 'Mabushi', 'Guzape', 'Wuye', 'Central Area'],
            'Gombe': ['Gombe', 'Kumo', 'Billiri', 'Kaltungo', 'Dukku', 'Bajoga'],
            'Imo': ['Owerri', 'Orlu', 'Okigwe', 'Oguta', 'Mbaise', 'Nkwerre'],
            'Jigawa': ['Dutse', 'Hadejia', 'Gumel', 'Kazaure', 'Birnin Kudu', 'Ringim'],
            'Kaduna': ['Kaduna', 'Zaria', 'Kafanchan', 'Kagoro', 'Kachia', 'Birnin Gwari'],
            'Kano': ['Kano', 'Fagge', 'Tarauni', 'Nassarawa', 'Ungogo', 'Dala', 'Wudil', 'Bichi'],
            'Katsina': ['Katsina', 'Daura', 'Funtua', 'Malumfashi', 'Dutsin-Ma', 'Kankia'],
            'Kebbi': ['Birnin Kebbi', 'Argungu', 'Yauri', 'Zuru', 'Jega', 'Bagudo'],
            'Kogi': ['Lokoja', 'Okene', 'Idah', 'Kabba', 'Ankpa', 'Ajaokuta'],
            'Kwara': ['Ilorin', 'Offa', 'Omu-Aran', 'Jebba', 'Lafiagi', 'Pategi'],
            'Lagos': ['Ikeja', 'Victoria Island', 'Lekki', 'Ikoyi', 'Ajah', 'Surulere', 'Yaba', 'Gbagada', 'Maryland', 'Ogba', 'Magodo', 'Ikorodu', 'Epe', 'Badagry', 'Oshodi', 'Isolo', 'Mushin', 'Apapa', 'Festac', 'Amuwo-Odofin', 'Alimosho', 'Agege', 'Ojodu', 'Berger', 'Ilupeju', 'Ogudu', 'Kosofe', 'Sangotedo', 'Oregun', 'Omole'],
            'Nasarawa': ['Lafia', 'Keffi', 'Akwanga', 'Nasarawa', 'Doma', 'Toto'],
            'Niger': ['Minna', 'Bida', 'Suleja', 'Kontagora', 'New Bussa', 'Lapai'],
            'Ogun': ['Abeokuta', 'Sagamu', 'Ijebu-Ode', 'Ota', 'Ilaro', 'Ifo', 'Sango-Otta', 'Mowe', 'Ibafo'],
            'Ondo': ['Akure', 'Ondo', 'Owo', 'Ikare', 'Ore', 'Okitipupa'],
            'Osun': ['Osogbo', 'Ile-Ife', 'Ilesa', 'Ede', 'Iwo', 'Ejigbo'],
            'Oyo': ['Ibadan', 'Ogbomoso', 'Oyo', 'Iseyin', 'Saki', 'Eruwa', 'Igbo-Ora'],
            'Plateau': ['Jos', 'Bukuru', 'Pankshin', 'Shendam', 'Langtang', 'Barkin Ladi'],
            'Rivers': ['Port Harcourt', 'Obio-Akpor', 'Eleme', 'Bonny', 'Degema', 'Ahoada', 'Omoku'],
            'Sokoto': ['Sokoto', 'Tambuwal', 'Bodinga', 'Gwadabawa', 'Illela', 'Wamakko'],
            'Taraba': ['Jalingo', 'Wukari', 'Bali', 'Takum', 'Serti', 'Gembu'],
            'Yobe': ['Damaturu', 'Potiskum', 'Gashua', 'Nguru', 'Geidam', 'Buni Yadi'],
            'Zamfara': ['Gusau', 'Kaura Namoda', 'Talata Mafara', 'Anka', 'Maru', 'Bungudu']
        };
        const stateSelect = document.querySelector('[name="state"]');
        const citySelect = document.getElementById('citySelect');
        const oldCity = @json(old('city', ''));

        stateSelect.addEventListener('change', function() {
            const selectedState = this.value;
            citySelect.innerHTML = '';

            if (selectedState) {
                const cities = stateCities[selectedState] || [];
                citySelect.innerHTML = '<option value="">Select your area</option>';
                cities.forEach(function(city) {
                    const opt = document.createElement('option');
                    opt.value = city;
                    opt.textContent = city;
                    if (city === oldCity) opt.selected = true;
                    citySelect.appendChild(opt);
                });
            } else {
                citySelect.innerHTML = '<option value="">Select a state first</option>';
            }
        });

        if (stateSelect.value) {
            stateSelect.dispatchEvent(new Event('change'));
        }

        function toggleApartmentField() {
            const apartmentSection = document.getElementById('apartmentSection');
            const isCleaningSelected = Array.from(serviceCheckboxes).some(cb => {
                if (!cb.checked) return false;
                const label = cb.closest('.service-card').querySelector('.fw-bold');
                return label && label.textContent.trim().toLowerCase() === 'cleaning service';
            });
            if (apartmentSection) {
                apartmentSection.classList.toggle('d-none', !isCleaningSelected);
            }
        }

        serviceCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                toggleApartmentField();
                updateCostDisplay();
            });
        });

        toggleApartmentField();

        // Management plan card highlighting
        const planCards = document.querySelectorAll('.management-plan-card');
        const planRadios = document.querySelectorAll('.management-plan-radio');

        function updatePlanHighlight() {
            planCards.forEach(card => card.classList.remove('border-primary', 'bg-light'));
            const selected = document.querySelector('.management-plan-radio:checked');
            if (selected) {
                selected.closest('.management-plan-card').classList.add('border-primary', 'bg-light');
            }
            updateCostDisplay();
        }

        planRadios.forEach(radio => radio.addEventListener('change', updatePlanHighlight));
        updatePlanHighlight();

        function updateCostDisplay() {
            const checkedServices = document.querySelectorAll('.service-checkbox:checked');
            if (checkedServices.length === 0 || !packageSelect.value) return;

            const serviceIds = Array.from(checkedServices).map(cb => cb.value);
            const packageId = packageSelect.value;
            const apartmentSelectEl = document.querySelector('[name="apartment_type"]');
            const apartmentType = apartmentSelectEl ? apartmentSelectEl.value : null;
            const durationSelectEl = document.querySelector('[name="duration_weeks"]');
            const durationWeeks = durationSelectEl && durationSelectEl.value ? parseInt(durationSelectEl.value) : 4;

            const selectedPlan = document.querySelector('.management-plan-radio:checked');
            const managementPlan = selectedPlan ? selectedPlan.value : 'client-managed';

            // Toggle cost card vs salary card
            const companyCard = document.getElementById('companyManagedCost');
            const clientCard = document.getElementById('clientManagedCost');
            if (companyCard) companyCard.style.display = managementPlan === 'client-managed' ? 'none' : 'block';
            if (clientCard) clientCard.style.display = managementPlan === 'client-managed' ? 'block' : 'none';

            fetch('{{ route("booking.calculate-cost") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    services: serviceIds,
                    package: packageId,
                    apartment_type: apartmentType,
                    management_plan: managementPlan,
                    duration_weeks: durationWeeks
                })
            })
            .then(response => response.json())
            .then(data => {
                const isOneOff = data.is_one_off || false;
                const months = Number(data.months || 1);

                // ── CLIENT MANAGED ──
                if (managementPlan === 'client-managed') {
                    const staffSalary = Number(data.staff_salary || 0);
                    const grandCost = Number(data.grand_cost || 0);
                    const breakdown = data.breakdown || [];

                    const salaryEl = document.getElementById('staffSalaryDisplay');
                    if (salaryEl) salaryEl.textContent = isOneOff
                        ? '₦' + staffSalary.toLocaleString() + ' (one-off)'
                        : '₦' + staffSalary.toLocaleString() + '/month';

                    const daysEl = document.getElementById('salaryDaysLabel');
                    if (daysEl) {
                        const pkg = document.querySelector('#packageSelect option:checked');
                        daysEl.textContent = isOneOff ? 'Single visit' : (pkg ? pkg.textContent.trim() : '');
                    }

                    let breakdownHtml = '<div class="mt-2 pt-2 border-top">';
                    breakdownHtml += '<small class="text-muted d-block mb-1 fw-semibold">Salary breakdown:</small>';

                    breakdown.forEach(function(item) {
                        const label = item.type === 'main' ? item.service + ' (main)' : item.service + ' (×0.5)';
                        breakdownHtml += '<div class="d-flex justify-content-between"><small class="text-muted">' + label + '</small><small>₦' + Number(item.cost).toLocaleString() + '</small></div>';
                    });

                    if (!isOneOff && Number(data.package_cost) > 0) {
                        breakdownHtml += '<div class="d-flex justify-content-between"><small class="text-muted">Frequency (package)</small><small>₦' + Number(data.package_cost).toLocaleString() + '</small></div>';
                    }

                    breakdownHtml += '<div class="d-flex justify-content-between border-top mt-1 pt-1">';
                    breakdownHtml += '<small class="fw-bold">' + (isOneOff ? 'One-off visit price' : 'Monthly staff salary') + '</small>';
                    breakdownHtml += '<small class="fw-bold text-success">₦' + staffSalary.toLocaleString() + '</small>';
                    breakdownHtml += '</div>';

                    // State the commitment period explicitly. This figure is a
                    // MONTHLY rate, but the booking is recorded against the full
                    // duration - without this line the client sees N67,000 while
                    // admin sees N201,000 for the same booking.
                    if (!isOneOff && months > 1) {
                        breakdownHtml += '<div class="d-flex justify-content-between mt-1 pt-1 border-top">';
                        breakdownHtml += '<small class="text-muted">Over ' + months + ' months</small>';
                        breakdownHtml += '<small class="text-muted">₦' + staffSalary.toLocaleString() + ' &times; ' + months + ' = <span class="fw-semibold">₦' + (staffSalary * months).toLocaleString() + '</span></small>';
                        breakdownHtml += '</div>';
                        breakdownHtml += '<small class="text-muted d-block mt-1">You pay your staff directly each month.</small>';
                    }

                    breakdownHtml += '</div>';

                    const breakdownEl = document.getElementById('salaryBreakdown');
                    if (breakdownEl) breakdownEl.innerHTML = breakdownHtml;
                    return;
                }

                // ── COMPANY MANAGED ──
                const clientCost = Number(data.client_cost || 0);
                const clientTotal = Number(data.client_total || 0);
                const platformFee = Number(data.platform_fee || 0);
                const discountPct = Number(data.discount_percentage || 0);
                const discountAmt = Number(data.discount_amount || 0);

                document.getElementById('baseCostDisplay').textContent = '₦' + Number(data.base_cost).toLocaleString();
                document.getElementById('extraCostDisplay').textContent = '₦' + Number(data.extra_service_cost).toLocaleString();
                document.getElementById('packageCostDisplay').textContent = isOneOff ? '₦0 (one-off)' : '₦' + Number(data.package_cost).toLocaleString();

                // Platform fee row
                const planRow = document.getElementById('managementPlanRow');
                const planCostDisplay = document.getElementById('managementPlanCostDisplay');
                if (planRow && planCostDisplay) {
                    if (platformFee > 0) {
                        planRow.classList.remove('d-none');
                        planRow.style.removeProperty('display');
                        planCostDisplay.textContent = '₦' + platformFee.toLocaleString() + '/month (20% fee)';
                    } else {
                        planRow.style.display = 'none';
                    }
                }

                // Discount row — on TOTAL not monthly
                const discountRow = document.getElementById('discountRow');
                if (discountRow) {
                    if (discountPct > 0 && !isOneOff && months > 1) {
                        discountRow.classList.remove('d-none');
                        discountRow.style.removeProperty('display');
                        const discountDisplay = document.getElementById('discountDisplay');
                        if (discountDisplay) discountDisplay.textContent = '-₦' + discountAmt.toLocaleString() + ' (' + discountPct + '% off total for ' + months + ' months)';
                    } else {
                        discountRow.style.display = 'none';
                    }
                }

                // Grand total — changes label based on duration
                const grandTotalDisplay = document.getElementById('grandTotalDisplay');
                const grandTotalLabel = document.querySelector('#companyManagedCost .fw-bold');
                if (isOneOff) {
                    if (grandTotalLabel) grandTotalLabel.textContent = 'One-off Total:';
                    if (grandTotalDisplay) grandTotalDisplay.textContent = '₦' + clientTotal.toLocaleString();
                } else if (months > 1) {
                    const suffix = discountPct > 0 ? ' (after ' + discountPct + '% discount):' : ':';
                    if (grandTotalLabel) grandTotalLabel.textContent = 'Total for ' + months + ' months' + suffix;
                    if (grandTotalDisplay) grandTotalDisplay.textContent = '₦' + clientTotal.toLocaleString();
                } else {
                    if (grandTotalLabel) grandTotalLabel.textContent = 'Monthly Total:';
                    if (grandTotalDisplay) grandTotalDisplay.textContent = '₦' + clientCost.toLocaleString();
                }
            })
            .catch(err => console.error('Cost calculation error:', err));
        }

        serviceCheckboxes.forEach(cb => cb.addEventListener('change', updateCostDisplay));
        packageSelect.addEventListener('change', updateCostDisplay);

        const apartmentSelectEl = document.querySelector('[name="apartment_type"]');
        if (apartmentSelectEl) apartmentSelectEl.addEventListener('change', updateCostDisplay);

        const durationSelectEl = document.querySelector('[name="duration_weeks"]');
        if (durationSelectEl) durationSelectEl.addEventListener('change', updateCostDisplay);

        // Disable submit button on form submission
        form.addEventListener('submit', function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        });
    });
</script>
@endsection


