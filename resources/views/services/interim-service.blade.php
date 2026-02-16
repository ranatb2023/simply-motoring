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
                <img src="{{ asset('images/a8a27d00025e9690cd36a942843d05fd3b25f24a.jpg') }}" alt="Expert Servicing"
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
                        class="text-white font-geist font-semibold text-[48px] lg:text-[96px] leading-[0.86] tracking-[-0.07em] uppercase mb-4 lg:mb-6 typewriter-effect opacity-0">
                        Interim Service
                    </h1>

                    <!-- Subheading -->
                    <p class="text-white text-2xl lg:text-[36px] font-medium font-geist tracking-tight lg:mt-4">
                        Quick maintenance to keep your car in top shape
                    </p>
                </div>

                <!-- Bottom Section -->
                <div
                    class="absolute bottom-12 lg:bottom-16 left-0 right-0 px-6 lg:px-20 w-full flex flex-col lg:flex-row justify-between items-end gap-8 lg:gap-0">

                    <!-- Bottom Left Text -->
                    <div class="flex gap-4 max-w-[500px]">
                        <div class="w-5 h-5 mt-1.5 bg-primary shrink-0"></div> <!-- Orange Square -->
                        <p class="text-white/80 text-base lg:text-[20px] leading-[1.3] font-geist font-medium">
                            An interim service from Simply Motoring helps maintain your vehicle’s performance. Our
                            technicians carry out essential checks, replace fluids where needed, and address minor wear so
                            your car remains safe, efficient, and reliable.
                        </p>
                    </div>

                    <!-- Bottom Right Button -->
                    <div
                        class="inline-block p-[1px] bg-white/40 hero-btn-clip transform rounded-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                        <a href="#book"
                            class="inline-flex items-center justify-center lg:w-full bg-black/40 text-white text-sm font-bold uppercase tracking-widest px-8 py-4 hero-btn-clip rounded-md hover:bg-white hover:text-black transition-colors">
                            Book Your Interim Service
                        </a>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- Full Vehicle Service Include Section -->
    <section class="bg-white py-10 lg:py-20 w-full overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 mb-8 lg:mb-16">
                <div class="w-full lg:w-1/2">
                    <h2
                        class="font-geist font-bold text-[40px] lg:text-[64px] leading-[0.86] tracking-[-0.06em] text-black mb-6 uppercase typewriter-effect">
                        Interim Service Includes
                    </h2>
                </div>
            </div>

            <!-- Carousel Container -->
            <div x-data="{
                                    active: 0,
                                    cardWidth: 0,
                                    gap: 24,
                                    transitioning: true,
                                    paused: false,
                                    updateWidth() {
                                        const card = this.$el.querySelector('.service-card');
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
                                        if (this.active === 5) {
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
                        <!-- Card 1 -->
                        <div class="service-card h-full shrink-0 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0">
                            <div class="bg-primary rounded-xl p-8 w-full h-full flex flex-col border border-white/50">
                                <h3
                                    class="text-white/80 text-2xl lg:text-3xl font-bold font-geist leading-tight mb-8 uppercase">
                                    Engine & Fluids
                                </h3>
                                <ul class="space-y-6">
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Oil change and
                                            replacement of the oil filter</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Check and
                                            top-up essential fluids</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Inspect belts
                                            and hoses for wear</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="service-card h-full shrink-0 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0">
                            <div class="bg-primary rounded-xl p-8 w-full h-full flex flex-col border border-white/50">
                                <h3
                                    class="text-white/80 text-2xl lg:text-3xl font-bold font-geist leading-tight mb-8 uppercase">
                                    BRAKE & SUSPENSION
                                </h3>
                                <ul class="space-y-6">
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Check brake
                                            pads, discs, and fluid condition</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Inspect
                                            suspension, steering components, and linkages</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Test brake
                                            response and ride quality</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="service-card h-full shrink-0 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0">
                            <div class="bg-primary rounded-xl p-8 w-full h-full flex flex-col border border-white/50">
                                <h3
                                    class="text-white/80 text-2xl lg:text-3xl font-bold font-geist leading-tight mb-8 uppercase">
                                    STEERING & TYRES
                                </h3>
                                <ul class="space-y-6">
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Check tyre
                                            pressures and tread depth</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Inspect
                                            wheels, alignment, and the steering system</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="service-card h-full shrink-0 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0">
                            <div class="bg-primary rounded-xl p-8 w-full h-full flex flex-col border border-white/50">
                                <h3
                                    class="text-white/80 text-2xl lg:text-3xl font-bold font-geist leading-tight mb-8 uppercase">
                                    Safety & Electrical Checks
                                </h3>
                                <ul class="space-y-6">
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Test the
                                            lights, horn, and dashboard warning indicators</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Check battery
                                            condition and charging system</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Inspect wipers
                                            and washers</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card 5 -->
                        <div class="service-card h-full shrink-0 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0">
                            <div class="bg-primary rounded-xl p-8 w-full h-full flex flex-col border border-white/50">
                                <h3
                                    class="text-white/80 text-2xl lg:text-3xl font-bold font-geist leading-tight mb-8 uppercase">
                                    Additional Checks
                                </h3>
                                <ul class="space-y-6">
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Visual
                                            inspection of the exhaust and underbody</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Basic check of
                                            air filters</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Road test to
                                            confirm performance</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Group 2 (Duplicate for loop) -->
                    <div class="flex gap-6 shrink-0" aria-hidden="true">
                        <!-- Card 1 -->
                        <div class="service-card h-full shrink-0 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0">
                            <div class="bg-primary rounded-xl p-8 w-full h-full flex flex-col border border-white/50">
                                <h3
                                    class="text-white/80 text-2xl lg:text-3xl font-bold font-geist leading-tight mb-8 uppercase">
                                    Engine & Fluids
                                </h3>
                                <ul class="space-y-6">
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Oil change and
                                            replacement of oil filter</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Check and
                                            top-up essential fluids</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Inspect belts
                                            and hoses for wear</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="service-card h-full shrink-0 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0">
                            <div class="bg-primary rounded-xl p-8 w-full h-full flex flex-col border border-white/50">
                                <h3
                                    class="text-white/80 text-2xl lg:text-3xl font-bold font-geist leading-tight mb-8 uppercase">
                                    BRAKE & SUSPENSION
                                </h3>
                                <ul class="space-y-6">
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Check brake
                                            pads, discs, and fluid condition</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Inspect
                                            suspension, steering components, and linkages</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Test brake
                                            response and ride quality</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="service-card h-full shrink-0 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0">
                            <div class="bg-primary rounded-xl p-8 w-full h-full flex flex-col border border-white/50">
                                <h3
                                    class="text-white/80 text-2xl lg:text-3xl font-bold font-geist leading-tight mb-8 uppercase">
                                    STEERING & TYRES
                                </h3>
                                <ul class="space-y-6">
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Check tyre
                                            pressures and tread depth</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Inspect
                                            wheels, alignment, and the steering system</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="service-card h-full shrink-0 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0">
                            <div class="bg-primary rounded-xl p-8 w-full h-full flex flex-col border border-white/50">
                                <h3
                                    class="text-white/80 text-2xl lg:text-3xl font-bold font-geist leading-tight mb-8 uppercase">
                                    Safety & Electrical Checks
                                </h3>
                                <ul class="space-y-6">
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Test the
                                            lights, horn, and dashboard warning indicators</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Check battery
                                            condition and charging system</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Inspect wipers
                                            and washers</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Card 5 -->
                        <div class="service-card h-full shrink-0 w-[calc(100vw-3rem)] lg:w-[400px] max-w-none shrink-0">
                            <div class="bg-primary rounded-xl p-8 w-full h-full flex flex-col border border-white/50">
                                <h3
                                    class="text-white/80 text-2xl lg:text-3xl font-bold font-geist leading-tight mb-8 uppercase">
                                    Additional Checks
                                </h3>
                                <ul class="space-y-6">
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Visual
                                            inspection of the exhaust and underbody</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Basic check of
                                            air filters</span>
                                    </li>
                                    <li class="flex items-start gap-4">
                                        <div class="flex items-center justify-center shrink-0 mt-1">
                                            <i class="fa-light fa-tire text-2xl text-white"></i>
                                        </div>
                                        <span class="text-lg lg:text-xl font-medium leading-tight text-white">Road test to
                                            confirm performance</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Bottom Text -->
            <div class="text-center w-full mt-10">
                <p class="text-[#0A0A0A] text-lg font-semibold mx-auto">
                    Note: Interim service is tailored to your vehicle’s mileage and manufacturer guidance, providing a
                    quicker, focused maintenance option between full services.
                </p>
            </div>
        </div>
    </section>

    <!-- Signs You Need a Full Service -->
    <section class="bg-black py-10 lg:py-20 w-full overflow-hidden relative">
        <div class="max-w-[1440px] mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row gap-8 lg:items-center mb-12">
                <h2
                    class="font-geist font-bold text-[40px] lg:text-[64px] leading-[0.9] tracking-tighter text-white uppercase typewriter-effect shrink-0">
                    SIGNS YOU NEED AN<br>Interim Service
                </h2>
                <p class="text-white/70 text-lg font-medium max-w-sm pb-2 lg:ml-10">
                    Even if your car seems fine, an interim service is recommended if:
                </p>
            </div>

            <div class="w-full lg:w-1/2">
                <div class="flex flex-col group mt-8 lg:mt-0">
                    <!-- Card 01 -->
                    <div class="entrance-anim entrance-hidden relative z-0 w-full transition-all duration-500 ease-out">
                        <div
                            class="bg-primary rounded-xl p-4 text-white w-full min-h-[120px] flex flex-col justify-between border border-white/50">
                            <span class="text-white/50 text-4xl font-bold font-geist leading-none">01</span>
                            <span class="text-xl lg:text-2xl font-semibold font-geist leading-tight">You’ve driven several
                                thousand miles since the last full service</span>
                        </div>
                    </div>

                    <!-- Card 02 -->
                    <div
                        class="entrance-anim entrance-hidden relative z-10 mt-2 lg:-mt-16 lg:group-hover:mt-2 transition-all duration-500 ease-out w-full">
                        <div
                            class="bg-primary rounded-xl p-4 text-white w-full min-h-[120px] flex flex-col justify-between border border-white/50">
                            <span class="text-white/50 text-4xl font-bold font-geist leading-none">02</span>
                            <span class="text-xl lg:text-2xl font-semibold font-geist leading-tight">Minor issues like
                                reduced fuel efficiency or warning lights appear</span>
                        </div>
                    </div>

                    <!-- Card 03 -->
                    <div
                        class="entrance-anim entrance-hidden relative z-20 mt-2 lg:-mt-16 lg:group-hover:mt-2 transition-all duration-500 ease-out w-full">
                        <div
                            class="bg-primary rounded-xl p-4 text-white w-full min-h-[120px] flex flex-col justify-between border border-white/50">
                            <span class="text-white/50 text-4xl font-bold font-geist leading-none">03</span>
                            <span class="text-xl lg:text-2xl font-semibold font-geist leading-tight">Brakes or steering feel
                                slightly off</span>
                        </div>
                    </div>

                    <!-- Card 04 -->
                    <div
                        class="entrance-anim entrance-hidden relative z-30 mt-2 lg:-mt-16 lg:group-hover:mt-2 transition-all duration-500 ease-out w-full">
                        <div
                            class="bg-primary rounded-xl p-4 text-white w-full min-h-[120px] flex flex-col justify-between border border-white/50">
                            <span class="text-white/50 text-4xl font-bold font-geist leading-none">04</span>
                            <span class="text-xl lg:text-2xl font-semibold font-geist leading-tight">You want to maintain
                                vehicle reliability and avoid breakdowns</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Tyre Image -->
                <div class="absolute right-[-28%] top-[0%] z-0 h-full w-full pointer-events-none hidden lg:block">
                    <img src="{{ asset('images/f2f7228f49e6c162f221a17ef06dbce97e4b5b88.png') }}" alt="Tyre"
                        class="absolute right-0 top-0 h-[700px] w-auto max-w-none object-contain animate-[spin_40s_linear_infinite]">
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="bg-white rounded-xl p-6 text-center w-full mt-12 relative z-10">
                <p class="text-black font-semibold text-lg lg:text-xl leading-tight font-geist">
                    Regular interim servicing helps extend vehicle life and ensures ongoing safety.
                </p>
            </div>
        </div>
    </section>

    <!-- Book Full Service Section -->
    <section class="bg-white py-10 lg:py-20 w-full">
        <div class="max-w-[1440px] mx-auto px-6 relative">

            <!-- Heading -->
            <div class="text-center mb-16 lg:mb-24 max-w-2xl mx-auto">
                <h2
                    class="font-geist font-semibold text-[48px] lg:text-[64px] uppercase leading-[0.96] lg:leading-[0.86] tracking-[-0.04em] mb-4 typewriter-effect">
                    Book Your Interim Service
                </h2>
                <p class="text-lg lg:text-xl text-black/80 tracking-[-0.04em] font-medium max-w-lg mx-auto">
                    Booking with Simply Motoring is simple:
                </p>
            </div>

            <!-- Steps Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10 mb-16">

                <!-- Step 1 -->
                <div class="relative group pt-16">
                    <!-- Tire Image -->
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[120px] h-[120px] z-0">
                        <img src="{{ asset('images/f2f7228f49e6c162f221a17ef06dbce97e4b5b88.png') }}" alt="Tire"
                            class="w-full h-full object-contain animate-[spin_20s_linear_infinite]">
                    </div>
                    <!-- Card -->
                    <div
                        class="bg-black/40 relative z-10 rounded-xl p-8 h-[200px] flex flex-col justify-between shadow-lg overflow-hidden backdrop-blur-md">
                        <!-- Blur/Gradient Overlay for top edge to blend with tire if needed, or simply let the tire peek from behind nicely -->
                        <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-transparent pointer-events-none">
                        </div>
                        <span class="text-black/50 text-3xl font-bold opacity-60 relative z-20">01</span>
                        <h3
                            class="font-geist font-bold text-2xl uppercase leading-[1.06] lg:leading-[0.96] tracking-[-0.04em] relative z-20 text-white">
                            Select your interim service type
                        </h3>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative group pt-16">
                    <!-- Tire Image -->
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[120px] h-[120px] z-0">
                        <img src="{{ asset('images/f2f7228f49e6c162f221a17ef06dbce97e4b5b88.png') }}" alt="Tire"
                            class="w-full h-full object-contain animate-[spin_20s_linear_infinite]">
                    </div>
                    <!-- Card -->
                    <div
                        class="bg-primary/60 relative z-10 rounded-xl p-8 h-[200px] flex flex-col justify-between shadow-lg overflow-hidden backdrop-blur-md">
                        <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-transparent pointer-events-none">
                        </div>
                        <span class="text-white/50 text-3xl font-bold opacity-60 relative z-20">02</span>
                        <h3
                            class="font-geist font-bold text-2xl uppercase leading-[1.06] lg:leading-[0.96] tracking-[-0.04em] relative z-20 text-white">
                            Book online or<br>call our friendly team
                        </h3>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative group pt-16">
                    <!-- Tire Image -->
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[120px] h-[120px] z-0">
                        <img src="{{ asset('images/f2f7228f49e6c162f221a17ef06dbce97e4b5b88.png') }}" alt="Tire"
                            class="w-full h-full object-contain animate-[spin_20s_linear_infinite]">
                    </div>
                    <!-- Card -->
                    <div
                        class="bg-black/40 relative z-10 rounded-xl p-8 h-[200px] flex flex-col justify-between shadow-lg overflow-hidden backdrop-blur-md">
                        <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-transparent pointer-events-none">
                        </div>
                        <span class="text-black/50 text-3xl font-bold opacity-60 relative z-20">03</span>
                        <h3
                            class="font-geist font-bold text-2xl uppercase leading-[1.06] lg:leading-[0.96] tracking-[-0.04em] relative z-20 text-white">
                            Receive professional care from trained technicians
                        </h3>
                    </div>
                </div>

            </div>

            <!-- CTA Button -->
            <div class="flex justify-center">
                <a href="#book"
                    class="bg-black text-white px-10 py-4 font-bold uppercase tracking-widest text-sm relative group overflow-hidden hero-btn-clip inline-block transition-transform transform hover:-translate-y-1 hover:shadow-xl">
                    <span class="relative z-10">Book Your Interim Service Today</span>
                </a>
            </div>

        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section
        class="bg-black relative z-10 w-full lg:rounded-bl-[32px] lg:[clip-path:polygon(0_0,100%_0,100%_calc(100%_-_80px),calc(100%_-_80px)_100%,0_100%,0_0)]">
        <div class="w-full">
            <!-- Container with custom clip-path -->
            <div
                class="relative lg:py-20 py-10 w-full overflow-hidden min-h-[700px] lg:min-h-[800px] flex lg:items-center lg:rounded-[32px] lg:[clip-path:polygon(80px_0,100%_0,100%_calc(100%_-_80px),calc(100%_-_80px)_100%,0_100%,0_80px)]">

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
                                    Skilled Technicians
                                </h3>
                            </div>
                            <div class="p-6">
                                <p class="text-white/80 text-[16px] lg:text-[18px] leading-relaxed font-medium">
                                    Our automotive experts handle full servicing following manufacturer guidelines. They
                                    inspect, maintain, and replace components on all makes and models.
                                </p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="rounded-xl overflow-hidden backdrop-blur-md bg-white/5 border border-white/10">
                            <div class="bg-white px-6 py-4">
                                <h3
                                    class="font-geist font-bold text-black text-[18px] lg:text-[20px] leading-[1.06] uppercase tracking-wide">
                                    Quality Parts
                                </h3>
                            </div>
                            <div class="p-6">
                                <p class="text-white/80 text-[16px] lg:text-[18px] leading-relaxed font-medium">
                                    We use manufacturer-approved or premium parts, filters, and fluids for lasting
                                    performance and compliance with warranty requirements.
                                </p>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="rounded-xl overflow-hidden backdrop-blur-md bg-white/5 border border-white/10">
                            <div class="bg-primary px-6 py-4">
                                <h3
                                    class="font-geist font-bold text-white text-[18px] lg:text-[20px] leading-[1.06] uppercase tracking-wide">
                                    Transparent Pricing
                                </h3>
                            </div>
                            <div class="p-6">
                                <p class="text-white/80 text-[16px] lg:text-[18px] leading-relaxed font-medium">
                                    Clear, upfront quotes cover labour and fluid replacement. No hidden fees, and every step
                                    is explained before we begin work on every model and make.
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
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
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">What is the difference
                                            between interim and full service?</span>
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
                                            An interim service is a lighter, quicker check focusing on key systems like
                                            brakes, fluids, and engine components, whereas a full service covers all vehicle
                                            systems in detail.
                                        </p>
                                    </div>
                                </div>

                                <!-- Item 2 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 2 ? null : 2)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">How often should I get
                                            an interim service?</span>
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
                                            Typically, every 6 months or around 6,000–7,000 miles, depending on your
                                            manufacturer’s recommendations and driving conditions.
                                        </p>
                                    </div>
                                </div>

                                <!-- Item 3 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 3 ? null : 3)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">Will an interim
                                            service improve fuel efficiency?</span>
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
                                            Yes. By checking fluids, tyres, and engine performance, an interim service helps
                                            your car run more efficiently and reduces fuel consumption.
                                        </p>
                                    </div>
                                </div>

                                <!-- Item 4 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 4 ? null : 4)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">Can minor wear be
                                            fixed during an interim service?</span>
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
                                            Yes. Technicians can replace worn fluids, filters, or small components to
                                            prevent issues from worsening.
                                        </p>
                                    </div>
                                </div>
                                <!-- Item 5 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 5 ? null : 5)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">Is an interim service
                                            suitable for older vehicles?</span>
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
                                            Absolutely. Interim servicing helps detect early signs of wear, keeping older
                                            cars reliable and safe between full services.
                                        </p>
                                    </div>
                                </div>
                                <!-- Item 6 -->
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === 6 ? null : 6)"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">Do I need to book a
                                            full service after every interim service?</span>
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
                                            Not necessarily. Interim services are designed to maintain performance between
                                            full services. Full servicing should still be done according to the
                                            manufacturer's schedule.
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
                <img src="{{ asset('images/f2f7228f49e6c162f221a17ef06dbce97e4b5b88.png') }}" alt="Tire"
                    class="absolute right-0 top-0 h-[800px] w-auto max-w-none object-contain animate-[spin_40s_linear_infinite]">
            </div>
        </div>
    </section>

    <style>
        .entrance-anim {
            opacity: 0;
            /* Initial state handled by animation, but keep opacity 0 to prevent flash */
        }

        @keyframes slide-in-blur {
            0% {
                opacity: 0;
                transform: translateY(40px) scale(0.9);
                filter: blur(4px);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        .entrance-visible {
            animation: slide-in-blur 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .entrance-hidden {
            opacity: 0;
            /* Optionally animate out or just hide */
            transition: opacity 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('entrance-hidden');
                        entry.target.classList.add('entrance-visible');
                    } else {
                        entry.target.classList.remove('entrance-visible');
                        entry.target.classList.add('entrance-hidden');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            document.querySelectorAll('.entrance-anim').forEach((el, index) => {
                // Set animation delay for entrance
                el.style.animationDelay = `${index * 100}ms`;
                observer.observe(el);
            });
        });
    </script>
@endsection