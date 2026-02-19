<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($post) ? __('Edit Post') : __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ isset($post) ? route('admin.blog.posts.update', $post) : route('admin.blog.posts.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($post))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Main Content (Left, 8 Cols) -->
                    <div class="lg:col-span-8 space-y-8">
                        
                        <!-- Content Editor Card -->
                        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
                             <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="font-semibold text-gray-900">Post Content</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                    <input type="text" name="title" id="title"
                                        value="{{ old('title', $post->title ?? '') }}"
                                        class="w-full text-xl font-bold px-4 py-3 rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition-all placeholder-gray-400"
                                        placeholder="Enter post title..." required>
                                    @error('title')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                 <!-- Slug -->
                                <div>
                                    <label for="slug" class="block text-xs font-medium text-gray-500 mb-1">Slug (URL)</label>
                                     <div class="flex items-center">
                                         <span class="text-gray-400 text-sm mr-2">{{ config('app.url') }}/blog/</span>
                                         <input type="text" name="slug" id="slug"
                                            value="{{ old('slug', $post->slug ?? '') }}"
                                            placeholder="auto-generated-from-title"
                                            class="w-full text-sm px-3 py-2 rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 bg-gray-50 text-gray-600">
                                     </div>
                                    @error('slug')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Quill Editor -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Editor</label>
                                    <div class="rounded-lg border border-gray-200 overflow-hidden focus-within:ring-2 focus-within:ring-primary/20 focus-within:border-primary transition-all">
                                         <!-- Quill Container -->
                                        <div id="quill-editor" class="bg-white prose max-w-none" style="height: 500px; font-size: 16px;"></div>
                                    </div>
                                    
                                    <textarea name="content" id="content" class="hidden" required>{{ old('content', $post->content ?? '') }}</textarea>
                                    @error('content')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div>
                                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">
                                        Excerpt <span class="text-gray-400 text-xs">(Summary)</span>
                                    </label>
                                    <textarea name="excerpt" id="excerpt" rows="3"
                                        class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 text-sm"
                                        placeholder="Brief summary of the post..."
                                        maxlength="500">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                                    @error('excerpt')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100" x-data="{
                            faqs: {{ json_encode(old('faqs') ? array_values(old('faqs')) : (isset($post) ? $post->faqs->map(fn($f) => ['id' => $f->id, 'question' => $f->question, 'answer' => $f->answer])->values()->all() : [])) }},
                            addFaq() {
                                this.faqs.push({ id: null, question: '', answer: '' });
                            },
                            removeFaq(index) {
                                this.faqs.splice(index, 1);
                            }
                        }">
                            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900">❓ Frequently Asked Questions</h3>
                                <button type="button" @click="addFaq()" class="text-sm text-primary hover:text-primary/80 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add FAQ
                                </button>
                            </div>
                            <div class="p-6 space-y-4">
                                <template x-for="(faq, index) in faqs" :key="index">
                                    <div class="border border-gray-200 rounded-lg p-4 bg-white relative group">
                                        <button type="button" @click="removeFaq(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 bg-white rounded-full p-1 shadow-sm border border-gray-100 hover:border-red-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <div class="space-y-3">
                                            <div>
                                                <label :for="`faq_question_${index}`" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Question</label>
                                                <input type="text" :id="`faq_question_${index}`" :name="`faqs[${index}][question]`" x-model="faq.question" class="w-full text-sm px-3 py-2 rounded-lg border-gray-300 focus:border-primary focus:ring-primary" placeholder="e.g. What is the return policy?">
                                            </div>
                                            <div>
                                                <label :for="`faq_answer_${index}`" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Answer</label>
                                                <textarea :id="`faq_answer_${index}`" :name="`faqs[${index}][answer]`" x-model="faq.answer" rows="2" class="w-full text-sm px-3 py-2 rounded-lg border-gray-300 focus:border-primary focus:ring-primary" placeholder="Enter the answer here..."></textarea>
                                            </div>
                                            <input type="hidden" :name="`faqs[${index}][id]`" x-model="faq.id">
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="faqs.length === 0" class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-gray-500 text-sm">No FAQs added yet.</p>
                                    <button type="button" @click="addFaq()" class="text-primary hover:text-primary/80 text-sm font-medium mt-1">
                                        Add your first FAQ
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
                            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center cursor-pointer" onclick="document.getElementById('seo-content').classList.toggle('hidden')">
                                <h3 class="font-semibold text-gray-900">🔎 SEO Configuration</h3>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                            <div id="seo-content" class="p-6 space-y-4 hidden">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                    <input type="text" name="meta_title"
                                        value="{{ old('meta_title', $post->meta_title ?? '') }}"
                                        class="w-full px-3 py-2 rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20"
                                        maxlength="60">
                                </div>
                                 <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                    <textarea name="meta_description" rows="2"
                                        class="w-full px-3 py-2 rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20"
                                        maxlength="160">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Focus Keyword</label>
                                    <input type="text" name="focus_keyword"
                                         value="{{ old('focus_keyword', $post->focus_keyword ?? '') }}"
                                        class="w-full px-3 py-2 rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar (Right, 4 Cols) -->
                    <!-- Sidebar (Right, 4 Cols) - WordPress Style -->
                    <div class="lg:col-span-4 space-y-0 bg-white border-l border-gray-200 min-h-screen">
                        
                        <!-- Summary Section -->
                        <div class="p-4 border-b border-gray-200">
                            <div class="space-y-4 text-sm">
                                <!-- Status -->
                                <div class="flex justify-between items-center group relative" x-data="{ 
                                    open: false, 
                                    currentStatus: '{{ old('status', isset($post) ? $post->status : 'draft') }}',
                                    labels: {
                                        'draft': 'Draft',
                                        'published': 'Published',
                                        'scheduled': 'Scheduled',
                                        'archived': 'Archived'
                                    }
                                }">
                                    <span class="text-gray-600">Status</span>
                                    <button type="button" @click="open = !open" class="text-primary font-medium hover:underline text-right focus:outline-none flex items-center justify-end">
                                        <span x-text="labels[currentStatus]"></span>
                                    </button>
                                    <input type="hidden" name="status" :value="currentStatus">

                                    <!-- Dropdown -->
                                    <div x-show="open" @click.away="open = false" 
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute top-7 right-0 bg-white border border-gray-200 shadow-xl rounded-md py-1 z-50 w-36"
                                        style="display: none;"
                                    >
                                        <template x-for="(label, key) in labels" :key="key">
                                            <button type="button" 
                                                @click="currentStatus = key; open = false"
                                                class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-primary/10 hover:text-primary transition-colors"
                                                :class="{'font-bold text-primary bg-primary/10': currentStatus === key}"
                                                x-text="label"
                                            ></button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Publish Date -->
                                <div class="flex justify-between items-center group relative" x-data="{ open: false }">
                                    <span class="text-gray-600">Publish</span>
                                    <button type="button" @click="open = !open" class="text-primary font-medium hover:underline text-right">
                                        {{ isset($post) && $post->published_at ? $post->published_at->format('M d, Y h:i A') : 'Immediately' }}
                                    </button>
                                    
                                    <!-- Date Picker Dropdown -->
                                    <div x-show="open" @click.away="open = false" class="absolute top-8 right-0 bg-white border border-gray-200 shadow-lg rounded p-3 z-50 w-64">
                                        <input type="datetime-local" name="published_at" 
                                            value="{{ old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                                            class="w-full text-sm border-gray-300 rounded focus:border-primary focus:ring-primary">
                                    </div>
                                </div>

                                <!-- URL / Slug -->
                                <div class="flex justify-between items-center group">
                                    <span class="text-gray-600">Slug</span>
                                    <input type="text" name="slug" 
                                        value="{{ old('slug', $post->slug ?? '') }}"
                                        class="text-right border-none bg-transparent text-primary font-medium focus:ring-0 p-0 w-1/2 placeholder-primary/40 text-sm"
                                        placeholder="URL Slug">
                                </div>
                                
                                <!-- Author -->
                                <div class="flex justify-between items-center group relative" x-data="{ 
                                    open: false, 
                                    currentAuthorId: '{{ old('author_id', $post->author_id ?? Auth::id()) }}',
                                    currentAuthorName: '{{ $users->firstWhere('id', old('author_id', $post->author_id ?? Auth::id()))->name ?? Auth::user()->name }}'
                                }">
                                    <span class="text-gray-600">Author</span>
                                    
                                    <button type="button" @click="open = !open" class="text-primary font-medium hover:underline text-right focus:outline-none flex items-center justify-end max-w-[150px] truncate space-x-1">
                                        <span x-text="currentAuthorName"></span>
                                        <svg class="w-3 h-3 text-primary/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <input type="hidden" name="author_id" :value="currentAuthorId">

                                    <!-- Dropdown -->
                                    <div x-show="open" @click.away="open = false" 
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute top-7 right-0 bg-white border border-gray-200 shadow-xl rounded-md py-1 z-50 w-48 max-h-60 overflow-y-auto custom-scrollbar"
                                        style="display: none;"
                                    >
                                        @foreach($users as $user)
                                            <button type="button" 
                                                @click="currentAuthorId = '{{ $user->id }}'; currentAuthorName = '{{ addslashes($user->name) }}'; open = false"
                                                class="block w-full text-left px-4 py-2 text-xs text-gray-700 hover:bg-primary/10 hover:text-primary transition-colors"
                                                :class="{'font-bold text-primary bg-primary/10': currentAuthorId == '{{ $user->id }}'}"
                                            >
                                                {{ $user->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Accordion -->
                        <div x-data="{ open: true }" class="border-b border-gray-200">
                            <button type="button" @click="open = !open" class="flex items-center justify-between w-full p-4 hover:bg-gray-50 focus:outline-none">
                                <span class="font-semibold text-gray-900 text-sm">Categories</span>
                                <svg class="w-4 h-4 text-gray-500 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <div x-show="open" class="p-4 pt-0">
                                <!-- Search Categories -->
                                <div class="relative mb-3">
                                    <input type="text" placeholder="Search Categories" class="w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded focus:border-primary focus:ring-primary">
                                    <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>

                                <!-- List -->
                                <div id="categories-list" class="max-h-48 overflow-y-auto border border-gray-200 rounded p-2 bg-white space-y-1 mb-3 custom-scrollbar">
                                    @foreach ($categories as $category)
                                        <label class="flex items-start space-x-2 p-1 hover:bg-gray-100 rounded cursor-pointer group">
                                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                            {{ in_array($category->id, old('categories', isset($post) ? $post->categories->pluck('id')->toArray() : [])) ? 'checked' : '' }}
                                            class="mt-0.5 rounded text-primary focus:ring-primary border-gray-300 w-4 h-4">
                                            <span class="text-sm text-gray-700 leading-snug select-none group-hover:text-gray-900">{{ $category->name }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <!-- Add New Category -->
                                <div x-data="{ adding: false, newName: '', newParent: '' }">
                                    <button type="button" @click="adding = !adding" class="text-primary hover:underline text-sm font-medium">Add New Category</button>
                                    
                                    <div x-show="adding" class="mt-3 space-y-2 p-3 bg-gray-50 border border-gray-100 rounded">
                                        <input type="text" x-model="newName" placeholder="New Category Name" class="w-full text-sm px-2 py-1.5 border border-gray-300 rounded focus:border-primary focus:ring-primary shadow-sm">
                                        
                                        <select x-model="newParent" class="w-full text-sm px-2 py-1.5 border border-gray-300 rounded focus:border-primary focus:ring-primary shadow-sm">
                                            <option value="">— Parent Category —</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <button type="button" @click="addNewCategory(newName, newParent); newName=''; adding=false;" class="w-full bg-primary hover:bg-primary/90 text-white text-sm font-medium py-1.5 rounded shadow-sm transition-colors">
                                            Add Category
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Primary Category</label>
                                    <select name="primary_category" class="w-full text-sm border-gray-300 rounded-lg focus:border-primary focus:ring-primary">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ (old('primary_category') == $category->id) || (isset($post) && $post->primary_category && $post->primary_category->id == $category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Tags Accordion -->
                        <div x-data="{ open: true }" class="border-b border-gray-200">
                            <button type="button" @click="open = !open" class="flex items-center justify-between w-full p-4 hover:bg-gray-50 focus:outline-none">
                                <span class="font-semibold text-gray-900 text-sm">Tags</span>
                                <svg class="w-4 h-4 text-gray-500 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <div x-show="open" class="p-4 pt-0" 
                                x-data="tagManager({{ json_encode(collect(old('tags', isset($post) ? $post->tags : []))->map(function($tag) {
                                    $t = is_object($tag) ? $tag : \App\Models\BlogTag::find($tag);
                                    return $t ? ['id' => $t->id, 'name' => $t->name] : null;
                                })->filter()->values()) }})"
                            >
                                <label class="block text-xs font-bold text-gray-600 uppercase mb-2">ADD TAG</label>
                                
                                <div class="bg-white border border-gray-300 rounded p-2 focus-within:ring-1 focus-within:ring-primary focus-within:border-primary transition-shadow">
                                    <div class="flex flex-wrap gap-2 mb-1" x-show="tags.length > 0">
                                        <template x-for="(tag, index) in tags" :key="tag.id">
                                            <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 border border-gray-200 text-sm text-gray-700">
                                                <span x-text="tag.name"></span>
                                                <button type="button" class="ml-1 text-gray-400 hover:text-red-500 focus:outline-none" @click="removeTag(index)">
                                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                </button>
                                                <input type="hidden" name="tags[]" :value="tag.id">
                                            </span>
                                        </template>
                                    </div>
                                    <input type="text" 
                                        x-model="input"
                                        @keydown.enter.prevent="addTag()"
                                        @keydown.backspace="handleBackspace()"
                                        @input="handleInput()"
                                        @blur="addTag()"
                                        class="w-full text-sm border-none outline-none focus:ring-0 p-0 placeholder-gray-400 leading-relaxed"
                                        placeholder="">
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Separate with commas or the Enter key.</p>

                                <div class="mt-4">
                                    <p class="text-xs font-bold text-gray-600 mb-2 uppercase">MOST USED</p>
                                    <div class="flex flex-wrap gap-x-3 gap-y-1">
                                        @foreach ($tags->take(10) as $tag)
                                            <button type="button" class="text-sm text-primary underline decoration-primary/30 hover:text-primary/80 hover:decoration-primary transition-colors"
                                                @click="addExistingTag({{ $tag->id }}, '{{ $tag->name }}')">
                                                {{ $tag->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image Accordion -->
                        <div x-data="{ open: false }" class="border-b border-gray-200">
                            <button type="button" @click="open = !open" class="flex items-center justify-between w-full p-4 hover:bg-gray-50 focus:outline-none">
                                <span class="font-semibold text-gray-900 text-sm">Featured Image</span>
                                <svg class="w-4 h-4 text-gray-500 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <div x-show="open" class="p-4 pt-0">
                                <!-- Image Preview Container -->
                                <div id="image-preview-container" class="{{ (isset($post) && $post->featured_image) ? '' : 'hidden' }} mb-3 relative group rounded bg-gray-100 border border-gray-200">
                                    <img id="image-preview" src="{{ (isset($post) && $post->featured_image) ? asset('storage/' . $post->featured_image) : '' }}" class="w-full h-auto block">
                                    <button type="button" onclick="document.getElementById('image-preview-container').classList.add('hidden'); document.getElementById('featured_image').value = '';" 
                                        class="absolute top-2 right-2 bg-white text-red-500 p-1 rounded shadow hover:bg-gray-100 transition-colors">
                                        <span class="sr-only">Remove</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                <label for="featured_image" class="block w-full text-center px-4 py-3 bg-gray-50 border border-gray-300 border-dashed rounded text-sm text-gray-600 hover:bg-gray-100 cursor-pointer transition-colors">
                                    Set featured image
                                    <input id="featured_image" name="featured_image" type="file" class="hidden" accept="image/*" onchange="previewImage(this)" />
                                </label>
                                
                                <div class="mt-3">
                                     <label class="flex items-start cursor-pointer">
                                        <input type="checkbox" name="is_featured" value="1"
                                            {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }}
                                            class="mt-1 rounded text-primary focus:ring-primary border-gray-300 w-4 h-4">
                                        <span class="ml-2 text-sm text-gray-600">Show featured image in the post lists only, but hide it in the single post view.</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Excerpt Accordion -->
                        <div x-data="{ open: false }" class="border-b border-gray-200">
                            <button type="button" @click="open = !open" class="flex items-center justify-between w-full p-4 hover:bg-gray-50 focus:outline-none">
                                <span class="font-semibold text-gray-900 text-sm">Excerpt</span>
                                <svg class="w-4 h-4 text-gray-500 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            <div x-show="open" class="p-4 pt-0">
                                <label for="excerpt" class="block text-xs font-medium text-gray-600 mb-2">Write an excerpt (optional)</label>
                                <textarea name="excerpt" id="excerpt" rows="3"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:border-primary focus:ring-primary"
                                    placeholder="">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                            </div>
                        </div>

                         <!-- Actions -->
                         <div class="p-4 border-t border-gray-200 bg-gray-50 mt-auto">
                            <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-medium py-2 px-4 rounded shadow-sm text-sm transition-colors">
                                {{ isset($post) ? 'Update' : 'Publish' }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quill Editor CSS & JS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Quill Init ---
            var quill = new Quill('#quill-editor', {
                theme: 'snow',
                modules: {
                     toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'color': [] }, { 'background': [] }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                },
                placeholder: 'Type your masterpiece here...',
            });

            // Adjust Editor Style
            quill.container.style.fontFamily = "inherit"; 
            
            // Fixed height/scrolling for editor in simplified layout
            if(document.querySelector('.ql-editor')) {
                document.querySelector('.ql-editor').style.minHeight = "400px";
            }

            // Load Content
            var contentTextarea = document.getElementById('content');
            if (contentTextarea.value) {
                // Clean up potentially broken src attributes (client-side fix for editor)
                let cleanedContent = contentTextarea.value;
                cleanedContent = cleanedContent.replace(/src=["']src=&quot;([^"]+)&quot;["']/g, 'src="$1"');
                cleanedContent = cleanedContent.replace(/src=["']src=['"]([^"']+)['"]['"]/g, 'src="$1"');
                
                quill.root.innerHTML = cleanedContent;
            }

            // Sync
            quill.on('text-change', function() {
                contentTextarea.value = quill.root.innerHTML;
            });

            contentTextarea.closest('form').addEventListener('submit', function() {
                contentTextarea.value = quill.root.innerHTML;
            });

            // --- Auto-Slug Script ---
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if(titleInput && slugInput) {
                titleInput.addEventListener('blur', function() {
                    if(!slugInput.value) { // Only auto-fill if empty
                        slugInput.value = titleInput.value
                            .toLowerCase()
                            .trim()
                            .replace(/[^\w\s-]/g, '')
                            .replace(/[\s_-]+/g, '-')
                            .replace(/^-+|-+$/g, '');
                    }
                });
            }
        });

        // Image Preview Function
        function previewImage(input) {
            const container = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            // const previewText = document.getElementById('preview-text'); // Element removed in redesign
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                    // previewText.textContent = 'New Image Preview';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                if (!preview.src.includes('storage')) {
                    container.classList.add('hidden');
                }
            }
        }

        // Add New Category AJAX
        function addNewCategory(name, parentId) {
            if (!name) return;

            fetch('{{ route("admin.blog.categories.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name,
                    parent_id: parentId,
                    is_active: true
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Append to list
                    const list = document.getElementById('categories-list');
                    const label = document.createElement('label');
                    label.className = 'flex items-start space-x-2 p-1 hover:bg-gray-100 rounded cursor-pointer group';
                    label.innerHTML = `
                        <input type="checkbox" name="categories[]" value="${data.category.id}" checked
                        class="mt-0.5 rounded text-primary focus:ring-primary border-gray-300 w-4 h-4">
                        <span class="text-sm text-gray-700 leading-snug select-none group-hover:text-gray-900">${data.category.name}</span>
                    `;
                    list.appendChild(label);
                    
                    // Also add to primary category dropdown
                    const select = document.querySelector('select[name="primary_category"]');
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.text = data.category.name;
                    select.appendChild(option);
                } else {
                    alert('Error creating category');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // --- Alpine Tag Manager ---
        // Tag Manager (Alpine)
        window.tagManager = function(initialTags) {
            return {
                tags: initialTags || [],
                input: '',

                addTag() {
                    let val = this.input.trim();
                    if (!val) return;

                    console.log('Adding tag:', val);
                    this.createTagOnServer(val);
                    this.input = '';
                },

                handleInput() {
                    if (this.input.includes(',')) {
                        const parts = this.input.split(',');
                        this.input = ''; // Clear immediately
                        
                        parts.forEach(part => {
                            const trimmed = part.trim();
                            if (trimmed) {
                                console.log('Processing comma tag:', trimmed);
                                this.createTagOnServer(trimmed);
                            }
                        });
                    }
                },
                
                handleBackspace() {
                    if (this.input === '' && this.tags.length > 0) {
                        this.tags.pop();
                    }
                },

                addExistingTag(id, name) {
                    if (this.tags.find(t => t.id == id)) return;
                    this.tags.push({ id: id, name: name });
                },

                removeTag(index) {
                    this.tags.splice(index, 1);
                },

                createTagOnServer(name) {
                    // Check if already in list visually
                    if (this.tags.find(t => t.name.toLowerCase() === name.toLowerCase())) {
                        console.log('Tag already exists locally');
                        return;
                    }

                    fetch('{{ route("admin.blog.tags.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name: name,
                            is_active: true
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Tag server response:', data);
                        if (data.success) {
                            // Check again for duplicates before adding
                             if (!this.tags.find(t => t.id == data.tag.id)) {
                                this.tags.push({ id: data.tag.id, name: data.tag.name });
                             }
                        } else {
                            console.error('Failed to create tag:', data);
                            alert('Could not create tag. ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error adding tag:', error);
                        alert('Could not add tag. Please check your connection.');
                    });
                }
            };
        };
    </script>
    <style>
        /* Custom Quill Styles */
        .ql-toolbar.ql-snow {
            border: none;
            border-bottom: 1px solid #e5e7eb;
            border-radius: 0.5rem 0.5rem 0 0;
            background: #f9fafb;
            padding: 12px;
        }
        .ql-container.ql-snow {
            border: none;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }
    </style>
</x-admin-layout>
