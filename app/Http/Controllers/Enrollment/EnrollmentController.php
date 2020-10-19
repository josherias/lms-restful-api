<?php

namespace App\Http\Controllers\Enrollment;

use App\Enrollment;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class EnrollmentController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        //calling the parent constructor with the api guard to protect all other routes that need the users credentials to be accessed
        $this->middleware('scope:read-general')->only('show');

        //policies
        $this->middleware('can:view,enrollment')->only('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->allowedAdminAction();

        $enrollments =  Enrollment::all();
        return  $this->showAll($enrollments);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Enrollment $enrollment)
    {
        return $this->showOne($enrollment);
    }
}
