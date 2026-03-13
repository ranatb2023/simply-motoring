<!-- Global Header -->
<header class="relative w-full bg-white border-b border-gray-100 z-50">
    <div class="max-w-[1440px] w-full mx-auto px-6 lg:px-[40px] py-4 lg:py-5 grid grid-cols-3 items-center gap-4 lg:gap-0">

        <!-- Left: Menu Trigger -->
        <div class="flex items-center">
            <button id="menu-trigger"
                class="flex items-center gap-3 text-white font-bold uppercase tracking-wider text-xs lg:text-sm hover:opacity-90 transition-opacity bg-primary px-5 py-2.5 rounded-full">
                <div class="flex flex-col gap-[4px] w-5 items-start">
                    <div class="w-full h-[2px] bg-current rounded-full"></div>
                    <div class="w-2/3 h-[2px] bg-current rounded-full"></div>
                </div>
                <span>MENU</span>
            </button>
        </div>

        <!-- Center: Logo -->
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="block">
                <img src="{{ asset('images/18179987ea4d5b1fd71ba5ba4de7e527319e17b9.png') }}" alt="Simply Motoring Logo"
                    class="hidden lg:block w-[120px] h-auto object-contain">
                <img src="{{ asset('images/18179987ea4d5b1fd71ba5ba4de7e527319e17b9.png') }}" alt="Simply Motoring Logo"
                    class="w-[120px] h-auto object-contain lg:hidden">
            </a>
        </div>

        <!-- Right: Social Icons + Phone -->
        <div class="flex items-center justify-end gap-4">
            <!-- Facebook -->
            <a href="https://www.facebook.com/simplymotoring?_rdr" target="_blank" rel="noopener noreferrer"
                class="text-gray-500 hover:text-primary transition-colors" aria-label="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                </svg>
            </a>
            <!-- Instagram -->
            <a href="https://www.instagram.com/simplymotoring?igsh=dm15MHpxNzF0aHJy" target="_blank" rel="noopener noreferrer"
                class="text-gray-500 hover:text-primary transition-colors" aria-label="Instagram">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                    <circle cx="12" cy="12" r="4"/>
                    <circle cx="17.5" cy="6.5" r=".5" fill="currentColor" stroke="none"/>
                </svg>
            </a>
            <!-- Phone -->
            <a href="tel:01302456406"
                class="hidden sm:flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.63 3.37 2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.72a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <span>01302 456 406</span>
            </a>
        </div>

    </div>
</header>