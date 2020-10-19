<?php

namespace App\Http\Controllers\Course;

use App\Category;
use App\Course;
use App\Http\Controllers\ApiController;


class CourseCategoryController extends ApiController
{


    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']); //accessed publicallly by all clients

        $this->middleware('auth:api')->except(['index']); //accessed using user credentials

        $this->middleware('scope:manage-courses')->except('index');


        //policies
        $this->middleware('can:add,course')->only('update');

        $this->middleware('can:delete,course')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Course $course)
    {
        $categories = $course->categories;

        return $this->showAll($categories);
    }

    /**
     * Update the course by adding a category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Course $course, Category $category)
    {
        $course->categories()->syncWithoutDetaching([$category->id]); //attach a category without detaching

        return $this->showAll($course->categories);
    }

    /**
     * Remove the specified category from the course.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course, Category $category)
    {
        if (!$course->categories()->find($category->id)) {
            return $this->errorResponse("The specified category is not a category of this course", 404);
        }

        $course->categories()->detach($category);

        return $this->showAll($course->categories);
    }
}
