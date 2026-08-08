const sidebar = document.getElementById("sidebar");
const burgerIcon = document.getElementById("burger-icon");

// Toggle sidebar when burger icon is clicked
burgerIcon.addEventListener("click", () => {
    sidebar.classList.toggle("active");
});

// Toggle submenu and handle close button
document.addEventListener("DOMContentLoaded", () => {
    const closeSidebarBtn = document.getElementById("close-sidebar");

    // Close sidebar on "X" button click and close any open submenus
    closeSidebarBtn.addEventListener("click", () => {
        closeSidebar(); // Use the function to close sidebar and submenus
    });

    // Toggle submenu for nav links
    document.querySelectorAll(".nav-item > .nav-link").forEach((link) => {
        link.addEventListener("click", function (event) {
            const submenu = this.nextElementSibling;
            const chevron = this.querySelector(".submenu-icon"); // Get the chevron icon

            // Check if the clicked link has a submenu
            if (submenu && submenu.classList.contains("submenu")) {
                event.preventDefault(); // Prevent default link behavior only for submenu links

                // Close all open submenus except the one being clicked
                document
                    .querySelectorAll(".nav-item .submenu")
                    .forEach((otherSubmenu) => {
                        if (otherSubmenu !== submenu) {
                            otherSubmenu.classList.remove("active");
                            const otherChevron =
                                otherSubmenu.previousElementSibling.querySelector(
                                    ".submenu-icon"
                                );
                            if (otherChevron) {
                                otherChevron.classList.remove("rotate");
                            }
                        }
                    });

                // Toggle the clicked submenu and rotate the chevron
                submenu.classList.toggle("active");
                if (chevron) {
                    chevron.classList.toggle("rotate");
                }
            }
        });
    });

    // Close sidebar when clicking outside
    document.addEventListener("click", function (event) {
        // Check if the click is outside the sidebar and the burger icon
        if (
            !sidebar.contains(event.target) &&
            !burgerIcon.contains(event.target)
        ) {
            if (sidebar.classList.contains("active")) {
                closeSidebar(); // Close sidebar if it's open and click is outside
            }
        }
    });
});

// Function to close the sidebar and reset submenus
function closeSidebar() {
    sidebar.classList.remove("active");
    document.querySelectorAll(".nav-item .submenu").forEach((submenu) => {
        submenu.classList.remove("active"); // Close all open submenus
        const chevron =
            submenu.previousElementSibling.querySelector(".submenu-icon");
        if (chevron) {
            chevron.classList.remove("rotate"); // Reset chevron rotation
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const messageListItems = document.querySelectorAll(".message-list-item");
    const messageAuthor = document.getElementById("message-author");
    const messageSubject = document.getElementById("message-subject");
    const messageBody = document.getElementById("message-body");
    const messageTimestamp = document.getElementById("message-timestamp");
    const replyContent = document.getElementById("reply-content");
    const replyTitle = document.getElementById("reply-title");
    const replyBox = document.querySelector(".reply-box");
    const contactMessageIdInput = document.getElementById("contact_message_id");

    messageListItems.forEach((item) => {
        item.addEventListener("click", function () {
            // Clear previous replies
            replyContent.innerHTML = "";

            // Remove active class from all items
            messageListItems.forEach((i) => i.classList.remove("active"));

            // Add active class to the clicked item
            item.classList.add("active");

            // Get data attributes from the selected message
            const firstName = item.getAttribute("data-firstname");
            const lastName = item.getAttribute("data-lastname");
            const subject = item.getAttribute("data-subject");
            const message = item.getAttribute("data-message");
            const createdAt = item.getAttribute("data-createdat");
            const messageType = item.getAttribute("data-messagetype");
            const phone = item.getAttribute("data-phone");
            const email = item.getAttribute("data-email");
            const replies = JSON.parse(item.getAttribute("data-replies"));

            // Update the message details section with selected message
            messageAuthor.textContent = `${firstName} ${lastName}`;
            messageSubject.innerHTML = `<strong>Subject:</strong> ${subject}`;
            messageBody.innerHTML = `
                    <p><strong>Message:</strong> ${message.replace(
                        /\n/g,
                        "<br>"
                    )}</p>
                    <p><strong>Phone:</strong> ${phone}</p>
                    <p><strong>Email:</strong> ${email}</p>
                `;
            messageTimestamp.textContent = createdAt;
            replyTitle.textContent = `Reply to ${firstName} ${lastName}`;
            contactMessageIdInput.value = item.getAttribute("data-id");

            // Display replies
            if (replies.length > 0) {
                replies.forEach((reply) => {
                    const replyDiv = document.createElement("div");
                    replyDiv.classList.add("reply-item");
                    replyDiv.innerHTML = `<strong>Admin:</strong> ${
                        reply.reply_message
                    } <br>
                            <span class="reply-timestamp">${new Date(
                                reply.created_at
                            ).toLocaleString()}</span>`;
                    replyContent.appendChild(replyDiv);
                });
            } else {
                replyContent.innerHTML = "<p>No replies yet</p>";
            }

            // Show reply box if the message type is not guest
            replyBox.style.display = messageType === "guest" ? "none" : "block";
        });
    });
});

// preloader
// Wait until the page has fully loaded before hiding the preloader
window.addEventListener("load", function () {
    // Hide preloader after loading
    document.getElementById("preloader").style.display = "none";
    // Show the main content
    document.getElementById("main-content").style.display = "block";
});


// payment timer countdown to be implimented soon
document.addEventListener("DOMContentLoaded", function () {
    const countdownEnd = new Date("{{ $paymentRecord->countdown_end }}").getTime();

    const countdown = setInterval(function () {
        const now = new Date().getTime();
        const distance = countdownEnd - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("countdown").innerHTML =
            `${days}d ${hours}h ${minutes}m ${seconds}s`;

        if (distance < 0) {
            clearInterval(countdown);
            document.getElementById("countdown").innerHTML = "EXPIRED";
        }
    }, 1000);
});



document.addEventListener('DOMContentLoaded', function () {
    // Approve button click event
    document.querySelectorAll('.approve-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            if (confirm('Are you sure you want to approve this user?')) {
                // Send AJAX request to approve user
                fetch(`/approve-user/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('User approved successfully!');
                            location.reload();
                        } else {
                            alert('Error approving user.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });

    // Pend button click event
    document.querySelectorAll('.pend-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-user-id');
            if (confirm('Are you sure you want to mark this user as pending?')) {
                // Send AJAX request to pend user
                fetch(`/pend-user/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('User marked as pending successfully!');
                            location.reload();
                        } else {
                            alert('Error marking user as pending.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });
});


// for services ........................................................................

document.addEventListener("DOMContentLoaded", function() {
    const serviceRegular = document.getElementById("service_regular");
    const sradContainer = document.getElementById("srad_container");
    const sdnradContainer = document.getElementById("sdnrad_container");
    const numberOfApartment = document.getElementById("number_of_apartment");
    const apartmentDetails = document.getElementById("apartment_details");

    const toggleVisibility = () => {
        if (serviceRegular.checked) {
            sradContainer.style.display = "block";
        } else {
            sradContainer.style.display = "none";
            numberOfApartment.checked = false; // Reset SRAD state
        }

        if (numberOfApartment.checked) {
            sdnradContainer.style.display = "none";
            apartmentDetails.style.display = "block";
        } else {
            sdnradContainer.style.display = "block";
            apartmentDetails.style.display = "none";
        }
    };

    // Event listeners
    document.querySelectorAll('input[name="service_type"]').forEach(radio => {
        radio.addEventListener("change", toggleVisibility);
    });

    numberOfApartment.addEventListener("change", toggleVisibility);

    // Initial check
    toggleVisibility();
});
