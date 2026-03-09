<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use App\Services\AssignmentService;
use Illuminate\Http\Request;


class SubmissionController extends Controller {
    public function __construct(private AssignmentService $assignmentService) {}


    public function store(Request $request, int $assignmentId) {
        $data = $request->validate([
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);
        $submission = $this->assignmentService->submit($request->user(), $assignmentId, $data, $request->file('file'));
        return new SubmissionResource($submission);
    }


    public function grade(Request $request, int $submissionId) {
        $data = $request->validate(['score' => 'required|integer|min:0', 'feedback' => 'nullable|string']);
        $submission = $this->assignmentService->grade($submissionId, $data['score'], $data['feedback'] ?? null);
        return new SubmissionResource($submission);
    }


    public function show(int $id) {
        $submission = Submission::with('student')->findOrFail($id);
        $this->authorize('view', $submission);
        return new SubmissionResource($submission);
    }
}
