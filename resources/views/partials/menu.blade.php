<!-- Fullscreen Menu Overlay -->
<div id="fullscreen-menu"
    class="fixed inset-0 z-[100] bg-black text-white transform translate-x-full transition-transform duration-500 ease-out flex flex-col overflow-hidden">

    <!-- Main Content Wrapper (1440px constraint) -->
    <div class="max-w-[1440px] w-full mx-auto h-full flex flex-col p-[20px] lg:p-[40px]">

        <!-- Menu Header -->
        <div class="flex justify-between items-center mb-16 lg:mb-24 shrink-0">
            <!-- Logo Image (Inverted for black background) -->
            <a href="{{ route('home') }}" class="block z-50">
                <img src="{{ asset('images/menu-logo.png') }}" alt="Simply Motoring Logo"
                    class="w-[180px] lg:w-[220px] h-auto object-contain">
            </a>

            <button id="menu-close"
                class="flex items-center gap-2 bg-white/10 px-5 py-2.5 rounded-full transition-colors hover:bg-primary group">
                <span class="font-bold text-xs lg:text-md tracking-widest uppercase transition-colors">Menu</span>
                <i
                    class="fa-solid fa-xmark text-lg transform group-hover:rotate-90 transition-transform duration-300"></i>
            </button>
        </div>

        <!-- Menu Content Grid -->
        <div class="flex flex-col lg:flex-row flex-1 min-h-0 relative overflow-y-auto lg:overflow-visible no-scrollbar">

            <!-- Left Column: Main Navigation -->
            <nav class="flex flex-col space-y-0 lg:w-1/2 z-10 pt-4 lg:justify-center relative">
                <a href="{{ route('home') }}"
                    class="menu-link text-[56px] lg:text-[96px] font-bold leading-[0.9] tracking-tighter {{ request()->routeIs('home') ? 'text-primary' : 'text-white/20' }} hover:text-primary transition-colors duration-300 uppercase block"
                    data-active="{{ request()->routeIs('home') ? 'true' : 'false' }}">Home</a>
                <!-- Services Link triggers submenu -->
                <a href="{{ route('service') }}" id="menu-link-services"
                    class="menu-link text-[56px] lg:text-[96px] font-bold leading-[0.9] tracking-tighter {{ request()->routeIs('service') ? 'text-primary' : 'text-white/20' }} hover:text-primary transition-colors duration-300 uppercase block relative"
                    data-active="{{ request()->routeIs('service') ? 'true' : 'false' }}">Services
                </a>

                <!-- Submenu placed here for mobile accordion flow -->
                <div id="submenu-container"
                    class="lg:absolute lg:top-0 lg:left-full lg:w-full lg:h-full flex flex-col justify-start lg:justify-center h-auto lg:pl-20 transition-all duration-500 max-h-0 lg:max-h-none overflow-hidden lg:overflow-visible opacity-0 pointer-events-none">
                    <!-- Services Submenu -->
                    <div id="submenu-services"
                        class="submenu-content flex flex-col gap-3 lg:gap-6 pt-2 pb-4 lg:pt-0 lg:pb-0">
                        <a href="{{ route('service') }}"
                            class="group flex items-center justify-between lg:justify-start gap-4 lg:gap-8 text-xl lg:text-3xl text-white font-medium hover:text-primary transition-colors">
                            <span
                                class="text-white/20 font-bold w-8 lg:w-12 group-hover:text-primary/50 transition-colors text-lg lg:text-xl">00</span>
                            All Services
                        </a>
                        <a href="{{ route('service.brake-discs-and-pads') }}"
                            class="group flex items-center justify-between lg:justify-start gap-4 lg:gap-8 text-xl lg:text-3xl text-white font-medium hover:text-primary transition-colors">
                            <span
                                class="text-white/20 font-bold w-8 lg:w-12 group-hover:text-primary/50 transition-colors text-lg lg:text-xl">01</span>
                            Brake discs & pads
                        </a>
                        <a href="{{ route('service.brake-fluid-change') }}"
                            class="group flex items-center justify-between lg:justify-start gap-4 lg:gap-8 text-xl lg:text-3xl text-white font-medium hover:text-primary transition-colors">
                            <span
                                class="text-white/20 font-bold w-8 lg:w-12 group-hover:text-primary/50 transition-colors text-lg lg:text-xl">02</span>
                            Brake fluid change
                        </a>
                        <a href="{{ route('service.full-service') }}"
                            class="group flex items-center justify-between lg:justify-start gap-4 lg:gap-8 text-xl lg:text-3xl text-white font-medium hover:text-primary transition-colors">
                            <span
                                class="text-white/20 font-bold w-8 lg:w-12 group-hover:text-primary/50 transition-colors text-lg lg:text-xl">03</span>
                            Full service
                        </a>
                        <a href="{{ route('service.interim-service') }}"
                            class="group flex items-center justify-between lg:justify-start gap-4 lg:gap-8 text-xl lg:text-3xl text-white font-medium hover:text-primary transition-colors">
                            <span
                                class="text-white/20 font-bold w-8 lg:w-12 group-hover:text-primary/50 transition-colors text-lg lg:text-xl">04</span>
                            Interim service
                        </a>
                        <a href="{{ route('service.major-service') }}"
                            class="group flex items-center justify-between lg:justify-start gap-4 lg:gap-8 text-xl lg:text-3xl text-white font-medium hover:text-primary transition-colors">
                            <span
                                class="text-white/20 font-bold w-8 lg:w-12 group-hover:text-primary/50 transition-colors text-lg lg:text-xl">05</span>
                            Major service
                        </a>
                    </div>
                </div>

                <a href="{{ route('blogs') }}"
                    class="menu-link text-[56px] lg:text-[96px] font-bold leading-[0.9] tracking-tighter {{ request()->routeIs('blogs') ? 'text-primary' : 'text-white/20' }} hover:text-primary transition-colors duration-300 uppercase block mt-2 lg:mt-0"
                    data-active="{{ request()->routeIs('blogs') ? 'true' : 'false' }}">Blogs</a>
                <a href="{{ route('contact') }}"
                    class="menu-link text-[56px] lg:text-[96px] font-bold leading-[0.9] tracking-tighter {{ request()->routeIs('contact') ? 'text-primary' : 'text-white/20' }} hover:text-primary transition-colors duration-300 uppercase block"
                    data-active="{{ request()->routeIs('contact') ? 'true' : 'false' }}">Contact Us</a>
            </nav>

            <!-- Vertical Divider Line (Desktop Only) -->
            <div class="absolute left-1/2 top-0 bottom-0 w-[1px] bg-white/10 hidden lg:block"></div>

        </div>
    </div>
</div>