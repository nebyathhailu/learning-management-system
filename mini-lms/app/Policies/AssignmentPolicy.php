<?php
namespace App\Policies;


use App\Models\User;
use App\Models\Assignment;


class AssignmentPolicy {
    public function create(User $user): bool { return $user->isInstructor() || $user->isAdmin(); }
    public function update(User $user, Assignment $assignment): bool {
        return $user->isAdmin() || $assignment->lesson->module->course->instructor_id === $user->id;
    }
    public function grade(User $user, Assignment $assignment): bool { return $this->update($user, $assignment); }
}
