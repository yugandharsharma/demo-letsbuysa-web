<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use App\User;

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

    use AuthenticatesUsers {
    logout as performLogout;
    }

    public function logout(Request $request)
    {    
        $userrole= Auth::user()->role;
        if($userrole==1 || $userrole==3)
        {
        $this->performLogout($request);
        return redirect('/admin');
        }
        else 
        {
        $this->performLogout($request);
        return redirect('/');
        }
       
    }

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

    public function redirectToGoogle()
    {
    return Socialite::driver('google')->redirect();
    }

    public function callbackToGoogle()
    {
    $user =Socialite::driver('google')->stateless()->user();
    $this->_registerOrLoginUser($user);
    return redirect('/');

    }

    public function redirectToFacebook()
    {
    return Socialite::driver('facebook')->redirect();
    }

    public function callbackToFacebook()
    {
    $user =Socialite::driver('facebook')->stateless()->user();
    $this->_registerOrLoginUser($user);
    return redirect('/');
    }

    protected function _registerOrLoginUser($data)
    {
    $user =User::where('email','=',$data->email)->first();
    if(!$user)
    {
    $user =new User();
    $user->name= $data->name;
    $user->email= $data->email;
    $user->provider_id= $data->id;
    $user->avtar= $data->avatar;
    $user->is_social= 1;
    $user->role= 2;
    $user->save();
    }
    Auth::login($user);
    }


}
