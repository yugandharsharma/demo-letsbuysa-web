<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Orderstatus;
use App\Model\Emailtemplate;
use App\Model\Transaction_details;
use App\Model\Order;
use App\Model\Wallet;
use App\Model\Product_details;
use App\Model\Order_details;
use App\Model\Order_track;
use App\Model\Product;
use App\Model\Address;
use App\Model\Option;
use App\Model\Redeem_rewards;
use App\Model\Reward;
use App\User;
use Redirect;
use Hash;
use Validator;
use Mail,Auth,DB;

class OrderController extends Controller
{
    public function order_status(Request $request)
    {
        $orderstatus=DB::table('order_status')->select('*')->get();
        return view('admin.orders.order_status_index', compact('orderstatus'));
    }
    
    public function order_status_delete($id)
    {
        $affectedRows = Orderstatus::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }

    public function order_status_add(Request $request)
    {
     if ($request->isMethod('post')) 
     {  
         $validator = Validator::make($request->all(), [
            'status_name_en'      =>    'required',
            'status_name_ar'      =>    'required',
        ]);
   
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

          $orderstatus                                  =   new Orderstatus;
          $orderstatus->status_name_en                  =   $request->status_name_en;
          $orderstatus->status_name_ar                  =   $request->status_name_ar;

          $orderstatus->save();

          return redirect()->back()->with('success', "Order Status Created Successfully !");

     }
     else 
     {
     return view('admin.orders.order_status_add');
     }
    }

    public function order_status_edit(Request $request,$id)
    {
     if ($request->isMethod('post')) 
     {  
        $rules = [
            'status_name_en'    => 'required',
            'status_name_ar'    => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
   
       if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
          $orderstatus                                 =   Orderstatus::find($id);
          $orderstatus->status_name_en                 =   $request->status_name_en;
          $orderstatus->status_name_ar                 =   $request->status_name_ar;
 
          $orderstatus->save();

          return redirect()->back()->with('success', "Order Status Updated Successfully !");

     }
     else 
     {
     $orderstatus=Orderstatus::where('id', $id)->first();
     return view('admin.orders.order_status_edit',compact('orderstatus'));
     }
    }
    
    public function index(Request $request)
    {
      $orders = Order::select('*',DB::raw('(select name from users where users.id=order.user_id)as username'))->orderby('id','desc')->paginate(10);
      $order_status = Orderstatus::all();
      return view('admin.orders.index',compact('orders','order_status'));
    }

     public function order_view(Request $request,$id)
    {
      $data = Order::select('*',DB::raw('(select name from users where users.id=order.user_id)as username'),DB::raw('(select status_name_en from order_status where order_status.id=order.status)as order_status'),DB::raw('(select coupon_name from coupon where coupon.id=order.coupan_id)as coupon_name'))->where('id',base64_decode($id))->first();
      
      $order_details = Order_details::select('*',DB::raw('(select color from product_details where product_details.id=order_details.color)as color1'))->where('order_id',base64_decode($id))->get();
      return view('admin.orders.view',compact('data','order_details'));
    }

      public function change_order_status(Request $request,$id)
    {
            $order          =    Order::find(base64_decode($id));
            $order->status  =    $request->status;
            $order_id       =    base64_decode($id);
            $user_data = User::where('id',$order->user_id)->first();  

            $totalreward=0;
             if($request->status==8)
             {
              $orderdetail =  Order_details::select('*',DB::raw('(select rewardpoints from products where products.id=order_details.product_id)as rewardpoints'))->where('order_id',base64_decode($id))->get();
              foreach($orderdetail as $reward)
              {
                $totalreward = $totalreward+$reward->rewardpoints;
              }

              if($totalreward>0)
              {
                $rewardhistory = new Redeem_rewards;
                $rewardhistory->reward = $totalreward;
                $rewardhistory->type   = 1;
                $rewardhistory->user_id= $order->user_id;
                $rewardhistory->save();

                $reward =Reward::where('user_id',$order->user_id)->first();
                if(!empty($reward))
                {
                  Reward::where('user_id',$order->user_id)->update(['reward'=>$reward->reward+$totalreward]);
                }
                else
                {
                   $reward =new Reward;
                   $reward->user_id = $order->user_id;
                   $reward->reward = $totalreward;
                   $reward->save();

                }
              }

            }

             $data = Order::select(DB::raw('(select name from users where users.id=order.user_id)as username'), DB::raw('(select status_name_en from order_status where order_status.id=order.status)as order_status'))->where('id', base64_decode($id))->first();

             $old_status = $data->order_status;
             $name = $data->username;
             $order->save();

             $order_track_data = Order_track::where('order_id',$order_id)->where('order_status',$request->status)->first();

           

             if(empty($order_track_data))
             {

            if ($request->status == 13) 
            {
              $ordertrack               =  new Order_track;
              $ordertrack->order_id     =  $order_id;
              $ordertrack->order_status =  11;
              $ordertrack->save();
            }

              
              $ordertrack               =  new Order_track;
              $ordertrack->order_id     =  $order_id;
              $ordertrack->order_status =  $request->status;
              $ordertrack->save();

             }

             $newdata = Order::select(DB::raw('(select name from users where users.id=order.user_id)as username'), DB::raw('(select status_name_en from order_status where order_status.id=order.status)as order_status'))->where('id', base64_decode($id))->first();

             $new_status = $newdata->order_status;

            $EmailTemplates = Emailtemplate::where('slug', 'order_status_change')->first();
            $message        = str_replace(array('{name}','{old_status}','{new_status}','{order_id}'), array($name,$old_status,$new_status,$order_id), $EmailTemplates->description_en);
            $subject        = $EmailTemplates->subject_en;
            $to_email       = $user_data->email;
            $data           = array();
            $data['msg']    = $message;
            Mail::send('emails.emailtemplate', $data, function ($message) use ($to_email, $subject) {
                $message->to($to_email)
               ->subject($subject);
                $message->from(env('MAIL_USERNAME', 'testingbydev@gmail.com'));
            });

            toastr()->success('Order Status Changed Successfully');
            return redirect()->back();
    }

    public function transaction(Request $request)
    {
      
    $transaction  = Transaction_details::select('*',DB::raw('(select name from users where users.id=transaction_details.user_id) as username'))->orderby('id','desc')->get();
    return view('admin.Transaction.index',compact('transaction'));

    }

    public function invoiceview(Request $request,$id)
    {
        $id  = base64_decode($id);
        $order         =  Order::where('id', $id)->first();
        $order_details =  Order_details::where('order_id', $id)->get();
        $address_detail=  Address::where('id',$order->address_id)->first();
        $user_data=  User::where('id', $order->user_id)->first();
        $countryname =  DB::table('country')->select('name')->where('country_id',$address_detail->country)->first();
 
        return view('frontend.invoice',compact('order','order_details','address_detail','user_data','countryname'));
    }

    public function order_edit(Request $request,$id)
    {

       if ($request->isMethod('post')) 
     {  
          return redirect()->back()->with('success', "Order Status Edited Successfully !");
     }
     else 
     {  
         $data = Order::select('*', DB::raw('(select name from users where users.id=order.user_id)as username'), DB::raw('(select status_name_en from order_status where order_status.id=order.status)as order_status'), DB::raw('(select coupon_name from coupon where coupon.id=order.coupan_id)as coupon_name'))->where('id', base64_decode($id))->first();

         $address = Address::where('id',$data->address_id)->first();
      
        $order_details = Order_details::select('*',DB::raw('(select color from product_details where product_details.id=order_details.color)as colorid'),DB::raw('(select name from colors where colors.colorcode=colorid)as colorname'))->where('order_id', base64_decode($id))->get();

        $country =  DB::table('country')->select('*')->where('status', 1)->get();

        return view('admin.orders.edit',compact('order_details','data','address','country'));
     }
    }

    public function payment_edit(Request $request)
    {
      if(!empty($request->order_id) && !empty($request->payment_mode))
      {
        Order::where('id', $request->order_id)->update(['payment_type'=>$request->payment_mode]);
        return response()->json(['success'=>'Payment Status Changed Successfully','status'=>'1']);
      }
      else 
      {
        return response()->json(['success'=>'Payment Status Not Changed ','status'=>'2']);
      }


    }

    public function quantitydescrease(Request $request)
    {
     
      if(!empty($request->order_id) && !empty($request->productid) && !empty($request->color))
      {
        $data = Order_details::where('order_id', $request->order_id)->where('color',$request->color)->where('product_id',$request->productid)->first();
        $orderdata = Order::where('id', $request->order_id)->first();
        $productdata = Product::where('id', $request->productid)->first();

           if($data->quantity==1)
           {
            return response()->json(['success'=>'Product Quantity Cannot be zero','status'=>'2']);
           }
        $quantity = $data->quantity-1;

         $productquantity =$productdata->quantity+1;
        $orderprice = $orderdata->price - $data->price;
        $producttotalprice = $orderdata->product_total_amount - $data->price;

      $user_wallet =  Wallet::where('user_id',$orderdata->user_id)->first();
      if($orderdata->payment_type ==2 || $orderdata->payment_type ==4)
      {
      if(!empty($user_wallet))
      {
        Wallet::where('user_id',$orderdata->user_id)->update(['amount'=>$data->price +$user_wallet->amount ]);
      }
      else
      {
        $wallet = new Wallet;
        $wallet->user_id = $orderdata->user_id;
        $wallet->amount  = $data->price;
        $wallet->save();
      }}

         if($data->color == 'nocolor')
        {
        Product::where('id', $request->productid)->update(['quantity'=>$productquantity]);
        }
        else 
        {
        $productdata = Product_details::where('id', $request->color)->first();
        $productquantity =$productdata->quantity+1;
        Product_details::where('id', $request->color)->update(['quantity'=>$productquantity]);
        }
        Order::where('id', $request->order_id)->update(['product_total_amount'=>$producttotalprice,'price'=>$orderprice]);
        Order_details::where('order_id', $request->order_id)->where('color',$request->color)->where('product_id',$request->productid)->update(['quantity'=>$quantity]);

        return response()->json(['success'=>'Quantity Updated Successfully','status'=>'1']);
      }
      else 
      {
        return response()->json(['success'=>'Quantity Not Changed','status'=>'2']);
      }

    }

     public function quantityincrease(Request $request)
    {
     
      if(!empty($request->order_id) && !empty($request->productid) && !empty($request->color))
      {
        $data = Order_details::where('order_id', $request->order_id)->where('color',$request->color)->where('product_id',$request->productid)->first();
        $orderdata = Order::where('id', $request->order_id)->first();
        if($data->color =='nocolor')
        {
        $productdata = Product::where('id', $request->productid)->first();
        }
        else 
        {
        $productdata = Product_details::where('product_id', $request->productid)->first();
        }

        if($productdata->quantity ==0)
        {
        return response()->json(['success'=>'Product is Not Available','status'=>'2']);
        }
        $productquantity =$productdata->quantity-1;
        $orderprice = $orderdata->price + $data->price;
        $producttotalprice = $orderdata->product_total_amount + $data->price;

        $quantity = $data->quantity+1;
        

        Order::where('id', $request->order_id)->update(['product_total_amount'=>$producttotalprice,'price'=>$orderprice]);
        if($data->color == 'nocolor')
        {
        Product::where('id', $request->productid)->update(['quantity'=>$productquantity]);
        }
        else 
        {
        $productdata = Product_details::where('id', $request->color)->first();
        $productquantity =$productdata->quantity-1;
        Product_details::where('id', $request->color)->update(['quantity'=>$productquantity]);
        }

        Order_details::where('order_id', $request->order_id)->where('color',$request->color)->where('product_id',$request->productid)->update(['quantity'=>$quantity]);

        return response()->json(['success'=>'Quantity Updated Successfully','status'=>'1']);
      }
      else 
      {
        return response()->json(['success'=>'Quantity Not Changed','status'=>'2']);
      }

    }

     public function removeproduct(Request $request,$id)
     { 
        $orderproductdata = Order_details::where('id', $id)->first();
        $orderproductquantity = $orderproductdata->quantity;
        $productremoveprice = $orderproductdata->quantity*$orderproductdata->price;
        $orderdata = Order::where('id', $orderproductdata->order_id)->first();
        $orderprice =$orderdata->price-$productremoveprice;
        $orderitemtotal =$orderdata->product_total_amount-$productremoveprice;

        $user_wallet =  Wallet::where('user_id',$orderdata->user_id)->first();
        if($orderdata->payment_type ==2 || $orderdata->payment_type ==4)
        {
          if(!empty($user_wallet))
          {
            Wallet::where('user_id',$orderdata->user_id)->update(['amount'=>$productremoveprice +$user_wallet->amount ]);
          }
          else
          {
            $wallet = new Wallet;
            $wallet->user_id = $orderdata->user_id;
            $wallet->amount  = $productremoveprice;
            $wallet->save();
          }
        }
        

        if($orderproductdata->color =='nocolor')
        {
        $productdata = Product::where('id', $orderproductdata->product_id)->first();
        $productquantity =$productdata->quantity +$orderproductquantity;
        Product::where('id', $orderproductdata->product_id)->update(['quantity'=>$productquantity]);
        }
        else 
        {
        $productdata = Product_details::where('product_id', $orderproductdata->product_id)->where('color', $orderproductdata->color)->first();
        $productquantity =$productdata->quantity +$orderproductquantity;
        Product_details::where('product_id', $orderproductdata->product_id)->where('color', $orderproductdata->color)->update(['quantity'=>$productquantity]);
        }
        Order::where('id', $orderproductdata->order_id)->update(['product_total_amount'=>$orderitemtotal,'price'=>$orderprice]);
        $data = Order_details::where('id', $id)->delete();

        return redirect()->back()->with('info', "Record delete successfully !");
     }

       public function productcolorchange(Request $request)
      {
        
      if(!empty($request->order_id) && !empty($request->color)&& !empty($request->id))
      {
       $ordercolor =  Order_details::where('order_id', $request->order_id)->where('product_id', $request->product_id)->where('color',$request->color)->first();
         if(empty($ordercolor))
         {
           $productdata = Product_details::where('id', $request->color)->first();
           Order_details::where('order_id', $request->order_id)->where('id', $request->id)->update(['color'=>$productdata->id]);
           return response()->json(['success'=>'Option Changed Successfully','status'=>'1']);
         }
         else 
         {
           return response()->json(['success'=>'Cannot Change Option Same Option Product Already Available','status'=>'2']);
         }
        
      }
      else 
      {
        return response()->json(['success'=>'Cannot Change Option Same Option Product Already Available','status'=>'2']);
      }


     }

     public function address_update(Request $request,$id)
     {
        $rules = [
            'fullname'    => 'required',
            'fulladdress' => 'required',
            'mobile'      => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
   
       if ($validator->fails()) {
           return redirect()->back()->withInput()->withErrors($validator);
       }

       $address               =  Address::find(base64_decode($id));
       $address->fullname     = $request->fullname;
       $address->fulladdress  = $request->fulladdress;
       $address->mobile       = $request->mobile;
       $address->address_details      = $request->address_details;
       $address->save();
       return redirect()->back()->with('success', "Record delete successfully !");

     }

    

    

    

    

}