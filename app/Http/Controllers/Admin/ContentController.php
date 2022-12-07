<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\PasswordReset;
use App\Model\Aboutus;
use App\Model\Returns;
use App\Model\Privacy_policy;
use App\Model\Bankaccount_payment;
use App\Model\Shipping_delivery;
use App\Model\Terms;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail;
use Auth;
use DB;

class ContentController extends Controller
{
    public function editaboutus(Request $request)
    {
        if ($request->isMethod('post')) {

            $rules = [
            'image_en'           => 'mimes:jpeg,jpg,png',
            'image_ar'           => 'mimes:jpeg,jpg,png',
            'description1_en'    => 'required|max:10000',
            'description1_ar'    => 'required|max:10000',
            'description2_en'    => 'required|max:10000',
            'description2_ar'    => 'required|max:10000',

        ];
            $validator = Validator::make($request->all(), $rules);
   
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $aboutusid=DB::table('aboutus')->orderBy('id', 'DESC')->first();
           
            $aboutus                           =   Aboutus::find($aboutusid->id);
            $aboutus->title_en                 = $request->title_en;
            $aboutus->title_ar                 = $request->title_ar;
            $aboutus->description1_en          = $request->description1_en;
            $aboutus->description1_ar          = $request->description1_ar;
            $aboutus->description2_en          = $request->description2_en;
            $aboutus->description2_ar          = $request->description2_ar;
            $aboutus->meta_tag_title_en        = $request->meta_tag_title_en;
            $aboutus->meta_tag_title_ar        = $request->meta_tag_title_ar;
            $aboutus->meta_tag_keyword_ar      = $request->meta_tag_keyword_ar;
            $aboutus->meta_tag_keyword_en      = $request->meta_tag_keyword_en;
            $aboutus->meta_tag_description_ar  = $request->meta_tag_description_ar;
            $aboutus->meta_tag_description_en  = $request->meta_tag_description_en;


            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/aboutus/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $aboutus->image_en = $filename;
                }
            }

            if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/aboutus/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $aboutus->image_ar = $filename;
                }
            }

            $aboutus->save();

            return redirect()->back()->with('success', "Banner Updated Successfully !");
        } else {
            $aboutus=Aboutus::orderBy('id', 'desc')->first();
            return view('admin.content_pages.aboutus', compact('aboutus'));
        }
    }

     public function editprivacypolicy(Request $request)
    {
        if ($request->isMethod('post')) {

            $rules = [
            'image_en'           => 'mimes:jpeg,jpg,png',
            'image_ar'           => 'mimes:jpeg,jpg,png',
            'description_en'     => 'required|max:10000',
            'description_ar'     => 'required|max:10000',
        ];
            $validator = Validator::make($request->all(), $rules);
   
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $privacyid=DB::table('privacypolicy')->orderBy('id', 'DESC')->first();
           
            $privacy                           = Privacy_policy::find($privacyid->id);
            $privacy->title_en                 = $request->title_en;
            $privacy->title_ar                 = $request->title_ar;
            $privacy->description_en           = $request->description_en;
            $privacy->description_ar           = $request->description_ar;
            $privacy->meta_tag_title_en        = $request->meta_tag_title_en;
            $privacy->meta_tag_title_ar        = $request->meta_tag_title_ar;
            $privacy->meta_tag_keyword_ar      = $request->meta_tag_keyword_ar;
            $privacy->meta_tag_keyword_en      = $request->meta_tag_keyword_en;
            $privacy->meta_tag_description_ar  = $request->meta_tag_description_ar;
            $privacy->meta_tag_description_en  = $request->meta_tag_description_en;

            $privacy->save();

            return redirect()->back()->with('success', "Banner Updated Successfully !");
        } else {
            $privacy=Privacy_policy::orderBy('id', 'desc')->first();
            return view('admin.content_pages.privacy', compact('privacy'));
        }
    }

    public function editterms(Request $request)
    {
        if ($request->isMethod('post')) {

            $rules = [
            'image_en'           => 'mimes:jpeg,jpg,png',
            'image_ar'           => 'mimes:jpeg,jpg,png',
            'description1_en'     => 'required|max:10000',
            'description1_ar'     => 'required|max:10000',
            'description2_en'     => 'required|max:10000',
            'description2_ar'     => 'required|max:10000',
            'description3_en'     => 'required|max:10000',
            'description3_ar'     => 'required|max:10000',
            'description4_en'     => 'required|max:10000',
            'description4_ar'     => 'required|max:10000',
            'description5_en'     => 'required|max:10000',
            'description5_ar'     => 'required|max:10000',
        ];
            $validator = Validator::make($request->all(), $rules);
   
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $termid=DB::table('terms')->orderBy('id', 'DESC')->first();
           
            $terms                           = Terms::find($termid->id);
            $terms->title_en                 = $request->title_en;
            $terms->title_ar                 = $request->title_ar;
            $terms->meta_tag_title_en        = $request->meta_tag_title_en;
            $terms->meta_tag_title_ar        = $request->meta_tag_title_ar;
            $terms->meta_tag_keyword_ar      = $request->meta_tag_keyword_ar;
            $terms->meta_tag_keyword_en      = $request->meta_tag_keyword_en;
            $terms->meta_tag_description_ar  = $request->meta_tag_description_ar;
            $terms->meta_tag_description_en  = $request->meta_tag_description_en;
            $terms->title1_en                = $request->title1_en;
            $terms->title1_ar                = $request->title1_ar;
            $terms->description1_en          = $request->description1_en;
            $terms->description1_ar          = $request->description1_ar;
            $terms->title2_en                = $request->title2_en;
            $terms->title2_ar                = $request->title2_ar;
            $terms->description2_en          = $request->description2_en;
            $terms->description2_ar          = $request->description2_ar;
            $terms->title3_en                = $request->title3_en;
            $terms->title3_ar                = $request->title3_ar;
            $terms->description3_en          = $request->description3_en;
            $terms->description3_ar          = $request->description3_ar;
            $terms->title4_en                = $request->title4_en;
            $terms->title4_ar                = $request->title4_ar;
            $terms->description4_en          = $request->description4_en;
            $terms->description4_ar          = $request->description4_ar;
            $terms->title5_en                = $request->title5_en;
            $terms->title5_ar                = $request->title5_ar;
            $terms->description5_en          = $request->description5_en;
            $terms->description5_ar          = $request->description5_ar;

            $terms->save();

            return redirect()->back()->with('success', "Banner Updated Successfully !");
        } else {
            $terms=Terms::orderBy('id', 'desc')->first();
            return view('admin.content_pages.terms', compact('terms'));
        }
    }

    public function editbankaccount(Request $request)
    {
        if ($request->isMethod('post')) {

            $rules = [
            'image_en'            => 'mimes:jpeg,jpg,png',
            'image_ar'            => 'mimes:jpeg,jpg,png',
            'description_en'     => 'required|max:10000',
            'description_ar'     => 'required|max:10000',
        ];
            $validator = Validator::make($request->all(), $rules);
   
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $bankpaymentid=DB::table('bankaccount_payment')->orderBy('id', 'DESC')->first();
           
            $bankpayment                           = Bankaccount_payment::find($bankpaymentid->id);
            $bankpayment->title_en                 = $request->title_en;
            $bankpayment->title_ar                 = $request->title_ar;
            $bankpayment->description_en           = $request->description_en;
            $bankpayment->description_ar           = $request->description_ar;
            $bankpayment->meta_tag_title_en        = $request->meta_tag_title_en;
            $bankpayment->meta_tag_title_ar        = $request->meta_tag_title_ar;
            $bankpayment->meta_tag_keyword_ar      = $request->meta_tag_keyword_ar;
            $bankpayment->meta_tag_keyword_en      = $request->meta_tag_keyword_en;
            $bankpayment->meta_tag_description_ar  = $request->meta_tag_description_ar;
            $bankpayment->meta_tag_description_en  = $request->meta_tag_description_en;

            $bankpayment->save();

            return redirect()->back()->with('success', "Bank Account Payment Updated Successfully !");
        } else {
            $bankpayment=Bankaccount_payment::orderBy('id', 'desc')->first();
            return view('admin.content_pages.bankaccount', compact('bankpayment'));
        }
    }

    public function shipping_delivery(Request $request)
    {
        if ($request->isMethod('post')) {

            $rules = [
            'image_en'           => 'mimes:jpeg,jpg,png',
            'image_ar'           => 'mimes:jpeg,jpg,png',
            'description_en'     => 'required|max:10000',
            'description_ar'     => 'required|max:10000',
        ];
            $validator = Validator::make($request->all(), $rules);
   
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $shippingid=DB::table('shipping_delivery')->orderBy('id', 'DESC')->first();
           
            $shipping                           = Shipping_delivery::find($shippingid->id);
            $shipping->title_en                 = $request->title_en;
            $shipping->title_ar                 = $request->title_ar;
            $shipping->description_en           = $request->description_en;
            $shipping->description_ar           = $request->description_ar;
            $shipping->meta_tag_title_en        = $request->meta_tag_title_en;
            $shipping->meta_tag_title_ar        = $request->meta_tag_title_ar;
            $shipping->meta_tag_keyword_ar      = $request->meta_tag_keyword_ar;
            $shipping->meta_tag_keyword_en      = $request->meta_tag_keyword_en;
            $shipping->meta_tag_description_ar  = $request->meta_tag_description_ar;
            $shipping->meta_tag_description_en  = $request->meta_tag_description_en;

            $shipping->save();

            return redirect()->back()->with('success', "Shipping Delivery Updated Successfully !");
        } else {
            $shipping=Shipping_delivery::orderBy('id', 'desc')->first();
            return view('admin.content_pages.shipping_delivery', compact('shipping'));
        }
    }


     public function editreturns(Request $request)
    {
        if ($request->isMethod('post')) {

            $rules = [
            'image_en'           => 'mimes:jpeg,jpg,png',
            'image_ar'           => 'mimes:jpeg,jpg,png',
            'description_en'     => 'required|max:10000',
            'description_ar'     => 'required|max:10000',
        ];
            $validator = Validator::make($request->all(), $rules);
   
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $returnid=DB::table('returns')->orderBy('id', 'DESC')->first();
           
            $return                           = Returns::find($returnid->id);
            $return->title_en                 = $request->title_en;
            $return->title_ar                 = $request->title_ar;
            $return->description_en           = $request->description_en;
            $return->description_ar           = $request->description_ar;
            $return->meta_tag_title_en        = $request->meta_tag_title_en;
            $return->meta_tag_title_ar        = $request->meta_tag_title_ar;
            $return->meta_tag_keyword_ar      = $request->meta_tag_keyword_ar;
            $return->meta_tag_keyword_en      = $request->meta_tag_keyword_en;
            $return->meta_tag_description_ar  = $request->meta_tag_description_ar;
            $return->meta_tag_description_en  = $request->meta_tag_description_en;

            $return->save();

            return redirect()->back()->with('success', "Return Updated Successfully !");
        } else {
            $return=Returns::orderBy('id', 'desc')->first();
            return view('admin.content_pages.return', compact('return'));
        }
    }
}


