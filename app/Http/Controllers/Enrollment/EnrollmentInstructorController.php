<?php

namespace App\Http\Controllers\Enrollment;

use App\Enrollment;
use App\Http\Controllers\ApiController;


class EnrollmentInstructorController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed
        $this->middleware('scope:read-general')->only('index');

        //policies
        $this->middleware('can:view,enrollment')->only('index');
    }

    /**
     * Display the instructor of an enrollment.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Enrollment $enrollment)
    {
        $instructor = $enrollment->course->instructor;

        return $this->showOne($instructor);
    }
}
