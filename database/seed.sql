-- Seed data for University Portal System
-- Password hash is for 'password' (bcrypt)

INSERT INTO users (name, email, password, role) VALUES 
('System Admin', 'admin@university.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('John Doe', 'john@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Jane Smith', 'jane@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

INSERT INTO departments (name, description) VALUES 
('Information Technology', 'Common track for all first and second year IT students.'),
('Software Development', 'Specialized track focusing on software engineering, programming, and development.'),
('Network', 'Specialized track focusing on networking, CCNA, and cybersecurity.');

INSERT INTO courses (name, code, description, department_id) VALUES 
('Intro. to Cyber Security', 'IT101', 'Introduction to fundamental concepts of cybersecurity and protection mechanisms.', 1),
('IT Essentials', 'IT102', 'Comprehensive introduction to computer hardware, software, and networking.', 1),
('Programming Essentials in Python', 'IT106', 'Introduction to programming concepts using Python.', 1),
('Advanced Programming in C++', 'SW301', 'Advanced C++ programming concepts including templates, STL, and design patterns.', 2),
('Mobile Programming II', 'SW402', 'Advanced mobile application development for iOS and Android.', 2),
('CCNA R&S II', 'NW301', 'Advanced Cisco CCNA Routing & Switching covering routing protocols and VLANs.', 3),
('Server Administration', 'NW405', 'Windows and Linux server administration, virtualization, and infrastructure management.', 3);

INSERT INTO news (title, content, created_by) VALUES
('Welcome to BATU Portal', 'Welcome to the new BATU University Portal System! Students can now browse IT, Software Development, and Network courses, enroll online, and stay updated.', 1),
('Fall 2026 Registration Open', 'The registration period for the Fall 2026 semester is now open. Make sure to check the BATU Timetable to plan your schedule before enrolling.', 1),
('New CCNA Certification Track', 'The Network department has updated the CCNA R&S IV course to align with the latest industry certifications. Enroll today to secure your spot.', 1);

INSERT INTO enrollments (student_id, course_id) VALUES 
(2, 1),
(2, 2),
(2, 3),
(3, 4),
(3, 5);
