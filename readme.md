# Dorado Salgado Grand Hotel - Reservation System

Dorado, Christine C.

Salgado, James Van T.

Dorado, Christine C.  
Salgado, James Van T.  
III - CCSAD

## Quick Open

Do not double-click `index.php`. PHP files must run through a server.

For Windows/Laragon, double-click:

```text
RUN_APP.bat
```

This starts PHP's local server and opens the app in the browser.

You may also start Laragon/XAMPP manually and open:

```text
http://localhost/MVCReservationSystemDoradoSalgado/
```


## Project Requirements Covered

- Custom public pages for Home, Company's Profile, Reservation, and Contacts.
- PDO database connectivity with MySQL.
- CRUD operations for reservations through the admin panel.
- Admin page for dashboard, reservations, and contact messages.
- MVC architectural pattern.

## MVC Structure

```text
app/
  Core/
    Database.php
    Router.php
  Controllers/
    HomeController.php
    ProfileController.php
    ReservationController.php
    ContactController.php
    AuthController.php
    BookingController.php
    AdminAuthController.php
    AdminController.php
  Models/
    Room.php
    Reservation.php
    Contact.php
    Guest.php
    AdminUser.php
  Views/
    layouts/
    pages/
    auth/
    bookings/
    admin/
```

The original page URLs still work, but they are now thin route wrappers. For example, `reservation.php` sends the request to `ReservationController`, which uses `Room` and `Reservation` models, then renders `app/Views/pages/reservation.php`.


## Setup

1. Start Apache and MySQL in Laragon or XAMPP.
2. Create a MySQL database named `hotel_reservation_db`.
3. Import `database/setup.sql`.
4. Check the database credentials in `config/database.php`.
5. Open the project in the browser:

```text
http://localhost/MVCReservationSystemDoradoSalgado/
```

## Accounts

Admin:

```text
Username: admin
Password: admin123
URL: /admin/login.php
```

Guest accounts can be created from the Login / Register page.

## Main Pages

- Home: `/index.php`
- Company's Profile: `/profile.php`
- Reservation: `/reservation.php`
- Contacts: `/contacts.php`
- Guest Bookings: `/my-bookings.php`
- Admin Dashboard: `/admin/dashboard.php`

## Notes

If the admin password needs to be refreshed, visit `/admin/fix_admin.php` once, then return to `/admin/login.php`.
