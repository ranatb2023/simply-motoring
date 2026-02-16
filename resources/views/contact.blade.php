@extends('layouts.main')

@section('content')
    <style>
        /* Clip path for desktop screens only */
        @media (min-width: 1024px) {
            .clipped-bg {
                clip-path: polygon(80px 0, 100% 0, 100% calc(100% - 80px), calc(100% - 80px) 100%, 0 100%, 0 80px);
            }
        }
    </style>

    <!-- Hero Section -->
    <div class="w-full bg-white min-h-screen relative">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-10 py-10 lg:py-20">
            <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-stretch">
                <!-- Left Column -->
                <div class="w-full lg:w-1/2 flex flex-col justify-between">
                    <div>
                        <!-- Badge -->
                        <span
                            class="inline-block bg-primary text-white text-xs lg:text-sm font-bold px-4 py-2 rounded-full uppercase tracking-wide mb-8">
                            CONTACT US
                        </span>

                        <!-- Heading -->
                        <h1
                            class="text-black font-geist font-bold text-[56px] lg:text-[96px] leading-[0.85] tracking-tighter uppercase mb-8 typewriter-effect">
                            YOUR EASE,<br>SIMPLIFIED.
                        </h1>

                        <!-- Description -->
                        <p class="text-black/80 font-medium text-lg lg:text-xl leading-relaxed max-w-md mb-12">
                            We are here to help with all your car service and MOT needs. Our experienced technicians are
                            dedicated to providing top-notch services and ensuring your complete satisfaction.
                        </p>
                    </div>

                    <!-- Layout Spacer for larger screens -->
                    <div class="hidden lg:block flex-grow"></div>

                    <!-- Contact Details -->
                    <div class="space-y-2 text-black font-bold font-geist text-lg lg:text-xl mt-auto">
                        <a href="mailto:hello@simplymotoring.uk"
                            class="block hover:text-primary transition-colors">hello@simplymotoring.uk</a>
                        <a href="tel:01302456406" class="block hover:text-primary transition-colors">01302 456 406</a>
                        <p class="leading-tight">243A Sprotbrough Road, Doncaster, DN5 8BP</p>
                    </div>
                </div>

                <!-- Right Column (Form) -->
                <div class="w-full lg:w-1/2 relative">
                    <!-- Background Shape -->
                    <div class="absolute inset-0 bg-black w-full h-full rounded-[20px] clipped-bg">
                    </div>

                    <!-- Content Container -->
                    <div class="relative w-full h-full p-6 lg:p-14 z-10">

                        <div class="mb-10">
                            <h2
                                class="text-white font-geist font-bold text-4xl lg:text-5xl leading-tight tracking-tight mb-2">
                                Convenience that puts you first
                            </h2>
                            <p class="text-white/60 text-lg mt-4">Fill in the form below</p>
                        </div>

                        <form action="#" method="POST" class="space-y-4">
                            <!-- Full Name & Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" placeholder="Full Name*"
                                    class="w-full bg-[#111111] text-white px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary placeholder-gray-500 border border-white/5 font-medium">
                                <input type="email" placeholder="Email*"
                                    class="w-full bg-[#111111] text-white px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary placeholder-gray-500 border border-white/5 font-medium">
                            </div>

                            <!-- Phone & Date -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="tel" placeholder="Phone*"
                                    class="w-full bg-[#111111] text-white px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary placeholder-gray-500 border border-white/5 font-medium">
                                <input type="text" placeholder="Date*" onfocus="(this.type='date')"
                                    onblur="(this.type='text')"
                                    class="w-full bg-[#111111] text-white px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary placeholder-gray-500 border border-white/5 font-medium">
                            </div>

                            <!-- Service Select -->
                            <div class="relative" x-data="{ 
                                                            open: false, 
                                                            selected: '', 
                                                            selectedValue: '',
                                                            options: [
                                                                { value: 'brake_discs_pads', label: 'Brake discs & pads' },
                                                                { value: 'brake_fluid_change', label: 'Brake fluid change' },
                                                                { value: 'full_service', label: 'Full Service' },
                                                                { value: 'interim_service', label: 'Interim Service' },
                                                                { value: 'major_service', label: 'Major Service' },
                                                                { value: 'mot', label: 'MOT' }
                                                            ]
                                                        }">
                                <!-- Hidden Input for Form Submission -->
                                <input type="hidden" name="service" :value="selectedValue">

                                <!-- Trigger Button -->
                                <button type="button" @click="open = !open" @click.outside="open = false"
                                    class="w-full bg-[#111111] px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary border border-white/5 font-medium flex items-center justify-between transition-colors duration-200"
                                    :class="selected ? 'text-white' : 'text-gray-500'">
                                    <span x-text="selected || 'Select Service*'"></span>
                                    <i class="fa-solid fa-chevron-down text-white/50 transition-transform duration-200"
                                        :class="open ? 'rotate-180' : ''"></i>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                                    class="absolute z-[100] w-full mt-2 bg-[#111111] border border-white/10 rounded-lg shadow-xl overflow-hidden py-1">
                                    <template x-for="option in options" :key="option.value">
                                        <div @click="selected = option.label; selectedValue = option.value; open = false"
                                            class="px-5 py-3 text-white hover:bg-primary/20 hover:text-primary cursor-pointer transition-colors duration-150 flex items-center justify-between group">
                                            <span x-text="option.label"></span>
                                            <i class="fa-solid fa-check text-primary opacity-0 group-hover:opacity-100 transition-opacity"
                                                x-show="selectedValue === option.value" style="display: none;"
                                                :style="selectedValue === option.value ? 'display: block' : ''"></i>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Message -->
                            <textarea placeholder="Message*" rows="4"
                                class="w-full bg-[#111111] text-white px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary placeholder-gray-500 border border-white/5 font-medium resize-none"></textarea>

                            <!-- Submit Button -->
                            <div class="mt-8">
                                <button type="submit"
                                    class="bg-primary text-white font-bold uppercase px-10 py-4 relative hover:bg-[#e05000] transition-colors"
                                    style="clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px); border-radius: 8px;">
                                    SUBMIT
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="w-full h-[400px] lg:h-[600px] relative bg-gray-200 group overflow-hidden" x-data="{ 
                                                                show: false,
                                                                init() {
                                                                    const observer = new IntersectionObserver((entries) => {
                                                                        entries.forEach(entry => {
                                                                            if (entry.isIntersecting) {
                                                                                this.show = true;
                                                                                observer.disconnect();
                                                                            }
                                                                        });
                                                                    }, { threshold: 0.2 });
                                                                    observer.observe(this.$el);
                                                                }
                                                            }">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2379.794625841755!2d-1.155!3d53.535!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4879017686526189%3A0x6408226955734204!2sSimply%20Motoring!5e0!3m2!1sen!2suk!4v1700000000000!5m2!1sen!2suk&q=243A+Sprotbrough+Road,+Doncaster,+DN5+8BP"
            width="100%" height="100%" style="border:0; filter: grayscale(100%);" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade" class="relative z-0"></iframe>

        <!-- Location Marker Overlay -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-full z-10 pointer-events-none pb-4"
            x-show="show" x-transition:enter="transition ease-out duration-700 delay-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div
                class="bg-white p-2 rounded-lg shadow-2xl flex flex-col items-center justify-center border border-gray-100 relative">
                <img src="{{ asset('images/logo.png') }}" alt="Simply Motoring" class="h-4 w-auto object-contain">
                <!-- Triangular Pointer -->
                <div
                    class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white rotate-45 transform border-b border-r border-gray-100 shadow-sm">
                </div>
            </div>
            <!-- Pulse Effect at the tip -->
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2">
                <div class="w-4 h-4 bg-primary rounded-full animate-ping opacity-75"></div>
                <div class="w-3 h-3 bg-primary rounded-full absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                </div>
            </div>
        </div>
    </div>
@endsection