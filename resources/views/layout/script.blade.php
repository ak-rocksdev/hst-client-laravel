<script>
    $(document).ready(function() {
        hideLoading();
    });
    // Get all the links
    const links = document.querySelectorAll('a');

    // Iterate over each link
    links.forEach(link => {
        link.addEventListener('click', function (event) {
            if (!link.getAttribute("href").startsWith("#")) {

                // Add the "loadingShow" class to the elements with the class "loadingContainer"
                const loadingContainers = document.querySelectorAll(".loadingContainer");
                loadingContainers.forEach((container) => {
                    container.classList.add("loadingShow");
                });

                // check if link tag has parent element with id "gallery-project"

                // Remove the "loadingShow" class after two seconds
                setTimeout(() => {
                    loadingContainers.forEach((container) => {
                        container.classList.remove("loadingShow");
                    });
                }, 2000);
            }
        });
    });

    // Add click event listener to all links
    links.forEach(function(link) {
        link.addEventListener('click', function(event) {
            // Check if the link is within the gallery
            if (!event.target.closest('#gallery-project')) {
                // Check if the link does not have '#' as the href attribute
                if (link.getAttribute('href') !== '#') {
                    // Add class "loadingShow" to loading containers
                    loadingContainers.forEach(function(container) {
                        container.classList.add('loadingShow');
                    });

                    // Remove class "loadingShow" after 2 seconds
                    setTimeout(function() {
                        loadingContainers.forEach(function(container) {
                            container.classList.remove('loadingShow');
                        });
                    }, 3000);
                }
            }
        });
    });
    
    let hamburgerButton = document.querySelector('.nav-mobile-toggle');
    
    
    function navbar() {
        let hamburger = document.getElementById('hamburger');
        hamburger.classList.toggle('open');
        let navbar = document.querySelector('.navbar-toggle'); 
        navbar.classList.toggle('show');
    }
    
    hamburgerButton.addEventListener("click", function(event) { 
        navbar()
    })

    // on click li.nav-item run function navbar
    let navItem = document.querySelectorAll('.nav-item');
    navItem.forEach(function(item) {
        item.addEventListener("click", function(event) {
            navbar()
        })
    })

    const navbarContainer = document.querySelector('.navbar');
    const hamburger = document.querySelector('.hamburger');
    const navbarToggle = document.querySelector('.navbar-toggle');

    // Attach a click event listener to the document object
    document.addEventListener('click', function(event) {
        if(navbarToggle.classList.contains('show')) {
            if (!navbarContainer.contains(event.target) && !hamburger.contains(event.target)) {
                navbar();
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        var footer = document.querySelector(".footer"); // Get the footer element
        var footerHeight = footer.offsetHeight; // Get the height of the footer
        var screenHeight = window.innerHeight; // Get the height of the screen

        window.addEventListener('scroll', function () {
            var scroll = window.pageYOffset || document.documentElement.scrollTop; // Get the current scroll position

            if (scroll > screenHeight * 0.25) { // When scrolled 25% of the screen height
                footer.classList.remove("hidden"); // Show the footer
            } else {
                footer.classList.add("hidden"); // Hide the footer
            }
        });
    });

    // global variable
    const loadingContainers = document.getElementById("loadingContainer");
    // create function show loading
    function showLoading() {
        loadingContainers.classList.add("loadingShow");
    }

    function hideLoading(){
        loadingContainers.classList.remove("loadingShow");
    }

</script>