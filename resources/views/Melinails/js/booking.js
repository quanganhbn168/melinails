const serviceCategories = window.MELI_SERVICE_CATEGORIES || [];
const services = window.MELI_SERVICES || [];

const CONFIG = {
  openingTime: '08:00',
  closingTime: '20:00',
  slotIntervalMinutes: 15
};

const booking = {
  step: 1,
  branch: '',
  branchPhone: '',
  category: 'all',
  categoryName: 'Všechny služby',
  services: [],
  staff: '',
  date: '',
  dateLabel: '',
  time: '',
  totalDuration: 0,
  totalPrice: 0
};

const stepData = {
  1: { title: 'Vyberte pobočku', desc: 'Zvolte salon, ve kterém chcete rezervaci vytvořit.' },
  2: { title: 'Vyberte služby', desc: 'Vyberte jednu nebo více služeb. Seznam lze vyhledávat a scrollovat.' },
  3: { title: 'Vyberte specialistku', desc: 'Můžete vybrat konkrétní specialistku nebo nechat výběr na salonu.' },
  4: { title: 'Vyberte datum a čas', desc: 'Časy jsou po 15 minutách. Backend později ověří dostupnost pro celkovou délku služeb.' },
  5: { title: 'Kontaktní údaje', desc: 'Doplňte údaje, aby salon mohl rezervaci potvrdit.' },
  6: { title: 'Kontrola rezervace', desc: 'Zkontrolujte údaje před odesláním.' }
};

const menuToggle = document.querySelector('.menu-toggle');
const mobileLinks = document.querySelectorAll('.mobile-menu a');
menuToggle?.addEventListener('click', () => {
  const isOpen = document.body.classList.toggle('menu-open');
  menuToggle.setAttribute('aria-expanded', String(isOpen));
});
mobileLinks.forEach((link) => link.addEventListener('click', () => {
  document.body.classList.remove('menu-open');
  menuToggle?.setAttribute('aria-expanded', 'false');
}));

const steps = [...document.querySelectorAll('.step')];
const stepHeading = document.getElementById('stepHeading');
const stepDescription = document.getElementById('stepDescription');
const stepCounter = document.getElementById('stepCounter');
const progressBar = document.getElementById('progressBar');
const prevStep = document.getElementById('prevStep');
const nextStep = document.getElementById('nextStep');
const stepActions = document.getElementById('stepActions');
const bookingForm = document.getElementById('bookingForm');
const successBox = document.getElementById('successBox');

const summary = {
  branch: document.getElementById('summaryBranch'),
  services: document.getElementById('summaryServices'),
  staff: document.getElementById('summaryStaff'),
  date: document.getElementById('summaryDate'),
  time: document.getElementById('summaryTime'),
  total: document.getElementById('summaryTotal')
};

function money(value) {
  return `${value.toLocaleString('cs-CZ')} Kč`;
}

function calculateTotals() {
  booking.totalDuration = booking.services.reduce((sum, item) => sum + Number(item.duration || 0), 0);
  booking.totalPrice = booking.services.reduce((sum, item) => sum + Number(item.price || 0), 0);
}

function getCategoryName(categoryId) {
  return serviceCategories.find((category) => category.id === categoryId)?.name || categoryId;
}

function updateSummary() {
  calculateTotals();
  summary.branch.textContent = booking.branch || 'Vyberte pobočku';
  summary.staff.textContent = booking.staff || 'Zatím nezvoleno';
  summary.date.textContent = booking.dateLabel || 'Zatím nezvoleno';
  summary.time.textContent = booking.time || 'Zatím nezvoleno';
  summary.total.textContent = booking.services.length
    ? `${money(booking.totalPrice)} • ${booking.totalDuration} min`
    : 'Cena a délka se zobrazí po výběru služeb';

  summary.services.innerHTML = booking.services.length
    ? booking.services.map((item) => `
      <div class="summary-service-item">
        <strong>${item.name}</strong>
        <small>${getCategoryName(item.category)} • ${item.priceText} • ${item.duration} min</small>
      </div>
    `).join('')
    : 'Vyberte služby';

  const durationLabel = document.getElementById('totalDurationLabel');
  if (durationLabel) durationLabel.textContent = `${booking.totalDuration || 0} min celkem`;
}

function renderReview() {
  const firstName = document.getElementById('firstName')?.value.trim() || '';
  const lastName = document.getElementById('lastName')?.value.trim() || '';
  const phone = document.getElementById('phone')?.value.trim() || '';
  const email = document.getElementById('email')?.value.trim() || '';
  const note = document.getElementById('note')?.value.trim() || '';
  const serviceList = booking.services.map((service) => `<li>${service.name} - ${service.priceText} - ${service.duration} min</li>`).join('');

  document.getElementById('reviewDetails').innerHTML = `
    <div><span>Pobočka</span><strong>${booking.branch}</strong></div>
    <div><span>Služby</span><strong><ul>${serviceList}</ul></strong></div>
    <div><span>Celkem</span><strong>${money(booking.totalPrice)} • ${booking.totalDuration} min</strong></div>
    <div><span>Specialistka</span><strong>${booking.staff}</strong></div>
    <div><span>Termín</span><strong>${booking.dateLabel} • ${booking.time}</strong></div>
    <div><span>Klient</span><strong>${firstName} ${lastName}</strong></div>
    <div><span>Kontakt</span><strong>${phone}${email ? ' • ' + email : ''}</strong></div>
    ${note ? `<div><span>Poznámka</span><strong>${note}</strong></div>` : ''}
  `;
}

function updateStep() {
  steps.forEach((step) => step.classList.toggle('active', Number(step.dataset.step) === booking.step));
  stepHeading.textContent = stepData[booking.step].title;
  stepDescription.textContent = stepData[booking.step].desc;
  stepCounter.textContent = `${booking.step}/6`;
  progressBar.style.width = `${booking.step * (100 / 6)}%`;
  prevStep.disabled = booking.step === 1;
  nextStep.textContent = booking.step === 6 ? 'Odeslat rezervaci' : 'Pokračovat';
  updateSummary();
  if (booking.step === 6) renderReview();
  document.querySelector('.booking-panel')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function canContinue() {
  if (booking.step === 1) return Boolean(booking.branch);
  if (booking.step === 2) return booking.services.length > 0;
  if (booking.step === 3) return Boolean(booking.staff);
  if (booking.step === 4) return Boolean(booking.date && booking.time);
  if (booking.step === 5) return validateContact();
  return true;
}

function showHint(message) {
  stepDescription.textContent = message;
  stepDescription.style.color = 'var(--danger)';
  setTimeout(() => {
    stepDescription.textContent = stepData[booking.step].desc;
    stepDescription.style.color = '';
  }, 1900);
}

document.querySelectorAll('[data-select]').forEach((button) => {
  button.addEventListener('click', () => {
    const type = button.dataset.select;
    document.querySelectorAll(`[data-select="${type}"]`).forEach((item) => item.classList.remove('selected'));
    button.classList.add('selected');

    if (type === 'branch') {
      booking.branch = button.dataset.value;
      booking.branchPhone = button.dataset.phone;
    }
    if (type === 'staff') booking.staff = button.dataset.value;
    updateSummary();
  });
});

const categoryList = document.getElementById('categoryList');
const serviceList = document.getElementById('serviceList');
const serviceEmpty = document.getElementById('serviceEmpty');
const serviceSearch = document.getElementById('serviceSearch');
const serviceSort = document.getElementById('serviceSort');
const selectedCategoryLabel = document.getElementById('selectedCategoryLabel');
const serviceCountLabel = document.getElementById('serviceCountLabel');
const selectedServicesStrip = document.getElementById('selectedServicesStrip');
const clearServices = document.getElementById('clearServices');

function getCategoryCount(categoryId) {
  if (categoryId === 'all') return services.length;
  return services.filter((service) => service.category === categoryId).length;
}

function renderCategories() {
  categoryList.innerHTML = serviceCategories.map((category) => `
    <button class="category-btn ${booking.category === category.id ? 'active' : ''}" type="button" data-category="${category.id}">
      <span class="category-icon">${category.icon}</span>
      <span><strong>${category.name}</strong><span>${category.desc}</span></span>
      <span class="count-badge">${getCategoryCount(category.id)}</span>
    </button>
  `).join('');

  categoryList.querySelectorAll('.category-btn').forEach((button) => {
    button.addEventListener('click', () => {
      const id = button.dataset.category;
      const category = serviceCategories.find((item) => item.id === id);
      booking.category = id;
      booking.categoryName = category.name;
      renderCategories();
      renderServices();
      updateSummary();
    });
  });
}

function filteredServices() {
  const query = serviceSearch.value.trim().toLowerCase();
  let list = services.filter((service) => {
    const matchCategory = booking.category === 'all' || service.category === booking.category;
    const text = [service.name, service.desc, service.priceText, String(service.duration), ...service.tags].join(' ').toLowerCase();
    const matchQuery = !query || text.includes(query);
    return matchCategory && matchQuery;
  });

  if (serviceSort.value === 'priceAsc') list = [...list].sort((a, b) => a.price - b.price);
  if (serviceSort.value === 'durationAsc') list = [...list].sort((a, b) => a.duration - b.duration);
  if (serviceSort.value === 'az') list = [...list].sort((a, b) => a.name.localeCompare(b.name, 'cs'));
  return list;
}

function isSelected(serviceId) {
  return booking.services.some((service) => service.id === serviceId);
}

function toggleService(serviceId) {
  const service = services.find((item) => item.id === serviceId);
  if (!service) return;

  if (isSelected(serviceId)) {
    booking.services = booking.services.filter((item) => item.id !== serviceId);
  } else {
    booking.services.push({ ...service, categoryName: getCategoryName(service.category) });
  }

  booking.time = '';
  calculateTotals();
  renderServices();
  renderSelectedServices();
  renderTimes();
  updateSummary();
}

function renderSelectedServices() {
  selectedServicesStrip.classList.toggle('active', booking.services.length > 0);
  selectedServicesStrip.innerHTML = booking.services.map((service) => `
    <span class="selected-chip">
      ${service.name}
      <button type="button" aria-label="Odebrat ${service.name}" data-remove-service="${service.id}">×</button>
    </span>
  `).join('');

  selectedServicesStrip.querySelectorAll('[data-remove-service]').forEach((button) => {
    button.addEventListener('click', (event) => {
      event.stopPropagation();
      toggleService(button.dataset.removeService);
    });
  });
}

function renderServices() {
  const category = serviceCategories.find((item) => item.id === booking.category);
  selectedCategoryLabel.textContent = category?.name || 'Všechny služby';
  const list = filteredServices();
  serviceCountLabel.textContent = `${list.length} služeb v aktuálním výběru • vybráno ${booking.services.length}`;
  serviceEmpty.classList.toggle('active', list.length === 0);
  serviceList.innerHTML = list.map((service) => {
    const selected = isSelected(service.id);
    return `
      <button class="service-row ${selected ? 'selected' : ''}" type="button" data-service-id="${service.id}">
        <span class="service-check">✓</span>
        <span>
          <h3>${service.name}</h3>
          <p>${service.desc}</p>
          <span class="service-meta">
            <span class="mini-tag">${service.duration} min</span>
            ${service.tags.slice(0, 3).map((tag) => `<span class="mini-tag">${tag}</span>`).join('')}
          </span>
        </span>
        <span class="service-price"><strong>${service.priceText}</strong><span>orientačně</span></span>
      </button>
    `;
  }).join('');

  serviceList.querySelectorAll('.service-row').forEach((button) => {
    button.addEventListener('click', () => toggleService(button.dataset.serviceId));
  });
}

serviceSearch.addEventListener('input', renderServices);
serviceSort.addEventListener('change', renderServices);
clearServices.addEventListener('click', () => {
  booking.services = [];
  booking.time = '';
  renderServices();
  renderSelectedServices();
  renderTimes();
  updateSummary();
});

prevStep.addEventListener('click', () => {
  if (booking.step > 1) {
    booking.step -= 1;
    updateStep();
  }
});

nextStep.addEventListener('click', () => {
  if (!canContinue()) {
    showHint('Prosím dokončete tento krok, než budete pokračovat.');
    return;
  }
  if (booking.step < 6) {
    booking.step += 1;
    updateStep();
  } else {
    submitBooking();
  }
});

const monthLabel = document.getElementById('monthLabel');
const daysGrid = document.getElementById('daysGrid');
const timesGrid = document.getElementById('timesGrid');
const timeHint = document.getElementById('timeHint');
const prevMonthBtn = document.getElementById('prevMonth');
const nextMonthBtn = document.getElementById('nextMonth');
const slotIntervalLabel = document.getElementById('slotIntervalLabel');

slotIntervalLabel.textContent = `${CONFIG.slotIntervalMinutes} min interval`;

const today = new Date();
today.setHours(0,0,0,0);
let viewMonth = new Date(today.getFullYear(), today.getMonth(), 1);

const monthNames = ['leden','únor','březen','duben','květen','červen','červenec','srpen','září','říjen','listopad','prosinec'];
const dayNames = ['Ne','Po','Út','St','Čt','Pá','So'];

function generateTimeSlots(open = '08:00', close = '20:00', interval = 15) {
  const slots = [];
  const [openHour, openMinute] = open.split(':').map(Number);
  const [closeHour, closeMinute] = close.split(':').map(Number);

  const current = new Date();
  current.setHours(openHour, openMinute, 0, 0);

  const end = new Date();
  end.setHours(closeHour, closeMinute, 0, 0);

  while (current < end) {
    const hour = String(current.getHours()).padStart(2, '0');
    const minute = String(current.getMinutes()).padStart(2, '0');
    slots.push(`${hour}:${minute}`);
    current.setMinutes(current.getMinutes() + interval);
  }

  return slots;
}

const timeSlots = generateTimeSlots(CONFIG.openingTime, CONFIG.closingTime, CONFIG.slotIntervalMinutes);

function timeToMinutes(value) {
  const [h, m] = value.split(':').map(Number);
  return h * 60 + m;
}

function formatDateISO(date) {
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, '0');
  const d = String(date.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`;
}

function formatDateLabel(date) {
  return `${dayNames[date.getDay()]} ${date.getDate()}. ${monthNames[date.getMonth()]} ${date.getFullYear()}`;
}

function renderCalendar() {
  const year = viewMonth.getFullYear();
  const month = viewMonth.getMonth();
  monthLabel.textContent = `${monthNames[month]} ${year}`;
  daysGrid.innerHTML = '';

  const firstDay = new Date(year, month, 1);
  const startOffset = (firstDay.getDay() + 6) % 7;
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  for (let i = 0; i < startOffset; i++) {
    const spacer = document.createElement('button');
    spacer.type = 'button';
    spacer.className = 'day-card disabled';
    spacer.disabled = true;
    daysGrid.appendChild(spacer);
  }

  for (let day = 1; day <= daysInMonth; day++) {
    const date = new Date(year, month, day);
    const iso = formatDateISO(date);
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'day-card';
    btn.textContent = String(day);
    btn.setAttribute('aria-label', formatDateLabel(date));

    if (date < today) {
      btn.disabled = true;
      btn.classList.add('disabled');
    }
    if (iso === formatDateISO(today)) btn.classList.add('today');
    if (booking.date === iso) btn.classList.add('selected');

    btn.addEventListener('click', () => {
      booking.date = iso;
      booking.dateLabel = formatDateLabel(date);
      booking.time = '';
      renderCalendar();
      renderTimes();
      updateSummary();
    });
    daysGrid.appendChild(btn);
  }
}

function renderTimes() {
  timesGrid.innerHTML = '';
  if (!booking.date) {
    timeHint.textContent = 'Nejprve vyberte datum.';
    return;
  }

  const duration = booking.totalDuration || CONFIG.slotIntervalMinutes;
  timeHint.textContent = `Vybrané datum: ${booking.dateLabel}. Rezervace zabere cca ${duration} min.`;

  const closeMinutes = timeToMinutes(CONFIG.closingTime);
  const dateSeed = booking.date.split('-').reduce((acc, part) => acc + Number(part), 0) + duration;

  timeSlots.forEach((slot, index) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'time-chip';
    btn.textContent = slot;

    const startMinutes = timeToMinutes(slot);
    const exceedsClosing = startMinutes + duration > closeMinutes;
    const demoUnavailable = (dateSeed + index) % 11 === 0;

    if (exceedsClosing || demoUnavailable) btn.disabled = true;
    if (booking.time === slot) btn.classList.add('selected');

    btn.addEventListener('click', () => {
      booking.time = slot;
      renderTimes();
      updateSummary();
    });
    timesGrid.appendChild(btn);
  });
}

prevMonthBtn.addEventListener('click', () => {
  const prev = new Date(viewMonth.getFullYear(), viewMonth.getMonth() - 1, 1);
  const currentMonth = new Date(today.getFullYear(), today.getMonth(), 1);
  if (prev >= currentMonth) {
    viewMonth = prev;
    renderCalendar();
  }
});

nextMonthBtn.addEventListener('click', () => {
  viewMonth = new Date(viewMonth.getFullYear(), viewMonth.getMonth() + 1, 1);
  renderCalendar();
});

function setError(name, message) {
  const target = document.querySelector(`[data-error-for="${name}"]`);
  if (target) target.textContent = message || '';
}

function validateContact() {
  let valid = true;
  const firstName = document.getElementById('firstName');
  const lastName = document.getElementById('lastName');
  const phone = document.getElementById('phone');
  const email = document.getElementById('email');
  const consent = document.getElementById('consent');

  setError('firstName', ''); setError('lastName', ''); setError('phone', ''); setError('email', ''); setError('consent', '');

  if (!firstName.value.trim()) { setError('firstName', 'Doplňte prosím jméno.'); valid = false; }
  if (!lastName.value.trim()) { setError('lastName', 'Doplňte prosím příjmení.'); valid = false; }
  if (!phone.value.trim()) { setError('phone', 'Doplňte prosím telefon.'); valid = false; }
  if (email.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) { setError('email', 'Zadejte platný e-mail.'); valid = false; }
  if (!consent.checked) { setError('consent', 'Pro odeslání rezervace je potřeba souhlas.'); valid = false; }

  return valid;
}

function submitBooking() {
  const firstName = document.getElementById('firstName').value.trim();
  const lastName = document.getElementById('lastName').value.trim();
  const phone = document.getElementById('phone').value.trim();
  const email = document.getElementById('email').value.trim();
  const note = document.getElementById('note').value.trim();

  const payload = {
    branch: booking.branch,
    branchPhone: booking.branchPhone,
    services: booking.services.map((service) => ({
      id: service.id,
      category: getCategoryName(service.category),
      name: service.name,
      price: service.price,
      priceText: service.priceText,
      durationMinutes: service.duration
    })),
    totalDurationMinutes: booking.totalDuration,
    totalPrice: booking.totalPrice,
    staff: booking.staff,
    date: booking.date,
    dateLabel: booking.dateLabel,
    startTime: booking.time,
    customer: { firstName, lastName, phone, email, note },
    slotIntervalMinutes: CONFIG.slotIntervalMinutes
  };

  console.log('Booking payload ready for backend:', payload);
  localStorage.setItem('meliBookingDraft', JSON.stringify(payload));

  document.querySelectorAll('.step').forEach((step) => step.classList.remove('active'));
  bookingForm.style.display = 'none';
  stepActions.style.display = 'none';
  progressBar.style.width = '100%';
  stepHeading.textContent = 'Rezervace odeslána';
  stepDescription.textContent = 'Děkujeme. Níže najdete souhrn rezervace.';
  stepCounter.textContent = '✓';

  const serviceList = payload.services.map((service) => `<li>${service.name} - ${service.priceText} - ${service.durationMinutes} min</li>`).join('');

  document.getElementById('successDetails').innerHTML = `
    <div><span>Klient</span><strong>${firstName} ${lastName}</strong></div>
    <div><span>Pobočka</span><strong>${booking.branch}</strong></div>
    <div><span>Služby</span><strong><ul>${serviceList}</ul></strong></div>
    <div><span>Celkem</span><strong>${money(booking.totalPrice)} • ${booking.totalDuration} min</strong></div>
    <div><span>Specialistka</span><strong>${booking.staff}</strong></div>
    <div><span>Termín</span><strong>${booking.dateLabel} • ${booking.time}</strong></div>
    <div><span>Telefon</span><strong>${phone}</strong></div>
  `;
  successBox.classList.add('active');
  successBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

document.getElementById('newBooking').addEventListener('click', () => window.location.reload());

renderCategories();
renderServices();
renderSelectedServices();
renderCalendar();
renderTimes();
updateStep();
document.getElementById('year').textContent = new Date().getFullYear();

AOS.init({
  duration: 850,
  once: true,
  offset: 70,
  easing: 'ease-out-cubic',
  disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches
});
