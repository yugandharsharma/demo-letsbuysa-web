<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Model\Order;

/**
 * Helps
 * Role
 * 1=>Admin
 * 2=>User
 * */

class ReportsController extends Controller{

	/**  Class construct */
	public function __construct(){
        $this->user_report_view_path = 'admin.reports.user.';
        $this->order_report_view_path = 'admin.reports.orders.';
    }// endof construct


	/**
	 * listUserReport uses for listing of user Reports
	 * @param 
	 * */
	public function listUserReport(Request $request){
		
		$query = 	
			User::
			select(['users.id','users.name','users.email','users.created_at','users.mobile','address.country','address.city','country.name as country_name'])
			->leftJoin('address', 'users.id', '=', 'address.user_id')
			->leftJoin('country', 'address.country', '=', 'country.country_id')
			->where(['users.is_delete'=>'0','users.role'=>'2'])
			->orderBy('users.id', 'DESC');

		if(!empty($request['name'])){
            $query->where('users.name', 'LIKE', '%'.trim($request['name']).'%');
        }
        if(!empty($request['email'])){
            $query->where('users.email', 'LIKE', '%'.trim($request['email']).'%');
        }
        if(!empty($request['mobile'])){
            $query->where('users.mobile', 'LIKE', '%'.trim($request['mobile']).'%');
        }
        if(!empty($request['city'])){
            $query->where('address.city', 'LIKE', '%'.trim($request['city']).'%');
        }
        if(!empty($request['country'])){
            $query->having('country_name', 'LIKE', '%'.trim($request['country']).'%');
        }

		$userReports =	$query->paginate(10);
		
		return view($this->user_report_view_path.'list', compact('userReports'));
	}// endof listUserReport

	/** 
	 * exportUserReportData uses of export user reports
	 *  */
	public function exportUserReportData(Request $request){
    	$fileName = "users_export_data-" . date('Ymd') . ".xls"; 

    	$query = 	
			User::
			select(['users.id','users.name','users.email','users.created_at','users.mobile','address.country','address.city','country.name as country_name'])
			->leftJoin('address', 'users.id', '=', 'address.user_id')
			->leftJoin('country', 'address.country', '=', 'country.country_id')
			->where(['users.is_delete'=>'0','users.role'=>'2']);
			

			if(!empty($request['name'])){
	            $query->where('users.name', 'LIKE', '%'.trim($request['name']).'%');
	        }
	        if(!empty($request['email'])){
	            $query->where('users.email', 'LIKE', '%'.trim($request['email']).'%');
	        }
	        if(!empty($request['mobile'])){
	            $query->where('users.mobile', 'LIKE', '%'.trim($request['mobile']).'%');
	        }
	        if(!empty($request['city'])){
	            $query->where('address.city', 'LIKE', '%'.trim($request['city']).'%');
	        }
	        if(!empty($request['country'])){
	            $query->having('country_name', 'LIKE', '%'.trim($request['country']).'%');
	        }


			$data = $query->get()->toArray();


        header('Content-Encoding: UTF-8'); // vilh, change to UTF-8!
		header("Content-type: application/x-msexcel; charset=utf-8");  // vilh, chang
		header('Content-Encoding: UTF-8'); // vilh, change to UTF-8!
		header("Content-type: application/x-msexcel; charset=utf-8");  // vilh, change to UTF-8!
		header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
		header('Cache-Control: max-age=0');
		echo "\xEF\xBB\xBF";

		$flag=false;
		foreach($data as $row) {
		    if(!$flag) {
		    	echo 'id,';
		    	echo 'Name,';
		    	echo 'Email,';
		    	echo 'Mobile,';
		    	echo 'City,';
		    	echo 'Country,';
		    	echo 'Created at';
		    	echo "\r\n";
		      	$flag = true;
		    }

		    echo $row['id'].',';
		    echo $row['name'].',';
		    echo $row['email'].',';
		    echo "'".$row['mobile']. ',';
		    echo $row['city'].',';
		    echo $row['country_name'].',';
		    echo $row['created_at'].',';
		    echo "\r\n";
		}

		return null;
    } //endof exportUserReportData


    /**
     * listOrderReports uses of listing of order reports
     * */
    public function listOrderReports(Request $request){
    	
    	$selection = [
    		'order.id',
    		'order.user_id',
    		'order.coupan_id',
    		'order.price',
    		'order.product_total_amount',
    		'order.discount',
    		'order.shipping_price',
    		'order.delivery_price',
    		'order.paid_by_wallet',
    		'order.status as order_status',
    		'order.address_id',
    		'order.created_at',
    		'users.name as user_name',
    		// 'coupon.coupon_name as coupon_name',
    		'order_status.status_name_en as order_status_name',
    		'address.city as address_city',
    		'address.country',
    		'country.name as address_country',
    		'order.payment_type',
    	];

    	$query = 
    		Order::
    		select($selection)
    		->leftJoin('users', 'order.user_id', '=', 'users.id')
    		->leftJoin('coupon', 'order.coupan_id', '=', 'coupon.id')
    		->leftJoin('order_status', 'order_status.id', '=', 'order.status')
    		->leftJoin('address', 'order.address_id', '=', 'address.id')
    		->leftJoin('country', 'address.country', '=', 'country.country_id')
    		->orderBy('users.id', 'DESC');

    	if(!empty($request['coupon_name'])){
            $query->having('coupon_name', 'LIKE', '%'.trim($request['coupon_name']).'%');
        }

        if(!empty($request['user_name'])){
            $query->having('user_name', 'LIKE', '%'.trim($request['user_name']).'%');
        }

        if(!empty($request['address_city'])){
            $query->having('address_city', 'LIKE', '%'.trim($request['address_city']).'%');
        }

        if(!empty($request['address_country'])){
            $query->having('address_country', 'LIKE', '%'.trim($request['address_country']).'%');
        }

        if(!empty($request['order_status_name'])){
            $query->having('order_status_name', 'LIKE', '%'.trim($request['order_status_name']).'%');
        }

        if(!empty($request['price'])){
            $query->where('order.price', 'LIKE', '%'.trim($request['price']).'%');
        }

        if(!empty($request['payment_method'])){
        	$inItem = explode(',',$request['payment_method']);
            $query->whereIn('order.payment_type', $inItem);
        }

        if(!empty($request['created_at'])){
        	$exploded_date= explode('-', $request['created_at']); 
	        $start_date =  date("Y-m-d", strtotime(trim($exploded_date[0])));
	        $end_date = date("Y-m-d", strtotime(trim($exploded_date[1])));
        	
        	$query->whereRaw("(order.created_at >= ? AND order.created_at <= ?)", [
			    $start_date." 00:00:00", 
			    $end_date." 23:59:59"
			]);
        }
    	$orderReports = $query->paginate(10);

    	return view($this->order_report_view_path.'list',compact(['orderReports']));
    } //endof listOrderReports


    /** 
	 * exportOrderReportData uses of export user reports
	 *  */
	public function exportOrderReportData(Request $request){
    	$fileName = "users_export_data-" . date('Ymd') . ".xls"; 

    	$selection = [
    		'order.id',
    		'order.user_id',
    		'order.coupan_id',
    		'order.price',
    		'order.product_total_amount',
    		'order.discount',
    		'order.shipping_price',
    		'order.delivery_price',
    		'order.paid_by_wallet',
    		'order.status as order_status',
    		'order.address_id',
    		'order.created_at',
    		'users.name as user_name',
    		'coupon.coupon_name as coupon_name',
    		'order_status.status_name_en as order_status_name',
    		'address.city as address_city',
    		'address.country',
    		'country.name as address_country',
    		'order.payment_type',
    		'order.payment_type as payment_method',
    	];

    	$query = 
    		Order::
    		select($selection)
    		->leftJoin('users', 'order.user_id', '=', 'users.id')
    		->leftJoin('coupon', 'order.coupan_id', '=', 'coupon.id')
    		->leftJoin('order_status', 'order_status.id', '=', 'order.status')
    		->leftJoin('address', 'order.address_id', '=', 'address.id')
    		->leftJoin('country', 'address.country', '=', 'country.country_id')
    		->orderBy('users.id', 'DESC');

    	if(!empty($request['coupon_name'])){
            $query->having('coupon_name', 'LIKE', '%'.trim($request['coupon_name']).'%');
        }

        if(!empty($request['user_name'])){
            $query->having('user_name', 'LIKE', '%'.trim($request['user_name']).'%');
        }

        if(!empty($request['address_city'])){
            $query->having('address_city', 'LIKE', '%'.trim($request['address_city']).'%');
        }

        if(!empty($request['address_country'])){
            $query->having('address_country', 'LIKE', '%'.trim($request['address_country']).'%');
        }

        if(!empty($request['order_status_name'])){
            $query->having('order_status_name', 'LIKE', '%'.trim($request['order_status_name']).'%');
        }

        if(!empty($request['price'])){
            $query->where('order.price', 'LIKE', '%'.trim($request['price']).'%');
        }

        if(!empty($request['payment_method'])){
        	$inItem = explode(',',$request['payment_method']);
            $query->whereIn('order.payment_type', $inItem);
        }

        if(!empty($request['created_at'])){
        	$exploded_date= explode('-', $request['created_at']); 
	        $start_date =  date("Y-m-d", strtotime(trim($exploded_date[0])));
	        $end_date = date("Y-m-d", strtotime(trim($exploded_date[1])));
        	
        	$query->whereRaw("(order.created_at >= ? AND order.created_at <= ?)", [
			    $start_date." 00:00:00", 
			    $end_date." 23:59:59"
			]);
        }


    	$data = $query->get()->toArray();


        header('Content-Encoding: UTF-8'); // vilh, change to UTF-8!
		header("Content-type: application/x-msexcel; charset=utf-8");  // vilh, chang
		header('Content-Encoding: UTF-8'); // vilh, change to UTF-8!
		header("Content-type: application/x-msexcel; charset=utf-8");  // vilh, change to UTF-8!
		header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
		header('Cache-Control: max-age=0');
		echo "\xEF\xBB\xBF";

		$flag=false;
		foreach($data as $row) {
		    if(!$flag) {
		    	echo 'User Name,';
		    	echo 'Coupon Name,';
		    	echo 'Product Price,';
		    	echo 'Shipping,';
		    	echo 'Delivery,';
		    	echo 'Paid,';
		    	echo 'City,';
		    	echo 'Country,';
		    	echo 'Order Status,';
		    	echo 'Payment type,';
		    	echo 'Created at,';
		    	echo "\r\n";
		      	$flag = true;
		    }

		    if($row['payment_method'] == 1){
	            $paymentMethod = 'Cash on Delivery';
		    }else if($row['payment_method'] == 2 || $row['payment_method'] == 4){
	            $paymentMethod = 'Credit or debit card';
		    }else if($row['payment_method'] == 6){
	            $paymentMethod = 'Apple pay';
		    }else{
	            $paymentMethod = 'Bank transfer';
		    }
	        

		    echo $row['user_name'].',';
		    echo $row['coupon_name'].',';
		    echo $row['product_total_amount'].',';
		    echo $row['shipping_price'].',';
		    echo $row['delivery_price'].',';
		    echo $row['price'].',';
		    echo $row['address_city'].',';
		    echo $row['address_country'].',';
		    echo $row['order_status_name'].',';
		    echo $paymentMethod.',';
		    echo $row['created_at'].',';
		    echo "\r\n";
		}

		return null;
    } //endof exportOrderReportData

}