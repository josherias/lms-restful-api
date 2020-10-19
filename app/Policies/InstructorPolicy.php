<?php

namespace App\Policies;

use App\Instructor;
use App\Traits\AdminActions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InstructorPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Instructor  $instructor
     * @return mixed
     */
    public function view(User $user, Instructor $instructor)
    {
        return $user->id === $instructor->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, User $instructor)
    {
        return $user->id === $instructor->id;
    }

    /**
     * Determine whether the user can edit a course.
     *
     * @param  \App\User  $user
     * @param  \App\Instructor  $instructor
     * @return mixed
     */
    public function editCourse(User $user, Instructor $instructor)
    {
        return $user->id === $instructor->id;
    }

    /**
     * Determine whether the user can delete a course.
     *
     * @param  \App\User  $user
     * @param  \App\Instructor  $instructor
     * @return mixed
     */
    public function deleteCourse(User $user, Instructor $instructor)
    {
        return $user->id === $instructor->id;
    }
}
