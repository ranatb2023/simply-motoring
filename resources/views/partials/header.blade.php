<!-- Global Header -->
<header
    class="absolute top-0 left-1/2 -translate-x-1/2 max-w-[1440px] w-full mx-auto px-6 lg:px-[40px] py-6 lg:py-8 flex justify-between items-center bg-transparent lg:mt-10 z-50">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="block z-50">
        <img src="{{ asset('images/18179987ea4d5b1fd71ba5ba4de7e527319e17b9.png') }}" alt="Simply Motoring Logo"
            class="hidden lg:block w-[180px] lg:w-[100px] h-auto object-contain">
        <img src="{{ asset('images/menu-logo.png') }}" alt="Simply Motoring Logo"
            class="w-[180px] h-auto object-contain lg:hidden">
    </a>

    <!-- Menu Trigger -->
    <button id="menu-trigger"
        class="flex items-center gap-3 text-black font-bold uppercase tracking-wider text-xs lg:text-md hover:bg-primary transition-colors bg-white backdrop-blur-md px-5 py-2.5 rounded-full lg:mr-[40px]">
        <span>MENU</span>
        <div class="flex flex-col gap-[4px] w-5 items-end">
            <div class="w-full h-[2px] bg-black rounded-full"></div>
            <div class="w-2/3 h-[2px] bg-black rounded-full"></div>
        </div>
    </button>
</header>