<?php

namespace App;

use Illuminate\Foundation\Auth\User;

class Teacher extends User
{
    protected $fillable = ['userName', 'password', 'fullName', 'email'];
    protected $guard = "teacher";

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
        return $this->hasMany(Period::class, 'teacherId');
    }
}
