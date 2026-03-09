<?php
namespace App\Interfaces;


interface CourseRepositoryInterface {
    public function all(array $filters, int $perPage);
    public function findById(int $id);
    public function findBySlug(string $slug);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getByInstructor(int $instructorId, int $perPage);
}
