@extends('layouts.front-app')

@section('content')
        <section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
              <?php $subdata =explode(',',$product->sub_category_id);?>
              <?php $categoryname = getcategoryname($product->category_id);?>
              <?php $subcategoryname = getsubcategoryname($subdata[0]);?>
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                @if((\App::getLocale() == 'en'))
                <li class="breadcrumb-item"><a href="{{url($product->category_name)}}">{{$categoryname->category_name_en}}</a></li>
                <li class="breadcrumb-item"><a href="{{url($product->category_name,$product->sub_category_name)}}">{{$subcategoryname->sub_category_name_en}}</a></li>
                <li class="breadcrumb-item"><a href="#">{{$product->name_en}}</a></li>
                @else
                <li class="breadcrumb-item"><a href="{{url('categorylist',base64_encode($product->category_id))}}">{{$categoryname->category_name_ar}}</a></li>
                <li class="breadcrumb-item"><a href="{{url('productlist',base64_encode($product->category_id))}}{{'/'}}{{base64_encode($subdata[0])}}">{{$subcategoryname->sub_category_name_ar}}</a></li>
                <li class="breadcrumb-item"><a href="#">{{$product->name_ar}}</a></li>
                @endif
              </ul>
            </nav>
          </div>
        </section>
 <section class="next-dash">
          <div class="container">
            <div class="row pb-5-here">
              <div class="col-md-5">

                <div class="product-sliders module-gallery module-12345">
            <div class="maxWidth900 padLR15">
              <div class="padTB20">
                <div class="slider-wrapper">
                  <ul class="slider-thumb noPad noMar">
                  
                  @if(!empty($product->img))
                    <li class="type-image"><img src="{{ url('/') }}/public/product_images/{{$product->img}}"></li>
                  @endif
                  @if(!empty($product->img1))
                    <li class="type-image"><img src="{{ url('/') }}/public/product_images/{{$product->img1}}"></li>
                  @endif
                  @if(!empty($product->img2))
                    <li class="type-image"><img src="{{ url('/') }}/public/product_images/{{$product->img2}}"></li>
                  @endif
                  @if(!empty($product->img3))
                    <li class="type-image"><img src="{{ url('/') }}/public/product_images/{{$product->img3}}"></li>
                  @endif
                  @if(!empty($product->img4))
                    <li class="type-image"><img src="{{ url('/') }}/public/product_images/{{$product->img4}}"></li>
                  @endif
                  @if(!empty($product->img5))
                    <li class="type-image"><img src="{{ url('/') }}/public/product_images/{{$product->img5}}"></li>
                  @endif
                  @if(!empty($product_detail))
                  @foreach($product_detail as $productimages)
                  @if(!empty($productimages->image))
                    <li class="type-image"><img src="{{ url('/') }}/public/product_images/{{$productimages->image}}"></li>
                  @endif
                  @endforeach
                  @endif
                  </ul>

                  <ul class="slider-preview noPad noMar">
                  @if(!empty($product->img))
                    <li class="type-image">
                    <figure><a class="fancybox" rel="group" href="{{ url('/') }}/public/product_images/{{$product->img}}">
                      <img class="zoom-img" id="zoom_01" src="{{ url('/') }}/public/product_images/{{$product->img}}" data-zoom-image="{{ url('/') }}/public/product_images/{{$product->img}}"width="100%"/>
                    </a></figure>
                    </li>
                  @endif
                  @if(!empty($product->img1))
                    <li class="type-image">
                    <figure><a class="fancybox" rel="group" href="{{ url('/') }}/public/product_images/{{$product->img1}}">
                      <img class="zoom-img" id="zoom_02" src="{{ url('/') }}/public/product_images/{{$product->img1}}" data-zoom-image="{{ url('/') }}/public/product_images/{{$product->img1}}"width="100%"/>
                    </a></figure>
                    </li>
                  @endif
                  @if(!empty($product->img2))
                    <li class="type-image">
                    <figure><a class="fancybox" rel="group" href="{{ url('/') }}/public/product_images/{{$product->img2}}">
                      <img class="zoom-img" id="zoom_03" src="{{ url('/') }}/public/product_images/{{$product->img2}}" data-zoom-image="{{ url('/') }}/public/product_images/{{$product->img2}}"width="100%"/>
                    </a></figure>
                    </li>
                  @endif
                  @if(!empty($product->img3))
                    <li class="type-image">
                    <figure><a class="fancybox" rel="group" href="{{ url('/') }}/public/product_images/{{$product->img3}}">
                      <img class="zoom-img" id="zoom_04" src="{{ url('/') }}/public/product_images/{{$product->img3}}" data-zoom-image="{{ url('/') }}/public/product_images/{{$product->img3}}"width="100%"/>
                    </a></figure>
                    </li>
                  @endif
                  @if(!empty($product->img4))
                    <li class="type-image">
                    <figure><a class="fancybox" rel="group" href="{{ url('/') }}/public/product_images/{{$product->img4}}">
                      <img class="zoom-img" id="zoom_05" src="{{ url('/') }}/public/product_images/{{$product->img4}}" data-zoom-image="{{ url('/') }}/public/product_images/{{$product->img4}}"width="100%"/>
                    </a></figure>
                    </li>
                  @endif
                  @if(!empty($product->img5))
                    <li class="type-image">
                    <figure><a class="fancybox" rel="group" href="{{ url('/') }}/public/product_images/{{$product->img5}}">
                      <img class="zoom-img" id="zoom_06" src="{{ url('/') }}/public/product_images/{{$product->img5}}" data-zoom-image="{{ url('/') }}/public/product_images/{{$product->img5}}"width="100%"/>
                    </a></figure>
                    </li>
                  @endif
                  @if(!empty($product_detail))
                  @foreach($product_detail as $productimages)
                  @if(!empty($productimages->image))
                    <li class="type-image"><img src="{{ url('/') }}/public/product_images/{{$productimages->image}}"></li>
                  @endif
                  @endforeach
                  @endif
                  </ul>
                </div>
                <div class="pro-heart">
                  <?php $iswishlist1 = getwishlist($product->id);?>
                    @if(!empty(Auth::id()))
                    @if(empty($iswishlist1))
                    <button onclick="addtowishlist('{{$product->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                    @else
                    <a href="{{url('removewishlist',$product->id)}}" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></a>
                    @endif
                    @else
                    <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                    @endif
                </div>
                @if($product->stock_availabity ==2)
                <div class="pro-outof-stock">
                  <h6><?php echo  __('messages.out_of_stock') ?></h6>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <?php $productdatadetails = get_product_details($product->id); ?>
          <div class="product-right">
            <div class="pro-heart">
                @if((\App::getLocale() == 'en'))
               <a href="{{url('brandproducts',base64_encode($product->brand_id))}}"> <h6>{{$product->brandname_en}}</h6></a>
                @else
                <a href="{{url('brandproducts',base64_encode($product->brand_id))}}"> <h6>{{$product->brandname_ar}}</h6></a>
                @endif
            </div>

            <h2>@if((\App::getLocale() == 'en')){{$product->name_en}}@else {{$product->name_ar}}@endif</h2>
                    <?php $rating = checkrating($product->id); ?>
            <div class="reviews-box">
              <ul>
                   @if(isset($rating['avgrating']))
                   @if(ceil($rating['avgrating']) == 5)
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li class="active"><a href="javascript:;"><di class="ri-star-fill"></i></a></li>
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  @endif
                    @if(ceil($rating['avgrating']) == 4)
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  @endif
                    @if(ceil($rating['avgrating']) == 3)
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  @endif
                     @if(ceil($rating['avgrating']) == 2)
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  @endif
                    @if(ceil($rating['avgrating']) == 1)
                  <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                  @endif
                  @endif
              </ul>
              <span class="reviews-point">({{$product_reviews_count}}) <?php echo  __('messages.reviews') ?></span>
            </div>
            <h5><?php echo  __('messages.product_code') ?>: <span>#{{$product->sku_en}}</span></h5>
            @if(!empty($product->discount_available))
            <h5><?php echo  __('messages.discount') ?>: <span>{{$product->discount_available}}% <?php echo  __('messages.off') ?></span></h5>
            @endif
            @if(!empty($product->rewardpoints))
            <h5><?php echo  __('messages.points') ?>: <span>{{$product->rewardpoints}} </span></h5>
            @endif
            @if(!empty($product->offer_price))
            <h1 style ="display:flex; color:red;" >
            @else
            <h1 style ="display:flex">
            @endif
            SR <div id ="changeprice" style ="padding-left: 4px; padding-right: 4px;">{{$product->price}}</div>  <font>@if(!empty($product->offer_price))SR {{$product->offer_price}}@endif</font></h1>
            @if((\App::getLocale() == 'en'))
            @if(!empty($product->tax_class_en))
            <div class="discount-tax-class"><h2>{{$product->tax_class_en}}</h2></div> 
            @endif
            @else
            @if(!empty($product->tax_class_ar))
            <div class="discount-tax-class"><h2>{{$product->tax_class_ar}}</h2></div> 
            @endif
            @endif
                 
            @if(count($product_detail_color)==0)
                    @if(!empty($product_detail) && count($product_detail)>0)
                    <div class="color-selector-circle">
                        <h5 class="color-title"><?php echo  __('messages.color') ?></h5>
                         <div class="color-choices">
                          @foreach($product_detail as $mykey => $record)
                          <div class="custom-control custom-radio">
                          <?php Session::put('mycolor',$product_detail[0]['color']); Session::put('myproduct',$product->id);?>
                          <input type ="hidden" name ="mycolor" id ="mycolor" value="<?php  echo Session::get('mycolor')?>">
                          <input type ="hidden" name ="myproduct" id ="myproduct" value="<?php echo Session::get('myproduct')?>">
                            <div class="check-color-box" style="background:{{$record['color']}};"></div>
                            @if($record['quantity']>0)
                            <input type="radio" class="custom-control-input" id="{{$record['color']}}" onchange="colorselect('{{$record['color']}}','{{$product->id}}')"; value="{{$record['color']}}" <?php if($product_detail[0]['color'] == $record['color']){
                                echo "checked";
                            } ?> name="color">
                            @else
                            <input type="radio" class="custom-control-input" id="{{$record['color']}}" onchange="colorselect('{{$record['color']}}','{{$product->id}}')"; value="{{$record['color']}}" disabled name="color">
                            @endif
                            <input type ="hidden" name="colorvalue" value ="1">
                            <label class="custom-control-label" for="{{$record['color']}}"></label>
                          </div>
                          @endforeach
                        </div>
                    </div>
                    @else
                    <input type ="hidden" name="colorvalue" value ="2">
                    @endif
                    @endif

                    @if(!empty($product_detail_color) && count($product_detail_color)>0)
                       @foreach($options as $key => $option)
                        @if(count($option->productoptions)>0)
                       <div class="custom-radio-mul-sec" style="font-size: 20px;">
                       @if((\App::getLocale() == 'en'))
                         <span>{{$option->name_en}}</span>
                       @else
                         <span>{{$option->name_ar}}</span>
                       @endif
                         <input type ="hidden" name="product_options[]" value="{{$option->name_en}}">
                         <div class="d-flex">
                          @foreach($option->productoptions as $secondkey => $prooptions)
                            <div class="custom-control custom-radio">
                              <?php $opvalue =  prooptionvalues($prooptions->option_value,$product->id);?>
                              @if($opvalue['quantity']>0)
                              <input type="radio" id="{{$prooptions->id}}" onchange ="changeproductprice('{{$opvalue['totalproductprice']}}')" name="{{$option->name_en}}" value="{{$opvalue['value']}}" class="custom-control-input" <?php if($option->productoptions[0]->id==$prooptions->id){echo 'checked';}?>>
                              @else
                              <input type="radio" id="{{$prooptions->id}}" onchange ="changeproductprice('{{$opvalue['totalproductprice']}}')" name="{{$option->name_en}}" value="{{$opvalue['value']}}" class="custom-control-input" disabled>
                              @endif
                              
                              <label class="custom-control-label" for="{{$prooptions->id}}">
                                    <div class="input-space-tx">
                                      <p class="option-size-name">
                                        {{$opvalue['value']}}
                                      </p>
                                      <p class="option-size-price">
                                        SAR {{$opvalue['totalproductprice']}}
                                      </p>
                                      @if($opvalue['quantity']<=0)
                                      <p class="option-size-price" style ="color:red">
                                        <?php echo  __('messages.out_of_stock') ?>
                                      </p>
                                      @endif
                                    </div>
                              </label>
                            </div>
                          @endforeach
                            </div>
                       </div>
                       @endif
                       @endforeach
                       @endif

                       <div id ="op"></div>

                  <div class="quantity-box d-flex line-qyt">
                            <h6><?php echo  __('messages.quantity') ?></h6>
                          @if($product->stock_availabity ==1 && $product->quantity !=0)
                          <div class="form-group">
                              <div class="input-group">
                                  <div class="input-group-btn">
                                      <button id="down" class="btn waves-effect waves-light" onclick="decreasequantitydetail('{{$product->id}}');"><i class="fas fa-minus"></i></button>
                                  </div>
                                  <input type="hidden" id="productquantity" value="{{$product->quantity}}">
                                  <input type="text" id="cart{{$product->id}}" class="form-control input-number" value="<?php if(isset($productdatadetails[$product->id]['req_quantity'])){echo $productdatadetails[$product->id]['req_quantity'];} else {
                                      echo 1;
                                  }?>" readonly>
                                  <div class="input-group-btn">
                                       <button id="up" class="btn waves-effect waves-light" onclick="increasequantitydetail('{{$product->id}}');"><i class="fas fa-plus"></i></button>
                                  </div>
                              </div>
                          </div>
                          @else
                           <div class="form-group">
                              <div class="input-group">
                                  <div class="input-group-btn">
                                      <button class="btn waves-effect waves-light"><i class="fas fa-minus" disabled></i></button>
                                  </div>
                                  <input type="text" class="form-control input-number" value="0" readonly>
                                  <div class="input-group-btn">
                                       <button class="btn waves-effect waves-light" disabled><i class="fas fa-plus"></i></button>
                                  </div>
                              </div>
                          </div>
                          @endif
                        </div>
                        <div class="buy-ad-add">
                          @if($product->stock_availabity ==1 && $product->quantity !=0)
                          <button onclick="addtocartdetail('{{$product->id}}');" id ="addcart{{$product->id}}" class="btn btn-black"> <?php echo  __('messages.add_to_cart') ?></button>
                          <!-- <button type="button" data-toggle="modal" data-target="#my-cart" class="btn btn-coffee"><?php echo  __('messages.buy_now') ?></button> -->
                          @else
                          <button class="btn btn-black" disabled> <?php echo  __('messages.out_of_stock') ?></button>
                          <!-- <button type="button" disabled class="btn btn-coffee"><?php echo  __('messages.buy_now') ?></button> -->
                          @endif
                        </div>

                       

                        @if(!empty($deliveryfeatures))
                        <ul class="delivery-flex">
                        @foreach($deliveryfeatures as $del_features)
                          <li><img src="{{ url('/') }}/public/product_images/{{$del_features->image_en}}"> <h6>
                        @if((\App::getLocale() == 'en'))
                          {{$del_features->title_en}}
                        @else
                          {{$del_features->title_ar}}
                        @endif
                          </h6></li>
                        @endforeach
                        </ul>
                        @endif
                        <div class="share-boxs">
                <a class="btn btn-transparent" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <?php echo  __('messages.share_with_friends') ?><i class="fas fa-angle-double-right"></i>
                </a>
              <div class="collapse" id="collapseExample">
                <div class="card card-body">
                 <ul>
                  <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?=Request::url()?>"><img src="{{asset('assets/front-end/images/fb.svg')}}"></a></li>
                  <!-- <li><a href="javascript:;"><img src="{{asset('assets/front-end/images/tw.svg')}}"></a></li>
                  <li><a href="javascript:;"><img src="{{asset('assets/front-end/images/snapchat.svg')}}"></a></li>
                  <li><a href="javascript:;"><img src="{{asset('assets/front-end/images/insta.svg')}}"></a></li> -->
                  <li><a href="whatsapp://send?text=<?=Request::url()?>" class="btn btn-outline ared-btn waves-effect waves-light"><img src="{{asset('assets/front-end/images/whats.svg')}}"></a></li>
                 </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

            <div class="detail-tabs">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="pills-description-tab" data-toggle="pill" href="#pills-description" role="tab" aria-controls="pills-description" aria-selected="true"><?php echo  __('messages.description') ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-features-tab" data-toggle="pill" href="#pills-features" role="tab" aria-controls="pills-features" aria-selected="false"><?php echo  __('messages.features') ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="pills-reviews-tab" data-toggle="pill" href="#pills-reviews" role="tab" aria-controls="pills-reviews" aria-selected="false"><?php echo  __('messages.reviews') ?></a>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-description" role="tabpanel" aria-labelledby="pills-description-tab">
                <div class="descrip-tb-in">
                @if((\App::getLocale() == 'en'))
                 <?php echo htmlspecialchars_decode($product->description_en);?>
                @else
                 <?php echo htmlspecialchars_decode($product->description_ar);?>
                @endif
                </div>

                @if(!empty($all_similar_products))
                <div class="detail-today-sec mb-6">
                      <div class="title-inner d-flex align-items-center bt_line">
                        <h4 class="m-0"><?php echo  __('messages.similar_products') ?></h4>
                        <a href="{{url('smiliar_products',base64_encode($product->id))}}" class="ml-auto"><?php echo  __('messages.see_all_items') ?></a>
                      </div>
                      <div class="slider-arrow similar-products-slider owl-carousel owl-theme">
                      @foreach($all_similar_products as $similarproduct)
                          <div class="item">
                            <div class="product-box">
                              <figure>
                                <a onclick="Similar_view_item('{{$similarproduct->category_name}}','{{$similarproduct->sub_category_name}}','{{$similarproduct->seo_url}}')">
                                  <img src="{{ url('/') }}/public/product_images/{{$similarproduct->img}}">
                                </a>
                                @if(!empty($similarproduct->discount_available))
                                <span>{{$similarproduct->discount_available}}% <?php echo  __('messages.off') ?></span>
                                @endif
                              </figure>
                             <figcaption>
                                <span class="pro-brand"><a href="{{url('brandproducts',base64_encode($similarproduct->brand_id))}}">@if((\App::getLocale() == 'en')){{$similarproduct->brandname_en}}@else {{$similarproduct->brandname_ar}} @endif</a></span>
                               <h4 class="pro-name"><a onclick="Similar_view_item('{{$similarproduct->category_name}}','{{$similarproduct->sub_category_name}}','{{$similarproduct->seo_url}}')">@if((\App::getLocale() == 'en')){{$similarproduct->name_en}}@else {{$similarproduct->name_ar}} @endif</a></h4>
                                @if(!empty($similarproduct->offer_price))
                                <div class="pr-rate d-flex ">
                                @else
                                <div class="pr-rate d-flex " id ="offerclass">
                                @endif
                                <h4>SR {{$similarproduct->price}} <font>@if(!empty($similarproduct->offer_price))SR {{$similarproduct->offer_price}}@endif</font></h4>
                                 <?php $rating = checkrating($similarproduct->id); ?>
                                  <div class="rat-pro">
                                    @if(isset($rating['avgrating']))
                                    @if(ceil($rating['avgrating']) == 5)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 4)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 3)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 2)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 1)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @endif

                                  </div>
                              </div>
                              <div class="pro-btns">
                                @if($similarproduct->stock_availabity ==1 && $similarproduct->quantity >0)
                                <?php $getcolorstatus = getcolor($similarproduct->id);?>
                                 @if($getcolorstatus==0)
                                <button onclick="addtocart('{{$similarproduct->id}}');" id ="addcart{{$similarproduct->id}}" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></button>
                                @else
                                <a onclick="Similar_view_item('{{$similarproduct->category_name}}','{{$similarproduct->sub_category_name}}','{{$similarproduct->seo_url}}')" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></a>
                                @endif
                                <?php $iswishlist = getwishlist($similarproduct->id); ?>
                                <button id="btnTest" style="display:none;"data-toggle="modal" data-target="#my-cart">Go To Cart</button>
                                @if(!empty(Auth::id()))
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @else
                                <button class="btn btn-border-brown" disabled><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.out_of_stock') ?></button>
                                @if(!empty(Auth::id()))
                                <?php $iswishlist = getwishlist($similarproduct->id); ?>
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @endif
                              </div>
                            </figcaption>
                            @if($similarproduct->stock_availabity ==2 || $similarproduct->quantity ==0)
                            <div class="pro-outof-stock">
                              <h6><?php echo  __('messages.out_of_stock') ?></h6>
                            </div>
                            @endif
                            </div>
                          </div>
                      @endforeach
                      </div>
                </div>
                @endif
                @if(count($all_recently_products)>4)
                <div class="detail-today-sec mb-6">
                      <div class="title-inner d-flex align-items-center">
                        <h4 class="m-0"><?php echo  __('messages.recently_viewed') ?></h4>
                        <a href="{{url('allproducts')}}" class="ml-auto"><?php echo  __('messages.see_all_items') ?></a>
                      </div>
                      <div class="slider-arrow similar-products-slider owl-carousel owl-theme">
                          @foreach($all_recently_products as $recentproduct)
                          <div class="item">
                            <div class="product-box">
                              <figure>
                               
                                <a onclick="Similar_view_item('{{$recentproduct->category_name}}','{{$recentproduct->sub_category_name}}','{{$recentproduct->seo_url}}')">
                                  <img src="{{ url('/') }}/public/product_images/{{$recentproduct->img}}">
                                </a>
                                @if(!empty($recentproduct->discount_available))
                                <span>{{$recentproduct->discount_available}}% <?php echo  __('messages.off') ?></span>
                                @endif
                              </figure>
                               <figcaption>
                                <span class="pro-brand"><a href="{{url('brandproducts',base64_encode($recentproduct->brand_id))}}">@if((\App::getLocale() == 'en')){{$recentproduct->brandname_en}}@else {{$recentproduct->brandname_ar}} @endif</a></span>
                                <h4 class="pro-name"><a onclick="Similar_view_item('{{$recentproduct->category_name}}','{{$recentproduct->sub_category_name}}','{{$recentproduct->seo_url}}')">@if((\App::getLocale() == 'en')){{$recentproduct->name_en}}@else {{$recentproduct->name_ar}} @endif</a></h4>
                                @if(!empty($recentproduct->offer_price))
                                <div class="pr-rate d-flex ">
                                @else
                                <div class="pr-rate d-flex" id ="offerclass">
                                @endif
                                <h4>SR {{$recentproduct->price}} <font>@if(!empty($recentproduct->offer_price))SR {{$recentproduct->offer_price}}@endif</font></h4>
                                   <?php $rating = checkrating($recentproduct->id); ?>
                                  <div class="rat-pro">
                                    @if(isset($rating['avgrating']))
                                    @if(ceil($rating['avgrating']) == 5)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 4)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 3)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 2)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 1)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @endif
                                  </div>
                              </div>
                              <div class="pro-btns">
                                @if($recentproduct->stock_availabity ==1 && $recentproduct->quantity >0)
                                <?php $getcolorstatus = getcolor($recentproduct->id);?>
                                 @if($getcolorstatus==0)
                                <button onclick="addtocart('{{$recentproduct->id}}');" id ="addcart{{$recentproduct->id}}" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></button>
                                @else
                                <a onclick="Similar_view_item('{{$recentproduct->category_name}}','{{$recentproduct->sub_category_name}}','{{$recentproduct->seo_url}}')" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></a>
                                @endif
                                <?php $iswishlist = getwishlist($recentproduct->id); ?>
                                <button id="btnTest" style="display:none;"data-toggle="modal" data-target="#my-cart"><?php echo  __('messages.go_to_cart') ?></button>
                                @if(!empty(Auth::id()))
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @else
                                <button class="btn btn-border-brown" disabled><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.out_of_stock') ?></button>
                                @if(!empty(Auth::id()))
                                <?php $iswishlist = getwishlist($recentproduct->id); ?>
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @endif
                              </div>
                            </figcaption>
                            @if($recentproduct->stock_availabity ==2 || $recentproduct->quantity ==0)
                            <div class="pro-outof-stock">
                              <h6><?php echo  __('messages.out_of_stock') ?></h6>
                            </div>
                            @endif
                            </div>
                          </div>
                        @endforeach
                      </div>
                </div>
                @endif
          </div>
          <div class="tab-pane fade" id="pills-features" role="tabpanel" aria-labelledby="pills-features-tab">
                <div class="features-tb-in">
                  <ul class="feat_points">
                  @foreach($product_attribute as $attributes)
                  @if((\App::getLocale() == 'en'))
                    <li><h6>{{$attributes->attributename_en}}</h6> <span>{{$attributes->name_en}}</span></li>
                  @else
                    <li><h6>{{$attributes->attributename_ar}}</h6> <span>{{$attributes->name_ar}}</span></li>
                  @endif
                  @endforeach
                  </ul>
                    @if(!empty($all_similar_products))
                <div class="detail-today-sec mb-6">
                      <div class="title-inner d-flex align-items-center bt_line">
                        <h4 class="m-0"><?php echo  __('messages.similar_products') ?></h4>
                        <a href="{{url('smiliar_products',base64_encode($product->id))}}" class="ml-auto"><?php echo  __('messages.see_all_items') ?></a>
                      </div>
                      <div class="slider-arrow similar-products-slider owl-carousel owl-theme">
                      @foreach($all_similar_products as $similarproduct)
                          <div class="item">
                            <div class="product-box">
                              <figure>
                                <a onclick="Similar_view_item('{{$similarproduct->category_name}}','{{$similarproduct->sub_category_name}}','{{$similarproduct->seo_url}}')">
                                  <img src="{{ url('/') }}/public/product_images/{{$similarproduct->img}}">
                                </a>
                                @if(!empty($similarproduct->discount_available))
                                <span>{{$similarproduct->discount_available}}% <?php echo  __('messages.off') ?></span>
                                @endif
                              </figure>
                             <figcaption>
                                <span class="pro-brand"><a href="{{url('brandproducts',base64_encode($similarproduct->brand_id))}}">@if((\App::getLocale() == 'en')){{$similarproduct->brandname_en}}@else {{$similarproduct->brandname_ar}} @endif</a></span>
                               <h4 class="pro-name"><a onclick="Similar_view_item('{{$similarproduct->category_name}}','{{$similarproduct->sub_category_name}}','{{$similarproduct->seo_url}}')">@if((\App::getLocale() == 'en')){{$similarproduct->name_en}}@else {{$similarproduct->name_ar}} @endif</a></h4>
                                @if(!empty($similarproduct->offer_price))
                                <div class="pr-rate d-flex ">
                                @else
                                <div class="pr-rate d-flex" id ="offerclass">
                                @endif
                                <h4>SR {{$similarproduct->price}} <font>@if(!empty($similarproduct->offer_price))SR {{$similarproduct->offer_price}}@endif</font></h4>
                                 <?php $rating = checkrating($similarproduct->id); ?>
                                  <div class="rat-pro">
                                    @if(isset($rating['avgrating']))
                                    @if(ceil($rating['avgrating']) == 5)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 4)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 3)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 2)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 1)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @endif

                                  </div>
                              </div>
                              <div class="pro-btns">
                                @if($similarproduct->stock_availabity ==1 && $similarproduct->quantity >0)
                                <?php $getcolorstatus = getcolor($similarproduct->id);?>
                                 @if($getcolorstatus==0)
                                <button onclick="addtocart('{{$similarproduct->id}}');" id ="addcart{{$similarproduct->id}}" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></button>
                                @else
                                <a onclick="Similar_view_item('{{$similarproduct->category_name}}','{{$similarproduct->sub_category_name}}','{{$similarproduct->seo_url}}')" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></a>
                                @endif
                                <?php $iswishlist = getwishlist($similarproduct->id); ?>
                                <button id="btnTest" style="display:none;"data-toggle="modal" data-target="#my-cart">Go To Cart</button>
                                @if(!empty(Auth::id()))
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @else
                                <button class="btn btn-border-brown" disabled><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.out_of_stock') ?></button>
                                @if(!empty(Auth::id()))
                                <?php $iswishlist = getwishlist($similarproduct->id); ?>
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @endif
                              </div>
                            </figcaption>
                            @if($similarproduct->stock_availabity ==2 || $similarproduct->quantity ==0)
                            <div class="pro-outof-stock">
                              <h6><?php echo  __('messages.out_of_stock') ?></h6>
                            </div>
                            @endif
                            </div>
                          </div>
                      @endforeach
                      </div>
                </div>
                @endif
                @if(count($all_recently_products)>4)
                <div class="detail-today-sec mb-6">
                      <div class="title-inner d-flex align-items-center">
                        <h4 class="m-0"><?php echo  __('messages.recently_viewed') ?></h4>
                        <a href="{{url('allproducts')}}" class="ml-auto"><?php echo  __('messages.see_all_items') ?></a>
                      </div>
                      <div class="slider-arrow similar-products-slider owl-carousel owl-theme">
                          @foreach($all_recently_products as $recentproduct)
                          <div class="item">
                            <div class="product-box">
                              <figure>
                                <a onclick="Similar_view_item('{{$recentproduct->category_name}}','{{$recentproduct->sub_category_name}}','{{$recentproduct->seo_url}}')">
                                  <img src="{{ url('/') }}/public/product_images/{{$recentproduct->img}}">
                                </a>
                                @if(!empty($recentproduct->discount_available))
                                <span>{{$recentproduct->discount_available}}% <?php echo  __('messages.off') ?></span>
                                @endif
                              </figure>
                               <figcaption>
                                <span class="pro-brand"><a href="{{url('brandproducts',base64_encode($recentproduct->brand_id))}}">@if((\App::getLocale() == 'en')){{$recentproduct->brandname_en}}@else {{$recentproduct->brandname_ar}} @endif</a></span>
                                <h4 class="pro-name"><a onclick="Similar_view_item('{{$recentproduct->category_name}}','{{$recentproduct->sub_category_name}}','{{$recentproduct->seo_url}}')">@if((\App::getLocale() == 'en')){{$recentproduct->name_en}}@else {{$recentproduct->name_ar}} @endif</a></h4>
                                @if(!empty($recentproduct->offer_price))
                                <div class="pr-rate d-flex ">
                                @else
                                <div class="pr-rate d-flex " id ="offerclass">
                                @endif
                                <h4>SR {{$recentproduct->price}} <font>@if(!empty($recentproduct->offer_price))SR {{$recentproduct->offer_price}}@endif</font></h4>
                                   <?php $rating = checkrating($recentproduct->id); ?>
                                  <div class="rat-pro">
                                    @if(isset($rating['avgrating']))
                                    @if(ceil($rating['avgrating']) == 5)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 4)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 3)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 2)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 1)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @endif
                                  </div>
                              </div>
                              <div class="pro-btns">
                                @if($recentproduct->stock_availabity ==1 && $recentproduct->quantity >0)
                                <?php $getcolorstatus = getcolor($recentproduct->id);?>
                                 @if($getcolorstatus==0)
                                <button onclick="addtocart('{{$recentproduct->id}}');" id ="addcart{{$recentproduct->id}}" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></button>
                                @else
                                <a href="{{url('productdetails',base64_encode($recentproduct->id))}}" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></a>
                                @endif
                                <?php $iswishlist = getwishlist($recentproduct->id); ?>
                                <button id="btnTest" style="display:none;"data-toggle="modal" data-target="#my-cart"><?php echo  __('messages.go_to_cart') ?></button>
                                @if(!empty(Auth::id()))
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @else
                                <button class="btn btn-border-brown" disabled><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.out_of_stock') ?></button>
                                @if(!empty(Auth::id()))
                                <?php $iswishlist = getwishlist($recentproduct->id); ?>
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @endif
                              </div>
                            </figcaption>
                            @if($recentproduct->stock_availabity ==2 || $recentproduct->quantity ==0)
                            <div class="pro-outof-stock">
                              <h6><?php echo  __('messages.out_of_stock') ?></h6>
                            </div>
                            @endif
                            </div>
                          </div>
                        @endforeach
                      </div>
                </div>
                @endif
                </div>
          </div>
          <div class="tab-pane fade" id="pills-reviews" role="tabpanel" aria-labelledby="pills-reviews-tab">
              <div class="reviews-tb-in">
                  <h5><?php echo  __('messages.all_reviews_and_ratings') ?> ({{$product_reviews_count}})</h5>
                  @foreach($product_reviews as $rating_record)
                  <div class="rating-bx">
                    <!-- <figure><img src="{{asset('assets/front-end/images/rate-user.png')}}"></figure> -->
                    <figcaption>
                        <div class="reviews-box">
                            <h6>{{$rating_record->name}}</h6>
                    <ul>
                      @if(isset($rating_record->rating))
                      @if($rating_record->rating == 5)
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      @endif
                       @if($rating_record->rating == 4)
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      @endif
                       @if($rating_record->rating == 3)
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      @endif
                       @if($rating_record->rating == 2)
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      @endif
                       @if($rating_record->rating == 1)
                      <li class="active"><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      <li><a href="javascript:;"><i class="ri-star-fill"></i></a></li>
                      @endif
                      @endif
                    </ul>
                  </div>
                  <p>{{$rating_record->review}}</p>
                  <?php $reviewimages = explode(',',$rating_record->images) ?>
                  @if(!empty($reviewimages[0]))
                    <ul class="reviews-multi-img">
                      @foreach($reviewimages as $reviewimg)
                       <li>
                         <figure><img src="{{ url('/') }}/public/reviewcomment/{{$reviewimg}}"></figure>
                       </li>
                      @endforeach
                    </ul>
                  @endif
                    </figcaption>
                    <span class="rt-date">{{$rating_record->created_at}}</span>
                  </div>
                  @endforeach
                  <!-- <div class="see_more">
                    <a href="javascript:;">See More</a>
                  </div> -->
                  @if(!empty(Auth::id()))
                 <form id="review" method ="POST" action ="{{url('addreview')}}" enctype="multipart/form-data">
                 @csrf
                  <div class="write-review-sec">
                        <div class="title-detail">
                          <h4><?php echo  __('messages.write_a_review') ?></h4>
                          <span class="drop-line drop-full-line"></span>
                        </div>
                      <div class="reviews-box">
                        <div class="star-rating">
                          <input id="star-5" type="radio" name="rating" value="5" />
                          <label for="star-5" title="5 stars">
                            <i class="active fa fa-star" aria-hidden="true"></i>
                          </label>
                          <input id="star-4" type="radio" name="rating" value="4" />
                          <label for="star-4" title="4 stars">
                            <i class="active fa fa-star" aria-hidden="true"></i>
                          </label>
                          <input id="star-3" type="radio" name="rating" value="3" />
                          <label for="star-3" title="3 stars">
                            <i class="active fa fa-star" aria-hidden="true"></i>
                          </label>
                          <input id="star-2" type="radio" name="rating" value="2" />
                          <label for="star-2" title="2 stars">
                            <i class="active fa fa-star" aria-hidden="true"></i>
                          </label>
                          <input id="star-1" type="radio" name="rating" value="1" />
                          <label for="star-1" title="1 star">
                            <i class="active fa fa-star" aria-hidden="true"></i>
                          </label>
                           @if($errors->has('rating'))
                              <span id="title-error" class="error text-danger">{{ $errors->first('rating') }}</span>
                           @endif
                        </div>
                    </div>
                        <div class="review-inputs">
                              <div class="row">
                                <div class="col-md-12">
                                   <div class="form-group">
                                    <input type="text" name ="name" class="form-control" placeholder="<?php echo  __('messages.name') ?>">
                                     @if($errors->has('name'))
                                      <span id="title-error" class="error text-danger">{{ $errors->first('name') }}</span>
                                     @endif
                                  </div>
                                </div>
                                    <input type ="hidden" name="product_id" value ="{{$product->id}}">
                                <div class="col-md-12">
                                   <div class="form-group">
                                     <textarea type="text" name="review" class="form-control" placeholder="<?php echo  __('messages.write_a_comment') ?>"></textarea>
                                      @if($errors->has('review'))
                                      <span id="title-error" class="error text-danger">{{ $errors->first('review') }}</span>
                                      @endif
                                   </div>
                                </div>

                                 <div class="col-md-12">
                                        <div class="grid-x grid-padding-x">
                                          <div class="small-10 small-offset-1 medium-8 medium-offset-2 cell">
                                              <div class="add-img-opt">
                                                <label for="upload_imgs" class="button hollow"><i class="ri-add-fill"></i></label>
                                                <input class="show-for-sr" onchange="addreviewimages(event,this)" type="file" id="upload_imgs" name="upload_imgs[]" multiple/>
                                              </div>
                                              <div class="quote-imgs-thumbs quote-imgs-thumbs--hidden" id="img_preview" aria-live="polite"></div>

                                          </div>
                                        </div>
                                 </div>

                                <div class="col-md-12">
                                  <div class="space-pdd">
                                     <button type ="submit" class="btn btn-black"><?php echo  __('messages.submit') ?></button>
                                  </div>
                                </div>
                              </div>
                        </div>
                      </div>
                     </form>
                     @endif
                         @if(!empty($all_similar_products))
                    <div class="detail-today-sec mb-6">
                      <div class="title-inner d-flex align-items-center bt_line">
                        <h4 class="m-0"><?php echo  __('messages.similar_products') ?></h4>
                        <a href="{{url('smiliar_products',base64_encode($product->id))}}" class="ml-auto"><?php echo  __('messages.see_all_items') ?></a>
                      </div>
                      <div class="slider-arrow similar-products-slider owl-carousel owl-theme">
                      @foreach($all_similar_products as $similarproduct)
                          <div class="item">
                            <div class="product-box">
                              <figure>
                                <a onclick="Similar_view_item('{{$similarproduct->category_name}}','{{$similarproduct->sub_category_name}}','{{$similarproduct->seo_url}}')">
                                  <img src="{{ url('/') }}/public/product_images/{{$similarproduct->img}}">
                                </a>
                                @if(!empty($similarproduct->discount_available))
                                <span>{{$similarproduct->discount_available}}% <?php echo  __('messages.off') ?></span>
                                @endif
                              </figure>
                             <figcaption>
                                <span class="pro-brand"><a href="{{url('brandproducts',base64_encode($similarproduct->brand_id))}}">@if((\App::getLocale() == 'en')){{$similarproduct->brandname_en}}@else {{$similarproduct->brandname_ar}} @endif</a></span>
                               <h4 class="pro-name"><a onclick="Similar_view_item('{{$similarproduct->category_name}}','{{$similarproduct->sub_category_name}}','{{$similarproduct->seo_url}}')">@if((\App::getLocale() == 'en')){{$similarproduct->name_en}}@else {{$similarproduct->name_ar}} @endif</a></h4>
                                @if(!empty($similarproduct->offer_price))
                                <div class="pr-rate d-flex ">
                                @else
                                <div class="pr-rate d-flex " id ="offerclass">
                                @endif
                                <h4>SR {{$similarproduct->price}} <font>@if(!empty($similarproduct->offer_price))SR {{$similarproduct->offer_price}}@endif</font></h4>
                                 <?php $rating = checkrating($similarproduct->id); ?>
                                  <div class="rat-pro">
                                    @if(isset($rating['avgrating']))
                                    @if(ceil($rating['avgrating']) == 5)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 4)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 3)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 2)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 1)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @endif

                                  </div>
                              </div>
                              <div class="pro-btns">
                                @if($similarproduct->stock_availabity ==1 && $similarproduct->quantity >0)
                                <?php $getcolorstatus = getcolor($similarproduct->id);?>
                                 @if($getcolorstatus==0)
                                <button onclick="addtocart('{{$similarproduct->id}}');" id ="addcart{{$similarproduct->id}}" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></button>
                                @else
                                <a onclick="Similar_view_item('{{$similarproduct->category_name}}','{{$similarproduct->sub_category_name}}','{{$similarproduct->seo_url}}')" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></a>
                                @endif
                                <?php $iswishlist = getwishlist($similarproduct->id); ?>
                                <button id="btnTest" style="display:none;"data-toggle="modal" data-target="#my-cart">Go To Cart</button>
                                @if(!empty(Auth::id()))
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @else
                                <button class="btn btn-border-brown" disabled><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.out_of_stock') ?></button>
                                @if(!empty(Auth::id()))
                                <?php $iswishlist = getwishlist($similarproduct->id); ?>
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$similarproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @endif
                              </div>
                            </figcaption>
                            @if($similarproduct->stock_availabity ==2 || $similarproduct->quantity ==0)
                            <div class="pro-outof-stock">
                              <h6><?php echo  __('messages.out_of_stock') ?></h6>
                            </div>
                            @endif
                            </div>
                          </div>
                      @endforeach
                      </div>
                </div>
                @endif
                @if(count($all_recently_products)>4)
                <div class="detail-today-sec mb-6">
                      <div class="title-inner d-flex align-items-center">
                        <h4 class="m-0"><?php echo  __('messages.recently_viewed') ?></h4>
                        <a href="{{url('allproducts')}}" class="ml-auto"><?php echo  __('messages.see_all_items') ?></a>
                      </div>
                      <div class="slider-arrow similar-products-slider owl-carousel owl-theme">
                          @foreach($all_recently_products as $recentproduct)
                          <div class="item">
                            <div class="product-box">
                              <figure>
                                <a onclick="Similar_view_item('{{$recentproduct->category_name}}','{{$recentproduct->sub_category_name}}','{{$recentproduct->seo_url}}')">
                                  <img src="{{ url('/') }}/public/product_images/{{$recentproduct->img}}">
                                </a>
                                @if(!empty($recentproduct->discount_available))
                                <span>{{$recentproduct->discount_available}}% <?php echo  __('messages.off') ?></span>
                                @endif
                              </figure>
                               <figcaption>
                                <span class="pro-brand"><a href="{{url('brandproducts',base64_encode($recentproduct->brand_id))}}">@if((\App::getLocale() == 'en')){{$recentproduct->brandname_en}}@else {{$recentproduct->brandname_ar}} @endif</a></span>
                                <h4 class="pro-name"><a onclick="Similar_view_item('{{$recentproduct->category_name}}','{{$recentproduct->sub_category_name}}','{{$recentproduct->seo_url}}')">@if((\App::getLocale() == 'en')){{$recentproduct->name_en}}@else {{$recentproduct->name_ar}} @endif</a></h4>
                                @if(!empty($recentproduct->offer_price))
                                <div class="pr-rate d-flex ">
                                @else
                                <div class="pr-rate d-flex " id ="offerclass">
                                @endif
                                <h4>SR {{$recentproduct->price}} <font>@if(!empty($recentproduct->offer_price))SR {{$recentproduct->offer_price}}@endif</font></h4>
                                   <?php $rating = checkrating($recentproduct->id); ?>
                                  <div class="rat-pro">
                                    @if(isset($rating['avgrating']))
                                    @if(ceil($rating['avgrating']) == 5)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 4)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 3)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 2)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @if(ceil($rating['avgrating']) == 1)
                                    <i class="ri-star-s-fill active"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    <i class="ri-star-s-fill"></i>
                                    @endif
                                    @endif
                                  </div>
                              </div>
                              <div class="pro-btns">
                                @if($recentproduct->stock_availabity ==1 && $recentproduct->quantity >0)
                                <?php $getcolorstatus = getcolor($recentproduct->id);?>
                                 @if($getcolorstatus==0)
                                <button onclick="addtocart('{{$recentproduct->id}}');" id ="addcart{{$recentproduct->id}}" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></button>
                                @else
                                <a onclick="Similar_view_item('{{$recentproduct->category_name}}','{{$recentproduct->sub_category_name}}','{{$recentproduct->seo_url}}')" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></a>
                                @endif
                                <?php $iswishlist = getwishlist($recentproduct->id); ?>
                                <button id="btnTest" style="display:none;"data-toggle="modal" data-target="#my-cart"><?php echo  __('messages.go_to_cart') ?></button>
                                @if(!empty(Auth::id()))
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @else
                                <button class="btn btn-border-brown" disabled><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.out_of_stock') ?></button>
                                @if(!empty(Auth::id()))
                                <?php $iswishlist = getwishlist($recentproduct->id); ?>
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$recentproduct->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @endif
                              </div>
                            </figcaption>
                            @if($recentproduct->stock_availabity ==2 || $recentproduct->quantity ==0)
                            <div class="pro-outof-stock">
                              <h6><?php echo  __('messages.out_of_stock') ?></h6>
                            </div>
                            @endif
                            </div>
                          </div>
                        @endforeach
                      </div>
                </div>
                @endif
              </div>
          </div>
        </div>
      </div>

          </div>
        </section>
<script>
var imgUpload = document.getElementById('upload_imgs')
  , imgPreview = document.getElementById('img_preview')
  , imgUploadForm = document.getElementById('img-upload-form')
  , totalFiles
  , previewTitle
  , previewTitleText
  , img;

imgUpload.addEventListener('change', previewImgs, false);
imgUploadForm.addEventListener('submit', function (e) {
  e.preventDefault();
  alert('Images Uploaded! (not really, but it would if this was on your website)');
}, false);

function previewImgs(event) {
  totalFiles = imgUpload.files.length;

  if(!!totalFiles) {
    imgPreview.classList.remove('quote-imgs-thumbs--hidden');
    previewTitle = document.createElement('p');
    previewTitle.style.fontWeight = 'bold';
    previewTitleText = document.createTextNode(totalFiles + ' Total Images Selected');
    previewTitle.appendChild(previewTitleText);
    imgPreview.appendChild(previewTitle);
  }

  for(var i = 0; i < totalFiles; i++) {
    img = document.createElement('img');
    img.src = URL.createObjectURL(event.target.files[i]);
    img.classList.add('img-preview-thumb');
    imgPreview.appendChild(img);
  }
}
</script>
<script>

function changeproductprice(price)
{
          document.getElementById("changeprice").innerHTML = price;
}

</script>

<script>
   var form_data = new FormData();
    var turntableId = 0;
    function addreviewimages(e, thisIs) {
        var len_files = $(thisIs).prop("files").length;
        console.log(len_files);
        for (var i = 0; i < len_files; i++) {
            var file_data = $(thisIs).prop("files")[i];
            form_data.append("upload_imgs[]", file_data);
        }
    }
</script>
<script>
function colorselect(id1,id2)
{

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: basrurl + '/coloroptions',
        data: { id1: id1, id2:id2 },
        success: function (data) {
          document.getElementById("op").innerHTML = data.message;
          if(typeof data.price != 'undefined')
          {
          document.getElementById("changeprice").innerHTML = data.price;
          }

        }

    });


}
</script>

@endsection
