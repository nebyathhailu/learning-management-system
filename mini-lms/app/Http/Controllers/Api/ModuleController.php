<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(int $courseId)
    {
        $course = Course::findOrFail($courseId);
        return response()->json($course->modules()->orderBy('sort_order')->get());
    }

    public function store(Request $request, int $courseId)
    {
        $course = Course::findOrFail($courseId);
        $this->authorize('update', $course);

        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'sort_order' => 'integer|min:0',
        ]);

        $module = $course->modules()->create($data);
        return response()->json($module, 201);
    }

    public function show(int $courseId, int $id)
    {
        $module = Module::where('course_id', $courseId)->findOrFail($id);
        return response()->json($module);
    }

    public function update(Request $request, int $courseId, int $id)
    {
        $module = Module::where('course_id', $courseId)->findOrFail($id);
        $this->authorize('update', $module->course);

        $data = $request->validate([
            'title'      => 'sometimes|string|max:255',
            'sort_order' => 'integer|min:0',
        ]);

        $module->update($data);
        return response()->json($module);
    }

    public function destroy(int $courseId, int $id)
    {
        $module = Module::where('course_id', $courseId)->findOrFail($id);
        $this->authorize('update', $module->course);
        $module->delete();
        return response()->json(['message' => 'Module deleted']);
    }
}