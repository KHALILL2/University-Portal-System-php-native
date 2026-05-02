<?php
declare(strict_types=1);

require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Enrollment.php';

/**
 * Student extends User — demonstrates Inheritance and Polymorphism.
 * Provides enrollment and browsing capabilities.
 */
class Student extends User
{
    /**
     * Enroll this student in a course.
     */
    public function enrollInCourse(int $studentId, int $courseId): bool
    {
        $enrollment = new Enrollment();
        return $enrollment->enroll($studentId, $courseId);
    }

    /**
     * Unenroll this student from a course.
     */
    public function unenrollFromCourse(int $studentId, int $courseId): bool
    {
        $enrollment = new Enrollment();
        return $enrollment->unenroll($studentId, $courseId);
    }

    /**
     * Get all enrollments for this student.
     */
    public function getEnrollments(int $studentId): array
    {
        $enrollment = new Enrollment();
        return $enrollment->getByStudent($studentId);
    }

    /**
     * Check if student is enrolled in a specific course.
     */
    public function isEnrolled(int $studentId, int $courseId): bool
    {
        $enrollment = new Enrollment();
        return $enrollment->isEnrolled($studentId, $courseId);
    }
}
