<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\ApiController;
use App\Student;


class StudentCourseController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed
        $this->middleware('scope:read-general')->only('index');

        //using the student policy
        $this->middleware('can:view,student')->only('show');
    }

    /**
     * Display a listing of courses of a specific student.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Student $student)
    {
        $courses = $student->enrollments()->with('course')
            ->get()
            ->pluck('course');

        return $this->showAll($courses);
    }
}
