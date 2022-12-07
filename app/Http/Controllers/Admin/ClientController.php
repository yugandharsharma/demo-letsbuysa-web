<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\PasswordReset;
use App\Model\Client;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail,Auth,DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $banner=DB::table('clients')->select('*')->get();
     
        return view('admin.client.index', compact('banner'));
    }
    
    public function delete($id)
    {
        $affectedRows = Client::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }

    public function add(Request $request)
    {
     if ($request->isMethod('post')) 
     {  
         $validator = Validator::make($request->all(), [
            'image_en'      =>    'required|mimes:jpeg,jpg,png,gif',
            'image_ar'      =>    'required|mimes:jpeg,jpg,png,gif',
            'url'           =>    'max:250',

             
        ]);
   
        if ($validator->fails()) {
            return redirect('admin/side_banner/add')->withInput()->withErrors($validator);
        }

          $banner                          =   new Client;
          $banner->category_id             =   $request->category_id;
          $banner->subcategory_id          =   $request->subcategory_id;
          $banner->status                  =   $request->status;
          $banner->url                     =   $request->url;


            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/side-banner/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image_en = $filename;
                }
            }

               if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/side-banner/';
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
     return view('admin.client.add');
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
            'url'           =>    'max:250',

        ];
        $validator = Validator::make($request->all(), $rules);
   
       if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
          $banner                          =   Client::find($id);
          $banner->category_id             =   $request->category_id;
          $banner->subcategory_id          =   $request->subcategory_id;
          $banner->status                  =   $request->status;
          $banner->url                     =   $request->url;

            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/side-banner/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image_en = $filename;
                }
            }

               if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/side-banner/';
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
     $banner=Client::where('id', $id)->first();
     return view('admin.client.edit',compact('banner'));
     }
    }
}