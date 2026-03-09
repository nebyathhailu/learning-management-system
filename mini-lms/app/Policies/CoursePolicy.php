<?php
namespace App\Policies;


use App\Models\User;
use App\Models\Course;


class CoursePolicy {
    public function viewAny(?User $user): bool { return true; }
    public function view(?User $user, Course $course): bool {
        return $course->status === 'published' || ($user && ($user->isAdmin() || $course->instructor_id === $user->id));
    }
    public function create(User $user): bool { return $user->isInstructor() || $user->isAdmin(); }
    public function update(User $user, Course $course): bool {
        return $user->isAdmin() || $course->instructor_id === $user->id;
    }
    public function delete(User $user, Course $course): bool { return $this->update($user, $course); }
}
