document.addEventListener('DOMContentLoaded', function () {
    if (typeof gbsAdminData === 'undefined') return;

    // --- State ---
    let services = [];

    let staff = [];
    let availability = [];
    let holidays = [];
    let editingItem = null; // For modals

    // --- Wizard State ---
    let currentWizStep = 1;
    let wizScheduleState = [];
    let wizDaysOff = [];
    let currentOffYear = new Date().getFullYear();
    let wizEditingId = null;
    window.googleAuthTempId = null; // Store temp auth ID for saving

    const btnAddStaff = document.getElementById('btn-add-staff-modal');
    if (btnAddStaff) {
        btnAddStaff.addEventListener('click', () => {
            wizEditingId = null;
            // Reset Title & Button
            const t = document.getElementById('gbs-wizard-title');
            if (t) t.innerText = 'Add New Staff Member';
            const b = document.getElementById('btn-wiz-finish');
            if (b) b.innerText = 'Finish'; // Or 'Add Staff' if preferred, but original was Finish

            // Reset Inputs explicitly to be safe
            const form = document.getElementById('form-add-staff-wizard');
            if (form) form.reset();

            // Open using standard function (handles steps/data)
            openWizard();
        });
    }

    function openEditStaffModal(id) {
        const s = staff.find(x => x.id == id);
        if (!s) return;

        wizEditingId = id;
        window.googleAuthTempId = null; // Reset auth logic

        // Populate Wizard Info
        document.getElementById('wiz-staff-name').value = s.name;
        const wpUserEl = document.getElementById('wiz-staff-wp-user');
        if (wpUserEl) wpUserEl.value = s.wp_user_id || 'none';
        document.getElementById('wiz-staff-email').value = s.email;
        const pInput = document.getElementById('wiz-staff-phone');
        if (pInput) pInput.value = s.phone || '';
        const iInput = document.getElementById('wiz-staff-info');
        if (iInput) iInput.value = s.info || '';
        const lInput = document.getElementById('wiz-staff-limit-hours');
        if (lInput) lInput.value = s.limit_hours || 0;
        const uInput = document.getElementById('wiz-staff-wp-user');
        if (uInput) uInput.value = s.wp_user_id || 'none';

        // Populate Timezone
        const tInput = document.getElementById('wiz-staff-timezone-value');
        if (tInput) {
            const tz = s.timezone || 'UTC';
            tInput.value = tz;
            const tLabel = document.getElementById('wiz-staff-tz-label');
            if (tLabel) tLabel.innerText = tz.replace('_', ' ');
        }

        // Google Auth Status
        const btnGoogle = document.getElementById('btn-wiz-connect-google');
        if (btnGoogle) {
            btnGoogle.classList.remove('connected');
            // Reset to default HTML
            btnGoogle.innerHTML = `<div class="gbs-connect-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div><div class="gbs-connect-info"><div class="gbs-connect-title">Google Calendar</div><div class="gbs-connect-sub">Gmail, G Suite</div></div><div class="gbs-connect-action"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></div>`;

            if (s.google_connected) {
                btnGoogle.classList.add('connected');
                btnGoogle.innerHTML = `<div class="gbs-connect-icon"><svg width="24" height="24" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.04-3.71 1.04-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg></div><div class="gbs-connect-info"><div class="gbs-connect-title">Google Calendar</div><div class="gbs-connect-sub">${s.google_email || 'Google Calendar'}</div><div class="gbs-connect-status" style="color:#2563eb; font-size:0.8rem;">Checking calendars...</div></div><div class="gbs-disconnect-btn" data-action="disconnect-google" style="padding:8px; color:#9ca3af; font-size:1.5rem; line-height:0.5;">&times;</div>`;
            }
        }

        // Open Modal
        const modal = document.getElementById('gbs-modal-add-staff');
        document.getElementById('gbs-wizard-title').innerText = 'Edit Staff Member';
        document.getElementById('btn-wiz-finish').innerText = 'Update Staff';
        modal.style.display = 'flex'; // Changed from 'block' to 'flex' to match openWizard

        // Initial Wizard Load (Resets logic but renders DOM)
        // We override this reset behavior for editing
        currentWizStep = 1;
        showWizStep(1); // Renamed from updateWizardUI
        loadWizardData();

        // 1. Services: Set Checked
        setTimeout(() => {
            if (s.service_ids) {
                s.service_ids.forEach(sid => {
                    const cb = document.querySelector(`.wiz-svc-check[value="${sid}"]`);
                    if (cb) {
                        cb.checked = true;
                        cb.dispatchEvent(new Event('change'));
                    }
                });
            }
        }, 50);

        // 2. Schedule: Override wizScheduleState
        if (s.schedule_id && schedules) {
            const sch = schedules.find(x => x.id == s.schedule_id);
            if (sch && sch.data) {
                // Reconstruct wizScheduleState from availability data
                // s.data is array of objects { day_of_week... }
                // wizScheduleState format: [{ l:'M', dayIndex:1, slots:[{start,end}] }]

                // Reset to empty structure
                const newSchedState = wizScheduleState.map(cw => ({ ...cw, slots: [] }));

                sch.data.forEach(row => {
                    const dIdx = parseInt(row.day_of_week);
                    if (row.is_closed != '1' && row.is_closed !== true) {
                        newSchedState[dIdx].slots.push({
                            start: row.start_time.substring(0, 5),
                            end: row.end_time.substring(0, 5)
                        });
                    }
                });
                wizScheduleState = newSchedState;
                renderWizSchedule();
            }
        }

        // 3. Days Off: Fetch holidays for this staff?
        // holidays global contains all? Check schema.
        // If holidays array has staff_id, filter it.
        // Assuming 'holidays' global has all holidays.
        if (holidays) {
            wizDaysOff = holidays.filter(h => h.staff_id == s.id).map(h => h.date);
            renderDaysOffCalendar();
        }
    }

    // Check URL Hash for sub-tabs (e.g., #cal-settings)
    if (window.location.hash) {
        // Handle cases like #cal-settings&param=val
        const hash = window.location.hash.slice(1).split('&')[0];
        // Find button with data-sub="{hash}"
        const targetBtn = document.querySelector(`.gbs-sub-tab[data-sub="${hash}"]`);
        if (targetBtn) {
            // Delay slightly to ensure UI is ready (listeners attached)
            setTimeout(() => targetBtn.click(), 50);
        }
    }

    // NEW: Schedule Management State
    let schedules = [];
    let activeScheduleId = 'default';

    // Helper: Show Toast (Global Access)
    const gbsShowToast = (msg) => {
        let toast = document.getElementById('gbs-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'gbs-toast';
            toast.style.cssText = 'position:fixed; bottom:20px; right:20px; background:#1f2937; color:white; padding:10px 20px; border-radius:6px; z-index:9999; box-shadow:0 4px 6px rgba(0,0,0,0.1); opacity:0; transition:opacity 0.3s;';
            document.body.appendChild(toast);
        }
        toast.innerText = msg;
        toast.style.opacity = '1';
        setTimeout(() => {
            toast.style.opacity = '0';
        }, 3000);
    };

    // --- References ---
    const headers = {
        'X-CSRF-TOKEN': gbsAdminData.nonce,
        'Content-Type': 'application/json'
    };
    const root = gbsAdminData.apiUrl;

    // --- Tabs ---
    const tabs = document.querySelectorAll('.nav-tab');
    const contents = document.querySelectorAll('.gbs-tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            // Toggle Active Class
            tabs.forEach(t => t.classList.remove('nav-tab-active'));
            this.classList.add('nav-tab-active');

            // Toggle Display
            const target = this.dataset.tab;
            contents.forEach(c => c.style.display = 'none');
            document.getElementById('tab-' + target).style.display = 'block';
        });
    });

    // --- Sub-Tabs (Availability) ---
    const subTabs = document.querySelectorAll('.gbs-sub-tab');
    const subContents = document.querySelectorAll('.gbs-sub-content');

    subTabs.forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();

            // Toggle Active Class
            subTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Toggle Display
            const target = this.dataset.sub;
            subContents.forEach(c => c.style.display = 'none');
            document.getElementById('sub-' + target).style.display = 'block';
        });
    });

    // --- Dropdown Menu (Schedule Actions) ---
    const btnSchedActions = document.getElementById('btn-sched-actions');
    const menuSchedActions = document.getElementById('menu-sched-actions');

    if (btnSchedActions && menuSchedActions) {
        // Toggle on click
        btnSchedActions.addEventListener('click', function (e) {
            e.stopPropagation();
            const isVisible = menuSchedActions.style.display === 'block';
            menuSchedActions.style.display = isVisible ? 'none' : 'block';
        });

        // Close on click outside
        document.addEventListener('click', function (e) {
            if (!btnSchedActions.contains(e.target) && !menuSchedActions.contains(e.target)) {
                menuSchedActions.style.display = 'none';
            }
        });

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                menuSchedActions.style.display = 'none';
            }
        });
    }

    // --- View Switcher (List vs Calendar) ---
    const viewBtns = document.querySelectorAll('.gbs-view-btn');
    const viewSections = document.querySelectorAll('.gbs-view-section');

    viewBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            // Toggle Buttons
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Toggle Views
            const target = this.dataset.view; // 'list' or 'calendar'
            document.getElementById('gbs-view-list').style.display = target === 'list' ? 'block' : 'none';
            document.getElementById('gbs-view-calendar').style.display = target === 'calendar' ? 'block' : 'none';

            // Re-render to ensure layout/offsets are correct if needed
            if (target === 'list') {
                checkAndRestoreTimezone();
                renderAvailability();
            }

            if (target === 'calendar') {
                renderCalendar();
            }
        });
    });

    // --- Data Fetching Logic ---
    function fetchAll() {
        // Fetch all necessary data: Settings (Schedules, Availability), Services, Staff, Holidays
        return Promise.all([
            fetch(root + 'settings', { headers }).then(r => r.json()),
            fetch(root + 'services', { headers }).then(r => r.json()),
            fetch(root + 'staff', { headers }).then(r => r.json()),
            fetch(root + 'holidays', { headers }).then(r => r.json())
        ])
            .then(([settingsData, servicesData, staffData, holidaysData]) => {
                console.log('Fetched Data:', { settingsData, servicesData, staffData });

                // 1. Process Schedules & Availability
                if (settingsData.schedules) {
                    schedules = settingsData.schedules.map(sch => {
                        const schedAvail = (settingsData.availability || []).filter(a => a.schedule_id == sch.id);
                        return {
                            ...sch,
                            data: schedAvail
                        };
                    });
                } else {
                    schedules = [];
                }

                // Ensure active schedule is valid
                if (schedules.length > 0) {
                    const current = schedules.find(s => s.id == activeScheduleId);
                    if (!current) {
                        // Check for default
                        const def = schedules.find(s => s.is_default == '1');
                        activeScheduleId = def ? def.id : schedules[0].id;
                    }
                }

                // 2. Services
                services = servicesData || [];

                // 3. Staff
                staff = staffData || [];

                // 4. Holidays
                holidays = holidaysData || [];

                // UI Refresh
                renderScheduleSelector();
                updateScheduleHeader();
                renderAvailability();
                renderHolidays();
                renderStaff();

                // Render Active Services List if dropdown logic exists
                if (typeof renderActiveServicesList === 'function') {
                    // But wait, renderActiveServicesList is part of updateScheduleHeader logic usually?
                    // Actually, updateScheduleHeader calls renderActiveServicesList if it exists?
                    // Let's check updateScheduleHeader below. 
                    // If not, we call it manually just in case the dropdown is open (unlikely on load).
                    // Better to depend on updateScheduleHeader.
                }

            })
            .catch(err => {
                console.error(err);
                showToast('API Error');
            });
    }

    // --- Weekly Edit Modal (Recurring) ---
    let weeklyModalState = {
        dayOfWeek: null,
        modal: null,
        intervals: []
    };

    function renderWeeklySlots() {
        const container = document.getElementById('weekly-slots-container');
        if (!container) return;
        container.innerHTML = '';

        if (!weeklyModalState.intervals) weeklyModalState.intervals = [];

        weeklyModalState.intervals.forEach((slot, idx) => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 mb-3';
            div.innerHTML = `
                <div class="gbs-time-input-group" style="background-color: #f3f4f6; border: none;">
                    <input type="time" class="gbs-time-input wk-start" value="${slot.start}" data-idx="${idx}">
                    <span style="color:#9ca3af; font-weight:300;">-</span>
                    <input type="time" class="gbs-time-input wk-end" value="${slot.end}" data-idx="${idx}">
                </div>
                
                <button type="button" class="gbs-icon-btn btn-wk-remove" data-idx="${idx}" title="Remove">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>

                 <button type="button" class="gbs-icon-btn btn-wk-add" title="Add Period">
                     <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </button>

                <button type="button" class="gbs-icon-btn btn-wk-dup" data-idx="${idx}" title="Duplicate">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                </button>
            `;
            container.appendChild(div);
        });

        // Listeners
        container.querySelectorAll('.btn-wk-remove').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idx = parseInt(e.currentTarget.dataset.idx);
                weeklyModalState.intervals.splice(idx, 1);
                renderWeeklySlots();
            });
        });

        container.querySelectorAll('.btn-wk-add').forEach(btn => {
            btn.addEventListener('click', () => {
                weeklyModalState.intervals.push({ start: '12:00', end: '13:00' });
                renderWeeklySlots();
            });
        });

        container.querySelectorAll('.btn-wk-dup').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idx = parseInt(e.currentTarget.dataset.idx);
                const current = weeklyModalState.intervals[idx];
                weeklyModalState.intervals.splice(idx + 1, 0, { ...current });
                renderWeeklySlots();
            });
        });

        container.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', (e) => {
                const idx = parseInt(e.target.dataset.idx);
                const field = e.target.classList.contains('wk-start') ? 'start' : 'end';
                if (weeklyModalState.intervals[idx]) weeklyModalState.intervals[idx][field] = e.target.value;
            });
        });
    }

    function initWeeklyEditModal() {
        const modal = document.getElementById('modal-weekly-edit-overlay');
        if (!modal) return;
        weeklyModalState.modal = modal;

        // Bindings
        const closeBtn = document.getElementById('btn-close-weekly');
        const cancelBtn = document.getElementById('btn-cancel-weekly');
        const applyBtn = document.getElementById('btn-apply-weekly');
        const checkUnavail = document.getElementById('check-weekly-unavailable');

        const closeFunc = () => modal.style.display = 'none';

        if (closeBtn) closeBtn.onclick = closeFunc;
        if (cancelBtn) cancelBtn.onclick = closeFunc;

        if (checkUnavail) {
            checkUnavail.onchange = () => {
                const container = document.getElementById('weekly-slots-container');
                if (checkUnavail.checked) {
                    if (container) container.style.display = 'none';
                } else {
                    if (container) container.style.display = 'block';
                    if (weeklyModalState.intervals.length === 0) {
                        weeklyModalState.intervals.push({ start: '09:00', end: '17:00' });
                        renderWeeklySlots();
                    }
                }
            };
        }

        if (applyBtn) {
            applyBtn.onclick = saveWeeklyEdit;
        }
    }

    window.openWeeklyEditModal = function (dayOfWeek) {
        initWeeklyEditModal();
        const modal = document.getElementById('modal-weekly-edit-overlay');
        if (!modal) return;

        weeklyModalState.dayOfWeek = dayOfWeek;

        const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        document.getElementById('weekly-modal-title').textContent = `Edit all ${dayNames[dayOfWeek]}s`;

        // Load Data
        const rules = availability.filter(r => r.day_of_week == dayOfWeek);
        weeklyModalState.intervals = [];
        let isClosed = false;

        if (rules.length === 0) {
            // No info implies closed or default? Usually explicit closed rule. 
            // If empty, assume open 9-5 or closed? 
            // Existing logic: "Closed"
            isClosed = true;
        } else {
            // Check if strictly closed
            const closedRule = rules.find(r => r.is_closed == 1 || r.is_closed === true);
            if (closedRule && rules.length === 1) { // If multiple rules and one is closed, technically mixed/open? Usually cleaning up.
                isClosed = true;
            } else {
                // Open rules
                const openRules = rules.filter(r => r.is_closed != 1 && r.is_closed !== true);
                if (openRules.length > 0) {
                    weeklyModalState.intervals = openRules.map(r => ({
                        start: r.start_time.substring(0, 5),
                        end: r.end_time.substring(0, 5)
                    })).sort((a, b) => a.start.localeCompare(b.start));
                } else {
                    isClosed = true;
                }
            }
        }

        const checkUnavail = document.getElementById('check-weekly-unavailable');
        if (checkUnavail) {
            checkUnavail.checked = isClosed;
            checkUnavail.onchange(); // Trigger UI visibility
        }

        if (!isClosed && weeklyModalState.intervals.length === 0) {
            weeklyModalState.intervals.push({ start: '09:00', end: '17:00' });
        }

        renderWeeklySlots();
        modal.style.display = 'flex';
    };

    function saveWeeklyEdit() {
        const isClosed = document.getElementById('check-weekly-unavailable').checked;

        // Clean existing availability for this day in memory (will rely on refetch usually)
        // Construct new payload
        let newAvailability = availability.filter(r => r.day_of_week != weeklyModalState.dayOfWeek); // Remove old

        if (isClosed) {
            newAvailability.push({
                schedule_id: activeScheduleId,
                day_of_week: String(weeklyModalState.dayOfWeek),
                is_closed: 1,
                start_time: '00:00',
                end_time: '00:00'
            });
        } else {
            weeklyModalState.intervals.forEach(iv => {
                newAvailability.push({
                    schedule_id: activeScheduleId,
                    day_of_week: String(weeklyModalState.dayOfWeek),
                    is_closed: 0,
                    start_time: iv.start,
                    end_time: iv.end
                });
            });
        }

        // Optimistic Update
        availability = newAvailability;

        // Save
        postData('settings', {
            availability: availability, // Assuming this endpoint accepts full availability dump or diff? 
            // Logic in 'settings' endpoint usually replaces availability.
            schedule_id: activeScheduleId
        }, () => {
            // Success
            document.getElementById('modal-weekly-edit-overlay').style.display = 'none';
            showToast('Weekly schedule updated');
            renderCalendar();
            renderAvailability(); // Refresh list view
        });
    }

    // Initial Load
    fetchAll();

    // --- Calendar Control State ---
    let currentDate = new Date(); // Tracks the currently viewed month

    const btnCalPrev = document.getElementById('cal-prev');
    if (btnCalPrev) {
        btnCalPrev.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
    }

    const btnCalNext = document.getElementById('cal-next');
    if (btnCalNext) {
        btnCalNext.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
    }

    function renderCalendar() {
        const grid = document.getElementById('gbs-calendar-mount');
        const label = document.getElementById('cal-label');
        if (!grid) return;

        const year = currentDate.getFullYear();
        const month = currentDate.getMonth(); // 0-11

        // Update Label
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        label.textContent = `${monthNames[month]} ${year}`;

        // Grid Logic
        grid.innerHTML = '';

        const firstDay = new Date(year, month, 1).getDay(); // 0 (Sun) - 6 (Sat)
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Pad empty cells before 1st of month
        for (let i = 0; i < firstDay; i++) {
            const cell = document.createElement('div');
            cell.className = 'gbs-cal-cell empty';
            cell.style.background = '#f9fafb';
            grid.appendChild(cell);
        }

        // Render Days
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        for (let d = 1; d <= daysInMonth; d++) {
            const dateObj = new Date(year, month, d);
            const dayOfWeek = dateObj.getDay(); // 0-6
            const isPast = dateObj < today;

            // Priority: Past > Override (Holiday) > Weekly Rule

            // 1. Weekly Availability (Aggregated) for Active Schedule
            // Availability is ALREADY filtered to active schedule context by updateActiveAvailability()
            const dayRules = availability.filter(r => r.day_of_week == dayOfWeek);

            let displayTime = 'Unavailable';
            let isClosed = true;

            // Check if we have any open rules
            const openRules = dayRules.filter(r => r.is_closed != 1 && r.is_closed !== true);

            if (openRules.length > 0) {
                isClosed = false;
                // Sort by start time
                openRules.sort((a, b) => a.start_time.localeCompare(b.start_time));

                // Map to string "08:00 - 12:00"
                const times = openRules.map(r => `${r.start_time.substring(0, 5)} - ${r.end_time.substring(0, 5)}`);

                // Join with <br> and wrap in a container that handles overflow if needed
                displayTime = times.join('<br>');
            }

            // 2. Override Check
            const yyyy = year;
            const mm = String(month + 1).padStart(2, '0');
            const dd = String(d).padStart(2, '0');
            const dateStr = `${yyyy}-${mm}-${dd}`;

            // Filter ALL overrides for this date
            const overrides = holidays.filter(h => (h.date || '').startsWith(dateStr));
            let overrideLabel = null;
            let overrideTimes = [];

            if (overrides.length > 0) {
                // Determine if closed.
                // If any override is NOT closed, we are open (unless we implement strict blocking logic).
                // Current Modal logic: If unchecked "Closed", adds intervals.
                // Backend: Deletes all, inserts intervals.
                // So if we have overrides, check validity.

                // Check if purely closed (e.g. one entry with is_closed=1)
                // Or if we have open slots.
                const openOverrides = overrides.filter(h => h.is_closed != 1 && h.is_closed !== true);

                if (openOverrides.length > 0) {
                    isClosed = false;
                    // Sort
                    openOverrides.sort((a, b) => (a.start_time || '').localeCompare(b.start_time || ''));

                    if (openOverrides.length === 1) {
                        const ov = openOverrides[0];
                        if (ov.start_time && ov.end_time) {
                            displayTime = `${ov.start_time.substring(0, 5)} - ${ov.end_time.substring(0, 5)}`;
                        }
                        if (ov.description) overrideLabel = ov.description;
                    } else {
                        // Multiple
                        displayTime = 'Multi'; // Placeholder for below render logic?
                        // Actually we can construct the HTML here or pass a flag.
                        // Let's reuse the openRules array if possible or create a new one.
                        // We can set `openRules` (variable name clash?) NO, overrides take precedence.
                        // Let's create a specialized render for override lists.
                        // We'll map them to strings.
                        const times = openOverrides.map(r => `${r.start_time.substring(0, 5)} - ${r.end_time.substring(0, 5)}`);
                        displayTime = times.join('<br>');
                    }

                    // Label from first/any?
                    const labeled = overrides.find(h => h.description);
                    if (labeled) overrideLabel = labeled.description;

                } else {
                    // All overrides are closed (or empty?)
                    isClosed = true;
                    displayTime = 'Unavailable';
                    const closedOv = overrides.find(h => h.is_closed == 1 || h.is_closed === true);
                    if (closedOv && closedOv.description) overrideLabel = closedOv.description || 'Closed';
                    else overrideLabel = 'Closed';
                }
            } else {
                // No overrides, `isClosed` and `displayTime` remain from Weekly Logic
            }

            // 3. Build Cell
            const cell = document.createElement('div');
            // 'disabled' visual for past dates
            cell.className = `gbs-cal-cell ${isPast ? 'disabled' : ''}`;
            if (overrides.length > 0) cell.classList.add('gbs-has-override');

            let content = `<span class="gbs-cal-date">${d}</span>`;

            if (isPast) {
                // Past
            } else {
                if (isClosed) {
                    // Check if it's explicitly closed by override
                    if (overrides.length > 0) {
                        content += `<span class="gbs-cal-holiday">${overrideLabel || 'Closed'}</span>`;
                    }
                } else {
                    // Open
                    // Render list of times
                    // Determines source: Override or Weekly?
                    if (overrides.length > 0) {
                        // Override Handling
                        if (displayTime.includes('<br>')) {
                            // Multi-line (from my previous join)
                            content += `<div class="gbs-cal-hours-list">`;
                            // I joined with <br>, but for clean styling maybe I should use divs?
                            // displayTime is "09:00 - 10:00<br>12:00 - 13:00"
                            // Just outputting it inside gbs-cal-hours might work if styles allow, 
                            // but gbs-cal-hours-list uses divs.
                            // Let's re-split or just trust the html.
                            // Actually, let's just make it a list if it has br, or use the string.
                            // Simple:
                            const lines = displayTime.split('<br>');
                            lines.forEach(l => {
                                content += `<div class="gbs-cal-hour-row" style="line-height:1.2; font-size:0.75rem;">${l}</div>`;
                            });
                            content += `</div>`;
                        } else {
                            content += `<span class="gbs-cal-hours">${displayTime}</span>`;
                        }

                    } else if (openRules.length > 0) {
                        // Multiple or Single Weekly Rules
                        content += `<div class="gbs-cal-hours-list">`;
                        openRules.forEach(r => {
                            content += `<div class="gbs-cal-hour-row" style="line-height:1.2; font-size:0.75rem;">${r.start_time.substring(0, 5)} - ${r.end_time.substring(0, 5)}</div>`;
                        });
                        content += `</div>`;
                    } else {
                        // Fallback
                        content += `<span class="gbs-cal-hours">${displayTime}</span>`;
                    }

                    if (overrideLabel) {
                        content += `<span class="gbs-cal-holiday" style="background:#dbeafe; color:#1e40af;">${overrideLabel}</span>`;
                    }
                }
            }

            cell.innerHTML = content;
            if (!isPast) {
                cell.addEventListener('click', (e) => openCalendarContextMenu(e, dateObj, dayOfWeek));
            }
            grid.appendChild(cell);
        }
    }

    // --- Override Modal Logic ---
    let miniCalDate = new Date();
    let selectedOverrideDates = [];

    function renderOverrideSlots() {
        const container = document.getElementById('override-slots-container');
        if (!container) return;
        container.innerHTML = '';

        if (!overrideModalState.intervals) overrideModalState.intervals = [];

        overrideModalState.intervals.forEach((slot, idx) => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 mb-3';
            div.innerHTML = `
                <div class="gbs-time-input-group" style="background-color: #f3f4f6; border: none;">
                    <input type="time" class="gbs-time-input ov-start" value="${slot.start}" data-idx="${idx}">
                    <span style="color:#9ca3af; font-weight:300;">-</span>
                    <input type="time" class="gbs-time-input ov-end" value="${slot.end}" data-idx="${idx}">
                </div>
                
                <button type="button" class="gbs-icon-btn btn-remove-slot" data-idx="${idx}" title="Remove">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>

                <button type="button" class="gbs-icon-btn btn-add-slot" title="Add Period">
                     <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </button>

                <button type="button" class="gbs-icon-btn btn-dup-slot" data-idx="${idx}" title="Duplicate">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                </button>
            `;
            container.appendChild(div);
        });

        // Bind Remove
        container.querySelectorAll('.btn-remove-slot').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const btn = e.currentTarget;
                const idx = parseInt(btn.dataset.idx);
                overrideModalState.intervals.splice(idx, 1);
                renderOverrideSlots();
            });
        });

        // Bind Add (New)
        container.querySelectorAll('.btn-add-slot').forEach(btn => {
            btn.addEventListener('click', () => {
                overrideModalState.intervals.push({ start: '12:00', end: '13:00' });
                renderOverrideSlots();
            });
        });

        // Bind Duplicate (New)
        container.querySelectorAll('.btn-dup-slot').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const btn = e.currentTarget;
                const idx = parseInt(btn.dataset.idx);
                const current = overrideModalState.intervals[idx];
                overrideModalState.intervals.splice(idx + 1, 0, { start: current.start, end: current.end });
                renderOverrideSlots();
            });
        });

        // Bind Inputs
        container.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', (e) => {
                const idx = parseInt(e.target.dataset.idx);
                const field = e.target.classList.contains('ov-start') ? 'start' : 'end';
                if (overrideModalState.intervals[idx]) {
                    overrideModalState.intervals[idx][field] = e.target.value;
                }
            });
        });
    }

    window.openDateOverrideModal = function (dateObj) {
        const modal = document.getElementById('modal-date-override-overlay');
        if (!modal) return;

        // Initialize State
        overrideModalState.intervals = [{ start: '09:00', end: '17:00' }];
        overrideModalState.selectedDates.clear();

        let initialIsClosed = false;
        let loadedDesc = '';

        if (dateObj) {
            const yyyy = dateObj.getFullYear();
            const mm = String(dateObj.getMonth() + 1).padStart(2, '0');
            const dd = String(dateObj.getDate()).padStart(2, '0');
            const dateStr = `${yyyy}-${mm}-${dd}`;

            overrideModalState.selectedDates.add(dateStr);
            miniCalDate = new Date(dateObj); // Clone
            miniCalDate.setDate(1);

            // Load existing data if any
            const existing = holidays.filter(h => (h.date || '').startsWith(dateStr));
            loadedDesc = '';

            if (existing.length > 0) {
                const descItem = existing.find(h => h.description);
                if (descItem) loadedDesc = descItem.description;

                const closedRule = existing.find(h => h.is_closed == 1 || h.is_closed === true);
                if (closedRule) {
                    initialIsClosed = true;
                    overrideModalState.intervals = [];
                } else {
                    const loadedIntervals = existing.map(h => ({
                        start: h.start_time ? h.start_time.substring(0, 5) : '09:00',
                        end: h.end_time ? h.end_time.substring(0, 5) : '17:00'
                    })).sort((a, b) => a.start.localeCompare(b.start));

                    if (loadedIntervals.length > 0) overrideModalState.intervals = loadedIntervals;
                }
            } else {
                // Defaults from weekly
                const dayOfWeek = dateObj.getDay();
                const rules = availability.filter(r => parseInt(r.day_of_week) === dayOfWeek && (r.is_closed != 1));
                if (rules.length > 0) {
                    overrideModalState.intervals = rules.map(r => ({
                        start: r.start_time.substring(0, 5),
                        end: r.end_time.substring(0, 5)
                    }));
                }
            }
        } else {
            miniCalDate = new Date();
            miniCalDate.setDate(1);
        }

        renderMiniCalendar();

        // UI Setup
        const checkUnavailable = document.getElementById('check-unavailable-all-day');
        if (checkUnavailable) {
            checkUnavailable.checked = initialIsClosed;

            // Define handler
            const handleCheckChange = (e) => {
                const isClosed = checkUnavailable.checked;
                const container = document.getElementById('override-slots-container');
                const btnAdd = document.getElementById('btn-add-override-slot');
                const descInput = document.getElementById('override-desc');

                // Auto-set text on user interaction
                if (e && descInput) {
                    if (isClosed) {
                        descInput.value = 'Closed';
                    } else if (descInput.value === 'Closed') {
                        descInput.value = '';
                    }
                }

                if (isClosed) {
                    if (container) container.style.display = 'none';
                    if (btnAdd) btnAdd.style.display = 'none';
                } else {
                    if (container) container.style.display = 'block';
                    if (btnAdd) btnAdd.style.display = 'flex';
                    if (overrideModalState.intervals.length === 0) {
                        overrideModalState.intervals.push({ start: '09:00', end: '17:00' });
                        renderOverrideSlots();
                    }
                }
            };

            checkUnavailable.onchange = handleCheckChange;
            // Initialize visibility
            handleCheckChange();
        }

        renderOverrideSlots();

        const descInput = document.getElementById('override-desc');
        if (descInput) descInput.value = loadedDesc;

        updateOverrideSettingsUI();

        modal.style.display = 'flex';

        // Bind Buttons
        const closeBtn = document.getElementById('btn-close-override');
        const cancelBtn = document.getElementById('btn-cancel-override');
        const applyBtn = document.getElementById('btn-apply-override');
        const addSlotBtn = document.getElementById('btn-add-override-slot');

        const closeFunc = () => modal.style.display = 'none';

        if (closeBtn) closeBtn.onclick = closeFunc;
        if (cancelBtn) cancelBtn.onclick = closeFunc;

        if (addSlotBtn) {
            addSlotBtn.onclick = () => {
                overrideModalState.intervals.push({ start: '12:00', end: '13:00' });
                renderOverrideSlots();
            };
        }

        document.getElementById('mini-cal-prev').onclick = () => {
            miniCalDate.setMonth(miniCalDate.getMonth() - 1);
            renderMiniCalendar();
        };
        document.getElementById('mini-cal-next').onclick = () => {
            miniCalDate.setMonth(miniCalDate.getMonth() + 1);
            renderMiniCalendar();
        };

        if (applyBtn) {
            applyBtn.onclick = () => {
                if (overrideModalState.selectedDates.size === 0) {
                    alert('Please select at least one date');
                    return;
                }

                const isClosed = checkUnavailable ? checkUnavailable.checked : false;
                const dates = Array.from(overrideModalState.selectedDates);

                const payload = {
                    dates: dates,
                    description: document.getElementById('override-desc') ? document.getElementById('override-desc').value : '',
                    is_closed: isClosed ? 1 : 0,
                    intervals: isClosed ? [] : overrideModalState.intervals
                };

                fetch(root + 'holidays', {
                    method: 'POST',
                    headers: { ...headers, 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            showToast('Dates updated');
                            closeFunc();
                            fetchAll().then(() => renderCalendar());
                        } else {
                            showToast('Error saving updates');
                        }
                    });
            };
        }
    };

    function renderMiniCalendar() {
        const grid = document.getElementById('mini-cal-grid');
        const label = document.getElementById('mini-cal-label');
        if (!grid) return;

        const year = miniCalDate.getFullYear();
        const month = miniCalDate.getMonth();

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        label.textContent = `${monthNames[month]} ${year}`;

        grid.innerHTML = '';

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) {
            grid.appendChild(document.createElement('div'));
        }

        for (let d = 1; d <= daysInMonth; d++) {
            const btn = document.createElement('button');
            btn.className = 'w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 text-gray-700 mx-auto';
            btn.textContent = d;

            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            if (overrideModalState.selectedDates.has(dateStr)) {
                btn.classList.add('bg-primary', 'text-white', 'hover:bg-primary');
            }

            btn.onclick = () => {
                if (overrideModalState.selectedDates.has(dateStr)) {
                    overrideModalState.selectedDates.delete(dateStr);
                } else {
                    overrideModalState.selectedDates.add(dateStr);
                }
                renderMiniCalendar();
                updateOverrideSettingsUI();
            };

            grid.appendChild(btn);
        }
    }

    function updateOverrideSettingsUI() {
        const wrapper = document.getElementById('override-settings-wrapper');
        const applyBtn = document.getElementById('btn-apply-override');
        if (overrideModalState.selectedDates.size > 0) {
            wrapper.style.display = 'block';
            applyBtn.disabled = false;
            applyBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            wrapper.style.display = 'none';
            applyBtn.disabled = true;
            applyBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Legacy openWeeklyEditModal removed (was switching to list view)

    // --- Calendar Context Menu Logic ---
    let activeContextCell = null;
    let contextMenuEl = null;

    function openCalendarContextMenu(e, dateObj, dayOfWeek) {
        e.stopPropagation();

        // 1. Create Menu if not exists
        if (!contextMenuEl) {
            contextMenuEl = document.createElement('div');
            contextMenuEl.className = 'gbs-context-menu';
            document.body.appendChild(contextMenuEl);

            // Close on click outside
            document.addEventListener('click', (e) => {
                if (contextMenuEl && contextMenuEl.style.display === 'block' && !contextMenuEl.contains(e.target)) {
                    closeContextMenu();
                }
            });
        }

        // 2. Clear Active State
        if (activeContextCell) activeContextCell.classList.remove('active-context');

        // 3. Set New Active
        const cell = e.currentTarget;
        activeContextCell = cell;
        cell.classList.add('active-context');

        // 4. Check for Overrides
        const yyyy = dateObj.getFullYear();
        const mm = String(dateObj.getMonth() + 1).padStart(2, '0');
        const dd = String(dateObj.getDate()).padStart(2, '0');
        const dateStr = `${yyyy}-${mm}-${dd}`;

        const overrideItems = holidays.filter(h => (h.date || '').startsWith(dateStr));
        const hasOverride = overrideItems.length > 0;

        // 5. Content
        const dayNames = ['Sundays', 'Mondays', 'Tuesdays', 'Wednesdays', 'Thursdays', 'Fridays', 'Saturdays'];
        const dayName = dayNames[dayOfWeek];

        let menuHtml = `
            <button class="gbs-context-item" id="btn-ctx-edit-date">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Edit date
            </button>
        `;

        // Add Reset Option if override exists
        if (hasOverride) {
            menuHtml += `
            <button class="gbs-context-item" id="btn-ctx-reset">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path></svg>
                Reset to weekly hours
            </button>
            `;
        } else {
            menuHtml += `
            <button class="gbs-context-item" id="btn-ctx-edit-weekly">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"></path><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                Edit all ${dayName}
            </button>
            `;
        }

        contextMenuEl.innerHTML = menuHtml;

        // 6. Position
        const rect = cell.getBoundingClientRect();
        contextMenuEl.style.top = (rect.bottom + 5) + 'px'; // Below cell
        contextMenuEl.style.left = rect.left + 'px';

        // Check overflow
        if (rect.left + 220 > window.innerWidth) {
            contextMenuEl.style.left = (rect.right - 220) + 'px';
        }

        contextMenuEl.style.display = 'block';

        // 7. Bind Actions
        const btnEditDate = contextMenuEl.querySelector('#btn-ctx-edit-date');
        if (btnEditDate) {
            btnEditDate.addEventListener('click', (e) => {
                e.stopPropagation();
                closeContextMenu();
                openDateOverrideModal(dateObj);
            });
        }

        const btnReset = contextMenuEl.querySelector('#btn-ctx-reset');
        if (btnReset) {
            btnReset.addEventListener('click', (e) => {
                e.stopPropagation();
                closeContextMenu();

                if (confirm('Are you sure you want to reset this date to weekly hours?')) {
                    const ids = overrideItems.map(h => h.id);
                    Promise.all(ids.map(id => fetch(root + 'holidays?id=' + id, { method: 'DELETE', headers }).then(r => r.json())))
                        .then(() => {
                            showToast('Date reset to weekly hours');
                            fetchAll().then(() => renderCalendar());
                        });
                }
            });
        }

        const btnEditAll = contextMenuEl.querySelector('#btn-ctx-edit-weekly');
        if (btnEditAll) {
            btnEditAll.addEventListener('click', (e) => {
                e.stopPropagation();
                closeContextMenu();

                console.log('Clicked Edit Weekly for day:', dayOfWeek);
                if (typeof window.openWeeklyEditModal === 'function') {
                    window.openWeeklyEditModal(dayOfWeek);
                } else {
                    console.error('window.openWeeklyEditModal is not defined');
                }
            });
        }
    }

    function closeContextMenu() {
        if (contextMenuEl) contextMenuEl.style.display = 'none';
        if (activeContextCell) {
            activeContextCell.classList.remove('active-context');
            activeContextCell = null;
        }
    }

    // --- Schedule Interaction Logic (New) ---
    function initScheduleLogic() {
        // Listener for + Hours button (Date Override)
        const btnOverride = document.getElementById('btn-add-override');
        if (btnOverride) {
            btnOverride.addEventListener('click', (e) => {
                e.preventDefault();
                // Open with today's date
                openDateOverrideModal(null);
            });
        }

        // 1. Selector Dropdown Toggle
        const titleRow = document.querySelector('.gbs-sched-name-row');
        const selectorMenu = document.getElementById('menu-sched-selector');

        if (titleRow && selectorMenu) {
            // MOVE MENU TO BE CHILD OF TITLE (for positioning)
            if (selectorMenu.parentElement !== titleRow) {
                titleRow.appendChild(selectorMenu);
            }

            titleRow.addEventListener('click', (e) => {
                // Ignore if clicking inside the menu
                if (selectorMenu.contains(e.target)) return;

                e.stopPropagation();
                selectorMenu.style.display = selectorMenu.style.display === 'block' ? 'none' : 'block';
                if (selectorMenu.style.display === 'block') renderScheduleSelector();
            });

            // Close on click out
            document.addEventListener('click', (e) => {
                if (!titleRow.contains(e.target)) {
                    selectorMenu.style.display = 'none';
                }
            });

            // Create Schedule Button Listener (ID from PHP)
            const btnCreate = document.getElementById('btn-new-schedule');

            const openCreateModal = () => {
                const modalCreate = document.getElementById('modal-create-schedule-overlay');
                const inputName = document.getElementById('input-new-schedule-name');
                if (inputName) inputName.value = '';
                if (modalCreate) {
                    modalCreate.style.display = 'flex';
                    if (inputName) inputName.focus();
                }
                selectorMenu.style.display = 'none';
            };

            if (btnCreate) {
                btnCreate.addEventListener('click', (e) => {
                    e.stopPropagation();
                    openCreateModal();
                });
            } else {
                // Fallback delegation
                selectorMenu.addEventListener('click', (e) => {
                    let target = e.target;
                    if (target.tagName !== 'BUTTON' && target.closest('button')) target = target.closest('button');
                    if (target && target.textContent && target.textContent.includes('Create schedule')) {
                        e.stopPropagation();
                        openCreateModal();
                    }
                });
            }
        }

        // 4. Create Schedule Modal Logic
        const modalCreate = document.getElementById('modal-create-schedule-overlay');
        if (modalCreate) {
            const btnClose = document.getElementById('btn-close-create');
            const btnCancel = document.getElementById('btn-cancel-create');
            const btnConfirm = document.getElementById('btn-create-schedule-confirm');
            const inputName = document.getElementById('input-new-schedule-name');

            const closeCreate = () => modalCreate.style.display = 'none';

            [btnClose, btnCancel].forEach(b => b?.addEventListener('click', closeCreate));

            // Use simple listener, assuming init runs once
            if (btnConfirm) {
                // Clone to remove old listeners if any (safety)
                const newBtn = btnConfirm.cloneNode(true);
                btnConfirm.parentNode.replaceChild(newBtn, btnConfirm);

                newBtn.addEventListener('click', () => {
                    const name = inputName.value.trim();
                    if (name) {
                        createSchedule(name);
                        closeCreate();
                    } else {
                        alert('Please enter a name');
                    }
                });
            }
        }




        // 2. Dynamic Schedule Actions Menu
        const btnSchedActions = document.getElementById('btn-sched-actions');
        const menuActions = document.getElementById('menu-sched-actions');

        if (btnSchedActions && menuActions) {
            btnSchedActions.addEventListener('click', () => {
                const current = schedules.find(s => s.id == activeScheduleId);
                if (!current) return;

                const isDefault = (current.is_default == '1');

                // Build Menu HTML dynamically
                let html = `
                    <button class="gbs-btn-text-icon" data-action="rename">
                        <!-- Icon Rename -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Rename
                    </button>
                    ${!isDefault ? `
                    <button class="gbs-btn-text-icon" data-action="set_default">
                        <!-- Icon Star -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        Set as default schedule
                    </button>
                    ` : ''}
                    <button class="gbs-btn-text-icon" data-action="duplicate">
                        <!-- Icon Copy -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                        Duplicate
                    </button>
                    ${!isDefault ? `
                    <button class="gbs-btn-text-icon error-text" data-action="delete">
                        <!-- Icon Trash -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Delete
                    </button>
                    ` : ''}
                `;

                menuActions.innerHTML = html;
            });

            // Action Delegation
            menuActions.addEventListener('click', (e) => {
                const btn = e.target.closest('button');
                if (!btn) return;

                const action = btn.dataset.action;
                if (action === 'rename') openRenameModal();
                else if (action === 'set_default') setAsDefault();
                else if (action === 'duplicate') duplicateSchedule();
                else if (action === 'delete') deleteSchedule();

                menuActions.style.display = 'none';
            });
        }

        // 3. Rename Modal Logic
        const modalRename = document.getElementById('modal-rename-overlay');
        const btnClose = document.getElementById('btn-close-rename');
        const btnCancel = document.getElementById('btn-cancel-rename');
        const btnSave = document.getElementById('btn-save-rename');
        const inputName = document.getElementById('input-schedule-name');
        const btnClear = document.getElementById('btn-clear-name');

        function closeRen() { modalRename.style.display = 'none'; }

        if (modalRename) {
            [btnClose, btnCancel].forEach(b => b?.addEventListener('click', closeRen));

            // Replace old inline logic with API call
            btnSave.replaceWith(btnSave.cloneNode(true)); // Remove old listeners
            const newBtnSave = document.getElementById('btn-save-rename');

            newBtnSave.addEventListener('click', () => {
                const newName = inputName.value.trim();
                if (newName) {
                    renameSchedule(newName); // Calls API helper
                }
                closeRen();
            });

            btnClear.addEventListener('click', () => inputName.value = '');
        }
    }

    function openRenameModal() {
        const modal = document.getElementById('modal-rename-overlay');
        const input = document.getElementById('input-schedule-name');
        const current = schedules.find(s => s.id == activeScheduleId); // Loose equality
        if (modal && current) {
            input.value = current.name;
            modal.style.display = 'flex';
            input.focus();
        }
    }

    // Legacy duplicateSchedule removed -> uses helper below

    function renderScheduleSelector() {
        const list = document.getElementById('sched-selector-list');
        if (!list) return;

        list.innerHTML = schedules.map(s => {
            const isDefault = (s.is_default == '1');
            const displayName = s.name + (isDefault ? ' (default)' : '');

            return `
            <div class="gbs-selector-item ${s.id == activeScheduleId ? 'selected' : ''}" data-id="${s.id}">
                <span>${displayName}</span>
                ${s.id == activeScheduleId ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>' : ''}
            </div>
            `;
        }).join('');

        // Add click listeners to items
        list.querySelectorAll('.gbs-selector-item').forEach(item => {
            item.addEventListener('click', () => {
                activeScheduleId = item.dataset.id;
                const selected = schedules.find(s => s.id == activeScheduleId); // Loose equality
                if (selected) {
                    availability = selected.data;
                    updateScheduleHeader();
                    renderAvailability();
                    document.getElementById('menu-sched-selector').style.display = 'none';
                }
            });
        });
    }

    function updateScheduleHeader() {
        const h2 = document.querySelector('.gbs-sched-name');
        const meta = document.querySelector('.gbs-active-meta');
        const current = schedules.find(s => s.id == activeScheduleId); // Loose equality

        if (h2 && current) {
            const isDefault = (current.is_default == '1');
            h2.textContent = current.name + (isDefault ? ' (default)' : '');
        }

        if (meta && current) {
            // Calculate active services (services that use this schedule)
            // 'services' array has service objects. We need to check their assignment.
            // Assumption: we have 'services' loaded globally.
            // But wait, the relationship is usually stored on the service itself (service.schedule_id or similar).
            // Or there is an assignment table?
            // Checking fetchAll(): fetch(root + 'services'...)
            // Service object structure: { id, name, ..., schedule_id? }
            // Let's assume for now we need to filter services that are assigned to this schedule.
            // If the system uses default logic (if no specific assignment, use default), that's complex.
            // BASED ON CODE: postData('assign/service-services'...) was not seen, but 'assign/service-bays' was.
            // There is no explicit 'schedule_id' on services visible in the code yet?
            // Actually, in fetchAll, we just get services.
            // Let's look at how assignments are handled.
            // If I look at lines 1640-ish...
            // Wait, the user wants me to implement the UI first.
            // I will implement the UI assuming I can filter `services` later.
            // For now, let's just count them if we can, or placeholder.

            // Let's rely on a helper to get assigned services count.
            const defaultId = getDefaultScheduleId();
            const assignedCount = getAssignedServicesCount(current.id, defaultId);
            const label = assignedCount === 1 ? 'service' : 'services';

            meta.innerHTML = `
                Active on: 
                <a href="#" id="trigger-active-services" style="display:inline-flex; align-items:center; gap:4px;">
                    ${assignedCount} ${label}
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </a>
                <div id="dropdown-active-services" class="gbs-dropdown-menu" style="display:none;">
                    <div class="gbs-dropdown-search">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#9ca3af;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" placeholder="Search..." id="input-service-search">
                    </div>
                    <div class="gbs-dropdown-actions-row">
                        <button class="gbs-link-btn" id="btn-select-all-services">select all</button>
                        <span>/</span>
                        <button class="gbs-link-btn" id="btn-clear-services">clear</button>
                    </div>
                    <div class="gbs-dropdown-header-label">USING WORKING HOURS</div>
                    <div class="gbs-dropdown-list" id="list-active-services">
                        <!-- Items rendered here -->
                    </div>
                    <div class="gbs-dropdown-footer">
                         <button class="gbs-btn-primary-clean" id="btn-apply-services" style="width:auto; padding: 6px 16px; font-size: 0.9rem;">Apply</button>
                         <button class="gbs-btn-secondary-clean" id="btn-cancel-services" style="width:auto; padding: 6px 16px; font-size: 0.9rem; border:none;">Cancel</button>
                    </div>
                </div>
            `;

            attachServiceDropdownListeners();
        }
    }

    function getAssignedServicesCount(scheduleId, defaultId) {
        if (!services) return 0;
        return services.filter(s => s.schedule_id == scheduleId).length;
    }

    function getDefaultScheduleId() {
        const def = schedules.find(s => s.is_default == '1');
        return def ? def.id : null;
    }

    function attachServiceDropdownListeners() {
        const trigger = document.getElementById('trigger-active-services');
        const dropdown = document.getElementById('dropdown-active-services');
        const inputSearch = document.getElementById('input-service-search');
        const btnApply = document.getElementById('btn-apply-services');
        const btnCancel = document.getElementById('btn-cancel-services');

        if (trigger && dropdown) {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const isVisible = dropdown.style.display === 'block';
                closeAllDropdowns(); // Close others
                dropdown.style.display = isVisible ? 'none' : 'block';

                if (!isVisible) {
                    renderActiveServicesList();
                    if (inputSearch) inputSearch.focus();
                }
            });

            dropdown.addEventListener('click', (e) => e.stopPropagation());

            if (btnCancel) btnCancel.addEventListener('click', () => dropdown.style.display = 'none');

            // Select All / Clear
            const btnSelectAll = document.getElementById('btn-select-all-services');
            const btnClear = document.getElementById('btn-clear-services');

            if (btnSelectAll) {
                btnSelectAll.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdown.querySelectorAll('.service-check').forEach(cb => cb.checked = true);
                });
            }

            if (btnClear) {
                btnClear.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdown.querySelectorAll('.service-check').forEach(cb => cb.checked = false);
                });
            }

            // Search Logic
            if (inputSearch) {
                inputSearch.addEventListener('input', (e) => {
                    const term = e.target.value.toLowerCase();
                    const items = dropdown.querySelectorAll('.gbs-service-item-row');
                    items.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        item.style.display = text.includes(term) ? 'flex' : 'none';
                    });
                });
            }

            // Apply Logic
            if (btnApply) {
                // Clone to remove old listeners
                const newBtn = btnApply.cloneNode(true);
                btnApply.parentNode.replaceChild(newBtn, btnApply);

                newBtn.addEventListener('click', () => {
                    saveServiceAssignments();
                    dropdown.style.display = 'none';
                });
            }
        }
    }

    function closeAllDropdowns() {
        document.querySelectorAll('.gbs-dropdown-menu').forEach(el => el.style.display = 'none');
    }

    // Close on click outside
    document.addEventListener('click', () => closeAllDropdowns());

    function renderActiveServicesList() {
        const list = document.getElementById('list-active-services');
        if (!list) return;

        // Combine all services
        // We need to show ALL services, checking the ones assigned to THIS schedule
        const currentSchedId = activeScheduleId;
        const defaultSchedId = getDefaultScheduleId();

        const html = services.map(s => {
            // Check if assigned to this schedule
            // Direct equality check since we enforced strict schedule_id
            const isChecked = (s.schedule_id == currentSchedId);

            return `
            <label class="gbs-service-item-row">
                <input type="checkbox" class="service-check" value="${s.id}" ${isChecked ? 'checked' : ''}>
                <span class="gbs-dot-purple"></span>
                <div class="gbs-svc-info">
                    <span class="gbs-svc-name">${s.name}</span>
                    <span class="gbs-svc-meta">${s.duration_minutes || 60} mins â€¢ ${s.type || 'Service'}</span>
                </div>
            </label>
            `;
        }).join('');

        list.innerHTML = html;
    }

    function saveServiceAssignments() {
        const checkedIds = Array.from(document.querySelectorAll('#list-active-services .service-check:checked')).map(cb => cb.value);

        // This is tricky. We are "Assigning these services to THIS schedule".
        // Use a backend endpoint to batch update.
        // Assuming endpoint exists or we loop.
        // Let's assume we post to 'assign/schedule-services'

        showToast('Saving assignments...');

        fetch(root + 'assign/schedule-services', {
            method: 'POST',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify({
                schedule_id: activeScheduleId,
                service_ids: checkedIds
            })
        })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    showToast('Services updated');
                    // We need to update local 'services' array to reflect changes?
                    // Ideally re-fetch all services
                    fetchAll().then(() => updateScheduleHeader());
                } else {
                    showToast('Error saving');
                }
            });
    }


    // --- Actions ---
    function renameSchedule(name) {
        const current = schedules.find(s => s.id == activeScheduleId);
        if (!current) return;

        // Optimistic UI
        const oldName = current.name;
        current.name = name;
        updateScheduleHeader();

        fetch(root + 'schedules', {
            method: 'POST',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'rename', id: current.id, name: name })
        })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    showToast('Schedule renamed');
                    renderScheduleSelector();
                } else {
                    current.name = oldName;
                    updateScheduleHeader();
                    showToast('Error renaming schedule');
                }
            });
    }

    function duplicateSchedule() {
        const current = schedules.find(s => s.id == activeScheduleId);
        if (!current) return;

        showToast('Duplicating...');

        fetch(root + 'schedules', {
            method: 'POST',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'duplicate', source_id: current.id })
        })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    // We need to reload to ensure IDs match DB
                    fetchAll().then(() => {
                        activeScheduleId = res.id;
                        updateActiveAvailability();
                        updateScheduleHeader();
                        renderAvailability();
                        showToast('Schedule duplicated');
                    });
                } else {
                    showToast('Error duplicating schedule');
                }
            });
    }

    function updateActiveAvailability() {
        const s = schedules.find(s => s.id == activeScheduleId);
        if (s) {
            // Deep copy to prevent reference issues
            availability = JSON.parse(JSON.stringify(s.data || []));
        } else {
            availability = [];
        }
        renderAvailability();
    }
    function setAsDefault() {
        const current = schedules.find(s => s.id == activeScheduleId);
        if (!current) return;

        fetch(root + 'schedules', {
            method: 'POST',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'set_default', id: current.id })
        })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    // Update local state
                    schedules.forEach(s => s.is_default = (s.id == current.id) ? '1' : '0');
                    showToast('Set as default schedule');
                    updateScheduleHeader(); // Might need to re-render menu or selector to show status
                } else {
                    showToast('Error setting default');
                }
            });
    }

    function deleteSchedule() {
        const current = schedules.find(s => s.id == activeScheduleId);
        if (!current) return;

        if (!confirm('Are you sure you want to delete this schedule? This cannot be undone.')) return;

        fetch(root + 'schedules?id=' + current.id, {
            method: 'DELETE',
            headers: { ...headers }
        })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    showToast('Schedule deleted');
                    // Reload or remove from array
                    schedules = schedules.filter(s => s.id != current.id);
                    // Switch to default
                    const def = schedules.find(s => s.is_default == '1');
                    activeScheduleId = def ? def.id : schedules[0].id;

                    updateActiveAvailability();
                    updateScheduleHeader();
                    renderAvailability();
                } else {
                    showToast(res.message || 'Error deleting schedule');
                }
            });
    }

    function createSchedule(name = 'Working hours') {
        showToast('Creating ' + name + '...');
        fetch(root + 'schedules', {
            method: 'POST',
            headers: { ...headers, 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'create', name: name })
        })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    fetchAll().then(() => {
                        activeScheduleId = res.id;
                        updateActiveAvailability();
                        updateScheduleHeader();
                        renderAvailability();
                        showToast('Schedule created');
                    });
                } else {
                    showToast('Error creating schedule');
                }
            });
    }

    // --- Data Fetching ---
    function fetchAll() {
        const ts = Date.now();
        return Promise.all([
            fetch(root + 'services?t=' + ts, { headers }).then(r => r.json()),

            fetch(root + 'staff?t=' + ts, { headers }).then(r => r.json()),
            fetch(root + 'settings?t=' + ts, { headers }).then(r => r.json()),
            fetch(root + 'holidays?t=' + ts, { headers }).then(r => r.json())
        ]).then(([dServices, dStaff, dSettings, dHolidays]) => {
            services = dServices;

            staff = dStaff;

            // NEW: Parse Schedules & Availability
            const rawSchedules = dSettings.schedules || [];
            const rawAvailability = dSettings.availability || [];

            if (rawSchedules.length > 0) {
                schedules = rawSchedules.map(sched => {
                    // Filter availability for this schedule
                    const schedData = rawAvailability.filter(a => a.schedule_id == sched.id);
                    return { ...sched, data: schedData };
                });

                // Set Active Schedule: Default or First or Keep Current if exists
                if (schedules.find(s => s.id == activeScheduleId)) {
                    // keep active
                } else {
                    const def = schedules.find(s => s.is_default == '1');
                    activeScheduleId = def ? def.id : schedules[0].id;
                }
            } else {
                // Fallback (Migration/Error)
                schedules = [{
                    id: 1,
                    name: 'Working hours (default)',
                    data: rawAvailability,
                    is_default: 1
                }];
                activeScheduleId = 1;
            }

            updateActiveAvailability();
            holidays = dHolidays;

            renderAll();
            // initScheduleLogic(); // Removed to prevent duplicate listeners

        }).catch(err => console.error(err));
    }

    // --- Rendering ---
    function renderAll() {
        renderServices();

        renderStaff();
        renderAvailability();
        renderHolidays();
        const calView = document.getElementById('gbs-view-calendar');
        if (calView && calView.style.display === 'block') {
            renderCalendar();
        }
    }

    let createServiceModalInited = false;
    function renderServices() {
        const container = document.getElementById('gbs-services-list');
        if (!container) return;

        // Init Modal Logic Once
        if (!createServiceModalInited) {
            initCreateServiceModal();
            createServiceModalInited = true;
        }

        if (services.length === 0) {
            container.innerHTML = `<div style="text-align:center; padding:40px; color:#6b7280; background:#f9fafb; border-radius:8px;">No services defined. Create one to get started.</div>`;
            return;
        }

        container.innerHTML = services.map(s => {
            const typeClass = 'type-' + (s.type || 'service').toLowerCase();
            const duration = s.duration_minutes || 60;
            const price = s.price || 0;

            // Format Duration
            const h = Math.floor(duration / 60);
            const m = duration % 60;
            const durText = (h > 0 ? h + ' hr ' : '') + (m > 0 ? m + ' min' : '');

            return `
            <div class="gbs-service-card ${typeClass}">
                <div class="gbs-sc-content">
                    <div class="gbs-sc-checkbox">
                        <input type="checkbox">
                    </div>
                    <div class="gbs-sc-info">
                         <h3>${s.name}</h3>
                         <div class="gbs-sc-meta">
                             <span>${durText}</span>
                             <span>&middot;</span>
                             <span style="text-transform: capitalize;">${s.type}</span>
                             <span>&middot;</span>
                             <span>Â£${price}</span>
                         </div>
                    </div>
                </div>
                
                <div class="gbs-sc-actions">
                    <button class="gbs-btn-copy" onclick="alert('Link copied!')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                        Copy link
                    </button>
                    <button class="gbs-btn-more btn-service-menu-trigger" data-id="${s.id}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </button>
                    <!-- Context Menu built on fly or static? Static for now -->
                    <div id="menu-service-${s.id}" class="gbs-service-menu">
                        <!-- <button>Edit</button> -->
                        <button class="delete btn-delete-service" data-id="${s.id}">Delete</button>
                    </div>
                </div>
            </div>
            `;
        }).join('');

        // Attach Menu Listeners
        container.querySelectorAll('.btn-service-menu-trigger').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                // Close others
                document.querySelectorAll('.gbs-service-menu').forEach(el => el.style.display = 'none');

                const menu = document.getElementById('menu-service-' + btn.dataset.id);
                if (menu) menu.style.display = 'block';
            });
        });

        // Close menus on click out
        document.addEventListener('click', () => {
            document.querySelectorAll('.gbs-service-menu').forEach(el => el.style.display = 'none');
        });
    }

    function initCreateServiceModal() {
        const btnOpen = document.getElementById('btn-open-create-service');
        const modal = document.getElementById('modal-create-service-overlay');
        const btnCancel = document.getElementById('btn-cancel-create-service');
        const form = document.getElementById('form-create-service-modal');

        // Dropdown Elements
        const dropdown = document.getElementById('dropdown-create-service');
        const btnOne = document.getElementById('btn-create-one-on-one');
        const btnRobin = document.getElementById('btn-create-round-robin');

        // Accordion Logic
        const sections = document.querySelectorAll('.gbs-em-section');
        sections.forEach(section => {
            const header = section.querySelector('.gbs-em-section-header');
            if (header) {
                header.addEventListener('click', () => {
                    // Optional: Close others? Calendly usually allows multiple open. Let's toggle.
                    section.classList.toggle('expanded');
                });
            }
        });

        // Live Summary Updates
        const durSelect = document.getElementById('modal-service-duration');
        const durSummary = document.getElementById('summary-duration');
        if (durSelect && durSummary) {
            durSelect.addEventListener('change', () => {
                durSummary.innerText = durSelect.options[durSelect.selectedIndex].text;
            });
        }

        // Toggle Dropdown
        if (btnOpen && dropdown) {
            btnOpen.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                // Close other open menus if any
                document.querySelectorAll('.gbs-dropdown-menu').forEach(el => {
                    if (el !== dropdown) el.style.display = 'none';
                });

                const isVisible = dropdown.style.display === 'block';
                dropdown.style.display = isVisible ? 'none' : 'block';
            });

            // Global click to close
            document.addEventListener('click', () => {
                dropdown.style.display = 'none';
            });
        }

        // Option 1: One-on-one -> Open Modal
        if (btnOne && modal) {
            btnOne.addEventListener('click', (e) => {
                e.preventDefault(); // prevent bubbling to document
                dropdown.style.display = 'none';
                modal.style.display = 'flex';
                // Reset form or set type if needed
            });
        }

        // Option 2: Round Robin -> Placeholder or Modal
        if (btnRobin) {
            btnRobin.addEventListener('click', (e) => {
                e.preventDefault();
                dropdown.style.display = 'none';
                alert('Round Robin / Group scheduling is coming soon!');
            });
        }

        // Close 'X' Button
        const btnCloseX = document.getElementById('btn-cancel-create-service-x');
        if (btnCloseX) btnCloseX.addEventListener('click', () => modal && (modal.style.display = 'none'));

        // "More Options" Button (Previously Cancel)
        if (btnCancel) {
            btnCancel.addEventListener('click', (e) => {
                e.preventDefault();
                alert('Advanced options coming soon!');
            });
        }

        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.style.display = 'none';
            });
        }

        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();

                // Collect Data
                const name = document.getElementById('modal-service-name').value;
                const type = document.getElementById('modal-service-type').value;
                const duration = document.getElementById('modal-service-duration').value;
                const price = document.getElementById('modal-service-price').value;

                // Send API Request
                fetch(root + 'services', {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({
                        name: name,
                        type: type,
                        duration: duration,
                        price: price
                    })
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            if (modal) modal.style.display = 'none';
                            form.reset();
                            showToast('Service created: ' + name);
                            // Refresh
                            fetchAll();
                        } else {
                            alert('Error: ' + res.message);
                        }
                    });
            });
        }
    }



    function renderStaff() {
        const list = document.getElementById('staff-list-container');
        if (list) {
            if (staff.length === 0) {
                list.innerHTML = `
                    <div style="text-align:center; padding: 40px 20px; background: #f9fafb; border-radius: 8px; border: 1px dashed #e5e7eb;">
                        <div style="color: #9ca3af; margin-bottom: 10px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 style="margin: 0 0 5px 0; color: #374151; font-size: 1rem;">No team members yet</h3>
                        <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">Add your first staff member to get started.</p>
                    </div>
                `;
                return;
            }
            list.innerHTML = staff.map(s => {
                const initials = s.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                return `
                <div class="gbs-staff-row" style="display: flex; align-items: center; justify-content: space-between; padding: 16px; border-bottom: 1px solid #f3f4f6; transition: background 0.2s;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem;">
                            ${initials}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #1f2937; font-size: 1rem;">${s.name}</div>
                            <div style="color: #6b7280; font-size: 0.85rem;">${s.email}</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 6px; background: #f3f4f6; padding: 4px 10px; border-radius: 20px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg> 
                            <span style="font-size: 0.85rem; font-weight: 500; color: #4b5563;">${s.service_ids ? s.service_ids.length : 0} services</span>
                        </div>

                        <div class="gbs-row-actions" style="display: flex; gap: 8px;">
                            <button class="gbs-icon-btn btn-edit-staff" data-id="${s.id}" title="Edit Staff Details">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>

                            <button class="gbs-icon-btn remove btn-delete-staff" data-id="${s.id}" title="Delete">
                                 <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            `}).join('');
        }
    }

    // --- Modern Scheduler Layout (Multi-Interval Logic) ---
    function renderAvailability() {
        const container = document.getElementById('availability-schedule');
        if (!container) return;

        // Group raw availability by day (0-6)
        const dayGroups = {};
        for (let i = 0; i < 7; i++) dayGroups[i] = [];

        availability.forEach(d => {
            const day = parseInt(d.day_of_week);
            if (dayGroups[day]) dayGroups[day].push(d);
        });

        const dayLetters = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

        container.innerHTML = Object.keys(dayGroups).map(key => {
            const dayIdx = parseInt(key);
            const slots = dayGroups[dayIdx];
            const letter = dayLetters[dayIdx];

            // Determine Closed State
            const isClosed = (slots.length === 0) || (slots.every(s => s.is_closed == '1' || s.is_closed === true));

            let slotsHtml = '';
            let actionsHtml = '';

            if (isClosed) {
                slotsHtml = `<div class="gbs-status-text" style="color:#ef4444; font-weight:500; display:flex; align-items:center; height:42px;">Unavailable</div>`;
                actionsHtml = `
                    <button type="button" class="gbs-icon-btn add btn-action-open" data-day="${dayIdx}" title="Add hours" style="color:#3b82f6;">
                         <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    </button>
                    ${renderCopyButton(dayIdx)}
                `;
            } else {
                // Active Slots
                const openSlots = slots.filter(s => s.is_closed != '1' && s.is_closed !== true);

                // Render each slot
                const renderedSlots = openSlots.map((slot, sIdx) => `
                    <div class="gbs-time-row" style="display:flex; align-items:center; gap:10px;">
                        <div class="gbs-time-range" style="background:#f3f4f6; border-radius:8px; padding:5px 10px; display:flex; align-items:center; gap:8px;">
                            <input type="text" readonly value="${(slot.start_time || '09:00').substring(0, 5)}" class="gbs-time-input avail-start" data-day="${dayIdx}" data-rpid="${sIdx}" style="width:60px; text-align:center; border:none; background:transparent; padding:0; font-weight:600; color:#1f2937;"> 
                            <span style="color:#9ca3af;">-</span>
                            <input type="text" readonly value="${(slot.end_time || '17:00').substring(0, 5)}" class="gbs-time-input avail-end" data-day="${dayIdx}" data-rpid="${sIdx}" style="width:60px; text-align:center; border:none; background:transparent; padding:0; font-weight:600; color:#1f2937;">
                        </div>
                        <button type="button" class="gbs-icon-btn remove btn-action-remove-slot" data-day="${dayIdx}" data-ref-idx="${availability.indexOf(slot)}" title="Remove slot" style="color:#ef4444; opacity:0.7;">
                           <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                        </button>
                    </div>
                `).join('');

                slotsHtml = `
                    <div class="gbs-slots-list" style="display:flex; flex-direction:column; gap:10px;">
                        ${renderedSlots}
                    </div>
                `;

                actionsHtml = `
                    <button type="button" class="gbs-icon-btn add btn-action-add-slot" data-day="${dayIdx}" title="Add another slot" style="color:#3b82f6;">
                        <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                    </button>
                    ${renderCopyButton(dayIdx)}
                `;
            }

            return `
                <div class="gbs-schedule-row" id="gbs-sched-row-${dayIdx}" style="display:flex; align-items:flex-start; gap:20px; padding:15px 0; border-bottom:1px dashed #e5e7eb;">
                    <div class="gbs-day-circle" style="margin-top:2px;">${letter}</div>
                    
                    <div style="flex:1;">
                        ${slotsHtml}
                    </div>

                    <div class="gbs-row-actions" style="display:flex; align-items:center; gap:5px; height:42px;"> <!-- Align with first slot input height -->
                        ${actionsHtml}
                    </div>
                </div>
            `;
        }).join('');
    }

    // --- Custom Time Picker Logic ---
    let activeInput = null;
    let pickerEl = null;

    function initTimePicker() {
        if (document.getElementById('gbs-time-picker')) return;

        pickerEl = document.createElement('div');
        pickerEl.id = 'gbs-time-picker';
        pickerEl.className = 'gbs-picker-dropdown';
        // Use fixed to escape any overflow:hidden containers in WP Admin
        pickerEl.style.position = 'fixed';
        pickerEl.style.display = 'none';

        // Hours Col
        let hoursHtml = '<div class="gbs-picker-header">Hour</div>';
        for (let i = 0; i < 24; i++) {
            const h = i.toString().padStart(2, '0');
            hoursHtml += `<div class="gbs-picker-item picker-hour" data-val="${h}">${h}</div>`;
        }

        // Minutes Col
        let minsHtml = '<div class="gbs-picker-header">Min</div>';
        for (let i = 0; i < 60; i += 15) {
            const m = i.toString().padStart(2, '0');
            minsHtml += `<div class="gbs-picker-item picker-min" data-val="${m}">${m}</div>`;
        }

        pickerEl.innerHTML = `
            <div class="gbs-picker-col" id="gbs-picker-hours">${hoursHtml}</div>
            <div style="width:1px; background:#e5e7eb;"></div>
            <div class="gbs-picker-col" id="gbs-picker-mins">${minsHtml}</div>
        `;
        document.body.appendChild(pickerEl);

        // Global Click Listener
        document.addEventListener('click', function (e) {
            // 1. Open Picker
            if (e.target.classList.contains('gbs-time-input')) {
                activeInput = e.target;
                const rect = activeInput.getBoundingClientRect();

                // Position Fixed relative to viewport
                pickerEl.style.top = (rect.bottom + 5) + 'px';
                pickerEl.style.left = rect.left + 'px';
                pickerEl.style.display = 'flex';

                // Highlight current selection
                const [h, m] = (activeInput.value || '09:00').split(':');
                document.querySelectorAll('.picker-hour').forEach(el => el.classList.toggle('selected', el.dataset.val === h));
                document.querySelectorAll('.picker-min').forEach(el => el.classList.toggle('selected', el.dataset.val === m));

                // Auto-scroll to selection
                const selectedHour = pickerEl.querySelector('.picker-hour.selected');
                if (selectedHour) selectedHour.scrollIntoView({ block: 'center' });

                e.stopPropagation(); // Prevent immediate close
            }

            // 2. Select Time
            else if (e.target.classList.contains('gbs-picker-item')) {
                if (!activeInput) return;

                let currentVal = activeInput.value || '09:00';
                // Handle partial times if user cleared input
                if (currentVal.indexOf(':') === -1) currentVal = '09:00';

                let [h, m] = currentVal.split(':');

                if (e.target.classList.contains('picker-hour')) {
                    h = e.target.dataset.val;
                } else {
                    m = e.target.dataset.val;
                    // Close on minute selection for better UX
                    pickerEl.style.display = 'none';
                }

                activeInput.value = `${h}:${m}`;
                activeInput.dispatchEvent(new Event('change'));

                // Update highlights
                document.querySelectorAll('.picker-hour').forEach(el => el.classList.toggle('selected', el.dataset.val === h));
                document.querySelectorAll('.picker-min').forEach(el => el.classList.toggle('selected', el.dataset.val === m));

                // If hour selected, maybe don't close? Let's keep it open until minute or outside click
                e.stopPropagation();
            }

            // 3. Close Picker (Click Outside)
            else if (pickerEl && pickerEl.style.display === 'flex' && !pickerEl.contains(e.target)) {
                pickerEl.style.display = 'none';
                activeInput = null;
            }
        });
    }

    // Call init immediately
    initTimePicker();

    function renderCopyButton(idx) {
        return `
            <button type="button" class="gbs-icon-btn btn-copy-day" data-idx="${idx}" title="Copy to all days">
               <svg viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg> 
            </button>
        `;
    }

    function renderHolidays() {
        // Legacy support (if table still exists)
        const tbody = document.getElementById('table-holidays-body');
        if (tbody) tbody.innerHTML = '';

        // New Container
        const container = document.getElementById('gbs-overrides-list');
        if (!container) return;

        if (holidays.length === 0) {
            container.innerHTML = `<div style="text-align:center; padding:2rem; color:#9ca3af; border:1px dashed #e5e7eb; border-radius:12px;">No overrides configured</div>`;
            return;
        }

        // Group by Date
        const grouped = {};
        holidays.forEach(h => {
            if (!grouped[h.date]) grouped[h.date] = [];
            grouped[h.date].push(h);
        });

        // Sort Dates
        const dates = Object.keys(grouped).sort();

        // Group by Year for headers
        const byYear = {};
        dates.forEach(d => {
            const y = d.split('-')[0];
            if (!byYear[y]) byYear[y] = [];
            byYear[y].push(d);
        });

        let html = '';

        Object.keys(byYear).sort().forEach(year => {
            html += `<h4 style="margin: 1.5rem 0 0.5rem 0; font-size: 1rem; color: #374151;">${year}</h4>`;

            byYear[year].forEach(dateStr => {
                const items = grouped[dateStr];
                // Determine display content
                // If any item is closed
                const isClosed = items.some(i => i.is_closed == 1);

                // Format Date: "Jan 7"
                const dObj = new Date(dateStr);
                const dateDisplay = dObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });

                let timeDisplay = '';
                if (isClosed) {
                    timeDisplay = '<span style="color:#ef4444; font-weight:500;">Closed</span>';
                } else {
                    // Sort items by start time
                    items.sort((a, b) => (a.start_time || '').localeCompare(b.start_time || ''));

                    const times = items.map(i => {
                        if (!i.start_time || !i.end_time) return '';
                        // Convert 14:00:00 to 2pm
                        const fmt = (t) => {
                            const [h, m] = t.split(':');
                            const hh = parseInt(h);
                            const suffix = hh >= 12 ? 'pm' : 'am';
                            const h12 = hh % 12 || 12;
                            return `${h12}:${m}${suffix}`;
                        };
                        return `${fmt(i.start_time)} - ${fmt(i.end_time)}`;
                    }).filter(Boolean);

                    timeDisplay = times.join('<br>');
                }

                // Collect all IDs for deletion
                const ids = items.map(i => i.id).join(',');

                html += `
                <div class="gbs-override-card" style="background:#f9fafb; border-radius:12px; padding:16px; margin-bottom:8px; display:flex; align-items:center; justify-content:space-between;">
                    <div style="font-weight:700; color:#1f2937; min-width:80px;">${dateDisplay}</div>
                    <div style="flex:1; padding:0 16px; color:#4b5563; font-size:0.95rem; line-height:1.4;">${timeDisplay}</div>
                    <button class="gbs-btn-icon-red-circle btn-delete-date-override" data-ids="${ids}" title="Remove">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"></path></svg>
                    </button>
                </div>
                `;
            });
        });

        container.innerHTML = html;

        // Attach listeners (simplest way here or use delegation on container)
        container.querySelectorAll('.btn-delete-date-override').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (!confirm('Remove overrides for this date?')) return;
                const idList = btn.dataset.ids.split(',');
                // Delete sequentially
                Promise.all(idList.map(id => fetch(root + 'holidays?id=' + id, { method: 'DELETE', headers }).then(r => r.json())))
                    .then(() => {
                        showToast('Removed');
                        fetchAll();
                    });
            });
        });
    }

    // --- Forms ---
    const fService = document.getElementById('form-add-service');
    if (fService) fService.addEventListener('submit', function (e) {
        e.preventDefault();
        const body = {
            name: document.getElementById('new-service-name').value,
            type: document.getElementById('new-service-type').value,
            duration_minutes: document.getElementById('new-service-duration').value,
            price: document.getElementById('new-service-price').value
        };
        postData('services', body, fetchAll);
        this.reset();
    });



    // --- New Staff Modal Logic ---
    const btnAddStaffModal = document.getElementById('btn-add-staff-modal');
    const modalAddStaff = document.getElementById('gbs-modal-add-staff');
    const btnCloseAddStaff = document.getElementById('btn-close-add-staff');
    const btnCancelAddStaff = document.getElementById('btn-cancel-add-staff');
    const fAddStaffModal = document.getElementById('form-add-staff-modal');

    // Open
    // Open (Delegation)
    // --- Wizard Logic ---
    const totalSteps = 5;
    // let wizScheduleState = []; // State for Step 4 - moved to global
    // let wizDaysOff = []; // State for Step 5 ['YYYY-MM-DD'] - moved to global
    // let currentOffYear = new Date().getFullYear(); - moved to global


    // Open (Delegation)
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('#btn-add-staff-modal');
        if (btn) {
            e.preventDefault();
            wizEditingId = null; // Reset for new staff
            document.getElementById('gbs-wizard-title').innerText = 'Add New Staff Member';
            document.getElementById('btn-wiz-finish').innerText = 'Finish';
            // Clear inputs:
            const inps = ['wiz-staff-name', 'wiz-staff-email', 'wiz-staff-phone', 'wiz-staff-info'];
            inps.forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
            const lim = document.getElementById('wiz-staff-limit-hours'); if (lim) lim.value = '0';

            // Re-run loadWizardData to reset defaults
            loadWizardData();
            openWizard();
        }
    });

    // Close
    document.addEventListener('click', (e) => {
        if (e.target.closest('#btn-close-add-staff')) {
            document.getElementById('gbs-modal-add-staff').style.display = 'none';
        }
    });

    function openWizard() {
        const modal = document.getElementById('gbs-modal-add-staff');
        if (!modal) return;

        modal.style.display = 'flex';
        currentWizStep = 1; // Renamed from currentStep
        showWizStep(); // Renamed from updateWizardUI
        // loadWizardData(); // Fetch services for Step 3 - already called in btn-add-staff-modal click handler
    }

    function showWizStep() { // Renamed from updateWizardUI
        // Horizontal Steps
        document.querySelectorAll('.gbs-step-h-item').forEach(el => {
            const step = parseInt(el.dataset.step);
            el.classList.remove('active', 'completed');
            if (step === currentWizStep) el.classList.add('active'); // Renamed from currentStep
            if (step < currentWizStep) el.classList.add('completed'); // Renamed from currentStep
        });

        // Panes
        document.querySelectorAll('.gbs-step-pane').forEach(el => el.classList.remove('active'));
        const activePane = document.getElementById(`step-pane-${currentWizStep}`); // Renamed from currentStep
        if (activePane) activePane.classList.add('active');

        // Buttons
        const btnPrev = document.getElementById('btn-wiz-prev');
        const btnNext = document.getElementById('btn-wiz-next');
        const btnFinish = document.getElementById('btn-wiz-finish');

        if (btnPrev) btnPrev.style.display = currentWizStep > 1 ? 'block' : 'none'; // Renamed from currentStep
        if (btnNext) btnNext.style.display = currentWizStep < totalSteps ? 'block' : 'none'; // Renamed from currentStep
        if (btnFinish) btnFinish.style.display = currentWizStep === totalSteps ? 'block' : 'none'; // Renamed from currentStep
    }

    // Navigation
    document.addEventListener('click', (e) => {
        if (e.target.closest('#btn-wiz-next')) {
            if (validateStep(currentWizStep)) { // Renamed from currentStep
                currentWizStep++; // Renamed from currentStep
                showWizStep(); // Renamed from updateWizardUI
            }
        }
        if (e.target.closest('#btn-wiz-prev')) {
            if (currentWizStep > 1) { // Renamed from currentStep
                currentWizStep--; // Renamed from currentStep
                showWizStep(); // Renamed from updateWizardUI
            }
        }
        if (e.target.closest('#btn-wiz-finish')) {
            submitWizard();
        }
    });

    function validateStep(step) {
        if (step === 1) {
            const name = document.getElementById('wiz-staff-name').value;
            const email = document.getElementById('wiz-staff-email').value;
            if (!name || !email) {
                alert('Please fill in required fields');
                return false;
            }
        }
        return true;
    }

    // --- Step 4 Schedule Interactivity ---

    // Delegation for Schedule Actions
    const wizSchedContainer = document.getElementById('wiz-schedule-container');
    if (wizSchedContainer) {
        // Clicks
        wizSchedContainer.addEventListener('click', (e) => {
            const btnAdd = e.target.closest('[data-action="add-slot"]');
            const btnRemove = e.target.closest('[data-action="remove-slot"]');
            const btnCopy = e.target.closest('[data-action="copy-day"]');

            if (btnAdd) {
                const dayIdx = parseInt(btnAdd.dataset.day);
                // Add default slot 09:00 - 17:00
                if (!wizScheduleState[dayIdx]) return;
                wizScheduleState[dayIdx].slots.push({ start: '09:00', end: '17:00' });
                renderWizSchedule();
            }

            if (btnRemove) {
                const dayIdx = parseInt(btnRemove.dataset.day);
                const slotIdx = parseInt(btnRemove.dataset.slot);
                if (wizScheduleState[dayIdx] && wizScheduleState[dayIdx].slots) {
                    wizScheduleState[dayIdx].slots.splice(slotIdx, 1);
                    renderWizSchedule();
                }
            }

            if (btnCopy) {
                const dayIdx = parseInt(btnCopy.dataset.day);
                if (wizScheduleState[dayIdx]) {
                    const sourceSlots = JSON.parse(JSON.stringify(wizScheduleState[dayIdx].slots));
                    if (confirm('Copy this day\'s schedule to all other days?')) {
                        wizScheduleState.forEach((d, i) => {
                            if (i !== dayIdx) d.slots = JSON.parse(JSON.stringify(sourceSlots));
                        });
                        renderWizSchedule();
                    }
                }
            }
        });

        // Open Time Picker Logic
        wizSchedContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('gbs-time-input')) {
                openGbsTimePicker(e.target);
            }
        });
    }

    // Inject Styles for Time Picker
    const tpStyle = document.createElement('style');
    tpStyle.innerHTML = `
        .gbs-tp-popup { position: absolute; z-index: 10000; background: white; border: 1px solid #e5e7eb; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border-radius: 12px; padding: 12px; display: flex; gap: 0; font-family: sans-serif; width: 220px; }
        .gbs-tp-col { flex: 1; display: flex; flex-direction: column; max-height: 240px; overflow-y: auto; align-items: center; }
        .gbs-tp-col::-webkit-scrollbar { width: 4px; }
        .gbs-tp-col::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .gbs-tp-header { text-align: center; font-size: 0.75rem; font-weight: 700; color: #9ca3af; margin-bottom: 8px; text-transform: uppercase; position: sticky; top: 0; background: white; width: 100%; padding-bottom: 4px; letter-spacing: 0.5px; }
        .gbs-tp-divider { width: 1px; background: #e5e7eb; margin: 0 8px; }
        .gbs-tp-item { padding: 8px 0; width: 100%; text-align: center; cursor: pointer; border-radius: 6px; font-size: 0.95rem; color: #374151; margin-bottom: 2px; font-weight: 500; }
        .gbs-tp-item:hover { background: #f3f4f6; }
        .gbs-tp-item.active { background: #4285f4; color: white; }
    `;
    document.head.appendChild(tpStyle);

    let activeTpInput = null;
    let activeTpPopup = null;

    function openGbsTimePicker(input) {
        // Close existing
        if (activeTpPopup) activeTpPopup.remove();

        activeTpInput = input;
        const val = input.value || '09:00';
        let [h, m] = val.split(':');

        // Create Popup
        const popup = document.createElement('div');
        popup.className = 'gbs-tp-popup';
        document.body.appendChild(popup);
        activeTpPopup = popup;

        // Position
        const rect = input.getBoundingClientRect();
        popup.style.top = (rect.bottom + window.scrollY + 5) + 'px';
        popup.style.left = (rect.left + window.scrollX) + 'px';

        renderTimePickerContent(popup, h, m);

        // Click outside to close
        setTimeout(() => {
            const closeHandler = (ev) => {
                if (!popup.contains(ev.target) && ev.target !== activeTpInput) {
                    popup.remove();
                    activeTpPopup = null;
                    document.removeEventListener('click', closeHandler);
                }
            };
            document.addEventListener('click', closeHandler);
        }, 10);
    }

    function renderTimePickerContent(popup, currH, currM) {
        // Hours 00-23
        const hours = [];
        for (let i = 0; i < 24; i++) hours.push(i.toString().padStart(2, '0'));

        // Minutes 00, 15, 30, 45
        const minutes = ['00', '15', '30', '45'];

        let html = `
            <div class="gbs-tp-col">
                <div class="gbs-tp-header">Hour</div>
                ${hours.map(h => `<div class="gbs-tp-item ${h === currH ? 'active' : ''}" data-h="${h}">${h}</div>`).join('')}
            </div>
            <div class="gbs-tp-divider"></div>
            <div class="gbs-tp-col">
                <div class="gbs-tp-header">Min</div>
                ${minutes.map(m => `<div class="gbs-tp-item ${m === currM ? 'active' : ''}" data-m="${m}">${m}</div>`).join('')}
            </div>
        `;
        popup.innerHTML = html;

        // Scroll active into view
        const activeH = popup.querySelector('.gbs-tp-item[data-h].active');
        if (activeH) activeH.scrollIntoView({ block: 'center' });

        // Events
        popup.querySelectorAll('.gbs-tp-item[data-h]').forEach(el => {
            el.addEventListener('click', () => {
                currH = el.dataset.h;
                updateTimePickerValue(currH, currM);
                renderTimePickerContent(popup, currH, currM);
            });
        });
        popup.querySelectorAll('.gbs-tp-item[data-m]').forEach(el => {
            el.addEventListener('click', () => {
                currM = el.dataset.m;
                updateTimePickerValue(currH, currM);
                renderTimePickerContent(popup, currH, currM);
            });
        });
    }

    function updateTimePickerValue(h, m) {
        if (activeTpInput) {
            const newVal = `${h}:${m}`;
            activeTpInput.value = newVal;

            // Update State
            const dayIdx = parseInt(activeTpInput.dataset.day);
            const slotIdx = parseInt(activeTpInput.dataset.slot);
            const key = activeTpInput.dataset.key;

            if (wizScheduleState[dayIdx] && wizScheduleState[dayIdx].slots[slotIdx]) {
                wizScheduleState[dayIdx].slots[slotIdx][key] = newVal;
            }
        }
    }

    function renderWizSchedule() {
        const container = document.getElementById('wiz-schedule-container');
        if (!container) return;

        container.innerHTML = wizScheduleState.map((d, i) => {
            let contentHtml = '';

            if (d.slots && d.slots.length > 0) {
                // Render Slots
                contentHtml = d.slots.map((slot, sIdx) => `
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                        <div style="background:#f3f4f6; border-radius:6px; padding:8px 16px; display:flex; align-items:center; gap:10px;">
                            <input type="text" class="gbs-time-input" readonly data-day="${i}" data-slot="${sIdx}" data-key="start" value="${slot.start}" style="width:50px; border:none; background:transparent; text-align:center; padding:0; font-weight:600; color:#1f2937; font-size:0.95rem; font-family:inherit; cursor:pointer;">
                            <span style="color:#d1d5db;">-</span>
                            <input type="text" class="gbs-time-input" readonly data-day="${i}" data-slot="${sIdx}" data-key="end" value="${slot.end}" style="width:50px; border:none; background:transparent; text-align:center; padding:0; font-weight:600; color:#1f2937; font-size:0.95rem; font-family:inherit; cursor:pointer;">
                        </div>
                        <div class="gbs-icon-btn" data-action="remove-slot" data-day="${i}" data-slot="${sIdx}" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:50%; cursor:pointer; transition:background 0.2s;" title="Remove slot">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </div>
                    </div>
                `).join('');
            } else {
                contentHtml = `<span style="color:#ef4444; font-weight:500; font-size:0.95rem;">Unavailable</span>`;
            }

            return `
            <div style="display:flex; align-items:flex-start; gap:20px; padding:4px 0; border-bottom:1px dashed #e5e7eb;">
                <!-- Day Circle -->
                <div style="width:36px; height:36px; border-radius:50%; background:#1f2937; color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.85rem; flex-shrink:0; margin-top:4px;">
                    ${d.l}
                </div>
                
                <!-- Content -->
                <div style="flex:1; display:flex; flex-direction:column; justify-content:center; min-height:44px;">
                    ${contentHtml}
                </div>

                <!-- Actions -->
                <div style="display:flex; align-items:center; gap:10px; margin-left:auto; margin-top:8px;">
                    <div class="gbs-icon-btn" data-action="add-slot" data-day="${i}" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:50%; cursor:pointer; color:#2563eb; transition:background 0.2s;" title="Add Slot">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="gbs-icon-btn" data-action="copy-day" data-day="${i}" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; border-radius:50%; cursor:pointer; color:#4b5563; transition:background 0.2s;" title="Copy to all">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                        </svg>
                    </div>
                </div>
            </div>
            `;
        }).join('');
    }

    function loadWizardData() {
        // Render Services into Step 3
        const list = document.getElementById('wiz-services-list');
        if (list && services) {
            list.innerHTML = services.map(s => `
                <label class="gbs-service-card-select" style="display:flex; align-items:center; gap:12px; padding:12px 16px; border:1px solid #e5e7eb; border-radius:8px; cursor:pointer; background:white; transition:all 0.2s ease; position:relative;">
                    <input type="checkbox" class="wiz-svc-check" value="${s.id}" style="position:absolute; opacity:0; width:0; height:0;">
                    
                    <div class="gbs-svc-icon-box" style="width:40px; height:40px; border-radius:8px; background:#f3f4f6; display:flex; align-items:center; justify-content:center; color:#6b7280; flex-shrink:0; transition:all 0.2s;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                    </div>
                    
                    <div style="flex:1;">
                        <div style="font-weight:600; color:#374151; font-size:0.95rem;">${s.name}</div>
                    </div>

                    <div class="gbs-check-indicator" style="width:22px; height:22px; border:2px solid #d1d5db; border-radius:6px; display:flex; align-items:center; justify-content:center; transition:all 0.2s; background:white;">
                        <svg class="check-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                </label>
            `).join('');

            // Add change listeners for UI state
            list.querySelectorAll('.wiz-svc-check').forEach(chk => {
                chk.addEventListener('change', (e) => {
                    const card = e.target.closest('label');
                    const indicator = card.querySelector('.gbs-check-indicator');
                    const icon = indicator.querySelector('.check-icon');
                    const iconBox = card.querySelector('.gbs-svc-icon-box');
                    const title = card.querySelector('div[style*="font-weight:600"]');

                    if (e.target.checked) {
                        card.style.borderColor = '#2563eb';
                        card.style.background = '#eff6ff';
                        indicator.style.borderColor = '#2563eb';
                        indicator.style.background = '#2563eb';
                        icon.style.display = 'block';
                        iconBox.style.background = '#dbeafe';
                        iconBox.style.color = '#2563eb';
                        title.style.color = '#1f2937';
                    } else {
                        card.style.borderColor = '#e5e7eb';
                        card.style.background = 'white';
                        indicator.style.borderColor = '#d1d5db';
                        indicator.style.background = 'white';
                        icon.style.display = 'none';
                        iconBox.style.background = '#f3f4f6';
                        iconBox.style.color = '#6b7280';
                        title.style.color = '#374151';
                    }
                });
            });
        }

        // Render Schedule into Step 4 (Init State)

        // Default: Mon-Fri Open, Sat-Sun Closed (or match screenshot pattern if desired)
        const defaultDays = [
            { l: 'S', dayIndex: 0, slots: [] }, // Sun (Closed)
            { l: 'M', dayIndex: 1, slots: [{ start: '09:00', end: '17:00' }] },
            { l: 'T', dayIndex: 2, slots: [{ start: '09:00', end: '17:00' }] },
            { l: 'W', dayIndex: 3, slots: [{ start: '09:00', end: '17:00' }] },
            { l: 'T', dayIndex: 4, slots: [{ start: '09:00', end: '17:00' }] },
            { l: 'F', dayIndex: 5, slots: [{ start: '09:00', end: '17:00' }] },
            { l: 'S', dayIndex: 6, slots: [{ start: '09:00', end: '17:00' }] } // Sat (Open)
        ];

        // Reset state or set defaults
        wizScheduleState = JSON.parse(JSON.stringify(defaultDays));
        renderWizSchedule();

        // Step 5: Render Days Off Calendar initially
        currentOffYear = new Date().getFullYear();
        wizDaysOff = [];
        renderDaysOffCalendar();
    }

    // Step 5 Logic
    function renderDaysOffCalendar() {
        const container = document.getElementById('wiz-days-off-calendar');
        const yearDisplay = document.getElementById('wiz-year-display');
        if (!container || !yearDisplay) return;

        yearDisplay.innerText = currentOffYear;

        const months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        container.innerHTML = months.map((mName, mIdx) => {
            // Get days in month
            const daysInMonth = new Date(currentOffYear, mIdx + 1, 0).getDate();
            const firstDay = new Date(currentOffYear, mIdx, 1).getDay(); // 0=Sun, 1=Mon...
            // Adjust for Mon start if needed. Let's assume Mon start for now as per scheduler
            // Actually screenshot shows Mon start.
            // Adjust for Sunday start
            let startOffset = firstDay;

            let daysHtml = '';
            // Padding
            for (let i = 0; i < startOffset; i++) {
                daysHtml += `<div></div>`;
            }
            // Days
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${currentOffYear}-${String(mIdx + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const isOff = wizDaysOff.includes(dateStr);
                const bg = isOff ? '#2563eb' : 'transparent';
                const color = isOff ? 'white' : '#374151';

                daysHtml += `
                    <div class="wiz-cal-day" data-date="${dateStr}" style="
                        width:26px; height:26px; display:flex; align-items:center; justify-content:center;
                        border-radius:50%; font-size:0.8rem; cursor:pointer; 
                        background:${bg}; color:${color}; font-weight:${isOff ? '600' : '400'};
                    ">${d}</div>
                `;
            }

            return `
                <div style="border-radius:8px; padding:8px; background:white;">
                    <div style="text-align:center; font-weight:700; margin-bottom:8px; color:#1f2937; font-size:0.9rem;">${mName}</div>
                    <div style="display:grid; grid-template-columns:repeat(7, 1fr); gap:2px; text-align:center; margin-bottom:4px;">
                        <span style="font-size:0.7rem; color:#9ca3af;">S</span>
                        <span style="font-size:0.7rem; color:#9ca3af;">M</span>
                        <span style="font-size:0.7rem; color:#9ca3af;">T</span>
                        <span style="font-size:0.7rem; color:#9ca3af;">W</span>
                        <span style="font-size:0.7rem; color:#9ca3af;">T</span>
                        <span style="font-size:0.7rem; color:#9ca3af;">F</span>
                        <span style="font-size:0.7rem; color:#9ca3af;">S</span>
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(7, 1fr); gap:2px; row-gap:2px;">
                        ${daysHtml}
                    </div>
                </div>
            `;
        }).join('');

        // Event Listeners for Days
        container.querySelectorAll('.wiz-cal-day').forEach(el => {
            el.addEventListener('click', (e) => {
                const date = e.target.dataset.date;
                if (wizDaysOff.includes(date)) {
                    wizDaysOff = wizDaysOff.filter(d => d !== date);
                } else {
                    wizDaysOff.push(date);
                }
                renderDaysOffCalendar(); // Re-render to update UI
            });
        });
    }

    // Step 5 Navigation Listeners (Delegation or Direct)
    document.addEventListener('click', (e) => {
        if (e.target.closest('#wiz-year-prev')) {
            currentOffYear--;
            renderDaysOffCalendar();
        }
        if (e.target.closest('#wiz-year-next')) {
            currentOffYear++;
            renderDaysOffCalendar();
        }
    });

    function submitWizard() {
        const name = document.getElementById('wiz-staff-name').value;
        const email = document.getElementById('wiz-staff-email').value;
        const wpUserEl = document.getElementById('wiz-staff-wp-user');
        const wpUser = wpUserEl ? wpUserEl.value : 'none';
        const phone = document.getElementById('wiz-staff-phone').value;
        const info = document.getElementById('wiz-staff-info').value;

        // Collect checked services
        const svcIds = Array.from(document.querySelectorAll('.wiz-svc-check:checked')).map(cb => cb.value);

        const btn = document.getElementById('btn-wiz-finish');
        btn.textContent = 'Saving...';

        // Sanitize schedule to ensure dayIndex is correct (0-6)
        const cleanSchedule = wizScheduleState.map((d, i) => ({
            ...d,
            dayIndex: i,
            day: i
        }));

        postData('staff', {
            name: name,
            email: email,
            wp_user_id: wpUser === 'new' ? 0 : (wpUser === 'none' ? 0 : wpUser),
            phone: phone,
            info: info,
            limit_hours: document.getElementById('wiz-staff-limit-hours').value,
            timezone: document.getElementById('wiz-staff-timezone-value') ? document.getElementById('wiz-staff-timezone-value').value : '',
            service_ids: svcIds,
            schedule: cleanSchedule,
            days_off: wizDaysOff,
            google_auth_temp_id: window.googleAuthTempId,
            id: wizEditingId // Include ID if editing
        }, () => {
            showToast(wizEditingId ? 'Staff updated successfully' : 'Staff added successfully');
            document.getElementById('gbs-modal-add-staff').style.display = 'none';
            fetchAll().then(() => renderStaff()); // Reload List
            // Reset form
            document.getElementById('form-add-staff-wizard').reset();
        });
    }

    // --- Custom Dropdown Logic (Removed) ---
    document.addEventListener('click', (e) => {
        // Toggle
        const trigger = e.target.closest('.gbs-dropdown-trigger');
        if (trigger) {
            const container = trigger.closest('.gbs-dropdown-container');
            container.classList.toggle('open');
            return;
        }

        // Select
        const item = e.target.closest('.gbs-dropdown-item');
        if (item) {
            const container = item.closest('.gbs-dropdown-container');
            const textSpan = container.querySelector('.gbs-selected-text');
            const hiddenInput = container.querySelector('input[type="hidden"]');

            // Update UI
            container.querySelectorAll('.gbs-dropdown-item').forEach(i => i.classList.remove('selected'));
            item.classList.add('selected');
            if (textSpan) textSpan.textContent = item.textContent.trim();
            if (hiddenInput) hiddenInput.value = item.dataset.value;

            container.classList.remove('open');
            return;
        }

        // Close on outside click
        if (!e.target.closest('.gbs-dropdown-container')) {
            document.querySelectorAll('.gbs-dropdown-container.open').forEach(c => c.classList.remove('open'));
        }
    });

    const btnHoliday = document.getElementById('btn-add-holiday');
    if (btnHoliday) btnHoliday.addEventListener('click', function () {
        postData('holidays', {
            date: document.getElementById('new-holiday-date').value,
            description: document.getElementById('new-holiday-desc').value
        }, fetchAll);
        document.getElementById('new-holiday-date').value = '';
        document.getElementById('new-holiday-desc').value = '';
    });

    const btnSave = document.getElementById('btn-save-settings');
    if (btnSave) btnSave.addEventListener('click', function () {
        // Reconstruct Availability from DOM to support multi-intervals
        const newAvailability = [];

        // Loop 0-6 days
        for (let d = 0; d < 7; d++) {
            const startInputs = document.querySelectorAll(`.avail-start[data-day="${d}"]`);
            const endInputs = document.querySelectorAll(`.avail-end[data-day="${d}"]`);

            if (startInputs.length > 0) {
                // Open day with one or more slots
                startInputs.forEach((inp, idx) => {
                    const start = inp.value;
                    const end = endInputs[idx] ? endInputs[idx].value : '17:00';
                    newAvailability.push({
                        schedule_id: activeScheduleId, // Ensure we attach ID
                        day_of_week: d.toString(),
                        start_time: start,
                        end_time: end,
                        is_closed: '0'
                    });
                });
            } else {
                // Closed day
                newAvailability.push({
                    schedule_id: activeScheduleId,
                    day_of_week: d.toString(),
                    start_time: '00:00',
                    end_time: '00:00',
                    is_closed: '1'
                });
            }
        }

        // Update global state
        availability = newAvailability;

        postData('settings', {
            availability,
            schedule_id: activeScheduleId
        }, () => showToast('Settings Saved'));
    });

    // --- Deletions & Interactions (Delegation) ---
    document.addEventListener('click', function (e) {
        // Standard Deletes
        if (e.target.classList.contains('btn-delete-service')) deleteItem('services', e.target.dataset.id);

        if (e.target.closest('.btn-delete-staff')) deleteItem('staff', e.target.closest('.btn-delete-staff').dataset.id);
        if (e.target.closest('.btn-edit-staff')) openEditStaffModal(e.target.closest('.btn-edit-staff').dataset.id);
        if (e.target.classList.contains('btn-delete-holiday')) deleteItem('holidays', e.target.dataset.id);

        function openEditStaffModal(id) {
            wizEditingId = id;
            const s = staff.find(x => x.id == id);
            if (!s) return;

            // Populate Wizard inputs
            const titleEl = document.getElementById('gbs-wizard-title');
            if (titleEl) titleEl.innerText = 'Edit Staff Member';

            const btnFinish = document.getElementById('btn-wiz-finish');
            if (btnFinish) btnFinish.innerText = 'Update Staff';

            const nameEl = document.getElementById('wiz-staff-name');
            if (nameEl) nameEl.value = s.name;

            const emailEl = document.getElementById('wiz-staff-email');
            if (emailEl) emailEl.value = s.email;

            const phoneEl = document.getElementById('wiz-staff-phone');
            if (phoneEl) phoneEl.value = s.phone || '';

            const infoEl = document.getElementById('wiz-staff-info');
            if (infoEl) infoEl.value = s.info || '';

            // Handle Schedule & Services - simplified for mock
            openWizard();
        }

        // Modal Openers

        if (e.target.classList.contains('btn-assign-services')) openModal('services', e.target.dataset.id);

        // List View: Action Delegation
        // 1. Open Today (was Closed)
        const btnOpen = e.target.closest('.btn-action-open');
        if (btnOpen) {
            const d = parseInt(btnOpen.dataset.day);
            // Remove any closed rows for this day
            availability = availability.filter(r => parseInt(r.day_of_week) !== d);
            // Add one default slot
            availability.push({
                schedule_id: activeScheduleId,
                day_of_week: d.toString(),
                start_time: '09:00',
                end_time: '17:00',
                is_closed: '0'
            });
            renderAvailability();
        }

        // 2. Add Slot
        const btnAddSlot = e.target.closest('.btn-action-add-slot');
        if (btnAddSlot) {
            const d = parseInt(btnAddSlot.dataset.day);
            availability.push({
                schedule_id: activeScheduleId,
                day_of_week: d.toString(),
                start_time: '09:00',
                end_time: '17:00',
                is_closed: '0'
            });
            renderAvailability();
        }

        // 3. Remove Slot
        const btnRemoveSlot = e.target.closest('.btn-action-remove-slot');
        if (btnRemoveSlot) {
            const refIdx = parseInt(btnRemoveSlot.dataset.refIdx);
            if (refIdx >= 0 && refIdx < availability.length) {
                availability.splice(refIdx, 1);
            }
            renderAvailability();
        }

        // Copy Schedule
        const copyBtn = e.target.closest('.btn-copy-day');
        if (copyBtn) {
            const idx = parseInt(copyBtn.dataset.idx);
            const source = availability[idx];

            if (confirm('Apply this schedule to all other days?')) {
                availability.forEach((day, i) => {
                    if (i !== idx) {
                        day.start_time = source.start_time;
                        day.end_time = source.end_time;
                        day.is_closed = source.is_closed;
                    }
                });
                renderAvailability();
            }
        }
    });

    // --- Modals ---
    const modal = document.getElementById('gbs-modal-overlay');
    const modalTitle = document.getElementById('modal-title');
    const modalList = document.getElementById('modal-list');
    let modalMode = '';

    function openModal(mode, id) {
        modalMode = mode;
        editingItem = staff.find(s => s.id == id);

        modalTitle.innerText = 'Assign Services to ' + editingItem.name;
        modal.style.display = 'flex';
        renderModalList();
    }

    function renderModalList() {
        let html = '';
        // Services Logic Only
        const assigned = (editingItem.service_ids || []).map(String);
        services.forEach(s => {
            const checked = assigned.includes(String(s.id)) ? 'checked' : '';
            html += `<div><label><input type="checkbox" class="modal-check" value="${s.id}" ${checked}> ${s.name}</label></div>`;
        });
        modalList.innerHTML = html;
    }

    const modalCancel = document.getElementById('btn-modal-cancel');
    if (modalCancel) modalCancel.addEventListener('click', () => modal.style.display = 'none');

    const modalSave = document.getElementById('btn-modal-save');
    if (modalSave) modalSave.addEventListener('click', function () {
        const checked = Array.from(document.querySelectorAll('.modal-check:checked')).map(c => c.value);

        postData('assign/staff-services', { staff_id: editingItem.id, service_ids: checked }, () => {
            modal.style.display = 'none';
            fetchAll();
        });
    });

    // --- General Settings Logic ---
    const fGeneralSettings = document.getElementById('form-general-settings');
    if (fGeneralSettings) {
        // Fetch current settings
        fetch(root + 'general-settings', { headers })
            .then(r => r.json())
            .then(data => {
                if (data.business_name) document.getElementById('gbs-biz-name').value = data.business_name;
                if (data.business_email) document.getElementById('gbs-biz-email').value = data.business_email;
                if (data.business_website) document.getElementById('gbs-biz-website').value = data.business_website;
                if (data.business_phone) document.getElementById('gbs-biz-phone').value = data.business_phone;
                if (data.business_color) {
                    const c = data.business_color;
                    const input = document.getElementById('gbs-biz-color');
                    const text = document.getElementById('gbs-color-text');
                    const swatch = document.getElementById('gbs-color-swatch');

                    if (input) input.value = c;
                    if (text) text.value = c.replace('#', '');
                    if (swatch) swatch.style.background = c;
                }
            });

        // Color Picker Interaction
        // Color Picker Interaction
        const colorInput = document.getElementById('gbs-biz-color');
        const colorText = document.getElementById('gbs-color-text');
        const colorSwatch = document.getElementById('gbs-color-swatch');

        if (colorInput && colorText && colorSwatch) {
            // Native Picker Change
            colorInput.addEventListener('input', (e) => {
                const val = e.target.value;
                colorText.value = val.replace('#', '');
                colorSwatch.style.background = val;
            });

            // Text Input Change
            colorText.addEventListener('input', (e) => {
                let val = e.target.value;
                if (!val.startsWith('#')) val = '#' + val;

                // Validate Hex
                if (/^#[0-9A-F]{6}$/i.test(val)) {
                    colorInput.value = val;
                    colorSwatch.style.background = val;
                }
            });
        }

        // Presets
        document.querySelectorAll('.gbs-preset-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const val = btn.dataset.color;
                if (colorInput) {
                    colorInput.value = val;
                    colorInput.dispatchEvent(new Event('input')); // Trigger sync
                }
            });
        });

        // Save Functionality
        fGeneralSettings.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-save-general-settings');
            const originalText = btn.textContent;
            btn.textContent = 'Saving...';
            btn.disabled = true;

            const body = {
                business_name: document.getElementById('gbs-biz-name').value,
                business_email: document.getElementById('gbs-biz-email').value,
                business_website: document.getElementById('gbs-biz-website').value,
                business_phone: document.getElementById('gbs-biz-phone').value,
                business_color: document.getElementById('gbs-biz-color').value
            };

            fetch(root + 'general-settings', {
                method: 'POST',
                headers: { ...headers, 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        showToast('Settings saved successfully');
                    } else {
                        showToast('Error saving settings');
                    }
                })
                .finally(() => {
                    btn.textContent = originalText;
                    btn.disabled = false;
                });
        });
    }

    // --- Helpers ---
    function postData(endpoint, body, cb) {
        fetch(root + endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': gbsAdminData.nonce },
            body: JSON.stringify(body)
        }).then(r => r.json()).then(res => {
            if (res.success) {
                showToast('Action Successful');
                if (cb) cb();
            }
        });
    }

    function postData(endpoint, data, callback) {
        fetch(root + endpoint, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(data)
        })
            .then(r => r.json())
            .then(res => {
                if (res.success || res.id) {
                    if (callback) callback(res);
                } else {
                    console.error('Save failed', res);
                    showToast('Error saving data');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Network error');
            });
    }

    function deleteItem(endpoint, id) {
        if (!confirm('Are you sure?')) return;
        fetch(root + endpoint + '?id=' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': gbsAdminData.nonce },
        }).then(() => {
            showToast('Item Deleted');
            fetchAll();
        });
    }

    function showToast(message) {
        let toast = document.getElementById('gbs-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'gbs-toast';
            toast.style.cssText = `
                position: fixed; bottom: 20px; right: 20px;
                background: #10b981; color: white; padding: 12px 24px;
                border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                font-family: inherit; z-index: 9999; transform: translateY(100px);
                transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                font-weight: 500;
            `;
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        requestAnimationFrame(() => toast.style.transform = 'translateY(0)');
        setTimeout(() => toast.style.transform = 'translateY(100px)', 3000);
    }

    // --- Date Override Modal System ---
    let overrideModalState = {
        currentMonth: new Date(),
        selectedDates: new Set(),
        modal: null
    };

    // LEGACY OVERRIDE LOGIC REMOVED

    // LEGACY WEEKLY MODAL REMOVED

    // Check for Google Auth redirect params
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success') && urlParams.get('success') === 'google_connected') {
        showToast('Google Calendar connected successfully!');
        // Optional: Clean URL
        const newUrl = window.location.href.replace('&success=google_connected', '').replace(/#.*$/, '');
        window.history.replaceState({ path: newUrl }, '', newUrl + '#sub-cal-settings');
    } else if (urlParams.has('error') && (urlParams.get('error').includes('google') || urlParams.get('error').includes('token'))) {
        let msg = urlParams.get('msg') || urlParams.get('err') || 'Unknown error';
        msg = decodeURIComponent(msg).replace(/\+/g, ' ');
        alert('Google Connection Failed: ' + msg);
    }

    // --- Connect Calendar Modal Logic ---
    function openConnectCalendarModal() {
        const modalConnect = document.getElementById('modal-connect-calendar-overlay');
        if (modalConnect) {
            modalConnect.style.display = 'flex';
        }
    }

    function initConnectCalendarModal() {
        const btnConnect = document.getElementById('btn-connect-calendar');
        const modalConnect = document.getElementById('modal-connect-calendar-overlay');
        const btnClose = document.getElementById('btn-close-connect-cal');

        if (btnConnect && modalConnect) {
            btnConnect.addEventListener('click', (e) => {
                e.preventDefault();
                openConnectCalendarModal();
            });
        }

        if (btnClose && modalConnect) {
            btnClose.addEventListener('click', (e) => {
                e.preventDefault();
                modalConnect.style.display = 'none';
            });
        }

        // Close on overlay click
        if (modalConnect) {
            modalConnect.addEventListener('click', (e) => {
                if (e.target === modalConnect) {
                    modalConnect.style.display = 'none';
                }
            });
        }
        // Google Provider Click
        const btnGoogle = document.getElementById('btn-provider-google');
        if (btnGoogle) {
            btnGoogle.addEventListener('click', (e) => {
                e.preventDefault();
                // Fetch Auth URL
                fetch(root + 'auth/google/connect', {
                    method: 'GET',
                    headers: headers
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.url) {
                            window.location.href = res.url;
                        } else if (res.message) {
                            alert('Error: ' + res.message);
                        } else {
                            alert('Could not initiate Google Auth.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Network error initiating Google Auth.');
                    });
            });
        }
    }

    // --- Calendar Settings Logic ---
    function initCalendarSettingsLogic() {
        const listContainer = document.getElementById('gbs-connected-calendars-list');
        if (!listContainer) return; // Not on this tab or not rendered yet

        const pCalendars = fetch(root + 'google/calendars', { method: 'GET', headers: headers })
            .then(async r => {
                if (r.status === 401) return []; // Not connected
                const text = await r.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('JSON Parse Error:', text);
                    throw new Error('Invalid server response');
                }
            });

        const pSettings = fetch(root + 'google/settings', { method: 'GET', headers: headers })
            .then(r => r.json())
            .catch(e => ({}));

        Promise.all([pCalendars, pSettings])
            .then(([res, settings]) => {
                // If it's an array, render
                if (Array.isArray(res)) {
                    renderConnectedCalendars(res, settings);
                }
                // If it's a WP_Error object that slipped through as 200 (rare) or other structure
                else if (res && (res.code === 'auth_error' || res.code === 'no_token')) {
                    renderConnectedCalendars([], settings);
                }
                else {
                    // Unknown error structure
                    console.error('Unknown calendar response:', res);
                    listContainer.innerHTML = '<p style="color:#ef4444; padding:10px 0;">Error loading calendars.</p>';
                }
            })
            .catch(err => {
                console.error(err);
                if (err.message === 'Invalid server response') {
                    listContainer.innerHTML = '<p style="color:#ef4444; padding:10px 0;">Server Error. Check console.</p>';
                } else {
                    listContainer.innerHTML = `<p style="color:#ef4444; padding:10px 0;">${err.message}</p>`;
                }
            });
    }

    function renderConnectedCalendars(calendars, settings) {
        const listContainer = document.getElementById('gbs-connected-calendars-list');
        // Clear container first to prevent duplicates
        listContainer.innerHTML = '';
        listContainer.style.background = 'transparent';
        listContainer.style.border = 'none';
        listContainer.style.padding = '0';

        // Group by account_email. 
        const groups = {};
        calendars.forEach(c => {
            let acct = c.account_email;
            if (!acct && c.primary) acct = c.id;
            if (!acct) acct = 'Connected Account';
            if (!groups[acct]) groups[acct] = [];
            groups[acct].push(c);
        });

        const accountEmails = Object.keys(groups);

        if (calendars.length === 0) {
            listContainer.innerHTML = `
                <div class="gbs-cal-settings-empty">
                    <div class="gbs-cal-empty-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <h3 class="gbs-cal-empty-title">No calendars connected</h3>
                    <p class="gbs-cal-empty-desc">Connect your Google Calendar to automatically sync bookings and prevent conflicts.</p>
                    <button class="gbs-btn-primary" id="btn-trigger-connect-cal-js" style="padding: 10px 20px;">
                        Connect Google Calendar
                    </button>
                </div>
            `;

            // Re-bind click
            const btn = document.getElementById('btn-trigger-connect-cal-js');
            if (btn) {
                btn.addEventListener('click', () => {
                    const modal = document.getElementById('modal-connect-calendar-overlay');
                    if (modal) modal.style.display = 'flex';
                });
            }
            return;
        }

        // --- SECTION 1: Calendars to check for conflicts ---
        const conflictsHeader = document.createElement('div');
        conflictsHeader.className = 'gbs-section-header';
        conflictsHeader.innerHTML = `
            <h3 class="gbs-section-title">Calendars to check for conflicts</h3>
            <button class="gbs-btn-connect-sm" id="btn-add-cal-account">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Connect calendar account
            </button>
        `;
        listContainer.appendChild(conflictsHeader);

        // Bind button
        conflictsHeader.querySelector('#btn-add-cal-account').onclick = (e) => {
            e.preventDefault();
            const modal = document.getElementById('modal-connect-calendar-overlay');
            if (modal) modal.style.display = 'flex';
        };

        const conflictsDescription = document.createElement('p');
        conflictsDescription.innerText = 'These calendars will be used to prevent double bookings';
        conflictsDescription.style.cssText = 'font-size:0.875rem; color:#6b7280; margin-top:-8px; margin-bottom:16px;';
        listContainer.appendChild(conflictsDescription);

        const conflictsCard = document.createElement('div');
        conflictsCard.className = 'gbs-cal-settings-card'; // New Card Container
        conflictsCard.style.padding = '0'; // Override padding for list style
        conflictsCard.style.overflow = 'hidden';
        conflictsCard.style.width = '50%'; // REQUESTED CHANGE

        accountEmails.forEach((email, index) => {
            const row = document.createElement('div');
            // Use new class but also ensure layout
            row.style.cssText = 'display:flex; justify-content:space-between; align-items:center; padding: 20px; background: #ffffff;';
            if (index < accountEmails.length - 1) {
                row.style.borderBottom = '1px solid #e5e7eb';
            }

            const accCalendars = groups[email];

            row.innerHTML = `
                <div class="gbs-cal-account-info">
                     <div class="gbs-cal-provider-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                             <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                             <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                             <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.24l-.19-.6z" fill="#FBBC05"/>
                             <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                             <text x="12" y="16" text-anchor="middle" font-family="sans-serif" font-weight="700" font-size="10" fill="#1a73e8">31</text>
                        </svg>
                    </div>
                    <div class="gbs-cal-meta">
                        <h4>Google Calendar</h4>
                        <span>${email}</span>
                        <div style="font-size:0.8rem; color:#2563eb; margin-top:2px;">Checking ${accCalendars.length} calendar${accCalendars.length !== 1 ? 's' : ''}</div>
                    </div>
                </div>
                <button class="gbs-disconnect-btn" data-email="${email}" title="Disconnect Account" style="border:none; border-radius: 50%; width: 32px; height: 32px; padding: 0; display:flex; align-items:center; justify-content:center; color:#ef4444; cursor:pointer;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            `;

            // Disconnect Hook
            row.querySelector('.gbs-disconnect-btn').addEventListener('click', (e) => {
                const targetEmail = e.currentTarget.dataset.email;
                if (confirm(`Disconnect Google Calendar account ${targetEmail}?`)) {
                    let url = root + 'auth/google/disconnect';
                    if (targetEmail !== 'Connected Account' && targetEmail.includes('@')) {
                        url += '?email=' + encodeURIComponent(targetEmail);
                    }
                    fetch(url, { method: 'DELETE', headers: headers })
                        .then(r => r.json())
                        .then(res => {
                            if (res.success) initCalendarSettingsLogic();
                            else alert('Failed to disconnect.');
                        });
                }
            });

            conflictsCard.appendChild(row);
        });

        listContainer.appendChild(conflictsCard);


        // --- SECTION 2: Calendar to add events to ---
        const eventsHeader = document.createElement('h3');
        eventsHeader.className = 'gbs-section-title';
        eventsHeader.innerText = 'Calendar to add events to';
        eventsHeader.style.marginBottom = '12px';
        eventsHeader.style.marginTop = '0';
        listContainer.appendChild(eventsHeader);

        // Determine "Active" selection. 
        // We look through all connected accounts in settings. If any has a service_id set, we use the first one we find.
        let activeEmail = accountEmails[0]; // Default to first
        let activeServiceId = null;
        let activeServiceName = 'Select Service';

        if (settings) {
            for (const email of accountEmails) {
                if (settings[email] && settings[email].service_id) {
                    activeEmail = email;
                    activeServiceId = settings[email].service_id;
                    // Try to find service name from services list, fallback to stored name
                    const svc = services.find(s => s.id == activeServiceId);
                    if (svc) {
                        activeServiceName = svc.name;
                    } else if (settings[email].service_name) {
                        activeServiceName = settings[email].service_name;
                    }
                    break;
                }
            }
        }

        const eventsCardWrapper = document.createElement('div');
        eventsCardWrapper.style.cssText = 'position: relative; margin-bottom: 16px; width: 50%; box-sizing: border-box;';

        eventsCardWrapper.innerHTML = `
            <div class="gbs-cal-trigger-card" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px 14px; display: flex; align-items: center; justify-content: space-between; background: #fff; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="gbs-cal-provider-icon" style="width:36px; height:36px; padding:6px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.24l-.19-.6z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                    </div>
                    <div>
                        <div class="gbs-trigger-title" style="font-weight: 600; color: #1f2937; font-size: 0.875rem;">${activeServiceName}</div>
                        <div class="gbs-trigger-email" style="color: #6b7280; font-size: 0.75rem;">${activeEmail}</div>
                    </div>
                </div>
                <div class="gbs-dropdown-arrow" style="color: #9ca3af; transition: transform 0.2s;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
            </div>

            <div class="gbs-service-popup" style="display: none; position: absolute; top: calc(100% + 2px); left: 0; right: 0; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.1); z-index: 50; max-height: 260px; overflow-y: auto;">
                ${accountEmails.map(email => `
                    <div style="padding: 6px 12px; font-weight: 600; color: #6b7280; font-size: 0.65rem; background: #f9fafb; border-bottom: 1px solid #e5e7eb; text-transform: uppercase; letter-spacing: 0.06em;">${email}</div>
                    ${services.map(s => {
            // Check if this specific item is the active one
            const isSelected = (email === activeEmail && s.id == activeServiceId);
            return `
                        <div class="gbs-svc-popup-item" data-email="${email}" data-id="${s.id}" data-name="${s.name}" style="padding: 8px 12px; border-bottom: 1px solid #f3f4f6; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.15s;">
                            <span class="check-icon" style="width: 16px; color: #2563eb; display:${isSelected ? 'flex' : 'none'}; align-items: center; justify-content: center;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            </span>
                            <span style="width: 16px; display:${isSelected ? 'none' : 'block'};"></span>
                            <span style="font-weight: 500; color: #374151; font-size: 0.8rem; flex:1;">${s.name}</span>
                        </div>`;
        }).join('')}
                `).join('')}
                 <div class="gbs-svc-popup-item-none" style="padding: 10px 12px; color: #9ca3af; cursor: pointer; font-size: 0.75rem; border-top: 1px solid #e5e7eb; display: flex; align-items: center; gap: 8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    Do not add new events to a calendar
                </div>
            </div>
        `;
        listContainer.appendChild(eventsCardWrapper);

        // Events Dropdown Logic
        const triggerCard = eventsCardWrapper.querySelector('.gbs-cal-trigger-card');
        const popup = eventsCardWrapper.querySelector('.gbs-service-popup');
        const triggerTitle = eventsCardWrapper.querySelector('.gbs-trigger-title');
        const triggerEmail = eventsCardWrapper.querySelector('.gbs-trigger-email');
        const dropdownArrow = eventsCardWrapper.querySelector('.gbs-dropdown-arrow');

        // Hover effect on trigger
        triggerCard.addEventListener('mouseenter', () => { triggerCard.style.borderColor = '#d1d5db'; triggerCard.style.boxShadow = '0 2px 4px rgba(0,0,0,0.06)'; });
        triggerCard.addEventListener('mouseleave', () => { triggerCard.style.borderColor = '#e5e7eb'; triggerCard.style.boxShadow = '0 1px 2px rgba(0,0,0,0.05)'; });

        triggerCard.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const isVisible = popup.style.display === 'block';
            popup.style.display = isVisible ? 'none' : 'block';
            dropdownArrow.style.transform = isVisible ? 'rotate(0deg)' : 'rotate(180deg)';
        });
        document.addEventListener('click', (e) => {
            if (!triggerCard.contains(e.target) && !popup.contains(e.target)) {
                popup.style.display = 'none';
                dropdownArrow.style.transform = 'rotate(0deg)';
            }
        });

        // Hover effects on popup items
        popup.querySelectorAll('.gbs-svc-popup-item').forEach(item => {
            item.addEventListener('mouseenter', () => { item.style.background = '#f3f4f6'; });
            item.addEventListener('mouseleave', () => { item.style.background = 'transparent'; });
        });

        popup.querySelectorAll('.gbs-svc-popup-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.stopPropagation();
                const clsEmail = item.dataset.email;
                const clsId = item.dataset.id;
                const clsName = item.dataset.name;

                // UI Update
                triggerTitle.textContent = clsName;
                triggerEmail.textContent = clsEmail;

                popup.querySelectorAll('.check-icon').forEach(el => el.style.display = 'none');
                popup.querySelectorAll('.gbs-svc-popup-item span:nth-child(2)').forEach(el => el.style.display = 'block'); // Show empty spacers
                item.querySelector('.check-icon').style.display = 'flex';
                item.querySelector('span:nth-child(2)').style.display = 'none'; // Hide spacer for selected
                popup.style.display = 'none';
                dropdownArrow.style.transform = 'rotate(0deg)';

                // Show saving indicator
                triggerEmail.innerHTML = '<span style="color:#2563eb;">Saving & syncing...</span>';

                // Save: This also triggers calendar creation on the backend
                fetch(root + 'google/settings', {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({
                        account_email: clsEmail,
                        service_id: clsId
                    })
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        triggerEmail.innerHTML = `<span style="color:#059669;">✓ Calendar synced</span>`;
                        setTimeout(() => {
                            triggerEmail.textContent = clsEmail;
                        }, 2000);
                        gbsShowToast('Calendar "' + clsName + '" created/linked!');
                    } else {
                        console.error('Failed to save calendar setting');
                        triggerEmail.textContent = clsEmail;
                        gbsShowToast('Error syncing calendar');
                    }
                }).catch(err => {
                    console.error(err);
                    triggerEmail.textContent = clsEmail;
                });
            });
        });

        // --- Sync Settings ---
        const syncHeader = document.createElement('h3');
        syncHeader.innerText = 'Sync settings';
        syncHeader.style.cssText = 'font-size:0.95rem; font-weight:700; color:#111827; margin-bottom:12px; margin-top:0;';
        listContainer.appendChild(syncHeader);

        const syncBox = document.createElement('div');
        syncBox.style.cssText = 'display:flex; flex-direction:column; gap:12px;';
        syncBox.innerHTML = `
            <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:0.9rem; color:#4b5563;">
                <input type="checkbox" style="width:16px; height:16px; border-radius:4px; border:1px solid #d1d5db; accent-color:#2563eb;">
                Include buffers on this calendar
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" style="cursor:help;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            </label>
             <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:0.9rem; color:#4b5563;">
                <input type="checkbox" checked style="width:16px; height:16px; border-radius:4px; border:1px solid #d1d5db; accent-color:#2563eb;">
                Automatically sync changes from this calendar to Google Calendar
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" style="cursor:help;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            </label>
        `;
        listContainer.appendChild(syncBox);

    }


    // --- Calendar Settings Logic (New) ---
    function initCalendarSettings() {
        const modalConnect = document.getElementById('modal-connect-calendar-overlay');
        const btnTrigger = document.getElementById('btn-trigger-connect-cal');
        const btnClose = document.getElementById('btn-close-connect-cal');
        const btnGoogle = document.getElementById('btn-provider-google');

        if (modalConnect) {
            if (btnTrigger) {
                // Clone to prevent duplicate listeners
                const newBtn = btnTrigger.cloneNode(true);
                if (btnTrigger.parentNode) {
                    btnTrigger.parentNode.replaceChild(newBtn, btnTrigger);
                    newBtn.addEventListener('click', () => {
                        modalConnect.style.display = 'flex';
                    });
                }
            }
            if (btnClose) {
                btnClose.addEventListener('click', () => {
                    modalConnect.style.display = 'none';
                });
            }
            // Close on click outside
            modalConnect.addEventListener('click', (e) => {
                if (e.target === modalConnect) modalConnect.style.display = 'none';
            });

            if (btnGoogle) {
                const newBtnG = btnGoogle.cloneNode(true);
                if (btnGoogle.parentNode) {
                    btnGoogle.parentNode.replaceChild(newBtnG, btnGoogle);

                    newBtnG.addEventListener('click', () => {
                        gbsShowToast('Redirecting to Google...');
                        // Use consistent API root
                        window.location.href = root + 'auth/google/connect';
                    });
                }
            }
        }
    }

    // --- Advanced Settings Logic ---
    function initAdvancedSettings() {
        const btnDropdown = document.getElementById('btn-country-dropdown');
        const listDropdown = document.getElementById('dropdown-country-list');
        const labelSelected = document.getElementById('label-selected-country');
        const contentArea = document.getElementById('gbs-holidays-content');
        const mainToggle = document.getElementById('toggle-holidays');

        if (!btnDropdown || !listDropdown || !contentArea) return;

        // Data
        const holidaysData = {
            'uk': [
                { name: "New Year's Day", date: "Next: 1 Jan", active: true },
                { name: "Good Friday", date: "Next: 18 Apr", active: false },
                { name: "Early May Bank Holiday", date: "Next: 5 May", active: true },
                { name: "Spring Bank Holiday", date: "Next: 26 May", active: true },
                { name: "Summer Bank Holiday", date: "Next: 25 Aug", active: true },
                { name: "Christmas Day", date: "Next: 25 Dec", active: true },
                { name: "Boxing Day", date: "Next: 26 Dec", active: true }
            ],
            'us': [
                { name: "New Year's Day", date: "Next: 1 Jan", active: true },
                { name: "Martin Luther King, Jr. Day", date: "Next: 20 Jan", active: true },
                { name: "Memorial Day", date: "Next: 26 May", active: true },
                { name: "Independence Day", date: "Next: 4 Jul", active: true },
                { name: "Labor Day", date: "Next: 1 Sep", active: true },
                { name: "Thanksgiving Day", date: "Next: 27 Nov", active: true },
                { name: "Christmas Day", date: "Next: 25 Dec", active: true }
            ],
            'au': [
                { name: "New Year's Day", date: "Next: 1 Jan", active: true },
                { name: "Australia Day", date: "Next: 27 Jan", active: true },
                { name: "Good Friday", date: "Next: 18 Apr", active: true },
                { name: "Easter Monday", date: "Next: 21 Apr", active: true },
                { name: "Anzac Day", date: "Next: 25 Apr", active: true },
                { name: "Christmas Day", date: "Next: 25 Dec", active: true },
                { name: "Boxing Day", date: "Next: 26 Dec", active: true }
            ],
            'br': [
                { name: "New Year's Day", date: "Next: 1 Jan", active: true },
                { name: "Carnival", date: "Next: 3 Mar", active: true },
                { name: "Good Friday", date: "Next: 18 Apr", active: true },
                { name: "Tiradentes Day", date: "Next: 21 Apr", active: true },
                { name: "Labor Day", date: "Next: 1 May", active: true },
                { name: "Independence Day", date: "Next: 7 Sep", active: true },
                { name: "Christmas Day", date: "Next: 25 Dec", active: true }
            ],
            'ca': [
                { name: "New Year's Day", date: "Next: 1 Jan", active: true },
                { name: "Good Friday", date: "Next: 18 Apr", active: true },
                { name: "Victoria Day", date: "Next: 19 May", active: true },
                { name: "Canada Day", date: "Next: 1 Jul", active: true },
                { name: "Labour Day", date: "Next: 1 Sep", active: true },
                { name: "Thanksgiving Day", date: "Next: 13 Oct", active: true },
                { name: "Christmas Day", date: "Next: 25 Dec", active: true }
            ],
            'fr': [
                { name: "New Year's Day", date: "Next: 1 Jan", active: true },
                { name: "Easter Monday", date: "Next: 21 Apr", active: true },
                { name: "Labor Day", date: "Next: 1 May", active: true },
                { name: "Victory Day", date: "Next: 8 May", active: true },
                { name: "Bastille Day", date: "Next: 14 Jul", active: true },
                { name: "All Saints' Day", date: "Next: 1 Nov", active: true },
                { name: "Christmas Day", date: "Next: 25 Dec", active: true }
            ],
            'de': [
                { name: "New Year's Day", date: "Next: 1 Jan", active: true },
                { name: "Good Friday", date: "Next: 18 Apr", active: true },
                { name: "Easter Monday", date: "Next: 21 Apr", active: true },
                { name: "Labor Day", date: "Next: 1 May", active: true },
                { name: "Ascension Day", date: "Next: 29 May", active: true },
                { name: "German Unity Day", date: "Next: 3 Oct", active: true },
                { name: "Christmas Day", date: "Next: 25 Dec", active: true }
            ],
            'mx': [
                { name: "New Year's Day", date: "Next: 1 Jan", active: true },
                { name: "Constitution Day", date: "Next: 3 Feb", active: true },
                { name: "Benito JuÃ¡rez Birthday", date: "Next: 17 Mar", active: true },
                { name: "Labor Day", date: "Next: 1 May", active: true },
                { name: "Independence Day", date: "Next: 16 Sep", active: true },
                { name: "Revolution Day", date: "Next: 17 Nov", active: true },
                { name: "Christmas Day", date: "Next: 25 Dec", active: true }
            ],
            'nl': [
                { name: "New Year's Day", date: "Next: 1 Jan", active: true },
                { name: "Good Friday", date: "Next: 18 Apr", active: true },
                { name: "Easter Monday", date: "Next: 21 Apr", active: true },
                { name: "King's Day", date: "Next: 27 Apr", active: true },
                { name: "Liberation Day", date: "Next: 5 May", active: true },
                { name: "Ascension Day", date: "Next: 29 May", active: true },
                { name: "Christmas Day", date: "Next: 25 Dec", active: true }
            ],
            'es': [
                { name: "New Year's Day", date: "Next: 1 Jan", active: true },
                { name: "Epiphany", date: "Next: 6 Jan", active: true },
                { name: "Good Friday", date: "Next: 18 Apr", active: true },
                { name: "Labor Day", date: "Next: 1 May", active: true },
                { name: "Assumption Day", date: "Next: 15 Aug", active: true },
                { name: "National Day", date: "Next: 12 Oct", active: true },
                { name: "Christmas Day", date: "Next: 25 Dec", active: true }
            ]
        };

        const countryNames = {
            'uk': 'United Kingdom',
            'us': 'United States',
            'other': 'Other',
            'au': 'Australia',
            'br': 'Brazil',
            'ca': 'Canada',
            'fr': 'France',
            'de': 'Germany',
            'mx': 'Mexico',
            'nl': 'Netherlands',
            'es': 'Spain'
        };

        // Toggle details
        const updateContent = (val) => {
            contentArea.innerHTML = '';

            if (val === 'other') {
                contentArea.innerHTML = `
                    <div style="padding: 30px; text-align: center; color: #9ca3af; font-size: 0.85rem;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 12px; color: #d1d5db;">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <div>Holidays for other countries are not yet supported.</div>
                    </div>
                `;
                // Disable Toggle
                mainToggle.checked = false;
                mainToggle.disabled = true;
                contentArea.style.opacity = '0.6';
            } else {
                // Fetching State
                mainToggle.disabled = true;
                contentArea.style.opacity = '0.5';
                contentArea.innerHTML = `<div style="padding: 30px; text-align: center; color: #6b7280; font-size: 0.85rem;">
                    <div style="width: 24px; height: 24px; border: 2px solid #e5e7eb; border-top-color: #3b82f6; border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto 12px;"></div>
                    Loading holidays...
                </div>`;

                fetch(root + 'google/holidays?country=' + val, { headers })
                    .then(r => r.json())
                    .then(res => {
                        if (res && Array.isArray(res)) {
                            contentArea.innerHTML = '';
                            contentArea.style.padding = '0';

                            res.forEach((h, idx) => {
                                const row = document.createElement('div');
                                row.className = 'gbs-holiday-row';
                                row.style.cssText = `padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; transition: background 0.15s;${idx < res.length - 1 ? ' border-bottom: 1px solid #f3f4f6;' : ''}`;
                                row.innerHTML = `
                                <div style="text-align: left;">
                                    <div style="font-weight: 500; color: #1f2937; font-size: 0.875rem; margin-bottom: 2px; text-align: left;">${h.name}</div>
                                    <div style="font-size: 0.75rem; color: #f97316; text-align: left;">${h.date}</div>
                                </div>
                                <label class="gbs-toggle" style="position: relative; display: inline-flex; align-items: center; cursor: pointer; flex-shrink: 0;">
                                    <input type="checkbox" ${h.active ? 'checked' : ''} style="position: absolute; opacity: 0; width: 0; height: 0;">
                                    <span class="gbs-slider" style="width: 40px; height: 22px; background: ${h.active ? '#f97316' : '#e5e7eb'}; border-radius: 11px; position: relative; transition: background 0.2s;">
                                        <span style="position: absolute; top: 2px; left: ${h.active ? '20px' : '2px'}; width: 18px; height: 18px; background: #fff; border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.15); transition: left 0.2s;"></span>
                                    </span>
                                </label>
                            `;

                                // Add hover effect
                                row.addEventListener('mouseenter', () => { row.style.background = '#f9fafb'; });
                                row.addEventListener('mouseleave', () => { row.style.background = 'transparent'; });

                                // Toggle logic
                                const toggle = row.querySelector('input[type="checkbox"]');
                                const slider = row.querySelector('.gbs-slider');
                                const knob = slider.querySelector('span');
                                toggle.addEventListener('change', () => {
                                    slider.style.background = toggle.checked ? '#f97316' : '#e5e7eb';
                                    knob.style.left = toggle.checked ? '20px' : '2px';
                                });

                                contentArea.appendChild(row);
                            });

                            mainToggle.disabled = false;
                            mainToggle.checked = true;
                            contentArea.style.opacity = '1';
                        } else {
                            let msg = res.message || 'Error loading holidays.';
                            if (res.code === 'no_token') msg = 'Please connect a Google Account to fetch holidays.';
                            contentArea.innerHTML = `<div style="padding: 24px; text-align: center; color: #ef4444; font-size: 0.85rem;">${msg}</div>`;
                            mainToggle.disabled = true;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        contentArea.innerHTML = `<div style="padding: 24px; text-align: center; color: #ef4444; font-size: 0.85rem;">Failed to fetch holidays.</div>`;
                        mainToggle.disabled = true;
                    });
            }
        };

        // Dropdown actions
        btnDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = listDropdown.style.display === 'block';
            listDropdown.style.display = isOpen ? 'none' : 'block';
            if (!isOpen) {
                const search = document.getElementById('input-country-search');
                if (search) {
                    search.value = '';
                    search.focus();
                    // Reset visibility
                    listDropdown.querySelectorAll('.gbs-country-option').forEach(o => o.style.display = 'flex');
                }
            }
        });

        document.addEventListener('click', (e) => {
            if (!btnDropdown.contains(e.target)) {
                listDropdown.style.display = 'none';
            }
        });

        // Shared UI Update
        const updateDropdownUI = (selectedVal) => {
            const allOpts = Array.from(listDropdown.querySelectorAll('.gbs-country-option'));

            // Update Selected State and Label
            allOpts.forEach(o => {
                o.classList.remove('selected');
                const icon = o.querySelector('svg');
                if (icon) icon.remove();

                // Reset Loop Styles if needed (though we overwrite below)
            });

            const opt = listDropdown.querySelector(`.gbs-country-option[data-val="${selectedVal}"]`);
            if (opt) {
                opt.classList.add('selected');
                opt.innerHTML += '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
                labelSelected.innerText = countryNames[selectedVal] || opt.innerText.replace('âœ“', '').trim();
            }

            // Sort and Render
            listDropdown.querySelectorAll('.gbs-dropdown-divider').forEach(d => d.remove());

            allOpts.sort((a, b) => {
                const valA = a.dataset.val;
                const valB = b.dataset.val;

                // 1. Selected item ALWAYS First
                if (valA === selectedVal) return -1;
                if (valB === selectedVal) return 1;

                // 2. 'other' ALWAYS Last (if not selected)
                if (valA === 'other') return 1;
                if (valB === 'other') return -1;

                // 3. Alphabetical
                return a.innerText.localeCompare(b.innerText);
            });

            // Reset content to ensure clean sort state (append to scrollable area)
            const scrollable = listDropdown.querySelector('.gbs-dropdown-scrollable');
            if (scrollable) {
                allOpts.forEach(o => scrollable.appendChild(o));
            }

            allOpts.forEach((o, index) => {
                // Update Style
                o.style.display = 'flex';
                o.style.justifyContent = 'space-between';
                o.style.alignItems = 'center';

                // Add Divider after 1st item (which is selected) if needed, 
                // BUT with new search design we might want to keep the Scrollable list clean.
                // The prompt says "show tick before selected zone". The previous code put selected item at top + divider.
                // Let's stick to simple "Selected Tick" logic inside the list for now, or keep the sort?
                // The prompt implies a standard searchable list. Sorting selected to top might be confusing with search.
                // Let's DISABLE the "move to top" sort for now, just keep alphabetical or original order.
            });

            // Re-sort alphabetically? Or keep original DOM order?
            // Let's keep original DOM order for simplicity and just show tick.

            allOpts.forEach(o => {
                const text = o.querySelector('span') ? o.querySelector('span').textContent.trim() : o.textContent.trim();
                const hasTick = o.querySelector('svg');

                if (o.dataset.val === selectedVal) {
                    // Add tick if not present
                    if (!hasTick) {
                        o.innerHTML = `<span>${text}</span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`;
                    }
                    o.style.fontWeight = '600';
                    o.style.color = '#f97316';
                } else {
                    // Remove tick if present
                    if (hasTick) {
                        o.innerHTML = `<span>${text}</span>`;
                    }
                    o.style.fontWeight = '500';
                    o.style.color = o.dataset.val === 'other' ? '#9ca3af' : '#374151';
                }
            });
        };

        // Search Logic
        const inputCountrySearch = document.getElementById('input-country-search');
        if (inputCountrySearch) {
            inputCountrySearch.addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase();
                const items = listDropdown.querySelectorAll('.gbs-country-option');
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(term) ? 'flex' : 'none';
                });
            });
            inputCountrySearch.addEventListener('click', (e) => e.stopPropagation());
        }

        listDropdown.querySelectorAll('.gbs-country-option').forEach(opt => {
            opt.addEventListener('click', (e) => {
                e.stopPropagation();

                const val = opt.dataset.val;
                updateDropdownUI(val);
                updateContent(val);
                listDropdown.style.display = 'none';

                // SAVE Setting
                fetch(`${gbsAdminData.apiUrl}holidays/country`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': gbsAdminData.nonce
                    },
                    body: JSON.stringify({ country: val })
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            gbsShowToast('Holiday country saved');
                        }
                    });
            });
        });

        // Main Toggle Logic
        const mainSlider = document.getElementById('main-holiday-slider');
        const mainKnob = document.getElementById('main-holiday-knob');

        const updateMainToggleStyle = (checked) => {
            if (mainSlider && mainKnob) {
                mainSlider.style.background = checked ? '#f97316' : '#e5e7eb';
                mainKnob.style.left = checked ? '22px' : '2px';
            }
        };

        mainToggle.addEventListener('change', (e) => {
            const checked = e.target.checked;
            updateMainToggleStyle(checked);

            if (checked) {
                contentArea.style.opacity = '1';
                contentArea.style.pointerEvents = 'auto';
            } else {
                contentArea.style.opacity = '0.5';
                contentArea.style.pointerEvents = 'none';
            }
        });
        // Initialize state check
        // Fetch saved holiday country
        const initHolidaySettings = () => {
            fetch(`${gbsAdminData.apiUrl}holidays/country`, {
                headers: { 'X-CSRF-TOKEN': gbsAdminData.nonce }
            }).then(r => r.json()).then(res => {
                if (res.country && res.country !== 'other') {
                    const savedVal = res.country;

                    // Update Content
                    updateContent(savedVal);

                    // Update Dropdown UI (Sort, Select, Divider)
                    updateDropdownUI(savedVal);

                    // Enable and style the toggle
                    mainToggle.disabled = false;
                    mainToggle.checked = true;
                    updateMainToggleStyle(true);
                } else {
                    // Default logic - "Other" or no country saved
                    contentArea.style.opacity = '0.5';
                    contentArea.style.pointerEvents = 'none';
                    mainToggle.disabled = true;
                    updateMainToggleStyle(false);
                }
            }).catch(() => {
                // On error, default to disabled state
                contentArea.style.opacity = '0.5';
                mainToggle.disabled = true;
                updateMainToggleStyle(false);
            });
        };
        initHolidaySettings();



        // ... (end of function) needs to be careful with existing 'Initialize state check' block removal
    }

    // Meeting Limits Logic
    function initMeetingLimitsLogic() {
        const wrapper = document.getElementById('gbs-meeting-limits-section');
        const container = document.getElementById('gbs-limits-rows');
        const header = document.getElementById('gbs-limits-header');
        const btnAdd = document.getElementById('btn-add-limit');

        if (!wrapper || !container || !btnAdd) return;

        let rowCount = 0;
        const maxRows = 3;

        const updateUI = () => {
            if (rowCount > 0) {
                if (header) header.style.display = 'block';
                btnAdd.innerText = '+ Add another meeting limit';
            } else {
                if (header) header.style.display = 'none';
                btnAdd.innerText = '+ Add a meeting limit';
            }

            if (rowCount >= maxRows) {
                btnAdd.style.display = 'none';
            } else {
                btnAdd.style.display = 'inline-block';
            }
        };

        // SAVE Logic
        const saveMeetingLimits = () => {
            const rows = Array.from(container.querySelectorAll('.gbs-limit-row'));
            const limits = rows.map(r => {
                const num = r.querySelector('input').value;
                const unit = r.querySelector('.curr-val').innerText.trim();
                return { limit: num, unit: unit };
            }).filter(l => l.limit); // Only save if number is present

            fetch(`${gbsAdminData.apiUrl}meeting-limits`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': gbsAdminData.nonce
                },
                body: JSON.stringify({ limits: limits })
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        gbsShowToast('Meeting limits saved');
                    }
                });
        };

        const addRow = (initialData = null) => {
            if (rowCount >= maxRows) return;

            rowCount++;
            updateUI();

            // Default values
            let nextVal = 'day';
            let initialNum = '';

            if (initialData) {
                nextVal = initialData.unit;
                initialNum = initialData.limit;
            } else {
                // Determine default value based on what is available
                const usedValues = Array.from(container.querySelectorAll('.gbs-limit-row'))
                    .map(r => r.querySelector('.curr-val').innerText.trim());
                const allUnits = ['day', 'week', 'month'];
                nextVal = allUnits.find(u => !usedValues.includes(u)) || 'day';
            }

            const row = document.createElement('div');
            row.className = 'gbs-limit-row';
            row.style.cssText = 'display: flex; align-items: center; gap: 12px; margin-bottom: 12px; flex-wrap: wrap;';

            row.innerHTML = `
                <input type="number" min="1" class="gbs-inp-simple" value="${initialNum}" style="width: 80px; padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 6px;" placeholder="">
                <span style="color: #374151; font-size: 0.95rem;">meetings per</span>

                <div class="gbs-limit-dropdown-wrapper" style="position: relative; display: inline-block;">
                    <button class="gbs-limit-dropdown-trigger" style="background: white; border: 1px solid #f97316; color: #f97316; padding: 6px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 0.95rem; min-width: 80px; justify-content: space-between;">
                        <span class="curr-val">${nextVal}</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="gbs-limit-dropdown-menu" style="display: none; position: absolute; top: 110%; left: 0; background: white; border: 1px solid #e5e7eb; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); width: 140px; z-index: 50; padding: 4px 0;">
                        <!-- Options Rendered Dynamically -->
                    </div>
                </div>

                <div style="margin-left:auto; display:flex; align-items:center;">
                    <button class="gbs-limit-remove" style="background: none; border: none; cursor: pointer; color: #6b7280; padding:4px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
            `;

            container.appendChild(row);

            // Inputs Logic (Save on KeyUp)
            const input = row.querySelector('input');
            let debounceTimer;
            input.addEventListener('keyup', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    saveMeetingLimits();
                }, 500); // 500ms debounce
            });

            // Dropdown Logic
            const dBtn = row.querySelector('.gbs-limit-dropdown-trigger');
            const dMenu = row.querySelector('.gbs-limit-dropdown-menu');
            const dValSpan = row.querySelector('.curr-val');

            // Render Menu Options dynamically based on what's already selected
            const renderMenuOptions = () => {
                // Get all currently selected values in other rows
                const currentSelections = Array.from(container.querySelectorAll('.gbs-limit-row'))
                    .map(r => r.querySelector('.curr-val').innerText.trim());

                // Defined order
                const allUnits = ['day', 'week', 'month'];

                // Clear menu
                dMenu.innerHTML = '';

                // If this row already has a value, include it in the list (so we can re-select it),
                // but exclude others that are used elsewhere
                const myVal = dValSpan.innerText.trim();

                allUnits.forEach(unit => {
                    // Show option if it's NOT used elsewhere, OR if it's the current row's value
                    // Actually, simpler logic: filter out values used in *other* rows
                    const isUsedElsewhere = currentSelections.some(s => s === unit && s !== myVal);

                    if (!isUsedElsewhere) {
                        const opt = document.createElement('div');
                        opt.className = 'gbs-limit-d-opt';
                        if (unit === myVal) opt.classList.add('selected');
                        opt.dataset.val = unit;
                        opt.style.cssText = 'padding: 8px 12px; cursor: pointer; display:flex; justify-content:space-between; align-items:center;';

                        let inner = unit;
                        if (unit === myVal) {
                            inner += '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
                        }
                        opt.innerHTML = inner;

                        // Click Handler
                        opt.addEventListener('click', (e) => {
                            e.stopPropagation();
                            dValSpan.innerText = unit;
                            dMenu.style.display = 'none';
                            saveMeetingLimits(); // Save on change
                        });

                        dMenu.appendChild(opt);
                    }
                });
            };

            dBtn.addEventListener('click', (e) => {
                e.stopPropagation();

                // Close others
                document.querySelectorAll('.gbs-limit-dropdown-menu').forEach(m => {
                    if (m !== dMenu) m.style.display = 'none';
                });

                if (dMenu.style.display === 'block') {
                    dMenu.style.display = 'none';
                } else {
                    renderMenuOptions(); // Re-render to check for conflicts
                    dMenu.style.display = 'block';
                }
            });

            // Remove Logic
            const btnRemove = row.querySelector('.gbs-limit-remove');
            btnRemove.addEventListener('click', () => {
                row.remove();
                rowCount--;
                updateUI();
                saveMeetingLimits(); // Save on remove
            });
        };

        // Add Button
        btnAdd.addEventListener('click', (e) => {
            e.stopPropagation();
            e.preventDefault();
            addRow();
        });

        // Hide Dropdowns on click outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.gbs-limit-dropdown-wrapper')) {
                document.querySelectorAll('.gbs-limit-dropdown-menu').forEach(m => m.style.display = 'none');
            }
        });

        // Initialize: Fetch Saved Limits
        fetch(`${gbsAdminData.apiUrl}meeting-limits`, {
            headers: { 'X-CSRF-TOKEN': gbsAdminData.nonce }
        }).then(r => r.json()).then(res => {
            if (Array.isArray(res) && res.length > 0) {
                res.forEach(limitObj => {
                    addRow(limitObj);
                });
            }
        });
    }

    // Timezone Logic
    function initTimezoneLogic() {

        // Data Provider
        const getAllTimezones = () => {
            if (typeof Intl === 'undefined' || typeof Intl.supportedValuesOf === 'undefined') {
                return ['UTC', 'Europe/London', 'America/New_York', 'Asia/Tokyo'];
            }
            return Intl.supportedValuesOf('timeZone');
        };
        const timezones = getAllTimezones();

        // Helper to format time
        const getTimeString = (tz) => {
            try {
                return new Date().toLocaleTimeString('en-US', { timeZone: tz, hour: 'numeric', minute: '2-digit', hour12: true }).toLowerCase();
            } catch (e) { return ''; }
        };

        // Shared Update Function
        const updateAllLabels = (displayVal) => {
            document.querySelectorAll('.gbs-current-timezone-label').forEach(el => el.innerText = displayVal);
        };

        const setupTimezonePicker = (btnId, dropdownId, searchId, listId, onSelect = null) => {
            const btn = document.getElementById(btnId);
            const dropdown = document.getElementById(dropdownId);
            const searchInp = document.getElementById(searchId);
            const list = document.getElementById(listId);

            let currentTz = document.getElementById('label-current-timezone')?.innerText || '';
            // Normalize currentTz back to ID like (e.g "London" -> not ID, we need full ID to match check)
            // But we store full ID in memory maybe?
            // Actually, we can check against current labels.
            // Better: activeTz variable if possible. Since we only have 'label', let's trust the browser default or stored value.
            // Let's pass currentTz in params or global. For now, we rely on checking if it matches the label logic is fuzzy.
            // Let's improve: fetch returns full ID "Europe/London". 
            // We need to keep track of selected ID. 
            // We can read it from a hidden input if present, or just use what we have.

            if (!btn || !dropdown || !list || !searchInp) return;

            const renderList = (filter = '') => {
                // Get current selected value if possible
                // We rely on global gbsAdminData or DOM. 
                // Let's check the button's dataset if we store it there?
                // The init function 'updateAllLabels' updates text.
                // Let's try to find the full ID from the text label "Europe London" -> "Europe/London" roughly.
                // Or better, let's just use the clicked logic to set a variable.
                const storedTz = btn.dataset.tzId;
                currentTz = storedTz || Intl.DateTimeFormat().resolvedOptions().timeZone; // Default fallback

                list.innerHTML = '';

                const groups = {};
                const q = filter.toLowerCase();

                timezones.forEach(tz => {
                    if (q && !tz.toLowerCase().includes(q)) return;

                    const parts = tz.split('/');
                    const region = parts[0].toUpperCase();
                    const city = parts.slice(1).join(' ').replace(/_/g, ' ');

                    if (!groups[region]) groups[region] = [];
                    groups[region].push({ id: tz, name: city, time: getTimeString(tz) });
                });

                for (const [region, items] of Object.entries(groups)) {
                    if (items.length === 0) continue;

                    const header = document.createElement('div');
                    header.style.cssText = 'padding: 8px 12px; font-weight:700; color:#374151; font-size:12px; letter-spacing:0.5px; margin-top:4px;';
                    header.innerText = region;
                    list.appendChild(header);

                    items.forEach(item => {
                        const el = document.createElement('div');
                        el.className = 'gbs-tz-item';
                        el.style.cssText = 'padding: 8px 12px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; color: #4b5563; font-size: 14px;';
                        el.onmouseover = () => el.style.background = '#f3f4f6';
                        el.onmouseout = () => el.style.background = 'transparent';

                        el.innerHTML = `
                            <div style="display:flex; align-items:center;">
                                ${item.id === currentTz ?
                                '<svg style="margin-right:8px; flex-shrink:0;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>'
                                : ''}
                                <span>${item.name}</span>
                            </div>
                            <span style="font-feature-settings: 'tnum'; font-variant-numeric: tabular-nums; color: #6b7280;">
                                ${item.time}
                            </span>
                        `;

                        el.addEventListener('click', () => {
                            selectTimezone(item.id);
                        });

                        list.appendChild(el);
                    });
                }
            };

            const selectTimezone = (tz) => {
                const display = tz.replace(/_/g, ' ');

                // If callback provided, use that
                if (onSelect) {
                    onSelect(tz, display);
                    document.querySelectorAll('.gbs-tz-dropdown').forEach(d => d.style.display = 'none');
                    return;
                }

                // Default behavior (Availability Page)
                updateAllLabels(display);

                // Store ID on buttons for checkmark logic
                document.querySelectorAll('.gbs-current-timezone-label').forEach(el => {
                    // Find button parent
                    const pBtn = el.closest('button');
                    if (pBtn) pBtn.dataset.tzId = tz;
                });

                // Close all dropdowns
                document.querySelectorAll('.gbs-tz-dropdown').forEach(d => d.style.display = 'none');

                // Save logic
                fetch(root + 'timezone', {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({ timezone: tz })
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            gbsShowToast('Timezone set to ' + display);
                        } else {
                            console.error('Timezone save failed:', res);
                            gbsShowToast('Error saving timezone');
                        }
                    })
                    .catch(err => {
                        console.error('Timezone API error:', err);
                        gbsShowToast('Network error');
                    });
            };

            // Toggle
            btn.addEventListener('click', (e) => {
                e.stopPropagation();

                // Close Others first
                document.querySelectorAll('.gbs-tz-dropdown').forEach(d => {
                    if (d !== dropdown) d.style.display = 'none';
                });

                if (dropdown.style.display === 'flex') {
                    dropdown.style.display = 'none';
                } else {
                    dropdown.style.display = 'flex'; // It's flex column
                    renderList(searchInp.value);
                    setTimeout(() => searchInp.focus(), 50); // Small delay to ensure display:flex is reliable
                }
            });

            // Search
            searchInp.addEventListener('input', (e) => {
                renderList(e.target.value);
            });

            // Close logic handled globally below
        };

        // Initialize Instances
        // 1. List View
        setupTimezonePicker('gbs-timezone-btn', 'gbs-timezone-dropdown', 'inp-timezone-search', 'list-timezones');
        // 2. Calendar View
        setupTimezonePicker('gbs-timezone-btn-cal', 'gbs-timezone-dropdown-cal', 'inp-timezone-search-cal', 'list-timezones-cal');

        // 3. Staff Wizard
        setupTimezonePicker('wiz-staff-tz-btn', 'wiz-staff-tz-dropdown', 'wiz-staff-tz-search', 'wiz-staff-tz-list', (tz, display) => {
            const hidden = document.getElementById('wiz-staff-timezone-value');
            const label = document.getElementById('wiz-staff-tz-label');
            if (hidden) hidden.value = tz;
            if (label) label.innerText = display;
        });

        // Global Click Outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.gbs-tz-dropdown') &&
                !e.target.closest('#gbs-timezone-btn') &&
                !e.target.closest('#gbs-timezone-btn-cal') &&
                !e.target.closest('#wiz-staff-tz-btn')) {
                document.querySelectorAll('.gbs-tz-dropdown').forEach(d => d.style.display = 'none');
            }
        });

        // Initial Fetch
        const browserTz = Intl.DateTimeFormat().resolvedOptions().timeZone;
        updateAllLabels(browserTz.replace(/_/g, ' ')); // optimistic default
        // Store default on buttons
        document.querySelectorAll('#gbs-timezone-btn, #gbs-timezone-btn-cal').forEach(b => b.dataset.tzId = browserTz);

        fetch(`${gbsAdminData.apiUrl}timezone`, {
            headers: { 'X-CSRF-TOKEN': gbsAdminData.nonce }
        }).then(r => r.json()).then(res => {
            if (res.timezone) {
                updateAllLabels(res.timezone.replace(/_/g, ' '));
                document.querySelectorAll('#gbs-timezone-btn, #gbs-timezone-btn-cal').forEach(b => b.dataset.tzId = res.timezone);
            }
        });

        // Limit Hours Counter Logic
        const btnLimitMinus = document.getElementById('btn-limit-minus');
        const btnLimitPlus = document.getElementById('btn-limit-plus');
        const inputLimit = document.getElementById('wiz-staff-limit-hours');

        if (btnLimitMinus && btnLimitPlus && inputLimit) {
            btnLimitMinus.addEventListener('click', (e) => {
                e.preventDefault(); // Stop form issues
                inputLimit.stepDown();
                inputLimit.dispatchEvent(new Event('input', { bubbles: true }));
            });
            btnLimitPlus.addEventListener('click', (e) => {
                e.preventDefault();
                inputLimit.stepUp();
                inputLimit.dispatchEvent(new Event('input', { bubbles: true }));
            });
        }

        // 4. Wizard Google Connect Logic
        const btnConnect = document.getElementById('btn-wiz-connect-google');
        if (btnConnect) {
            btnConnect.addEventListener('click', () => {
                // Pass context=staff
                const authUrl = `${gbsAdminData.siteUrl}/wp-json/gbs/v1/auth/google/connect?context=staff`;

                const w = 500;
                const h = 600;
                const left = (screen.width / 2) - (w / 2);
                const top = (screen.height / 2) - (h / 2);

                const win = window.open(authUrl, 'gbs_google_auth',
                    `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=${w}, height=${h}, top=${top}, left=${left}`);

                // We don't need the polling interval if we use postMessage reliability, 
                // but keeping it as fallback is okay. For now let's trust postMessage.

                // Listen for completion
                window.addEventListener('message', (e) => {
                    if (e.data.gbsAuthSuccess) {
                        // win.close(); // JS closes itself
                        const email = e.data.email || 'Connected';
                        const tempToken = e.data.temp_token || ''; // Capture temp token

                        // Store temp token for form submission
                        btnConnect.dataset.tempToken = tempToken;

                        gbsShowToast('Google Account Connected!');

                        // Update UI to show connected state (Match Availability Card Style)
                        btnConnect.innerHTML = `
                             <div style="display:flex; gap:12px; align-items:center;">
                                 <div style="width:40px; height:40px; display:flex; align-items:center; justify-content:center; background:#fff; border-radius:4px; border:1px solid #e5e7eb;">
                                     <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                         <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                         <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                         <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.24l-.19-.6z" fill="#FBBC05"/>
                                         <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                     </svg>
                                 </div>
                                 <div style="text-align:left;">
                                     <div style="font-weight:600; color:#1f2937; font-size:0.95rem;">Google Calendar</div>
                                     <div style="color:#6b7280; font-size:0.85rem; margin-bottom:2px;">${email}</div>
                                     <div style="color:#2563eb; font-size:0.85rem;">Connected</div>
                                 </div>
                             </div>
                         <div style="margin-left:auto; cursor:pointer;" id="btn-remove-google-connection" title="Disconnect Account">
                            <div style="width:28px; height:28px; display:flex; align-items:center; justify-content:center; border-radius:50%; background:#fee2e2; color:#ef4444; transition:background 0.2s;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </div>
                         </div>
                         `;
                        btnConnect.style.background = '#fff';
                        btnConnect.style.borderColor = '#e5e7eb';
                        btnConnect.style.display = 'flex';
                        btnConnect.style.alignItems = 'center';
                        btnConnect.style.padding = '12px';
                        btnConnect.style.pointerEvents = 'auto'; // Re-enable clicks so we can click the X

                        // Add listener for disconnect
                        const btnRemove = document.getElementById('btn-remove-google-connection');
                        if (btnRemove) {
                            btnRemove.addEventListener('click', (ev) => {
                                ev.stopPropagation(); // Stop bubbling to btnConnect
                                // Reset UI
                                delete btnConnect.dataset.tempToken;
                                // Reset styles but keep width 50%
                                btnConnect.style.cssText = 'width: 50%;';
                                btnConnect.innerHTML = `
                                    <div class="gbs-connect-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    </div>
                                    <div class="gbs-connect-info">
                                        <div class="gbs-connect-title">Google Calendar</div>
                                        <div class="gbs-connect-sub">Gmail, G Suite</div>
                                    </div>
                                    <div class="gbs-connect-action">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                    </div>
                                `;
                                gbsShowToast('Account removed. You can connect another one.');
                            });
                        }

                        // DO NOT refresh main settings as request is atomic now
                    }
                }, false);
            });
        }
    }

    // Init
    initCalendarSettings();
    initAdvancedSettings();
    initMeetingLimitsLogic();
    // initConnectCalendarModal(); // Removed as it appeared undefined
    initScheduleLogic();
    initTimezoneLogic(); // Start Timezone
    fetchAll().then(() => {
        initCalendarSettingsLogic();
        const def = schedules.find(s => s.is_default == '1');
        if (def) {
            activeScheduleId = def.id;
        }
        updateScheduleHeader();
    });

    // Google Auth Logic for Staff Wizard
    document.addEventListener('click', (e) => {
        const btnGoogle = e.target.closest('#btn-wiz-connect-google');
        if (btnGoogle) {
            e.preventDefault();
            const authUrl = gbsAdminData.apiUrl.replace('/admin/', '/auth/google/connect') + '?context=staff';
            window.open(authUrl, 'gbs_google_auth', 'width=600,height=700,scrollbars=yes');
        }
    });

    window.addEventListener('message', (e) => {
        if (e.data && e.data.gbsAuthSuccess) {
            // Case 1: Staff Wizard (Temp Token)
            if (e.data.temp_token) {
                window.googleAuthTempId = e.data.temp_token;
                const email = e.data.email || 'Connected Account';

                // Update UI (Wizard)
                const btn = document.getElementById('btn-wiz-connect-google');
                if (btn) {
                    btn.classList.add('connected');
                    btn.innerHTML = `<div class="gbs-connect-icon"><svg width="24" height="24" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.04-3.71 1.04-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg></div><div class="gbs-connect-info"><div class="gbs-connect-title">Google Calendar</div><div class="gbs-connect-sub">${email}</div><div class="gbs-connect-status" style="color:#2563eb; font-size:0.8rem;">Checking calendars...</div></div><div class="gbs-disconnect-btn" data-action="disconnect-google" style="padding:8px; color:#9ca3af; font-size:1.5rem; line-height:0.5;">&times;</div>`;
                }
                showToast('Google Account Linked!');
            }
            // Case 2: Global Settings (No Temp Token)
            else {
                showToast('Google Account Connected!');
                // Close the modal
                const modal = document.getElementById('modal-connect-calendar-overlay');
                if (modal) modal.style.display = 'none';

                // Refresh the list
                initCalendarSettingsLogic();
            }
        }
    });

    // Delegation for Disconnect
    document.addEventListener('click', (e) => {
        const btnDisconnect = e.target.closest('[data-action="disconnect-google"]');
        if (btnDisconnect) {
            e.stopPropagation();
            e.preventDefault();
            if (!confirm('Disconnect Google Account?')) return;

            window.googleAuthTempId = ''; // Clear temp
            // Note: We can only clear properly on Save.
            // But we can update UI instantly.
            const btn = document.getElementById('btn-wiz-connect-google');
            if (btn) {
                btn.classList.remove('connected');
                // Default content
                btn.innerHTML = `<div class="gbs-connect-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div><div class="gbs-connect-info"><div class="gbs-connect-title">Google Calendar</div><div class="gbs-connect-sub">Gmail, G Suite</div></div><div class="gbs-connect-action"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></div>`;
            }
            // Mark for deletion on Save?
            // Since we rely on google_auth_temp_id being set to link,
            // if we want to UNLINK, we might need a flag?
            // Currently backend unlinks if we save? 
            // Step 1633 updated Delete logic, but Manage Staff (Post) Logic?
            // If google_auth_temp_id is empty, it does NOTHING.
            // So saving won't unlink if it was already linked.
            // We need a way to Explicitly Unlink via the wizard.
            // Hack: set google_auth_temp_id = 'disconnect';
            window.googleAuthTempId = 'disconnect';
        }
    });


    // --- Calendar View Logic (Restored) ---
    let calCurrentDate = new Date();

    function initCalendarView() {
        // Nav Logic
        const btnPrev = document.getElementById('cal-prev');
        const btnNext = document.getElementById('cal-next');

        if (btnPrev) {
            btnPrev.onclick = (e) => {
                e.preventDefault();
                calCurrentDate.setMonth(calCurrentDate.getMonth() - 1);
                renderCalendar();
            };
        }

        if (btnNext) {
            btnNext.onclick = (e) => {
                e.preventDefault();
                calCurrentDate.setMonth(calCurrentDate.getMonth() + 1);
                renderCalendar();
            };
        }

        // Hook into switcher to move timezone if needed
        const btnCal = document.querySelector('.gbs-view-btn[data-view="calendar"]');
        if (btnCal) {
            btnCal.addEventListener('click', () => {
                setTimeout(checkAndMoveTimezone, 50);
            });
        }
    }

    // Move Timezone Logic
    function checkAndMoveTimezone() {
        const tzBtn = document.getElementById('gbs-timezone-btn');
        const tzDrop = document.getElementById('gbs-timezone-dropdown');
        const calNav = document.querySelector('.gbs-cal-nav-row');
        if (tzBtn && tzDrop && calNav) {
            let tzContainer = document.getElementById('gbs-cal-tz-container');
            if (!tzContainer) {
                tzContainer = document.createElement('div');
                tzContainer.id = 'gbs-cal-tz-container';
                tzContainer.className = 'relative ml-auto';
                calNav.appendChild(tzContainer);
            }
            if (!tzContainer.contains(tzBtn)) {
                tzContainer.appendChild(tzBtn);
                tzContainer.appendChild(tzDrop);
                tzDrop.classList.remove('bottom-full');
                tzDrop.classList.add('top-full', 'mt-2');
                tzDrop.style.bottom = 'auto';
                tzDrop.style.left = 'auto';
                tzDrop.style.right = '0';
            }
        }
    }

    function checkAndRestoreTimezone() {
        const tzBtn = document.getElementById('gbs-timezone-btn');
        const tzDrop = document.getElementById('gbs-timezone-dropdown');
        const footer = document.querySelector('.gbs-timezone-footer');

        if (tzBtn && tzDrop && footer) {
            if (!footer.contains(tzBtn)) {
                footer.appendChild(tzBtn);
                footer.appendChild(tzDrop);

                // Reset Styles
                tzDrop.classList.add('bottom-full');
                tzDrop.classList.remove('top-full', 'mt-2');
                tzDrop.style.bottom = '100%'; // or whatever it was, 'bottom-full' handles it usually? 
                // Tailwind bottom-full is usually bottom: 100%.
                // JS set style.bottom='auto' previously. restore it.
                tzDrop.style.bottom = '';
                tzDrop.style.left = '0';
                tzDrop.style.right = '';
            }
        }
    }

    // Expose renderCalendar globally
    window.renderCalendar = function () {
        const mount = document.getElementById('gbs-calendar-mount');
        const label = document.getElementById('cal-label');
        if (!mount) return;

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        if (label) label.textContent = `${monthNames[calCurrentDate.getMonth()]} ${calCurrentDate.getFullYear()}`;

        checkAndMoveTimezone();

        // Remove manual inline styles, let CSS handle it
        mount.removeAttribute('style');
        mount.innerHTML = '';
        mount.className = 'gbs-calendar-grid';

        const year = calCurrentDate.getFullYear();
        const month = calCurrentDate.getMonth();

        // Month calc
        const firstDate = new Date(year, month, 1, 12, 0, 0);
        let firstDay = firstDate.getDay(); // 0=Sun
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const startOffset = (firstDay + 6) % 7;

        // Empty Cells (Previous Month)
        for (let i = 0; i < startOffset; i++) {
            const cell = document.createElement('div');
            cell.className = 'gbs-cal-cell disabled';
            // Calc Date logic
            const prevMonthLastDay = new Date(year, month, 0).getDate();
            const pDay = prevMonthLastDay - (startOffset - 1 - i);

            cell.innerHTML = `<span class="gbs-cal-date">${pDay}</span>`;
            mount.appendChild(cell);
        }

        // Days
        const today = new Date();
        const isCurrentMonth = (today.getFullYear() === year && today.getMonth() === month);

        for (let d = 1; d <= daysInMonth; d++) {
            const dbDay = (firstDay + (d - 1)) % 7;
            const slots = (typeof availability !== 'undefined' ? availability : []).filter(a => parseInt(a.day_of_week) == dbDay && !a.is_closed && a.is_closed != '1');
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const overrides = (typeof holidays !== 'undefined' ? holidays : []).filter(h => h.date === dateStr);

            let displaySlots = [];
            let isClosed = (slots.length === 0);
            let hasOverride = false;

            if (overrides.length > 0) {
                hasOverride = true;
                if (overrides.some(o => o.is_closed == 1)) {
                    isClosed = true;
                    displaySlots = [];
                } else {
                    isClosed = false;
                    displaySlots = overrides.map(o => ({ start: o.start_time, end: o.end_time }));
                }
            } else {
                if (!isClosed) {
                    displaySlots = slots.map(s => ({ start: s.start_time, end: s.end_time }));
                }
            }

            displaySlots.sort((a, b) => (a.start || '').localeCompare(b.start || ''));

            let contentHtml = '';

            if (isClosed) {
                if (hasOverride && isClosed) {
                    contentHtml = `<span class="gbs-cal-holiday">Staff Day Off</span>`;
                }
            } else {
                const times = displaySlots.map(s => {
                    const fs = s.start ? s.start.substring(0, 5) : '';
                    const fe = s.end ? s.end.substring(0, 5) : '';
                    return `${fs} - ${fe}`;
                }).join('<br>');
                contentHtml = `<span class="gbs-cal-hours">${times}</span>`;
            }

            const cell = document.createElement('div');
            cell.className = 'gbs-cal-cell';

            const isToday = (isCurrentMonth && today.getDate() === d);

            cell.innerHTML = `
                <span class="gbs-cal-date" style="${isToday ? 'color:var(--gbs-primary);' : ''}">${d}</span>
                ${contentHtml}
            `;
            mount.appendChild(cell);
        }
    }


    // Parse init immediately if we are in calendar view
    initCalendarView();
    if (document.getElementById('gbs-view-calendar') && !document.getElementById('gbs-view-calendar').classList.contains('hidden')) {
        renderCalendar();
    }

});
