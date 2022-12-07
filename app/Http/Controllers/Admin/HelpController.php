<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Help_categories;
use App\Model\Help_articles;
use App\Model\Help_subcategories;
use App\Model\article_reviews;
use App\Model\Option_value;
use App\Model\Filter_values;
use App\Model\Filter;
use App\Model\Option;
use App\Model\Colors;
use App\Model\Emailtemplate;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail;
use Auth;
use DB;

class HelpController extends Controller
{
  public function help_category(Request $request)
  {
    $category=DB::table('help_categories')->select('*')->orderby('id','desc')->get();
     
    return view('admin.helpcategory.index', compact('category'));
  }

  public function help_category_delete($id)
  {   
      $article =  Help_articles::where('category_id', $id)->get();

      $category = Help_subcategories::where('category_id', $id)->get();

      if (count($article)>0) {
          return redirect()->back()->with('infoerror', "Category/Subcategory Associated With Articles !");
      }
      
      if (count($category)>0) {
          return redirect()->back()->with('infoerrorsubcategory', "Category/Subcategory Associated With Articles !");
      }

      $affectedRows = Help_categories::where('id', $id)->delete();
      return redirect()->back()->with('info', "Record delete successfully !");
  }


  public function help_category_add(Request $request)
  {
      if ($request->isMethod('post')) {
          $validator = Validator::make($request->all(), [

          'name_en'                =>    'required|unique:help_categories',
          'name_ar'                =>    'required|unique:help_categories',
          'description_en'         =>    'required|max:10000',
          'description_ar'         =>    'required|max:10000',
          'image_en'               =>    'mimes:jpeg,jpg,png,svg',
          'image_ar'               =>    'mimes:jpeg,jpg,png,svg',
           
      ]);
 
          if ($validator->fails()) {
              return redirect('admin/help_category/add')->withInput()->withErrors($validator);
          }

          $category                                   =   new Help_categories;
          $category->name_en                          =   $request->name_en;
          $category->name_ar                          =   $request->name_ar;
          $category->description_en                   =   $request->description_en;
          $category->description_ar                   =   $request->description_ar;

           if ($request->file('image_en')) {
              $file = $request->file('image_en');
              if ($file) {
                  $destinationPath = 'public/images/help/';
                  $extension = $request->file('image_en')->getClientOriginalExtension();
                  $filename =  time(). '_1.' . $extension;
                  $file->move($destinationPath, $filename);
                  $category->image_en = $filename;
              }
          }

             if ($request->file('image_ar')) {
              $file = $request->file('image_ar');
              if ($file) {
                  $destinationPath = 'public/images/help/';
                  $extension = $request->file('image_ar')->getClientOriginalExtension();
                  $filename =  time(). '_2.' . $extension;
                  $file->move($destinationPath, $filename);
                  $category->image_ar = $filename;
              }
          }

          $category->save();

          return redirect()->back()->with('success', "Category Created Successfully !");
      } else {
          return view('admin.helpcategory.add');
      }
  }

  public function help_category_edit(Request $request, $id)
  {
      if ($request->isMethod('post')) {
          $validator = Validator::make($request->all(), [

          'name_en'       =>    'required|unique:help_categories,name_en,'.$id,
          'name_ar'       =>    'required|unique:help_categories,name_ar,'.$id,
          'description_en'         =>    'required|max:10000',
          'description_ar'         =>    'required|max:10000',
          'image_en'               =>    'mimes:jpeg,jpg,png,svg',
          'image_ar'               =>    'mimes:jpeg,jpg,png,svg',
           
          ]);
 
          if ($validator->fails()) {
              return redirect()->back()->withInput()->withErrors($validator);
          }

          $category                                   =   Help_categories::find($id);
          $category->name_en                          =   $request->name_en;
          $category->name_ar                          =   $request->name_ar;
          $category->description_en                   =   $request->description_en;
          $category->description_ar                   =   $request->description_ar;

           if ($request->file('image_en')) {
                  $file = $request->file('image_en');
                  if ($file) {
                      $destinationPath = 'public/images/help/';
                      $extension = $request->file('image_en')->getClientOriginalExtension();
                      $filename =  time(). '_1.' . $extension;
                      $file->move($destinationPath, $filename);
                      $category->image_en = $filename;
                  }
              }

             if ($request->file('image_ar')) {
                 $file = $request->file('image_ar');
                 if ($file) {
                     $destinationPath = 'public/images/help/';
                     $extension = $request->file('image_ar')->getClientOriginalExtension();
                     $filename =  time(). '_2.' . $extension;
                     $file->move($destinationPath, $filename);
                     $category->image_ar = $filename;
                 }
             }
          $category->save();

          return redirect()->back()->with('success', "Category Updated Successfully !");
      } else {
          $category=Help_categories::where('id', $id)->first();
          return view('admin.helpcategory.edit', compact('category'));
      }
  }

  public function help_subcategory(Request $request)
  {
      $subcategory = DB::table('help_subcategories')
        ->select('*')
        ->orderBy('id', 'desc')
        ->get();
      return view('admin.helpsubcategory.index', compact('subcategory'));
  }
  
  public function help_subcategory_delete($id)
  {  
      $articles =  Help_articles::where('subcategory_id',$id)->get();

      if(count($articles)>0)
      {
          return redirect()->back()->with('infoerror', "Category/Subcategory Associated With articles!");
      }
      $affectedRows = Help_subcategories::where('id', $id)->delete();
      return redirect()->back()->with('info', "Record delete successfully !");


  }


  public function help_subcategory_add(Request $request)
  {
      if ($request->isMethod('post')) {
          $validator = Validator::make($request->all(), [
          'category_id'                =>    'required',
          'name_en'                    =>    'required|unique:help_subcategories',
          'name_ar'                    =>    'required|unique:help_subcategories',
          'image_en'                   =>    'mimes:jpeg,jpg,png,svg',
          'image_ar'                   =>    'mimes:jpeg,jpg,png,svg',
          'description_en'             =>    'required|max:10000',
          'description_ar'             =>    'required|max:10000',

           
          ]);
 
          if ($validator->fails()) {
              return redirect('admin/help_subcategory/add')->withInput()->withErrors($validator);
          }

          $productfilters='';

          if(!empty($request->productfilters))
          {
          $productfilters = implode(',', $request->productfilters); 
          }
 

          $subcategory                           =   new Help_subcategories;
          $subcategory->category_id              =   $request->category_id;
          $subcategory->name_en                  =   $request->name_en;
          $subcategory->name_ar                  =   $request->name_ar;
          $subcategory->description_en           =   $request->description_en;
          $subcategory->description_ar           =   $request->description_ar;

          $subcategory->save();

          return redirect()->back()->with('success', "Sub Category Created Successfully !");
      } else {
          
          $category = DB::table('help_categories')->select('*')->get();

          return view('admin.helpsubcategory.add',compact('category'));
      }
  }

  public function help_subcategory_edit(Request $request, $id)
  {
      if ($request->isMethod('post')) {
          $validator = Validator::make($request->all(), [

          'category_id'               =>    'required',
          'name_en'                   =>    'required|unique:help_subcategories,name_en,'.$id,
          'name_ar'                   =>    'required|unique:help_subcategories,name_ar,'.$id,
          'image_en'                  =>    'mimes:jpeg,jpg,png,svg',
          'image_ar'                  =>    'mimes:jpeg,jpg,png,svg',
          'description_en'            =>    'required|max:10000',
          'description_ar'            =>    'required|max:10000',     
           
          ]);
 
          if ($validator->fails()) {
              return redirect()->back()->withInput()->withErrors($validator);
          }


          $subcategory                           =   Help_subcategories::find($id);
          $subcategory->category_id              =   $request->category_id;
          $subcategory->name_en                  =   $request->name_en;
          $subcategory->name_ar                  =   $request->name_ar;
          $subcategory->description_en           =   $request->description_en;
          $subcategory->description_ar           =   $request->description_ar;

          $subcategory->save();

          return redirect()->back()->with('success', "Sub Category Updated Successfully !");
      } else {
          $category = DB::table('help_categories')->select('*')->get();
          $subcategory=Help_subcategories::where('id', $id)->first();
          return view('admin.helpsubcategory.edit', compact('category','subcategory'));
      }
  }

  public function help_article(Request $request)
  {
  $articles =  Help_articles::select('*',DB::raw('(select name_en from help_categories where help_categories.id=help_articles.category_id)as categoryname'),DB::raw('(select name_en from help_subcategories where help_subcategories.id=help_articles.subcategory_id)as subcategoryname'),DB::raw('(select count(id) from article_reviews where article_id=help_articles.id AND review_status=0)as negative'),DB::raw('(select count(id) from article_reviews where article_id=help_articles.id AND review_status=1)as positive'))->get();
  return view('admin.helparticle.index', compact('articles'));
  }

  public function help_article_add(Request $request)
  {
      if ($request->isMethod('post')) {
          $validator = Validator::make($request->all(), [
          'category_id'                =>    'required',
          'subcategory_id'             =>    'required',
          'title_en'                   =>    'required',
          'title_ar'                   =>    'required',
          'description_en'             =>    'required|max:10000',
          'description_ar'             =>    'required|max:10000',

           
          ]);
 
          if ($validator->fails()) {
              return redirect('admin/help_article/add')->withInput()->withErrors($validator);
          }

          $article                           =   new Help_articles;
          $article->category_id              =   $request->category_id;
          $article->subcategory_id           =   $request->subcategory_id;
          $article->title_en                 =   $request->title_en;
          $article->title_ar                 =   $request->title_ar;
          $article->description_en           =   $request->description_en;
          $article->description_ar           =   $request->description_ar;

          $article->save();

          return redirect()->back()->with('success', "Sub Category Created Successfully !");
      } else {
          
          $category = DB::table('help_categories')->select('*')->get();

          return view('admin.helparticle.add',compact('category'));
      }
  }

  public function gethelpsubcategory($id)
  {
  $subcategory = Help_subcategories::where(['category_id'=>$id])->get();
  return $subcategory;
  }

  public function help_article_delete($id)
  {   
      $affectedRows = Help_articles::where('id', $id)->delete();
      return redirect()->back()->with('info', "Record delete successfully !");
  }

  public function help_article_edit(Request $request, $id)
  {
      if ($request->isMethod('post')) {
          $validator = Validator::make($request->all(), [

            'category_id'                =>    'required',
            'subcategory_id'             =>    'required',
            'title_en'                   =>    'required',
            'title_ar'                   =>    'required',
            'description_en'             =>    'required|max:10000',
            'description_ar'             =>    'required|max:10000', 
           
          ]);
 
          if ($validator->fails()) {
              return redirect()->back()->withInput()->withErrors($validator);
          }

          $article                           =   Help_articles::find($id);
          $article->category_id              =   $request->category_id;
          $article->subcategory_id           =   $request->subcategory_id;
          $article->title_en                 =   $request->title_en;
          $article->title_ar                 =   $request->title_ar;
          $article->description_en           =   $request->description_en;
          $article->description_ar           =   $request->description_ar;

          $article->save();

          return redirect()->back()->with('success', "Sub Category Updated Successfully !");
      } else {
          $category = DB::table('help_categories')->select('*')->get();
          $subcategory=Help_subcategories::all();
          $article=Help_articles::where('id', $id)->first();

          return view('admin.helparticle.edit', compact('category','subcategory','article'));
      }
  }



}