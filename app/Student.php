<?php

namespace App;

use App\Scopes\StudentScope;
use App\Transformers\StudentTransformer;

class Student extends User
{


    public $transformer = StudentTransformer::class;


    //this will run evrytime an instance of the student is created
    protected static function boot()
    {
        parent::boot();

        //add the scope wen querying db for students
        static::addGlobalScope(new StudentScope);
    }

    /* relationships */

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
