@extends('layouts.main')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-900 to-black text-white py-32 overflow-hidden">
         <!-- Abstract Background shapes -->
         <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary opacity-10 rounded-full blur-3xl"></div>
         <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-600 opacity-10 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-6 text-center z-10">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 tracking-tight">Our <span class="text-primary">Blog</span></h1>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto mb-10">
                Discover the latest automotive insights, maintenance tips, and industry news crafted by the Simply Motoring team.
            </p>

            <!-- Search & Filter Container -->
            <div class="max-w-4xl mx-auto bg-white/10 backdrop-blur-md border border-white/20 p-2 rounded-2xl shadow-2xl">
                <form method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="flex-1 relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-search text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles..."
                            class="w-full bg-white/90 text-gray-900 pl-11 pr-4 py-4 rounded-xl border-none focus:ring-2 focus:ring-primary placeholder-gray-500 transition-all">
                    </div>

                    <div class="grid grid-cols-2 md:flex gap-2">
                        <select name="category" class="bg-white/90 text-gray-900 px-6 py-4 rounded-xl border-none focus:ring-2 focus:ring-primary cursor-pointer hover:bg-white transition-colors">
                            <option value="">All Categories</option>
                             @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                         <select name="sort" class="bg-white/90 text-gray-900 px-6 py-4 rounded-xl border-none focus:ring-2 focus:ring-primary cursor-pointer hover:bg-white transition-colors">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-primary hover:bg-orange-600 text-white font-bold px-8 py-4 rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-orange-500/30">
                        Filter
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="max-w-7xl mx-auto px-6 py-20">

        <!-- Featured Section -->
        @if ($featuredPosts->count() > 0 && !request('search') && !request('category'))
            <div class="mb-20">
                <div class="flex items-center gap-4 mb-10">
                    <h2 class="text-3xl font-bold text-gray-900">Featured Stories</h2>
                    <div class="h-1 flex-1 bg-gray-100 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    @foreach ($featuredPosts as $index => $post)
                        <!-- Featured Card -->
                          <a href="{{ $post->url }}" class="group relative flex flex-col h-full rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300">
                             <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10 pointer-events-none"></div>

                            @if ($post->featured_image)
                                <div class="relative h-full min-h-[400px] overflow-hidden">
                                     <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                        class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                </div>
                            @else
                                <div class="w-full h-96 bg-gray-800"></div>
                            @endif

                             <div class="absolute bottom-0 left-0 right-0 p-8 z-20 text-white">
                                 <div class="flex items-center gap-3 mb-3">
                                     @if ($post->primaryCategory)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider backdrop-blur-md bg-white/20 border border-white/20 text-white transform group-hover:-translate-y-1 transition-transform">
                                            {{ $post->primaryCategory->name }}
                                        </span>
                                    @endif
                                     <span class="text-xs font-medium text-gray-300">{{ $post->reading_time }} min read</span>
                                 </div>
                                 <h3 class="text-3xl font-bold mb-3 leading-tight group-hover:text-primary transition-colors">
                                     {{ $post->title }}
                                 </h3>
                                  <p class="text-gray-300 line-clamp-2 md:w-3/4 mb-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 delay-75">
                                      {{ Str::limit($post->excerpt, 120) }}
                                  </p>
                             </div>

                             <!-- Featured Badge -->
                             <div class="absolute top-4 left-4 z-20 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                 ★ FEATURED
                             </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif


        <!-- Recent Posts Grid -->
        <div>
             <div class="flex items-center gap-4 mb-10">
                <h2 class="text-3xl font-bold text-gray-900">
                    @if (request('search'))
                        Results for "{{ request('search') }}"
                    @elseif (request('category'))
                         {{ $categories->firstWhere('slug', request('category'))->name ?? 'Category' }}
                    @else
                        Latest Articles
                    @endif
                </h2>
                 <div class="h-1 flex-1 bg-gray-100 rounded-full"></div>
            </div>

            @if ($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($posts as $post)
                        <!-- Standard Post Card -->
                        <article class="group flex flex-col bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                            <a href="{{ $post->url }}" class="relative aspect-[16/10] overflow-hidden">
                                @if ($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                                        <i class="fa-solid fa-image text-4xl"></i>
                                    </div>
                                @endif

                                @if ($post->primaryCategory)
                                    <span class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur text-xs font-bold rounded-lg shadow-sm text-gray-900">
                                        {{ $post->primaryCategory->name }}
                                    </span>
                                @endif
                            </a>

                            <div class="flex-1 p-6 flex flex-col">
                                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                                    <span class="font-medium text-primary">{{ $post->published_at->format('M d, Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $post->reading_time }} min read</span>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 mb-3 leading-snug group-hover:text-primary transition-colors">
                                    <a href="{{ $post->url }}">
                                        {{ Str::limit($post->title, 70) }}
                                    </a>
                                </h3>

                                <p class="text-gray-600 text-sm line-clamp-3 mb-6 flex-1">
                                    {{ Str::limit($post->excerpt, 120) }}
                                </p>

                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                     <div class="flex items-center text-xs text-gray-500">
                                        <i class="fa-regular fa-eye mr-1"></i> {{ number_format($post->views_count) }}
                                     </div>
                                     <a href="{{ $post->url }}" class="text-sm font-semibold text-primary group-hover:translate-x-1 transition-transform inline-flex items-center">
                                         Read Article <i class="fa-solid fa-arrow-right ml-1"></i>
                                     </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 text-gray-500 bg-gray-50 rounded-3xl border border-gray-100 border-dashed">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-magnifying-glass text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">No articles found</h3>
                    <p class="text-sm">Try adjusting your search or filters</p>
                    <a href="{{ route('blog.index') }}" class="mt-4 text-primary hover:underline font-medium">Clear all filters</a>
                </div>
            @endif
        </div>
    </section>

    <!-- Popular Tags Section -->
    @if ($popularTags->count() > 0)
        <div class="border-t border-gray-100 bg-gray-50 py-20">
            <div class="max-w-6xl mx-auto px-6 text-center">
                <h2 class="text-2xl font-bold mb-8">Explore by Topic</h2>
                <div class="flex flex-wrap justify-center gap-3">
                    @foreach ($popularTags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}"
                            class="group px-6 py-3 bg-white rounded-full text-sm font-medium border border-gray-200 shadow-sm hover:border-primary hover:text-primary hover:shadow-md transition-all">
                            <span class="text-gray-400 group-hover:text-primary mr-1">#</span>{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection