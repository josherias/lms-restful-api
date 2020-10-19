<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\ApiController;
use App\Student;

class StudentInstructorController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed
    }

    /**
     * Display a listing of instructors of a specifc student.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Student $student)
    {

        $this->allowedAdminAction();

        $instructors = $student->enrollments()->with('course.instructor')
            ->get()
            ->pluck('course.instructor')
            ->unique('id')
            ->values();

        return $this->showAll($instructors);
    }
}
