@extends('layouts.usermain')

@section('booking-service-selection')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="text-center mb-4">
            <i class="fas fa-bolt fa-3x text-warning mb-3"></i>
            <h3 class="fw-bold">Urgent Staff Request</h3>
            <p class="text-muted">Need a staff member immediately? Submit this form and our team will contact you ASAP.</p>
        </div>

        <div class="alert alert-warning mb-4">
            <div class="text-center">
                <strong>For fastest response, call us directly:</strong><br>
                <a href="tel:08126237436" class="btn btn-warning btn-lg mt-2">
                    <i class="fas fa-phone me-2"></i>08126237436
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Urgent Request Form</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('booking.submit-urgent-request') }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">State <span class="text-danger">*</span></label>
                            <select name="state" id="urgentState" class="form-select" required>
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
                            <select name="city" id="urgentCity" class="form-select" required>
                                <option value="">Select state first</option>
                            </select>
                            @error('city')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control"
                            placeholder="Your street address" value="{{ old('address') }}" required>
                        @error('address')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">What services do you need? (Optional)</label>
                        <textarea name="services_needed" class="form-control" rows="3"
                            placeholder="e.g. Cleaning, Laundry, Cooking...">{{ old('services_needed') }}</textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-warning btn-lg px-5">
                            <i class="fas fa-bolt me-2"></i>Submit Urgent Request
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('booking.service-selection') }}" class="text-muted">
                        <i class="fas fa-arrow-left me-1"></i>Back to full booking form
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        const stateSelect = document.getElementById('urgentState');
        const citySelect = document.getElementById('urgentCity');
        const oldCity = @json(old('city', ''));

        function updateCities() {
            const state = stateSelect.value;
            citySelect.innerHTML = '';

            if (!state) {
                citySelect.innerHTML = '<option value="">Select state first</option>';
                return;
            }

            const cities = stateCities[state] || [];
            citySelect.innerHTML = '<option value="">Select your area</option>';
            cities.forEach(function(city) {
                var option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                if (city === oldCity) option.selected = true;
                citySelect.appendChild(option);
            });
        }

        stateSelect.addEventListener('change', updateCities);

        if (stateSelect.value) {
            updateCities();
        }
    });
</script>
@endsection
