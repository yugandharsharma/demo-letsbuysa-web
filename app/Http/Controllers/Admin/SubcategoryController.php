<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\PasswordReset;
use App\Model\Product;
use App\Model\Customer;
use App\Model\Address;
use App\Model\Subcategory;
use App\Model\Filter_values;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail;
use Auth;
use DB;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $subcategory = DB::table('sub_category')
          ->leftjoin('category AS A', 'A.id', '=', 'sub_category.id')
          ->select('sub_category.*', 'A.category_name_en as category_name')
          ->orderBy('id', 'desc')
          ->get();

        return view('admin.subcategory.index', compact('subcategory'));
    }
    
    public function delete_sub_category($id)
    {  
        $products =  Product::where('sub_category_id',$id)->get();

        if(count($products)>0)
        {
            return redirect()->back()->with('infoerror', "Category/Subcategory Associated With Products!");
        }
        $affectedRows = Subcategory::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");

 
    }


    public function add_sub_category(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
            'category_id'                =>    'required',
            'sub_category_name_en'       =>    'required|unique:sub_category',
            'sub_category_name_ar'       =>    'required|unique:sub_category',
            'image_en'                   =>    'required|mimes:jpeg,jpg,png,svg',
            'image_ar'                   =>    'required|mimes:jpeg,jpg,png,svg',

             
            ]);
   
            if ($validator->fails()) {
                return redirect('admin/sub_category/add')->withInput()->withErrors($validator);
            }

            $productfilters='';

            if(!empty($request->productfilters))
            {
            $productfilters = implode(',', $request->productfilters); 
            }
   

            $subcategory                           =   new Subcategory;
            $subcategory->category_id              =   $request->category_id;
            $subcategory->sub_category_name_en     =   $request->sub_category_name_en;
            $subcategory->sub_category_name_ar     =   $request->sub_category_name_ar;
            $subcategory->sub_category_name_ar     =   $productfilters;

             if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/subcategory/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $subcategory->image_en = $filename;
                }
            }

               if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/subcategory/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $subcategory->image_ar = $filename;
                }
            }

            $subcategory->save();

            return redirect()->back()->with('success', "Sub Category Created Successfully !");
        } else {
            
            $category = DB::table('category')->select('*')->get();
            $filter = Filter_values::select('*',DB::raw('(select name_en from filters where filters.id=filter_values.filter_id) as filtername'))->get();

            return view('admin.subcategory.add',compact('category','filter'));
        }
    }

    public function edit_sub_category(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [

            'category_id'               =>    'required',
            'sub_category_name_en'      =>    'required|unique:sub_category,sub_category_name_en,'.$id,
            'sub_category_name_ar'      =>    'required|unique:sub_category,sub_category_name_ar,'.$id,
            'image_en'                  =>    'mimes:jpeg,jpg,png,svg',
            'image_ar'                  =>    'mimes:jpeg,jpg,png,svg',
             
            ]);
   
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }


            if(!empty($request->productfilters))
             {
             $productfilters = implode(',', $request->productfilters); 
             }

            $subcategory                           =   Subcategory::find($id);
            $subcategory->category_id              =   $request->category_id;
            $subcategory->sub_category_name_en     =   $request->sub_category_name_en;
            $subcategory->sub_category_name_ar     =   $request->sub_category_name_ar;

            if(!empty($request->productfilters))
            {
            $subcategory->productfilters = $productfilters;
            }

             if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/subcategory/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $subcategory->image_en = $filename;
                }
            }

               if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/subcategory/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $subcategory->image_ar = $filename;
                }
            }

            $subcategory->save();

            return redirect()->back()->with('success', "Sub Category Updated Successfully !");
        } else {
            $category = DB::table('category')->select('*')->get();
            $subcategory=Subcategory::where('id', $id)->first();
            $filter_values = DB::table('filter_values')->select('*')->get();
            return view('admin.subcategory.edit', compact('category','subcategory','filter_values'));
        }
    }
}
