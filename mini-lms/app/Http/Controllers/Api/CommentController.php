<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;


class CommentController extends Controller {
    public function index(int $lessonId) {
        $comments = Comment::with(['user','replies.user'])->where('lesson_id',$lessonId)->whereNull('parent_id')->paginate(20);
        return CommentResource::collection($comments);
    }


    public function store(Request $request, int $lessonId) {
        $data = $request->validate(['body' => 'required|string', 'parent_id' => 'nullable|exists:comments,id']);
        $comment = Comment::create(array_merge($data, ['lesson_id' => $lessonId, 'user_id' => $request->user()->id]));
        return new CommentResource($comment->load('user'));
    }


    public function destroy(Request $request, int $id) {
        $comment = Comment::findOrFail($id);
        $this->authorize('delete', $comment);
        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
}
