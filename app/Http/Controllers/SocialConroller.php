<?php

namespace App\Http\Controllers;

use App\Model\HotToday;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail,Auth,DB,Session;


class SocialConroller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function redirectToGoogle(Request $request)
    {

    }

    public function redirectToFacebook(Request $request)
    {

    }

}
