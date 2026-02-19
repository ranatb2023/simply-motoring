<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Blog Posts') }}
                </h2>
                <p class="text-gray-500 text-sm mt-1">Manage, edit, and publish your content.</p>
            </div>
            <a href="{{ route('admin.blog.posts.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-primary hover:bg-orange-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-colors duration-200">
                <i class="fa-solid fa-plus mr-2"></i>
                New Post
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Management Toolbar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <!-- Search -->
                    <div class="md:col-span-4 relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i
                                class="fa-solid fa-search text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by title..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary block transition-all" />
                    </div>

                    <!-- Status Filter -->
                    <div class="md:col-span-3 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-filter text-gray-400"></i>
                        </div>
                        <select name="status"
                            class="w-full pl-10 pr-8 py-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary block appearance-none cursor-pointer transition-all bg-none">
                            <option value="all">All Statuses</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                            </option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled
                            </option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived
                            </option>
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-500">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="md:col-span-3 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-folder text-gray-400"></i>
                        </div>
                        <select name="category"
                            class="w-full pl-10 pr-8 py-2.5 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary block appearance-none cursor-pointer transition-all bg-none">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-500">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>

                    <!-- Filter Button -->
                    <div class="md:col-span-2">
                        <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors shadow-sm">
                            <i class="fa-solid fa-sliders mr-2"></i> Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Messages -->
            @if ($success = session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center"
                    role="alert">
                    <i class="fa-solid fa-circle-check text-green-500 text-xl mr-3"></i>
                    <p class="text-green-700 font-medium">{{ $success }}</p>
                </div>
            @endif

            <!-- Content Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                                <th class="px-6 py-4">Article</th>
                                <th class="px-6 py-4">Author</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Stats</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($posts as $post)
                                <tr class="hover:bg-gray-50 transition-colors group">
                                    <!-- Article Info -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 shadow-sm relative group-hover:shadow-md transition-all">
                                                @if ($post->featured_image)
                                                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                                                        alt="{{ $post->title }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                        <i class="fa-solid fa-image text-xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4
                                                    class="text-sm font-bold text-gray-900 mb-1 group-hover:text-primary transition-colors line-clamp-1">
                                                    {{ $post->title }}
                                                </h4>

                                                <div class="flex flex-wrap gap-2 mb-1.5">
                                                    @foreach ($post->categories->take(2) as $category)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                            {{ $category->name }}
                                                        </span>
                                                    @endforeach
                                                    @if ($post->categories->count() > 2)
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500 border border-gray-200">
                                                            +{{ $post->categories->count() - 2 }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="text-xs text-gray-400 flex items-center gap-2">
                                                    @if($post->published_at)
                                                        <span title="Published Date">
                                                            <i class="fa-regular fa-calendar mr-1"></i>
                                                            {{ $post->published_at->format('M d, Y') }}
                                                        </span>
                                                    @else
                                                        <span class="italic">Not published</span>
                                                    @endif

                                                    @if ($post->is_featured)
                                                        <span class="text-yellow-500 font-medium flex items-center"
                                                            title="Featured Post">
                                                            <i class="fa-solid fa-star text-[10px] mr-1"></i> Featured
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Author -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold ring-2 ring-white">
                                                {{ substr($post->author->name, 0, 1) }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ $post->author->name }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClasses = [
                                                'published' => 'bg-green-100 text-green-700 border-green-200',
                                                'draft' => 'bg-gray-100 text-gray-600 border-gray-200',
                                                'scheduled' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'archived' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            ];
                                            $icon = [
                                                'published' => 'fa-check-circle',
                                                'draft' => 'fa-file-pen',
                                                'scheduled' => 'fa-clock',
                                                'archived' => 'fa-box-archive',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClasses[$post->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            <i
                                                class="fa-solid {{ $icon[$post->status] ?? 'fa-circle' }} mr-1.5 text-[10px]"></i>
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </td>

                                    <!-- Stats -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-4 text-xs text-gray-500">
                                            <div class="flex flex-col items-center gap-1" title="Views">
                                                <i class="fa-regular fa-eye"></i>
                                                <span
                                                    class="font-semibold text-gray-700">{{ number_format($post->views_count) }}</span>
                                            </div>
                                            <div class="w-px h-6 bg-gray-200"></div>
                                            <div class="flex flex-col items-center gap-1" title="Comments">
                                                <i class="fa-regular fa-comments"></i>
                                                <span class="font-semibold text-gray-700">{{ $post->comments_count }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ $post->url }}" target="_blank"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all"
                                                title="View Post">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                            <a href="{{ route('admin.blog.posts.edit', $post) }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-primary hover:bg-primary/10 transition-all"
                                                title="Edit Post">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form action="{{ route('admin.blog.posts.destroy', $post) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all"
                                                    title="Delete Post">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 bg-white">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                                <i class="fa-solid fa-file-circle-xmark text-2xl"></i>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">No posts found</h3>
                                            <p class="text-sm max-w-sm mx-auto mb-6">Try adjusting your search or filters to
                                                find what you're looking for, or start fresh.</p>
                                            <a href="{{ route('admin.blog.posts.create') }}"
                                                class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-orange-600 transition-colors shadow-sm text-sm font-medium">
                                                Create First Post
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer / Pagination -->
                @if($posts->hasPages())
                    <div class="bg-gray-50 border-t border-gray-100 px-6 py-4">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>