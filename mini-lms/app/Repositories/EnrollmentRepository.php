<?php
namespace App\Repositories;


use App\Interfaces\EnrollmentRepositoryInterface;
use App\Models\Enrollment;


class EnrollmentRepository implements EnrollmentRepositoryInterface {
    public function enroll(int $userId, int $courseId): bool {
        if ($this->isEnrolled($userId, $courseId)) return false;
        Enrollment::create(['user_id' => $userId, 'course_id' => $courseId]);
        return true;
    }
    public function unenroll(int $userId, int $courseId): bool {
        return (bool) Enrollment::where(['user_id'=>$userId,'course_id'=>$courseId])->delete();
    }
    public function isEnrolled(int $userId, int $courseId): bool {
        return Enrollment::where(['user_id'=>$userId,'course_id'=>$courseId])->exists();
    }
    public function getStudentEnrollments(int $userId) {
        return Enrollment::where('user_id', $userId)->with('course')->paginate(15);
    }
    public function getCourseStudents(int $courseId) {
        return Enrollment::where('course_id', $courseId)->with('user')->paginate(15);
    }
}
