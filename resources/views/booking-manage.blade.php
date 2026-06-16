@extends('layouts.main')

@section('meta_title', 'Manage Your Booking | Simply Motoring')
@section('meta_description', 'View, edit, reschedule or cancel your Simply Motoring booking.')

@section('content')
<section class="max-w-2xl mx-auto px-4 py-14 md:py-20">
    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#FF6900]">Simply Motoring</p>
    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mt-1 mb-2">Manage Your Booking</h1>
    <p class="text-gray-500 text-sm mb-8">Update your details, reschedule, change service, or cancel below.</p>

    <div id="bkmAlert" class="hidden mb-6 rounded-lg px-4 py-3 text-sm"></div>

    @if ($booking->status === 'cancelled')
        <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
            This booking has been <strong>cancelled</strong>. If this was a mistake, please make a new booking.
        </div>
    @else
        <div id="bkmForm" class="space-y-6">
            {{-- Service --}}
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">Service</label>
                <select id="bkmService" class="w-full border border-gray-200 rounded-lg px-3 py-3 text-sm focus:border-[#FF6900] focus:ring-0"></select>
            </div>

            {{-- Option (sub-service) --}}
            <div id="bkmOptionWrap" class="hidden">
                <label id="bkmOptionLabel" class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">Select Option</label>
                <select id="bkmOption" class="w-full border border-gray-200 rounded-lg px-3 py-3 text-sm focus:border-[#FF6900] focus:ring-0"></select>
            </div>

            {{-- Date --}}
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">Date</label>
                <input type="date" id="bkmDate" class="w-full border border-gray-200 rounded-lg px-3 py-3 text-sm focus:border-[#FF6900] focus:ring-0">
            </div>

            {{-- Time slots --}}
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">Time</label>
                <div id="bkmSlots" class="grid grid-cols-3 sm:grid-cols-4 gap-2"></div>
                <p id="bkmSlotsMsg" class="text-gray-400 text-xs mt-2"></p>
            </div>

            {{-- Details --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">Full Name</label>
                    <input type="text" id="bkmName" class="w-full border border-gray-200 rounded-lg px-3 py-3 text-sm focus:border-[#FF6900] focus:ring-0">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" id="bkmEmail" class="w-full border border-gray-200 rounded-lg px-3 py-3 text-sm focus:border-[#FF6900] focus:ring-0">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">Phone Number</label>
                    <input type="text" id="bkmPhone" class="w-full border border-gray-200 rounded-lg px-3 py-3 text-sm focus:border-[#FF6900] focus:ring-0">
                </div>
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-gray-700 mb-1.5">Vehicle Registration</label>
                    <input type="text" id="bkmReg" class="w-full border border-gray-200 rounded-lg px-3 py-3 text-sm uppercase focus:border-[#FF6900] focus:ring-0">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="button" id="bkmSave"
                    class="flex-1 inline-flex items-center justify-center gap-2 text-white font-bold text-sm uppercase tracking-[0.12em] px-7 py-3 rounded-lg transition disabled:opacity-40"
                    style="background:linear-gradient(135deg,#FF6900 0%,#FB5200 100%)">Save Changes</button>
                <button type="button" id="bkmCancel"
                    class="inline-flex items-center justify-center gap-2 font-bold text-sm uppercase tracking-[0.12em] px-7 py-3 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition">Cancel Booking</button>
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

    let selectedStart = BKM.booking.start; // 'Y-m-d H:i:s'

    const $service = document.getElementById('bkmService');
    const $optWrap = document.getElementById('bkmOptionWrap');
    const $opt     = document.getElementById('bkmOption');
    const $optLbl  = document.getElementById('bkmOptionLabel');
    const $date    = document.getElementById('bkmDate');
    const $slots   = document.getElementById('bkmSlots');
    const $slotsMsg= document.getElementById('bkmSlotsMsg');
    const $name    = document.getElementById('bkmName');
    const $email   = document.getElementById('bkmEmail');
    const $phone   = document.getElementById('bkmPhone');
    const $reg     = document.getElementById('bkmReg');
    const $save    = document.getElementById('bkmSave');
    const $cancel  = document.getElementById('bkmCancel');
    const $alert   = document.getElementById('bkmAlert');

    function showAlert(msg, ok) {
        $alert.className = 'mb-6 rounded-lg px-4 py-3 text-sm ' + (ok ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700');
        $alert.textContent = msg;
        $alert.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Populate service dropdown
    BKM.services.forEach(s => {
        const o = document.createElement('option');
        o.value = s.id; o.textContent = s.name;
        if (s.id == BKM.booking.service_id) o.selected = true;
        $service.appendChild(o);
    });

    function currentService() {
        return BKM.services.find(s => s.id == $service.value);
    }

    function renderOptions() {
        const svc = currentService();
        const opts = (svc && Array.isArray(svc.options)) ? svc.options : [];
        if (opts.length) {
            $optLbl.textContent = svc.options_label || 'Select Option';
            $opt.innerHTML = '<option value="">— Choose —</option>' +
                opts.map(o => `<option value="${o}">${o}</option>`).join('');
            $opt.value = BKM.booking.sub && opts.includes(BKM.booking.sub) ? BKM.booking.sub : '';
            $optWrap.classList.remove('hidden');
        } else {
            $opt.innerHTML = '';
            $optWrap.classList.add('hidden');
        }
    }

    async function fetchSlots() {
        const date = $date.value;
        const sid  = $service.value;
        if (!date || !sid) return;
        $slots.innerHTML = '';
        $slotsMsg.textContent = 'Loading times…';
        try {
            const url = new URL(BKM.base + '/slots');
            url.searchParams.set('date', date);
            url.searchParams.set('service_id', sid);
            const res = await fetch(url);
            const data = await res.json();
            $slots.innerHTML = '';
            if (!Array.isArray(data) || !data.length) {
                $slotsMsg.textContent = 'No available times on this date — try another day.';
                return;
            }
            $slotsMsg.textContent = '';
            data.forEach(slot => {
                const b = document.createElement('button');
                b.type = 'button';
                b.dataset.start = slot.start;
                b.textContent = slot.display;
                b.className = 'border rounded-lg py-2 text-sm transition';
                applySlotStyle(b, slot.start === selectedStart);
                b.addEventListener('click', () => {
                    selectedStart = slot.start;
                    [...$slots.children].forEach(c => applySlotStyle(c, c.dataset.start === selectedStart));
                });
                $slots.appendChild(b);
            });
            // If the previously-selected time isn't in this list, clear it
            if (![...$slots.children].some(c => c.dataset.start === selectedStart)) {
                selectedStart = null;
            }
        } catch {
            $slotsMsg.textContent = 'Could not load times. Please try again.';
        }
    }

    function applySlotStyle(el, on) {
        el.style.cssText = on
            ? 'background:linear-gradient(135deg,#FF6900,#FB5200);color:#fff;border-color:transparent;'
            : 'background:#fff;color:#374151;border-color:#e5e7eb;';
    }

    // Init values
    $date.min = new Date().toISOString().slice(0, 10);
    $date.value = BKM.booking.date;
    $name.value = BKM.booking.name;
    $email.value = BKM.booking.email;
    $phone.value = BKM.booking.phone;
    $reg.value = BKM.booking.reg;
    renderOptions();
    fetchSlots();

    $service.addEventListener('change', () => { BKM.booking.sub = ''; renderOptions(); selectedStart = null; fetchSlots(); });
    $date.addEventListener('change', () => { selectedStart = null; fetchSlots(); });

    $save.addEventListener('click', async () => {
        if (!$name.value.trim() || !$email.value.trim()) return showAlert('Please fill in your name and email.', false);
        if (!selectedStart) return showAlert('Please choose an available time.', false);
        if (!$optWrap.classList.contains('hidden') && !$opt.value) return showAlert('Please select a service option.', false);

        $save.disabled = true;
        const prev = $save.textContent;
        $save.textContent = 'Saving…';
        try {
            const res = await fetch(BKM.base, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': BKM.csrf },
                body: JSON.stringify({
                    service_id:     $service.value,
                    start:          selectedStart,
                    customer_name:  $name.value.trim(),
                    customer_email: $email.value.trim(),
                    customer_phone: $phone.value.trim(),
                    vehicle_reg:    $reg.value.trim(),
                    sub_service:    $optWrap.classList.contains('hidden') ? '' : $opt.value,
                }),
            });
            const data = await res.json();
            if (data.success) {
                BKM.booking.start = selectedStart;
                showAlert('Your booking has been updated. A confirmation email is on its way.', true);
            } else {
                showAlert(data.message || 'Could not save changes. Please try again.', false);
            }
        } catch {
            showAlert('Network error. Please try again.', false);
        }
        $save.disabled = false;
        $save.textContent = prev;
    });

    $cancel.addEventListener('click', async () => {
        if (!confirm('Are you sure you want to cancel this booking? This cannot be undone.')) return;
        $cancel.disabled = true;
        try {
            const res = await fetch(BKM.base + '/cancel', { method: 'POST', headers: { 'X-CSRF-TOKEN': BKM.csrf } });
            const data = await res.json();
            if (data.success) {
                document.getElementById('bkmForm').style.display = 'none';
                showAlert('Your booking has been cancelled. A confirmation email has been sent.', true);
            } else {
                showAlert('Could not cancel. Please try again.', false);
                $cancel.disabled = false;
            }
        } catch {
            showAlert('Network error. Please try again.', false);
            $cancel.disabled = false;
        }
    });
})();
</script>
@endif
@endsection
