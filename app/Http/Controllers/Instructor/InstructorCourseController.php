<?php

namespace App\Http\Controllers\Instructor;

use App\Course;
use App\Http\Controllers\ApiController;
use App\Instructor;
use App\Transformers\CourseTransformer;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InstructorCourseController extends ApiController
{


    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . CourseTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-courses')->except('index');

        //instructor policy
        $this->middleware('can:view,seller')->only('index');
        $this->middleware('can:create,seller')->only('store');
        $this->middleware('can:edit-product,seller')->only('update');
        $this->middleware('can:delete-product,seller')->only('destroy');
    }


    /**
     * Display a listing of the courses of an instructor.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Instructor $instructor)
    {

        if (request()->user()->tokenCan('read-general') || request()->user()->tokenCan('manage-courses')) {

            $courses = $instructor->courses;

            return  $this->showAll($courses);
        }

        throw new AuthorizationException('Invalid scope(s)');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $instructor)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image'
        ];

        $this->validate($request, $rules);

        $courseData = $request->all();

        $courseData['status'] = Course::UNPUBLISHED_COURSE;
        $courseData['image'] =  $request->image->store('');
        $courseData['instructor_id'] = $instructor->id;

        $course = Course::create($courseData);

        return  $this->showOne($course, 201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instructor $instructor, Course $course)
    {
        $rules = [
            'status' => 'in:' . Course::PUBLISHED_COURSE . ',' . Course::UNPUBLISHED_COURSE,
            'image' => 'image'
        ];

        $this->validate($request, $rules);


        $this->checkInstructor($instructor, $course); //check if the true instructor is indeed the one updating the course

        $course->fill($request->only('name', 'description', 'status'));

        if ($request->has('status')) {
            $course->staus = $request->status;

            if ($course->isPublished() && $course->categories()->count() == 0) {
                return $this->errorResponse('A course should atleast have a category before being published', 409);
            }
        }

        if ($request->hasFile('image')) {

            Storage::delete($course->image);

            $course->image = $request->image->store('');
        }

        if ($course->isClean()) {
            return $this->errorResponse('You should specify a differnt value to update', 422);
        }

        $course->save();

        return $this->showOne($course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instructor $instructor, Course $course)
    {
        $this->checkInstructor($instructor, $course);


        $course->delete();
        Storage::delete($course->image);

        return $this->showOne($course);
    }

    private function checkInstructor(Instructor $instructor, Course $course)
    {
        if ($instructor->id != $course->instructor_id) {
            throw new HttpException(422, 'The specified instructor is not the actual instructor of the course');
        }
    }
}
