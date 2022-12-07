<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\PasswordReset;
use App\Model\Customer;
use App\Model\User_group;
use App\Model\Address;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail,Auth,DB;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
     $i = (request()->input('page', 1) - 1) * 10;
     $customer=DB::table('users')->select('*')->where('role',2)->orderby('id','desc')->paginate(10);

    return view('admin.customer.index',compact('customer','i'));
    }

    public function add_customer(Request $request)
    {
     if ($request->isMethod('post'))
     {
          $validator = Validator::make($request->all(), [

            'password'       =>    'required',
            'confirm'        =>    'required',
            'email'          =>    'required|unique:users,email',
            'username'       =>    'required',
            'telephone'      =>    'required|unique:users,email',
            'address_label'  =>    'required',
            'telephone1'     =>    'required',
            'fullname'       =>    'required',

        ]);

          if ($validator->fails()) {
            return redirect('admin/customer/add')->withInput()->withErrors($validator);
          }

          $customer                          =   new User;
          $customer->name                    =   $request->username;
          $customer->user_group_id           =   $request->user_group_id;
          $customer->email                   =   $request->email;
          $customer->mobile                  =   $request->telephone;
          $customer->password                =   Hash::make($request->password);
          $customer->status                  =   $request->status;
          $customer->email_verification      =   $request->approved;
          $customer->role                    =   2;

          $customer->save();

          $customerid=DB::table('users')->select('id')->orderby('id','desc')->first();

          $address                          =   new Address;
          $address->user_id                 =   $customerid->id;
          $address->fulladdress             =   $request->address_label;
          $address->address_details         =   $request->address_details;
          $address->mobile                  =   $request->telephone1;
          $address->fullname                =   $request->fullname;

          $address->save();

          return redirect()->back()->with('success', "Customer Created Successfully !");

     }
     else
     {
     $country=DB::table('country')->select('*')->get();
     $user_group =  User_group::all();

     return view('admin.customer.add',compact('country','user_group'));
     }
    }

    public function state(Request $request,$id)
    {
        $zone=DB::table('zone')->select('*')->where('country_id',$id)->get();
        return $zone;

    }

    public function delete_customer($id)
      {
       $affectedRows = User::where('id',$id)->delete();
       return redirect()->back()->with('info', "Record delete successfully !");
      }


    public function edit_customer(Request $request,$customer_id)
    {
     if ($request->isMethod('post'))
     {
         $validator = Validator::make($request->all(), [

            'password'       =>    'required',
            'confirm'        =>    'required',
            'email'          =>    'required',
            'username'       =>    'required',
            'telephone'      =>    'required',
            'telephone1'     =>    'required',
            'address_label'  =>    'required',
            'fullname'       =>    'required',

        ]);

          if ($validator->fails()) {
              return redirect()->back()->withInput()->withErrors($validator);
          }


          $customer                          =   User::find($customer_id);
          $customer->name                    =   $request->username;
          $customer->user_group_id           =   $request->user_group_id;
          $customer->email                   =   $request->email;
          $customer->mobile                  =   $request->telephone;
          $customer->password                =   Hash::make($request->password);
          $customer->status                  =   $request->status;
          $customer->email_verification      =   $request->approved;

          $customer->save();

          $addressid=DB::table('address')->select('*')->where('user_id',$customer_id)->first();
          if(!empty($addressid))
          {
          $address                          =   Address::find($addressid->id);
          $address->fulladdress             =   $request->address_label;
          $address->user_id                 =   $customer_id;
          $address->mobile                  =   $request->telephone1;
          $address->address_details         =   $request->address_details;
          $address->fullname                =   $request->fullname;

          $address->save();
          }
          else
          {
          $address                          =   new Address;
          $address->fulladdress             =   $request->address_label;
          $address->user_id                 =   $customer_id;
          $address->postcode                =   $request->postcode;
          $address->city                    =   $request->city;
          $address->country_id              =   $request->country_id;
          $address->zone_id                 =   $request->state_id;
          $address->mobile                  =   $request->telephone1;
          $address->fullname                =   $request->fullname;

          $address->save();

          }



          return redirect()->back()->with('success', "Customer Edited Successfully !");

     }
     else
     {
     $country=DB::table('country')->select('*')->get();
     $customer=User::where('id',$customer_id)->first();
     $address=Address::where('user_id', $customer_id)->first();
     $zone=DB::table('zone')->select('*')->get();
     $user_group =  User_group::all();

     return view('admin.customer.edit',compact('country','customer','address','zone','user_group'));
     }
    }

    public function view_customer(Request $request,$customer_id)
    {

     $customer=User::where('id',$customer_id)->first();
     $address=Address::where('user_id', $customer_id)->first();
     $countryname=DB::table('address')->select('*')->where('user_id',$customer_id)->first();

     $country= '';
     $zone= '';
     if(!empty($countryname))
     {
     $country_name=DB::table('country')->select('*')->where('country_id', $countryname->country_id)->first();
     $state_name=DB::table('zone')->select('*')->where('zone_id', $countryname->zone_id)->first();
     if(!empty($country_name))
     {
     $country= $country_name->name;
     }
     if (!empty($state_name))
     {
     $zone= $state_name->name;
     }

     }

     return view('admin.customer.view',compact('country','customer','address','zone'));

    }


    public function customer_filter(Request $request)
    {

    if (!empty($request->start_date) && !empty($request->end_date))
     {
        $from=date('Y-m-d', strtotime($request->start_date));
        $to=date('Y-m-d', strtotime($request->end_date));
     }
    $val=$request->name;
    $start_date=$request->start_date;
    $end_date=$request->end_date;
    $email=$request->email;
    $approved=$request->approved;
    $status=$request->status;

    $Order_details = DB::table('users')->select('*')->where('role',2);

    if(!empty($request->name))
    {
    $columns = array('name');
    $Order_details->where(function($q) use($columns, $val){
        foreach($columns as $search) {
        $q->orWhere($search, 'like', '%' . $val . '%');
        }
    });
    }

    if (!empty($request->email)) {
    $columns = array('email');
    $Order_details->where(function ($q) use ($columns, $email) {
        foreach ($columns as $search) {
            $q->orWhere($search, 'like', '%' . $email . '%');
        }
    });
    }

    if (!empty($request->approved)) {
    $columns = array('email_verification');
    $Order_details->where(function ($q) use ($columns, $approved) {
        foreach ($columns as $search) {
            $q->orWhere($search, 'like', '%' . $approved . '%');
        }
    });
    }

    if (!empty($request->status)) {
    $columns = array('status');
    $Order_details->where(function ($q) use ($columns, $status) {
        foreach ($columns as $search) {
            $q->orWhere($search, 'like', '%' . $status . '%');
        }
    });
}

    if (!empty($request->start_date) && !empty($request->end_date)) {
        if ($from && $to) {
            $Order_details = $Order_details->whereBetween('created_at', [$from, $to]);
        } elseif ($from) {
            $Order_details = $Order_details->where('created_at', $from);
        } elseif ($to) {
            $Order_details = $Order_details->where('created_at', $from);
        }
    }
    $customer= $Order_details->paginate(10);

    $i = (request()->input('page', 1) - 1) * 10;

    return view('admin.customer.index',compact('customer','start_date','end_date','val','email','approved','status','i'));
    }

      public function user_group_index(Request $request)
        {
          $user_group =  User_group::all();
          return view('admin.customer_groups.index',compact('user_group'));
        }

      public function user_group_delete(Request $request,$id)
        {
        $affectedRows = User_group::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
        }

      public function user_group_add(Request $request)
         {

           if($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'description_en' => 'required',
                'description_ar' => 'required',
                'title_en'       => 'required|max:255',
                'title_ar'       => 'required|max:255',
             ]);

        if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

         $user_group = new User_group;
         $user_group->title_en       = $request->title_en;
         $user_group->title_ar       = $request->title_ar;
         $user_group->description_ar = $request->description_ar;
         $user_group->description_en = $request->description_en;
         $user_group->status         = $request->status;

         $user_group->save();
         return redirect()->back()->with('success', "user Group Created Successfully !");

    }
    else
    {
        return view('admin.customer_groups.add');
    }
}

  public function user_group_edit(Request $request,$id)
         {

           if($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'description_en' => 'required',
                'description_ar' => 'required',
                'title_en'       => 'required|max:255',
                'title_ar'       => 'required|max:255',
             ]);

        if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }


         $user_group = User_group::find($id);
         $user_group->title_en       = $request->title_en;
         $user_group->title_ar       = $request->title_ar;
         $user_group->description_ar = $request->description_ar;
         $user_group->description_en = $request->description_en;
         $user_group->status         = $request->status;

         $user_group->save();
         return redirect()->back()->with('success', "product Created Successfully !");

    }
    else
    {
        $data = User_group::where('id',$id)->first();
        return view('admin.customer_groups.edit',compact('data'));
    }
}


}
