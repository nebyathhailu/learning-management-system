<?php
namespace App\Services;


use App\Models\LessonCompletion;
use App\Models\Enrollment;
use App\Models\Lesson;


class ProgressService {
    public function markComplete($user, int $lessonId): array {
        [$completion, $created] = LessonCompletion::firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lessonId],
            ['completed_at' => now()]
        );
        $this->checkCourseCompletion($user, $lessonId);
        return ['completed' => $created, 'already_completed' => !$created];
    }


    public function getCourseProgress($user, int $courseId): array {
        $enrollment = Enrollment::where(['user_id' => $user->id, 'course_id' => $courseId])->firstOrFail();
        $totalLessons = Lesson::whereHas('module', fn($q) => $q->where('course_id', $courseId))->count();
        $completedLessons = LessonCompletion::where('user_id', $user->id)
            ->whereHas('lesson.module', fn($q) => $q->where('course_id', $courseId))->count();
        return [
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'percentage' => $totalLessons ? round(($completedLessons / $totalLessons) * 100) : 0,
        ];
    }


    private function checkCourseCompletion($user, int $lessonId): void {
        $lesson = Lesson::with('module')->findOrFail($lessonId);
        $courseId = $lesson->module->course_id;
        $progress = $this->getCourseProgress($user, $courseId);
        if ($progress['percentage'] === 100) {
            Enrollment::where(['user_id' => $user->id, 'course_id' => $courseId])
                ->update(['status' => 'completed', 'completed_at' => now()]);
        }
    }
}
