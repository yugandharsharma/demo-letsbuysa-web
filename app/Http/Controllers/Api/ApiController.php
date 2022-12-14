<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\Brand;
use App\Model\Cart;
use App\Model\Category;
use App\Model\Coupon;
use App\Model\Coupon_applied;
use App\Model\Coupon_history;
use App\Model\Emailtemplate;
use App\Model\Filter;
use App\Model\Filter_values;
use App\Model\Gift_voucher;
use App\Model\Global_settings;
use App\Model\HotToday;
use App\Model\Mostviewedproduct;
use App\Model\Notification;
use App\Model\Option;
use App\Model\Option_value;
use App\Model\Order;
use App\Model\Order_details;
use App\Model\Order_track;
use App\Model\PasswordReset;
use App\Model\Product;
use App\Model\Product_attribute;
use App\Model\Product_delivery_features;
use App\Model\Product_details;
use App\Model\Product_reviews;
use App\Model\Subcategory;
use App\Model\Transaction_details;
use App\Model\Wallet;
use App\Model\Wallet_recharge_history;
use App\Model\Wishlist;
use App\User;
use Auth;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mail;
use URL;
use Validator;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'email.unique' => "Email Address Already Exists",
                'email.min' => "Email Must Have Minimum 6 Characters",
                'email.max' => "Email Must Be Less Than 250 Characters",
                'email.email' => "Enter Valid Email Format",
                'email.required' => "Please Enter Email Address",

                'telephone.unique' => "Mobile Number Already Exists",
                'telephone.required' => "Please Enter Mobile Number",
                'telephone.min' => "Mobile Number Must Have Minimum 7 Characters",
                'telephone.max' => "Mobile Number Must Be Less Than 12 Characters",

                'full_name.required' => "Please Enter Full Name",
                'full_name.min' => "Full Name Must Have Minimum 3 Characters",
                'full_name.max' => "Full Name Must Be Less Than 55 Characters",

                'password.required' => "Please Enter Password",
                'password.min' => "Password Must Have Minimum 6 Characters",
                'password.max' => "Password Must Be Less Than 20 Characters",

                'device_id.required' => "Please Enter Device ID",
                'device_id.max' => "Device ID Must Be Less Than 255 Characters",

                'device_token.required' => "Please Enter Device Token",
                'device_token.max' => "Device Token Must Be Less Than 255 Characters",

                'device_type.required' => "Please Enter Device Type",
                'device_type.max' => "Device Type Must Be Less Than 255 Characters",
            ];
        } else {
            $messages = [
                'email.unique' => "?????????? ???????????? ???????????????????? ?????????? ????????????",
                'email.min' => "?????? ???? ?????????? ???????????? ???????????????????? ?????? 6 ???????? ?????? ????????",
                'email.max' => "?????? ???? ???????? ???????????? ???????????????????? ?????? ???? 250 ??????????",
                'email.email' => "???????? ?????????? ???????? ???????????????? ????????",
                'email.required' => "???????????? ?????????? ?????????? ???????????? ????????????????????",

                'telephone.unique' => "?????? ???????????? ???????? ????????????",
                'telephone.required' => "???????????? ?????????? ?????? ???????????? ??????????????",
                'telephone.min' => "?????? ?????? ?????? ?????? ???????????? ?????????????? ???? 7 ????????",
                'telephone.max' => "?????? ???? ???????? ?????? ???????????? ?????????????? ?????? ???? 12 ??????????",

                'full_name.required' => "???????????? ?????????? ?????????? ????????????",
                'full_name.min' => "?????? ?????? ?????? ?????????? ???????????? ???? 3 ????????",
                'full_name.max' => "?????? ???? ???????? ?????????? ???????????? ?????? ???? 55 ??????????",

                'password.required' => "???????????? ?????????? ???????? ????????????",
                'password.min' => "?????? ???? ?????????? ???????? ???????????? ?????? 6 ???????? ?????? ??????????",
                'password.max' => "?????? ???? ???????? ???????? ???????????? ?????? ???? 20 ??????????",

                'device_id.required' => "???????????? ?????????? ???????? ????????????",
                'device_id.max' => "?????? ???? ???????? ???????? ???????????? ?????? ???? 255 ??????????",

                'device_token.required' => "???????????? ?????????? ?????? ????????????",
                'device_token.max' => "?????? ???? ???????? ?????? ???????????? ?????? ???? 255 ??????????",

                'device_type.required' => "???????????? ?????????? ?????? ????????????",
                'device_type.max' => "?????? ???? ???????? ?????? ???????????? ?????? ???? 255 ??????????",
            ];
        }

        $rules = [
            'full_name' => 'required|min:3|max:55',
            'email' => 'required|unique:users|email|min:6|max:250',
            'telephone' => 'required|unique:users,mobile|min:7|max:12',
            'password' => 'required|min:6|max:20',
            'device_token' => 'required|max:255',
            'device_id' => 'required|max:255',
            'device_type' => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        //Sms send code start
        $otp = $this->smssend($request['telephone']);

        $user_data = new User();
        $user_data->name = $request['full_name'];
        $user_data->email = $request['email'];
        $user_data->mobile = $request['telephone'];
        $user_data->device_id = $request['device_id'];
        $user_data->device_token = $request['device_token'];
        $user_data->device_type = $request['device_type'];
        $user_data->password = Hash::make($request['password']);
        $user_data->otp = $otp;
        $user_data->role = 2;
        $user_data->save();

        $user = User::select('*')->where('id', $user_data['id'])->first();

        $mycartdata = json_decode($request->addtocart_products, true);

        if (!empty($mycartdata)) {
            foreach ($mycartdata as $carts) {
                $product = Cart::where('user_id', $user->id)->where('product_id', $carts['product_id'])->first();
                if (empty($product)) {
                    $cart = new Cart;
                    $cart->user_id = $user->id;
                    $cart->product_id = $carts['product_id'];
                    if (!empty($carts['option_id'])) {
                        $cart->option_id = $carts['option_id'];
                    }
                    $cart->quantity = $carts['quantity'];
                    $cart->save();
                }
            }
        }

        $res['status'] = 1;
        if ($request->header('language') == 3) {
            $res['success']['message'] = "The activation code has been sent to the phone number you registered with";
        } else {
            $res['success']['message'] = "???? ?????????? ?????? ?????????????? ?????? ?????? ???????????? ???????? ?????? ???????????????? ????";
        }
        $res['user'] = ['customer_id' => $user_data['id'], 'email' => $user_data['email'], 'full_name' => $user_data['name'], 'telephone' => $user_data['mobile'], 'notification_push' => ($user['notification_push'] == 1) ? true : false, 'telephone_status' => ($user['otp_verify'] == 1) ? 'verified' : 'not_verified', 'notification_sms' => ($user['notification_sms'] == 1) ? true : false, 'notification_email' => ($user['notification_email'] == 1) ? true : false, 'social_login' => false, 'otp' => $otp];
        return response($res);
    }

    public function otpverification(Request $request)
    {
        $user_id = $request['customer_id'];
        $otp = $request['code'];

        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'code' => "Please Enter Code",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'code' => "???????????? ?????????? ??????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'code' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $user_data = User::find($user_id);
        if (!empty($user_data) && !empty($otp)) {
            $user = User::select('*')->where('id', $user_data['id'])->first();

            if (($user_data->otp == $request['code'])) {
                $user_data->otp_verify = 1;
                $user_data->otp = null;
                $user_data->save();

                $res['status'] = 1;
                if ($request->header('language') == 3) {
                    $res['success']['message'] = "Account Verified Successfully.";
                } else {
                    $res['success']['message'] = "???? ???????????? ???? ???????????? ??????????.";
                }
                $res['user'] = ['customer_id' => $user['id'], 'email' => $user['email'], 'full_name' => $user['name'], 'telephone' => $user['mobile'], 'notification_push' => ($user['notification_push'] == 1) ? true : false, 'telephone_status' => ($user['otp_verify'] == 1) ? 'verified' : 'not_verified', 'notification_sms' => ($user['notification_sms'] == 1) ? true : false, 'notification_email' => ($user['notification_email'] == 1) ? true : false, 'social_login' => false];

                return response($res);
            } else {
                $res['status'] = 0;
                if ($request->header('language') == 3) {
                    $res['error']['message'] = "Invalid OTP";
                } else {
                    $res['error']['message'] = "OTP ?????? ????????";
                }
                return response($res);
            }
        } else {
            $res['status'] = 0;
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Invalid User";
            } else {
                $res['error']['message'] = "???????????? ?????? ????????";
            }
            return response($res);
        }
    }

    public function login(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'email.min' => "Email Must Have Minimum 6 Characters",
                'email.max' => "Email Must Be Less Than 250 Characters",
                'email.email' => "Enter Valid Email Format",
                'email.required' => "Please Enter Email Address",

                'password.required' => "Please Enter Password",
                'password.min' => "Password Must Have Minimum 6 Characters",
                'password.max' => "Password Must Be Less Than 20 Characters",

                'device_id.required' => "Please Enter Device ID",
                'device_id.max' => "Device ID Must Be Less Than 255 Characters",

                'device_token.required' => "Please Enter Device Token",
                'device_token.max' => "Device Token Must Be Less Than 255 Characters",

                'device_type.required' => "Please Enter Device Type",
                'device_type.max' => "Device Type Must Be Less Than 255 Characters",
            ];
        } else {
            $messages = [
                'email.min' => "?????? ???? ?????????? ???????????? ???????????????????? ?????? 6 ???????? ?????? ????????",
                'email.max' => "?????? ???? ???????? ???????????? ???????????????????? ?????? ???? 250 ??????????",
                'email.email' => "???????? ?????????? ???????? ???????????????? ????????",
                'email.required' => "???????????? ?????????? ?????????? ???????????? ????????????????????",

                'password.required' => "???????????? ?????????? ???????? ????????????",
                'password.min' => "?????? ???? ?????????? ???????? ???????????? ?????? 6 ???????? ?????? ??????????",
                'password.max' => "?????? ???? ???????? ???????? ???????????? ?????? ???? 20 ??????????",

                'device_id.required' => "???????????? ?????????? ???????? ????????????",
                'device_id.max' => "?????? ???? ???????? ???????? ???????????? ?????? ???? 255 ??????????",

                'device_token.required' => "???????????? ?????????? ?????? ????????????",
                'device_token.max' => "?????? ???? ???????? ?????? ???????????? ?????? ???? 255 ??????????",

                'device_type.required' => "???????????? ?????????? ?????? ????????????",
                'device_type.max' => "?????? ???? ???????? ?????? ???????????? ?????? ???? 255 ??????????",
            ];
        }

        $rules = [
            'email' => 'required|email|min:6|max:250',
            'password' => 'required|min:6|max:20',
            'device_token' => 'required|max:255',
            'device_id' => 'required|max:255',
            'device_type' => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $user = User::where('email', $request['email'])->where("status", '1')->where("is_delete", '0')->where('role', '2')->first();

        $mycartdata = json_decode($request->addtocart_products, true);

        if (!empty($mycartdata)) {
            foreach ($mycartdata as $carts) {
                $product = Cart::where('user_id', $user->id)->where('product_id', $carts['product_id'])->first();
                if (empty($product)) {
                    $cart = new Cart;
                    $cart->user_id = $user->id;
                    $cart->product_id = $carts['product_id'];
                    if (!empty($carts['option_id'])) {
                        $cart->option_id = $carts['option_id'];
                    }
                    $cart->quantity = $carts['quantity'];
                    $cart->save();
                }
            }
        }

        if (!empty($user)) {
            if (Hash::check($request['password'], $user->password)) {
                $otp = '';
                if ($user->otp_verify == 0) {
                    $otp = $this->smssend($user->mobile);
                    User::where('id', $user->id)->update(['otp' => $otp]);
                }

                User::where('id', $user->id)->update(['device_token' => $request['device_token'], 'device_id' => $request['device_id'], 'device_type' => $request['device_type']]);

                $res['status'] = 1;
                if ($request->header('language') == 3) {
                    $res['success']['message'] = "Login Successfully";
                } else {
                    $res['success']['message'] = "Login Successfully";
                }

                $res['user'] = ['customer_id' => $user['id'], 'email' => $user['email'], 'full_name' => $user['name'], 'telephone' => $user['mobile'], 'notification_push' => ($user['notification_push'] == 1) ? true : false, 'telephone_status' => ($user['otp_verify'] == 1) ? 'verified' : 'not_verified', 'notification_sms' => ($user['notification_sms'] == 1) ? true : false, 'notification_email' => ($user['notification_email'] == 1) ? true : false, 'social_login' => false, 'otp' => $otp];
                return response($res);
            } else {
                $res['status'] = 0;
                if ($request->header('language') == 3) {
                    $res['error']['message'] = "These credentials do not match our records";
                } else {
                    $res['error']['message'] = "These credentials do not match our records";
                }
                return response($res);
            }
        } else {
            $res['status'] = 0;
            if ($request->header('language') == 3) {
                $res['error']['message'] = "These credentials do not match our records";
            } else {
                $res['error']['message'] = "These credentials do not match our records";
            }

            return response($res);
        }
    }

    public function forgetpassword(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'email.min' => "Email Must Have Minimum 6 Characters",
                'email.max' => "Email Must Be Less Than 250 Characters",
                'email.email' => "Enter Valid Email Format",
                'email.required' => "Please Enter Email Address",
            ];
        } else {
            $messages = [
                'email.min' => "?????? ???? ?????????? ???????????? ???????????????????? ?????? 6 ???????? ?????? ????????",
                'email.max' => "?????? ???? ???????? ???????????? ???????????????????? ?????? ???? 250 ??????????",
                'email.email' => "???????? ?????????? ???????? ???????????????? ????????",
                'email.required' => "???????????? ?????????? ?????????? ???????????? ????????????????????",
            ];
        }

        $rules = [
            'email' => 'required|email|min:6|max:250',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $user = User::where('email', $request->email)->where('status', 1)->where('is_delete', 0)->first();

        if (empty($user)) {
            $res['success']['message'] = 'Invalid Email';
            return response($res);
        } else {
            $forget = new PasswordReset;
            $forget->user_id = $user->id;
            $forget->email = $request->email;

            $forget->save();

            $name = $user->name;
            $link = url('/') . "/user/forgetpassword/" . base64_encode($user->id);

            $EmailTemplates = Emailtemplate::where('slug', 'forgot_password')->first();
            $message = str_replace(array('{name}', '{link}'), array($name, $link), $EmailTemplates->description_en);
            $subject = $EmailTemplates->subject_en;
            $to_email = $request->email;
            $data = array();
            $data['msg'] = $message;
            Mail::send('emails.email_verification', $data, function ($message) use ($to_email, $subject) {
                $message->to($to_email)
                    ->subject($subject);
                $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
            });

            $res['success']['message'] = 'Reset password link sent on registered email id.';
            return response($res);
        }
    }

    public function home(Request $request)
    {
        $customer_id = $request['customer_id'] ? $request['customer_id'] : 0;

        $banners = DB::table('banner')->select('*')->where('status', 1)->get();
        $banners1 = DB::table('banner')->select('*')->where('status', 1)->get();
        $sidebanners = DB::table('clients')->select('*')->where('status', 1)->orderby('id', 'desc')->take(2)->get();
        $offerbanners = DB::table('offer_banner')->select('*')->where('status', 1)->orderby('banner_id', 'desc')->take(6)->get();
        $hotdeals = DB::table('hotdeals')->select('*')->where('status', 1)->orderby('id', 'desc')->take(4)->get();
        $hottodaybanner = HotToday::where('status', 1)->take(4)->get();
        $topbrands = DB::table('brands')->select('*')->where('status', 1)->orderby('id', 'desc')->take(5)->get();
        if ($request->header('language') == 3) {
            $new_arrival_products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->orderby('id', 'desc')->take(20)->get();
        } else {
            $new_arrival_products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->orderby('id', 'desc')->take(20)->get();
        }

        if ($request->header('language') == 3) {
            $hot_today_products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->take(20)->get();
            $hot_deal_products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->take(20)->get();

            $best_seller_products = DB::table('order_details')
                ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                ->orderby('total', 'desc')
                ->groupby('order_details.product_id')
                ->take(20)->get();

            $trending_products = DB::table('most_product_viewed')
                ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                ->orderby('most_product_viewed.count', 'desc')
                ->whereNull('user_id')
                ->groupby('most_product_viewed.product_id')
                ->take(20)->get();

        } else {
            $hot_today_products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->take(20)->get();
            $hot_deal_products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->take(20)->get();

            $best_seller_products = DB::table('order_details')
                ->select('products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                ->orderby('total', 'desc')
                ->groupby('order_details.product_id')
                ->take(20)->get();

            $trending_products = DB::table('most_product_viewed')
                ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))
                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                ->orderby('most_product_viewed.count', 'desc')
                ->whereNull('user_id')
                ->groupby('most_product_viewed.product_id')
                ->take(20)->get();

        }
        $globalsettings = Global_settings::all();
        if ($request->header('language') == 3) {
            $image = $globalsettings[0]->popup_image_en;
        } else {
            $image = $globalsettings[0]->popup_image_ar;
        }
        $wishlistcount = Wishlist::where('user_id', $customer_id)->count();
        $cartcount = Cart::where('user_id', $customer_id)->count();
        $res['wishlist_count'] = $wishlistcount;

        $data = array();

        $data['banners'] = $banners;
        $data['sidebanners'] = $sidebanners;
        $data['offerbanners'] = $offerbanners;
        $data['hottodaybanner'] = $hottodaybanner;
        $data['hotdeals'] = $hotdeals;
        $data['topbrands'] = $topbrands;
        $data['hot_deal_products'] = $hot_deal_products;
        $data['hot_today_products'] = $hot_today_products;
        $data['new_arrival_products'] = $new_arrival_products;
        $data['trending_products'] = $trending_products;
        $data['best_seller_products'] = $best_seller_products;
        $data['cartcount'] = $cartcount;
        $data['model_image'] = $image;
        $data['model_sub_category_id'] = $globalsettings[0]->pop_up_subcategory_id;
        $data['model_category_id'] = $globalsettings[0]->pop_up_category_id;
        $data['model_status'] = $globalsettings[0]->popup_status;

        if ($data) {
            $res['product_path'] = URL::to('/') . '/public/product_images/';
            $res['banner_path'] = URL::to('/') . '/public/images/banners/';
            $res['hottoday'] = URL::to('/') . '/public/images/hot_today/';
            $res['hotdeal'] = URL::to('/') . '/public/images/hotdeal/';
            $res['sidebanner_path'] = URL::to('/') . '/public/images/side-banner/';
            $res['topbrands_path'] = URL::to('/') . '/public/images/brands/';
            $res['mid_banner_path1'] = URL::to('/') . '/assets/front-end/images/mid-banner1.jpg';
            $res['mid_banner_path2'] = URL::to('/') . '/assets/front-end/images/mid-banner2.jpg';
            $res['mid_banner_path3'] = URL::to('/') . '/assets/front-end/images/mid-banner3.jpg';
            $res['mid_banner_path4'] = URL::to('/') . '/assets/front-end/images/mid-banner4.jpg';
            $res['model_image_path'] = URL::to('/') . '/public/images/globalsetting/';

            $res['status'] = 1;
            $res['message'] = "Data Fetches Successfully";
            $res['response'] = $data;

            return response($res);
        } else {
            $res['status'] = 0;
            $res['message'] = "Data Not found";
            $res['response'] = [];

            return response($res);
        }
    }

    public function resend(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'customer_id.required' => "Please Enter Customer ID",
            ];
        } else {
            $messages = [
                'customer_id.required' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = [
            'customer_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }
        $user = User::select('*')->where('id', $request['customer_id'])->first();

        if (!empty($user)) {
            //Sms send code start
            $otp = $this->smssend($user->mobile);
            User::where('id', $request['customer_id'])->update(['otp' => $otp]);

            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = 'OTP sent successfully.';
                $res['otp'] = $otp;
            } else {
                $res['success']['message'] = '???? ?????????? OTP ??????????.';
                $res['otp'] = $otp;
            }
            return response($res);
        } else {
            $res['status'] = 0;
            if ($request->header('language') == 3) {
                $res['error']['message'] = 'Customer not found.';
            } else {
                $res['error']['message'] = '???????????? ?????? ??????????.';
            }
            return response($res);
        }
    }

    public function change_number(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [

                'customer_id.required' => "Please Enter Customer ID",

                'telephone.unique' => "Mobile Number Already Exists",
                'telephone.required' => "Please Enter Mobile Number",
                'telephone.min' => "Mobile Number Must Have Minimum 7 Characters",
                'telephone.max' => "Mobile Number Must Be Less Than 12 Characters",

            ];
        } else {
            $messages = [

                'customer_id.required' => "???????????? ?????????? ???????? ????????????",

                'telephone.unique' => "???? ?????? ???????????? ????????????",
                'telephone.required' => "???????????? ?????????? ?????? ???????????? ??????????????",
                'telephone.min' => "?????? ?????? ?????? ?????? ???????????? ?????????????? ???? 7 ????????",
                'telephone.max' => "?????? ???? ???????? ?????? ???????????? ?????????????? ?????? ???? 12 ??????????",

            ];
        }

        $rules = [
            'customer_id' => 'required',
            'telephone' => 'required|unique:users,mobile|min:7|max:12',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $user = User::select('*')->where('id', $request['customer_id'])->first();

        if (!empty($user)) {
            User::where('id', $request['customer_id'])->update(['mobile' => $request['telephone']]);

            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Mobile Number is updated.';
            } else {
                $res['success']['message'] = '???? ?????????? ?????? ???????????? ??????????????.';
            }
            $res['user'] = ['customer_id' => $user['id'], 'email' => $user['email'], 'full_name' => $user['name'], 'telephone' => $user['mobile'], 'notification_push' => ($user['notification_push'] == 1) ? true : false, 'telephone_status' => ($user['otp_verify'] == 1) ? 'verified' : 'not_verified', 'notification_sms' => ($user['notification_sms'] == 1) ? true : false, 'notification_email' => ($user['notification_email'] == 1) ? true : false, 'social_login' => false];
            return response($res);
        } else {
            $res['status'] = 0;
            if ($request->header('language') == 3) {
                $res['error']['message'] = 'User is not registered.';
            } else {
                $res['error']['message'] = '???????????????? ?????? ????????.';
            }
            return response($res);
        }
    }

    public function appconfig(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'device_type.required' => "Please Enter Device Type",
                'version.required' => "Please Enter Version",
            ];
        } else {
            $messages = [
                'device_type.required' => "???????????? ?????????? ?????? ????????????",
                'version.required' => "???????????? ?????????? ??????????????",
            ];
        }

        $rules = [
            'device_type' => 'required',
            'version' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $res['appVersion'] = ['forceUpdate' => false, 'recommendedUpdate' => false];
        $res['languages'] = [['name' => 'English', 'code' => 'en-gb', 'language_id' => '3', 'translations' => ["navHome" => "Home",
            "navMyAccount" => "My Account",
            "aboutus" => "About Us",
            "sku" => "Product Code",
            "size" => "Size",
            "no_transaction" => "No Transactions",
            "readmore" => "Read More",
            "color" => "Color",
            "return" => "Return",
            "order" => "Order",
            "credit_card" => "Credit Card",
            "soldout" => "Sold out",
            "description" => "Description",
            "feature" => "Features",
            "prduct_remove" => "Product Succefully Removed From Cart",
            "readless" => "Read Less",
            "transaction_history" => "Transactions History",
            "navCategories" => "Categories",
            "navCart" => "Cart",
            "addressdetail" => "Address Detail",
            "my_wallet" => "My Wallet",
            "product_attribute" => "Product Attributes",
            "pay" => "Pay",
            "addfunds" => "Add Funds",
            "add" => "Add",
            "applepay" => "Pay",
            "choosemethod" => "Choose Method",
            "select" => "Select",
            "creditdebit" => "Credit/Debit Card",
            "voucher" => "Voucher",
            "availablecredit" => "Available Credit",
            "addcredit" => "Add Credit",
            "reedemvoucher" => "Redeem Voucher",
            "address" => "Address",
            "payment" => "Payment",
            "orderplaced" => "Order Placed",
            "placeorder" => "Place Order",
            "navWishlist" => "Wishlist",
            "navAboutApp" => "About Let's Buy",
            "navProductDetail" => "Product Detail",
            "navNewAddress" => "Add New Address",
            "navEditAddress" => "Edit Address",
            "navMyCart" => "My Cart",
            "navCheckout" => "Checkout",
            "navShippingInfo" => "Shipping Info",
            "navReturnPolicy" => "Return Policy",
            "navPrivacyPolicy" => "Privacy Policy",
            "navTermsAndConditions" => "Terms and Conditions",
            "navAboutUs" => "About Us",
            "navMyOrders" => "My Orders",
            "maintenanceMessage" => "\n\nCurrently, we are under server maintenance. Please come back after some time. \n\nSorry for the Inconvenience =>nd we appreciate your patience.\n\n",
            "email" => "Email",
            "couponcode" => "Coupon Code",
            "signIn" => "Sign In",
            "continueshopping" => "Continue shopping",
            "shippingto" => "Shipping To",
            "order_number" => "Order Number",
            "thanks_for_your_order" => "Thanks for your order!",
            "place_order" => "Place order",
            "signUp" => "Sign Up",
            "skip" => "SKIP",
            "continue" => "Continue",
            "balance_sr" => "Balance SR",
            "delivery_address" => "Delivery address",
            "wallet" => "Wallet",
            "forgotPassword" => "Forgot password?",
            "signInWith" => "or sign in with",
            "noHaveAccount" => "Don't have and account? ",
            "haveAccount" => "Have an account? ",
            "firstName" => "First Name",
            "lastName" => "Last Name",
            "fullName" => "Full Name",
            "password" => "Password",
            "mobileNumber" => "Mobile Number",
            "mobileNumberPlaceholder" => "5XXXXXXX",
            "signUpNoteOne" => "By signing up you accept all our ",
            "signUpNoteTwo" => " & ",
            "termCondition" => "Terms",
            "policies" => "Policies",
            "verifyCode" => "Verify Code",
            "codeNote" => "Your code will expire in 10 minutes",
            "codeLabel" => "Verification Code",
            "verify" => "Verify",
            "sendCodeAgain" => "SEND CODE AGAIN",
            "mobileNumberNote" => "Your code has been sent to ",
            "oldPassword" => "Old Password",
            "newPassword" => "New Password",
            "confirmNewPassword" => "Confirm New Password",
            "cancel" => "Cancel",
            "phoneBlank" => "Please enter mobile number",
            "phoneLength" => "Mobile number must between 7 to 12 digits",
            "phoneInvalid" => "Mobile number must only contain numeric values",
            "passwordBlank" => "Enter password",
            "passwordInvalid" => "Password must not contain whitespaces",
            "passwordLength" => "Password must be 6 character long",
            "emailInvalid" => "Enter valid email address",
            "emailBlank" => "Enter email address",
            "otpCodeInvalid" => "OTP code must be number.",
            "emptyCode" => "Please enter code",
            "settings" => "Settings",
            "settingsNote" => "Set your preferences",
            "addressLabel" => "Address label",
            "addressPlaceholder" => "eg.Home, Work",
            "neighbourHood" => "City",
            "streetName" => "Neighborhood/Street Name",
            "city" => "City",
            "zipCode" => "Post/Zip Code",
            "state" => "State/Province",
            "country" => "Country/Region",
            "saveAddress" => "Save Address",
            "updateAndSave" => "Update & Save",
            "signOut" => "Sign Out",
            "saveUpdate" => "Save & Update",
            "ok" => "OK",
            "thankYouRegister" => "Thank you for registering in Let's Buy. We'll send you verification code on your mobile number.",
            "error" => "Error",
            "success" => "Success",
            "information" => "Information",
            "errorMessage" => "Something went wrong. Please try again later",
            "download" => "Download",
            "remindLater" => "Remind Me Later",
            "updateAvailable" => "Update available",
            "updateMessage" => "New app version available for update.",
            "internalServerError" => "Internal Server error.Please try again later",
            "appName" => "Let's Buy",
            "retry" => "Retry",
            "linkUnableToOpen" => "Unable to open link",
            "changePhoneNumber" => "Change Mobile Number",
            "resendOTP" => "Resend OTP",
            "youHave" => "You have",
            "attemptsLeft" => "attempt left",
            "noAttemptsError" => "You have 0 attempt left, Please try again after 1 hour.",
            "retryAfterThirty" => "Resend OTP after 60 seconds",
            "tagLine" => "t   o      b   e      s   t   y   l   i   s   h",
            "welcome" => "Welcome",
            "hello" => "Hello",
            "viewAll" => "See All Items",
            "sendLink" => "Send Link",
            "nameBlank" => "Please enter Name",
            "nameLength" => "Name characters exceed limit",
            "nameInvalid" => "Name is invalid",
            "updatePassword" => "Update Password",
            "passwordUpdateSuccess" => "Password updated successfully.",
            "forgotSuccessMessage" => "A password reset link has been sent to your register Email",
            "pleaseWait" => "Please wait...",
            "writeYourReview" => "Write your review here...",
            "reviewFullName" => "Full Name",
            "reviewBlankError" => "Please enter review text",
            "myOrders" => "My Orders",
            "myAddress" => "My Addresses",
            "myWishList" => "My Wishlist",
            "helpSupport" => "Help & Support",
            "rateApp" => "Rate App",
            "shareApp" => "Share App",
            "editAddress" => "Edit Address",
            "loadingReviews" => "Loading more reviews ...",
            "pullRefresh" => "Pull To Refresh",
            "allReviews" => "All Reviews",
            "productUpdateCartMessage" => "Product updated in cart",
            "productMoveToWishlist" => "Product moved to wishlist",
            "areUSure" => "Are you sure?",
            "deleteItemToCartMessage" => "You want to delete item from cart",
            "deleteItemToCartAndMoveToWishlist" => "You want to remove item from cart and move to wishlist",
            "item" => "Item",
            "items" => "Items",
            "noItems" => "No items",
            "add" => "Add",
            "freeShipping" => "Free Shipping",
            "noProductsInCard" => "Your cart is empty!",
            "quantity" => "Quantity",
            "quantityPrice" => "Quantity Price",
            "moveToWishList" => "Move to Wishlist",
            "checkOut" => "CHECKOUT",
            "cartTotal" => "Cart Total",
            "remove" => "Remove",
            "requestLinkMessage" => "You will have to request reset link again.",
            "resetSuccessPassword" => "Password successfully reset",
            "loginNewPassword" => "Please login using your new password.",
            "changePassword" => "Change Password",
            "updateYourPassword" => "Update your password",
            "enterNewMobileForOTP" => "Enter new mobile number to receive OTP",
            "otpSendMessage" => "OTP has been sent to the new number",
            "editProfile" => "Edit Profile",
            "updateDetails" => "Update your details",
            "forgotPasswordTitle" => "Forgot Password",
            "getCodeMessage" => "Enter your registered email to get the code",
            "productAddWishlist" => "Product added to wishlist",
            "productRemoveWishlist" => "Product removed from wishlist",
            "productAddCard" => "Product added to cart",
            "welcomeBack" => "Welcome Back",
            "signInContinue" => "Sign in to continue",
            "deleteAddress" => "you want to delete address?",
            "delete" => "Delete",
            "edit" => "Edit",
            "verifyMobile" => "Verify Mobile No.",
            "noAddress" => "Add your Address to continue",
            "features" => "Features",
            "descriptions" => "Descriptions",
            "seeMore" => "See more",
            "writeReview" => "Write a review",
            "viewAllReview" => "View all reviews",
            "similarProdcuts" => "Similar Products",
            "recentlyViewed" => "Recently Viewed",
            "outOfStock" => "Out of Stock",
            "addToCart" => "Add to cart",
            "buyNow" => "Buy Now",
            "sortBy" => "Sort by",
            "filters" => "Filters",
            "loadingMoreProducts" => "Loading more products ...",
            "emptyWishlist" => "No items in your wishlist!",
            "changeLanguage" => "Change Language",
            "createAccount" => "Create Your Account",
            "justAStep" => "Just a step to go.",
            "submit" => "SUBMIT",
            "addImages" => "Add images",
            "yourReview" => "Your Review",
            "openGallery" => "Open Gallery",
            "openCamera" => "Open Camera",
            "pickImage" => "Pick an Image",
            "apply" => "Apply",
            "clear" => "Clear",
            "filterBy" => "Filter By",
            "mobile" => "Mobile",
            "verified" => "Verified",
            "yes" => "Yes",
            "no" => "No",
            "reviews" => "Reviews",
            "toGet" => "to get",
            "youAreEligible" => "You are eligible for",
            "done" => "Done",
            "ratingCountError" => "Please add rating",
            "goShopping" => "Go Shopping",
            "only" => "Only",
            "left" => "left",
            "chooseColor" => "choose color",
            "chooseSize" => "choose size",
            "couldNotAuthenticate" => "Could not authenticate reset link",
            "chooseDeliveryAddress" => "Choose delivery address",
            "paymentMethodTitle" => "Payment Methods",
            "off" => "Off",
            "all" => "all",
            "addItems" => "Add Items",
            "yourName" => "Your Name",
            "addLocation" => "Add Location",
            "dragMap" => "Drag map to address",
            "fetchAgain" => "Fetch again",
            "waitingForLocation" => "Waiting for location",
            "addressLoading" => "Loading...",
            "locationDisabled" => "Location permission disabled",
            "locationDisabledMessage" => "Location permission is required to fetch address",
            "noProductsFound" => "No products found",
            "couponCode" => "Coupon Code",
            "couponCodePlaceholder" => "Enter Coupon Code",
            "giftCode" => "Gift Code",
            "giftCodePlaceholder" => "Enter Gift Code",
            "applied" => "Applied",
            "applyString" => "Apply",
            "choose" => "choose",
            "total" => "Total",
            "shippingQuery" => "shipping",
            "optionsError" => "Please select all options",
            "navOrderDetails" => "Order Details",
            "productAddCart" => "Product added to cart",
            "followUsOn" => "Follow us on",
            "countryInvalid" => "Selected country invalid",
            "stateInvalid" => "Selected state invalid",
            "searchOnMap" => "Select location on map",
            "errCouldNotGetProductDetail" => "Could not fetch product detail",
            "categoryAll" => "All",
            "shippingMethodTitle" => "Shipping Methods",
            "orderSummery" => "Order Summary",
            "confirmOrder" => "Confirm Order",
            "errOrderDetail" => "Could not fetch details",
            "orderID" => "Order ID",
            "dateAddedLabel" => "Order Placed",
            "dateStatusLabel" => "Order",
            "trackOrder" => "Track Order",
            "cancelOrder" => "Cancel Order",
            "orderShippingTitle" => "Shipping Method",
            "orderShippingAddress" => "Shipping Address",
            "noOrders" => "No orders found",
            "loadingOrders" => "Loading more orders...",
            "inStock" => "In Stock",
            "warning" => "Attention",
            "appRestart" => "The app will restart now.",
            "enterRemainingDetails" => "Please enter remaining details and register",
            "rateProduct" => "Rate the product",
            "navWriteReview" => "Write a review",
            "navAllReviews" => "All Reviews",
            "searchPlaceholder" => "Search brands, products",
            "reasonPlaceholder" => "Write your reason here...",
            "reasonTextBlank" => "Please enter reason",
            "selectLanguage" => "Select Language",
            "customerSupportTitle" => "Customer Service",
            "allOrders" => "All Orders",
            "cancellationReason" => "Cancellation Reason",
            "otherReason" => "Other",
            "orderContactInfo" => "Contact Info",
            "orderPaymentMethod" => "Payment Method",
            "order" => "Order",
            "navTrackOrder" => "Track Order",
            "cancelVerifyMessage" => "You will be logged out.\nYour cart and wishlist have been saved. Please verify your account to retrieve this information",
            "productOptions" => "Product Options",
            "emailNotifLabel" => "Email notifications",
            "smsNotifLabel" => "SMS notifications",
            "pushNotifLabel" => "Push notifications",
            "notificationTitle" => "Notifications",
            "notificationSettingCaption" => "Enable or disable notifications",
            "navMyNotifications" => "My Notifications",
            "returnOrder" => "Return Order",
            "cardPaymentError" => "This payment method is currently unavailable",
            "bankTransferInfo" => "Please see bank transfer information below",
            "noNotifications" => "No notifications received",
            "select" => "select",
            "loadingNotifications" => "Loading more notifications...",
            "seeLess" => "See less",
            "trackingDetailLoadingMessage" => "We are fetching data from our tracking partners",
            "placeOrderConfirmMessage" => "Do you want to place this order?",
            "callUs" => "Call Us",
            "whatsapp" => "WhatsApp",
            "feedbackEmail" => "Email",
            "liveChat" => "Live Chat",
            "englishLabel" => "English",
            "arabicLabel" => "Arabic",
            "troubleMessage" => "Hello,\nI need your assistance. \nHere are my details:\n",
            "troubleSubject" => "Support request",
            "supportEmailError" => "Could not send email",
            "callingNotSupport" => "Your device not support calling.",
            "navReturnOrder" => "Return Order",
            "removeAll" => "Remove all",
            "selectAll" => "Select all",
            "returnReasonPlaceholder" => "Faulty or other details...",
            "phoneWithoutZero" => "Please enter the phone number without '0'",
            "itemsSelectError" => "Please select items for return",
            "returnReasonError" => "Please select reason for return",
            "productOpenedError" => "Please select answer",
            "streetNameBlank" => "Please add street name",
            "neighbourHoodInvalid" => "Neighbourhood characters exceed limit",
            "addressLabelInvalid" => "Label characters exceed limit",
            "zipCodeBlank" => "Please enter ZipCode",
            "zipCodeInvalid" => "Zip Code is invalid",
            "zipCodeLong" => "Zip Code must be 5 to 6 digits",
            "countryBlank" => "Please select country",
            "stateBlank" => "Please select state",
            "returnID" => "Return ID",
            "productIsOpened" => "Product is opened",
            "selectItemsReturn" => "Select items to return",
            "returnReasonTitle" => "Reason for returning order",
            "returnComments" => "Comments",
            "returnedStatus" => "Returned",
            "makeDefault" => "Make Default",
            "noAddressAddedError" => "You don't have any verified address. Add an address or verify your added address",
            "additionalItemsWarning" => "You have additional items in cart. They will be checked-out along with current product. Continue?",
            "cod" => "Cash on Delivery",
            "bank_transfer" => "Bank Transfer",
            "tap-card" => "Credit or Debit Card",
            "tap-mada" => "MADA Payment"],
            'return_reason' => [
                ["return_reason_id" => "1", "name" => " I got damage product"],
                ["return_reason_id" => "5", "name" => " Other, Please Explain"],
                ["return_reason_id" => "4", "name" => " There is a mistake, please explain"],
                ["return_reason_id" => "3", "name" => " Wrong product"],
                ["return_reason_id" => "2", "name" => " You received a product by mistake"],
            ],
            'order_cancel_reasone' => [
                ["cancel_reasone_id" => "1", "name" => "Duplicate Order"],
                ["return_reason_id" => "2", "name" => "Wrong Items"],
                ["return_reason_id" => "3", "name" => "Other"],
            ],
            'sort_filter' => [
                ["id" => "0", "label" => "Default", "sort_val" => "id", "order_val" => "ASC"],
                ["id" => "1", "label" => "Name (A - Z)", "sort_val" => "name", "order_val" => "ASC"],
                ["id" => "2", "label" => "Name (Z - A)", "sort_val" => "name", "order_val" => "DESC"],
                ["id" => "3", "label" => "Price (Low & High)", "sort_val" => "price", "order_val" => "ASC"],
                ["id" => "4", "label" => "Price (High & Low)", "sort_val" => "price", "order_val" => "DESC"],
                ["id" => "5", "label" => "Rating (Highest)", "sort_val" => "rating", "order_val" => "DESC"],
                ["id" => "6", "label" => "Rating (Lowest)", "sort_val" => "rating", "order_val" => "ASC"],
                // ["id"=> "7","label"=> "Model (A - Z)","sort_val"=> "p.model","order_val"=> "ASC"],
                // ["id"=> "8","label"=> "Model (Z - A)","sort_val"=> "p.model","order_val"=> "DESC"],
            ],
            'home_string' => [
                ["id" => "topbrands", "title" => "Shop By Brand"],
                ["id" => "hot_today_products", "title" => "Hot Today"],
                ["id" => "hot_deal_products", "title" => "Hot Deal"],
                ["id" => "trending_products", "title" => "Trending"],
                ["id" => "best_seller_products", "title" => "Best Seller"],
                ["id" => "new_arrival_products", "title" => "New Arrival"],

            ],
            'information' => [
                ["title" => "About Us", "description" => URL::to('/') . '/aboutus'],
                ["title" => "Terms of Use", "description" => URL::to('/') . '/terms'],
                ["title" => "Privacy", "description" => URL::to('/') . '/privacy'],
                ["title" => "Bank accounts and payment methods", "description" => URL::to('/') . '/bankaccountpayment'],
                ["title" => "Shipping & Delivery", "description" => URL::to('/') . '/shipping'],

            ],
        ],
            ['name' => '????????', 'code' => 'ar', 'language_id' => '2', 'translations' => ["navHome" => "????????????????",
                "navMyAccount" => "??????????",
                "transaction_history" => "?????? ??????????????",
                "no_transaction" => "???? ???????? ??????????????",
                "aboutus" => "?????????????? ??????",
                "size" => "????????????",
                "color" => "??????????",
                "return" => "??????????",
                "order" => "??????",
                "credit_card" => "?????????? ????????????????",
                "sku" => "?????? ????????????",
                "navCategories" => "??????????????",
                "navCart" => "?????? ????????????",
                "readmore" => "???????? ????????",
                "readless" => "???????? ??????",
                "soldout" => "??????",
                "description" => "??????",
                "feature" => "????????",
                "prduct_remove" => "?????? ?????????? ???????????? ?????????? ???? ?????? ????????????",
                "addressdetail" => "???????????? ??????????????",
                "my_wallet" => "????????????",
                "pay" => "????????",
                "addfunds" => "?????????? ??????????????",
                "add" => "????????",
                "product_attribute" => "???????? ????????????",
                "applepay" => "????????",
                "choosemethod" => "???????? ??????????????",
                "select" => "??????????",
                "creditdebit" => "?????????????? ???????????????????? / ??????",
                "voucher" => "?????????? ????????????",
                "availablecredit" => "???????????? ??????????????",
                "addcredit" => "?????????? ????????",
                "reedemvoucher" => "?????????????? ??????????????",
                "address" => "??????????",
                "payment" => "??????",
                "orderplaced" => "?????????? ??????????",
                "placeorder" => "???????? ??????????",
                "navWishlist" => "??????????????",
                "navAboutApp" => "???? ?????? ??????",
                "navProductDetail" => "???????????? ????????????",
                "navNewAddress" => "?????? ?????????? ????????",
                "navEditAddress" => "?????????? ??????????????",
                "navMyCart" => "?????? ????????????",
                "navCheckout" => "?????????? ?????????? ????????????",
                "navShippingInfo" => "?????????????? ??????????",
                "navReturnPolicy" => "?????????? ??????????????",
                "navPrivacyPolicy" => "????????????????",
                "navTermsAndConditions" => "???????????? ????????????????",
                "navAboutUs" => "???? ??????",
                "navMyOrders" => "????????????",
                "maintenanceMessage" => "???????????? ?? ?????????? ?????????? ?????????????? ???????? ???????????? ????????????.\n?????? ???????????????? ?????????? ???? ????????.",
                "email" => "???????????? ????????????????????",
                "signIn" => "?????????? ????????????",
                "continueshopping" => "???????????? ????????????",
                "shippingto" => "?????????? ??????????",
                "order_number" => "?????? ??????????",
                "thanks_for_your_order" => "???????? ??????????!",
                "place_order" => "???????? ??????????",
                "signUp" => "?????????? ????????",
                "skip" => "??????????",
                "continue" => "????????",
                "balance_sr" => "???????????? SR",
                "delivery_address" => "?????????? ??????????????",
                "wallet" => "?????????? ??????",
                "forgotPassword" => "???? ???????? ???????? ??????????????",
                "signInWith" => "???? ?????? ???????????? ???? ????????",
                "noHaveAccount" => "???? ???????? ???????? ???????? ??",
                "haveAccount" => "???????? ??????????",
                "firstName" => "?????????? ??????????",
                "lastName" => "?????????? ????????????",
                "fullName" => "?????????? ??????????????",
                "password" => "???????? ????????????",
                "mobileNumber" => "?????? ????????????",
                "mobileNumberPlaceholder" => "5XXXXXXX",
                "signUpNoteOne" => "???????????????? ???????? ?? ?????? ?????????? ??????",
                "signUpNoteTwo" => "??",
                "termCondition" => "???????? ??????????????????",
                "policies" => "????????",
                "verifyCode" => "????????????",
                "codeNote" => "?????? ?????????????? ?????????? ???? ???????????? ???????? 10 ??????????",
                "codeLabel" => "?????? ????????????",
                "verify" => "??????????",
                "sendCodeAgain" => "?????????? ?????????? ?????? ????????",
                "mobileNumberNote" => "???? ?????????? ?????? ?????????????? ?????????? ???? ??????",
                "oldPassword" => "???????? ???????????? ??????????????",
                "newPassword" => "???????? ???????????? ??????????????",
                "confirmNewPassword" => "?????????? ???????? ???????????? ??????????????",
                "cancel" => "??????????",
                "phoneBlank" => "???????????? ?????? ?????? ????????????",
                "phoneLength" => "?????? ???? ?????????? ?????? ???????????? ???? 7 ?????? 12 ??????",
                "phoneInvalid" => "?????? ???? ?????????? ?????? ???????????? ?????? ?????????? ?????????? ??????",
                "passwordBlank" => "?????? ???????? ????????????",
                "passwordInvalid" => "?????? ???? ???? ?????????? ???????? ???????????? ?????? ????????????",
                "passwordLength" => "?????? ???? ???????? ???????? ???????????? ???????? 6 ??????????",
                "emailInvalid" => "?????? ?????????? ???????? ???????????????? ????????",
                "emailBlank" => "?????? ?????????? ???????? ????????????????",
                "otpCodeInvalid" => "?????? ???? ???????? ?????? ?????????????? ??????????",
                "emptyCode" => "???????????? ?????????? ??????????",
                "settings" => "??????????????????",
                "settingsNote" => "?????????? ??????????????????",
                "addressLabel" => "?????? ??????????????",
                "addressPlaceholder" => "...????????, ???????????? ?? ?????????? ?? ???????? ????????",
                "neighbourHood" => "??????????????",
                "streetName" => "????????/????????????",
                "city" => "??????????????",
                "zipCode" => "?????????? ??????????????",
                "state" => "??????????????",
                "country" => "????????????",
                "saveAddress" => "?????? ??????????????",
                "updateAndSave" => "?????????? ?? ??????",
                "signOut" => "?????????? ????????????",
                "saveUpdate" => "?????? ????????????",
                "ok" => "??????",
                "thankYouRegister" => "?????????? ?????????????? ???????? ???? ?????? ??????. ?????????? ?????? ?????????? ?????? ?????? ???????????? ????????????.",
                "error" => "??????",
                "success" => "??????????",
                "information" => "??????????????",
                "errorMessage" => "?????? ?????? ???? ?? ???????? ???????????? ???????????????? ????????????",
                "download" => "??????????",
                "remindLater" => "?????????????? ????????????",
                "updateAvailable" => "?????????????? ??????????",
                "updateMessage" => "?????????? ?????????? ???????? ??????????????",
                "internalServerError" => "?????? ?????????? ???? ???????????? ?? ???????? ???????????? ???????????????? ????????????",
                "appName" => "?????? ??????",
                "retry" => "?????????? ????????????????",
                "linkUnableToOpen" => "???? ???????? ?????? ????????????",
                "changePhoneNumber" => "?????????? ?????? ????????????",
                "resendOTP" => "?????????? ?????????? ?????? ??????????????",
                "youHave" => "????????",
                "attemptsLeft" => "???????????? ????????????",
                "noAttemptsError" => "???????? 0 ?????????????? ???????????? ?? ???????? ???????????? ???????????????? ?????? 1 ????????",
                "retryAfterThirty" => "?????????? ?????????? ?????? ?????????????? ?????? 30 ??????????",
                "tagLine" => "???????????? ????????????",
                "welcome" => "??????????",
                "hello" => "??????????",
                "viewAll" => "???????? ????????????????",
                "sendLink" => "?????????? ????????????",
                "nameBlank" => "???????????? ?????????? ??????????",
                "nameLength" => "???????? ?????????? ???????????? ???????? ??????????????",
                "nameInvalid" => "?????????? ?????? ????????",
                "updatePassword" => "?????????? ???????? ????????????",
                "passwordUpdateSuccess" => "???? ?????????? ???????? ???????????? ??????????",
                "forgotSuccessMessage" => "???? ?????????? ???????? ?????????? ?????????? ???????? ???????????? ?????? ???????????? ???????????????????? ???????????? ?????????? ????",
                "pleaseWait" => "...???????????? ????????????????",
                "writeYourReview" => "???????? ???????????? ??????",
                "reviewFullName" => "?????????? ??????????????",
                "reviewBlankError" => "???????????? ?????????? ???? ??????????",
                "myOrders" => "My Orders",
                "myAddress" => "??????????????",
                "myWishList" => "????????????",
                "helpSupport" => "???????????????? ?? ??????????",
                "rateApp" => "?????????? ??????????????",
                "shareApp" => "???????? ??????????????",
                "editAddress" => "Edit Address",
                "loadingReviews" => "...???????????? ???????????? ???? ??????????????????",
                "pullRefresh" => "???????? ??????????????",
                "allReviews" => "All Reviews",
                "productUpdateCartMessage" => "???? ?????????? ???????????? ???? ??????????",
                "productMoveToWishlist" => "???? ?????????? ???????????? ??????????????",
                "areUSure" => "???? ?????? ????????????",
                "deleteItemToCartMessage" => "???????? ?????????? ???????????? ???? ??????????",
                "deleteItemToCartAndMoveToWishlist" => "???????? ?????????? ???????????? ???? ?????????? ?? ???????????? ??????????????",
                "item" => "????????",
                "items" => "????????????",
                "noItems" => "???? ???????? ????????????",
                "add" => "??????",
                "freeShipping" => "?????? ??????????",
                "noProductsInCard" => "?????? ???????????? ??????????!",
                "quantity" => "????????????",
                "quantityPrice" => "?????? ????????????",
                "moveToWishList" => "?????? ?????? ??????????????",
                "checkOut" => "?????????? ?????????? ????????????",
                "cartTotal" => "??????????????",
                "remove" => "??????????",
                "requestLinkMessage" => "?????? ???? ???????? ???????? ???????? ?????????? ?????????????? ?????? ????????",
                "resetSuccessPassword" => "???? ?????????? ?????????? ???????? ???????????? ??????????",
                "loginNewPassword" => "???????? ?????????????? ???????? ???????????? ?????????????? ???????????? ????????????",
                "changePassword" => "?????????? ???????? ????????????",
                "updateYourPassword" => "?????? ???????? ????????????",
                "enterNewMobileForOTP" => "?????? ?????? ???????? ???????? ???????????????? ?????? ??????????????",
                "otpSendMessage" => "???? ?????????? ?????? ?????????????? ???????? ???????????? ????????????",
                "editProfile" => "?????????? ?????????? ????????????",
                "updateDetails" => "?????? ????????????????",
                "forgotPasswordTitle" => "?????????????? ???????? ????????????",
                "getCodeMessage" => "?????? ?????????? ???????????????????? ???????????? ???????????? ?????? ??????????",
                "productAddWishlist" => "???? ?????????? ???????????? ??????????????",
                "productRemoveWishlist" => "???? ?????????? ???????????? ???? ??????????????",
                "productAddCard" => "Product added to cart",
                "welcomeBack" => "?????????? ????????????",
                "signInContinue" => "???????????? ?????????? ?????????? ????????????????",
                "deleteAddress" => "???????? ?????? ?????? ??????????????",
                "delete" => "??????",
                "edit" => "??????????",
                "verifyMobile" => "?????????? ?????? ????????????",
                "noAddress" => "???? ?????? ?????????? ???? ?????????? ?? ?????? ???????????? ????????????????",
                "features" => "??????????????????",
                "descriptions" => "????????????????",
                "seeMore" => "???????????? ????????????",
                "writeReview" => "?????????? ??????????",
                "viewAllReview" => "???????????? ???? ??????????????????",
                "similarProdcuts" => "???????????? ?????? ??????",
                "recentlyViewed" => "???????????? ???? ????????????????",
                "outOfStock" => "?????? ??????????",
                "addToCart" => "?????? ?????? ??????????",
                "buyNow" => "???????????? ????????",
                "sortBy" => "??????????",
                "filters" => "??????????????????",
                "loadingMoreProducts" => "...???????????? ???????????? ???? ????????????????",
                "emptyWishlist" => "???? ???????? ?????????? ???? ????????????!",
                "changeLanguage" => "?????????? ??????????",
                "createAccount" => "?????????? ????????",
                "justAStep" => "???????? ?????????? ????????????",
                "submit" => "??????????",
                "addImages" => "?????? ????????",
                "yourReview" => "????????????",
                "openGallery" => "??????????",
                "openCamera" => "????????????????",
                "pickImage" => "???????? ????????",
                "apply" => "??????????",
                "clear" => "??????",
                "filterBy" => "?????????? ??????",
                "mobile" => "?????? ????????????",
                "verified" => "???? ????????????",
                "yes" => "??????",
                "no" => "????",
                "reviews" => "??????????????????",
                "toGet" => "???????????? ??????",
                "youAreEligible" => "?????? ???????? ????????",
                "done" => "Done",
                "ratingCountError" => "???????????? ?????????? ??????????",
                "goShopping" => "???????? ????????????",
                "only" => "??????????",
                "left" => "??????",
                "chooseColor" => "??????????",
                "chooseSize" => "????????????",
                "couldNotAuthenticate" => "???? ???????? ???????????? ???????? ?????????? ??????????????",
                "chooseDeliveryAddress" => "?????????? ??????????????",
                "paymentMethodTitle" => "?????????? ??????????",
                "off" => "??????",
                "all" => "????????",
                "addItems" => "?????? ????????",
                "yourName" => "????????",
                "addLocation" => "?????? ????????",
                "dragMap" => "???????? ?????????????? ???????????? ??????????????",
                "fetchAgain" => "?????? ?????? ????????",
                "waitingForLocation" => "???????????? ?????????? ????????????",
                "addressLoading" => "...??????????",
                "locationDisabled" => "?????????? ?????????? ???????????? ????????",
                "locationDisabledMessage" => "?????? ???????????? ???????????? ?????????? ???????????? ??????????????",
                "noProductsFound" => "???? ???????? ????????????",
                "couponCode" => "?????? ??????????",
                "couponCodePlaceholder" => "?????? ?????? ?????????? ??????",
                "giftCode" => "?????? ????????????",
                "giftCodePlaceholder" => "?????? ?????? ???????????? ??????",
                "applied" => "????????",
                "applyString" => "??????????",
                "choose" => "????????????",
                "total" => "???????????? ??????????",
                "shippingQuery" => "??????????",
                "optionsError" => "???????????? ?????????? ???? ????????????????????",
                "navOrderDetails" => "???????????? ??????????",
                "productAddCart" => "???? ?????????? ???????????? ???????? ????????????",
                "followUsOn" => "???????????? ??????",
                "countryInvalid" => "???????????? ???????????????? ?????? ??????????",
                "stateInvalid" => "?????????????? ???????????????? ?????? ??????????",
                "searchOnMap" => "???????? ???????? ???? ??????????????",
                "errCouldNotGetProductDetail" => "?????? ???????? ?????? ?????? ???????????? ????????????",
                "categoryAll" => "????????",
                "shippingMethodTitle" => "?????????? ??????????",
                "orderSummery" => "???????? ??????????",
                "confirmOrder" => "?????????? ??????????",
                "errOrderDetail" => "?????? ???????? ?????? ?????? ??????????????????",
                "orderID" => "?????? ??????????",
                "dateAddedLabel" => "???? ?????????? ??????????",
                "dateStatusLabel" => "??????????",
                "trackOrder" => "???????? ??????????",
                "cancelOrder" => "?????????? ??????????",
                "orderShippingTitle" => "?????????? ??????????",
                "orderShippingAddress" => "?????????? ??????????",
                "noOrders" => "???? ???????? ??????????",
                "loadingOrders" => "?????????? ???????????? ???? ?????????????? ...",
                "inStock" => "??????????",
                "warning" => "??????????",
                "appRestart" => "???????? ?????????? ?????????? ?????????????? ????????",
                "enterRemainingDetails" => "???????????? ?????????? ???????? ???????????????? ????????????????",
                "rateProduct" => "?????? ????????????",
                "navWriteReview" => "?????? ????????????",
                "navAllReviews" => "???? ??????????????????",
                "searchPlaceholder" => "?????? ???????????????? ?? ??????????????????",
                "reasonPlaceholder" => "...?????? ?????????? ??????",
                "reasonTextBlank" => "???????????? ?????????? ??????????????",
                "selectLanguage" => "???????????? ??????????",
                "couponcode" => "?????? ??????????????",
                "customerSupportTitle" => "???????? ??????????????",
                "allOrders" => "???? ??????????????",
                "cancellationReason" => "?????? ?????????? ??????????",
                "otherReason" => "????????",
                "orderContactInfo" => "?????????????? ??????????????",
                "orderPaymentMethod" => "?????????? ??????????",
                "order" => "??????",
                "navTrackOrder" => "???????? ??????????",
                "cancelVerifyMessage" => "???????? ?????????? ??????????.\n???? ?????? ?????????????? ???????? ???????????? ???????????? ????.???????????? ???????????? ???? ?????????? ???????????????? ?????? ??????????????????.",
                "productOptions" => "???????????? ????????????",
                "emailNotifLabel" => "?????????????? ???????????? ????????????????????",
                "smsNotifLabel" => "?????????????? ?????????????? ????????????",
                "pushNotifLabel" => "?????????? ??????????????????",
                "notificationTitle" => "??????????????????",
                "notificationSettingCaption" => "?????????? ???? ?????????? ??????????????????",
                "navMyNotifications" => "????????????????",
                "returnOrder" => "?????????? ??????????",
                "cardPaymentError" => "?????????? ?????????? ?????? ?????? ?????????? ????????????",
                "bankTransferInfo" => "???????????? ???????????? ?????????????? ?????????? ??????????????",
                "noNotifications" => "???? ???????? ??????????????",
                "select" => "?????? ????????",
                "loadingNotifications" => "...?????????? ???????????? ???? ??????????????????",
                "seeLess" => "???????????? ????????????",
                "trackingDetailLoadingMessage" => "?????? ???????? ???????? ?????????????? ???????????? ???? ??????????????",
                "placeOrderConfirmMessage" => "???????? ?????????? ???????????? ?????? ??????????",
                "callUs" => "???????? ??????",
                "whatsapp" => "???????????? ????",
                "feedbackEmail" => "???????????? ????????????????????",
                "liveChat" => "???????????? ????????????",
                "englishLabel" => "English",
                "arabicLabel" => "??????????????",
                "troubleMessage" => "???????????? ?????? ???????? \n ?????? ?????????? ????????????????.\n ?????? ????????????????: ",
                "troubleSubject" => "?????? ?????? ?????? ??????",
                "supportEmailError" => "???? ???????? ?????????? ???????? ????????????????",
                "callingNotSupport" => "?????????? ???? ???????? ??????????????",
                "navReturnOrder" => "?????? ????????",
                "selectAll" => "???????? ????????",
                "removeAll" => "?????????? ????????",
                "returnReasonPlaceholder" => "?????????? ????????",
                "itemsSelectError" => "???????????? ???????? ???????????? ??????????????",
                "returnReasonError" => "???????????? ?????????? ?????? ??????????????",
                "productOpenedError" => "???????????? ???????? ??????????",
                "streetNameBlank" => "???????????? ?????????? ?????? ????????????",
                "neighbourHoodInvalid" => "?????????????? ???????? ???????? ???? ???????? ?????????????? ????",
                "addressLabelInvalid" => "?????????????? ?????? ?????????????? ???????? ???? ???????? ?????????????? ????",
                "zipCodeBlank" => "???????????? ?????????? ?????????? ??????????????",
                "zipCodeInvalid" => "?????????? ?????????????? ?????? ????????",
                "zipCodeLong" => "?????????? ?????????????? ?????? ???? ???????? ???? ?????? 5 ?????? 6 ??????????",
                "countryBlank" => "???????????? ???????? ????????????",
                "stateBlank" => "???????????? ???????? ??????????????",
                "phoneWithoutZero" => "???????????? ?????????? ?????? ???????????? ???????? 0",
                "productIsOpened" => "???? ?????? ????????????",
                "selectItemsReturn" => "???????? ???????? ??????????????",
                "returnReasonTitle" => "?????? ?????????? ??????????",
                "returnID" => "?????? ??????????????",
                "returnComments" => "??????????????",
                "returnedStatus" => "?????????? ????????????",
                "makeDefault" => "?????????? ???????????? ??????????????",
                "noAddressAddedError" => "?????? ???????? ???? ?????????? ???? ???????????? ??????. ?????? ?????????????? ???? ???????? ???? ???????????? ??????????????",
                "additionalItemsWarning" => "???????? ?????????? ???????????? ???? ????????????. ???????? ???????? ???? ???????????? ????????????. ????????????",
                "cod" => "?????????? ?????? ????????????????",
                "bank_transfer" => "?????????????? ??????????????",
                "tap-card" => "?????????????? ????????????????????",
                "tap-mada" => "?????????? ??????"],
                'return_reason' => [
                    ["return_reason_id" => "1", "name" => " ???????????? ?????????? ????????"],
                    ["return_reason_id" => "5", "name" => " ?????????? ???????????? ??????????????"],
                    ["return_reason_id" => "4", "name" => " ???????? ???????? ???????????? ??????????????"],
                    ["return_reason_id" => "3", "name" => " ???????? ????????"],
                    ["return_reason_id" => "2", "name" => " ???????????? ???????? ????????????"],
                ],
                'order_cancel_reasone' => [
                    ["cancel_reasone_id" => "1", "name" => "?????? ????????"],
                    ["return_reason_id" => "2", "name" => "???????????? ??????????"],
                    ["return_reason_id" => "3", "name" => "????????"],
                ],
                'sort_filter' => [
                    ["id" => "0", "label" => "Default", "sort_val" => "id", "order_val" => "ASC"],
                    ["id" => "1", "label" => "?????????? ???? ?? - ??", "sort_val" => "name", "order_val" => "ASC"],
                    ["id" => "2", "label" => "?????????? ???? ?? - ??", "sort_val" => "name", "order_val" => "DESC"],
                    ["id" => "3", "label" => "?????? ?????????? (?????????? > ??????????)", "sort_val" => "price", "order_val" => "ASC"],
                    ["id" => "4", "label" => "?????? ?????????? (?????????? > ??????????)", "sort_val" => "price", "order_val" => "DESC"],
                    ["id" => "5", "label" => "?????????? (??????????)", "sort_val" => "rating", "order_val" => "DESC"],
                    ["id" => "6", "label" => "?????????? (??????????)", "sort_val" => "rating", "order_val" => "ASC"],
                    // ["id"=> "7","label"=> "?????????? (?? - ??)","sort_val"=> "p.model","order_val"=> "ASC"],
                    // ["id"=> "8","label"=> "?????????? (?? - ??)","sort_val"=> "p.model","order_val"=> "DESC"],
                ],
                'home_string' => [
                    ["id" => "topbrands", "title" => "????????????????"],
                    ["id" => "hot_today_products", "title" => " ???????? ??????????"],
                    ["id" => "hot_deal_products", "title" => "???????????? ??????????????"],
                    ["id" => "trending_products", "title" => "???????????? ????????"],
                    ["id" => "best_seller_products", "title" => "???????????? ????????????"],
                    ["id" => "new_arrival_products", "title" => "?????? ????????????"],

                ],
                'information' => [
                    ["title" => "???? ??????", "description" => "description"],
                    ["title" => "???????? ??????????????????", "description" => "description"],
                    ["title" => "????????????????", "description" => "description"],
                    ["title" => "???????????????? ?????????????? ?? ?????? ??????????", "description" => "description"],
                    ["title" => "?????????? ????????????????", "description" => "description"],

                ],
            ]];
        $globalsettings = Global_settings::all();
        $res['facebook'] = 'https://www.facebook.com/Lets-Buy-sa-323803371381882/';
        $res['twitter'] = 'https://www.twitter.com/letsbuy_sa';
        $res['google'] = '';
        $res['youtube'] = 'http://www.lts-buy.com/index.php?route=common/home';
        $res['instagram'] = 'https://www.instagram.com/letsbuy_sa/';
        $res['min_stock_qty'] = '5';
        $res['free_total'] = $globalsettings[0]->min_amount_shipping;
        $res['applepay'] = $globalsettings[0]->applepay;
        if ($request->header('language') == 3) {
            $res['copyright'] = $globalsettings[0]->copyright_en;
        } else {
            $res['copyright'] = $globalsettings[0]->copyright_ar;
        }
        $res['currencies'] = ['name' => '???????? ??????????', 'code' => 'SAR', 'currency_id' => '5', 'symbol_right' => ''];
        $res['success']['response'] = 'Config listing successfully.';

        return response($res);
    }

    public function smssend($mobile)
    {
        $mobile = '966' . $mobile;

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

        return $otp;
    }

    public function category(Request $request)
    {
        if ($request->header('language') == 3) {
            $categories = Category::select('id', 'category_name_en as name', 'header_image_en as header_image', 'footer_image_en as footer_image', 'image_en as image')->with(['subcategories:id,category_id,sub_category_name_en as name,image_en as image'])->get();

            $res['categoryimagepath'] = URL::to('/') . '/public/images/category/';
            $res['subcategoryimagepath'] = URL::to('/') . '/public/images/subcategory/';
            $res['categories'] = $categories;
            $res['success']['message'] = 'Categories listing successfully.';
            return response($res);
        } else {
            $categories = Category::select('id', 'category_name_ar as name', 'header_image_ar as header_image', 'footer_image_ar as footer_image', 'image_ar as image')->with(['subcategories:id,category_id,sub_category_name_ar as name,image_ar as image'])->get();

            $res['categoryimagepath'] = URL::to('/') . '/public/images/category/';
            $res['subcategoryimagepath'] = URL::to('/') . '/public/images/subcategory/';
            $res['categories'] = $categories;
            $res['success']['message'] = '?????????? ???????????? ??????????.';
            return response($res);
        }
    }

    public function wishlist(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'customer_id.required' => "Please Enter Customer ID",
            ];
        } else {
            $messages = [
                'customer_id.required' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = [
            'customer_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        if ($request->header('language') == 3) {
            $products = Wishlist::select('products.id as product_id', 'products.img as thumb', 'products.name_en as name', 'products.stock_availabity as stock_availabity', 'products.price as price', 'products.offer_price as special', 'products.description_en as description', 'products.discount_available as percentage', 'products.quantity as quantity', 'wishlist.status as wishlist', DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id) as optionid"))
                ->leftjoin('products', 'products.id', '=', 'wishlist.product_id')
                ->where('wishlist.user_id', $request->customer_id)->get();
        } else {
            $products = Wishlist::select('products.id as product_id', 'products.img as thumb', 'products.name_ar as name', 'products.stock_availabity as stock_availabity', 'products.price as price', 'products.offer_price as special', 'products.description_ar as description', 'products.discount_available as percentage', 'products.quantity as quantity', 'wishlist.status as wishlist', DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id) as optionid"))
                ->leftjoin('products', 'products.id', '=', 'wishlist.product_id')
                ->where('wishlist.user_id', $request->customer_id)->get();
        }

        $res['status'] = 1;
        $res['imagepath'] = URL::to('/') . '/public/product_images/';
        if ($request->header('language') == 3) {
            $res['success']['message'] = "Wishlist listing Products successfully";
        } else {
            $res['success']['message'] = "???? ?????????? ???????????? ?????????????? ??????????";
        }
        $res['product'] = $products;
        return response($res);
    }

    public function wishlist_add(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'customer_id.required' => "Please Enter Customer ID",
                'product_id.required' => "Please Enter Product ID",

            ];
        } else {
            $messages = [
                'customer_id.required' => "???????????? ?????????? ???????? ????????????",
                'product_id.required' => "???????????? ?????????? ???????? ????????????",

            ];
        }

        $rules = [
            'customer_id' => 'required',
            'product_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $wishlistdata = Wishlist::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->first();
        $product = Product::select('*')->where('id', $request->product_id)->first();

        if (empty($wishlistdata)) {
            $addwishlist = new Wishlist;
            $addwishlist->category_id = $product->category_id;
            $addwishlist->subcategory_id = $product->subcategory_id;
            $addwishlist->user_id = $request->customer_id;
            $addwishlist->price = $request->price;
            $addwishlist->product_id = $request->product_id;

            $addwishlist->save();

            $wishlist_count = wishlist::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->count();

            $res['status'] = 1;
            $res['wishlist_count'] = $wishlist_count;
            $res['wishlist'] = true;

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Wishlist Added successfully";
            } else {
                $res['success']['message'] = "?????? ?????????? ?????????? ?????????????? ??????????";
            }

            return response($res);
        } else {
            wishlist::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->delete();
            $wishlist_count = wishlist::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->count();

            $res['status'] = 1;
            $res['wishlist_count'] = $wishlist_count;
            $res['wishlist'] = false;

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Wishlist removed successfully";
            } else {
                $res['success']['message'] = "?????? ?????????? ?????????? ?????????????? ??????????";
            }
            return response($res);
        }
    }

    public function logout(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'customer_id.required' => "Please Enter Customer ID",
            ];
        } else {
            $messages = [
                'customer_id.required' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = [
            'customer_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        User::where('id', $request->customer_id)->update(['device_id' => null, 'device_token' => null, 'device_type' => null]);

        $res['status'] = 1;
        if ($request->header('language') == 3) {
            $res['success']['message'] = "Logout successfully";
        } else {
            $res['success']['message'] = "?????????? ???????????? ??????????";
        }
        return response($res);
    }

    public function product_review_listing(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'product_id.required' => "Please Enter Product ID",
            ];
        } else {
            $messages = [
                'product_id.required' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = [
            'product_id' => 'required',
        ];

        $page = $request['page'] ? $request['page'] : 1;
        $page = $page - 1;

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $reviews = DB::table('product_reviews')->where('product_id', $request->product_id)->skip($page * 10)->take(10)->get();
        $reviewscount = DB::table('product_reviews')->where('product_id', $request->product_id)->count();
        $total_pages = ceil($reviewscount / 10);

        $product_reviews = array();

        foreach ($reviews as $key => $review) {
            $product_reviews[$key]['rating'] = $review->rating;
            $product_reviews[$key]['date'] = $review->created_at;
            $product_reviews[$key]['text'] = $review->review;
            $product_reviews[$key]['author'] = $review->name;

            $data = explode(',', $review->images);

            $product_reviews[$key]['images'] = $data;
        }

        $res['status'] = 1;
        $res['total_pages'] = $total_pages;

        if ($request->header('language') == 3) {
            $res['success']['message'] = "Review listing successfully";
        } else {
            $res['success']['message'] = "???????????? ?????????????? ??????????";
        }
        $res['reviews'] = $product_reviews;

        return response($res);
    }

    public function product_review_add(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'product_id.required' => "Please Enter Product ID",
                'customer_id.required' => "Please Enter Customer ID",
                'name.required' => "Please Enter Name",
                'rating.required' => "Please Enter Rating",
                'text.required' => "Please Enter Review",

            ];
        } else {
            $messages = [
                'product_id.required' => "???????????? ?????????? ???????? ????????????",
                'customer_id.required' => "???????????? ?????????? ???????? ????????????",
                'name.required' => "???????????? ?????????? ??????????",
                'rating.required' => "???????????? ?????????? ??????????????",
                'text.required' => "???????????? ?????????? ????????????",
            ];
        }

        $rules = [
            'product_id' => 'required',
            'customer_id' => 'required',
            'name' => 'required',
            'rating' => 'required',
            'text' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $product_reviews = Product_reviews::where('product_id', $request->product_id)->where('user_id', $request->customer_id)->first();
        if (empty($product_reviews)) {
            $addproduct = new Product_reviews;
            $addproduct->product_id = $request->product_id;
            $addproduct->user_id = $request->customer_id;
            $addproduct->name = $request->name;
            $addproduct->rating = $request->rating;
            $addproduct->review = $request->text;

            $review_images = "";

            $t_image_name = array();
            if (!empty($request['image'])) {
                foreach ($request['image'] as $t_key => $t_value) {
                    $t_file_name = $t_value->getClientOriginalName();
                    $t_value->move(public_path() . '/reviewcomment', $t_file_name);
                    array_push($t_image_name, $t_file_name);
                }
            }

            if (!empty($t_image_name)) {
                $review_images = implode(',', $t_image_name);
            }
            $addproduct->images = $review_images;
            $addproduct->save();

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Thank you for your review. It has been submitted to the webmaster for approval!";
            } else {
                $res['success']['message'] = "???????? ?????? ????????????????. ???? ???????????? ?????? ?????????? ???????????? ???????????????? ????????";
            }

            return response($res);
        } else {
            $addproduct = Product_reviews::find($product_reviews->id);
            $addproduct->product_id = $request->product_id;
            $addproduct->user_id = $request->customer_id;
            $addproduct->name = $request->name;
            $addproduct->rating = $request->rating;
            $addproduct->review = $request->text;

            $review_images = "";

            $t_image_name = array();
            if (!empty($request['image'])) {
                foreach ($request['image'] as $t_key => $t_value) {
                    $t_file_name = $t_value->getClientOriginalName();
                    $t_value->move(public_path() . '/reviewcomment', $t_file_name);
                    array_push($t_image_name, $t_file_name);
                }
            }

            if (!empty($t_image_name)) {
                $review_images = implode(',', $t_image_name);
            }
            $addproduct->images = $review_images;
            $addproduct->save();

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Thank you for your review. It has been submitted to the webmaster for approval!";
            } else {
                $res['success']['message'] = "???????? ?????? ????????????????. ???? ???????????? ?????? ?????????? ???????????? ???????????????? ????????";
            }

            return response($res);
        }
    }

    public function profile_update(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'customer_id.required' => "Please Enter Customer ID",

                'name.required' => "Please Enter Name",

                'telephone.unique' => "Mobile Number Already Exists",
                'telephone.required' => "Please Enter Mobile Number",
                'telephone.min' => "Mobile Number Must Have Minimum 7 Characters",
                'telephone.max' => "Mobile Number Must Be Less Than 12 Characters",

            ];
        } else {
            $messages = [
                'customer_id.required' => "???????????? ?????????? ???????? ????????????",

                'fullname.required' => "???????????? ?????????? ??????????",

                'telephone.unique' => "???? ?????? ???????????? ????????????",
                'telephone.required' => "???????????? ?????????? ?????? ???????????? ??????????????",
                'telephone.min' => "?????? ?????? ?????? ?????? ???????????? ?????????????? ???? 7 ????????",
                'telephone.max' => "?????? ???? ???????? ?????? ???????????? ?????????????? ?????? ???? 12 ??????????",

            ];
        }

        $id = $request->customer_id;

        $rules = [
            'customer_id' => 'required',
            'fullname' => 'required',
            'telephone' => 'required|min:7|max:12|unique:users,mobile,' . $id,
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        User::select('*')->where('id', $id)->update(['name' => $request->fullname, 'mobile' => $request->telephone]);

        $user = User::select('*')->where('id', $id)->first();

        $res['status'] = 1;
        if ($request->header('language') == 3) {
            $res['success']['message'] = "Profile updated successfully.";
        } else {
            $res['success']['message'] = "???? ?????????? ?????????? ???????????? ??????????.";
        }
        $res['user'] = ['customer_id' => $user['id'], 'email' => $user['email'], 'full_name' => $user['name'], 'telephone' => $user['mobile'], 'notification_push' => ($user['notification_push'] == 1) ? true : false, 'telephone_status' => ($user['otp_verify'] == 1) ? 'verified' : 'not_verified', 'notification_sms' => ($user['notification_sms'] == 1) ? true : false, 'notification_email' => ($user['notification_email'] == 1) ? true : false, 'social_login' => false];
        return response($res);
    }

    public function profile_notification(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'customer_id.required' => "Please Enter Customer ID",
                'notification_email.required' => "Please Enter Email Notification",
                'notification_sms.required' => "Please Enter Sms Notification",
                'notification_push.required' => "Please Enter Push Notification",
            ];
        } else {
            $messages = [
                'customer_id.required' => "???????????? ?????????? ???????? ????????????",
                'notification_email.required' => "???????????? ?????????? ?????????? ???????????? ????????????????????",
                'notification_sms.required' => "???????????? ?????????? ?????????? ?????????????? ??????????????",
                'notification_push.required' => "???????????? ?????????? ?????????? ??????",
            ];
        }

        $id = $request->customer_id;

        $rules = [
            'customer_id' => 'required',
            'notification_email' => 'required',
            'notification_sms' => 'required',
            'notification_push' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        User::select('*')->where('id', $id)->update(['notification_email' => $request->notification_email, 'notification_sms' => $request->notification_sms, 'notification_push' => $request->notification_push]);

        $user = User::select('*')->where('id', $id)->first();

        $res['status'] = 1;
        if ($request->header('language') == 3) {
            $res['success']['message'] = "Notification updated successfully.";
        } else {
            $res['success']['message'] = "???? ?????????? ?????????????? ??????????";
        }

        $res['user'] = ['customer_id' => $user['id'], 'email' => $user['email'], 'full_name' => $user['name'], 'telephone' => $user['mobile'], 'notification_push' => ($user['notification_push'] == 1) ? true : false, 'telephone_status' => ($user['otp_verify'] == 1) ? 'verified' : 'not_verified', 'notification_sms' => ($user['notification_sms'] == 1) ? true : false, 'notification_email' => ($user['notification_email'] == 1) ? true : false, 'social_login' => false];
        return response($res);
    }

    public function country(Request $request)
    {
        $Country = DB::table('country')->select('*')->get();

        $res['status'] = 1;
        if ($request->header('language') == 3) {
            $res['success']['message'] = "Country listing successfully.";
        } else {
            $res['success']['message'] = "?????????? ?????????? ??????????";
        }

        $res['countries'] = $Country;
        return response($res);
    }

    public function state(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'country_id.required' => "Please Enter Country ID",
            ];
        } else {
            $messages = [
                'country_id.required' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $id = $request->country_id;

        $rules = [
            'country_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $states = DB::table('zone')->select('*')->where('country_id', $id)->get();

        $res['status'] = 1;
        if ($request->header('language') == 3) {
            $res['success']['message'] = "State listing successfully.";
        } else {
            $res['success']['message'] = "?????????? ???????????? ??????????";
        }

        $res['states'] = $states;

        return response($res);
    }

    public function address_delete(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'customer_id.required' => "Please Enter Customer ID",
                'address_id.required' => "Please Enter Address ID",

            ];
        } else {
            $messages = [
                'customer_id.required' => "???????????? ?????????? ???????? ????????????",
                'address_id.required' => "???????????? ?????????? ???????? ??????????????",
            ];
        }

        $addressid = $request->address_id;

        $rules = [
            'customer_id' => 'required',
            'address_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $address = DB::table('address')->where('id', $addressid)->where('user_id', $request->customer_id)->first();

        if (empty($address)) {
            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Address not available in our record.";
            } else {
                $res['success']['message'] = "?????????????? ?????? ?????????? ???? ??????????.";
            }

            return response($res);
        } else {
            DB::table('address')->where('id', $addressid)->where('user_id', $request->customer_id)->delete();

            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Address Deleted Successfully.";
            } else {
                $res['success']['message'] = "???? ?????? ?????????????? ??????????.";
            }
            return response($res);
        }
    }

    public function address_update(Request $request)
    {
        if ($request->header('language') == 3) {

            $messages = [
                'customer_id.required' => "Please Enter Customer ID",
                'address_id.required' => "Please Enter Address ID",
                'fullname.required' => "Please Enter FullName",
                'fulladdress.required' => "Please Enter Fulladdress",
                'telephone.required' => "Please Enter Telephone",
                'lat.required' => "Please Enter Latitude",
                'long.required' => "Please Enter Longitude",
                'default.required' => "Please Enter Default",

            ];
        } else {
            $messages = [
                'customer_id.required' => "?????????? ?????????? ???? ?????????? ??????????",
                'address_id.required' => "???????????? ?????????? ??????????????????",
                'fullname.required' => "???????????? ?????????? ?????????? ????????????",
                'fulladdress.required' => "???????????? ?????????? ?????????????? ????????????",
                'telephone.required' => "???????????? ?????????? ????????????????",
                'lat.required' => "???????????? ?????????? ???? ??????????",
                'long.required' => "???????????? ?????????? ???? ??????????",
                'default.required' => "???????????? ?????????? ??????????????????",
            ];
        }

        $rules = [
            'customer_id' => 'required',
            'address_id' => 'required',
            'fullname' => 'required',
            'fulladdress' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'telephone' => 'required',
            'default' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $address = DB::table('address')->where('id', $request->address_id)->where('user_id', $request->customer_id)->first();

        if (empty($address)) {
            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Address not available in our record.";
            } else {
                $res['success']['message'] = "?????????????? ?????? ?????????? ???? ??????????.";
            }

            return response($res);
        } else {
            if ($request->is_default == 1) {
                Address::where('user_id', $request->customer_id)->update(['is_default' => 0]);
            }

            DB::table('address')->where('id', $request->address_id)->where('user_id', $request->customer_id)->update(['fullname' => $request->fullname, 'fulladdress' => $request->fulladdress, 'mobile' => $request->telephone, 'is_default' => $request->default, 'address_details' => $request->address_details, 'long' => $request->long, 'lat' => $request->lat]);

            $address = DB::table('address')->select('id', 'user_id as customer_id', 'fulladdress', 'address_details', 'fullname', 'mobile as telephone', 'lat', 'long')->where('id', $request->address_id)->where('user_id', $request->customer_id)->first();

            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Address Updated Successfully.";
            } else {
                $res['success']['message'] = "???? ?????????? ?????????????? ??????????";
            }
            $res['address'] = $address;
            return response($res);
        }
    }

    public function address_add(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'customer_id.required' => "Please Enter Customer ID",
                'fullname.required' => "Please Enter FullName",
                'fulladdress.required' => "Please Enter Fulladdress",
                'telephone.required' => "Please Enter Telephone",
                'lat.required' => "Please Enter Latitude",
                'long.required' => "Please Enter Longitude",
                'default.required' => "Please Enter Default",

            ];
        } else {
            $messages = [
                'customer_id.required' => "?????????? ?????????? ???? ?????????? ??????????",
                'fullname.required' => "???????????? ?????????? ?????????? ????????????",
                'fulladdress.required' => "???????????? ?????????? ?????????????? ????????????",
                'telephone.required' => "???????????? ?????????? ????????????????",
                'lat.required' => "???????????? ?????????? ???? ??????????",
                'long.required' => "???????????? ?????????? ???? ??????????",
                'default.required' => "???????????? ?????????? ??????????????????",
            ];
        }

        $rules = [
            'customer_id' => 'required',
            'fullname' => 'required',
            'fulladdress' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'telephone' => 'required',
            'default' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        if ($request->is_default == 1) {
            Address::where('user_id', $request->customer_id)->update(['is_default' => 0]);
        }

        $add_address = new Address;
        $add_address->user_id = $request->customer_id;
        $add_address->fullname = $request->fullname;
        $add_address->fulladdress = $request->fulladdress;
        $add_address->mobile = $request->telephone;
        $add_address->is_default = $request->default;
        $add_address->lat = $request->lat;
        $add_address->long = $request->long;
        $add_address->address_details = $request->address_details;

        $add_address->save();

        $address = DB::table('address')->select('id', 'user_id as customer_id', 'fulladdress', 'address_details', 'fullname', 'mobile as telephone', 'lat', 'long')->where('id', $add_address->id)->where('user_id', $request->customer_id)->first();

        $res['status'] = 1;
        if ($request->header('language') == 3) {
            $res['success']['message'] = "Address Added Successfully.";
        } else {
            $res['success']['message'] = "?????? ?????????? ?????????????? ??????????";
        }
        $res['address'] = $address;
        return response($res);
    }

    public function address_send_code(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'customer_id.required' => "Please Enter Customer ID",
                'address_id.required' => "Please Enter Address ID",
            ];
        } else {
            $messages = [
                'customer_id.required' => "???????????? ?????????? ???????? ????????????",
                'address_id.required' => "???????????? ?????????? ???????? ??????????????",
            ];
        }

        $rules = [
            'customer_id' => 'required',
            'address_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $address_data = DB::table('address')->select('id', 'user_id as customer_id', 'fulladdress', 'address_details', 'fullname', 'mobile as telephone', 'lat', 'long')->where('id', $request->address_id)->where('user_id', $request->customer_id)->first();

        if (empty($address_data)) {
            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Address not available in our record.";
            } else {
                $res['success']['message'] = "?????????????? ?????? ?????????? ???? ??????????.";
            }

            return response($res);
        } else {
            $otp = $this->smssend($address_data->telephone);

            Address::select("*")->where('user_id', $request->customer_id)->where('id', $request->address_id)->update(['otp' => $otp]);

            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "OTP sent successfully.";
            } else {
                $res['success']['message'] = "???? ?????????? OTP ??????????.";
            }
            return response($res);
        }
    }

    public function address_verify_code(Request $request)
    {
        $user_id = $request['customer_id'];
        $address_id = $request['address_id'];
        $otp = $request['code'];

        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'code' => "Please Enter Code",
                'address_id.required' => "Please Enter Address ID",

            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'code' => "???????????? ?????????? ??????????",
                'address_id.required' => "???????????? ?????????? ???????? ??????????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'code' => 'required', 'address_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $address_data = Address::find($address_id);
        if (!empty($address_data) && !empty($otp)) {
            if (($address_data->otp == $request['code'])) {
                $address_data->otp_verify = 1;
                $address_data->otp = null;
                $address_data->save();

                $res['status'] = 1;
                if ($request->header('language') == 3) {
                    $res['success']['message'] = "Address Verified Successfully.";
                } else {
                    $res['success']['message'] = "???? ???????????? ???? ???????????? ??????????.";
                }

                return response($res);
            } else {
                $res['status'] = 0;
                if ($request->header('language') == 3) {
                    $res['error']['message'] = "Invalid OTP";
                } else {
                    $res['error']['message'] = "OTP ?????? ????????";
                }
                return response($res);
            }
        } else {
            $res['status'] = 0;
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Invalid User";
            } else {
                $res['error']['message'] = "???????????? ?????? ????????";
            }
            return response($res);
        }
    }

    public function address(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'customer_id.required' => "Please Enter Customer ID",

            ];
        } else {
            $messages = [
                'customer_id.required' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $addressid = $request->address_id;

        $rules = [
            'customer_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $address = DB::table('address')->where('user_id', $request->customer_id)->first();

        if (empty($address)) {
            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Address not available in our record.";
            } else {
                $res['success']['message'] = "?????????????? ?????? ?????????? ???? ??????????.";
            }

            return response($res);
        } else {
            $address = DB::table('address')->select('id', 'user_id as customer_id', 'fulladdress', 'address_details', 'fullname', 'mobile as telephone', 'lat', 'long')->where('user_id', $request->customer_id)->orderby('id', 'desc')->get();

            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Address listing successfully.";
            } else {
                $res['success']['message'] = "?????????? ?????????????? ??????????";
            }
            $res['address'] = $address;
            return response($res);
        }
    }

    public function product(Request $request)
    {
        $page = $request['page'] ? $request['page'] : 1;
        $page = $page - 1;
        $search = $request->search;
        $customer_id = $request['customer_id'] ? $request['customer_id'] : 0;
        $brand_id = $request['brand_id'] ? $request['brand_id'] : 0;
        $type = $request->type;
        $order = $request->order ? $request->order : "ASC";
        $sort = $request->sort ? $request->sort : "id";
        $filter = $request->filter;

        if ($request->header('language') == 3) {
            if ($sort == 'name') {
                $sort = 'name_en';
            }
        } else {
            if ($sort == 'name') {
                $sort = 'name_ar';
            }
        }

        $new_arrival_products = DB::table('products')->select('*')->where('status', 1)->orderby('id', 'desc')->take(10)->get();

        $bestsellproducts = DB::table('order_details')->select('product_id')->groupby('product_id')->get();
        $mostviewedproducts = Mostviewedproduct::select('product_id', DB::raw('COUNT(product_id)  as count'))
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->having('count', '>', 10)
            ->get();

        $bestsellid = array();
        if (!empty($bestsellproducts)) {
            foreach ($bestsellproducts as $productsid) {
                array_push($bestsellid, $productsid->product_id);
            }
        }

        $mostview = array();
        if (!empty($mostviewedproducts)) {
            foreach ($mostviewedproducts as $most) {
                array_push($mostview, $most->product_id);
            }
        }

        $hot_today_products = DB::table('products')->select('*')->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->take(20)->get();
        $hot_deal_products = DB::table('products')->select('*')->where('status', 1)->where('discount_available', '!=', 0)->orderBy('id', 'desc')->take(20)->get();
        $best_seller_products = DB::table('products')->select('*')->where('status', 1)->whereIN('id', $bestsellid)->orderBy('id', 'desc')->take(20)->get();

        $trending_products = DB::table('products')->select('*')->where('status', 1)->whereIN('id', $mostview)->orderBy('id', 'desc')->take(20)->get();

        if (!empty($brand_id)) {
            if ($request->header('language') == 3) {
                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('brand_id', $brand_id)->get();
            } else {
                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('brand_id', $brand_id)->get();
            }

            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('brand_id', $brand_id)->get();

        } else {
            if (!empty($request->category_id)) {
                if (empty($filter)) {
                    if (empty($search) && empty($type) && empty($filter)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->whereRaw('FIND_IN_SET(' . $request->category_id . ',sub_category_id)')->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->whereRaw('FIND_IN_SET(' . $request->category_id . ',sub_category_id)')->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->whereRaw('FIND_IN_SET(' . $request->category_id . ',sub_category_id)')->where('status', 1)->get();
                    }

                    if (!empty($search)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_en', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_ar', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('sub_category_id', $request->category_id)->where('status', 1)->get();
                    }

                    if (!empty($search) && !empty($order)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_en', 'like', '%' . $search . '%')->whereRaw('FIND_IN_SET(' . $request->category_id . ',sub_category_id)')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_ar', 'like', '%' . $search . '%')->whereRaw('FIND_IN_SET(' . $request->category_id . ',sub_category_id)')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->whereRaw('FIND_IN_SET(' . $request->category_id . ',sub_category_id)')->where('status', 1)->get();
                    }

                    if (!empty($search) && !empty($order) && empty($type)) {
                        $new_arrival_products = DB::table('products')->select('*')->where('status', 1)->orderby('id', 'desc')->take(10)->get();

                        $bestsellproducts = DB::table('order_details')->select('product_id')->groupby('product_id')->get();
                        $mostviewedproducts = Mostviewedproduct::select('product_id', DB::raw('COUNT(product_id)  as count'))
                            ->groupBy('product_id')
                            ->orderBy('count', 'desc')
                            ->having('count', '>', 10)
                            ->get();

                        $bestsellid = array();
                        if (!empty($bestsellproducts)) {
                            foreach ($bestsellproducts as $productsid) {
                                array_push($bestsellid, $productsid->product_id);
                            }
                        }

                        $mostview = array();
                        if (!empty($mostviewedproducts)) {
                            foreach ($mostviewedproducts as $most) {
                                array_push($mostview, $most->product_id);
                            }
                        }

                        if ($type == 'hot_today_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_en', 'like', '%' . $search . '%')->where('discount_available', '!=', 0)->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_ar', 'like', '%' . $search . '%')->where('discount_available', '!=', 0)->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->get();
                        }

                        if ($type == 'recently_viewed_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.id', 'desc')
                                ->where('user_id', $customer_id)
                                ->groupby('most_product_viewed.product_id')
                                ->get();
                        }

                        if ($type == 'new_arrival_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('status', 1)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('status', 1)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->get();
                        }

                        if ($type == 'topbrands') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('brand_id', $brand_id)->get();
                        }

                        if ($type == 'trending_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.count', 'desc')
                                ->whereNull('user_id')
                                ->groupby('most_product_viewed.product_id')
                                ->get();

                        }

                        if ($type == 'best_seller_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('order_details')
                                ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                ->orderby('total', 'desc')
                                ->groupby('order_details.product_id')
                                ->get();
                        }

                        if ($type == 'hot_deal_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->orderBy($sort, $order)->get();
                        }
                    }

                    if (!empty($order) && !empty($type)) {
                        $new_arrival_products = DB::table('products')->select('*')->where('status', 1)->orderby('id', 'desc')->take(10)->get();

                        $bestsellproducts = DB::table('order_details')->select('product_id')->groupby('product_id')->get();
                        $mostviewedproducts = Mostviewedproduct::select('product_id', DB::raw('COUNT(product_id)  as count'))
                            ->groupBy('product_id')
                            ->orderBy('count', 'desc')
                            ->having('count', '>', 10)
                            ->get();

                        $bestsellid = array();
                        if (!empty($bestsellproducts)) {
                            foreach ($bestsellproducts as $productsid) {
                                array_push($bestsellid, $productsid->product_id);
                            }
                        }

                        $mostview = array();
                        if (!empty($mostviewedproducts)) {
                            foreach ($mostviewedproducts as $most) {
                                array_push($mostview, $most->product_id);
                            }
                        }

                        if ($type == 'hot_today_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->get();
                        }

                        if ($type == 'recently_viewed_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.id', 'desc')
                                ->where('user_id', $customer_id)
                                ->groupby('most_product_viewed.product_id')
                                ->get();
                        }

                        if ($type == 'new_arrival_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->get();
                        }

                        if ($type == 'topbrands') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('brand_id', $brand_id)->get();
                        }

                        if ($type == 'trending_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.count', 'desc')
                                ->whereNull('user_id')
                                ->groupby('most_product_viewed.product_id')
                                ->get();

                        }

                        if ($type == 'best_seller_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('order_details')
                                ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                ->orderby('total', 'desc')
                                ->groupby('order_details.product_id')
                                ->get();

                        }

                        if ($type == 'hot_deal_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->where('sub_category_id', $request->category_id)->orderBy($sort, $order)->get();
                        }
                    }
                } else {
                    $filterdata = DB::table('filter_values')->select('filter_name_en as min', 'filter_name_ar as max')->where('id', $filter)->first();
                    $min = (int) number_format($filterdata->min);
                    $max = (int) number_format($filterdata->max);

                    if (empty($search) && empty($type)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('price', '>', $min)->where('price', '<', $max)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->whereBetween('price', [$min, $max])->get();
                    }

                    if (!empty($search)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->where('name_en', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->where('name_ar', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->whereBetween('price', [$min, $max])->where('status', 1)->get();
                    }

                    if (!empty($search) && !empty($order)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->whereBetween('price', [$min, $max])->where('status', 1)->where('name_en', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->where('name_ar', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->whereBetween('price', [$min, $max])->where('status', 1)->get();
                    }

                    if (!empty($search) && !empty($order) && empty($type)) {
                        $new_arrival_products = DB::table('products')->select('*')->where('status', 1)->orderby('id', 'desc')->take(10)->get();

                        $bestsellproducts = DB::table('order_details')->select('product_id')->groupby('product_id')->get();
                        $mostviewedproducts = Mostviewedproduct::select('product_id', DB::raw('COUNT(product_id)  as count'))
                            ->groupBy('product_id')
                            ->orderBy('count', 'desc')
                            ->having('count', '>', 10)
                            ->get();

                        $bestsellid = array();
                        if (!empty($bestsellproducts)) {
                            foreach ($bestsellproducts as $productsid) {
                                array_push($bestsellid, $productsid->product_id);
                            }
                        }

                        $mostview = array();
                        if (!empty($mostviewedproducts)) {
                            foreach ($mostviewedproducts as $most) {
                                array_push($mostview, $most->product_id);
                            }
                        }

                        if ($type == 'hot_today_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_en', 'like', '%' . $search . '%')->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_ar', 'like', '%' . $search . '%')->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->get();
                        }

                        if ($type == 'recently_viewed_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.id', 'desc')
                                ->where('user_id', $customer_id)
                                ->groupby('most_product_viewed.product_id')
                                ->get();
                        }

                        if ($type == 'new_arrival_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('status', 1)->whereBetween('price', [$min, $max])->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('status', 1)->whereBetween('price', [$min, $max])->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->whereBetween('price', [$min, $max])->where('status', 1)->get();
                        }

                        if ($type == 'topbrands') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('brand_id', $brand_id)->get();
                        }

                        if ($type == 'trending_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.count', 'desc')
                                ->whereNull('user_id')
                                ->groupby('most_product_viewed.product_id')
                                ->get();

                        }

                        if ($type == 'best_seller_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('order_details')
                                ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                ->orderby('total', 'desc')
                                ->groupby('order_details.product_id')
                                ->get();
                        }

                        if ($type == 'hot_deal_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('status', 1)->whereBetween('price', [$min, $max])->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('status', 1)->whereBetween('price', [$min, $max])->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderBy($sort, $order)->get();
                        }
                    }

                    if (!empty($order) && !empty($type)) {
                        $new_arrival_products = DB::table('products')->select('*')->where('status', 1)->orderby('id', 'desc')->take(10)->get();

                        $bestsellproducts = DB::table('order_details')->select('product_id')->groupby('product_id')->get();
                        $mostviewedproducts = Mostviewedproduct::select('product_id', DB::raw('COUNT(product_id)  as count'))
                            ->groupBy('product_id')
                            ->orderBy('count', 'desc')
                            ->having('count', '>', 10)
                            ->get();

                        $bestsellid = array();
                        if (!empty($bestsellproducts)) {
                            foreach ($bestsellproducts as $productsid) {
                                array_push($bestsellid, $productsid->product_id);
                            }
                        }

                        $mostview = array();
                        if (!empty($mostviewedproducts)) {
                            foreach ($mostviewedproducts as $most) {
                                array_push($mostview, $most->product_id);
                            }
                        }

                        if ($type == 'hot_today_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->get();
                        }

                        if ($type == 'new_arrival_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->whereBetween('price', [$min, $max])->where('status', 1)->get();
                        }

                        if ($type == 'recently_viewed_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.id', 'desc')
                                ->where('user_id', $customer_id)
                                ->groupby('most_product_viewed.product_id')
                                ->get();
                        }

                        if ($type == 'trending_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.count', 'desc')
                                ->whereNull('user_id')
                                ->groupby('most_product_viewed.product_id')
                                ->get();

                        }

                        if ($type == 'topbrands') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('brand_id', $brand_id)->get();
                        }

                        if ($type == 'best_seller_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('order_details')
                                ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                ->orderby('total', 'desc')
                                ->groupby('order_details.product_id')
                                ->get();

                        }

                        if ($type == 'hot_deal_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->whereBetween('price', [$min, $max])->orderBy($sort, $order)->get();
                        }
                    }
                }

            } else {
                if (empty($filter)) {
                    if (empty($search) && empty($type) && empty($filter)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->get();
                    }

                    if (!empty($search)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_en', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_ar', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->get();
                    }

                    if (!empty($search) && !empty($order)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_en', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_ar', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->get();
                    }

                    if (!empty($search) && !empty($order) && empty($type)) {
                        $new_arrival_products = DB::table('products')->select('*')->where('status', 1)->orderby('id', 'desc')->take(10)->get();

                        $bestsellproducts = DB::table('order_details')->select('product_id')->groupby('product_id')->get();
                        $mostviewedproducts = Mostviewedproduct::select('product_id', DB::raw('COUNT(product_id)  as count'))
                            ->groupBy('product_id')
                            ->orderBy('count', 'desc')
                            ->having('count', '>', 10)
                            ->get();

                        $bestsellid = array();
                        if (!empty($bestsellproducts)) {
                            foreach ($bestsellproducts as $productsid) {
                                array_push($bestsellid, $productsid->product_id);
                            }
                        }

                        $mostview = array();
                        if (!empty($mostviewedproducts)) {
                            foreach ($mostviewedproducts as $most) {
                                array_push($mostview, $most->product_id);
                            }
                        }

                        if ($type == 'hot_today_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_en', 'like', '%' . $search . '%')->where('discount_available', '!=', 0)->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_ar', 'like', '%' . $search . '%')->where('discount_available', '!=', 0)->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->get();
                        }

                        if ($type == 'recently_viewed_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.id', 'desc')
                                ->where('user_id', $customer_id)
                                ->groupby('most_product_viewed.product_id')
                                ->get();
                        }

                        if ($type == 'new_arrival_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('status', 1)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('status', 1)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->get();
                        }

                        if ($type == 'topbrands') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('brand_id', $brand_id)->get();
                        }

                        if ($type == 'trending_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->get();
                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.count', 'desc')
                                ->whereNull('user_id')
                                ->groupby('most_product_viewed.product_id')
                                ->get();

                        }

                        if ($type == 'best_seller_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('order_details')
                                ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                ->orderby('total', 'desc')
                                ->groupby('order_details.product_id')
                                ->get();

                        }

                        if ($type == 'hot_deal_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->get();
                        }
                    }

                    if (!empty($order) && !empty($type)) {
                        $new_arrival_products = DB::table('products')->select('*')->where('status', 1)->orderby('id', 'desc')->take(10)->get();

                        $bestsellproducts = DB::table('order_details')->select('product_id')->groupby('product_id')->get();
                        $mostviewedproducts = Mostviewedproduct::select('product_id', DB::raw('COUNT(product_id)  as count'))
                            ->groupBy('product_id')
                            ->orderBy('count', 'desc')
                            ->having('count', '>', 10)
                            ->get();

                        $bestsellid = array();
                        if (!empty($bestsellproducts)) {
                            foreach ($bestsellproducts as $productsid) {
                                array_push($bestsellid, $productsid->product_id);
                            }
                        }

                        $mostview = array();
                        if (!empty($mostviewedproducts)) {
                            foreach ($mostviewedproducts as $most) {
                                array_push($mostview, $most->product_id);
                            }
                        }

                        if ($type == 'hot_today_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->orderByRaw('RAND()')->get();
                        }

                        if ($type == 'recently_viewed_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.id', 'desc')
                                ->where('user_id', $customer_id)
                                ->groupby('most_product_viewed.product_id')
                                ->get();
                        }

                        if ($type == 'new_arrival_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->get();
                        }

                        if ($type == 'topbrands') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('brand_id', $brand_id)->get();
                        }

                        if ($type == 'trending_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.count', 'desc')
                                ->whereNull('user_id')
                                ->groupby('most_product_viewed.product_id')
                                ->get();

                        }

                        if ($type == 'best_seller_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->whereIN('id', $bestsellid)->orderBy($sort, $order)->get();
                        }

                        if ($type == 'hot_deal_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->orderBy($sort, $order)->get();
                        }
                    }
                } else {
                    $filterdata = DB::table('filter_values')->select('filter_name_en as min', 'filter_name_ar as max')->where('id', $filter)->first();
                    $min = (int) number_format($filterdata->min);
                    $max = (int) number_format($filterdata->max);

                    if (empty($search) && empty($type)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('price', '>', $min)->where('price', '<', $max)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->whereBetween('price', [$min, $max])->get();
                    }

                    if (!empty($search)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->where('name_en', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->where('name_ar', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->whereBetween('price', [$min, $max])->where('status', 1)->get();
                    }

                    if (!empty($search) && !empty($order)) {
                        if ($request->header('language') == 3) {
                            $products = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->whereBetween('price', [$min, $max])->where('status', 1)->where('name_en', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        } else {
                            $products = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->where('name_ar', 'like', '%' . $search . '%')->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                        }

                        $productdata = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->whereBetween('price', [$min, $max])->where('status', 1)->get();
                    }

                    if (!empty($search) && !empty($order) && empty($type)) {
                        $new_arrival_products = DB::table('products')->select('*')->where('status', 1)->orderby('id', 'desc')->take(10)->get();

                        $bestsellproducts = DB::table('order_details')->select('product_id')->groupby('product_id')->get();
                        $mostviewedproducts = Mostviewedproduct::select('product_id', DB::raw('COUNT(product_id)  as count'))
                            ->groupBy('product_id')
                            ->orderBy('count', 'desc')
                            ->having('count', '>', 10)
                            ->get();

                        $bestsellid = array();
                        if (!empty($bestsellproducts)) {
                            foreach ($bestsellproducts as $productsid) {
                                array_push($bestsellid, $productsid->product_id);
                            }
                        }

                        $mostview = array();
                        if (!empty($mostviewedproducts)) {
                            foreach ($mostviewedproducts as $most) {
                                array_push($mostview, $most->product_id);
                            }
                        }

                        if ($type == 'hot_today_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_en', 'like', '%' . $search . '%')->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('name_ar', 'like', '%' . $search . '%')->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->get();
                        }

                        if ($type == 'new_arrival_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('status', 1)->whereBetween('price', [$min, $max])->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('status', 1)->whereBetween('price', [$min, $max])->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->whereBetween('price', [$min, $max])->where('status', 1)->get();
                        }

                        if ($type == 'recently_viewed_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.id', 'desc')
                                ->where('user_id', $customer_id)
                                ->groupby('most_product_viewed.product_id')
                                ->get();
                        }

                        if ($type == 'topbrands') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('brand_id', $brand_id)->get();
                        }

                        if ($type == 'trending_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.count', 'desc')
                                ->whereNull('user_id')
                                ->groupby('most_product_viewed.product_id')
                                ->get();

                        }

                        if ($type == 'best_seller_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('order_details')
                                ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                ->orderby('total', 'desc')
                                ->groupby('order_details.product_id')
                                ->get();
                        }

                        if ($type == 'hot_deal_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('status', 1)->whereBetween('price', [$min, $max])->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('status', 1)->whereBetween('price', [$min, $max])->where('discount_available', '!=', 0)->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderBy($sort, $order)->get();
                        }
                    }

                    if (!empty($order) && !empty($type)) {
                        $new_arrival_products = DB::table('products')->select('*')->where('status', 1)->orderby('id', 'desc')->take(10)->get();

                        $bestsellproducts = DB::table('order_details')->select('product_id')->groupby('product_id')->get();
                        $mostviewedproducts = Mostviewedproduct::select('product_id', DB::raw('COUNT(product_id)  as count'))
                            ->groupBy('product_id')
                            ->orderBy('count', 'desc')
                            ->having('count', '>', 10)
                            ->get();

                        $bestsellid = array();
                        if (!empty($bestsellproducts)) {
                            foreach ($bestsellproducts as $productsid) {
                                array_push($bestsellid, $productsid->product_id);
                            }
                        }

                        $mostview = array();
                        if (!empty($mostviewedproducts)) {
                            foreach ($mostviewedproducts as $most) {
                                array_push($mostview, $most->product_id);
                            }
                        }

                        if ($type == 'hot_today_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderByRaw('RAND()')->get();
                        }

                        if ($type == 'recently_viewed_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.id', 'desc')
                                    ->where('user_id', $customer_id)
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.id', 'desc')
                                ->where('user_id', $customer_id)
                                ->groupby('most_product_viewed.product_id')
                                ->get();
                        }

                        if ($type == 'new_arrival_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->whereBetween('price', [$min, $max])->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->whereBetween('price', [$min, $max])->where('status', 1)->get();
                        }

                        if ($type == 'trending_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('most_product_viewed')
                                    ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                    ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                    ->orderby('most_product_viewed.count', 'desc')
                                    ->whereNull('user_id')
                                    ->groupby('most_product_viewed.product_id')
                                    ->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('most_product_viewed')
                                ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))
                                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                                ->orderby('most_product_viewed.count', 'desc')
                                ->whereNull('user_id')
                                ->groupby('most_product_viewed.product_id')
                                ->get();

                        }

                        if ($type == 'topbrands') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('name_en', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('name_ar', 'like', '%' . $search . '%')->where('brand_id', $brand_id)->where('status', 1)->orderby($sort, $order)->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('brand_id', $brand_id)->get();
                        }

                        if ($type == 'best_seller_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            } else {
                                $products = DB::table('order_details')
                                    ->select('products.id', 'products.name_ar as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                    ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                    ->orderby('total', 'desc')
                                    ->groupby('order_details.product_id')
                                    ->skip($page * 10)->take(10)->get();

                            }

                            $productdata = DB::table('order_details')
                                ->select('products.id', 'products.name_en as name', 'products.name_ar', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                                ->leftjoin('products', 'products.id', '=', 'order_details.product_id')
                                ->orderby('total', 'desc')
                                ->groupby('order_details.product_id')
                                ->get();
                        }

                        if ($type == 'hot_deal_products') {
                            if ($request->header('language') == 3) {
                                $products = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            } else {
                                $products = DB::table('products')->select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'))->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderBy('discount_available', 'desc')->skip($page * 10)->take(10)->get();
                            }

                            $productdata = DB::table('products')->select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity', 'stock_availabity', 'discount_available', 'relatedproducts')->where('status', 1)->where('discount_available', '!=', 0)->whereBetween('price', [$min, $max])->orderBy('discount_available', 'desc')->get();
                        }
                    }
                }

            }
        }

        $wishlistcount = Wishlist::where('user_id', $customer_id)->count();
        $cartcount = Cart::where('user_id', $customer_id)->count();

        $total_pages = ceil(count($productdata) / 10);

        $res['status'] = 1;
        $res['product_path'] = URL::to('/') . '/public/product_images/';
        $res['cart_count'] = $cartcount;
        $res['wishlist_count'] = $wishlistcount;
        $res['total_pages'] = $total_pages;
        $res['product'] = $products;

        if ($request->header('language') == 3) {
            $res['success']['message'] = "Product listing successfully";
        } else {
            $res['success']['message'] = "???????????? ?????????????? ??????????";
        }

        return response($res);
    }

    public function product_detail(Request $request)
    {
        $product_id = $request['product_id'];
        $customer_id = $request['customer_id'] ? $request['customer_id'] : 0;

        if ($request->header('language') == 3) {
            $messages = ['product_id' => "Please Enter Proudct ID",
            ];
        } else {
            $messages = ['product_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['product_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $mostdata = Mostviewedproduct::where('product_id', $product_id)->where('user_id', $customer_id)->first();

        if (empty($mostdata)) {
            if (!empty($customer_id)) {
                $viewed = new Mostviewedproduct;
                $viewed->product_id = $product_id;
                $viewed->user_id = $customer_id;
                $viewed->save();

            } else {
                $viewed = new Mostviewedproduct;
                $viewed->product_id = $product_id;
                $viewed->save();
            }

        } else {

            $mostdata = Mostviewedproduct::where('product_id', $product_id)->whereNull('user_id')->first();

            if (!empty($mostdata)) {
                Mostviewedproduct::where('product_id', $product_id)->whereNull('user_id')->update(['count' => $mostdata->count + 1]);
            }
        }

        if ($request->header('language') == 3) {
            $product_details = Product::select('id', 'name_en as name', 'sku_en as sku', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'brand_id', 'description_en as description', 'quantity as qty', 'stock_availabity', 'discount_available', 'relatedproducts', 'delivery_features', DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"))->with(['product_attributes_en', 'product_reviews'])->where('id', $product_id)->get();

            foreach ($product_details as $record) {

                $rating = Product_reviews::select('*', DB::raw('(select avg(rating) from product_reviews)as avgrating'))->where('product_id', $record->id)->where('approved', 1)->first();

                $product_data['id'] = $record->id;
                $product_data['name'] = $record->name;
                $product_data['img'] = $record->img;
                $product_data['img1'] = $record->img1;
                $product_data['img2'] = $record->img2;
                $product_data['img3'] = $record->img3;
                $product_data['img4'] = $record->img4;
                $product_data['brand_id'] = $record->brand_id;
                $product_data['sku'] = $record->sku;
                $product_data['img5'] = $record->img5;
                $product_data['seo_url'] = $record->seo_url;
                $product_data['price'] = $record->price;
                $product_data['offer_price'] = $record->offer_price;
                $product_data['description'] = html_entity_decode($record->description);
                if (!empty($rating)) {
                    $product_data['rating'] = $rating->avgrating;
                } else {
                    $product_data['rating'] = null;
                }
                $product_data['qty'] = $record->qty;
                $product_data['stock_availabity'] = $record->stock_availabity;
                $product_data['discount_available'] = $record->discount_available;
                $product_data['relatedproducts'] = $record->relatedproducts;
                $product_data['delivery_features'] = $record->delivery_features;
                $product_data['brandname'] = $record->brandname;
                $product_data['wishlist'] = $record->wishlist;

            }

            $product_attribute = Product_attribute::select('name_en as name', DB::raw('(select name_en from attributes where attributes.id=product_attribute.attribute_id)as title'))->where('product_id', $product_id)->get();

            $all_recently_products = DB::table('most_product_viewed')
                ->select('most_product_viewed.product_id', 'products.id', 'products.name_en as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                ->orderby('most_product_viewed.id', 'desc')
                ->where('user_id', $customer_id)
                ->groupby('most_product_viewed.product_id')
                ->take(10)->get();

            if (!empty($product_details[0]['delivery_features'])) {
                $product_delivery_id = explode(',', $product_details[0]['delivery_features']);
                $product_delivery_features = Product_delivery_features::select('title_en as title', 'image_en as image')->whereIN('id', $product_delivery_id)->get();
            } else {
                $product_delivery_features = [];
            }

            $option = Option::with(["productoptions1" => function ($q) use ($product_id) {
                $q->where('product_details.product_id', '=', $product_id);
            }])->get();
        } else {
            $product_details = Product::select('id', 'name_ar as name', 'sku_ar as sku', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'brand_id', 'description_ar as description', 'quantity as qty', 'stock_availabity', 'discount_available', 'relatedproducts', 'delivery_features', DB::raw('(select name_ar from brands where brands.id=products.brand_id)as brandname'), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"))->with(['product_attributes_ar', 'product_reviews'])->where('id', $product_id)->get();

            foreach ($product_details as $record) {

                $rating = Product_reviews::select('*', DB::raw('(select avg(rating) from product_reviews)as avgrating'))->where('product_id', $record->id)->where('approved', 1)->first();

                $product_data['id'] = $record->id;
                $product_data['name'] = $record->name;
                $product_data['img'] = $record->img;
                $product_data['img1'] = $record->img1;
                $product_data['img2'] = $record->img2;
                $product_data['img3'] = $record->img3;
                $product_data['img4'] = $record->img4;
                $product_data['img5'] = $record->img5;
                $product_data['sku'] = $record->sku;
                $product_data['brand_id'] = $record->brand_id;
                $product_data['seo_url'] = $record->seo_url;
                $product_data['price'] = $record->price;
                $product_data['offer_price'] = $record->offer_price;
                $product_data['description'] = html_entity_decode($record->description);
                if (!empty($rating)) {
                    $product_data['rating'] = $rating->avgrating;
                } else {
                    $product_data['rating'] = null;
                }
                $product_data['qty'] = $record->qty;
                $product_data['stock_availabity'] = $record->stock_availabity;
                $product_data['discount_available'] = $record->discount_available;
                $product_data['relatedproducts'] = $record->relatedproducts;
                $product_data['delivery_features'] = $record->delivery_features;
                $product_data['brandname'] = $record->brandname;
                $product_data['wishlist'] = $record->wishlist;
            }

            $product_attribute = Product_attribute::select('name_ar as name', DB::raw('(select name_ar from attributes where attributes.id=product_attribute.attribute_id)as title'))->where('product_id', $product_id)->get();

            $all_recently_products = DB::table('most_product_viewed')
                ->select('most_product_viewed.product_id', 'products.id', 'products.name_ar as name', 'products.img', 'products.price', 'products.offer_price', 'products.quantity', 'description_en as description', 'products.img', 'products.img1', 'products.img2', 'products.img3', 'products.img4', 'products.img5', 'products.stock_availabity', 'products.relatedproducts', 'products.discount_available', 'products.category_id', 'products.sub_category_id', 'products.seo_url', 'products.brand_id', DB::raw("(select AVG(rating) from product_reviews where product_reviews.product_id=products.id) as rating"), DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"), DB::raw('(select name_en from brands where brands.id=products.brand_id)as brandname'), DB::raw('(select sum(quantity) from order_details where order_details.product_id=products.id) as total'))
                ->leftjoin('products', 'products.id', '=', 'most_product_viewed.product_id')
                ->orderby('most_product_viewed.id', 'desc')
                ->where('user_id', $customer_id)
                ->groupby('most_product_viewed.product_id')
                ->take(10)->get();

            if (!empty($product_details[0]['delivery_features'])) {
                $product_delivery_id = explode(',', $product_details[0]['delivery_features']);
                $product_delivery_features = Product_delivery_features::select('title_ar as title', 'image_ar as image')->whereIN('id', $product_delivery_id)->get();
            } else {
                $product_delivery_features = [];
            }

            $option = Option::with(["productoptions1" => function ($q) use ($product_id) {
                $q->where('product_details.product_id', '=', $product_id);
            }])->get();
        }

        if (!empty($product_details) && count($product_details) > 0) {
            if ($request->header('language') == 3) {
                $related = Product::select('id', 'name_en as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_en as description', 'quantity as quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"))->where('status', 1)->whereIN('id', explode(',', $product_details[0]->relatedproducts))->get();
            } else {
                $related = Product::select('id', 'name_ar as name', 'img', 'img1', 'img2', 'img3', 'img4', 'img5', 'seo_url', 'price', 'offer_price', 'description_ar as description', 'quantity as quantity', 'stock_availabity', 'discount_available', 'relatedproducts', DB::raw("(select count(id) from wishlist where wishlist.product_id=products.id AND wishlist.user_id=$customer_id) as wishlist"), DB::raw("(select count(id) from product_details where product_details.product_id=products.id) as optionid"))->where('status', 1)->whereIN('id', explode(',', $product_details[0]->relatedproducts))->get();
            }
        } else {
            $related = [];
        }

        $colors = Product_details::select('id', 'product_id', 'color', 'quantity', 'image', 'price')->where('product_id', $product_id)->WhereNotNull('color')->get();

        $mydata = [];
        foreach ($colors as $myproductdetail) {
            $data['id'] = $myproductdetail->id;
            $data['product_id'] = $myproductdetail->product_id;
            $data['color'] = $myproductdetail->color;
            $data['price'] = $myproductdetail->price;
            $data['quantity'] = $myproductdetail->quantity;
            $data['image'] = $myproductdetail->image;

            if (count($mydata) > 0) {

                if (array_search($myproductdetail->color, array_column($mydata, 'color')) === false) {
                    array_push($mydata, $data);
                }
            } else {
                array_push($mydata, $data);
            }

        }
        $colors = $mydata;

        $res['status'] = 1;
        $res['product_path'] = URL::to('/') . '/public/product_images/';
        $res['review_path'] = URL::to('/') . '/public/reviewcomment/';

        if ($request->header('language') == 3) {
            $res['success']['message'] = "Product Detail successfully";
        } else {
            $res['success']['message'] = "???????????? ?????????????? ??????????";
        }

        $res['recently'] = ["id" => "recently_viewed_products", "title" => "Recently Viewed"];
        $res['productreviews'] = $product_details;
        $res['product'] = $product_data;
        $res['option'] = $option;
        $res['color'] = $colors;
        $res['related'] = $related;
        $res['product_delivery_features'] = $product_delivery_features;
        $res['all_recently_products'] = $all_recently_products;
        $res['product_attribute'] = $product_attribute;

        return response($res);
    }

    public function product_filter(Request $request)
    {
        $category_id = $request['category_id'];

        if ($request->header('language') == 3) {
            $messages = ['category_id' => "Please Enter Category ID",
            ];
        } else {
            $messages = ['category_id' => "???????????? ?????????? ???????? ??????????",
            ];
        }

        $rules = ['category_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $product_filters = Subcategory::select('productfilters')->where('id', $category_id)->first();

        if (!empty($product_filters)) {
            $filters1 = Filter_values::select('id', 'filter_id', 'filter_name_en as min', 'filter_name_ar as max')->where('id', explode(',', $product_filters->productfilters))->get();
        }

        if (!empty($product_filters) && count($filters1) > 0) {
            if ($request->header('language') == 3) {
                $filtername = Filter::select('name_en as name')->where('id', $filters1[0]->filter_id)->first();
                $filters = Filter_values::select('id', 'filter_name_en as min', 'filter_name_ar as max')->whereIN('id', explode(',', $product_filters->productfilters))->get();
                $res['success']['message'] = "Filter listing successfully";
                $res['filters']['name'] = $filtername->name;
                $res['filters']['filter'] = $filters;
                return response($res);
            } else {
                $filtername = Filter::select('name_ar as name')->where('id', $filters1[0]->filter_id)->first();
                $filters = Filter_values::select('id', 'filter_name_en as min', 'filter_name_ar as max')->whereIN('id', explode(',', $product_filters->productfilters))->get();
                $res['success']['message'] = "?????????? ?????????????? ??????????";
                $res['filters']['name'] = $filtername->name;
                $res['filters']['filter'] = $filters;
                return response($res);
            }
        } else {
            $res['status'] = 0;

            if ($request->header('language') == 3) {
                $res['error']['message'] = "Filter not available in our record.";
            } else {
                $res['error']['message'] = "???????? ?????? ?????????? ???? ??????????.";
            }

            return response($res);
        }
    }
    public function reviewtransfer()
    {
        $reviews = DB::table('product_reviews')->select('*')->get();
        foreach ($reviews as $review) {
            $reviews1 = DB::table('oc_product_description')->select('*')->where('language_id', 3)->where('product_id', $review->product_id)->first();
            if (!empty($reviews1->tag)) {
                $reviews2 = DB::table('products')->select('*')->where('seo_url', $reviews1->tag)->first();
                if (!empty($reviews2->id)) {
                    DB::table('product_reviews')->where('product_id', $review->product_id)->update(['product_id' => $reviews2->id]);
                }
            }
        }
    }

    public function cart(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        if ($request->header('language') == 3) {
            $cartdata = Cart::
                leftjoin('products', 'products.id', '=', 'cart.product_id')
                ->select('products.id as product_id', 'products.quantity as qty', 'products.name_en as name', 'cart.quantity', 'products.offer_price as special', 'products.price as price', 'products.price as price', 'cart.option_id as option_id', 'products.img as image', 'products.discount_available as percentage', 'cart.id as cart_id')->where('cart.user_id', $request->customer_id)->get();
        } else {
            $cartdata = Cart::
                leftjoin('products', 'products.id', '=', 'cart.product_id')
                ->select('products.id as product_id', 'products.quantity as qty', 'products.name_ar as name', 'cart.quantity', 'products.offer_price as special', 'products.price as price', 'products.price as price', 'cart.option_id as option_id', 'products.img as image', 'products.discount_available as percentage', 'cart.id as cart_id')->where('cart.user_id', $request->customer_id)->get();
        }

        $allproducts = [];

        foreach ($cartdata as $recorddata) {
            $data['product_id'] = $recorddata->product_id;
            $data['cart_id'] = $recorddata->cart_id;
            $data['name'] = $recorddata->name;
            $data['quantity'] = $recorddata->quantity;
            $data['qty'] = $recorddata->qty;
            $data['option_id'] = $recorddata->option_id;
            $data['image'] = $recorddata->image;
            $data['special'] = $recorddata->special;
            $data['price'] = $recorddata->price;
            $data['percentage'] = $recorddata->percentage;
            if ($recorddata->option_id == 'nocolor') {
                $data['color'] = '';
                $data['option_name'] = '';
                $data['option_value'] = '';
            } elseif (empty($recorddata->option_id)) {
                $data['color'] = '';
                $data['option_name'] = '';
                $data['option_value'] = '';
            } else {
                $details = Product_details::where('id', $recorddata->option_id)->first();
                if (!empty($details->color)) {
                    $data['color'] = $details->color;
                } else {
                    $data['color'] = '';
                }
                $option = Option::where('id', $details->option_id)->first();
                if (!empty($option)) {
                    $data['option_name'] = $option->name_en;
                } else {
                    $data['option_name'] = '';
                }

                $option_value = Option_value::where('id', $details->option_value)->first();

                if (!empty($option_value)) {
                    $data['option_value'] = $option_value->value;
                } else {
                    $data['option_value'] = '';
                }
            }

            array_push($allproducts, $data);
        }

        $res['products'] = $allproducts;
        $res['information']['About Us'] = URL::to('/') . '/aboutus';
        $res['information']['Terms & Conditions'] = URL::to('/') . '/terms';
        $res['information']['Privacy'] = URL::to('/') . '/privacy';
        $res['information']['Bank Account And Payment'] = URL::to('/') . '/bankaccountpayment';
        $res['information']['Shipping & Delivery'] = URL::to('/') . '/shipping';

        if (!empty($cartdata)) {
            $res['product_path'] = URL::to('/') . '/public/product_images/';

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Cart List successfully";
            } else {
                $res['success']['message'] = "?????????? ???????? ???????????? ??????????";
            }
            $res['cart_count'] = Cart::where('user_id', $request->customer_id)->sum('quantity');

            return response($res);
        } else {
            $res['error']['message'] = "Your shopping cart is empty!";
        }
    }

    public function cart_add(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'product_id' => "Please Enter Product ID",
                'quantity' => "Please Enter Quantity",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'product_id' => "???????????? ?????????? ???????? ????????????",
                'quantity' => "???????????? ?????????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'product_id' => 'required', 'quantity' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $addproduct = Product::where('id', $request->product_id)->first();
        if ($request->quantity > $addproduct->quantity) {
            $res['status'] = 0;
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Product Is Out Of Stock";
            } else {
                $res['error']['message'] = "???????????? ???? ???? ??????????????";
            }
            return response($res);
        }

        if (!empty($request->option_id)) {

            $optionproduct = Product_details::where('id', $request->option_id)->first();
            if ($request->quantity > $optionproduct->quantity) {
                $res['status'] = 0;
                if ($request->header('language') == 3) {
                    $res['error']['message'] = "Product Is Out Of Stock";
                } else {
                    $res['error']['message'] = "???????????? ???? ???? ??????????????";
                }
                return response($res);
            }

        }

        $product = Cart::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->where('option_id', $request->option_id)->first();
        if (empty($product)) {
            $cart = new Cart;
            $cart->user_id = $request->customer_id;
            $cart->product_id = $request->product_id;
            $cart->option_id = $request->option_id;
            $cart->quantity = $request->quantity;
            $cart->save();
        } else {
            Cart::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->where('option_id', $request->option_id)->update(['quantity' => $request->quantity]);
        }

        if ($request->header('language') == 3) {
            $res['success']['message'] = "Cart Added successfully";
        } else {
            $res['success']['message'] = "?????? ?????????? ???????? ???????????? ??????????";
        }
        $res['cart_count'] = Cart::where('user_id', $request->customer_id)->sum('quantity');

        return response($res);
    }

    public function cart_remove(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'product_id' => "Please Enter Product ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'product_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'product_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $product = Cart::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->where('option_id', $request->option_id)->first();

        if (!empty($product)) {
            $product = Cart::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->where('option_id', $request->option_id)->delete();
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Remove successfully";
            } else {
                $res['success']['message'] = "?????? ?????????????? ??????????";
            }

            if ($request->header('language') == 3) {
                $cart = Cart::select('products.name_en as name', 'products.price as price', 'products.price as total', 'products.offer_price as special', 'products.quantity as qty', 'products.img as images', 'products.discount_available as percentage', 'products.stock_availabity as stock', 'cart.quantity as quantity', 'cart.id as cart_id', 'cart.product_id', 'cart.user_id', 'cart.option_id')
                    ->leftjoin('products', 'products.id', '=', 'cart.product_id')
                    ->where('cart.user_id', $request->customer_id)->get();
            } else {
                $cart = Cart::select('products.name_ar as name', 'products.price as price', 'products.price as total', 'products.offer_price as special', 'products.quantity as qty', 'products.img as images', 'products.discount_available as percentage', 'products.stock_availabity as stock', 'cart.quantity as quantity', 'cart.id as cart_id', 'cart.product_id', 'cart.user_id', 'cart.option_id')
                    ->leftjoin('products', 'products.id', '=', 'cart.product_id')
                    ->where('cart.user_id', $request->customer_id)->get();
            }
            $res['products'] = $cart;
            $res['cart_count'] = Cart::where('user_id', $request->customer_id)->sum('quantity');
            return response($res);
        } else {
            Cart::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->where('option_id', $request->option_id)->update(['quantity' => $request->quantity]);
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Product not available in our record";
            } else {
                $res['error']['message'] = "???????????? ?????? ?????????? ???? ??????????";
            }

            return response($res);
        }
    }

    public function information()
    {
        $res['information']['About Us'] = URL::to('/') . '/aboutus';
        $res['information']['Terms & Conditions'] = URL::to('/') . '/terms';
        $res['information']['Privacy'] = URL::to('/') . '/privacy';
        $res['information']['Bank Account And Payment'] = URL::to('/') . '/bankaccountpayment';
        $res['information']['Shipping & Delivery'] = URL::to('/') . '/shipping';
        return response($res);
    }

    public function order_status(Request $request)
    {
        if ($request->header('language') == 3) {
            $order_status = DB::table('order_status')->select('status_name_en as name', 'id as order_status_id')->get();
        } else {
            $order_status = DB::table('order_status')->select('status_name_ar as name', 'id as order_status_id')->get();
        }

        if ($request->header('language') == 3) {
            $res['success']['message'] = "Order Status successfully";
        } else {
            $res['success']['message'] = "???????? ?????????? ??????????";
        }

        $res['order_status'] = $order_status;

        return response($res);
    }

    public function cartmovetowishlist(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'cart_id' => "Please Enter Cart ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'cart_id' => "???????????? ?????????? ???????? ?????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'cart_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $cart = Cart::where('user_id', $request->customer_id)->where('id', $request->cart_id)->first();

        if (!empty($cart)) {
            $product = Product::where('id', $cart->product_id)->first();

            $wishlist = new Wishlist;
            $wishlist->user_id = $cart->user_id;
            $wishlist->product_id = $cart->product_id;
            $wishlist->option_id = $cart->option_id;
            $wishlist->category_id = $product->category_id;
            $wishlist->subcategory_id = $product->subcategory_id;
            $wishlist->save();

            Cart::where('user_id', $request->customer_id)->where('id', $request->cart_id)->delete();

            $res['cart_count'] = Cart::where('user_id', $request->customer_id)->count();
            $res['wishlist_count'] = Wishlist::where('user_id', $request->customer_id)->count();

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Move to Wishlist successfully";
            } else {
                $res['success']['message'] = "?????????? ?????? ?????????? ?????????????? ??????????";
            }
            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Product not available in our record.";
            } else {
                $res['error']['message'] = "???????????? ?????? ?????????? ???? ??????????.";
            }
            return response($res);
        }
    }

    public function order(Request $request)
    {
        $page = $request['page'] ? $request['page'] : 1;
        $page = $page - 1;
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        if (!empty($request->order_status_id)) {
            $order = Order::select('id as order_id', 'created_at as date_added', 'price as total', DB::raw('(select name from users where users.id=order.user_id)as name'), DB::raw('(select status_name_en from order_status where order_status.id=order.status)as status'), DB::raw('(select sum(quantity) from order_details where order_id=order.id)as product'))->where('user_id', $request->customer_id)->where('status', $request->order_status_id)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
        } else {
            $order = Order::select('id as order_id', 'created_at as date_added', 'price as total', DB::raw('(select name from users where users.id=order.user_id)as name'), DB::raw('(select status_name_en from order_status where order_status.id=order.status)as status'), DB::raw('(select sum(quantity) from order_details where order_id=order.id)as product'))->where('user_id', $request->customer_id)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
        }
        $ordercount = $order->count();

        $total_pages = ceil($ordercount / 10);

        if (!empty($order) && count($order) > 0) {
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Order List Successfully";
            } else {
                $res['success']['message'] = "?????????? ?????????????? ??????????";
            }

            $res['total_pages'] = $total_pages;
            $res['orders'] = $order;

            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Orders not available in our record";
            } else {
                $res['error']['message'] = "???????? ?????????????? ?????? ???????????? ???? ??????????";
            }
            return response($res);
        }
    }

    public function order_detail(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'order_id' => "Please Enter Order ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'order_id' => "???????????? ?????????? ???????? ??????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'order_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $order = Order::select('*', DB::raw('(select status_name_en from order_status where order_status.id=order.status)as orderstatus'))->where('user_id', $request->customer_id)->where('id', $request->order_id)->first();

        if (!empty($order)) {
            $cartdata = Order_details::
                leftjoin('products', 'products.id', '=', 'order_details.product_id')
                ->select('products.id as product_id', 'products.name_en as name', 'order_details.quantity', 'products.offer_price as special', 'products.price as price', 'products.price as price', 'order_details.color as option_id', 'products.img as image', 'products.discount_available as percentage')->where('order_details.user_id', $request->customer_id)->where('order_details.order_id', $request->order_id)->get();

            $address = DB::table('address')->select('id', 'user_id as customer_id', 'fulladdress', 'address_details', 'fullname', 'mobile as telephone', 'lat', 'long')->where('id', $order->address_id)->orderby('id', 'desc')->get();

            $user_data = User::where('id', $request->customer_id)->first();
            $res['contact_info']['email'] = $user_data->email;
            $res['contact_info']['telephone'] = $user_data->mobile;

            $res['order_id'] = $order->id;
            $res['date_added'] = $order->created_at;
            $res['status'] = $order->orderstatus;
            $res['date_last_status'] = $order->updated_at;

            $res['product_path'] = URL::to('/') . '/public/product_images/';

            if ($order->status == 6) {
                $res['cancel'] = true;
            } else {
                $res['cancel'] = false;
            }
            $res['return'] = false;
            $res['transaction_data'] = '';

            $allproducts = [];

            foreach ($cartdata as $recorddata) {
                $data['product_id'] = $recorddata->product_id;
                $data['name'] = $recorddata->name;
                $data['quantity'] = $recorddata->quantity;
                $data['image'] = $recorddata->image;
                $data['special'] = $recorddata->special;
                $data['price'] = $recorddata->price;
                $data['percentage'] = $recorddata->percentage;
                if ($recorddata->option_id == 'nocolor') {
                    $data['color'] = '';
                    $data['option_name'] = '';
                    $data['option_value'] = '';
                } elseif (empty($recorddata->option_id)) {
                    $data['color'] = '';
                    $data['option_name'] = '';
                    $data['option_value'] = '';
                } else {
                    $details = Product_details::where('id', $recorddata->option_id)->first();
                    if (!empty($details->color)) {
                        $data['color'] = $details->color;
                    } else {
                        $data['color'] = '';
                    }
                    $option = Option::where('id', $details->option_id)->first();
                    if (!empty($option)) {
                        $data['option_name'] = $option->name_en;
                    } else {
                        $data['option_name'] = '';
                    }

                    $option_value = Option_value::where('id', $details->option_value)->first();

                    if (!empty($option_value)) {
                        $data['option_value'] = $option_value->value;
                    } else {
                        $data['option_value'] = '';
                    }
                }

                array_push($allproducts, $data);
            }

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Order Detail Successfully";
            } else {
                $res['success']['message'] = "???????????? ?????????? ??????????";
            }

            if ($order->paymenttype == 1) {
                $res['payment_method']['code'] = "cod";
                $res['payment_method']['title'] = "Cash On Delivery ( " . $global[0]->delivery_charge . " SR Extra fee )";
            } elseif ($order->paymenttype == 2) {
                $res['payment_method']['code'] = "tap-card";
                $res['payment_method']['title'] = "<img src=\"https://www.gotapnow.com/web/tap.png\" />";
            } elseif ($order->paymenttype == 3) {
                $res['payment_method']['code'] = "bank_transfer";
                $res['payment_method']['title'] = "Bank Transfer";
                $res['payment_method']['bank_transfer'] = "National Commercial Bank<br />\r\nIBAN (SA 09 1000 0013 8471 8800 6605)<br />\r\n_____________________________________________<br />\r\n<br />\r\nAl Rajhi Bank<br />\r\nIBAN (SA 10 8000 0376 6080 1093 5845)";
            } else {
                $res['payment_method']['code'] = "tap-mada";
                $res['payment_method']['title'] = "<img src=\"https://www.gotapnow.com/web/tap.png\" />";
            }

            $paymenttotals = [];

            $datapush['title'] = 'Sub-Total';
            $datapush['text'] = $order->product_total_amount;
            array_push($paymenttotals, $datapush);

            if (!empty($order->delivery_price)) {
                $datapush['title'] = 'Delivery-charge';
                $datapush['text'] = $order->delivery_price;
                array_push($paymenttotals, $datapush);
            }

            if (!empty($order->discount)) {
                $datapush['title'] = 'Discount';
                $datapush['text'] = $order->discount;
                array_push($paymenttotals, $datapush);
            }
            if (!empty($order->shipping_price)) {
                $datapush['title'] = 'Shipping-Charge';
                $datapush['text'] = $order->shipping_price;
                array_push($paymenttotals, $datapush);
            }

            $datapush['title'] = 'Total';
            $datapush['text'] = $order->price;

            array_push($paymenttotals, $datapush);

            $res['totals'] = $paymenttotals;

            $res['products'] = $allproducts;

            $res['payment_address'] = $address;
            $res['shipping_address'] = $address;

            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Order not available in our record";
            } else {
                $res['error']['message'] = "???????? ?????????????? ?????? ???????????? ???? ??????????";
            }
            return response($res);
        }
    }
    public function order_track(Request $request)
    {
        $page = $request['page'] ? $request['page'] : 1;
        $page = $page - 1;
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'order_id' => "Please Enter Order ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'order_id' => "???????????? ?????????? ???????? ??????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'order_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $order = Order::select('*')->where('user_id', $request->customer_id)->where('id', $request->order_id)->get();

        if (!empty($order) && count($order) > 0) {
            $order_status = Order_track::select('id as order_status_id', 'created_at as date_added', DB::raw('(select status_name_en from order_status where order_status.id=order_track.order_status)as name'), DB::raw('(select status_name_ar from order_status where order_status.id=order_track.order_status)as name_ar'))->where('order_id', $request->order_id)->get();

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Order Status List Successfully";
            } else {
                $res['success']['message'] = "?????????? ???????? ?????????? ??????????";
            }

            $res['order_status'] = $order_status;
            $res['order_track_history'] = '';
            $res['service_provider'] = '';

            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Orders status not available in our record";
            } else {
                $res['error']['message'] = "???????? ?????????????? ?????? ???????????? ???? ??????????";
            }
            return response($res);
        }
    }

    public function notification(Request $request)
    {
        $page = $request['page'] ? $request['page'] : 1;
        $page = $page - 1;

        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $notification = Notification::where('user_id', $request->customer_id)->skip($page * 10)->take(10)->get();
        $notificationcount = Notification::where('user_id', $request->customer_id)->count();

        $total_pages = ceil($notificationcount / 10);

        if (!empty($notification) && count($notification) > 0) {
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Notification List Successfully";
            } else {
                $res['success']['message'] = "?????????? ?????????????????? ??????????";
            }

            $res['total_pages'] = $total_pages;
            $res['notifications'] = $notification;

            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Notification Not Available";
            } else {
                $res['error']['message'] = "?????????????? ?????? ??????????";
            }
            return response($res);
        }
    }

    public function coupon(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'coupon' => "Please Enter Coupon",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'coupon' => "???????????? ?????????? ??????????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'coupon' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $coupan = Coupon::where('code', $request->coupon)->first();

        if (empty($coupan)) {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Invalid Coupan Code";
            } else {
                $res['error']['message'] = "?????? ?????????????? ?????? ????????";
            }
            return response($res);
        }

        $product = Cart::select('product_id')->where('user_id', $request->customer_id)->get();

        $product_id = [];
        if (!empty($product)) {
            foreach ($product as $key => $productvalue) {
                array_push($product_id, $productvalue->product_id);
            }
        }

        $product_categories = DB::table('products')->select('category_id')->where('status', 1)->whereIn('id', $product_id)->groupby('category_id')->get();
        $product_subcategories = DB::table('products')->select('sub_category_id')->where('status', 1)->whereIn('id', $product_id)->groupby('sub_category_id')->get();

        $sub_cat = array();
        foreach ($product_subcategories as $subcategories) {
            $subcate = explode(',', $subcategories->sub_category_id);
            foreach ($subcate as $subcatedata) {
                array_push($sub_cat, $subcatedata);
            }
        }

        $cat = array();
        foreach ($product_categories as $categories) {
            array_push($cat, $categories->category_id);
        }

        if (!empty($coupan->subcategories)) {
            $allsub = explode(",", $coupan->subcategories);
            $containsSearch = count(array_intersect($sub_cat, $allsub)) == count($sub_cat);
            if ($containsSearch == '') {
                if ($request->header('language') == 3) {
                    $res['error']['message'] = "Coupon Code is not valid on this Category";
                } else {
                    $res['error']['message'] = "?????? ?????????????? ?????? ???????? ???? ?????? ??????????";
                }
                return response($res);
            }
        }

        if (!empty($coupan->categories)) {
            $allcat = explode(",", $coupan->categories);

            $contains_Search_categories = count(array_intersect($cat, $allcat)) == count($cat);
            if ($contains_Search_categories == '') {
                if ($request->header('language') == 3) {
                    $res['error']['message'] = "Coupon Code is not valid on this Category";
                } else {
                    $res['error']['message'] = "?????? ?????????????? ?????? ???????? ???? ?????? ??????????";
                }
                return response($res);
            }
        }

        $products = DB::table('products')->select('*')->where('status', 1)->whereIn('id', $product_id)->orderby('id', 'desc')->get();
        $order_price = 0;
        $productdata = array();
        foreach ($products as $productnew) {
            $order_price = $order_price + $productnew->price;
        }

        $coupon_uses = DB::table('coupon_history')->where('id', $coupan->id)->where('status', 1)->count();
        $coupan_use_per_customer = DB::table('coupon_history')->where('id', $coupan->id)->where('id', $request->customer_id)->where('status', 1)->count();

        $currentDate = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($currentDate));
        $start_date = $coupan->start_date;
        $start_date = date('Y-m-d', strtotime($start_date));
        $end_date = $coupan->end_date;
        $end_date = date('Y-m-d', strtotime($end_date));

        if (($currentDate >= $start_date) && ($currentDate <= $end_date)) {
            if ($coupon_uses <= $coupan->uses_per_coupon) {
                if ($coupan->uses_per_customer >= $coupan_use_per_customer) {
                    if ($order_price >= $coupan->total_amount) {
                        $couponcheck = Coupon_applied::where('user_id', $request->customer_id)->first();
                        if (!empty($couponcheck)) {
                            Coupon_applied::where('user_id', $request->customer_id)->update(['coupon_id' => $coupan->id]);
                        } else {
                            $couponapply = new Coupon_applied;
                            $couponapply->user_id = $request->customer_id;
                            $couponapply->coupon_id = $coupan->id;
                            $couponapply->save();
                        }

                        if ($request->header('language') == 3) {
                            $res['success']['message'] = "Success: Your coupon discount has been applied!";
                        } else {
                            $res['success']['message'] = "????????: ???? ?????????? ?????? ?????????????? ?????????? ????!";
                        }
                        return response($res);
                    } else {
                        if ($request->header('language') == 3) {
                            $res['error']['message'] = "Order Amount Must Be Greater than $coupan->total_amount To Apply This PromoCode";
                        } else {
                            $res['error']['message'] = "?????? ???? ???????? ???????? ?????????? ???????? ???? $coupan->total_amount ???????????? ?????? ?????????? ????????????????";
                        }
                        return response($res);
                    }
                } else {
                    if ($request->header('language') == 3) {
                        $res['error']['message'] = "Coupan Usage Maximum Limit Exists";
                    } else {
                        $res['error']['message'] = "???????? ???????? ???????????? ???????????????? ????????????????";
                    }
                    return response($res);
                }
            } else {
                if ($request->header('language') == 3) {
                    $res['error']['message'] = "Coupan Usage Maximum Limit Exists";
                } else {
                    $res['error']['message'] = "???????? ???????? ???????????? ???????????????? ????????????????";
                }
                return response($res);
            }
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Coupan Code Is Expired";
            } else {
                $res['error']['message'] = "?????? ?????????????? ?????????? ????????????????";
            }
            return response($res);
        }
    }

    public function social_login(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'device_id.required' => "Please Enter Device ID",
                'device_id.max' => "Device ID Must Be Less Than 255 Characters",

                'provider_id.required' => "Please Enter Provider ID",
                'provider_id.max' => "Provider ID Must Be Less Than 255 Characters",

                'device_token.required' => "Please Enter Device Token",
                'device_token.max' => "Device Token Must Be Less Than 255 Characters",

                'device_type.required' => "Please Enter Device Type",
                'device_type.max' => "Device Type Must Be Less Than 255 Characters",
            ];
        } else {
            $messages = [
                'device_id.required' => "???????????? ?????????? ???????? ????????????",
                'device_id.max' => "?????? ???? ???????? ???????? ???????????? ?????? ???? 255 ??????????",

                'provider_id.required' => "???????????? ?????????? ???????? ????????????",
                'provider_id.max' => "?????? ???? ???????? ???????? ???????????? ?????? ???? 255 ??????????",

                'device_token.required' => "???????????? ?????????? ?????? ????????????",
                'device_token.max' => "?????? ???? ???????? ?????? ???????????? ?????? ???? 255 ??????????",

                'device_type.required' => "???????????? ?????????? ?????? ????????????",
                'device_type.max' => "?????? ???? ???????? ?????? ???????????? ?????? ???? 255 ??????????",
            ];
        }

        $rules = [
            'device_token' => 'required|max:255',
            'device_id' => 'required|max:255',
            'provider_id' => 'required|max:255',
            'device_type' => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $user = User::where('provider_id', $request['provider_id'])->where("status", '1')->where("is_delete", '0')->where('role', '2')->first();

        if (!empty($user)) {
            User::where('id', $user->id)->update(['device_token' => $request['device_token'], 'device_id' => $request['device_id'], 'device_type' => $request['device_type']]);

            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Login Successfully";
            } else {
                $res['success']['message'] = "???? ?????????? ???????????? ??????????";
            }

            $res['user'] = ['customer_id' => $user['id'], 'email' => $user['email'], 'full_name' => $user['name'], 'telephone' => $user['mobile'], 'notification_push' => ($user['notification_push'] == 1) ? true : false, 'telephone_status' => ($user['otp_verify'] == 1) ? 'verified' : 'not_verified', 'notification_sms' => ($user['notification_sms'] == 1) ? true : false, 'notification_email' => ($user['notification_email'] == 1) ? true : false, 'social_login' => true];
            return response($res);
        } else {
            $res['status'] = 1;
            $res['user'] = '';

            return response($res);
        }
    }

    public function social_register(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = [
                'email.unique' => "Email Address Already Exists",
                'email.required' => "Please Enter Email Address",

                'telephone.unique' => "Mobile Number Already Exists",
                'telephone.required' => "Please Enter Mobile Number",

                'full_name.required' => "Please Enter Full Name",
                'full_name.min' => "Full Name Must Have Minimum 3 Characters",
                'full_name.max' => "Full Name Must Be Less Than 55 Characters",

                'provider_id.required' => "Please Enter Provider ID",
                'provider_id.max' => "Provider ID Must Be Less Than 255 Characters",

                'device_id.required' => "Please Enter Device ID",
                'device_id.max' => "Device ID Must Be Less Than 255 Characters",

                'device_token.required' => "Please Enter Device Token",
                'device_token.max' => "Device Token Must Be Less Than 255 Characters",

                'device_type.required' => "Please Enter Device Type",
                'device_type.max' => "Device Type Must Be Less Than 255 Characters",
            ];
        } else {
            $messages = [
                'email.unique' => "?????????? ???????????? ???????????????????? ?????????? ????????????",
                'email.required' => "???????????? ?????????? ?????????? ???????????? ????????????????????",

                'telephone.unique' => "???? ?????? ???????????? ????????????",
                'telephone.required' => "???????????? ?????????? ?????? ???????????? ??????????????",

                'full_name.required' => "???????????? ?????????? ?????????? ????????????",
                'full_name.min' => "?????? ?????? ?????? ?????????? ???????????? ???? 3 ????????",
                'full_name.max' => "?????? ???? ???????? ?????????? ???????????? ?????? ???? 55 ??????????",

                'device_id.required' => "???????????? ?????????? ???????? ????????????",
                'device_id.max' => "?????? ???? ???????? ???????? ???????????? ?????? ???? 255 ??????????",

                'provider_id.required' => "???????????? ?????????? ???????? ????????????",
                'provider_id.max' => "?????? ???? ???????? ???????? ???????????? ?????? ???? 255 ??????????",

                'device_token.required' => "???????????? ?????????? ?????? ????????????",
                'device_token.max' => "?????? ???? ???????? ?????? ???????????? ?????? ???? 255 ??????????",

                'device_type.required' => "???????????? ?????????? ?????? ????????????",
                'device_type.max' => "?????? ???? ???????? ?????? ???????????? ?????? ???? 255 ??????????",
            ];
        }

        $rules = [
            'full_name' => 'required|min:3|max:55',
            'email' => 'required|unique:users',
            'telephone' => 'required|unique:users,mobile',
            'device_token' => 'required|max:255',
            'device_id' => 'required|max:255',
            'provider_id' => 'required|max:255',
            'device_type' => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $user_data = new User();
        $user_data->name = $request['full_name'];
        $user_data->email = $request['email'];
        $user_data->mobile = $request['telephone'];
        $user_data->device_id = $request['device_id'];
        $user_data->device_token = $request['device_token'];
        $user_data->device_type = $request['device_type'];
        $user_data->provider_id = $request['provider_id'];
        $user_data->is_social = 1;
        $user_data->role = 2;
        $user_data->save();

        $user = User::select('*')->where('id', $user_data['id'])->first();
        $res['status'] = 1;
        if ($request->header('language') == 3) {
            $res['success']['message'] = "Registerd Successfully";
        } else {
            $res['success']['message'] = "???? ?????????????? ??????????";
        }
        $res['user'] = ['customer_id' => $user_data['id'], 'email' => $user_data['email'], 'full_name' => $user_data['name'], 'telephone' => $user_data['mobile'], 'notification_push' => ($user['notification_push'] == 1) ? true : false, 'telephone_status' => ($user['otp_verify'] == 1) ? 'verified' : 'not_verified', 'notification_sms' => ($user['notification_sms'] == 1) ? true : false, 'notification_email' => ($user['notification_email'] == 1) ? true : false, 'social_login' => true];
        return response($res);
    }

    public function changepassword(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id ' => "Please Enter Customer ID",
                'old_password' => "Please Enter Code",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'old_password' => "???????????? ?????????? ??????????",
            ];
        }

        $rules = ['customer_id' => 'required',
            'old_password' => 'required|min:6|max:50',
            'password' => 'required|min:6|max:50',
            'confirm_password' => 'required|same:password',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $user = User::where('id', $request->customer_id)->first();

        if (!Hash::check($request->old_password, $user->password)) {
            $res['status'] = 0;

            if ($request->header('language') == 3) {
                $res['success']['message'] = "The Old Password Does Not Match";
            } else {
                $res['success']['message'] = "???????? ???????????? ?????????????? ?????? ??????????????";
            }
            return response($res);
        } else {
            User::where("id", $request->customer_id)->update(["password" => Hash::make($request->password)]);

            $res['status'] = 1;

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Password Changed Successfully";
            } else {
                $res['success']['message'] = "???? ?????????? ?????????? ?????????? ??????????";
            }
            return response($res);
        }
    }

    public function checkout(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id ' => "Please Enter Customer ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        if (empty($request->address_id)) {
            $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();
            if (!empty($coupon)) {
                Coupon_applied::where('user_id', $request->customer_id)->delete();
            }
        }

        $cartdata = Cart::where('user_id', $request->customer_id)->get();

        if (count($cartdata) == 0) {
            $res['status'] = 0;

            if ($request->header('language') == 3) {
                $res['error']['message'] = "Your cart is empty";
            } else {
                $res['error']['message'] = "???????? ???????????? ??????????";
            }
            return response($res);
        } else {
            if ($request->order_confirm == 0 || empty($request->address_id) || empty($request->payment_method)) {
                $address_id = $request['address_id'] ? $request['address_id'] : 0;

                $cartcount = Cart::select(DB::raw('(select sum(quantity) from cart where cart.user_id=' . $request->customer_id . ')as totalcount'))->first();
                if ($request->header('language') == 3) {
                    $res['totals']['Item'] = $cartcount->totalcount;
                } else {
                    $res['totals']['????????????'] = $cartcount->totalcount;
                }
                $address = DB::table('address')->select('id as address_id', 'user_id as customer_id', 'fulladdress', 'address_1 as street_name', 'fullname', 'mobile as telephone', 'city', 'state as zone_id', 'postcode', 'otp_verify as is_telephone_approved', 'country_id', 'is_default as default', DB::raw('(select iso_code_2 from country where country.country_id=address.country_id)as country'), DB::raw('(select iso_code_3 from country where country.country_id=address.country_id)as country'), DB::raw('(select name from country where country.country_id=address.country_id)as country'), DB::raw('(select name from zone where zone.zone_id=address.state)as zone'), DB::raw('(select code from zone where zone.zone_id=address.state)as zone_code'))->where('user_id', $request->customer_id)->get();
                $wallet = Wallet::where('user_id', $request->customer_id)->first();
                $res['addresses'] = $address;
                $res['selected_address'] = $request->address_id;
                if (!empty($wallet)) {
                    $res['mywallet'] = $wallet->amount;
                } else {
                    $res['mywallet'] = 0;
                }
                $global = Global_settings::all();
                $res['payment_method']['cod']['code'] = "cod";
                $res['payment_method']['cod']['title'] = "Cash On Delivery ( " . $global[0]->delivery_charge . " SR Extra fee )";
                if ($request->payment_method == 'cod') {
                    $res['payment_method']['cod']['selected'] = true;
                } else {
                    $res['payment_method']['cod']['selected'] = false;
                }
                $res['payment_method']['bank_transfer']['code'] = "bank_transfer";
                $res['payment_method']['bank_transfer']['title'] = "Bank Transfer";
                $res['payment_method']['bank_transfer']['bank_transfer'] = "National Commercial Bank<br />\r\nIBAN (SA 09 1000 0013 8471 8800 6605)<br />\r\n_____________________________________________<br />\r\n<br />\r\nAl Rajhi Bank<br />\r\nIBAN (SA 10 8000 0376 6080 1093 5845)";
                if ($request->payment_method == 'bank_transfer') {
                    $res['payment_method']['bank_transfer']['selected'] = true;
                } else {
                    $res['payment_method']['bank_transfer']['selected'] = false;
                }
                $res['payment_method']['tap-card']['code'] = "tap-card";
                $res['payment_method']['tap-card']['title'] = "<img src=\"https://www.gotapnow.com/web/tap.png\" />";
                if ($request->payment_method == 'tap-card') {
                    $res['payment_method']['tap-card']['selected'] = true;
                } else {
                    $res['payment_method']['tap-card']['selected'] = false;
                }
                $res['payment_method']['tap-mada']['code'] = "tap-mada";
                $res['payment_method']['tap-mada']['title'] = "<img src=\"https://www.gotapnow.com/web/tap.png\" />";
                if ($request->payment_method == 'tap-mada') {
                    $res['payment_method']['tap-mada']['selected'] = true;
                } else {
                    $res['payment_method']['tap-mada']['selected'] = false;
                }

                $paymentmethod = [];

                foreach ($res['payment_method'] as $allpayment) {
                    array_push($paymentmethod, $allpayment);
                }

                $res['payment_method'] = $paymentmethod;

                $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();

                if (!empty($coupon)) {
                    $promocode = Coupon::where('id', $coupon->coupon_id)->first();
                    if (!empty($promocode)) {
                        $res['coupon'] = $promocode->code;
                    } else {
                        $res['coupon'] = '';
                    }
                } else {
                    $res['coupon'] = '';
                }

                $customer_cart = Cart::where('user_id', $request->customer_id)->get();

                $orderprice = 0;
                foreach ($customer_cart as $value) {
                    $product = Product::where('id', $value->product_id)->first();

                    if (!empty($value->option_id)) {
                        $product_detail = Product_details::where('id', $value->option_id)->first();

                        $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                    } else {
                        $orderprice = $orderprice + ($product->price) * $value->quantity;
                    }
                }

                if ($request->header('language') == 3) {
                    $res['totals']['Sub-Total'] = $orderprice;
                } else {
                    $res['totals']['?????????????? ????????????'] = $orderprice;
                }

                if ($request->payment_method == 'cod') {
                    if ($request->header('language') == 3) {
                        $res['totals']['Cash-On-Delivery'] = $global[0]->delivery_charge;
                    } else {
                        $res['totals']['?????????? ?????? ????????????????'] = $global[0]->delivery_charge;
                    }
                }

                if ($orderprice > $global[0]->min_amount_shipping) {
                    $orderprice = $orderprice;
                } else {
                    $orderprice = $orderprice + $global[0]->shipping_charge;
                    if ($request->header('language') == 3) {
                        $res['totals']['Shipping-charge'] = $global[0]->shipping_charge;
                    } else {
                        $res['totals']['???????? ??????????'] = $global[0]->shipping_charge;
                    }
                }

                if (!empty($coupon) && !empty($promocode)) {
                    $discount = ($orderprice / 100) * $promocode->discount;
                    if ($request->header('language') == 3) {
                        $res['totals']['Discount'] = number_format($discount, 2);

                    } else {
                        $res['totals']['??????'] = number_format($discount, 2);
                    }
                    if ($request->payment_method == 'cod') {
                        if ($request->header('language') == 3) {
                            $orderprice = number_format($global[0]->delivery_charge + $orderprice, 2);

                        } else {
                            $orderprice = number_format($global[0]->delivery_charge + $orderprice, 2);
                        }
                    }

                    $orderprice = number_format($orderprice - $discount, 2);
                } else {
                    if ($request->payment_method == 'cod') {
                        $orderprice = $orderprice + $global[0]->delivery_charge;
                    }
                    if ($request->header('language') == 3) {
                        $orderprice = $orderprice;
                    } else {
                        $orderprice = $orderprice;
                    }
                    $orderprice = $orderprice;
                }

                if (!empty($request->wallet)) {
                    $walletamount = $request->wallet;
                    $wallet = Wallet::where('user_id', $request->customer_id)->first();
                    if (!empty($wallet)) {
                        if ($walletamount >= $orderprice) {
                            $orderprice = 0;
                        } else {
                            $orderprice = $orderprice - $walletamount;
                        }
                    }
                }

                if ($request->header('language') == 3) {
                    $res['totals']['Total'] = number_format($orderprice, 2);
                } else {
                    $res['totals']['??????????'] = number_format($orderprice, 2);
                }
                $res['ordertotal'] = number_format($orderprice, 2);

                $paymenttotals = [];

                foreach ($res['totals'] as $key => $alltotal) {
                    $datapush['title'] = $key;
                    $datapush['text'] = $alltotal;
                    array_push($paymenttotals, $datapush);
                }

                $res['totals'] = $paymenttotals;

                if ($request->header('language') == 3) {
                    $cartdata = Cart::
                        leftjoin('products', 'products.id', '=', 'cart.product_id')
                        ->select('products.id as product_id', 'products.name_en as name', 'cart.quantity', 'products.offer_price as special', 'products.price as price', 'products.price as price', 'cart.option_id as option_id', 'products.img as image', 'products.discount_available as percentage')->where('cart.user_id', $request->customer_id)->get();
                } else {
                    $cartdata = Cart::
                        leftjoin('products', 'products.id', '=', 'cart.product_id')
                        ->select('products.id as product_id', 'products.name_ar as name', 'cart.quantity', 'products.offer_price as special', 'products.price as price', 'products.price as price', 'cart.option_id as option_id', 'products.img as image', 'products.discount_available as percentage')->where('cart.user_id', $request->customer_id)->get();
                }
                $res['product_path'] = URL::to('/') . '/public/product_images/';

                $allproducts = [];

                foreach ($cartdata as $recorddata) {
                    $data['product_id'] = $recorddata->product_id;
                    $data['name'] = $recorddata->name;
                    $data['quantity'] = $recorddata->quantity;
                    $data['image'] = $recorddata->image;
                    $data['special'] = $recorddata->special;
                    $data['option_id'] = $recorddata->option_id;
                    $data['price'] = $recorddata->price;
                    $data['percentage'] = $recorddata->percentage;
                    if ($recorddata->option_id == 'nocolor') {
                        $data['color'] = '';
                        $data['option_name'] = '';
                        $data['option_value'] = '';
                    } elseif (empty($recorddata->option_id)) {
                        $data['color'] = '';
                        $data['option_name'] = '';
                        $data['option_value'] = '';
                    } else {
                        $details = Product_details::where('id', $recorddata->option_id)->first();
                        if (!empty($details->color)) {
                            $data['color'] = $details->color;
                        } else {
                            $data['color'] = '';
                        }
                        $option = Option::where('id', $details->option_id)->first();
                        if (!empty($option)) {
                            $data['option_name'] = $option->name_en;
                        } else {
                            $data['option_name'] = '';
                        }

                        $option_value = Option_value::where('id', $details->option_value)->first();

                        if (!empty($option_value)) {
                            $data['option_value'] = $option_value->value;
                        } else {
                            $data['option_value'] = '';
                        }
                    }

                    array_push($allproducts, $data);
                }

                $res['products'] = $allproducts;

                return response($res);
            } else {
                if ($request->order_confirm == 1) {
                    if ($request->payment_method == 'cod' || $request->payment_method == 'bank_transfer') {
                        $global = Global_settings::all();

                        $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();

                        if (!empty($coupon)) {
                            $promocode = Coupon::where('id', $coupon->coupon_id)->first();

                            $coupon_history = new Coupon_history;
                            $coupon_history->coupan_id = $coupon->coupon_id;
                            $coupon_history->status = 1;
                            $coupon_history->user_id = $request->customer_id;
                            $coupon_history->save();
                        }

                        $customer_cart = Cart::where('user_id', $request->customer_id)->get();

                        $orderprice = 0;
                        foreach ($customer_cart as $value) {
                            $product = Product::where('id', $value->product_id)->first();

                            if ($value->quantity > $product->quantity) {
                                $res['error']['message'] = "Product is out of stock";
                                return response($res);
                            }

                            if (!empty($value->option_id)) {
                                $product_detail = Product_details::where('id', $value->option_id)->first();

                                if ($value->quantity > $product_detail->quantity) {
                                    $res['error']['message'] = "Product is out of stock";
                                    return response($res);
                                }

                                $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                            } else {
                                $orderprice = $orderprice + ($product->price) * $value->quantity;
                            }
                        }
                        $product_sub_total = $orderprice;

                        //Ship charge
                        if ($orderprice > $global[0]->min_amount_shipping) {
                            $orderprice = $orderprice;
                            $ship_charge = 0;
                        } else {
                            $orderprice = $orderprice + $global[0]->shipping_charge;
                            $ship_charge = $global[0]->shipping_charge;
                        }

                        //coupon code applied

                        if (!empty($coupon) && !empty($promocode)) {
                            $discount = ($orderprice / 100) * $promocode->discount;
                            $orderprice = number_format($orderprice - $discount, 2);
                        }

                        //Delivery charge
                        if ($request->payment_method == 'cod') {
                            $orderprice = number_format($orderprice + $global[0]->delivery_charge, 2);
                            $delivery_charge = $global[0]->delivery_charge;
                        } else {
                            $orderprice = $orderprice;
                            $delivery_charge = 0;
                        }

                        //Quantity Decrease
                        foreach ($customer_cart as $value) {
                            $product = Product::where('id', $value->product_id)->first();
                            $product_detail = Product_details::where('id', $value->option_id)->first();

                            Product::where('id', $value->product_id)->update(['quantity' => $product->quantity - $value->quantity]);

                            if (!empty($value->option_id)) {
                                Product_details::where('id', $value->option_id)->update(['quantity' => $product_detail->quantity - $value->quantity]);
                            }
                        }

                        //Wallet manage

                        if (!empty($request->wallet)) {
                            $walletamount = $request->wallet;
                            $wallet = Wallet::where('user_id', $request->customer_id)->first();
                            if (!empty($wallet)) {
                                if ($walletamount >= $orderprice) {
                                    Wallet::where('user_id', $request->customer_id)->update(['amount' => $wallet->amount - $orderprice]);
                                    $walletamount = $orderprice;

                                    $history = new Wallet_recharge_history;
                                    $history->amount = $walletamount;
                                    $history->user_id = $request->customer_id;
                                    $history->type = 2;
                                    $history->reason = "Order";
                                    $history->reason_ar = "??????????";
                                    $history->save();
                                } else {
                                    Wallet::where('user_id', $request->customer_id)->update(['amount' => $wallet->amount - $request->wallet]);
                                    $walletamount = $request->wallet;

                                    $history = new Wallet_recharge_history;
                                    $history->amount = $walletamount;
                                    $history->user_id = $request->customer_id;
                                    $history->type = 2;
                                    $history->reason = "Order";
                                    $history->reason_ar = "??????????";
                                    $history->save();
                                }
                            } else {
                                $walletamount = 0;
                            }
                        } else {
                            $walletamount = 0;
                        }

                        //Order Create

                        $order = new Order;
                        if ($request->payment_method == 'bank_transfer') {
                            $order->payment_type = 5;
                        } else {
                            $order->payment_type = 1;
                        }
                        $order->shipping_price = $ship_charge;
                        $order->delivery_price = $delivery_charge;
                        if (!empty($coupon)) {
                            $order->coupan_id = $coupon->coupon_id;
                        }
                        $order->discount = $orderprice - ($product_sub_total + $ship_charge + $delivery_charge);
                        $order->product_total_amount = $product_sub_total;
                        $order->paid_by_wallet = $walletamount;
                        $order->user_id = $request->customer_id;
                        $order->address_id = $request->address_id;
                        $order->price = $orderprice;
                        $order->save();

                        $ordertrack = new Order_track;
                        $ordertrack->order_id = $order->id;
                        $ordertrack->save();

                        foreach ($customer_cart as $record) {
                            $product = Product::where('id', $record->product_id)->first();
                            $order_details = new Order_details;
                            $order_details->order_id = $order->id;
                            $order_details->user_id = $request->customer_id;
                            $order_details->product_id = $record->product_id;
                            if (!empty($record->option_id)) {
                                $product_detail = Product_details::where('id', $value->option_id)->first();

                                $order_details->color = $record->option_id;
                                $order_details->price = $product_detail->price + $product->price;
                            } else {
                                $order_details->color = 'nocolor';
                                $order_details->price = $product->price;
                            }
                            $order_details->quantity = $record->quantity;
                            $order_details->product_name_en = $product->name_en;
                            $order_details->product_name_ar = $product->name_ar;

                            $order_details->save();
                        }

                        $userdata = User::where('id', $request->customer_id)->first();

                        $Device_token = $userdata->device_token;
                        $user_id = $userdata->id;
                        $msg = array(
                            'body' => "Your Order #" . $order->id . " is Processing",
                            'title' => 'Order Info',
                            'subtitle' => 'Letsbuy',
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon',
                        );
                        $this->Notificationsend($Device_token, $msg, $user_id);

                        $name = $userdata->name;

                        $EmailTemplates = Emailtemplate::where('slug', 'order_completed')->first();
                        $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
                        $subject = $EmailTemplates->subject_en;
                        $to_email = $userdata->email;
                        $data = array();
                        $data['msg'] = $message;
                        Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                            $message->to($to_email)
                                ->subject($subject);
                            $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
                        });

                        $user_mobile = User::select('mobile')->where('id', $request->customer_id)->first();

                        $mobile = $user_mobile->mobile;

                        $user = "letsbuy";
                        $password = "Nn0450292**";
                        $mobilenumbers = $mobile;
                        if ($request->header('language') == 3) {
                            $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
                        } else {
                            $message = ' ???? ?????????? ???????? ?????????? ?????????? . ?????? ?????????? ' . $order->id;
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

                        $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();
                        if (!empty($coupon)) {
                            Coupon_applied::where('user_id', $request->customer_id)->delete();
                        }

                        Cart::where('user_id', $request->customer_id)->delete();

                        $getaddress = Address::where('id', $request->address_id)->first();

                        if ($request->header('language') == 3) {
                            $res['success']['message'] = 'Order Placed Successfully';
                            $res['order_id'] = $order->id;
                            $res['total'] = $order->price;
                            $res['fulladdress'] = $getaddress->fulladdress;
                        } else {
                            $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                            $res['order_id'] = $order->id;
                            $res['total'] = $order->price;
                            $res['fulladdress'] = $getaddress->fulladdress;
                        }
                        return response($res);
                    } elseif ($request->payment_method == 'wallet') {

                        //Order when wallet is greater than order
                        $global = Global_settings::all();

                        $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();

                        if (!empty($coupon)) {
                            $promocode = Coupon::where('id', $coupon->coupon_id)->first();

                            $coupon_history = new Coupon_history;
                            $coupon_history->coupan_id = $coupon->coupon_id;
                            $coupon_history->status = 1;
                            $coupon_history->user_id = $request->customer_id;
                            $coupon_history->save();
                        }

                        $customer_cart = Cart::where('user_id', $request->customer_id)->get();

                        $orderprice = 0;
                        foreach ($customer_cart as $value) {
                            $product = Product::where('id', $value->product_id)->first();

                            if ($value->quantity > $product->quantity) {
                                $res['error']['message'] = "Product is out of stock";
                                return response($res);
                            }

                            if (!empty($value->option_id)) {
                                $product_detail = Product_details::where('id', $value->option_id)->first();

                                if ($value->quantity > $product_detail->quantity) {
                                    $res['error']['message'] = "Product is out of stock";
                                    return response($res);
                                }

                                $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                            } else {
                                $orderprice = $orderprice + ($product->price) * $value->quantity;
                            }
                        }
                        $product_sub_total = $orderprice;

                        //Ship charge
                        if ($orderprice > $global[0]->min_amount_shipping) {
                            $orderprice = $orderprice;
                            $ship_charge = 0;
                        } else {
                            $orderprice = $orderprice + $global[0]->shipping_charge;
                            $ship_charge = $global[0]->shipping_charge;
                        }

                        //Delivery charge
                        if ($request->payment_method == 'cod') {
                            $orderprice = $orderprice + $global[0]->delivery_charge;
                            $delivery_charge = $global[0]->delivery_charge;
                        } else {
                            $orderprice = $orderprice;
                            $delivery_charge = 0;
                        }

                        //coupon code applied

                        if (!empty($coupon) && !empty($promocode)) {
                            $discount = ($orderprice / 100) * $promocode->discount;
                            $orderprice = number_format($orderprice - $discount, 2);
                        }

                        //Quantity Decrease
                        foreach ($customer_cart as $value) {
                            $product = Product::where('id', $value->product_id)->first();
                            $product_detail = Product_details::where('id', $value->option_id)->first();

                            Product::where('id', $value->product_id)->update(['quantity' => $product->quantity - $value->quantity]);

                            if (!empty($value->option_id)) {
                                Product_details::where('id', $value->option_id)->update(['quantity' => $product_detail->quantity - $value->quantity]);
                            }
                        }

                        //Wallet manage

                        if (!empty($request->wallet)) {
                            $walletamount = $request->wallet;
                            $wallet = Wallet::where('user_id', $request->customer_id)->first();
                            if (!empty($wallet)) {
                                if ($walletamount >= $orderprice) {
                                    Wallet::where('user_id', $request->customer_id)->update(['amount' => $wallet->amount - $orderprice]);
                                    $walletamount = $orderprice;
                                } else {
                                    Wallet::where('user_id', $request->customer_id)->update(['amount' => $wallet->amount - $request->wallet]);
                                    $walletamount = $request->wallet;
                                }
                            } else {
                                $walletamount = 0;
                            }
                        } else {
                            $walletamount = 0;
                        }

                        $history = new Wallet_recharge_history;
                        $history->amount = $walletamount;
                        $history->user_id = $request->customer_id;
                        $history->type = 2;
                        $history->reason = "Order";
                        $history->reason_ar = "??????????";
                        $history->save();

                        //Order Create

                        $order = new Order;
                        $order->payment_type = 2;
                        $order->shipping_price = $ship_charge;
                        $order->delivery_price = $delivery_charge;
                        if (!empty($coupon)) {
                            $order->coupan_id = $coupon->coupon_id;
                        }
                        $order->discount = $orderprice - ($product_sub_total + $ship_charge + $delivery_charge);
                        $order->product_total_amount = $product_sub_total;
                        $order->paid_by_wallet = $walletamount;
                        $order->user_id = $request->customer_id;
                        $order->address_id = $request->address_id;
                        $order->price = $orderprice;
                        $order->save();

                        $ordertrack = new Order_track;
                        $ordertrack->order_id = $order->id;
                        $ordertrack->save();

                        foreach ($customer_cart as $record) {
                            $product = Product::where('id', $record->product_id)->first();
                            $order_details = new Order_details;
                            $order_details->order_id = $order->id;
                            $order_details->user_id = $request->customer_id;
                            $order_details->product_id = $record->product_id;
                            if (!empty($record->option_id)) {
                                $product_detail = Product_details::where('id', $value->option_id)->first();

                                $order_details->color = $record->option_id;
                                $order_details->price = $product_detail->price + $product->price;
                            } else {
                                $order_details->color = 'nocolor';
                                $order_details->price = $product->price;
                            }
                            $order_details->quantity = $record->quantity;
                            $order_details->product_name_en = $product->name_en;
                            $order_details->product_name_ar = $product->name_ar;

                            $order_details->save();
                        }

                        $userdata = User::where('id', $request->customer_id)->first();

                        $Device_token = $userdata->device_token;
                        $user_id = $userdata->id;
                        $msg = array(
                            'body' => "Your Order #" . $order->id . " is Processing",
                            'title' => 'Order Info',
                            'subtitle' => 'Letsbuy',
                            'key' => '5',
                            'vibrate' => 1,
                            'sound' => 1,
                            'largeIcon' => 'large_icon',
                            'smallIcon' => 'small_icon',
                        );
                        $this->Notificationsend($Device_token, $msg, $user_id);

                        $name = $userdata->name;

                        $EmailTemplates = Emailtemplate::where('slug', 'order_completed')->first();
                        $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
                        $subject = $EmailTemplates->subject_en;
                        $to_email = $userdata->email;
                        $data = array();
                        $data['msg'] = $message;
                        Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                            $message->to($to_email)
                                ->subject($subject);
                            $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
                        });

                        $user_mobile = User::select('mobile')->where('id', $request->customer_id)->first();

                        $mobile = $user_mobile->mobile;

                        $user = "letsbuy";
                        $password = "Nn0450292**";
                        $mobilenumbers = $mobile;
                        if ($request->header('language') == 3) {
                            $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
                        } else {
                            $message = ' ???? ?????????? ???????? ?????????? ?????????? . ?????? ?????????? ' . $order->id;
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

                        Cart::where('user_id', $request->customer_id)->delete();

                        $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();
                        if (!empty($coupon)) {
                            Coupon_applied::where('user_id', $request->customer_id)->delete();
                        }

                        $getaddress = Address::where('id', $request->address_id)->first();

                        if ($request->header('language') == 3) {
                            $res['success']['message'] = 'Order Placed Successfully';
                            $res['order_id'] = $order->id;
                            $res['total'] = $order->price;
                            $res['fulladdress'] = $getaddress->fulladdress;
                        } else {
                            $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                            $res['order_id'] = $order->id;
                            $res['total'] = $order->price;
                            $res['fulladdress'] = $getaddress->fulladdress;
                        }
                        return response($res);
                    } elseif ($request->payment_method == 'tap-card') {
                        $global = Global_settings::all();

                        $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();

                        if (!empty($coupon)) {
                            $promocode = Coupon::where('id', $coupon->coupon_id)->first();

                            $coupon_history = new Coupon_history;
                            $coupon_history->coupan_id = $coupon->coupon_id;
                            $coupon_history->status = 1;
                            $coupon_history->user_id = $request->customer_id;
                            $coupon_history->save();
                        }

                        $customer_cart = Cart::where('user_id', $request->customer_id)->get();

                        $orderprice = 0;
                        foreach ($customer_cart as $value) {
                            $product = Product::where('id', $value->product_id)->first();

                            if ($value->quantity > $product->quantity) {
                                $res['error']['message'] = "Product is out of stock";
                                return response($res);
                            }

                            if (!empty($value->option_id)) {
                                $product_detail = Product_details::where('id', $value->option_id)->first();

                                if ($value->quantity > $product_detail->quantity) {
                                    $res['error']['message'] = "Product is out of stock";
                                    return response($res);
                                }

                                $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                            } else {
                                $orderprice = $orderprice + ($product->price) * $value->quantity;
                            }
                        }
                        $product_sub_total = $orderprice;

                        //Ship charge
                        if ($orderprice > $global[0]->min_amount_shipping) {
                            $orderprice = $orderprice;
                            $ship_charge = 0;
                        } else {
                            $orderprice = $orderprice + $global[0]->shipping_charge;
                            $ship_charge = $global[0]->shipping_charge;
                        }

                        //Delivery charge
                        if ($request->payment_method == 'cod') {
                            $orderprice = $orderprice + $global[0]->delivery_charge;
                            $delivery_charge = $global[0]->delivery_charge;
                        } else {
                            $orderprice = $orderprice;
                            $delivery_charge = 0;
                        }

                        //coupon code applied

                        if (!empty($coupon) && !empty($promocode)) {
                            $discount = ($orderprice / 100) * $promocode->discount;
                            $orderprice = number_format($orderprice - $discount, 2);
                        }

                        //Wallet manage

                        if (!empty($request->wallet)) {
                            $walletamount = $request->wallet;
                            $wallet = Wallet::where('user_id', $request->customer_id)->first();
                            if (!empty($wallet)) {
                                if ($walletamount >= $orderprice) {

                                    //Order when wallet is greater than order
                                    $global = Global_settings::all();

                                    $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();

                                    if (!empty($coupon)) {
                                        $promocode = Coupon::where('id', $coupon->coupon_id)->first();

                                        $coupon_history = new Coupon_history;
                                        $coupon_history->coupan_id = $coupon->coupon_id;
                                        $coupon_history->status = 1;
                                        $coupon_history->user_id = $request->customer_id;
                                        $coupon_history->save();
                                    }

                                    $customer_cart = Cart::where('user_id', $request->customer_id)->get();

                                    $orderprice = 0;
                                    foreach ($customer_cart as $value) {
                                        $product = Product::where('id', $value->product_id)->first();

                                        if ($value->quantity > $product->quantity) {
                                            $res['error']['message'] = "Product is out of stock";
                                            return response($res);
                                        }

                                        if (!empty($value->option_id)) {
                                            $product_detail = Product_details::where('id', $value->option_id)->first();

                                            if ($value->quantity > $product_detail->quantity) {
                                                $res['error']['message'] = "Product is out of stock";
                                                return response($res);
                                            }

                                            $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                                        } else {
                                            $orderprice = $orderprice + ($product->price) * $value->quantity;
                                        }
                                    }
                                    $product_sub_total = $orderprice;

                                    //Ship charge
                                    if ($orderprice > $global[0]->min_amount_shipping) {
                                        $orderprice = $orderprice;
                                        $ship_charge = 0;
                                    } else {
                                        $orderprice = $orderprice + $global[0]->shipping_charge;
                                        $ship_charge = $global[0]->shipping_charge;
                                    }

                                    //Delivery charge
                                    if ($request->payment_method == 'cod') {
                                        $orderprice = $orderprice + $global[0]->delivery_charge;
                                        $delivery_charge = $global[0]->delivery_charge;
                                    } else {
                                        $orderprice = $orderprice;
                                        $delivery_charge = 0;
                                    }

                                    //coupon code applied

                                    if (!empty($coupon) && !empty($promocode)) {
                                        $discount = ($orderprice / 100) * $promocode->discount;
                                        $orderprice = number_format($orderprice - $discount, 2);
                                    }

                                    //Quantity Decrease
                                    foreach ($customer_cart as $value) {
                                        $product = Product::where('id', $value->product_id)->first();
                                        $product_detail = Product_details::where('id', $value->option_id)->first();

                                        Product::where('id', $value->product_id)->update(['quantity' => $product->quantity - $value->quantity]);

                                        if (!empty($value->option_id)) {
                                            Product_details::where('id', $value->option_id)->update(['quantity' => $product_detail->quantity - $value->quantity]);
                                        }
                                    }

                                    //Wallet manage

                                    if (!empty($request->wallet)) {
                                        $walletamount = $request->wallet;
                                        $wallet = Wallet::where('user_id', $request->customer_id)->first();
                                        if (!empty($wallet)) {
                                            if ($walletamount >= $orderprice) {
                                                Wallet::where('user_id', $request->customer_id)->update(['amount' => $wallet->amount - $orderprice]);
                                                $walletamount = $orderprice;

                                                $history = new Wallet_recharge_history;
                                                $history->amount = $walletamount;
                                                $history->user_id = $request->customer_id;
                                                $history->type = 2;
                                                $history->reason = "Order";
                                                $history->reason_ar = "??????????";
                                                $history->save();
                                            } else {
                                                Wallet::where('user_id', $request->customer_id)->update(['amount' => $wallet->amount - $request->wallet]);
                                                $walletamount = $request->wallet;

                                                $history = new Wallet_recharge_history;
                                                $history->amount = $walletamount;
                                                $history->user_id = $request->customer_id;
                                                $history->type = 2;
                                                $history->reason = "Order";
                                                $history->reason_ar = "??????????";
                                                $history->save();
                                            }
                                        } else {
                                            $walletamount = 0;
                                        }
                                    } else {
                                        $walletamount = 0;
                                    }

                                    //Order Create

                                    $order = new Order;
                                    $order->payment_type = 2;
                                    $order->shipping_price = $ship_charge;
                                    $order->delivery_price = $delivery_charge;
                                    if (!empty($coupon)) {
                                        $order->coupan_id = $coupon->coupon_id;
                                    }
                                    $order->discount = $orderprice - ($product_sub_total + $ship_charge + $delivery_charge);
                                    $order->product_total_amount = $product_sub_total;
                                    $order->paid_by_wallet = $walletamount;
                                    $order->user_id = $request->customer_id;
                                    $order->address_id = $request->address_id;
                                    $order->price = $orderprice;
                                    $order->save();

                                    $ordertrack = new Order_track;
                                    $ordertrack->order_id = $order->id;
                                    $ordertrack->save();

                                    foreach ($customer_cart as $record) {
                                        $product = Product::where('id', $record->product_id)->first();
                                        $order_details = new Order_details;
                                        $order_details->order_id = $order->id;
                                        $order_details->user_id = $request->customer_id;
                                        $order_details->product_id = $record->product_id;
                                        if (!empty($record->option_id)) {
                                            $product_detail = Product_details::where('id', $value->option_id)->first();

                                            $order_details->color = $record->option_id;
                                            $order_details->price = $product_detail->price + $product->price;
                                        } else {
                                            $order_details->color = 'nocolor';
                                            $order_details->price = $product->price;
                                        }
                                        $order_details->quantity = $record->quantity;
                                        $order_details->product_name_en = $product->name_en;
                                        $order_details->product_name_ar = $product->name_ar;

                                        $order_details->save();
                                    }

                                    $userdata = User::where('id', $request->customer_id)->first();
                                    $name = $userdata->name;

                                    $Device_token = $userdata->device_token;
                                    $user_id = $userdata->id;
                                    $msg = array(
                                        'body' => "Your Order #" . $order->id . " is Processing",
                                        'title' => 'Order Info',
                                        'subtitle' => 'Letsbuy',
                                        'key' => '5',
                                        'vibrate' => 1,
                                        'sound' => 1,
                                        'largeIcon' => 'large_icon',
                                        'smallIcon' => 'small_icon',
                                    );
                                    $this->Notificationsend($Device_token, $msg, $user_id);

                                    $EmailTemplates = Emailtemplate::where('slug', 'order_completed')->first();
                                    $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
                                    $subject = $EmailTemplates->subject_en;
                                    $to_email = $userdata->email;
                                    $data = array();
                                    $data['msg'] = $message;
                                    Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                                        $message->to($to_email)
                                            ->subject($subject);
                                        $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
                                    });

                                    $user_mobile = User::select('mobile')->where('id', $request->customer_id)->first();

                                    $mobile = $user_mobile->mobile;

                                    $user = "letsbuy";
                                    $password = "Nn0450292**";
                                    $mobilenumbers = $mobile;
                                    if ($request->header('language') == 3) {
                                        $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
                                    } else {
                                        $message = ' ???? ?????????? ???????? ?????????? ?????????? . ?????? ?????????? ' . $order->id;
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

                                    Cart::where('user_id', $request->customer_id)->delete();

                                    $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();
                                    if (!empty($coupon)) {
                                        Coupon_applied::where('user_id', $request->customer_id)->delete();
                                    }

                                    $getaddress = Address::where('id', $request->address_id)->first();

                                    if ($request->header('language') == 3) {
                                        $res['success']['message'] = 'Order Placed Successfully';
                                        $res['order_id'] = $order->id;
                                        $res['total'] = $order->price;
                                        $res['fulladdress'] = $getaddress->fulladdress;
                                    } else {
                                        $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                                        $res['order_id'] = $order->id;
                                        $res['total'] = $order->price;
                                        $res['fulladdress'] = $getaddress->fulladdress;
                                    }
                                    return response($res);
                                } else {
                                    $walletamount = $request->wallet;
                                    $discountprice = $orderprice - $request->wallet;
                                }
                            } else {
                                $walletamount = 0;
                                $discountprice = $orderprice;
                            }
                        } else {
                            $walletamount = 0;
                            $discountprice = $orderprice;
                        }

                        $user_data = User::where('id', $request->customer_id)->first();

                        $transaction_id = "txn_" . rand(100000, 900000);
                        $order_id = "ord_" . rand(100000, 900000);

                        $curl = curl_init();

                        $payload = [];
                        $payload['amount'] = $discountprice;
                        $payload['currency'] = "SAR";
                        $payload['threeDSecure'] = true;
                        $payload['save_card'] = false;
                        $payload['description'] = "Test Description";
                        $payload['save_card'] = "statement_descriptor";
                        $payload['metadata']['udf1'] = $request->wallet;
                        $payload['metadata']['udf2'] = 2;
                        $payload['reference']['transaction'] = $transaction_id;
                        $payload['reference']['order'] = $order_id;
                        $payload['receipt']['email'] = false;
                        $payload['receipt']['email'] = true;
                        $payload['customer']['first_name'] = $user_data->name;
                        $payload['customer']['email'] = $user_data->email;
                        $payload['customer']['phone']['country_code'] = $request->address_id;
                        $payload['customer']['phone']['number'] = $user_data->mobile;
                        $payload['merchant']['id'] = "";
                        $payload['source']['id'] = "src_card";
                        $payload['post']['url'] = URL::to('/');
                        $payload['redirect']['url'] = URL::to('/') . '/api/payment/';

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://api.tap.company/v2/charges",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => json_encode($payload),

                            CURLOPT_HTTPHEADER => array(
                                "authorization: Bearer sk_live_tA8OicMD2sbKCkqxXlmp4TBU",
                                "content-type: application/json",
                            ),
                        ));

                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        $resp = json_decode($response);
                        curl_close($curl);
                        if ($err) {
                            if ($request->header('language') == 3) {
                                $res['success']['error'] = 'Server Error';
                            } else {
                                $res['success']['error'] = '?????? ???? ????????????';
                            }
                            return response($res);
                        } else {
                            if ($request->header('language') == 3) {
                                $res['success']['message'] = 'Order Processed Successfully';
                                $res['success']['url'] = $resp->transaction->url;
                            } else {
                                $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                                $res['success']['url'] = $resp->transaction->url;
                            }
                            return response($res);
                        }
                    } else {
                        $global = Global_settings::all();

                        $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();

                        if (!empty($coupon)) {
                            $promocode = Coupon::where('id', $coupon->coupon_id)->first();

                            $coupon_history = new Coupon_history;
                            $coupon_history->coupan_id = $coupon->coupon_id;
                            $coupon_history->status = 1;
                            $coupon_history->user_id = $request->customer_id;
                            $coupon_history->save();
                        }

                        $customer_cart = Cart::where('user_id', $request->customer_id)->get();

                        $orderprice = 0;
                        foreach ($customer_cart as $value) {
                            $product = Product::where('id', $value->product_id)->first();

                            if ($value->quantity > $product->quantity) {
                                $res['error']['message'] = "Product is out of stock";
                                return response($res);
                            }

                            if (!empty($value->option_id)) {
                                $product_detail = Product_details::where('id', $value->option_id)->first();

                                if ($value->quantity > $product_detail->quantity) {
                                    $res['error']['message'] = "Product is out of stock";
                                    return response($res);
                                }

                                $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                            } else {
                                $orderprice = $orderprice + ($product->price) * $value->quantity;
                            }
                        }
                        $product_sub_total = $orderprice;

                        //Ship charge
                        if ($orderprice > $global[0]->min_amount_shipping) {
                            $orderprice = $orderprice;
                            $ship_charge = 0;
                        } else {
                            $orderprice = $orderprice + $global[0]->shipping_charge;
                            $ship_charge = $global[0]->shipping_charge;
                        }

                        //Delivery charge
                        if ($request->payment_method == 'cod') {
                            $orderprice = $orderprice + $global[0]->delivery_charge;
                            $delivery_charge = $global[0]->delivery_charge;
                        } else {
                            $orderprice = $orderprice;
                            $delivery_charge = 0;
                        }

                        //coupon code applied

                        if (!empty($coupon) && !empty($promocode)) {
                            $discount = ($orderprice / 100) * $promocode->discount;
                            $orderprice = number_format($orderprice - $discount, 2);
                        }

                        //Wallet manage

                        if (!empty($request->wallet)) {
                            $walletamount = $request->wallet;
                            $wallet = Wallet::where('user_id', $request->customer_id)->first();
                            if (!empty($wallet)) {
                                if ($walletamount >= $orderprice) {
                                    $walletamount = $orderprice;

                                    //Order when wallet is greater than order
                                    $global = Global_settings::all();

                                    $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();

                                    if (!empty($coupon)) {
                                        $promocode = Coupon::where('id', $coupon->coupon_id)->first();

                                        $coupon_history = new Coupon_history;
                                        $coupon_history->coupan_id = $coupon->coupon_id;
                                        $coupon_history->status = 1;
                                        $coupon_history->user_id = $request->customer_id;
                                        $coupon_history->save();
                                    }

                                    $customer_cart = Cart::where('user_id', $request->customer_id)->get();

                                    $orderprice = 0;
                                    foreach ($customer_cart as $value) {
                                        $product = Product::where('id', $value->product_id)->first();

                                        if ($value->quantity > $product->quantity) {
                                            $res['error']['message'] = "Product is out of stock";
                                            return response($res);
                                        }

                                        if (!empty($value->option_id)) {
                                            $product_detail = Product_details::where('id', $value->option_id)->first();

                                            if ($value->quantity > $product_detail->quantity) {
                                                $res['error']['message'] = "Product is out of stock";
                                                return response($res);
                                            }

                                            $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                                        } else {
                                            $orderprice = $orderprice + ($product->price) * $value->quantity;
                                        }
                                    }
                                    $product_sub_total = $orderprice;

                                    //Ship charge
                                    if ($orderprice > $global[0]->min_amount_shipping) {
                                        $orderprice = $orderprice;
                                        $ship_charge = 0;
                                    } else {
                                        $orderprice = $orderprice + $global[0]->shipping_charge;
                                        $ship_charge = $global[0]->shipping_charge;
                                    }

                                    //Delivery charge
                                    if ($request->payment_method == 'cod') {
                                        $orderprice = $orderprice + $global[0]->delivery_charge;
                                        $delivery_charge = $global[0]->delivery_charge;
                                    } else {
                                        $orderprice = $orderprice;
                                        $delivery_charge = 0;
                                    }

                                    //coupon code applied

                                    if (!empty($coupon) && !empty($promocode)) {
                                        $discount = ($orderprice / 100) * $promocode->discount;
                                        $orderprice = number_format($orderprice - $discount, 2);
                                    }

                                    //Quantity Decrease
                                    foreach ($customer_cart as $value) {
                                        $product = Product::where('id', $value->product_id)->first();
                                        $product_detail = Product_details::where('id', $value->option_id)->first();

                                        Product::where('id', $value->product_id)->update(['quantity' => $product->quantity - $value->quantity]);

                                        if (!empty($value->option_id)) {
                                            Product_details::where('id', $value->option_id)->update(['quantity' => $product_detail->quantity - $value->quantity]);
                                        }
                                    }

                                    //Wallet manage

                                    if (!empty($request->wallet)) {
                                        $walletamount = $request->wallet;
                                        $wallet = Wallet::where('user_id', $request->customer_id)->first();
                                        if (!empty($wallet)) {
                                            if ($walletamount >= $orderprice) {
                                                Wallet::where('user_id', $request->customer_id)->update(['amount' => $wallet->amount - $orderprice]);
                                                $walletamount = $orderprice;

                                                $history = new Wallet_recharge_history;
                                                $history->amount = $walletamount;
                                                $history->user_id = $request->customer_id;
                                                $history->type = 2;
                                                $history->reason = "Order";
                                                $history->reason_ar = "??????????";
                                                $history->save();
                                            } else {
                                                Wallet::where('user_id', $request->customer_id)->update(['amount' => $wallet->amount - $request->wallet]);
                                                $walletamount = $request->wallet;

                                                $history = new Wallet_recharge_history;
                                                $history->amount = $walletamount;
                                                $history->user_id = $request->customer_id;
                                                $history->type = 2;
                                                $history->reason = "Order";
                                                $history->reason_ar = "??????????";
                                                $history->save();
                                            }
                                        } else {
                                            $walletamount = 0;
                                        }
                                    } else {
                                        $walletamount = 0;
                                    }

                                    //Order Create

                                    $order = new Order;
                                    $order->payment_type = 4;
                                    $order->shipping_price = $ship_charge;
                                    $order->delivery_price = $delivery_charge;
                                    if (!empty($coupon)) {
                                        $order->coupan_id = $coupon->coupon_id;
                                    }
                                    $order->discount = $orderprice - ($product_sub_total + $ship_charge + $delivery_charge);
                                    $order->product_total_amount = $product_sub_total;
                                    $order->paid_by_wallet = $walletamount;
                                    $order->user_id = $request->customer_id;
                                    $order->address_id = $request->address_id;
                                    $order->price = $orderprice;
                                    $order->save();

                                    $ordertrack = new Order_track;
                                    $ordertrack->order_id = $order->id;
                                    $ordertrack->save();

                                    foreach ($customer_cart as $record) {
                                        $product = Product::where('id', $record->product_id)->first();
                                        $order_details = new Order_details;
                                        $order_details->order_id = $order->id;
                                        $order_details->user_id = $request->customer_id;
                                        $order_details->product_id = $record->product_id;
                                        if (!empty($record->option_id)) {
                                            $product_detail = Product_details::where('id', $value->option_id)->first();

                                            $order_details->color = $record->option_id;
                                            $order_details->price = $product_detail->price + $product->price;
                                        } else {
                                            $order_details->color = 'nocolor';
                                            $order_details->price = $product->price;
                                        }
                                        $order_details->quantity = $record->quantity;
                                        $order_details->product_name_en = $product->name_en;
                                        $order_details->product_name_ar = $product->name_ar;

                                        $order_details->save();
                                    }

                                    $userdata = User::where('id', $request->customer_id)->first();
                                    $name = $userdata->name;

                                    $Device_token = $userdata->device_token;
                                    $user_id = $userdata->id;
                                    $msg = array(
                                        'body' => "Your Order #" . $order->id . " is Processing",
                                        'title' => 'Order Info',
                                        'subtitle' => 'Letsbuy',
                                        'key' => '5',
                                        'vibrate' => 1,
                                        'sound' => 1,
                                        'largeIcon' => 'large_icon',
                                        'smallIcon' => 'small_icon',
                                    );
                                    $this->Notificationsend($Device_token, $msg, $user_id);

                                    $EmailTemplates = Emailtemplate::where('slug', 'order_completed')->first();
                                    $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
                                    $subject = $EmailTemplates->subject_en;
                                    $to_email = $userdata->email;
                                    $data = array();
                                    $data['msg'] = $message;
                                    Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                                        $message->to($to_email)
                                            ->subject($subject);
                                        $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
                                    });

                                    $user_mobile = User::select('mobile')->where('id', $request->customer_id)->first();

                                    $mobile = $user_mobile->mobile;

                                    $user = "letsbuy";
                                    $password = "Nn0450292**";
                                    $mobilenumbers = $mobile;
                                    if ($request->header('language') == 3) {
                                        $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
                                    } else {
                                        $message = ' ???? ?????????? ???????? ?????????? ?????????? . ?????? ?????????? ' . $order->id;
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

                                    Cart::where('user_id', $request->customer_id)->delete();

                                    $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();
                                    if (!empty($coupon)) {
                                        Coupon_applied::where('user_id', $request->customer_id)->delete();
                                    }

                                    $getaddress = Address::where('id', $request->address_id)->first();

                                    if ($request->header('language') == 3) {
                                        $res['success']['message'] = 'Order Placed Successfully';
                                        $res['order_id'] = $order->id;
                                        $res['total'] = $order->price;
                                        $res['fulladdress'] = $getaddress->fulladdress;
                                    } else {
                                        $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                                        $res['order_id'] = $order->id;
                                        $res['total'] = $order->price;
                                        $res['fulladdress'] = $getaddress->fulladdress;
                                    }
                                    return response($res);
                                } else {
                                    $walletamount = $request->wallet;
                                    $discountprice = $orderprice - $request->wallet;
                                }
                            } else {
                                $walletamount = 0;
                                $discountprice = $orderprice;
                            }
                        } else {
                            $walletamount = 0;
                            $discountprice = $orderprice;
                        }

                        $user_data = User::where('id', $request->customer_id)->first();

                        $transaction_id = "txn_" . rand(100000, 900000);
                        $order_id = "ord_" . rand(100000, 900000);

                        $curl = curl_init();

                        $payload = [];
                        $payload['amount'] = $discountprice;
                        $payload['currency'] = "SAR";
                        $payload['threeDSecure'] = true;
                        $payload['save_card'] = false;
                        $payload['description'] = "Test Description";
                        $payload['save_card'] = "statement_descriptor";
                        $payload['metadata']['udf1'] = $request->wallet;
                        $payload['metadata']['udf2'] = 4;
                        $payload['reference']['transaction'] = $transaction_id;
                        $payload['reference']['order'] = $order_id;
                        $payload['receipt']['email'] = false;
                        $payload['receipt']['email'] = true;
                        $payload['customer']['first_name'] = $user_data->name;
                        $payload['customer']['email'] = $user_data->email;
                        $payload['customer']['phone']['country_code'] = $request->address_id;
                        $payload['customer']['phone']['number'] = $user_data->mobile;
                        $payload['merchant']['id'] = "";
                        $payload['source']['id'] = "src_sa.mada";
                        $payload['post']['url'] = URL::to('/');
                        $payload['redirect']['url'] = URL::to('/') . '/api/payment/';

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => "https://api.tap.company/v2/charges",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => json_encode($payload),

                            CURLOPT_HTTPHEADER => array(
                                "authorization: Bearer sk_live_tA8OicMD2sbKCkqxXlmp4TBU",
                                "content-type: application/json",
                            ),
                        ));

                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        $resp = json_decode($response);
                        curl_close($curl);
                        if ($err) {
                            if ($request->header('language') == 3) {
                                $res['success']['error'] = 'Server Error';
                            } else {
                                $res['success']['error'] = '?????? ???? ????????????';
                            }
                            return response($res);
                        } else {
                            if ($request->header('language') == 3) {
                                $res['success']['message'] = 'Order Processed Successfully';
                                $res['success']['url'] = $resp->transaction->url;
                            } else {
                                $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                                $res['success']['url'] = $resp->transaction->url;
                            }
                            return response($res);
                        }
                    }
                }
            }
        }
    }

    public function payment(Request $request)
    {
        if (empty($request->tap_id)) {
            return redirect('/');
        }
        $charge_id = $request->tap_id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges/" . $charge_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_live_tA8OicMD2sbKCkqxXlmp4TBU",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $result = json_decode($response, true);
            if ($result['status'] == 'CAPTURED') {
                $global = Global_settings::all();
                $useremail = User::where('email', $result['customer']['email'])->first();
                $customer_id = $useremail->id;

                $coupon = Coupon_applied::where('user_id', $customer_id)->first();

                if (!empty($coupon)) {
                    $promocode = Coupon::where('id', $coupon->coupon_id)->first();

                    $coupon_history = new Coupon_history;
                    $coupon_history->coupan_id = $coupon->coupon_id;
                    $coupon_history->status = 1;
                    $coupon_history->user_id = $customer_id;
                    $coupon_history->save();
                }

                $customer_cart = Cart::where('user_id', $customer_id)->get();

                $orderprice = 0;
                foreach ($customer_cart as $value) {
                    $product = Product::where('id', $value->product_id)->first();

                    if ($value->quantity > $product->quantity) {
                        $res['error']['message'] = "Product is out of stock";
                        return response($res);
                    }

                    if (!empty($value->option_id)) {
                        $product_detail = Product_details::where('id', $value->option_id)->first();

                        if ($value->quantity > $product_detail->quantity) {
                            $res['error']['message'] = "Product is out of stock";
                            return response($res);
                        }

                        $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                    } else {
                        $orderprice = $orderprice + ($product->price) * $value->quantity;
                    }
                }
                $product_sub_total = $orderprice;

                //Ship charge
                if ($orderprice > $global[0]->min_amount_shipping) {
                    $orderprice = $orderprice;
                    $ship_charge = 0;
                } else {
                    $orderprice = $orderprice + $global[0]->shipping_charge;
                    $ship_charge = $global[0]->shipping_charge;
                }

                //Delivery charge
                $delivery_charge = 0;

                //coupon code applied

                if (!empty($coupon) && !empty($promocode)) {
                    $discount = ($orderprice / 100) * $promocode->discount;
                    $orderprice = number_format($orderprice - $discount, 2);
                }

                //Quantity Decrease
                foreach ($customer_cart as $value) {
                    $product = Product::where('id', $value->product_id)->first();
                    $product_detail = Product_details::where('id', $value->option_id)->first();

                    Product::where('id', $value->product_id)->update(['quantity' => $product->quantity - $value->quantity]);

                    if (!empty($value->option_id)) {
                        Product_details::where('id', $value->option_id)->update(['quantity' => $product_detail->quantity - $value->quantity]);
                    }
                }

                // Wallet manage

                if (!empty($result['metadata']['udf1'])) {
                    $walletamount = $result['metadata']['udf1'];
                    $wallet = Wallet::where('user_id', $customer_id)->first();
                    if (!empty($wallet)) {
                        if ($walletamount >= $orderprice) {
                            Wallet::where('user_id', $customer_id)->update(['amount' => $wallet->amount - $orderprice]);
                            $walletamount = $orderprice;

                            $history = new Wallet_recharge_history;
                            $history->amount = $walletamount;
                            $history->user_id = $customer_id;
                            $history->type = 2;
                            $history->reason = "Order";
                            $history->reason_ar = "??????????";
                            $history->save();

                        } else {
                            Wallet::where('user_id', $customer_id)->update(['amount' => $wallet->amount - $result['metadata']['udf1']]);
                            $walletamount = $result['metadata']['udf1'];

                            $history = new Wallet_recharge_history;
                            $history->amount = $walletamount;
                            $history->user_id = $customer_id;
                            $history->type = 2;
                            $history->reason = "Order";
                            $history->reason_ar = "??????????";
                            $history->save();

                        }
                    } else {
                        $walletamount = 0;
                    }
                } else {
                    $walletamount = 0;
                }

                //Order Create

                $order = new Order;
                $order->payment_type = $result['metadata']['udf2'];
                $order->shipping_price = $ship_charge;
                $order->delivery_price = $delivery_charge;
                if (!empty($coupon)) {
                    $order->coupan_id = $coupon->coupon_id;
                }
                $order->discount = $orderprice - ($product_sub_total + $ship_charge + $delivery_charge);
                $order->product_total_amount = $product_sub_total;
                $order->paid_by_wallet = $walletamount;
                $order->user_id = $customer_id;
                $order->address_id = $result['customer']['phone']['country_code'];
                $order->price = $orderprice;
                $order->save();

                $ordertrack = new Order_track;
                $ordertrack->order_id = $order->id;
                $ordertrack->save();

                foreach ($customer_cart as $record) {
                    $product = Product::where('id', $record->product_id)->first();
                    $order_details = new Order_details;
                    $order_details->order_id = $order->id;
                    $order_details->user_id = $customer_id;
                    $order_details->product_id = $record->product_id;
                    if (!empty($record->option_id)) {
                        $product_detail = Product_details::where('id', $value->option_id)->first();

                        $order_details->color = $record->option_id;
                        $order_details->price = $product_detail->price + $product->price;
                    } else {
                        $order_details->color = 'nocolor';
                        $order_details->price = $product->price;
                    }
                    $order_details->quantity = $record->quantity;
                    $order_details->product_name_en = $product->name_en;
                    $order_details->product_name_ar = $product->name_ar;

                    $order_details->save();
                }

                $userdata = User::where('id', $customer_id)->first();
                $name = $userdata->name;

                $Device_token = $userdata->device_token;
                $user_id = $userdata->id;
                $msg = array(
                    'body' => "Your Order #" . $order->id . " is Processing",
                    'title' => 'Order Info',
                    'subtitle' => 'Letsbuy',
                    'key' => '5',
                    'vibrate' => 1,
                    'sound' => 1,
                    'largeIcon' => 'large_icon',
                    'smallIcon' => 'small_icon',
                );
                $this->Notificationsend($Device_token, $msg, $user_id);

                $EmailTemplates = Emailtemplate::where('slug', 'order_completed')->first();
                $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
                $subject = $EmailTemplates->subject_en;
                $to_email = $userdata->email;
                $data = array();
                $data['msg'] = $message;
                Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                    $message->to($to_email)
                        ->subject($subject);
                    $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
                });

                $user_mobile = User::select('mobile')->where('id', $customer_id)->first();

                $mobile = $user_mobile->mobile;

                $user = "letsbuy";
                $password = "Nn0450292**";
                $mobilenumbers = $mobile;
                if ($request->header('language') == 3) {
                    $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
                } else {
                    $message = ' ???? ?????????? ???????? ?????????? ?????????? . ?????? ?????????? ' . $order->id;
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

                if (!empty($result)) {
                    $transaction = new Transaction_details;
                    $transaction->charge_id = $result['id'];
                    $transaction->payment_status = $result['status'];
                    $transaction->user_id = $customer_id;
                    $transaction->order_id = $order->id;
                    $transaction->amount = $result['amount'];
                    $transaction->currency = $result['currency'];
                    $transaction->track_id = $result['reference']['track'];
                    $transaction->payment_id = $result['reference']['payment'];
                    $transaction->transaction_generate_id = $result['reference']['transaction'];
                    $transaction->order_generate_id = $result['reference']['order'];
                    $transaction->receipt_id = $result['receipt']['id'];
                    $transaction->payment_method = $result['source']['payment_method'];
                    $transaction->payment_type = $result['source']['payment_type'];
                    $transaction->token_id = $result['source']['id'];

                    $transaction->save();
                }

                $getaddress = Address::where('id', $result['customer']['phone']['country_code'])->first();
                Cart::where('user_id', $customer_id)->delete();

                $coupon = Coupon_applied::where('user_id', $customer_id)->first();
                if (!empty($coupon)) {
                    Coupon::where('id', $coupon->coupon_id)->delete();
                }

                if ($request->header('language') == 3) {
                    $res['success']['message'] = 'Order Placed Successfully';
                    $res['order_id'] = $order->id;
                    $res['total'] = $order->price;
                    $res['fulladdress'] = $getaddress->fulladdress;
                } else {
                    $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                    $res['order_id'] = $order->id;
                    $res['total'] = $order->price;
                    $res['fulladdress'] = $getaddress->fulladdress;
                }
                return response($res);
            }
        }
    }

    public function checkout_removeproduct(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'product_id' => "Please Enter Product ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'product_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'product_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $product = Cart::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->where('option_id', $request->option_id)->first();
        if (!empty($product)) {
            $product = Cart::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->where('option_id', $request->option_id)->delete();
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Remove successfully";
            } else {
                $res['success']['message'] = "?????? ?????????????? ??????????";
            }

            $address_id = $request['address_id'] ? $request['address_id'] : 0;

            $address = DB::table('address')->select('id as address_id', 'user_id as customer_id', 'fulladdress', 'address_1 as street_name', 'fullname', 'mobile as telephone', 'city', 'state as zone_id', 'postcode', 'otp_verify as is_telephone_approved', 'country_id', 'is_default as default', DB::raw('(select iso_code_2 from country where country.country_id=address.country_id)as country'), DB::raw('(select iso_code_3 from country where country.country_id=address.country_id)as country'), DB::raw('(select name from country where country.country_id=address.country_id)as country'), DB::raw('(select name from zone where zone.zone_id=address.state)as zone'), DB::raw('(select code from zone where zone.zone_id=address.state)as zone_code'))->where('user_id', $request->customer_id)->get();
            $wallet = Wallet::where('user_id', $request->customer_id)->first();
            $res['addresses'] = $address;
            $res['selected_address'] = $request->address_id;
            if (!empty($wallet)) {
                $res['mywallet'] = $wallet->amount;
            } else {
                $res['mywallet'] = 0;
            }
            $global = Global_settings::all();
            $res['payment_method']['cod']['code'] = "cod";
            $res['payment_method']['cod']['title'] = "Cash On Delivery ( " . $global[0]->delivery_charge . " SR Extra fee )";
            if ($request->payment_method == 'cod') {
                $res['payment_method']['cod']['selected'] = true;
                $res['totals']['Cash-On-Delivery'] = $global[0]->delivery_charge;
            } else {
                $res['payment_method']['cod']['selected'] = false;
            }
            $res['payment_method']['bank_transfer']['code'] = "bank_transfer";
            $res['payment_method']['bank_transfer']['title'] = "Bank Transfer";
            $res['payment_method']['bank_transfer']['bank_transfer'] = "National Commercial Bank<br />\r\nIBAN (SA 09 1000 0013 8471 8800 6605)<br />\r\n_____________________________________________<br />\r\n<br />\r\nAl Rajhi Bank<br />\r\nIBAN (SA 10 8000 0376 6080 1093 5845)";
            if ($request->payment_method == 'bank_transfer') {
                $res['payment_method']['bank_transfer']['selected'] = true;
            } else {
                $res['payment_method']['bank_transfer']['selected'] = false;
            }
            $res['payment_method']['tap-card']['code'] = "tap-card";
            $res['payment_method']['tap-card']['title'] = "<img src=\"https://www.gotapnow.com/web/tap.png\" />";
            if ($request->payment_method == 'tap-card') {
                $res['payment_method']['tap-card']['selected'] = true;
            } else {
                $res['payment_method']['tap-card']['selected'] = false;
            }
            $res['payment_method']['tap-mada']['code'] = "tap-mada";
            $res['payment_method']['tap-mada']['title'] = "<img src=\"https://www.gotapnow.com/web/tap.png\" />";
            if ($request->payment_method == 'tap-mada') {
                $res['payment_method']['tap-mada']['selected'] = true;
            } else {
                $res['payment_method']['tap-mada']['selected'] = false;
            }

            $paymentmethod = [];

            foreach ($res['payment_method'] as $allpayment) {
                array_push($paymentmethod, $allpayment);
            }

            $res['payment_method'] = $paymentmethod;

            $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();

            if (!empty($coupon)) {
                $promocode = Coupon::where('id', $coupon->coupon_id)->first();
                if (!empty($promocode)) {
                    $res['coupon'] = $promocode->code;
                } else {
                    $res['coupon'] = '';
                }
            } else {
                $res['coupon'] = '';
            }

            $customer_cart = Cart::where('user_id', $request->customer_id)->get();

            $orderprice = 0;
            foreach ($customer_cart as $value) {
                $product = Product::where('id', $value->product_id)->first();

                if (!empty($value->option_id)) {
                    $product_detail = Product_details::where('id', $value->option_id)->first();

                    $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                } else {
                    $orderprice = $orderprice + ($product->price) * $value->quantity;
                }
            }
            $res['totals']['Sub-Total'] = $orderprice;

            if ($orderprice > $global[0]->min_amount_shipping) {
                $orderprice = $orderprice;
            } else {
                $orderprice = $orderprice + $global[0]->shipping_charge;
                $res['totals']['Shipping-charge'] = $global[0]->shipping_charge;
            }

            if ($request->payment_method == 'cod') {
                $res['totals']['Total'] = $orderprice + $global[0]->delivery_charge;
            } else {
                $res['totals']['Total'] = $orderprice;
            }

            if (!empty($request->wallet)) {
                $walletamount = $request->wallet;
                $wallet = Wallet::where('user_id', $request->customer_id)->first();
                if (!empty($wallet)) {
                    if ($walletamount >= $orderprice) {
                        $orderprice = 0;
                    } else {
                        $orderprice = $orderprice - $walletamount;
                    }
                }
            }

            if (!empty($coupon) && !empty($promocode)) {
                $discount = ($orderprice / 100) * $promocode->discount;
                $res['totals']['Discount'] = number_format($discount, 2);
                $res['totals']['Total'] = number_format($orderprice - $discount, 2);
            } else {
                $res['totals']['Total'] = $orderprice;
            }

            $paymenttotals = [];

            foreach ($res['totals'] as $key => $alltotal) {
                $datapush['title'] = $key;
                $datapush['text'] = $alltotal;
                array_push($paymenttotals, $datapush);
            }

            $res['totals'] = $paymenttotals;

            if ($request->header('language') == 3) {
                $cartdata = Cart::
                    leftjoin('products', 'products.id', '=', 'cart.product_id')
                    ->select('products.id as product_id', 'products.name_en as name', 'cart.quantity', 'products.offer_price as special', 'products.price as price', 'products.price as price', 'cart.option_id as option_id', 'products.img as image', 'products.discount_available as percentage')->where('cart.user_id', $request->customer_id)->get();
            } else {
                $cartdata = Cart::
                    leftjoin('products', 'products.id', '=', 'cart.product_id')
                    ->select('products.id as product_id', 'products.name_ar as name', 'cart.quantity', 'products.offer_price as special', 'products.price as price', 'products.price as price', 'cart.option_id as option_id', 'products.img as image', 'products.discount_available as percentage')->where('cart.user_id', $request->customer_id)->get();
            }
            $res['product_path'] = URL::to('/') . '/public/product_images/';

            $allproducts = [];

            foreach ($cartdata as $recorddata) {
                $data['product_id'] = $recorddata->product_id;
                $data['name'] = $recorddata->name;
                $data['quantity'] = $recorddata->quantity;
                $data['image'] = $recorddata->image;
                $data['special'] = $recorddata->special;
                $data['option_id'] = $recorddata->option_id;
                $data['price'] = $recorddata->price;
                $data['percentage'] = $recorddata->percentage;
                if ($recorddata->option_id == 'nocolor') {
                    $data['color'] = '';
                    $data['option_name'] = '';
                    $data['option_value'] = '';
                } elseif (empty($recorddata->option_id)) {
                    $data['color'] = '';
                    $data['option_name'] = '';
                    $data['option_value'] = '';
                } else {
                    $details = Product_details::where('id', $recorddata->option_id)->first();
                    if (!empty($details->color)) {
                        $data['color'] = $details->color;
                    } else {
                        $data['color'] = '';
                    }
                    $option = Option::where('id', $details->option_id)->first();
                    if (!empty($option)) {
                        $data['option_name'] = $option->name_en;
                    } else {
                        $data['option_name'] = '';
                    }

                    $option_value = Option_value::where('id', $details->option_value)->first();

                    if (!empty($option_value)) {
                        $data['option_value'] = $option_value->value;
                    } else {
                        $data['option_value'] = '';
                    }
                }

                array_push($allproducts, $data);
            }

            $res['products'] = $allproducts;

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Product Removed Successfully";
            } else {
                $res['success']['message'] = "?????? ?????????? ???????????? ??????????";
            }

            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Product not available in our record";
            } else {
                $res['error']['message'] = "???????????? ?????? ?????????? ???? ??????????";
            }

            return response($res);
        }
    }

    public function checkout_movetowishlit(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'product_id' => "Please Enter Product ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'product_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'product_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $cart = Cart::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->first();

        if (!empty($cart)) {
            $product = Product::where('id', $request->product_id)->first();

            $wishlist = new Wishlist;
            $wishlist->user_id = $request->customer_id;
            $wishlist->product_id = $request->product_id;
            $wishlist->option_id = $request->option_id;
            $wishlist->category_id = $product->category_id;
            $wishlist->subcategory_id = $product->subcategory_id;
            $wishlist->save();

            if (!empty($request->option_id)) {
                Cart::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->where('option_id', $request->option_id)->delete();
            } else {
                Cart::where('user_id', $request->customer_id)->where('product_id', $request->product_id)->delete();
            }

            $address_id = $request['address_id'] ? $request['address_id'] : 0;

            $address = DB::table('address')->select('id as address_id', 'user_id as customer_id', 'fulladdress', 'address_1 as street_name', 'fullname', 'mobile as telephone', 'city', 'state as zone_id', 'postcode', 'otp_verify as is_telephone_approved', 'country_id', 'is_default as default', DB::raw('(select iso_code_2 from country where country.country_id=address.country_id)as country'), DB::raw('(select iso_code_3 from country where country.country_id=address.country_id)as country'), DB::raw('(select name from country where country.country_id=address.country_id)as country'), DB::raw('(select name from zone where zone.zone_id=address.state)as zone'), DB::raw('(select code from zone where zone.zone_id=address.state)as zone_code'))->where('user_id', $request->customer_id)->get();
            $wallet = Wallet::where('user_id', $request->customer_id)->first();
            $res['addresses'] = $address;
            $res['selected_address'] = $request->address_id;
            if (!empty($wallet)) {
                $res['mywallet'] = $wallet->amount;
            } else {
                $res['mywallet'] = 0;
            }
            $global = Global_settings::all();
            $res['payment_method']['cod']['code'] = "cod";
            $res['payment_method']['cod']['title'] = "Cash On Delivery ( " . $global[0]->delivery_charge . " SR Extra fee )";
            if ($request->payment_method == 'cod') {
                $res['payment_method']['cod']['selected'] = true;
                $res['totals']['Cash-On-Delivery'] = $global[0]->delivery_charge;
            } else {
                $res['payment_method']['cod']['selected'] = false;
            }
            $res['payment_method']['bank_transfer']['code'] = "bank_transfer";
            $res['payment_method']['bank_transfer']['title'] = "Bank Transfer";
            $res['payment_method']['bank_transfer']['bank_transfer'] = "National Commercial Bank<br />\r\nIBAN (SA 09 1000 0013 8471 8800 6605)<br />\r\n_____________________________________________<br />\r\n<br />\r\nAl Rajhi Bank<br />\r\nIBAN (SA 10 8000 0376 6080 1093 5845)";
            if ($request->payment_method == 'bank_transfer') {
                $res['payment_method']['bank_transfer']['selected'] = true;
            } else {
                $res['payment_method']['bank_transfer']['selected'] = false;
            }
            $res['payment_method']['tap-card']['code'] = "tap-card";
            $res['payment_method']['tap-card']['title'] = "<img src=\"https://www.gotapnow.com/web/tap.png\" />";
            if ($request->payment_method == 'tap-card') {
                $res['payment_method']['tap-card']['selected'] = true;
            } else {
                $res['payment_method']['tap-card']['selected'] = false;
            }
            $res['payment_method']['tap-mada']['code'] = "tap-mada";
            $res['payment_method']['tap-mada']['title'] = "<img src=\"https://www.gotapnow.com/web/tap.png\" />";
            if ($request->payment_method == 'tap-mada') {
                $res['payment_method']['tap-mada']['selected'] = true;
            } else {
                $res['payment_method']['tap-mada']['selected'] = false;
            }

            $paymentmethod = [];

            foreach ($res['payment_method'] as $allpayment) {
                array_push($paymentmethod, $allpayment);
            }

            $res['payment_method'] = $paymentmethod;

            $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();

            if (!empty($coupon)) {
                $promocode = Coupon::where('id', $coupon->coupon_id)->first();
                if (!empty($promocode)) {
                    $res['coupon'] = $promocode->code;
                } else {
                    $res['coupon'] = '';
                }
            } else {
                $res['coupon'] = '';
            }

            $customer_cart = Cart::where('user_id', $request->customer_id)->get();

            $orderprice = 0;
            foreach ($customer_cart as $value) {
                $product = Product::where('id', $value->product_id)->first();

                if (!empty($value->option_id)) {
                    $product_detail = Product_details::where('id', $value->option_id)->first();

                    $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                } else {
                    $orderprice = $orderprice + ($product->price) * $value->quantity;
                }
            }
            $res['totals']['Sub-Total'] = $orderprice;

            if ($orderprice > $global[0]->min_amount_shipping) {
                $orderprice = $orderprice;
            } else {
                $orderprice = $orderprice + $global[0]->shipping_charge;
                $res['totals']['Shipping-charge'] = $global[0]->shipping_charge;
            }

            if ($request->payment_method == 'cod') {
                $res['totals']['Total'] = $orderprice + $global[0]->delivery_charge;
            } else {
                $res['totals']['Total'] = $orderprice;
            }

            if (!empty($request->wallet)) {
                $walletamount = $request->wallet;
                $wallet = Wallet::where('user_id', $request->customer_id)->first();
                if (!empty($wallet)) {
                    if ($walletamount >= $orderprice) {
                        $orderprice = 0;
                    } else {
                        $orderprice = $orderprice - $walletamount;
                    }
                }
            }

            if (!empty($coupon) && !empty($promocode)) {
                $discount = ($orderprice / 100) * $promocode->discount;
                $res['totals']['Discount'] = number_format($discount, 2);
                $res['totals']['Total'] = number_format($orderprice - $discount, 2);
            } else {
                $res['totals']['Total'] = $orderprice;
            }

            $paymenttotals = [];

            foreach ($res['totals'] as $key => $alltotal) {
                $datapush['title'] = $key;
                $datapush['text'] = $alltotal;
                array_push($paymenttotals, $datapush);
            }

            $res['totals'] = $paymenttotals;

            if ($request->header('language') == 3) {
                $cartdata = Cart::
                    leftjoin('products', 'products.id', '=', 'cart.product_id')
                    ->select('products.id as product_id', 'products.name_en as name', 'cart.quantity', 'products.offer_price as special', 'products.price as price', 'products.price as price', 'cart.option_id as option_id', 'products.img as image', 'products.discount_available as percentage')->where('cart.user_id', $request->customer_id)->get();
            } else {
                $cartdata = Cart::
                    leftjoin('products', 'products.id', '=', 'cart.product_id')
                    ->select('products.id as product_id', 'products.name_ar as name', 'cart.quantity', 'products.offer_price as special', 'products.price as price', 'products.price as price', 'cart.option_id as option_id', 'products.img as image', 'products.discount_available as percentage')->where('cart.user_id', $request->customer_id)->get();
            }
            $res['product_path'] = URL::to('/') . '/public/product_images/';

            $allproducts = [];

            foreach ($cartdata as $recorddata) {
                $data['product_id'] = $recorddata->product_id;
                $data['name'] = $recorddata->name;
                $data['quantity'] = $recorddata->quantity;
                $data['option_id'] = $recorddata->option_id;
                $data['image'] = $recorddata->image;
                $data['special'] = $recorddata->special;
                $data['price'] = $recorddata->price;
                $data['percentage'] = $recorddata->percentage;
                if ($recorddata->option_id == 'nocolor') {
                    $data['color'] = '';
                    $data['option_name'] = '';
                    $data['option_value'] = '';
                } elseif (empty($recorddata->option_id)) {
                    $data['color'] = '';
                    $data['option_name'] = '';
                    $data['option_value'] = '';
                } else {
                    $details = Product_details::where('id', $recorddata->option_id)->first();
                    if (!empty($details->color)) {
                        $data['color'] = $details->color;
                    } else {
                        $data['color'] = '';
                    }
                    $option = Option::where('id', $details->option_id)->first();
                    if (!empty($option)) {
                        $data['option_name'] = $option->name_en;
                    } else {
                        $data['option_name'] = '';
                    }

                    $option_value = Option_value::where('id', $details->option_value)->first();

                    if (!empty($option_value)) {
                        $data['option_value'] = $option_value->value;
                    } else {
                        $data['option_value'] = '';
                    }
                }

                array_push($allproducts, $data);
            }

            $res['products'] = $allproducts;

            if ($request->header('language') == 3) {
                $res['success']['message'] = "Product Moved To Successfully";
            } else {
                $res['success']['message'] = "???? ?????? ???????????? ??????????";
            }

            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Product not available in our record.";
            } else {
                $res['error']['message'] = "???????????? ?????? ?????????? ???? ??????????.";
            }
            return response($res);
        }
    }

    public function orderstatuscheck(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['charge_id' => "Please Enter Charge ID",
            ];
        } else {
            $messages = ['charge_id' => "???????????? ?????????? ?????????? ??????????",
            ];
        }

        $rules = ['charge_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $transaction = Transaction_details::where('charge_id', $request->charge_id)->first();

        if (!empty($transaction)) {
            $order = Order::where('id', $transaction->order_id)->first();
            $getaddress = Address::where('id', $order->address_id)->first();

            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Order Placed Successfully';
                $res['order_id'] = $order->id;
                $res['total'] = number_format($order->price, 2);
                $res['fulladdress'] = $getaddress->fulladdress;
            } else {
                $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                $res['order_id'] = $order->id;
                $res['total'] = number_format($order->price, 2);

                $res['fulladdress'] = $getaddress->fulladdress;
            }
            return response($res);
        } else {
            $res['status'] = 0;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Order Not Completed Successfully.";
            } else {
                $res['success']['message'] = "?????????? ???? ?????????? ??????????";
            }
            return response($res);
        }
    }

    public function wallet(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $wallet = Wallet::where('user_id', $request->customer_id)->first();
        if (!empty($wallet)) {
            $amount = number_format($wallet->amount, 2);
        } else {
            $amount = 0;
        }

        $wallettransaction = Wallet_recharge_history::where('user_id', $request->customer_id)->orderby('id', 'desc')->get();

        $res['payment_method']['tap-card']['code'] = "tap-card";
        $res['payment_method']['tap-card']['title'] = "<img src=\"https://www.gotapnow.com/web/tap.png\" />";
        $res['payment_method']['tap-mada']['code'] = "tap-mada";
        $res['payment_method']['tap-mada']['title'] = "<img src=\"https://www.gotapnow.com/web/tap.png\" />";

        $paymentmethod = [];

        foreach ($res['payment_method'] as $allpayment) {
            array_push($paymentmethod, $allpayment);
        }

        $res['payment_method'] = $paymentmethod;

        if ($request->header('language') == 3) {
            $res['success']['message'] = 'Wallet List Successfully';
            $res['amount'] = $amount;
            $res['wallettransaction'] = $wallettransaction;
        } else {
            $res['success']['message'] = '?????????? ?????????????? ??????????';
            $res['amount'] = $amount;
            $res['wallettransaction'] = $wallettransaction;
        }
        return response($res);

    }

    public function walletpayment(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'payment_type' => "Please Enter Payment Type",
                'amount' => "Please Enter Amount",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'payment_type' => "???????????? ?????????? ?????? ??????????",
                'amount' => "???????????? ?????????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'payment_type' => 'required', 'amount' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $user_data = User::where('id', $request->customer_id)->first();

        $transaction_id = "txn_" . rand(100000, 900000);
        $order_id = "recharge_" . rand(100000, 900000);

        $curl = curl_init();

        $payload = [];
        $payload['amount'] = $request->amount;
        $payload['currency'] = "SAR";
        $payload['threeDSecure'] = true;
        $payload['save_card'] = false;
        $payload['description'] = "Test Description";
        $payload['save_card'] = "statement_descriptor";
        $payload['metadata']['udf1'] = $request->customer_id;
        $payload['metadata']['udf2'] = 'test';
        $payload['reference']['transaction'] = $transaction_id;
        $payload['reference']['order'] = $order_id;
        $payload['receipt']['email'] = false;
        $payload['receipt']['email'] = true;
        $payload['customer']['first_name'] = $user_data->name;
        $payload['customer']['email'] = $user_data->email;
        $payload['customer']['phone']['country_code'] = '';
        $payload['customer']['phone']['number'] = $user_data->mobile;
        $payload['merchant']['id'] = "";
        if ($request->payment_type == 'tap-card') {
            $payload['source']['id'] = "src_card";
        } else {
            $payload['source']['id'] = "src_sa.mada";
        }
        $payload['post']['url'] = URL::to('/');
        $payload['redirect']['url'] = URL::to('/') . '/api/rechargepayment/';

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),

            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_live_tA8OicMD2sbKCkqxXlmp4TBU",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $resp = json_decode($response);
        curl_close($curl);
        if ($err) {
            if ($request->header('language') == 3) {
                $res['success']['error'] = 'Server Error';
            } else {
                $res['success']['error'] = '?????? ???? ????????????';
            }
            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Order Processed Successfully';
                $res['success']['url'] = $resp->transaction->url;
            } else {
                $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                $res['success']['url'] = $resp->transaction->url;
            }
            return response($res);
        }
    }

    public function walletpaymentapple(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'transaction_id' => "Please Enter Transaction ID",
                'amount' => "Please Enter Amount",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'transaction_id' => "???????????? ?????????? ???????? ????????????????",
                'amount' => "???????????? ?????????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'transaction_id' => 'required', 'amount' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $userdata = User::where('id', $request->customer_id)->first();

        $Device_token = $userdata->device_token;
        $user_id = $userdata->id;
        $msg = array(
            'body' => "Your Recharge is Successfully Completed",
            'title' => 'Recharge Info',
            'subtitle' => 'Letsbuy',
            'key' => '5',
            'vibrate' => 1,
            'sound' => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon',
        );
        $this->Notificationsend($Device_token, $msg, $user_id);

        $name = $userdata->name;

        $EmailTemplates = Emailtemplate::where('slug', 'recharge_completed')->first();
        $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
        $subject = $EmailTemplates->subject_en;
        $to_email = $userdata->email;
        $data = array();
        $data['msg'] = $message;
        Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
            $message->to($to_email)
                ->subject($subject);
            $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
        });

        $user_mobile = User::select('mobile')->where('id', $user_id)->first();

        $mobile = $user_mobile->mobile;

        $user = "letsbuy";
        $password = "Nn0450292**";
        $mobilenumbers = $mobile;
        if ((\App::getLocale() == 'en')) {
            $message = 'Your Wallet Recharege is Successfully Completed Amount :' . $request->amount;
        } else {
            $message = ' ?????????? ???????????? ?????????????? ???????????? ???? ???? ???????????? ?????????? ' . $request->amount;
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

        $walletrecharge = new Wallet_recharge_history;
        $walletrecharge->amount = $request->amount;
        $walletrecharge->user_id = $user_id;
        $walletrecharge->type = 1;
        $walletrecharge->reason = "Recharge";
        $walletrecharge->reason_ar = "?????????? ????????";
        $walletrecharge->save();

        $walletamount = Wallet::where('user_id', $user_id)->first();
        if (!empty($walletamount)) {
            Wallet::where('user_id', $user_id)->update(['amount' => $request->amount + $walletamount->amount]);
        } else {
            $walletupdate = new Wallet;
            $walletupdate->amount = $request->amount;
            $walletupdate->user_id = $user_id;
            $walletupdate->save();
        }

        $transaction = new Transaction_details;
        $transaction->transaction_generate_id = $request->transaction_id;
        $transaction->is_applepay = 1;

        $transaction->save();

        if ($request->header('language') == 3) {
            $res['success']['message'] = 'Recharge Completed Successfully';
        } else {
            $res['success']['message'] = '???????????? ?????????? ?????????? ??????????';
        }
        return response($res);

    }

    public function rechargepayment(Request $request)
    {

        if (empty($request->tap_id)) {
            return redirect('/');
        }

        $charge_id = $request->tap_id;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.tap.company/v2/charges/" . $charge_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer sk_live_tA8OicMD2sbKCkqxXlmp4TBU",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $res['status'] = 0;
            if ($request->header('language') == 3) {
                $res['success']['error'] = "Recharge Not Completed Successfully.";
            } else {
                $res['success']['error'] = "?????????? ?????????? ???? ?????????? ??????????";
            }
            return response($res);

        } else {
            $result = json_decode($response, true);
            if ($result['status'] == 'CAPTURED') {
                $userdata = User::where('id', $result['metadata']['udf1'])->first();
                $Device_token = $userdata->device_token;
                $user_id = $userdata->id;
                $msg = array(
                    'body' => "Your Recharge is Successfully Completed",
                    'title' => 'Recharge Info',
                    'subtitle' => 'Letsbuy',
                    'key' => '5',
                    'vibrate' => 1,
                    'sound' => 1,
                    'largeIcon' => 'large_icon',
                    'smallIcon' => 'small_icon',
                );
                $this->Notificationsend($Device_token, $msg, $user_id);

                $name = $userdata->name;

                $EmailTemplates = Emailtemplate::where('slug', 'recharge_completed')->first();
                $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
                $subject = $EmailTemplates->subject_en;
                $to_email = $userdata->email;
                $data = array();
                $data['msg'] = $message;
                Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                    $message->to($to_email)
                        ->subject($subject);
                    $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
                });

                $user_mobile = User::select('mobile')->where('id', $result['metadata']['udf1'])->first();

                $mobile = $user_mobile->mobile;

                $user = "letsbuy";
                $password = "Nn0450292**";
                $mobilenumbers = $mobile;
                if ((\App::getLocale() == 'en')) {
                    $message = 'Your Wallet Recharege is Successfully Completed Amount :' . $result['amount'];
                } else {
                    $message = ' ?????????? ???????????? ?????????????? ???????????? ???? ???? ???????????? ?????????? ' . $result['amount'];
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

                $walletrecharge = new Wallet_recharge_history;
                $walletrecharge->amount = $result['amount'];
                $walletrecharge->user_id = $result['metadata']['udf1'];
                $walletrecharge->type = 1;
                $walletrecharge->reason = "Recharge";
                $walletrecharge->reason_ar = "?????????? ????????";
                $walletrecharge->save();

                $walletamount = Wallet::where('user_id', $result['metadata']['udf1'])->first();
                if (!empty($walletamount)) {
                    Wallet::where('user_id', $result['metadata']['udf1'])->update(['amount' => $result['amount'] + $walletamount->amount]);
                } else {
                    $walletupdate = new Wallet;
                    $walletupdate->amount = $result['amount'];
                    $walletupdate->user_id = $result['metadata']['udf1'];
                    $walletupdate->save();
                }

                if (!empty($result)) {
                    $transaction = new Transaction_details;
                    $transaction->charge_id = $result['id'];
                    $transaction->payment_status = $result['status'];
                    $transaction->user_id = $result['metadata']['udf1'];
                    $transaction->order_id = $walletrecharge->id;
                    $transaction->amount = $result['amount'];
                    $transaction->currency = $result['currency'];
                    $transaction->track_id = $result['reference']['track'];
                    $transaction->payment_id = $result['reference']['payment'];
                    $transaction->transaction_generate_id = $result['reference']['transaction'];
                    $transaction->order_generate_id = $result['reference']['order'];
                    $transaction->receipt_id = $result['receipt']['id'];
                    $transaction->payment_method = $result['source']['payment_method'];
                    $transaction->payment_type = $result['source']['payment_type'];
                    $transaction->token_id = $result['source']['id'];

                    $transaction->save();
                }

                if ($request->header('language') == 3) {
                    $res['success']['message'] = 'Recharge Completed Successfully';
                } else {
                    $res['success']['message'] = '???????????? ?????????? ?????????? ??????????';
                }
                return response($res);
            }
        }

    }

    public function rechargestatuscheck(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['charge_id' => "Please Enter Charge ID",
            ];
        } else {
            $messages = ['charge_id' => "???????????? ?????????? ?????????? ??????????",
            ];
        }

        $rules = ['charge_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $transaction = Transaction_details::where('charge_id', $request->charge_id)->first();

        if (!empty($transaction)) {
            $res['status'] = 1;
            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Recharge Completed Successfully';
            } else {
                $res['success']['message'] = "???????????? ?????????? ?????????? ??????????";
            }
            return response($res);
        } else {
            $res['status'] = 0;
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Recharge Not Completed Successfully.";
            } else {
                $res['success']['message'] = "?????????? ?????????? ???? ?????????? ??????????";
            }
            return response($res);
        }
    }

    public function voucher(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['voucher' => "Please Enter Voucher",
                'customer_id' => "Please Enter Customer ID",
            ];
        } else {
            $messages = ['voucher' => "???????????? ?????????? ??????????????",
                'customer_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['voucher' => 'required', 'customer_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $voucher = Gift_voucher::where('code', $request->voucher)->where('status', 1)->first();

        if (!empty($voucher)) {
            $currentDate = date('Y-m-d');
            $currentDate = date('Y-m-d', strtotime($currentDate));
            $start_date = $voucher->start_date;
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = $voucher->end_date;
            $end_date = date('Y-m-d', strtotime($end_date));

            if (($currentDate >= $start_date) && ($currentDate <= $end_date)) {
                if ($voucher->use_count >= 1) {
                    $walletamount = Wallet::where('user_id', $request->customer_id)->first();
                    if (!empty($walletamount)) {
                        Wallet::where('user_id', $request->customer_id)->update(['amount' => $voucher->amount + $walletamount->amount]);
                    } else {
                        $walletupdate = new Wallet;
                        $walletupdate->amount = $voucher->amount;
                        $walletupdate->user_id = $request->customer_id;
                        $walletupdate->save();
                    }

                    $history = new Wallet_recharge_history;
                    $history->amount = $voucher->amount;
                    $history->user_id = $request->customer_id;
                    $history->type = 1;
                    $history->reason = "Voucher";
                    $history->reason_ar = "????????????";
                    $history->save();

                    $userdata = User::where('id', $request->customer_id)->first();
                    $Device_token = $userdata->device_token;
                    $user_id = $userdata->id;
                    $msg = array(
                        'body' => "Voucher Redeemed Successfully",
                        'title' => 'Voucher Info',
                        'subtitle' => 'Letsbuy',
                        'key' => '5',
                        'vibrate' => 1,
                        'sound' => 1,
                        'largeIcon' => 'large_icon',
                        'smallIcon' => 'small_icon',
                    );
                    $this->Notificationsend($Device_token, $msg, $user_id);

                    Gift_voucher::where('id', $voucher->id)->update(['use_count' => $voucher->use_count - 1, 'is_used' => 1]);

                    $res['status'] = 1;
                    if ($request->header('language') == 3) {
                        $res['success']['message'] = 'Voucher Applied Successfully';
                    } else {
                        $res['success']['message'] = "???? ?????????? ?????????????? ??????????";
                    }
                    return response($res);
                } else {

                    $res['status'] = 0;
                    if ($request->header('language') == 3) {
                        $res['success']['message'] = 'Voucher Usage Maximum Limit Exists';
                    } else {
                        $res['success']['message'] = "???????? ???????? ???????????? ???????????????? ??????????????";
                    }
                    return response($res);

                }
            } else {

                $res['status'] = 0;
                if ($request->header('language') == 3) {
                    $res['success']['message'] = 'Voucher Code Is Expired';
                } else {
                    $res['success']['message'] = "?????????? ???????????? ?????? ??????????????";
                }
                return response($res);

            }
        } else {

            $res['status'] = 0;
            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Please Enter Valid Voucher';
            } else {
                $res['success']['message'] = "???????????? ?????????? ?????????? ??????????";
            }
            return response($res);
        }

    }

    public function brands(Request $request)
    {
        $page = $request['page'] ? $request['page'] : 1;
        $page = $page - 1;

        if ($request->header('language') == 3) {
            $brand = Brand::select('id', 'name_en as name', 'image_en as image')->where('status', 1)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
        } else {
            $brand = Brand::select('id', 'name_ar as name', 'image_ar as image')->where('status', 1)->orderby('id', 'desc')->skip($page * 10)->take(10)->get();
        }

        $brandcount = Brand::where('status', 1)->count();

        $total_pages = ceil($brandcount / 10);

        if (!empty($brand) && count($brand) > 0) {
            $res['topbrands_path'] = URL::to('/') . '/public/images/brands/';
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Brands List Successfully";
            } else {
                $res['success']['message'] = "?????????? ?????????????????? ??????????";
            }

            $res['total_pages'] = $total_pages;
            $res['brands'] = $brand;

            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Brands Not Available";
            } else {
                $res['error']['message'] = "?????????????? ?????? ??????????";
            }
            return response($res);
        }
    }

    public function Notificationsend($Device_token, $msg, $user_id)
    {

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'to' => $Device_token,
            'data' => $msg,
            'notification' => $msg,

        );
        // Firebase API Key
        $headers = array('Authorization:key=AAAAxF6P2ow:APA91bGuVr8cLvhZxVnB-8Ynv0y_k3RNJeHun3FcSJmONmmgJLOj-PTV2tamEojpmyg3jSn9JlDQqIa2zmYCUV6_aFnW5pkNhaNsaQcN8PWaAfGQFjl4TttzMzdWCongBqiWwHbGEPH9', 'Content-Type:application/json');
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        $notification = new Notification;
        $notification->user_id = $user_id;
        $notification->message = $msg['body'];
        $notification->message_type = $msg['key'];
        $notification->save();

    }

    public function remove_coupon(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $couponapply = Coupon_applied::where('user_id', $request->customer_id)->get();
        if (!empty($couponapply) && count($couponapply) > 0) {
            Coupon_applied::where('user_id', $request->customer_id)->delete();
            if ($request->header('language') == 3) {
                $res['success']['message'] = "Coupon Deleted Successfully";
            } else {
                $res['success']['message'] = "???? ?????? ?????????????? ??????????";
            }

            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = "Coupon Not Applied";
            } else {
                $res['error']['message'] = "?????????????? ?????? ??????????";
            }
            return response($res);
        }

    }

    public function checkoutapple(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
                'transaction_id' => "Please Enter Transaction ID",
                'amount' => "Please Enter Amount",
                'address_id' => "Please Enter Address ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
                'transaction_id' => "???????????? ?????????? ???????? ????????????????",
                'amount' => "???????????? ?????????? ????????????",
                'address_id' => "???????????? ?????????? ???????? ??????????????",
            ];
        }

        $rules = ['customer_id' => 'required', 'transaction_id' => 'required', 'amount' => 'required', 'address_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $cartdata = Cart::where('user_id', $request->customer_id)->get();

        if (count($cartdata) == 0) {
            $res['status'] = 0;

            if ($request->header('language') == 3) {
                $res['error']['message'] = "Your cart is empty";
            } else {
                $res['error']['message'] = "???????? ???????????? ??????????";
            }
            return response($res);
        }

        $global = Global_settings::all();

        $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();

        if (!empty($coupon)) {
            $promocode = Coupon::where('id', $coupon->coupon_id)->first();

            $coupon_history = new Coupon_history;
            $coupon_history->coupan_id = $coupon->coupon_id;
            $coupon_history->status = 1;
            $coupon_history->user_id = $request->customer_id;
            $coupon_history->save();
        }

        $customer_cart = Cart::where('user_id', $request->customer_id)->get();

        $orderprice = 0;
        foreach ($customer_cart as $value) {
            $product = Product::where('id', $value->product_id)->first();

            if ($value->quantity > $product->quantity) {
                $res['error']['message'] = "Product is out of stock";
                return response($res);
            }

            if (!empty($value->option_id)) {
                $product_detail = Product_details::where('id', $value->option_id)->first();

                if ($value->quantity > $product_detail->quantity) {
                    $res['error']['message'] = "Product is out of stock";
                    return response($res);
                }

                $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
            } else {
                $orderprice = $orderprice + ($product->price) * $value->quantity;
            }
        }
        $product_sub_total = $orderprice;

        //Ship charge
        if ($orderprice > $global[0]->min_amount_shipping) {
            $orderprice = $orderprice;
            $ship_charge = 0;
        } else {
            $orderprice = $orderprice + $global[0]->shipping_charge;
            $ship_charge = $global[0]->shipping_charge;
        }

        //coupon code applied

        if (!empty($coupon) && !empty($promocode)) {
            $discount = ($orderprice / 100) * $promocode->discount;
            $orderprice = number_format($orderprice - $discount, 2);
        }

        //Delivery charge
        if ($request->payment_method == 'cod') {
            $orderprice = number_format($orderprice + $global[0]->delivery_charge, 2);
            $delivery_charge = $global[0]->delivery_charge;
        } else {
            $orderprice = $orderprice;
            $delivery_charge = 0;
        }

        //Quantity Decrease
        foreach ($customer_cart as $value) {
            $product = Product::where('id', $value->product_id)->first();
            $product_detail = Product_details::where('id', $value->option_id)->first();

            Product::where('id', $value->product_id)->update(['quantity' => $product->quantity - $value->quantity]);

            if (!empty($value->option_id)) {
                Product_details::where('id', $value->option_id)->update(['quantity' => $product_detail->quantity - $value->quantity]);
            }
        }

        //Wallet manage

        if (!empty($request->wallet)) {
            $walletamount = $request->wallet;
            $wallet = Wallet::where('user_id', $request->customer_id)->first();
            if (!empty($wallet)) {
                if ($walletamount >= $orderprice) {
                    Wallet::where('user_id', $request->customer_id)->update(['amount' => $wallet->amount - $orderprice]);
                    $walletamount = $orderprice;

                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = $request->customer_id;
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "??????????";
                    $history->save();
                } else {
                    Wallet::where('user_id', $request->customer_id)->update(['amount' => $wallet->amount - $request->wallet]);
                    $walletamount = $request->wallet;

                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = $request->customer_id;
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "??????????";
                    $history->save();
                }
            } else {
                $walletamount = 0;
            }
        } else {
            $walletamount = 0;
        }

        //Order Create

        $order = new Order;
        $order->payment_type = 6;
        $order->shipping_price = $ship_charge;
        $order->delivery_price = $delivery_charge;
        if (!empty($coupon)) {
            $order->coupan_id = $coupon->coupon_id;
        }
        $order->discount = $orderprice - ($product_sub_total + $ship_charge + $delivery_charge);
        $order->product_total_amount = $product_sub_total;
        $order->paid_by_wallet = $walletamount;
        $order->user_id = $request->customer_id;
        $order->address_id = $request->address_id;
        $order->price = $orderprice;
        $order->save();

        $ordertrack = new Order_track;
        $ordertrack->order_id = $order->id;
        $ordertrack->save();

        foreach ($customer_cart as $record) {
            $product = Product::where('id', $record->product_id)->first();
            $order_details = new Order_details;
            $order_details->order_id = $order->id;
            $order_details->user_id = $request->customer_id;
            $order_details->product_id = $record->product_id;
            if (!empty($record->option_id)) {
                $product_detail = Product_details::where('id', $value->option_id)->first();

                $order_details->color = $record->option_id;
                $order_details->price = $product_detail->price + $product->price;
            } else {
                $order_details->color = 'nocolor';
                $order_details->price = $product->price;
            }
            $order_details->quantity = $record->quantity;
            $order_details->product_name_en = $product->name_en;
            $order_details->product_name_ar = $product->name_ar;

            $order_details->save();
        }

        $userdata = User::where('id', $request->customer_id)->first();

        $Device_token = $userdata->device_token;
        $user_id = $userdata->id;
        $msg = array(
            'body' => "Your Order #" . $order->id . " is Processing",
            'title' => 'Order Info',
            'subtitle' => 'Letsbuy',
            'key' => '5',
            'vibrate' => 1,
            'sound' => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon',
        );
        $this->Notificationsend($Device_token, $msg, $user_id);

        $name = $userdata->name;

        $EmailTemplates = Emailtemplate::where('slug', 'order_completed')->first();
        $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
        $subject = $EmailTemplates->subject_en;
        $to_email = $userdata->email;
        $data = array();
        $data['msg'] = $message;
        Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
            $message->to($to_email)->subject($subject);
            $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
        });

        $user_mobile = User::select('mobile')->where('id', $request->customer_id)->first();

        $mobile = $user_mobile->mobile;

        $user = "letsbuy";
        $password = "Nn0450292**";
        $mobilenumbers = $mobile;
        if ($request->header('language') == 3) {
            $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
        } else {
            $message = ' ???? ?????????? ???????? ?????????? ?????????? . ?????? ?????????? ' . $order->id;
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

        $transaction = new Transaction_details;
        $transaction->transaction_generate_id = $request->transaction_id;
        $transaction->is_applepay = 1;

        $transaction->save();

        $coupon = Coupon_applied::where('user_id', $request->customer_id)->first();
        if (!empty($coupon)) {
            Coupon_applied::where('user_id', $request->customer_id)->delete();
        }

        Cart::where('user_id', $request->customer_id)->delete();

        $getaddress = Address::where('id', $request->address_id)->first();

        if ($request->header('language') == 3) {
            $res['success']['message'] = 'Order Placed Successfully';
            $res['order_id'] = $order->id;
            $res['total'] = $order->price;
            $res['fulladdress'] = $getaddress->fulladdress;
        } else {
            $res['success']['message'] = '???? ?????????? ?????????? ??????????';
            $res['order_id'] = $order->id;
            $res['total'] = $order->price;
            $res['fulladdress'] = $getaddress->fulladdress;
        }
        return response($res);
    }

    public function check_product_availability(Request $request)
    {

        if ($request->header('language') == 3) {
            $messages = ['customer_id' => "Please Enter Customer ID",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????",
            ];
        }

        $rules = ['customer_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $cartdata = Cart::where('user_id', $request->customer_id)->get();

        if (count($cartdata) == 0) {
            $res['status'] = 0;

            if ($request->header('language') == 3) {
                $res['error']['message'] = "Your cart is empty";
            } else {
                $res['error']['message'] = "???????? ???????????? ??????????";
            }
            return response($res);
        }

        $customer_cart = Cart::where('user_id', $request->customer_id)->get();

        foreach ($customer_cart as $value) {
            $product = Product::where('id', $value->product_id)->first();

            if ($value->quantity > $product->quantity) {
                $res['error']['message'] = "Product is out of stock";
                return response($res);
            }

            if (!empty($value->option_id)) {
                $product_detail = Product_details::where('id', $value->option_id)->first();

                if ($value->quantity > $product_detail->quantity) {
                    $res['error']['message'] = "Product is out of stock";
                    return response($res);
                }

            }
        }

        $res['success']['message'] = "Product Available";
        return response($res);
    }

    public function paywithHyperPay(Request $request)
    {

        $messages = ['type' => "Please Enter Payment Type",
            'amount' => "Please Enter Amount",
            'user_id' => "Please Enter User ID",
            'address_id' => 'Please Enter Address ID',
        ];

        $rules = ['type' => 'required', 'amount' => 'required', 'user_id' => 'required', 'address_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $types = $request->type;
        $order_price = $request->amount;
        $order_Confirm = $request->order_confirm;
        $user_id = $request->user_id;
        $address_id = $request->address_id;

        $getaddress = Address::where('id', $address_id)->first();
        if ($types == 2) {
            $entityId = "8ac9a4c97dbd4ae3017dc315fc7562da"; //live
            $method = "VISA MASTER";
        }
        if ($types == 4) {
            $entityId = "8ac9a4c97dbd4ae3017dc31689fc62e3"; //live
            $method = "MADA";
        }

        // $entityId = "8a8294174d0595bb014d05d82e5b01d2";
        $milliseconds = date_create()->format('Uv');

        $getaddress = Address::leftjoin('country', 'country.country_id', '=', 'address.country')
            ->where('id', $address_id)
            ->select('address.*', 'country.iso_code_2')
            ->first();
        $getUser = User::where('id', $user_id)->first();
        $url = "https://eu-prod.oppwa.com/v1/checkouts";
        $data = "entityId=" . $entityId .
        "&amount=" . $order_price .
        "&currency=SAR" .
        "&paymentType=DB" .
        "&customer.email=" . $getUser->email ?? 'test@getnada.com';
        $data .= "&merchantTransactionId=" . $user_id . $milliseconds ?? Auth::id() . $milliseconds;
        $data .= "&billing.street1=" . $getaddress->fulladdress ?? 'Jaipur';
        $data .= "&billing.city=" . $getaddress->city ?? 'Jaipur';
        $data .= "&billing.state=" . $getaddress->state ?? 'Rajasthan';
        $data .= "&billing.country=" . $getaddress->iso_code_2 ?? 'IN';
        $data .= "&billing.postcode=" . $getaddress->postcode ?? '332012';
        $data .= "&customer.givenName=" . $getUser->name ?? 'Test';
        $data .= "&customer.surname=" . $getUser->name ?? 'Test'
        ;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($responseData)->id;
        $url = url('hyper-page/') . '/' . $data . '/' . $entityId . '/' . $user_id . '/' . $address_id . '/' . $method;
        return response()->json([
            'url' => $url,
        ]);

    }

    public function paymentDone(Type $var = null)
    {

        $result = json_decode($responseData, true);

        if ($result['status'] == 'CAPTURED') {
            $global = Global_settings::all();
            $useremail = User::where('email', $result['customer']['email'])->first();
            $customer_id = $useremail->id;

            $coupon = Coupon_applied::where('user_id', $customer_id)->first();

            if (!empty($coupon)) {
                $promocode = Coupon::where('id', $coupon->coupon_id)->first();

                $coupon_history = new Coupon_history;
                $coupon_history->coupan_id = $coupon->coupon_id;
                $coupon_history->status = 1;
                $coupon_history->user_id = $customer_id;
                $coupon_history->save();
            }

            $customer_cart = Cart::where('user_id', $customer_id)->get();

            $orderprice = 0;
            foreach ($customer_cart as $value) {
                $product = Product::where('id', $value->product_id)->first();

                if ($value->quantity > $product->quantity) {
                    $res['error']['message'] = "Product is out of stock";
                    return response($res);
                }

                if (!empty($value->option_id)) {
                    $product_detail = Product_details::where('id', $value->option_id)->first();

                    if ($value->quantity > $product_detail->quantity) {
                        $res['error']['message'] = "Product is out of stock";
                        return response($res);
                    }

                    $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                } else {
                    $orderprice = $orderprice + ($product->price) * $value->quantity;
                }
            }
            $product_sub_total = $orderprice;

            //Ship charge
            if ($orderprice > $global[0]->min_amount_shipping) {
                $orderprice = $orderprice;
                $ship_charge = 0;
            } else {
                $orderprice = $orderprice + $global[0]->shipping_charge;
                $ship_charge = $global[0]->shipping_charge;
            }

            //Delivery charge
            $delivery_charge = 0;

            //coupon code applied

            if (!empty($coupon) && !empty($promocode)) {
                $discount = ($orderprice / 100) * $promocode->discount;
                $orderprice = number_format($orderprice - $discount, 2);
            }

            //Quantity Decrease
            foreach ($customer_cart as $value) {
                $product = Product::where('id', $value->product_id)->first();
                $product_detail = Product_details::where('id', $value->option_id)->first();

                Product::where('id', $value->product_id)->update(['quantity' => $product->quantity - $value->quantity]);

                if (!empty($value->option_id)) {
                    Product_details::where('id', $value->option_id)->update(['quantity' => $product_detail->quantity - $value->quantity]);
                }
            }

            // Wallet manage

            if (!empty($result['metadata']['udf1'])) {
                $walletamount = $result['metadata']['udf1'];
                $wallet = Wallet::where('user_id', $customer_id)->first();
                if (!empty($wallet)) {
                    if ($walletamount >= $orderprice) {
                        Wallet::where('user_id', $customer_id)->update(['amount' => $wallet->amount - $orderprice]);
                        $walletamount = $orderprice;

                        $history = new Wallet_recharge_history;
                        $history->amount = $walletamount;
                        $history->user_id = $customer_id;
                        $history->type = 2;
                        $history->reason = "Order";
                        $history->reason_ar = "??????????";
                        $history->save();

                    } else {
                        Wallet::where('user_id', $customer_id)->update(['amount' => $wallet->amount - $result['metadata']['udf1']]);
                        $walletamount = $result['metadata']['udf1'];

                        $history = new Wallet_recharge_history;
                        $history->amount = $walletamount;
                        $history->user_id = $customer_id;
                        $history->type = 2;
                        $history->reason = "Order";
                        $history->reason_ar = "??????????";
                        $history->save();

                    }
                } else {
                    $walletamount = 0;
                }
            } else {
                $walletamount = 0;
            }

            //Order Create

            $order = new Order;
            $order->payment_type = $result['metadata']['udf2'];
            $order->shipping_price = $ship_charge;
            $order->delivery_price = $delivery_charge;
            if (!empty($coupon)) {
                $order->coupan_id = $coupon->coupon_id;
            }
            $order->discount = $orderprice - ($product_sub_total + $ship_charge + $delivery_charge);
            $order->product_total_amount = $product_sub_total;
            $order->paid_by_wallet = $walletamount;
            $order->user_id = $customer_id;
            $order->address_id = $result['customer']['phone']['country_code'];
            $order->price = $orderprice;
            $order->save();

            $ordertrack = new Order_track;
            $ordertrack->order_id = $order->id;
            $ordertrack->save();

            foreach ($customer_cart as $record) {
                $product = Product::where('id', $record->product_id)->first();
                $order_details = new Order_details;
                $order_details->order_id = $order->id;
                $order_details->user_id = $customer_id;
                $order_details->product_id = $record->product_id;
                if (!empty($record->option_id)) {
                    $product_detail = Product_details::where('id', $value->option_id)->first();

                    $order_details->color = $record->option_id;
                    $order_details->price = $product_detail->price + $product->price;
                } else {
                    $order_details->color = 'nocolor';
                    $order_details->price = $product->price;
                }
                $order_details->quantity = $record->quantity;
                $order_details->product_name_en = $product->name_en;
                $order_details->product_name_ar = $product->name_ar;

                $order_details->save();
            }

            $userdata = User::where('id', $customer_id)->first();
            $name = $userdata->name;

            $Device_token = $userdata->device_token;
            $user_id = $userdata->id;
            $msg = array(
                'body' => "Your Order #" . $order->id . " is Processing",
                'title' => 'Order Info',
                'subtitle' => 'Letsbuy',
                'key' => '5',
                'vibrate' => 1,
                'sound' => 1,
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon',
            );
            $this->Notificationsend($Device_token, $msg, $user_id);

            $EmailTemplates = Emailtemplate::where('slug', 'order_completed')->first();
            $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
            $subject = $EmailTemplates->subject_en;
            $to_email = $userdata->email;
            $data = array();
            $data['msg'] = $message;
            Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                $message->to($to_email)
                    ->subject($subject);
                $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
            });

            $user_mobile = User::select('mobile')->where('id', $customer_id)->first();

            $mobile = $user_mobile->mobile;

            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            if ($request->header('language') == 3) {
                $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
            } else {
                $message = ' ???? ?????????? ???????? ?????????? ?????????? . ?????? ?????????? ' . $order->id;
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

            if (!empty($result)) {
                $transaction = new Transaction_details;
                $transaction->charge_id = $result['id'];
                $transaction->payment_status = $result['status'];
                $transaction->user_id = $customer_id;
                $transaction->order_id = $order->id;
                $transaction->amount = $result['amount'];
                $transaction->currency = $result['currency'];
                $transaction->track_id = $result['reference']['track'];
                $transaction->payment_id = $result['reference']['payment'];
                $transaction->transaction_generate_id = $result['reference']['transaction'];
                $transaction->order_generate_id = $result['reference']['order'];
                $transaction->receipt_id = $result['receipt']['id'];
                $transaction->payment_method = $result['source']['payment_method'];
                $transaction->payment_type = $result['source']['payment_type'];
                $transaction->token_id = $result['source']['id'];

                $transaction->save();
            }

            $getaddress = Address::where('id', $result['customer']['phone']['country_code'])->first();
            Cart::where('user_id', $customer_id)->delete();

            $coupon = Coupon_applied::where('user_id', $customer_id)->first();
            if (!empty($coupon)) {
                Coupon::where('id', $coupon->coupon_id)->delete();
            }

            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Order Placed Successfully';
                $res['order_id'] = $order->id;
                $res['total'] = $order->price;
                $res['fulladdress'] = $getaddress->fulladdress;
            } else {
                $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                $res['order_id'] = $order->id;
                $res['total'] = $order->price;
                $res['fulladdress'] = $getaddress->fulladdress;
            }
            return response($res);
        }
    }

    public function getCheckoutIdByApplePay(Request $request)
    {
        $messages = [
            'amount' => "Please Enter Amount",
            'user_id' => "Please Enter User ID",
            'address_id' => 'Please Enter Address ID',
        ];
        $rules = ['amount' => 'required', 'user_id' => 'required', 'address_id' => 'required'];
        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $order_price = $request->amount;
        $user_id = $request->user_id;
        $address_id = $request->address_id;
        $getaddress = Address::where('id', $address_id)->first();
        // $entityId = "8ac7a4c87ddfac9f017de1b23ed302c6"; //local
        $entityId = "8ac9a4cb7e34cff5017e3e4090c34e15"; //live
        $milliseconds = date_create()->format('Uv');
        $getaddress = Address::leftjoin('country', 'country.country_id', '=', 'address.country')
            ->where('id', $address_id)
            ->select('address.*', 'country.iso_code_2')
            ->first();
        $getUser = User::where('id', $user_id)->first();
        $url = "https://eu-prod.oppwa.com/v1/checkouts";
        $data = "entityId=" . $entityId .
        "&amount=1" .
        "&currency=SAR" .
        "&paymentType=DB" .
        "&customer.email=" . $getUser->email ?? 'test@getnada.com';
        $data .= "&merchantTransactionId=" . Auth::id() . $milliseconds;
        $data .= "&billing.street1=" . $getaddress->fulladdress ?? 'Jaipur';
        $data .= "&billing.city=" . $getaddress->city ?? 'Jaipur';
        $data .= "&billing.state=" . $getaddress->state ?? 'Rajasthan';
        $data .= "&billing.country=" . $getaddress->iso_code_2 ?? 'IN';
        $data .= "&billing.postcode=" . $getaddress->postcode ?? '332012';
        $data .= "&customer.givenName=" . $getUser->name ?? 'Test';
        $data .= "&customer.surname=" . $getUser->name ?? 'Test'
        ;

        // $data = "entityId=" . $entityId .
        // "&amount=0.10" .
        // "&currency=SAR" .
        // "&merchantTransactionId=" . md5(time()) .
        //     "&customer.email=test" .
        //     "&paymentType=DB" .
        //     "&billing.street1=test" .
        //     "&billing.city=test" .
        //     "&billing.state=test" .
        //     "&billing.country=SA" .
        //     "&billing.postcode=test" .
        //     "&customer.givenName=test" .
        //     "&customer.surname=test";
        //Merchant-determined tokenization for store info
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        curl_close($ch);
        $token = json_decode($responseData)->id;

        return response()->json([
            'checkout_id' => $token,
        ]);

    }

    public function payWithApplePay(Request $request)
    {
        Log::debug($request->all());
        $id = $request->id;
        // $entityId = "8ac7a4c87ddfac9f017de1b23ed302c6"; // local
        $entityId = "8ac9a4cb7e34cff5017e3e4090c34e15"; // live
        $address = $request->address;
        $user_id = $request->user_id;
        $url = "https://eu-prod.oppwa.com/" . $id;
        $url .= "?entityId=" . $entityId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $payment_capture = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        $payment_status = json_decode($payment_capture);
        if ($payment_status->result->code == '000.100.110') {

            $global = Global_settings::all();
            $useremail = User::where('id', $user_id)->first();
            $customer_id = $useremail->id;

            $coupon = Coupon_applied::where('user_id', $customer_id)->first();

            if (!empty($coupon)) {
                $promocode = Coupon::where('id', $coupon->coupon_id)->first();
                $coupon_history = new Coupon_history;
                $coupon_history->coupan_id = $coupon->coupon_id;
                $coupon_history->status = 1;
                $coupon_history->user_id = $customer_id;
                $coupon_history->save();
            }
            $customer_cart = Cart::where('user_id', $customer_id)->get();
            $orderprice = 0;
            foreach ($customer_cart as $value) {
                $product = Product::where('id', $value->product_id)->first();
                if ($value->quantity > $product->quantity) {
                    $res['error']['message'] = "Product is out of stock";
                    return response($res);
                }

                if (!empty($value->option_id)) {
                    $product_detail = Product_details::where('id', $value->option_id)->first();
                    if ($value->quantity > $product_detail->quantity) {
                        $res['error']['message'] = "Product is out of stock";
                        return response($res);
                    }
                    $orderprice = $orderprice + ($product_detail->price + $product->price) * $value->quantity;
                } else {
                    $orderprice = $orderprice + ($product->price) * $value->quantity;
                }
            }
            $product_sub_total = $orderprice;

            //Ship charge
            if ($orderprice > $global[0]->min_amount_shipping) {
                $orderprice = $orderprice;
                $ship_charge = 0;
            } else {
                $orderprice = $orderprice + $global[0]->shipping_charge;
                $ship_charge = $global[0]->shipping_charge;
            }

//Delivery charge
            $delivery_charge = 0;

//coupon code applied

            if (!empty($coupon) && !empty($promocode)) {
                $discount = ($orderprice / 100) * $promocode->discount;
                $orderprice = number_format($orderprice - $discount, 2);
            }

//Quantity Decrease
            foreach ($customer_cart as $value) {
                $product = Product::where('id', $value->product_id)->first();
                $product_detail = Product_details::where('id', $value->option_id)->first();

                Product::where('id', $value->product_id)->update(['quantity' => $product->quantity - $value->quantity]);

                if (!empty($value->option_id)) {
                    Product_details::where('id', $value->option_id)->update(['quantity' => $product_detail->quantity - $value->quantity]);
                }
            }

// Wallet manage
            $wallet = Wallet::where('user_id', $customer_id)->first();
            $walletamount = $payment_status->amount;
            if (!empty($wallet)) {
                if ($walletamount >= $orderprice) {
                    Wallet::where('user_id', $customer_id)->update(['amount' => $wallet->amount - $orderprice]);
                    $walletamount = $orderprice;
                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = $customer_id;
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "??????????";
                    $history->save();

                } else {
                    Wallet::where('user_id', $customer_id)->update(['amount' => $wallet->amount - $payment_status->amount]);
                    $walletamount = $payment_status->amount;

                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = $customer_id;
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "??????????";
                    $history->save();

                }
            } else {
                $walletamount = 0;
            }

//Order Create

            $order = new Order;
            $order->payment_type = $payment_status->paymentType;
            $order->shipping_price = $ship_charge;
            $order->delivery_price = $delivery_charge;
            if (!empty($coupon)) {
                $order->coupan_id = $coupon->coupon_id;
            }
            $order->discount = $orderprice - ($product_sub_total + $ship_charge + $delivery_charge);
            $order->product_total_amount = $product_sub_total;
            $order->paid_by_wallet = $walletamount;
            $order->user_id = $customer_id;
            $order->address_id = $address;
            $order->price = $orderprice;
            $order->save();

            $ordertrack = new Order_track;
            $ordertrack->order_id = $order->id;
            $ordertrack->save();

            foreach ($customer_cart as $record) {
                $product = Product::where('id', $record->product_id)->first();
                $order_details = new Order_details;
                $order_details->order_id = $order->id;
                $order_details->user_id = $customer_id;
                $order_details->product_id = $record->product_id;
                if (!empty($record->option_id)) {
                    $product_detail = Product_details::where('id', $value->option_id)->first();

                    $order_details->color = $record->option_id;
                    $order_details->price = $product_detail->price + $product->price;
                } else {
                    $order_details->color = 'nocolor';
                    $order_details->price = $product->price;
                }
                $order_details->quantity = $record->quantity;
                $order_details->product_name_en = $product->name_en;
                $order_details->product_name_ar = $product->name_ar;

                $order_details->save();
            }

            $userdata = User::where('id', $customer_id)->first();
            $name = $userdata->name;

            $Device_token = $userdata->device_token;
            $user_id = $userdata->id;
            $msg = array(
                'body' => "Your Order #" . $order->id . " is Processing",
                'title' => 'Order Info',
                'subtitle' => 'Letsbuy',
                'key' => '5',
                'vibrate' => 1,
                'sound' => 1,
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon',
            );
            $this->Notificationsend($Device_token, $msg, $user_id);

            $EmailTemplates = Emailtemplate::where('slug', 'order_completed')->first();
            $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
            $subject = $EmailTemplates->subject_en;
            $to_email = $userdata->email;
            $data = array();
            $data['msg'] = $message;
// Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
            //     $message->to($to_email)
            //         ->subject($subject);
            //     $message->from(env('MAIL_USERNAME', 'yallacashdubai@gmail.com'));
            //     // $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
            // });

            $user_mobile = User::select('mobile')->where('id', $customer_id)->first();

            $mobile = $user_mobile->mobile;

            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            if ($request->header('language') == 3) {
                $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
            } else {
                $message = ' ???? ?????????? ???????? ?????????? ?????????? . ?????? ?????????? ' . $order->id;
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
            if (!empty($payment_status)) {
                $transaction = new Transaction_details;
                $transaction->charge_id = $payment_status->id;
                $transaction->payment_status = $payment_status->result->description;
                $transaction->user_id = Auth::id();
                $transaction->order_id = $order->id;
                $transaction->amount = $payment_status->amount;
                $transaction->currency = $payment_status->currency;
                $transaction->track_id = $payment_status->id;
                $transaction->payment_id = $payment_status->id;
                $transaction->transaction_generate_id = $payment_status->id;
                $transaction->order_generate_id = $payment_status->resultDetails->ConnectorTxID1;
                $transaction->receipt_id = $payment_status->resultDetails->ConnectorTxID1;
                $transaction->payment_method = $payment_status->paymentBrand;
                $transaction->payment_type = $payment_status->paymentType;
                $transaction->token_id = $payment_status->id;
                $transaction->save();
            }

            $getaddress = Address::where('id', $address)->first();
            Cart::where('user_id', $customer_id)->delete();

            $coupon = Coupon_applied::where('user_id', $customer_id)->first();
            if (!empty($coupon)) {
                Coupon::where('id', $coupon->coupon_id)->delete();
            }

            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Order Placed Successfully';
                $res['order_id'] = $order->id;
                $res['total'] = $order->price;
                $res['fulladdress'] = $getaddress->fulladdress;
            } else {
                $res['success']['message'] = '???? ?????????? ?????????? ??????????';
                $res['order_id'] = $order->id;
                $res['total'] = $order->price;
                $res['fulladdress'] = $getaddress->fulladdress;
            }
            return response($res);
        } else {

            if ($payment_status->result->code == "200.300.404") {
                $res['error']['message'] = "payment session expired";
            }
            if ($payment_status->result->code == "800.120.100") {
                $res['error']['message'] = "Too many requests. Please try again later.";
            } else {
                $res['error']['message'] = "Server side error";
            }
            return response($res);

        }
    }

    // Wallet for Apple Pay

    public function walletCheckout(Request $request)
    {
        $messages = [
            'amount' => "Please Enter Amount",
            'user_id' => "Please Enter User ID",
        ];
        $rules = ['amount' => 'required', 'user_id' => 'required'];
        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $order_price = $request->amount;
        $user_id = $request->user_id;
        // $address_id = $request->address_id;
        $getaddress = Address::where('user_id', $user_id)->where('is_default', '1')->first();
        $entityId = "8ac7a4c87ddfac9f017de1b23ed302c6"; //live
        // $entityId = "8ac9a4cb7e34cff5017e3e4090c34e15"; //local
        $milliseconds = date_create()->format('Uv');
        $getaddress = Address::leftjoin('country', 'country.country_id', '=', 'address.country')
            ->where('user_id', $user_id)
            ->where('is_default', 0)
            ->select('address.*', 'country.iso_code_2')
            ->first();
        $getUser = User::where('id', $user_id)->first();
        $url = "https://eu-prod.oppwa.com/v1/checkouts";
        $data = "entityId=" . $entityId .
        "&amount=" . $order_price .
        "&currency=SAR" .
        "&paymentType=DB" .
        "&customer.email=" . $getUser->email ?? 'test@getnada.com';
        $data .= "&merchantTransactionId=" . Auth::id() . $milliseconds;
        $data .= "&billing.street1=" . $getaddress->fulladdress ?? 'Jaipur';
        $data .= "&billing.city=" . $getaddress->city ?? 'Jaipur';
        $data .= "&billing.state=" . $getaddress->state ?? 'Rajasthan';
        $data .= "&billing.country=" . $getaddress->iso_code_2 ?? 'IN';
        $data .= "&billing.postcode=" . $getaddress->postcode ?? '332012';
        $data .= "&customer.givenName=" . $getUser->name ?? 'Test';
        $data .= "&customer.surname=" . $getUser->name ?? 'Test'
        ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        curl_close($ch);
        $token = json_decode($responseData)->id;

        return response()->json([
            'checkout_id' => $token,
        ]);
    }

    public function walletAddMoney(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['user_id' => "Please Enter User ID",
                'id' => "Please Enter Transaction ID",
                'amount' => "Please Enter Amount",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????????",
                'transaction_id' => "???????????? ?????????? ???????? ????????????????",
                'amount' => "???????????? ?????????? ????????????",
            ];
        }

        $rules = ['user_id' => 'required', 'id' => 'required', 'amount' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $id = $request->id;
        // $entityId = "8ac7a4c87ddfac9f017de1b23ed302c6"; // local
        $entityId = "8ac9a4cb7e34cff5017e3e4090c34e15"; // live
        $url = "https://eu-prod.oppwa.com/v1/checkouts/" . $id . "/payment";
        $url .= "?entityId=" . $entityId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $payment_capture = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        $payment_status = json_decode($payment_capture);
        Log::debug((array) $payment_status);
        if ($payment_status->result->code == '000.100.110') {
            $userdata = User::where('id', $request->user_id)->first();
            $Device_token = $userdata->device_token;
            $user_id = $userdata->id;
            $msg = array(
                'body' => "Your Recharge is Successfully Completed",
                'title' => 'Recharge Info',
                'subtitle' => 'Letsbuy',
                'key' => '5',
                'vibrate' => 1,
                'sound' => 1,
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon',
            );
            $this->Notificationsend($Device_token, $msg, $user_id);
            $name = $userdata->name;
            $EmailTemplates = Emailtemplate::where('slug', 'recharge_completed')->first();
            $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
            $subject = $EmailTemplates->subject_en;
            $to_email = $userdata->email;
            $data = array();
            $data['msg'] = $message;
            Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                $message->to($to_email)
                    ->subject($subject);
                $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
            });

            $user_mobile = User::select('mobile')->where('id', $user_id)->first();
            $mobile = $user_mobile->mobile;
            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            if ((\App::getLocale() == 'en')) {
                $message = 'Your Wallet Recharege is Successfully Completed Amount :' . $request->amount;
            } else {
                $message = ' ?????????? ???????????? ?????????????? ???????????? ???? ???? ???????????? ?????????? ' . $request->amount;
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

            $walletrecharge = new Wallet_recharge_history;
            $walletrecharge->amount = $request->amount;
            $walletrecharge->user_id = $user_id;
            $walletrecharge->type = 1;
            $walletrecharge->reason = "Recharge";
            $walletrecharge->reason_ar = "?????????? ????????";
            $walletrecharge->save();

            $walletamount = Wallet::where('user_id', $user_id)->first();
            if (!empty($walletamount)) {
                Wallet::where('user_id', $user_id)->update(['amount' => $request->amount + $walletamount->amount]);
            } else {
                $walletupdate = new Wallet;
                $walletupdate->amount = $request->amount;
                $walletupdate->user_id = $user_id;
                $walletupdate->save();
            }

            $transaction = new Transaction_details;
            $transaction->transaction_generate_id = $payment_status->id;
            $transaction->is_applepay = 1;
            $transaction->save();

            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Recharge Completed Successfully';
            } else {
                $res['success']['message'] = '???????????? ?????????? ?????????? ??????????';
            }
            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = 'Recharge Not Completed';
            } else {
                $res['error']['message'] = '?????????? ?????????? ???? ??????????';
            }
            return response($res);
        }
    }

    public function hyperPayAddWallet(Request $request)
    {
        if ($request->header('language') == 3) {
            $messages = ['user_id' => "Please Enter User ID",
                'id' => "Please Enter Transaction ID",
                'amount' => "Please Enter Amount",
            ];
        } else {
            $messages = ['customer_id' => "???????????? ?????????? ???????? ????????????????",
                'transaction_id' => "???????????? ?????????? ???????? ????????????????",
                'amount' => "???????????? ?????????? ????????????",
            ];
        }
        $url = "https://eu-prod.oppwa.com/v1/checkouts/" . $id . "/payment";
        // $url = "https://test.oppwa.com/v1/checkouts/" . $id . "/payment";
        $url .= "?entityId=" . $entry_id;
        // test token : OGFjN2E0Yzg3ZDBlYTA3NDAxN2QwZjBhYjgxMDAxMWV8WGhzalJONzZuag;
        // live token : OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $payment_status = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        $payment_status = json_decode($payment_status);
        if ($payment_status->result->code == "000.100.110") {
            $userdata = User::where('id', $request->user_id)->first();
            $Device_token = $userdata->device_token;
            $user_id = $userdata->id;
            $msg = array(
                'body' => "Your Recharge is Successfully Completed",
                'title' => 'Recharge Info',
                'subtitle' => 'Letsbuy',
                'key' => '5',
                'vibrate' => 1,
                'sound' => 1,
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon',
            );
            $this->Notificationsend($Device_token, $msg, $user_id);
            $name = $userdata->name;
            $EmailTemplates = Emailtemplate::where('slug', 'recharge_completed')->first();
            $message = str_replace(array('{name}'), array($name), $EmailTemplates->description_en);
            $subject = $EmailTemplates->subject_en;
            $to_email = $userdata->email;
            $data = array();
            $data['msg'] = $message;
            Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                $message->to($to_email)
                    ->subject($subject);
                $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
            });

            $user_mobile = User::select('mobile')->where('id', $user_id)->first();

            $mobile = $user_mobile->mobile;

            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            if ((\App::getLocale() == 'en')) {
                $message = 'Your Wallet Recharege is Successfully Completed Amount :' . $request->amount;
            } else {
                $message = ' ?????????? ???????????? ?????????????? ???????????? ???? ???? ???????????? ?????????? ' . $request->amount;
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

            $walletrecharge = new Wallet_recharge_history;
            $walletrecharge->amount = $request->amount;
            $walletrecharge->user_id = $user_id;
            $walletrecharge->type = 1;
            $walletrecharge->reason = "Recharge";
            $walletrecharge->reason_ar = "?????????? ????????";
            $walletrecharge->save();

            $walletamount = Wallet::where('user_id', $user_id)->first();
            if (!empty($walletamount)) {
                Wallet::where('user_id', $user_id)->update(['amount' => $request->amount + $walletamount->amount]);
            } else {
                $walletupdate = new Wallet;
                $walletupdate->amount = $request->amount;
                $walletupdate->user_id = $user_id;
                $walletupdate->save();
            }

            $transaction = new Transaction_details;
            $transaction->transaction_generate_id = $payment_status->id;
            $transaction->is_applepay = 1;

            $transaction->save();

            if ($request->header('language') == 3) {
                $res['success']['message'] = 'Recharge Completed Successfully';
            } else {
                $res['success']['message'] = '???????????? ?????????? ?????????? ??????????';
            }
            return response($res);
        } else {
            if ($request->header('language') == 3) {
                $res['error']['message'] = 'Recharge Not Completed';
            } else {
                $res['error']['message'] = '?????????? ?????????? ???? ??????????';
            }
            return response($res);
        }
    }

    public function hyperPayWalletUrl(Request $request)
    {
        $messages = ['type' => "Please Enter Payment Type",
            'amount' => "Please Enter Amount",
            'user_id' => "Please Enter User ID",
        ];

        $rules = ['type' => 'required', 'amount' => 'required', 'user_id' => 'required'];

        $validator = Validator::make($request->all(), $rules, $messages);
        $errors = $validator->errors();

        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $res['status'] = 0;
                $res['error']['message'] = $message;
                return response($res);
            }
        }

        $types = $request->type;
        $order_price = $request->amount;
        $order_Confirm = $request->order_confirm;
        $user_id = $request->user_id;

        if ($types == 2) {
            $entityId = "8ac9a4c97dbd4ae3017dc315fc7562da"; //live
            // $entityId = "8ac7a4c87d0ea074017d0f0b7a140170"; //test
            $method = "VISA MASTER";
        }
        if ($types == 4) {
            $entityId = "8ac9a4c97dbd4ae3017dc31689fc62e3"; //live
            // $entityId = "8ac7a4c87d0ea074017d0f0c14cc0189"; //test
            $method = "MADA";
        }
        $milliseconds = date_create()->format('Uv');
        $getUser = User::where('id', $user_id)->first();
        $url = "https://eu-prod.oppwa.com/v1/checkouts";
        $data = "entityId=" . $entityId .
        "&amount=" . $order_price .
        "&currency=SAR" .
        "&paymentType=DB" .
        "&customer.email=" . $getUser->email ?? 'test@getnada.com';
        $data .= "&merchantTransactionId=" . $user_id . $milliseconds ?? Auth::id() . $milliseconds;
        $data .= "&billing.street1=Jaipur";
        $data .= "&billing.city=Jaipur";
        $data .= "&billing.state=Rajasthan";
        $data .= "&billing.country=IN";
        $data .= "&billing.postcode=332012";
        $data .= "&customer.givenName=" . $getUser->name ?? 'Test';
        $data .= "&customer.surname=" . $getUser->name ?? 'Test';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($responseData)->id;
        $url = url('hyper-page-wallet/') . '/' . $data . '/' . $entityId . '/' . $user_id . '/' . $method.'/'.$order_price;
        return response()->json([
            'url' => $url,
        ]);
    }
}
