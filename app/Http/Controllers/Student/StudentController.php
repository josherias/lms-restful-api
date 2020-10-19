<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\ApiController;
use App\Student;


class StudentController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed

        $this->middleware('scope:read-general')->only('show');

        //using the student policy
        $this->middleware('can:view,student')->only('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->allowedAdminAction();

        $students = Student::has('enrollments')->get(); //obtain only users that have enroled in courses
        return $this->showAll($students);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        /* $student = Student::has('enrollments')->findOrFail($id); */
        return $this->showOne($student);
    }
}
