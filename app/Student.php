<?php

namespace App;

use Illuminate\Foundation\Auth\User;

class Student extends User
{
    protected $fillable = ['userName', 'password', 'fullName', 'grade'];
    protected $guard = 'student';

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'userName';
    }

    public function periods()
    {
        return $this->belongsToMany(Period::class, 'schedules', 'studentId', 'periodId');
    }
}
