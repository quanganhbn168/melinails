const menuToggle = document.querySelector('.menu-toggle');
const mobileLinks = document.querySelectorAll('.mobile-menu a');

menuToggle?.addEventListener('click', () => {
  const isOpen = document.body.classList.toggle('menu-open');
  menuToggle.setAttribute('aria-expanded', String(isOpen));
});

mobileLinks.forEach((link) => {
  link.addEventListener('click', () => {
    document.body.classList.remove('menu-open');
    menuToggle?.setAttribute('aria-expanded', 'false');
  });
});

const contactForm = document.getElementById('contactForm');
const formSuccess = document.getElementById('formSuccess');

function setError(name, message) {
  const target = document.querySelector(`[data-error-for="${name}"]`);
  if (target) target.textContent = message || '';
}

function validateForm() {
  let valid = true;
  const name = document.getElementById('name');
  const phone = document.getElementById('phone');
  const email = document.getElementById('email');
  const message = document.getElementById('message');
  const consent = document.getElementById('consent');

  ['name', 'phone', 'email', 'message', 'consent'].forEach((field) => setError(field, ''));

  if (!name.value.trim()) {
    setError('name', 'Doplňte prosím jméno.');
    valid = false;
  }

  if (!phone.value.trim()) {
    setError('phone', 'Doplňte prosím telefon.');
    valid = false;
  }

  if (email.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
    setError('email', 'Zadejte platný e-mail.');
    valid = false;
  }

  if (!message.value.trim()) {
    setError('message', 'Napište prosím zprávu.');
    valid = false;
  }

  if (!consent.checked) {
    setError('consent', 'Pro odeslání je potřeba souhlas.');
    valid = false;
  }

  return valid;
}

contactForm?.addEventListener('submit', (event) => {
  event.preventDefault();

  if (!validateForm()) return;

  const payload = {
    name: document.getElementById('name').value.trim(),
    phone: document.getElementById('phone').value.trim(),
    email: document.getElementById('email').value.trim(),
    branch: document.getElementById('branch').value,
    topic: document.getElementById('topic').value,
    preferredTime: document.getElementById('preferredTime').value,
    message: document.getElementById('message').value.trim(),
    createdAt: new Date().toISOString()
  };

  console.log('Contact payload ready for backend:', payload);
  localStorage.setItem('meliContactMessage', JSON.stringify(payload));
  formSuccess.classList.add('active');
  contactForm.reset();
});

document.querySelectorAll('.faq-item').forEach((item) => {
  item.addEventListener('click', () => {
    const isActive = item.classList.toggle('active');
    item.setAttribute('aria-expanded', String(isActive));
  });
});

document.getElementById('year').textContent = new Date().getFullYear();

AOS.init({
  duration: 850,
  once: true,
  offset: 70,
  easing: 'ease-out-cubic',
  disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches
});
