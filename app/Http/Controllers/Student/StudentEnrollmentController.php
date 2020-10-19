<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\ApiController;
use App\Student;


class StudentEnrollmentController extends ApiController
{


    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed
        $this->middleware('scope:read-general')->only('index');

        //using the student policy
        $this->middleware('can:view,student')->only('index');
    }

    /**
     * Display a listing of the enrollments of a student.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Student $student)
    {
        $enrollments = $student->enrollments;

        return $this->showAll($enrollments);
    }
}
