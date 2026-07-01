<!-- Page Header -->
<header
    class="max-w-[1440px] w-full mx-auto px-6 lg:px-[40px] py-6 flex justify-between items-center bg-transparent z-50">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="block z-50">
        <img src="{{ asset('images/18179987ea4d5b1fd71ba5ba4de7e527319e17b9.png') }}" alt="Professional vehicle diagnostics"
            class="w-[180px] lg:w-[100px] h-auto object-contain">
    </a>

    <!-- Menu Trigger -->
    <button id="menu-trigger"
        class="flex items-center gap-3 text-white font-bold uppercase tracking-wider text-xs lg:text-md hover:bg-primary transition-colors bg-black/50 backdrop-blur-md px-5 py-2.5 rounded-full">
        <span>MENU</span>
        <div class="flex flex-col gap-[4px] w-5 items-end">
            <div class="w-full h-[2px] bg-white rounded-full"></div>
            <div class="w-2/3 h-[2px] bg-white rounded-full"></div>
        </div>
    </button>
</header>