<?php
namespace App\Policies;


use App\Models\User;
use App\Models\Submission;


class SubmissionPolicy {
    public function view(User $user, Submission $submission): bool {
        return $user->isAdmin() || $submission->user_id === $user->id ||
            $submission->assignment->lesson->module->course->instructor_id === $user->id;
    }
}
