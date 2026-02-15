<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Management') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 px-4 sm:px-0 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Team Members</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Manage your staff scheduling and permissions.</p>
                </div>
                <a href="{{ route('admin.staff.create') }}"
                    class="inline-flex items-center px-5 py-3 bg-primary border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:bg-orange-600 hover:shadow-xl focus:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Staff
                </a>
            </div>

            <!-- Notifications -->
            @if ($success = session('success'))
                <div class="mb-6 mx-4 sm:mx-0 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start"
                    role="alert">
                    <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-green-700 font-medium">{{ $success }}</p>
                </div>
            @endif

            <!-- Main Content Card -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">

                <!-- Toolbar -->
                <div
                    class="p-5 border-b border-gray-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="relative w-full max-w-sm group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="staff-search-input"
                            class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary sm:text-sm transition-all duration-200"
                            placeholder="Search by name, email or phone...">
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $staff->count() }}
                            Members</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/80">
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Member Details</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Contact Info</th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100" id="staff-table-body">
                            @forelse ($staff as $person)
                                <tr class="hover:bg-orange-50/30 transition duration-150 ease-in-out group search-item">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-12 w-12 cursor-pointer transform hover:scale-105 transition-transform duration-200">
                                                <div
                                                    class="h-12 w-12 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 flex items-center justify-center text-primary font-bold text-lg shadow-sm border-2 border-white ring-2 ring-gray-50">
                                                    {{ strtoupper(substr($person->name, 0, 2)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 search-name">{{ $person->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 mt-0.5">Joined
                                                    {{ $person->created_at->format('M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center text-sm text-gray-600 search-email">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ $person->email }}
                                            </div>
                                            @if($person->phone)
                                                <div class="flex items-center text-sm text-gray-600 search-phone">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    {{ $person->phone }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div
                                            class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity duration-200">
                                            <a href="{{ route('admin.staff.edit', $person) }}"
                                                class="p-2 bg-gray-50 rounded-lg text-gray-500 hover:text-primary hover:bg-orange-50 border border-gray-200 hover:border-orange-200 transition-all duration-200"
                                                title="Edit Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.staff.destroy', $person) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Are you sure you want to remove {{ $person->name }} from the team?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 bg-gray-50 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 border border-gray-200 hover:border-red-200 transition-all duration-200"
                                                    title="Delete Staff">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-24 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mb-6 shadow-sm">
                                                <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">No staff members yet</h3>
                                            <p class="text-gray-500 max-w-sm mx-auto mb-8">Get started by adding your first
                                                team member to manage appointments and schedules.</p>
                                            <a href="{{ route('admin.staff.create') }}"
                                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl shadow-lg text-white bg-primary hover:bg-orange-600 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Add First Member
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
            const searchInput = document.getElementById('staff-search-input');
            const tableBody = document.getElementById('staff-table-body');

            if (searchInput && tableBody) {
                searchInput.addEventListener('input', function (e) {
                    const term = e.target.value.toLowerCase();
                    const rows = tableBody.getElementsByClassName('search-item');

                    Array.from(rows).forEach(row => {
                        const name = row.getElementsByClassName('search-name')[0].innerText.toLowerCase();
                        const email = row.getElementsByClassName('search-email')[0].innerText.toLowerCase();
                        const phone = row.getElementsByClassName('search-phone')[0] ? row.getElementsByClassName('search-phone')[0].innerText.toLowerCase() : '';

                        if (name.includes(term) || email.includes(term) || phone.includes(term)) {
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