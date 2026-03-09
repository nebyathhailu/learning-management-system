<?php
namespace App\Repositories;


use App\Interfaces\CourseRepositoryInterface;
use App\Models\Course;
use Illuminate\Support\Str;


class CourseRepository implements CourseRepositoryInterface {
    public function all(array $filters = [], int $perPage = 15) {
        return Course::published()->filter($filters)->with('instructor')->paginate($perPage);
    }
    public function findById(int $id) { return Course::with(['instructor','modules.lessons'])->findOrFail($id); }
    public function findBySlug(string $slug) { return Course::with(['instructor','modules.lessons'])->where('slug',$slug)->firstOrFail(); }
    public function create(array $data) {
        $data['slug'] = Str::slug($data['title']);
        return Course::create($data);
    }
    public function update(int $id, array $data) {
        $course = Course::findOrFail($id);
        if (isset($data['title'])) $data['slug'] = Str::slug($data['title']);
        $course->update($data);
        return $course;
    }
    public function delete(int $id) { return Course::findOrFail($id)->delete(); }
    public function getByInstructor(int $instructorId, int $perPage = 15) {
        return Course::where('instructor_id', $instructorId)->paginate($perPage);
    }
}
