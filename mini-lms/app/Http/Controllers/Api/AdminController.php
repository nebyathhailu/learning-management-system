<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;


class AdminController extends Controller {
    public function stats() {
        return response()->json([
            'users' => User::count(),
            'students' => User::where('role','student')->count(),
            'instructors' => User::where('role','instructor')->count(),
            'courses' => Course::count(),
            'published_courses' => Course::where('status','published')->count(),
            'enrollments' => Enrollment::count(),
        ]);
    }


    public function users(Request $request) {
        $users = User::when($request->role, fn($q,$v) => $q->where('role',$v))->paginate(20);
        return response()->json($users);
    }


    public function updateUserRole(Request $request, int $id) {
        $data = $request->validate(['role' => 'required|in:student,instructor,admin']);
        User::findOrFail($id)->update($data);
        return response()->json(['message' => 'Role updated']);
    }
}
