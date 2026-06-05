# Meli Nails & Beauty - Booking Page

## Files

- `booking.html` - booking page markup
- `css/booking.css` - full styling
- `js/services-data.js` - service categories and individual services
- `js/booking.js` - booking flow, multi-service selection, search/filter/sort, calendar, 15-minute slots, validation, payload
- `assets/logo.png` - logo
- `assets/favicon.png` - favicon

## Key features

- Choose branch
- Choose multiple services
- Search services
- Filter services by category
- Sort services
- Scroll inside service list
- Auto calculate total duration and total price
- Time slots generated every 15 minutes
- Disable times that do not fit before closing time
- Contact validation
- Review step
- Success state
- Backend-ready payload printed in console and saved in localStorage for demo

## Slot interval

Edit `js/booking.js`:

```js
const CONFIG = {
  openingTime: '08:00',
  closingTime: '20:00',
  slotIntervalMinutes: 15
};
```

Change `slotIntervalMinutes` to `30` if needed later.

## Backend integration

Replace this part in `submitBooking()`:

```js
console.log('Booking payload ready for backend:', payload);
localStorage.setItem('meliBookingDraft', JSON.stringify(payload));
```

with an API call, for example:

```js
await fetch('/api/bookings', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify(payload)
});
```
