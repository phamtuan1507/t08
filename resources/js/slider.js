// $(document).ready(function() {
//     $(".banner__list.owl-carousel").owlCarousel({
//         // items: 3,
//         slidesToShow: 1, // Ensure only one slide is shown at a time
//         slidesToScroll: 1, // Scroll one slide at a time
//         loop: true,
//         margin: 20,
//         nav: false,
//         dots: false,
//         autoplay: true,
//         autoplayTimeout: 5000,
//         autoplayHoverPause: true,
//         responsiveClass: true,
//         responsiveRefreshRate: true,
//         navText: [
//             "<i class='fal fa-angle-left'></i>",
//             "<i class='fal fa-angle-right'></i>",
//         ],
        // responsive: {
        //     0: {
        //         items: 1,
        //         nav: false,
        //     },
        //     768: {
        //         items: 1,
        //         nav: false,
        //     },
        //     992: {
        //         items: 1,
        //         nav: false,
        //     },
        //     1200: {
        //         items: 1,
        //         nav: false,
        //     },
        // },
//         responsive: [
//             {
//                 breakpoint: 768,
//                 settings: {
//                     arrows: false,
//                     dots: true
//                 }
//             }
//         ]
//     });
// });

document.getElementById('userDropdown')?.addEventListener('click', function() {
    const menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('hidden');
});

document.getElementById('userDropdown1')?.addEventListener('click', function() {
    const menu = document.getElementById('dropdownMenu1');
    menu.classList.toggle('hidden');
});

// document.addEventListener('click', function(event) {
//     const dropdown = document.getElementById('dropdownMenu');
//     const button = document.getElementById('userDropdown');
//     if (dropdown && button && !dropdown.contains(event.target) && !button.contains(event.target)) {
//         dropdown.classList.add('hidden');
//     }
// });

$(document).ready(function() {
    $('.banner-carousel').owlCarousel({
        loop: true, // Loops banners but should display unique items
        items: 1, // Show one banner at a time
        margin: 0,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        dots: true,
        nav: false,
        // navText: [
        //     '<button class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full">←</button>',
        //     '<button class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full">→</button>'
        // ],
        responsive: {
            0: {
                nav: false, // Hide arrows on mobile
                dots: true
            },
            768: {
                nav: false
            }
        },

    });
    // Animation khi cuộn đến sản phẩm
    $(window).scroll(function() {
        $('.bento-layout > div').each(function() {
            if ($(this).isInViewport()) {
                $(this).addClass('animate__fadeInUp');
            }
        });
    });

    $.fn.isInViewport = function() {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };
});

$(document).ready(function () {
    if ($(window).width() < 1024) {
        $(window).scroll(function () {
            var pos_body = $("html,body").scrollTop();
            if (pos_body > 80) {
                $(".header").addClass("sticky shadow");
            } else if (pos_body <= 0) {
                $(".header").removeClass("sticky shadow");
            }
        });
    }
    if ($(window).width() > 1024) {
        $(window).scroll(function () {
            var pos_body = $("html,body").scrollTop();
            if (pos_body > 170) {
                $(".header").addClass("sticky shadow");
            } else if (pos_body <= 0) {
                $(".header").removeClass("sticky shadow");
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('loginModal');
    const closeModal = document.querySelector('.close-modal');
    const closeBtn = document.querySelector('.close-btn');
    const openModalLinks = document.querySelectorAll('.open-modal');

    openModalLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const productName = link.getAttribute('data-product-name');
            document.getElementById('modal-product-name').textContent = productName;
            modal.classList.remove('hidden');
        });
    });

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
