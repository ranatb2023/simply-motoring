@extends('layouts.main')

@section('content')
    <!-- Main Wrapper: 1440px max-width, 40px padding -->
    <!-- Hero Section -->
    <section class="relative w-full h-screen overflow-hidden">

        <!-- Mobile Background Image (Absolute Fullscreen) -->
        <div class="absolute inset-0 z-0 lg:hidden">
            <img src="{{ asset('images/ab7a55c40020dbf18750f61e6987c734559524fb.png') }}" class="w-full h-full object-cover"
                alt="Background">
            <div class="absolute inset-0" style="background-color: rgba(0, 0, 0, 0.6);"></div>
        </div>

        <!-- Main Content Wrapper -->
        <div
            class="max-w-[1440px] w-full mx-auto px-6 lg:px-[40px] h-full flex flex-col lg:flex-row gap-8 lg:gap-0 relative z-10">

            <!-- Left Side Content -->
            <div class="w-full lg:w-[50%] flex flex-col lg:justify-between py-10 h-full relative z-10 shrink-0 gap-10">

                <!-- Headlines -->
                <div>
                    <!-- Desktop Headline -->
                    <h1 id="hero-title"
                        style="font-family: 'Geist', sans-serif; font-weight: 600; font-size: 96px; line-height: 0.83; letter-spacing: -0.06em; text-transform: uppercase; leading-trim: CAP_HEIGHT;"
                        class="mt-32 text-black opacity-0 typewriter-effect hidden lg:block">
                        MOT TESTING<br>
                        & SERVICING<br>
                        <span class="flex items-baseline gap-3 flex-wrap">
                            <span class="text-black">IN</span>
                            <span class="text-primary">DONCASTER</span>
                        </span>
                    </h1>

                    <!-- Mobile Headline -->
                    <h1
                        class="font-geist font-semibold text-[48px] leading-[1.06] tracking-tighter text-white uppercase lg:hidden typewriter-effect opacity-0 mt-20">
                        MOT TESTING<br>& SERVICING<br>IN <span class="text-primary">DONCASTER</span>
                    </h1>
                </div>

                <!-- Info Box & CTA (Desktop) -->
                <div class="hidden lg:flex flex-col gap-8">
                    <div class="bg-black text-white p-6 rounded-2xl max-w-lg shadow-xl flex items-start gap-5">
                        <div class="bg-primary aspect-square w-12 h-12 flex items-center justify-center rounded shrink-0">
                            <!-- Wrench Icon -->
                            <svg class="w-6 h-6 text-black transform rotate-45" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium opacity-90 leading-relaxed font-geist text-gray-300">
                            Simply Motoring focuses on safety, performance, and long-term reliability.
                            From quick MOT checks to full car servicing, we treat every vehicle with care, delivering
                            service you can depend on.
                        </p>
                    </div>

                    <!-- Desktop Button -->
                    <div>
                        <a href="#book"
                            class="inline-flex items-center justify-center bg-black text-white text-sm font-bold uppercase tracking-widest px-10 py-5 hover:bg-primary hover:-translate-y-2 hover:translate-x-2 hover:shadow-2xl transition-all duration-300 shadow-lg group relative hero-btn-clip rounded-md">
                            BOOK YOUR CAR SERVICE
                        </a>
                    </div>
                </div>

                <!-- Mobile Info Text -->
                <div class="flex gap-4 lg:hidden">
                    <div class="w-5 h-5 bg-primary shrink-0 mt-1"></div>
                    <p class="text-white/90 text-lg leading-relaxed font-geist font-medium">
                        Simply Motoring focuses on safety, performance, and long-term reliability.
                        From quick MOT checks to full car servicing, we treat every vehicle with care, delivering
                        service you can depend on.
                    </p>
                </div>
            </div>

            <!-- Right Image Section (Desktop Only) -->
            <div class="hidden lg:block w-[50%] h-full relative py-[40px] pl-[40px]">
                <div class="w-full h-full relative overflow-hidden hero-image-clip bg-gray-100 rounded-[20px]">
                    <img src="{{ asset('images/ab7a55c40020dbf18750f61e6987c734559524fb.png') }}" alt="Mechanic Garage"
                        class="absolute inset-0 w-full h-full object-cover">

                    <!-- Secondary Button (Bottom Right of Image) -->
                    <div class="absolute bottom-0 right-10 z-20">
                        <div
                            class="inline-block p-[1px] bg-white/60 hero-btn-clip rounded-md shadow-xl transition-all duration-300 hover:-translate-y-2 hover:translate-x-2 hover:shadow-2xl">
                            <a href="#book"
                                class="inline-flex items-center justify-center w-full h-full bg-black/30 text-white text-sm font-semibold uppercase tracking-widest px-10 py-5 hover:bg-white hover:text-black transition-colors hero-btn-clip rounded-md">
                                BOOK YOUR CAR SERVICE
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Button (Sticky Bottom) -->
        <div class="lg:hidden absolute bottom-10 left-6 right-6 z-30">
            <div class="inline-block lg:w-full p-[1px] bg-white/40 hero-btn-clip rounded-md backdrop-blur-sm">
                <a href="#book"
                    class="inline-flex items-center justify-center lg:w-full bg-black/40 text-white text-sm font-bold uppercase tracking-widest px-8 py-4 hero-btn-clip rounded-md hover:bg-white hover:text-black transition-colors">
                    BOOK YOUR CAR SERVICE
                </a>
            </div>
        </div>
    </section>

    <!-- Fullscreen Menu Overlay -->


    <!-- Marquee Banner Section -->
    <section class="bg-primary text-white py-6 overflow-hidden border-t border-white/10 relative z-10">
        <div class="marquee-content">
            @php
                $marqueeItems = [
                    'DVSA-APPROVED MOT TESTING',
                    'FAST TURNAROUND',
                    '5-STAR RATING',
                    '12+ YEARS OF EXPERIENCE'
                ];
            @endphp

            @for ($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-16 px-8">
                    @foreach ($marqueeItems as $item)
                        <span
                            class="whitespace-nowrap font-geist font-bold text-[20px] lg:text-[24px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">{{ $item }}</span>
                        <img src="{{ asset('images/game-icons_car-wheel.svg') }}" alt="Car Wheel"
                            class="w-10 h-10 animate-spin-slow">
                    @endforeach
                </div>
            @endfor
        </div>
    </section>

    <!-- About Section -->
    <section class="bg-white py-16 lg:py-32 relative z-10">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-10">
            <div class="grid lg:grid-cols-2 gap-32 lg:gap-24 items-start">

                <!-- Left Column: Headline -->
                <div class="flex flex-col gap-6">
                    <span class="text-lg font-semibold uppercase tracking-tight text-[#0A0A0Ac2] font-geist">About
                        Simply Motoring</span>

                    <h2
                        class="text-[48px] lg:text-[96px] font-bold leading-[0.9] tracking-tighter uppercase font-geist opacity-0 typewriter-effect">
                        <span class="text-black">Car Care<br>Made Easy,</span><br>
                        <span class="text-primary">Hassle Free</span>
                    </h2>
                </div>

                <!-- Right Column: Content -->
                <div class="flex flex-col gap-8 text-lg font-medium leading-relaxed text-[#0A0A0A] lg:pt-14">
                    <p>
                        Your car deserves more than a quick tick-box check. That is why we focus on careful inspections,
                        clear communication, and work that supports how your vehicle actually performs on the road.
                    </p>
                    <p>
                        Every service is carried out with attention to safety, comfort, and reliability. Whether you are
                        booking an MOT or a full service, our approach stays the same. The result is a smoother drive,
                        fewer surprises, and greater confidence every time you get behind the wheel.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Services Menu Section -->
    <section class="bg-white relative z-10 w-full">
        <div class="w-full">
            <!-- Container with custom clip-path (chamfered corners) -->
            <div
                class="relative w-full overflow-hidden min-h-[700px] h-auto lg:h-screen flex items-center lg:rounded-3xl lg:[clip-path:polygon(80px_0,100%_0,100%_calc(100%_-_80px),calc(100%_-_80px)_100%,0_100%,0_80px)]">

                <!-- Background Image -->
                <img src="{{ asset('images/e0658670663b4326043443088ca927f3be988fd4.png') }}"
                    class="absolute inset-0 w-full h-full object-cover z-0" alt="Audi RS7 Background">

                <!-- Dark Gradient/Overlay -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent z-10"></div>
                <!-- Additional overall darkening -->
                <div class="absolute inset-0 bg-black/40 z-10"></div>

                <!-- Content Grid -->
                <div
                    class="relative z-20 w-full max-w-[1440px] mx-auto grid lg:grid-cols-2 gap-8 lg:gap-24 py-16 lg:py-20 px-6 lg:px-8 h-full items-center">

                    <!-- Left Column: Heading & Text -->
                    <div class="flex flex-col justify-between h-auto lg:h-[80%] gap-6 lg:gap-10">
                        <h2
                            class="text-white text-[48px] lg:text-[96px] font-bold uppercase leading-[0.9] font-geist tracking-tighter opacity-0 typewriter-effect">
                            Complete<br>Services<br>Under One<br>Roof
                        </h2>

                        <p class="text-white/90 text-lg lg:text-lg max-w-md font-medium leading-relaxed">
                            We keep car care simple, clear and hassle-free. From the moment you book to the moment you
                            drive
                            away, everything is designed to save you time and give you confidence in the work being
                            done.
                        </p>
                    </div>

                    <!-- Right Column: Services List -->
                    <div class="flex flex-col gap-3 lg:gap-2 w-full lg:max-w-xl ml-auto" x-data="{ 
                                active: null, 
                                shown: false,
                                services: [
                                    { 
                                        id: 1, 
                                        title: 'Full Service', 
                                        desc: 'A comprehensive yearly service that checks key systems, refreshes essentials, and helps keep your car reliable all year round.', 
                                        cta: 'Book Full Service' 
                                    },
                                    { 
                                        id: 2, 
                                        title: 'Interim Service', 
                                        desc: 'A lighter service focused on the main safety and maintenance essentials, ideal for mid-year checkups or higher mileage drivers.', 
                                        cta: 'Book Interim Service' 
                                    },
                                    { 
                                        id: 3, 
                                        title: 'Major Service', 
                                        desc: 'Our most in-depth service with extra checks and maintenance, designed for maximum peace of mind and long-term performance.', 
                                        cta: 'Book Major Service' 
                                    },
                                    { 
                                        id: 4, 
                                        title: 'Brake Discs & Pads', 
                                        desc: 'Inspection and replacement of worn brake discs and pads to restore safe, smooth stopping power and reduce brake noise or vibration.', 
                                        cta: 'Check Your Brakes' 
                                    },
                                    { 
                                        id: 5, 
                                        title: 'Brake Fluid Change', 
                                        desc: 'Replacement of old brake fluid to help maintain braking response, protect components, and keep the braking system performing properly.', 
                                        cta: 'Book Brake Fluid Change' 
                                    }
                                ]
                            }" x-init="
                                const observer = new IntersectionObserver((entries) => {
                                    if (entries[0].isIntersecting) {
                                        shown = true;
                                        if (window.innerWidth >= 1024) {
                                            setTimeout(() => {
                                                if (shown) active = 1;
                                            }, 800);
                                        }
                                    }
                                }, { threshold: 0.1 });
                                observer.observe($el);
                            ">
                        <template x-for="(service, index) in services" :key="service.id">
                            <div @mouseenter="window.innerWidth >= 1024 ? active = service.id : null"
                                @click="window.innerWidth < 1024 ? (active === service.id ? active = null : active = service.id) : null"
                                class="rounded-xl overflow-hidden transition-all duration-700 ease-out border border-white/5 backdrop-blur-md"
                                :class="[
                                            active === service.id ? 'bg-black/40 lg:bg-white/10' : 'bg-white/10 hover:bg-white/20 hover:border-white/10 cursor-pointer',
                                            shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-24'
                                        ]" :style="`transition-delay: ${shown ? index * 100 : 0}ms`">

                                <div class="flex items-center px-6 py-5 lg:p-5 transition-all duration-500 ease-in-out"
                                    :class="active === service.id ? 'lg:px-8 lg:py-4 lg:mb-3 gap-4 lg:gap-0' : 'justify-between'">

                                    <span class="font-bold text-xl lg:text-2xl transition-colors duration-500 font-geist"
                                        :class="active === service.id ? 'text-white/50' : 'text-white/40 group-hover:text-white/60'"
                                        x-text="'0' + service.id"></span>

                                    <!-- Spacer for desktop layout -->
                                    <div class="hidden lg:block transition-all duration-500 ease-in-out h-px"
                                        :class="active === service.id ? 'w-4 flex-none' : 'flex-1'"></div>

                                    <h3 class="text-white font-bold uppercase font-geist tracking-tight transition-all duration-500 whitespace-nowrap text-lg lg:text-2xl text-right lg:text-left"
                                        x-text="service.title"></h3>
                                </div>

                                <div class="grid transition-[grid-template-rows] duration-500 ease-in-out"
                                    :class="active === service.id ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'">
                                    <div class="overflow-hidden">
                                        <div class="flex flex-col opacity-0 transition-opacity duration-500 delay-100"
                                            :class="active === service.id ? 'opacity-100' : 'opacity-0'">
                                            <p class="px-6 lg:px-8 mb-6 text-white/80 text-base leading-relaxed font-medium"
                                                x-text="service.desc"></p>

                                            <button
                                                class="w-full bg-primary hover:bg-[#ff5500] text-white font-bold py-4 px-6 uppercase tracking-wide transition-all shadow-lg text-sm"
                                                x-text="service.cta">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-10 lg:py-20 bg-white relative z-10 overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-10">
            <!-- Header -->
            <div class="flex flex-col items-center text-center mb-16">
                <span
                    class="bg-primary text-white px-4 py-1.5 rounded-full mb-6 font-geist font-medium text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">Pricing</span>
                <h2
                    class="font-geist font-semibold text-[48px] lg:text-[96px] leading-[0.83] tracking-[-0.06em] text-center uppercase [leading-trim:CAP_HEIGHT] text-primary mb-8 typewriter-effect">
                    Service Worth<br>Every Penny
                </h2>
                <p class="text-[#0A0A0A] font-medium text-lg leading-[1.26] max-w-sm">
                    Upfront pricing with no hidden fees. Simply Motoring is your go-to car services and MOT testing
                    centre.
                </p>
            </div>

            <!-- Carousel Container -->
            <div x-data="{
                        active: 0,
                        cardWidth: 0,
                        gap: 24,
                        transitioning: true,
                        paused: false,
                        updateWidth() {
                            const card = this.$el.querySelector('.pricing-card');
                            if (card) this.cardWidth = card.offsetWidth;
                        },
                        init() {
                            // Small delay to ensure DOM is ready
                            setTimeout(() => this.updateWidth(), 100);
                            window.addEventListener('resize', () => this.updateWidth());
                            setInterval(() => {
                                if (!this.paused) this.next();
                            }, 3000);
                        },
                        next() {
                            this.transitioning = true;
                            this.active++;
                            if (this.active === 4) {
                                setTimeout(() => {
                                    this.transitioning = false;
                                    this.active = 0;
                                }, 700);
                            }
                        }
                    }" class="w-[calc(100vw-3rem)] overflow-hidden" @mouseenter="paused = true"
                @mouseleave="paused = false">
                <div class="flex gap-6" :class="transitioning ? 'transition-transform duration-700 ease-in-out' : ''"
                    :style="'transform: translateX(-' + (active * (cardWidth + gap)) + 'px)'">
                    <!-- Group 1 -->
                    <div class="flex gap-6 shrink-0">
                        <!-- Card 1: Interim + MOT (Orange) -->
                        <div class="pricing-card flex flex-col bg-primary text-white rounded-tr-[16px] rounded-bl-[16px] overflow-hidden relative group h-full shadow-lg hover:shadow-xl transition-shadow duration-300 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0"
                            style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                            <!-- Main Content -->
                            <div class="p-8 pb-12 flex-1 flex flex-col">
                                <div
                                    class="inline-block border text-white/80 border-white rounded-full px-4 py-1.5 text-[16px] font-medium uppercase tracking-[-0.06em] leading-[1.26] mb-8 w-fit">
                                    Interim Service + MOT Test
                                </div>
                                <div class="flex items-baseline gap-1 mb-10">
                                    <span
                                        class="text-[16px] font-medium tracking-[-0.06em] leading-[0.83] uppercase">From</span>
                                    <span
                                        class="text-[64px] font-semibold uppercase leading-[0.83] tracking-[-0.06em]">£120</span>
                                </div>

                                <ul class="flex flex-col mt-auto -mx-8">
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        MOT +77 point, 12,000 miles inspection
                                    </li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        A more detailed maintenance option than an oil and filter change
                                    </li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Save when booking together, on the same day or different days
                                    </li>
                                </ul>
                            </div>

                            <!-- Footer / Button -->
                            <button
                                class="h-[70px] flex items-stretch border-t border-white/50 hover:bg-black/20 transition-colors cursor-pointer group/btn mt-auto">
                                <div
                                    class="flex-1 flex items-center px-8 font-semibold text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">
                                    Book Now
                                </div>
                                <div
                                    class="w-[100px] relative flex items-center justify-center border-l border-white/50 origin-bottom">
                                    <i
                                        class="fa-solid fa-arrow-right -rotate-0 group-hover/btn:-rotate-45 transition-transform duration-300 text-lg"></i>
                                </div>
                            </button>
                        </div>

                        <!-- Card 2: MOT Test (Light Gray) -->
                        <div class="flex flex-col bg-[#F0F0F0] text-black rounded-tr-[16px] rounded-bl-[16px] overflow-hidden relative group h-full shadow-sm hover:shadow-lg transition-shadow duration-300 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0"
                            style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                            <!-- Main Content -->
                            <div class="p-8 pb-12 flex-1 flex flex-col">
                                <div
                                    class="inline-block border text-black/80 border-black rounded-full px-4 py-1.5 text-[16px] font-medium uppercase tracking-[-0.06em] leading-[1.26] mb-8 w-fit bg-transparent">
                                    MOT Test
                                </div>
                                <div class="flex items-baseline gap-1 mb-10">
                                    <span
                                        class="text-[16px] font-medium tracking-[-0.06em] leading-[0.83] uppercase">From</span>
                                    <span
                                        class="text-[64px] font-semibold uppercase leading-[0.83] tracking-[-0.06em]">£45</span>
                                </div>

                                <ul class="flex flex-col mt-auto -mx-8">
                                    <li
                                        class="py-4 px-8 border-t border-black/10 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-black/80">
                                        25 point inspection
                                    </li>
                                    <li
                                        class="py-4 px-8 border-t border-black/10 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-black/80">
                                        Takes 45-60 minutes
                                    </li>
                                    <li
                                        class="py-4 px-8 border-t border-black/10 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-black/80">
                                        MOT Results
                                    </li>
                                </ul>
                            </div>

                            <!-- Footer / Button -->
                            <button
                                class="h-[70px] flex items-stretch border-t border-black/50 hover:bg-black/10 transition-colors cursor-pointer group/btn mt-auto text-black">
                                <div
                                    class="flex-1 flex items-center px-8 font-semibold text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">
                                    Book Now
                                </div>
                                <div
                                    class="w-[100px] relative flex items-center justify-center border-l border-black/50 origin-bottom">
                                    <i
                                        class="fa-solid fa-arrow-right -rotate-0 group-hover/btn:-rotate-45 transition-transform duration-300 text-lg"></i>
                                </div>
                            </button>
                        </div>

                        <!-- Card 3: Pre-MOT Check (Black) -->
                        <div class="flex flex-col bg-black text-white rounded-tr-[16px] rounded-bl-[16px] overflow-hidden relative group h-full shadow-lg hover:shadow-xl transition-shadow duration-300 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0"
                            style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                            <!-- Main Content -->
                            <div class="p-8 pb-12 flex-1 flex flex-col">
                                <div
                                    class="inline-block border text-white/80 border-white rounded-full px-4 py-1.5 text-[16px] font-medium uppercase tracking-[-0.06em] leading-[1.26] mb-8 w-fit bg-transparent">
                                    Pre-MOT Check
                                </div>
                                <div class="flex items-baseline gap-1 mb-10">
                                    <span
                                        class="text-[16px] font-medium tracking-[-0.06em] leading-[0.83] uppercase">From</span>
                                    <span
                                        class="text-[64px] font-semibold uppercase leading-[0.83] tracking-[-0.06em]">£10</span>
                                </div>

                                <ul class="flex flex-col mt-auto -mx-8">
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Overall MOT inspection
                                    </li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Takes 30 minutes
                                    </li>
                                </ul>
                            </div>

                            <!-- Footer / Button -->
                            <button
                                class="h-[70px] flex items-stretch border-t border-white/50 hover:bg-white/20 transition-colors cursor-pointer group/btn mt-auto text-white">
                                <div
                                    class="flex-1 flex items-center px-8 font-semibold text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">
                                    Book Now
                                </div>
                                <div
                                    class="w-[100px] relative flex items-center justify-center border-l border-white/50 origin-bottom">
                                    <i
                                        class="fa-solid fa-arrow-right -rotate-0 group-hover/btn:-rotate-45 transition-transform duration-300 text-lg"></i>
                                </div>
                            </button>
                        </div>

                        <!-- Card 4: Full Service (Orange - Placeholder based on design pattern) -->
                        <div class="flex flex-col bg-primary text-white rounded-tr-[16px] rounded-bl-[16px] overflow-hidden relative group h-full shadow-lg hover:shadow-xl transition-shadow duration-300 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0"
                            style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                            <!-- Main Content -->
                            <div class="p-8 pb-12 flex-1 flex flex-col">
                                <div
                                    class="inline-block border text-white/80 border-white rounded-full px-4 py-1.5 text-[16px] font-medium uppercase tracking-[-0.06em] leading-[1.26] mb-8 w-fit">
                                    Full Service
                                </div>
                                <div class="flex items-baseline gap-1 mb-10">
                                    <span
                                        class="text-[16px] font-medium tracking-[-0.06em] leading-[0.83] uppercase">From</span>
                                    <span
                                        class="text-[64px] font-semibold uppercase leading-[0.83] tracking-[-0.06em]">£120</span>
                                </div>

                                <ul class="flex flex-col mt-auto -mx-8">
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Standard MOT
                                    </li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        A more detailed maintenance
                                    </li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Option than an oil and filter change
                                    </li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Save when booking together
                                    </li>
                                </ul>
                            </div>

                            <!-- Footer / Button -->
                            <button
                                class="h-[70px] flex items-stretch border-t border-white/50 hover:bg-black/20 transition-colors cursor-pointer group/btn mt-auto">
                                <div
                                    class="flex-1 flex items-center px-8 font-semibold text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">
                                    Book Now
                                </div>
                                <div
                                    class="w-[100px] relative flex items-center justify-center border-l border-white/50 origin-bottom">
                                    <i
                                        class="fa-solid fa-arrow-right -rotate-0 group-hover/btn:-rotate-45 transition-transform duration-300 text-lg"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Group 2 (Duplicate for Loop) -->
                    <div class="flex gap-6 shrink-0" aria-hidden="true">
                        <!-- Duplicate content of Group 1 exactly -->
                        <!-- Card 1 Duplicate -->
                        <div class="flex flex-col bg-primary text-white rounded-tr-[16px] rounded-bl-[16px] overflow-hidden relative group h-full shadow-lg hover:shadow-xl transition-shadow duration-300 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0"
                            style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                            <!-- Main Content -->
                            <div class="p-8 pb-12 flex-1 flex flex-col">
                                <div
                                    class="inline-block border text-white/80 border-white rounded-full px-4 py-1.5 text-[16px] font-medium uppercase tracking-[-0.06em] leading-[1.26] mb-8 w-fit">
                                    Interim Service + MOT Test
                                </div>
                                <div class="flex items-baseline gap-1 mb-10">
                                    <span
                                        class="text-[16px] font-medium tracking-[-0.06em] leading-[0.83] uppercase">From</span>
                                    <span
                                        class="text-[64px] font-semibold uppercase leading-[0.83] tracking-[-0.06em]">£120</span>
                                </div>
                                <ul class="flex flex-col mt-auto -mx-8">
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        MOT +77 point, 12,000 miles inspection</li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        A more detailed maintenance option than an oil and filter change</li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Save when booking together, on the same day or different days</li>
                                </ul>
                            </div>
                            <button
                                class="h-[70px] flex items-stretch border-t border-white/50 hover:bg-black/20 transition-colors cursor-pointer group/btn mt-auto">
                                <div
                                    class="flex-1 flex items-center px-8 font-semibold text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">
                                    Book Now</div>
                                <div
                                    class="w-[100px] relative flex items-center justify-center border-l border-white/50 origin-bottom">
                                    <i
                                        class="fa-solid fa-arrow-right -rotate-0 group-hover/btn:-rotate-45 transition-transform duration-300 text-lg"></i>
                                </div>
                            </button>
                        </div>
                        <!-- Card 2 Duplicate -->
                        <div class="flex flex-col bg-[#F0F0F0] text-black rounded-tr-[16px] rounded-bl-[16px] overflow-hidden relative group h-full shadow-sm hover:shadow-lg transition-shadow duration-300 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0"
                            style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                            <div class="p-8 pb-12 flex-1 flex flex-col">
                                <div
                                    class="inline-block border text-black/80 border-black rounded-full px-4 py-1.5 text-[16px] font-medium uppercase tracking-[-0.06em] leading-[1.26] mb-8 w-fit bg-transparent">
                                    MOT Test</div>
                                <div class="flex items-baseline gap-1 mb-10">
                                    <span
                                        class="text-[16px] font-medium tracking-[-0.06em] leading-[0.83] uppercase">From</span>
                                    <span
                                        class="text-[64px] font-semibold uppercase leading-[0.83] tracking-[-0.06em]">£45</span>
                                </div>
                                <ul class="flex flex-col mt-auto -mx-8">
                                    <li
                                        class="py-4 px-8 border-t border-black/10 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-black/80">
                                        25 point inspection</li>
                                    <li
                                        class="py-4 px-8 border-t border-black/10 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-black/80">
                                        Takes 45-60 minutes</li>
                                    <li
                                        class="py-4 px-8 border-t border-black/10 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-black/80">
                                        MOT Results</li>
                                </ul>
                            </div>
                            <button
                                class="h-[70px] flex items-stretch border-t border-black/50 hover:bg-black/10 transition-colors cursor-pointer group/btn mt-auto text-black">
                                <div
                                    class="flex-1 flex items-center px-8 font-semibold text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">
                                    Book Now</div>
                                <div
                                    class="w-[100px] relative flex items-center justify-center border-l border-black/50 origin-bottom">
                                    <i
                                        class="fa-solid fa-arrow-right -rotate-0 group-hover/btn:-rotate-45 transition-transform duration-300 text-lg"></i>
                                </div>
                            </button>
                        </div>
                        <!-- Card 3 Duplicate -->
                        <div class="flex flex-col bg-black text-white rounded-tr-[16px] rounded-bl-[16px] overflow-hidden relative group h-full shadow-lg hover:shadow-xl transition-shadow duration-300 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0"
                            style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                            <div class="p-8 pb-12 flex-1 flex flex-col">
                                <div
                                    class="inline-block border text-white/80 border-white rounded-full px-4 py-1.5 text-[16px] font-medium uppercase tracking-[-0.06em] leading-[1.26] mb-8 w-fit bg-transparent">
                                    Pre-MOT Check</div>
                                <div class="flex items-baseline gap-1 mb-10">
                                    <span
                                        class="text-[16px] font-medium tracking-[-0.06em] leading-[0.83] uppercase">From</span>
                                    <span
                                        class="text-[64px] font-semibold uppercase leading-[0.83] tracking-[-0.06em]">£10</span>
                                </div>
                                <ul class="flex flex-col mt-auto -mx-8">
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Overall MOT inspection</li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Takes 30 minutes</li>
                                </ul>
                            </div>
                            <button
                                class="h-[70px] flex items-stretch border-t border-white/50 hover:bg-white/20 transition-colors cursor-pointer group/btn mt-auto text-white">
                                <div
                                    class="flex-1 flex items-center px-8 font-semibold text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">
                                    Book Now</div>
                                <div
                                    class="w-[100px] relative flex items-center justify-center border-l border-white/50 origin-bottom">
                                    <i
                                        class="fa-solid fa-arrow-right -rotate-0 group-hover/btn:-rotate-45 transition-transform duration-300 text-lg"></i>
                                </div>
                            </button>
                        </div>
                        <!-- Card 4 Duplicate -->
                        <div class="flex flex-col bg-primary text-white rounded-tr-[16px] rounded-bl-[16px] overflow-hidden relative group h-full shadow-lg hover:shadow-xl transition-shadow duration-300 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0"
                            style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                            <div class="p-8 pb-12 flex-1 flex flex-col">
                                <div
                                    class="inline-block border text-white/80 border-white rounded-full px-4 py-1.5 text-[16px] font-medium uppercase tracking-[-0.06em] leading-[1.26] mb-8 w-fit">
                                    Full Service</div>
                                <div class="flex items-baseline gap-1 mb-10">
                                    <span
                                        class="text-[16px] font-medium tracking-[-0.06em] leading-[0.83] uppercase">From</span>
                                    <span
                                        class="text-[64px] font-semibold uppercase leading-[0.83] tracking-[-0.06em]">£120</span>
                                </div>
                                <ul class="flex flex-col mt-auto -mx-8">
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Standard MOT</li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        A more detailed maintenance</li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Option than an oil and filter change</li>
                                    <li
                                        class="py-4 px-8 border-t border-white/20 text-[20px] font-medium leading-[1.26] tracking-[-0.06em] text-white/80">
                                        Save when booking together</li>
                                </ul>
                            </div>
                            <button
                                class="h-[70px] flex items-stretch border-t border-white/50 hover:bg-black/20 transition-colors cursor-pointer group/btn mt-auto">
                                <div
                                    class="flex-1 flex items-center px-8 font-semibold text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">
                                    Book Now</div>
                                <div
                                    class="w-[100px] relative flex items-center justify-center border-l border-white/50 origin-bottom">
                                    <i
                                        class="fa-solid fa-arrow-right -rotate-0 group-hover/btn:-rotate-45 transition-transform duration-300 text-lg"></i>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="bg-black py-10 lg:py-20 relative overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-10 relative z-10 w-full">
            <!-- Top Badge -->
            <div class="flex justify-center relative mb-8 z-10">
                <span
                    class="bg-primary text-white px-5 py-2 rounded-full font-geist font-medium text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT] inline-block">Process</span>
            </div>

            <!-- Header Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end relative mb-10 lg:mb-20">

                <!-- Left Title -->
                <div class="col-span-12 lg:col-span-5 relative z-10">
                    <h2
                        class="font-geist font-bold lg:text-[96px] text-[48px] leading-[0.85] tracking-[-0.04em] uppercase text-white mb-2 typewriter-effect">
                        <span class="text-primary block">Book It.</span>
                        <span class="text-primary block">Bring It.</span>
                        <span class="block">Drive Away</span>
                        <span class="block">Happier.</span>
                    </h2>
                </div>

                <!-- Description (Aligned Bottom) -->
                <div class="col-span-12 lg:col-span-4 flex flex-col items-start pb-2">
                    <p class="text-white/70 text-lg leading-[1.26] max-w-[350px] text-left">
                        We keep car care simple, clear and hassle-free. From the moment you book to the moment you drive
                        away, everything is designed to save you time and give you confidence in the work being done.
                    </p>
                </div>

                <!-- Right Tyre Image -->
                <div class="absolute right-[-28%] top-[-10%] z-0 h-full w-full pointer-events-none hidden lg:block">
                    <img src="{{ asset('images/f2f7228f49e6c162f221a17ef06dbce97e4b5b88.png') }}" alt="Tyre"
                        class="absolute right-0 top-0 h-[800px] w-auto max-w-none object-contain animate-[spin_40s_linear_infinite]">
                </div>

            </div>

            <!-- Cards Grid -->
            <div x-data="{
                        show: false,
                        init() {
                            const observer = new IntersectionObserver((entries) => {
                                entries.forEach(entry => {
                                    this.show = entry.isIntersecting;
                                });
                            }, { threshold: 0.2 });
                            observer.observe(this.$el);
                        }
                    }" class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10 h-full items-stretch">
                <!-- Card 1: Pick Your Service -->
                <div class="bg-[#1A103C] rounded-[24px] p-6 lg:p-10 min-h-[200px] lg:min-h-[300px] flex flex-col justify-between group hover:bg-[#241654] transition-all duration-700 ease-out relative overflow-hidden"
                    :class="show ? 'translate-x-0 opacity-100' : '-translate-x-[10%] opacity-0'">
                    <div class="flex justify-between items-start lg:mb-8 mb-4 z-10 relative">
                        <h3
                            class="text-white font-semibold text-[24px] lg:text-[32px] leading-[1.1] uppercase tracking-[-0.06em]">
                            Pick Your Service</h3>
                    </div>
                    <p class="text-white/70 text-lg leading-[1.26] mt-auto z-10 relative lg:max-w-[90%] max-w-[100%]">
                        Choose MOT, interim/full service, or tell us what's going on. Book online in minutes or call if
                        you'd rather talk it through.
                    </p>
                </div>

                <!-- Card 2: We Check & Explain -->
                <div class="bg-primary/70 rounded-[24px] p-10 min-h-[200px] lg:min-h-[300px] flex flex-col justify-between transition-all duration-700 delay-100 ease-out relative overflow-hidden z-20"
                    :class="show ? 'translate-x-0 rotate-0 opacity-100' : '-translate-x-[105%] -rotate-2 opacity-0'">
                    <div class="flex justify-between items-start lg:mb-8 mb-4 z-10 relative">
                        <h3
                            class="text-white font-semibold text-[24px] lg:text-[32px] leading-[1.1] uppercase tracking-[-0.06em]">
                            We Check & Explain</h3>
                    </div>
                    <p class="text-white/80 text-lg leading-[1.26] mt-auto z-10 relative lg:max-w-[90%] max-w-[100%]">
                        We carry out the checks and discuss your options. If anything needs doing, you'll know what it
                        is, why it matters, and what it'll cost.
                    </p>
                </div>

                <!-- Card 3: Back On The Road -->
                <div class="bg-white rounded-[24px] p-10 min-h-[200px] lg:min-h-[300px] flex flex-col justify-between group hover:bg-gray-50 transition-all duration-700 delay-200 ease-out relative overflow-hidden"
                    :class="show ? 'translate-x-0 rotate-0 opacity-100' : '-translate-x-[210%] -rotate-4 opacity-0'">
                    <div class="flex justify-between items-start lg:mb-8 mb-4 z-10 relative">
                        <h3
                            class="text-black font-semibold text-[24px] lg:text-[32px] leading-[1.1] uppercase tracking-[-0.06em]">
                            Back On The Road</h3>
                    </div>
                    <p class="text-black/70 text-lg leading-[1.26] mt-auto z-10 relative lg:max-w-[90%] max-w-[100%]">
                        Once it's sorted, we get the work done and hand your car back ready to drive. Quick turnaround,
                        smooth experience, and no unnecessary delays.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quality Section -->
    <section
        class="bg-black relative z-10 w-full lg:rounded-bl-[32px] lg:[clip-path:polygon(0_0,100%_0,100%_calc(100%_-_80px),calc(100%_-_80px)_100%,0_100%,0_0)]">
        <div class="w-full">
            <!-- Container with custom clip-path -->
            <div
                class="relative py-10 w-full overflow-hidden min-h-[700px] lg:min-h-[800px] flex lg:items-center lg:rounded-[32px] lg:[clip-path:polygon(80px_0,100%_0,100%_calc(100%_-_80px),calc(100%_-_80px)_100%,0_100%,0_80px)]">

                <!-- Background Video -->
                <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0">
                    <source src="{{ asset('images/7565186-hd_1366_720_25fps.mp4') }}" type="video/mp4">
                </video>

                <!-- Dark Overlay -->
                <div class="absolute inset-0 bg-black/50 z-10"></div>

                <!-- Content Container -->
                <div class="relative z-20 w-full max-w-[1440px] mx-auto h-full flex items-center justify-end p-8 lg:p-20">

                    <!-- Glass Content Card -->
                    <div
                        class="bg-white/10 backdrop-blur-sm lg:backdrop-blur-xl p-6 lg:p-8 rounded-[20px] max-w-[586px] w-full">
                        <h2
                            class="font-geist font-bold text-[36px] lg:text-[90px] leading-[0.85] tracking-[-0.04em] uppercase text-white mb-10 lg:mb-20 typewriter-effect">
                            Quality In Every Check
                        </h2>

                        <!-- Orange Square -->
                        <div class="w-12 h-12 bg-primary mb-10 lg:mb-20"></div>

                        <p class="text-white/80 text-lg lg:text-xl leading-relaxed font-medium">
                            At Simply Motoring, every vehicle is handled with care, attention, and experience. We focus
                            on doing the job properly, keeping you informed, and ensuring work is completed to a
                            standard every time you visit.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="bg-white py-10 lg:py-20 relative z-10 w-full overflow-hidden border-b border-black/10">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-20" x-data="{
                        activeReview: 0,
                        isLoading: true,
                        reviews: [
                            {
                                quote: 'Fantastic all around. My Ford Kuga failed its MOT, but they got all the required work done the same day and had me back on the road quickly',
                                name: 'Jason Adams',
                                image: 'https://ui-avatars.com/api/?name=Jason+Adams&background=e0e0e0&color=333',
                                stars: 5,
                                location: 'Local Customer'
                            },
                            {
                                quote: 'The team at Simply Motoring kept me informed throughout the entire service. Transparent pricing and no hidden costs. Highly recommended!',
                                name: 'Sarah Jenkins',
                                image: 'https://ui-avatars.com/api/?name=Sarah+Jenkins&background=e0e0e0&color=333',
                                stars: 5,
                                location: 'Verified Review'
                            },
                            {
                                quote: 'Quick, efficient, and friendly. I\'ve been bringing my cars here for years and they always do a top-notch job. The best in the area.',
                                name: 'Mike Thompson',
                                image: 'https://ui-avatars.com/api/?name=Mike+Thompson&background=e0e0e0&color=333',
                                stars: 5,
                                location: 'Local Customer'
                            }
                        ],
                        next() {
                            this.activeReview = (this.activeReview + 1) % this.reviews.length;
                        },
                        prev() {
                            this.activeReview = (this.activeReview - 1 + this.reviews.length) % this.reviews.length;
                        },
                        async init() {
                            try {
                                const response = await fetch('/api/reviews');
                                if (!response.ok) throw new Error('API fetch failed');

                                const data = await response.json();
                                // Assuming API returns { reviews: [...] } or just [...]
                                const reviewList = data.reviews || data; 

                                if (Array.isArray(reviewList) && reviewList.length > 0) {
                                    this.reviews = reviewList.map(r => ({
                                        quote: r.text || r.quote || 'No review text provided.',
                                        name: r.author_name || r.name || 'Anonymous',
                                        image: r.profile_photo_url || r.image || `https://ui-avatars.com/api/?name=${encodeURIComponent(r.author_name || 'A')}&background=e0e0e0&color=333`,
                                        stars: r.rating || r.stars || 5,
                                        location: r.relative_time_description || 'Google Review'
                                    }));
                                }
                            } catch (error) {
                                console.warn('Using static reviews fallback:', error);
                            } finally {
                                this.isLoading = false;
                            }
                        }
                    }">
            <!-- Badge -->
            <div class="mb-16">
                <span
                    class="bg-primary text-white px-5 py-2 rounded-full font-geist font-medium text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT] inline-block">
                    Driven By Great Reviews
                </span>
            </div>

            <!-- Review Content -->
            <div class="relative min-h-[400px] flex flex-col justify-between">

                <!-- Quote Icon & Text -->
                <div class="relative">
                    <i class="fa-solid fa-quote-left text-black text-[48px] mb-8 block leading-none"></i>

                    <style>
                        .custom-scrollbar::-webkit-scrollbar {
                            width: 6px;
                            background-color: transparent;
                        }

                        .custom-scrollbar:hover::-webkit-scrollbar {
                            background-color: #f1f1f1;
                        }

                        .custom-scrollbar::-webkit-scrollbar-thumb {
                            background-color: transparent;
                            border-radius: 9999px;
                        }

                        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
                            background-color: #FB5200;
                        }
                    </style>
                    <div class="relative h-[200px] lg:h-[220px] overflow-y-auto custom-scrollbar scroll-smooth pr-4">
                        <template x-for="(review, index) in reviews" :key="index">
                            <div x-show="activeReview === index"
                                x-transition:enter="transition ease-out duration-500 delay-100"
                                x-transition:enter-start="opacity-0 translate-y-8"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-300 absolute top-0 w-full"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-8" class="absolute w-full top-0 left-0">
                                <p class="font-geist font-medium text-[28px] lg:text-[48px] leading-[1.2] tracking-[-0.06em] text-[0A0A0A] max-w-full cursor-default"
                                    x-text="review.quote">
                                </p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer: User Info & Navigation -->
                <div class="flex items-end justify-between mt-8 pt-8 relative z-20">

                    <!-- User Profile -->
                    <div class="flex lg:flex-row flex-col lg:items-center lg:gap-6 gap-2">
                        <!-- User Image Container -->
                        <div class="w-16 h-16 relative rounded-[16px] overflow-hidden bg-gray-100">
                            <template x-for="(review, index) in reviews" :key="index">
                                <img x-show="activeReview === index" :src="review.image" alt="Reviewer"
                                    class="absolute w-full h-full object-cover transition-opacity duration-500"
                                    x-transition:enter="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="opacity-0 scale-90 absolute">
                            </template>
                        </div>

                        <div class="flex flex-col">
                            <template x-for="(review, index) in reviews" :key="index">
                                <div x-show="activeReview === index"
                                    x-transition:enter="transition ease-out duration-500 delay-100"
                                    x-transition:enter-start="opacity-0 translate-x-4"
                                    x-transition:enter-end="opacity-100 translate-x-0" class="flex flex-col">
                                    <h4 class="font-geist font-bold text-lg text-black mb-1" x-text="review.name"></h4>
                                    <div class="flex gap-1 text-[#FB5200] text-sm">
                                        <template x-for="i in 5">
                                            <i class="fa-solid fa-star text-xs"></i>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="flex lg:gap-10 gap-4 items-center">
                        <button @click="prev()"
                            class="font-geist font-bold text-sm tracking-widest uppercase text-black/40 hover:text-black transition-colors">
                            Prev
                        </button>
                        <button @click="next()"
                            class="font-geist font-bold text-sm tracking-widest uppercase text-black hover:text-black transition-colors">
                            Next
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="bg-white lg:py-20 py-10 w-full border-b border-black/10">
        <div class="max-w-[1440px] mx-auto lg:px-4 px-6">
            <div
                class="grid grid-cols-2 md:grid-cols-4 gap-x-12 lg:gap-y-12 gap-y-6 items-center justify-items-center transition-all duration-500">
                <!-- Row 1 -->
                <img src="/images/partners/139d259a0cfa0e10acaa5b198802930f69a527f4.png" alt="Partner 1"
                    class="h-[90px] w-auto object-contain hover:scale-105 transition-all duration-300 opacity-40 hover:opacity-100 grayscale hover:grayscale-0">
                <img src="/images/partners/37be3654173c53b83f5f7104fee74a7056a62058.png" alt="Partner 2"
                    class="h-[90px] w-auto object-contain hover:scale-105 transition-all duration-300 opacity-40 hover:opacity-100 grayscale hover:grayscale-0">
                <img src="/images/partners/66b14bc5b51d3fce57b19bb133d26ae265f3b362.png" alt="Partner 3"
                    class="h-[90px] w-auto object-contain hover:scale-105 transition-all duration-300 opacity-40 hover:opacity-100 grayscale hover:grayscale-0">
                <img src="/images/partners/76c87e9f0b8a7ff28b44bf159765b78f47b7548a.png" alt="Partner 4"
                    class="h-[90px] w-auto object-contain hover:scale-105 transition-all duration-300 opacity-40 hover:opacity-100 grayscale hover:grayscale-0">

                <!-- Row 2 -->
                <img src="/images/partners/8646307f9e8a241e53f2525813b627920bcd86e7.png" alt="Partner 5"
                    class="h-[90px] w-auto object-contain hover:scale-105 transition-all duration-300 opacity-40 hover:opacity-100 grayscale hover:grayscale-0">
                <img src="/images/partners/95279ac34816d841b2573c342ad986382ba43c77.png" alt="Partner 6"
                    class="h-[90px] w-auto object-contain hover:scale-105 transition-all duration-300 opacity-40 hover:opacity-100 grayscale hover:grayscale-0">
                <img src="/images/partners/bd802aa77d6ba5e891e0b2fc7156feef79d5ff26.png" alt="Partner 7"
                    class="h-[90px] w-auto object-contain hover:scale-105 transition-all duration-300 opacity-40 hover:opacity-100 grayscale hover:grayscale-0">
                <img src="/images/partners/ddc26cc794a617eaa62bff812b71f3ba209e4d65.png" alt="Partner 8"
                    class="h-[90px] w-auto object-contain hover:scale-105 transition-all duration-300 opacity-40 hover:opacity-100 grayscale hover:grayscale-0">
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="bg-white py-10 lg:py-20 w-full relative overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-20 relative z-10 h-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start h-full">

                <!-- Left Content -->
                <div class="w-full lg:pt-10 lg:col-span-8">
                    <div class="flex flex-col md:flex-row gap-8 lg:gap-12 items-start">
                        <!-- Badge Column -->
                        <div class="flex-shrink-0">
                            <span
                                class="bg-primary text-white px-5 py-2 rounded-full font-geist font-medium text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT] inline-block">
                                FAQS
                            </span>
                        </div>

                        <!-- Content Column -->
                        <div class="flex-1 w-full">
                            <!-- Heading -->
                            <h2
                                class="font-geist font-bold text-[40px] lg:text-[64px] leading-[0.95] tracking-tight text-black lg:mb-16 mb-8 uppercase typewriter-effect">
                                What Our Client<br>Usually Asks
                            </h2>

                            <!-- Accordion -->
                            <div x-data="{ active: 1 }" class="space-y-0 border-t border-black/10">

                                <!-- Item 1 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 1 ? null : 1)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">How long does car
                                            service
                                            take?</span>
                                        <div class="relative w-10 h-10 flex-shrink-0">
                                            <div class="absolute inset-0 transition-all duration-300 rounded-md"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 1 ? 'bg-primary' : 'bg-black'">
                                            </div>
                                            <div class="absolute inset-[2px] flex items-center justify-center rounded-md transition-all duration-300"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 1 ? 'bg-black' : 'bg-primary'">
                                                <i class="fa-solid fa-caret-up transition-colors duration-300"
                                                    :class="active === 1 ? 'text-primary rotate-0' : 'text-black rotate-180'"></i>
                                            </div>
                                        </div>
                                    </button>
                                    <div x-show="active === 1" x-collapse>
                                        <p class="mt-4 text-[#0A0A0A] lg:text-lg text-base leading-relaxed max-w-lg">
                                            Typically, a full car service takes 3–4 hours. However, this may vary
                                            depending on
                                            what your car needs help with.
                                        </p>
                                    </div>
                                </div>

                                <!-- Item 2 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 2 ? null : 2)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">How often should
                                            you
                                            Service your Car?</span>
                                        <div class="relative w-10 h-10 flex-shrink-0">
                                            <div class="absolute inset-0 rounded-md transition-all duration-300"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 2 ? 'bg-primary' : 'bg-black'">
                                            </div>
                                            <div class="absolute inset-[2px] flex items-center justify-center rounded-md transition-all duration-300"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 2 ? 'bg-black' : 'bg-primary'">
                                                <i class="fa-solid fa-caret-up transition-colors duration-300"
                                                    :class="active === 2 ? 'text-primary rotate-0' : 'text-black rotate-180'"></i>
                                            </div>
                                        </div>
                                    </button>
                                    <div x-show="active === 2" x-collapse>
                                        <p class="mt-4 text-[#0A0A0A] lg:text-lg text-base leading-relaxed max-w-lg">
                                            To avoid major issues, we recommend you get a full car service annually or
                                            after
                                            every 12,000 miles.
                                        </p>
                                    </div>
                                </div>

                                <!-- Item 3 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 3 ? null : 3)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">What does a Car
                                            Service
                                            include?</span>
                                        <div class="relative w-10 h-10 flex-shrink-0">
                                            <div class="absolute inset-0 rounded-md transition-all duration-300"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 3 ? 'bg-primary' : 'bg-black'">
                                            </div>
                                            <div class="absolute inset-[2px] flex items-center justify-center rounded-md transition-all duration-300"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 3 ? 'bg-black' : 'bg-primary'">
                                                <i class="fa-solid fa-caret-up transition-colors duration-300"
                                                    :class="active === 3 ? 'text-primary rotate-0' : 'text-black rotate-180'"></i>
                                            </div>
                                        </div>
                                    </button>
                                    <div x-show="active === 3" x-collapse>
                                        <p class="mt-4 text-[#0A0A0A] lg:text-lg text-base leading-relaxed max-w-lg">
                                            Our car services can range from inspection of all major parts of your car,
                                            from the
                                            brakes to the engine.
                                        </p>
                                    </div>
                                </div>

                                <!-- Item 4 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 4 ? null : 4)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">How much does a
                                            MOT test
                                            cost?</span>
                                        <div class="relative w-10 h-10 flex-shrink-0">
                                            <div class="absolute inset-0 rounded-md transition-all duration-300"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 4 ? 'bg-primary' : 'bg-black'">
                                            </div>
                                            <div class="absolute inset-[2px] flex items-center justify-center transition-all duration-300 rounded-md"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 4 ? 'bg-black' : 'bg-primary'">
                                                <i class="fa-solid fa-caret-up transition-colors duration-300"
                                                    :class="active === 4 ? 'text-primary rotate-0' : 'text-black rotate-180'"></i>
                                            </div>
                                        </div>
                                    </button>
                                    <div x-show="active === 4" x-collapse>
                                        <p class="mt-4 text-[#0A0A0A] lg:text-lg text-base leading-relaxed max-w-lg">
                                            At Simply Motoring, we offer a MOT test for £45.
                                        </p>
                                    </div>
                                </div>

                                <!-- Item 5 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 5 ? null : 5)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">How long does a
                                            MOT test
                                            take?</span>
                                        <div class="relative w-10 h-10 flex-shrink-0">
                                            <div class="absolute inset-0 rounded-md transition-all duration-300"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 5 ? 'bg-primary' : 'bg-black'">
                                            </div>
                                            <div class="absolute inset-[2px] flex items-center justify-center transition-all duration-300 rounded-md"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 5 ? 'bg-black' : 'bg-primary'">
                                                <i class="fa-solid fa-caret-up transition-colors duration-300"
                                                    :class="active === 5 ? 'text-primary rotate-0' : 'text-black rotate-180'"></i>
                                            </div>
                                        </div>
                                    </button>
                                    <div x-show="active === 5" x-collapse>
                                        <p class="mt-4 text-[#0A0A0A] lg:text-lg text-base leading-relaxed max-w-lg">
                                            Usually, MOT tests take around 45 to 60 minutes, however, this may vary
                                            depending on
                                            the condition of your car.
                                        </p>
                                    </div>
                                </div>

                                <!-- Item 6 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 6 ? null : 6)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">What do they check
                                            on a
                                            MOT test?</span>
                                        <div class="relative w-10 h-10 flex-shrink-0">
                                            <div class="absolute inset-0 rounded-md transition-all duration-300"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 6 ? 'bg-primary' : 'bg-black'">
                                            </div>
                                            <div class="absolute inset-[2px] flex items-center justify-center transition-all duration-300 rounded-md"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === 6 ? 'bg-black' : 'bg-primary'">
                                                <i class="fa-solid fa-caret-up transition-colors duration-300"
                                                    :class="active === 6 ? 'text-primary rotate-0' : 'text-black rotate-180'"></i>
                                            </div>
                                        </div>
                                    </button>
                                    <div x-show="active === 6" x-collapse>
                                        <p class="mt-4 text-[#0A0A0A] lg:text-lg text-base leading-relaxed max-w-lg">
                                            MOT testing basically checks whether your car is safe to drive. It includes
                                            inspecting the state of the seatbelt, brakes, wing mirrors, exhaust system,
                                            etc. For
                                            more information, check out our MOT service page.
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Image (Absolute) -->
            <div class="hidden lg:block absolute right-[-20%] bottom-[-40%] w-[50%] h-full pointer-events-none z-0">
                <img src="images/f2f7228f49e6c162f221a17ef06dbce97e4b5b88.png" alt="Tire"
                    class="absolute right-0 top-0 h-[800px] w-auto max-w-none object-contain animate-[spin_40s_linear_infinite]">
            </div>
        </div>
    </section>

    <!-- Convenience Section -->
    <section
        class="bg-black lg:py-32 py-16 w-full relative flex flex-col items-center justify-center overflow-hidden lg:min-h-[600px]">
        <!-- Top Glow -->
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[761px] h-[71px] bg-primary blur-[120px] pointer-events-none ">
        </div>

        <div class="relative z-10 flex flex-col items-center gap-16 max-w-5xl px-4 text-center">
            <!-- Heading -->
            <h2
                class="font-geist font-bold text-[48px] lg:text-[96px] leading-[0.85] tracking-tighter text-primary uppercase text-center typewriter-effect">
                CONVENIENCE THAT PUTS YOU FIRST
            </h2>

            <!-- Button (Hero Secondary Style) -->
            <div
                class="inline-block p-[1px] bg-white/60 hero-btn-clip rounded-md shadow-xl transition-all duration-300 hover:-translate-y-2 hover:translate-x-2 hover:shadow-2xl">
                <a href="#book"
                    class="inline-flex items-center justify-center h-[50px] bg-black text-white text-sm font-bold uppercase tracking-widest px-10 hover:bg-white hover:text-black transition-colors hero-btn-clip rounded-md">
                    BOOK A CAR SERVICE
                </a>
            </div>
        </div>
    </section>
@endsection('content')