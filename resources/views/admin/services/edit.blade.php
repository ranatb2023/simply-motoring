<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.services.update', $service) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Service Name:</label>
                            <input type="text" name="name" id="name" value="{{ $service->name }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type:</label>
                            <select name="type" id="type"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="mot" {{ $service->type == 'mot' ? 'selected' : '' }}>MOT</option>
                                <option value="service" {{ $service->type == 'service' ? 'selected' : '' }}>Service
                                </option>
                                <option value="repair" {{ $service->type == 'repair' ? 'selected' : '' }}>Repair</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="duration_minutes" class="block text-gray-700 text-sm font-bold mb-2">Duration
                                (Minutes):</label>
                            <input type="number" name="duration_minutes" id="duration_minutes"
                                value="{{ $service->duration_minutes }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price (£):</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ $service->price }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="bg-primary hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Service
                            </button>
                            <a href="{{ route('admin.services.index') }}"
                                class="text-gray-600 hover:text-gray-800">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </x-app-layout>