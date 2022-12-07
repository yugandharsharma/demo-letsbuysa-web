<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\HotToday;
use Illuminate\Http\Request;


class HotTodayController extends Controller
{
    public function list(){
        $list = HotToday::get();
        return view('admin.hot_today.list', compact('list'));
    }

    public function add(Request $request){
        if ($request->isMethod('post')){
            $request->validate([
                'image_en' => 'required|mimes:jpeg,jpg,png,gif',
                'image_ar' => 'required|mimes:jpeg,jpg,png,gif',
                'url' => 'max:250',
            ]);
            $add = new HotToday();
            $add->url = $request['url'];
            $add->category_id = $request['category_id'];
            $add->subcategory_id = $request['subcategory_id'];
            $add->status = $request['status'];
            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/hot_today/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $add->image_en = $filename;
                }
            }

            if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/hot_today/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $add->image_ar = $filename;
                }
            }
            $add->save();
            toastr()->success('Hot today successfully added!');
            return redirect()->route('hot_today_list');
        }
        return view('admin.hot_today.add');
    }

    public function edit(Request $request, $id){
        $edit = HotToday::findOrFail(base64_decode($id));
        if ($request->isMethod('post')){
            $request->validate([
                'image_en' => 'mimes:jpeg,jpg,png,gif',
                'image_ar' => 'mimes:jpeg,jpg,png,gif',
                'url' => 'max:250',
            ]);
            $edit->url = $request['url'];
            $edit->status = $request['status'];
            $edit->category_id = $request['category_id'];
            $edit->subcategory_id = $request['subcategory_id'];
            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = 'public/images/hot_today/';
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename =  time(). '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->image_en = $filename;
                }
            }
            if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = 'public/images/hot_today/';
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename =  time(). '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->image_ar = $filename;
                }
            }
            $edit->save();
            toastr()->success('Hot today successfully updated!');
            return redirect()->route('hot_today_list');
         }
        return view('admin.hot_today.edit', compact('edit'));
    }
}
