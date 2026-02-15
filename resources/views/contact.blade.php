@extends('layouts.main')

@section('content')
    <div class="w-full bg-white min-h-screen relative">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-10 py-10 lg:py-20">
            <div class="flex flex-col lg:flex-row gap-12 lg:gap-20 items-stretch">
                <!-- Left Column -->
                <div class="w-full lg:w-1/2 flex flex-col justify-between pt-10 lg:pt-0">
                    <div>
                        <!-- Badge -->
                        <span
                            class="inline-block bg-[#FF5C00] text-white text-xs lg:text-sm font-bold px-4 py-2 rounded-full uppercase tracking-wide mb-8">
                            CONTACT US
                        </span>

                        <!-- Heading -->
                        <h1
                            class="text-black font-geist font-bold text-[56px] lg:text-[100px] leading-[0.85] tracking-tighter uppercase mb-8 typewriter-effect">
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
                            class="block hover:text-[#FF5C00] transition-colors">hello@simplymotoring.uk</a>
                        <a href="tel:01302456406" class="block hover:text-[#FF5C00] transition-colors">01302 456 406</a>
                        <p class="max-w-xs leading-tight">243A Sprotbrough Road, Doncaster, DN5 8BP</p>
                    </div>
                </div>

                <!-- Right Column (Form) -->
                <div class="w-full lg:w-1/2">
                    <div class="bg-black w-full h-full p-8 lg:p-14 relative"
                        style="clip-path: polygon(40px 0, 100% 0, 100% calc(100% - 40px), calc(100% - 40px) 100%, 0 100%, 0 40px); border-radius: 20px;">

                        <div class="mb-10">
                            <h2
                                class="text-white font-geist font-bold text-4xl lg:text-5xl leading-tight tracking-tight mb-2">
                                Convenience that<br>puts you first
                            </h2>
                            <p class="text-white/60 text-lg">Fill in the form below</p>
                        </div>

                        <form action="#" method="POST" class="space-y-4">
                            <!-- Full Name & Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="text" placeholder="Full Name*"
                                    class="w-full bg-[#111111] text-white px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#FF5C00] placeholder-gray-500 border border-white/5 font-medium">
                                <input type="email" placeholder="Email*"
                                    class="w-full bg-[#111111] text-white px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#FF5C00] placeholder-gray-500 border border-white/5 font-medium">
                            </div>

                            <!-- Phone & Date -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <input type="tel" placeholder="Phone*"
                                    class="w-full bg-[#111111] text-white px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#FF5C00] placeholder-gray-500 border border-white/5 font-medium">
                                <input type="text" placeholder="Date*" onfocus="(this.type='date')"
                                    onblur="(this.type='text')"
                                    class="w-full bg-[#111111] text-white px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#FF5C00] placeholder-gray-500 border border-white/5 font-medium">
                            </div>

                            <!-- Service Select -->
                            <div class="relative">
                                <select
                                    class="w-full bg-[#111111] text-gray-500 px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#FF5C00] border border-white/5 font-medium appearance-none cursor-pointer"
                                    onchange="this.style.color = 'white'">
                                    <option value="" disabled selected>Select Service*</option>
                                    <option value="full_service" class="text-white">Full Service</option>
                                    <option value="interim_service" class="text-white">Interim Service</option>
                                    <option value="major_service" class="text-white">Major Service</option>
                                    <option value="brake_service" class="text-white">Brake Service</option>
                                    <option value="mot" class="text-white">MOT</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i class="fa-solid fa-caret-down text-white/50"></i>
                                </div>
                            </div>

                            <!-- Message -->
                            <textarea placeholder="Message*" rows="4"
                                class="w-full bg-[#111111] text-white px-5 py-4 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#FF5C00] placeholder-gray-500 border border-white/5 font-medium resize-none"></textarea>

                            <!-- Submit Button -->
                            <div class="mt-8">
                                <button type="submit"
                                    class="bg-[#FF5C00] text-white font-bold uppercase px-10 py-4 relative hover:bg-[#e05000] transition-colors"
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
@endsection