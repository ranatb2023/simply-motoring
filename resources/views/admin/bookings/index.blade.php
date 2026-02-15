<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-bold text-2xl text-gray-800">Bookings</h2>
        <a href="{{ route('admin.bookings.create') }}"
            class="px-4 py-2 bg-primary text-white rounded-lg shadow-sm hover:bg-orange-600 transition">Create
            Booking</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="text-left font-bold text-gray-500 border-b border-gray-100 bg-gray-50">
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Vehicle</th>
                        <th class="px-6 py-4">Service</th>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="font-bold text-gray-800">{{ $booking->customer_name }}</p>
                                    <p class="text-gray-500">{{ $booking->customer_email }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $booking->vehicle_reg }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($booking->service)
                                    <span
                                        class="px-2 py-1 bg-orange-50 text-orange-600 rounded-md text-xs font-semibold">{{ $booking->service->name }}</span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <p>{{ $booking->start_datetime->format('M d, Y') }}</p>
                                <p class="text-gray-500 text-xs">{{ $booking->start_datetime->format('h:i A') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 {{ $booking->status === 'confirmed' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }} rounded-full text-xs font-bold uppercase">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <button class="text-gray-400 hover:text-primary transition">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>