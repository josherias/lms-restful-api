<?php

namespace App\Http\Controllers\Enrollment;

use App\Enrollment;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class EnrollmentCategoryController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']); //accessed publicallly by all clients
    }
    /**
     * Display a listing of the categories of an enrollment.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Enrollment $enrollment)
    {
        $categories = $enrollment->course->categories;

        return $this->showAll($categories);
    }
}
