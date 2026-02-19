<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Blog Tags') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Messages -->
            @if ($success = session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-center"
                    role="alert">
                    <i class="fa-solid fa-circle-check text-green-500 text-xl mr-3"></i>
                    <div>
                        <p class="text-green-700 font-medium">Success</p>
                        <p class="text-green-600 text-sm">{{ $success }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-center"
                    role="alert">
                    <i class="fa-solid fa-circle-exclamation text-red-500 text-xl mr-3"></i>
                    <div>
                        <p class="text-red-700 font-medium">There were some problems with your input.</p>
                        <ul class="list-disc list-inside text-red-600 text-sm mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @php
                $oldId = old('id');
                $oldName = old('name', '');
                $oldSlug = old('slug', '');
                $oldDescription = old('description', '');
                $oldMethod = old('_method', 'POST');
                $storeRoute = route('admin.blog.tags.store');
                $indexRoute = route('admin.blog.tags.index');
                $allIds = $tags->pluck('id')->toArray();
            @endphp

            <div class="flex flex-col lg:flex-row gap-8 items-start" x-data="{
                isEditing: false,
                form: {
                    id: {{ json_encode($oldId) }},
                    name: {{ json_encode($oldName) }},
                    slug: {{ json_encode($oldSlug) }},
                    description: {{ json_encode($oldDescription) }}
                },
                formAction: '{{ $storeRoute }}',
                formMethod: '{{ $oldMethod }}',
                selected: [],
                allIds: {{ json_encode($allIds) }},
                bulkAction: '',
                init() {
                    const oldMethod = '{{ $oldMethod }}';
                    if (oldMethod === 'PUT') {
                        this.isEditing = true;
                        this.formAction = '{{ $indexRoute }}/' + this.form.id;
                    }
                },
                edit(tag) {
                    this.isEditing = true;
                    this.form.id = tag.id;
                    this.form.name = tag.name;
                    this.form.slug = tag.slug;
                    this.form.description = tag.description || '';
                    this.formAction = '{{ $indexRoute }}/' + tag.id;
                    this.formMethod = 'PUT';
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                cancel() {
                    this.isEditing = false;
                    this.form = { id: '', name: '', slug: '', description: '' };
                    this.formAction = '{{ $storeRoute }}';
                    this.formMethod = 'POST';
                },
                toggleAll() {
                    if (this.selected.length === this.allIds.length) {
                        this.selected = [];
                    } else {
                        this.selected = this.allIds;
                    }
                },
                submitBulk() {
                    if (this.selected.length === 0) {
                        alert('Please select at least one item.');
                        return;
                    }
                    if (this.bulkAction !== 'delete') {
                        alert('Please select an action.');
                        return;
                    }
                    if (confirm('Are you sure you want to delete ' + this.selected.length + ' items?')) {
                        this.$refs.bulkForm.submit();
                    }
                }
            }" x-init="init()">

                <!-- Left Column: Add/Edit Tag Form -->
                <div class="w-full lg:w-1/3 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                    <i class="fa-solid fa-tag text-lg" x-show="!isEditing"></i>
                                    <i class="fa-solid fa-pen-to-square text-lg" x-show="isEditing" style="display: none;"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800"
                                    x-text="isEditing ? 'Edit Tag' : 'Add Tag'">Add Tag</h3>
                            </div>
                            <button type="button" x-show="isEditing" @click="cancel()" style="display: none;"
                                class="text-xs text-red-500 hover:text-red-700 font-medium underline">
                                Cancel
                            </button>
                        </div>

                        <form :action="formAction" method="POST">
                            @csrf
                            <input type="hidden" name="_method" :value="formMethod">
                            <input type="hidden" name="id" x-model="form.id">

                            <!-- Name -->
                            <div class="mb-5">
                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                                <input type="text" name="name" id="name" x-model="form.name"
                                    class="w-full px-4 py-2.5 bg-gray-50 border rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all @error('name') border-red-500 bg-red-50 @else border-gray-200 @enderror"
                                    placeholder="e.g. Electric Vehicles" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-400 text-xs mt-1.5 flex items-start gap-1"
                                    x-show="!{{ $errors->has('name') ? 'true' : 'false' }}">
                                    <i class="fa-solid fa-info-circle mt-0.5"></i> The name is how it appears on your
                                    site.
                                </p>
                            </div>

                            <!-- Slug -->
                            <div class="mb-5">
                                <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">Slug</label>
                                <input type="text" name="slug" id="slug" x-model="form.slug"
                                    class="w-full px-4 py-2.5 bg-gray-50 border rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all @error('slug') border-red-500 bg-red-50 @else border-gray-200 @enderror"
                                    placeholder="e.g. electric-vehicles">
                                @error('slug')
                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                                <p class="text-gray-400 text-xs mt-1.5 flex items-start gap-1"
                                    x-show="!{{ $errors->has('slug') ? 'true' : 'false' }}">
                                    <i class="fa-solid fa-link mt-0.5"></i> The "slug" is the URL-friendly version of the
                                    name. It is usually all lowercase and contains only letters, numbers, and hyphens.
                                </p>
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <label for="description"
                                    class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                <textarea name="description" id="description" rows="4" x-model="form.description"
                                    class="w-full px-4 py-2.5 bg-gray-50 border rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary focus:bg-white transition-all resize-none @error('description') border-red-500 bg-red-50 @else border-gray-200 @enderror"
                                    placeholder="Describe this tag..."></textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 duration-200">
                                <i class="fa-solid" :class="isEditing ? 'fa-save' : 'fa-plus-circle'"></i>
                                <span x-text="isEditing ? 'Update Tag' : 'Add New Tag'">Add New Tag</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Tag List -->
                <div class="w-full lg:w-2/3">

                    <form x-ref="bulkForm" method="POST" action="{{ route('admin.blog.tags.bulk-action') }}">
                        @csrf
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <!-- Toolbar -->
                            <div
                                class="p-4 border-b border-gray-100 bg-gray-50/30 flex flex-col md:flex-row justify-between items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="font-bold text-gray-700">All Tags</h3>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-gray-200">{{ $tags->count() }}</span>
                                </div>

                                <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                                    <!-- Extra Actions -->
                                    <div class="flex gap-2">
                                        <button type="submit" formaction="{{ route('admin.blog.tags.sync-usage') }}"
                                            class="px-3 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-colors text-xs font-medium shadow-sm"
                                            title="Sync Usage Counts">
                                            <i class="fa-solid fa-rotate mr-1"></i> Sync
                                        </button>
                                        <button type="submit" formaction="{{ route('admin.blog.tags.delete-unused') }}"
                                            onclick="return confirm('Delete all unused tags?');"
                                            class="px-3 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors text-xs font-medium shadow-sm"
                                            title="Delete Unused Tags">
                                            <i class="fa-solid fa-broom mr-1"></i> Cleanup
                                        </button>
                                    </div>
                                    
                                    <div class="h-6 w-px bg-gray-300 mx-2 hidden sm:block"></div>

                                    <!-- Bulk Actions Dropdown -->
                                    <div class="relative min-w-[140px] w-full sm:w-auto" x-data="{ open: false }">
                                        <input type="hidden" name="action" x-model="bulkAction">
                                        <button type="button" @click="open = !open" @click.away="open = false"
                                            class="w-full flex items-center justify-between gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 hover:border-primary/50 hover:text-primary transition-all shadow-sm">
                                            <span x-text="bulkAction === 'delete' ? 'Delete Selected' : 'Bulk Actions'">Bulk Actions</span>
                                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                                        </button>
                                        
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             class="absolute right-0 mt-1 w-full bg-white border border-gray-100 rounded-lg shadow-lg py-1 z-50 origin-top-right"
                                             style="display: none;">
                                            <button type="button" @click="bulkAction = ''; open = false"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors flex items-center gap-2">
                                                <span>Bulk Actions</span>
                                            </button>
                                            <button type="button" @click="bulkAction = 'delete'; open = false"
                                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>

                                    <button type="button" @click="submitBulk"
                                        class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 hover:text-primary transition-colors text-sm font-medium shadow-sm">
                                        Apply
                                    </button>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold tracking-wider">
                                            <th class="p-4 w-10 text-center">
                                                <input type="checkbox" @click="toggleAll" :checked="selected.length === allIds.length && allIds.length > 0"
                                                    class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary">
                                            </th>
                                            <th class="px-6 py-4">Name</th>
                                            <th class="px-6 py-4">Slug</th>
                                            <th class="px-6 py-4 text-center">Usage</th>
                                            <th class="px-6 py-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse ($tags as $tag)
                                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                                <td class="p-4 text-center">
                                                    <input type="checkbox" name="selected_ids[]" value="{{ $tag->id }}" x-model="selected"
                                                        class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-col">
                                                        <a href="#" @click.prevent="edit(@js($tag))"
                                                            class="font-bold text-gray-800 hover:text-primary transition-colors text-sm">
                                                            {{ $tag->name }}
                                                        </a>
                                                        @if ($tag->description)
                                                            <span
                                                                class="text-xs text-gray-400 mt-1 line-clamp-1">{{ Str::limit($tag->description, 60) }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded border border-gray-200">
                                                            {{ $tag->slug }}
                                                        </span>
                                                        <button type="button" class="text-gray-300 hover:text-gray-500" title="Copy Slug"
                                                            onclick="navigator.clipboard.writeText('{{ $tag->slug }}');">
                                                            <i class="fa-regular fa-copy text-xs"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if ($tag->usage_count > 0)
                                                        <span
                                                            class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                                                            {{ $tag->usage_count }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-300 text-xs">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <div
                                                        class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <!-- Edit -->
                                                        <button type="button" @click="edit(@js($tag))"
                                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all border border-transparent hover:border-blue-100"
                                                            title="Edit">
                                                            <i class="fa-solid fa-pen text-xs"></i>
                                                        </button>
                                                        <!-- View -->
                                                        <a href="{{ route('blog.tag', $tag->slug) }}" target="_blank"
                                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 transition-all border border-transparent hover:border-green-100"
                                                            title="View">
                                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                                        </a>
                                                        <!-- Delete -->
                                                        <button type="submit" form="delete-form-{{ $tag->id }}"
                                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all border border-transparent hover:border-red-100"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this tag?');">
                                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 bg-white">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div
                                                            class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                                            <i class="fa-solid fa-tag text-2xl"></i>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-1">No tags yet
                                                        </h3>
                                                        <p class="text-sm text-gray-500">Add your first tag using the form
                                                            on the left.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Footer / Pagination Area -->
                             <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 text-xs text-gray-500 flex justify-between items-center">
                                <div>
                                    Showing all {{ $tags->count() }} tags
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Individual Delete Forms -->
                    @foreach ($tags as $tag)
                        <form id="delete-form-{{ $tag->id }}" action="{{ route('admin.blog.tags.destroy', $tag) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</x-admin-layout>