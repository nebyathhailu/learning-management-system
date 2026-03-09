<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\ProgressService;
use Illuminate\Http\Request;


class ProgressController extends Controller {
    public function __construct(private ProgressService $progressService) {}


    public function markComplete(Request $request, int $lessonId) {
        $result = $this->progressService->markComplete($request->user(), $lessonId);
        return response()->json($result);
    }


    public function courseProgress(Request $request, int $courseId) {
        $progress = $this->progressService->getCourseProgress($request->user(), $courseId);
        return response()->json($progress);
    }
}
