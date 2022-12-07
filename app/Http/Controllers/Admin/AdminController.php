<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Attributes;
use App\Model\Cart;
use App\Model\Colors;
use App\Model\Content_management;
use App\Model\Emailtemplate;
use App\Model\Filter;
use App\Model\Filter_values;
use App\Model\Global_settings;
use App\Model\Notification;
use App\Model\Option;
use App\Model\Option_value;
use App\Model\Product_details;
use App\Model\Subscribe_email;
use App\User;
use DB;
use Illuminate\Http\Request;
use Redirect;
use Validator;

class AdminController extends Controller
{
    public function global_settings(Request $request)
    {  
        if ($request->isMethod('post')) {
           
            $validator = Validator::make($request->all(), [
                'title_en' => 'required',
                'title_ar' => 'required',
                'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                'supportemail' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
                'mobile' => 'required',
                'min_amount_shipping' => 'required',
                'shipping_charge' => 'required',
                'delivery_charge' => 'required',
                'popup_status' => 'required',
                'popup_url' => 'required',
                'pop_up_subcategory_id' => 'required',
                'pop_up_category_id' => 'required',
                'address_en' => 'required',
                'address_ar' => 'required',
                'footer_description_en' => 'required',
                'footer_description_ar' => 'required',
                'facebook' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                'twitter' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                'instagram' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                'footer_icon_en' => 'mimes:jpeg,jpg,png,svg',
                'footer_icon_ar' => 'mimes:jpeg,jpg,png,svg',
                'mega_sale_banner' => 'mimes:jpeg,jpg,png,svg,gif',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $globalid = DB::table('global_settings')->orderBy('id', 'DESC')->first();
            $global = Global_settings::findOrFail($globalid->id);
            $global->title_en = $request->title_en;
            $global->title_ar = $request->title_ar;
            $global->email = $request->email;
            $global->supportemail = $request->supportemail;
            $global->mobile = $request->mobile;
            $global->min_amount_shipping = $request->min_amount_shipping;
            $global->shipping_charge = $request->shipping_charge;
            $global->delivery_charge = $request->delivery_charge;
            $global->facebook = $request->facebook;
            $global->twitter = $request->twitter;
            $global->instagram = $request->instagram;
            $global->invoice_return_message_en = $request->invoice_return_message_en;
            $global->invoice_return_message_ar = $request->invoice_return_message_ar;
            $global->address_en = $request->address_en;
            $global->popup_status = $request->popup_status;
            $global->popup_url = $request->popup_url;
            $global->codpayment = $request->codpayment;
            $global->bank_transfer = $request->bank_transfer;
            $global->applepay = $request->applepay;
            $global->tappayment = $request->tappayment;
            $global->address_ar = $request->address_ar;
            $global->copyright_en = $request->copyright_en;
            $global->copyright_ar = $request->copyright_ar;
            $global->minimum_reward_point = $request->minimum_reward_point;
            $global->appstoreurl = $request->appstoreurl;
            $global->playstoreurl = $request->playstoreurl;
            $global->pop_up_category_id = $request->pop_up_category_id;
            $global->pop_up_subcategory_id = $request->pop_up_subcategory_id;
            $global->footer_description_en = $request->footer_description_en;
            $global->footer_description_ar = $request->footer_description_ar;

            if ($request->file('footer_icon_en')) {
                $file = $request->file('footer_icon_en');
                if ($file) {
                    $destinationPath = 'public/images/globalsetting/';
                    $extension = $request->file('footer_icon_en')->getClientOriginalExtension();
                    $filename = rand(1, 5000) . '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $global->footer_icon_en = $filename;
                }
            }

            if ($request->file('footer_icon_ar')) {
                $file = $request->file('footer_icon_ar');
                if ($file) {
                    $destinationPath = 'public/images/globalsetting/';
                    $extension = $request->file('footer_icon_ar')->getClientOriginalExtension();
                    $filename = rand(1, 5000) . '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $global->footer_icon_ar = $filename;
                }
            }

            if ($request->file('mega_sale_banner')) {
                $file = $request->file('mega_sale_banner');
                if ($file) {
                    $destinationPath = 'public/images/globalsetting/';
                    $extension = $request->file('mega_sale_banner')->getClientOriginalExtension();
                    $filename = rand(1, 5000) . '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $global->mega_sale_banner = $filename;
                }
            }

            if ($request->file('popup_image_en')) {
                $file = $request->file('popup_image_en');
                if ($file) {
                    $destinationPath = 'public/images/globalsetting/';
                    $extension = $request->file('popup_image_en')->getClientOriginalExtension();
                    $filename = rand(1, 5000) . '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $global->popup_image_en = $filename;
                }
            }

            if ($request->file('popup_image_ar')) {
                $file = $request->file('popup_image_ar');
                if ($file) {
                    $destinationPath = 'public/images/globalsetting/';
                    $extension = $request->file('popup_image_ar')->getClientOriginalExtension();
                    $filename = rand(1, 5000) . '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $global->popup_image_ar = $filename;
                }
            }

            if ($request->file('gift_voucher_image')) {
                $file = $request->file('gift_voucher_image');
                if ($file) {
                    $destinationPath = 'public/images/globalsetting/';
                    $extension = $request->file('gift_voucher_image')->getClientOriginalExtension();
                    $filename = rand(1, 5000) . '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $global->gift_voucher_image = $filename;
                }
            }
            DB::table('cod_countries')->where('global_id',$global->id)->delete();
            if($request->codCountr != null ){
                foreach ($request->codCountry as $key => $value) {
                    DB::table('cod_countries')->insert(['COD_Country_Name'=>$value,'global_id'=>$global->id]);
                }
            }
            
            $global->save();
            return redirect()->back()->with('success', "Global Settings Updated Successfully !");

        } else {
            
            $global_settings = DB::table('global_settings')->select('*')->first();
            $codCountries=DB::table('cod_countries')->where('global_id',$global_settings->id)->pluck('COD_Country_Name')->toArray();
           
            return view('admin.globalsetting', compact('global_settings','codCountries'));
        }

    }

    public function content(Request $request)
    {
        $content = DB::table('content_management')->select('*')->get();
        return view('admin.content_management.index', compact('content'));
    }

    public function view_content(Request $request, $id)
    {
        $content = DB::table('content_management')->select('*')->where('id', $id)->first();
        return view('admin.content_management.view', compact('content'));
    }

    public function edit_content(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'title_en' => 'required',
                'title_ar' => 'required',
                'description_en' => 'required|max:5000',
                'description_ar' => 'required|max:5000',
                'meta_tag_title_en' => 'required',
                'meta_tag_title_ar' => 'required',
                'meta_tag_description_en' => 'required',
                'meta_tag_description_ar' => 'required',
                'meta_tag_keyword_en' => 'required',
                'meta_tag_keyword_ar' => 'required',

            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $content = Content_management::find($id);
            $content->title_en = $request->title_en;
            $content->title_ar = $request->title_ar;
            $content->description_en = $request->description_en;
            $content->description_ar = $request->description_ar;
            $content->meta_tag_title_en = $request->meta_tag_title_en;
            $content->meta_tag_title_ar = $request->meta_tag_title_ar;
            $content->meta_tag_description_en = $request->meta_tag_description_en;
            $content->meta_tag_description_ar = $request->meta_tag_description_ar;
            $content->meta_tag_keyword_en = $request->meta_tag_keyword_en;
            $content->meta_tag_keyword_ar = $request->meta_tag_keyword_ar;

            $content->save();
            return redirect()->back()->with('success', "Content Updated Successfully !");

        } else {
            $content = DB::table('content_management')->select('*')->where('id', $id)->first();
            return view('admin.content_management.edit', compact('content'));
        }

    }

    public function emailtemplate(Request $request)
    {
        $emailtemplate = DB::table('email_template')->select('*')->get();
        return view('admin.email_template.index', compact('emailtemplate'));
    }

    public function emailtemplateview(Request $request, $id)
    {
        $emailtemplate = DB::table('email_template')->select('*')->where('id', $id)->first();
        return view('admin.email_template.view', compact('emailtemplate'));
    }

    public function emailtemplateedit(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'title_en' => 'required',
                'title_ar' => 'required',
                'description_en' => 'required|max:5000',
                'description_ar' => 'required|max:5000',
                'subject_en' => 'required',
                'subject_ar' => 'required',

            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $emailtemplate = Emailtemplate::find($id);
            $emailtemplate->title_en = $request->title_en;
            $emailtemplate->title_ar = $request->title_ar;
            $emailtemplate->description_en = $request->description_en;
            $emailtemplate->description_ar = $request->description_ar;
            $emailtemplate->subject_en = $request->subject_en;
            $emailtemplate->subject_ar = $request->subject_ar;

            $emailtemplate->save();
            return redirect()->back()->with('success', "Email template Updated Successfully !");
        } else {
            $emailtemplate = DB::table('email_template')->select('*')->where('id', $id)->first();
            return view('admin.email_template.edit', compact('emailtemplate'));
        }

    }

    public function color(Request $request)
    {
        $colors = Colors::all();
        return view('admin.colors.index', compact('colors'));

    }

    public function color_add(Request $request)
    {

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'colorcode' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $color = new Colors;
            $color->name = $request->name;
            $color->status = $request->status;
            $color->colorcode = $request->colorcode;

            $color->save();
            return redirect()->back()->with('success', "Color Created Successfully !");

        } else {
            return view('admin.colors.add');
        }
    }

    public function color_edit(Request $request, $id)
    {

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'colorcode' => 'required|max:255',

            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $color = Colors::find($id);
            $color->name = $request->name;
            $color->status = $request->status;
            $color->colorcode = $request->colorcode;

            $color->save();

            return redirect()->back()->with('success', "Color Updated Successfully !");

        } else {
            $colors = Colors::where('id', $id)->first();
            return view('admin.colors.edit', compact('colors'));
        }
    }

    public function color_delete(Request $request, $id)
    {
        $affectedRows = Colors::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }

    public function attribute_index(Request $request)
    {
        $attribute = Attributes::all();
        return view('admin.attributes.index', compact('attribute'));
    }

    public function attribute_add(Request $request)
    {

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name_en' => 'required|max:255',
                'name_ar' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $attribute = new Attributes;
            $attribute->name_en = $request->name_en;
            $attribute->name_ar = $request->name_ar;
            $attribute->status = $request->status;

            $attribute->save();
            return redirect()->back()->with('success', "Attribute Created Successfully !");

        } else {
            return view('admin.attributes.add');
        }
    }

    public function attribute_edit(Request $request, $id)
    {

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name_en' => 'required|max:255',
                'name_ar' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $attribute = Attributes::find($id);
            $attribute->name_en = $request->name_en;
            $attribute->name_ar = $request->name_ar;
            $attribute->status = $request->status;

            $attribute->save();

            return redirect()->back()->with('success', "Attribute Updated Successfully !");

        } else {
            $attribute = Attributes::where('id', $id)->first();
            return view('admin.attributes.edit', compact('attribute'));
        }
    }

    public function attribute_delete(Request $request, $id)
    {
        $affectedRows = Attributes::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }

    public function option(Request $request)
    {
        $option = Option::all();
        return view('admin.option.index', compact('option'));

    }

    public function option_add(Request $request)
    {
        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name_en' => 'required|max:255',
                'name_ar' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $option = new Option;
            $option->name_en = $request->name_en;
            $option->name_ar = $request->name_ar;

            $option->save();

            if (!empty($request->option_value_name_en) && !empty($request->option_value_name_ar) && !empty($request->option_value)) {
                foreach ($request->option_value as $key => $value) {
                    if (!empty($value) && !empty($request->option_value_name_ar[$key]) && !empty($request->option_value_name_en[$key])) {
                        $option_value = new Option_value;
                        $option_value->option_id = $option->id;
                        $option_value->option_value_name_en = $request->option_value_name_en[$key];
                        $option_value->value = $value;
                        $option_value->option_value_name_ar = $request->option_value_name_ar[$key];
                        $option_value->save();
                    }
                }
            }

            return redirect()->back()->with('success', "Options Created Successfully !");

        } else {
            return view('admin.option.add');
        }
    }

    public function option_delete(Request $request, $id)
    {
        $affectedRows = Option::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }

    public function option_edit(Request $request, $id)
    {

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name_en' => 'required|max:255',
                'name_ar' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $option = Option::find($id);
            $option->name_en = $request->name_en;
            $option->name_ar = $request->name_ar;

            $option->save();

            $option_id = Option_value::select('id')->whereIn('id', $request->optionid)->get();
            $all_option_id = Option_value::select('id')->where('option_id', $option->id)->get();
            $allid = array();
            $remainid = array();
            foreach ($all_option_id as $alloptionid) {
                array_push($allid, $alloptionid->id);
            }
            foreach ($option_id as $optionid) {
                array_push($remainid, $optionid->id);
            }
            $result = array_diff($allid, $remainid);

            if (!empty($result)) {
                foreach ($result as $resultdata) {
                    Option_value::where('id', $resultdata)->delete();
                }
            }

            foreach ($request->option_value as $key => $value) {
                if (!empty($request->optionid[$key])) {
                    $option_value = Option_value::find($request->optionid[$key]);
                    $option_value->option_id = $option->id;
                    $option_value->option_value_name_en = $request->option_value_name_en[$key];
                    $option_value->option_value_name_ar = $request->option_value_name_ar[$key];
                    $option_value->value = $value;

                    $option_value->save();

                } else {
                    if (!empty($request->option_value[$key]) && !empty($request->option_value_name_en[$key]) && !empty($request->option_value_name_ar[$key])) {
                        $option_value = new Option_value;
                        $option_value->option_id = $option->id;
                        $option_value->option_value_name_en = $request->option_value_name_en[$key];
                        $option_value->option_value_name_ar = $request->option_value_name_ar[$key];
                        $option_value->value = $value;

                        $option_value->save();

                    }
                }
            }

            return redirect()->back()->with('success', "Option Updated Successfully !");

        } else {
            $option = Option::where('id', $id)->first();
            $option_values = Option_value::where('option_id', $id)->get();
            return view('admin.option.edit', compact('option', 'option_values'));
        }
    }

    public function filter(Request $request)
    {
        $filter = Filter::all();
        return view('admin.filter.index', compact('filter'));

    }

    public function filter_add(Request $request)
    {
        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name_en' => 'required|max:255',
                'name_ar' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $filter = new Filter;
            $filter->name_en = $request->name_en;
            $filter->name_ar = $request->name_ar;

            $filter->save();

            if (!empty($request->filter_name_en) && !empty($request->filter_name_ar)) {
                foreach ($request->filter_name_en as $key => $filtername) {
                    if (!empty($filtername) && !empty($request->filter_name_ar[$key])) {
                        $filter_value = new Filter_values;
                        $filter_value->filter_id = $filter->id;
                        $filter_value->filter_name_en = $filtername;
                        $filter_value->filter_name_ar = $request->filter_name_ar[$key];
                        $filter_value->save();
                    }
                }
            }

            return redirect()->back()->with('success', "Options Created Successfully !");

        } else {
            return view('admin.filter.add');
        }
    }

    public function filter_delete(Request $request, $id)
    {
        $affectedRows = Filter::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }

    public function filter_edit(Request $request, $id)
    {

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'name_en' => 'required|max:255',
                'name_ar' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $filter = Filter::find($id);
            $filter->name_en = $request->name_en;
            $filter->name_ar = $request->name_ar;

            $filter->save();

            $filter_id = Filter_values::select('id')->whereIn('id', $request->filterid)->get();
            $all_filter_id = Filter_values::select('id')->where('filter_id', $filter->id)->get();
            $allid = array();
            $remainid = array();
            foreach ($all_filter_id as $allfilterid) {
                array_push($allid, $allfilterid->id);
            }
            foreach ($filter_id as $filterid) {
                array_push($remainid, $filterid->id);
            }
            $result = array_diff($allid, $remainid);

            if (!empty($result)) {
                foreach ($result as $resultdata) {
                    Filter_values::where('id', $resultdata)->delete();
                }
            }

            foreach ($request->filter_name_en as $key => $filternameen) {
                if (!empty($request->filterid[$key])) {
                    $filter_value = Filter_values::find($request->filterid[$key]);
                    $filter_value->filter_id = $filter->id;
                    $filter_value->filter_name_en = $filternameen;
                    $filter_value->filter_name_ar = $request->filter_name_ar[$key];
                    $filter_value->save();

                } else {
                    if (!empty($request->filter_name_en[$key]) && !empty($request->filter_name_ar[$key])) {
                        $filter_value = new Filter_values;
                        $filter_value->filter_id = $filter->id;
                        $filter_value->filter_name_en = $filternameen;
                        $filter_value->filter_name_ar = $request->filter_name_ar[$key];

                        $filter_value->save();

                    }
                }
            }

            return redirect()->back()->with('success', "Option Updated Successfully !");

        } else {
            $filter = Filter::where('id', $id)->first();
            $filter_values = Filter_values::where('filter_id', $id)->get();
            return view('admin.filter.edit', compact('filter', 'filter_values'));
        }
    }

    public function user_cart(Request $request)
    {
        $user = DB::table('cart')->select('*', DB::raw('(select name from users where users.id=cart.user_id)as name'), DB::raw('(select email from users where users.id=cart.user_id)as email'))->get();

        $mydata = [];
        foreach ($user as $myproductdetail) {
            $data['id'] = $myproductdetail->id;
            $data['product_id'] = $myproductdetail->product_id;
            $data['user_id'] = $myproductdetail->user_id;
            $data['name'] = $myproductdetail->name;
            $data['email'] = $myproductdetail->email;
            $data['quantity'] = $myproductdetail->quantity;
            $data['option_id'] = $myproductdetail->option_id;

            if (count($mydata) > 0) {
                if (array_search($myproductdetail->user_id, array_column($mydata, 'user_id')) === false) {
                    array_push($mydata, $data);
                }
            } else {
                array_push($mydata, $data);
            }
        }
        $user = $mydata;

        return view('admin.user_cart.index', compact('user'));
    }

    public function user_cart_view(Request $request, $id)
    {

        $cartdata = Cart::
            leftjoin('products', 'products.id', '=', 'cart.product_id')
            ->select('products.id as product_id', 'products.name_ar as name', 'cart.quantity', 'products.offer_price as special', 'products.price as price', 'products.price as price', 'cart.option_id as option_id', 'products.img as image', 'products.discount_available as percentage')->where('cart.user_id', base64_decode($id))->get();

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

        $user = $allproducts;

        return view('admin.user_cart.view', compact('user'));
    }

    public function newsletter(Request $request)
    {
        $newsletter = Subscribe_email::paginate(10);
        return view('admin.newsletter.index', compact('newsletter'));
    }
    public function GetNotification()
    {
        $user = User::all();
        return view('admin.notification.create', compact('user'));
    }
    public function PostNotification(Request $request)
    {

        if (!isset($request->emails)) {
            return back()->with('error', "Please Select email!")->withInput();
        }

        $userdatas = User::whereIn('email', $request->emails)->get();

        foreach ($userdatas as $key => $userdata) {
            $Device_token = $userdata->device_token;
            $user_id = $userdata->id;
            $msg = array(
                'body' => strip_tags($request->description),
                'title' => $request->title,
                // 'subtitle' => 'Letsbuy',
                'key' => '5',
                'vibrate' => 1,
                'sound' => 1,
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon',
            );
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
                dd("sdffds");
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);

            $notification = new Notification();
            $notification->user_id = $user_id;
            $notification->message = $msg['body'];
            $notification->message_type = $msg['key'];
            $notification->save();
        }
        return redirect()->back()->with('success', "Notification sent Successfully !");
    }
}
