<?php
namespace App\Providers;


use App\Interfaces\CourseRepositoryInterface;
use App\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\CourseRepository;
use App\Repositories\EnrollmentRepository;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(EnrollmentRepositoryInterface::class, EnrollmentRepository::class);
    }
    public function boot(): void {}
}
