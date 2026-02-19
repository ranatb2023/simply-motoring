@extends('layouts.main')

@section('content')
    <!-- Blog Post Header -->
    <section class="relative bg-dark text-white py-6 lg:py-20">
        <div class="w-full max-w-[1440px] mx-auto lg:px-28 px-6">
            <!-- Breadcrumb -->
            <nav class="text-sm mb-6 lg:mt-20 mt-20">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-primary transition">Home</a>
                <span class="text-gray-600 mx-2">/</span>
                <a href="{{ route('blog.index') }}" class="text-gray-400 hover:text-primary transition">Blog</a>
                <span class="text-gray-600 mx-2">/</span>
                <span class="text-gray-300">{{ $post->title }}</span>
            </nav>

            <!-- Category Badge -->
            @if ($post->primaryCategory)
                <a href="{{ route('blog.category', $post->primaryCategory->slug) }}"
                    class="inline-block px-4 py-2 rounded-full text-sm font-semibold mb-4 transition hover:scale-105 bg-primary/20 text-primary">
                    @if ($post->primaryCategory->icon)
                        <i class="{{ $post->primaryCategory->icon }} mr-1"></i>
                    @endif
                    {{ $post->primaryCategory->name }}
                </a>
            @endif

            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">{{ $post->title }}</h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-4 text-gray-400 text-sm">
                <div class="flex items-center">
                    <i class="fa-solid fa-user mr-2"></i>
                    <span>{{ $post->author->name }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fa-solid fa-calendar mr-2"></i>
                    <span>{{ $post->published_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fa-solid fa-eye mr-2"></i>
                    <span>{{ number_format($post->views_count) }} views</span>
                </div>
                @if ($post->comments_count > 0)
                    <div class="flex items-center">
                        <i class="fa-solid fa-comments mr-2"></i>
                        <span>{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}</span>
                    </div>
                @endif
                <div class="flex items-center">
                    <i class="fa-solid fa-clock mr-2"></i>
                    <span>{{ $post->reading_time }} min read</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Post Content -->
    <div class="max-w-7xl mx-auto px-6 py-12 flex flex-col lg:flex-row gap-12">
        <!-- Left Column: Article & Comments -->
        <div class="lg:w-2/3">
            <article>
                <!-- Featured Image -->
                @if ($post->featured_image)
                    <div class="mb-12 rounded-xl overflow-hidden shadow-lg"
                        style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                            alt="{{ $post->featured_image_alt ?? $post->title }}"
                            class="w-full h-auto object-cover transform hover:scale-105 transition duration-700 ease-in-out">
                        @if ($post->featured_image_caption)
                            <p class="text-sm text-gray-600 mt-2 text-center italic">{{ $post->featured_image_caption }}</p>
                        @endif
                    </div>
                @endif

                <!-- Excerpt -->
                @if ($post->excerpt)
                    <div class="text-xl text-gray-700 mb-8 pb-8 border-b border-gray-200 leading-relaxed font-serif italic">
                        {{ $post->excerpt }}
                    </div>
                @endif

                <!-- Content -->
                <div
                    class="prose prose-lg max-w-none text-gray-700 prose-img:rounded-xl prose-img:shadow-lg prose-img:[clip-path:polygon(50px_0,100%_0,100%_calc(100%_-_50px),calc(100%_-_50px)_100%,0_100%,0_50px)]">
                    {!! $post->content !!}
                </div>

                <!-- FAQs -->
                @if ($post->faqs->isNotEmpty())
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <div class="flex items-center gap-4 mb-8">
                            <span
                                class="bg-primary text-white px-5 py-2 rounded-full font-geist font-medium text-[16px] leading-[1.26] tracking-[-0.06em] uppercase inline-block">
                                FAQS
                            </span>
                            <h3 class="text-2xl font-bold uppercase font-geist">Frequently Asked Questions</h3>
                        </div>

                        <div x-data="{ active: null }" class="space-y-0 border-t border-black/10">
                            @foreach ($post->faqs as $index => $faq)
                                <div class="border-b border-black/10 py-6">
                                    <button @click="active = (active === {{ $index }} ? null : {{ $index }})"
                                        class="w-full flex justify-between items-center text-left group">
                                        <span class="text-xl lg:text-2xl font-medium text-black pr-8">{{ $faq->question }}</span>
                                        <div class="relative w-10 h-10 flex-shrink-0">
                                            <div class="absolute inset-0 transition-all duration-300 rounded-md"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === {{ $index }} ? 'bg-primary' : 'bg-black'">
                                            </div>
                                            <div class="absolute inset-[2px] flex items-center justify-center rounded-md transition-all duration-300"
                                                style="clip-path: polygon(10px 0, 100% 0, 100% calc(100% - 10px), calc(100% - 10px) 100%, 0 100%, 0 10px);"
                                                :class="active === {{ $index }} ? 'bg-black' : 'bg-primary'">
                                                <i class="fa-solid fa-caret-up transition-colors duration-300"
                                                    :class="active === {{ $index }} ? 'text-primary rotate-0' : 'text-black rotate-180'"></i>
                                            </div>
                                        </div>
                                    </button>
                                    <div x-show="active === {{ $index }}" x-collapse>
                                        <div class="mt-4 text-[#0A0A0A] text-lg leading-relaxed max-w-3xl">
                                            {!! nl2br(e($faq->answer)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Tags -->
                @if ($post->tags->count() > 0)
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($post->tags as $tag)
                                <a href="{{ route('blog.tag', $tag->slug) }}"
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition bg-gray-100 text-gray-600 hover:bg-primary hover:text-white">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Share Buttons -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Share this post</h3>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($post->url) }}" target="_blank"
                            class="w-10 h-10 rounded-full bg-[#1877F2] text-white flex items-center justify-center hover:scale-110 transition shadow-sm">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($post->url) }}&text={{ urlencode($post->title) }}"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-[#1DA1F2] text-white flex items-center justify-center hover:scale-110 transition shadow-sm">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($post->url) }}&title={{ urlencode($post->title) }}"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-[#0A66C2] text-white flex items-center justify-center hover:scale-110 transition shadow-sm">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                        <button onclick="copyToClipboard('{{ $post->url }}')"
                            class="w-10 h-10 rounded-full bg-gray-600 text-white flex items-center justify-center hover:scale-110 transition shadow-sm">
                            <i class="fa-solid fa-link"></i>
                        </button>
                    </div>
                </div>

                <!-- Post Navigation -->
                <div class="mt-12 pt-8 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if ($previousPost)
                        <a href="{{ $previousPost->url }}"
                            class="group p-6 bg-white border border-gray-200 rounded-xl hover:border-primary/50 hover:shadow-md transition">
                            <div class="text-xs font-bold text-gray-400 uppercase mb-2 tracking-wide">Previous Post</div>
                            <div class="font-bold text-gray-900 group-hover:text-primary transition line-clamp-2">
                                {{ $previousPost->title }}
                            </div>
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if ($nextPost)
                        <a href="{{ $nextPost->url }}"
                            class="group p-6 bg-white border border-gray-200 rounded-xl hover:border-primary/50 hover:shadow-md transition text-right">
                            <div class="text-xs font-bold text-gray-400 uppercase mb-2 tracking-wide">Next Post</div>
                            <div class="font-bold text-gray-900 group-hover:text-primary transition line-clamp-2">
                                {{ $nextPost->title }}
                            </div>
                        </a>
                    @endif
                </div>
            </article>

            <!-- Comments Section -->
            @if ($post->allow_comments)
                <div class="mt-16 pt-12 border-t border-gray-200">
                    <h2 class="text-2xl font-bold mb-8">Comments ({{ $post->approvedComments->count() }})</h2>

                    <!-- Comment Form -->
                    <div class="bg-gray-50 rounded-2xl p-6 md:p-8 mb-12 border border-gray-100">
                        <h3 class="font-bold text-lg mb-6">Leave a Comment</h3>
                        <div id="comment-message" class="hidden mb-4 p-4 rounded-lg"></div>
                        <form id="comment-form" action="{{ route('blog.comments.store', $post->slug) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <input type="text" name="commenter_name" placeholder="Your Name *" required
                                    class="px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent bg-white">
                                <input type="email" name="guest_email" placeholder="Your Email *" required
                                    class="px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent bg-white">
                            </div>
                            <textarea name="content" rows="4" placeholder="Your Comment *" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:border-transparent mb-6 bg-white"></textarea>
                            <button type="submit"
                                class="px-8 py-3 bg-primary text-white rounded-lg font-bold hover:bg-orange-600 transition shadow-lg shadow-primary/30">
                                Post Comment
                            </button>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div id="comments-list" class="space-y-8">
                        @foreach ($post->approvedComments as $comment)
                            <div class="flex gap-4">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->commenter_name) }}&background=random"
                                    alt="{{ $comment->commenter_name }}" class="w-12 h-12 rounded-full flex-shrink-0">
                                <div class="flex-1 bg-gray-50 p-6 rounded-2xl rounded-tl-none">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-bold text-gray-900">{{ $comment->commenter_name }}</h4>
                                        <span
                                            class="text-xs text-gray-500 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <aside class="lg:w-1/3 space-y-8">
            <!-- Author Box -->
            <div class="bg-white p-8 border rounded-xl border-gray-100 shadow-sm text-center"
                style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                <div class="relative w-24 h-24 mx-auto mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->author->name) }}&background=FB5200&color=fff&size=128"
                        alt="{{ $post->author->name }}" class="w-full h-full rounded-full border-4 border-white shadow-md">
                </div>
                <h4 class="font-bold text-xl mb-1">{{ $post->author->name }}</h4>
                <p class="text-primary font-medium text-sm mb-4">Author</p>
                <div class="w-full h-px bg-gray-100 mb-4"></div>
                <div class="flex justify-center gap-4 text-gray-400">
                    <a href="#" class="hover:text-primary transition"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="hover:text-primary transition"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" class="hover:text-primary transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>

            <!-- CTA Widget -->
            <div class="bg-primary text-white rounded-xl p-8 text-center relative overflow-hidden group"
                style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition duration-500"></div>
                <div class="relative z-10">
                    <div
                        class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6 backdrop-blur-sm">
                        <i class="fa-solid fa-screwdriver-wrench text-3xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-2xl mb-3">Need Car Service?</h3>
                    <p class="text-white/90 mb-8 leading-relaxed">Experience top-quality car maintenance and repair services
                        with Simply Motoring.</p>
                    <a href="{{ route('service') }}"
                        class="inline-block w-full bg-white text-primary px-6 py-4 rounded-xl font-bold hover:bg-gray-50 transition shadow-xl">
                        Book Appointment
                    </a>
                </div>
            </div>

            <!-- Categories Widget -->
            <div class="bg-white p-8 border rounded-xl border-gray-100 shadow-sm"
                style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-primary"></i> Categories
                </h3>
                <ul class="space-y-3">
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('blog.category', $cat->slug) }}"
                                class="flex justify-between items-center group p-2 hover:bg-gray-50 rounded-lg transition">
                                <span
                                    class="text-gray-600 group-hover:text-primary font-medium transition">{{ $cat->name }}</span>
                                <span
                                    class="bg-gray-100 text-gray-500 text-xs px-2.5 py-1 rounded-full group-hover:bg-primary group-hover:text-white transition font-bold">{{ $cat->posts_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Featured Posts Widget -->
            <div class="bg-white p-8 border rounded-xl border-gray-100 shadow-sm"
                style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-star text-primary"></i> Featured
                </h3>
                <div class="space-y-6">
                    @foreach($featuredPosts as $featured)
                        <a href="{{ $featured->url }}" class="flex gap-4 group">
                            <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden relative">
                                @if($featured->featured_image)
                                    <img src="{{ asset('storage/' . $featured->featured_image) }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fa-solid fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4
                                    class="font-bold text-sm leading-snug group-hover:text-primary transition line-clamp-2 mb-1">
                                    {{ $featured->title }}
                                </h4>
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <i class="fa-regular fa-clock"></i> {{ $featured->published_at->format('M d, Y') }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Popular Tags Widget -->
            <div class="bg-white p-8 border rounded-xl border-gray-100 shadow-sm"
                style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-tags text-primary"></i> Popular Tags
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($popularTags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}"
                            class="px-3 py-1.5 bg-gray-50 text-gray-600 text-xs font-medium rounded-lg hover:bg-primary hover:text-white transition">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>

    <!-- Related Posts -->
    @if ($relatedPosts->count() > 0)
        <section class="bg-gray-50 py-16 border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-center justify-between mb-10">
                    <h2 class="text-3xl font-bold">Related Posts</h2>
                    <a href="{{ route('blog.index') }}" class="text-primary font-semibold hover:underline">View All Blog</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($relatedPosts as $related)
                        <a href="{{ $related->url }}"
                            class="group h-full flex flex-col bg-white overflow-hidden rounded-xl shadow-sm hover:shadow-xl transition duration-500"
                            style="clip-path: polygon(50px 0, 100% 0, 100% calc(100% - 50px), calc(100% - 50px) 100%, 0 100%, 0 50px);">
                            <div class="aspect-video overflow-hidden relative">
                                @if ($related->featured_image)
                                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <i class="fa-solid fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                                <div class="absolute top-4 left-4">
                                    @foreach($related->categories->take(1) as $cat)
                                        <span
                                            class="bg-black/50 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                            {{ $cat->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="font-bold text-xl mb-3 group-hover:text-primary transition line-clamp-2">
                                    {{ $related->title }}
                                </h3>
                                <div
                                    class="mt-auto flex items-center justify-between text-sm text-gray-500 border-t border-gray-100 pt-4">
                                    <span>{{ $related->published_at->format('M d, Y') }}</span>
                                    <span>{{ $related->reading_time }} min read</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <script>
        function copyToClipboard(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link copied to clipboard!');
            });
        }

        // Helper to prevent XSS
        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        document.getElementById('comment-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const form = this;
            const messageDiv = document.getElementById('comment-message');
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerText;

            // Reset state
            messageDiv.classList.add('hidden');
            messageDiv.className = 'hidden mb-4 p-4 rounded-lg';
            submitBtn.disabled = true;
            submitBtn.innerText = 'Posting...';

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    // Show success message
                    messageDiv.innerText = data.message;
                    messageDiv.classList.remove('hidden');
                    messageDiv.classList.add('bg-green-100', 'text-green-700', 'border', 'border-green-200');

                    form.reset();

                    // If comment is approved and returned, append it
                    if (data.comment && data.comment.status === 'approved') {
                        const commentList = document.getElementById('comments-list');
                        // Use a default or the provided commenter name
                        const rawName = data.comment.commenter_name || (data.comment.user ? data.comment.user.name : 'Guest');
                        const commenterName = escapeHtml(rawName);
                        // Encode name for avatar URL
                        const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(rawName)}&background=random`;
                        const escapedContent = escapeHtml(data.comment.content);

                        const commentHtml = `
                                <div class="flex gap-4 animate-fade-in-up">
                                    <img src="${avatarUrl}" alt="${commenterName}" class="w-12 h-12 rounded-full flex-shrink-0">
                                    <div class="flex-1 bg-gray-50 p-6 rounded-2xl rounded-tl-none border border-gray-100">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-bold text-gray-900">${commenterName}</h4>
                                            <span class="text-xs text-gray-500 font-medium">Just now</span>
                                        </div>
                                        <p class="text-gray-700 leading-relaxed">${escapedContent}</p>
                                    </div>
                                </div>
                            `;

                        // Insert at the beginning of the list
                        commentList.insertAdjacentHTML('afterbegin', commentHtml);
                    }
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            } catch (error) {
                console.error(error);
                messageDiv.innerText = error.message;
                messageDiv.classList.remove('hidden');
                messageDiv.classList.add('bg-red-100', 'text-red-700', 'border', 'border-red-200');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;
            }
        });
    </script>
@endsection