# Railway Reservation System

A PHP and MySQL based railway reservation web application. It provides a passenger-facing flow to register, log in, search trains, book seats, view bookings, and cancel tickets. It also includes an admin area for managing trains, users, and bookings.

## Features

### Passenger
- User registration and login
- Search trains by source and destination
- View train details and available seats
- Book one or more seats
- View personal booking history
- Cancel a booking

### Admin
- Admin login
- Admin dashboard
- Add, edit, and delete trains
- View and manage bookings
- View, add, edit, and delete users

## Tech stack

- PHP 8+
- MySQL
- HTML, CSS, JavaScript
- XAMPP (recommended for local development)
- Font Awesome and Google Fonts (loaded from their CDNs)

## Project structure

```text
railway-reservation/
├── assets/                 # Images and visual assets
├── includes/
│   └── db.php              # MySQL connection settings
├── index.php               # Public home page
├── register.php             # Passenger registration
├── login.php                # Passenger login
├── search_train.php         # Train search
├── book_ticket.php          # Ticket booking
├── my_booking.php           # Passenger booking history
├── cancel_ticket.php        # Passenger ticket cancellation
├── admin_login.php          # Admin login
├── admin_dashboard.php      # Admin dashboard
├── manage_trains.php        # Train management
├── manage_users.php         # User management
└── manage_bookings.php      # Booking management
```

## Run locally with XAMPP

### 1. Install and start services

1. Install [XAMPP](https://www.apachefriends.org/).
2. Open XAMPP Control Panel.
3. Start **Apache** and **MySQL**.

### 2. Copy the project

Copy this project folder into XAMPP's `htdocs` directory. For example:

```text
C:\xampp\htdocs\railway-reservation
```

The folder containing `index.php` should be the folder served by Apache.

### 3. Create the database

Open [phpMyAdmin](http://localhost/phpmyadmin), create a database named `railway`, then open the SQL tab and run the following starter schema:

```sql
CREATE DATABASE IF NOT EXISTS railway;
USE railway;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS trains (
    id INT AUTO_INCREMENT PRIMARY KEY,
    train_name VARCHAR(150) NOT NULL,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    departure_time TIME NOT NULL,
    arrival_time TIME NOT NULL,
    seats INT NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    train_id INT NOT NULL,
    seats_booked INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bookings_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_bookings_train
        FOREIGN KEY (train_id) REFERENCES trains(id)
        ON DELETE CASCADE
);
```

Add an admin account for local testing (change these sample credentials):

```sql
INSERT INTO admins (username, password)
VALUES ('admin', 'change-this-password');
```

> The current PHP admin login compares the password directly with the database value. This is suitable only for a local college/demo project. Do not use this authentication implementation with real user data or deploy it publicly without first implementing password hashing, prepared statements, CSRF protection, and other security hardening.

### 4. Configure the database connection

Open `includes/db.php` and confirm the settings match your local MySQL setup:

```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "railway";
```

If your MySQL root account has a password, put it in `$pass`. Do not commit real passwords or production credentials to GitHub.

### 5. Open the website

With Apache running, visit:

```text
http://localhost/railway-reservation/
```

If you use PHP's built-in server instead of Apache, open PowerShell in the folder containing `index.php` and run:

```powershell
C:\xampp\php\php.exe -S 0.0.0.0:8000
```

Then visit `http://localhost:8000`.

To test from a phone on the same Wi-Fi, use the computer's local IPv4 address:

```text
http://YOUR-COMPUTER-IP:8000
```

Keep the PHP server terminal open while testing. Allow PHP through Windows Firewall on private networks if prompted. The phone and computer must be on the same local network.

## GitHub: push future changes

After creating the GitHub repository and connecting it as `origin`, use:

```powershell
git add .
git commit -m "Describe your changes"
git push
```

For the first push of a new local branch, use:

```powershell
git push -u origin main
```

## Important notes

- The application expects a MySQL database named `railway`.
- Keep database credentials private. Review `includes/db.php` before publishing changes.
- The starter schema above is based on the columns referenced by the PHP pages. If you already have a working database, back it up before changing its structure.
- This project is intended as a learning/demo application. Perform a security review before any public or production deployment.

## Author

Add your name and project/course details here.
