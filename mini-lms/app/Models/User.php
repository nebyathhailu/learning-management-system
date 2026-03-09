<?php
namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable {
    use HasApiTokens, Notifiable;


    protected $fillable = ['name', 'email', 'password', 'role', 'bio', 'avatar'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['password' => 'hashed'];


    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isInstructor(): bool { return $this->role === 'instructor'; }
    public function isStudent(): bool { return $this->role === 'student'; }


    public function courses() { return $this->hasMany(Course::class, 'instructor_id'); }
    public function enrollments() { return $this->hasMany(Enrollment::class); }
    public function enrolledCourses() {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('status', 'enrolled_at', 'completed_at')->withTimestamps();
    }
    public function submissions() { return $this->hasMany(Submission::class); }
    public function completedLessons() { return $this->belongsToMany(Lesson::class, 'lesson_completions')->withPivot('completed_at'); }
    public function comments() { return $this->hasMany(Comment::class); }
}