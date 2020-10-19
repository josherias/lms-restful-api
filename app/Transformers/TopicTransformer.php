<?php

namespace App\Transformers;

use App\Topic;
use League\Fractal\TransformerAbstract;

class TopicTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Topic $topic)
    {
        return [
            'identifier' => (int)$topic->id,
            'title' => (string)$topic->name,
            'details' => (string) $topic->description,
            'videoFile' => url("files/{$topic->video}"),
            'notesFile' => url("files/{$topic->notes}"),
            'creationDate' => (string)$topic->created_at,
            'lastChange' => (string)$topic->updated_at,
            'deletedDate' => isset($topic->deleted_at) ? (string)$topic->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('topics.show', $topic->id)
                ],
                [
                    'rel' => 'topics.courses',
                    'href' => route('topics.courses.index', $topic->id)
                ],

            ]
        ];
    }




    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
            'videoFile' => 'video',
            'notesFile' => 'notes',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'  => 'identifier',
            'name' => 'title',
            'description' => 'details',
            'video' => 'videoFile',
            'notes' => 'notesFile',
            'created_at'  => 'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at' => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
