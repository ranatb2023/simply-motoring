@extends('layouts.main')

@section('meta_description', 'DVSA-approved MOT testing in Doncaster with thorough inspections, honest advice and fast results to keep you road legal.')

@section('meta_title', 'MOT Test in Doncaster | DVSA Approved MOT Centre')

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
        <section class="relative w-full h-[80vh] lg:h-[100vh] overflow-hidden lg:rounded-2xl hero-image-clip">

            <!-- Background Image -->
            <div class="absolute inset-0 z-0 bg-black">
                <img src="{{ asset('images/d2d51f5843582c2813015b713901596c625b5333.jpg') }}" alt="Mechanic servicing vehicle"
                    class="w-full h-full object-cover">
                <!-- Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-transparent"></div>
            </div>

            <!-- Content Container -->
            <div class="relative z-10 w-full h-full flex flex-col justify-between py-10 lg:py-24 px-6 lg:px-20">

                <!-- Main Text Content -->
                <div class="mt-20 lg:mt-10 max-w-4xl">
                    <!-- Heading -->
                    <h1
                        class="text-white font-geist font-semibold text-[48px] lg:text-[96px] leading-[0.86] tracking-[-0.07em] uppercase mb-4 lg:mb-6">
                        MOT Test Centre<br>in Doncaster
                    </h1>

                    <!-- Subheading -->
                    <p class="max-w-2xl text-white text-lg lg:text-xl font-medium font-geist tracking-tight lg:mt-4">
                        At Simply Motoring, we combine expert inspection with honest advice and transparent pricing. Our
                        goal is to make MOT testing straightforward, stress-free, and affordable for every driver.
                    </p>
                </div>

                <!-- Bottom Section -->
                <div class="absolute bottom-12 lg:bottom-20 left-0 right-0 px-6 lg:px-20 w-full">
                    <div
                        class="inline-block p-[1px] bg-white/40 hero-btn-clip transform rounded-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                        <a href="#book"
                            class="inline-flex items-center justify-center lg:w-full bg-black/40 text-white text-sm font-bold uppercase tracking-widest px-8 py-4 hero-btn-clip rounded-md hover:bg-white hover:text-black transition-colors">
                            Book your MOT test today
                        </a>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- Drive Safe with Simply Motoring -->
    <section class="bg-white py-10 lg:py-20 w-full">
        <div class="max-w-[1440px] mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-12 lg:gap-24 items-center">

                <!-- Left Content -->
                <div class="w-full lg:w-1/2 flex flex-col gap-6">

                    <h2
                        class="font-geist font-bold text-[40px] lg:text-[60px] leading-[0.88] tracking-[-0.05em] text-black uppercase">
                        Drive Safe with<br>Simply Motoring
                    </h2>

                    <!-- Divider -->
                    <div class="w-12 h-[3px] bg-primary rounded-full"></div>

                    <p
                        class="font-geist text-[#0A0A0A] text-[15px] lg:text-[17px] leading-[1.6] tracking-[-0.02em] font-medium hidden lg:block">
                        Keeping your car roadworthy is essential for both legal compliance and driver safety. A yearly MOT
                        test ensures your vehicle continues to meet the required safety and environmental standards.
                    </p>

                    <p
                        class="font-geist text-[#0A0A0A] text-[15px] lg:text-[16px] leading-[1.6] tracking-[-0.02em] font-medium">
                        Our qualified technicians conduct detailed inspections to identify any potential issues early.
                        Whether it’s worn tyres, faulty lights, or braking concerns, our team ensures your vehicle is
                        thoroughly checked and ready for the road.
                    </p>
                </div>

                <!-- Right Content: Stacked Cards -->
                <div class="w-full lg:w-1/2">
                    <p class="text-[16px] font-bold tracking-[0.18em] text-[#0A0A0A] uppercase mb-5">Our service focuses on
                    </p>

                    <div class="flex flex-col group">

                        <!-- Card 01 -->
                        <div class="entrance-anim entrance-hidden relative z-0 w-full transition-all duration-500 ease-out">
                            <div
                                class="bg-black rounded-2xl px-6 py-5 text-white w-full min-h-[130px] flex flex-col justify-between relative overflow-hidden">
                                <span
                                    class="pointer-events-none select-none absolute -top-1 -right-1 font-geist font-bold text-[80px] leading-none tracking-[-0.06em] text-white/[0.10]">01</span>
                                <div class="flex items-center justify-between">
                                    <span class="text-[16px] font-bold tracking-[0.18em] text-white/30 uppercase">01</span>
                                </div>
                                <span
                                    class="font-geist font-bold text-[20px] lg:text-[22px] leading-[1.1] tracking-[-0.04em] uppercase">Professional
                                    DVSA-approved inspections</span>
                            </div>
                        </div>

                        <!-- Card 02 -->
                        <div
                            class="entrance-anim entrance-hidden relative z-10 mt-2 lg:-mt-14 lg:group-hover:mt-2 transition-all duration-500 ease-out w-full">
                            <div
                                class="bg-primary rounded-2xl px-6 py-5 text-white w-full min-h-[130px] flex flex-col justify-between relative overflow-hidden">
                                <span
                                    class="pointer-events-none select-none absolute -top-1 -right-1 font-geist font-bold text-[80px] leading-none tracking-[-0.06em] text-white/[0.10]">02</span>
                                <div class="flex items-center justify-between">
                                    <span class="text-[16px] font-bold tracking-[0.18em] text-white/40 uppercase">02</span>
                                </div>
                                <span
                                    class="font-geist font-bold text-[20px] lg:text-[22px] leading-[1.1] tracking-[-0.04em] uppercase">Honest
                                    pricing with no hidden costs</span>
                            </div>
                        </div>

                        <!-- Card 03 -->
                        <div
                            class="entrance-anim entrance-hidden relative z-20 mt-2 lg:-mt-14 lg:group-hover:mt-2 transition-all duration-500 ease-out w-full">
                            <div
                                class="bg-black rounded-2xl px-6 py-5 text-white w-full min-h-[130px] flex flex-col justify-between relative overflow-hidden border border-white/10">
                                <span
                                    class="pointer-events-none select-none absolute -top-1 -right-1 font-geist font-bold text-[80px] leading-none tracking-[-0.06em] text-white/[0.10]">03</span>
                                <div class="flex items-center justify-between">
                                    <span class="text-[16px] font-bold tracking-[0.18em] text-white/30 uppercase">03</span>
                                </div>
                                <span
                                    class="font-geist font-bold text-[20px] lg:text-[22px] leading-[1.1] tracking-[-0.04em] uppercase">Fast
                                    service so you can get back on the road</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Why Is an MOT Test Important? -->
    <section class="w-full bg-black brake-service-clip lg:rounded-[32px] overflow-hidden">
        <div class="max-w-[1440px] mx-auto text-white px-6 py-10 lg:py-20 relative">
            <!-- Header -->
            <div class="lg:text-center mb-8 lg:mb-16 max-w-xl mx-auto">
                <h2
                    class="font-geist font-bold text-[48px] lg:text-[64px] uppercase leading-[0.9] lg:leading-[0.86] tracking-[-0.06em] mb-4">
                    Why Is an MOT Test Important?
                </h2>
                <p class="text-xl text-white/80 tracking-[-0.04em] font-medium">
                    An MOT is a legal annual inspection for vehicles over 3 years in the UK, designed to ensure they meet
                    the minimum safety, roadworthiness, and environmental standards.
                </p>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

                <!-- Card 1 -->
                <div
                    class="relative overflow-hidden bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl flex flex-col p-8">
                    <!-- Ghost watermark number -->
                    <span
                        class="pointer-events-none select-none absolute -top-2 -right-2 font-geist font-bold text-[100px] leading-none tracking-[-0.06em] text-white/[0.06]">01</span>
                    <!-- Step label -->
                    <div class="flex flex-col gap-3">
                        <h3
                            class="font-geist font-bold text-[26px] lg:text-[30px] uppercase leading-[0.9] tracking-[-0.04em] text-white">
                            Stay Legal
                        </h3>
                        <div class="w-8 h-[2px] bg-white/20 rounded-full"></div>
                        <p
                            class="font-geist text-white/80 text-[15px] lg:text-[16px] leading-[1.5] tracking-[-0.02em] font-medium">
                            Vehicles older than three years must pass an MOT every year to remain legally roadworthy.
                            Driving without a valid MOT certificate could lead to fines, penalty points, or invalid
                            insurance.
                        </p>
                    </div>
                </div>

                <!-- Card 2 (accent) -->
                <div class="relative overflow-hidden bg-primary rounded-2xl flex flex-col p-8">
                    <!-- Ghost watermark number -->
                    <span
                        class="pointer-events-none select-none absolute -top-2 -right-2 font-geist font-bold text-[100px] leading-none tracking-[-0.06em] text-white/[0.10]">02</span>
                    <!-- Step label -->
                    <div class="flex flex-col gap-3">
                        <h3
                            class="font-geist font-bold text-[26px] lg:text-[30px] uppercase leading-[0.9] tracking-[-0.04em] text-white">
                            Drive Safely
                        </h3>
                        <div class="w-8 h-[2px] bg-white/30 rounded-full"></div>
                        <p
                            class="font-geist text-white/80 text-[15px] lg:text-[16px] leading-[1.5] tracking-[-0.02em] font-medium">
                            The MOT inspection checks vital safety components such as brakes, tyres, lights, steering, and
                            suspension to ensure your vehicle is road worthy to operate on public roads.
                        </p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div
                    class="relative overflow-hidden bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl flex flex-col p-8">
                    <!-- Ghost watermark number -->
                    <span
                        class="pointer-events-none select-none absolute -top-2 -right-2 font-geist font-bold text-[100px] leading-none tracking-[-0.06em] text-white/[0.06]">03</span>
                    <!-- Step label -->
                    <div class="flex flex-col gap-3">
                        <h3
                            class="font-geist font-bold text-[26px] lg:text-[30px] uppercase leading-[0.9] tracking-[-0.04em] text-white">
                            Peace of Mind
                        </h3>
                        <div class="w-8 h-[2px] bg-white/20 rounded-full"></div>
                        <p
                            class="font-geist text-white/80 text-[15px] lg:text-[16px] leading-[1.5] tracking-[-0.02em] font-medium">
                            By identifying potential issues early, an MOT test helps prevent costly breakdowns and repairs
                            later on.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Simple MOT Process Section -->
    <section class="bg-white py-10 lg:py-24 w-full">
        <div class="max-w-[1440px] mx-auto px-6">

            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12 lg:mb-16">
                <div>
                    <h2
                        class="font-geist font-bold text-[40px] lg:text-[60px] leading-[0.88] tracking-[-0.05em] text-black uppercase">
                        Simple MOT<br class="hidden lg:block"> Process
                    </h2>
                </div>
                <p
                    class="font-geist text-[#0A0A0A] text-lg lg:text-xl leading-[1.6] tracking-[-0.02em] font-medium lg:max-w-xs">
                    At Simply Motoring, we keep the MOT process quick so you can get back on the road without delays.
                </p>
            </div>

            <!-- Steps Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Step 1: Pre-MOT Check -->
                <div class="relative overflow-hidden bg-gray-50 border border-gray-100 rounded-2xl p-7 flex flex-col gap-4">
                    <span
                        class="pointer-events-none select-none absolute -top-2 -right-2 font-geist font-bold text-[90px] leading-none tracking-[-0.06em] text-black/[0.04]">01</span>
                    <span class="text-[10px] font-bold tracking-[0.18em] text-gray-400 uppercase">Step 01</span>
                    <div class="flex flex-col gap-3">
                        <h3
                            class="font-geist font-bold text-[20px] lg:text-[22px] uppercase leading-[0.9] tracking-[-0.04em] text-black">
                            Pre-MOT Check
                        </h3>
                        <div class="w-6 h-[2px] bg-gray-200 rounded-full"></div>
                        <p class="font-geist text-[#0A0A0A] text-[16px] leading-[1.55] tracking-[-0.02em] font-medium">
                            Our technicians perform a quick inspection to identify potential issues before the official test
                            begins.
                        </p>
                    </div>
                </div>

                <!-- Step 2: MOT Test -->
                <div class="relative overflow-hidden bg-gray-50 border border-gray-100 rounded-2xl p-7 flex flex-col gap-4">
                    <span
                        class="pointer-events-none select-none absolute -top-2 -right-2 font-geist font-bold text-[90px] leading-none tracking-[-0.06em] text-black/[0.04]">02</span>
                    <span class="text-[10px] font-bold tracking-[0.18em] text-gray-400 uppercase">Step 02</span>
                    <div class="flex flex-col gap-3">
                        <h3
                            class="font-geist font-bold text-[20px] lg:text-[22px] uppercase leading-[0.9] tracking-[-0.04em] text-black">
                            MOT Test
                        </h3>
                        <div class="w-6 h-[2px] bg-gray-200 rounded-full"></div>
                        <p class="font-geist text-[#0A0A0A] text-[16px] leading-[1.55] tracking-[-0.02em] font-medium">
                            Your vehicle undergoes a full inspection carried out by our qualified DVSA-approved testers.
                        </p>
                    </div>
                </div>

                <!-- Step 3: MOT Results -->
                <div class="relative overflow-hidden bg-black rounded-2xl p-7 flex flex-col gap-4">
                    <span
                        class="pointer-events-none select-none absolute -top-2 -right-2 font-geist font-bold text-[90px] leading-none tracking-[-0.06em] text-white/[0.10]">03</span>
                    <span class="text-[10px] font-bold tracking-[0.18em] text-white/30 uppercase">Step 03</span>
                    <div class="flex flex-col gap-3">
                        <h3
                            class="font-geist font-bold text-[20px] lg:text-[22px] uppercase leading-[0.9] tracking-[-0.04em] text-white">
                            MOT Results
                        </h3>
                        <div class="w-6 h-[2px] bg-white/20 rounded-full"></div>
                        <!-- Pass / Fail outcomes -->
                        <div class="flex flex-col gap-3 mt-1">
                            <div class="flex items-start gap-3">
                                <span
                                    class="mt-[3px] w-5 h-5 rounded-full bg-green-500/20 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-check text-green-400 text-[9px]"></i>
                                </span>
                                <p
                                    class="font-geist text-white/80 text-[16px] leading-[1.5] tracking-[-0.02em] font-medium">
                                    <span class="text-white font-bold">Pass —</span> You receive an MOT certificate valid
                                    for at least 12 months or can be 13 months in some circumstances.
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span
                                    class="mt-[3px] w-5 h-5 rounded-full bg-red-500/20 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-xmark text-red-400 text-[9px]"></i>
                                </span>
                                <p
                                    class="font-geist text-white/80 text-[16px] leading-[1.5] tracking-[-0.02em] font-medium">
                                    <span class="text-white font-bold">Fail —</span> We provide a detailed explanation of
                                    every issue that needs attention.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Repairs if Needed -->
                <div class="relative overflow-hidden bg-primary rounded-2xl p-7 flex flex-col gap-4">
                    <span
                        class="pointer-events-none select-none absolute -top-2 -right-2 font-geist font-bold text-[90px] leading-none tracking-[-0.06em] text-white/[0.10]">04</span>
                    <span class="text-[10px] font-bold tracking-[0.18em] text-white/40 uppercase">Step 04</span>
                    <div class="flex flex-col gap-3">
                        <h3
                            class="font-geist font-bold text-[20px] lg:text-[22px] uppercase leading-[0.9] tracking-[-0.04em] text-white">
                            Repairs if Needed
                        </h3>
                        <div class="w-6 h-[2px] bg-white/30 rounded-full"></div>
                        <p class="font-geist text-white/85 text-[16px] leading-[1.55] tracking-[-0.02em] font-medium">
                            Should your vehicle require repairs, our experienced technicians fix problems quickly and
                            efficiently so your vehicle can pass the test.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- What Does an MOT Test Include? -->
    <section class="w-full bg-black brake-service-clip lg:rounded-[32px] overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-6 py-10 lg:py-20">

            <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-start">

                <!-- Left: Heading + text -->
                <div class="w-full lg:w-[38%] flex flex-col gap-6 lg:sticky lg:top-24">

                    <h2
                        class="font-geist font-bold text-[40px] lg:text-[54px] leading-[0.88] tracking-[-0.05em] text-white uppercase">
                        What Does an<br>MOT Test<br>Include?
                    </h2>

                    <div class="w-10 h-[3px] bg-primary rounded-full"></div>

                    <p class="font-geist text-white/80 text-lg lg:text-xl leading-[1.6] tracking-[-0.02em] font-medium">
                        An MOT inspection provides a comprehensive snapshot of your vehicle's overall condition, checking
                        all the critical systems that keep you and others safe on the road.
                    </p>

                    <p
                        class="font-geist text-white/50 text-[16px] leading-[1.6] tracking-[-0.02em] font-medium border-l-2 border-primary/60 pl-4">
                        This thorough inspection confirms that your car is compliant with UK road
                        regulations.
                    </p>
                </div>

                <!-- Right: Checklist Grid -->
                <div class="w-full lg:w-[62%] grid grid-cols-1 sm:grid-cols-2 gap-3">

                    <!-- Item 1 -->
                    <div
                        class="flex items-center gap-4 bg-white/[0.06] border border-white/10 rounded-2xl px-5 py-4 group hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                        <div
                            class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center shrink-0 group-hover:bg-primary/30 transition-colors duration-200">
                            <i class="fa-solid fa-gauge-high text-primary text-sm"></i>
                        </div>
                        <span class="font-geist font-medium text-white text-[16px] leading-tight tracking-[-0.03em]">Brakes
                            & braking performance</span>
                    </div>

                    <!-- Item 2 -->
                    <div
                        class="flex items-center gap-4 bg-white/[0.06] border border-white/10 rounded-2xl px-5 py-4 group hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                        <div
                            class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center shrink-0 group-hover:bg-primary/30 transition-colors duration-200">
                            <i class="fa-solid fa-lightbulb text-primary text-sm"></i>
                        </div>
                        <span class="font-geist font-medium text-white text-[16px] leading-tight tracking-[-0.03em]">Lights,
                            indicators & electrical systems</span>
                    </div>

                    <!-- Item 3 -->
                    <div
                        class="flex items-center gap-4 bg-white/[0.06] border border-white/10 rounded-2xl px-5 py-4 group hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                        <div
                            class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center shrink-0 group-hover:bg-primary/30 transition-colors duration-200">
                            <i class="fa-solid fa-circle-nodes text-primary text-sm"></i>
                        </div>
                        <span
                            class="font-geist font-medium text-white text-[16px] leading-tight tracking-[-0.03em]">Steering
                            & suspension</span>
                    </div>

                    <!-- Item 4 -->
                    <div
                        class="flex items-center gap-4 bg-white/[0.06] border border-white/10 rounded-2xl px-5 py-4 group hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                        <div
                            class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center shrink-0 group-hover:bg-primary/30 transition-colors duration-200">
                            <i class="fa-solid fa-circle-dot text-primary text-sm"></i>
                        </div>
                        <span class="font-geist font-medium text-white text-[16px] leading-tight tracking-[-0.03em]">Tyres &
                            wheel condition</span>
                    </div>

                    <!-- Item 5 -->
                    <div
                        class="flex items-center gap-4 bg-white/[0.06] border border-white/10 rounded-2xl px-5 py-4 group hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                        <div
                            class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center shrink-0 group-hover:bg-primary/30 transition-colors duration-200">
                            <i class="fa-solid fa-shield-halved text-primary text-sm"></i>
                        </div>
                        <span
                            class="font-geist font-medium text-white text-[16px] leading-tight tracking-[-0.03em]">Seatbelts
                            & safety systems</span>
                    </div>

                    <!-- Item 6 -->
                    <div
                        class="flex items-center gap-4 bg-white/[0.06] border border-white/10 rounded-2xl px-5 py-4 group hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                        <div
                            class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center shrink-0 group-hover:bg-primary/30 transition-colors duration-200">
                            <i class="fa-solid fa-wind text-primary text-sm"></i>
                        </div>
                        <span class="font-geist font-medium text-white text-[16px] leading-tight tracking-[-0.03em]">Exhaust
                            & emissions</span>
                    </div>

                    <!-- Item 7 -->
                    <div
                        class="flex items-center gap-4 bg-white/[0.06] border border-white/10 rounded-2xl px-5 py-4 group hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                        <div
                            class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center shrink-0 group-hover:bg-primary/30 transition-colors duration-200">
                            <i class="fa-solid fa-eye text-primary text-sm"></i>
                        </div>
                        <span class="font-geist font-medium text-white text-[16px] leading-tight tracking-[-0.03em]">Mirrors
                            & windscreen visibility</span>
                    </div>

                    <!-- Item 8 -->
                    <div
                        class="flex items-center gap-4 bg-white/[0.06] border border-white/10 rounded-2xl px-5 py-4 group hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                        <div
                            class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center shrink-0 group-hover:bg-primary/30 transition-colors duration-200">
                            <i class="fa-solid fa-car text-primary text-sm"></i>
                        </div>
                        <span class="font-geist font-medium text-white text-[16px] leading-tight tracking-[-0.03em]">Vehicle
                            structure & body condition</span>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="bg-white py-10 lg:py-20 w-full">
        <div class="max-w-[1440px] px-6 mx-auto relative">
            <!-- Content Container -->
            <div class="flex flex-col lg:flex-row justify-between gap-10 lg:gap-20">

                <!-- Left Column: Title & CTA -->
                <div class="w-full lg:w-1/2 flex flex-col items-start justify-between text-left">
                    <div>
                        <h2
                            class="font-geist font-bold text-[40px] lg:text-[64px] leading-[0.83] tracking-[-0.06em] uppercase text-black mb-4 lg:mb-10">
                            Why Choose Simply Motoring for Your MOT Test?
                        </h2>
                        <p class="text-[#0A0A0A] font-medium text-[20px] lg:text-[20px] leading-[1.3] mb-10">
                            Drivers across Doncaster choose Simply Motoring because we make vehicle maintenance simple,
                            transparent, and reliable.
                        </p>
                    </div>

                    <div
                        class="inline-block p-[1px] bg-white/40 hero-btn-clip transform rounded-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                        <a href="tel:0123456789"
                            class="inline-flex items-center justify-center lg:w-full bg-black text-white text-sm font-bold uppercase tracking-widest px-8 py-4 hero-btn-clip rounded-md hover:bg-primary hover:text-white transition-colors">
                            CALL FOR A QUOTE
                        </a>
                    </div>
                </div>

                <!-- Right Column: Feature Cards -->
                <div class="w-full lg:w-1/2 flex flex-col gap-4">

                    <!-- Card 1 -->
                    <div class="rounded-xl overflow-hidden backdrop-blur-md bg-primary/5 border border-black/10">
                        <div class="bg-primary px-6 py-4">
                            <h3
                                class="font-geist font-bold text-white text-[18px] lg:text-[20px] leading-[1.06] uppercase tracking-wide">
                                Flexible Appointments
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-black/80 text-[16px] lg:text-[18px] leading-relaxed font-medium">
                                We offer convenient booking slots that fit around your schedule.
                            </p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="rounded-xl overflow-hidden backdrop-blur-md bg-black/5 border border-black/10">
                        <div class="bg-black px-6 py-4">
                            <h3
                                class="font-geist font-bold text-white text-[18px] lg:text-[20px] leading-[1.06] uppercase tracking-wide">
                                Affordable Pricing
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-black/80 text-[16px] lg:text-[18px] leading-relaxed font-medium">
                                Our MOT tests are available for just £45, offering excellent value with no hidden charges.
                            </p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="rounded-xl overflow-hidden backdrop-blur-md bg-primary/5 border border-black/10">
                        <div class="bg-primary px-6 py-4">
                            <h3
                                class="font-geist font-bold text-white text-[18px] lg:text-[20px] leading-[1.06] uppercase tracking-wide">
                                Clear Advice
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-black/80 text-[16px] lg:text-[18px] leading-relaxed font-medium">
                                Our technicians explain the results clearly so you understand exactly what your vehicle
                                needs.
                            </p>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="rounded-xl overflow-hidden backdrop-blur-md bg-black/5 border border-black/10">
                        <div class="bg-black px-6 py-4">
                            <h3
                                class="font-geist font-bold text-white text-[18px] lg:text-[20px] leading-[1.06] uppercase tracking-wide">
                                Trusted Local Garage
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-black/80 text-[16px] lg:text-[18px] leading-relaxed font-medium">
                                Our garage is known for honest service, professional workmanship, and a customer-focused
                                approach.
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-white py-10 lg:py-20 w-full">
        <div class="max-w-[1440px] mx-auto lg:px-6">
            <div class="bg-black w-full lg:w-[80%] mx-auto px-8 py-16 lg:py-24 text-center flex flex-col items-center gap-7 lg:rounded-3xl"
                style="clip-path: polygon(80px 0, 100% 0, 100% calc(100% - 80px), calc(100% - 80px) 100%, 0 100%, 0 80px);">

                <h2
                    class="font-geist font-bold text-[36px] lg:text-[60px] leading-[0.88] tracking-[-0.05em] text-white uppercase">
                    Visit Our MOT Test<br>Centre in <span class="text-primary">Doncaster</span>
                </h2>

                <p
                    class="font-geist text-white/80 text-lg lg:text-xl leading-[1.6] tracking-[-0.02em] font-medium max-w-2xl">
                    Our experienced team is ready to provide professional MOT testing, repairs, and vehicle inspections in a
                    clean, modern workshop. Whether you need your annual MOT or additional repairs, we're here to help.
                </p>

                <div
                    class="inline-block p-[1px] bg-white/80 hero-btn-clip transform rounded-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                    <a href="tel:0123456789"
                        class="inline-flex items-center justify-center lg:w-full bg-primary text-white text-sm font-bold uppercase tracking-widest px-8 py-4 hero-btn-clip rounded-md hover:bg-white hover:text-primary transition-colors">
                        Book your mot test now
                    </a>
                </div>

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