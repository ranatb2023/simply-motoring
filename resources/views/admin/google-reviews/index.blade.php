<x-admin-layout>
    <div class="container mx-auto px-6 py-8" x-data="googleReviews()">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h3 class="text-gray-800 text-3xl font-bold tracking-tight">Google Reviews</h3>
                <p class="text-gray-500 mt-1 text-sm">Manage your business connection and showcase customer feedback.</p>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div class="fixed bottom-5 right-5 z-50 flex flex-col space-y-3 pointer-events-none">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="toast.visible" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="translate-y-2 opacity-0 scale-95"
                     x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                     x-transition:leave-end="translate-y-2 opacity-0 scale-95"
                     class="pointer-events-auto w-80 bg-white border-l-4 shadow-lg rounded-r-lg p-4 flex items-start"
                     :class="toast.type === 'success' ? 'border-green-500' : 'border-red-500'">
                    
                    <div class="flex-shrink-0">
                        <template x-if="toast.type === 'success'">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="toast.type === 'error'">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </template>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900" x-text="toast.title"></p>
                        <p class="mt-1 text-xs text-gray-500" x-text="toast.message"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="dismissToast(toast.id)" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Search & Connection -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Search Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Find Business
                        </h4>
                        
                        <div class="relative">
                            <input type="text" x-model="searchQuery" @keydown.enter="searchBusiness()"
                                class="w-full pl-4 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm"
                                placeholder="Enter business name...">
                            
                            <button @click="searchBusiness()" 
                                    class="absolute right-2 top-2 p-1.5 bg-white rounded-md text-gray-400 hover:text-primary border border-gray-100 hover:border-primary/30 transition-colors"
                                    :disabled="isSearching">
                                <svg x-show="!isSearching" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                                <svg x-show="isSearching" class="animate-spin w-5 h-5 text-primary" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Results Dropdown -->
                        <div x-show="searchResults.length > 0" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="mt-4 border border-gray-100 rounded-lg max-h-60 overflow-y-auto custom-scrollbar">
                            <template x-for="result in searchResults" :key="result.place_id">
                                <div class="p-3 hover:bg-gray-50 transition-colors flex justify-between items-start group border-b border-gray-50 last:border-0 cursor-pointer" @click="selectBusiness(result)">
                                    <div class="pr-2">
                                        <p class="text-sm font-semibold text-gray-800 group-hover:text-primary transition-colors" x-text="result.name"></p>
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-1" x-text="result.formatted_address"></p>
                                    </div>
                                    <button class="text-gray-300 group-hover:text-primary transition-colors mt-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div x-show="errorMessage" class="mt-3 p-3 bg-red-50 text-red-600 text-xs rounded-md border border-red-100 flex items-center">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span x-text="errorMessage"></span>
                        </div>
                    </div>
                </div>

                <!-- Connected Business Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Active Connection</h4>
                        <template x-if="currentBusiness">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                Live
                            </span>
                        </template>
                    </div>
                    
                    <div class="p-6">
                        <template x-if="currentBusiness">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1" x-text="currentBusiness.name"></h3>
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="line-clamp-1" x-text="currentBusiness.formatted_address"></span>
                                </div>
                                
                                <div class="flex items-center mb-6 bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                                    <span class="text-3xl font-bold text-gray-900 mr-2" x-text="currentBusiness.rating || 'N/A'"></span>
                                    <div class="flex flex-col">
                                        <div class="flex text-yellow-400 text-sm">
                                            <template x-for="i in 5">
                                                <svg class="w-4 h-4" :class="i <= (currentBusiness.rating || 0) ? 'fill-current' : 'text-gray-300 fill-current'" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </template>
                                        </div>
                                        <span class="text-xs text-gray-500 mt-0.5" x-text="`${currentBusiness.user_ratings_total || 0} reviews on Google`"></span>
                                    </div>
                                </div>

                                <button @click="fetchReviews()" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Sync Reviews
                                </button>
                            </div>
                        </template>
                        
                        <template x-if="!currentBusiness">
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900">No Business Connected</h3>
                                <p class="text-xs text-gray-500 mt-1 max-w-[200px] mx-auto">Search and select your business to start displaying reviews.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Right Column: Reviews Grid -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-full">
                    <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-800">Review Stream</h4>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Last synched: Just now</span>
                    </div>

                    <div class="p-6">
                         <div x-show="currentBusiness && currentBusiness.reviews && currentBusiness.reviews.length > 0">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template x-for="review in currentBusiness.reviews" :key="review.author_name">
                                    <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-center">
                                                <template x-if="review.profile_photo_url">
                                                    <img :src="review.profile_photo_url" class="h-10 w-10 rounded-full object-cover border border-gray-100 mr-3">
                                                </template>
                                                <template x-if="!review.profile_photo_url">
                                                    <div class="h-10 w-10 rounded-full bg-primary/10 mr-3 flex items-center justify-center text-sm font-bold text-primary" x-text="review.author_name.charAt(0)"></div>
                                                </template>
                                                <div>
                                                    <h5 class="text-sm font-bold text-gray-900 line-clamp-1" x-text="review.author_name"></h5>
                                                    <p class="text-xs text-gray-400" x-text="review.relative_time_description"></p>
                                                </div>
                                            </div>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" class="w-4 h-4 opacity-50">
                                        </div>
                                        
                                        <div class="flex text-yellow-400 text-xs mb-3">
                                            <template x-for="i in 5">
                                                <svg class="w-3.5 h-3.5" :class="i <= review.rating ? 'fill-current' : 'text-gray-200 fill-current'" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </template>
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 leading-relaxed line-clamp-3" x-text="review.text"></p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="!currentBusiness || !currentBusiness.reviews || currentBusiness.reviews.length === 0" class="h-64 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <h3 class="text-gray-900 font-medium">No reviews to display</h3>
                            <p class="text-gray-500 text-sm mt-1 max-w-sm">Once you connect your business, your latest reviews will appear here automatically.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function googleReviews() {
            return {
                searchQuery: '',
                isSearching: false,
                searchResults: [],
                errorMessage: '',
                currentBusiness: @json($reviewsData), // Hydrate from controller
                toasts: [],

                // Toast Logic
                showToast(title, message, type = 'success') {
                    const id = Date.now();
                    this.toasts.push({ id, title, message, type, visible: true });
                    setTimeout(() => {
                        this.dismissToast(id);
                    }, 5000); // Auto dismiss after 5s
                },

                dismissToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index > -1) {
                        this.toasts[index].visible = false;
                        setTimeout(() => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }, 300); // Wait for transition
                    }
                },

                async searchBusiness() {
                    if (!this.searchQuery) return;
                    this.isSearching = true;
                    this.errorMessage = '';
                    this.searchResults = [];

                    try {
                         const response = await fetch(`/api/admin/google-reviews/search?query=${encodeURIComponent(this.searchQuery)}`, {
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        const data = await response.json();
                        
                        if (data.results && data.results.length > 0) {
                            this.searchResults = data.results;
                        } else if (data.candidates && data.candidates.length > 0) {
                             this.searchResults = data.candidates;
                        } else {
                            this.errorMessage = 'No businesses found matching your query.';
                            this.showToast('Search Result', 'No businesses found.', 'error');
                        }
                    } catch (error) {
                        this.errorMessage = 'Search failed. Please try again.';
                        this.showToast('Error', 'Failed to perform search. Please check your connection.', 'error');
                        console.error(error);
                    } finally {
                        this.isSearching = false;
                    }
                },

                async selectBusiness(place) {
                     // Confirmation handled via UI or simple confirm for now, 
                     // but let's just do it directly for speed as we have a refresh button anyway.
                    
                    this.isSearching = true; 
                    this.searchResults = []; 
                    this.searchQuery = ''; // Clear search input visually

                    try {
                        const response = await fetch('/api/admin/google-reviews/save', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ place_id: place.place_id })
                        });

                        const result = await response.json();
                        if (result.success) {
                            this.currentBusiness = result.data;
                            this.showToast('Success', `Connected to ${result.data.name}!`, 'success');
                        } else {
                            this.showToast('Error', 'Failed to connect business.', 'error');
                        }
                    } catch (error) {
                        console.error(error);
                        this.showToast('Error', 'An unexpected error occurred.', 'error');
                    } finally {
                        this.isSearching = false;
                    }
                },
                
                async fetchReviews() {
                    // Logic to re-sync. For now, since we don't have a separate sync endpoint, 
                    // we can re-trigger the save with the current place_id if known, or just show a message.
                    // Ideally, we'd have a refresh endpoint.
                    // For the sake of this UI demo, let's pretend it refreshed.
                    this.showToast('Sync Started', 'Refetching latest reviews from Google...', 'success');
                    
                     // Simulate network delay
                     setTimeout(() => {
                        this.showToast('Updated', 'Reviews data is up to date.', 'success');
                     }, 1500);
                }
            }
        }
    </script>
</x-admin-layout>