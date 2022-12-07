@extends('layouts.front-app')

@section('content')
<section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_wishlist') ?></a></li>
              </ul>
            </nav>
          </div>
        </section>

 <section class="next-dash">
          <div class="container">
              <!-- ----inner-dashboard----- -->
              <div class="inner-dash-bord">
                <div class="dashboard-left">
                  <nav class="navbar navbar-expand-lg">
                    <!-- Collapse button -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- Collapsible content -->
                    <div class="collapse navbar-collapse" id="sidebar-menu">
                      <ul class="navbar-nav">
                        @if(!empty(Auth::id()))
                        <li class="nav-item">
                          <a class="nav-link" href="{{url('myaccount')}}">
                            <figure><img src="{{asset('assets/front-end/images/my-account.svg')}}"></figure>
                            <h6><?php echo  __('messages.my_account') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{url('myorder')}}">
                            <figure><img src="{{asset('assets/front-end/images/my-orders.svg')}}"></figure>
                            <h6><?php echo  __('messages.my_orders') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{url('myaddress')}}">
                            <figure><img src="{{asset('assets/front-end/images/my-address.svg')}}"></figure>
                            <h6><?php echo  __('messages.my_address') ?></h6>
                          </a>
                        </li>
                         <li class="nav-item">
                          <a class="nav-link" href="{{url('mywallet')}}">
                            <figure><i class="ri-wallet-3-line"></i></figure>
                            <h6><?php echo  __('messages.my_wallet') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{url('mygifts')}}">
                            <figure><i class="ri-gift-2-line"></i></figure>
                            <h6><?php echo  __('messages.my_gift') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a class="nav-link" href="{{url('myrewards')}}">
                            <figure><i class="ri-money-dollar-box-line"></i></figure>
                            <h6><?php echo  __('messages.my_reward') ?></h6>
                          </a>
                        </li>
                        @endif
                        <li class="nav-item active">
                          <a class="nav-link" href="{{url('wishlist')}}">
                            <figure><img src="{{asset('assets/front-end/images/my-wishlist.svg')}}"></figure>
                            <h6><?php echo  __('messages.my_wishlist') ?></h6>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </nav>
                </div>
                <div class="dashboard-right">
                    <div class="dash-titlenav">
                      <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                          <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_wishlist') ?></a></li>
                        </ul>
                      </nav>
                    </div>
                    <div class="dash-credit-sec">
                          <div class="my-location-bx pt-0">
                                <div class="row wsh-lists">
                                  @if(count($products)>0)
                                  @foreach($products as $product)
                                  <div class="col-md-4">
                                      <div class="product-box">
                                        <figure>
                                          <a href="{{url($product->category_name.'/'.$product->sub_category_name.'/'.$product->seo_url)}}">
                                            <img src="{{ url('/') }}/public/product_images/{{$product->img}}">
                                          </a>
                                          @if(!empty($product->discount_available))
                                          <span>{{$product->discount_available}}% <?php echo  __('messages.off') ?></span>
                                          @endif
                                        </figure>
                                       <figcaption>
                                          <span class="pro-brand"><a href="{{url($product->category_name.'/'.$product->sub_category_name.'/'.$product->seo_url)}}">@if((\App::getLocale() == 'en')){{$product->brandname_en}}@else {{$product->brandname_ar}} @endif</a></span>
                                          <h4 class="pro-name"><a href="{{url($product->category_name.'/'.$product->sub_category_name.'/'.$product->seo_url)}}">@if((\App::getLocale() == 'en')){{$product->name_en}}@else {{$product->name_ar}} @endif</a></h4>
                                          @if(!empty($product->offer_price))
                                          <div class="pr-rate d-flex">
                                          @else
                                          <div class="pr-rate d-flex" id ="offerclass">
                                          @endif
                                          <h4>SR {{$product->price}} <font>@if(!empty($product->offer_price))SR {{$product->offer_price}}@endif</font></h4>
                                         <?php $rating = checkrating($product->id); ?>
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
                                          @if($product->stock_availabity ==1 && $product->quantity >0)
                                          <?php $getcolorstatus = getcolor($product->id);?>
                                           @if($getcolorstatus==0)
                                          <button onclick="addtocart('{{$product->id}}');" id ="addcart{{$product->id}}" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></button>
                                          @else
                                          <a href="{{url($product->category_name.'/'.$product->sub_category_name.'/'.$product->seo_url)}}" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></a>
                                          @endif
                                          <?php $iswishlist = getwishlist($product->id); ?>
                                          <button id="btnTest" style="display:none;"data-toggle="modal" data-target="#my-cart">Go To Cart</button>
                                          @if(!empty(Auth::id()))
                                          @if(!empty($iswishlist))
                                          <a onclick="remove_to_wishlist('{{$product->id}}','{{$product->name_en}}','{{auth()->user()}}')"  class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></a>
                                          @else
                                          <a onclick="remove_to_wishlist('{{$product->id}}','{{$product->name_en}}','{{auth()->user()}}')"  class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></a>
                                          @endif
                                          @else
                                          <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                          @endif
                                          @else
                                          <button class="btn btn-border-brown" disabled><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></button>
                                            @if(!empty(Auth::id()))
                                            <a onclick="remove_to_wishlist('{{$product->id}}','{{$product->name_en}}','{{auth()->user()}}')"  class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></a>
                                            @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black"><i class="ri-heart-fill"></i></a>
                                            @endif
                                          @endif
                                        </div>
                                      </figcaption>
                                      @if($product->stock_availabity ==2 || $product->quantity ==0)
                                      <div class="pro-outof-stock">
                                        <h6><?php echo  __('messages.out_of_stock') ?></h6>
                                      </div>
                                      @endif
                                      </div>
                                  </div>
                                  @endforeach
                                  @else
                                  <div class="container">
                                      <div class="no-avilable">
                                        <figure><img src="{{asset('assets/front-end/images/no-producat.svg')}}"></figure>
                                        <figcaption>
                                          <h4><?php echo  __('messages.no_product_available') ?></h4>
                                        </figcaption>
                                      </div>
                                  </div>
                                  @endif
                                </div>
                          </div>
                    </div>
                </div>
              </div>
              <!-- ----inner-dashboard----- -->
          </div>
        </section>
@endsection
