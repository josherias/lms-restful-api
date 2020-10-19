<?php

namespace App\Http\Controllers\Course;

use App\Course;
use App\Http\Controllers\ApiController;
use App\Topic;


class CourseTopicController extends ApiController
{


    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']); //accessed publicallly by all clients

        $this->middleware('auth:api')->except(['index']); //accessed publicallly by only with user credentials

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
        $topics = $course->topics;

        return $this->showAll($topics);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Course $course, Topic $topic)
    {
        $course->topics()->syncWithoutDetaching([$topic->id]);

        return $this->showAll($course->topics);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course, Topic $topic)
    {
        if (!$course->topics()->find($topic->id)) {
            return $this->errorResponse("The specified topic is not a topic of this course", 404);
        }

        $course->topics()->detach($topic->id);

        return $this->showAll($course->topics);
    }
}
