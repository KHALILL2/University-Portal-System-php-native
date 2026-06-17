# University Portal System (PHP Native)

A comprehensive, robust, and secure University Portal System built entirely with **Native PHP, MySQL, and HTML/CSS**. 
This project demonstrates a strict adherence to Object-Oriented Programming (OOP) principles, modern web security standards, and clean architectural patterns without relying on external PHP frameworks like Laravel or Symfony.

---

## 📖 Project Overview

The University Portal System serves as a centralized platform for both students and administrators. It facilitates the management of university departments, courses, news announcements, and student course enrollments. 

### 🎨 Design & UI/UX Enhancements
The UI features a clean, professional "Dark Teal" academic theme inspired by modern university portals.
*   **Responsive Design:** Flawless viewing across monitors, laptops, tablets, and smartphones using fluid CSS grids and robust media queries.
*   **Scroll Animations:** Elements such as data cards and tables dynamically slide and fade into view using Javascript's `IntersectionObserver`.
*   **Modern Iconography:** Comprehensive integration of FontAwesome across the navigation, dashboards, and the premium custom footer.
*   **Custom Footer:** A 4-column responsive footer linked to external university resources and the legacy BATU Timetable project.

---

## ✨ Features

### 👨‍💼 Administrator Portal
*   **Dashboard Analytics:** View real-time statistics for total users, departments, courses, and recent enrollments.
*   **Department Management:** Full CRUD operations for university departments.
*   **Course Management:** Create and manage courses, linking them to specific departments.
*   **News & Announcements:** Publish, edit, and remove news articles visible to all students.
*   **User Management:** View all registered users and remove accounts (with self-deletion protection).
*   **Global Search:** Instantly search across courses and departments.

### 🎓 Student Portal
*   **Student Dashboard:** Quick overview of enrolled courses and university news.
*   **Course Browsing:** View all available courses, filterable by department.
*   **Course Enrollment:** Seamlessly enroll in or unenroll from courses.
*   **Profile Management:** Update personal information, email, and password.
*   **News Feed:** Stay updated with the latest announcements from administrators.

---

## 🛠️ Technology Stack & Architecture

*   **Backend:** Native PHP 7.4+
*   **Database:** MySQL / MariaDB (via PDO)
*   **Frontend:** HTML5, CSS3 (Custom Design System), Vanilla JavaScript
*   **Architecture:** Object-Oriented Programming (OOP)
    *   **Encapsulation:** Secure data handling within classes.
    *   **Inheritance:** Base `User` class extended by `Admin` and `Student` classes.
    *   **Native Autoloading:** Utilizes `spl_autoload_register()` to dynamically load classes, eliminating the need for manual `require_once` statements.
    *   **Exception Handling:** Centralized `try/catch` blocks in controllers gracefully handle native `Exceptions` thrown by the models, preventing error leakage.

---

## 🔒 Security Measures

Security is a primary focus of this application. The following measures are strictly implemented:

*   **SQL Injection Prevention:** 100% usage of **PDO Prepared Statements** for all database queries. No raw string concatenation.
*   **Cross-Site Request Forgery (CSRF) Protection:** Every state-changing `POST` request, as well as the `GET` logout route, requires a valid, session-bound CSRF token.
*   **Session Security:** Implements `session_regenerate_id(true)` upon authentication to prevent Session Fixation attacks.
*   **Cross-Site Scripting (XSS) Protection:** All user-generated output is sanitized using `htmlspecialchars()` via a dedicated utility method (`User::e()`).
*   **Authentication & Hashing:** Passwords are securely hashed using PHP's native `password_hash()` (bcrypt) and verified with `password_verify()`.
*   **Role-Based Access Control (RBAC):** Strict session middleware ensures students cannot access admin pages and unauthenticated users are redirected to the login screen.
*   **Environment Variables:** Sensitive database credentials are extracted into an external `env.php` file excluded from version control to prevent accidental leaks.

---

## 📁 Directory Structure

```text
University Portal System-php native/
├── assets/                 # CSS design system and JavaScript utilities
├── classes/                # Core OOP Entity Classes (Auto-loaded)
│   ├── User.php            
│   ├── Admin.php           
│   ├── Student.php         
│   ├── Department.php      
│   ├── Course.php          
│   ├── News.php            
│   └── Enrollment.php      
├── config/
│   ├── app.php             # App bootstrap, Session start, Autoloader, CSRF, URL routing
│   ├── Database.php        # PDO Singleton connection class
│   └── env.php             # (GIT IGNORED) Database credentials
├── database/
│   └── seed.sql            # Demo data
├── includes/
│   ├── auth_middleware.php # RBAC and Login enforcement
│   ├── header.php          
│   └── footer.php          
├── views/                  # UI Pages separated by role
├── index.php               # Login page
├── register.php            # Student registration page
├── logout.php              # Secure Session termination
└── university_portal.sql   # Database Schema
```

---

## 🚀 Installation & Setup

### 1. Clone the Project
Clone this repository into your local web server environment (e.g., `htdocs` for XAMPP, `www` for WAMP/Laragon).

### 2. Configure the Environment
1. In the `config/` directory, create a new file named `env.php` (if it does not already exist).
2. Add your database credentials as constants:
```php
<?php
// config/env.php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'university_portal');
define('DB_USER', 'root');
define('DB_PASS', ''); // Leave empty for default XAMPP/Laragon
```

### 3. Setup the Database
1. Open your database manager (e.g., phpMyAdmin or HeidiSQL).
2. Import the `university_portal.sql` file to create the tables.
3. Import the `database/seed.sql` file to populate the database with sample departments, courses, news, and users.

### 4. Run the Application
Navigate to the project directory in your browser: `http://localhost/University-Portal-System-php-native/`

---

## ☁️ Live Deployment (InfinityFree)

If deploying to a shared host like **InfinityFree**:
1. Upload all files via FTP or the built-in File Manager.
2. In the hosting control panel, create a new MySQL Database.
3. Import the SQL files using the provided phpMyAdmin interface.
4. **Crucial:** Update `config/env.php` on the live server with your remote InfinityFree database credentials (Host, DB Name, DB User, and DB Password). Do not upload your local `env.php` file if tracking with Git.

---

## 🔑 Demo Accounts

Use the following credentials to test the system (seeded via `seed.sql`):

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@university.com` | `password` |
| **Student** | `john@student.com` | `password` |
| **Student** | `jane@student.com` | `password` |

---
*Developed for Web Development II.*
