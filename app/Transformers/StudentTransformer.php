<?php

namespace App\Transformers;

use App\Student;
use League\Fractal\TransformerAbstract;

class StudentTransformer extends TransformerAbstract
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
    public function transform(Student $student)
    {
        return [
            'identifier' => (int)$student->id,
            'name' => (string)$student->name,
            'email' => (string) $student->email,
            'isVerified' => (int)$student->verified,
            'creationDate' => (string)$student->created_at,
            'lastChange' => (string)$student->updated_at,
            'deletedDate' => isset($student->deleted_at) ? (string)$student->deleted_at : null,


            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('students.show', $student->id),
                ],
                [
                    'rel' => 'students.enrollments',
                    'href' => route('students.enrollments.index', $student->id),
                ],
                [
                    'rel' => 'students.instructors',
                    'href' => route('students.instructors.index', $student->id),
                ],
                [
                    'rel' => 'students.courses',
                    'href' => route('students.courses.index', $student->id),
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
