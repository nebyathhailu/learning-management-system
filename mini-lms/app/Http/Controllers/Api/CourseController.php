<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Services\CourseService;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller {
    public function __construct(private CourseService $courseService) {}


    public function index(Request $request) {
        $filters = $request->only(['search','category','level']);
        $courses = $this->courseService->list($filters, $request->get('per_page', 15));
        return CourseResource::collection($courses);
    }


    public function show(string $slug) {
        $course = $this->courseService->show($slug);
        $this->authorize('view', $course);
        return new CourseResource($course);
    }


    public function store(Request $request) {
        $this->authorize('create', Course::class);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'price' => 'nullable|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);
        $data['instructor_id'] = $request->user()->id;
        $course = $this->courseService->create($data, $request->file('thumbnail'));
        return new CourseResource($course);
    }


    public function update(Request $request, Course $course) {
        // $course = \App\Models\Course::findOrFail($id);
        $this->authorize('update', $course);
        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:draft,published,archived',
            'thumbnail' => 'nullable|image|max:2048',
        ]);
        $course = $this->courseService->update($course->id, $data, $request->file('thumbnail'));
        return new CourseResource($course);
    }


    public function destroy(int $id) {
        $course = \App\Models\Course::findOrFail($id);
        $this->authorize('delete', $course);
        $this->courseService->delete($id);
        return response()->json(['message' => 'Course deleted']);
    }
}
