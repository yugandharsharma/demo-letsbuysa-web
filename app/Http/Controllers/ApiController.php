<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Model\Product;
use Validator;
use Hash;
use Cookie;
use Auth;
use DB;
class ApiController extends CommonController{

    public function __construct(Request $request){
        $lang       =   ($request['lang'])? $request['lang'] : 'en';
        app()->setLocale($lang);
    }

    public function ProductDataTransfer(Request $request){

        $Record=Product::all();
        foreach ($Record as $key => $value) {
         $Update=Product::find($value->id);
         $Update->price=rtrim($value->price,'.0000');
         $Update->save();
        }

    }

    public function ColorDataTransfer(Request $request){


        $Product=DB::table('oc_product_description')->select('*')->where('language_id',3)->get();
        $NewArray=[];
        foreach ($Product as $key => $value) {
           $Record=DB::table('oc_product_option')->select('*', DB::raw('(select name from oc_product_description where oc_product_description.language_id=3 and oc_product_description.product_id='.$value->product_id.')as ProductName'), DB::raw('(select name from oc_option_description where oc_option_description.option_id=oc_product_option.option_id and language_id=3)as OptionName'))->where('product_id',$value->product_id)->where('option_id',5)->get();
           // echo "<pre>";
           // print_r($Record[0]->product_option_id);die;
           if(count($Record)>0){
           $ColorRecord[$key]=DB::table('oc_product_option_value')->select('*', DB::raw('(select name from oc_product_description where oc_product_description.language_id=3 and oc_product_description.product_id='.$Record[0]->product_id.')as ProductName'), DB::raw('(select op_value from oc_option_value where oc_option_value.option_value_id=oc_product_option_value.option_value_id)as Color'))->where('product_option_id',$Record[0]->product_option_id)->get();
           for($i=0;$i<count($ColorRecord[$key]);$i++)
          {
            
            $NewArray['ProductName'][]=$ColorRecord[$key][$i]->ProductName;
            $NewArray['Color'][]=$ColorRecord[$key][$i]->Color;
            $NewArray['Quantity'][]=$ColorRecord[$key][$i]->quantity;
            $NewArray['Price'][]=0;
          }
           }
           
           
          
           
         }

        
         
        echo "<pre>";
        print_r($NewArray);die;
           
        
        

    }

      
        

    
    
    
}
