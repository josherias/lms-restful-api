<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;



class CategoryEnrollmentController extends ApiController
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
    public function index(Category $category)
    {

        $this->allowedAdminAction();
        $enrollments = $category->courses()
            ->whereHas('enrollments')
            ->with('enrollments')
            ->get()
            ->pluck('enrollments')
            ->collapse();



        return $this->showAll($enrollments);
    }
}
