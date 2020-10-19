<?php

namespace App\Http\Controllers\Course;

use App\Course;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseStudentController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed

        //using the student policy
        $this->middleware('can:enroll,student')->only('store');
    }

    /**
     * Display a listing of students enrolled in a course.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {

        $this->allowedAdminAction();

        $students = $course->enrollments()
            ->with('student')
            ->get()
            ->pluck('student');

        return $this->showAll($students);
    }
}
