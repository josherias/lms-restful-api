<?php

namespace App\Http\Controllers\Course;

use App\Course;
use App\Http\Controllers\ApiController;


class CourseController extends ApiController
{


    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']); //accessed publicallly by all clients
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::all();

        return $this->showAll($courses);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return $this->showOne($course);
    }
}
