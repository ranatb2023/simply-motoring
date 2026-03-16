<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bookings</h2>
            <a href="{{ route('admin.bookings.create') }}"
                class="inline-flex items-center px-5 py-2.5 bg-orange-500 border border-transparent rounded-xl font-bold text-xs text-white shadow-md hover:bg-orange-600 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-300 uppercase tracking-wider">
                <i class="fa-solid fa-plus mr-2 text-[10px]"></i>
                New Booking
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50/50 min-h-screen">
        <div class="w-full px-6 space-y-6">

            {{-- Flash message --}}
            @if($success = session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    <p class="text-sm text-green-700 font-medium">{{ $success }}</p>
                </div>
            @endif

            {{-- Stats cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                @php
                    $statCards = [
                        ['label' => 'Total',     'value' => $stats['total'],     'icon' => 'fa-calendar-days',  'color' => 'orange'],
                        ['label' => 'Today',     'value' => $stats['today'],     'icon' => 'fa-clock',          'color' => 'blue'],
                        ['label' => 'Upcoming',  'value' => $stats['upcoming'],  'icon' => 'fa-calendar-check', 'color' => 'violet'],
                        ['label' => 'Confirmed', 'value' => $stats['confirmed'], 'icon' => 'fa-circle-check',   'color' => 'green'],
                        ['label' => 'Cancelled', 'value' => $stats['cancelled'], 'icon' => 'fa-circle-xmark',   'color' => 'red'],
                    ];
                    $colorMap = [
                        'orange' => 'bg-orange-50 text-orange-500 ring-orange-100',
                        'blue'   => 'bg-blue-50 text-blue-500 ring-blue-100',
                        'violet' => 'bg-violet-50 text-violet-500 ring-violet-100',
                        'green'  => 'bg-emerald-50 text-emerald-500 ring-emerald-100',
                        'red'    => 'bg-red-50 text-red-500 ring-red-100',
                    ];
                @endphp
                @foreach($statCards as $card)
                    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center ring-1 {{ $colorMap[$card['color']] }}">
                            <i class="fa-solid {{ $card['icon'] }} text-base"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 leading-none">{{ $card['value'] }}</p>
                            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mt-0.5">{{ $card['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Filters --}}
            @php
                $activeDateFilter   = request('date_filter', 'all');
                $activeStatus       = request('status', '');
                $dateFilterLabels   = ['all' => 'All dates', 'today' => 'Today', 'upcoming' => 'Upcoming', 'past' => 'Past'];
                $dateFilterIcons    = ['all' => 'fa-calendar-days', 'today' => 'fa-clock', 'upcoming' => 'fa-calendar-check', 'past' => 'fa-calendar-xmark'];
                $statusLabels       = ['' => 'All statuses', 'confirmed' => 'Confirmed', 'pending' => 'Pending', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
                $statusIcons        = ['' => 'fa-bars-filter', 'confirmed' => 'fa-circle-check', 'pending' => 'fa-clock', 'completed' => 'fa-flag-checkered', 'cancelled' => 'fa-circle-xmark'];
                $statusColors       = ['' => 'text-gray-400', 'confirmed' => 'text-emerald-500', 'pending' => 'text-amber-500', 'completed' => 'text-blue-500', 'cancelled' => 'text-red-500'];
            @endphp

            <style>
                .bk-dropdown-panel {
                    display: none;
                    position: absolute;
                    top: calc(100% + 6px);
                    left: 0;
                    z-index: 50;
                    min-width: 170px;
                    background: #fff;
                    border-radius: 16px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.06);
                    padding: 6px;
                    border: 1px solid #f3f4f6;
                    animation: bkDropIn 0.15s ease;
                }
                .bk-dropdown-panel.open { display: block; }
                @keyframes bkDropIn {
                    from { opacity: 0; transform: translateY(-4px) scale(0.98); }
                    to   { opacity: 1; transform: translateY(0) scale(1); }
                }
                .bk-dropdown-item {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 9px 12px;
                    border-radius: 10px;
                    font-size: 13px;
                    font-weight: 500;
                    color: #374151;
                    cursor: pointer;
                    transition: background 0.12s;
                    white-space: nowrap;
                }
                .bk-dropdown-item:hover { background: #f9fafb; }
                .bk-dropdown-item.active { background: #fff7f2; color: #ea580c; font-weight: 600; }
                .bk-dropdown-item.active .bk-item-icon { color: #ea580c; }
                .bk-item-icon { width: 16px; text-align: center; font-size: 11px; color: #9ca3af; }
                .bk-item-check { margin-left: auto; color: #ea580c; font-size: 10px; }
                .bk-trigger {
                    display: flex; align-items: center; gap: 8px;
                    padding: 10px 16px;
                    background: #fff;
                    border-radius: 14px;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
                    ring: 1px solid #e5e7eb;
                    border: 1px solid #e5e7eb;
                    font-size: 13.5px;
                    font-weight: 500;
                    color: #374151;
                    cursor: pointer;
                    transition: border-color 0.15s, box-shadow 0.15s;
                    white-space: nowrap;
                    user-select: none;
                }
                .bk-trigger:hover { border-color: #fb923c; box-shadow: 0 2px 8px rgba(251,146,60,0.15); }
                .bk-trigger.has-value { border-color: #f97316; background: #fff7f2; color: #c2410c; }
                .bk-trigger.has-value .bk-trigger-icon { color: #f97316; }
                .bk-trigger-icon { font-size: 12px; color: #9ca3af; }
                .bk-trigger-chevron { font-size: 9px; color: #9ca3af; margin-left: 2px; transition: transform 0.15s; }
                .bk-trigger.open .bk-trigger-chevron { transform: rotate(180deg); }
                .bk-separator { height: 1px; background: #f3f4f6; margin: 4px 0; }
            </style>

            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Search --}}
                <form id="filterForm" method="GET" action="{{ route('admin.bookings.index') }}" class="flex-1 flex gap-3 items-center">
                    <input type="hidden" name="date_filter" id="hiddenDateFilter" value="{{ $activeDateFilter }}">
                    <input type="hidden" name="status"      id="hiddenStatus"     value="{{ $activeStatus }}">

                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-300 group-focus-within:text-orange-500 transition-colors text-sm"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full pl-11 pr-4 py-[11px] border border-gray-200 rounded-2xl bg-white shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm transition-all"
                            placeholder="Search by name, email or reg…">
                    </div>

                    {{-- Date filter custom dropdown --}}
                    <div class="relative shrink-0" id="dateDropWrap">
                        <div class="bk-trigger {{ $activeDateFilter !== 'all' ? 'has-value' : '' }}" id="dateTrigger" onclick="toggleDrop('date')">
                            <i class="bk-trigger-icon fa-solid {{ $dateFilterIcons[$activeDateFilter] }}"></i>
                            <span id="dateTriggerLabel">{{ $dateFilterLabels[$activeDateFilter] }}</span>
                            <i class="bk-trigger-chevron fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="bk-dropdown-panel" id="dateDropPanel">
                            @foreach($dateFilterLabels as $val => $lbl)
                                <div class="bk-dropdown-item {{ $activeDateFilter === $val ? 'active' : '' }}"
                                     onclick="selectFilter('date', '{{ $val }}', '{{ $lbl }}')">
                                    <i class="bk-item-icon fa-solid {{ $dateFilterIcons[$val] }}"></i>
                                    {{ $lbl }}
                                    @if($activeDateFilter === $val)
                                        <i class="bk-item-check fa-solid fa-check"></i>
                                    @endif
                                </div>
                                @if($val === 'all')<div class="bk-separator"></div>@endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Status filter custom dropdown --}}
                    <div class="relative shrink-0" id="statusDropWrap">
                        <div class="bk-trigger {{ $activeStatus !== '' ? 'has-value' : '' }}" id="statusTrigger" onclick="toggleDrop('status')">
                            <i class="bk-trigger-icon fa-solid {{ $statusIcons[$activeStatus] }} {{ $statusColors[$activeStatus] }}"></i>
                            <span id="statusTriggerLabel">{{ $statusLabels[$activeStatus] }}</span>
                            <i class="bk-trigger-chevron fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="bk-dropdown-panel" id="statusDropPanel">
                            @foreach($statusLabels as $val => $lbl)
                                <div class="bk-dropdown-item {{ $activeStatus === $val ? 'active' : '' }}"
                                     onclick="selectFilter('status', '{{ $val }}', '{{ $lbl }}')">
                                    <i class="bk-item-icon fa-solid {{ $statusIcons[$val] }} {{ $statusColors[$val] }}"></i>
                                    {{ $lbl }}
                                    @if($activeStatus === $val)
                                        <i class="bk-item-check fa-solid fa-check"></i>
                                    @endif
                                </div>
                                @if($val === '')<div class="bk-separator"></div>@endif
                            @endforeach
                        </div>
                    </div>

                    @if(request('search') || $activeStatus !== '' || $activeDateFilter !== 'all')
                        <a href="{{ route('admin.bookings.index') }}"
                            class="flex items-center gap-1.5 px-4 py-[11px] rounded-2xl bg-white border border-gray-200 shadow-sm text-sm text-gray-400 hover:text-red-500 hover:border-red-200 transition-all whitespace-nowrap">
                            <i class="fa-solid fa-xmark text-xs"></i> Clear
                        </a>
                    @endif
                </form>

                {{-- Count badge --}}
                <div class="flex items-center shrink-0">
                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider bg-white px-4 py-2 rounded-full shadow-sm ring-1 ring-gray-100">
                        {{ $bookings->total() }} {{ Str::plural('booking', $bookings->total()) }}
                    </span>
                </div>
            </div>

            {{-- Table --}}
            <div class="w-full">
                <table class="w-full border-separate border-spacing-y-2">
                    <thead>
                        <tr>
                            <th class="px-6 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-2 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            @php
                                $isToday   = $booking->start_datetime->isToday();
                                $isPast    = $booking->start_datetime->isPast();
                                $statusMap = [
                                    'confirmed' => ['bg-emerald-50 text-emerald-600 border-emerald-100',  'fa-circle-check',  'CONFIRMED'],
                                    'pending'   => ['bg-amber-50 text-amber-600 border-amber-100',         'fa-clock',         'PENDING'],
                                    'completed' => ['bg-blue-50 text-blue-600 border-blue-100',            'fa-flag-checkered','COMPLETED'],
                                    'cancelled' => ['bg-red-50 text-red-500 border-red-100',               'fa-circle-xmark',  'CANCELLED'],
                                ];
                                $st = $statusMap[$booking->status] ?? $statusMap['pending'];
                            @endphp
                            <tr class="bg-white hover:bg-orange-50/20 transition-all duration-200 group shadow-sm hover:shadow-md">
                                {{-- Customer --}}
                                <td class="px-6 py-4 first:rounded-l-2xl">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-orange-500/20 ring-2 ring-white shrink-0">
                                            {{ strtoupper(substr($booking->customer_name, 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ $booking->customer_name }}</p>
                                            <p class="text-xs text-gray-400 truncate">{{ $booking->customer_email }}</p>
                                            @if($booking->customer_phone)
                                                <p class="text-xs text-gray-400">{{ $booking->customer_phone }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Service --}}
                                <td class="px-6 py-4">
                                    @if($booking->service)
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-orange-50 text-orange-600 border border-orange-100">
                                            <i class="fa-solid fa-wrench text-[9px] mr-1.5"></i>
                                            {{ $booking->service->name }}
                                        </span>
                                        @if($booking->sub_service)
                                            <p class="text-xs text-gray-400 mt-1 pl-0.5">{{ $booking->sub_service }}</p>
                                        @endif
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Date & Time --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-lg flex flex-col items-center justify-center shrink-0 {{ $isToday ? 'bg-orange-500 text-white' : 'bg-gray-50 text-gray-700' }}">
                                            <span class="text-[10px] font-bold leading-none uppercase">{{ $booking->start_datetime->format('M') }}</span>
                                            <span class="text-sm font-bold leading-none mt-0.5">{{ $booking->start_datetime->format('d') }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ $isToday ? 'Today' : $booking->start_datetime->format('D, j M Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $booking->start_datetime->format('H:i') }}
                                                @if($booking->end_datetime)
                                                    – {{ $booking->end_datetime->format('H:i') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Vehicle --}}
                                <td class="px-6 py-4">
                                    @if($booking->vehicle_reg)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs font-bold text-gray-700 font-mono tracking-widest uppercase">
                                            <i class="fa-solid fa-car text-gray-300 text-[9px]"></i>
                                            {{ strtoupper($booking->vehicle_reg) }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    <div class="relative status-drop-wrap">
                                        <button type="button" onclick="toggleStatusDrop(this)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold border tracking-wider {{ $st[0] }} hover:opacity-80 transition-opacity">
                                            <i class="fa-solid {{ $st[1] }} text-[9px]"></i>
                                            {{ $st[2] }}
                                            <i class="fa-solid fa-chevron-down text-[8px] ml-0.5 opacity-60"></i>
                                        </button>
                                        <div class="status-drop-panel hidden absolute top-full left-0 mt-1 z-20 bg-white rounded-xl shadow-xl ring-1 ring-gray-100 py-1.5 min-w-[140px]">
                                            @foreach(['confirmed' => ['text-emerald-600','fa-circle-check'], 'pending' => ['text-amber-500','fa-clock'], 'completed' => ['text-blue-600','fa-flag-checkered'], 'cancelled' => ['text-red-500','fa-circle-xmark']] as $s => $si)
                                                @if($s !== $booking->status)
                                                    <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ $s }}">
                                                        <button type="submit"
                                                            class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                                                            <i class="fa-solid {{ $si[1] }} {{ $si[0] }} text-xs w-4"></i>
                                                            {{ ucfirst($s) }}
                                                        </button>
                                                    </form>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right last:rounded-r-2xl">
                                    <div class="flex justify-end items-center gap-2">
                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}"
                                            onsubmit="return confirm('Delete this booking for {{ addslashes($booking->customer_name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all"
                                                title="Delete">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-24 text-center bg-white rounded-2xl shadow-sm">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mb-5 shadow-sm">
                                            <i class="fa-solid fa-calendar-days text-3xl text-orange-300"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 mb-1">No bookings found</h3>
                                        <p class="text-sm text-gray-400 mb-6">
                                            @if(request('search') || request('status') || request('date_filter','all') !== 'all')
                                                No bookings match your current filters.
                                            @else
                                                Bookings made through the website will appear here.
                                            @endif
                                        </p>
                                        @if(request('search') || request('status') || request('date_filter','all') !== 'all')
                                            <a href="{{ route('admin.bookings.index') }}"
                                                class="inline-flex items-center px-5 py-2.5 rounded-xl bg-orange-500 text-white text-sm font-bold hover:bg-orange-600 transition-colors">
                                                <i class="fa-solid fa-xmark mr-2 text-xs"></i> Clear filters
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($bookings->hasPages())
                <div class="flex justify-center">
                    {{ $bookings->links() }}
                </div>
            @endif

        </div>
    </div>

    <script>
        // ── Filter dropdowns ───────────────────────────────────────────────
        function toggleDrop(type) {
            const panel   = document.getElementById(type + 'DropPanel');
            const trigger = document.getElementById(type + 'Trigger');
            const isOpen  = panel.classList.contains('open');
            closeAllDrops();
            if (!isOpen) {
                panel.classList.add('open');
                trigger.classList.add('open');
            }
        }

        function closeAllDrops() {
            ['date', 'status'].forEach(t => {
                document.getElementById(t + 'DropPanel')?.classList.remove('open');
                document.getElementById(t + 'Trigger')?.classList.remove('open');
            });
        }

        function selectFilter(type, value, label) {
            if (type === 'date') {
                document.getElementById('hiddenDateFilter').value = value;
                document.getElementById('dateTriggerLabel').textContent  = label;
            } else {
                document.getElementById('hiddenStatus').value = value;
                document.getElementById('statusTriggerLabel').textContent = label;
            }
            closeAllDrops();
            document.getElementById('filterForm').submit();
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#dateDropWrap') && !e.target.closest('#statusDropWrap')) {
                closeAllDrops();
            }
        });

        // ── Status row dropdowns ────────────────────────────────────────────
        function toggleStatusDrop(btn) {
            const wrap  = btn.closest('.status-drop-wrap');
            const panel = wrap.querySelector('.status-drop-panel');
            const isOpen = !panel.classList.contains('hidden');

            // Close all others
            document.querySelectorAll('.status-drop-panel').forEach(p => p.classList.add('hidden'));

            if (!isOpen) panel.classList.remove('hidden');
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.status-drop-wrap')) {
                document.querySelectorAll('.status-drop-panel').forEach(p => p.classList.add('hidden'));
            }
        });
        </script>
</x-admin-layout>