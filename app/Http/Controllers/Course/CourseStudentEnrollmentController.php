<?php

namespace App\Http\Controllers\Course;

use App\Course;
use App\Enrollment;
use App\Http\Controllers\ApiController;
use App\Student;
use App\Transformers\EnrollmentTransformer;
use Illuminate\Http\Request;

class CourseStudentEnrollmentController extends ApiController
{


    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . EnrollmentTransformer::class)->only(['store']);
        $this->middleware('scope:enroll-course')->only(['store']);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Course $course, Student $student)
    {
        if ($student->id == $course->instructor_id) {
            return $this->errorResponse("The student should be different from the instructor of the course", 409);
        }

        if (!$student->isVerified()) {
            return $this->errorResponse("The student should be a verified use before enrolling in course", 409);
        }

        if (!$course->instructor->isVerified()) {
            return $this->errorResponse("Only courses from verified instructors can be enrolled in", 409);
        }

        if (!$course->isPublished()) {
            return $this->errorResponse("The course is not yet published", 409);
        }

        //////
        /*   if ($student->enrollments()->where('course_id', $course->id)->get()) {
            return $this->errorResponse("Student is already enrolled in the course", 409);
        } */

        ////

        //create the enrollment
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);


        return $this->showOne($enrollment, 201);
    }
}
