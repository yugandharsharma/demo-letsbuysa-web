<?php

namespace App\Http\Controllers;

use App\Model\Address;
use App\Model\Category;
use App\Model\Coupon;
use App\Model\Coupon_history;
use App\Model\Emailtemplate;
use App\Model\Global_settings;
use App\Model\Order;
use App\Model\Order_details;
use App\Model\Order_track;
use App\Model\Product;
use App\Model\Product_details;
use App\Model\Subcategory;
use App\Model\Transaction_details;
use App\Model\Wallet;
use App\Model\Wallet_recharge_history;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Mail;
use Redirect;
use Session;
use URL;
use Validator;
use Yoeunes\Toastr\Facades\Toastr;

class OrderController extends Controller
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
    public function ordersubmit(Request $request)
    {
        $rules = [
            'paymenttype' => 'required',
        ];

        $messages = [
            'paymenttype.required' => 'Please Choose Payment Type',
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            return redirect()->back()->withInput()->withErrors($v);
        }
        $product = Session::get('product');

        $new_array = array();

        foreach ($product as $key => $value) {
            if (!empty($value)) {
                $new_array[$key] = $value;
            }
        }

        if (empty($new_array)) {
            if ((\App::getLocale() == 'en')) {
                toastr()->success('Your Cart Is Empty Please Add Products In Cart');
            } else {
                toastr()->success('سلة التسوق الخاصة بك فارغة الرجاء إضافة المنتجات في سلة التسوق');
            }

            return redirect()->back();
        }

        $coupan_code = Session::get('coupan_code');

        $coupan = Coupon::where('id', $coupan_code)->first();

        $products = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();
        $order_price = 0;

        foreach ($products as $productnew) {
            foreach ($product[$productnew->id] as $key => $colorpro) {
                if ($colorpro['id'] == $productnew->id) {
                    $productdata[$productnew->id][$key]['req_quantity'] = $colorpro['qty'];
                    $productdata[$productnew->id][$key]['color'] = $key;
                    if ($key != 'nocolor') {
                        $product_detail_option = explode(',', $key);
                        $products1 = DB::table('product_details')->select('*')->where('status', 1)->where('id', $product_detail_option[0])->first();
                        $productdata[$productnew->id][$key]['quantity'] = $products1->quantity;
                        $productdata[$productnew->id][$key]['price'] = $productnew->price + $products1->price;

                    } else {
                        $productdata[$productnew->id][$key]['quantity'] = $productnew->quantity;
                        $productdata[$productnew->id][$key]['price'] = $productnew->price;
                    }
                    $productdata[$productnew->id][$key]['id'] = $productnew->id;
                    $productdata[$productnew->id][$key]['name_en'] = $productnew->name_en;
                    $productdata[$productnew->id][$key]['name_ar'] = $productnew->name_ar;
                    $productdata[$productnew->id][$key]['img'] = $productnew->img;
                    $productdata[$productnew->id][$key]['offer_price'] = $productnew->offer_price;
                    $productdata[$productnew->id][$key]['discount_available'] = $productnew->discount_available;
                }
                $order_price = $order_price + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];
                $product_sub_total = $order_price;
            }
        }

        $reqantity = array();
        foreach ($productdata as $firstkey => $productquantity) {
            $totalreq = 0;
            foreach ($productquantity as $secondkey => $pro) {
                if ($pro['req_quantity'] > $pro['quantity']) {
                    if ((\App::getLocale() == 'en')) {
                        toastr()->success('Product Quantity is Not Available');
                    } else {
                        toastr()->success('كمية المنتج غير متوفرة');
                    }
                    return redirect()->back();
                }
                $totalreq = $totalreq + $pro['req_quantity'];

            }

            $reqantity[$firstkey] = array(
                "id" => $firstkey,
                "qty" => $totalreq,
                "quantity" => $pro['quantity'],
            );

        }

        foreach ($reqantity as $checkquantity) {
            $proquant = Product::where('id', $checkquantity['id'])->first();
            if ($checkquantity['qty'] > $proquant->quantity) {
                if ((\App::getLocale() == 'en')) {
                    toastr()->success('Product Quantity is Not Available');
                } else {
                    toastr()->success('كمية المنتج غير متوفرة');
                }

                return redirect()->back();
            }
        }

        if ($request->paymenttype == 2) {

            foreach ($productdata as $firstkey => $productquantity) {
                foreach ($productquantity as $secondkey => $pro) {

                    $available = $pro['quantity'] - $pro['req_quantity'];
                    $mycoloroptdata = Product_details::where('id', $secondkey)->first();
                    if (!empty($mycoloroptdata)) {
                        Product_details::where('id', $secondkey)->update(['quantity' => $available]);
                    }
                }
            }

            foreach ($reqantity as $checkquantity) {
                $proquant = Product::where('id', $checkquantity['id'])->first();
                $newquantity = $proquant->quantity - $checkquantity['qty'];
                Product::where('id', $checkquantity['id'])->update(['quantity' => $newquantity]);
            }

            if (!empty($coupan)) {
                $discounted_price = number_format((float) ($order_price / 100) * $coupan->discount, 2, '.', '');
                $discounted_price = $order_price - $discounted_price;

                $global = Global_settings::all();
                $ship_charge = 0;

                if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                    if ($order_price >= $global[0]->min_amount_shipping) {
                        $ship_charge = 0;
                    } else {
                        $ship_charge = $global[0]->shipping_charge;
                    }
                }
                $delivery_charge = $global[0]->delivery_charge;

                $discounted_price = $discounted_price + $ship_charge + $delivery_charge;

                //coupan code management

                $coupon_history = new Coupon_history;
                $coupon_history->coupan_id = $coupan->id;
                $coupon_history->status = 1;
                $coupon_history->user_id = Auth::id();
                $coupon_history->save();
            } else {
                $global = Global_settings::all();
                $ship_charge = 0;

                if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                    if ($order_price >= $global[0]->min_amount_shipping) {
                        $ship_charge = 0;
                    } else {
                        $ship_charge = $global[0]->shipping_charge;
                    }
                }
                $delivery_charge = $global[0]->delivery_charge;

                $discounted_price = $order_price + $ship_charge + $delivery_charge;

            }

            if (!empty($request->wallet)) {
                $walletamount = $request->wallet;

                if ($walletamount >= $discounted_price) {
                    $wallet = Wallet::where('user_id', Auth::id())->first();
                    Wallet::where('user_id', Auth::id())->update(['amount' => $wallet->amount - $discounted_price]);
                    $walletamount = $discounted_price;

                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = Auth::id();
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "ترتيب";
                    $history->save();

                } else {
                    $wallet = Wallet::where('user_id', Auth::id())->first();
                    Wallet::where('user_id', Auth::id())->update(['amount' => $wallet->amount - $request->wallet]);
                    $walletamount = $request->wallet;

                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = Auth::id();
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "ترتيب";
                    $history->save();

                }

            } else {
                $walletamount = 0;
            }

            $order = new Order;
            $order->payment_type = 1;
            $order->shipping_price = $ship_charge;
            $order->delivery_price = $delivery_charge;
            $order->coupan_id = Session::get('coupan_code');
            $order->discount = $discounted_price - ($product_sub_total + $ship_charge + $delivery_charge);
            $order->product_total_amount = $product_sub_total;
            $order->paid_by_wallet = $walletamount;
            $order->user_id = Auth::id();
            $order->address_id = Session::get('address_id');
            $order->price = $discounted_price;
            $order->save();

            $ordertrack = new Order_track;
            $ordertrack->order_id = $order->id;
            $ordertrack->save();

            $userdata = User::where('id', Auth::id())->first();
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

            $user_mobile = User::select('mobile')->where('id', Auth::id())->first();

            $mobile = $user_mobile->mobile;

            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            if ((\App::getLocale() == 'en')) {
                $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
            } else {
                $message = ' تم تسجيل طلبك لدينا بنجاح . رقم الطلب ' . $order->id;
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

            foreach ($productdata as $record) {
                foreach ($record as $newrecord) {
                    $order_details = new Order_details;
                    $order_details->order_id = $order->id;
                    $order_details->user_id = Auth::id();
                    $order_details->product_id = $newrecord['id'];
                    if (isset($newrecord['color'])) {
                        $order_details->color = $newrecord['color'];
                    }
                    $order_details->quantity = $newrecord['req_quantity'];
                    $order_details->price = $newrecord['price'];
                    $order_details->product_name_en = $newrecord['name_en'];
                    $order_details->product_name_ar = $newrecord['name_ar'];

                    $order_details->save();
                }
            }

            Session::forget('address_id');
            Session::forget('product');
            Session::forget('coupan_code');

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Order Completed Successfully');
            } else {
                toastr()->success('اكتمل الطلب بنجاح');
            }
            return redirect('/myorder');
        } elseif ($request->paymenttype == 1 || $request->paymenttype == 3) {
            if (!empty($coupan)) {

                $discounted_price = number_format((float) ($order_price / 100) * $coupan->discount, 2, '.', '');
                $discounted_price = $order_price - $discounted_price;
            } else {
                $discounted_price = $order_price;
            }
            $global = Global_settings::all();
            $ship_charge = 0;
            $delivery_charge = 0;

            if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                if ($order_price >= $global[0]->min_amount_shipping) {
                    $ship_charge = 0;
                } else {
                    $ship_charge = $global[0]->shipping_charge;
                }
            }

            $discounted_price = $discounted_price + $ship_charge + $delivery_charge;

            if (!empty($request->wallet)) {
                if ($request->wallet >= $discounted_price) {

                    $wallet = Wallet::where('user_id', Auth::id())->first();
                    Wallet::where('user_id', Auth::id())->update(['amount' => $wallet->amount - $discounted_price]);
                    $paid_by_wallet = $discounted_price;

                    $product = Session::get('product');
                    $coupan_code = Session::get('coupan_code');

                    $coupan = Coupon::where('id', $coupan_code)->first();

                    $products = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();
                    $order_price = 0;

                    foreach ($products as $productnew) {
                        foreach ($product[$productnew->id] as $key => $colorpro) {
                            if ($colorpro['id'] == $productnew->id) {
                                $productdata[$productnew->id][$key]['req_quantity'] = $colorpro['qty'];
                                $productdata[$productnew->id][$key]['color'] = $key;
                                $productdata[$productnew->id][$key]['id'] = $productnew->id;
                                $productdata[$productnew->id][$key]['name_en'] = $productnew->name_en;
                                $productdata[$productnew->id][$key]['name_ar'] = $productnew->name_ar;
                                $productdata[$productnew->id][$key]['img'] = $productnew->img;
                                if ($key != 'nocolor') {
                                    $product_detail_option = explode(',', $key);
                                    $products1 = DB::table('product_details')->select('*')->where('status', 1)->where('id', $product_detail_option[0])->first();
                                    $productdata[$productnew->id][$key]['quantity'] = $products1->quantity;
                                    $productdata[$productnew->id][$key]['price'] = $productnew->price + $products1->price;

                                } else {
                                    $productdata[$productnew->id][$key]['quantity'] = $productnew->quantity;
                                    $productdata[$productnew->id][$key]['price'] = $productnew->price;
                                }
                                $productdata[$productnew->id][$key]['offer_price'] = $productnew->offer_price;
                                $productdata[$productnew->id][$key]['discount_available'] = $productnew->discount_available;
                            }
                            $order_price = $order_price + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];
                            $product_sub_total = $order_price;
                        }
                    }

                    $reqantity = array();
                    foreach ($productdata as $firstkey => $productquantity) {
                        $totalreq = 0;
                        foreach ($productquantity as $secondkey => $pro) {
                            if ($pro['req_quantity'] > $pro['quantity']) {
                                if ((\App::getLocale() == 'en')) {
                                    toastr()->success('Product Quantity is Not Available');
                                } else {
                                    toastr()->success('كمية المنتج غير متوفرة');
                                }
                                return redirect()->back();
                            }
                            $totalreq = $totalreq + $pro['req_quantity'];
                        }

                        $reqantity[$firstkey] = array(
                            "id" => $firstkey,
                            "qty" => $totalreq,
                            "quantity" => $pro['quantity'],
                        );
                    }

                    foreach ($productdata as $firstkey => $productquantity) {
                        foreach ($productquantity as $secondkey => $pro) {
                            $available = $pro['quantity'] - $pro['req_quantity'];
                            $mycoloroptdata = Product_details::where('id', $secondkey)->first();
                            if (!empty($mycoloroptdata)) {
                                Product_details::where('id', $secondkey)->update(['quantity' => $available]);
                            }
                        }
                    }

                    foreach ($reqantity as $checkquantity) {
                        $proquant = Product::where('id', $checkquantity['id'])->first();
                        $newquantity = $proquant->quantity - $checkquantity['qty'];
                        Product::where('id', $checkquantity['id'])->update(['quantity' => $newquantity]);
                    }

                    if (!empty($coupan)) {
                        $discounted_price = number_format((float) ($order_price / 100) * $coupan->discount, 2, '.', '');
                        $discounted_price = $order_price - $discounted_price;

                        $global = Global_settings::all();
                        $ship_charge = 0;
                        $delivery_charge = 0;

                        if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                            if ($order_price >= $global[0]->min_amount_shipping) {
                                $ship_charge = 0;
                            } else {
                                $ship_charge = $global[0]->shipping_charge;
                            }
                        }

                        $discounted_price = $discounted_price + $ship_charge + $delivery_charge;

                        //coupan code management

                        $coupon_history = new Coupon_history;
                        $coupon_history->coupan_id = $coupan->id;
                        $coupon_history->status = 1;
                        $coupon_history->user_id = Auth::id();
                        $coupon_history->save();
                    } else {
                        $global = Global_settings::all();
                        $ship_charge = 0;
                        $delivery_charge = 0;

                        if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                            if ($order_price >= $global[0]->min_amount_shipping) {
                                $ship_charge = 0;
                            } else {
                                $ship_charge = $global[0]->shipping_charge;
                            }
                        }

                        $discounted_price = $order_price + $ship_charge + $delivery_charge;
                    }

                    $history = new Wallet_recharge_history;
                    $history->amount = $paid_by_wallet;
                    $history->user_id = Auth::id();
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "ترتيب";

                    $history->save();

                    $order = new Order;
                    $order->payment_type = 2;
                    $order->delivery_price = $delivery_charge;
                    $order->shipping_price = $ship_charge;
                    $order->product_total_amount = $product_sub_total;
                    $order->paid_by_wallet = $paid_by_wallet;
                    $order->coupan_id = Session::get('coupan_code');
                    $order->discount = $discounted_price - ($product_sub_total + $ship_charge + $delivery_charge);
                    $order->user_id = Auth::id();
                    $order->address_id = Session::get('address_id');
                    $order->price = $discounted_price;
                    $order->save();

                    $ordertrack = new Order_track;
                    $ordertrack->order_id = $order->id;
                    $ordertrack->save();

                    $userdata = User::where('id', Auth::id())->first();
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

                    $user_mobile = User::select('mobile')->where('id', Auth::id())->first();

                    $mobile = $user_mobile->mobile;

                    $user = "letsbuy";
                    $password = "Nn0450292**";
                    $mobilenumbers = $mobile;
                    if ((\App::getLocale() == 'en')) {
                        $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
                    } else {
                        $message = ' تم تسجيل طلبك لدينا بنجاح . رقم الطلب ' . $order->id;
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

                    foreach ($productdata as $record) {
                        foreach ($record as $newrecord) {
                            $order_details = new Order_details;
                            $order_details->order_id = $order->id;
                            $order_details->user_id = Auth::id();
                            $order_details->product_id = $newrecord['id'];
                            if (isset($newrecord['color'])) {
                                $order_details->color = $newrecord['color'];
                            }
                            $order_details->quantity = $newrecord['req_quantity'];
                            $order_details->price = $newrecord['price'];
                            $order_details->product_name_en = $newrecord['name_en'];
                            $order_details->product_name_ar = $newrecord['name_ar'];

                            $order_details->save();
                        }
                    }

                    Session::forget('address_id');
                    Session::forget('product');
                    Session::forget('coupan_code');

                    if ((\App::getLocale() == 'en')) {
                        toastr()->success('Order Completed Successfully');
                    } else {
                        toastr()->success('اكتمل الطلب بنجاح');
                    }
                    return redirect('/myorder');

                } else {

                    $discounted_price = $discounted_price - $request->wallet;
                    $wallet = Wallet::where('user_id', Auth::id())->first();
                    Wallet::where('user_id', Auth::id())->update(['amount' => $wallet->amount - $request->wallet]);
                    $paid_by_wallet = $request->wallet;
                    Session::put('wallet', $paid_by_wallet);

                    $user_data = User::where('id', Auth::id())->first();
                    return view('frontend.payment', compact('discounted_price', 'productdata', 'user_data', 'paid_by_wallet'));

                }
            }

            $user_data = User::where('id', Auth::id())->first();
            $paid_by_wallet = 0;
            Session::put('wallet', $paid_by_wallet);
            if ($request->paymenttype == 3) {
                $entityId = "8ac9a4c97dbd4ae3017dc31689fc62e3";
                $types = " MADA";
            } else {
                $types = "VISA MASTER";
                $entityId = "8ac9a4c97dbd4ae3017dc315fc7562da";
            }
            // -------deepak work start-------------//
            $url = "https://oppwa.com/v1/checkouts";
            $data = "entityId=" . $entityId .
                "&amount=" . $discounted_price .
                "&currency=SAR" .
                "&paymentType=DB";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $data = json_decode($responseData)->id;

            return redirect()->route('hyperpay')->with(['types' => $types, 'discounted_price' => $discounted_price, 'data' => $data, 'entityId' => $entityId]);
            // return view('frontend.hyperpay',compact('data','discounted_price','productdata','user_data','paid_by_wallet'));
            // -------deepak work end-------------//
            //   return view('frontend.payment',compact('discounted_price','productdata','user_data','paid_by_wallet'));

        } elseif ($request->paymenttype == 3) {

            if (!empty($coupan)) {
                $discounted_price = number_format((float) ($order_price / 100) * $coupan->discount, 2, '.', '');
                $discounted_price = $order_price - $discounted_price;
            } else {
                $discounted_price = $order_price;
            }

            $global = Global_settings::all();
            $ship_charge = 0;
            $delivery_charge = 0;

            if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                if ($order_price >= $global[0]->min_amount_shipping) {
                    $ship_charge = 0;
                } else {
                    $ship_charge = $global[0]->shipping_charge;
                }
            }

            $discounted_price = $discounted_price + $ship_charge + $delivery_charge;

            $wallet = Wallet::where('user_id', Auth::id())->first();
            Wallet::where('user_id', Auth::id())->update(['amount' => $wallet->amount - $discounted_price]);
            $paid_by_wallet = $discounted_price;

            $product = Session::get('product');
            $coupan_code = Session::get('coupan_code');

            $coupan = Coupon::where('id', $coupan_code)->first();

            $products = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();
            $order_price = 0;

            foreach ($products as $productnew) {
                foreach ($product[$productnew->id] as $key => $colorpro) {
                    if ($colorpro['id'] == $productnew->id) {
                        $productdata[$productnew->id][$key]['req_quantity'] = $colorpro['qty'];
                        $productdata[$productnew->id][$key]['color'] = $key;
                        $productdata[$productnew->id][$key]['id'] = $productnew->id;
                        $productdata[$productnew->id][$key]['name_en'] = $productnew->name_en;
                        $productdata[$productnew->id][$key]['name_ar'] = $productnew->name_ar;
                        $productdata[$productnew->id][$key]['img'] = $productnew->img;
                        if ($key != 'nocolor') {
                            $product_detail_option = explode(',', $key);
                            $products1 = DB::table('product_details')->select('*')->where('status', 1)->where('id', $product_detail_option[0])->first();
                            $productdata[$productnew->id][$key]['quantity'] = $products1->quantity;
                            $productdata[$productnew->id][$key]['price'] = $productnew->price + $products1->price;

                        } else {
                            $productdata[$productnew->id][$key]['quantity'] = $productnew->quantity;
                            $productdata[$productnew->id][$key]['price'] = $productnew->price;
                        }
                        $productdata[$productnew->id][$key]['offer_price'] = $productnew->offer_price;
                        $productdata[$productnew->id][$key]['discount_available'] = $productnew->discount_available;
                    }
                    $order_price = $order_price + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];
                    $product_sub_total = $order_price;
                }
            }

            $reqantity = array();
            foreach ($productdata as $firstkey => $productquantity) {
                $totalreq = 0;
                foreach ($productquantity as $secondkey => $pro) {
                    if ($pro['req_quantity'] > $pro['quantity']) {
                        if ((\App::getLocale() == 'en')) {
                            toastr()->success('Product Quantity is Not Available');
                        } else {
                            toastr()->success('كمية المنتج غير متوفرة');
                        }

                        return redirect()->back();
                    }
                    $totalreq = $totalreq + $pro['req_quantity'];
                }

                $reqantity[$firstkey] = array(
                    "id" => $firstkey,
                    "qty" => $totalreq,
                    "quantity" => $pro['quantity'],
                );
            }

            foreach ($productdata as $firstkey => $productquantity) {
                foreach ($productquantity as $secondkey => $pro) {
                    $available = $pro['quantity'] - $pro['req_quantity'];
                    if (!empty($mycoloroptdata)) {
                        Product_details::where('id', $secondkey)->update(['quantity' => $available]);
                    }

                }
            }

            foreach ($reqantity as $checkquantity) {
                $proquant = Product::where('id', $checkquantity['id'])->first();
                $newquantity = $proquant->quantity - $checkquantity['qty'];
                Product::where('id', $checkquantity['id'])->update(['quantity' => $newquantity]);
            }

            if (!empty($coupan)) {
                $discounted_price = number_format((float) ($order_price / 100) * $coupan->discount, 2, '.', '');
                $discounted_price = $order_price - $discounted_price;

                $global = Global_settings::all();
                $ship_charge = 0;
                $delivery_charge = 0;

                if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                    if ($order_price >= $global[0]->min_amount_shipping) {
                        $ship_charge = 0;
                    } else {
                        $ship_charge = $global[0]->shipping_charge;
                    }
                }

                $discounted_price = $discounted_price + $ship_charge + $delivery_charge;

                //coupan code management

                $coupon_history = new Coupon_history;
                $coupon_history->coupan_id = $coupan->id;
                $coupon_history->status = 1;
                $coupon_history->user_id = Auth::id();
                $coupon_history->save();
            } else {
                $global = Global_settings::all();
                $ship_charge = 0;
                $delivery_charge = 0;

                if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                    if ($order_price >= $global[0]->min_amount_shipping) {
                        $ship_charge = 0;
                    } else {
                        $ship_charge = $global[0]->shipping_charge;
                    }
                }

                $discounted_price = $order_price + $ship_charge + $delivery_charge;
            }

            $history = new Wallet_recharge_history;
            $history->amount = $paid_by_wallet;
            $history->user_id = Auth::id();
            $history->type = 2;
            $history->reason = "Order";
            $history->reason_ar = "ترتيب";

            $history->save();

            $order = new Order;
            $order->payment_type = 6;
            $order->delivery_price = $delivery_charge;
            $order->shipping_price = $ship_charge;
            $order->product_total_amount = $product_sub_total;
            $order->paid_by_wallet = $paid_by_wallet;
            $order->coupan_id = Session::get('coupan_code');
            $order->discount = $discounted_price - ($product_sub_total + $ship_charge + $delivery_charge);
            $order->user_id = Auth::id();
            $order->address_id = Session::get('address_id');
            $order->price = $discounted_price;
            $order->save();

            $ordertrack = new Order_track;
            $ordertrack->order_id = $order->id;
            $ordertrack->save();

            $userdata = User::where('id', Auth::id())->first();
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

            $user_mobile = User::select('mobile')->where('id', Auth::id())->first();

            $mobile = $user_mobile->mobile;

            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            if ((\App::getLocale() == 'en')) {
                $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
            } else {
                $message = ' تم تسجيل طلبك لدينا بنجاح . رقم الطلب ' . $order->id;
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

            foreach ($productdata as $record) {
                foreach ($record as $newrecord) {
                    $order_details = new Order_details;
                    $order_details->order_id = $order->id;
                    $order_details->user_id = Auth::id();
                    $order_details->product_id = $newrecord['id'];
                    if (isset($newrecord['color'])) {
                        $order_details->color = $newrecord['color'];
                    }
                    $order_details->quantity = $newrecord['req_quantity'];
                    $order_details->price = $newrecord['price'];
                    $order_details->product_name_en = $newrecord['name_en'];
                    $order_details->product_name_ar = $newrecord['name_ar'];

                    $order_details->save();
                }
            }

            Session::forget('address_id');
            Session::forget('product');
            Session::forget('coupan_code');

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Order Completed Successfully');
            } else {
                toastr()->success('اكتمل الطلب بنجاح');
            }

            return redirect('/myorder');

        } elseif ($request->paymenttype == 5) {
            foreach ($productdata as $firstkey => $productquantity) {
                foreach ($productquantity as $secondkey => $pro) {

                    $available = $pro['quantity'] - $pro['req_quantity'];
                    $mycoloroptdata = Product_details::where('id', $secondkey)->first();
                    if (!empty($mycoloroptdata)) {
                        Product_details::where('id', $secondkey)->update(['quantity' => $available]);
                    }
                }
            }

            foreach ($reqantity as $checkquantity) {
                $proquant = Product::where('id', $checkquantity['id'])->first();
                $newquantity = $proquant->quantity - $checkquantity['qty'];
                Product::where('id', $checkquantity['id'])->update(['quantity' => $newquantity]);
            }

            if (!empty($coupan)) {
                $discounted_price = number_format((float) ($order_price / 100) * $coupan->discount, 2, '.', '');
                $discounted_price = $order_price - $discounted_price;

                $global = Global_settings::all();
                $ship_charge = 0;
                $delivery_charge = 0;
                if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                    if ($order_price >= $global[0]->min_amount_shipping) {
                        $ship_charge = 0;
                    } else {
                        $ship_charge = $global[0]->shipping_charge;
                    }
                }

                $discounted_price = $discounted_price + $ship_charge + $delivery_charge;

                //coupan code management

                $coupon_history = new Coupon_history;
                $coupon_history->coupan_id = $coupan->id;
                $coupon_history->status = 1;
                $coupon_history->user_id = Auth::id();
                $coupon_history->save();
            } else {
                $global = Global_settings::all();
                $ship_charge = 0;

                if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                    if ($order_price >= $global[0]->min_amount_shipping) {
                        $ship_charge = 0;
                    } else {
                        $ship_charge = $global[0]->shipping_charge;
                    }
                }
                $delivery_charge = 0;

                $discounted_price = $order_price + $ship_charge + $delivery_charge;

            }

            if (!empty($request->wallet)) {
                $walletamount = $request->wallet;

                if ($walletamount >= $discounted_price) {
                    $wallet = Wallet::where('user_id', Auth::id())->first();
                    Wallet::where('user_id', Auth::id())->update(['amount' => $wallet->amount - $discounted_price]);
                    $walletamount = $discounted_price;

                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = Auth::id();
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "ترتيب";
                    $history->save();

                } else {
                    $wallet = Wallet::where('user_id', Auth::id())->first();
                    Wallet::where('user_id', Auth::id())->update(['amount' => $wallet->amount - $request->wallet]);
                    $walletamount = $request->wallet;

                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = Auth::id();
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "ترتيب";
                    $history->save();

                }

            } else {
                $walletamount = 0;
            }

            $order = new Order;
            $order->payment_type = 3;
            $order->shipping_price = $ship_charge;
            $order->delivery_price = $delivery_charge;
            $order->coupan_id = Session::get('coupan_code');
            $order->discount = $discounted_price - ($product_sub_total + $ship_charge + $delivery_charge);
            $order->product_total_amount = $product_sub_total;
            $order->paid_by_wallet = $walletamount;
            $order->user_id = Auth::id();
            $order->address_id = Session::get('address_id');
            $order->price = $discounted_price;
            $order->save();

            $ordertrack = new Order_track;
            $ordertrack->order_id = $order->id;
            $ordertrack->save();

            $userdata = User::where('id', Auth::id())->first();
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

            $user_mobile = User::select('mobile')->where('id', Auth::id())->first();

            $mobile = $user_mobile->mobile;

            $user = "letsbuy";
            $password = "Nn0450292**";
            $mobilenumbers = $mobile;
            if ((\App::getLocale() == 'en')) {
                $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
            } else {
                $message = ' تم تسجيل طلبك لدينا بنجاح . رقم الطلب ' . $order->id;
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

            foreach ($productdata as $record) {
                foreach ($record as $newrecord) {
                    $order_details = new Order_details;
                    $order_details->order_id = $order->id;
                    $order_details->user_id = Auth::id();
                    $order_details->product_id = $newrecord['id'];
                    if (isset($newrecord['color'])) {
                        $order_details->color = $newrecord['color'];
                    }
                    $order_details->quantity = $newrecord['req_quantity'];
                    $order_details->price = $newrecord['price'];
                    $order_details->product_name_en = $newrecord['name_en'];
                    $order_details->product_name_ar = $newrecord['name_ar'];

                    $order_details->save();
                }
            }

            Session::forget('address_id');
            Session::forget('product');
            Session::forget('coupan_code');

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Order Completed Successfully');
            } else {
                toastr()->success('اكتمل الطلب بنجاح');
            }
            return redirect('/myorder');
        }elseif ($request->wallet != null && $request->paymenttype != 1 &&  $request->paymenttype != 2 && $request->paymenttype != 3 && $request->paymenttype != 5) {
          
            foreach ($productdata as $firstkey => $productquantity) {
                foreach ($productquantity as $secondkey => $pro) {

                    $available = $pro['quantity'] - $pro['req_quantity'];
                    $mycoloroptdata = Product_details::where('id', $secondkey)->first();
                    if (!empty($mycoloroptdata)) {
                        Product_details::where('id', $secondkey)->update(['quantity' => $available]);
                    }
                }
            }

            foreach ($reqantity as $checkquantity) {
                $proquant = Product::where('id', $checkquantity['id'])->first();
                $newquantity = $proquant->quantity - $checkquantity['qty'];
                Product::where('id', $checkquantity['id'])->update(['quantity' => $newquantity]);
            }

            if (!empty($coupan)) {
                $discounted_price = number_format((float) ($order_price / 100) * $coupan->discount, 2, '.', '');
                $discounted_price = $order_price - $discounted_price;

                $global = Global_settings::all();
                $ship_charge = 0;

                if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                    if ($order_price >= $global[0]->min_amount_shipping) {
                        $ship_charge = 0;
                    } else {
                        $ship_charge = $global[0]->shipping_charge;
                    }
                }
                $delivery_charge = $global[0]->delivery_charge;

                // $discounted_price = $discounted_price + $ship_charge + $delivery_charge;
                $discounted_price = $discounted_price + $ship_charge;

                //coupan code management

                $coupon_history = new Coupon_history;
                $coupon_history->coupan_id = $coupan->id;
                $coupon_history->status = 1;
                $coupon_history->user_id = Auth::id();
                $coupon_history->save();
            } else {
                $global = Global_settings::all();
                $ship_charge = 0;

                if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                    if ($order_price >= $global[0]->min_amount_shipping) {
                        $ship_charge = 0;
                    } else {
                        $ship_charge = $global[0]->shipping_charge;
                    }
                }
                $delivery_charge = $global[0]->delivery_charge;

                // $discounted_price = $order_price + $ship_charge + $delivery_charge;
                $discounted_price = $order_price + $ship_charge;

            }

           
                $walletamount = $request->wallet;

                if ($walletamount >= $discounted_price) {
                    $wallet = Wallet::where('user_id', Auth::id())->first();
                    Wallet::where('user_id', Auth::id())->update(['amount' => $wallet->amount - $discounted_price]);
                    $walletamount = $discounted_price;

                    $history = new Wallet_recharge_history;
                    $history->amount = $walletamount;
                    $history->user_id = Auth::id();
                    $history->type = 2;
                    $history->reason = "Order";
                    $history->reason_ar = "ترتيب";
                    $history->save();

                } 

            

            $order = new Order;
            $order->payment_type = 10;
            $order->shipping_price = $ship_charge;
            $order->delivery_price = $delivery_charge;
            $order->coupan_id = Session::get('coupan_code');
            $order->discount = $discounted_price - ($product_sub_total + $ship_charge + $delivery_charge);
            $order->product_total_amount = $product_sub_total;
            $order->paid_by_wallet = $walletamount;
            $order->user_id = Auth::id();
            $order->address_id = Session::get('address_id');
            $order->price = $discounted_price;
            $order->save();

            $ordertrack = new Order_track;
            $ordertrack->order_id = $order->id;
            $ordertrack->save();

            $userdata = User::where('id', Auth::id())->first();
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

            $user_mobile = User::select('mobile')->where('id', Auth::id())->first();

            $mobile = $user_mobile->mobile;

  

            foreach ($productdata as $record) {
                foreach ($record as $newrecord) {
                    $order_details = new Order_details;
                    $order_details->order_id = $order->id;
                    $order_details->user_id = Auth::id();
                    $order_details->product_id = $newrecord['id'];
                    if (isset($newrecord['color'])) {
                        $order_details->color = $newrecord['color'];
                    }
                    $order_details->quantity = $newrecord['req_quantity'];
                    $order_details->price = $newrecord['price'];
                    $order_details->product_name_en = $newrecord['name_en'];
                    $order_details->product_name_ar = $newrecord['name_ar'];

                    $order_details->save();
                }
            }

            Session::forget('address_id');
            Session::forget('product');
            Session::forget('coupan_code');

            if ((\App::getLocale() == 'en')) {
                toastr()->success('Order Completed Successfully');
            } else {
                toastr()->success('اكتمل الطلب بنجاح');
            }
            return redirect('/myorder');
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
                $product = Session::get('product');
                $coupan_code = Session::get('coupan_code');

                $coupan = Coupon::where('id', $coupan_code)->first();

                $products = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();
                $order_price = 0;
                $dataSet = [];
                foreach ($products as $key => $value) {
                    $category_name = Category::where('id', $value->category_id)->value('category_name_en');
                    $value->category_name = str_replace(' ', '-', $category_name);
                    $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
                    $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
                    $dataSet[$key]['item_id'] = $value->id;
                    $dataSet[$key]['item_name'] = $value->name_en;
                    $dataSet[$key]['affiliation'] = "Google Store";
                    $dataSet[$key]['coupon'] = "SUMMER_FUN";
                    $dataSet[$key]['currency'] = "SR";
                    $dataSet[$key]['discount'] = 2.22;
                    $dataSet[$key]['index'] = 5;
                    $dataSet[$key]['item_brand'] = "Google";
                    $dataSet[$key]['item_category'] = str_replace(' ', '-', $category_name);
                    $dataSet[$key]['item_category2'] = str_replace(' ', '-', $subcategory_name);
                    $dataSet[$key]['item_list_id'] = $value->category_id;
                    $dataSet[$key]['item_list_name'] = str_replace(' ', '-', $category_name);
                    $dataSet[$key]['price'] = $value->price;
                    $dataSet[$key]['quantity'] = 1;
                }
                foreach ($products as $productnew) {
                    foreach ($product[$productnew->id] as $key => $colorpro) {
                        if ($colorpro['id'] == $productnew->id) {
                            $productdata[$productnew->id][$key]['req_quantity'] = $colorpro['qty'];
                            $productdata[$productnew->id][$key]['color'] = $key;
                            $productdata[$productnew->id][$key]['id'] = $productnew->id;
                            $productdata[$productnew->id][$key]['name_en'] = $productnew->name_en;
                            $productdata[$productnew->id][$key]['name_ar'] = $productnew->name_ar;
                            $productdata[$productnew->id][$key]['img'] = $productnew->img;
                            if ($key != 'nocolor') {
                                $product_detail_option = explode(',', $key);
                                $products1 = DB::table('product_details')->select('*')->where('status', 1)->where('id', $product_detail_option[0])->first();
                                $productdata[$productnew->id][$key]['quantity'] = $products1->quantity;
                                $productdata[$productnew->id][$key]['price'] = $productnew->price + $products1->price;

                            } else {
                                $productdata[$productnew->id][$key]['quantity'] = $productnew->quantity;
                                $productdata[$productnew->id][$key]['price'] = $productnew->price;
                            }
                            $productdata[$productnew->id][$key]['offer_price'] = $productnew->offer_price;
                            $productdata[$productnew->id][$key]['discount_available'] = $productnew->discount_available;
                        }
                        $order_price = $order_price + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];
                        $product_sub_total = $order_price;
                    }
                }

                $reqantity = array();
                foreach ($productdata as $firstkey => $productquantity) {
                    $totalreq = 0;
                    foreach ($productquantity as $secondkey => $pro) {
                        if ($pro['req_quantity'] > $pro['quantity']) {
                            if ((\App::getLocale() == 'en')) {
                                toastr()->success('Product Quantity is Not Available');
                            } else {
                                toastr()->success('كمية المنتج غير متوفرة');
                            }
                            return redirect()->back();
                        }
                        $totalreq = $totalreq + $pro['req_quantity'];
                    }

                    $reqantity[$firstkey] = array(
                        "id" => $firstkey,
                        "qty" => $totalreq,
                        "quantity" => $pro['quantity'],
                    );
                }

                foreach ($productdata as $firstkey => $productquantity) {
                    foreach ($productquantity as $secondkey => $pro) {
                        $available = $pro['quantity'] - $pro['req_quantity'];
                        if (!empty($mycoloroptdata)) {
                            Product_details::where('id', $secondkey)->update(['quantity' => $available]);
                        }
                    }
                }

                foreach ($reqantity as $checkquantity) {
                    $proquant = Product::where('id', $checkquantity['id'])->first();
                    $newquantity = $proquant->quantity - $checkquantity['qty'];
                    Product::where('id', $checkquantity['id'])->update(['quantity' => $newquantity]);
                }

                if (!empty($coupan)) {
                    $discounted_price = number_format((float) ($order_price / 100) * $coupan->discount, 2, '.', '');
                    $discounted_price = $order_price - $discounted_price;

                    $global = Global_settings::all();
                    $ship_charge = 0;
                    $delivery_charge = 0;

                    if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                        if ($order_price >= $global[0]->min_amount_shipping) {
                            $ship_charge = 0;
                        } else {
                            $ship_charge = $global[0]->shipping_charge;
                        }
                    }

                    $discounted_price = $discounted_price + $ship_charge + $delivery_charge;

                    //coupan code management

                    $coupon_history = new Coupon_history;
                    $coupon_history->coupan_id = $coupan->id;
                    $coupon_history->status = 1;
                    $coupon_history->user_id = Auth::id();
                    $coupon_history->save();
                } else {
                    $global = Global_settings::all();
                    $ship_charge = 0;
                    $delivery_charge = 0;

                    if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                        if ($order_price >= $global[0]->min_amount_shipping) {
                            $ship_charge = 0;
                        } else {
                            $ship_charge = $global[0]->shipping_charge;
                        }
                    }

                    $discounted_price = $order_price + $ship_charge + $delivery_charge;
                }

                $wallet = Session::get('wallet');

                $history = new Wallet_recharge_history;
                $history->amount = $wallet;
                $history->user_id = Auth::id();
                $history->type = 2;
                $history->reason = "Order";
                $history->reason_ar = "ترتيب";

                $history->save();

                $order = new Order;
                $order->payment_type = 2;
                $order->delivery_price = $delivery_charge;
                $order->shipping_price = $ship_charge;
                $order->product_total_amount = $product_sub_total;
                $order->coupan_id = Session::get('coupan_code');
                $order->discount = $result['amount'] + $wallet - ($product_sub_total + $ship_charge + $delivery_charge);
                $order->user_id = Auth::id();
                $order->address_id = Session::get('address_id');
                $order->price = $result['amount'] + $wallet;
                $order->paid_by_wallet = $wallet;

                $order->save();

                $ordertrack = new Order_track;
                $ordertrack->order_id = $order->id;
                $ordertrack->save();

                $userdata = User::where('id', Auth::id())->first();
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

                $user_mobile = User::select('mobile')->where('id', Auth::id())->first();

                $mobile = $user_mobile->mobile;

                $user = "letsbuy";
                $password = "Nn0450292**";
                $mobilenumbers = $mobile;
                if ((\App::getLocale() == 'en')) {
                    $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
                } else {
                    $message = ' تم تسجيل طلبك لدينا بنجاح . رقم الطلب ' . $order->id;
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
                    $transaction->user_id = Auth::id();
                    $transaction->order_id = $order->id;
                    $transaction->amount = $result['amount'];
                    $transaction->currency = $result['currency'];
                    $transaction->track_id = $result['reference']['track'];
                    $transaction->payment_id = $result['reference']['payment'];
                    $transaction->transaction_generate_id = $result['reference']['transaction'];
                    // $transaction->order_generate_id = $result['reference']['order'];
                    $transaction->receipt_id = $result['receipt']['id'];
                    $transaction->payment_method = $result['source']['payment_method'];
                    $transaction->payment_type = $result['source']['payment_type'];
                    $transaction->token_id = $result['source']['id'];

                    $transaction->save();
                }

                foreach ($productdata as $record) {
                    foreach ($record as $newrecord) {
                        $order_details = new Order_details;
                        $order_details->order_id = $order->id;
                        $order_details->user_id = Auth::id();
                        $order_details->product_id = $newrecord['id'];
                        if (isset($newrecord['color'])) {
                            $order_details->color = $newrecord['color'];
                        }
                        $order_details->quantity = $newrecord['req_quantity'];
                        $order_details->price = $newrecord['price'];
                        $order_details->product_name_en = $newrecord['name_en'];
                        $order_details->product_name_ar = $newrecord['name_ar'];

                        $order_details->save();
                    }
                }

                Session::forget('address_id');
                Session::forget('product');
                Session::forget('coupan_code');
                Session::forget('wallet');

                if ((\App::getLocale() == 'en')) {
                    toastr()->success('Order Completed Successfully');
                } else {
                    toastr()->success('اكتمل الطلب بنجاح');
                }
                Session::put('PaymentData', $dataSet);
                Session::put('order_price', $order_price);
                return redirect('/myorder');

            } else {
                if ((\App::getLocale() == 'en')) {
                    toastr()->success('Your Payment Is Declined By Bank');
                } else {
                    toastr()->success('تم رفض مدفوعاتك من قبل البنك');
                }
                return redirect(URL::to('/'));

            }

        }

    }

    public function reorder(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();
        $order_details = Order_details::where('order_id', $id)->get();

        Session::put('address_id', $order->address_id);

        foreach ($order_details as $record) {
            $product[$record->product_id][$record->color] = array(
                "id" => $record->product_id,
                "qty" => $record->quantity,
                "color" => $record->color,
            );

            Session::put('product', $product);
        }

        $address = Session::get('address_id', $order->address_id);

        $addressid = Address::where('id', $address)->first();

        if (!empty($addressid)) {
            $url = url('/') . '/' . app()->getLocale() . '/checkoutpayment?address_id=' . base64_encode($address);

            return redirect($url);
        } else {
            return redirect('checkoutaddress');
        }

    }

    public function invoice(Request $request, $id)
    {
        $id = base64_decode($id);
        $order = Order::where('id', $id)->first();
        $order_details = Order_details::where('order_id', $id)->get();
        $address_detail = Address::where('id', $order->address_id)->first();
        $user_data = User::where('id', $order->user_id)->first();
        $countryname = DB::table('country')->select('name')->where('country_id', $address_detail->country)->first();

        return view('frontend.invoice', compact('order', 'order_details', 'address_detail', 'user_data', 'countryname'));
    }
    public function orderPurchaseInfo(Request $request)
    {
        $rules = [
            'paymenttype' => 'required',
        ];

        $messages = [
            'paymenttype.required' => 'Please Choose Payment Type',
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            return response()->json(['status' => false], 200);
        }

        if ($request->paymenttype == "2" || $request->paymenttype == "5") {

            $product = Session::get('product');

            $new_array = array();

            foreach ($product as $key => $value) {
                if (!empty($value)) {
                    $new_array[$key] = $value;
                }
            }

            if (empty($new_array)) {
                return response()->json(['status' => false], 200);
            }

            $coupan_code = Session::get('coupan_code');

            $coupan = Coupon::where('id', $coupan_code)->first();

            $products = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();
            $dataSet = [];
            foreach ($products as $key => $value) {

                $category_name = Category::where('id', $value->category_id)->value('category_name_en');

                $value->category_name = str_replace(' ', '-', $category_name);

                $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
                $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
                $dataSet[$key]['item_id'] = $value->id;
                $dataSet[$key]['item_name'] = $value->name_en;
                $dataSet[$key]['affiliation'] = "Google Store";
                $dataSet[$key]['coupon'] = "SUMMER_FUN";
                $dataSet[$key]['currency'] = "SR";
                $dataSet[$key]['discount'] = 2.22;
                $dataSet[$key]['index'] = 5;
                $dataSet[$key]['item_brand'] = "Google";
                $dataSet[$key]['item_category'] = str_replace(' ', '-', $category_name);
                $dataSet[$key]['item_category2'] = str_replace(' ', '-', $subcategory_name);
                $dataSet[$key]['item_list_id'] = $value->category_id;
                $dataSet[$key]['item_list_name'] = str_replace(' ', '-', $category_name);
                $dataSet[$key]['price'] = $value->price;
                $dataSet[$key]['quantity'] = 1;
            }

            return response()->json(['message' => $dataSet, 'order_price' => $request->order_price, 'status' => true], 200);
        } else {
            return response()->json(['status' => false], 200);
        }

    }
    public function orderPurchaseSession(Request $request)
    {
        if (session()->has('PaymentData')) {
            $data = session()->get('PaymentData')[0];
            $order_price = session()->get('order_price');

            Session::forget('PaymentData');

            Session::forget('order_price');

            return response()->json(['message' => $data, 'order_price' => $order_price, 'status' => true], 200);
        } else {
            return response()->json(['status' => false], 200);
        }
    }

    public function hyperpayPayment($id, $entry_id)
    {
          
        //payment status
        $url = "https://eu-prod.oppwa.com/v1/checkouts/" . $id . "/payment";
        $url .= "?entityId=" . $entry_id;

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
      
        if (!isset($payment_status->id) || !isset($payment_status->amount) || !isset($payment_status->currency)) {
           
            if ((\App::getLocale() == 'en')) {
                toastr()->error('Something went wrong');
            } else {
                toastr()->error('هناك خطأ ما');
            }
            return redirect()->back();
        }
        //payment Capture
        $url = "https://eu-prod.oppwa.com/v1/payments/" . $payment_status->id;
        $data = "entityId=" . $entry_id .
        "&amount=" . $payment_status->amount ?? '0' .
        "&currency=" . $payment_status->currency .
            "&paymentType=DB";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjOWE0Yzk3ZGJkNGFlMzAxN2RjMzE1N2NlNzYyY2Z8S3lSZTlZaHJqRA=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $payment_capture = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $payment_capture = json_decode($payment_capture);

        $product = Session::get('product');

        $coupan_code = Session::get('coupan_code');

        $coupan = Coupon::where('id', $coupan_code)->first();

        $products = DB::table('products')->select('*')->where('status', 1)->whereIn('id', array_keys($product))->orderby('id', 'desc')->get();
        $order_price = 0;
        $dataSet = [];
        foreach ($products as $key => $value) {
            $category_name = Category::where('id', $value->category_id)->value('category_name_en');
            $value->category_name = str_replace(' ', '-', $category_name);
            $subcategory_name = Subcategory::where('id', $value->sub_category_id)->value('sub_category_name_en');
            $value->sub_category_name = str_replace(' ', '-', $subcategory_name);
            $dataSet[$key]['item_id'] = $value->id;
            $dataSet[$key]['item_name'] = $value->name_en;
            $dataSet[$key]['affiliation'] = "Google Store";
            $dataSet[$key]['coupon'] = "SUMMER_FUN";
            $dataSet[$key]['currency'] = "SR";
            $dataSet[$key]['discount'] = 2.22;
            $dataSet[$key]['index'] = 5;
            $dataSet[$key]['item_brand'] = "Google";
            $dataSet[$key]['item_category'] = str_replace(' ', '-', $category_name);
            $dataSet[$key]['item_category2'] = str_replace(' ', '-', $subcategory_name);
            $dataSet[$key]['item_list_id'] = $value->category_id;
            $dataSet[$key]['item_list_name'] = str_replace(' ', '-', $category_name);
            $dataSet[$key]['price'] = $value->price;
            $dataSet[$key]['quantity'] = 1;
        }
        foreach ($products as $productnew) {
            foreach ($product[$productnew->id] as $key => $colorpro) {
                if ($colorpro['id'] == $productnew->id) {
                    $productdata[$productnew->id][$key]['req_quantity'] = $colorpro['qty'];
                    $productdata[$productnew->id][$key]['color'] = $key;
                    $productdata[$productnew->id][$key]['id'] = $productnew->id;
                    $productdata[$productnew->id][$key]['name_en'] = $productnew->name_en;
                    $productdata[$productnew->id][$key]['name_ar'] = $productnew->name_ar;
                    $productdata[$productnew->id][$key]['img'] = $productnew->img;
                    if ($key != 'nocolor') {
                        $product_detail_option = explode(',', $key);
                        $products1 = DB::table('product_details')->select('*')->where('status', 1)->where('id', $product_detail_option[0])->first();
                        $productdata[$productnew->id][$key]['quantity'] = $products1->quantity;
                        $productdata[$productnew->id][$key]['price'] = $productnew->price + $products1->price;

                    } else {
                        $productdata[$productnew->id][$key]['quantity'] = $productnew->quantity;
                        $productdata[$productnew->id][$key]['price'] = $productnew->price;
                    }
                    $productdata[$productnew->id][$key]['offer_price'] = $productnew->offer_price;
                    $productdata[$productnew->id][$key]['discount_available'] = $productnew->discount_available;
                }
                $order_price = $order_price + $productdata[$productnew->id][$key]['req_quantity'] * $productdata[$productnew->id][$key]['price'];
                $product_sub_total = $order_price;
            }
        }

        $reqantity = array();
        foreach ($productdata as $firstkey => $productquantity) {
            $totalreq = 0;

            foreach ($productquantity as $secondkey => $pro) {
              
                if ($pro['req_quantity'] > $pro['quantity']) {
                    if ((\App::getLocale() == 'en')) {
                        toastr()->success('Product Quantity is Not Available');
                    } else {
                        toastr()->success('كمية المنتج غير متوفرة');
                    }
                    return redirect()->back();
                }
                $totalreq = $totalreq + $pro['req_quantity'];
            }

            $reqantity[$firstkey] = array(
                "id" => $firstkey,
                "qty" => $totalreq,
                "quantity" => $pro['quantity'],
            );
        }

        foreach ($productdata as $firstkey => $productquantity) {
            foreach ($productquantity as $secondkey => $pro) {
                $available = $pro['quantity'] - $pro['req_quantity'];
                if (!empty($mycoloroptdata)) {
                    Product_details::where('id', $secondkey)->update(['quantity' => $available]);
                }
            }
        }

        foreach ($reqantity as $checkquantity) {
            $proquant = Product::where('id', $checkquantity['id'])->first();
            $newquantity = $proquant->quantity - $checkquantity['qty'];
            Product::where('id', $checkquantity['id'])->update(['quantity' => $newquantity]);
        }

        if (!empty($coupan)) {
            $discounted_price = number_format((float) ($order_price / 100) * $coupan->discount, 2, '.', '');
            $discounted_price = $order_price - $discounted_price;

            $global = Global_settings::all();
            $ship_charge = 0;
            $delivery_charge = 0;

            if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                if ($order_price >= $global[0]->min_amount_shipping) {
                    $ship_charge = 0;
                } else {
                    $ship_charge = $global[0]->shipping_charge;
                }
            }

            $discounted_price = $discounted_price + $ship_charge + $delivery_charge;

            //coupan code management

            $coupon_history = new Coupon_history;
            $coupon_history->coupan_id = $coupan->id;
            $coupon_history->status = 1;
            $coupon_history->user_id = Auth::id();
            $coupon_history->save();
        } else {
            $global = Global_settings::all();
            $ship_charge = 0;
            $delivery_charge = 0;

            if (!empty($global[0]->shipping_charge) && !empty($global[0]->min_amount_shipping)) {
                if ($order_price >= $global[0]->min_amount_shipping) {
                    $ship_charge = 0;
                } else {
                    $ship_charge = $global[0]->shipping_charge;
                }
            }

            $discounted_price = $order_price + $ship_charge + $delivery_charge;
        }

        // $wallet     = Session::get('wallet');

        if (Session::get('wallet')== '1') {
            $wallet = Wallet::where('user_id', Auth::id())->first();
            $walletamount = $wallet->amount;

            
                $wallet= $order_price>$walletamount? 0 :$walletamount-$order_price;
                 Wallet::where('user_id', Auth::id())->update(['amount' => $wallet]);
                $history = new Wallet_recharge_history;
                $history->amount  = $wallet;
                $history->user_id = Auth::id();
                $history->type    = 2;
                $history->reason  = "Order";
                $history->reason_ar  = "ترتيب";
                $history->save();
        } 

        

        $order = new Order;
        $order->payment_type = 1;
        $order->delivery_price = $delivery_charge;
        $order->shipping_price = $ship_charge;
        $order->product_total_amount = $product_sub_total;
        $order->coupan_id = Session::get('coupan_code');
        // $order->discount             =   $payment_status->amount+($product_sub_total+$ship_charge+$delivery_charge);
        $order->user_id = Auth::id();
        $order->address_id = Session::get('address_id');
        $order->price = $payment_status->amount;
        // $order->paid_by_wallet       = $wallet;

        $order->save();

        $ordertrack = new Order_track;
        $ordertrack->order_id = $order->id;
        $ordertrack->save();

        $userdata = User::where('id', Auth::id())->first();
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

        $user_mobile = User::select('mobile')->where('id', Auth::id())->first();

        $mobile = $user_mobile->mobile;

        $user = "letsbuy";
        $password = "Nn0450292**";
        $mobilenumbers = $mobile;
        if ((\App::getLocale() == 'en')) {
            $message = 'Your Order is Successfully Placed. Order No ' . $order->id;
        } else {
            $message = ' تم تسجيل طلبك لدينا بنجاح . رقم الطلب ' . $order->id;
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
            // $transaction->order_generate_id = $payment_status->resultDetails->ConnectorTxID1;
            // $transaction->receipt_id = $payment_status->resultDetails->ConnectorTxID1;
            $transaction->payment_method = $payment_status->paymentBrand;
            $transaction->payment_type = $payment_status->paymentType;
            // $transaction->token_id = $payment_capture->referencedId;

            $transaction->save();
        }

        foreach ($productdata as $record) {
            foreach ($record as $newrecord) {
                $order_details = new Order_details;
                $order_details->order_id = $order->id;
                $order_details->user_id = Auth::id();
                $order_details->product_id = $newrecord['id'];
                if (isset($newrecord['color'])) {
                    $order_details->color = $newrecord['color'];
                }
                $order_details->quantity = $newrecord['req_quantity'];
                $order_details->price = $newrecord['price'];
                $order_details->product_name_en = $newrecord['name_en'];
                $order_details->product_name_ar = $newrecord['name_ar'];

                $order_details->save();
            }
        }
        Session::forget('address_id');
        Session::forget('product');
        Session::forget('coupan_code');
        Session::forget('wallet');
        Session::put('PaymentData', $dataSet);
        Session::put('order_price', $order_price);

        // if ($payment_status->result->code == "000.000.000") {
            if ((\App::getLocale() == 'en')) {
                toastr()->success('Order Completed Successfully');
            } else {
                toastr()->success('اكتمل الطلب بنجاح');
            }
            return redirect('/myorder');
        // } else {

        //     if ((\App::getLocale() == 'en')) {
        //         toastr()->success($payment_status->result->code);
        //     } else {
        //         toastr()->success('تم رفض مدفوعاتك من قبل البنك');
        //     }
        //     return redirect(URL::to('/'));

        // }
    }
    public function hyperpayView()
    {
        return view('frontend.hyperpay');
    }
}
