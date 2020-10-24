<?php

use App\Category;
use App\Course;
use App\Enrollment;
use App\Topic;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // $this->call(UserSeeder::class);

        //DB::statement('SET FOREIGN_KEY_CHECKS = 0'); //disable forign key checks temporaliry fr truncateing the tables

        //first truncate all the tables before seedingthe data in db
        User::truncate();
        Category::truncate();
        Course::truncate();
        Topic::truncate();
        Enrollment::truncate();

        //use the db facade to truncate the pivot tables since they dont have models
        DB::table('category_course')->truncate();
        DB::table('course_topic')->truncate();

        ///prevent listening to events when running our db seed command
        User::flushEventListeners();
        Category::flushEventListeners();
        Course::flushEventListeners();
        Topic::flushEventListeners();
        Enrollment::flushEventListeners();


        //setting quntity for the entities to put in database
        $usersQuantity = 1000;
        $categoriesQuantity = 30;
        $topicsQuantity = 100;
        $coursesQuantity = 100;
        $enrollmentsQuantity = 200;

        factory(User::class, $usersQuantity)->create();

        factory(Category::class, $categoriesQuantity)->create();

        factory(Topic::class, $topicsQuantity)->create();

        //create courses and attach relationships to each of them
        factory(Course::class, $coursesQuantity)->create()->each(
            function ($course) {
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $topics = Topic::all()->random(mt_rand(1, 5))->pluck('id');

                $course->categories()->attach($categories); //attching categories
                $course->topics()->attach($topics); //attaching topics
            }
        );

        factory(Enrollment::class, $enrollmentsQuantity)->create();
    }
}
