{{-- Booking Modal - Simply Motoring - Light Theme --}}
<style>
    /* ── Clip-path: panel + buttons only ─────────────────────── */
    .bm-clip     { clip-path: polygon(12px 0, 100% 0, 100% calc(100% - 12px), calc(100% - 12px) 100%, 0 100%, 0 12px); }
    .bm-clip-lg  { clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%, 0 20px); }
    .bm-panel-clip { clip-path: polygon(28px 0, 100% 0, 100% calc(100% - 28px), calc(100% - 28px) 100%, 0 100%, 0 28px); }

    #bookingModal { font-family: 'Geist', 'Inter', sans-serif; }

    /* ── Custom scrollbar ─────────────────────────────────────── */
    #bmScrollBody { scrollbar-width: thin; scrollbar-color: #FF6900 #f3f4f6; }
    #bmScrollBody::-webkit-scrollbar { width: 5px; }
    #bmScrollBody::-webkit-scrollbar-track { background: #f3f4f6; }
    #bmScrollBody::-webkit-scrollbar-thumb { background: #FF6900; border-radius: 0; }
    #bmScrollBody::-webkit-scrollbar-thumb:hover { background: #e55e00; }

    /* ── Service cards ────────────────────────────────────────── */
    .bm-service-card { border: 1.5px solid #d1d5db; background: #fff; border-radius: 0; transition: border-color .2s, background .2s; }
    .bm-service-card:hover { border-color: #FF6900; background: #fff7f2; }
    .bm-service-card.selected { border-color: #FF6900 !important; background: #fff7f2 !important; }
    .bm-service-card.selected .bm-radio { background: #FF6900; border-color: #FF6900; }
    .bm-service-card.selected .bm-radio::after { content: ''; display: block; width: 8px; height: 8px; background: white; border-radius: 50%; margin: auto; }

    /* ── Calendar days ────────────────────────────────────────── */
    .bm-cal-day { border-radius: 4px; transition: background .15s, color .15s; }
    .bm-cal-day:not(:disabled):hover { background: #FF6900; color: #fff; }
    .bm-cal-day.bm-selected { background: linear-gradient(135deg,#FF6900,#FB5200) !important; color: #fff !important; font-weight: 700; }

    /* ── Time slots ───────────────────────────────────────────── */
    .bm-slot { border: 1.5px solid #e5e7eb; background: #fff; color: #111827; font-weight: 600; font-size: 0.8rem; border-radius: 4px; transition: border-color .15s, color .15s, background .15s; }
    .bm-slot:hover:not(.selected) { border-color: #FF6900; color: #FF6900; background: #fff7f2; }
    .bm-slot.selected { background: #FF6900; color: #fff; border-color: #FF6900; }

    /* ── Inputs ───────────────────────────────────────────────── */
    .bm-input { border: 1.5px solid #d1d5db; background: #fff; color: #111827; border-radius: 4px; transition: border-color .2s, box-shadow .2s; }
    .bm-input::placeholder { color: #9ca3af; }
    .bm-input:focus { outline: none; border-color: #FF6900; box-shadow: 0 0 0 3px rgba(255,105,0,0.12); }

    /* ── Custom option picker ─────────────────────────────────── */
    .bm-opt-btn {
        display: flex; align-items: center; gap: 12px;
        width: 100%; padding: 0.75rem 1rem;
        border: 1.5px solid #e5e7eb;
        background: #fff; border-radius: 6px; cursor: pointer;
        transition: border-color .15s, background .15s;
        text-align: left;
    }
    .bm-opt-btn:hover:not(.bm-opt-selected) { border-color: #FF6900; background: #fff7f2; }
    .bm-opt-btn.bm-opt-selected {
        border-color: #FF6900 !important; background: #fff7f2 !important;
    }
    .bm-opt-btn.bm-opt-selected .bm-opt-radio { background: #FF6900; border-color: #FF6900; }
    .bm-opt-btn.bm-opt-selected .bm-opt-radio::after {
        content: ''; display: block; width: 7px; height: 7px;
        background: #fff; border-radius: 50%; margin: auto;
    }
    .bm-opt-radio {
        width: 18px; height: 18px; border-radius: 50%;
        border: 2px solid #d1d5db; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s, border-color .15s;
    }
    .bm-opt-label { font-size: 0.875rem; font-weight: 600; color: #111827; }

    /* ── Step indicator ──────────────────────────────────────── */
    .bm-step-num { transition: background .3s, color .3s; }
    .bm-step-line { transition: background .5s; }

    /* ── Loading spinner ─────────────────────────────────────── */
    @keyframes bm-spin { to { transform: rotate(360deg); } }
    .bm-spin { animation: bm-spin 0.7s linear infinite; }

    /* ── Success icon ────────────────────────────────────────── */
    .bm-success-icon { border-radius: 50%; }
</style>

<div id="bookingModal"
    class="fixed inset-0 z-[9999] items-end sm:items-center justify-center"
    style="display:none"
    role="dialog" aria-modal="true">

    {{-- Backdrop --}}
    <div id="bmBackdrop"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>

    {{-- Panel --}}
    <div id="bmPanel"
        class="relative bg-white w-full sm:max-w-[520px] bm-panel-clip shadow-2xl flex flex-col max-h-[95vh] sm:max-h-[90vh] transition-all duration-300 translate-y-8 opacity-0 sm:translate-y-0 sm:scale-95">

        {{-- Header --}}
        <div class="shrink-0 px-7 pt-6 pb-5 border-b border-gray-100 flex items-start justify-between">
            <div>
                <p class="text-[#FF6900] text-[10px] font-bold uppercase tracking-[0.25em] mb-1">Simply Motoring</p>
                <h2 id="bm-title" class="font-bold text-xl uppercase tracking-[-0.03em] text-gray-950 leading-tight">
                    Book Your Service
                </h2>
            </div>
            <button id="bmClose"
                class="bm-clip w-9 h-9 flex items-center justify-center border border-gray-200 hover:border-[#FF6900] hover:text-[#FF6900] text-gray-400 transition-colors mt-0.5 bg-white">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Step Progress --}}
        <div class="shrink-0 px-7 py-4 border-b border-gray-100 bg-gray-50/60">
            <div class="flex items-center gap-0">
                @php $steps = ['Service', 'Date', 'Time', 'Details']; @endphp
                @foreach($steps as $i => $label)
                    <div class="flex items-center {{ $i < count($steps)-1 ? 'flex-1' : '' }} bm-step-wrap" data-step-indicator="{{ $i+1 }}">
                        <div class="flex items-center gap-2 shrink-0">
                            <div class="bm-step-num w-6 h-6 rounded-sm flex items-center justify-center text-[11px] font-bold border border-gray-300 text-gray-400 transition-all duration-300">
                                <span class="bm-step-num-inner">{{ $i+1 }}</span>
                            </div>
                            <span class="bm-step-lbl text-[11px] font-bold uppercase tracking-[0.08em] text-gray-400 transition-colors duration-300 hidden sm:block">{{ $label }}</span>
                        </div>
                        @if($i < count($steps)-1)
                            <div class="bm-step-line flex-1 h-px bg-gray-200 mx-2 transition-colors duration-500"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Scrollable Body --}}
        <div id="bmScrollBody" class="flex-1 overflow-y-auto">

            {{-- Step 1: Select Service --}}
            <div data-step-panel="1" class="p-6">
                <p class="text-gray-600 text-xs mb-5 uppercase tracking-[0.1em] font-semibold">Choose a service to get started</p>
                <div id="bmServiceList" class="flex flex-col gap-2.5">
                    @for($i = 0; $i < 2; $i++)
                        <div class="h-[76px] bg-gray-100 rounded animate-pulse"></div>
                    @endfor
                </div>
            </div>

            {{-- Step 2: Date --}}
            <div data-step-panel="2" class="p-6 hidden">
                <p class="text-gray-600 text-xs mb-5 uppercase tracking-[0.1em] font-semibold">Pick an available date</p>
                <div class="border border-gray-200 rounded-sm overflow-hidden">
                    {{-- Month nav --}}
                    <div class="flex items-center justify-between px-4 py-3.5 border-b border-gray-100 bg-gray-50">
                        <button id="bmPrevMonth" class="bm-clip w-8 h-8 flex items-center justify-center border border-gray-200 hover:border-[#FF6900] hover:text-[#FF6900] text-gray-400 transition-colors bg-white">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>
                        <span id="bmMonthLabel" class="font-bold uppercase tracking-[0.05em] text-gray-800 text-sm"></span>
                        <button id="bmNextMonth" class="bm-clip w-8 h-8 flex items-center justify-center border border-gray-200 hover:border-[#FF6900] hover:text-[#FF6900] text-gray-400 transition-colors bg-white">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                    {{-- Day labels --}}
                    <div class="grid grid-cols-7 px-3 pt-3 bg-white">
                        @foreach(['Mo','Tu','We','Th','Fr','Sa','Su'] as $d)
                            <div class="text-center text-[10px] font-bold uppercase tracking-[0.08em] text-gray-500 pb-2">{{ $d }}</div>
                        @endforeach
                    </div>
                    <div id="bmCalendarGrid" class="grid grid-cols-7 gap-1 px-3 pb-4 bg-white"></div>
                </div>
            </div>

            {{-- Step 3: Time --}}
            <div data-step-panel="3" class="p-6 hidden">
                <p class="text-gray-600 text-xs mb-1 uppercase tracking-[0.1em] font-semibold">Available times for</p>
                <p id="bmSelectedDateLabel" class="font-bold text-gray-900 text-base uppercase tracking-[-0.02em] mb-5"></p>
                <div id="bmSlotsList" class="grid grid-cols-4 gap-2"></div>
                <p id="bmNoSlots" class="hidden text-center text-gray-400 text-sm py-10 uppercase tracking-[0.05em]">
                    No slots available — try another date.
                </p>
            </div>

            {{-- Step 4: Details --}}
            <div data-step-panel="4" class="p-6 hidden">
                <p class="text-gray-600 text-xs mb-5 uppercase tracking-[0.1em] font-semibold">Your details</p>
                <form id="bmForm" novalidate class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">
                            Full Name <span class="text-[#FF6900]">*</span>
                        </label>
                        <input type="text" name="customer_name" required
                            class="bm-input w-full px-4 py-3 text-sm"
                            placeholder="John Smith">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">
                            Email Address <span class="text-[#FF6900]">*</span>
                        </label>
                        <input type="email" name="customer_email" required
                            class="bm-input w-full px-4 py-3 text-sm"
                            placeholder="john@example.com">
                    </div>
                    <div id="bmPhoneWrap">
                        <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">Phone Number</label>
                        <input type="tel" name="customer_phone"
                            class="bm-input w-full px-4 py-3 text-sm"
                            placeholder="+44 7700 900000">
                    </div>
                    <div id="bmVehicleRegWrap">
                        <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">Vehicle Registration</label>
                        <input type="text" name="vehicle_reg"
                            class="bm-input w-full px-4 py-3 text-sm uppercase font-mono tracking-wider"
                            placeholder="AB12 CDE">
                    </div>
                    <div id="bmSubServiceWrap" class="hidden">
                        <label id="bmSubServiceLabel" class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">
                            Select Option <span class="text-[#FF6900]">*</span>
                        </label>
                        <input type="hidden" name="sub_service" id="bmSubService" value="">
                        <div id="bmSubServiceList" class="flex flex-col gap-2"></div>
                    </div>
                    <div id="bmFormError" class="hidden text-sm text-red-600 bg-red-50 border border-red-200 rounded px-4 py-3"></div>
                </form>
            </div>

            {{-- Step 5: Confirmed --}}
            <div data-step-panel="5" class="p-6 hidden">
                <div class="flex flex-col items-center text-center py-4">
                    <div class="bm-success-icon w-16 h-16 flex items-center justify-center mb-5"
                        style="background: linear-gradient(135deg,#FF6900,#FB5200)">
                        <i class="fa-solid fa-check text-white text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-2xl uppercase tracking-[-0.03em] text-gray-900 mb-2">You're Booked In!</h3>
                    <p class="text-gray-400 text-sm mb-6 max-w-[280px]">
                        A confirmation will be sent to your email. We look forward to seeing you.
                    </p>
                    <div id="bmSummary" class="w-full text-left border border-gray-100 bg-gray-50 rounded-sm p-5 flex flex-col gap-3 mb-6"></div>
                    <button id="bmDone"
                        class="bm-clip-lg w-full flex items-center justify-center gap-3 text-white font-bold text-sm uppercase tracking-[0.15em] py-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                        style="background: linear-gradient(135deg,#FF6900,#FB5200)">
                        Done <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div id="bmFooter" class="shrink-0 px-6 py-4 border-t border-gray-100 bg-gray-50/60 flex items-center justify-between gap-4">
            <button id="bmBack"
                class="bm-clip flex items-center gap-2 text-xs font-bold uppercase tracking-[0.15em] text-gray-400 hover:text-gray-700 border border-gray-200 hover:border-gray-400 px-4 py-2.5 bg-white transition-colors invisible">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back
            </button>
            <button id="bmNext"
                class="bm-clip ml-auto flex items-center gap-2.5 text-white font-bold text-sm uppercase tracking-[0.12em] px-7 py-3 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-30 disabled:cursor-not-allowed disabled:translate-y-0"
                style="background: linear-gradient(135deg,#FF6900 0%,#FB5200 100%)" disabled>
                Next <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
            <button id="bmSubmit"
                class="bm-clip ml-auto items-center gap-2.5 text-white font-bold text-sm uppercase tracking-[0.12em] px-7 py-3 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-30 disabled:cursor-not-allowed"
                style="display:none;background: linear-gradient(135deg,#FF6900 0%,#FB5200 100%)" disabled>
                Confirm <i class="fa-solid fa-check text-xs"></i>
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    // ── State ─────────────────────────────────────────────────────────────────
    const state = {
        step: 1,
        services: [],
        selectedService: null,
        calYear: null,
        calMonth: null,
        selectedDate: null,
        slots: [],
        selectedSlot: null,
        holidayDates: [],
    };

    const SUB_OPTIONS = {
        'full service':    ['Health Check', 'Interim Service', 'Full Service'],
        'interim service': ['Health Check', 'Interim Service', 'Full Service'],
        'health check':    ['Health Check', 'Interim Service', 'Full Service'],
        'mot':             ['MOT', 'MOT & Service'],
        'mot test':        ['MOT', 'MOT & Service'],
        'mot and service': ['MOT', 'MOT & Service'],
    };

    // ── DOM refs ───────────────────────────────────────────────────────────────
    const modal     = document.getElementById('bookingModal');
    const backdrop  = document.getElementById('bmBackdrop');
    const panel     = document.getElementById('bmPanel');
    const closeBtn  = document.getElementById('bmClose');
    const backBtn   = document.getElementById('bmBack');
    const nextBtn   = document.getElementById('bmNext');
    const submitBtn = document.getElementById('bmSubmit');
    const doneBtn   = document.getElementById('bmDone');

    // ── Open / Close ──────────────────────────────────────────────────────────
    function openModal(serviceSlug) {
        modal.style.display = 'flex';
        requestAnimationFrame(() => {
            backdrop.classList.replace('opacity-0', 'opacity-100');
            panel.classList.remove('translate-y-8', 'opacity-0', 'sm:scale-95');
            panel.classList.add('sm:scale-100', 'opacity-100');
        });
        document.body.classList.add('overflow-hidden');
        state._pendingSlug = serviceSlug ? serviceSlug.toLowerCase() : null;
        goToStep(1);
        if (state.services.length) {
            renderServices();
            if (state._pendingSlug) autoSelect(state._pendingSlug);
        } else {
            loadServices();
        }
    }

    function closeModal() {
        backdrop.classList.replace('opacity-100', 'opacity-0');
        panel.classList.add('translate-y-8', 'opacity-0');
        panel.classList.remove('sm:scale-100');
        panel.classList.add('sm:scale-95');
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.classList.remove('overflow-hidden');
            resetState();
        }, 300);
    }

    function resetState() {
        state.step            = 1;
        state.selectedService = null;
        state.selectedDate    = null;
        state.slots           = [];
        state.selectedSlot    = null;
        state.holidayDates    = [];
        state._pendingSlug    = null;
        const f = document.getElementById('bmForm');
        if (f) f.reset();
        const subInput = document.getElementById('bmSubService');
        if (subInput) subInput.value = '';
        const subList = document.getElementById('bmSubServiceList');
        if (subList) subList.innerHTML = '';
        document.getElementById('bmFormError')?.classList.add('hidden');
    }

    // ── Steps ─────────────────────────────────────────────────────────────────
    function goToStep(n) {
        state.step = n;
        document.querySelectorAll('[data-step-panel]').forEach(el => {
            el.classList.toggle('hidden', parseInt(el.dataset.stepPanel) !== n);
        });
        updateStepIndicator(n);
        updateFooter(n);
        if (n === 2) initCalendar();
        if (n === 3) loadSlots();
        if (n === 4) updateFormFields();
        // scroll to top on step change
        const scroll = document.getElementById('bmScrollBody');
        if (scroll) scroll.scrollTop = 0;
    }

    function updateStepIndicator(current) {
        document.querySelectorAll('.bm-step-wrap').forEach(wrap => {
            const s      = parseInt(wrap.dataset.stepIndicator);
            const numEl  = wrap.querySelector('.bm-step-num');
            const inner  = wrap.querySelector('.bm-step-num-inner');
            const lbl    = wrap.querySelector('.bm-step-lbl');
            const line   = wrap.querySelector('.bm-step-line');

            numEl.style.background = '';
            numEl.style.borderColor = '';
            numEl.style.color = '';

            if (s < current) {
                numEl.style.background   = '#22c55e';
                numEl.style.borderColor  = '#22c55e';
                numEl.style.color        = '#fff';
                if (inner) inner.innerHTML = '<i class="fa-solid fa-check" style="font-size:9px"></i>';
                lbl  && (lbl.style.color  = '#22c55e');
                line && (line.style.background = '#22c55e');
            } else if (s === current) {
                numEl.style.background  = 'linear-gradient(135deg,#FF6900,#FB5200)';
                numEl.style.borderColor = '#FF6900';
                numEl.style.color       = '#fff';
                if (inner) inner.textContent = s;
                lbl  && (lbl.style.color  = '#FF6900');
                line && (line.style.background = '');
            } else {
                numEl.style.borderColor = '#e5e7eb';
                numEl.style.color       = '#d1d5db';
                if (inner) inner.textContent = s;
                lbl  && (lbl.style.color  = '');
                line && (line.style.background = '');
            }
        });
    }

    function updateFooter(step) {
        const isResult = step === 5;
        backBtn.classList.toggle('invisible', step <= 1 || isResult);
        nextBtn.classList.toggle('hidden', step === 4 || isResult);
        submitBtn.style.display = (step === 4 && !isResult) ? 'flex' : 'none';
        document.getElementById('bmFooter').classList.toggle('hidden', isResult);
        refreshNextState(step);
    }

    function refreshNextState(step) {
        if (step === 1) nextBtn.disabled = !state.selectedService;
        if (step === 2) nextBtn.disabled = !state.selectedDate;
        if (step === 3) nextBtn.disabled = !state.selectedSlot;
        if (step === 4) submitBtn.disabled = false;
    }

    // ── Step 1: Services ──────────────────────────────────────────────────────
    function loadServices() {
        fetch('/api/booking/services')
            .then(r => r.json())
            .then(data => {
                state.services = data;
                renderServices();
                if (state._pendingSlug) autoSelect(state._pendingSlug);
            })
            .catch(() => {
                document.getElementById('bmServiceList').innerHTML =
                    '<p class="text-red-500 text-sm">Could not load services.</p>';
            });
    }

    function renderServices() {
        const list = document.getElementById('bmServiceList');
        if (!state.services.length) {
            list.innerHTML = '<p class="text-gray-400 text-sm">No active services available.</p>';
            return;
        }
        list.innerHTML = state.services.map(s => `
            <button type="button"
                class="bm-service-card w-full flex items-center gap-4 p-4 text-left"
                data-id="${s.id}">
                <div class="w-11 h-11 rounded shrink-0 flex items-center justify-center"
                    style="background:linear-gradient(135deg,#FF6900,#FB5200)">
                    <i class="fa-solid fa-wrench text-white text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold uppercase tracking-[-0.02em] text-gray-900 text-sm">${s.name}</p>
                    <p class="text-gray-400 text-xs mt-0.5">${s.duration_minutes} min &nbsp;·&nbsp; ${(s.options && s.options.length) ? 'From ' : ''}£${parseFloat(s.price ?? 0).toFixed(2)}</p>
                </div>
                <div class="bm-radio w-5 h-5 border-2 border-gray-200 rounded-full shrink-0 flex items-center justify-center transition-all"></div>
            </button>
        `).join('');

        list.querySelectorAll('.bm-service-card').forEach(btn => {
            btn.addEventListener('click', () => selectService(parseInt(btn.dataset.id)));
        });
    }

    function selectService(id) {
        state.selectedService = state.services.find(s => s.id === id) ?? null;
        state.holidayDates    = [];
        document.querySelectorAll('.bm-service-card').forEach(btn => {
            btn.classList.toggle('selected', parseInt(btn.dataset.id) === id);
        });
        nextBtn.disabled = false;
        fetchClosedDates(id);
    }

    function fetchClosedDates(serviceId) {
        fetch(`/api/booking/closed-dates?service_id=${serviceId}`)
            .then(r => r.json())
            .then(dates => {
                state.holidayDates = dates;
                if (state.step === 2) drawCalendar();
            })
            .catch(() => {});
    }

    function autoSelect(slug) {
        const match = state.services.find(s => s.name.toLowerCase().includes(slug));
        if (match) {
            selectService(match.id);
            state._pendingSlug = null;
            goToStep(2);
        }
    }

    // ── Step 2: Calendar ──────────────────────────────────────────────────────
    function initCalendar() {
        if (!state.calYear) {
            const now = new Date();
            state.calYear  = now.getFullYear();
            state.calMonth = now.getMonth();
        }
        drawCalendar();
    }

    function drawCalendar() {
        const y = state.calYear, m = state.calMonth;
        const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        document.getElementById('bmMonthLabel').textContent = `${months[m]} ${y}`;

        const today   = new Date(); today.setHours(0,0,0,0);
        const first   = new Date(y, m, 1);
        let startDow  = first.getDay();
        startDow      = startDow === 0 ? 6 : startDow - 1; // Mon-first

        const daysInMonth    = new Date(y, m + 1, 0).getDate();
        const grid           = document.getElementById('bmCalendarGrid');
        const minNotice      = state.selectedService?.min_notice_hours ?? 4;
        const advance        = state.selectedService?.advance_booking_days ?? 60;
        const closedWeekdays = state.selectedService?.closed_days ?? [];
        const holidayDates   = state.holidayDates;
        
        const now = new Date();
        const earliestBookingTime = new Date(now.getTime() + minNotice * 3600000);
        const minDate = new Date(earliestBookingTime);
        minDate.setHours(0, 0, 0, 0); // Truncate to midnight to compare with calendar dates

        const maxDate        = new Date(today.getTime() + advance * 86400000);

        grid.innerHTML = '';
        for (let i = 0; i < startDow; i++) grid.insertAdjacentHTML('beforeend', '<div></div>');

        for (let d = 1; d <= daysInMonth; d++) {
            const date        = new Date(y, m, d);
            const dateStr     = fmtDate(date);
            const isPast      = date < minDate;
            const isFar       = date > maxDate;
            const isWeekdayOff= closedWeekdays.includes(date.getDay());
            const isHoliday   = holidayDates.includes(dateStr);
            const isSel       = state.selectedDate === dateStr;
            const isDisabled  = isPast || isFar || isWeekdayOff || isHoliday;

            const btn = document.createElement('button');
            btn.type  = 'button';
            btn.textContent = d;

            if (isDisabled) {
                btn.disabled = true;
                if (isPast || isFar) {
                    btn.className = 'bm-cal-day w-full aspect-square text-xs text-gray-200 cursor-not-allowed';
                } else {
                    btn.className = 'bm-cal-day w-full aspect-square text-xs text-red-300 cursor-not-allowed line-through';
                    btn.title = isHoliday ? 'Holiday / Day off' : 'Closed';
                }
            } else if (isSel) {
                btn.className = 'bm-cal-day bm-selected w-full aspect-square text-xs';
            } else {
                btn.className = 'bm-cal-day w-full aspect-square text-xs font-semibold text-gray-800 hover:text-white transition-all';
                btn.addEventListener('click', () => {
                    state.selectedDate = dateStr;
                    nextBtn.disabled   = false;
                    document.getElementById('bmSelectedDateLabel').textContent =
                        date.toLocaleDateString('en-GB', {weekday:'long', day:'numeric', month:'long'}).toUpperCase();
                    drawCalendar();
                });
            }
            grid.appendChild(btn);
        }
    }

    document.getElementById('bmPrevMonth').addEventListener('click', () => {
        if (--state.calMonth < 0) { state.calMonth = 11; state.calYear--; }
        drawCalendar();
    });
    document.getElementById('bmNextMonth').addEventListener('click', () => {
        if (++state.calMonth > 11) { state.calMonth = 0; state.calYear++; }
        drawCalendar();
    });

    function fmtDate(d) {
        return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
    }

    // ── Step 3: Slots ─────────────────────────────────────────────────────────
    function loadSlots() {
        const list  = document.getElementById('bmSlotsList');
        const empty = document.getElementById('bmNoSlots');
        list.innerHTML = `<div class="col-span-4 text-center text-gray-300 text-xs uppercase tracking-widest py-8">
            <i class="fa-solid fa-circle-notch bm-spin mr-2"></i>Loading…
        </div>`;
        empty.classList.add('hidden');
        state.selectedSlot = null;
        nextBtn.disabled   = true;

        fetch(`/api/booking/slots?date=${state.selectedDate}&service_id=${state.selectedService.id}`)
            .then(r => r.json())
            .then(slots => { state.slots = slots; renderSlots(); })
            .catch(() => {
                list.innerHTML = '<div class="col-span-4 text-center text-red-400 text-xs py-6">Could not load slots.</div>';
            });
    }

    function renderSlots() {
        const list  = document.getElementById('bmSlotsList');
        const empty = document.getElementById('bmNoSlots');

        if (!state.slots.length) {
            list.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }
        empty.classList.add('hidden');

        list.innerHTML = state.slots.map(slot => `
            <button type="button" class="bm-slot py-3"
                data-start="${slot.start}" data-bay="${slot.bay_id}">
                ${slot.display}
            </button>
        `).join('');

        list.querySelectorAll('.bm-slot').forEach(btn => {
            btn.addEventListener('click', () => {
                list.querySelectorAll('.bm-slot').forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                state.selectedSlot = { start: btn.dataset.start, bay_id: btn.dataset.bay };
                nextBtn.disabled   = false;
            });
        });
    }

    // ── Step 4: Form fields ───────────────────────────────────────────────────
    function updateFormFields() {
        const svc  = state.selectedService;
        const wrap = document.getElementById('bmSubServiceWrap');
        const sel  = document.getElementById('bmSubService');

        if (!svc) { wrap.classList.add('hidden'); return; }

        // Show/hide optional fields based on service config
        const phoneWrap = document.getElementById('bmPhoneWrap');
        if (phoneWrap) phoneWrap.classList.toggle('hidden', !svc.collect_phone);
        const regWrap = document.getElementById('bmVehicleRegWrap');
        if (regWrap) regWrap.classList.toggle('hidden', !svc.collect_vehicle_reg);

        // Sub-service options — custom card picker
        let optionsToRender = [];
        let labelToUse = 'Select Option';

        if (svc.options && Array.isArray(svc.options) && svc.options.length > 0) {
            optionsToRender = svc.options;
            if (svc.options_label) {
                labelToUse = svc.options_label;
            }
        } else {
            const key  = svc.name.toLowerCase();
            const opts = Object.entries(SUB_OPTIONS).find(([k]) => key.includes(k));
            if (opts) {
                optionsToRender = opts[1];
            }
        }

        const list = document.getElementById('bmSubServiceList');
        const labelEl = document.getElementById('bmSubServiceLabel');

        if (optionsToRender.length > 0 && list) {
            if (labelEl) {
                labelEl.innerHTML = `${labelToUse} <span class="text-[#FF6900]">*</span>`;
            }
            sel.value  = '';
            list.innerHTML = '';
            optionsToRender.forEach(o => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'bm-opt-btn';
                btn.dataset.value = o;
                btn.innerHTML = `<span class="bm-opt-radio"></span><span class="bm-opt-label">${o}</span>`;
                btn.addEventListener('click', () => {
                    list.querySelectorAll('.bm-opt-btn').forEach(b => b.classList.remove('bm-opt-selected'));
                    btn.classList.add('bm-opt-selected');
                    sel.value = o;
                });
                list.appendChild(btn);
            });
            wrap.classList.remove('hidden');
        } else {
            wrap.classList.add('hidden');
            if (sel) sel.value = '';
        }
    }

    // ── Submit ────────────────────────────────────────────────────────────────
    submitBtn.addEventListener('click', async () => {
        const form = document.getElementById('bmForm');
        const err  = document.getElementById('bmFormError');
        err.classList.add('hidden');

        const name  = form.querySelector('[name=customer_name]').value.trim();
        const email = form.querySelector('[name=customer_email]').value.trim();
        if (!name || !email) {
            err.textContent = 'Please fill in your name and email address.';
            err.classList.remove('hidden');
            return;
        }

        const subWrap = document.getElementById('bmSubServiceWrap');
        const subVal  = document.getElementById('bmSubService').value;
        if (!subWrap.classList.contains('hidden') && !subVal) {
            err.textContent = 'Please select a service option.';
            err.classList.remove('hidden');
            return;
        }

        setSubmitLoading(true);

        const payload = {
            service_id:     state.selectedService.id,
            start:          state.selectedSlot.start,
            bay_id:         state.selectedSlot.bay_id || 0,
            customer_name:  name,
            customer_email: email,
            customer_phone: form.querySelector('[name=customer_phone]')?.value.trim() || '',
            vehicle_reg:    form.querySelector('[name=vehicle_reg]')?.value.trim() || '',
            sub_service:    subVal || '',
        };

        try {
            const res  = await fetch('/api/booking/store', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
                body:    JSON.stringify(payload),
            });
            const data = await res.json();

            if (data.success) {
                buildSummary(payload);
                goToStep(5);
            } else {
                err.textContent = data.message || 'Booking failed. Please try again.';
                err.classList.remove('hidden');
                setSubmitLoading(false);
            }
        } catch {
            err.textContent = 'Network error. Please try again.';
            err.classList.remove('hidden');
            setSubmitLoading(false);
        }
    });

    function setSubmitLoading(loading) {
        submitBtn.disabled = loading;
        submitBtn.innerHTML = loading
            ? '<i class="fa-solid fa-circle-notch bm-spin text-xs mr-2"></i>Booking…'
            : 'Confirm <i class="fa-solid fa-check text-xs ml-1"></i>';
    }

    function buildSummary(payload) {
        const d = new Date(state.selectedSlot.start);
        const rows = [
            ['Service', state.selectedService.name + (payload.sub_service ? ` · ${payload.sub_service}` : '')],
            ['Date',    d.toLocaleDateString('en-GB', {weekday:'long', day:'numeric', month:'long', year:'numeric'})],
            ['Time',    d.toLocaleTimeString('en-GB', {hour:'2-digit', minute:'2-digit'})],
            ['Name',    payload.customer_name],
            ['Email',   payload.customer_email],
        ];
        if (payload.vehicle_reg) rows.push(['Reg', payload.vehicle_reg.toUpperCase()]);

        document.getElementById('bmSummary').innerHTML = rows.map(([k, v]) => `
            <div class="flex items-baseline gap-3">
                <span class="w-14 shrink-0 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400">${k}</span>
                <span class="text-sm font-semibold text-gray-800">${v}</span>
            </div>
        `).join('');
    }

    // ── Navigation ────────────────────────────────────────────────────────────
    backBtn.addEventListener('click', () => { if (state.step > 1) goToStep(state.step - 1); });
    nextBtn.addEventListener('click', () => { if (state.step < 4) goToStep(state.step + 1); });
    doneBtn.addEventListener('click', closeModal);
    closeBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    // ── Global + Auto-attach ──────────────────────────────────────────────────
    window.openBookingModal = openModal;

    function attachBookTriggers() {
        // data-book-trigger attribute (pre-selects a service slug)
        document.querySelectorAll('[data-book-trigger]').forEach(el => {
            if (el._bmAttached) return;
            el._bmAttached = true;
            el.addEventListener('click', e => {
                e.preventDefault();
                let slug = el.dataset.bookTrigger || '';
                if (!slug) {
                    const text = (el.textContent || '').trim().toLowerCase();
                    if (text.includes('mot')) slug = 'mot';
                }
                openModal(slug);
            });
        });

        // <a href="#book"> links
        document.querySelectorAll('a[href="#book"]').forEach(el => {
            if (el._bmAttached) return;
            el._bmAttached = true;
            el.addEventListener('click', e => {
                e.preventDefault();
                const text = (el.textContent || '').trim().toLowerCase();
                let slug = '';
                if (text.includes('mot')) slug = 'mot';
                openModal(slug);
            });
        });

        // Any <button> or <a> whose visible text starts with "Book" (case-insensitive)
        document.querySelectorAll('button, a').forEach(el => {
            if (el._bmAttached || el.closest('#bookingModal')) return;
            const text = (el.textContent || '').trim().toLowerCase();
            if (text.startsWith('book')) {
                el._bmAttached = true;
                // Detect service from button text to pre-select + skip to date step
                let slug = '';
                if (text.includes('mot')) slug = 'mot';
                el.addEventListener('click', e => { e.preventDefault(); openModal(slug); });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', attachBookTriggers);
})();
</script>