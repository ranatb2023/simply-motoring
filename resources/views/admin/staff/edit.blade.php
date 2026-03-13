<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Staff') }}
        </h2>
    </x-slot>

    <!-- Load Google Font & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @php
        // Robust cleaning of Bio/Notes to remove Google Calendar sync info for display
        $bioDisplay = $staff->info;
        $bioDisplay = preg_replace('/Google Calendar:\s*.*$/m', '', $bioDisplay);
        $bioDisplay = trim($bioDisplay);

        // Check for session-persisted data (e.g. after returning from Google OAuth)
        $wizardData = session('staff_wizard_data', []);
        $googleAccount = session('staff_wizard_google_account', $googleAccount);
    @endphp

    @vite(['resources/css/staff-wizard.css'])
    <div class="bg-white min-h-screen" style="font-family: 'Outfit', sans-serif;">
        <div class="w-full">
            <div>

                <!-- Breadcrumbs -->
                <div
                    style="padding: 14px 32px; border-bottom: 1px solid #f3f4f6; background: #fff; display:flex; align-items:center; justify-content:space-between;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <a href="{{ route('admin.staff.index') }}"
                            style="display:inline-flex; align-items:center; gap:4px; font-size:0.8rem; font-weight:500; color:#6b7280; text-decoration:none;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            Staff Members
                        </a>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                        <span style="font-size:0.85rem; font-weight:700; color:#111827;">Edit Staff:
                            {{ $staff->name }}</span>
                    </div>
                </div>

                <!-- Wizard Steps Header -->
                <div class="gbs-wizard-top-steps" style="justify-content: flex-start; gap: 40px;">
                    <div style="display:flex; align-items:center; width:100%; max-width: none;">
                        <div class="gbs-step-h-item active" data-step="1">
                            <div class="gbs-step-h-circle">1</div><span class="gbs-step-h-label">General Info</span>
                        </div>
                        <div class="gbs-step-h-line"></div>
                        <div class="gbs-step-h-item" data-step="2">
                            <div class="gbs-step-h-circle">2</div><span class="gbs-step-h-label">Calendar</span>
                        </div>
                        <div class="gbs-step-h-line"></div>
                        <div class="gbs-step-h-item" data-step="3">
                            <div class="gbs-step-h-circle">3</div><span class="gbs-step-h-label">Services</span>
                        </div>
                        <div class="gbs-step-h-line"></div>
                        <div class="gbs-step-h-item" data-step="4">
                            <div class="gbs-step-h-circle">4</div><span class="gbs-step-h-label">Schedule</span>
                        </div>
                        <div class="gbs-step-h-line"></div>
                        <div class="gbs-step-h-item" data-step="5">
                            <div class="gbs-step-h-circle">5</div><span class="gbs-step-h-label">Days Off</span>
                        </div>
                    </div>
                </div>

                <div class="gbs-wizard-content">
                    <form id="form-edit-staff-wizard" action="{{ route('admin.staff.update', $staff->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Step 1: General Info -->
                        <div class="gbs-step-pane active" id="step-pane-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="gbs-input-group">
                                    <label class="gbs-input-label">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" class="gbs-input-modern"
                                        value="{{ $wizardData['name'] ?? old('name', $staff->name) }}"
                                        placeholder="e.g. John Doe">
                                </div>
                                <div class="gbs-input-group">
                                    <label class="gbs-input-label">Email Address <span
                                            class="text-red-500">*</span></label>
                                    <input type="email" name="email" class="gbs-input-modern"
                                        value="{{ $wizardData['email'] ?? old('email', $staff->email) }}"
                                        placeholder="john@example.com">
                                </div>
                            </div>
                            <div class="gbs-input-group">
                                <label class="gbs-input-label">Phone Number</label>
                                <input type="tel" name="phone" class="gbs-input-modern"
                                    value="{{ $wizardData['phone'] ?? old('phone', $staff->phone) }}"
                                    placeholder="+1 234 567 890">
                            </div>
                            <div class="gbs-input-group">
                                <label class="gbs-input-label">Additional Info / Bio</label>
                                <textarea name="info" class="gbs-input-modern" rows="2"
                                    placeholder="Notes about this staff member...">{{ $wizardData['info'] ?? old('info', $bioDisplay) }}</textarea>
                            </div>
                        </div>

                        <!-- Step 2: Calendar & Connect -->
                        <div class="gbs-step-pane" id="step-pane-2">
                            <div class="cal-step-grid">
                                <div>
                                    <div class="cal-section-label">Daily Working Hours</div>
                                    <div class="hours-stepper mb-8">
                                        <button type="button" id="btn-limit-minus" class="hours-stepper-btn"><i
                                                class="fa-solid fa-minus"></i></button>
                                        <div class="hours-stepper-val">
                                            <input type="number" id="wiz-staff-limit-hours" name="limit_hours"
                                                value="{{ $wizardData['limit_hours'] ?? old('limit_hours', $staff->limit_hours ?? 8) }}"
                                                min="1" max="24"
                                                style="width:48px; border:none; text-align:center; font-weight:700; background:transparent; outline:none;"
                                                readonly>
                                            <span style="font-size:0.85rem; color:#6b7280;">hrs</span>
                                        </div>
                                        <button type="button" id="btn-limit-plus" class="hours-stepper-btn"><i
                                                class="fa-solid fa-plus"></i></button>
                                    </div>

                                    <div class="cal-section-label">Staff Timezone</div>
                                    <div class="relative">
                                        <input type="hidden" id="wiz-staff-timezone-value" name="timezone"
                                            value="{{ $wizardData['timezone'] ?? old('timezone', $staff->timezone ?? date_default_timezone_get()) }}">
                                        <button type="button" id="wiz-staff-tz-btn" class="tz-trigger-btn">
                                            <i class="fa-solid fa-earth-americas"></i>
                                            <span
                                                id="wiz-staff-tz-label">{{ $wizardData['timezone'] ?? old('timezone', $staff->timezone ?? date_default_timezone_get()) }}</span>
                                            <i class="fa-solid fa-chevron-down text-xs ml-2"></i>
                                        </button>
                                        <!-- Timezone dropdown is same as create -->
                                        <div id="wiz-staff-tz-dropdown"
                                            class="absolute left-0 w-80 bg-white border border-gray-200 rounded-lg shadow-xl z-50 p-2 flex-col"
                                            style="display:none; max-height:400px; top:105%;">
                                            <div class="p-2 sticky top-0 bg-white"><input type="text"
                                                    id="wiz-staff-tz-search"
                                                    class="w-full border rounded px-3 py-2 text-sm"
                                                    placeholder="Search timezone..."></div>
                                            <div id="wiz-staff-tz-list" class="overflow-y-auto px-1 max-h-60">
                                                @foreach ($groupedTimezones as $region => $items)
                                                    <div class="gbs-tz-group">
                                                        <div
                                                            class="px-3 py-1 text-xs font-bold text-gray-500 uppercase bg-gray-50">
                                                            {{ $region }}
                                                        </div>
                                                        @foreach ($items as $item)
                                                            <div class="gbs-tz-item px-3 py-2 text-sm hover:bg-orange-50 cursor-pointer"
                                                                data-value="{{ $item['value'] }}">
                                                                <span>{{ $item['name'] }}</span> <span
                                                                    class="float-right text-gray-400 text-xs">{{ $item['time'] }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="cal-section-label">Google Calendar</div>
                                    <div class="gcal-panel">
                                        <div class="gcal-panel-header">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Google_Calendar_icon_%282020%29.svg"
                                                width="20" alt="">
                                            <div>
                                                <div style="font-weight:700; font-size:0.9rem;">Sync Calendar</div>
                                                <div style="font-size:0.75rem; color:#6b7280;">Keep schedules in sync
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gcal-panel-body">
                                            @if($googleAccount)
                                                <div class="gcal-connected-card">
                                                    <div
                                                        class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                                        <i class="fa-solid fa-check"></i>
                                                    </div>
                                                    <div>
                                                        <div style="font-weight:600; font-size:0.85rem;">
                                                            {{ $googleAccount['email'] }}
                                                        </div>
                                                        <div style="font-size:0.75rem; color:#16a34a;">Connected</div>
                                                    </div>
                                                    <button type="button" id="btn-disconnect-cal"
                                                        class="gcal-disconnect-btn">Remove</button>
                                                </div>
                                            @else
                                                <button type="button" class="gcal-connect-btn"
                                                    id="btn-wiz-connect-google-full">
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_Color_Icon.svg"
                                                        width="18" alt="">
                                                    Connect Google Calendar
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Google Calendar script logic moved to bottom -->
                        </div>

                        <!-- Step 3: Services -->
                        <div class="gbs-step-pane" id="step-pane-3">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Assign Services</h3>
                            <div class="gbs-services-grid">
                                @forelse($services as $service)
                                    <label
                                        class="gbs-service-card {{ in_array($service->id, $selectedServices ?? []) ? 'selected' : '' }}"
                                        onclick="this.classList.toggle('selected', this.querySelector('input').checked)">
                                        <input type="checkbox" name="services[]" value="{{ $service->id }}" {{ in_array($service->id, $selectedServices ?? []) ? 'checked' : '' }}
                                            class="gbs-service-checkbox">
                                        <div class="flex flex-col">
                                            <span class="gbs-service-label">{{ $service->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $service->duration_minutes }} mins •
                                                {{ $service->price }}</span>
                                        </div>
                                    </label>
                                @empty
                                    <div class="col-span-2 py-10 text-center bg-gray-50 border border-dashed rounded-xl">No
                                        services found.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Step 4: Weekly Schedule -->
                        <div class="gbs-step-pane" id="step-pane-4">
                            <input type="hidden" name="schedule_json" id="schedule-json-input"
                                value="{{ $scheduleJson }}">
                            <div id="wk-schedule-editor"></div>

                            <!-- Weekly Schedule Logic moved to bottom -->
                        </div>

                        <!-- Step 5: Days Off -->
                        <div class="gbs-step-pane" id="step-pane-5">
                            <input type="hidden" name="days_off_json" id="days-off-json-input"
                                value="{{ $daysOffJson }}">
                            <div class="mb-6 flex justify-center items-center gap-10">
                                <button type="button" class="do-nav" id="do-prev-year"><i
                                        class="fa-solid fa-chevron-left"></i></button>
                                <span class="text-xl font-bold" id="do-year-label"></span>
                                <button type="button" class="do-nav" id="do-next-year"><i
                                        class="fa-solid fa-chevron-right"></i></button>
                            </div>
                            <div id="do-calendar" class="do-grid"></div>

                            <!-- Days Off Calendar Logic moved to bottom -->
                        </div>
                    </form>
                </div>

                <!-- Footer Navigation -->
                <div class="gbs-wizard-footer" style="justify-content: flex-start; gap: 20px;">
                    <button type="button" class="gbs-btn-secondary-clean" id="btn-staff-prev"
                        style="display:none;">Back</button>
                    <button type="button" class="gbs-btn-primary-clean" id="btn-staff-next">
                        <span>Next Step</span> <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <button type="button" class="gbs-btn-primary-clean" id="btn-staff-finish" style="display:none;">
                        <span>Update Staff Member</span> <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            let currentStep = parseInt(urlParams.get('step')) || 1;
            const totalSteps = 5;
            const btnNext = document.getElementById('btn-staff-next'), btnPrev = document.getElementById('btn-staff-prev'), btnFinish = document.getElementById('btn-staff-finish');
            const form = document.getElementById('form-edit-staff-wizard');

            function updateWizard() {
                document.querySelectorAll('.gbs-step-h-item').forEach(item => {
                    var s = parseInt(item.dataset.step);
                    item.classList.toggle('active', s === currentStep);
                    item.classList.toggle('completed', s < currentStep);
                });
                document.querySelectorAll('.gbs-step-h-line').forEach((line, i) => line.classList.toggle('filled', i < currentStep - 1));
                document.querySelectorAll('.gbs-step-pane').forEach((p, i) => p.classList.toggle('active', i + 1 === currentStep));
                btnPrev.style.display = currentStep === 1 ? 'none' : 'block';
                if (currentStep === totalSteps) { btnNext.style.display = 'none'; btnFinish.style.display = 'inline-flex'; }
                else { btnNext.style.display = 'inline-flex'; btnFinish.style.display = 'none'; }
            }

            btnNext.onclick = () => { if (currentStep < totalSteps) { currentStep++; updateWizard(); } };
            btnPrev.onclick = () => { if (currentStep > 1) { currentStep--; updateWizard(); } };
            btnFinish.onclick = () => { btnFinish.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Updating...'; form.submit(); };

            // Handle counter
            var hPlus = document.getElementById('btn-limit-plus'), hMinus = document.getElementById('btn-limit-minus'), hInp = document.getElementById('wiz-staff-limit-hours');
            if (hPlus) hPlus.onclick = () => { if (hInp.value < 24) hInp.value++; };
            if (hMinus) hMinus.onclick = () => { if (hInp.value > 1) hInp.value--; };

            // Timezone Dropdown Logic
            const tzBtn = document.getElementById('wiz-staff-tz-btn');
            const tzDrop = document.getElementById('wiz-staff-tz-dropdown');
            const tzSearch = document.getElementById('wiz-staff-tz-search');
            const tzInput = document.getElementById('wiz-staff-timezone-value');
            const tzLabel = document.getElementById('wiz-staff-tz-label');

            function toggleTzDropdown(e) {
                if (e) { e.preventDefault(); e.stopPropagation(); }
                if (tzDrop) {
                    const isOpen = tzDrop.classList.toggle('is-open');
                    if (isOpen && tzSearch) setTimeout(() => tzSearch.focus(), 10);
                }
            }

            if (tzBtn) tzBtn.addEventListener('click', toggleTzDropdown);

            document.addEventListener('click', () => {
                if (tzDrop) tzDrop.classList.remove('is-open');
            });

            if (tzDrop) {
                tzDrop.addEventListener('click', (e) => e.stopPropagation());
                tzDrop.querySelectorAll('.gbs-tz-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const val = item.dataset.value;
                        if (tzInput) tzInput.value = val;
                        if (tzLabel) tzLabel.textContent = val;
                        tzDrop.classList.remove('is-open');
                    });
                });
                if (tzSearch) {
                    tzSearch.addEventListener('input', (e) => {
                        const q = e.target.value.toLowerCase();
                        tzDrop.querySelectorAll('.gbs-tz-item').forEach(el => {
                            el.style.display = el.textContent.toLowerCase().includes(q) ? 'block' : 'none';
                        });
                    });
                }
            }

            // --- Step 4: Weekly Schedule ---
            const wkInp = document.getElementById('schedule-json-input'), wkEditor = document.getElementById('wk-schedule-editor');
            if (wkInp && wkEditor) {
                const defaultDays = [
                    { l: 'S', on: false, slots: [] }, { l: 'M', on: true, slots: [{ s: '09:00', e: '17:00' }] }, { l: 'T', on: true, slots: [{ s: '09:00', e: '17:00' }] },
                    { l: 'W', on: true, slots: [{ s: '09:00', e: '17:00' }] }, { l: 'T', on: true, slots: [{ s: '09:00', e: '17:00' }] }, { l: 'F', on: true, slots: [{ s: '09:00', e: '17:00' }] }, { l: 'S', on: true, slots: [{ s: '09:00', e: '17:00' }] }
                ];
                const dayLabels = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
                let wkDays = JSON.parse(wkInp.value || '[]');
                if (wkDays.length === 0) wkDays = defaultDays;
                else wkDays.forEach((d, i) => { if (!d.l) d.l = dayLabels[i % 7]; });

                const fmt = (v) => { var p = v.split(':'), h = parseInt(p[0]), ap = h >= 12 ? 'PM' : 'AM'; return (h % 12 || 12).toString().padStart(2, '0') + ':' + p[1] + ' ' + ap; };
                const buildTP = (v, di, si, f) => {
                    var uid = 'tp' + di + si + f, p = v.split(':'), sh = p[0], sm = p[1], html = '<div class="wk-tp"><button type="button" class="wk-tp-trigger" data-uid="' + uid + '">' + fmt(v) + '</button><div class="wk-tp-dd" id="' + uid + '" data-di="' + di + '" data-si="' + si + '" data-f="' + f + '"><div class="wk-tp-header"><span>HR</span><span>MIN</span></div><div class="flex h-40"><div class="flex-1 overflow-y-auto" data-role="h">';
                    for (var h = 0; h < 24; h++) { var hS = h.toString().padStart(2, '0'); html += '<div class="wk-tp-item p-1 text-center cursor-pointer ' + (hS === sh ? 'sel' : '') + '" data-h="' + hS + '">' + hS + '</div>'; }
                    html += '</div><div class="flex-1 overflow-y-auto" data-role="m">';
                    ['00', '15', '30', '45'].forEach(m => { html += '<div class="wk-tp-item p-1 text-center cursor-pointer ' + (m === sm ? 'sel' : '') + '" data-m="' + m + '">' + m + '</div>'; });
                    html += '</div></div></div></div>'; return html;
                };

                const renderWk = () => {
                    wkEditor.innerHTML = ''; wkDays.forEach((d, di) => {
                        var row = document.createElement('div'); row.className = 'wk-row';
                        var center = ''; if (!d.on) center = '<span class="wk-unavail">Unavailable</span>'; else d.slots.forEach((s, si) => center += '<div class="wk-pill">' + buildTP(s.s, di, si, 's') + ' – ' + buildTP(s.e, di, si, 'e') + '<button type="button" class="wk-x ml-2" data-action="remove" data-di="' + di + '" data-si="' + si + '">×</button></div>');
                        row.innerHTML = '<div class="wk-badge' + (d.on ? '' : ' off') + '" data-action="toggle" data-di="' + di + '">' + d.l + '</div><div class="wk-center">' + center + '</div><div class="wk-actions"><button type="button" class="wk-act-btn" data-action="add" data-di="' + di + '"><i class="fa-solid fa-plus"></i></button><button type="button" class="wk-act-btn" data-action="copy" data-di="' + di + '"><i class="fa-solid fa-copy"></i></button></div>';
                        wkEditor.appendChild(row);
                    });
                    wkEditor.querySelectorAll('.wk-tp-trigger').forEach(b => b.onclick = (e) => { e.stopPropagation(); var dd = document.getElementById(b.dataset.uid); if (dd.classList.contains('open')) { dd.classList.remove('open') } else { document.querySelectorAll('.wk-tp-dd.open').forEach(x => x.classList.remove('open')); dd.classList.add('open') } });
                    wkEditor.querySelectorAll('.wk-tp-item').forEach(i => i.onclick = () => { var dd = i.closest('.wk-tp-dd'), di = dd.dataset.di, si = dd.dataset.si, f = dd.dataset.f, h = dd.querySelector('[data-role="h"] .sel')?.dataset.h || '09', m = dd.querySelector('[data-role="m"] .sel')?.dataset.m || '00'; if (i.dataset.h) h = i.dataset.h; if (i.dataset.m) m = i.dataset.m; wkDays[di].slots[si][f] = h + ':' + m; renderWk(); });
                    wkEditor.querySelectorAll('[data-action="toggle"]').forEach(b => b.onclick = () => { var di = b.dataset.di; wkDays[di].on = !wkDays[di].on; if (wkDays[di].on && !wkDays[di].slots.length) wkDays[di].slots = [{ s: '09:00', e: '17:00' }]; renderWk(); });
                    wkEditor.querySelectorAll('[data-action="add"]').forEach(b => b.onclick = () => { var di = b.dataset.di; wkDays[di].on = true; wkDays[di].slots.push({ s: '09:00', e: '17:00' }); renderWk(); });
                    wkEditor.querySelectorAll('[data-action="remove"]').forEach(b => b.onclick = () => { var di = b.dataset.di, si = b.dataset.si; wkDays[di].slots.splice(si, 1); if (!wkDays[di].slots.length) wkDays[di].on = false; renderWk(); });
                    wkEditor.querySelectorAll('[data-action="copy"]').forEach(b => b.onclick = () => { var di = b.dataset.di; wkDays.forEach((d, i) => { if (i != di) { d.on = wkDays[di].on; d.slots = wkDays[di].slots.map(s => ({ s: s.s, e: s.e })); } }); renderWk(); });
                    wkInp.value = JSON.stringify(wkDays);
                };
                document.addEventListener('click', () => document.querySelectorAll('.wk-tp-dd.open').forEach(x => x.classList.remove('open')));
                renderWk();
            }

            // --- Step 5: Days Off ---
            const doInp = document.getElementById('days-off-json-input'), doCalEl = document.getElementById('do-calendar'), doYearLbl = document.getElementById('do-year-label');
            if (doInp && doCalEl) {
                let dOff = JSON.parse(doInp.value || '[]'), calYear = new Date().getFullYear();
                const mons = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const renderDo = () => {
                    if (doYearLbl) doYearLbl.textContent = calYear;
                    doCalEl.innerHTML = '';
                    for (var m = 0; m < 12; m++) {
                        var box = document.createElement('div'); box.className = 'do-month-box';
                        box.innerHTML = '<div class="font-bold mb-3 border-l-4 border-orange-500 pl-2">' + mons[m] + '</div><div class="grid grid-cols-7 text-center text-[10px] text-gray-400 font-bold mb-2"><span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span></div>';
                        var grid = document.createElement('div'); grid.className = 'grid grid-cols-7 gap-1';
                        var start = new Date(calYear, m, 1).getDay(), end = new Date(calYear, m + 1, 0).getDate();
                        for (var e = 0; e < start; e++) grid.innerHTML += '<span class="w-7 h-7"></span>';
                        for (var d = 1; d <= end; d++) {
                            var ds = calYear + '-' + (m + 1).toString().padStart(2, '0') + '-' + d.toString().padStart(2, '0');
                            var cell = document.createElement('span'); cell.className = 'do-cell' + (dOff.includes(ds) ? ' off' : '');
                            cell.textContent = d; (function (s) { cell.onclick = () => { if (dOff.includes(s)) dOff = dOff.filter(x => x != s); else dOff.push(s); renderDo(); }; })(ds);
                            grid.appendChild(cell);
                        }
                        box.appendChild(grid); doCalEl.appendChild(box);
                    }
                    doInp.value = JSON.stringify(dOff);
                };
                const pY = document.getElementById('do-prev-year'), nY = document.getElementById('do-next-year');
                if (pY) pY.onclick = () => { calYear--; renderDo() };
                if (nY) nY.onclick = () => { calYear++; renderDo() };
                renderDo();
            }

            // Google Calendar Logic
            const gBtn = document.getElementById('btn-wiz-connect-google-full');
            if (gBtn) gBtn.onclick = async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                await fetch('{{ route('admin.staff.save-wizard') }}', { method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                window.location.href = '/api/admin/auth/google/connect?context=staff';
            };

            const dBtn = document.getElementById('btn-disconnect-cal');
            if (dBtn) dBtn.onclick = async () => {
                if (confirm('Remove connection?')) {
                    const r = await fetch('/api/admin/auth/google/disconnect?context=staff', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                    const res = await r.json();
                    if (res.success) window.location.reload();
                }
            };

            // Fix for return from Google
            @if(request()->query('google_connected'))
                currentStep = 2;
            @endif
            updateWizard();
        });
    </script>
</x-admin-layout>