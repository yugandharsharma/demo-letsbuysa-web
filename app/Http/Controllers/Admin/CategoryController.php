<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\PasswordReset;
use App\Model\Customer;
use App\Model\Address;
use App\Model\Product;
use App\Model\Category;
use App\Model\Subcategory;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail;
use Auth;
use DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $category=DB::table('category')->select('*')->orderby('id','desc')->get();
     
        return view('admin.category.index', compact('category'));
    }
    
    public function delete_category($id)
    {   
        $products =  Product::where('category_id', $id)->get();

        $category = Subcategory::where('category_id', $id)->get();

        if (count($products)>0) {
            return redirect()->back()->with('infoerror', "Category/Subcategory Associated With Products !");
        }
        
        if (count($category)>0) {
            return redirect()->back()->with('infoerrorsubcategory', "Category/Subcategory Associated With Products !");
        }
        $affectedRows = Category::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }


    public function add_category(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [

            'category_name_en'       =>    'required|unique:category',
            'category_name_ar'       =>    'required|unique:category',
            'meta_tag_title_en'      =>    'required',
            'meta_tag_title_ar'      =>    'required',
            'description_en'         =>    'required|max:10000',
            'description_ar'         =>    'required|max:10000',
            'image_en'               =>    'mimes:jpeg,jpg,png',
            'image_ar'               =>    'mimes:jpeg,jpg,png',
            'header_image_en'        =>    'mimes:jpeg,jpg,png',
            'header_image_ar'        =>    'mimes:jpeg,jpg,png',
            'footer_image_en'        =>    'mimes:jpeg,jpg,png',
            'footer_image_en'        =>    'mimes:jpeg,jpg,png',
             
        ]);
   
            if ($validator->fails()) {
                return redirect('admin/category/add')->withInput()->withErrors($validator);
            }

            $category                                   =   new Category;
            $category->category_name_en                 =   $request->category_name_en;
            $category->category_name_ar                 =   $request->category_name_ar;
            $category->description_en                   =   $request->description_en;
            $category->description_ar                   =   $request->description_ar;
            $category->meta_tag_title_en                =   $request->meta_tag_title_en;
            $category->meta_tag_title_ar                =   $request->meta_tag_title_ar;
            $category->meta_tag_description_en          =   $request->meta_tag_description_en;
            $category->meta_tag_description_ar          =   $request->meta_tag_description_ar;
            $category->meta_tag_keyword_en              =   $request->meta_tag_keyword_en;
            $category->meta_tag_keyword_ar              =   $request->meta_tag_keyword_ar;

             if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/category/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $category->image_en = $filename;
                }
            }

               if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/category/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $category->image_ar = $filename;
                }
            }

            if ($request->file('header_image_en')) {
                $file = $request->file('header_image_en');
                if ($file) {
                    $destinationPath = 'public/images/category/';
                    $extension = $request->file('header_image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $category->header_image_en = $filename;
                }
            }

               if ($request->file('header_image_ar')) {
                $file = $request->file('header_image_ar');
                if ($file) {
                    $destinationPath = 'public/images/category/';
                    $extension = $request->file('header_image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $category->header_image_ar = $filename;
                }
            }

            if ($request->file('footer_image_en')) {
                $file = $request->file('footer_image_en');
                if ($file) {
                    $destinationPath = 'public/images/category/';
                    $extension = $request->file('footer_image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $category->footer_image_en = $filename;
                }
            }

               if ($request->file('footer_image_ar')) {
                $file = $request->file('footer_image_ar');
                if ($file) {
                    $destinationPath = 'public/images/category/';
                    $extension = $request->file('footer_image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $category->footer_image_ar = $filename;
                }
            }

            $category->save();

            return redirect()->back()->with('success', "Category Created Successfully !");
        } else {
            return view('admin.category.add');
        }
    }

    public function edit_category(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [

            'category_name_en'       =>    'required|unique:category,category_name_en,'.$id,
            'category_name_ar'       =>    'required|unique:category,category_name_ar,'.$id,
            'meta_tag_title_en'      =>    'required',
            'meta_tag_title_ar'      =>    'required',
            'description_en'         =>    'required|max:10000',
            'description_ar'         =>    'required|max:10000',
            'image_en'               =>    'mimes:jpeg,jpg,png',
            'image_ar'               =>    'mimes:jpeg,jpg,png',
            'header_image_en'        =>    'mimes:jpeg,jpg,png',
            'header_image_ar'        =>    'mimes:jpeg,jpg,png',
            'footer_image_en'        =>    'mimes:jpeg,jpg,png',
            'footer_image_en'        =>    'mimes:jpeg,jpg,png',
             
            ]);
   
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $category                                   =   Category::find($id);
            $category->category_name_en                 =   $request->category_name_en;
            $category->category_name_ar                 =   $request->category_name_ar;
            $category->description_en                   =   $request->description_en;
            $category->description_ar                   =   $request->description_ar;
            $category->meta_tag_title_en                =   $request->meta_tag_title_en;
            $category->meta_tag_title_ar                =   $request->meta_tag_title_ar;
            $category->meta_tag_description_en          =   $request->meta_tag_description_en;
            $category->meta_tag_description_ar          =   $request->meta_tag_description_ar;
            $category->meta_tag_keyword_en              =   $request->meta_tag_keyword_en;
            $category->meta_tag_keyword_ar              =   $request->meta_tag_keyword_ar;

             if ($request->file('image_en')) {
                    $file = $request->file('image_en');
                    if ($file) {
                        $destinationPath = 'public/images/category/';
                        $extension = $request->file('image_en')->getClientOriginalExtension();
                        $filename =  time(). '_1.' . $extension;
                        $file->move($destinationPath, $filename);
                        $category->image_en = $filename;
                    }
                }

               if ($request->file('image_ar')) {
                   $file = $request->file('image_ar');
                   if ($file) {
                       $destinationPath = 'public/images/category/';
                       $extension = $request->file('image_ar')->getClientOriginalExtension();
                       $filename =  time(). '_2.' . $extension;
                       $file->move($destinationPath, $filename);
                       $category->image_ar = $filename;
                   }
               }

            if ($request->file('header_image_en')) {
                $file = $request->file('header_image_en');
                if ($file) {
                    $destinationPath = 'public/images/category/';
                    $extension = $request->file('header_image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $category->header_image_en = $filename;
                }
            }

               if ($request->file('header_image_ar')) {
                   $file = $request->file('header_image_ar');
                   if ($file) {
                       $destinationPath = 'public/images/category/';
                       $extension = $request->file('header_image_ar')->getClientOriginalExtension();
                       $filename =  time(). '_2.' . $extension;
                       $file->move($destinationPath, $filename);
                       $category->header_image_ar = $filename;
                   }
               }

            if ($request->file('footer_image_en')) {
                $file = $request->file('footer_image_en');
                if ($file) {
                    $destinationPath = 'public/images/category/';
                    $extension = $request->file('footer_image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $category->footer_image_en = $filename;
                }
            }

               if ($request->file('footer_image_ar')) {
                   $file = $request->file('footer_image_ar');
                   if ($file) {
                       $destinationPath = 'public/images/category/';
                       $extension = $request->file('footer_image_ar')->getClientOriginalExtension();
                       $filename =  time(). '_2.' . $extension;
                       $file->move($destinationPath, $filename);
                       $category->footer_image_ar = $filename;
                   }
               }


            $category->save();

            return redirect()->back()->with('success', "Category Updated Successfully !");
        } else {
            $category=Category::where('id', $id)->first();
            return view('admin.category.edit', compact('category'));
        }
    }
}
