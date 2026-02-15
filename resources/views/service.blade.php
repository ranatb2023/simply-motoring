@extends('layouts.main')

@section('content')
    <style>
        .brake-service-clip {
            /* Default: no clip or different clip on mobile if desired */
        }

        @media (min-width: 1024px) {
            .brake-service-clip {
                clip-path: polygon(80px 0, 100% 0, 100% calc(100% - 80px), calc(100% - 80px) 100%, 0 100%, 0 80px);
            }
        }
    </style>
    <!-- Main Container with Padding for White Space -->
    <div class="max-w-[1440px] mx-auto p-0 lg:p-6 w-full">

        <!-- Hero Section with Rounded Corners and Clip -->
        <section class="relative w-full h-[100vh] min-h-[700px] overflow-hidden lg:rounded-2xl hero-image-clip">

            <!-- Background Image -->
            <div class="absolute inset-0 z-0 bg-black">
                <img src="{{ asset('images/0dc3fe40d7bdb554314cba94e509be8f0e3be032.png') }}" alt="Expert Servicing"
                    class="w-full h-full object-cover">
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-transparent"></div>
            </div>

            <!-- Content Container -->
            <div class="relative z-10 w-full h-full flex flex-col justify-between py-10 lg:py-24 px-6 lg:px-20">

                <!-- Main Text Content -->
                <div class="mt-20 lg:mt-10 max-w-5xl">
                    <!-- Heading -->
                    <h1
                        class="text-white font-geist font-semibold text-[48px] lg:text-[110px] leading-[0.86] tracking-[-0.07em] uppercase mb-4 lg:mb-6 typewriter-effect opacity-0">
                        Servicing<br>
                        Built Around<br>
                        Your Car
                    </h1>

                    <!-- Subheading -->
                    <p class="text-white text-2xl lg:text-[36px] font-medium font-geist tracking-tight lg:mt-4">
                        Stay confident every time you drive
                    </p>
                </div>

                <!-- Bottom Section -->
                <div
                    class="absolute bottom-12 lg:bottom-16 left-0 right-0 px-6 lg:px-20 w-full flex flex-col lg:flex-row justify-between items-end gap-8 lg:gap-0">

                    <!-- Bottom Left Text -->
                    <div class="flex gap-4 max-w-[500px]">
                        <div class="w-5 h-5 mt-1.5 bg-primary shrink-0"></div> <!-- Orange Square -->
                        <p class="text-white/80 text-base lg:text-[20px] leading-[1.3] font-geist font-medium">
                            From routine maintenance to essential safety work, Simply Motoring delivers expert vehicle care
                            with
                            honest advice, clear communication, and transparent pricing.
                        </p>
                    </div>

                    <!-- Bottom Right Button -->
                    <div
                        class="inline-block p-[1px] bg-white/40 hero-btn-clip transform rounded-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                        <a href="#book"
                            class="inline-flex items-center justify-center lg:w-full bg-black/40 text-white text-sm font-bold uppercase tracking-widest px-8 py-4 hero-btn-clip rounded-md hover:bg-white hover:text-black transition-colors">
                            BOOK A SERVICE
                        </a>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- About Section -->
    <section class="bg-white py-10 lg:py-32 relative z-10">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-10">
            <div class="grid lg:grid-cols-2 gap-32 lg:gap-24 items-start">

                <!-- Left Column: Headline -->
                <div class="flex flex-col gap-6">
                    <span class="text-lg font-semibold uppercase tracking-tight text-[#0A0A0Ac2] font-geist">About
                        Simply Motoring</span>

                    <h2
                        class="text-[48px] lg:text-[96px] font-bold leading-[0.9] tracking-tighter uppercase font-geist opacity-0 typewriter-effect">
                        <span class="text-primary">Keeping<br>Your Car</span><br>
                        <span class="text-black">Road-Ready!</span>
                    </h2>
                </div>

                <!-- Right Column: Content -->
                <div class="flex flex-col gap-8 text-lg font-medium leading-relaxed text-[#0A0A0A] lg:pt-14">
                    <p>
                        Regular servicing is one of the most effective ways to protect your vehicle and avoid unexpected
                        breakdowns. It helps maintain engine performance, supports fuel efficiency, and ensures key safety
                        systems as they should.
                    </p>
                    <p>
                        A well-serviced car is also more reliable, safer to drive, and often cheaper to run over time.
                        Whether you drive daily or only occasionally, scheduled maintenance helps extend the life of your
                        vehicle and preserve its value.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-10 lg:py-20 bg-white relative z-10 overflow-hidden border-t border-black/10">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-10">
            <!-- Header -->
            <div class="flex flex-col items-center text-center mb-16">
                <span
                    class="bg-primary text-white px-4 py-1.5 rounded-full mb-6 font-geist font-medium text-[20px] leading-[1.26] tracking-[-0.06em] uppercase [leading-trim:CAP_HEIGHT]">Services</span>
                <h2
                    class="font-geist font-semibold text-[48px] lg:text-[96px] leading-[0.83] tracking-[-0.06em] text-center uppercase [leading-trim:CAP_HEIGHT] text-primary mb-8 typewriter-effect">
                    Our vehicle<br>servicing options
                </h2>
                <p class="text-[#0A0A0A] font-medium text-lg leading-[1.26] max-w-sm">
                    We offer clearly defined service levels to suit different driving habits, mileages, and vehicle needs.
                </p>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Card 1: Full Services (Orange) -->
                <div class="flex flex-col bg-primary text-white rounded-[16px] relative overflow-hidden group h-full shadow-lg hover:shadow-xl transition-shadow duration-300 transform translate-z-0"
                    style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">

                    <!-- Content -->
                    <div class="px-8 pt-16 flex-1 flex flex-col">
                        <h3
                            class="font-geist font-semibold text-[24px] lg:text-[32px] leading-[1] uppercase tracking-[-0.04em] mb-4">
                            Full Services
                        </h3>
                        <p class="text-white/90 text-[16px] lg:text-[18px] leading-[1.3] font-medium mb-8 min-h-[50px]">
                            A full service is ideal for annual maintenance, providing a comprehensive inspection.
                        </p>

                        <!-- Outline Button -->
                        <div class="mb-10">
                            <a href="#"
                                class="inline-block border border-white rounded-full px-6 py-3 text-[14px] font-bold uppercase tracking-wider hover:bg-white hover:text-primary transition-colors">
                                What's Included
                            </a>
                        </div>
                    </div>

                    <!-- List -->
                    <ul class="flex flex-col mt-auto text-[16px] lg:text-[18px] font-medium leading-[1]">
                        <li class="px-8 py-4 border-t border-white/30">Engine oil and oil filter replacement</li>
                        <li class="px-8 py-4 border-t border-white/30">Air filter inspection and replacement</li>
                        <li class="px-8 py-4 border-t border-white/30">Brake system inspection</li>
                        <li class="px-8 py-4 border-t border-white/30">Steering/suspension checks</li>
                        <li class="px-8 py-4 border-t border-white/30 border-b">Fluid level top ups</li>
                    </ul>

                    <!-- Footer -->
                    <button
                        class="h-[70px] flex items-stretch border-t border-white/50 hover:bg-black/10 transition-colors cursor-pointer group/btn mt-auto">
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

                <!-- Card 2: Interim Service (Gray) -->
                <div class="flex flex-col bg-[#F0F0F0] text-black rounded-[16px] relative overflow-hidden group h-full shadow-lg hover:shadow-xl transition-shadow duration-300 transform translate-z-0"
                    style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">

                    <!-- Content -->
                    <div class="px-8 pt-16 flex-1 flex flex-col">
                        <h3
                            class="font-geist font-semibold text-[24px] lg:text-[32px] leading-[1] uppercase tracking-[-0.04em] mb-4">
                            Interim Service
                        </h3>
                        <p class="text-[#0A0A0A]/80 text-[16px] lg:text-[18px] leading-[1.3] font-medium mb-8 min-h-[50px]">
                            It is recommended for high-mileage drivers or vehicles that need a check between annual services
                        </p>

                        <!-- Outline Button -->
                        <div class="mb-10">
                            <a href="#"
                                class="inline-block border border-black rounded-full px-6 py-3 text-[14px] font-bold uppercase tracking-wider hover:bg-black hover:text-white transition-colors">
                                What's Included
                            </a>
                        </div>


                    </div>

                    <!-- List -->
                    <ul class="flex flex-col mt-auto text-[16px] lg:text-[18px] font-medium leading-[1]">
                        <li class="px-8 py-4 border-t border-black/10">Engine oil and oil filter change</li>
                        <li class="px-8 py-4 border-t border-black/10">Brake inspection</li>
                        <li class="px-8 py-4 border-t border-black/10">Tyre and pressure checks</li>
                        <li class="px-8 py-4 border-t border-black/10">Fluid level assessment</li>
                        <li class="px-8 py-4 border-t border-black/10">Visual safety checks</li>
                    </ul>

                    <!-- Footer -->
                    <button
                        class="h-[70px] flex items-stretch border-t border-black/50 hover:bg-black/10 transition-colors cursor-pointer group/btn mt-auto">
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

                <!-- Card 3: Major Service (Black) -->
                <div class="flex flex-col bg-[#0A0A0A] text-white rounded-[16px] relative overflow-hidden group h-full shadow-lg hover:shadow-xl transition-shadow duration-300 transform translate-z-0"
                    style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">

                    <!-- Content -->
                    <div class="px-8 pt-16 flex-1 flex flex-col">
                        <h3
                            class="font-geist font-semibold text-[24px] lg:text-[32px] leading-[1] uppercase tracking-[-0.04em] mb-4">
                            Major Service
                        </h3>
                        <p class="text-white/90 text-[16px] lg:text-[18px] leading-[1.3] font-medium mb-8 min-h-[50px]">
                            A major service offers the most comprehensive level of care and is recommended every two years.
                        </p>

                        <!-- Outline Button -->
                        <div class="mb-10">
                            <a href="#"
                                class="inline-block border border-white rounded-full px-6 py-3 text-[14px] font-bold uppercase tracking-wider hover:bg-white hover:text-black transition-colors">
                                What's Included
                            </a>
                        </div>
                    </div>

                    <!-- List -->
                    <ul class="flex flex-col mt-auto text-[16px] lg:text-[18px] font-medium leading-[1]">
                        <li class="px-8 py-4 border-t border-white/30">All full service checks</li>
                        <li class="px-8 py-4 border-t border-white/30">Replacement of multiple filters</li>
                        <li class="px-8 py-4 border-t border-white/30">Spark plug replacement</li>
                        <li class="px-8 py-4 border-t border-white/30">Extensive safety and performance inspection
                        </li>
                    </ul>

                    <!-- Footer -->
                    <button
                        class="h-[70px] flex items-stretch border-t border-white/50 hover:bg-white/10 transition-colors cursor-pointer group/btn mt-auto">
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
        </div>
    </section>

    <!-- Brake Services Section -->
    <section class="py-0 lg:pb-20 bg-white relative z-10 overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-0 lg:px-10">

            <!-- Dark Container -->
            <div
                class="bg-black text-white lg:rounded-[32px] py-10 px-6 lg:p-16 relative overflow-hidden brake-service-clip">
                <!-- Decor Background (Optional subtle gradient or effect if needed, keeping it simple for now) -->

                <!-- Header -->
                <div class="flex flex-col items-center text-center mb-12 lg:mb-16 max-w-2xl mx-auto">
                    <h2
                        class="font-geist font-bold text-[40px] lg:text-[64px] leading-[0.9] tracking-[-0.04em] uppercase mb-6 typewriter-effect">
                        Brake Services
                    </h2>
                    <p class="text-white/80 font-medium text-[16px] lg:text-[18px] leading-[1.4]">
                        Your braking system is one of the most important safety features on your vehicle. We focus on
                        thorough inspections and honest recommendations.
                    </p>
                </div>

                <!-- Cards Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Left Card: Discs & Pads -->
                    <div class="bg-[#F0F0F0] text-black rounded-[16px] p-8 lg:p-10 flex flex-col h-full">
                        <h3
                            class="font-geist font-semibold text-[28px] lg:text-[32px] leading-[0.96] uppercase tracking-[-0.06em] mb-4">
                            Brake Discs & Pads<br>Replacement
                        </h3>
                        <p
                            class="text-black/80 text-[16px] lg:text-[20px] leading-[1.3] tracking-[-0.06em] font-medium mb-8">
                            The braking system is a critical feature of your vehicle. Worn brake discs or pads can
                            significantly reduce stopping power and compromise safety.
                        </p>

                        <div class="mb-10">
                            <a href="#"
                                class="inline-block border border-black rounded-full px-5 py-2.5 text-[13px] lg:text-[20px] font-semibold uppercase tracking-wider hover:bg-black hover:text-white transition-colors">
                                What's Included
                            </a>
                        </div>

                        <ul class="mt-auto space-y-4">
                            <li class="flex items-center gap-4">
                                <span class="shrink-0 pt-1 text-black">
                                    <i class="fa-solid fa-compact-disc text-[24px]"></i>
                                </span>
                                <span
                                    class="text-[18px] font-semibold leading-[1.3] tracking-[-0.04em] text-black">Inspection
                                    of brake discs and
                                    pads</span>
                            </li>
                            <li class="flex items-center gap-4">
                                <span class="shrink-0 pt-1 text-black">
                                    <i class="fa-solid fa-compact-disc text-[24px]"></i>
                                </span>
                                <span
                                    class="text-[18px] font-semibold leading-[1.3] tracking-[-0.04em] text-black">Replacement
                                    using quality
                                    parts</span>
                            </li>
                            <li class="flex items-center gap-4">
                                <span class="shrink-0 pt-1 text-black">
                                    <i class="fa-solid fa-compact-disc text-[24px]"></i>
                                </span>
                                <span class="text-[18px] font-semibold leading-[1.3] tracking-[-0.04em] text-black">System
                                    testing to ensure correct
                                    performance</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Right Card: Brake Fluid -->
                    <div class="bg-primary text-white rounded-[16px] p-8 lg:p-10 flex flex-col h-full">
                        <h3
                            class="font-geist font-semibold text-[28px] lg:text-[32px] leading-[0.96] uppercase tracking-[-0.06em] mb-4">
                            Brake Fluid<br>Change
                        </h3>
                        <p
                            class="text-white/80 text-[16px] lg:text-[20px] leading-[1.3] tracking-[-0.06em] font-medium mb-8">
                            Brake fluid can reduce braking efficiency over time. Regular brake fluid changes help maintain
                            responsive braking and system reliability.
                        </p>

                        <div class="mb-10">
                            <a href="#"
                                class="inline-block border border-white rounded-full px-5 py-2.5 text-[13px] lg:text-[20px] font-semibold uppercase tracking-wider hover:bg-white hover:text-primary transition-colors">
                                What's Included
                            </a>
                        </div>

                        <ul class="mt-auto space-y-4">
                            <li class="flex items-center gap-4">
                                <span class="shrink-0 pt-1 text-white">
                                    <i class="fa-solid fa-compact-disc text-[24px]"></i>
                                </span>
                                <span class="text-[18px] font-semibold leading-[1.3] tracking-[-0.04em] text-white">Improves
                                    braking performance</span>
                            </li>
                            <li class="flex items-center gap-4">
                                <span class="shrink-0 pt-1 text-white">
                                    <i class="fa-solid fa-compact-disc text-[24px]"></i>
                                </span>
                                <span class="text-[18px] font-semibold leading-[1.3] tracking-[-0.04em] text-white">Protects
                                    internal brake
                                    components</span>
                            </li>
                            <li class="flex items-center gap-4">
                                <span class="shrink-0 pt-1 text-white">
                                    <i class="fa-solid fa-compact-disc text-[24px]"></i>
                                </span>
                                <span class="text-[18px] font-semibold leading-[1.3] tracking-[-0.04em] text-white">Reduces
                                    risk of brake
                                    failure</span>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </section>

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
                <!-- Content Container -->
                <div
                    class="bg-white/10 backdrop-blur-sm lg:backdrop-blur-xl relative z-20 w-full max-w-[90%] lg:max-w-[80%] mx-auto h-full flex flex-col lg:flex-row justify-between p-6 lg:p-8 gap-10 lg:gap-20 rounded-[20px]">

                    <!-- Left Column: Title & CTA -->
                    <div class="w-full lg:w-1/2 flex flex-col items-start justify-between text-left">
                        <div>
                            <h2
                                class="font-geist font-bold text-[40px] lg:text-[64px] leading-[0.83] tracking-[-0.06em] uppercase text-white mb-4 lg:mb-10 typewriter-effect">
                                WHY CHOOSE<br>SIMPLY<br>MOTORING
                            </h2>
                            <p class="text-white font-medium text-[20px] lg:text-[20px] leading-[1.3] mb-10">
                                Choosing the right service partner matters.
                            </p>
                        </div>

                        <div
                            class="inline-block p-[1px] bg-white/40 hero-btn-clip transform rounded-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                            <a href="tel:0123456789"
                                class="inline-flex items-center justify-center lg:w-full bg-black text-white text-sm font-bold uppercase tracking-widest px-8 py-4 hero-btn-clip rounded-md hover:bg-white hover:text-black transition-colors">
                                CALL FOR A QUOTE
                            </a>
                        </div>
                    </div>

                    <!-- Right Column: Feature Cards -->
                    <div class="w-full lg:w-1/2 flex flex-col gap-4">

                        <!-- Card 1 -->
                        <div class="rounded-xl overflow-hidden backdrop-blur-md bg-white/5 border border-white/10">
                            <div class="bg-primary px-6 py-4">
                                <h3
                                    class="font-geist font-bold text-white text-[18px] lg:text-[20px] leading-[1.06] uppercase tracking-wide">
                                    PROFESSIONAL TECHNICIANS
                                </h3>
                            </div>
                            <div class="p-6">
                                <p class="text-white/80 text-[16px] lg:text-[18px] leading-relaxed font-medium">
                                    Our skilled technicians are experienced, trained, and focused on delivering safe, high
                                    quality workmanship on every vehicle.
                                </p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="rounded-xl overflow-hidden backdrop-blur-md bg-white/5 border border-white/10">
                            <div class="bg-white px-6 py-4">
                                <h3
                                    class="font-geist font-bold text-black text-[18px] lg:text-[20px] leading-[1.06] uppercase tracking-wide">
                                    COMPETITIVE, UPFRONT PRICING
                                </h3>
                            </div>
                            <div class="p-6">
                                <p class="text-white/80 text-[16px] lg:text-[18px] leading-relaxed font-medium">
                                    We offer fair pricing with clear quotes and no hidden charges, helping you manage your
                                    car care costs with confidence.
                                </p>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="rounded-xl overflow-hidden backdrop-blur-md bg-white/5 border border-white/10">
                            <div class="bg-primary px-6 py-4">
                                <h3
                                    class="font-geist font-bold text-white text-[18px] lg:text-[20px] leading-[1.06] uppercase tracking-wide">
                                    CUSTOMER CENTRED EXPERIENCE
                                </h3>
                            </div>
                            <div class="p-6">
                                <p class="text-white/80 text-[16px] lg:text-[18px] leading-relaxed font-medium">
                                    From booking your appointment to collecting your vehicle, we provide friendly,
                                    personalised support and reliable service you can trust.
                                </p>
                            </div>
                        </div>

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
                                                                                                                                                                                                                                                                                                                                    }"
                class="w-[calc(100vw-3rem)] overflow-hidden" @mouseenter="paused = true" @mouseleave="paused = false">
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

    <!-- Reviews Section -->
    <section class="bg-white py-10 lg:py-20 relative z-10 w-full overflow-hidden border-t border-black/10">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-20"
            x-data="{
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

@endsection