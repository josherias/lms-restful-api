<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\ApiController;
use App\Instructor;


class InstructorEnrollmentController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed
        $this->middleware('scope:read-general')->only('index');
        $this->middleware('can:view,seller')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Instructor $instructor)
    {
        $enrollments = $instructor->courses()
            ->whereHas('enrollments')
            ->with('enrollments')
            ->get()
            ->pluck('enrollments')
            ->collapse();
        return  $this->showAll($enrollments);
    }
}
