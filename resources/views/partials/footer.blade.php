<!-- Footer Section -->
<footer class="bg-black text-white w-full border-t border-white/10">
    <div class="max-w-[1440px] mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[600px]">

            <!-- Left Column (60%) -->
            <div
                class="lg:col-span-7 flex flex-col justify-between border-r border-white/10 lg:px-8 px-6 lg:py-20 py-10 lg:gap-32 gap-4">
                <!-- Logo -->
                <div>
                    <img src="{{ asset('images/menu-logo.png') }}" alt="Simply Motoring Logo"
                        class="w-[200px] lg:w-[240px] h-auto object-contain">
                </div>

                <!-- Newsletter Box -->
                <div class="bg-primary rounded-xl p-8 lg:p-12 mt-20 lg:mt-0 shadow-lg">
                    <h3
                        class="font-geist font-bold text-[28px] lg:text-[40px] leading-[1.1] tracking-tight mb-4 text-white typewriter-effect lg:max-w-[80%]">
                        Subscribe to our monthly newsletter
                    </h3>
                    <p class="text-white/90 lg:text-lg text-base mb-8 font-medium">
                        Expert insights, tips, and trends you won't find anywhere else.
                    </p>

                    <form class="flex flex-col sm:flex-row gap-4 bg-black/10 p-2 rounded-2xl">
                        <input type="email" placeholder="Enter Email"
                            class="flex-1 bg-transparent placeholder-white/60 text-white border border-transparent focus:border-none focus:outline-none focus:ring-0 transition-colors h-[60px]">
                        <button type="button"
                            class="bg-white text-primary font-bold px-8 py-4 rounded-xl hover:bg-black hover:text-white transition-colors h-[60px] flex items-center justify-center gap-2">
                            Subscribe <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column (40%) -->
            <div
                class="lg:col-span-5 flex flex-col border-t lg:border-t-0 border-white/10 justify-between lg:px-8 px-6 lg:py-20 py-10 text-right">
                <!-- Links -->
                <div class="flex justify-between lg:justify-end gap-16 lg:gap-32">
                    <!-- Socials -->
                    <div class="flex flex-col gap-1">
                        <a href="https://www.facebook.com/simplymotoring?_rdr" target="_blank"
                            class="font-geist font-medium text-lg hover:text-primary transition-colors">Facebook</a>
                        <a href="https://www.instagram.com/simplymotoring?igsh=dm15MHpxNzF0aHJy" target="_blank"
                            class="font-geist font-medium text-lg hover:text-primary transition-colors">Instagram</a>
                        <a href="#" class="font-geist font-medium text-lg hover:text-primary transition-colors">X</a>
                    </div>

                    <!-- Services -->
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('service.brake-discs-and-pads') }}"
                            class="font-geist font-medium text-lg hover:text-primary transition-colors">Brake
                            discs
                            & Pads</a>
                        <a href="{{ route('service.brake-fluid-change') }}"
                            class="font-geist font-medium text-lg hover:text-primary transition-colors">Brake
                            Fluid
                            Change</a>
                        <a href="{{ route('service.full-service') }}"
                            class="font-geist font-medium text-lg hover:text-primary transition-colors">Full
                            Service</a>
                        <a href="{{ route('service.interim-service') }}"
                            class="font-geist font-medium text-lg hover:text-primary transition-colors">Interim
                            Service</a>
                        <a href="{{ route('service.major-service') }}"
                            class="font-geist font-medium text-lg hover:text-primary transition-colors">Major
                            Service</a>
                    </div>
                </div>

                <!-- Contact & Copyright -->
                <div class="flex flex-col gap-1 mt-20 lg:mt-0 font-geist">
                    <a href="mailto:hello@simplymotoring.uk"
                        class="text-xl font-medium hover:text-primary transition-colors text-white/90">hello@simplymotoring.uk</a>
                    <a href="tel:01302456406"
                        class="text-xl font-medium hover:text-primary transition-colors text-white/90">01302
                        456 406</a>
                    <p class="text-white/90 text-lg">243A Sprotbrough Road, Doncaster, DN5 8BP</p>
                    <p class="text-white/40 text-base">Simply Motoring © 2026</p>
                </div>
            </div>

        </div>
    </div>
</footer>