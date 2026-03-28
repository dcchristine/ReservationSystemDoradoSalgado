# Dorado Salgado Grand Hotel - Reservation System

Dorado, Christine C.

Salgado, James Van T.

III - CCSAD

## 🚀 Setup & Installation

1. **Local Server Setup:**
   - Ensure you have a local server environment (Laragon or XAMPP) running Apache and MySQL.
   - Place the project folder `ReservationSystemDoradoSalgado` inside your `www` (Laragon) or `htdocs` (XAMPP) directory.

2. **Database Configuration:**
   - Open your MySQL management tool (e.g., MySQL Workbench, phpMyAdmin).
   - Create a new database named `hotel_reservation_db`.
   - Import the database structures by executing the SQL scripts in the `database` folder in this exact order:
     1. First, run **`database/setup.sql`** (This creates the core tables: rooms, contacts, reservations, admin_users, and inserts the initial pricing/rooms data).
     2. Second, run **`database/add_guests.sql`** (This creates the `guests` table for authentication and updates the booking structure).

3. **Access the Application:**
   - Open your web browser and navigate to: `http://localhost/ReservationSystemDoradoSalgado/` (or your configured local virtual host domain).

---

## 🔐 System Accounts

### Admin Account

Used to access the backend dashboard to manage contacts, rooms, and approve/cancel guest reservations.

- **Username:** `admin`
- **Password:** `admin123`
- _Login URL:_ You can click "Admin Login" at the bottom of the Guest Login screen, or visit `/admin/login.php`.

### Guest (User) Account

Guests must be registered and logged in to finalize bookings and view their booking history.

existing accounts

- **Email:** `cdorado.a12241788@umak.edu.ph`
- **Password:** `123456`

---

## ⚙️ System Process & Features

### 1. Guest Browsing & Quoting (Public)

- **Home & Rooms:** Users can browse the hotel's 9 rooms (Single, Double, Family variants) through an elegant tabbed interface on the Reservation page.
- **Dynamic Quote Calculator:** Any user (anonymous or logged in) can use the Reservation page to generate a live price quote. The system calculates the total instantly using JavaScript and PHP based on:
  - Base Room Rate × Number of Days.
  - **Payment Type Surcharges:** Check (+5% fee), Credit Card (+10% fee), Cash (No fee).
  - **Length of Stay Discounts:** 3–5 days (-10% off), 6+ days (-15% off).

### 2. Guest Registration & Booking (Authenticated)

- **Account Creation:** Guests seamlessly register with their name, email, phone, and password (securely hashed via `PASSWORD_BCRYPT`).
- **Making a Reservation:** Once logged in, the quote calculator unlocks the "Confirm Reservation" feature. The user inputs conditional payment details (e.g., Credit Card numbers/CVV, or Checking Account Numbers) and "Special Requests".
- **My Bookings Dashboard:** Logged-in guests have a dedicated, private dashboard where they can see all their past and upcoming reservations, exact pricing breakdowns, and their current booking status.
- **Self-Service Cancellation:** Guests can self-cancel any of their reservations directly from "My Bookings", provided the Check-In date is still strictly in the future.

### 3. Administrator Workflow (Backend)

- **Dashboard Overview:** Admins get a bird's-eye view of revenue, total bookings, pending requests, and unread contact messages.
- **Reservation Management:** Admins review "Pending" reservations. They can verify the submitted payment details and change the status safely to "Confirmed", "Completed", or "Cancelled".
- **Inquiries:** Admins can read messages submitted through the public Contact page and mark them as read.
