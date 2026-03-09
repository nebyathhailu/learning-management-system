<?php
namespace App\Interfaces;


interface EnrollmentRepositoryInterface {
    public function enroll(int $userId, int $courseId): bool;
    public function unenroll(int $userId, int $courseId): bool;
    public function isEnrolled(int $userId, int $courseId): bool;
    public function getStudentEnrollments(int $userId);
    public function getCourseStudents(int $courseId);
}
