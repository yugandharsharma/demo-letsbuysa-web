<?php

namespace App\Http\Controllers;

use App\Model\Emailtemplate;
use App\Model\PasswordReset;
use App\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Session;
use Toastr;
use Validator;

class UserController extends Controller
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
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|max:50',
            'password' => 'required|min:6|max:20',
        ];

        $messages = [
            'email.required' => 'Please Enter Email OR Mobile Number',
            'email.max' => 'Email Or Mobile Number Must be Less than 50 Characters',
            'password.required' => 'Please Enter Password',
            'password.min' => 'Password Must be Greater than 8 Characters',
            'password.max' => 'Password Must be Less than 20 Characters',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();
        $password = $request->password;

        if ($validator->fails()) {
            return response()->json(['email' => $errors->first('email'), 'password' => $errors->first('password')]);
        }

        if (!empty($request->email)) {

            $useremail = User::where('email', $request->email)->first();
            $usermobile = User::where('mobile', $request->email)->first();

            if (empty($useremail)) {
                if (empty($usermobile)) {
                    if ((\App::getLocale() == 'en')) {
                        return response()->json(['error' => 'Email Or Mobile is Not Registerd']);
                    } else {
                        return response()->json(['error' => 'البريد الإلكتروني أو الهاتف المحمول غير مسجل']);
                    }
                }
            }

        }

        if (!empty($useremail)) {
            if ($useremail->otp_verify == 0) {
                $mobile = '966' . $useremail->mobile;

                $user = "letsbuy";
                $password = "Nn0450292**";
                $mobilenumbers = $mobile;
                $otp = rand(1000, 9999);
                $message = 'Your Verification Code: ' . $otp;
                $senderid = "LetsBuy"; //Your senderid
                $message = urlencode($message);
                $url = "https://www.enjazsms.com/api/sendsms.php?username=" . $user . "&password=" . $password . "&message=" . $message . "&numbers=" . $mobilenumbers . "&sender=LetsBuy&unicode=E&return=null&port=1";
                // create a new cURL resource
                $ch = curl_init();
                // set URL and other appropriate options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // grab URL and pass it to the browser
                // close cURL resource, and free up system resources
                $curlresponse = curl_exec($ch);
                curl_close($ch);

                Session::put('user_id_login', $useremail->id);

                User::where('email', $request->email)->update(['otp' => $otp]);

                if ((\App::getLocale() == 'en')) {
                    return response()->json(['status' => 'Please Verify OTP Sent On Your Mobile Number', 'value' => '2']);
                } else {
                    return response()->json(['status' => 'يرجى التحقق من إرسال OTP على رقم هاتفك المحمول', 'value' => '2']);
                }
            }

            $check = Hash::check($password, $useremail->password);
            if (!empty($check)) {
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 2])) {
                    if ((\App::getLocale() == 'en')) {
                        return response()->json(['status' => 'Login Successfully', 'value' => '1']);
                    } else {
                        return response()->json(['status' => 'تم تسجيل الدخول بنجاح', 'value' => '1']);
                    }
                } else {
                    if ((\App::getLocale() == 'en')) {
                        return response()->json(['error' => 'Email Or Password Does Not Match']);
                    } else {
                        return response()->json(['error' => 'البريد الإلكتروني أو كلمة المرور غير متطابقتين']);
                    }
                }
            } else {
                if ((\App::getLocale() == 'en')) {
                    return response()->json(['error' => 'Email Or Password Does Not Match']);
                } else {
                    return response()->json(['error' => 'البريد الإلكتروني أو كلمة المرور غير متطابقتين']);
                }
            }

        }

        if (!empty($usermobile)) {
            if ($usermobile->otp_verify == 0) {
                $mobile = '966' . $usermobile->mobile;

                $user = "letsbuy";
                $password = "Nn0450292**";
                $mobilenumbers = $mobile;
                $otp = rand(1000, 9999);
                $message = 'Your Verification Code: ' . $otp;
                $senderid = "LetsBuy"; //Your senderid
                $message = urlencode($message);
                $url = "https://www.enjazsms.com/api/sendsms.php?username=" . $user . "&password=" . $password . "&message=" . $message . "&numbers=" . $mobilenumbers . "&sender=LetsBuy&unicode=E&return=null&port=1";
                // create a new cURL resource
                $ch = curl_init();
                // set URL and other appropriate options
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // grab URL and pass it to the browser
                // close cURL resource, and free up system resources
                $curlresponse = curl_exec($ch);
                curl_close($ch);

                Session::put('user_id_login', $usermobile->id);

                User::where('email', $usermobile->mobile)->update(['otp' => $otp]);

                if ((\App::getLocale() == 'en')) {
                    return response()->json(['status' => 'Please Verify OTP Sent On Your Mobile Number', 'value' => '2']);
                } else {
                    return response()->json(['status' => 'يرجى التحقق من إرسال OTP على رقم هاتفك المحمول', 'value' => '2']);
                }
            }

            $check = Hash::check($password, $usermobile->password);
            if (!empty($check)) {
                if (Auth::attempt(['mobile' => $request->email, 'password' => $request->password, 'role' => 2])) {
                    if ((\App::getLocale() == 'en')) {
                        return response()->json(['status' => 'Login Successfully', 'value' => '1']);
                    } else {
                        return response()->json(['status' => 'تم تسجيل الدخول بنجاح', 'value' => '1']);
                    }
                } else {
                    if ((\App::getLocale() == 'en')) {
                        return response()->json(['error' => 'Email Or Password Does Not Match']);
                    } else {
                        return response()->json(['error' => 'البريد الإلكتروني أو كلمة المرور غير متطابقتين']);
                    }
                }
            } else {
                if ((\App::getLocale() == 'en')) {
                    return response()->json(['error' => 'Email Or Password Does Not Match']);
                } else {
                    return response()->json(['error' => 'البريد الإلكتروني أو كلمة المرور غير متطابقتين']);
                }
            }
        }

    }

    public function register(Request $request)
    {

        if ($request->isMethod('post')) {
            $rules = [
                'fullname' => 'required',
                'createemail' => 'required|unique:users,email',
                'mobile' => 'required|unique:users,mobile',
                'createpassword' => 'required|min:6|max:20',
            ];

            $messages = [
                'fullname.required' => 'Please Enter Full Name',
                'createemail.required' => 'Please Enter Email',
                'createemail.unique' => 'This Email is Already Registered',
                'mobile.required' => 'Please Enter Mobile Number',
                'mobile.unique' => 'This Mobile Number is Already Registered',
                'createpassword.required' => 'Please Enter Password',
                'createpassword.min' => 'Password Must be Greater than 20 Characters',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            $errors = $validator->errors();

            if ($validator->fails()) {
                return response()->json(['fullname' => $errors->first('fullname'), 'createemail' => $errors->first('createemail'), 'mobile' => $errors->first('mobile'), 'createpassword' => $errors->first('createpassword')]);
            }
            $createuser = new User;
            $createuser->name = $request->fullname;
            $createuser->email = $request->createemail;
            $createuser->mobile = $request->mobile;
            $createuser->role = 2;
            $createuser->password = Hash::make($request->createpassword);

            $mobile = '966' . $request->mobile;

            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            $otp = rand(1000, 9999);
            $message = 'Your Verification Code: ' . $otp;
            $senderid = "LetsBuy"; //Your senderid
            $message = urlencode($message);
            $url = "https://www.enjazsms.com/api/sendsms.php?username=" . $user . "&password=" . $password . "&message=" . $message . "&numbers=" . $mobilenumbers . "&sender=LetsBuy&unicode=E&return=null&port=1";
            // create a new cURL resource
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // grab URL and pass it to the browser
            // close cURL resource, and free up system resources
            $curlresponse = curl_exec($ch);
            curl_close($ch);
            $createuser->otp = $otp;
            $createuser->save();

            Session::put('user_id', $createuser->id);

            if ((\App::getLocale() == 'en')) {
                return response()->json(['success' => 'Please Verify OTP Sent On Your Mobile Number', 'status' => '1']);
            } else {
                return response()->json(['success' => 'يرجى التحقق من إرسال OTP على رقم هاتفك المحمول', 'status' => '1']);
            }

        }

    }

    public function emailverification(Request $request, $id)
    {
        $user = base64_decode($id);
        User::where('id', $user)->update(['email_verification' => 1]);
        if ((\App::getLocale() == 'en')) {
            toastr()->success('Email Verified Successfully');
        } else {
            toastr()->success('تم التحقق من البريد الإلكتروني بنجاح');
        }
        return redirect('/');

    }

    public function forget(Request $request)
    {

        if ($request->isMethod('post')) {
            $rules = [
                'forgetemail' => 'required|email',
            ];

            $messages = [
                'forgetemail.required' => 'Please Enter Email',
                'forgetemail.email' => 'Please Enter Valid Email',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            $errors = $validator->errors();

            if ($validator->fails()) {
                return response()->json(['forgetemail' => $errors->first('forgetemail')]);
            }

            $user = User::where('email', $request->forgetemail)->first();
            $user_status = User::where('email', $request->forgetemail)->where('status', 0)->first();
            $is_delete_user = User::where('email', $request->forgetemail)->where('is_delete', 1)->first();

            if (empty($user)) {
                if ((\App::getLocale() == 'en')) {
                    return response()->json(['error' => 'Email is Not Registered']);
                } else {
                    return response()->json(['error' => 'البريد الإلكتروني غير مسجل']);
                }
            }

            if (!empty($user_status)) {
                if ((\App::getLocale() == 'en')) {
                    return response()->json(['error' => 'Your Account is Suspended By Admin']);
                } else {
                    return response()->json(['error' => 'تم تعليق حسابك من قبل المسؤول']);
                }
            }

            if (!empty($is_delete_user)) {
                if ((\App::getLocale() == 'en')) {
                    return response()->json(['error' => 'Your Account is Deleted By Admin']);
                } else {
                    return response()->json(['error' => 'تم حذف حسابك من قبل المسؤول']);
                }
            }

            $forget = new PasswordReset;
            $forget->user_id = $user->id;
            $forget->email = $request->forgetemail;

            $forget->save();

            $name = $user->name;
            $link = url('/') . "/user/forgetpassword/" . base64_encode($user->id);

            $EmailTemplates = Emailtemplate::where('slug', 'forgot_password')->first();
            $message = str_replace(array('{name}', '{link}'), array($name, $link), $EmailTemplates->description_en);
            $subject = $EmailTemplates->subject_en;
            $to_email = $request->forgetemail;
            $data = array();
            $data['msg'] = $message;
            Mail::send('emails.email_verification', $data, function ($message) use ($to_email, $subject) {
                $message->to($to_email)
                    ->subject($subject);
                $message->from(env('MAIL_USERNAME', 'testingbydev@gmail.com'));
            });

            $mobile = $user->mobile;

            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            if ((\App::getLocale() == 'en')) {
                $message = ' Change password link ' . $link;
            } else {
                $message = ' رابط تغيير كلمة المرور ' . $link;
            }
            $senderid = "LetsBuy"; //Your senderid
            $message = urlencode($message);
            $url = "https://www.enjazsms.com/api/sendsms.php?username=" . $user . "&password=" . $password . "&message=" . $message . "&numbers=" . $mobilenumbers . "&sender=LetsBuy&unicode=E&return=null&port=1";
            // create a new cURL resource
            $ch = curl_init();
            // set URL and other appropriate options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // grab URL and pass it to the browser
            // close cURL resource, and free up system resources
            $curlresponse = curl_exec($ch);
            curl_close($ch);

            if ((\App::getLocale() == 'en')) {
                return response()->json(['success' => 'Password Change Link Sent Successfully', 'status' => '1']);
            } else {
                return response()->json(['success' => 'تم إرسال رابط تغيير كلمة المرور بنجاح', 'status' => '1']);
            }

        }

    }

    public function changepassword(Request $request, $id)
    {

        $user_id = $id;

        $isexpired = PasswordReset::where('user_id', base64_decode($user_id))->where('is_used', 0)->first();

        if (empty($isexpired)) {
            if ((\App::getLocale() == 'en')) {
                toastr()->error('Forget Password Link Is Expired');
            } else {
                toastr()->error('نسيت كلمة المرور الارتباط منتهي الصلاحية');
            }
            return redirect()->back();
        }

        if ($request->isMethod('post')) {

            $rules = [
                'password' => 'required|min:6||max:8|same:confirmpassword',
                'confirmpassword' => 'required|min:6|max:8',
            ];

            $messages = [
                'password.required' => 'Please Enter Password',
                'password.min' => 'Password Must Be Greater Than 8 Characters',
                'confirmpassword.min' => 'Confirm Password Must Be Greater Than 8 Characters',
                'password.same' => 'Please Enter Password same as Confirm Password',
                'confirmpassword.required' => 'Please Enter Confirm Password',
            ];

            $v = Validator::make($request->all(), $rules, $messages);
            if ($v->fails()) {

                foreach ($request->all() as $key => $value) {
                    if ($v->errors()->first($key)) {
                        return redirect()->back()->with('error', $v->errors()->first($key))->withInput();
                    }
                }
            }

            PasswordReset::where('user_id', base64_decode($user_id))->update(['is_used' => 1]);

            $user = User::where('id', base64_decode($user_id))->update(['password' => Hash::make($request->password)]);

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Password Changed Successfully');
            } else {
                toastr()->success('تم تغيير الرقم السري بنجاح');
            }
            return redirect('/');
        }

        return view('frontend.reset-password', compact('user_id'));
    }

    public function smssend($mobile)
    {
        $user = "letsbuy";
        $password = "Nn0450292**";
        $mobilenumbers = $mobile;
        $otp = rand(1000, 9999);
        $message = 'Your Verification Code: ' . $otp;
        $senderid = "LetsBuy"; //Your senderid
        $message = urlencode($message);
        $url = "https://www.enjazsms.com/api/sendsms.php?username=" . $user . "&password=" . $password . "&message=" . $message . "&numbers=" . $mobilenumbers . "&sender=LetsBuy&unicode=E&return=full&port=1";
        // create a new cURL resource
        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // grab URL and pass it to the browser
        $curlresponse = curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close($ch);
        $response = array();
        $response['otp'] = $otp;
        return $response;

    }

    public function userverify(Request $request)
    {
        $user_id = session::get('user_id');

        $userdata = User::where('id', $user_id)->where('otp', $request->otp)->first();
        if (!empty($userdata)) {
            User::where('id', $user_id)->update(['otp_verify' => 1, 'otp' => null]);
            Auth::loginUsingId($user_id);
            if ((\App::getLocale() == 'en')) {
                return response()->json(['success' => 'Otp Verified Successfully', 'status' => '1']);
            } else {
                return response()->json(['success' => 'تم التحقق من Otp بنجاح', 'status' => '1']);
            }
        } else {
            if ((\App::getLocale() == 'en')) {
                return response()->json(['success' => 'Please Enter Valid Otp', 'status' => '2']);
            } else {
                return response()->json(['success' => 'الرجاء إدخال صالح Otp', 'status' => '2']);
            }
        }

    }

    public function userverifylogin(Request $request)
    {
        $user_id = session::get('user_id_login');

        $userdata = User::where('id', $user_id)->where('otp', $request->otp)->first();
        if (!empty($userdata)) {
            User::where('id', $user_id)->update(['otp_verify' => 1, 'otp' => null]);
            if ((\App::getLocale() == 'en')) {
                return response()->json(['success' => 'Otp Verified Successfully', 'status' => '1']);
            } else {
                return response()->json(['success' => 'تم التحقق من Otp بنجاح', 'status' => '1']);
            }
        } else {
            if ((\App::getLocale() == 'en')) {
                return response()->json(['success' => 'Please Enter Valid Otp', 'status' => '2']);
            } else {
                return response()->json(['success' => 'الرجاء إدخال صالح Otp', 'status' => '2']);
            }
        }

    }

    public function mobile_login(Request $request)
    {
        $rules = [
            'mobile' => 'required|exists:users,mobile',
        ];

        $messages = [
            'mobile.required' => 'Please Enter Mobile Number',
            'mobile.exists' => 'This Mobile Number is Not Registered',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();
        $password = $request->password;

        if ($validator->fails()) {
            return response()->json(['mobile' => $errors->first('mobile')]);
        }

        $usermobile = User::where('mobile', $request->mobile)->first();

        $mobile = '966' . $request->mobile;

        $user = "letsbuy";
        $password = "Nn0450292**";
        $mobilenumbers = $mobile;
        $otp = rand(1000, 9999);
        $message = 'Your Verification Code: ' . $otp;
        $senderid = "LetsBuy"; //Your senderid
        $message = urlencode($message);
        $url = "https://www.enjazsms.com/api/sendsms.php?username=" . $user . "&password=" . $password . "&message=" . $message . "&numbers=" . $mobilenumbers . "&sender=LetsBuy&unicode=E&return=null&port=1";
        // create a new cURL resource
        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // grab URL and pass it to the browser
        // close cURL resource, and free up system resources
        $curlresponse = curl_exec($ch);
        curl_close($ch);

        Session::put('user_id', $usermobile->id);

        User::where('mobile', $request->mobile)->update(['otp' => $otp]);

        if ((\App::getLocale() == 'en')) {
            return response()->json(['status' => 'Please Verify OTP Sent On Your Mobile Number', 'value' => '2']);
        } else {
            return response()->json(['status' => 'يرجى التحقق من إرسال OTP على رقم هاتفك المحمول', 'value' => '2']);
        }

    }

}
