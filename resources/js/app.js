import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    const membershipMenu = document.getElementById('membershipMenu');
    const ledgerMenu = document.getElementById('ledgerMenu');
    const membershipArrow = document.getElementById('membershipArrow');

    membershipMenu.addEventListener('show.bs.collapse', function () {
        membershipArrow.classList.remove('bi-chevron-down');
        membershipArrow.classList.add('bi-chevron-up');
    });

    membershipMenu.addEventListener('hide.bs.collapse', function () {
        membershipArrow.classList.remove('bi-chevron-up');
        membershipArrow.classList.add('bi-chevron-down');
    });

    ledgerMenu.addEventListener('show.bs.collapse', function () {
        membershipArrow.classList.remove('bi-chevron-down');
        membershipArrow.classList.add('bi-chevron-up');
    });

    ledgerMenu.addEventListener('hide.bs.collapse', function () {
        membershipArrow.classList.remove('bi-chevron-up');
        membershipArrow.classList.add('bi-chevron-down');
    });
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


