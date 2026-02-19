@extends('layouts.main')

@section('content')
    <!-- Category Header -->
    <section class="relative bg-dark text-white py-20">
        <div class="max-w-6xl mx-auto px-6">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-6">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-primary transition">Home</a>
                <span class="text-gray-600 mx-2">/</span>
                <a href="{{ route('blog.index') }}" class="text-gray-400 hover:text-primary transition">Blog</a>
                <span class="text-gray-600 mx-2">/</span>
                <span class="text-gray-300">{{ $category->name }}</span>
            </nav>

            <!-- Category Info -->
            <div class="flex items-center gap-4 mb-4">
                @if ($category->icon)
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl"
                        style="background-color: {{ $category->color }}20; color: {{ $category->color }}">
                        <i class="{{ $category->icon }}"></i>
                    </div>
                @endif
                <div>
                    <h1 class="text-5xl md:text-6xl font-bold">{{ $category->name }}</h1>
                </div>
            </div>

            @if ($category->description)
                <p class="text-xl text-gray-300 max-w-3xl">{{ $category->description }}</p>
            @endif

            <div class="mt-6 text-gray-400">
                {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} in this category
            </div>
        </div>
    </section>

    <!-- Posts Grid -->
    <section class="max-w-6xl mx-auto px-6 py-16">
        @if ($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($posts as $post)
                    <a href="{{ $post->url }}" class="group">
                        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition h-full flex flex-col">
                            @if ($post->featured_image)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                </div>
                            @endif
                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="font-bold text-lg mb-2 group-hover:text-primary transition">
                                    {{ Str::limit($post->title, 60) }}
                                </h3>
                                @if ($post->excerpt)
                                    <p class="text-gray-600 text-sm mb-4 flex-1">{{ Str::limit($post->excerpt, 100) }}</p>
                                @endif
                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-auto">
                                    <span>{{ $post->published_at->format('M d, Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $post->reading_time }} min</span>
                                    <span>•</span>
                                    <span>{{ number_format($post->views_count) }} views</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fa-solid fa-folder-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-600">No posts in this category yet.</p>
            </div>
        @endif
    </section>

    <!-- Other Categories -->
    @if ($otherCategories->count() > 0)
        <section class="bg-gray-50 py-16">
            <div class="max-w-6xl mx-auto px-6">
                <h2 class="text-2xl font-bold mb-6">Other Categories</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($otherCategories as $otherCategory)
                        <a href="{{ route('blog.category', $otherCategory->slug) }}"
                            class="p-4 bg-white rounded-xl hover:shadow-lg transition group">
                            <div class="flex items-center gap-3 mb-2">
                                @if ($otherCategory->icon)
                                    <i class="{{ $otherCategory->icon }} text-xl" style="color: {{ $otherCategory->color }}"></i>
                                @endif
                                <h3 class="font-semibold group-hover:text-primary transition">{{ $otherCategory->name }}</h3>
                            </div>
                            <p class="text-sm text-gray-600">{{ $otherCategory->posts_count }}
                                {{ Str::plural('post', $otherCategory->posts_count) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection