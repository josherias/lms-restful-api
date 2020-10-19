<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;



class CategoryCourseController extends ApiController
{


    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']); //accessed publicallly by all clients
    }
    /**
     * Display a listing of the courses of a category.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $courses = $category->courses;

        return $this->showAll($courses);
    }
}
