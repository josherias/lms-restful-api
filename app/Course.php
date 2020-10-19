<?php

namespace App;

use App\Transformers\CourseTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{

    use SoftDeletes;
    const PUBLISHED_COURSE = 'available';
    const UNPUBLISHED_COURSE = 'unavailable';


    public $transformer = CourseTransformer::class;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'description', 'status', 'instructor_id', 'image'];

    protected $hidden = [
        'pivot'
    ];


    /* 
    isPublished
    returns bool true or false
    */
    public function isPublished()
    {
        return $this->status == Course::PUBLISHED_COURSE;
    }


    /* relationships */

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class);
    }


    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
