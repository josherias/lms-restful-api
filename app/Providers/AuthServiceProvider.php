<?php

namespace App\Providers;

use App\Course;
use App\Enrollment;
use App\Instructor;
use App\Policies\CoursePolicy;
use App\Policies\EnrollmentPolicy;
use App\Policies\InstructorPolicy;
use App\Policies\StudentPolicy;
use App\Policies\UserPolicy;
use App\Student;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Student::class => StudentPolicy::class,
        Instructor::class => InstructorPolicy::class,
        User::class => UserPolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
        Course::class => CoursePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-action', function ($user) {
            return $user->isAdmin();
        });


        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::enableImplicitGrant();

        //registering scopes
        Passport::tokensCan([
            'enroll-course' => 'Enroll into a particular course',
            'manage-courses' => 'Create, read, update and delete courses (CRUD)',
            'manage-account' => 'Read account data, id, name, email, if verifed, if admin (cannot read password). Modify your account data. cannot delete your account',
            'read-general' => 'read general information like enrolled categories, enrolled courses, your enrollments. etc'
        ]);
    }
}
