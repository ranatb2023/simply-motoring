@extends('layouts.main')

@section('content')
    <!-- Tag Header -->
    <section class="relative bg-dark text-white py-20">
        <div class="max-w-6xl mx-auto px-6">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-6">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-primary transition">Home</a>
                <span class="text-gray-600 mx-2">/</span>
                <a href="{{ route('blog.index') }}" class="text-gray-400 hover:text-primary transition">Blog</a>
                <span class="text-gray-600 mx-2">/</span>
                <span class="text-gray-300">#{{ $tag->name }}</span>
            </nav>

            <!-- Tag Info -->
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl font-bold"
                    style="background-color: {{ $tag->color }}20; color: {{ $tag->color }}">
                    #
                </div>
                <div>
                    <h1 class="text-5xl md:text-6xl font-bold">#{{ $tag->name }}</h1>
                </div>
            </div>

            @if ($tag->description)
                <p class="text-xl text-gray-300 max-w-3xl">{{ $tag->description }}</p>
            @endif

            <div class="mt-6 text-gray-400">
                {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} with this tag
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
                                @if ($post->primaryCategory)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mb-3 w-fit"
                                        style="background-color: {{ $post->primaryCategory->color }}20; color: {{ $post->primaryCategory->color }}">
                                        {{ $post->primaryCategory->name }}
                                    </span>
                                @endif
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
                <i class="fa-solid fa-tag text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-600">No posts with this tag yet.</p>
            </div>
        @endif
    </section>

    <!-- Other Tags -->
    @if ($otherTags->count() > 0)
        <section class="bg-gray-50 py-16">
            <div class="max-w-6xl mx-auto px-6">
                <h2 class="text-2xl font-bold mb-6">Other Tags</h2>
                <div class="flex flex-wrap gap-3">
                    @foreach ($otherTags as $otherTag)
                        <a href="{{ route('blog.tag', $otherTag->slug) }}"
                            class="px-4 py-2 rounded-full text-sm font-medium transition hover:scale-105"
                            style="background-color: {{ $otherTag->color }}20; color: {{ $otherTag->color }}">
                            #{{ $otherTag->name }} ({{ $otherTag->usage_count }})
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection