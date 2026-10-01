@extends('layouts.main')

@section('meta_title', 'Thank You | Simply Motoring')
@section('robots', 'noindex, nofollow')

@section('content')
    <section class="bg-white w-full min-h-[70vh] flex items-center justify-center py-16 lg:py-28">
        <div class="max-w-2xl mx-auto px-6 text-center flex flex-col items-center">

            {{-- Tick --}}
            <div class="w-20 h-20 rounded-full flex items-center justify-center mb-8"
                style="background:#ecfdf5;">
                <i class="fa-solid fa-circle-check" style="color:#16a34a; font-size:44px;"></i>
            </div>

            <h1 class="font-geist font-bold text-[40px] lg:text-[64px] leading-[0.9] tracking-tighter uppercase text-black mb-5">
                Thank You!
            </h1>

            <p class="text-gray-700 text-lg lg:text-xl font-medium leading-relaxed max-w-xl mb-3">
                We've received your request and a confirmation has been sent to your email.
                Our team will be in touch shortly.
            </p>

            <p class="text-gray-500 text-sm mb-10">
                Please check your inbox (and spam folder) for the details.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center justify-center bg-black text-white text-sm font-bold uppercase tracking-widest px-8 py-4 hover:bg-primary transition-all duration-300 rounded-md">
                    Back to Home
                </a>
                <a href="tel:01302456406"
                    class="inline-flex items-center justify-center gap-2 bg-white border border-gray-200 text-gray-800 text-sm font-bold uppercase tracking-widest px-8 py-4 hover:border-primary hover:text-primary transition-all duration-300 rounded-md">
                    <i class="fa-solid fa-phone text-xs"></i> 01302 456 406
                </a>
            </div>
        </div>
    </section>
@endsection
