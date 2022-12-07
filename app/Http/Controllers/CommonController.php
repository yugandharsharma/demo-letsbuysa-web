<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use App\Models\EmailTemplate;

class CommonController extends Controller
{
    public static function mail_send($slug='default',$subject=array(),$content=array(),$options=array(),$attachment_path=null){
        $to_name    =   (isset($options['to_name']) && !empty($options['to_name']) )? $options['to_name'] : 'NA';
        $to_email   =   (isset($options['to_email']) && !empty($options['to_email']) )? $options['to_email'] : 'NA';

        $email_template     =   EmailTemplate::where("slug",$slug)->where("status",1)->where("is_delete",0)->first();
        
        if(!empty($email_template)){
            if(!empty($subject)){
                foreach($subject as $sb_key=>$sb_value){
                    $email_template->subject    =   str_replace( "{".$sb_key."}", $sb_value, $email_template->subject );
                }
            }
 
            if(!empty($content)){
                foreach($content as $ct_key=>$ct_value){
                
                    $email_template->content    =   str_replace( "{".$ct_key."}", $ct_value, $email_template->content );
                     // print_r($email_template->content ); 
                }
               //die;
            }
            $data   =   ['content' => $email_template->content];

            Mail::send('emails.emails', $data, function($message) use ($to_name, $to_email, $email_template,$attachment_path) {
                $message->to($to_email, $to_name)->subject
                    ($email_template->subject);
                $message->from((getSettings('admin-receive-email'))? getSettings('admin-receive-email') : env("MAIL_USERNAME", "testingbydev@gmail.com"), (getSettings('site-title'))? getSettings('site-title') : env("APP_NAME", "Laravel") );
                if($attachment_path){
                    $message->attach($attachment_path,$options = []);
                }
            });
        }
        return;
    }
    
    
    
    
    public function SendSMS($mobile=null,$message=null)
    {
        $user="yallacash"; //your username
            $password="49050758"; //your password
            $mobilenumbers=$mobile;//"+971521707034"; //enter Mobile numbers comma seperated
            $message = $message;//"test messgae"; //enter Your Message
            $senderid="YALLA CASH"; //Your senderid
            $messagetype="N"; //Type Of Your Message
            $DReports="Y"; //Delivery Reports
            $url="http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
            $message = urlencode($message);
            $ch = curl_init();
            if (!$ch){die("Couldn't initialize a cURL handle");}
            $ret = curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt ($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt ($ch, CURLOPT_POSTFIELDS,
            "User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
            $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //If you are behind proxy then please uncomment below line and provide your proxy ip with port.
            // $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
            $curlresponse = curl_exec($ch); // execute
            if(curl_errno($ch))
            echo 'curl error : '. curl_error($ch);
            if (empty($ret)) {
            // some kind of an error happened
            die(curl_error($ch));
            curl_close($ch); // close cURL handler
            } else {
            $info = curl_getinfo($ch);
            curl_close($ch); // close cURL handler
            //echo $curlresponse; //echo "Message Sent Succesfully" ;
}
    }
}
