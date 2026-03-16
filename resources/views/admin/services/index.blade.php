<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Services') }}
            </h2>
            <a href="{{ route('admin.services.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-orange-500 border border-transparent rounded-xl font-bold text-xs text-white shadow-md hover:bg-orange-600 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-300 uppercase tracking-wider">
                <i class="fa-solid fa-plus mr-2 text-[10px]"></i>
                Add New Service
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50/50 min-h-screen">
        <div class="w-full px-6">

            <!-- Notifications -->
            @if ($success = session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start"
                    role="alert">
                    <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-green-700 font-medium">{{ $success }}</p>
                </div>
            @endif

            <div class="space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="relative w-full group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-300 group-focus-within:text-orange-500 transition-colors duration-300 text-base"></i>
                        </div>
                        <input type="text" id="service-search-input"
                            class="block w-full pl-14 pr-6 py-4 border-0 rounded-2xl leading-5 bg-white shadow-sm ring-1 ring-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:shadow-xl text-base transition-all duration-300"
                            placeholder="Search by name or type...">
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider bg-white px-4 py-2 rounded-full shadow-sm ring-1 ring-gray-100">{{ $services->count() }} SERVICES</span>
                    </div>
                </div>

                <div class="w-full">
                    <table class="w-full border-separate border-spacing-y-2">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="px-8 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    Service</th>
                                <th scope="col"
                                    class="px-8 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    Type</th>
                                <th scope="col"
                                    class="px-8 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    Duration</th>
                                <th scope="col"
                                    class="px-8 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    Price</th>
                                <th scope="col"
                                    class="px-8 py-3 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="service-table-body">
                            @forelse ($services as $service)
                                <tr class="bg-white hover:bg-orange-50/30 transition-all duration-300 group search-item shadow-sm hover:shadow-xl">
                                    <td class="px-8 py-4 first:rounded-l-2xl">
                                        <div class="flex items-center gap-4">
                                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 ring-4 ring-white shrink-0">
                                                <i class="fa-solid fa-wrench text-white text-sm"></i>
                                            </div>
                                            <div class="text-base font-bold text-gray-900 search-name leading-tight">{{ $service->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 tracking-wider capitalize search-type">
                                            {{ $service->type }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-2 text-sm text-gray-600 font-medium">
                                            <i class="fa-regular fa-clock text-gray-300"></i>
                                            {{ $service->duration_minutes }} mins
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="text-base font-bold text-gray-900">£{{ number_format($service->price, 2) }}</div>
                                    </td>
                                    <td class="px-8 py-4 text-right last:rounded-r-2xl">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('admin.services.edit', $service) }}"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-orange-500 hover:bg-orange-50 hover:shadow-sm transition-all duration-300"
                                                title="Edit Service">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </a>
                                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Are you sure you want to delete {{ $service->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-red-600 hover:bg-red-50 hover:shadow-sm transition-all duration-300"
                                                    title="Delete Service">
                                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-24 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mb-6 shadow-sm">
                                                <i class="fa-solid fa-wrench text-primary text-3xl"></i>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">No services yet</h3>
                                            <p class="text-gray-500 max-w-sm mx-auto mb-8">Get started by adding your first service.</p>
                                            <a href="{{ route('admin.services.create') }}"
                                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl shadow-lg text-white bg-primary hover:bg-orange-600 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                                <i class="fa-solid fa-plus mr-2"></i>
                                                Add First Service
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('service-search-input');
            const tableBody = document.getElementById('service-table-body');

            if (searchInput && tableBody) {
                searchInput.addEventListener('input', function (e) {
                    const term = e.target.value.toLowerCase();
                    const rows = tableBody.getElementsByClassName('search-item');

                    Array.from(rows).forEach(row => {
                        const name = row.getElementsByClassName('search-name')[0].innerText.toLowerCase();
                        const type = row.getElementsByClassName('search-type')[0].innerText.toLowerCase();

                        if (name.includes(term) || type.includes(term)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
</x-admin-layout>
