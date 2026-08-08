@extends('layouts.adminmain') {{-- Your admin layout --}}

@section('calculator')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Service Cost Calculator</h1>
                    <p class="text-muted mb-0">Quickly calculate service costs for clients</p>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-secondary" onclick="resetCalculator()">
                        <i class="fas fa-redo me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calculator Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <!-- Left Side: Inputs -->
                <div class="col-md-6">
                    <div class="row g-3">
                        <!-- Services Selection -->
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-list text-primary me-2"></i>Services
                            </h5>
                            <div class="row">
                                @foreach($allServices as $service)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input service-check" 
                                               type="checkbox" 
                                               value="{{ $service->id }}"
                                               data-cost="{{ $service->base_cost ?? 0 }}"
                                               data-type="{{ $service->service_type ?? 'PSS' }}"
                                               id="service{{ $service->id }}">
                                        <label class="form-check-label w-100" for="service{{ $service->id }}">
                                            <div class="d-flex justify-content-between">
                                                <span>{{ $service->service_name }}</span>
                                                @if($service->base_cost)
                                                <small class="text-muted">₦{{ number_format($service->base_cost, 2) }}</small>
                                                @endif
                                            </div>
                                            @if($service->service_type)
                                            <small class="text-muted d-block">{{ $service->service_type }}</small>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Apartment Type (for PRS) -->
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-home text-primary me-2"></i>Apartment Type
                            </h5>
                            <select class="form-select" id="apartmentSelect">
                                <option value="">-- Not Applicable --</option>
                                @foreach($apartments as $apartment)
                                <option value="{{ $apartment->id }}" 
                                        data-cost="{{ $apartment->cost }}">
                                    {{ $apartment->name }} (₦{{ number_format($apartment->cost, 2) }})
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Only required for PRS services</small>
                        </div>

                        <!-- Package Selection -->
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-box text-primary me-2"></i>Service Package
                            </h5>
                            <div class="row">
                                @foreach($packages as $package)
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input package-check" 
                                               type="radio" 
                                               name="package" 
                                               value="{{ $package->id }}"
                                               data-cost="{{ $package->cost }}"
                                               data-days="{{ $package->days_per_week }}"
                                               id="package{{ $package->id }}">
                                        <label class="form-check-label w-100" for="package{{ $package->id }}">
                                            <div class="d-flex justify-content-between">
                                                <strong>{{ $package->name }}</strong>
                                                <strong class="text-primary">₦{{ number_format($package->cost, 2) }}</strong>
                                            </div>
                                            <small class="text-muted">{{ $package->days_per_week }} time(s) per week</small>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Days Selection -->
                        <div class="col-12" hidden>
                            <h5 class="mb-3">
                                <i class="fas fa-calendar text-primary me-2"></i>Service Days
                            </h5>
                            <div class="row" id="daysContainer">
                                @php $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']; @endphp
                                @foreach($days as $day)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input day-check" 
                                               type="checkbox" 
                                               value="{{ $day }}"
                                               id="day{{ $day }}">
                                        <label class="form-check-label" for="day{{ $day }}">
                                            {{ $day }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Results -->
                <div class="col-md-6">
                    <div class="sticky-top" style="top: 20px;">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h4 class="card-title mb-4 text-success">
                                    <i class="fas fa-calculator me-2"></i>Cost Breakdown
                                </h4>
                                
                                <!-- Cost Summary -->
                                <div id="costSummary" class="mb-4">
                                    <div class="text-center py-5">
                                        <i class="fas fa-calculator fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Select options to see cost breakdown</p>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="mt-4 pt-3 border-top">
                                    <h6 class="mb-3">Quick Actions</h6>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <button class="btn btn-outline-primary w-100" onclick="calculateTotal()">
                                                <i class="fas fa-calculator me-1"></i> Calculate
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button class="btn btn-outline-success w-100" onclick="copyToClipboard()">
                                                <i class="fas fa-copy me-1"></i> Copy Summary
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Templates -->
    <div class="row mt-4" hidden>
        <div class="col-12">
            <h5 class="mb-3">Quick Templates</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <button class="btn btn-outline-info w-100" onclick="loadTemplate('basic_cleaning')">
                        <i class="fas fa-broom me-1"></i> Basic Cleaning
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-info w-100" onclick="loadTemplate('weekly_cooking')">
                        <i class="fas fa-utensils me-1"></i> Weekly Cooking
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-info w-100" onclick="loadTemplate('prs_service')">
                        <i class="fas fa-home me-1"></i> PRS Service
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-info w-100" onclick="loadTemplate('multi_service')">
                        <i class="fas fa-layer-group me-1"></i> Multi-Service
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden textarea for copying -->
<textarea id="copyText" style="position: absolute; left: -9999px;"></textarea>

<script>
    // Constants
    const SERVICE_CHARGE = 10000;
    const EXTRA_SERVICE_COST = 12000;
    const REGISTRATION_FEE = 10000;

    // DOM Elements
    const serviceChecks = document.querySelectorAll('.service-check');
    const apartmentSelect = document.getElementById('apartmentSelect');
    const packageChecks = document.querySelectorAll('.package-check');
    const dayChecks = document.querySelectorAll('.day-check');
    const costSummary = document.getElementById('costSummary');
    const copyText = document.getElementById('copyText');

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners
        serviceChecks.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateApartmentRequirement();
                calculateTotal();
            });
        });

        apartmentSelect.addEventListener('change', calculateTotal);
        
        packageChecks.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateDaysLimit();
                calculateTotal();
            });
        });

        dayChecks.forEach(checkbox => {
            checkbox.addEventListener('change', calculateTotal);
        });

        // Initial calculation
        calculateTotal();
    });

    // Check if PRS service is selected
    function hasPRSService() {
        return Array.from(serviceChecks).some(checkbox => {
            if (checkbox.checked) {
                const serviceType = checkbox.dataset.type;
                return serviceType === 'PRS';
            }
            return false;
        });
    }

    // Update apartment requirement visibility
    function updateApartmentRequirement() {
        const hasPRS = hasPRSService();
        
        if (hasPRS) {
            apartmentSelect.required = true;
            // Enable apartment select
            apartmentSelect.disabled = false;
            apartmentSelect.closest('.col-12').style.display = 'block';
        } else {
            apartmentSelect.required = false;
            // Reset and disable
            apartmentSelect.value = '';
            apartmentSelect.disabled = true;
            apartmentSelect.closest('.col-12').style.display = 'block'; // Keep visible but disabled
        }
    }

    // Update days selection limit based on package
    function updateDaysLimit() {
        const selectedPackage = document.querySelector('input[name="package"]:checked');
        
        if (!selectedPackage) return;
        
        const maxDays = parseInt(selectedPackage.dataset.days) || 0;
        const selectedDays = Array.from(dayChecks).filter(cb => cb.checked).length;
        
        // If max days reached, disable unselected checkboxes
        if (maxDays > 0 && selectedDays >= maxDays) {
            dayChecks.forEach(checkbox => {
                if (!checkbox.checked) {
                    checkbox.disabled = true;
                }
            });
        } else {
            // Enable all checkboxes
            dayChecks.forEach(checkbox => {
                checkbox.disabled = false;
            });
        }
    }

    // Calculate total cost
    function calculateTotal() {
        // Get selected services
        const selectedServices = Array.from(serviceChecks)
            .filter(cb => cb.checked)
            .map(cb => ({
                id: cb.value,
                cost: parseFloat(cb.dataset.cost) || 0,
                type: cb.dataset.type || 'PSS'
            }));

        // Get apartment cost
        let apartmentCost = 0;
        let apartmentName = 'N/A';
        if (apartmentSelect.value && hasPRSService()) {
            const selectedOption = apartmentSelect.options[apartmentSelect.selectedIndex];
            apartmentCost = parseFloat(selectedOption.dataset.cost) || 0;
            apartmentName = selectedOption.text.split(' (')[0];
        }

        // Get selected package
        const selectedPackage = document.querySelector('input[name="package"]:checked');
        let packageCost = 0;
        let packageName = 'Not selected';
        let packageDays = 0;
        
        if (selectedPackage) {
            packageCost = parseFloat(selectedPackage.dataset.cost) || 0;
            packageName = selectedPackage.nextElementSibling.querySelector('strong').textContent;
            packageDays = parseInt(selectedPackage.dataset.days) || 0;
        }

        // Get selected days
        const selectedDays = Array.from(dayChecks)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        // Calculate costs (matching client logic)
        let baseCost = 0;
        let extraCost = 0;

        if (selectedServices.length > 0) {
            // Find main service (highest cost)
            const mainService = selectedServices.reduce((prev, current) => 
                (prev.cost > current.cost) ? prev : current, { cost: 0 }
            );
            baseCost = mainService.cost;

            // Calculate extra service costs
            selectedServices.forEach(service => {
                if (service.id === mainService.id) return;

                switch (service.type) {
                    case 'PRS':
                        extraCost += apartmentCost / 2;
                        break;
                    case 'PMS':
                        extraCost += service.cost / 2;
                        break;
                    case 'PSS':
                        extraCost += EXTRA_SERVICE_COST;
                        break;
                    default:
                        extraCost += EXTRA_SERVICE_COST;
                        break;
                }
            });
        }

        // Calculate totals
        const serviceCost = baseCost + packageCost + extraCost;
        const grandTotal = serviceCost + SERVICE_CHARGE;

        // Format currency
        const formatCurrency = (amount) => {
            return new Intl.NumberFormat('en-NG', {
                style: 'currency',
                currency: 'NGN',
                minimumFractionDigits: 2
            }).format(amount);
        };

        // Build summary HTML
        let summaryHTML = `
            <div class="mb-4">
                <h6 class="text-muted mb-3">Selected Options</h6>
                <div class="row mb-2">
                    <div class="col-6">
                        <small class="text-muted">Services:</small>
                    </div>
                    <div class="col-6 text-end">
                        <small>${selectedServices.length} selected</small>
                    </div>
                </div>
                ${hasPRSService() ? `
                <div class="row mb-2">
                    <div class="col-6">
                        <small class="text-muted">Apartment:</small>
                    </div>
                    <div class="col-6 text-end">
                        <small>${apartmentName}</small>
                    </div>
                </div>
                ` : ''}
                <div class="row mb-2">
                    <div class="col-6">
                        <small class="text-muted">Package:</small>
                    </div>
                    <div class="col-6 text-end">
                        <small>${packageName}</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        <small class="text-muted">Days:</small>
                    </div>
                    <div class="col-6 text-end">
                        <small>${selectedDays.length} day(s)</small>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <h6 class="text-muted mb-3">Cost Breakdown</h6>
                <div class="row mb-2">
                    <div class="col-6">
                        <small>Service Cost:</small>
                    </div>
                    <div class="col-6 text-end">
                        <small>${formatCurrency(serviceCost)}</small>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        <small>Service Charge:</small>
                    </div>
                    <div class="col-6 text-end">
                        <small>${formatCurrency(SERVICE_CHARGE)}</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Registration Fee:</small>
                    </div>
                    <div class="col-6 text-end">
                        <small class="text-muted">${formatCurrency(REGISTRATION_FEE)}/year</small>
                    </div>
                </div>
                <div class="row pt-3 border-top">
                    <div class="col-6">
                        <h6 class="mb-0">Total Amount:</h6>
                    </div>
                    <div class="col-6 text-end">
                        <h5 class="mb-0 text-success">${formatCurrency(grandTotal)}</h5>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <small class="text-muted">+ Registration fee of ₦10,000 for first-time clients</small>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-warning p-2 mt-3">
                <small>
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Note:</strong> This is an estimate. Final cost may vary based on specific requirements.
                </small>
            </div>
        `;

        costSummary.innerHTML = summaryHTML;
        
        // Store data for copying
        updateCopyText(selectedServices, apartmentName, packageName, selectedDays, serviceCost, grandTotal);
    }

    // Update copy text
    function updateCopyText(services, apartment, packageName, days, serviceCost, grandTotal) {
        const serviceNames = services.map(s => {
            const serviceElement = document.querySelector(`#service${s.id} + label span`);
            return serviceElement ? serviceElement.textContent : `Service ${s.id}`;
        }).join(', ');
        
        const formatCurrency = (amount) => `₦${amount.toLocaleString('en-NG', {minimumFractionDigits: 2})}`;
        
        const text = `
SERVICE COST ESTIMATE
=====================
Services: ${serviceNames}
${hasPRSService() ? `Apartment: ${apartment}\n` : ''}
Package: ${packageName}
Service Days: ${days.length > 0 ? days.join(', ') : 'None selected'}

COST BREAKDOWN:
───────────────
Service Cost: ${formatCurrency(serviceCost)}
Service Charge: ${formatCurrency(SERVICE_CHARGE)}
─────────────────────────────
Subtotal: ${formatCurrency(serviceCost + SERVICE_CHARGE)}

Registration Fee: ${formatCurrency(REGISTRATION_FEE)} (one-time, yearly)
─────────────────────────────
GRAND TOTAL: ${formatCurrency(grandTotal + REGISTRATION_FEE)}

*This is an estimate. Final cost may vary.
*Generated on: ${new Date().toLocaleDateString()}
        `.trim();
        
        copyText.value = text;
    }

    // Copy summary to clipboard
    function copyToClipboard() {
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        
        try {
            document.execCommand('copy');
            showAlert('Summary copied to clipboard!', 'success');
        } catch (err) {
            console.error('Failed to copy:', err);
            showAlert('Failed to copy. Please select and copy manually.', 'error');
        }
    }

    // Reset calculator
    function resetCalculator() {
        // Uncheck all services
        serviceChecks.forEach(checkbox => checkbox.checked = false);
        
        // Reset apartment
        apartmentSelect.value = '';
        apartmentSelect.disabled = false;
        
        // Uncheck packages
        packageChecks.forEach(checkbox => checkbox.checked = false);
        
        // Uncheck days
        dayChecks.forEach(checkbox => {
            checkbox.checked = false;
            checkbox.disabled = false;
        });
        
        // Recalculate
        calculateTotal();
        showAlert('Calculator has been reset', 'info');
    }

    // Load template
    function loadTemplate(templateName) {
        resetCalculator(); // Clear first
        
        switch(templateName) {
            case 'basic_cleaning':
                // Check first cleaning service
                const cleaningService = Array.from(serviceChecks).find(cb => 
                    cb.nextElementSibling.textContent.includes('Cleaning'));
                if (cleaningService) {
                    cleaningService.checked = true;
                }
                // Select basic package (assuming first is basic)
                const basicPackage = Array.from(packageChecks)[0];
                if (basicPackage) {
                    basicPackage.checked = true;
                }
                // Check Monday and Thursday
                document.getElementById('dayMonday').checked = true;
                document.getElementById('dayThursday').checked = true;
                break;
                
            case 'weekly_cooking':
                // Check cooking service
                const cookingService = Array.from(serviceChecks).find(cb => 
                    cb.nextElementSibling.textContent.includes('Cooking'));
                if (cookingService) {
                    cookingService.checked = true;
                }
                // Select weekly package
                const weeklyPackage = Array.from(packageChecks).find(cb => 
                    cb.nextElementSibling.textContent.includes('Weekly'));
                if (weeklyPackage) {
                    weeklyPackage.checked = true;
                }
                break;
                
            case 'prs_service':
                // Check PRS service
                const prsService = Array.from(serviceChecks).find(cb => 
                    cb.dataset.type === 'PRS');
                if (prsService) {
                    prsService.checked = true;
                    // Select first apartment
                    apartmentSelect.selectedIndex = 1;
                }
                break;
                
            case 'multi_service':
                // Check multiple services
                serviceChecks[0].checked = true; // First service
                if (serviceChecks[1]) serviceChecks[1].checked = true; // Second service
                // Select premium package
                const premiumPackage = Array.from(packageChecks).find(cb => 
                    cb.nextElementSibling.textContent.includes('Premium'));
                if (premiumPackage) {
                    premiumPackage.checked = true;
                }
                // Check multiple days
                document.getElementById('dayMonday').checked = true;
                document.getElementById('dayWednesday').checked = true;
                document.getElementById('dayFriday').checked = true;
                break;
        }
        
        calculateTotal();
        showAlert(`Loaded "${templateName.replace('_', ' ')}" template`, 'info');
    }

    // Show alert
    function showAlert(message, type = 'info') {
        // Create alert element
        const alert = document.createElement('div');
        alert.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
        alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 1050; max-width: 300px;';
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 3000);
    }
</script>

<style>
    .form-check {
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    
    .form-check:hover {
        border-color: #479c18;
        background-color: rgba(71, 156, 24, 0.05);
    }
    
    .form-check-input:checked + .form-check-label {
        color: #479c18;
        font-weight: 500;
    }
    
    .form-check-input:checked {
        background-color: #479c18;
        border-color: #479c18;
    }
    
    .sticky-top {
        position: sticky;
        z-index: 10;
    }
    
    .card.bg-light {
        background-color: #f8f9fa !important;
    }
    
    h5 i, h6 i {
        width: 20px;
    }
    
    .btn-outline-info {
        border-color: #17a2b8;
        color: #17a2b8;
    }
    
    .btn-outline-info:hover {
        background-color: #17a2b8;
        color: white;
    }
    
    @media (max-width: 768px) {
        .sticky-top {
            position: static;
        }
    }
</style>
@endsection