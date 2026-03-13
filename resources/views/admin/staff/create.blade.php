<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Staff') }}
        </h2>
    </x-slot>

    <!-- Load Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/staff-wizard.css'])
    <div class="bg-white min-h-screen" style="font-family: 'Outfit', sans-serif;">
        <div class="w-full">
            <!-- Main Full-Width Container -->
            <div>


                <div
                    style="padding: 14px 32px; border-bottom: 1px solid #f3f4f6; background: #fff; display:flex; align-items:center; justify-content:space-between;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <a href="{{ route('admin.staff.index') }}"
                            style="display:inline-flex; align-items:center; gap:4px; font-size:0.8rem; font-weight:500; color:#6b7280; text-decoration:none; transition:color 0.2s;"
                            onmouseover="this.style.color='#FB5200'" onmouseout="this.style.color='#6b7280'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            Staff Members
                        </a>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                        <span style="font-size:0.85rem; font-weight:700; color:#111827;">Add New Staff</span>
                    </div>
                </div>

                <!-- Wizard Container -->
                <div class="gbs-wizard-container">
                    <!-- Top Steps -->
                    <div class="gbs-wizard-top-steps">
                        <div style="display:flex; align-items:center; max-width:600px; width:100%;">
                            <!-- Step 1 -->
                            <div class="gbs-step-h-item active" data-step="1">
                                <div class="gbs-step-h-circle">1</div>
                                <span class="gbs-step-h-label">General Info</span>
                            </div>
                            <div class="gbs-step-h-line"></div>
                            <!-- Step 2 -->
                            <div class="gbs-step-h-item" data-step="2">
                                <div class="gbs-step-h-circle">2</div>
                                <span class="gbs-step-h-label">Calendar</span>
                            </div>
                            <div class="gbs-step-h-line"></div>
                            <!-- Step 3 -->
                            <div class="gbs-step-h-item" data-step="3">
                                <div class="gbs-step-h-circle">3</div>
                                <span class="gbs-step-h-label">Services</span>
                            </div>
                            <div class="gbs-step-h-line"></div>
                            <!-- Step 4 -->
                            <div class="gbs-step-h-item" data-step="4">
                                <div class="gbs-step-h-circle">4</div>
                                <span class="gbs-step-h-label">Schedule</span>
                            </div>
                            <div class="gbs-step-h-line"></div>
                            <!-- Step 5 -->
                            <div class="gbs-step-h-item" data-step="5">
                                <div class="gbs-step-h-circle">5</div>
                                <span class="gbs-step-h-label">Days Off</span>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="gbs-wizard-content">
                        <form id="form-add-staff-wizard" action="{{ route('admin.staff.store') }}" method="POST">
                            @csrf

                            <!-- Step 1: General Info -->
                            <div class="gbs-step-pane active" id="step-pane-1">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="gbs-input-group">
                                        <label for="wiz-staff-name" class="gbs-input-label">Full Name <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" id="wiz-staff-name" name="name"
                                            class="gbs-input-modern @error('name') error @enderror"
                                            value="{{ $wizardData['name'] ?? old('name') }}"
                                            placeholder="e.g. John Doe">

                                        <!-- Error Container -->
                                        <div class="gbs-error-feedback" id="error-name"
                                            style="display: {{ $errors->has('name') ? 'flex' : 'none' }}">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                            <span>{{ $errors->first('name') ?? 'Please enter a valid name.' }}</span>
                                        </div>
                                    </div>

                                    <div class="gbs-input-group">
                                        <label for="wiz-staff-email" class="gbs-input-label">Email Address <span
                                                class="text-red-500">*</span></label>
                                        <input type="email" id="wiz-staff-email" name="email"
                                            class="gbs-input-modern @error('email') error @enderror"
                                            value="{{ $wizardData['email'] ?? old('email') }}"
                                            placeholder="john@example.com">

                                        <div class="gbs-error-feedback" id="error-email"
                                            style="display: {{ $errors->has('email') ? 'flex' : 'none' }}">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                            <span>{{ $errors->first('email') ?? 'Please enter a valid email.' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="gbs-input-group">
                                        <label for="wiz-staff-phone" class="gbs-input-label">Phone Number</label>
                                        <input type="tel" id="wiz-staff-phone" name="phone" class="gbs-input-modern"
                                            value="{{ $wizardData['phone'] ?? old('phone') }}"
                                            placeholder="+1 234 567 890">
                                    </div>
                                </div>

                                <div class="gbs-input-group">
                                    <label for="wiz-staff-info" class="gbs-input-label">Additional Info / Bio</label>
                                    <textarea id="wiz-staff-info" name="info" class="gbs-input-modern" rows="2"
                                        placeholder="Short bio or notes about this staff member...">{{ $wizardData['info'] ?? old('info') }}</textarea>
                                </div>
                            </div>

                            <!-- Step 2: Google Calendar -->
                            <div class="gbs-step-pane" id="step-pane-2">


                                <div class="cal-step-grid">
                                    <!-- LEFT: Hours + Timezone -->
                                    <div>
                                        <!-- Working Hours -->
                                        <div style="margin-bottom: 32px;">
                                            <div class="cal-section-label">Daily Working Hours</div>
                                            <div class="hours-stepper">
                                                <button type="button" id="btn-limit-minus" class="hours-stepper-btn">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    </svg>
                                                </button>
                                                <div class="hours-stepper-val">
                                                    <input type="number" id="wiz-staff-limit-hours" name="limit_hours"
                                                        value="{{ $wizardData['limit_hours'] ?? 8 }}" min="1" max="24"
                                                        style="width:48px; text-align:center; font-size:1.3rem; font-weight:700; color:#111827; border:none; background:transparent; outline:none;"
                                                        readonly>
                                                    <span style="font-size:0.85rem; color:#6b7280; font-weight:500;">hrs
                                                        / day</span>
                                                </div>
                                                <button type="button" id="btn-limit-plus" class="hours-stepper-btn">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    </svg>
                                                </button>
                                            </div>
                                            <p style="margin-top:10px; font-size:0.8rem; color:#9ca3af;">Maximum
                                                bookable hours per day for this staff member.</p>
                                        </div>

                                        <!-- Timezone -->
                                        <div>
                                            <div class="cal-section-label">Staff Timezone</div>
                                            <div class="relative">
                                                <input type="hidden" id="wiz-staff-timezone-value" name="timezone"
                                                    value="{{ $wizardData['timezone'] ?? date_default_timezone_get() }}">
                                                <button type="button" id="wiz-staff-tz-btn" class="tz-trigger-btn">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="2" y1="12" x2="22" y2="12"></line>
                                                        <path
                                                            d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                                        </path>
                                                    </svg>
                                                    <span
                                                        id="wiz-staff-tz-label">{{ $wizardData['timezone'] ?? date_default_timezone_get() }}</span>
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </button>
                                                <!-- Dropdown -->
                                                <div id="wiz-staff-tz-dropdown"
                                                    class="absolute left-0 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-50 p-2 flex-col"
                                                    style="display:none; flex-direction:column; max-height:400px; top:calc(100% + 6px);">
                                                    <div class="p-2 flex-shrink-0 bg-white z-10 sticky top-0">
                                                        <input type="text" id="wiz-staff-tz-search"
                                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:border-orange-400 placeholder-gray-400 text-gray-700"
                                                            placeholder="Search timezone...">
                                                    </div>
                                                    <div id="wiz-staff-tz-list"
                                                        class="overflow-y-auto flex-grow px-1 max-h-60">
                                                        @foreach ($groupedTimezones as $region => $items)
                                                            <div class="gbs-tz-group">
                                                                <div
                                                                    class="gbs-tz-header px-3 py-1 text-xs font-bold text-gray-500 uppercase bg-gray-50 sticky top-0">
                                                                    {{ $region }}</div>
                                                                @foreach ($items as $item)
                                                                    <div class="gbs-tz-item px-3 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-primary rounded cursor-pointer duration-150 flex justify-between items-center"
                                                                        data-value="{{ $item['value'] }}"
                                                                        data-region="{{ strtolower($region) }}">
                                                                        <span class="font-medium">{{ $item['name'] }}</span>
                                                                        <span
                                                                            class="text-xs text-gray-400">{{ $item['time'] }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <p style="margin-top:10px; font-size:0.8rem; color:#9ca3af;">All
                                                appointments will be shown in this timezone.</p>
                                        </div>

                                        {{-- Standalone timezone script — runs independently, cannot be blocked by other
                                        JS errors --}}
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                var tzBtn = document.getElementById('wiz-staff-tz-btn');
                                                var tzDropdown = document.getElementById('wiz-staff-tz-dropdown');
                                                var tzLabel = document.getElementById('wiz-staff-tz-label');
                                                var tzInput = document.getElementById('wiz-staff-timezone-value');
                                                var tzSearch = document.getElementById('wiz-staff-tz-search');

                                                if (!tzBtn || !tzDropdown) { console.warn('TZ: btn or dropdown not found'); return; }
                                                console.log('TZ: init OK');

                                                var selectedTzValue = tzInput ? tzInput.value : '';

                                                // Cache names before tick mutations
                                                var tzItems = document.querySelectorAll('.gbs-tz-item');
                                                tzItems.forEach(function (item) {
                                                    var el = item.querySelector('.font-medium');
                                                    if (el) item.setAttribute('data-tz-name', el.textContent.trim());
                                                });

                                                function tzIsOpen() { return tzDropdown.style.display === 'flex'; }
                                                function tzOpen() {
                                                    console.log('TZ: opening');
                                                    tzDropdown.style.display = 'flex';
                                                    if (tzSearch) tzSearch.value = '';
                                                    // Reset all items visible
                                                    document.querySelectorAll('.gbs-tz-group').forEach(function (g) { g.style.display = 'block'; });
                                                    tzItems.forEach(function (i) { i.style.display = 'flex'; });
                                                }
                                                function tzClose() {
                                                    tzDropdown.style.display = 'none';
                                                }

                                                // Button toggle
                                                tzBtn.addEventListener('mousedown', function (e) { e.stopPropagation(); });
                                                tzBtn.addEventListener('click', function (e) {
                                                    e.preventDefault();
                                                    e.stopPropagation();
                                                    e.stopImmediatePropagation();
                                                    if (tzIsOpen()) { tzClose(); } else { tzOpen(); }
                                                });

                                                // Dropdown: stop all propagation
                                                tzDropdown.addEventListener('mousedown', function (e) { e.stopPropagation(); });
                                                tzDropdown.addEventListener('click', function (e) { e.stopPropagation(); e.stopImmediatePropagation(); });

                                                // Close on outside mousedown
                                                document.addEventListener('mousedown', function () {
                                                    if (tzIsOpen()) tzClose();
                                                });

                                                // Search filter
                                                if (tzSearch) {
                                                    tzSearch.addEventListener('input', function (e) {
                                                        var term = e.target.value.toLowerCase();
                                                        document.querySelectorAll('.gbs-tz-group').forEach(function (group) {
                                                            var any = false;
                                                            group.querySelectorAll('.gbs-tz-item').forEach(function (item) {
                                                                var name = (item.getAttribute('data-tz-name') || '').toLowerCase();
                                                                var region = (item.getAttribute('data-region') || '').toLowerCase();
                                                                var show = name.indexOf(term) !== -1 || region.indexOf(term) !== -1;
                                                                item.style.display = show ? 'flex' : 'none';
                                                                if (show) any = true;
                                                            });
                                                            group.style.display = any ? 'block' : 'none';
                                                        });
                                                    });
                                                }

                                                // Tick marks
                                                function updateTicks() {
                                                    tzItems.forEach(function (item) {
                                                        var old = item.querySelector('.tz-tick');
                                                        if (old) old.remove();
                                                        var nameSpan = item.querySelector('.font-medium');
                                                        if (!nameSpan) return;
                                                        if (item.getAttribute('data-value') === selectedTzValue) {
                                                            nameSpan.style.fontWeight = '700';
                                                            nameSpan.style.color = '#FB5200';
                                                            var tick = document.createElement('span');
                                                            tick.className = 'tz-tick';
                                                            tick.style.cssText = 'display:inline-flex;align-items:center;margin-right:6px;flex-shrink:0;';
                                                            tick.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#FB5200" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                                                            nameSpan.parentNode.insertBefore(tick, nameSpan);
                                                        } else {
                                                            nameSpan.style.fontWeight = '';
                                                            nameSpan.style.color = '';
                                                        }
                                                    });
                                                }
                                                updateTicks();

                                                // Item selection
                                                tzItems.forEach(function (item) {
                                                    item.addEventListener('click', function () {
                                                        selectedTzValue = item.getAttribute('data-value');
                                                        if (tzLabel) tzLabel.textContent = selectedTzValue;
                                                        if (tzInput) tzInput.value = selectedTzValue;
                                                        updateTicks();
                                                        tzClose();
                                                    });
                                                });
                                            });
                                        </script>
                                    </div>

                                    <!-- RIGHT: Google Calendar Integration -->
                                    <div>
                                        <div class="cal-section-label">Google Calendar</div>
                                        <div class="gcal-panel">
                                            <div class="gcal-panel-header">
                                                <!-- Google G icon -->
                                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                                        fill="#4285F4" />
                                                    <path
                                                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                                        fill="#34A853" />
                                                    <path
                                                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.24-.19-.6z"
                                                        fill="#FBBC05" />
                                                    <path
                                                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.57 2.77c.87-2.6 3.3-4.46 6.25-4.46z"
                                                        fill="#EA4335" />
                                                </svg>
                                                <div>
                                                    <div class="gcal-panel-header-title">Google Calendar Sync</div>
                                                    <div class="gcal-panel-header-sub">Optional — can be connected later
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="gcal-panel-body">
                                                @if(isset($google_account) && $google_account)
                                                    {{-- Connected State --}}
                                                    <div class="gcal-connected-card">
                                                        <div class="gcal-connected-avatar">
                                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                                stroke="#16a34a" stroke-width="2.5" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <polyline points="20 6 9 17 4 12"></polyline>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <div class="gcal-connected-email">{{ $google_account['email'] }}
                                                            </div>
                                                            <div class="gcal-connected-meta">✓ Connected &amp; syncing</div>
                                                        </div>
                                                        <button type="button" id="btn-disconnect-cal"
                                                            class="gcal-disconnect-btn">Remove</button>
                                                    </div>
                                                    <script>
                                                        document.getElementById('btn-disconnect-cal').addEventListener('click', function () {
                                                            if (confirm('Remove this Google Calendar connection?')) {
                                                                fetch('/api/admin/auth/google/disconnect?context=staff', {
                                                                    method: 'DELETE',
                                                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
                                                                }).then(r => r.json()).then(d => { if (d.success) window.location.reload(); });
                                                            }
                                                        });
                                                    </script>
                                                @else
                                                    {{-- Not Connected State --}}
                                                    <button type="button" class="gcal-connect-btn"
                                                        id="btn-wiz-connect-google-full">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                                                fill="#4285F4" />
                                                            <path
                                                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                                                fill="#34A853" />
                                                            <path
                                                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.24-.19-.6z"
                                                                fill="#FBBC05" />
                                                            <path
                                                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.57 2.77c.87-2.6 3.3-4.46 6.25-4.46z"
                                                                fill="#EA4335" />
                                                        </svg>
                                                        Connect Google Calendar
                                                        <svg class="gcal-connect-arrow" width="16" height="16"
                                                            viewBox="0 0 24 24" fill="none" stroke="#9ca3af"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="9 18 15 12 9 6"></polyline>
                                                        </svg>
                                                    </button>

                                                    <div class="gcal-benefits">
                                                        <div class="gcal-benefit-item"><span
                                                                class="gcal-benefit-dot"></span>Automatically block off busy
                                                            times</div>
                                                        <div class="gcal-benefit-item"><span
                                                                class="gcal-benefit-dot"></span>Sync appointments to their
                                                            calendar</div>
                                                        <div class="gcal-benefit-item"><span
                                                                class="gcal-benefit-dot"></span>Prevent double-bookings
                                                        </div>
                                                    </div>
                                                    <p class="gcal-skip-note">You can also connect this later from the staff
                                                        profile.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Step 3: Services -->
                            <div class="gbs-step-pane" id="step-pane-3">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Assign Services</h3>
                                <p class="text-gray-500 mb-8">Select which services this staff member is qualified to
                                    perform.</p>

                                <div class="gbs-services-grid">
                                    @forelse($services as $service)
                                        <label
                                            class="gbs-service-card @if(isset($wizardData['services']) && in_array($service->id, $wizardData['services'])) selected @endif"
                                            onclick="this.classList.toggle('selected', this.querySelector('input').checked)">
                                            <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                                @if(isset($wizardData['services']) && in_array($service->id, $wizardData['services'])) checked @endif id="service-{{ $service->id }}"
                                                class="gbs-service-checkbox w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                                            <div class="flex flex-col">
                                                <span class="gbs-service-label text-base">{{ $service->name }}</span>
                                                <span class="text-xs text-gray-500">{{ $service->duration_minutes }} mins •
                                                    {{ $service->price }}</span>
                                            </div>
                                        </label>
                                    @empty
                                        <div
                                            class="col-span-2 text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                            <p class="text-gray-500">No services found. Please create services first.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Step 4: Weekly Schedule -->
                            <div class="gbs-step-pane" id="step-pane-4">
                                <input type="hidden" name="schedule_json" id="schedule-json-input" value="">


                                <div id="wk-schedule-editor"></div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var days = [
                                        { l: 'S', label: 'Sunday', on: false, slots: [] },
                                        { l: 'M', label: 'Monday', on: true, slots: [{ s: '09:00', e: '17:00' }] },
                                        { l: 'T', label: 'Tuesday', on: true, slots: [{ s: '09:00', e: '17:00' }] },
                                        { l: 'W', label: 'Wednesday', on: true, slots: [{ s: '09:00', e: '17:00' }] },
                                        { l: 'T', label: 'Thursday', on: true, slots: [{ s: '09:00', e: '17:00' }] },
                                        { l: 'F', label: 'Friday', on: true, slots: [{ s: '09:00', e: '17:00' }] },
                                        { l: 'S', label: 'Saturday', on: true, slots: [{ s: '09:00', e: '17:00' }] }
                                    ];
                                    function fmtLabel(val) {
                                        var parts = val.split(':');
                                        var hh = parseInt(parts[0]), mm = parts[1];
                                        var ap = hh >= 12 ? 'PM' : 'AM';
                                        var h12 = hh % 12 || 12;
                                        return String(h12).padStart(2, '0') + ':' + mm + ' ' + ap;
                                    }

                                    var editor = document.getElementById('wk-schedule-editor');
                                    if (!editor) return;

                                    function closeAll() { document.querySelectorAll('.wk-tp-dd.open').forEach(function (d) { d.classList.remove('open'); }); }
                                    document.addEventListener('mousedown', function (e) { if (!e.target.closest('.wk-tp')) closeAll(); });

                                    function buildTP(val, di, si, f) {
                                        var uid = 'tp' + di + si + f;
                                        var parts = val.split(':'), selH = parseInt(parts[0]), selM = parts[1];
                                        var html = '<div class="wk-tp"><button type="button" class="wk-tp-trigger" data-uid="' + uid + '">' + fmtLabel(val) + '</button>';
                                        html += '<div class="wk-tp-dd" id="' + uid + '" data-di="' + di + '" data-si="' + si + '" data-f="' + f + '">';
                                        html += '<div class="wk-tp-header"><span>HOUR</span><span>MIN</span></div>';
                                        html += '<div class="wk-tp-body">';
                                        // Hour column (0-23)
                                        html += '<div class="wk-tp-col" data-role="hours">';
                                        for (var h = 0; h < 24; h++) {
                                            var hStr = String(h).padStart(2, '0');
                                            html += '<div class="wk-tp-item' + (h === selH ? ' sel' : '') + '" data-h="' + hStr + '">' + hStr + '</div>';
                                        }
                                        html += '</div>';
                                        // Minute column (00,15,30,45)
                                        html += '<div class="wk-tp-col" data-role="mins">';
                                        ['00', '15', '30', '45'].forEach(function (mn) {
                                            html += '<div class="wk-tp-item' + (mn === selM ? ' sel' : '') + '" data-m="' + mn + '">' + mn + '</div>';
                                        });
                                        html += '</div>';
                                        html += '</div>';
                                        html += '</div></div>';
                                        return html;
                                    }

                                    function render() {
                                        editor.innerHTML = '';
                                        days.forEach(function (day, di) {
                                            var row = document.createElement('div'); row.className = 'wk-row';
                                            var badge = '<div class="wk-badge' + (day.on ? '' : ' off') + '" data-action="toggle" data-di="' + di + '" title="' + day.label + '">' + day.l + '</div>';
                                            var center = '';
                                            if (!day.on) { center = '<span class="wk-unavail">Unavailable</span>'; }
                                            else {
                                                if (day.slots.length === 0) day.slots.push({ s: '09:00', e: '17:00' });
                                                day.slots.forEach(function (slot, si) {
                                                    center += '<div class="wk-pill">' + buildTP(slot.s, di, si, 's') + '<span class="wk-pill-dash">–</span>' + buildTP(slot.e, di, si, 'e') + '</div>';
                                                    center += '<button type="button" class="wk-x" data-action="remove" data-di="' + di + '" data-si="' + si + '" title="Remove">×</button>';
                                                });
                                            }
                                            var actions = '<div class="wk-actions"><button type="button" class="wk-act-btn" data-action="add" data-di="' + di + '" title="Add">+</button>';
                                            actions += '<button type="button" class="wk-act-btn" data-action="copy" data-di="' + di + '" title="Copy to all"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></button></div>';
                                            row.innerHTML = badge + '<div class="wk-center">' + center + '</div>' + actions;
                                            editor.appendChild(row);
                                        });
                                        // Open trigger
                                        editor.querySelectorAll('.wk-tp-trigger').forEach(function (btn) {
                                            btn.addEventListener('mousedown', function (e) { e.stopPropagation(); });
                                            btn.addEventListener('click', function (e) {
                                                e.stopPropagation();
                                                var dd = document.getElementById(this.getAttribute('data-uid'));
                                                var wasOpen = dd.classList.contains('open');
                                                closeAll();
                                                if (!wasOpen) {
                                                    dd.classList.add('open');
                                                    // Scroll hour column to selected
                                                    var selH = dd.querySelector('[data-role="hours"] .sel');
                                                    if (selH) selH.scrollIntoView({ block: 'center' });
                                                }
                                            });
                                        });
                                        // Helper to apply time from a dropdown
                                        function applyTime(dd) {
                                            var di = parseInt(dd.getAttribute('data-di')), si = parseInt(dd.getAttribute('data-si')), f = dd.getAttribute('data-f');
                                            var selH = dd.querySelector('[data-role="hours"] .sel');
                                            var selM = dd.querySelector('[data-role="mins"] .sel');
                                            var hv = selH ? selH.getAttribute('data-h') : '09';
                                            var mv = selM ? selM.getAttribute('data-m') : '00';
                                            days[di].slots[si][f] = hv + ':' + mv;
                                            closeAll(); render();
                                        }
                                        // Hour item clicks - highlight & apply
                                        editor.querySelectorAll('.wk-tp-item').forEach(function (item) {
                                            item.addEventListener('mousedown', function (e) { e.stopPropagation(); });
                                            item.addEventListener('click', function (e) {
                                                e.stopPropagation();
                                                var col = this.parentElement;
                                                col.querySelectorAll('.wk-tp-item').forEach(function (it) { it.classList.remove('sel'); });
                                                this.classList.add('sel');
                                                // Apply immediately
                                                var dd = this.closest('.wk-tp-dd');
                                                applyTime(dd);
                                            });
                                        });
                                        // Toggle
                                        editor.querySelectorAll('[data-action="toggle"]').forEach(function (el) { el.addEventListener('click', function () { var di = parseInt(this.getAttribute('data-di')); days[di].on = !days[di].on; if (days[di].on && days[di].slots.length === 0) days[di].slots.push({ s: '09:00', e: '17:00' }); render(); }); });
                                        // Add
                                        editor.querySelectorAll('[data-action="add"]').forEach(function (btn) { btn.addEventListener('click', function () { var di = parseInt(this.getAttribute('data-di')); if (!days[di].on) { days[di].on = true; days[di].slots = [{ s: '09:00', e: '17:00' }]; } else { var l = days[di].slots[days[di].slots.length - 1]; days[di].slots.push({ s: l.e, e: '18:00' }); } render(); }); });
                                        // Remove
                                        editor.querySelectorAll('[data-action="remove"]').forEach(function (btn) { btn.addEventListener('click', function () { var di = parseInt(this.getAttribute('data-di')), si = parseInt(this.getAttribute('data-si')); days[di].slots.splice(si, 1); if (days[di].slots.length === 0) days[di].on = false; render(); }); });
                                        // Copy
                                        editor.querySelectorAll('[data-action="copy"]').forEach(function (btn) { btn.addEventListener('click', function () { var di = parseInt(this.getAttribute('data-di')), src = days[di]; days.forEach(function (d, i) { if (i !== di) { d.on = src.on; d.slots = src.slots.map(function (s) { return { s: s.s, e: s.e }; }); } }); render(); }); });
                                        syncHidden();
                                    }
                                    function syncHidden() { var inp = document.getElementById('schedule-json-input'); if (inp) inp.value = JSON.stringify(days); }
                                    render();
                                });
                            </script>

                            <!-- Step 5: Days Off -->
                            <div class="gbs-step-pane" id="step-pane-5">
                                <input type="hidden" name="days_off_json" id="days-off-json-input" value="[]">


                                <div class="do-container">
                                    <div class="do-header">
                                        <button type="button" class="do-nav" id="do-prev-year">
                                            <i class="fa-solid fa-chevron-left"></i>
                                        </button>
                                        <span class="do-year" id="do-year-label"></span>
                                        <button type="button" class="do-nav" id="do-next-year">
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </button>
                                    </div>
                                    <div id="do-calendar" class="do-grid"></div>
                                    <div class="do-legend">
                                        <div class="do-legend-item">
                                            <span class="do-legend-dot" style="background:#FB5200;"></span>
                                            <span>Day Off</span>
                                        </div>
                                        <div class="do-legend-item">
                                            <span class="do-legend-dot"
                                                style="background:#fff; border:1px solid #e2e8f0;"></span>
                                            <span>Working</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var daysOff = [];
                                    var calYear = new Date().getFullYear();
                                    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    var dayLabels = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
                                    var calEl = document.getElementById('do-calendar');
                                    var yearLabel = document.getElementById('do-year-label');
                                    if (!calEl) return;

                                    var today = new Date();
                                    var todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');

                                    function renderCal() {
                                        yearLabel.textContent = calYear;
                                        calEl.innerHTML = '';
                                        for (var m = 0; m < 12; m++) {
                                            var box = document.createElement('div');
                                            box.className = 'do-month-box';
                                            // Month title
                                            var title = document.createElement('div');
                                            title.className = 'do-month-title';
                                            title.textContent = months[m];
                                            box.appendChild(title);
                                            // Day name headers
                                            var dn = document.createElement('div');
                                            dn.className = 'do-daynames';
                                            for (var d = 0; d < 7; d++) {
                                                var sp = document.createElement('span');
                                                sp.textContent = dayLabels[d];
                                                dn.appendChild(sp);
                                            }
                                            box.appendChild(dn);
                                            // Days grid
                                            var grid = document.createElement('div');
                                            grid.className = 'do-days';
                                            var firstDay = new Date(calYear, m, 1).getDay();
                                            var daysInMonth = new Date(calYear, m + 1, 0).getDate();
                                            // Empty cells
                                            for (var e = 0; e < firstDay; e++) {
                                                var empty = document.createElement('span');
                                                empty.className = 'do-cell empty';
                                                grid.appendChild(empty);
                                            }
                                            // Date cells
                                            for (var day = 1; day <= daysInMonth; day++) {
                                                var cell = document.createElement('span');
                                                cell.className = 'do-cell';
                                                cell.textContent = day;
                                                var dateStr = calYear + '-' + String(m + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
                                                cell.setAttribute('data-date', dateStr);
                                                if (daysOff.indexOf(dateStr) !== -1) cell.classList.add('off');
                                                if (dateStr === todayStr) cell.classList.add('today');
                                                grid.appendChild(cell);
                                            }
                                            box.appendChild(grid);
                                            calEl.appendChild(box);
                                        }
                                        // Bind click
                                        calEl.querySelectorAll('.do-cell[data-date]').forEach(function (c) {
                                            c.addEventListener('click', function () {
                                                var dt = this.getAttribute('data-date');
                                                var idx = daysOff.indexOf(dt);
                                                if (idx === -1) { daysOff.push(dt); this.classList.add('off'); }
                                                else { daysOff.splice(idx, 1); this.classList.remove('off'); }
                                                syncDaysOff();
                                            });
                                        });
                                        syncDaysOff();
                                    }

                                    function syncDaysOff() {
                                        var inp = document.getElementById('days-off-json-input');
                                        if (inp) inp.value = JSON.stringify(daysOff);
                                    }

                                    document.getElementById('do-prev-year').addEventListener('click', function () { calYear--; renderCal(); });
                                    document.getElementById('do-next-year').addEventListener('click', function () { calYear++; renderCal(); });

                                    renderCal();
                                });
                            </script>

                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="gbs-wizard-footer" style="justify-content: space-between;">
                        <button type="button" class="gbs-btn-secondary-clean" id="btn-staff-prev" style="display:none;">
                            Back
                        </button>
                        <div style="flex:1;"></div>
                        <button type="button" class="gbs-btn-primary-clean" id="btn-staff-next">
                            <span>Next Step</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>
                        <button type="button" class="gbs-btn-primary-clean" id="btn-staff-finish" style="display:none;">
                            <span>Create Staff Member</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check for step in URL
            const urlParams = new URLSearchParams(window.location.search);
            let currentStep = parseInt(urlParams.get('step')) || 1;

            const totalSteps = 5;

            const btnNext = document.getElementById('btn-staff-next');
            const btnPrev = document.getElementById('btn-staff-prev');
            const btnFinish = document.getElementById('btn-staff-finish');
            const form = document.getElementById('form-add-staff-wizard');

            // Initialize wizard if step > 1
            if (currentStep > 1) {
                setTimeout(updateWizard, 50);
            }

            // --- Validation Functions ---
            const inputs = {
                name: document.getElementById('wiz-staff-name'),
                email: document.getElementById('wiz-staff-email')
            };

            const errors = {
                name: document.getElementById('error-name'),
                email: document.getElementById('error-email')
            };

            function showInputError(input, errorEl, msg) {
                input.classList.add('error');
                errorEl.querySelector('span').innerText = msg;
                errorEl.style.display = 'flex';
                // Shake animation reset
                input.style.animation = 'none';
                input.offsetHeight; /* trigger reflow */
                input.style.animation = 'shake 0.3s';
            }

            function clearInputError(input, errorEl) {
                input.classList.remove('error');
                errorEl.style.display = 'none';
            }

            // Real-time validation
            Object.keys(inputs).forEach(key => {
                if (inputs[key]) {
                    inputs[key].addEventListener('input', () => {
                        if (inputs[key].value.trim() !== '') {
                            clearInputError(inputs[key], errors[key]);
                        }
                    });
                }
            });

            function validateStep(step) {
                let isValid = true;
                if (step === 1) {
                    // Validate Name
                    if (!inputs.name.value.trim()) {
                        showInputError(inputs.name, errors.name, 'Full Name is required');
                        isValid = false;
                    }

                    // Validate Email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!inputs.email.value.trim()) {
                        showInputError(inputs.email, errors.email, 'Email Address is required');
                        isValid = false;
                    } else if (!emailRegex.test(inputs.email.value.trim())) {
                        showInputError(inputs.email, errors.email, 'Please enter a valid email address');
                        isValid = false;
                    }
                }
                return isValid;
            }

            // --- Wizard Navigation ---
            function updateWizard() {
                // Update header steps
                document.querySelectorAll('.gbs-step-h-item').forEach(step => {
                    const stepNum = parseInt(step.dataset.step);
                    if (stepNum < currentStep) {
                        step.classList.add('completed');
                        step.classList.remove('active');
                    } else if (stepNum === currentStep) {
                        step.classList.add('active');
                        step.classList.remove('completed');
                    } else {
                        step.classList.remove('active', 'completed');
                    }
                });

                // Update Progress Lines
                document.querySelectorAll('.gbs-step-h-line').forEach((line, idx) => {
                    // line index is 0 for between step 1 & 2
                    if (idx < currentStep - 1) {
                        line.classList.add('filled');
                    } else {
                        line.classList.remove('filled');
                    }
                });

                // Update panes
                document.querySelectorAll('.gbs-step-pane').forEach(pane => {
                    pane.classList.remove('active');
                    if (pane.id === `step-pane-${currentStep}`) {
                        pane.classList.add('active');
                    }
                });

                // Update buttons
                // Prev Btn
                btnPrev.style.display = currentStep === 1 ? 'none' : 'block';

                // Next/Finish Btn
                if (currentStep === totalSteps) {
                    btnNext.style.display = 'none';
                    btnFinish.style.display = 'inline-flex';
                } else {
                    btnNext.style.display = 'inline-flex';
                    btnFinish.style.display = 'none';
                }
            }

            if (btnNext) btnNext.addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        updateWizard();
                    }
                }
            });

            if (btnPrev) btnPrev.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateWizard();
                }
            });

            if (btnFinish) btnFinish.addEventListener('click', () => {
                if (validateStep(currentStep)) {
                    btnFinish.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating...';
                    form.submit();
                }
            });

            // --- Counter Logic ---
            const inputHours = document.getElementById('wiz-staff-limit-hours');
            const btnMinus = document.getElementById('btn-limit-minus');
            const btnPlus = document.getElementById('btn-limit-plus');
            if (btnMinus && inputHours) btnMinus.addEventListener('click', () => {
                let v = parseInt(inputHours.value);
                if (v > 1) inputHours.value = v - 1;
            });
            if (btnPlus && inputHours) btnPlus.addEventListener('click', () => {
                let v = parseInt(inputHours.value);
                if (v < 24) inputHours.value = v + 1;
            });

            // Timezone logic is now in its own standalone <script> tag above

            // --- Google Calendar Connection ---
            const btnGoogle = document.getElementById('btn-wiz-connect-google-full');
            if (btnGoogle) {
                btnGoogle.addEventListener('click', async (e) => {
                    e.preventDefault();
                    e.stopPropagation();

                    // Save current form data to session first
                    const formData = new FormData(document.getElementById('form-add-staff-wizard'));
                    try {
                        await fetch('{{ route('admin.staff.save-wizard') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                    } catch (err) {
                        console.error('Failed to save wizard data', err);
                    }

                    const baseUrl = (window.gbsAdminData && window.gbsAdminData.apiUrl)
                        ? window.gbsAdminData.apiUrl
                        : '/api/admin/';

                    window.location.href = baseUrl + 'auth/google/connect?context=staff';
                });
            }

            // Restore from wizardData if we just returned from Google
            @if(request()->query('google_connected'))
                currentStep = 2;
                updateWizardUI();
            @endif

            // Check PHP errors on load
            @if($errors->any())
                // If there are any errors, usually they are on step 1 (name/email).
                // The logic ensures we are on Step 1 by default, so they will be visible.
            @endif
        });
    </script>

</x-admin-layout>