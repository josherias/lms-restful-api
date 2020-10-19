<?php

namespace App\Policies;

use App\Enrollment;
use App\Traits\AdminActions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnrollmentPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the enrolment.
     *
     * @param  \App\User  $user
     * @param  \App\Enrollment  $enrollment
     * @return mixed
     */
    public function view(User $user, Enrollment $enrollment)
    {
        return $user->id === $enrollment->student->id || $user->id === $enrollment->course->instructor->id;
    }
}
