<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Model\Attributes;
use App\Model\Brand;
use App\Model\Colors;
use App\Model\Filter_values;
use App\Model\Option;
use App\Model\Option_value;
use App\Model\Product;
use App\Model\Product_attribute;
use App\Model\Product_delivery_features;
use App\Model\Product_details;
use App\Model\Product_reviews;
use App\Model\Subcategory;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $i = (request()->input('page', 1) - 1) * 10;
        $products = DB::table('products')->select('*')->orderBy('id', 'DESC')->paginate(10);
        return view('admin.product.index', compact('products', 'i'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {$category = DB::table('category')->select('*')->get();
        $sub_category = DB::table('sub_category')->select('*')->get();
        $Products = DB::table('products')->select('*')->get();
        $colors = DB::table('colors')->select('*')->get();
        $product_delivery_features = DB::table('product_delivery_features')->select('*')->where('status', 1)->get();
        $attribute = Attributes::select('*')->where('status', 1)->get();
        $option = Option::all();
        $brand = Brand::all();

        $filter = Filter_values::select('*', DB::raw('(select name_en from filters where filters.id=filter_values.filter_id) as filtername'))->get();
        return view('admin.product.add', compact('category', 'brand', 'sub_category', 'Products', 'filter', 'product_delivery_features', 'colors', 'attribute', 'option'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'img' => 'required|mimes:jpeg,jpg,png|max:500000',
                'img1' => 'required|mimes:jpeg,jpg,png|max:500000',
                'name_en' => 'required|max:255',
                'name_ar' => 'required|max:255',
                'category_id_en' => 'required',
                'sub_category_id_en' => 'required',
                'description_en' => 'required',
                'description_ar' => 'required',
                'seo_url' => 'unique:products|max:500',
                'price' => 'required|numeric',
                'quantity' => 'required|numeric',
                'dimension_en' => 'max:255',
                'dimension_ar' => 'max:255',
                'img2' => 'mimes:jpeg,jpg,png|max:500000',
                'img3' => 'mimes:jpeg,jpg,png|max:500000',
                'img4' => 'mimes:jpeg,jpg,png|max:500000',
                'img5' => 'mimes:jpeg,jpg,png|max:500000',

            ]);

            if ($validator->fails()) {

                return redirect()->back()->withInput()->withErrors($validator);
            }

            if (!empty($request->optionquantity)) {$total_quant = 0;
                foreach ($request->optionquantity as $optquantity) {
                    $total_quant = $total_quant + $optquantity;
                }
                if ($request->quantity < $total_quant) {
                    return redirect()->back()->withInput()->with('Quantity', "product  Created Successfully !");
                }
            }

            $related_products = '';

            if (!empty($request->relatedproducts)) {
                $related_products = implode(',', $request->relatedproducts);
            }

            $productfilters = '';

            if (!empty($request->productfilters)) {
                $productfilters = implode(',', $request->productfilters);
            }

            $sub_category_id_en = '';

            if (!empty($request->sub_category_id_en)) {
                $sub_category_id_en = implode(',', $request->sub_category_id_en);
            }

            $deliveryfeatures = '';

            if (!empty($request->deliveryfeatures)) {
                $deliveryfeatures = implode(',', $request->deliveryfeatures);
            }

            if (!empty($request->price) && !empty($request->offer_price)) {
                $discount = $request->price - $request->offer_price;

                $discount = ($discount / $request->price) * 100;
                $discount = number_format((float) $discount, 2, '.', '');

            }

            $product = new Product;
            $product->name_en = $request->name_en;
            $product->name_ar = $request->name_ar;
            $product->description_en = $request->description_en;
            $product->description_ar = $request->description_ar;
            $product->seo_url = $request->seo_url;
            $product->model_en = $request->model_en;
            $product->model_ar = $request->model_ar;
            $product->sku_en = $request->sku_en;
            $product->sku_ar = $request->sku_ar;
            if (!empty($request->offer_price)) {
                $product->price = $request->offer_price;
                $product->offer_price = $request->price;
            } else {
                $product->price = $request->price;
                $product->offer_price = $request->offer_price;
            }
            $product->serial_number = $request->serial_number;
            $product->type = $request->type;
            $product->material = $request->material;
            $product->quantity = $request->quantity;
            $product->dimension_en = $request->dimension_en;
            $product->dimension_ar = $request->dimension_ar;
            $product->brand_id = $request->brand;
            $product->category_id = $request->category_id_en;
            $product->sub_category_id = $sub_category_id_en;
            $product->meta_tag_title_en = $request->meta_tag_title_en;
            $product->meta_tag_title_ar = $request->meta_tag_title_ar;
            $product->meta_tag_desc_en = $request->meta_tag_desc_en;
            $product->meta_tag_desc_ar = $request->meta_tag_desc_ar;
            $product->keywords_en = $request->keywords_en;
            $product->offer_label_en = $request->offer_label_en;
            $product->offer_label_ar = $request->offer_label_ar;
            $product->tax_class_en = $request->tax_class_en;
            $product->tax_class_ar = $request->tax_class_ar;
            $product->keywords_ar = $request->keywords_ar;
            $product->stock_availabity = $request->stock_availabity;
            $product->brandname_en = $request->brandname_en;
            $product->brandname_ar = $request->brandname_ar;
            $product->rewardpoints = $request->rewardpoints;
            $product->availabledate = $request->availabledate;
            $product->taxclass = $request->taxclass;
            $product->weight = $request->weight;
            $product->relatedproducts = $related_products;
            $product->productfilters = $productfilters;
            $product->delivery_features = $deliveryfeatures;
            if (!empty($request->price) && !empty($request->offer_price)) {
                $product->discount_available = (int) $discount;
            } else {
                $product->discount_available = 0;
            }

            if ($request->file('img')) {
                $file = $request->file('img');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img')->getClientOriginalExtension();
                    $filename = time() . '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img = $filename;
                }
            }

            if ($request->file('img1')) {
                $file = $request->file('img1');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img1')->getClientOriginalExtension();
                    $filename = time() . '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img1 = $filename;
                }
            }
            if ($request->file('img2')) {
                $file = $request->file('img2');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img2')->getClientOriginalExtension();
                    $filename = time() . '_3.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img2 = $filename;
                }
            }

            if ($request->file('img3')) {
                $file = $request->file('img3');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img3')->getClientOriginalExtension();
                    $filename = time() . '_4.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img3 = $filename;
                }
            }

            if ($request->file('img4')) {
                $file = $request->file('img4');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img4')->getClientOriginalExtension();
                    $filename = time() . '_5.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img4 = $filename;
                }
            }

            if ($request->file('img5')) {
                $file = $request->file('img5');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img5')->getClientOriginalExtension();
                    $filename = time() . '_6.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img5 = $filename;
                }
            }

            $product->save();

            if (!empty($request->attribute) && !empty($request->attributename_en) && !empty($request->attributename_ar)) {
                foreach ($request->attribute as $key => $attr) {
                    if (!empty($attr) && !empty($request->attributename_en[$key]) && !empty($request->attributename_ar[$key])) {
                        $productattribute = new Product_attribute;
                        $productattribute->product_id = $product->id;
                        $productattribute->name_en = $request->attributename_en[$key];
                        $productattribute->attribute_id = $attr;
                        $productattribute->name_ar = $request->attributename_ar[$key];
                        $productattribute->save();
                    }
                }
            }

            if (!empty($request->colors)) {
                foreach ($request->colors as $key => $colors) {
                    if (!empty($request->optionvalue[$key]) || !empty($request->optionid[$key]) || !empty($request->optionquantity[$key]) || !empty($request->optionprice[$key])) {
                        $productoption = new Product_details;
                        $productoption->product_id = $product->id;
                        $productoption->option_id = $request->optionvalue[$key];
                        $productoption->option_value = $request->optionid[$key];
                        $productoption->quantity = $request->optionquantity[$key];
                        $productoption->price = $request->optionprice[$key];
                        $productoption->color = $colors;

                        if (!empty($request->filename[$key])) {
                            $t_file_name = $request->filename[$key]->getClientOriginalName();
                            $request->filename[$key]->move(public_path() . '/product_images', $t_file_name);
                            $productoption->image = $t_file_name;
                        }
                        $productoption->save();

                    }

                }

            } else {
                if (!empty($request->optionid)) {
                    foreach ($request->optionid as $key => $opt_id) {
                        if (!empty($request->optionvalue[$key]) || !empty($request->colors[$key]) || !empty($request->optionquantity[$key]) || !empty($request->optionprice[$key])) {
                            $productoption = new Product_details;
                            $productoption->product_id = $product->id;
                            $productoption->option_id = $opt_id;
                            $productoption->option_value = $request->optionid[$key];
                            $productoption->quantity = $request->optionquantity[$key];
                            $productoption->price = $request->optionprice[$key];
                            $productoption->color = $request->colors[$key];

                            if (!empty($request->filename[$key])) {
                                $t_file_name = $request->filename[$key]->getClientOriginalName();
                                $request->filename[$key]->move(public_path() . '/product_images', $t_file_name);
                                $productoption->image = $t_file_name;
                            }
                            $productoption->save();
                        }
                    }
                }

            }

            return redirect()->back()->with('success', "product  Created Successfully !");

        } else {

            $category = DB::table('category')->select('*')->get();
            $sub_category = DB::table('sub_category')->select('*')->get();

            return view('admin.product.add', compact('category', 'sub_category'));
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $product = DB::table('products')->select('*')->where('id', $id)->first();

        $cid = $product->category_id;
        $sid = $product->sub_category_id;
        $category = DB::table('category')
            ->select('category_name_en', 'category_name_ar')
            ->Where('id', $cid)
            ->get();

        $sub_category = DB::table('sub_category')
            ->select('sub_category_name_en', 'sub_category_name_ar')
            ->Where('id', $sid)
            ->get();

        return view('admin.product.show', compact('product', 'category', 'sub_category'));

    }

    public function edit(Request $request, $id)
    {
        $product = DB::table('products')->select('*')->where('id', $id)->first();
        $category = DB::table('category')->select('*')->get();
        $sub_category = DB::table('sub_category')->select('*')->get();
        if (!empty($product->category_id)) {
            $sub_category = DB::table('sub_category')->where('category_id', $product->category_id)->select('*')->get();
        }
        $Products = DB::table('products')->select('*')->get();
        $colors = DB::table('colors')->select('*')->get();
        $attribute = Attributes::select('*')->where('status', 1)->get();
        $product_delivery_features = DB::table('product_delivery_features')->select('*')->where('status', 1)->get();
        $product_details = Product_details::select('*', DB::raw('(select value from option_values where option_values.id=product_details.option_value)as opname'))->where('product_id', $product->id)->get();
        $option = Option::all();
        $filter_values = DB::table('filter_values')->select('*')->get();
        $product_attribute = Product_attribute::where('product_id', $product->id)->get();
        $brand = Brand::all();

        return view('admin.product.edit', compact('product', 'brand', 'category', 'sub_category', 'Products', 'product_delivery_features', 'colors', 'product_details', 'attribute', 'product_attribute', 'option', 'filter_values'));
    }

    public function update(Request $request, $id)
    {
        $product = DB::table('products')->select('*')->where('id', $id)->first();

        if ($request->isMethod('put')) {
            $validator = Validator::make($request->all(), [
                'name_en' => [
                    'max:255',
                    'required',

                ],
                'name_ar' => [
                    'max:255',
                    'required',

                ],
                'category_id_en' => 'required',
                'sub_category_id_en' => 'required',
                'description_en' => 'required',
                'description_ar' => 'required',
                'price' => 'required|numeric',
                'quantity' => 'required|numeric',
                'seo_url' => 'required|max:500|unique:products,seo_url,' . $id,
                'dimension_en' => 'max:255',
                'dimension_ar' => 'max:255',
                'img1' => 'mimes:jpeg,jpg,png|max:500000',
                'img' => 'mimes:jpeg,jpg,png|max:500000',
                'img2' => 'mimes:jpeg,jpg,png|max:500000',
                'img3' => 'mimes:jpeg,jpg,png|max:500000',
                'img4' => 'mimes:jpeg,jpg,png|max:500000',
                'img5' => 'mimes:jpeg,jpg,png|max:500000',

            ]);

            if ($validator->fails()) {

                return redirect()->back()->withInput()->withErrors($validator);
            }

            if (!empty($request->optionquantity)) {$total_quant = 0;
                foreach ($request->optionquantity as $optquantity) {
                    $total_quant = $total_quant + $optquantity;
                }
                if ($request->quantity < $total_quant) {
                    return redirect()->back()->withInput()->with('Quantity', "product  Created Successfully !");
                }
            }

            if (!empty($request->relatedproducts)) {
                $related_products = implode(',', $request->relatedproducts);
            }
            if (!empty($request->productfilters)) {
                $productfilters = implode(',', $request->productfilters);
            }

            if (!empty($request->price) && !empty($request->offer_price)) {
                $discount = $request->price - $request->offer_price;

                $discount = ($discount / $request->price) * 100;
                $discount = number_format((float) $discount, 2, '.', '');

            }

            $deliveryfeatures = '';

            if (!empty($request->deliveryfeatures)) {
                $deliveryfeatures = implode(',', $request->deliveryfeatures);
            }

            $sub_category_id_en = '';
            if (!empty($request->sub_category_id_en)) {
                $sub_category_id_en = implode(',', $request->sub_category_id_en);
            }

            $id = $product->id;
            $product = Product::find($id);
            $product->name_en = $request->name_en;
            $product->name_ar = $request->name_ar;
            $product->description_en = $request->description_en;
            $product->description_ar = $request->description_ar;
            $product->seo_url = $request->seo_url;
            $product->model_en = $request->model_en;
            $product->brand_id = $request->brand;
            $product->model_ar = $request->model_ar;
            $product->sku_en = $request->sku_en;
            $product->sku_ar = $request->sku_ar;
            if (!empty($request->offer_price)) {
                $product->price = $request->offer_price;
                $product->offer_price = $request->price;
            } else {
                $product->price = $request->price;
                $product->offer_price = $request->offer_price;
            }
            $product->serial_number = $request->serial_number;
            $product->type = $request->type;
            $product->material = $request->material;
            $product->quantity = $request->quantity;
            $product->dimension_en = $request->dimension_en;
            $product->dimension_ar = $request->dimension_ar;
            $product->stock_availabity = $request->stock_availabity;
            $product->brandname_en = $request->brandname_en;
            $product->brandname_ar = $request->brandname_ar;
            $product->rewardpoints = $request->rewardpoints;
            $product->offer_label_en = $request->offer_label_en;
            $product->offer_label_ar = $request->offer_label_ar;
            $product->tax_class_en = $request->tax_class_en;
            $product->tax_class_ar = $request->tax_class_ar;
            $product->availabledate = $request->availabledate;
            $product->taxclass = $request->taxclass;
            $product->weight = $request->weight;
            $product->delivery_features = $deliveryfeatures;

            if (!empty($request->price) && !empty($request->offer_price)) {
                $product->discount_available = (int) $discount;
            } else {
                $product->discount_available = 0;
            }

            $product->category_id = $request->category_id_en;
            $product->sub_category_id = $sub_category_id_en;
            if (!empty($request->colors)) {
                $product->colors = implode(",", $request->colors);
            }

            if (!empty($request->relatedproducts)) {
                $product->relatedproducts = $related_products;
            }
            if (!empty($request->productfilters)) {
                $product->productfilters = $productfilters;
            }

            if ($request->file('img')) {
                $file = $request->file('img');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img')->getClientOriginalExtension();
                    $filename = time() . '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img = $filename;
                }
            }

            if ($request->file('img1')) {
                $file = $request->file('img1');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img1')->getClientOriginalExtension();
                    $filename = time() . '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img1 = $filename;
                }
            }
            if ($request->file('img2')) {
                $file = $request->file('img2');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img2')->getClientOriginalExtension();
                    $filename = time() . '_3.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img2 = $filename;
                }
            }

            if ($request->file('img3')) {
                $file = $request->file('img3');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img3')->getClientOriginalExtension();
                    $filename = time() . '_4.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img3 = $filename;
                }
            }

            if ($request->file('img4')) {
                $file = $request->file('img4');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img4')->getClientOriginalExtension();
                    $filename = time() . '_5.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img4 = $filename;
                }
            }

            if ($request->file('img5')) {
                $file = $request->file('img5');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('img5')->getClientOriginalExtension();
                    $filename = time() . '_6.' . $extension;
                    $file->move($destinationPath, $filename);
                    $product->img5 = $filename;
                }
            }

            //    $product_detail_id    = Product_details::select('id')->where('status',1)->whereIn('id',$request->colorproductid)->get();
            //    $allproduct_detail_id = Product_details::select('id')->where('status', 1)->where('product_id',$product->id)->get();
            //    $allid =array();
            //    $remainid=array();
            //   foreach($allproduct_detail_id as $allproductid)
            //   {
            //     array_push($allid,$allproductid->id);
            //   }
            //     foreach($product_detail_id as $productid)
            //   {
            //     array_push($remainid,$productid->id);
            //   }
            //   $result=array_diff($allid, $remainid);

            //   if(!empty($result))
            //   {
            //       foreach($result as $resultdata)
            //       {
            //        Product_details::where('id', $resultdata)->delete();
            //       }
            //   }

            //     foreach($request->colorproductid as $key => $data)
            //     {
            //         if(!empty($request->colorproductid[$key]))
            //         {
            //                 $productdetail                 =   Product_details::find($request->colorproductid[$key]);
            //                 $productdetail->product_id     = $product->id;
            //                 $productdetail->quantity       = $request->colorquantity[$key];
            //                 $productdetail->color          = $request->colors[$key];
            //                 $productdetail->price          = $request->colorprice[$key];
            //                 if(!empty($request->filename[$key]))
            //                 {
            //                 $t_file_name= $request->filename[$key]->getClientOriginalName();
            //                 $request->filename[$key]->move(public_path().'/product_images', $t_file_name);
            //                 $productdetail->image       = $t_file_name;

            //                 }

            //                 $productdetail->save();

            //         }
            //         else
            //         {
            //             if (!empty($request->filename[$key]) && !empty($request->colorquantity[$key]) && !empty($request->colors[$key]) && !empty($request->colorprice[$key])) {
            //                 $productdetail              = new Product_details;
            //                 $productdetail->product_id  = $product->id;
            //                 $productdetail->quantity    = $request->colorquantity[$key];
            //                 $productdetail->color       = $request->colors[$key];
            //                 $productdetail->price       = $request->colorprice[$key];

            //                 $t_file_name= $request->filename[$key]->getClientOriginalName();
            //                 $request->filename[$key]->move(public_path().'/product_images', $t_file_name);
            //                 $productdetail->image       = $t_file_name;
            //                 $productdetail->save();
            //             }
            //         }

            //     }

            $product_attribute_id = Product_attribute::select('id')->whereIn('id', $request->attrid)->get();
            $allproduct_attribute_id = Product_attribute::select('id')->where('product_id', $product->id)->get();
            $allattrid = array();
            $remainattrid = array();
            foreach ($allproduct_attribute_id as $allproductattrid) {
                array_push($allattrid, $allproductattrid->id);
            }
            foreach ($product_attribute_id as $attriid) {
                array_push($remainattrid, $attriid->id);
            }
            $result1 = array_diff($allattrid, $remainattrid);

            if (!empty($result1)) {
                foreach ($result1 as $resultdata1) {
                    Product_attribute::where('id', $resultdata1)->delete();
                }
            }

            foreach ($request->attrid as $key => $data) {
                if (!empty($request->attrid[$key])) {
                    $productattribute = Product_attribute::find($request->attrid[$key]);
                    $productattribute->product_id = $product->id;
                    $productattribute->attribute_id = $request->attribute[$key];
                    $productattribute->name_en = $request->attributename_en[$key];
                    $productattribute->name_ar = $request->attributename_ar[$key];
                    $productattribute->save();
                } else {
                    if (!empty($request->attributename_en[$key]) && !empty($request->attributename_ar[$key]) && !empty($request->attribute[$key])) {
                        $productattribute = new Product_attribute;
                        $productattribute->product_id = $product->id;
                        $productattribute->attribute_id = $request->attribute[$key];
                        $productattribute->name_en = $request->attributename_en[$key];
                        $productattribute->name_ar = $request->attributename_ar[$key];
                        $productattribute->save();
                    }
                }
            }

            $product_option_id = Product_details::select('id')->whereIn('id', $request->option_ids)->get();
            $allproduct_option_id = Product_details::select('id')->where('product_id', $product->id)->get();
            $alloptionid = array();
            $remainoptionid = array();
            foreach ($allproduct_option_id as $allproductoptid) {
                array_push($alloptionid, $allproductoptid->id);
            }
            foreach ($product_option_id as $optiid) {
                array_push($remainoptionid, $optiid->id);
            }
            $result2 = array_diff($alloptionid, $remainoptionid);

            if (!empty($result2)) {
                foreach ($result2 as $resultdata2) {
                    Product_details::where('id', $resultdata2)->delete();
                }
            }

            foreach ($request->option_ids as $key => $data) {
                if (!empty($request->option_ids[$key])) {
                    $productoption = Product_details::find($request->option_ids[$key]);
                    $productoption->product_id = $product->id;
                    if (!empty($request->optionvalue[$key])) {
                        $productoption->option_id = $request->optionvalue[$key];
                    }
                    if (!empty($request->optionquantity[$key])) {
                        $productoption->quantity = $request->optionquantity[$key];
                    }
                    if (!empty($request->optionid[$key])) {
                        $productoption->option_value = $request->optionid[$key];
                    }
                    if (!empty($request->optionprice[$key])) {
                        $productoption->price = $request->optionprice[$key];
                    }
                    if (!empty($request->colors[$key])) {
                        $productoption->color = $request->colors[$key];
                    }
                    if (!empty($request->filename[$key])) {
                        $t_file_name = $request->filename[$key]->getClientOriginalName();
                        $request->filename[$key]->move(public_path() . '/product_images', $t_file_name);
                        $productoption->image = $t_file_name;

                    }

                    $productoption->save();
                } else {
                    if (!empty($request->colors[$key]) || !empty($request->optionid[$key])) {
                        $productoption = new Product_details;
                        $productoption->product_id = $product->id;
                        $productoption->option_id = $request->optionvalue[$key];
                        $productoption->option_value = $request->optionid[$key];
                        $productoption->quantity = $request->optionquantity[$key];
                        $productoption->price = $request->optionprice[$key];
                        $productoption->color = $request->colors[$key];

                        if (!empty($request->filename[$key])) {
                            $t_file_name = $request->filename[$key]->getClientOriginalName();
                            $request->filename[$key]->move(public_path() . '/product_images', $t_file_name);
                            $productoption->image = $t_file_name;
                        }
                        $productoption->save();
                    }
                }
            }

            $product->save($request->all());
            return redirect()->back()
                ->with('success', 'Product updated successfully');
        }
    }

    //Destroy Product
    public function destroy($id)
    {

        $data = Product::where('id', $id)->first();

        if (is_null($data)) {

            return redirect()->back()->with('error', "data not found");

        }
        $data_info = DB::select("SELECT
                            TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
                        FROM
                        INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                        WHERE
                        -- REFERENCED_TABLE_SCHEMA = 'ess_sahil' AND
                        REFERENCED_TABLE_NAME = 'products   ' AND
                        REFERENCED_COLUMN_NAME = 'id'");

        foreach ($data_info as $key => $tables) {
            if ($tables->TABLE_NAME == "product_attributes") {
                $table = DB::select("select product_id from `product_attribute` where product_id = '$id'");
            } else if ($tables->TABLE_NAME == "wish_lists") {
                $table = DB::select("select product_id from `wishlist` where product_id = '$id'");
            }

            if (!empty($table)) {
                return redirect()->back()->with('error', "This data has children, you can not delete because this item is in use");
                break;
            }
        }
        $orderData = DB::table('order_details')->where('product_id', $id)->pluck('order_id')->toarray();
        $status = ["11", "14"];
        $checkOrder = DB::table('order')->whereIn('id', $orderData)->whereNotIn('status', $status)->pluck('id')->toarray();

        if (!empty($checkOrder)) {

            return redirect()->back()->with('error', "This data has children, you can not delete because this item is in use");

        }

        $fileName = $data->id . '.json';
        Storage::disk('public')->put('deleted_items/product/' . $fileName, response()->json($data));
        $data->forceDelete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }

    //Export Product
    public function Export()
    {

        return $Record = Excel::download(new ProductsExport, 'products.xlsx');
    }

    //Import product load view
    public function import()
    {
        return view('admin.product.import');

    }

    //Import product
    public function product_import(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xlsx',
            ]);
        }
        if ($validator->fails()) {

            return redirect()->back()->withInput()->withErrors($validator);
        } else {

            Excel::import(new ProductsImport, request()->file('file'));
            return redirect()->back()
                ->with('success', 'Product Imported successfully');
        }
    }

    //product Status update through toggle
    public function updateStatus(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->status = $request->status;
        $product->save();
        if ($request->status == 1) {
            return response()->json(['message' => 'Product status Enable successfully.']);
        } else {
            return response()->json(['message' => 'Product status Disable successfully.']);

        }
    }

    public function getsubcategory($id)
    {
        $subcategory = Subcategory::where(['category_id' => $id])->get();
        return $subcategory;
    }

    public function product_image_delete(Request $request)
    {

        $id = $request->id;
        $productid = $request->image;

        if ($id == 2) {
            Product::where('id', $productid)->update(['img2' => null]);
            return redirect()->back()->with('success', 'Product updated successfully');
        }

        if ($id == 3) {
            Product::where('id', $productid)->update(['img3' => null]);
            return redirect()->back()->with('success', 'Product updated successfully');
        }

        if ($id == 4) {
            Product::where('id', $productid)->update(['img4' => null]);
            return redirect()->back()->with('success', 'Product updated successfully');
        }

        if ($id == 5) {
            Product::where('id', $productid)->update(['img5' => null]);
            return redirect()->back()->with('success', 'Product updated successfully');
        }

    }

    public function product_delivery_index(Request $request)
    {
        $product_delivery_features = Product_delivery_features::all();
        return view('admin.product_delivery_features.index', compact('product_delivery_features'));
    }

    public function product_delivery_delete(Request $request, $id)
    {
        $affectedRows = Product_delivery_features::where('id', $id)->delete();
        return redirect()->back()->with('info', "Record delete successfully !");
    }

    public function product_delivery_add(Request $request)
    {

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'image_en' => 'required|mimes:jpeg,jpg,png,svg|max:500000',
                'image_ar' => 'required|mimes:jpeg,jpg,png,svg|max:500000',
                'title_en' => 'required|max:255',
                'title_ar' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $productfeatures = new Product_delivery_features;
            $productfeatures->title_en = $request->title_en;
            $productfeatures->title_ar = $request->title_ar;
            $productfeatures->status = $request->status;

            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename = time() . '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $productfeatures->image_en = $filename;
                }
            }

            if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename = time() . '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $productfeatures->image_ar = $filename;
                }
            }

            $productfeatures->save();
            return redirect()->back()->with('success', "product Created Successfully !");

        } else {
            return view('admin.product_delivery_features.add');
        }
    }

    public function product_delivery_edit(Request $request, $id)
    {

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(), [
                'image_en' => 'mimes:jpeg,jpg,png,svg|max:500000',
                'image_ar' => 'mimes:jpeg,jpg,png,svg|max:500000',
                'title_en' => 'required|max:255',
                'title_ar' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $productfeatures = Product_delivery_features::find($id);
            $productfeatures->title_en = $request->title_en;
            $productfeatures->title_ar = $request->title_ar;
            $productfeatures->status = $request->status;

            if ($request->file('image_en')) {
                $file = $request->file('image_en');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('image_en')->getClientOriginalExtension();
                    $filename = time() . '_1.' . $extension;
                    $file->move($destinationPath, $filename);
                    $productfeatures->image_en = $filename;
                }
            }

            if ($request->file('image_ar')) {
                $file = $request->file('image_ar');
                if ($file) {
                    $destinationPath = public_path('/product_images');
                    $extension = $request->file('image_ar')->getClientOriginalExtension();
                    $filename = time() . '_2.' . $extension;
                    $file->move($destinationPath, $filename);
                    $productfeatures->image_ar = $filename;
                }
            }

            $productfeatures->save();
            return redirect()->back()->with('success', "product Created Successfully !");

        } else {
            $data = Product_delivery_features::where('id', $id)->first();
            return view('admin.product_delivery_features.edit', compact('data'));
        }
    }

    public function review_index(Request $request)
    {
        $review = Product_reviews::select('*', DB::raw('(select name from users where users.id=product_reviews.user_id)as username'), DB::raw('(select name_en from products where products.id=product_reviews.product_id)as productname'))->where('status', 1)->paginate(10);

        return view('admin.product_review.index', compact('review'));
    }

    public function product_review_status(Request $request)
    {
        $product = Product_reviews::findOrFail($request->review_id);
        $product->approved = $request->status;
        $product->save();
        if ($request->status == 1) {
            return response()->json(['message' => 'Product status Enable successfully.']);
        } else {
            return response()->json(['message' => 'Product status Disable successfully.']);
        }

    }

    public function review_view(Request $request, $id)
    {
        $review = Product_reviews::select('*', DB::raw('(select name from users where users.id=product_reviews.user_id)as username'), DB::raw('(select name_en from products where products.id=product_reviews.product_id)as productname'))->where('id', $id)->where('status', 1)->first();

        return view('admin.product_review.view', compact('review'));

    }

    public function getoptions($id)
    {
        $options = Option_value::where('option_id', $id)->get();
        return $options;
    }

    public function search(Request $request)
    {
        $val = $request->name;
        $i = (request()->input('page', 1) - 1) * 10;
        $products = DB::table('products')->select('*')->where('name_en', 'like', '%' . $request->name . '%')->orderBy('id', 'DESC')->paginate(10);
        return view('admin.product.index', compact('products', 'i', 'val'));
    }

}
