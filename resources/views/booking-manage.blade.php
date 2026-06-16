@extends('layouts.main')

@section('meta_title', 'Manage Your Booking | Simply Motoring')
@section('meta_description', 'View, edit, reschedule or cancel your Simply Motoring booking.')

@section('content')
<style>
    .bkm-num { width:24px; height:24px; border-radius:50%; background:#FF6900; color:#fff; font-size:12px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .bkm-h { font-size:13px; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; color:#111827; }
    .bkm-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; color:#6b7280; }
    .bkm-cal-day { border-radius:6px; transition:background .15s, color .15s; }
    .bkm-cal-day:not(:disabled):hover { background:#FF6900; color:#fff; }
    .bkm-cal-day.sel { background:linear-gradient(135deg,#FF6900,#FB5200) !important; color:#fff !important; font-weight:700; }
</style>

<section class="max-w-2xl mx-auto px-4 py-12 md:py-16">
    <div class="mb-7">
        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#FF6900]">Simply Motoring</p>
        <h1 class="text-2xl md:text-[28px] font-extrabold text-gray-900 mt-1">Manage Your Booking</h1>
        <p class="text-gray-500 text-sm mt-1">Reschedule, change service, update your details or cancel — no login needed.</p>
    </div>

    <div id="bkmAlert" class="hidden mb-6 rounded-xl px-4 py-3 text-sm"></div>

    @if ($booking->status === 'cancelled')
        <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 text-sm">
            This booking has been <strong>cancelled</strong>. If this was a mistake, please make a new booking.
        </div>
    @else
        <div id="bkmForm" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Current appointment banner --}}
            <div class="bg-gray-900 px-6 md:px-8 py-5">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-white/45">Current appointment</p>
                <p class="text-white font-semibold text-[15px] mt-1.5">
                    {{ $booking->service->name ?? 'Service' }}@if($booking->sub_service) · {{ $booking->sub_service }}@endif
                    <span class="text-white/55 font-normal">&nbsp;·&nbsp; {{ $booking->start_datetime->format('D j M Y') }} at {{ $booking->start_datetime->format('H:i') }}</span>
                </p>
            </div>

            <div class="p-6 md:p-8 space-y-9">

                {{-- 1 · Service --}}
                <div>
                    <div class="flex items-center gap-2.5 mb-4"><span class="bkm-num">1</span><h3 class="bkm-h">Service</h3></div>
                    <div id="bkmServiceList" class="space-y-2.5"></div>
                    <div id="bkmOptWrap" class="hidden mt-4">
                        <p id="bkmOptLabel" class="bkm-label mb-2">Select Option</p>
                        <div id="bkmOptList" class="flex flex-col gap-2"></div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- 2 · Date & Time --}}
                <div>
                    <div class="flex items-center gap-2.5 mb-4"><span class="bkm-num">2</span><h3 class="bkm-h">Date &amp; Time</h3></div>
                    <div class="grid md:grid-cols-2 gap-5">
                        <div class="border border-gray-200 rounded-xl overflow-hidden self-start">
                            <div class="flex items-center justify-between px-3.5 py-3 border-b border-gray-100 bg-gray-50">
                                <button type="button" id="bkmPrev" class="w-7 h-7 flex items-center justify-center border border-gray-200 hover:border-[#FF6900] hover:text-[#FF6900] text-gray-400 transition bg-white rounded">
                                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                                </button>
                                <span id="bkmMonth" class="font-bold uppercase tracking-[0.05em] text-gray-800 text-[13px]"></span>
                                <button type="button" id="bkmNext" class="w-7 h-7 flex items-center justify-center border border-gray-200 hover:border-[#FF6900] hover:text-[#FF6900] text-gray-400 transition bg-white rounded">
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-7 px-2.5 pt-2.5 bg-white">
                                @foreach(['Mo','Tu','We','Th','Fr','Sa','Su'] as $d)
                                    <div class="text-center text-[9px] font-bold uppercase tracking-[0.06em] text-gray-400 pb-1.5">{{ $d }}</div>
                                @endforeach
                            </div>
                            <div id="bkmCalGrid" class="grid grid-cols-7 gap-1 px-2.5 pb-3 bg-white"></div>
                        </div>

                        <div>
                            <p class="bkm-label mb-2">Time <span id="bkmDateLabel" class="normal-case tracking-normal text-gray-400 font-semibold"></span></p>
                            <div id="bkmSlots" class="grid grid-cols-3 gap-2"></div>
                            <p id="bkmSlotsMsg" class="text-gray-400 text-xs mt-2"></p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- 3 · Your details --}}
                <div>
                    <div class="flex items-center gap-2.5 mb-4"><span class="bkm-num">3</span><h3 class="bkm-h">Your Details</h3></div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <p class="bkm-label mb-1.5">Full Name</p>
                            <input type="text" id="bkmName" class="bm-input w-full px-4 py-3 text-sm">
                        </div>
                        <div>
                            <p class="bkm-label mb-1.5">Email Address</p>
                            <input type="email" id="bkmEmail" class="bm-input w-full px-4 py-3 text-sm">
                        </div>
                        <div>
                            <p class="bkm-label mb-1.5">Phone Number</p>
                            <input type="text" id="bkmPhone" class="bm-input w-full px-4 py-3 text-sm">
                        </div>
                        <div>
                            <p class="bkm-label mb-1.5">Vehicle Registration</p>
                            <input type="text" id="bkmReg" class="bm-input w-full px-4 py-3 text-sm uppercase">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="border-t border-gray-100 bg-gray-50 px-6 md:px-8 py-5 flex flex-col-reverse sm:flex-row items-center gap-3">
                <button type="button" id="bkmCancel"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 font-bold text-xs uppercase tracking-[0.12em] px-6 py-3 border-[1.5px] border-gray-200 text-gray-500 hover:border-red-300 hover:text-red-600 transition rounded-lg">
                    <i class="fa-solid fa-xmark text-[11px]"></i> Cancel Booking
                </button>
                <button type="button" id="bkmSave"
                    class="bm-clip w-full sm:flex-1 inline-flex items-center justify-center gap-2 text-white font-bold text-sm uppercase tracking-[0.12em] px-7 py-3.5 transition hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-40"
                    style="background:linear-gradient(135deg,#FF6900 0%,#FB5200 100%)">Save Changes</button>
            </div>
        </div>
    @endif
</section>

@if ($booking->status !== 'cancelled')
<script>
(function () {
    const BKM = {
        services: @json($services),
        booking: {
            service_id: {{ $booking->service_id }},
            start:      @json($booking->start_datetime->format('Y-m-d H:i:s')),
            date:       @json($booking->start_datetime->format('Y-m-d')),
            sub:        @json($booking->sub_service ?? ''),
            name:       @json($booking->customer_name),
            email:      @json($booking->customer_email),
            phone:      @json($booking->customer_phone ?? ''),
            reg:        @json($booking->vehicle_reg ?? ''),
        },
        base: @json(url('/booking/manage/'.$booking->edit_token)),
        csrf: document.querySelector('meta[name=csrf-token]')?.content || '',
    };

    const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    let selectedServiceId = BKM.booking.service_id;
    let selectedDate  = BKM.booking.date;
    let selectedStart = BKM.booking.start;
    let selectedSub   = BKM.booking.sub;
    let holidayDates  = [];
    const initD = new Date(BKM.booking.date + 'T00:00:00');
    let calMonth = initD.getMonth(), calYear = initD.getFullYear();

    const $ = id => document.getElementById(id);
    const fmt = d => `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
    const svc = () => BKM.services.find(s => s.id == selectedServiceId);

    function showAlert(msg, ok) {
        const a = $('bkmAlert');
        a.className = 'mb-6 rounded-xl px-4 py-3 text-sm ' + (ok ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700');
        a.textContent = msg;
        a.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function renderServices() {
        $('bkmServiceList').innerHTML = BKM.services.map(s => `
            <button type="button" class="bm-service-card flex items-center gap-3 p-3.5 text-left w-full ${s.id == selectedServiceId ? 'selected' : ''}" data-id="${s.id}">
                <div class="w-10 h-10 rounded shrink-0 flex items-center justify-center" style="background:linear-gradient(135deg,#FF6900,#FB5200)">
                    <i class="fa-solid fa-wrench text-white text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold uppercase tracking-[-0.02em] text-gray-900 text-sm">${s.name}</p>
                    <p class="text-gray-400 text-xs mt-0.5">${s.duration_minutes} min &nbsp;·&nbsp; ${(s.options && s.options.length) ? 'From ' : ''}£${parseFloat(s.price ?? 0).toFixed(2)}</p>
                </div>
                <div class="bm-radio w-5 h-5 border-2 border-gray-200 rounded-full shrink-0"></div>
            </button>`).join('');
        $('bkmServiceList').querySelectorAll('[data-id]').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedServiceId = parseInt(btn.dataset.id);
                selectedSub = '';
                renderServices(); renderOptions(); fetchClosedDates(); selectedStart = null; drawCalendar(); fetchSlots();
            });
        });
    }

    function renderOptions() {
        const s = svc();
        const opts = (s && Array.isArray(s.options)) ? s.options : [];
        const wrap = $('bkmOptWrap'), list = $('bkmOptList');
        if (opts.length) {
            $('bkmOptLabel').textContent = s.options_label || 'Select Option';
            list.innerHTML = '';
            opts.forEach(o => {
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'bm-opt-btn' + (o === selectedSub ? ' bm-opt-selected' : '');
                b.innerHTML = `<span class="bm-opt-radio"></span><span class="bm-opt-label">${o}</span>`;
                b.addEventListener('click', () => {
                    selectedSub = o;
                    list.querySelectorAll('.bm-opt-btn').forEach(x => x.classList.remove('bm-opt-selected'));
                    b.classList.add('bm-opt-selected');
                });
                list.appendChild(b);
            });
            wrap.classList.remove('hidden');
        } else {
            selectedSub = '';
            wrap.classList.add('hidden');
        }
    }

    function drawCalendar() {
        const s = svc();
        $('bkmMonth').textContent = `${months[calMonth]} ${calYear}`;
        const today = new Date(); today.setHours(0,0,0,0);
        const first = new Date(calYear, calMonth, 1);
        let dow = first.getDay(); dow = dow === 0 ? 6 : dow - 1;
        const days = new Date(calYear, calMonth + 1, 0).getDate();
        const minNotice = s?.min_notice_hours ?? 4;
        const advance   = s?.advance_booking_days ?? 60;
        const closed    = s?.closed_days ?? [];
        const minDate = new Date(Date.now() + minNotice * 3600000); minDate.setHours(0,0,0,0);
        const maxDate = new Date(today.getTime() + advance * 86400000);

        const grid = $('bkmCalGrid');
        grid.innerHTML = '';
        for (let i = 0; i < dow; i++) grid.insertAdjacentHTML('beforeend', '<div></div>');
        for (let d = 1; d <= days; d++) {
            const date = new Date(calYear, calMonth, d);
            const ds = fmt(date);
            const disabled = date < minDate || date > maxDate || closed.includes(date.getDay()) || holidayDates.includes(ds);
            const btn = document.createElement('button');
            btn.type = 'button'; btn.textContent = d;
            if (disabled) {
                btn.disabled = true;
                btn.className = 'bkm-cal-day w-full aspect-square text-xs text-gray-200 cursor-not-allowed';
            } else if (ds === selectedDate) {
                btn.className = 'bkm-cal-day sel w-full aspect-square text-xs';
            } else {
                btn.className = 'bkm-cal-day w-full aspect-square text-xs font-semibold text-gray-800';
                btn.addEventListener('click', () => { selectedDate = ds; selectedStart = null; drawCalendar(); fetchSlots(); });
            }
            grid.appendChild(btn);
        }
        const dObj = new Date(selectedDate + 'T00:00:00');
        $('bkmDateLabel').textContent = '· ' + dObj.toLocaleDateString('en-GB', {weekday:'short', day:'numeric', month:'short'});
    }

    async function fetchSlots() {
        if (!selectedDate) return;
        $('bkmSlots').innerHTML = '';
        $('bkmSlotsMsg').textContent = 'Loading times…';
        try {
            const url = new URL(BKM.base + '/slots');
            url.searchParams.set('date', selectedDate);
            url.searchParams.set('service_id', selectedServiceId);
            const data = await (await fetch(url)).json();
            $('bkmSlots').innerHTML = '';
            if (!Array.isArray(data) || !data.length) { $('bkmSlotsMsg').textContent = 'No available times — try another day.'; return; }
            $('bkmSlotsMsg').textContent = '';
            data.forEach(slot => {
                const b = document.createElement('button');
                b.type = 'button'; b.className = 'bm-slot py-2.5'; b.dataset.start = slot.start; b.textContent = slot.display;
                if (slot.start === selectedStart) b.classList.add('selected');
                b.addEventListener('click', () => {
                    selectedStart = slot.start;
                    $('bkmSlots').querySelectorAll('.bm-slot').forEach(x => x.classList.toggle('selected', x.dataset.start === selectedStart));
                });
                $('bkmSlots').appendChild(b);
            });
            if (![...$('bkmSlots').children].some(c => c.dataset.start === selectedStart)) selectedStart = null;
        } catch { $('bkmSlotsMsg').textContent = 'Could not load times.'; }
    }

    async function fetchClosedDates() {
        holidayDates = [];
        try {
            const data = await (await fetch(`/api/booking/closed-dates?service_id=${selectedServiceId}`)).json();
            if (Array.isArray(data)) holidayDates = data;
        } catch {}
        drawCalendar();
    }

    $('bkmName').value = BKM.booking.name;
    $('bkmEmail').value = BKM.booking.email;
    $('bkmPhone').value = BKM.booking.phone;
    $('bkmReg').value = BKM.booking.reg;
    renderServices(); renderOptions(); drawCalendar(); fetchSlots(); fetchClosedDates();

    $('bkmPrev').addEventListener('click', () => { if (--calMonth < 0) { calMonth = 11; calYear--; } drawCalendar(); });
    $('bkmNext').addEventListener('click', () => { if (++calMonth > 11) { calMonth = 0; calYear++; } drawCalendar(); });

    $('bkmSave').addEventListener('click', async () => {
        if (!$('bkmName').value.trim() || !$('bkmEmail').value.trim()) return showAlert('Please fill in your name and email.', false);
        if (!selectedStart) return showAlert('Please choose an available time.', false);
        if (!$('bkmOptWrap').classList.contains('hidden') && !selectedSub) return showAlert('Please select a service option.', false);
        const btn = $('bkmSave'); btn.disabled = true; const t = btn.textContent; btn.textContent = 'Saving…';
        try {
            const data = await (await fetch(BKM.base, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': BKM.csrf },
                body: JSON.stringify({
                    service_id: selectedServiceId, start: selectedStart,
                    customer_name: $('bkmName').value.trim(), customer_email: $('bkmEmail').value.trim(),
                    customer_phone: $('bkmPhone').value.trim(), vehicle_reg: $('bkmReg').value.trim(),
                    sub_service: $('bkmOptWrap').classList.contains('hidden') ? '' : selectedSub,
                }),
            })).json();
            if (data.success) { BKM.booking.start = selectedStart; showAlert('Your booking has been updated. A confirmation email is on its way.', true); }
            else showAlert(data.message || 'Could not save changes. Please try again.', false);
        } catch { showAlert('Network error. Please try again.', false); }
        btn.disabled = false; btn.textContent = t;
    });

    $('bkmCancel').addEventListener('click', async () => {
        if (!confirm('Are you sure you want to cancel this booking? This cannot be undone.')) return;
        const btn = $('bkmCancel'); btn.disabled = true;
        try {
            const data = await (await fetch(BKM.base + '/cancel', { method: 'POST', headers: { 'X-CSRF-TOKEN': BKM.csrf } })).json();
            if (data.success) { $('bkmForm').style.display = 'none'; showAlert('Your booking has been cancelled. A confirmation email has been sent.', true); }
            else { showAlert('Could not cancel. Please try again.', false); btn.disabled = false; }
        } catch { showAlert('Network error. Please try again.', false); btn.disabled = false; }
    });
})();
</script>
@endif
@endsection
