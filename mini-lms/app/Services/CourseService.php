<?php
namespace App\Services;


use App\Interfaces\CourseRepositoryInterface;
use Illuminate\Support\Facades\Storage;


class CourseService {
    public function __construct(private CourseRepositoryInterface $courseRepository) {}


    public function list(array $filters = [], int $perPage = 15) {
        return $this->courseRepository->all($filters, $perPage);
    }
    public function show(string $slug) { return $this->courseRepository->findBySlug($slug); }
    public function create(array $data, $thumbnail = null) {
        if ($thumbnail) $data['thumbnail'] = $thumbnail->store('thumbnails','public');
        return $this->courseRepository->create($data);
    }
    public function update(int $id, array $data, $thumbnail = null) {
        if ($thumbnail) {
            $course = $this->courseRepository->findById($id);
            if ($course->thumbnail) Storage::disk('public')->delete($course->thumbnail);
            $data['thumbnail'] = $thumbnail->store('thumbnails','public');
        }
        return $this->courseRepository->update($id, $data);
    }
    public function delete(int $id) { return $this->courseRepository->delete($id); }
    public function instructorCourses(int $instructorId) { return $this->courseRepository->getByInstructor($instructorId); }
}
