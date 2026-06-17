<?php
declare(strict_types=1);

require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Enrollment.php';
require_once __DIR__ . '/Department.php';
require_once __DIR__ . '/Course.php';

// Students are just Users with the ability to browse and manage their classes
class Student extends User
{
    // Let students see all departments and their descriptions
    public function viewDepartments(): array
    {
        $dept = new Department();
        return $dept->getAll();
    }

    // Browse courses, with an optional department filter
    public function viewCourses(int $departmentId = 0): array
    {
        $course = new Course();
        return ($departmentId > 0) ? $course->getByDepartment($departmentId) : $course->getAll();
    }

    // Pass the enrollment request off to the Enrollment class
    public function enrollInCourse(int $studentId, int $courseId): bool
    {
        $enrollment = new Enrollment();
        return $enrollment->enroll($studentId, $courseId);
    }

    // Drop a class
    public function unenrollFromCourse(int $studentId, int $courseId): bool
    {
        $enrollment = new Enrollment();
        return $enrollment->unenroll($studentId, $courseId);
    }

    // Fetch the list of classes the student is currently taking
    public function getEnrollments(int $studentId): array
    {
        $enrollment = new Enrollment();
        return $enrollment->getByStudent($studentId);
    }

    // Quick check to see if a student is already in a course (used for hiding the enroll button)
    public function isEnrolled(int $studentId, int $courseId): bool
    {
        $enrollment = new Enrollment();
        return $enrollment->isEnrolled($studentId, $courseId);
    }
}
