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
    @if (request()->routeIs('service') || request()->routeIs('service.brake-discs-and-pads') || request()->routeIs('service.brake-fluid-change'))
        @include('partials.service-header')
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

        // Typewriter Effect for Headings
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('.typewriter-effect');
            if (elements.length === 0) return;

            // Helper to wrap characters in spans
            function wrapChars(element) {
                // Get all child nodes
                const nodes = Array.from(element.childNodes);

                nodes.forEach(node => {
                    if (node.nodeType === 3) { // Text node
                        // Fix for "IN" having space:
                        // excessive whitespace/newlines (indentation) between elements should be ignored.
                        // If the node is purely whitespace and contains a newline, it's likely source code indentation.
                        if (!node.textContent.trim() && node.textContent.includes('\n')) {
                            node.textContent = '';
                            return;
                        }

                        // Normalize remaining whitespace (e.g. spaces between words)
                        let text = node.textContent.replace(/\s+/g, ' ');

                        if (text.length === 0) return;

                        const fragment = document.createDocumentFragment();
                        text.split('').forEach(char => {
                            const span = document.createElement('span');
                            span.textContent = char;
                            span.style.opacity = '0';
                            span.style.transition = 'opacity 0.1s ease'; // Fast fade for crisp type feel
                            span.classList.add('char-reveal');
                            fragment.appendChild(span);
                        });
                        node.parentNode.replaceChild(fragment, node);
                    } else if (node.nodeType === 1) { // Element node (e.g. BR, SPANS)
                        wrapChars(node);
                    }
                });
            }

            // Observer to trigger animation
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const target = entry.target;
                    const chars = target.querySelectorAll('.char-reveal');

                    if (entry.isIntersecting) {
                        // Make parent visible
                        target.classList.remove('opacity-0');

                        // Animate characters
                        chars.forEach((char, index) => {
                            // Use a small delay for staggered effect
                            // Store timeout ID on the element if needed for robust clearing, 
                            // but basic CSS transition + simple delay usually works fine if reset properly on exit.
                            setTimeout(() => {
                                char.style.opacity = '1';
                            }, index * 40);
                        });
                    } else {
                        // Creating a "reset" effect when scrolling away
                        target.classList.add('opacity-0');
                        chars.forEach(char => {
                            char.style.opacity = '0';
                        });
                    }
                });
            }, { threshold: 0.1 });

            // Apply to all elements
            elements.forEach(el => {
                wrapChars(el);
                observer.observe(el);
            });
        });
    </script>
</body>

</html>