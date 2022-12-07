<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Model\Brand;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    public function list(){
        $list = Brand::get();
        return view('admin.brands.list', compact('list'));
    }

    public function add(Request $request){
        if ($request->isMethod('post')){
            $request->validate([
                'name_en' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
                'name_ar' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
                'image_en' => 'required|mimes:jpeg,jpg,png',
                'image_ar' => 'required|mimes:jpeg,jpg,png',
            ]);
            $add = new Brand();
            $add->name_en = $request['name_en'];
            $add->name_ar = $request['name_ar'];
            $add->status = $request['status'];
            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/brands/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $add->image_en = $filename;
                }
            }
            if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/brands/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $add->image_ar = $filename;
                }
            }
            $add->save();
            toastr()->success('Brands successfully added!');
            return redirect()->route('brands_list');
        }
        return view('admin.brands.add');
    }

    public function edit(Request $request, $id){
        $edit = Brand::findOrFail(base64_decode($id));
        if ($request->isMethod('post')){
            $request->validate([
                'name_en' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
                'name_ar' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
                'image_en' => 'mimes:jpeg,jpg,png',
                'image_ar' => 'mimes:jpeg,jpg,png',
            ]);
            $edit->name_en = $request['name_en'];
            $edit->name_ar = $request['name_ar'];
            $edit->status = $request['status'];
            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/brands/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->image_en = $filename;
                }
            }
            if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/brands/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->image_ar = $filename;
                }
            }
            $edit->save();
            return redirect()->route('brands_list');
        }
        return view('admin.brands.edit', compact('edit'));
    }

    public function delete(Request $request, $id){
        $delete = Brand::findOrFail(base64_decode($id));
        if ($delete){
            $delete->delete();
            return redirect()->route('brands_list');
        }
        else{
            return redirect()->route('brands_list');
        }

    }
}
