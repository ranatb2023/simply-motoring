@extends('layouts.main')

@section('content')
    <div class="w-full bg-white min-h-screen">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-10 py-10">
            <!-- Header -->
            <h1
                class="font-geist font-bold text-[56px] lg:text-[96px] leading-[0.85] tracking-tighter uppercase mb-12 text-black typewriter-effect">
                BLOGS
            </h1>

            @if(isset($featuredPost) && $featuredPost)
                <!-- Featured Blog Post -->
                <div
                    class="relative w-full h-[400px] lg:h-[600px] rounded-2xl overflow-hidden group mb-10 bg-gray-100 lg:[clip-path:polygon(60px_0,100%_0,100%_calc(100%_-_60px),calc(100%_-_60px)_100%,0_100%,0_60px)]">
                    <!-- Image -->
                    @if($featuredPost->featured_image)
                        <img src="{{ asset('storage/' . $featuredPost->featured_image) }}" alt="{{ $featuredPost->title }}"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 bg-gray-300 w-full h-full flex items-center justify-center">
                            <span class="text-gray-500 font-bold">No Image</span>
                        </div>
                    @endif

                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>

                    <!-- Content Positioned -->
                    <div class="absolute inset-0 p-6 lg:p-12 flex flex-col justify-between">
                        <!-- Top Right Pills -->
                        <div class="flex justify-end gap-2">
                            <span
                                class="bg-black/30 backdrop-blur-md text-white/90 text-[10px] lg:text-xs font-bold px-4 py-1.5 rounded-full border border-white/20 uppercase tracking-widest font-geist">
                                {{ $featuredPost->published_at ? $featuredPost->published_at->format('d M, Y') : 'Unknown Date' }}
                            </span>
                            <span
                                class="bg-black/30 backdrop-blur-md text-white/90 text-[10px] lg:text-xs font-bold px-4 py-1.5 rounded-full border border-white/20 uppercase tracking-widest font-geist">
                                ARTICLE
                            </span>
                        </div>

                        <!-- Bottom Content -->
                        <div class="flex items-center justify-between w-full gap-8 bg-white/10 rounded-xl backdrop-blur-lg p-6">
                            <!-- Title -->
                            <h2
                                class="font-geist font-bold text-2xl lg:text-[40px] text-white leading-[1.1] max-w-3xl uppercase tracking-tight">
                                {{ $featuredPost->title }}
                            </h2>

                            <!-- Link/Button -->
                            <a href="{{ route('blog.post', $featuredPost->slug) }}"
                                class="bg-white hover:bg-white/90 text-primary w-12 h-12 lg:w-14 lg:h-14 rounded-xl flex items-center justify-center transition-colors shadow-lg flex-shrink-0">
                                <i class="fa-solid fa-arrow-right text-lg lg:text-xl text-[#e05000]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- More Blogs Section -->
            @if(isset($posts) && $posts->count() > 0)
                <h2
                    class="font-geist font-bold text-[48px] lg:text-[64px] leading-tight tracking-tight uppercase mb-8 text-black typewriter-effect">
                    MORE BLOGS
                </h2>

                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
                    @foreach($posts as $post)
                        <!-- Blog Card -->
                        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden group w-full bg-gray-100">
                            <!-- Image -->
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="absolute inset-0 bg-gray-300 w-full h-full flex items-center justify-center">
                                    <span class="text-gray-500 text-sm font-bold">No Image</span>
                                </div>
                            @endif

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                            <!-- Content -->
                            <div class="absolute inset-0 p-6 flex flex-col justify-between">
                                <!-- Top Left Pills -->
                                <div class="flex justify-start gap-2">
                                    <span
                                        class="bg-black/30 backdrop-blur-md text-white/90 text-[10px] font-bold px-3 py-1 rounded-full border border-white/20 uppercase tracking-widest font-geist">
                                        {{ $post->published_at ? $post->published_at->format('d M, Y') : '' }}
                                    </span>
                                    <span
                                        class="bg-black/30 backdrop-blur-md text-white/90 text-[10px] font-bold px-3 py-1 rounded-full border border-white/20 uppercase tracking-widest font-geist">
                                        ARTICLE
                                    </span>
                                </div>

                                <!-- Bottom Content -->
                                <div
                                    class="flex items-center justify-between w-full gap-4 bg-white/10 rounded-xl backdrop-blur-md p-4">
                                    <!-- Title -->
                                    <h3
                                        class="font-geist font-bold text-xl text-white leading-tight uppercase line-clamp-3 tracking-tight">
                                        {{ $post->title }}
                                    </h3>

                                    <!-- Link/Button -->
                                    <a href="{{ route('blog.post', $post->slug) }}"
                                        class="bg-white hover:bg-white/90 text-primary w-10 h-10 flex-shrink-0 rounded-lg flex items-center justify-center transition-colors shadow-lg">
                                        <i class="fa-solid fa-arrow-right text-sm text-[#e05000]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between pt-8 pb-8">
                    <!-- Page Numbers (Left) -->
                    <div class="flex gap-2">
                        @if ($posts->lastPage() > 1)
                            @for ($i = 1; $i <= $posts->lastPage(); $i++)
                                <div class="w-10 h-10 p-[1px] {{ $posts->currentPage() == $i ? 'bg-[#e05000]' : 'bg-gray-300 hover:bg-gray-400' }}"
                                    style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);">
                                    <a href="{{ $posts->url($i) }}"
                                        class="w-full h-full flex items-center justify-center text-sm font-bold transition-colors font-geist {{ $posts->currentPage() == $i ? 'bg-[#e05000] text-white' : 'bg-white text-black' }}"
                                        style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);">
                                        {{ $i }}
                                    </a>
                                </div>
                            @endfor
                        @endif
                    </div>

                    <!-- Pagination Info (Center) -->
                    <div class="hidden sm:block text-xs font-bold text-black uppercase tracking-widest font-geist">
                        Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}
                    </div>

                    <!-- Next/Prev Buttons (Right) -->
                    <div class="flex gap-2">
                        @if ($posts->onFirstPage())
                            <div class="p-[1px] bg-black cursor-not-allowed"
                                style="clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px);">
                                <span
                                    class="px-8 py-2.5 flex items-center justify-center bg-white text-black text-[10px] font-bold uppercase tracking-widest font-geist"
                                    style="clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px);">
                                    PREV
                                </span>
                            </div>
                        @else
                            <div class="p-[1px] bg-black hover:bg-[#e05000] transition-colors"
                                style="clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px);">
                                <a href="{{ $posts->previousPageUrl() }}"
                                    class="px-8 py-2.5 flex items-center justify-center bg-black hover:bg-transparent hover:text-white text-white text-[10px] font-bold uppercase tracking-widest transition-colors font-geist"
                                    style="clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px);">
                                    PREV
                                </a>
                            </div>
                        @endif

                        @if ($posts->hasMorePages())
                            <div class="p-[1px] bg-black hover:bg-[#e05000] transition-colors"
                                style="clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px);">
                                <a href="{{ $posts->nextPageUrl() }}"
                                    class="px-8 py-2.5 flex items-center justify-center bg-black hover:bg-[#e05000] text-white text-[10px] font-bold uppercase tracking-widest transition-colors font-geist"
                                    style="clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px);">
                                    NEXT
                                </a>
                            </div>
                        @else
                            <div class="p-[1px] bg-black cursor-not-allowed"
                                style="clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px);">
                                <span
                                    class="px-8 py-2.5 flex items-center justify-center bg-white text-black text-[10px] font-bold uppercase tracking-widest font-geist"
                                    style="clip-path: polygon(15px 0, 100% 0, 100% calc(100% - 15px), calc(100% - 15px) 100%, 0 100%, 0 15px);">
                                    NEXT
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection