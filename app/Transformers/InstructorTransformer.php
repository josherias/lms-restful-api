<?php

namespace App\Transformers;

use App\Instructor;
use League\Fractal\TransformerAbstract;

class InstructorTransformer extends TransformerAbstract
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
    public function transform(Instructor $instructor)
    {
        return [

            'identifier' => (int)$instructor->id,
            'name' => (string)$instructor->name,
            'email' => (string) $instructor->email,
            'isVerified' => (int)$instructor->verified,
            'creationDate' => (string)$instructor->created_at,
            'lastChange' => (string)$instructor->updated_at,
            'deletedDate' => isset($instructor->deleted_at) ? (string)$instructor->deleted_at : null,


            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('instructors.show', $instructor->id),
                ],
                [
                    'rel' => 'instructors.enrollments',
                    'href' => route('instructors.enrollments.index', $instructor->id),
                ],
                [
                    'rel' => 'instructors.students',
                    'href' => route('instructors.students.index', $instructor->id),
                ],
                [
                    'rel' => 'instructors.courses',
                    'href' => route('instructors.courses.index', $instructor->id),
                ],

            ]
        ];
    }


    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'name' => 'name',
            'email' => 'email',
            'isVerified' => 'verified',
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
            'name' => 'name',
            'email' => 'email',
            'verified' => 'isVerified',
            'created_at'  => 'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at' => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
