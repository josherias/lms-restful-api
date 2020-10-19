<?php

namespace App;

use App\Transformers\EnrollmentTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{

    use SoftDeletes;


    public $transformer = EnrollmentTransformer::class;

    protected $dates = ['deleted_at'];
    protected  $fillable = ['student_id', 'course_id'];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    /* relationship */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
