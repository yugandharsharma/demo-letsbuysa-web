@extends('layouts.front-app')

@section('content')
    <section class="home-banner mb-4">
        <div class="container">
            <div class="row ml-0 mr-0">
                <div class="col-md-8 banner-slider-out">
                    <div class="banner-slider owl-carousel owl-theme">
                        @if (!empty($banners))
                            @foreach ($banners as $banner)
                                <div class="item">
                                    <a href="{{ $banner->url }}">
                                        @if (\App::getLocale() == 'en')
                                            <img src="{{ url('/') }}/public/images/banners/{{ $banner->image_en }}">
                                        @else
                                            <img src="{{ url('/') }}/public/images/banners/{{ $banner->image_ar }}">
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="item">
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/front-end/images/home-banner2.jpg') }}">
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 small-banners">
                    <ul class="small-banners-in">
                        @if (!empty($sidebanners))
                            @foreach ($sidebanners as $sidebanner)
                                <li>
                                    <a href="{{ $sidebanner->url }}">
                                        @if (\App::getLocale() == 'en')
                                            <img
                                                src="{{ url('/') }}/public/images/side-banner/{{ $sidebanner->image_en }}">
                                        @else
                                            <img
                                                src="{{ url('/') }}/public/images/side-banner/{{ $sidebanner->image_ar }}">
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/front-end/images/small-banner1.jpg') }}">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/front-end/images/small-banner2.jpg') }}">
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="brand-logos mb-6">
        <div class="container">
            <div class="title-inner d-flex align-items-center">

                <h4 class="m-0"><?php echo __('messages.shop_brand'); ?> </h4>

                <a href="{{ url('brands') }}" class="ml-auto"><?php echo __('messages.see_all_items'); ?></a>
            </div>
            <div class="slider-arrow brand-logos-slider owl-carousel owl-theme">
                @if (!empty($topbrands))
                    @foreach ($topbrands as $topbrand)
                        <div class="item brand-logos-item">
                            <a href="{{ url('brandproducts', base64_encode($topbrand->id)) }}">
                                @if (\App::getLocale() == 'en')
                                    <img src="{{ url('/') }}/public/images/brands/{{ $topbrand->image_en }}">
                                @else
                                    <img src="{{ url('/') }}/public/images/brands/{{ $topbrand->image_ar }}">
                                @endif
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="item brand-logos-item">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/brand2.jpg') }}">
                        </a>
                    </div>
                    <div class="item brand-logos-item">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/brand3.jpg') }}">
                        </a>
                    </div>
                    <div class="item brand-logos-item">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/brand4.jpg') }}">
                        </a>
                    </div>
                    <div class="item brand-logos-item">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/brand5.jpg') }}">
                        </a>
                    </div>
                    <div class="item brand-logos-item">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/brand6.jpg') }}">
                        </a>
                    </div>
                    <div class="item brand-logos-item">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/brand4.jpg') }}">
                        </a>
                    </div>
                    <div class="item brand-logos-item">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/brand5.jpg') }}">
                        </a>
                    </div>
                    <div class="item brand-logos-item">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/brand6.jpg') }}">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if (!empty($hottoday) && count($hottoday) > 0)
        <section class="hot-today-sec mb-6">
            <div class="container">
                <div class="title-inner d-flex align-items-center">
                    <h4 class="m-0"><?php echo __('messages.hot_today'); ?></h4>
                    <a href="{{ url('hottodayproducts') }}" class="ml-auto"><?php echo __('messages.see_all_items'); ?></a>
                </div>
                <div class="slider-arrow hot-today-slider owl-carousel owl-theme">
                    @if (!empty($hottoday))
                        @foreach ($hottoday as $product)

                            <div class="item">
                                <div class="product-box">
                                    <figure>
                                        <a
                                            href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">
                                            <img src="{{ url('/') }}/public/product_images/{{ $product->img }}">
                                        </a>
                                        @if (!empty($product->discount_available))
                                            <span>{{ $product->discount_available }}% <?php echo __('messages.off'); ?></span>
                                        @endif
                                        @if (\App::getLocale() == 'en')
                                            @if (!empty($product->offer_label_en))
                                                <div class="discount-left">{{ $product->offer_label_en }}</div>
                                            @endif
                                        @else
                                            @if (!empty($product->offer_label_ar))
                                                <div class="discount-left">{{ $product->offer_label_ar }}</div>
                                            @endif
                                        @endif
                                    </figure>
                                    <figcaption>

                                        <span class="pro-brand"><a
                                                href="{{ url('brandproducts', base64_encode($product->brand_id)) }}">@if (\App::getLocale() == 'en'){{ $product->brandname_en }}@else {{ $product->brandname_ar }} @endif</a></span>
                                        <h4 class="pro-name"><a
                                                href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">@if (\App::getLocale() == 'en'){{ $product->name_en }}@else {{ $product->name_ar }} @endif</a>
                                        </h4>
                                        @if (!empty($product->offer_price))
                                            <div class="pr-rate d-flex">
                                            @else
                                                <div class="pr-rate d-flex" id="offerclass">
                                        @endif
                                        <h4>SR {{ $product->price }} <font>@if (!empty($product->offer_price))SR {{ $product->offer_price }}@endif </font>
                                        </h4>
                                        <?php $rating = checkrating($product->id); ?>
                                        <div class="rat-pro">
                                            @if (isset($rating['avgrating']))
                                                @if (ceil($rating['avgrating']) == 5)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 4)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 3)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 2)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 1)
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
                                    @if ($product->stock_availabity == 1 && $product->quantity > 0)
                                        <?php $getcolorstatus = getcolor($product->id); ?>
                                        @if ($getcolorstatus == 0)
                                            <button onclick="addtocart('{{ $product->id }}');"
                                                id="addcart{{ $product->id }}" class="btn btn-border-brown"><i
                                                    class="ri-shopping-cart-2-line"></i>
                                                <font><?php echo __('messages.add_to_cart'); ?></font>
                                            </button>
                                        @else
                                            <a href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}"
                                                class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                                                <?php echo __('messages.add_to_cart'); ?></a>
                                        @endif
                                        <?php $iswishlist = getwishlist($product->id); ?>
                                        <button id="btnTest" style="display:none;" data-toggle="modal"
                                            data-target="#my-cart">Go To Cart</button>
                                        @if (!empty(Auth::id()))
                                            @if (!empty($iswishlist))
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-fill"></i></button>
                                            @else
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-line"></i></button>
                                            @endif
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                                class="btn btn-border-black waves-effect waves-light"><i
                                                    class="ri-heart-line"></i></a>
                                        @endif
                                    @else
                                        <button class="btn btn-border-brown" disabled><i
                                                class="ri-shopping-cart-2-line"></i>
                                            <font><?php echo __('messages.out_of_stock'); ?></font>
                                        </button>
                                        @if (!empty(Auth::id()))
                                            <?php $iswishlist = getwishlist($product->id); ?>
                                            @if (!empty($iswishlist))
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-fill"></i></button>
                                            @else
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-line"></i></button>
                                            @endif
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                                class="btn btn-border-black waves-effect waves-light"><i
                                                    class="ri-heart-line"></i></a>
                                        @endif
                                    @endif
                                </div>
                                </figcaption>
                                @if ($product->stock_availabity == 2 || $product->quantity == 0)
                                    <div class="pro-outof-stock">
                                        <h6><?php echo __('messages.out_of_stock'); ?></h6>
                                    </div>
                                @endif
                            </div>
                </div>
    @endforeach
@else
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro1.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
                <div class="discount-left">40% </div>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro2.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro3.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro4.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    @endif
    </div>
    </div>
    </section>
    @endif

    <section class="banner-tiles mb-6">
        <div class="container-fluid">
            <div class="row">
                @if (!empty($offerbanners))
                    @foreach ($offerbanners as $record)
                        <div class="col-md-4">
                            <a href="{{ url($record->category_name . '/' . $record->sub_category_name) }}">
                                @if (\App::getLocale() == 'en')
                                    <img src="{{ url('/') }}/public/images/banners/{{ $record->image_en }}">
                                @else
                                    <img src="{{ url('/') }}/public/images/banners/{{ $record->image_ar }}">
                                @endif
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-4">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/block1.jpg') }}">
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/block2.jpg') }}">
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/block3.jpg') }}">
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/block4.jpg') }}">
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/block5.jpg') }}">
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/block6.jpg') }}">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
    @if (!empty($hotdealproduct) && count($hotdealproduct) > 0)
        <section class="hot-today-sec mb-6">
            <div class="container">
                <div class="title-inner d-flex align-items-center">
                    <h4 class="m-0"><?php echo __('messages.hot_deals'); ?></h4>
                    <a href="{{ url('hotdealproduct') }}" class="ml-auto"><?php echo __('messages.see_all_items'); ?></a>
                </div>
                <div class="slider-arrow hot-deals-slider owl-carousel owl-theme">
                    @if (!empty($hotdealproduct))
                        @foreach ($hotdealproduct as $product)
                            <div class="item">
                                <div class="product-box">
                                    <figure>
                                        <a
                                            href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">
                                            <img src="{{ url('/') }}/public/product_images/{{ $product->img }}">
                                        </a>
                                        @if (!empty($product->discount_available))
                                            <span>{{ $product->discount_available }}% <?php echo __('messages.off'); ?></span>
                                        @endif
                                        @if (\App::getLocale() == 'en')
                                            @if (!empty($product->offer_label_en))
                                                <div class="discount-left">{{ $product->offer_label_en }}</div>
                                            @endif
                                        @else
                                            @if (!empty($product->offer_label_ar))
                                                <div class="discount-left">{{ $product->offer_label_ar }}</div>
                                            @endif
                                        @endif
                                    </figure>
                                    <figcaption>
                                        <span class="pro-brand"><a
                                                href="{{ url('brandproducts', base64_encode($product->brand_id)) }}">@if (\App::getLocale() == 'en'){{ $product->brandname_en }}@else {{ $product->brandname_ar }} @endif</a></span>
                                        <h4 class="pro-name"><a
                                                href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">@if (\App::getLocale() == 'en'){{ $product->name_en }}@else {{ $product->name_ar }} @endif</a>
                                        </h4>
                                        @if (!empty($product->offer_price))
                                            <div class="pr-rate d-flex">
                                            @else
                                                <div class="pr-rate d-flex" id="offerclass">
                                        @endif
                                        <h4>SR {{ $product->price }} <font>@if (!empty($product->offer_price))SR {{ $product->offer_price }}@endif</font>
                                        </h4>
                                        <?php $rating = checkrating($product->id); ?>
                                        <div class="rat-pro">
                                            @if (isset($rating['avgrating']))
                                                @if (ceil($rating['avgrating']) == 5)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 4)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 3)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 2)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 1)
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
                                    @if ($product->stock_availabity == 1 && $product->quantity > 0)
                                        <?php $getcolorstatus = getcolor($product->id); ?>
                                        @if ($getcolorstatus == 0)
                                            <button onclick="addtocart('{{ $product->id }}');"
                                                id="addcart{{ $product->id }}" class="btn btn-border-brown"><i
                                                    class="ri-shopping-cart-2-line"></i>
                                                <font> <?php echo __('messages.add_to_cart'); ?></font>
                                            </button>
                                        @else
                                            <a href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}"
                                                class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                                                <font> <?php echo __('messages.add_to_cart'); ?></font>
                                            </a>
                                        @endif
                                        <?php $iswishlist = getwishlist($product->id); ?>
                                        <button id="btnTest" style="display:none;" data-toggle="modal"
                                            data-target="#my-cart">Go To Cart</button>
                                        @if (!empty(Auth::id()))
                                            @if (!empty($iswishlist))
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-fill"></i></button>
                                            @else
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-line"></i></button>
                                            @endif
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                                class="btn btn-border-black waves-effect waves-light"><i
                                                    class="ri-heart-line"></i></a>
                                        @endif
                                    @else
                                        <button class="btn btn-border-brown" disabled><i
                                                class="ri-shopping-cart-2-line"></i>
                                            <font><?php echo __('messages.out_of_stock'); ?></font>
                                        </button>
                                        @if (!empty(Auth::id()))
                                            <?php $iswishlist = getwishlist($product->id); ?>
                                            @if (!empty($iswishlist))
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-fill"></i></button>
                                            @else
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-line"></i></button>
                                            @endif
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                                class="btn btn-border-black waves-effect waves-light"><i
                                                    class="ri-heart-line"></i></a>
                                        @endif
                                    @endif
                                </div>
                                </figcaption>
                                @if ($product->stock_availabity == 2 || $product->quantity == 0)
                                    <div class="pro-outof-stock">
                                        <h6><?php echo __('messages.out_of_stock'); ?></h6>
                                    </div>
                                @endif
                            </div>
                </div>
    @endforeach
@else
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro1.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro2.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>

        <div class="item">
            <div class="product-box">
                <figure>
                    <a href="javascript:;">
                        <img src="{{ asset('assets/front-end/images/pro3.jpg') }}">
                    </a>
                    <span>20% <?php echo __('messages.off'); ?></span>
                </figure>
                <figcaption>
                    <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                    <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                    <div class="pr-rate d-flex">
                        <h4>SR 30 <font>SR 22 </font>
                        </h4>
                        <div class="rat-pro">
                            <i class="ri-star-s-fill active"></i>
                            <i class="ri-star-s-fill active"></i>
                            <i class="ri-star-s-fill active"></i>
                            <i class="ri-star-s-fill"></i>
                            <i class="ri-star-s-fill"></i>
                        </div>
                    </div>
                    <div class="pro-btns">
                        <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                            <?php echo __('messages.add_to_cart'); ?></a>
                        <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                    </div>
                </figcaption>
            </div>
        </div>
        <div class="item">
            <div class="product-box">
                <figure>
                    <a href="javascript:;">
                        <img src="{{ asset('assets/front-end/images/pro4.jpg') }}">
                    </a>
                    <span>20% <?php echo __('messages.off'); ?></span>
                </figure>
                <figcaption>
                    <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                    <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                    <div class="pr-rate d-flex">
                        <h4>SR 30 <font>SR 22 </font>
                        </h4>
                        <div class="rat-pro">
                            <i class="ri-star-s-fill active"></i>
                            <i class="ri-star-s-fill active"></i>
                            <i class="ri-star-s-fill active"></i>
                            <i class="ri-star-s-fill"></i>
                            <i class="ri-star-s-fill"></i>
                        </div>
                    </div>
                    <div class="pro-btns">
                        <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                            <?php echo __('messages.add_to_cart'); ?></a>
                        <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                    </div>
                </figcaption>
            </div>
        </div>
        @endif
    </div>
    </div>
    </section>
    @endif

    <section class="mid-banner mb-6">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-4">
                    <a
                        href="{{ url($hottodaybanner['0']->category_name . '/' . $hottodaybanner['0']->sub_category_name) }}">
                        @if (\App::getLocale() == 'en')
                            <img src="{{ asset('public/images/hot_today/' . $hottodaybanner['0']->image_en) }}"
                                width="100%">
                        @else
                            <img src="{{ asset('public/images/hot_today/' . $hottodaybanner['0']->image_ar) }}"
                                width="100%">
                        @endif
                    </a>
                </div>
                <div class="col-md-4">
                    <a
                        href="{{ url($hottodaybanner['1']->category_name . '/' . $hottodaybanner['1']->sub_category_name) }}">
                        @if (\App::getLocale() == 'en')
                            <img src="{{ asset('public/images/hot_today/' . $hottodaybanner['1']->image_en) }}"
                                width="100%">
                        @else
                            <img src="{{ asset('public/images/hot_today/' . $hottodaybanner['1']->image_ar) }}"
                                width="100%">
                        @endif
                    </a>
                </div>
                <div class="col-md-4">
                    <a
                        href="{{ url($hottodaybanner['2']->category_name . '/' . $hottodaybanner['2']->sub_category_name) }}">
                        @if (\App::getLocale() == 'en')
                            <img src="{{ asset('public/images/hot_today/' . $hottodaybanner['2']->image_en) }}"
                                width="100%">
                        @else
                            <img src="{{ asset('public/images/hot_today/' . $hottodaybanner['2']->image_ar) }}"
                                width="100%">
                        @endif
                    </a>
                </div>
                <div class="col-md-12 mt-4">
                    <a
                        href="{{ url($hottodaybanner['3']->category_name . '/' . $hottodaybanner['3']->sub_category_name) }}">
                        @if (\App::getLocale() == 'en')
                            <img src="{{ asset('public/images/hot_today/' . $hottodaybanner['3']->image_en) }}"
                                width="100%">
                        @else
                            <img src="{{ asset('public/images/hot_today/' . $hottodaybanner['3']->image_ar) }}"
                                width="100%">
                        @endif
                    </a>
                </div>

            </div>
        </div>
    </section>
    @if (!empty($trending) && count($trending) > 0)
        <section class="hot-today-sec mb-6">
            <div class="container">
                <div class="title-inner d-flex align-items-center">
                    <h4 class="m-0"><?php echo __('messages.trending'); ?></h4>
                    <a href="{{ url('tendingproducts') }}" class="ml-auto"><?php echo __('messages.see_all_items'); ?></a>
                </div>
                <div class="slider-arrow trending-slider owl-carousel owl-theme">
                    @if (!empty($trending))
                        @foreach ($trending as $product)
                            <div class="item">
                                <div class="product-box">
                                    <figure>
                                        <a
                                            href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">
                                            <img src="{{ url('/') }}/public/product_images/{{ $product->img }}">
                                        </a>
                                        @if (!empty($product->discount_available))
                                            <span>{{ $product->discount_available }}% <?php echo __('messages.off'); ?></span>
                                        @endif
                                        @if (\App::getLocale() == 'en')
                                            @if (!empty($product->offer_label_en))
                                                <div class="discount-left">{{ $product->offer_label_en }}</div>
                                            @endif
                                        @else
                                            @if (!empty($product->offer_label_ar))
                                                <div class="discount-left">{{ $product->offer_label_ar }}</div>
                                            @endif
                                        @endif
                                    </figure>
                                    <figcaption>
                                        <span class="pro-brand"><a
                                                href="{{ url('brandproducts', base64_encode($product->brand_id)) }}">@if (\App::getLocale() == 'en'){{ $product->brandname_en }}@else {{ $product->brandname_ar }} @endif</a></span>
                                        <h4 class="pro-name"><a
                                                href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">@if (\App::getLocale() == 'en'){{ $product->name_en }}@else {{ $product->name_ar }} @endif</a>
                                        </h4>
                                        @if (!empty($product->offer_price))
                                            <div class="pr-rate d-flex">
                                            @else
                                                <div class="pr-rate d-flex" id="offerclass">
                                        @endif
                                        <h4>SR {{ $product->price }} <font>@if (!empty($product->offer_price))SR {{ $product->offer_price }}@endif </font>
                                        </h4>
                                        <?php $rating = checkrating($product->id); ?>
                                        <div class="rat-pro">
                                            @if (isset($rating['avgrating']))
                                                @if (ceil($rating['avgrating']) == 5)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 4)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 3)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 2)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 1)
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
                                    @if ($product->stock_availabity == 1 && $product->quantity > 0)
                                        <?php $getcolorstatus = getcolor($product->id); ?>
                                        @if ($getcolorstatus == 0)
                                            <button onclick="addtocart('{{ $product->id }}');"
                                                id="addcart{{ $product->id }}" class="btn btn-border-brown"><i
                                                    class="ri-shopping-cart-2-line"></i>
                                                <font> <?php echo __('messages.add_to_cart'); ?></font>
                                            </button>
                                        @else
                                            <a href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}"
                                                class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                                                <font><?php echo __('messages.add_to_cart'); ?></font>
                                            </a>
                                        @endif
                                        <?php $iswishlist = getwishlist($product->id); ?>
                                        <button id="btnTest" style="display:none;" data-toggle="modal"
                                            data-target="#my-cart">Go To Cart</button>
                                        @if (!empty(Auth::id()))
                                            @if (!empty($iswishlist))
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-fill"></i></button>
                                            @else
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-line"></i></button>
                                            @endif
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                                class="btn btn-border-black waves-effect waves-light"><i
                                                    class="ri-heart-line"></i></a>
                                        @endif
                                    @else
                                        <button class="btn btn-border-brown" disabled><i
                                                class="ri-shopping-cart-2-line"></i>
                                            <font> <?php echo __('messages.out_of_stock'); ?></font>
                                        </button>
                                        @if (!empty(Auth::id()))
                                            <?php $iswishlist = getwishlist($product->id); ?>
                                            @if (!empty($iswishlist))
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-fill"></i></button>
                                            @else
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-line"></i></button>
                                            @endif
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                                class="btn btn-border-black waves-effect waves-light"><i
                                                    class="ri-heart-line"></i></a>
                                        @endif
                                    @endif
                                </div>
                                </figcaption>
                                @if ($product->stock_availabity == 2 || $product->quantity == 0)
                                    <div class="pro-outof-stock">
                                        <h6><?php echo __('messages.out_of_stock'); ?></h6>
                                    </div>
                                @endif
                            </div>
                </div>
    @endforeach
@else
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro1.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro2.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro3.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro4.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    @endif
    </div>
    </div>
    </section>
    @endif

    <section class="four-blk-tiles mb-6">
        <div class="container-fluid">
            <div class="row">
                @if (!empty($hotdeals))
                    @foreach ($hotdeals as $hotdeal)
                        <div class="col-md-6">
                            <a href="{{ url($hotdeal->category_name . '/' . $hotdeal->sub_category_name) }}">
                                @if (\App::getLocale() == 'en')
                                    <img src="{{ url('/') }}/public/images/hotdeal/{{ $hotdeal->image_en }}">
                                @else
                                    <img src="{{ url('/') }}/public/images/hotdeal/{{ $hotdeal->image_ar }}">
                                @endif
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-6">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/four-bl1.jpg') }}">
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/four-bl2.jpg') }}">
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/four-bl3.jpg') }}">
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="javascript:;">
                            <img src="{{ asset('assets/front-end/images/four-bl4.jpg') }}">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if (!empty($bestsellingproducts) && count($bestsellingproducts) > 0)
        <section class="hot-today-sec mb-6">
            <div class="container">
                <div class="title-inner d-flex align-items-center">
                    <h4 class="m-0"><?php echo __('messages.best_seller'); ?></h4>
                    <a href="{{ url('bestsellingproducts') }}" class="ml-auto"><?php echo __('messages.see_all_items'); ?></a>
                </div>
                <div class="slider-arrow best-seller-slider owl-carousel owl-theme">
                    @if (!empty($bestsellingproducts))
                        @foreach ($bestsellingproducts as $product)
                            <div class="item">
                                <div class="product-box">
                                    <figure>
                                        <a
                                            href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">
                                            <img src="{{ url('/') }}/public/product_images/{{ $product->img }}">
                                        </a>
                                        @if (!empty($product->discount_available))
                                            <span>{{ $product->discount_available }}% <?php echo __('messages.off'); ?></span>
                                        @endif
                                        @if (\App::getLocale() == 'en')
                                            @if (!empty($product->offer_label_en))
                                                <div class="discount-left">{{ $product->offer_label_en }}</div>
                                            @endif
                                        @else
                                            @if (!empty($product->offer_label_ar))
                                                <div class="discount-left">{{ $product->offer_label_ar }}</div>
                                            @endif
                                        @endif
                                    </figure>
                                    <figcaption>
                                        <span class="pro-brand"><a
                                                href="{{ url('brandproducts', base64_encode($product->brand_id)) }}">@if (\App::getLocale() == 'en'){{ $product->brandname_en }}@else {{ $product->brandname_ar }} @endif</a></span>
                                        <h4 class="pro-name"><a
                                                href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">@if (\App::getLocale() == 'en'){{ $product->name_en }}@else {{ $product->name_ar }} @endif</a>
                                        </h4>
                                        @if (!empty($product->offer_price))
                                            <div class="pr-rate d-flex">
                                            @else
                                                <div class="pr-rate d-flex" id="offerclass">
                                        @endif
                                        <h4>SR {{ $product->price }} <font>@if (!empty($product->offer_price))SR {{ $product->offer_price }}@endif </font>
                                        </h4>
                                        <?php $rating = checkrating($product->id); ?>
                                        <div class="rat-pro">
                                            @if (isset($rating['avgrating']))
                                                @if (ceil($rating['avgrating']) == 5)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 4)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 3)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 2)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 1)
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
                                    @if ($product->stock_availabity == 1 && $product->quantity > 0)
                                        <?php $getcolorstatus = getcolor($product->id); ?>
                                        @if ($getcolorstatus == 0)
                                            <button onclick="addtocart('{{ $product->id }}');"
                                                id="addcart{{ $product->id }}" class="btn btn-border-brown"><i
                                                    class="ri-shopping-cart-2-line"></i>
                                                <font><?php echo __('messages.add_to_cart'); ?></font>
                                            </button>
                                        @else
                                            <a href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}"
                                                class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                                                <font><?php echo __('messages.add_to_cart'); ?></font>
                                            </a>
                                        @endif
                                        <?php $iswishlist = getwishlist($product->id); ?>
                                        <button id="btnTest" style="display:none;" data-toggle="modal"
                                            data-target="#my-cart">Go To Cart</button>
                                        @if (!empty(Auth::id()))
                                            @if (!empty($iswishlist))
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-fill"></i></button>
                                            @else
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-line"></i></button>
                                            @endif
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                                class="btn btn-border-black waves-effect waves-light"><i
                                                    class="ri-heart-line"></i></a>
                                        @endif
                                    @else
                                        <button class="btn btn-border-brown" disabled><i
                                                class="ri-shopping-cart-2-line"></i>
                                            <font> <?php echo __('messages.out_of_stock'); ?></font>
                                        </button>
                                        @if (!empty(Auth::id()))
                                            <?php $iswishlist = getwishlist($product->id); ?>
                                            @if (!empty($iswishlist))
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-fill"></i></button>
                                            @else
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-line"></i></button>
                                            @endif
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                                class="btn btn-border-black waves-effect waves-light"><i
                                                    class="ri-heart-line"></i></a>
                                        @endif
                                    @endif
                                </div>
                                </figcaption>
                                @if ($product->stock_availabity == 2 || $product->quantity == 0)
                                    <div class="pro-outof-stock">
                                        <h6><?php echo __('messages.out_of_stock'); ?></h6>
                                    </div>
                                @endif
                            </div>
                </div>
    @endforeach
@else
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro1.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro2.jpg') }}">
                    <figcaption>
                        <span class="pro-brand"><a
                                href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">{{ $product->brandname }}</a></span>
                        <h4 class="pro-name"><a
                                href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">{{ $product->name_en }}</a>
                        </h4>
                        <div class="pr-rate d-flex">
                            <h4>SR {{ $product->price }} <font>@if (!empty($product->offer_price))SR {{ $product->offer_price }}@endif</font>
                            </h4>
                            <div class="rat-pro">
                                <i class="ri-star-s-fill active"></i>
                                <i class="ri-star-s-fill active"></i>
                                <i class="ri-star-s-fill active"></i>
                                <i class="ri-star-s-fill"></i>
                                <i class="ri-star-s-fill"></i>
                            </div>
                        </div>
                        <div class="pro-btns">
                            <?php $getcolorstatus = getcolor($product->id); ?>
                            @if ($getcolorstatus == 0)
                                <button onclick="addtocart('{{ $product->id }}');" id="addcart{{ $product->id }}"
                                    class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                                    <font> <?php echo __('messages.add_to_cart'); ?></font>
                                </button>
                            @else
                                <a href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}"
                                    class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                                    <font><?php echo __('messages.add_to_cart'); ?></font>
                                </a>
                            @endif
                            <?php $iswishlist = getwishlist($product->id); ?>
                            <button id="btnTest" style="display:none;" data-toggle="modal" data-target="#my-cart">Go To
                                Cart</button>
                            @if (!empty(Auth::id()))
                                @if (!empty($iswishlist))
                                    <button onclick="addtowishlist('{{ $product->id }}');"
                                        class="btn btn-border-black waves-effect waves-light"><i
                                            class="ri-heart-fill"></i></button>
                                @else
                                    <button onclick="addtowishlist('{{ $product->id }}');"
                                        class="btn btn-border-black waves-effect waves-light"><i
                                            class="ri-heart-line"></i></button>
                                @endif
                            @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                    class="btn btn-border-black waves-effect waves-light"><i
                                        class="ri-heart-line"></i></a>
                            @endif
                        </div>
                    </figcaption>
                    <figcaption>
                        <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                        <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                        <div class="pr-rate d-flex">
                            <h4>SR 30 <font>SR 22 </font>
                            </h4>
                            <div class="rat-pro">
                                <i class="ri-star-s-fill active"></i>
                                <i class="ri-star-s-fill active"></i>
                                <i class="ri-star-s-fill active"></i>
                                <i class="ri-star-s-fill"></i>
                                <i class="ri-star-s-fill"></i>
                            </div>
                        </div>
                        <div class="pro-btns">
                            <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                                <font><?php echo __('messages.add_to_cart'); ?></font>
                            </a>
                            <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                        </div>
                    </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro3.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro4.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    @endif
    </div>
    </div>
    </section>
    @endif
    @if (!empty($products) && count($products) > 0)
        <section class="hot-today-sec mb-6">
            <div class="container">
                <div class="title-inner d-flex align-items-center">
                    <h4 class="m-0"><?php echo __('messages.new_arrivales'); ?></h4>
                    <a href="{{ url('newarrivalproducts') }}" class="ml-auto"><?php echo __('messages.see_all_items'); ?></a>
                </div>
                <div class="slider-arrow new-arrivales-slider owl-carousel owl-theme">
                    @if (!empty($products))
                        @foreach ($products as $product)
                            <div class="item">
                                <div class="product-box">
                                    <figure>
                                        <a
                                            href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">
                                            <img src="{{ url('/') }}/public/product_images/{{ $product->img }}">
                                        </a>
                                        @if (!empty($product->discount_available))
                                            <span>{{ $product->discount_available }}% <?php echo __('messages.off'); ?></span>
                                        @endif
                                        @if (\App::getLocale() == 'en')
                                            @if (!empty($product->offer_label_en))
                                                <div class="discount-left">{{ $product->offer_label_en }}</div>
                                            @endif
                                        @else
                                            @if (!empty($product->offer_label_ar))
                                                <div class="discount-left">{{ $product->offer_label_ar }}</div>
                                            @endif
                                        @endif
                                    </figure>
                                    <figcaption>
                                        <span class="pro-brand"><a
                                                href="{{ url('brandproducts', base64_encode($product->brand_id)) }}">@if (\App::getLocale() == 'en'){{ $product->brandname_en }}@else {{ $product->brandname_ar }} @endif</a></span>
                                        <h4 class="pro-name"><a
                                                href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}">@if (\App::getLocale() == 'en'){{ $product->name_en }}@else {{ $product->name_ar }} @endif</a>
                                        </h4>
                                        @if (!empty($product->offer_price))
                                            <div class="pr-rate d-flex">
                                            @else
                                                <div class="pr-rate d-flex" id="offerclass">
                                        @endif
                                        <h4>SR {{ $product->price }} <font>@if (!empty($product->offer_price))SR {{ $product->offer_price }}@endif</font>
                                        </h4>
                                        <?php $rating = checkrating($product->id); ?>
                                        <div class="rat-pro">
                                            @if (isset($rating['avgrating']))
                                                @if (ceil($rating['avgrating']) == 5)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 4)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 3)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 2)
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill active"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                    <i class="ri-star-s-fill"></i>
                                                @endif
                                                @if (ceil($rating['avgrating']) == 1)
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
                                    @if ($product->stock_availabity == 1 && $product->quantity > 0)
                                        <?php $getcolorstatus = getcolor($product->id); ?>
                                        @if ($getcolorstatus == 0)
                                            <button onclick="addtocart('{{ $product->id }}');"
                                                id="addcart{{ $product->id }}" class="btn btn-border-brown"><i
                                                    class="ri-shopping-cart-2-line"></i>
                                                <font><?php echo __('messages.add_to_cart'); ?></font>
                                            </button>
                                        @else
                                            <a href="{{ url($product->category_name . '/' . $product->sub_category_name . '/' . $product->seo_url) }}"
                                                class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                                                <font> <?php echo __('messages.add_to_cart'); ?></font>
                                            </a>
                                        @endif
                                        <?php $iswishlist = getwishlist($product->id); ?>
                                        <button id="btnTest" style="display:none;" data-toggle="modal"
                                            data-target="#my-cart">Go To Cart</button>
                                        @if (!empty(Auth::id()))
                                            @if (!empty($iswishlist))
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-fill"></i></button>
                                            @else
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-line"></i></button>
                                            @endif
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                                class="btn btn-border-black waves-effect waves-light"><i
                                                    class="ri-heart-line"></i></a>
                                        @endif
                                    @else
                                        <button class="btn btn-border-brown" disabled><i
                                                class="ri-shopping-cart-2-line"></i>
                                            <font><?php echo __('messages.out_of_stock'); ?></font>
                                        </button>
                                        @if (!empty(Auth::id()))
                                            <?php $iswishlist = getwishlist($product->id); ?>
                                            @if (!empty($iswishlist))
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-fill"></i></button>
                                            @else
                                                <button onclick="addtowishlist('{{ $product->id }}');"
                                                    class="btn btn-border-black waves-effect waves-light"><i
                                                        class="ri-heart-line"></i></button>
                                            @endif
                                        @else
                                            <a href="javascript:;" data-toggle="modal" data-target="#login-modal"
                                                class="btn btn-border-black waves-effect waves-light"><i
                                                    class="ri-heart-line"></i></a>
                                        @endif
                                    @endif
                                </div>
                                </figcaption>
                                @if ($product->stock_availabity == 2 || $product->quantity == 0)
                                    <div class="pro-outof-stock">
                                        <h6><?php echo __('messages.out_of_stock'); ?></h6>
                                    </div>
                                @endif
                            </div>
                </div>
    @endforeach
@else
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro1.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro2.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro3.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    <div class="item">
        <div class="product-box">
            <figure>
                <a href="javascript:;">
                    <img src="{{ asset('assets/front-end/images/pro4.jpg') }}">
                </a>
                <span>20% <?php echo __('messages.off'); ?></span>
            </figure>
            <figcaption>
                <span class="pro-brand"><a href="javascript:;">Brand name</a></span>
                <h4 class="pro-name"><a href="javascript:;">Silver Necklace</a></h4>
                <div class="pr-rate d-flex">
                    <h4>SR 30 <font>SR 22 </font>
                    </h4>
                    <div class="rat-pro">
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill active"></i>
                        <i class="ri-star-s-fill"></i>
                        <i class="ri-star-s-fill"></i>
                    </div>
                </div>
                <div class="pro-btns">
                    <a href="javascript:;" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i>
                        <?php echo __('messages.add_to_cart'); ?></a>
                    <a href="javascript:;" class="btn btn-border-black"><i class="ri-heart-line"></i></a>
                </div>
            </figcaption>
        </div>
    </div>
    @endif
    </div>
    </div>
    </section>

    <!-- Button trigger modal -->
    <button type="button" style="display:none;" data-toggle="modal" data-target="#offerbanner"></button>

    <!-- Modal -->
    @if ($global->popup_status == 1 && empty(Session::get('popup')))
        <?php Session::put('popup', 1); ?>
        <div class="modal fade offerbanner-in" id="offerbanner" tabindex="-1" role="dialog"
            aria-labelledby="offerbannerTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <figure>
                            @if (\App::getLocale() == 'en')
                                <a href="{{ $global->popup_url }}"> <img
                                        src="{{ url('/') }}/public/images/globalsetting/{{ $global->popup_image_en }}"
                                        width="100%"> </a>
                            @else
                                <a href="{{ $global->popup_url }}"> <img
                                        src="{{ url('/') }}/public/images/globalsetting/{{ $global->popup_image_ar }}"
                                        width="100%"> </a>
                            @endif
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @endif

@endsection
