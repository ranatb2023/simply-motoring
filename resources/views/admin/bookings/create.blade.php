<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('New Booking') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Add a Booking</h1>
                    <p class="text-sm text-gray-500 mt-1">Log a booking taken over the phone, in person, or manually.</p>
                </div>
                <a href="{{ route('admin.bookings.index') }}"
                    class="text-sm text-gray-500 hover:text-gray-800">&larr; Back to bookings</a>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.bookings.store') }}" method="POST"
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                @csrf

                {{-- Source --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Booking source</label>
                    <select name="source"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="phone" {{ old('source', 'phone') === 'phone' ? 'selected' : '' }}>Phone call</option>
                        <option value="walk-in" {{ old('source') === 'walk-in' ? 'selected' : '' }}>Walk-in</option>
                        <option value="online" {{ old('source') === 'online' ? 'selected' : '' }}>Online</option>
                    </select>
                </div>

                {{-- Service --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service <span class="text-red-500">*</span></label>
                    <select name="service_id" required
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Select a service…</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                                @if (!is_null($service->price)) — £{{ rtrim(rtrim(number_format($service->price, 2), '0'), '.') }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Date + Time --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="date" value="{{ old('date') }}" required
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time <span class="text-red-500">*</span></label>
                        <input type="time" name="time" value="{{ old('time') }}" required
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Customer --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer name <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" value="{{ old('customer_name') }}" required maxlength="100"
                        placeholder="e.g. John Smith"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" maxlength="20"
                            placeholder="e.g. 07xxx xxxxxx"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle reg</label>
                        <input type="text" name="vehicle_reg" value="{{ old('vehicle_reg') }}" maxlength="20"
                            placeholder="e.g. AB12 CDE" style="text-transform:uppercase"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email
                        <span class="text-gray-400 font-normal">(optional — needed to email a confirmation)</span></label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}" maxlength="100"
                        placeholder="e.g. john@example.com"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold text-white shadow-sm transition-colors"
                        style="background-color:#ea580c;"
                        onmouseover="this.style.backgroundColor='#c2410c'"
                        onmouseout="this.style.backgroundColor='#ea580c'">
                        Add Booking
                    </button>
                    <a href="{{ route('admin.bookings.index') }}"
                        class="px-6 py-2.5 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
