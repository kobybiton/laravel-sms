<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function studentLogin(Request $request)
    {
        $this->validate($request, [
            'userName'   => 'bail|required|min:3|max:50',
            'password' => 'bail|required|min:6|max:50'
        ]);

        if (Auth::guard('student')->attempt(['userName' => $request->userName, 'password' => $request->password])) {
            return 'student is logged in!';
        }
    }

        public function teacherLogin(Request $request)
        {
            $this->validate($request, [
                'userName'   => 'bail|required|min:3|max:50',
                'password' => 'bail|required|min:6|max:50'
            ]);

            if (Auth::guard('teacher')->attempt(['userName' => $request->userName, 'password' => $request->password])) {
                return 'teacher is logged in!';
            }
        }
}
