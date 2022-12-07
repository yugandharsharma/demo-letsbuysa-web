<?php


namespace App\Http\Controllers\Admin;


use App\Model\Emailtemplate;
use App\Http\Controllers\Controller;
use App\Model\Permission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SubAdminManagementController extends Controller
{
    public function list(){

        $list = User::where('role', 3)->orderBy('created_at', 'desc')->get();
        
        return view('admin.sub_admin_management.list', compact('list'));
    }

    public function add(Request $request){

        if ($request->isMethod('post')){
            $request->validate([
                'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:50',
                'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{3,4})$/|unique:users|max:100',
                'mobile' => 'required|unique:users|digits_between:7,15',
                'module_name' => 'required|array|min:1',
                'module_name.*' => 'required|min:1',


            ]);
            DB::beginTransaction();
            $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
            $password = substr($random, 0, 10);
            $user = new User();
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->mobile = $request['mobile'];
            $user->role = 3;
            $user->password = Hash::make($password);
            $user->save();
            $module_name = $request['module_name'];
            $module_add = $request['add'] ?? [];
            $module_edit = $request['edit'] ?? [];
            $module_delete = $request['delete'] ?? [];
            if (!empty($module_name)){
                foreach ($module_name as $name){
                    $permission = new Permission();
                    $permission->user_id = $user->id;
                    $permission->module_name = $name;
                    if (in_array($name, $module_add)){
                        $permission->add = 1;
                    }
                    if (in_array($name, $module_edit)){
                        $permission->edit = 1;
                    }
                    if (in_array($name, $module_delete)){
                        $permission->delete = 1;
                    }
                    $permission->save();
                }
            }
            $mail_array = [
                'name' => ucfirst($request['name']),
                'email' => $request['email'],
                'password' => $password,
            ];
            $email_template = Emailtemplate::where('slug', 'create_new_sub_admin')->first();
            $message = str_replace(['{name}', '{email}', '{password}'], $mail_array, $email_template->description_en);
            $subject = $email_template->subject_en;
            $to_name = $mail_array['name'];
            $to_email = $mail_array['email'];
            $data['msg'] = $message;
            Mail::send('emails.create_sub_admin_management', $data, function ($message) use ($to_name, $subject, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject($subject);
                $message->from('testbydev.nr@gmail.com');
            });
            DB::commit();
            toastr()->success('Sub Admin successfully added!');
            return redirect()->route('sub_admin_management_list');
        }
        return view('admin.sub_admin_management.add');
    }

    public function edit(Request $request, $id){

        DB::beginTransaction();
        $permissions = [];
        $edit = User::with('permission')->findOrFail(base64_decode($id));
        
        foreach ($edit->permission as $record)
        {
            $permissions['module_name'][]=$record->module_name;
            $permissions[$record->module_name]['add']=$record->add;
            $permissions[$record->module_name]['edit']=$record->edit;
            $permissions[$record->module_name]['delete']=$record->delete;
        }
        //dd($permissions);
        if ($request->isMethod('post')){
            $request->validate([
                'name' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
                'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})$/|max:100|unique:users,email,'.base64_decode($id),
                'mobile' => 'required|digits_between:7,15|unique:users,mobile,'.base64_decode($id),
                'module_name' => 'required|array|min:1',
                'module_name.*' => 'required|min:1',
            ]);
           
            $edit->name = $request['name'];
            $edit->email = $request['email'];
            $edit->mobile = $request['mobile'];
            $module_name = $request['module_name'];
            $module_add = $request['add'] ?? [];
            $module_edit = $request['edit'] ?? [];
            $module_delete = $request['delete'] ?? [];
            $permission_data = Permission::where('user_id', base64_decode($id))->get();
            foreach ($permission_data as $permission_records){
                $permission_records->delete();
            }
            if (!empty($module_name)){
                foreach ($module_name as $name){
                    $permission = new Permission();
                    $permission->user_id = $edit->id;
                    $permission->module_name = $name;
                    if (in_array($name, $module_add)){
                        $permission->add = 1;
                    }
                    else{
                        $permission->add = 0;
                    }

                    if (in_array($name, $module_edit)){
                        $permission->edit = 1;
                    }
                    else{
                        $permission->edit = 0;
                    }
                    if (in_array($name, $module_delete)){
                        $permission->delete = 1;
                    }
                    else{
                        $permission->delete = 0;
                    }
                    $permission->save();
                }
            }
            $edit->save();
            DB::commit();
            toastr()->success('Sub Admin successfully updated!');
            return redirect()->route('sub_admin_management_list');
        }
        return view('admin.sub_admin_management.edit', compact('edit', 'permissions'));
    }

    public function delete(Request $request, $id){

        $delete = User::findOrFail(base64_decode($id));

        if ($delete->is_delete === 1)
        {
            $delete->is_delete = 0;
            toastr()->success('Sub Admin successfully deleted!');
        }
        else
        {
            $delete->is_delete = 1;
            toastr()->success('Sub Admin successfully active!');
        }
        $delete->save();
        return redirect()->back();
    }

    public function view(Request $request, $id)
    {
        $view = User::with('permission')->findOrFail(base64_decode($id));
        return view('admin.sub_admin_management.view', compact( 'view'));
    }
}
