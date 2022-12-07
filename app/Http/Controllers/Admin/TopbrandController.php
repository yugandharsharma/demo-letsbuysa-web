<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\PasswordReset;
use App\Model\Topbrand;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail,Auth,DB;

class TopbrandController extends Controller
{
    public function index(Request $request)
    {
        $banner=DB::table('topbrands')->select('*')->get();
     
        return view('admin.topbrand.index', compact('banner'));
    }
    
    public function delete($id)
    {
        $affectedRows = Topbrand::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }

    public function add(Request $request)
    {
     if ($request->isMethod('post')) 
     {  
         $validator = Validator::make($request->all(), [
            'image_en'      =>    'required|mimes:jpeg,jpg,png,gif',
            'image_ar'      =>    'required|mimes:jpeg,jpg,png,gif',
             
        ]);
   
        if ($validator->fails()) {
            return redirect('admin/top_brands/add')->withInput()->withErrors($validator);
        }

          $banner                          =   new Topbrand;
        //   $banner->name_en                 =   $request->name_en;
        //   $banner->name_ar                 =   $request->name_ar;
          $banner->status                  =   $request->status;

            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/top-brands/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image_en = $filename;
                }
            }

               if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/top-brands/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image_ar = $filename;
                }
            }

          $banner->save();

          return redirect()->back()->with('success', "Record Created Successfully !");

     }
     else 
     {
     return view('admin.topbrand.add');
     }
    }

    public function edit(Request $request,$id)
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
        ];
        $validator = Validator::make($request->all(), $rules);
   
       if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
          $banner                          =   Topbrand::find($id);
        //   $banner->name_en                 =   $request->name_en;
        //   $banner->name_ar                 =   $request->name_ar;
          $banner->status                  =   $request->status;

            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/top-brands/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image_en = $filename;
                }
            }

               if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/top-brands/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image_ar = $filename;
                }
            }

          $banner->save();

          return redirect()->back()->with('success', "Record Updated Successfully !");

     }
     else 
     {
     $banner=Topbrand::where('id', $id)->first();
     return view('admin.topbrand.edit',compact('banner'));
     }
    }
}