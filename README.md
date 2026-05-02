# University Portal System

A comprehensive, robust, and secure University Portal System built using **Native PHP, MySQL, and HTML/CSS**. This project was developed as part of the **Web Development II** course and demonstrates a strict adherence to Object-Oriented Programming (OOP) principles and modern web security standards.

---

## 📖 Project Overview

The University Portal System serves as a centralized platform for both students and administrators. It facilitates the management of university departments, courses, news announcements, and student course enrollments. The application is built without any external PHP frameworks (like Laravel or Symfony), showcasing a deep understanding of core PHP capabilities.

### 🎨 Design
The UI features a clean, professional "Dark Teal" academic theme inspired by modern university portals. It is fully responsive, ensuring a seamless experience across desktop and mobile devices.

---

## ✨ Features

### 👨‍💼 Administrator Portal
*   **Dashboard Analytics:** View real-time statistics for total users, departments, courses, and recent enrollments.
*   **Department Management:** Full CRUD (Create, Read, Update, Delete) operations for university departments.
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
*   **Database:** MySQL / MariaDB
*   **Frontend:** HTML5, CSS3 (Custom Design System), Vanilla JavaScript
*   **Architecture:** Object-Oriented Programming (OOP)
    *   **Encapsulation:** Secure data handling within classes.
    *   **Inheritance:** Base `User` class extended by `Admin` and `Student` classes.
    *   **Abstraction & Polymorphism:** Shared methods and role-specific behaviors.

---

## 🔒 Security Measures

Security is a primary focus of this application. The following measures are strictly implemented:

*   **SQL Injection Prevention:** 100% usage of **PDO Prepared Statements** for all database queries. No raw string concatenation.
*   **Cross-Site Scripting (XSS) Protection:** All user-generated output is sanitized using `htmlspecialchars()` via a dedicated utility method (`User::e()`).
*   **Cross-Site Request Forgery (CSRF) Protection:** Every state-changing POST request requires a valid, session-bound CSRF token.
*   **Authentication & Hashing:** Passwords are securely hashed using PHP's native `password_hash()` (bcrypt) and verified with `password_verify()`.
*   **Role-Based Access Control (RBAC):** Strict session middleware ensures students cannot access admin pages and unauthenticated users are redirected to the login screen.

---

## 📁 Directory Structure

```text
University Portal System-php native/
├── assets/                 # CSS design system and JavaScript utilities
│   ├── css/style.css
│   └── js/main.js
├── classes/                # Core OOP Entity Classes
│   ├── User.php            # Base class for authentication & profiles
│   ├── Admin.php           # Admin specific methods (extends User)
│   ├── Student.php         # Student specific methods (extends User)
│   ├── Department.php      # Department CRUD
│   ├── Course.php          # Course CRUD
│   ├── News.php            # News CRUD
│   └── Enrollment.php      # Enrollment logic
├── config/
│   ├── app.php             # App bootstrap, Session start, CSRF, URL routing
│   └── Database.php        # PDO Singleton connection class
├── database/
│   └── seed.sql            # Demo data (Users, Depts, Courses, etc.)
├── includes/
│   ├── auth_middleware.php # RBAC and Login enforcement
│   ├── header.php          # Global header / navigation
│   └── footer.php          # Global footer
├── views/                  # UI Pages separated by role
│   ├── admin/              # Admin CRUD interfaces
│   ├── student/            # Student interfaces
│   ├── admin_dashboard.php 
│   ├── student_dashboard.php
│   ├── student_profile.php
│   └── search.php
├── index.php               # Login page
├── register.php            # Student registration page
├── logout.php              # Session termination
└── university_portal.sql   # Database Schema
```

---

## 🚀 Installation & Setup

To run this project locally, you will need a local server environment like **XAMPP**, **MAMP**, or **Laragon**.

### 1. Clone or Extract the Project
Place the `University Portal System-php native` folder into your local server's public directory (e.g., `C:\xampp\htdocs\` for XAMPP).

### 2. Configure the Database
1. Open your database manager (e.g., phpMyAdmin at `http://localhost/phpmyadmin`).
2. Import the database schema file: `university_portal.sql`. This will create the `university_portal` database and all required tables.
3. Import the seed data file: `database/seed.sql`. This will populate the database with sample departments, courses, news, and users.

### 3. Database Credentials (Optional)
The application expects a MySQL server running on `127.0.0.1` with username `root` and an empty password. 
If your local server uses different credentials, update them in `config/Database.php`:
```php
private string $host = '127.0.0.1';
private string $dbName = 'university_portal';
private string $username = 'root';
private string $password = ''; // Update if needed
```

### 4. Run the Application
Open your web browser and navigate to the project directory, for example:
`http://localhost/University%20Portal%20System-php%20native/`

### 🔑 Demo Accounts

Use the following credentials to test the system (seeded via `seed.sql`):

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@university.com` | `password` |
| **Student** | `john@student.com` | `password` |
| **Student** | `jane@student.com` | `password` |

---

## 📝 Database Schema Overview

*   `users`: id, name, email, password, role, created_at
*   `departments`: id, name, description, created_at
*   `courses`: id, name, code, description, department_id, created_at
*   `news`: id, title, content, published_at, created_by
*   `enrollments`: id, student_id, course_id, enrolled_at

*(Relationships enforce `ON DELETE CASCADE` to maintain referential integrity).*

---
*Developed for Web Development II.*
