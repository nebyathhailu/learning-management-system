<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(int $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        return response()->json($lesson->assignments()->get());
    }

    public function store(Request $request, int $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $this->authorize('update', $lesson->module->course);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'max_score'   => 'nullable|integer|min:0',
        ]);

        $assignment = $lesson->assignments()->create($data);
        return response()->json($assignment, 201);
    }

    public function show(int $id)
    {
        $assignment = Assignment::with('lesson')->findOrFail($id);
        return response()->json($assignment);
    }

    public function update(Request $request, int $id)
    {
        $assignment = Assignment::findOrFail($id);
        $this->authorize('update', $assignment->lesson->module->course);

        $data = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'max_score'   => 'nullable|integer|min:0',
        ]);

        $assignment->update($data);
        return response()->json($assignment);
    }

    public function destroy(int $id)
    {
        $assignment = Assignment::findOrFail($id);
        $this->authorize('update', $assignment->lesson->module->course);
        $assignment->delete();
        return response()->json(['message' => 'Assignment deleted']);
    }
}