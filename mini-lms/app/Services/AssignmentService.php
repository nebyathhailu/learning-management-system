<?php
namespace App\Services;


use App\Models\Assignment;
use App\Models\Submission;
use App\Notifications\AssignmentGraded;
use Illuminate\Support\Facades\Storage;


class AssignmentService {
    public function createAssignment(array $data): Assignment { return Assignment::create($data); }


    public function submit($user, int $assignmentId, array $data, $file = null): Submission {
        if ($file) {
            $data['file_path'] = $file->store('submissions', 'private');
            $data['file_name'] = $file->getClientOriginalName();
        }
        return Submission::updateOrCreate(
            ['assignment_id' => $assignmentId, 'user_id' => $user->id],
            array_merge($data, ['status' => 'submitted', 'submitted_at' => now()])
        );
    }


    public function grade(int $submissionId, int $score, string $feedback = null): Submission {
        $submission = Submission::with('student')->findOrFail($submissionId);
        $submission->update(['score' => $score, 'feedback' => $feedback, 'status' => 'graded', 'graded_at' => now()]);
        $submission->student->notify(new AssignmentGraded($submission));
        return $submission;
    }
}
