<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @unless (str_contains(request()->getHost(), 'staging'))
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PGJ7F29P');</script>
    <!-- End Google Tag Manager -->

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-18425103934"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'AW-18425103934');
    </script>
    <!-- End Google tag -->

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window,document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '4562194687430483');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=4562194687430483&ev=PageView&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->
    @endunless

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('favicon-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    {{-- Robots: a page can opt out of indexing via @section('robots', 'noindex, nofollow').
         The staging subdomain is always noindexed (never affects the live site). --}}
    @php
        $__robots = trim($__env->yieldContent('robots'));
        if (str_contains(request()->getHost(), 'staging')) {
            $__robots = 'noindex, nofollow';
        }
    @endphp
    @if ($__robots)
        <meta name="robots" content="{{ $__robots }}">
    @endif

    {{-- Canonical URL — always points to the non-www version so Google treats it as the single source --}}
    <link rel="canonical" href="{{ rtrim(config('app.url'), '/') . request()->getPathInfo() }}">

    {{-- Meta description — each page can override via @section('meta_description', '...') --}}
    <meta name="description" content="@yield('meta_description', 'Simply Motoring — trusted MOT testing, car servicing and repairs in Doncaster. Book your appointment online today.')">

    {{-- Page title — each page can override via @section('meta_title', '...') --}}
    <title>@yield('meta_title', 'Simply Motoring UK')</title>

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

        @media (min-width: 1024px) {
            #submenu-container {
                max-height: none !important;
            }
        }
    </style>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="antialiased bg-white">
    @unless (str_contains(request()->getHost(), 'staging'))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PGJ7F29P"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endunless

    <!-- Splash Screen -->
    <div id="splash-screen"
        class="fixed inset-0 bg-dark flex flex-col justify-end p-8 sm:p-12 transition-opacity duration-700 ease-out"
        style="z-index: 9999;">
        <div
            class="flex justify-between lg:items-end w-full max-w-[1440px] mx-auto flex-col md:flex-row gap-4 md:gap-0">
            <h2 class="text-primary text-6xl sm:text-8xl font-bold tracking-tighter leading-none font-geist uppercase">
                Simply Motoring</h2>
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
    @include('partials.header')
    @include('partials.menu')

    @yield('content')

    @include('partials.footer')

    @include('partials.booking-modal')

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

                    // Reset submenu state on open
                    submenuContainer.style.maxHeight = null;
                    submenuContainer.classList.remove('opacity-100');
                    submenuContainer.classList.add('opacity-0', 'pointer-events-none', 'max-h-0');

                    // Reset links color
                    links.forEach(l => {
                        if (l.dataset.active === 'true') {
                            l.classList.remove('text-[#333]', 'text-white/20');
                            l.classList.add('text-primary');
                        } else {
                            l.classList.remove('text-primary');
                            l.classList.add('text-white/20'); // Ensure it returns to default non-active color rather than #333 on mobile
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
                    // Toggle visibility using inline max-height for smooth accordion flow
                    if (submenuContainer.classList.contains('pointer-events-none')) {
                        // Open
                        submenuContainer.style.maxHeight = submenuContainer.scrollHeight + 'px';
                        submenuContainer.classList.remove('opacity-0', 'pointer-events-none', 'max-h-0');
                        submenuContainer.classList.add('opacity-100');
                    } else {
                        // Close
                        submenuContainer.style.maxHeight = '0px';
                        submenuContainer.classList.remove('opacity-100');
                        submenuContainer.classList.add('opacity-0', 'pointer-events-none');

                        // We do not add max-h-0 immediately to allow the transition to animate,
                        // but setting style.maxHeight to '0px' triggers the transition.
                    }
                }
            });

            links.forEach(link => {
                link.addEventListener('mouseenter', () => {
                    if (window.innerWidth >= 1024) {
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
                    }
                });
            });
        });
    </script>

    <!-- WhatsApp Widget -->
    <div class="fixed bottom-6 right-6 z-50">
        <div class="relative group">
            <div class="absolute -inset-1 bg-[#25D366] rounded-full opacity-40 animate-ping"></div>
            <a href="https://wa.me/441302456406" target="_blank" rel="noopener noreferrer nofollow"
                class="relative flex items-center justify-center w-14 h-14 bg-[#25D366] text-white rounded-full shadow-lg hover:scale-110 transition-transform duration-300"
                aria-label="Chat with us on WhatsApp">
                <i class="fa-brands fa-whatsapp text-3xl"></i>
            </a>
        </div>
    </div>
</body>

</html>