<?php

namespace App\Http\Controllers\Course;

use App\Course;
use App\Http\Controllers\ApiController;



class CourseEnrollmentController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed
    }

    /**
     * Display a listing of the enrollments of a course.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {

        $this->allowedAdminAction();

        $enrollments = $course->enrollments;
        return $this->showAll($enrollments);
    }
}
