<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\EnrollmentResource;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;


class EnrollmentController extends Controller {
    public function __construct(private EnrollmentService $enrollmentService) {}


    public function enroll(Request $request, int $courseId) {
        $result = $this->enrollmentService->enroll($request->user(), $courseId);
        return response()->json($result, $result['enrolled'] ? 201 : 200);
    }


    public function unenroll(Request $request, int $courseId) {
        $result = $this->enrollmentService->unenroll($request->user(), $courseId);
        return response()->json($result);
    }


    public function myEnrollments(Request $request) {
        return EnrollmentResource::collection($this->enrollmentService->myEnrollments($request->user()));
    }


    public function courseStudents(int $courseId) {
        return response()->json($this->enrollmentService->courseStudents($courseId));
    }
}
