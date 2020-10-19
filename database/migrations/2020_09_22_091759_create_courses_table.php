<?php

use App\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description', 1000); //string length 1000
            $table->string('image');
            $table->string('status')->default(Course::UNPUBLISHED_COURSE);
            $table->integer('instructor_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            //seting foreign key to refernce id of instructor on users table id
            $table->foreign('instructor_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
