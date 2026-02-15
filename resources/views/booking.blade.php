<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book a Service - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .gbs-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .gbs-wizard-wrapper {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .gbs-step-content {
            margin-top: 20px;
        }

        .gbs-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
        }

        .gbs-step {
            padding-bottom: 10px;
            color: #999;
            font-weight: 500;
        }

        .gbs-step.active {
            color: #FB5200;
            border-bottom: 2px solid #FB5200;
            margin-bottom: -2px;
        }

        .gbs-services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .gbs-service-card {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .gbs-service-card:hover,
        .gbs-service-card.selected {
            border-color: #FB5200;
            background-color: #fff8f5;
        }

        .gbs-slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
            max-height: 300px;
            overflow-y: auto;
        }

        .gbs-slot-btn {
            border: 1px solid #ddd;
            background: #fff;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .gbs-slot-btn:hover,
        .gbs-slot-btn.selected {
            background: #FB5200;
            color: #fff;
            border-color: #FB5200;
        }

        .gbs-btn {
            background: #FB5200;
            color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }

        .gbs-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .gbs-btn-secondary {
            background: #eee;
            color: #333;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            margin-right: 10px;
        }

        .gbs-actions {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .gbs-input,
        .gbs-input-large {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .gbs-input-large {
            font-size: 1.5rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased text-gray-900">

    <div class="min-h-screen py-10">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800"><span class="text-primary">⚡</span> Simply Motoring</h1>
            <p class="text-gray-500">Book your vehicle service online</p>
        </div>

        <script>
            const gbsData = {
                apiUrl: '/api/',
                nonce: '{{ csrf_token() }}' // For API if needed, standard csrf for web routes
            };

            document.addEventListener('alpine:init', () => {
                Alpine.data('gbsApp', () => ({
                    step: 1,
                    loading: false,
                    completed: false,
                    error: false,

                    services: [],
                    availableSlots: [],
                    selectedDate: '',

                    booking: {
                        reg: '',
                        service: null,
                        slot: null
                    },

                    customer: {
                        name: '',
                        email: '',
                        phone: ''
                    },

                    get isValid() {
                        return this.customer.name && this.customer.email && this.customer.phone;
                    },

                    init() {
                        // Default Date: Tomorrow
                        const tomorrow = new Date();
                        tomorrow.setDate(tomorrow.getDate() + 1);
                        this.selectedDate = tomorrow.toISOString().split('T')[0];
                        this.fetchServices();
                    },

                    async fetchServices() {
                        this.loading = true;
                        try {
                            const res = await fetch(gbsData.apiUrl + 'services');
                            if (!res.ok) throw new Error('API Error');
                            this.services = await res.json();
                        } catch (e) {
                            console.error(e);
                            this.error = true;
                        }
                        this.loading = false;
                    },

                    selectService(service) {
                        this.booking.service = service;
                    },

                    async fetchSlots() {
                        if (!this.selectedDate || !this.booking.service) return;

                        this.loading = true;

                        try {
                            const url = new URL(window.location.origin + gbsData.apiUrl + 'slots');
                            url.searchParams.append('date', this.selectedDate);
                            url.searchParams.append('service_id', this.booking.service.id);

                            const res = await fetch(url);
                            const json = await res.json();

                            if (json.error) {
                                alert(json.error);
                                this.availableSlots = [];
                            } else {
                                this.availableSlots = json;
                            }
                            this.booking.slot = null;
                            if (this.step < 3) this.step = 3;

                        } catch (e) {
                            console.error(e);
                        }
                        this.loading = false;
                    },

                    async submitBooking() {
                        if (!this.isValid) return;
                        this.loading = true;

                        const payload = {
                            reg: this.booking.reg,
                            service_id: this.booking.service.id,
                            bay_id: this.booking.slot.bay_id,
                            start: this.booking.slot.start,
                            duration: this.booking.service.duration_minutes,
                            customer: this.customer
                        };

                        try {
                            const res = await fetch(gbsData.apiUrl + 'book', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
                                },
                                body: JSON.stringify(payload)
                            });

                            const json = await res.json();
                            if (json.success) {
                                this.completed = true;
                            } else {
                                alert('Error: ' + (json.message || 'Unknown'));
                            }
                        } catch (e) {
                            console.error(e);
                            alert('Connection error');
                        }
                        this.loading = false;
                    },

                    nextStep() {
                        if (this.step === 1 && this.booking.reg) {
                            this.step = 2;
                        } else if (this.step === 2 && this.booking.service) {
                            this.fetchSlots();
                        } else if (this.step === 3 && this.booking.slot) {
                            this.step = 4;
                        }
                    },

                    formatMoney(amount) {
                        // Simple formatter
                        return '£' + Number(amount || 0).toFixed(2);
                    },

                    formatDate(dateStr) {
                        if (!dateStr) return '';
                        return new Date(dateStr).toLocaleString();
                    },

                    reset() {
                        this.step = 1;
                        this.completed = false;
                        this.booking = { reg: '', service: null, slot: null };
                        this.customer = { name: '', email: '', phone: '' };
                    }
                }));
            });
        </script>

        <div id="gbs-booking-app" x-data="gbsApp" x-cloak>
            <div class="gbs-container">

                <!-- Loading -->
                <div x-show="loading" class="text-center py-10" style="display: none;">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                    <p class="mt-2 text-gray-500">Loading...</p>
                </div>

                <!-- Wizard -->
                <div x-show="!loading && !completed" class="gbs-wizard-wrapper">
                    <div class="gbs-wizard">

                        <!-- Progress -->
                        <div class="gbs-progress">
                            <div class="gbs-step" :class="step >= 1 ? 'active' : ''">1. Vehicle</div>
                            <div class="gbs-step" :class="step >= 2 ? 'active' : ''">2. Service</div>
                            <div class="gbs-step" :class="step >= 3 ? 'active' : ''">3. Date/Time</div>
                            <div class="gbs-step" :class="step >= 4 ? 'active' : ''">4. Details</div>
                        </div>

                        <!-- Step 1 -->
                        <div x-show="step === 1" class="gbs-step-content">
                            <h2 class="text-xl font-bold mb-4">Enter Vehicle Registration</h2>
                            <input type="text" x-model="booking.reg" placeholder="AB12 CDE" class="gbs-input-large">
                            <div class="text-right">
                                <button @click="nextStep()" :disabled="!booking.reg" class="gbs-btn">Next</button>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div x-show="step === 2" class="gbs-step-content" style="display: none;">
                            <h2 class="text-xl font-bold mb-4">Select Service</h2>
                            <div class="gbs-services-grid">
                                <template x-for="service in services" :key="service.id">
                                    <div class="gbs-service-card"
                                        :class="booking.service && booking.service.id === service.id ? 'selected' : ''"
                                        @click="selectService(service)">
                                        <h3 class="font-bold text-lg" x-text="service.name"></h3>
                                        <p class="text-primary font-bold" x-text="formatMoney(service.price)"></p>
                                        <span class="text-sm text-gray-500"
                                            x-text="service.duration_minutes + ' mins'"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="gbs-actions">
                                <button @click="step--" class="gbs-btn-secondary">Back</button>
                                <button @click="nextStep()" :disabled="!booking.service" class="gbs-btn">Next</button>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div x-show="step === 3" class="gbs-step-content" style="display: none;">
                            <h2 class="text-xl font-bold mb-4">Select a Time</h2>
                            <input type="date" x-model="selectedDate" @change="fetchSlots()"
                                class="gbs-input w-auto mb-6">

                            <div class="gbs-slots-grid">
                                <template x-if="availableSlots.length === 0 && !loading">
                                    <p class="col-span-full text-center text-gray-500">No slots available for this date.
                                    </p>
                                </template>
                                <template x-for="slot in availableSlots" :key="slot.start">
                                    <button class="gbs-slot-btn"
                                        :class="booking.slot && booking.slot.start === slot.start ? 'selected' : ''"
                                        @click="booking.slot = slot" x-text="slot.display"></button>
                                </template>
                            </div>

                            <div class="gbs-actions">
                                <button @click="step--" class="gbs-btn-secondary">Back</button>
                                <button @click="step++" :disabled="!booking.slot" class="gbs-btn">Next</button>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div x-show="step === 4" class="gbs-step-content" style="display: none;">
                            <h2 class="text-xl font-bold mb-4">Your Details</h2>
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" x-model="customer.name" class="gbs-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" x-model="customer.email" class="gbs-input">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <input type="tel" x-model="customer.phone" class="gbs-input">
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <h3 class="font-bold mb-2">Summary</h3>
                                <p><strong>Vehicle:</strong> <span x-text="booking.reg"></span></p>
                                <p><strong>Service:</strong> <span
                                        x-text="booking.service ? booking.service.name : ''"></span></p>
                                <p><strong>Date:</strong> <span
                                        x-text="formatDate(booking.slot ? booking.slot.start : '')"></span></p>
                                <p><strong>Price:</strong> <span
                                        x-text="formatMoney(booking.service ? booking.service.price : 0)"></span></p>
                            </div>

                            <div class="gbs-actions">
                                <button @click="step--" class="gbs-btn-secondary">Back</button>
                                <button @click="submitBooking()" :disabled="!isValid" class="gbs-btn">Confirm
                                    Booking</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success -->
                <div x-show="completed" class="gbs-wizard-wrapper text-center py-10" style="display: none;">
                    <div class="mb-4 text-green-500">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">Booking Confirmed!</h2>
                    <p class="text-gray-500 mb-6">Thank you for booking with us. A confirmation email has been sent.</p>
                    <button @click="reset()" class="gbs-btn">Book Another</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>