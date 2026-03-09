<?php
namespace App\Services;


use App\Interfaces\EnrollmentRepositoryInterface;
use App\Notifications\EnrollmentConfirmed;


class EnrollmentService {
    public function __construct(private EnrollmentRepositoryInterface $enrollmentRepository) {}


    public function enroll($user, int $courseId): array {
        $enrolled = $this->enrollmentRepository->enroll($user->id, $courseId);
        if ($enrolled) $user->notify(new EnrollmentConfirmed($courseId));
        return ['enrolled' => $enrolled, 'message' => $enrolled ? 'Enrolled successfully' : 'Already enrolled'];
    }
    public function unenroll($user, int $courseId): array {
        $removed = $this->enrollmentRepository->unenroll($user->id, $courseId);
        return ['unenrolled' => $removed];
    }
    public function myEnrollments($user) { return $this->enrollmentRepository->getStudentEnrollments($user->id); }
    public function courseStudents(int $courseId) { return $this->enrollmentRepository->getCourseStudents($courseId); }
}
