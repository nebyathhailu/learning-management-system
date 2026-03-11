<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\AdminController;


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{slug}', [CourseController::class, 'show']);


// Auth required routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);


    // Enrollment
    Route::post('/courses/{courseId}/enroll', [EnrollmentController::class, 'enroll']);
    Route::delete('/courses/{courseId}/enroll', [EnrollmentController::class, 'unenroll']);
    Route::get('/my-enrollments', [EnrollmentController::class, 'myEnrollments']);


    // Progress
    Route::post('/lessons/{lessonId}/complete', [ProgressController::class, 'markComplete']);
    Route::get('/courses/{courseId}/progress', [ProgressController::class, 'courseProgress']);


    // Comments
    Route::get('/lessons/{lessonId}/comments', [CommentController::class, 'index']);
    Route::post('/lessons/{lessonId}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);


    // Assignments - student submit
    Route::post('/assignments/{assignmentId}/submit', [SubmissionController::class, 'store']);
    Route::get('/submissions/{id}', [SubmissionController::class, 'show']);


    // Instructor + Admin routes
    Route::middleware('role:instructor,admin')->group(function () {
        Route::apiResource('courses', CourseController::class)->except(['index']);
        Route::apiResource('courses.modules', ModuleController::class)->except(['index','show']);
        Route::apiResource('modules.lessons', LessonController::class)->except(['index','show']);
        Route::post('/lessons/{lessonId}/assignments', [AssignmentController::class, 'store']);
        Route::put('/assignments/{id}', [AssignmentController::class, 'update']);
        Route::post('/submissions/{id}/grade', [SubmissionController::class, 'grade']);
        Route::get('/courses/{courseId}/students', [EnrollmentController::class, 'courseStudents']);
    });


    // Admin only
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::patch('/users/{id}/role', [AdminController::class, 'updateUserRole']);
    });
});

