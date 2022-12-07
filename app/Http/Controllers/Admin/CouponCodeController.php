<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\PasswordReset;
use App\Model\Coupon;
use App\Model\Address;
use App\Model\Category;
use App\Model\Subcategory;
use App\Model\Gift_voucher;
use App\Model\Emailtemplate;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail;
use Auth;
use DB;

class CouponCodeController extends Controller
{
    public function index(Request $request)
    {
        $coupon=DB::table('coupon')->select('*')->orderby('id','desc')->get();
        return view('admin.coupon.index', compact('coupon'));
    }
    
    public function delete_coupon($id)
    {
        $affectedRows = Coupon::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }


    public function add_coupon(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [

            'coupon_name'            =>    'required|unique:coupon',
            'code'                   =>    'required|unique:coupon',
            'discount'               =>    'required',
            'start_date'             =>    'required',
            'end_date'               =>    'required',
             
        ]);
           
            $start_date=$request->start_date;
            $end_date=$request->end_date;

           if (!empty($start_date && $end_date)) {
                $this->validate($request, [
                'end_date'   => 'date|after_or_equal:start_date',
                'start_date' => 'date',
                ]);
            }

   
            if ($validator->fails()) {
                return redirect('admin/coupon/add')->withInput()->withErrors($validator);
            }

            $coupon                                   =   new Coupon;
            $coupon->coupon_name                      =   $request->coupon_name;
            $coupon->code                             =   $request->code;
            $coupon->discount                         =   $request->discount;
            $coupon->total_amount                     =   $request->total_amount;
            $coupon->start_date                       =   $request->start_date;
            $coupon->end_date                         =   $request->end_date;
            $coupon->status                           =   $request->status;
            $coupon->customerlogin                    =   $request->customerlogin;
            $coupon->type                           =   $request->type;
            if(!empty($request->subcategories)){
            $coupon->subcategories                           =   implode(',',$request->subcategories);
            }
            if(!empty($request->categories)){
            $coupon->categories                           =   implode(',',$request->categories);
            }
            $coupon->uses_per_coupon                           =   $request->uses_per_coupon;
            $coupon->uses_per_customer                           =   $request->uses_per_customer;
            $coupon->save();

            return redirect()->back()->with('success', "coupon Created Successfully !");
        } else {
            $Subcategory=Subcategory::where('status','1')->get();
            $Category=Category::where('status','1')->get();
            return view('admin.coupon.add',compact('Subcategory','Category'));
        }
    }

    public function edit_coupon(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [

            'coupon_name'            =>    'required|unique:coupon,coupon_name,'.$id,
            'code'                   =>    'required|unique:coupon,code,'.$id,
            'discount'               =>    'required',
            'start_date'               =>    'required',
            'end_date'               =>    'required',
             
        ]);
         
           $start_date=$request->start_date;
           $end_date=$request->end_date;

           if (!empty($start_date && $end_date)) {
               $this->validate($request, [
                'end_date'   => 'date|after_or_equal:start_date',
                'start_date' => 'date',
                ]);
           }

   
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $coupon                                   =   Coupon::find($id);
            $coupon->coupon_name                      =   $request->coupon_name;
            $coupon->code                             =   $request->code;
            $coupon->discount                         =   $request->discount;
            $coupon->total_amount                     =   $request->total_amount;
            $coupon->start_date                       =   $request->start_date;
            $coupon->end_date                         =   $request->end_date;
            $coupon->status                           =   $request->status;
            $coupon->customerlogin                    =   $request->customerlogin;
            $coupon->type                           =   $request->type;
            if(!empty($request->subcategories)){
            $coupon->subcategories                           =   implode(',',$request->subcategories);
            }
            if(!empty($request->categories)){
            $coupon->categories                           =   implode(',',$request->categories);
            }
            $coupon->uses_per_coupon                           =   $request->uses_per_coupon;
            $coupon->uses_per_customer                           =   $request->uses_per_customer;
            $coupon->save();

            return redirect()->back()->with('success', "coupon Updated Successfully !");
        } else {
            $coupon=Coupon::where('id', $id)->first();
            $Subcategory=Subcategory::where('status','1')->get();
            $Category=Category::where('status','1')->get();
            return view('admin.coupon.edit', compact('coupon','Subcategory','Category'));
        }
    }

    public function voucher(Request $request)
    {
        $voucher=DB::table('gift_voucher')->select('*')->orderby('id','desc')->get();
        return view('admin.voucher.index', compact('voucher'));
    }


     public function delete_voucher($id)
    {
        $affectedRows = Gift_voucher::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }


    public function add_voucher(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [

            'voucher_name'            =>    'required|unique:gift_voucher',
            'code'                   =>    'required|unique:gift_voucher',
            'amount'                 =>    'required',
            'use_count'              =>    'required',
            'user'                   =>    'required',
            'start_date'             =>    'required',
            'end_date'               =>    'required',
             
        ]);
           
            $start_date=$request->start_date;
            $end_date=$request->end_date;

           if (!empty($start_date && $end_date)) {
                $this->validate($request, [
                'end_date'   => 'date|after_or_equal:start_date',
                'start_date' => 'date',
                ]);
            }

   
            if ($validator->fails()) {
                return redirect('admin/voucher/add')->withInput()->withErrors($validator);
            }

            $voucher                                   =   new Gift_voucher;
            $voucher->voucher_name                     =   $request->voucher_name;
            $voucher->code                             =   $request->code;
            $voucher->amount                           =   $request->amount;
            $voucher->start_date                       =   $request->start_date;
            $voucher->end_date                         =   $request->end_date;
            $voucher->status                           =   $request->status;
            $voucher->use_count                        =   $request->use_count;
            $voucher->user_id                          =   $request->user;
            $voucher->message                          =   $request->message;

            $voucher->save();

            return redirect()->back()->with('success', "Voucher Created Successfully !");
        } else {
            
            $user = User::all();
            return view('admin.voucher.add',compact('user'));
        }
    }

    public function edit_voucher(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [

            'voucher_name'           =>    'required|unique:gift_voucher,voucher_name,'.$id,
            'code'                   =>    'required|unique:gift_voucher,code,'.$id,
            'amount'                 =>    'required',
            'use_count'              =>    'required',
            'user'                   =>    'required',
            'start_date'             =>    'required',
            'end_date'               =>    'required',
             
        ]);
         
           $start_date=$request->start_date;
           $end_date=$request->end_date;

           if (!empty($start_date && $end_date)) {
               $this->validate($request, [
                'end_date'   => 'date|after_or_equal:start_date',
                'start_date' => 'date',
                ]);
           }

   
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $voucher                                   =   Gift_voucher::find($id);
            $voucher->voucher_name                     =   $request->voucher_name;
            $voucher->code                             =   $request->code;
            $voucher->amount                           =   $request->amount;
            $voucher->start_date                       =   $request->start_date;
            $voucher->end_date                         =   $request->end_date;
            $voucher->status                           =   $request->status;
            $voucher->use_count                        =   $request->use_count;
            $voucher->user_id                          =   $request->user;
            $voucher->message                          =   $request->message;

            $voucher->save();

            return redirect()->back()->with('success', "Voucher Updated Successfully !");
        } else {
            $voucher=Gift_voucher::where('id', $id)->first();
            $user = User::all();
            return view('admin.voucher.edit', compact('voucher','user'));
        }
    }


    public function email_voucher(Request $request,$id)
    {

        $voucher=Gift_voucher::where('id', $id)->first();

        $userdata = User::where('id', $voucher->user_id)->first();
        $name = $userdata->name;
        $vouchername = $voucher->voucher_name;
        $code = $voucher->code;
        $amount = $voucher->amount;
        $message =strip_tags($voucher->message);


        $EmailTemplates = Emailtemplate::where('slug', 'voucher')->first();
        $message        = str_replace(array('{name}','{vouchername}','{code}','{amount}','{message}'), array($name,$code,$vouchername,$amount,$message), $EmailTemplates->description_en);
        $subject        = $EmailTemplates->subject_en;
        $to_email       = $userdata->email;
        $data           = array();
        $data['msg']    = $message;
        Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
            $message->to($to_email)
        ->subject($subject);
            $message->from(env('MAIL_USERNAME', 'letsbuysa1@gmail.com'));
        });

        return redirect()->back()->with('success', "Voucher Updated Successfully !");

    }


}
