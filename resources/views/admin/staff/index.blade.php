<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Staff Management') }}
            </h2>
            <a href="{{ route('admin.staff.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-orange-500 border border-transparent rounded-xl font-bold text-xs text-white shadow-md hover:bg-orange-600 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-300 uppercase tracking-wider">
                <i class="fa-solid fa-plus mr-2 text-[10px]"></i>
                Add New Staff
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50/50 min-h-screen">
        <div class="w-full px-6">


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

            <div class="space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div class="relative w-full group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-300 group-focus-within:text-orange-500 transition-colors duration-300 text-base"></i>
                        </div>
                        <input type="text" id="staff-search-input"
                            class="block w-full pl-14 pr-6 py-4 border-0 rounded-2xl leading-5 bg-white shadow-sm ring-1 ring-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:shadow-xl text-base transition-all duration-300"
                            placeholder="Type name, email or mobile number to search...">
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider bg-white px-4 py-2 rounded-full shadow-sm ring-1 ring-gray-100">{{ $staff->count() }} MEMBERS</span>
                    </div>
                </div>

                <div class="w-full">
                    <table class="w-full border-separate border-spacing-y-2">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="px-8 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    Member</th>
                                <th scope="col"
                                    class="px-8 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    Contact</th>
                                <th scope="col"
                                    class="px-8 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-8 py-3 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="staff-table-body">
                            @forelse ($staff as $person)
                                <tr class="bg-white hover:bg-orange-50/30 transition-all duration-300 group search-item shadow-sm hover:shadow-xl">
                                    <td class="px-8 py-4 first:rounded-l-2xl">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div
                                                    class="h-12 w-12 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-orange-500/20 ring-4 ring-white">
                                                    {{ strtoupper(substr($person->name, 0, 2)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-base font-bold text-gray-900 search-name leading-tight">{{ $person->name }}</div>
                                                <div class="text-xs text-gray-400 font-medium mt-0.5">Joined {{ $person->created_at->format('M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center text-sm text-gray-600 search-email font-medium">
                                                <i class="fa-regular fa-envelope w-5 text-gray-300"></i>
                                                {{ $person->email }}
                                            </div>
                                            @if($person->phone)
                                                <div class="flex items-center text-sm text-gray-600 search-phone font-medium">
                                                    <i class="fa-solid fa-phone w-5 text-gray-300 text-xs"></i>
                                                    {{ $person->phone }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <span
                                            class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 tracking-wider shadow-sm shadow-emerald-500/5">
                                            <i class="fa-solid fa-circle text-[6px] mr-2 animate-pulse"></i>
                                            ACTIVE
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-right last:rounded-r-2xl">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('admin.staff.edit', $person) }}"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-orange-500 hover:bg-orange-50 hover:shadow-sm transition-all duration-300"
                                                title="Edit Details">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                            </a>
                                            <form action="{{ route('admin.staff.destroy', $person) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Are you sure you want to remove {{ $person->name }} from the team?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-red-600 hover:bg-red-50 hover:shadow-sm transition-all duration-300"
                                                    title="Delete Staff">
                                                    <i class="fa-solid fa-trash-can text-sm"></i>
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