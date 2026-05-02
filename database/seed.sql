-- Seed data for University Portal System
-- Password hash is for 'password' (bcrypt)

INSERT INTO users (name, email, password, role) VALUES 
('System Admin', 'admin@university.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('John Doe', 'john@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('Jane Smith', 'jane@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

INSERT INTO departments (name, description) VALUES 
('Computer Science', 'CS Department offering Software Engineering, Networking, and AI programs.'),
('Mathematics', 'Department of Applied and Pure Mathematics.'),
('Physics', 'Department of Physics covering Mechanics, Thermodynamics, and Quantum Physics.');

INSERT INTO courses (name, code, description, department_id) VALUES 
('Web Development II', 'CS402', 'Advanced PHP and MySQL with OOP', 1),
('Data Structures', 'CS201', 'Trees, Graphs, and Hash Tables', 1),
('Artificial Intelligence', 'CS501', 'Introduction to AI and Machine Learning', 1),
('Calculus I', 'MATH101', 'Limits, Derivatives, and Integrals', 2),
('Linear Algebra', 'MATH201', 'Vectors, Matrices, and Linear Transformations', 2),
('Classical Mechanics', 'PHY101', 'Newtons Laws and Kinematics', 3);

INSERT INTO news (title, content, created_by) VALUES
('Welcome to the University Portal', 'We are excited to launch the new University Portal System! Students can now browse courses, enroll online, and stay updated with the latest news. Navigate through the portal to explore all features.', 1),
('Registration Period Open', 'The registration period for the Fall 2026 semester is now open. Please browse available courses and complete your enrollment before the deadline on June 15, 2026.', 1),
('New AI Course Available', 'We are pleased to announce a new Artificial Intelligence course (CS501) in the Computer Science department. This course covers fundamentals of AI and Machine Learning. Enroll now!', 1);

INSERT INTO enrollments (student_id, course_id) VALUES 
(2, 1),
(2, 2),
(3, 1),
(3, 4);
