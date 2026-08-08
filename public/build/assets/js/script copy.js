const mobileMenu = document.getElementById("mobile-menu");
const navLinks = document.querySelector(".nav-links");

mobileMenu.addEventListener("click", () => {
    navLinks.classList.toggle("active");
});

// ........................................................................

// Track maximum days per week from the selected package
let maxDaysPerWeek = 0;

// When package selection changes, update the max days per week and cost
document.getElementById("package").addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    const packageCost = selectedOption.getAttribute("data-cost");
    const packageDays = selectedOption.getAttribute("data-days"); // Fetch days per week from the package

    // Update the cost and the max days per week allowed
    document.getElementById("package-cost").value = packageCost
        ? packageCost
        : "";
    maxDaysPerWeek = packageDays ? parseInt(packageDays) : 0; // Set the allowed days from package

    // Reset checkboxes and update the countdown when a new package is selected
    resetCheckboxes();
    updateCountdown(); // Initialize the countdown based on the package
    updateExtraDaysCheckboxes(); // Update the extra days checkboxes based on selected service days

    // Update total cost
    updateTotalCost();
});

// Track service day checkboxes
const serviceDayCheckboxes = document.querySelectorAll(
    'input[name="service_days[]"]'
);

// Add event listeners to each checkbox
serviceDayCheckboxes.forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
        const checkedDays = document.querySelectorAll(
            'input[name="service_days[]"]:checked'
        ).length;

        // If more days are checked than allowed, show an alert and uncheck the checkbox
        if (checkedDays > maxDaysPerWeek) {
            alert(
                `You can select a maximum of ${maxDaysPerWeek} days per week for the selected package.`
            );
            this.checked = false; // Uncheck the checkbox
        }

        // Update the countdown
        updateCountdown();

        // Update the extra days checkboxes based on selected service days
        updateExtraDaysCheckboxes();

        // Update total cost
        updateTotalCost();
    });
});

// Function to reset all checkboxes
function resetCheckboxes() {
    serviceDayCheckboxes.forEach(function (checkbox) {
        checkbox.checked = false; // Uncheck all checkboxes when package changes
    });
}

// Function to update the countdown
function updateCountdown() {
    const checkedDays = document.querySelectorAll(
        'input[name="service_days[]"]:checked'
    ).length;
    const remainingDays = maxDaysPerWeek - checkedDays;

    // Update the countdown display
    document.getElementById(
        "countdown"
    ).textContent = `Choose ${remainingDays} Day(s) for Service`;
}

// Function to update extra days checkboxes based on selected service days
function updateExtraDaysCheckboxes() {
    const selectedServiceDays = Array.from(
        document.querySelectorAll('input[name="service_days[]"]:checked')
    ).map((checkbox) => checkbox.value);

    // Disable extra day checkboxes if they are selected in service days
    const extraDayCheckboxes = document.querySelectorAll(
        'input[name="extra_days[]"]'
    );
    extraDayCheckboxes.forEach(function (checkbox) {
        if (selectedServiceDays.includes(checkbox.value)) {
            checkbox.disabled = true; // Disable the checkbox
            checkbox.checked = false; // Uncheck it if it's already checked
        } else {
            checkbox.disabled = false; // Enable the checkbox
        }
    });
}

// Event listener for extra day checkboxes
const extraDayCheckboxes = document.querySelectorAll(
    'input[name="extra_days[]"]'
);
extraDayCheckboxes.forEach(function (checkbox) {
    checkbox.addEventListener("change", function () {
        updateTotalCost(); // Update total cost when extra days are checked/unchecked
    });
});

function updateExtraDaysCost() {
    const costPerDay = 4000; // Cost per extra day
    const checkboxes = document.querySelectorAll(
        'input[name="extra_days[]"]:checked'
    );
    const extraDaysCount = checkboxes.length;
    const extraTotalCost = extraDaysCount * costPerDay;

    // Update the count of extra days selected
    document.getElementById(
        "extra-days-count"
    ).innerText = `${extraDaysCount} Extra Day(s) selected`;

    // Store the plain number (without formatting) in the input field
    document.getElementById("extra-cost").value = extraTotalCost;

    // Display the formatted value (with commas) to the user
    document.getElementById("extra-cost-display").innerText =
        extraTotalCost.toLocaleString("en-NG");

    return extraTotalCost; // Return the total cost of extra days
}

// Function to update total cost
function updateTotalCost() {
    // Remove commas from package cost before parsing
    const packageCost =
        parseFloat(
            document.getElementById("package-cost").value.replace(/,/g, "")
        ) || 0;

    // Get the extra days cost
    const extraTotalCost = updateExtraDaysCost();

    // Calculate the total cost
    const totalCost = packageCost + extraTotalCost;

    // Store the plain number (without formatting) in the total cost field
    document.getElementById("total-cost").value = totalCost;

    // Display the formatted value (with commas) to the user
    document.getElementById("total-cost-display").innerText =
        totalCost.toLocaleString("en-NG");
}

// new codes

// ........................................................................


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
    const discountPercentageElement = document.getElementById('discount-percentage');
    let maxDaysPerWeek = 0; // Track maximum days per week for the package

    // Toggle mobile menu visibility
    mobileMenu.addEventListener("click", () => {
        navLinks.classList.toggle("active");
    });

    // When the package selection changes, update max days per week, cost, and reset selections
    packageSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const packageCost = parseFloat(selectedOption.getAttribute("data-cost") || 0);
        const packageDays = parseInt(selectedOption.getAttribute("data-days") || 0);

        maxDaysPerWeek = packageDays; // Set allowed days from package

        // Update the package cost
        document.getElementById("package-cost").value = packageCost.toLocaleString();

        // Reset checkboxes and update counts
        resetCheckboxes();
        updateCountdown();
        updateExtraDaysCheckboxes();
        updateTotalCost(); // Update the total cost when a new package is selected
    });

    // Add event listeners to service day checkboxes
    serviceDayCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            const checkedDays = document.querySelectorAll('input[name="service_days[]"]:checked').length;

            // Ensure selected days do not exceed the package limit
            if (checkedDays > maxDaysPerWeek) {
                alert(`You can select a maximum of ${maxDaysPerWeek} days per week for the selected package.`);
                this.checked = false;
            }

            updateCountdown();
            updateExtraDaysCheckboxes();
            updateTotalCost();
        });
    });

    // Function to reset all service day checkboxes
    function resetCheckboxes() {
        serviceDayCheckboxes.forEach((checkbox) => {
            checkbox.checked = false; // Uncheck all service day checkboxes
        });
    }

    // Update the remaining service day countdown based on package
    function updateCountdown() {
        const checkedDays = document.querySelectorAll('input[name="service_days[]"]:checked').length;
        const remainingDays = maxDaysPerWeek - checkedDays;

        // Display the remaining days for the package
        document.getElementById("countdown").textContent = `You are left with ${remainingDays} out of ${maxDaysPerWeek} Day(s) for this Service`;
    }

    // Disable extra day checkboxes if they conflict with selected service days
    function updateExtraDaysCheckboxes() {
        const selectedServiceDays = Array.from(document.querySelectorAll('input[name="service_days[]"]:checked')).map((checkbox) => checkbox.value);

        extraDayCheckboxes.forEach((checkbox) => {
            if (selectedServiceDays.includes(checkbox.value)) {
                checkbox.disabled = true; // Disable the checkbox if it conflicts
                checkbox.checked = false; // Uncheck it if it's already checked
            } else {
                checkbox.disabled = false; // Enable other checkboxes
            }
        });
    }

    // Add event listener for extra day checkboxes
    extraDayCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            updateTotalCost(); // Update total cost when extra days are checked/unchecked
        });
    });

    // Function to calculate and display the cost for extra days
    function updateExtraDaysCost() {
        const costPerDay = 4000; // Cost per extra day
        const checkedExtraDays = document.querySelectorAll('input[name="extra_days[]"]:checked').length;
        const extraTotalCost = checkedExtraDays * costPerDay;

        document.getElementById("extra-days-count").innerText = `${checkedExtraDays} Extra Day(s) selected`;
        document.getElementById("extra-cost").value = extraTotalCost;
        document.getElementById("extra-cost-display").innerText = extraTotalCost.toLocaleString("en-NG");

        return extraTotalCost;
    }

    // Function to calculate and update the grand total for apartment features
    function calculateGrandTotal() {
        let grandTotal = 0;

        featureInputs.forEach((input) => {
            const costPerUnit = parseFloat(input.dataset.cost) || 0;
            const quantity = parseInt(input.value) || 0;
            const totalCost = costPerUnit * quantity;

            const totalSpan = document.getElementById("total_" + input.id.split("feature_")[1]);
            if (totalSpan) {
                totalSpan.textContent = totalCost.toLocaleString(); // Format total with commas
            }

            grandTotal += totalCost;
        });

        return grandTotal;
    }

    // Recalculate grand total whenever the user changes apartment feature quantities
    featureInputs.forEach((input) => {
        input.addEventListener("input", updateTotalCost);
    });

    // Function to update the overall total cost
    function updateTotalCost() {
        const packageCost = parseFloat(document.getElementById("package-cost").value.replace(/,/g, "")) || 0;
        const extraTotalCost = updateExtraDaysCost();
        const apartmentFeatureCost = calculateGrandTotal();

        const totalCost = packageCost + extraTotalCost + apartmentFeatureCost;
        const discountPercentage = parseFloat(discountPercentageElement.innerText) || 0; // Get discount percentage
        const discountAmount = (totalCost * (discountPercentage / 100)); // Calculate discount amount
        const finalTotalCost = totalCost - discountAmount; // Calculate final total cost

        document.getElementById("total-cost").value = totalCost; // Store total cost
        grandTotalElement.innerText = `₦${totalCost.toLocaleString("en-NG")}`; // Format grand total cost with commas
        discountedTotalElement.innerText = `₦${finalTotalCost.toLocaleString("en-NG")}`; // Format discounted total cost with commas
    }

    // Initialize total cost on page load
    updateTotalCost();
});



// Discount fetching and handling
document.addEventListener('DOMContentLoaded', function() {
    const discountPercentageElement = document.getElementById('discount-percentage');
    const serviceCountElement = document.getElementById('service-count');

    // Function to fetch discount based on selected service count
    const fetchDiscount = (selectedServices) => {
        fetch(window.Laravel.getDiscountRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken // Use the CSRF token from the global object
            },
            body: JSON.stringify({
                serviceName: selectedServices
            }) // Send selected services to the server
        })
        .then(response => response.json())
        .then(data => {
            discountPercentageElement.innerText = `${data.discount_percent}`; // Update the UI with the discount
            updateTotalCost(); // Update the total cost whenever discount is fetched
        })
        .catch(error => console.error('Error fetching discount:', error));
    };

    // Function to handle service selection
    const handleServiceSelection = () => {
        const selectedServices = Array.from(document.querySelectorAll('input[name="serviceName[]"]:checked'))
            .map(service => service.value); // Get selected service names

        serviceCountElement.innerText = selectedServices.length; // Update service count
        fetchDiscount(selectedServices); // Fetch the discount based on selected services
    };

    // Attach event listeners to service checkboxes
    document.querySelectorAll('input[name="serviceName[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', handleServiceSelection);
    });

    // Initial call to set the display to zero
    handleServiceSelection();
});


// Discount fetching and handling
document.addEventListener('DOMContentLoaded', function() {
    const discountPercentageElement = document.getElementById('discount-percentage');
    const serviceCountElement = document.getElementById('service-count');

    // Function to fetch discount based on selected service count
    const fetchDiscount = (selectedServices) => {
        fetch(window.Laravel.getDiscountRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.Laravel.csrfToken // Use the CSRF token from the global object
            },
            body: JSON.stringify({
                serviceName: selectedServices
            }) // Send selected services to the server
        })
        .then(response => response.json())
        .then(data => {
            discountPercentageElement.innerText = `${data.discount_percent}`; // Update the UI with the discount
            updateTotalCost(); // Update the total cost whenever discount is fetched
        })
        .catch(error => console.error('Error fetching discount:', error));
    };

    // Function to handle service selection
    const handleServiceSelection = () => {
        const selectedServices = Array.from(document.querySelectorAll('input[name="serviceName[]"]:checked'))
            .map(service => service.value); // Get selected service names

        serviceCountElement.innerText = selectedServices.length; // Update service count
        fetchDiscount(selectedServices); // Fetch the discount based on selected services
    };

    // Attach event listeners to service checkboxes
    document.querySelectorAll('input[name="serviceName[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', handleServiceSelection);
    });

    // Initial call to set the display to zero
    handleServiceSelection();
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

// // <!-- ID Card Upload with Image Preview -->
// // Make images clickable to open file dialog
// document.getElementById("passport_preview").onclick = function () {
//     document.getElementById("passport").click();
// };

// document.getElementById("id_card_preview").onclick = function () {
//     document.getElementById("id_card").click();
// };

// // Preview the selected image
// function previewImage(event, previewId) {
//     const preview = document.getElementById(previewId);
//     const file = event.target.files[0];

//     if (file) {
//         const reader = new FileReader();
//         reader.onload = function (e) {
//             preview.src = e.target.result;
//             preview.style.display = "block";
//         };
//         reader.readAsDataURL(file);
//     } else {
//         preview.src = "";
//         preview.style.display = "none";
//     }
// }


// preview password
function togglePassword(id) {
    const passwordField = document.getElementById(id);
    const passwordToggleIcon = passwordField.nextElementSibling;

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordToggleIcon.classList.remove('fa-eye');
        passwordToggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordToggleIcon.classList.remove('fa-eye-slash');
        passwordToggleIcon.classList.add('fa-eye');
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


