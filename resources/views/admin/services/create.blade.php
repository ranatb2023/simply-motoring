<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.services.index') }}"
                class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 hover:bg-orange-50 hover:text-orange-500 transition-all duration-200">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h2 class="font-bold text-xl text-gray-900 leading-tight">Create Service</h2>
                <p class="text-xs text-gray-400 mt-0.5">Fill in the details below to add a new service</p>
            </div>
        </div>
    </x-slot>

    <style>
        .accordion-body {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.35s cubic-bezier(0.4,0,0.2,1), opacity 0.25s ease;
            opacity: 0;
        }
        .accordion-body.open {
            max-height: 1200px;
            opacity: 1;
        }
        .field-input {
            display: block;
            width: 100%;
            padding: 0.875rem 1.25rem;
            border: 0;
            border-radius: 0.75rem;
            background: #f9fafb;
            box-shadow: 0 0 0 1px #e5e7eb;
            color: #111827;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: box-shadow 0.2s;
            outline: none;
            appearance: none;
        }
        .field-input::placeholder { color: #d1d5db; }
        .field-input:focus { box-shadow: 0 0 0 2px #f97316; }
        .field-input.pl-icon { padding-left: 3rem; }

        /* ── Custom select dropdown ───────────────── */
        .svc-select { position: relative; }
        .svc-select-trigger {
            display: flex; align-items: center; justify-content: space-between;
            width: 100%; padding: 0.875rem 1.25rem;
            border: 0; border-radius: 0.75rem;
            background: #f9fafb; box-shadow: 0 0 0 1px #e5e7eb;
            color: #111827; font-weight: 500; font-size: 0.9375rem;
            cursor: pointer; text-align: left; font-family: inherit;
            transition: box-shadow 0.2s;
        }
        .svc-select-trigger:focus { box-shadow: 0 0 0 2px #f97316; outline: none; }
        .svc-select.open .svc-select-trigger { box-shadow: 0 0 0 2px #f97316; }
        .svc-select-chevron { transition: transform 0.2s; color: #9ca3af; font-size: 0.7rem; flex-shrink: 0; }
        .svc-select.open .svc-select-chevron { transform: rotate(180deg); }
        .svc-select-panel {
            display: none; position: absolute; top: calc(100% + 5px); left: 0; right: 0;
            background: #fff; border-radius: 0.875rem; z-index: 60;
            box-shadow: 0 12px 40px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid #f0f0f0; padding: 5px; max-height: 220px; overflow-y: auto;
        }
        .svc-select.open .svc-select-panel { display: block; }
        .svc-select-opt {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.625rem 0.875rem; border-radius: 0.5rem;
            font-size: 0.9rem; font-weight: 500; color: #374151;
            cursor: pointer; transition: background 0.1s;
        }
        .svc-select-opt:hover { background: #fff7f2; color: #ea580c; }
        .svc-select-opt.selected { background: #fff7f2; color: #ea580c; font-weight: 600; }
        .svc-select-opt.selected::after {
            content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
            font-size: 0.65rem; color: #f97316;
        }
    </style>

    <div class="py-8 bg-gray-50/60 min-h-screen">
        <div class="w-full px-6" style="max-width:740px;">

            @if ($errors->any())
                <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 p-4 rounded-2xl shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-red-700 mb-1">Please fix the following errors:</p>
                        <ul class="text-sm text-red-600 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-center gap-1.5"><i class="fa-solid fa-minus text-[8px]"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf

                <div class="space-y-2">

                    {{-- ══════════════════════════════════════════
                         1 · SERVICE DETAILS
                    ══════════════════════════════════════════ --}}
                    <div class="accordion-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        <button type="button" class="accordion-trigger w-full flex items-center justify-between px-7 py-5 text-left group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md shadow-orange-200 shrink-0">
                                    <i class="fa-solid fa-wrench text-white"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">Service Details</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Name, description, duration &amp; price</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] font-bold text-orange-500 bg-orange-50 px-2.5 py-1 rounded-full tracking-wider hidden section-required">REQUIRED</span>
                                <i class="fa-solid fa-chevron-down text-gray-300 text-sm transition-transform duration-300 accordion-icon rotate-180"></i>
                            </div>
                        </button>

                        <div class="accordion-body open">
                            <div class="px-7 pb-7 pt-1 grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">
                                        Service Name <span class="text-orange-500">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="field-input"
                                        placeholder="e.g. Full MOT &amp; Service" required>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Description</label>
                                    <textarea name="description" rows="3"
                                        class="field-input resize-none"
                                        placeholder="Write a short description customers will see when booking…">{{ old('description') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">
                                        Duration <span class="text-orange-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fa-regular fa-clock text-gray-300 text-sm"></i>
                                        </div>
                                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes') }}" min="1"
                                            class="field-input pl-icon" placeholder="60" required>
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                            <span class="text-xs text-gray-400 font-medium">min</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">
                                        Price <span class="text-orange-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-gray-400 font-bold text-sm">£</span>
                                        </div>
                                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" min="0"
                                            class="field-input pl-icon" placeholder="0.00" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Options Label</label>
                                    <input type="text" name="options_label" value="{{ old('options_label') }}"
                                        class="field-input" placeholder="e.g. Select Option">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Options (Comma Separated)</label>
                                    <input type="text" name="options" value="{{ old('options') }}"
                                        class="field-input" placeholder="e.g. MOT, MOT &amp; Service">
                                </div>

                                <div class="md:col-span-2">
                                    <div class="flex items-center justify-between p-5 rounded-2xl bg-gradient-to-r from-gray-50 to-white border border-gray-100">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                                                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-800">Active Service</div>
                                                <div class="text-xs text-gray-400">Visible and available for customers to book</div>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="sr-only peer">
                                            <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-orange-400 rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:shadow after:transition-all peer-checked:bg-orange-500"></div>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════
                         2 · AVAILABILITY
                    ══════════════════════════════════════════ --}}
                    <div class="accordion-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        <button type="button" class="accordion-trigger w-full flex items-center justify-between px-7 py-5 text-left">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md shadow-orange-200 shrink-0">
                                    <i class="fa-regular fa-calendar text-white"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">Availability</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Schedule, booking window &amp; time slots</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-gray-300 text-sm transition-transform duration-300 accordion-icon"></i>
                        </button>

                        <div class="accordion-body">
                            <div class="px-7 pb-7 pt-1 grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Assign Schedule</label>
                                    @php $selSchedule = old('schedule_id', ''); @endphp
                                    <div class="svc-select">
                                        <button type="button" class="svc-select-trigger">
                                            <span class="svc-select-label">
                                                @if($selSchedule && ($found = $schedules->find($selSchedule)))
                                                    {{ $found->name }}{{ $found->is_default ? ' (Default)' : '' }}
                                                @else
                                                    — No schedule —
                                                @endif
                                            </span>
                                            <i class="fa-solid fa-chevron-down svc-select-chevron"></i>
                                        </button>
                                        <div class="svc-select-panel">
                                            <div class="svc-select-opt {{ $selSchedule === '' ? 'selected' : '' }}" data-value="">— No schedule —</div>
                                            @foreach ($schedules as $schedule)
                                                <div class="svc-select-opt {{ $selSchedule == $schedule->id ? 'selected' : '' }}" data-value="{{ $schedule->id }}">
                                                    {{ $schedule->name }}{{ $schedule->is_default ? ' (Default)' : '' }}
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="schedule_id" value="{{ $selSchedule }}">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Time Slot Increments</label>
                                    @php $selInc = old('time_increment', 30); @endphp
                                    <div class="svc-select">
                                        <button type="button" class="svc-select-trigger">
                                            <span class="svc-select-label">Every {{ $selInc }} minutes</span>
                                            <i class="fa-solid fa-chevron-down svc-select-chevron"></i>
                                        </button>
                                        <div class="svc-select-panel">
                                            @foreach ([15 => 'Every 15 minutes', 30 => 'Every 30 minutes', 60 => 'Every 60 minutes'] as $val => $lbl)
                                                <div class="svc-select-opt {{ $selInc == $val ? 'selected' : '' }}" data-value="{{ $val }}">{{ $lbl }}</div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="time_increment" value="{{ $selInc }}">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Advance Booking Window</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fa-regular fa-calendar-plus text-gray-300 text-sm"></i>
                                        </div>
                                        <input type="number" name="advance_booking_days" value="{{ old('advance_booking_days', 60) }}" min="1"
                                            class="field-input pl-icon pr-14" placeholder="60">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                            <span class="text-xs text-gray-400 font-medium">days</span>
                                        </div>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1.5 leading-relaxed">How far ahead customers can book</p>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Minimum Notice</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fa-regular fa-clock text-gray-300 text-sm"></i>
                                        </div>
                                        <input type="number" name="min_notice_hours" value="{{ old('min_notice_hours', 4) }}" min="0"
                                            class="field-input pl-icon pr-14" placeholder="4">
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                            <span class="text-xs text-gray-400 font-medium">hrs</span>
                                        </div>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-1.5 leading-relaxed">Minimum notice before a booking</p>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════
                         3 · LIMITS & BUFFERS
                    ══════════════════════════════════════════ --}}
                    <div class="accordion-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        <button type="button" class="accordion-trigger w-full flex items-center justify-between px-7 py-5 text-left">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md shadow-orange-200 shrink-0">
                                    <i class="fa-solid fa-sliders text-white"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">Limits &amp; Buffers</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Buffer times &amp; max bookings per day</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-gray-300 text-sm transition-transform duration-300 accordion-icon"></i>
                        </button>

                        <div class="accordion-body">
                            <div class="px-7 pb-7 pt-1 space-y-5">

                                <!-- Buffer row -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Buffer Before</label>
                                        @php $selBefore = old('buffer_before_minutes', 0); @endphp
                                        <div class="svc-select">
                                            <button type="button" class="svc-select-trigger">
                                                <span class="svc-select-label">{{ $selBefore == 0 ? 'No buffer' : $selBefore.' minutes' }}</span>
                                                <i class="fa-solid fa-chevron-down svc-select-chevron"></i>
                                            </button>
                                            <div class="svc-select-panel">
                                                @foreach ([0,5,10,15,30,45,60] as $m)
                                                    <div class="svc-select-opt {{ $selBefore == $m ? 'selected' : '' }}" data-value="{{ $m }}">
                                                        {{ $m === 0 ? 'No buffer' : $m.' minutes' }}
                                                    </div>
                                                @endforeach
                                            </div>
                                            <input type="hidden" name="buffer_before_minutes" value="{{ $selBefore }}">
                                        </div>
                                        <p class="text-[11px] text-gray-400 mt-1.5">Prep time before each appointment</p>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Buffer After</label>
                                        @php $selAfter = old('buffer_after_minutes', 0); @endphp
                                        <div class="svc-select">
                                            <button type="button" class="svc-select-trigger">
                                                <span class="svc-select-label">{{ $selAfter == 0 ? 'No buffer' : $selAfter.' minutes' }}</span>
                                                <i class="fa-solid fa-chevron-down svc-select-chevron"></i>
                                            </button>
                                            <div class="svc-select-panel">
                                                @foreach ([0,5,10,15,30,45,60] as $m)
                                                    <div class="svc-select-opt {{ $selAfter == $m ? 'selected' : '' }}" data-value="{{ $m }}">
                                                        {{ $m === 0 ? 'No buffer' : $m.' minutes' }}
                                                    </div>
                                                @endforeach
                                            </div>
                                            <input type="hidden" name="buffer_after_minutes" value="{{ $selAfter }}">
                                        </div>
                                        <p class="text-[11px] text-gray-400 mt-1.5">Clean-up time after each appointment</p>
                                    </div>
                                </div>

                                <!-- Max bookings -->
                                <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                        <div class="flex-1">
                                            <div class="text-sm font-bold text-gray-800">Daily Booking Limit</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Maximum number of bookings allowed per day for this service</div>
                                        </div>
                                        <div class="w-full sm:w-36">
                                            <input type="number" name="max_bookings_per_day" value="{{ old('max_bookings_per_day') }}" min="1"
                                                class="field-input text-center" placeholder="Unlimited">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════
                         4 · CUSTOMER DATA
                    ══════════════════════════════════════════ --}}
                    <div class="accordion-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        <button type="button" class="accordion-trigger w-full flex items-center justify-between px-7 py-5 text-left">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md shadow-orange-200 shrink-0">
                                    <i class="fa-solid fa-user text-white"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">Customer Data</div>
                                    <div class="text-xs text-gray-400 mt-0.5">What information to collect at booking</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-gray-300 text-sm transition-transform duration-300 accordion-icon"></i>
                        </button>

                        <div class="accordion-body">
                            <div class="px-7 pb-7 pt-1 space-y-3">

                                <div class="flex items-center justify-between p-5 rounded-2xl bg-gradient-to-r from-gray-50 to-white border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-phone text-blue-400"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">Collect Phone Number</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Show phone number field in the booking form</div>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
                                        <input type="checkbox" name="collect_phone" value="1" {{ old('collect_phone') ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-orange-400 rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:shadow after:transition-all peer-checked:bg-orange-500"></div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between p-5 rounded-2xl bg-gradient-to-r from-gray-50 to-white border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-car text-blue-400"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">Collect Vehicle Registration</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Show vehicle registration field in the booking form</div>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
                                        <input type="checkbox" name="collect_vehicle_reg" value="1" {{ old('collect_vehicle_reg', '1') ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-orange-400 rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:shadow after:transition-all peer-checked:bg-orange-500"></div>
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════
                         5 · ASSIGN STAFF
                    ══════════════════════════════════════════ --}}
                    <div class="accordion-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        <button type="button" class="accordion-trigger w-full flex items-center justify-between px-7 py-5 text-left">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md shadow-orange-200 shrink-0">
                                    <i class="fa-solid fa-users text-white"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">Assign Staff</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Select who can perform this service</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-gray-300 text-sm transition-transform duration-300 accordion-icon"></i>
                        </button>

                        <div class="accordion-body">
                            <div class="px-7 pb-7 pt-1">
                                @if ($users->isEmpty())
                                    <div class="flex flex-col items-center justify-center py-10 text-center">
                                        <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-users text-orange-300 text-xl"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-600">No staff members found</p>
                                        <p class="text-xs text-gray-400 mt-1">Add staff from the Staff Management section first.</p>
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        @foreach ($users as $user)
                                            <label for="user_{{ $user->id }}"
                                                class="flex items-center gap-3 p-4 rounded-xl border border-gray-100 bg-gray-50/50 hover:border-orange-200 hover:bg-orange-50/30 cursor-pointer transition-all duration-200 has-[:checked]:border-orange-300 has-[:checked]:bg-orange-50 has-[:checked]:shadow-sm">
                                                <input type="checkbox" name="user_ids[]" id="user_{{ $user->id }}"
                                                    value="{{ $user->id }}"
                                                    {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}
                                                    class="w-4 h-4 rounded text-orange-500 border-gray-300 focus:ring-orange-400 shrink-0">
                                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-400 capitalize">{{ $user->getRoleNames()->first() ?? 'User' }}</div>
                                                </div>
                                                <i class="fa-solid fa-check text-orange-500 text-xs opacity-0 has-check-indicator shrink-0"></i>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════
                         6 · NOTIFICATIONS
                    ══════════════════════════════════════════ --}}
                    <div class="accordion-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        <button type="button" class="accordion-trigger w-full flex items-center justify-between px-7 py-5 text-left">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md shadow-orange-200 shrink-0">
                                    <i class="fa-regular fa-bell text-white"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">Notifications</div>
                                    <div class="text-xs text-gray-400 mt-0.5">Email confirmations &amp; reminders</div>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-gray-300 text-sm transition-transform duration-300 accordion-icon"></i>
                        </button>

                        <div class="accordion-body">
                            <div class="px-7 pb-7 pt-1">
                                <div class="flex items-center justify-between p-5 rounded-2xl bg-gradient-to-r from-gray-50 to-white border border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-envelope text-blue-400"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">Booking Confirmation Email</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Automatically send a confirmation email after booking</div>
                                        </div>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0 ml-4">
                                        <input type="checkbox" name="send_confirmation_email" value="1" {{ old('send_confirmation_email', '1') ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-orange-400 rounded-full peer peer-checked:after:translate-x-6 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:shadow after:transition-all peer-checked:bg-orange-500"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ══════════════════════════════════════════
                     STICKY ACTION BAR
                ══════════════════════════════════════════ --}}
                <div class="sticky bottom-0 mt-4 -mx-6 px-6 py-4 bg-white/90 backdrop-blur border-t border-gray-100 flex items-center justify-between z-10">
                    <a href="{{ route('admin.services.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-all duration-200">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        Discard
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-7 py-2.5 bg-orange-500 hover:bg-orange-600 active:scale-[0.98] text-white font-bold text-sm rounded-xl shadow-lg shadow-orange-200 hover:shadow-xl hover:shadow-orange-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Service
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Accordion ──────────────────────────────────────────
            document.querySelectorAll('.accordion-card').forEach(function (card) {
                const trigger = card.querySelector('.accordion-trigger');
                const body    = card.querySelector('.accordion-body');
                const icon    = card.querySelector('.accordion-icon');
                trigger.addEventListener('click', function () {
                    const isOpen = body.classList.contains('open');
                    body.classList.toggle('open', !isOpen);
                    icon.style.transform = isOpen ? '' : 'rotate(180deg)';
                });
                if (body.classList.contains('open')) {
                    icon.style.transform = 'rotate(180deg)';
                }
            });

            // ── Custom selects ─────────────────────────────────────
            document.querySelectorAll('.svc-select').forEach(function (wrap) {
                const trigger = wrap.querySelector('.svc-select-trigger');
                const panel   = wrap.querySelector('.svc-select-panel');
                const label   = wrap.querySelector('.svc-select-label');
                const hidden  = wrap.querySelector('input[type=hidden]');
                const opts    = wrap.querySelectorAll('.svc-select-opt');

                trigger.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isOpen = wrap.classList.contains('open');
                    document.querySelectorAll('.svc-select.open').forEach(s => s.classList.remove('open'));
                    if (!isOpen) wrap.classList.add('open');
                });

                opts.forEach(function (opt) {
                    opt.addEventListener('click', function () {
                        opts.forEach(o => o.classList.remove('selected'));
                        opt.classList.add('selected');
                        label.textContent = opt.textContent.trim();
                        hidden.value = opt.dataset.value;
                        wrap.classList.remove('open');
                    });
                });
            });

            document.addEventListener('click', function () {
                document.querySelectorAll('.svc-select.open').forEach(s => s.classList.remove('open'));
            });
        });
    </script>
</x-admin-layout>
