<?php

namespace App\Transformers;

use App\Course;
use League\Fractal\TransformerAbstract;

class CourseTransformer extends TransformerAbstract
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
    public function transform(Course $course)
    {
        return [
            'identifier' => (int)$course->id,
            'title' => (string)$course->name,
            'details' => (string) $course->description,
            'picture' => url("files/{$course->image}"),
            'isPublished' => (string) $course->status,
            'instructor' => (int) $course->instructor_id,
            'creationDate' => (string)$course->created_at,
            'lastChange' => (string)$course->updated_at,
            'deletedDate' => isset($course->deleted_at) ? (string)$course->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('courses.show', $course->id)
                ],
                [
                    'rel' => 'courses.enrollments',
                    'href' => route('courses.enrollments.index', $course->id)
                ],
                [
                    'rel' => 'courses.students',
                    'href' => route('courses.students.index', $course->id)
                ],
                [
                    'rel' => 'courses.categories',
                    'href' => route('courses.categories.index', $course->id)
                ],
                [
                    'rel' => 'courses.topics',
                    'href' => route('courses.topics.index', $course->id)
                ],
                [
                    'rel' => 'instructor',
                    'href' => route('instructors.show', $course->instructor_id)
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
            'picture' => 'image',
            'isPublished' => 'status',
            'instructor' => 'instructor_id',
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
            'image' => 'picture',
            'status' => 'isPublished',
            'instructor_id' => 'instructor',
            'created_at'  => 'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at' => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
