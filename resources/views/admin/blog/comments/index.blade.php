<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog Comments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Header with Stats -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Moderate Comments</h3>
                            <p class="text-sm text-gray-500">Manage and moderate your blog interactions.</p>
                        </div>
                        <div class="flex gap-2">
                            @if($statusCounts['spam'] > 0)
                                <form action="{{ route('admin.blog.comments.delete-spam') }}" method="POST" class="inline"
                                    onsubmit="return confirm('Delete all spam comments permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-sm bg-red-50 text-red-600 border border-red-200 rounded hover:bg-red-100 transition">
                                        Delete All Spam ({{ $statusCounts['spam'] }})
                                    </button>
                                </form>
                            @endif
                            @if($statusCounts['trash'] > 0)
                                <form action="{{ route('admin.blog.comments.empty-trash') }}" method="POST" class="inline"
                                    onsubmit="return confirm('Empty trash permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-sm bg-gray-50 text-gray-600 border border-gray-200 rounded hover:bg-gray-100 transition">
                                        Empty Trash ({{ $statusCounts['trash'] }})
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if ($success = session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                            class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative mb-6 flex items-center shadow-sm"
                            role="alert">
                            <i class="fa-solid fa-check-circle mr-2"></i>
                            <span class="block sm:inline">{{ $success }}</span>
                        </div>
                    @endif

                    <!-- Status Tabs -->
                    <div class="mb-6 border-b border-gray-200 overflow-x-auto no-scrollbar">
                        <nav class="-mb-px flex space-x-6 min-w-max">
                            @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'spam' => 'Spam', 'trash' => 'Trash'] as $status => $label)
                                                    <a href="{{ route('admin.blog.comments.index', ['status' => $status]) }}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors
                                                                                                            {{ request('status', 'all') == $status
                                ? 'border-primary text-primary'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                                        {{ $label }}
                                                        <span
                                                            class="ml-2 py-0.5 px-2 rounded-full text-xs 
                                                                                                                {{ request('status', 'all') == $status ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-600' }}">
                                                            {{ $statusCounts[$status] }}
                                                        </span>
                                                    </a>
                            @endforeach
                            <a href="{{ route('admin.blog.comments.index', ['flagged' => 1]) }}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors
                                {{ request('flagged') ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-red-600 hover:border-red-300' }}">
                                🚩 Flagged <span
                                    class="ml-2 py-0.5 px-2 rounded-full text-xs bg-red-50 text-red-600">{{ $statusCounts['flagged'] }}</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Filters & Bulk Form Wrapper -->
                    <form method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            <!-- Search -->
                            <div class="md:col-span-5">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                        placeholder="Search author, email, or content..."
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 sm:text-sm">
                                </div>
                            </div>

                            <!-- Post Filter (Custom Dropdown) -->
                            <div class="md:col-span-5" x-data="{
                                    open: false,
                                    search: '',
                                    selected: '{{ request('post') }}',
                                    options: {{ \Illuminate\Support\Js::from($posts->map(fn($p) => ['id' => (string) $p->id, 'title' => $p->title])->prepend(['id' => '', 'title' => 'All Posts'])->values()) }},
                                    get selectedLabel() {
                                        let found = this.options.find(o => o.id == this.selected);
                                        return found ? found.title : 'All Posts';
                                    },
                                    get filteredOptions() {
                                        if (this.search === '') return this.options;
                                        return this.options.filter(option => {
                                            return option.title.toLowerCase().includes(this.search.toLowerCase());
                                        });
                                    }
                                }" @click.away="open = false; search = ''">

                                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Post</label>
                                <input type="hidden" name="post" :value="selected">

                                <div class="relative">
                                    <button type="button" @click="open = !open"
                                        class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm">
                                        <span class="block truncate" x-text="selectedLabel"></span>
                                        <span
                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <div x-show="open" x-transition:leave="transition ease-in duration-100"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                        style="display: none;">

                                        <div class="sticky top-0 z-10 bg-white border-b border-gray-100 px-2 py-2">
                                            <input type="text" x-model="search" placeholder="Search posts..."
                                                class="w-full border-gray-300 rounded-md text-sm focus:ring-primary focus:border-primary px-2 py-1 h-8">
                                        </div>

                                        <ul class="py-1">
                                            <template x-for="option in filteredOptions" :key="option.id">
                                                <li class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-gray-50 cursor-pointer"
                                                    @click="selected = option.id; open = false; search = ''">
                                                    <span class="block truncate"
                                                        :class="{'font-semibold': selected == option.id, 'font-normal': selected != option.id}"
                                                        x-text="option.title"></span>

                                                    <span x-show="selected == option.id"
                                                        class="text-primary absolute inset-y-0 right-0 flex items-center pr-4">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </li>
                                            </template>
                                            <li x-show="filteredOptions.length === 0"
                                                class="text-gray-500 cursor-default select-none relative py-2 pl-3 pr-9 text-center text-sm">
                                                No posts found
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="md:col-span-2">
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Comments Table & Bulk Actions -->
                    <form action="{{ route('admin.blog.comments.bulk-action') }}" method="POST" id="bulk-action-form">
                        @csrf

                        <!-- Bulk Actions Bar -->
                        <div class="flex items-center gap-3 mb-4 bg-gray-50 p-2 rounded-lg border border-gray-200"
                            x-data="{ count: 0 }"
                            x-init="$watch('count', value => count = document.querySelectorAll('.comment-checkbox:checked').length)">
                            <div class="relative w-48" x-data="{
                                    open: false,
                                    selected: '',
                                    options: [
                                        {id: '', title: 'Bulk Actions'},
                                        {id: 'approve', title: 'Approve'},
                                        {id: 'spam', title: 'Mark as Spam'},
                                        {id: 'trash', title: 'Move to Trash'},
                                        {id: 'restore', title: 'Restore'},
                                        {id: 'delete', title: 'Delete Permanently'}
                                    ],
                                    get selectedLabel() {
                                        let found = this.options.find(o => o.id == this.selected);
                                        return found ? found.title : 'Bulk Actions';
                                    }
                                }" @click.away="open = false">

                                <input type="hidden" name="action" :value="selected">

                                <button type="button" @click="open = !open"
                                    class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-1.5 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm">
                                    <span class="block truncate" x-text="selectedLabel"></span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>

                                <div x-show="open" x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                                    style="display: none;">

                                    <ul class="py-1">
                                        <template x-for="option in options" :key="option.id">
                                            <li class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-gray-50 cursor-pointer"
                                                @click="selected = option.id; open = false">
                                                <span class="block truncate"
                                                    :class="{'font-semibold': selected == option.id, 'font-normal': selected != option.id}"
                                                    x-text="option.title"></span>

                                                <span x-show="selected == option.id"
                                                    class="text-primary absolute inset-y-0 right-0 flex items-center pr-4">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                            <button type="submit"
                                class="px-3 py-1.5 bg-white border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                                Apply
                            </button>
                        </div>

                        <div class="overflow-x-auto border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                                            <input type="checkbox" id="select-all"
                                                class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4"
                                                onclick="document.querySelectorAll('.comment-checkbox').forEach(c => c.checked = this.checked)">
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[200px]">
                                            Author
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Comment
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[200px]">
                                            In Response To
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[100px]">
                                            Quick Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse ($comments as $comment)
                                        <tr
                                            class="hover:bg-gray-50 transition-colors {{ $comment->is_flagged ? 'bg-red-50/50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                                <input type="checkbox" name="ids[]" value="{{ $comment->id }}"
                                                    class="comment-checkbox rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                                            </td>
                                            <td class="px-6 py-4 align-top">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full"
                                                            src="https://ui-avatars.com/api/?name={{ urlencode($comment->commenter_name) }}&background=random&size=64"
                                                            alt="">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-bold text-gray-900">
                                                            {{ $comment->commenter_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 mb-1">
                                                            {{ $comment->guest_email ?? $comment->user->email }}
                                                        </div>
                                                        <div class="text-xs text-gray-400 font-mono">
                                                            {{ $comment->ip_address }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 align-top">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="text-xs font-semibold px-2 py-0.5 rounded
                                                                    @if ($comment->status === 'approved') bg-green-100 text-green-800
                                                                    @elseif ($comment->status === 'pending') bg-yellow-100 text-yellow-800
                                                                    @elseif ($comment->status === 'spam') bg-red-100 text-red-800
                                                                    @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst($comment->status) }}
                                                    </span>
                                                    @if ($comment->is_flagged)
                                                        <span
                                                            class="text-xs font-semibold px-2 py-0.5 rounded bg-red-100 text-red-800">
                                                            <i class="fa-solid fa-flag mr-1"></i> Flagged
                                                        </span>
                                                    @endif
                                                </div>
                                                <div
                                                    class="text-sm text-gray-800 leading-relaxed mb-2 line-clamp-3 hover:line-clamp-none transition-all">
                                                    {{ $comment->content }}
                                                </div>
                                                <div class="flex gap-4 text-xs text-gray-500">
                                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                                    @if($comment->likes_count > 0 || $comment->dislikes_count > 0)
                                                        <span class="flex gap-2">
                                                            <span class="flex items-center"><i
                                                                    class="fa-regular fa-thumbs-up mr-1"></i>
                                                                {{ $comment->likes_count }}</span>
                                                            <span class="flex items-center"><i
                                                                    class="fa-regular fa-thumbs-down mr-1"></i>
                                                                {{ $comment->dislikes_count }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 align-top">
                                                @if($comment->post)
                                                    <a href="{{ $comment->post->url }}" target="_blank"
                                                        class="text-sm font-medium text-primary hover:text-orange-700 hover:underline block mb-1">
                                                        {{ $comment->post->title }}
                                                    </a>
                                                    <a href="{{ $comment->post->url }}#comment-{{ $comment->id }}"
                                                        target="_blank" class="text-xs text-gray-400 hover:text-gray-600">
                                                        View on Post <i class="fa-solid fa-external-link-alt ml-1"></i>
                                                    </a>
                                                @else
                                                    <span class="text-sm text-gray-400 italic">Deleted Post</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 align-top text-center whitespace-nowrap">
                                                <div class="flex flex-col gap-2 items-center">
                                                    @if ($comment->status === 'pending' || $comment->status === 'spam')
                                                        <button type="submit" form="audit-form-approve-{{ $comment->id }}"
                                                            class="text-green-600 hover:text-green-900 text-sm font-medium"
                                                            title="Approve">
                                                            <i class="fa-solid fa-check mr-1"></i> Approve
                                                        </button>
                                                    @endif

                                                    @if ($comment->status === 'approved')
                                                        <button type="submit" form="audit-form-spam-{{ $comment->id }}"
                                                            class="text-orange-600 hover:text-orange-900 text-sm font-medium"
                                                            title="Mark Spam">
                                                            <i class="fa-solid fa-ban mr-1"></i> Spam
                                                        </button>
                                                    @endif

                                                    @if ($comment->status !== 'trash')
                                                        <button type="submit" form="audit-form-trash-{{ $comment->id }}"
                                                            class="text-gray-600 hover:text-gray-900 text-sm font-medium"
                                                            title="Trash">
                                                            <i class="fa-solid fa-trash mr-1"></i> Trash
                                                        </button>
                                                    @endif
                                                    @if ($comment->status === 'trash')
                                                        <button type="submit" form="audit-form-restore-{{ $comment->id }}"
                                                            class="text-blue-600 hover:text-blue-900 text-sm font-medium flex items-center gap-1"
                                                            title="Restore">
                                                            <i class="fa-solid fa-undo"></i> Restore
                                                        </button>
                                                        <button type="submit" form="audit-form-destroy-{{ $comment->id }}"
                                                            class="text-red-600 hover:text-red-900 text-sm font-medium flex items-center gap-1"
                                                            title="Delete Permanently"
                                                            onclick="return confirm('Permanently delete this comment?')">
                                                            <i class="fa-solid fa-times"></i> Delete Permanently
                                                        </button>
                                                    @endif

                                                    @if ($comment->is_flagged)
                                                        <button type="submit" form="audit-form-unflag-{{ $comment->id }}"
                                                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium flex items-center gap-1"
                                                            title="Unflag">
                                                            <i class="fa-regular fa-flag"></i> Unflag
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div
                                                        class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                        <i class="fa-regular fa-comments text-2xl text-gray-400"></i>
                                                    </div>
                                                    <p class="text-lg font-medium text-gray-900">No comments found</p>
                                                    <p class="text-sm text-gray-500">Try adjusting your filters or search
                                                        query.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <!-- Hidden Forms for Quick Actions (to keep table clean) -->
                    @foreach ($comments as $comment)
                        <form id="audit-form-approve-{{ $comment->id }}"
                            action="{{ route('admin.blog.comments.approve', $comment) }}" method="POST" class="hidden">
                            @csrf </form>
                        <form id="audit-form-spam-{{ $comment->id }}"
                            action="{{ route('admin.blog.comments.spam', $comment) }}" method="POST" class="hidden"> @csrf
                        </form>
                        <form id="audit-form-trash-{{ $comment->id }}"
                            action="{{ route('admin.blog.comments.trash', $comment) }}" method="POST" class="hidden"> @csrf
                        </form>
                        <form id="audit-form-restore-{{ $comment->id }}"
                            action="{{ route('admin.blog.comments.restore', $comment) }}" method="POST" class="hidden">
                            @csrf </form>
                        <form id="audit-form-unflag-{{ $comment->id }}"
                            action="{{ route('admin.blog.comments.unflag', $comment) }}" method="POST" class="hidden"> @csrf
                        </form>
                        <form id="audit-form-destroy-{{ $comment->id }}"
                            action="{{ route('admin.blog.comments.destroy', $comment) }}" method="POST" class="hidden">
                            @csrf @method('DELETE') </form>
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>