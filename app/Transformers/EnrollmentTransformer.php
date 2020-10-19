<?php

namespace App\Transformers;

use App\Enrollment;
use League\Fractal\TransformerAbstract;

class EnrollmentTransformer extends TransformerAbstract
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
    public function transform(Enrollment $enrollment)
    {
        return [
            'identifier' => (int)$enrollment->id,
            'course' => (int)$enrollment->course_id,
            'student' => (int) $enrollment->student_id,
            'creationDate' => (string)$enrollment->created_at,
            'lastChange' => (string)$enrollment->updated_at,
            'deletedDate' => isset($enrollment->deleted_at) ? (string)$enrollment->deleted_at : null,


            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('enrollments.show', $enrollment->id),
                ],
                [
                    'rel' => 'enrollments.categories',
                    'href' => route('enrollments.categories.index', $enrollment->id),
                ],
                [
                    'rel' => 'enrollments.instructors',
                    'href' => route('enrollments.instructors.index', $enrollment->id),
                ],
                [
                    'rel' => 'enrollments.topics',
                    'href' => route('enrollments.topics.index', $enrollment->id),
                ],
                [
                    'rel' => 'course',
                    'href' => route('courses.show', $enrollment->course_id),
                ],
                [
                    'rel' => 'student',
                    'href' => route('students.show', $enrollment->student_id),
                ],
            ]
        ];
    }


    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'course' => 'course_id',
            'student' => 'student_id',
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
            'course_id' => 'course',
            'student_id' => 'student',
            'created_at'  => 'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at' => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
