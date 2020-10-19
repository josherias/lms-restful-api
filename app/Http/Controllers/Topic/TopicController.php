<?php

namespace App\Http\Controllers\Topic;

use App\Http\Controllers\ApiController;
use App\Topic;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TopicController extends ApiController
{

    public function __construct()
    {

        $this->middleware('client.credentials')->only(['index', 'show']); //accessed publicallly by all clients

        $this->middleware('auth:api')->except(['index', 'show']); //accessed only with user credentiasl


        $this->middleware('transform.input:' . TopicTransformer::class)->only(['store', 'update']);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::all();

        return $this->showAll($topics);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'video' => 'required|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime',
            'notes' => 'required|file',

        ];

        $this->validate($request, $rules);

        $topicData = $request->all();

        $topicData['video'] = $request->video->store('');
        $topicData['notes'] = $request->notes->store('');

        $topic = Topic::create($topicData);

        return $this->showOne($topic, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        return $this->showOne($topic);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {

        $rules = [
            'video' => 'mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime',
            'notes' => 'file',

        ];

        $this->validate($request, $rules);

        $topic->fill($request->only([
            'name',
            'description',
            'video',
            'notes'
        ]));

        if ($request->hasFile('video')) {
            Storage::delete($topic->video);

            $topic->video = $request->video->store('');
        }

        if ($request->hasFile('notes')) {
            Storage::delete($topic->notes);

            $topic->notes = $request->notes->store('');
        }

        if ($topic->isClean()) {
            return $this->errorResponse("You need to specify a value to update", 422);
        }

        $topic->save();

        return $this->showOne($topic);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        $topic->delete();

        Storage::delete($topic->video);
        Storage::delete($topic->notes);

        return $this->showOne($topic);
    }
}
