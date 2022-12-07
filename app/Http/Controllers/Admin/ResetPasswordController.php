<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\PasswordReset;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail;

class ResetPasswordController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $messages = [
                'email' => "Please enter Email",
            ];
            $rules = [
                'email' => 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $email      =   $request['email'];
                $user_data  =   User::where("email", $email)->first();
                if (!empty($user_data)) {
                    $otp    =   rand(111111, 999999);
                    $token  =   base64_encode($email.$otp);
                    $password_reset     =   PasswordReset::where("email", $email)->first();
                    if (empty($password_reset)) {
                        $password_reset             =   new PasswordReset();
                    }
                    $password_reset->email      =   $email;
                    $password_reset->token      =   $token;
                    $password_reset->user_id      =   $user_data['id'];
                    $password_reset->save();
                    $reset_pass_link    =   url("/admin/update-password")."/".$token;
                    $data = [
                'no-reply' => 'no-reply@pinkpill.com',
                'Email'    => $email,
                ];
                    Mail::send(
                        'emails.forget-password',
                        ['link'=>$reset_pass_link],
                        function ($message) use ($data) {
                        $message
                            ->from($data['no-reply'])
                            ->to($data['Email'], 'pinkpill')->subject('Forget Password (pinkpill)');
                    }
                    );
                    return redirect()->back()->with('success', "Please check email for password reset link !");

                } else {
                    return redirect()->back()->with('error', "Email is Not Registered !");
                }
            }
        } else {
            return view('admin.pages.reset_password');
        }
    }
    
    public function reset_password($slug, Request $request)
    {
        if ($request->isMethod('post')) {
            $messages = [
                        'same' => "Password and Confirm password didn't match.",
                    ];
            $validator = Validator::make($request->all(), [
                        'password'          =>  'required|min:6|max:55',
                        'confirmpassword'   =>  'required|same:password',
                    ], $messages);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $reset_data         =   PasswordReset::where("token", $slug)->first();
                if (!empty($reset_data)) {
                    $email      =   $reset_data->email;
                    $to_time    =   strtotime($reset_data->updated_at);
                    $from_time  =   strtotime(date("Y-m-d H:i:s"));
                    $time_diff  =   round(abs($from_time - $to_time) / 60, 2);
                    if ($time_diff <= 15) {
                        $password       =   $request['password'];
                        $update_data    =   [ "password"  =>  bcrypt($password) ];
                        User::where("id", $reset_data->user_id)->update($update_data);
                        PasswordReset::where("token", $slug)->delete();
                        return redirect()->to("/admin");
                    } else {
                        return redirect()->route('reset-password');
                    }
                } else {
                    return redirect()->back();
                }
            }
        } else {
            $data['slug']=$slug;
            return view('admin.pages.update_password', $data);
        }
    }
}
