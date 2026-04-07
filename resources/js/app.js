import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    const membershipMenu = document.getElementById('membershipMenu');
    const ledgerMenu = document.getElementById('ledgerMenu');
    const membershipArrow = document.getElementById('membershipArrow');

    // Back to top button
    var backToTopButton = document.getElementById('back-to-top');

    if(backToTopButton) {
        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 100) { // Show button after 100px of scroll
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });

        backToTopButton.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    if(membershipMenu) {
        membershipMenu.addEventListener('show.bs.collapse', function () {
            membershipArrow.classList.remove('bi-chevron-down');
            membershipArrow.classList.add('bi-chevron-up');
        });

        membershipMenu.addEventListener('hide.bs.collapse', function () {
            membershipArrow.classList.remove('bi-chevron-up');
            membershipArrow.classList.add('bi-chevron-down');
        });
    }

    if(ledgerMenu) {
        ledgerMenu.addEventListener('show.bs.collapse', function () {
            membershipArrow.classList.remove('bi-chevron-down');
            membershipArrow.classList.add('bi-chevron-up');
        });

        ledgerMenu.addEventListener('hide.bs.collapse', function () {
            membershipArrow.classList.remove('bi-chevron-up');
            membershipArrow.classList.add('bi-chevron-down');
        });
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const accordions = document.querySelectorAll(".accordion-header");

    accordions.forEach(header => {
        header.addEventListener("click", function() {
            const content = this.nextElementSibling;

            // Close other open accordions
            document.querySelectorAll(".accordion-content").forEach(item => {
                if (item !== content) {
                    item.style.maxHeight = null;
                }
            });

            // Toggle current accordion
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    });
});


