<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(int $moduleId)
    {
        $module = Module::findOrFail($moduleId);
        return response()->json($module->lessons()->orderBy('sort_order')->get());
    }

    public function store(Request $request, int $moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $this->authorize('update', $module->course);

        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'nullable|string',
            'video_url'  => 'nullable|url',
            'sort_order' => 'integer|min:0',
        ]);

        $lesson = $module->lessons()->create($data);
        return response()->json($lesson, 201);
    }

    public function show(int $moduleId, int $id)
    {
        $lesson = Lesson::where('module_id', $moduleId)->findOrFail($id);
        return response()->json($lesson);
    }

    public function update(Request $request, int $moduleId, int $id)
    {
        $lesson = Lesson::where('module_id', $moduleId)->findOrFail($id);
        $this->authorize('update', $lesson->module->course);

        $data = $request->validate([
            'title'      => 'sometimes|string|max:255',
            'content'    => 'nullable|string',
            'video_url'  => 'nullable|url',
            'sort_order' => 'integer|min:0',
        ]);

        $lesson->update($data);
        return response()->json($lesson);
    }

    public function destroy(int $moduleId, int $id)
    {
        $lesson = Lesson::where('module_id', $moduleId)->findOrFail($id);
        $this->authorize('update', $lesson->module->course);
        $lesson->delete();
        return response()->json(['message' => 'Lesson deleted']);
    }
}