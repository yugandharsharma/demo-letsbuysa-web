<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Global_settings;
use App\Model\Content_management;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail;
use Auth;
use DB;

class MarketingController extends Controller
{
    public function marketing_mail(Request $request)
    {  
     if ($request->isMethod('post')) 
     {

        $validator = Validator::make($request->all(), [
            'to'                     =>    'required',
            'subject'                =>    'required',
            'description'            =>    'required|max:5000',
             
        ]);
   
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $customer       =   $request->customer;
        $description    =   $request->description;
        $subject        =   $request->subject;
        
        if(!empty($customer))
        {
        foreach($customer as $record)
        {
           $data = [
                'no-reply'   => 'no-reply@letsbuy.com',
                'Email'      => $record,
                'subject'    => $subject,
                'description'=> $description,
                ];
            Mail::send(
            'emails.email',
            ['description'=>$data['description'],'sender'=>$data['no-reply'],'email'=>$data['Email']],
            function ($message) use ($data) {
                            $message
                            ->from($data['no-reply'])
                            ->to($data['Email'], 'letsbuy')->subject($data['subject']);
                        }
                 );
        }
        }

        
      return redirect()->back()->with('success', "Mail Sent Successfully !");
     } 
     else 
     {
        $emails=DB::table('users')->select('email','id')->where('role',2)->get();
        return view('admin.marketing.mail', compact('emails'));
     }

    }
}
