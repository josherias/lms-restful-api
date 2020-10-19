<?php

namespace App;

use App\Transformers\TopicTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{

    use SoftDeletes;


    public $transformer = TopicTransformer::class;

    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'description', 'video', 'notes'];

    protected $hidden = [
        'pivot'
    ];



    /* relationships */
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
