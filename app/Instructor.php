<?php

namespace App;

use App\Scopes\InstructorScope;
use App\Transformers\InstructorTransformer;

class Instructor extends User
{


    public $transformer = InstructorTransformer::class;

    //this will run evrytime an instance of the instructor is created
    protected static function boot()
    {
        parent::boot();

        //add the scope wen querying db for instructors
        static::addGlobalScope(new InstructorScope);
    }

    /* relationships */

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
