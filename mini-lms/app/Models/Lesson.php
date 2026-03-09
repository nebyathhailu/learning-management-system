<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Lesson extends Model {
    protected $fillable = ['module_id','title','content','video_url','duration_minutes','sort_order','is_free_preview'];


    public function module() { return $this->belongsTo(Module::class); }
    public function course() { return $this->hasOneThrough(Course::class, Module::class, 'id', 'id', 'module_id', 'course_id'); }
    public function assignments() { return $this->hasMany(Assignment::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function completions() { return $this->hasMany(LessonCompletion::class); }
    public function completedByUsers() { return $this->belongsToMany(User::class, 'lesson_completions')->withPivot('completed_at'); }
}
