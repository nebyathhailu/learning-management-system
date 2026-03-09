<?php
namespace Database\Seeders;


use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder {
    public function run(): void {
        $admin = User::create(['name'=>'Admin','email'=>'admin@lms.com','password'=>Hash::make('password'),'role'=>'admin']);
        $instructor = User::create(['name'=>'John Instructor','email'=>'instructor@lms.com','password'=>Hash::make('password'),'role'=>'instructor']);
        $student = User::create(['name'=>'Jane Student','email'=>'student@lms.com','password'=>Hash::make('password'),'role'=>'student']);


        $course = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Introduction to Laravel',
            'slug' => 'introduction-to-laravel',
            'description' => 'Learn the fundamentals of Laravel framework',
            'status' => 'published',
            'category' => 'Web Development',
            'level' => 'beginner',
        ]);


        $module = Module::create(['course_id'=>$course->id,'title'=>'Getting Started','sort_order'=>1]);
        Lesson::create(['module_id'=>$module->id,'title'=>'What is Laravel?','content'=>'Laravel is a PHP framework...','sort_order'=>1,'is_free_preview'=>true]);
        Lesson::create(['module_id'=>$module->id,'title'=>'Installation','content'=>'Install via composer...','sort_order'=>2]);
    }
}
