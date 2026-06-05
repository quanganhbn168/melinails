@extends('layouts.master')

@section('title', 'Rezervace online | Meli Nails & Beauty')

@section('content')
@php
    $bookingBranches = $meliBranches->map(fn ($branch) => [
        'category_ids' => $branch->services
            ->filter(fn ($service) => $branch->serviceCategories->pluck('id')->contains($service->service_category_id))
            ->pluck('service_category_id')
            ->unique()
            ->values(),
        'id' => $branch->id,
        'slug' => $branch->slug,
        'name' => $branch->name,
        'city' => $branch->city,
        'phone' => $branch->phone,
        'timezone' => $branch->timezone ?: 'Europe/Prague',
        'now_iso' => now($branch->timezone ?: 'Europe/Prague')->toIso8601String(),
        'today' => now($branch->timezone ?: 'Europe/Prague')->toDateString(),
        'min_notice_date' => now($branch->timezone ?: 'Europe/Prague')->addMinutes((int) ($branch->booking_min_notice_minutes ?: 0))->toDateString(),
        'min_notice_time' => now($branch->timezone ?: 'Europe/Prague')->addMinutes((int) ($branch->booking_min_notice_minutes ?: 0))->format('H:i'),
        'opening_time' => substr($branch->opening_time, 0, 5),
        'closing_time' => substr($branch->closing_time, 0, 5),
        'slot_minutes' => $branch->booking_slot_minutes ?: 15,
        'buffer_minutes' => $branch->booking_buffer_minutes ?: 0,
        'min_notice_minutes' => $branch->booking_min_notice_minutes ?: 0,
        'max_days_ahead' => $branch->booking_max_days_ahead ?: 60,
        'capacity' => max(1, (int) ($branch->booking_capacity ?: 1)),
        'online_booking_enabled' => $branch->online_booking_enabled ?? true,
        'services' => $branch->services
            ->filter(fn ($service) => $branch->serviceCategories->pluck('id')->contains($service->service_category_id))
            ->map(fn ($service) => [
            'id' => $service->id,
            'code' => $service->code,
            'name' => $service->name,
            'category_id' => $service->service_category_id,
            'category' => $service->category?->name,
            'price' => $service->pivot->price ?? $service->price ?? 0,
            'price_text' => $service->pivot->price_text ?: (($service->pivot->price ?? $service->price) ? number_format((int) ($service->pivot->price ?? $service->price), 0, ',', ' ') . ' Kč' : 'Dle domluvy'),
            'duration' => $service->pivot->duration_minutes ?? $service->duration_minutes,
        ])->values(),
    ])->values();
    $bookingCategories = $meliBranches
        ->flatMap(fn ($branch) => $branch->services
            ->filter(fn ($service) => $branch->serviceCategories->pluck('id')->contains($service->service_category_id))
            ->map(fn ($service) => $service->category)
            ->filter())
        ->unique('id')
        ->sortBy('name')
        ->map(fn ($category) => ['id' => $category->id, 'name' => $category->name])
        ->values();
    $bookingStaff = $staff->map(fn ($person) => [
        'id' => $person->id,
        'branch_id' => $person->branch_id,
        'name' => $person->name,
        'role' => $person->role,
        'service_ids' => $person->services->pluck('id')->values(),
        'working_mode' => $person->working_mode ?: 'full_time',
        'shift_start' => $person->shift_start ? substr($person->shift_start, 0, 5) : null,
        'shift_end' => $person->shift_end ? substr($person->shift_end, 0, 5) : null,
        'working_weekdays' => collect($person->working_weekdays ?? $person->working_days ?? [1, 2, 3, 4, 5, 6, 7])->map(fn ($day) => (int) $day)->values(),
        'working_shifts' => collect($person->working_shifts ?? [])->map(fn ($shift) => [
            'weekday' => (int) ($shift['weekday'] ?? 0),
            'start' => isset($shift['start']) ? substr($shift['start'], 0, 5) : null,
            'end' => isset($shift['end']) ? substr($shift['end'], 0, 5) : null,
        ])->values(),
        'working_dates' => collect($person->working_dates ?? [])->map(fn ($entry) => [
            'date' => $entry['date'] ?? null,
            'start' => isset($entry['start']) ? substr($entry['start'], 0, 5) : null,
            'end' => isset($entry['end']) ? substr($entry['end'], 0, 5) : null,
        ])->values(),
    ])->values();
    $bookingAppointments = $appointments->map(fn ($appointment) => [
        'id' => $appointment->id,
        'branch_id' => $appointment->branch_id,
        'staff_id' => $appointment->beauty_staff_id,
        'date' => $appointment->starts_at->format('Y-m-d'),
        'start' => $appointment->starts_at->format('H:i'),
        'end' => $appointment->ends_at->format('H:i'),
        'status' => $appointment->status,
        'segments' => $appointment->segments->map(fn ($segment) => [
            'staff_id' => $segment->beauty_staff_id,
            'service_id' => $segment->service_id,
            'date' => $segment->starts_at->format('Y-m-d'),
            'start' => $segment->starts_at->format('H:i'),
            'end' => $segment->ends_at->format('H:i'),
        ])->values(),
    ])->values();
@endphp

<div class="booking-leaderboard-wrap">
    <x-frontend.leaderboard
        image="https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1800&q=82"
        title="Rezervace online"
        subline="Meli Nails & Beauty"
        description="Vyberte pobočku, služby, specialistku, datum a volný čas. Booking hlídá obsazené termíny podle specialistky a délky procedur."
        :breadcrumb="[['label' => 'Rezervace']]"
        :actions="[
            ['label' => 'Vybrat služby', 'url' => '#booking-form', 'style' => 'primary', 'icon' => 'fas fa-calendar-check'],
            ['label' => 'Zavolat salon', 'url' => 'tel:+420777768681', 'style' => 'secondary', 'icon' => 'fas fa-phone'],
        ]"
        :stats="[
            ['value' => '2', 'label' => 'pobočky', 'icon' => 'fas fa-store'],
            ['value' => '15 min', 'label' => 'sloty', 'icon' => 'fas fa-clock'],
            ['value' => '40+', 'label' => 'služeb', 'icon' => 'fas fa-spa'],
            ['value' => '6', 'label' => 'specialistek', 'icon' => 'fas fa-user-check'],
        ]"
    />
</div>

<div class="booking-page">

<section id="booking-form" class="booking-mobile-safe booking-section"
    x-data="meliBooking({
        branches: @js($bookingBranches),
        categories: @js($bookingCategories),
        staff: @js($bookingStaff),
        appointments: @js($bookingAppointments),
        bookingOnlineEnabled: @js($bookingOnlineEnabled),
        allowStaffSelection: @js($allowStaffSelection),
        autoAssignStaff: @js($autoAssignStaff),
        initialBranch: @js(request('branch')),
        initialService: @js(request('service')),
        storeUrl: @js(route('meli.booking.store')),
        csrfToken: @js(csrf_token()),
    })">
    <div class="booking-container grid gap-4 lg:gap-8 lg:grid-cols-[1fr_370px]">
        <div class="space-y-4 lg:space-y-6">
            <div x-show="!bookingOnlineEnabled" class="booking-panel p-5 text-sm font-semibold text-stone-700">
                Online booking je momentálně vypnutý. Kontaktujte prosím salon telefonicky.
            </div>
            <div class="booking-panel overflow-hidden">
                <div class="booking-step-header">
                    <div>
                        <h2>Vyberte pobočku</h2>
                        <p>Zvolte salon, ve kterém chcete rezervaci vytvořit.</p>
                    </div>
                </div>
                <div class="p-4 sm:p-6 lg:p-8">
                <h3 class="booking-step-label">1. Pobočka</h3>
                <div class="mt-4 grid gap-3 sm:mt-5 md:grid-cols-2">
                    <template x-for="branch in branches" :key="branch.id">
                        <button type="button" class="booking-choice-card"
                            :class="selectedBranchId === branch.id ? 'is-selected' : ''"
                            :disabled="!bookingOnlineEnabled || !branch.online_booking_enabled"
                            @click="selectBranch(branch.id)">
                            <strong class="block text-base text-stone-950 sm:text-lg" x-text="branch.name"></strong>
                            <span class="mt-1 block text-sm text-stone-600 sm:mt-2" x-text="branch.city"></span>
                            <span class="mt-1 block text-sm text-stone-500" x-text="branch.opening_time + ' - ' + branch.closing_time"></span>
                            <span x-show="!branch.online_booking_enabled" class="mt-2 block text-xs font-bold uppercase tracking-[0.14em] text-red-700">Booking vypnutý</span>
                        </button>
                    </template>
                </div>
                </div>
            </div>

            <div class="booking-panel p-4 sm:p-6 lg:p-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h2 class="booking-step-label">2. Služby</h2>
                        <p class="mt-2 text-sm text-stone-600">Nabídka se mění podle zvolené pobočky.</p>
                    </div>
                    <div class="grid gap-2 sm:grid-cols-[1fr_180px] lg:w-[470px]">
                        <input type="search" x-model="query" placeholder="Hledat službu..." class="booking-input w-full">
                        <select x-model.number="categoryId" class="booking-input w-full">
                            <option value="0">Vše</option>
                            <template x-for="category in filteredCategories()" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex gap-2 overflow-x-auto pb-1" x-show="selectedServices.length > 0">
                    <template x-for="service in selectedServices" :key="service.id">
                        <button type="button" class="booking-chip" @click="toggleService(service)">
                            <span x-text="service.name"></span>
                            <i class="fa-solid fa-xmark ml-1"></i>
                        </button>
                    </template>
                </div>
                <div class="mt-4 grid max-h-[420px] gap-3 overflow-y-auto pr-1 sm:mt-5 lg:max-h-[520px]">
                    <template x-for="service in filteredServices()" :key="service.id">
                        <button type="button" class="booking-service-card"
                            :class="isSelected(service.id) ? 'is-selected' : ''"
                            @click="toggleService(service)">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <strong class="block text-stone-950" x-text="service.name"></strong>
                                    <span class="mt-1 block text-xs font-semibold uppercase tracking-[0.18em] text-rose-700" x-text="service.category"></span>
                                </div>
                                <span class="shrink-0 text-sm font-bold text-stone-950" x-text="service.price_text"></span>
                            </div>
                            <span class="mt-2 block text-sm text-stone-500" x-text="service.duration + ' min'"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="booking-panel p-4 sm:p-6 lg:p-8">
                <h2 class="booking-step-label" x-text="allowStaffSelection ? '3. Specialistka a čas' : '3. Datum a čas'"></h2>
                <div class="mt-4 grid gap-3 sm:mt-5 md:grid-cols-3">
                    <select x-show="allowStaffSelection" x-model.number="staffId" class="booking-input w-full" @change="syncTime()">
                        <option value="0">Kdokoliv z týmu</option>
                        <template x-for="person in availableStaff()" :key="person.id">
                            <option :value="person.id" x-text="person.name + ' - ' + person.role"></option>
                        </template>
                    </select>
                    <input type="date" x-model="date" :min="today" :max="maxBookingDate()" class="booking-input w-full" @change="syncTime()">
                    <select x-model="time" class="booking-input w-full">
                        <template x-for="slot in timeSlots()" :key="slot">
                            <option :value="slot" x-text="slot"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="booking-panel p-4 sm:p-6 lg:p-8">
                <h2 class="booking-step-label">4. Kontakt</h2>
                <div class="mt-4 grid gap-3 sm:mt-5 md:grid-cols-2">
                    <input type="text" x-model="customer.name" placeholder="Jméno a příjmení" class="booking-input w-full">
                    <input type="tel" x-model="customer.phone" placeholder="Telefon" class="booking-input w-full">
                    <input type="email" x-model="customer.email" placeholder="Email" class="booking-input w-full md:col-span-2">
                    <textarea x-model="customer.note" placeholder="Poznámka ke službě, barva, inspirace..." class="booking-input w-full md:col-span-2" rows="4"></textarea>
                </div>
            </div>
        </div>

        <aside class="booking-summary p-5 shadow-sm lg:sticky lg:top-24 lg:self-start">
            <img src="{{ asset('melinails/assets/logo.png') }}" alt="Meli Nails & Beauty" class="mb-5 h-12 w-auto">
            <h2 class="text-2xl font-bold text-stone-950">Souhrn</h2>
            <div class="mt-5 space-y-4 text-sm">
                <div><span class="text-stone-500">Pobočka</span><strong class="block text-stone-950" x-text="selectedBranch()?.name || 'Vyberte pobočku'"></strong></div>
                <div><span class="text-stone-500">Služby</span><div class="mt-2 space-y-2">
                    <template x-for="service in selectedServices" :key="service.id">
                        <div class="rounded-sm bg-stone-50 p-3"><strong x-text="service.name"></strong><span class="block text-stone-500" x-text="service.price_text + ' • ' + service.duration + ' min'"></span></div>
                    </template>
                    <p x-show="selectedServices.length === 0" class="text-stone-500">Vyberte služby</p>
                </div></div>
                <div class="flex justify-between border-t border-stone-200 pt-4"><span>Celkový čas</span><strong x-text="totalDuration() + ' min'"></strong></div>
                <div class="flex justify-between"><span>Celková cena</span><strong x-text="formatPrice(totalPrice())"></strong></div>
                <div><span class="text-stone-500">Termín</span><strong class="block text-stone-950" x-text="date + ' ' + time"></strong></div>
            </div>
            <p x-show="errorMessage" x-text="errorMessage" class="mt-4 rounded-sm bg-red-50 px-4 py-3 text-sm font-semibold text-red-700"></p>
            <p x-show="successMessage" x-text="successMessage" class="mt-4 rounded-sm bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700"></p>
            <button type="button" class="mt-6 w-full rounded-sm bg-stone-950 px-5 py-3 text-sm font-bold text-white hover:bg-rose-800 disabled:cursor-not-allowed disabled:opacity-60" @click="submitBooking()" :disabled="isSubmitting || successMessage">
                <span x-show="!isSubmitting">Odeslat rezervaci</span>
                <span x-show="isSubmitting">Ukládám rezervaci...</span>
            </button>
            <p class="mt-3 text-xs leading-5 text-stone-500">Po odeslání se termín uloží a stejná specialistka už nepůjde rezervovat v překrývajícím se čase.</p>
        </aside>
    </div>
</section>
</div>

@push('head_scripts')
<script>
    function meliBooking(payload) {
        return {
            branches: payload.branches,
            categories: payload.categories,
            staff: payload.staff,
            appointments: payload.appointments,
            storeUrl: payload.storeUrl,
            csrfToken: payload.csrfToken,
            bookingOnlineEnabled: payload.bookingOnlineEnabled,
            allowStaffSelection: payload.allowStaffSelection,
            autoAssignStaff: payload.autoAssignStaff,
            selectedBranchId: 0,
            selectedServices: [],
            categoryId: 0,
            staffId: 0,
            query: '',
            today: '',
            date: '',
            time: '09:00',
            customer: { name: '', phone: '', email: '', note: '' },
            isSubmitting: false,
            errorMessage: '',
            successMessage: '',
            init() {
                const branch = this.branches.find((item) => item.slug === payload.initialBranch && item.online_booking_enabled)
                    || this.branches.find((item) => item.online_booking_enabled)
                    || this.branches[0];
                if (branch) this.selectBranch(branch.id);
                if (payload.initialService) {
                    const service = this.selectedBranch()?.services.find((item) => item.code === payload.initialService);
                    if (service) this.toggleService(service);
                }
                this.syncTime();
            },
            selectedBranch() { return this.branches.find((item) => item.id === this.selectedBranchId); },
            selectBranch(id) {
                const branch = this.branches.find((item) => item.id === id);
                if (!this.bookingOnlineEnabled || !branch?.online_booking_enabled) return;
                this.selectedBranchId = id;
                this.today = branch.today || new Date().toISOString().slice(0, 10);
                this.date = this.date && this.date >= this.today && this.date <= this.maxBookingDate()
                    ? this.date
                    : this.today;
                this.selectedServices = [];
                this.categoryId = 0;
                this.staffId = 0;
                this.syncTime();
            },
            filteredServices() {
                const branch = this.selectedBranch();
                if (!branch) return [];
                const q = this.query.toLowerCase();
                return branch.services.filter((service) => (!this.categoryId || service.category_id === this.categoryId) && (!q || service.name.toLowerCase().includes(q)));
            },
            filteredCategories() {
                const branch = this.selectedBranch();
                if (!branch) return [];
                const categoryIds = (branch.category_ids || []).map(Number);
                return this.categories.filter((category) => categoryIds.includes(Number(category.id)));
            },
            isSelected(id) { return this.selectedServices.some((item) => item.id === id); },
            toggleService(service) {
                this.selectedServices = this.isSelected(service.id)
                    ? this.selectedServices.filter((item) => item.id !== service.id)
                    : [...this.selectedServices, service];
                this.staffId = 0;
                this.syncTime();
            },
            availableStaff() {
                const selectedIds = this.selectedServices.map((service) => service.id);
                return this.staff.filter((person) => person.branch_id === this.selectedBranchId && (!selectedIds.length || selectedIds.some((id) => person.service_ids.includes(id))));
            },
            totalDuration() { return this.selectedServices.reduce((sum, service) => sum + Number(service.duration || 0), 0); },
            totalPrice() { return this.selectedServices.reduce((sum, service) => sum + Number(service.price || 0), 0); },
            formatPrice(value) { return new Intl.NumberFormat('cs-CZ').format(value) + ' Kč'; },
            timeToMinutes(value) {
                const [hour, minute] = value.split(':').map(Number);
                return hour * 60 + minute;
            },
            minuteToTime(minute) {
                return String(Math.floor(minute / 60)).padStart(2, '0') + ':' + String(minute % 60).padStart(2, '0');
            },
            staffCandidatesForSlot() {
                if (!this.allowStaffSelection) return [];
                const team = this.availableStaff();
                if (this.staffId) {
                    return team.filter((person) => person.id === this.staffId);
                }
                return team;
            },
            staffCandidatesForSegment(serviceId) {
                const team = this.staff.filter((person) => person.branch_id === this.selectedBranchId && person.service_ids.includes(serviceId));
                if (!this.allowStaffSelection || !this.staffId) return team;
                return [...team].sort((a, b) => (a.id === this.staffId ? 0 : 1) - (b.id === this.staffId ? 0 : 1));
            },
            isoWeekday(dateString) {
                const day = new Date(dateString + 'T12:00:00').getDay();
                return day === 0 ? 7 : day;
            },
            dateAfterDays(days) {
                const date = new Date(this.today + 'T12:00:00');
                date.setDate(date.getDate() + Number(days || 60));
                return date.toISOString().slice(0, 10);
            },
            maxBookingDate() {
                return this.dateAfterDays(this.selectedBranch()?.max_days_ahead || 60);
            },
            staffWindows(person) {
                const branch = this.selectedBranch();
                if (!branch) return [];
                const mode = person.working_mode || 'full_time';
                const weekday = this.isoWeekday(this.date);

                if (mode === 'full_time') {
                    return [{ start: branch.opening_time, end: branch.closing_time }];
                }

                if (mode === 'weekdays') {
                    const weekdays = (person.working_weekdays || []).map(Number);
                    if (!weekdays.includes(weekday)) return [];
                    return [{
                        start: person.shift_start || branch.opening_time,
                        end: person.shift_end || branch.closing_time,
                    }];
                }

                if (mode === 'shifts') {
                    return (person.working_shifts || [])
                        .filter((shift) => Number(shift.weekday) === weekday)
                        .map((shift) => ({
                            start: shift.start || branch.opening_time,
                            end: shift.end || branch.closing_time,
                        }));
                }

                if (mode === 'specific_dates') {
                    return (person.working_dates || [])
                        .filter((entry) => entry.date === this.date)
                        .map((entry) => ({
                            start: entry.start || branch.opening_time,
                            end: entry.end || branch.closing_time,
                        }));
                }

                return [{ start: branch.opening_time, end: branch.closing_time }];
            },
            staffWorksAt(person, startMinute, endMinute) {
                return this.staffWindows(person).some((window) => {
                    return startMinute >= this.timeToMinutes(window.start) && endMinute <= this.timeToMinutes(window.end);
                });
            },
            staffIsFree(person, startMinute, endMinute) {
                return !this.appointments.some((appointment) => {
                    const buffer = Number(this.selectedBranch()?.buffer_minutes || 0);
                    if (appointment.branch_id !== this.selectedBranchId) return false;
                    if ((appointment.segments || []).length) {
                        return appointment.segments.some((segment) => {
                            if (segment.staff_id !== person.id || segment.date !== this.date) return false;
                            const bookedStart = this.timeToMinutes(segment.start);
                            const bookedEnd = this.timeToMinutes(segment.end);
                            return (startMinute - buffer) < bookedEnd && (endMinute + buffer) > bookedStart;
                        });
                    }
                    if (appointment.staff_id !== person.id || appointment.date !== this.date) return false;
                    const bookedStart = this.timeToMinutes(appointment.start);
                    const bookedEnd = this.timeToMinutes(appointment.end);
                    return (startMinute - buffer) < bookedEnd && (endMinute + buffer) > bookedStart;
                });
            },
            branchHasCapacity(startMinute, endMinute) {
                const branch = this.selectedBranch();
                if (!branch) return false;
                const overlapping = this.appointments.filter((appointment) => {
                    if (appointment.branch_id !== this.selectedBranchId || appointment.date !== this.date) return false;
                    const bookedStart = this.timeToMinutes(appointment.start);
                    const bookedEnd = this.timeToMinutes(appointment.end);
                    return startMinute < bookedEnd && endMinute > bookedStart;
                }).length;
                return overlapping < Number(branch.capacity || 1);
            },
            serviceSegments(startMinute) {
                let cursor = startMinute;
                return this.selectedServices.map((service) => {
                    const duration = Math.max(1, Number(service.duration || 15));
                    const segment = {
                        service_id: service.id,
                        start: cursor,
                        end: cursor + duration,
                    };
                    cursor += duration;
                    return segment;
                });
            },
            segmentsCanBeAssigned(startMinute) {
                if (!this.autoAssignStaff) return true;
                if (!this.allowStaffSelection) return true;
                const segments = this.serviceSegments(startMinute);
                if (!segments.length) return true;
                return segments.every((segment) => {
                    return this.staffCandidatesForSegment(segment.service_id)
                        .some((person) => this.staffWorksAt(person, segment.start, segment.end) && this.staffIsFree(person, segment.start, segment.end));
                });
            },
            slotIsFree(startMinute) {
                const endMinute = startMinute + Math.max(this.totalDuration(), 15);
                return this.branchHasCapacity(startMinute, endMinute) && this.segmentsCanBeAssigned(startMinute);
            },
            respectsNotice(startMinute) {
                const branch = this.selectedBranch();
                if (!branch) return false;
                if (this.date > this.maxBookingDate()) return false;
                if (this.date < branch.min_notice_date) return false;
                if (this.date > branch.min_notice_date) return true;
                return startMinute >= this.timeToMinutes(branch.min_notice_time || branch.opening_time);
            },
            timeSlots() {
                const branch = this.selectedBranch();
                if (!branch) return ['09:00'];
                if (!this.bookingOnlineEnabled || !branch.online_booking_enabled) return [];
                const [openH, openM] = branch.opening_time.split(':').map(Number);
                const [closeH, closeM] = branch.closing_time.split(':').map(Number);
                const start = openH * 60 + openM;
                const end = closeH * 60 + closeM - Math.max(this.totalDuration(), 15);
                const step = Math.max(5, Number(branch.slot_minutes || 15));
                const slots = [];
                for (let minute = start; minute <= end; minute += step) {
                    if (this.respectsNotice(minute) && this.slotIsFree(minute)) slots.push(this.minuteToTime(minute));
                }
                return slots;
            },
            syncTime() {
                setTimeout(() => {
                    const slots = this.timeSlots();
                    this.time = slots.includes(this.time) ? this.time : (slots[0] || '');
                });
            },
            bookingPayload() {
                return {
                    branch_id: this.selectedBranchId,
                    service_ids: this.selectedServices.map((service) => service.id),
                    staff_id: this.allowStaffSelection ? (this.staffId || null) : null,
                    date: this.date,
                    time: this.time,
                    customer: this.customer,
                };
            },
            validateBooking() {
                if (!this.bookingOnlineEnabled) return 'Online booking je momentálně vypnutý.';
                if (!this.selectedBranch()?.online_booking_enabled) return 'Online booking pro vybranou pobočku je vypnutý.';
                if (!this.selectedBranchId) return 'Vyberte pobočku.';
                if (!this.selectedServices.length) return 'Vyberte alespoň jednu službu.';
                if (!this.time) return this.allowStaffSelection ? 'Pro zvolený den a specialistku není volný čas.' : 'Pro zvolený den není volný čas.';
                if (!this.customer.name || !this.customer.phone) return 'Vyplňte jméno a telefon.';
                return '';
            },
            async submitBooking() {
                this.errorMessage = this.validateBooking();
                this.successMessage = '';
                if (this.errorMessage) return;

                this.isSubmitting = true;
                try {
                    const response = await fetch(this.storeUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(this.bookingPayload()),
                    });
                    const result = await response.json();
                    if (!response.ok) {
                        this.errorMessage = result.message || 'Rezervaci se nepodařilo uložit.';
                        return;
                    }
                    this.appointments.push({
                        id: result.appointment.id,
                        branch_id: result.appointment.branch_id,
                        staff_id: result.appointment.staff_id,
                        date: result.appointment.starts_at.slice(0, 10),
                        start: result.appointment.starts_at.slice(11, 16),
                        end: result.appointment.ends_at.slice(11, 16),
                        status: result.appointment.status,
                        segments: (result.appointment.segments || []).map((segment) => ({
                            staff_id: segment.staff_id,
                            date: segment.starts_at.slice(0, 10),
                            start: segment.starts_at.slice(11, 16),
                            end: segment.ends_at.slice(11, 16),
                        })),
                    });
                    this.staffId = result.appointment.staff_id || 0;
                    this.successMessage = result.appointment.staff_name
                        ? 'Rezervace ' + result.appointment.code + ' je uložená pro ' + result.appointment.staff_name + '.'
                        : 'Rezervace ' + result.appointment.code + ' je uložená. Tým salonu ji přiřadí interně.';
                    if (window.Swal) {
                        await window.Swal.fire('Rezervace uložena', this.successMessage, 'success');
                    } else {
                        alert(this.successMessage);
                    }
                    if (result.appointment.thank_url) {
                        window.location.href = result.appointment.thank_url;
                    }
                    this.syncTime();
                } catch (error) {
                    this.errorMessage = 'Rezervaci se nepodařilo odeslat. Zkuste to prosím znovu.';
                } finally {
                    this.isSubmitting = false;
                }
            },
        };
    }
</script>
@endpush

@push('css')
<style>
    .booking-leaderboard-wrap > section {
        border-bottom-color: rgba(31, 27, 22, .08) !important;
        background: #f7f1e6 !important;
    }
    .booking-leaderboard-wrap > section [class*="text-brand-900"],
    .booking-leaderboard-wrap > section h1 {
        color: #1f1b16 !important;
    }
    .booking-leaderboard-wrap > section [class*="text-brand-800"],
    .booking-leaderboard-wrap > section [class*="text-blue-700"],
    .booking-leaderboard-wrap > section [class*="text-blue-600"] {
        color: #ac8449 !important;
    }
    .booking-leaderboard-wrap > section [class*="bg-blue-700"] {
        background: #15120f !important;
        box-shadow: 0 16px 40px rgba(31, 27, 22, .18) !important;
    }
    .booking-leaderboard-wrap > section [class*="border-blue"],
    .booking-leaderboard-wrap > section [class*="border-blue-200"] {
        border-color: rgba(172, 132, 73, .24) !important;
    }
    .booking-leaderboard-wrap > section [class*="bg-blue-50"],
    .booking-leaderboard-wrap > section [class*="bg-[#eef6ff]"] {
        background: #f4ebde !important;
    }
    .booking-leaderboard-wrap > section [class*="text-slate-700"] {
        color: #746b60 !important;
    }
    .booking-page {
        --bg: #f7f1e6;
        --bg-2: #fbf8f2;
        --paper: #fffdf8;
        --paper-2: #f1e7d8;
        --text: #1f1b16;
        --muted: #746b60;
        --gold: #d8bd7e;
        --gold-dark: #ac8449;
        --black: #15120f;
        --line: rgba(31, 27, 22, .09);
        --shadow: 0 28px 90px rgba(31, 27, 22, .09);
        --shadow-small: 0 14px 40px rgba(31, 27, 22, .08);
        color: var(--text);
        background:
            radial-gradient(circle at 8% 8%, rgba(216,189,126,.20), transparent 28%),
            radial-gradient(circle at 92% 12%, rgba(172,132,73,.10), transparent 30%),
            linear-gradient(180deg, var(--bg), var(--bg-2));
        overflow: visible;
    }
    .booking-container {
        width: min(1220px, calc(100% - 48px));
        margin-inline: auto;
    }
    .booking-hero {
        padding: 70px 0 48px;
    }
    .booking-hero-grid {
        display: grid;
        grid-template-columns: .92fr 1.08fr;
        gap: clamp(34px, 5vw, 72px);
        align-items: center;
    }
    .booking-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 20px;
        color: var(--gold-dark);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .18em;
        line-height: 1.2;
        text-transform: uppercase;
    }
    .booking-eyebrow::before {
        content: "";
        width: 34px;
        height: 1px;
        background: currentColor;
        opacity: .7;
    }
    .booking-hero h1,
    .booking-step-header h2 {
        font-family: Georgia, "Times New Roman", serif;
        letter-spacing: -.035em;
        line-height: .98;
        font-weight: 600;
    }
    .booking-hero h1 {
        max-width: 760px;
        margin-bottom: 24px;
        font-size: clamp(54px, 7.2vw, 104px);
    }
    .booking-hero p {
        max-width: 650px;
        color: var(--muted);
        font-size: clamp(17px, 1.35vw, 19px);
        line-height: 1.75;
    }
    .booking-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 30px;
    }
    .booking-pills span {
        display: inline-flex;
        min-height: 40px;
        align-items: center;
        border: 1px solid var(--line);
        border-radius: 999px;
        background: rgba(255,255,255,.52);
        padding: 0 15px;
        color: var(--muted);
        font-size: 13px;
        font-weight: 800;
    }
    .booking-hero-visual {
        position: relative;
        min-height: 520px;
        overflow: hidden;
        border-radius: 42px;
        background: var(--paper-2);
        box-shadow: var(--shadow);
    }
    .booking-hero-visual img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .booking-hero-visual::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(31,27,22,.04), rgba(31,27,22,.48));
    }
    .booking-hero-card {
        position: absolute;
        z-index: 2;
        right: 28px;
        bottom: 28px;
        left: 28px;
        border: 1px solid rgba(255,255,255,.48);
        border-radius: 28px;
        background: rgba(255,255,255,.82);
        padding: 26px;
        backdrop-filter: blur(14px);
    }
    .booking-hero-card strong {
        display: block;
        margin-bottom: 8px;
        font-size: 17px;
    }
    .booking-hero-card p {
        font-size: 14px;
        line-height: 1.7;
    }
    .booking-section {
        padding: 48px 0 118px;
    }
    .booking-panel,
    .booking-summary {
        border: 1px solid var(--line);
        border-radius: 32px;
        background: rgba(255,255,255,.68);
        box-shadow: var(--shadow);
    }
    .booking-step-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        border-bottom: 1px solid var(--line);
        padding: 30px 34px;
    }
    .booking-step-header h2 {
        margin-bottom: 8px;
        font-size: clamp(34px, 4vw, 54px);
    }
    .booking-step-header p {
        color: var(--muted);
        font-size: 15px;
    }
    .booking-step-label {
        color: var(--text);
        font-size: 20px;
        font-weight: 800;
    }
    .booking-summary {
        background: rgba(255,255,255,.72);
        position: sticky;
        top: 96px;
    }
    .booking-input {
        min-height: 52px;
        border: 1px solid rgba(31, 27, 22, .12) !important;
        border-radius: 18px !important;
        background: rgba(255,255,255,.64);
        padding: 14px 16px;
        color: var(--text);
        font-size: 14px;
        box-shadow: none !important;
        transition: border-color .2s ease, background .2s ease, box-shadow .2s ease;
    }
    .booking-input:focus {
        border-color: rgba(172, 132, 73, .55) !important;
        background: var(--paper);
        outline: none;
        box-shadow: 0 0 0 4px rgba(216, 189, 126, .18) !important;
    }
    textarea.booking-input {
        min-height: 112px;
        resize: vertical;
    }
    .booking-choice-card,
    .booking-service-card {
        width: 100%;
        border: 1px solid rgba(31, 27, 22, .10);
        border-radius: 24px;
        background: rgba(255,255,255,.58);
        padding: 18px;
        text-align: left;
        transition: transform .2s ease, border-color .2s ease, background .2s ease, box-shadow .2s ease;
    }
    .booking-choice-card:hover,
    .booking-service-card:hover {
        transform: translateY(-2px);
        border-color: rgba(172, 132, 73, .35);
        background: var(--paper);
        box-shadow: var(--shadow-small);
    }
    .booking-choice-card.is-selected,
    .booking-service-card.is-selected {
        border-color: rgba(172, 132, 73, .62);
        background: var(--paper);
        box-shadow: inset 0 0 0 1px rgba(172,132,73,.18), var(--shadow-small);
    }
    .booking-choice-card:disabled,
    .booking-service-card:disabled {
        cursor: not-allowed;
        opacity: .52;
        transform: none;
        box-shadow: none;
    }
    .booking-chip {
        flex-shrink: 0;
        border: 1px solid rgba(172, 132, 73, .28);
        border-radius: 999px;
        background: #fffdf8;
        padding: 7px 12px;
        color: #8d6837;
        font-size: 12px;
        font-weight: 800;
        box-shadow: 0 8px 22px rgba(31, 27, 22, .06);
    }
    .booking-summary {
        max-height: calc(100vh - 120px);
        overflow: auto;
    }
    .booking-summary .summary-box {
        border-radius: 18px;
        background: #f4ebde;
    }
    @media (max-width: 1023px) {
        .booking-mobile-safe {
            padding-bottom: 44px;
        }
        .booking-hero-grid {
            grid-template-columns: 1fr;
        }
        .booking-hero-visual {
            min-height: 430px;
        }
    }
    @media (max-width: 760px) {
        .booking-container {
            width: min(1220px, calc(100% - 30px));
        }
        .booking-hero {
            padding: 44px 0 36px;
        }
        .booking-hero h1 {
            font-size: clamp(50px, 15vw, 70px);
        }
        .booking-hero-visual {
            min-height: 390px;
            border-radius: 32px;
        }
        .booking-hero-card {
            right: 16px;
            bottom: 16px;
            left: 16px;
            padding: 18px;
        }
        .booking-section {
            padding: 26px 0 62px;
        }
        .booking-step-header {
            align-items: flex-start;
            padding: 24px 20px;
        }
        .booking-step-counter {
            width: 58px;
            height: 58px;
            flex-basis: 58px;
            font-size: 13px;
        }
        .booking-summary {
            margin-top: 6px;
            position: static;
            max-height: none;
            overflow: visible;
        }
    }
</style>
@endpush
@endsection
