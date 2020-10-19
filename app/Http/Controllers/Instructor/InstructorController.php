<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\ApiController;
use App\Instructor;


class InstructorController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed
        $this->middleware('scope:read-general')->only('show');
        $this->middleware('can:view,seller')->only('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->allowedAdminAction();

        $instructors = Instructor::has('courses')->get(); //instructors should have courses
        return $this->showAll($instructors);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Instructor $instructor)
    {
        return $this->showOne($instructor);
    }
}
