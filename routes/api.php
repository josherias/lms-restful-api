<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/* users */

Route::name('me')->get('users/me', 'User\UserController@me');
Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);
Route::name('verify')->get('users/verify/{token}', 'User\UserController@verify');
Route::name('resend')->get('users/{user}/resend', 'User\UserController@resend');




/* students */
Route::resource('students', 'Student\StudentController', ['only' => ['index', 'show']]);
Route::resource('students.courses', 'Student\StudentCourseController', ['only' => ['index']]);
Route::resource('students.instructors', 'Student\StudentInstructorController', ['only' => ['index']]);
Route::resource('students.enrollments', 'Student\StudentEnrollmentController', ['only' => ['index']]);


/* instructors */
Route::resource('instructors', 'Instructor\InstructorController', ['only' => ['index', 'show']]);
Route::resource('instructors.enrollments', 'Instructor\InstructorEnrollmentController', ['only' => ['index']]);
Route::resource('instructors.students', 'Instructor\InstructorStudentController', ['only' => ['index']]);
Route::resource('instructors.courses', 'Instructor\InstructorCourseController', ['except' => ['create', 'edit', 'show']]);

/* enrollments */
Route::resource('enrollments', 'Enrollment\EnrollmentController', ['only' => ['index', 'show']]);
Route::resource('enrollments.categories', 'Enrollment\EnrollmentCategoryController', ['only' => ['index']]);
Route::resource('enrollments.topics', 'Enrollment\EnrollmentTopicController', ['only' => ['index']]);
Route::resource('enrollments.instructors', 'Enrollment\EnrollmentInstructorController', ['only' => ['index']]);

/* courses */
Route::resource('courses', 'Course\CourseController', ['only' => ['index', 'show']]);
Route::resource('courses.enrollments', 'Course\CourseEnrollmentController', ['only' => ['index']]);
Route::resource('courses.students', 'Course\CourseStudentController', ['only' => ['index']]);
Route::resource('courses.categories', 'Course\CourseCategoryController', ['except' => ['create', 'edit', 'show', 'store']]);
Route::resource('courses.topics', 'Course\CourseTopicController', ['except' => ['create', 'edit', 'show', 'store']]);

Route::resource('courses.students.enrollments', 'Course\CourseStudentEnrollmentController', ['only' => ['store']]);

/* topics */
Route::resource('topics', 'Topic\TopicController', ['except' => ['create', 'edit']]);
Route::resource('topics.courses', 'Topic\TopicCourseController', ['only' => ['index']]);

/* categories */
Route::resource('categories', 'Category\CategoryController', ['except' => ['create', 'edit']]);
Route::resource('categories.courses', 'Category\CategoryCourseController', ['only' => ['index']]);
Route::resource('categories.enrollments', 'Category\CategoryEnrollmentController', ['only' => ['index']]);


//passport
//make the route for creating toknes use the api middleware that consists all the throttle, signature and bindings but not only the not hte throttle middleware as t was initially
Route::post('oauth.token', ' Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
