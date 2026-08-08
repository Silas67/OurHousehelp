const sidebar = document.getElementById('sidebar');
const burgerIcon = document.getElementById('burger-icon');

// Toggle sidebar when burger icon is clicked
burgerIcon.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});

// Toggle submenu and handle close button
document.addEventListener('DOMContentLoaded', () => {
    const closeSidebarBtn = document.getElementById('close-sidebar');

    // Close sidebar on "X" button click and close any open submenus
    closeSidebarBtn.addEventListener('click', () => {
        closeSidebar(); // Use the function to close sidebar and submenus
    });

    // Toggle submenu for nav links
    document.querySelectorAll('.nav-item > .nav-link').forEach(link => {
        link.addEventListener('click', function(event) {
            const submenu = this.nextElementSibling;
            const chevron = this.querySelector('.submenu-icon'); // Get the chevron icon

            // Check if the clicked link has a submenu
            if (submenu && submenu.classList.contains('submenu')) {
                event.preventDefault(); // Prevent default link behavior only for submenu links

                // Close all open submenus except the one being clicked
                document.querySelectorAll('.nav-item .submenu').forEach(otherSubmenu => {
                    if (otherSubmenu !== submenu) {
                        otherSubmenu.classList.remove('active');
                        const otherChevron = otherSubmenu.previousElementSibling.querySelector('.submenu-icon');
                        if (otherChevron) {
                            otherChevron.classList.remove('rotate');
                        }
                    }
                });

                // Toggle the clicked submenu and rotate the chevron
                submenu.classList.toggle('active');
                if (chevron) {
                    chevron.classList.toggle('rotate');
                }
            }
        });
    });

    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        // Check if the click is outside the sidebar and the burger icon
        if (!sidebar.contains(event.target) && !burgerIcon.contains(event.target)) {
            if (sidebar.classList.contains('active')) {
                closeSidebar(); // Close sidebar if it's open and click is outside
            }
        }
    });
});

// Function to close the sidebar and reset submenus
function closeSidebar() {
    sidebar.classList.remove('active');
    document.querySelectorAll('.nav-item .submenu').forEach(submenu => {
        submenu.classList.remove('active'); // Close all open submenus
        const chevron = submenu.previousElementSibling.querySelector('.submenu-icon');
        if (chevron) {
            chevron.classList.remove('rotate'); // Reset chevron rotation
        }
    });
}


// inbox ........................................

// document.addEventListener("DOMContentLoaded", function () {
//     const messageListItems = document.querySelectorAll(".message-list-item");
//     const messageAuthor = document.getElementById("message-author");
//     const messageSubject = document.getElementById("message-subject");
//     const messageBody = document.getElementById("message-body");
//     const messageTimestamp = document.getElementById("message-timestamp");
//     const replyTitle = document.getElementById("reply-title");

//     messageListItems.forEach((item) => {
//         item.addEventListener("click", function () {
//             // Remove active class from all items
//             messageListItems.forEach((i) => i.classList.remove("active"));

//             // Add active class to the clicked item
//             item.classList.add("active");

//             // Get data attributes from the selected message
//             const firstName = item.getAttribute("data-firstname");
//             const lastName = item.getAttribute("data-lastname");
//             const subject = item.getAttribute("data-subject");
//             const message = item.getAttribute("data-message");
//             const createdAt = item.getAttribute("data-createdat");

//             // Update the message details section with selected message
//             messageAuthor.textContent = `${firstName} ${lastName}`;
//             messageSubject.innerHTML = `<strong>Subject:</strong> ${subject}`;
//             messageBody.innerHTML = message.replace(/\n/g, "<br>");
//             messageTimestamp.textContent = createdAt;
//             replyTitle.textContent = `Reply to ${firstName} ${lastName}`;
//         });
//     });
// });




// preloader
// Wait until the page has fully loaded before hiding the preloader
window.addEventListener("load", function () {
    // Hide preloader after loading
    document.getElementById("preloader").style.display = "none";
    // Show the main content
    document.getElementById("main-content").style.display = "block";
});




