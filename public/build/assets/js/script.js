document.addEventListener("DOMContentLoaded", function () {
    const mobileMenu = document.getElementById("mobile-menu");
    const navLinks = document.querySelector(".nav-links");
    const featureInputs = document.querySelectorAll(".feature-input");
    const grandTotalElement = document.getElementById("total-cost-display");
    const discountedTotalElement = document.getElementById("discounted-cost-display");
    const grandTotalInput = document.getElementById("total-cost");
    const extraDayCheckboxes = document.querySelectorAll('input[name="extra_days[]"]');
    const serviceDayCheckboxes = document.querySelectorAll('input[name="service_days[]"]');
    const packageSelect = document.getElementById("package");
    const discountPercentageElement = document.getElementById("discount-percentage");
    const serviceCountElement = document.getElementById("service-count");
    let maxDaysPerWeek = 0;

    // Mobile menu toggle
    mobileMenu.addEventListener("click", () => {
        navLinks.classList.toggle("active");
    });

    // Package selection change
    packageSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const packageCost = parseFloat(selectedOption.getAttribute("data-cost") || 0);
        const packageDays = parseInt(selectedOption.getAttribute("data-days") || 0);

        maxDaysPerWeek = packageDays;

        // Update cost and reset selections
        document.getElementById("package-cost").value = packageCost.toLocaleString();
        resetCheckboxes();
        updateCountdown();
        updateExtraDaysCheckboxes();
        updateTotalCost();
    });

    // Service day checkboxes change
    // serviceDayCheckboxes.forEach(function (checkbox) {
    //     checkbox.addEventListener("change", function () {
    //         const checkedDays = document.querySelectorAll('input[name="service_days[]"]:checked').length;
    //         if (checkedDays > maxDaysPerWeek) {
    //             alert(`You can select a maximum of ${maxDaysPerWeek} day(s) per week for the selected package.`);
    //             this.checked = false;
    //         }
    //         updateCountdown();
    //         updateExtraDaysCheckboxes();
    //         updateTotalCost();
    //     });
    // });

    // Reset all checkboxes
    function resetCheckboxes() {
        serviceDayCheckboxes.forEach(function (checkbox) {
            checkbox.checked = false;
        });
    }

    // Countdown for remaining service days
    function updateCountdown() {
        const checkedDays = document.querySelectorAll('input[name="service_days[]"]:checked').length;
        const remainingDays = maxDaysPerWeek - checkedDays;
        document.getElementById("countdown").textContent = `You are left with ${remainingDays} out of ${maxDaysPerWeek} Day(s) for this Service`;
    }

    // Update extra day checkboxes based on selected service days
    function updateExtraDaysCheckboxes() {
        const selectedServiceDays = Array.from(document.querySelectorAll('input[name="service_days[]"]:checked')).map((checkbox) => checkbox.value);
        extraDayCheckboxes.forEach((checkbox) => {
            checkbox.disabled = selectedServiceDays.includes(checkbox.value);
            checkbox.checked = false; // Uncheck disabled checkboxes
        });
    }

    // Calculate total cost for apartment features
    function calculateGrandTotal() {
        let grandTotal = 0;
        featureInputs.forEach((input) => {
            const costPerUnit = parseFloat(input.dataset.cost) || 0;
            const quantity = parseInt(input.value) || 0;
            const totalCost = costPerUnit * quantity;

            const totalSpan = document.getElementById("total_" + input.id.split("feature_")[1]);
            if (totalSpan) {
                totalSpan.textContent = totalCost.toLocaleString();
            }

            grandTotal += totalCost;
        });
        return grandTotal;
    }

    // Update overall total cost
    function updateTotalCost() {
        const packageCost = parseFloat(document.getElementById("package-cost").value.replace(/,/g, "")) || 0;
        const extraTotalCost = updateExtraDaysCost();
        const apartmentFeatureCost = calculateGrandTotal();

        const totalCost = packageCost + extraTotalCost + apartmentFeatureCost;
        const discountPercentage = parseFloat(discountPercentageElement.innerText) || 0;
        const discountAmount = totalCost * (discountPercentage / 100);
        const finalTotalCost = totalCost - discountAmount;

        grandTotalElement.innerText = `₦${totalCost.toLocaleString("en-NG")}`;
        discountedTotalElement.innerText = `₦${finalTotalCost.toLocaleString("en-NG")}`;
        grandTotalInput.value = totalCost;
    }

    // Fetch discount based on selected services
    const fetchDiscount = (selectedServices) => {
        fetch(window.Laravel.getDiscountRoute, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.Laravel.csrfToken,
            },
            body: JSON.stringify({ serviceName: selectedServices }),
        })
            .then((response) => response.json())
            .then((data) => {
                discountPercentageElement.innerText = `${data.discount_percent}`;
                updateTotalCost();
            })
            .catch((error) => console.error("Error fetching discount:", error));
    };

    // Handle service selection change
    const handleServiceSelection = () => {
        const selectedServices = Array.from(document.querySelectorAll('input[name="serviceName[]"]:checked')).map((service) => service.value);
        serviceCountElement.innerText = selectedServices.length;
        fetchDiscount(selectedServices);
    };

    // Attach event listeners for service selection
    document.querySelectorAll('input[name="serviceName[]"]').forEach((checkbox) => {
        checkbox.addEventListener("change", handleServiceSelection);
    });

    // Initialize total cost on page load
    updateTotalCost();
});


//..................................... Function to format time as AM/PM
function formatTime(time) {
    const [hours, minutes] = time.split(":").map(Number);
    const ampm = hours >= 12 ? "PM" : "AM";
    const formattedHours = hours % 12 || 12; // Convert 0 to 12
    const formattedMinutes = minutes < 10 ? "0" + minutes : minutes;
    return `${formattedHours}:${formattedMinutes} ${ampm}`;
}

// Event listener for package selection
document.getElementById("package").addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const workingHoursStart = JSON.parse(
        selectedOption.getAttribute("data-working-hours-start")
    );
    const workingHoursEnd = JSON.parse(
        selectedOption.getAttribute("data-working-hours-end")
    );

    // Hide working hours initially
    document.getElementById("working-hours").style.display = "none";
    const workingHoursList = document.getElementById("working-hours-list");
    workingHoursList.innerHTML = ""; // Clear previous working hours

    let hasWorkingHours = false;

    // Create a select dropdown for working hours
    const workingHoursDropdown = document.createElement("select");
    workingHoursDropdown.className = "form-control";
    workingHoursDropdown.id = "working_hour_select"; // This ID can be referenced in validation
    workingHoursDropdown.name = "working_hour"; // Set name to working_hour
    workingHoursDropdown.required = true; // Make it required

    // Add a default option
    const defaultOption = document.createElement("option");
    defaultOption.value = ""; // No value for default
    defaultOption.textContent = "Select Working Hour";
    workingHoursDropdown.appendChild(defaultOption);

    // Loop through the working hours
    workingHoursStart.forEach((startTime, index) => {
        if (startTime) {
            // Check if start time has a value
            hasWorkingHours = true;

            const endTime = workingHoursEnd[index];
            const formattedStart = formatTime(startTime);
            const formattedEnd = endTime ? formatTime(endTime) : "N/A"; // Handle end time if empty

            // Create an option for each working hour
            const option = document.createElement("option");
            option.value = `${formattedStart} - ${formattedEnd}`; // Use formatted time range as value
            option.textContent = `From: ${formattedStart} - To: ${formattedEnd}`;
            workingHoursDropdown.appendChild(option);
        }
    });

    // Append dropdown to the working hours list
    workingHoursList.appendChild(workingHoursDropdown);

    // Show working hours section if there are any
    if (hasWorkingHours) {
        document.getElementById("working-hours").style.display = "block";
    }
});

// Validate selection on form submission
document
    .getElementById("yourFormId")
    .addEventListener("submit", function (event) {
        const selectedWorkingHour = document.getElementById(
            "working_hour_select"
        ).value;

        if (!selectedWorkingHour) {
            event.preventDefault(); // Prevent form submission
            alert("Please select a working hour."); // Error message
        }
    });

// ................................... apartment detail calculations

function calculateTotal(name, cost) {
    // Get the quantity value entered
    const quantity = document.getElementById("feature_" + name).value;

    // Calculate total for this feature
    const total = quantity * cost;

    // Update the total display for this feature
    document.getElementById("total_" + name).textContent =
        total.toLocaleString();

    // Recalculate the grand total
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let grandTotal = 0;

    // Loop through all feature inputs
    document.querySelectorAll(".feature-input").forEach(function (input) {
        const cost = input.getAttribute("data-cost");
        const quantity = input.value;

        // Add this feature's total to the grand total
        grandTotal += quantity * cost;
    });

    // Update the grand total display
    document.getElementById("grand_total").textContent =
        grandTotal.toLocaleString();
}

// Initialize grand total calculation on page load
document.addEventListener("DOMContentLoaded", function () {
    calculateGrandTotal();
});

// <!-- National Identification Number (NIN) -->
function validateNIN() {
    const ninInput = document.getElementById("nin");
    const ninError = document.getElementById("nin-error");
    let ninValue = ninInput.value;

    // Remove any non-digit characters in real-time
    ninValue = ninValue.replace(/\D/g, "");
    ninInput.value = ninValue; // Set the value to only digits

    // Check if the input contains exactly 11 digits
    if (ninValue.length === 11) {
        ninError.style.display = "none"; // Hide error if valid
    } else {
        ninError.style.display = "block"; // Show error if invalid
    }
}


// preview password
function togglePassword(id) {
    const passwordField = document.getElementById(id);
    const passwordToggleIcon = passwordField.nextElementSibling;

    if (passwordField.type === "password") {
        passwordField.type = "text";
        passwordToggleIcon.classList.remove("fa-eye");
        passwordToggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        passwordToggleIcon.classList.remove("fa-eye-slash");
        passwordToggleIcon.classList.add("fa-eye");
    }
}

// preloader
// Wait until the page has fully loaded before hiding the preloader
window.addEventListener("load", function () {
    // Hide preloader after loading
    document.getElementById("preloader").style.display = "none";
    // Show the main content
    document.getElementById("main-content").style.display = "block";
});

// Check if the input length is exactly 11 digits .......................................
document.addEventListener("DOMContentLoaded", function () {
    const phoneInput = document.getElementById("phone");
    const phoneError = document.getElementById("phone-error");

    // Event listener for input validation
    phoneInput.addEventListener("input", function () {
        const phoneValue = phoneInput.value;

        // Check if the input length is exactly 11 digits
        if (phoneValue.length > 11 || phoneValue.length < 11) {
            phoneError.style.display = "block";
            phoneError.textContent = "Phone number must be exactly 11 digits.";
        } else {
            phoneError.style.display = "none";
        }

        // Prevent typing non-numeric characters
        if (!/^\d*$/.test(phoneValue)) {
            phoneInput.value = phoneValue.replace(/\D/g, ""); // Remove non-numeric characters
        }
    });
});

// images preview ..................................

function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    const reader = new FileReader();
    reader.onload = function (e) {
        preview.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}



