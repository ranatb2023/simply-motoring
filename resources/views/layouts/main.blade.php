<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simply Motoring UK</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <style>
        @font-face {
            font-family: 'Geist';
            src: url('https://cdn.jsdelivr.net/npm/geist/dist/fonts/geist-sans/Geist-Regular.woff2') format('woff2');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Geist';
            src: url('https://cdn.jsdelivr.net/npm/geist/dist/fonts/geist-sans/Geist-Bold.woff2') format('woff2');
            font-weight: 700;
            font-style: normal;
        }

        body {
            font-family: 'Geist', sans-serif;
        }

        /* -- Clip Paths -- */
        .hero-btn-clip {
            clip-path: polygon(30px 0,
                    100% 0,
                    100% calc(100% - 30px),
                    calc(100% - 30px) 100%,
                    0 100%,
                    0 30px);
        }

        /* Apply mask globally for all screens */
        /* Apply mask globally for all screens >= lg */
        @media (min-width: 1024px) {
            .hero-image-clip {
                /* Use mask instead of clip-path to allow border-radius on other corners */
                -webkit-mask-image: linear-gradient(45deg, transparent 85px, black 85px);
                mask-image: linear-gradient(45deg, transparent 85px, black 85px);
            }
        }

        /* Marquee Animation */
        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .marquee-content {
            display: flex;
            width: max-content;
            animation: marquee 30s linear infinite;
        }

        .marquee-content:hover {
            animation-play-state: paused;
        }
    </style>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/92041d487f.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.7.2/css/all.css">
</head>

<body class="antialiased bg-white">
    <!-- Splash Screen -->
    <div id="splash-screen"
        class="fixed inset-0 bg-dark flex flex-col justify-end p-8 sm:p-12 transition-opacity duration-700 ease-out"
        style="z-index: 9999;">
        <div
            class="flex justify-between lg:items-end w-full max-w-[1440px] mx-auto flex-col md:flex-row gap-4 md:gap-0">
            <h1 class="text-primary text-6xl sm:text-8xl font-bold tracking-tighter leading-none font-geist uppercase">
                Simply Motoring</h1>
            <div class="text-primary">
                <i class="fa-solid fa-tire text-6xl sm:text-8xl" style="animation: spin 10s linear infinite;"></i>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('load', function () {
            const splash = document.getElementById('splash-screen');
            if (splash) {
                setTimeout(() => {
                    splash.classList.add('opacity-0', 'pointer-events-none');
                    setTimeout(() => {
                        splash.remove();
                    }, 700);
                }, 500);
            }
        });
    </script>
    @if (request()->routeIs('service') || request()->routeIs('service.brake-discs-and-pads') || request()->routeIs('service.brake-fluid-change') || request()->routeIs('service.full-service') || request()->routeIs('service.interim-service') || request()->routeIs('service.major-service') || request()->routeIs('blog.show') || request()->routeIs('blog.post'))
        @include('partials.service-header')
    @elseif (request()->routeIs('contact') || request()->routeIs('blogs'))
        @include('partials.page-header')
    @else
        @include('partials.header')
    @endif
    @include('partials.menu')

    @yield('content')

    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const menuTriggers = document.querySelectorAll('#menu-trigger, #mobile-menu-trigger');
            const menuClose = document.getElementById('menu-close');
            const menu = document.getElementById('fullscreen-menu');
            const servicesLink = document.getElementById('menu-link-services');
            const submenuContainer = document.getElementById('submenu-container');

            // Open Menu
            menuTriggers.forEach(trigger => {
                trigger.addEventListener('click', () => {
                    menu.classList.remove('translate-x-full');
                    // Reset state on open
                    submenuContainer.classList.add('opacity-0', 'pointer-events-none');
                    links.forEach(l => {
                        if (l.dataset.active === 'true') {
                            l.classList.remove('text-[#333]');
                            l.classList.add('text-primary');
                        } else {
                            l.classList.remove('text-primary');
                            l.classList.add('text-[#333]');
                        }
                    });
                });
            });

            // Close Menu
            menuClose.addEventListener('click', () => {
                menu.classList.add('translate-x-full');
            });

            // Handle Submenu Hover (Desktop) & Click (Mobile)
            const links = document.querySelectorAll('.menu-link');

            // Initial state: Submenu is hidden by default CSS class

            servicesLink.addEventListener('click', (e) => {
                // If mobile view (<1024px)
                if (window.innerWidth < 1024) {
                    e.preventDefault();
                    // Toggle visibility classes for mobile accordion effect
                    submenuContainer.classList.toggle('max-h-0');
                    submenuContainer.classList.toggle('max-h-[1000px]'); // Arbitrary large height for transition
                    submenuContainer.classList.toggle('opacity-0');
                    submenuContainer.classList.toggle('opacity-100');
                    submenuContainer.classList.toggle('pointer-events-none');
                    submenuContainer.classList.toggle('mt-10');
                    submenuContainer.classList.toggle('pb-10');
                }
            });

            links.forEach(link => {
                link.addEventListener('mouseenter', () => {
                    // Reset all links color except hovered
                    links.forEach(l => {
                        l.classList.remove('text-primary');
                        l.classList.add('text-[#333]');
                    });

                    // Highlight hovered
                    link.classList.remove('text-[#333]');
                    link.classList.add('text-primary');

                    // Show submenu only if Services is hovered
                    if (link === servicesLink) {
                        submenuContainer.classList.remove('opacity-0', 'pointer-events-none');
                    } else {
                        submenuContainer.classList.add('opacity-0', 'pointer-events-none');
                    }
                });
            });
        });
    </script>
</body>

</html>