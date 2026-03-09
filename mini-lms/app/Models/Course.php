<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Course extends Model {
    use HasFactory;


    protected $fillable = ['instructor_id','title','slug','description','thumbnail','status','price','category','level'];


    public function instructor() { return $this->belongsTo(User::class, 'instructor_id'); }
    public function modules() { return $this->hasMany(Module::class)->orderBy('sort_order'); }
    public function lessons() { return $this->hasManyThrough(Lesson::class, Module::class); }
    public function enrollments() { return $this->hasMany(Enrollment::class); }
    public function students() { return $this->belongsToMany(User::class, 'enrollments')->withPivot('status'); }


    public function scopePublished($query) { return $query->where('status', 'published'); }
    public function scopeSearch($query, $term) {
        return $query->where('title', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%");
    }
    public function scopeFilter($query, array $filters) {
        $query->when($filters['category'] ?? null, fn($q, $v) => $q->where('category', $v));
        $query->when($filters['level'] ?? null, fn($q, $v) => $q->where('level', $v));
        $query->when($filters['search'] ?? null, fn($q, $v) => $q->search($v));
        return $query;
    }
}
