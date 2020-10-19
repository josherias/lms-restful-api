<?php

namespace App\Http\Controllers\Enrollment;

use App\Enrollment;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnrollmentTopicController extends ApiController
{


    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']); //accessed publicallly by all clients


    }

    /**
     * Display a listing of the topics in an enrollment
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Enrollment $enrollment)
    {
        $topics = $enrollment->course->topics;

        return $this->showAll($topics);
    }
}
