@extends('layouts.main')

@section('meta_description', 'View transparent pricing for MOT tests, interim services, full services and major services. No hidden costs, just honest value.')

@section('meta_title', 'MOT & Service Pricing in Doncaster | Simply Motoring')

@section('content')
    <style>
        .pricing-clip {
            clip-path: polygon(40px 0, 100% 0, 100% calc(100% - 40px), calc(100% - 40px) 100%, 0 100%, 0 40px);
        }

        @media (min-width: 1024px) {
            .pricing-hero-clip {
                clip-path: polygon(80px 0, 100% 0, 100% calc(100% - 80px), calc(100% - 80px) 100%, 0 100%, 0 80px);
            }
        }

        .price-nav-link.active {
            color: #FF6900;
            background-color: #fff5ee;
            font-weight: 700;
        }

        .price-nav-link.active i {
            color: #FF6900;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <!-- ═══════════════════════════════════════════════════════════
                                                 HERO
                                            ═══════════════════════════════════════════════════════════ -->
    <div class="max-w-[1440px] mx-auto p-0 lg:p-6 w-full">
        <section
            class="relative w-full min-h-[70vh] overflow-hidden lg:rounded-2xl pricing-hero-clip bg-black flex flex-col">
            <div class="absolute inset-0 z-0"
                style="background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px); background-size: 32px 32px;">
            </div>
            <div
                class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-primary/10 rounded-full blur-[120px] z-0 pointer-events-none">
            </div>
            <div class="relative z-10 flex flex-col justify-between flex-1 py-10 lg:py-24 px-6 lg:px-20">
                <div class="mt-20 lg:mt-10 max-w-4xl">
                    <div
                        class="inline-flex items-center gap-2 bg-primary/15 border border-primary/30 rounded-full px-4 py-2 mb-6">
                        <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
                        <span class="text-primary text-[11px] font-bold tracking-[0.18em] uppercase">Transparent
                            Pricing</span>
                    </div>
                    <h1
                        class="text-white font-geist font-bold text-[52px] lg:text-[96px] leading-[0.86] tracking-[-0.06em] uppercase mb-6">
                        All Our Prices.<br>No <span class="text-primary">Surprises.</span>
                    </h1>
                    <p class="max-w-xl text-white/80 text-lg lg:text-xl font-medium leading-[1.65] tracking-[-0.02em]">
                        Every service, every price is transparent. We believe in honest car care with no hidden fees, no
                        upsells, and no nasty bills at the end.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3 mt-14 lg:mt-8">
                    <div
                        class="flex items-center gap-3 bg-white/[0.07] border border-white/10 rounded-xl px-5 py-3 backdrop-blur-sm">
                        <i class="fa-solid fa-list-check text-primary text-sm"></i>
                        <span class="text-white font-bold text-[13px] tracking-[-0.01em]">33+ Services Listed</span>
                    </div>
                    <div
                        class="flex items-center gap-3 bg-white/[0.07] border border-white/10 rounded-xl px-5 py-3 backdrop-blur-sm">
                        <i class="fa-solid fa-tag text-primary text-sm"></i>
                        <span class="text-white font-bold text-[13px] tracking-[-0.01em]">No Hidden Costs</span>
                    </div>
                    <div
                        class="flex items-center gap-3 bg-white/[0.07] border border-white/10 rounded-xl px-5 py-3 backdrop-blur-sm">
                        <i class="fa-solid fa-shield-halved text-primary text-sm"></i>
                        <span class="text-white font-bold text-[13px] tracking-[-0.01em]">Price Match Guarantee</span>
                    </div>
                    <div
                        class="flex items-center gap-3 bg-white/[0.07] border border-white/10 rounded-xl px-5 py-3 backdrop-blur-sm">
                        <i class="fa-solid fa-location-dot text-primary text-sm"></i>
                        <span class="text-white font-bold text-[13px] tracking-[-0.01em]">Doncaster Workshop</span>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- ═══════════════════════════════════════════════════════════
                                                 FEATURED / MOST POPULAR
                                            ═══════════════════════════════════════════════════════════ -->
    <section class="bg-white py-12 lg:py-20">
        <div class="max-w-[1440px] mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-4 lg:gap-6 items-end mb-10 lg:mb-14">
                <div class="flex-1">
                    <h2
                        class="font-geist font-bold text-[36px] lg:text-[52px] leading-[0.9] tracking-[-0.05em] text-black uppercase">
                        Our Most Booked<br>Services
                    </h2>
                </div>
                <p class="max-w-sm text-[#0A0A0A] text-lg lg:text-xl leading-[1.7] lg:text-right">
                    These three services account for the majority of bookings — all at unbeatable fixed prices.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-5">
                <!-- MOT Test -->
                <div class="bg-black rounded-2xl p-7 flex flex-col gap-5 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300"
                    style="clip-path: polygon(24px 0, 100% 0, 100% calc(100% - 24px), calc(100% - 24px) 100%, 0 100%, 0 24px);">
                    <span
                        class="pointer-events-none select-none absolute -bottom-4 -right-4 font-geist font-bold text-[100px] leading-none tracking-[-0.06em] text-white/[0.04] uppercase">MOT</span>
                    <div class="w-11 h-11 bg-primary rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-clipboard-check text-white text-base"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.15em] text-white/35 uppercase mb-1.5">MOT & Inspections
                        </p>
                        <h3 class="font-geist font-bold text-[22px] text-white leading-tight tracking-[-0.03em]">MOT Test
                        </h3>
                        <p class="text-white/80 text-[16px] mt-1.5 leading-[1.5]">Annual roadworthiness test. Quick,
                            thorough, and stress-free.</p>
                    </div>
                    <div class="mt-auto pt-4 border-t border-white/10 flex items-end justify-between">
                        <div>
                            <p class="font-geist font-bold text-[40px] text-white leading-none tracking-[-0.05em]">£45</p>
                        </div>
                        <a href="#book"
                            class="inline-flex items-center gap-2 bg-primary text-white text-[11px] font-bold uppercase tracking-widest px-5 py-3 rounded-xl hover:bg-orange-600 transition-colors">
                            Book <i class="fa-solid fa-arrow-right text-[9px]"></i>
                        </a>
                    </div>
                </div>
                <!-- Full Service -->
                <div class="bg-primary rounded-2xl p-7 flex flex-col gap-5 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300"
                    style="clip-path: polygon(24px 0, 100% 0, 100% calc(100% - 24px), calc(100% - 24px) 100%, 0 100%, 0 24px);">
                    <span
                        class="pointer-events-none select-none absolute -bottom-4 -right-4 font-geist font-bold text-[90px] leading-none tracking-[-0.06em] text-white/[0.08] uppercase">SVC</span>
                    <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-wrench text-white text-base"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.15em] text-white/60 uppercase mb-1.5">Servicing</p>
                        <h3 class="font-geist font-bold text-[22px] text-white leading-tight tracking-[-0.03em]">Full
                            Service</h3>
                        <p class="text-white/80 text-[16px] mt-1.5 leading-[1.5]">Comprehensive vehicle service covering all
                            major systems.</p>
                    </div>
                    <div class="mt-auto pt-4 border-t border-white/20 flex items-end justify-between">
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.1em] text-white/60 uppercase mb-1">From</p>
                            <p class="font-geist font-bold text-[40px] text-white leading-none tracking-[-0.05em]">£150</p>
                        </div>
                        <a href="#book"
                            class="inline-flex items-center gap-2 bg-black text-white text-[11px] font-bold uppercase tracking-widest px-5 py-3 rounded-xl hover:bg-black/80 transition-colors">
                            Book <i class="fa-solid fa-arrow-right text-[9px]"></i>
                        </a>
                    </div>
                </div>
                <!-- Brake Pads -->
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-7 flex flex-col gap-5 relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300"
                    style="clip-path: polygon(24px 0, 100% 0, 100% calc(100% - 24px), calc(100% - 24px) 100%, 0 100%, 0 24px);">
                    <span
                        class="pointer-events-none select-none absolute -bottom-4 -right-4 font-geist font-bold text-[90px] leading-none tracking-[-0.06em] text-black/[0.04] uppercase">BRK</span>
                    <div class="w-11 h-11 bg-black rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-circle-stop text-white text-base"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.15em] text-gray-400 uppercase mb-1.5">Brakes</p>
                        <h3 class="font-geist font-bold text-[22px] text-black leading-tight tracking-[-0.03em]">Brake Pads
                        </h3>
                        <p class="text-[#0A0A0A] text-[16px] mt-1.5 leading-[1.5]">Quality pads fitted by experienced brake
                            specialists.</p>
                    </div>
                    <div class="mt-auto pt-4 border-t border-gray-200 flex items-end justify-between">
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.1em] text-gray-400 uppercase mb-1">From</p>
                            <p class="font-geist font-bold text-[40px] text-black leading-none tracking-[-0.05em]">£120</p>
                        </div>
                        <a href="#book"
                            class="inline-flex items-center gap-2 bg-black text-white text-[11px] font-bold uppercase tracking-widest px-5 py-3 rounded-xl hover:bg-gray-800 transition-colors">
                            Book <i class="fa-solid fa-arrow-right text-[9px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════
                                                 FULL PRICE GUIDE
                                            ═══════════════════════════════════════════════════════════ -->
    <section class="bg-gray-50 py-12 lg:py-20 border-t border-gray-100">
        <div class="max-w-[1440px] mx-auto px-6">

            <div class="flex gap-3 lg:gap-16">

                <!-- ─── Left: Sticky Category Navigation (Desktop) ─── -->
                <aside class="hidden lg:block w-[220px] xl:w-[260px] shrink-0">
                    <div class="sticky top-28">
                        <p class="text-[10px] font-bold tracking-[0.18em] text-gray-400 uppercase mb-4 px-3">Categories</p>
                        <nav class="flex flex-col gap-0.5" id="price-sidenav">
                            <a href="#cat-servicing"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-oil-can text-[11px] text-gray-400 w-4 text-center"></i>Servicing &
                                Maintenance</a>
                            <a href="#cat-mot"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-clipboard-check text-[11px] text-gray-400 w-4 text-center"></i>MOT &
                                Inspections</a>
                            <a href="#cat-brakes"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-circle-stop text-[11px] text-gray-400 w-4 text-center"></i>Brakes</a>
                            <a href="#cat-suspension"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-car-side text-[11px] text-gray-400 w-4 text-center"></i>Suspension &
                                Steering</a>
                            <a href="#cat-engine"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-gears text-[11px] text-gray-400 w-4 text-center"></i>Engine &
                                Diagnostics</a>
                            <a href="#cat-exhaust"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-wind text-[11px] text-gray-400 w-4 text-center"></i>Exhaust
                                System</a>
                            <a href="#cat-clutch"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-cog text-[11px] text-gray-400 w-4 text-center"></i>Clutch &
                                Gearbox</a>
                            <a href="#cat-timing"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-rotate text-[11px] text-gray-400 w-4 text-center"></i>Timing Belt</a>
                            <a href="#cat-cooling"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-temperature-low text-[11px] text-gray-400 w-4 text-center"></i>Cooling
                                System</a>
                            <a href="#cat-battery"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-bolt text-[11px] text-gray-400 w-4 text-center"></i>Battery &
                                Electrical</a>
                            <a href="#cat-wipers"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-eye text-[11px] text-gray-400 w-4 text-center"></i>Wipers, Lights &
                                Visibility</a>
                            <a href="#cat-bodywork"
                                class="price-nav-link flex items-center gap-3 text-[13px] font-medium text-gray-500 hover:text-black py-2.5 px-3 rounded-xl hover:bg-white transition-all duration-200"><i
                                    class="fa-solid fa-brush text-[11px] text-gray-400 w-4 text-center"></i>Bodywork</a>
                        </nav>
                        <div class="mt-6 bg-primary/10 border border-primary/20 rounded-2xl p-4">
                            <p class="text-[12px] font-semibold text-gray-700 leading-[1.6]">
                                <i class="fa-solid fa-circle-info text-primary mr-1.5"></i>
                                Prices marked "from" may vary depending on your vehicle make and model.
                            </p>
                        </div>
                    </div>
                </aside>

                <!-- ─── Right: Price Content ─── -->
                <div class="flex-1 min-w-0 flex flex-col gap-4">

                    <!-- Mobile tabs -->
                    <div class="lg:hidden -mx-6 px-6 overflow-x-auto no-scrollbar mb-2">
                        <div class="flex gap-2 pb-1" style="width: max-content;">
                            <a href="#cat-servicing"
                                class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-600 text-[12px] font-bold uppercase tracking-wide px-4 py-2 rounded-full whitespace-nowrap hover:bg-primary hover:text-white hover:border-primary transition-all"><i
                                    class="fa-solid fa-oil-can text-[10px]"></i> Servicing</a>
                            <a href="#cat-mot"
                                class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-600 text-[12px] font-bold uppercase tracking-wide px-4 py-2 rounded-full whitespace-nowrap hover:bg-primary hover:text-white hover:border-primary transition-all"><i
                                    class="fa-solid fa-clipboard-check text-[10px]"></i> MOT</a>
                            <a href="#cat-brakes"
                                class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-600 text-[12px] font-bold uppercase tracking-wide px-4 py-2 rounded-full whitespace-nowrap hover:bg-primary hover:text-white hover:border-primary transition-all"><i
                                    class="fa-solid fa-circle-stop text-[10px]"></i> Brakes</a>
                            <a href="#cat-engine"
                                class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-600 text-[12px] font-bold uppercase tracking-wide px-4 py-2 rounded-full whitespace-nowrap hover:bg-primary hover:text-white hover:border-primary transition-all"><i
                                    class="fa-solid fa-gears text-[10px]"></i> Engine</a>
                            <a href="#cat-battery"
                                class="inline-flex items-center gap-1.5 bg-white border border-gray-200 text-gray-600 text-[12px] font-bold uppercase tracking-wide px-4 py-2 rounded-full whitespace-nowrap hover:bg-primary hover:text-white hover:border-primary transition-all"><i
                                    class="fa-solid fa-bolt text-[10px]"></i> Battery</a>
                        </div>
                    </div>

                    {{-- ═══ HELPER MACROS ═══
                    Each category = full-width white card with:
                    - Gray header: number badge + icon + name + service count
                    - White body: items in 1-col (mobile) / 2-col (desktop) grid
                    --}}

                    <!-- ── 01 Servicing & Maintenance ── -->
                    <div id="cat-servicing"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-oil-can text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        01</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Servicing & Maintenance</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">7
                                services</span>
                        </div>
                        <div class="p-3 grid grid-cols-1 lg:grid-cols-2 gap-2">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200 group">
                                <div class="min-w-0">
                                    <p class="font-semibold text-[14px] text-black leading-tight">Interim Service</p>
                                </div>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £125</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Full Service</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £150</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Major Service</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">From</p>
                                    <p class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">£290</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Oil & Oil Filter Change</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">From</p>
                                    <p class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">£90</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Air Filter Replacement</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £45</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Cabin Filter Replacement</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £45</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Coolant & Brake Fluid Top-Up
                                </p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £10</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 02 MOT & Inspections ── -->
                    <div id="cat-mot"
                        class="price-section scroll-mt-28 bg-black border border-black rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-white/[0.06] border-b border-white/10">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-primary rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-clipboard-check text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-white/30 uppercase leading-none mb-0.5">
                                        02</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-white uppercase">
                                        MOT & Inspections</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-white/40 bg-white/[0.08] border border-white/10 rounded-full px-3 py-1 shrink-0 hidden sm:block">4
                                services</span>
                        </div>
                        <div class="p-3 grid grid-cols-1 lg:grid-cols-2 gap-2">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-white/[0.07] border border-transparent hover:border-white/10 transition-all duration-200 lg:col-span-2">
                                <div>
                                    <p class="font-semibold text-[14px] text-white leading-tight">MOT Test</p>
                                    <p class="text-[12px] text-white/40 mt-0.5">Annual roadworthiness certificate</p>
                                </div>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p
                                        class="font-geist font-bold text-[18px] text-primary leading-none tracking-[-0.04em]">
                                        £45</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-white/[0.07] border border-transparent hover:border-white/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-white leading-tight">Pre-MOT Inspection</p>
                                <p
                                    class="font-geist font-bold text-[18px] text-white leading-none tracking-[-0.04em] ml-4 shrink-0">
                                    £30</p>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-white/[0.07] border border-transparent hover:border-white/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-white leading-tight">Full Vehicle Health Check</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-white/40 uppercase leading-none">From</p>
                                    <p class="font-geist font-bold text-[18px] text-white leading-none tracking-[-0.04em] mt-0.5">£190</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 03 Brakes ── -->
                    <div id="cat-brakes"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-circle-stop text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        03</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Brakes</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">3
                                services</span>
                        </div>
                        <div class="p-3 grid grid-cols-1 lg:grid-cols-3 gap-2">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Brake Pads</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £120</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Brake Discs & Pads</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £220</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Brake Fluid Change</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £96</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 04 Suspension & Steering ── -->
                    <div id="cat-suspension"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-car-side text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        04</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Suspension & Steering</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">1
                                service</span>
                        </div>
                        <div class="p-3">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Suspension & Steering Repairs
                                </p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £95</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 05 Engine & Diagnostics ── -->
                    <div id="cat-engine"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-gears text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        05</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Engine & Diagnostics</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">2
                                services</span>
                        </div>
                        <div class="p-3 grid grid-cols-1 lg:grid-cols-2 gap-2">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <div>
                                    <p class="font-semibold text-[14px] text-black leading-tight">Engine Diagnostics</p>
                                    <p class="text-[12px] text-gray-400 mt-0.5">OBD / ECU scan</p>
                                </div>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £54</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Electrical Diagnostics</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">From</p>
                                    <p class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">£96</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 06 Exhaust System ── -->
                    <div id="cat-exhaust"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-wind text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        06</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Exhaust System</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">2
                                services</span>
                        </div>
                        <div class="p-3 grid grid-cols-1 lg:grid-cols-2 gap-2">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Exhaust System Repair</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £54</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Exhaust System Replacement</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £150</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 07 Clutch & Gearbox ── -->
                    <div id="cat-clutch"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-cog text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        07</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Clutch & Gearbox</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">1
                                service</span>
                        </div>
                        <div class="p-3">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Clutch Replacement</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £300</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 08 Timing Belt ── -->
                    <div id="cat-timing"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-primary rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-rotate text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        08</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Timing Belt</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">1
                                service</span>
                        </div>
                        <div class="p-3">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <div>
                                    <p class="font-semibold text-[14px] text-black leading-tight">Timing Belt / Cam Belt
                                        Replacement</p>
                                    <p class="text-[12px] text-gray-400 mt-0.5">Includes belts, tensioner & water pump (if
                                        required)</p>
                                </div>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £550</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 09 Cooling System ── -->
                    <div id="cat-cooling"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-temperature-low text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        09</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Cooling System</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">1
                                service</span>
                        </div>
                        <div class="p-3">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Radiator & Cooling System
                                    Repairs</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £96</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 10 Battery & Electrical ── -->
                    <div id="cat-battery"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-bolt text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        10</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Battery & Electrical</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">2
                                services</span>
                        </div>
                        <div class="p-3 grid grid-cols-1 lg:grid-cols-2 gap-2">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 bg-black border border-transparent transition-all duration-200">
                                <div>
                                    <p class="font-semibold text-[14px] text-white leading-tight">Battery Testing</p>
                                    <p class="text-[12px] text-white/40 mt-0.5">Quick health check at no cost</p>
                                </div>
                                <span
                                    class="shrink-0 ml-4 inline-flex items-center gap-1.5 bg-green-500 text-white text-[11px] font-bold uppercase tracking-wide px-3 py-1.5 rounded-lg">
                                    <i class="fa-solid fa-check text-[9px]"></i> Free
                                </span>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Battery Replacement</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £144</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 11 Wipers, Lights & Visibility ── -->
                    <div id="cat-wipers"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-eye text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        11</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Wipers, Lights & Visibility</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">3
                                services</span>
                        </div>
                        <div class="p-3 grid grid-cols-1 lg:grid-cols-3 gap-2">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Wiper Replacement</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £30</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Washer Repairs</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £96</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Bulb Replacement</p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £15</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── 12 Bodywork ── -->
                    <div id="cat-bodywork"
                        class="price-section scroll-mt-28 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-brush text-white text-[11px]"></i>
                                </div>
                                <div>
                                    <p
                                        class="text-[9px] font-bold tracking-[0.2em] text-gray-400 uppercase leading-none mb-0.5">
                                        12</p>
                                    <h2
                                        class="font-geist font-bold text-[15px] lg:text-[17px] leading-none tracking-[-0.03em] text-black uppercase">
                                        Bodywork</h2>
                                </div>
                            </div>
                            <span
                                class="text-[11px] font-semibold text-gray-400 bg-white border border-gray-200 rounded-full px-3 py-1 shrink-0 hidden sm:block">1
                                service</span>
                        </div>
                        <div class="p-3">
                            <div
                                class="flex items-center justify-between rounded-xl px-4 py-3.5 hover:bg-orange-50/50 border border-transparent hover:border-primary/10 transition-all duration-200">
                                <p class="font-semibold text-[14px] text-black leading-tight">Bodywork Repairs & Painting
                                </p>
                                <div class="shrink-0 text-right ml-4 flex flex-col items-end">
                                    <p class="text-[9px] font-bold tracking-[0.1em] text-gray-400 uppercase leading-none">
                                        from</p>
                                    <p
                                        class="font-geist font-bold text-[18px] text-black leading-none tracking-[-0.04em] mt-0.5">
                                        £200</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════════
                                                 CTA
                                            ═══════════════════════════════════════════════════════════ -->

    <section class="bg-white py-10 lg:py-20 w-full">
        <div class="max-w-[1440px] mx-auto lg:px-6">
            <div class="bg-black w-full lg:w-[80%] mx-auto px-8 py-16 lg:py-24 text-center flex flex-col items-center gap-7 lg:rounded-3xl"
                style="clip-path: polygon(80px 0, 100% 0, 100% calc(100% - 80px), calc(100% - 80px) 100%, 0 100%, 0 80px);">
                <h2
                    class="font-geist font-bold text-[36px] lg:text-[60px] leading-[0.88] tracking-[-0.05em] text-white uppercase">
                    Ready to Book Your<br>Next <span class="text-primary">Service?</span>
                </h2>
                <p
                    class="font-geist text-white/80 text-lg lg:text-xl leading-[1.6] tracking-[-0.02em] font-medium max-w-2xl">
                    Choose your service from our full price list and book online in minutes. Fixed prices, no surprises.
                </p>
                <div
                    class="inline-block p-[1px] bg-white/80 hero-btn-clip transform rounded-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl group">
                    <a href="#book"
                        class="inline-flex items-center justify-center lg:w-full bg-primary text-white text-sm font-bold uppercase tracking-widest px-8 py-4 hero-btn-clip rounded-md hover:bg-white hover:text-primary transition-colors">
                        Book a service now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        (function () {
            const navLinks = document.querySelectorAll('#price-sidenav .price-nav-link');
            const sections = document.querySelectorAll('.price-section');

            if (!navLinks.length || !sections.length) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.id;
                        navLinks.forEach(link => {
                            link.classList.toggle('active', link.getAttribute('href') === `#${id}`);
                        });
                    }
                });
            }, { rootMargin: '-20% 0px -70% 0px' });

            sections.forEach(s => observer.observe(s));

            document.querySelectorAll('a[href^="#cat-"]').forEach(link => {
                link.addEventListener('click', e => {
                    const target = document.querySelector(link.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        })();
    </script>
@endsection