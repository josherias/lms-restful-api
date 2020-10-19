<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Instructor;
use Illuminate\Http\Request;

class InstructorStudentController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Instructor $instructor)
    {


        $this->allowedAdminAction();

        $students = $instructor->courses()
            ->whereHas('enrollments')
            ->with('enrollments.student')
            ->get()
            ->pluck('enrollments')
            ->collapse()
            ->pluck('student')
            ->unique('id')
            ->values();


        return  $this->showAll($students);
    }
}
