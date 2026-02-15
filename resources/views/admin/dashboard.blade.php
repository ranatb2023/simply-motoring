<x-admin-layout>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Welcome Card -->
        <div
            class="md:col-span-1 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden">
            <div class="z-10">
                <p class="text-gray-400 font-medium text-sm mb-1">GOOD DAY,</p>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ Auth::user()->name }}!</h2>
                <div class="flex items-center text-gray-500 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>{{ now()->format('M d, Y') }}</span>
                    <svg class="w-4 h-4 ml-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ now()->format('h:i A') }}</span>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-4 translate-y-4">
                <svg class="w-32 h-32 text-primary" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>

        <!-- Orders -->
        <div class="md:col-span-1 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-gray-400 font-medium text-sm">BOOKINGS</h3>
                <span class="p-2 bg-orange-50 text-primary rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </span>
            </div>
            <div class="mb-2">
                <span class="text-3xl font-bold text-gray-800">{{ \App\Models\Booking::count() }}</span>
            </div>
            <div class="flex items-center text-red-500 text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                <span>1.89%</span>
                <span class="text-gray-400 ml-1 font-normal">Since last month</span>
            </div>
        </div>

        <!-- Revenue -->
        <div class="md:col-span-1 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-gray-400 font-medium text-sm">REVENUE</h3>
                <span class="p-2 bg-primary/10 text-primary rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </span>
            </div>
            <div class="mb-2">
                <span class="text-3xl font-bold text-gray-800">£0.00</span>
            </div>
            <div class="flex items-center text-red-500 text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                <span>5.23%</span>
                <span class="text-gray-400 ml-1 font-normal">Since last month</span>
            </div>
        </div>

        <!-- Growth -->
        <div class="md:col-span-1 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-gray-400 font-medium text-sm">GROWTH</h3>
                <span class="p-2 bg-orange-50 text-primary rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </span>
            </div>
            <div class="mb-2">
                <span class="text-3xl font-bold text-gray-800">+ 25.08%</span>
            </div>
            <div class="flex items-center text-green-500 text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18">
                    </path>
                </svg>
                <span>4.87%</span>
                <span class="text-gray-400 ml-1 font-normal">Since last month</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Store Performance -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-800 font-semibold">Store Performance Analytics</h3>
                <button class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="relative h-64 flex justify-center items-center">
                <!-- Placeholder for Donut Chart -->
                <canvas id="performanceChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-gray-500 text-sm">Total</span>
                    <span class="text-3xl font-bold text-gray-800">140</span>
                </div>
            </div>
            <div class="text-center mt-4">
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    POOR SALES
                </span>
            </div>
        </div>

        <!-- Weekly Performance -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-800 font-semibold">Weekly Performance Insights</h3>
                <button class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="h-64">
                <!-- Placeholder for Bar Chart -->
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Scripts for Charts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Donut Chart
            const ctx1 = document.getElementById('performanceChart').getContext('2d');
            new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Pending', 'Cancelled'],
                    datasets: [{
                        data: [60, 30, 10],
                        backgroundColor: ['#fb5200', '#6b7280', '#fbbf24'],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Bar Chart
            const ctx2 = document.getElementById('weeklyChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar', // Using bar to simulate the line range chart kind of look or just standard bar
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Bookings',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        backgroundColor: '#fb5200',
                        borderRadius: 5,
                        barThickness: 10
                    },
                    {
                        label: 'Revenue',
                        data: [28, 48, 40, 19, 86, 27, 90],
                        backgroundColor: '#1f2937',
                        borderRadius: 5,
                        barThickness: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                borderDash: [5, 5]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-admin-layout>