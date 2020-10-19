<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Topic;
use Illuminate\Http\Request;

class TopicCourseController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']); //accessed publicallly by all clients
    }

    /**
     * Display a listing of the courses for a topic.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Topic $topic)
    {
        $courses = $topic->courses;
        return $this->showAll($courses);
    }
}
