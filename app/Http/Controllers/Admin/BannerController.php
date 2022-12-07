<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\PasswordReset;
use App\Model\Customer;
use App\Model\Address;
use App\Model\Banner;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail,Auth,DB;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $banner=DB::table('banner')->select('*')->get();
     
        return view('admin.banner.index', compact('banner'));
    }
    
    public function delete_banner($id)
    {
        $affectedRows = Banner::where('banner_id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }

    public function add_banner(Request $request)
    {
     if ($request->isMethod('post')) 
     {  
         $validator = Validator::make($request->all(), [
            'image_en'      =>    'required|mimes:jpeg,jpg,png,gif',
            'image_ar'      =>    'required|mimes:jpeg,jpg,png,gif',
            'url'           =>    'max:250',
             
        ]);
   
        if ($validator->fails()) {
            return redirect('admin/banner/add')->withInput()->withErrors($validator);
        }

          $banner                          =   new Banner;
          $banner->category_id             =   $request->category_id;
          $banner->subcategory_id          =   $request->subcategory_id;
          $banner->status                  =   $request->status;
          $banner->url                     =   $request->url;

            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/banners/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image_en = $filename;
                }
            }

               if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/banners/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image_ar = $filename;
                }
            }

          $banner->save();

          return redirect()->back()->with('success', "Banner Created Successfully !");

     }
     else 
     {
     return view('admin.banner.add');
     }
    }

    public function edit_banner(Request $request,$id)
    {
     if ($request->isMethod('post')) 
     {  

        // $messages = [
        //         'name_en' => "Please Banner Name In English",
        //         'name_ar' => "Please Banner Name In Arabic",
        //     ];

        $rules = [
            'image_en'    => 'mimes:jpeg,jpg,png,gif',
            'image_ar'    => 'mimes:jpeg,jpg,png,gif',
            'url'           =>    'max:250',

        ];
        $validator = Validator::make($request->all(), $rules);
   
       if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
          $banner                          =   Banner::find($id);
          $banner->category_id             =   $request->category_id;
          $banner->subcategory_id          =   $request->subcategory_id;
          $banner->status                  =   $request->status;
          $banner->url                     =   $request->url;
 

            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/banners/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image_en = $filename;
                }
            }

               if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/banners/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image_ar = $filename;
                }
            }

          $banner->save();

          return redirect()->back()->with('success', "Banner Updated Successfully !");

     }
     else 
     {
     $banner=Banner::where('banner_id', $id)->first();
     return view('admin.banner.edit',compact('banner'));
     }
    }
}