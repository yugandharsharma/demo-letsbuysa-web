<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--responsive-meta-here-->
    <meta name="viewport" content="minimum-scale=1.0, maximum-scale=1.0,width=device-width, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <?php $metadata = getmetadata(); ?>
    @if (\App::getLocale() == 'en')
        <title>{{ $global->title_en }}</title>
        <meta name="keyword" content="<?php if (isset($metadata->keywords_en)) {
    echo $metadata->keywords_en;
} else {
    echo 'Luca, lenses, Nora Bo Awadh, original perfumes, scarf, shawl, bags, brooch, shopping site, shopping, earrings, necklace, accessories, rings, lets buy, womens store, makeup store, care store, Benefit makeup, Bourjois, eyeliner the balm morphy palette';
} ?>">
        <meta name="title" content="<?php if (isset($metadata->meta_tag_title_en)) {
    echo $metadata->meta_tag_title_en;
} ?>">
        <meta name="description" content="<?php if (isset($metadata->meta_tag_desc_en)) {
    echo $metadata->meta_tag_desc_en;
} else {
    echo 'Secure shopping, cash on delivery, fast shipping, easy returns, lets buy a Saudi online shopping platform to shop perfume, original care, fashion, beauty and accessories products at competitive prices';
} ?>">
    @else
        <title>{{ $global->title_ar }}</title>
        <meta name="keyword" content="<?php if (isset($metadata->keywords_ar)) {
    echo $metadata->keywords_ar;
} else {
    echo 'لوكا,عدسات,نوره بوعوض,عطور أصليه,سكارف,شال,شنط,بروش,موقع تسوق,تسوق,حلق,سلسال,اكسسوارات,خواتم,لتس باي,متجر نسائي,متجر مكياج,متجر العناية, مكياج بنفت,ارواج برجوا ,كحل ذا بالم ,باليت مورفي,كونسيلر,رموش,مورفي,ماسك,مكياج,بنفت,مكياج ناعم,برجوا';
} ?>">
        <meta name="title" content="<?php if (isset($metadata->meta_tag_title_ar)) {
    echo $metadata->meta_tag_title_ar;
} ?>">
        <meta name="description" content="<?php if (isset($metadata->meta_tag_desc_ar)) {
    echo $metadata->meta_tag_desc_ar;
} else {
    echo 'تسوق امن ،الدفع عند الاستلام ، شحن سريع ، ارجاع سهل , لتس باي منصه تسوق الكترونيه سعوديه لتسوق منتجات العطور والعناية الأصليه والموضه والجمال والاكسسوارات بأسعار تنافسيه';
} ?>">
    @endif
    <!--responsive-meta-end-->


    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-203892630-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-203892630-1');
    </script>

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-KVPHG2L');
    </script>
    <!-- End Google Tag Manager -->





    <link href="{{ asset('assets/front-end/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/front-end/css/mdb.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/front-end/css/owl.carousel.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/front-end/css/fontawesome-all.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/front-end/css/fontawesome.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/front-end/fonts/remixicon.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css">
    <link rel="stylesheet" href="{{ asset('assets/front-end/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('assets/front-end/css/responsive.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    @toastr_css
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-209995958-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-209995958-1');
    </script>



</head>
@if (\App::getLocale() == 'en')

    <body class="loader-body ltr">
    @else

        <body class="loader-body rtl">
@endif

<div id='loader' style="display:none;">
    <div class="loader-body-bx">
        <img src="{{ asset('assets/front-end/images/loader.gif') }}">
    </div>
</div>
<div class="wraper">
    <!-- Header in -->
    <header class="">
        @if (!empty($global->mega_sale_banner))
            <div class="offers-head alert alert-dismissible fade show" role="alert">
                <a href="javascript:;"><img
                        src="{{ url('/') }}/public/images/globalsetting/{{ $global->mega_sale_banner }}"></a>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- <button onclick="buttonClick()" class="btn btn-primary">AAAAAAJAJAJAJA</button> -->

        <div class="header-main">
            <div class="container">
                <div class="header-main-in d-flex">
                    <div class="logo-site">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/front-end/images/logo.svg') }}">
                        </a>
                    </div>
                    <div class="header-search">
                        <div class="header-search-in">
                            <form action="{{ url(app()->getLocale() . '/' . 'search') }}" id="productsearchform">
                                <input type="text" id="search" name="search" placeholder="<?php echo __('messages.search_your_product'); ?>"
                                    value="<?php if (isset($product_name)) {
                                        echo $product_name;
                                    } ?>">
                                <button><i class="ri-search-line"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="header-login-icons">
                        <ul>
                            <li>
                                <div class="header-login-icons-in">
                                    <a href="{{ url('/wishlist') }}" class="header-icons">
                                        <i class="ri-heart-line"></i>
                                        <?php $wishlist = getwishlistcount(); ?>
                                        @if (!empty($wishlist))
                                            <span class="cart-count"><?php if (!empty($wishlist)) {
    echo $wishlist;
} ?></span>
                                        @endif
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="header-login-icons-in">
                                    <a href="javascript:;" onclick="view_cart()" class="header-icons header-cart"
                                        data-toggle="modal" data-target="#my-cart">
                                        <i class="ri-shopping-cart-2-line"></i>
                                        <?php $productcount = getitemcount(); ?>
                                        @if (!empty($productcount))
                                            <span class="cart-count"><?php if (!empty($productcount)) {
    echo $productcount;
} ?></span>
                                        @endif
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="header-login-icons-in dropdown site-dropdown">
                                    <a href="javascript:;" class="dropdown-toggle header-icons" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="ri-user-line"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @if (!empty(Auth::user()))
                                            <a href="{{ url('/myaccount') }}"
                                                class="dropdown-item">{{ Auth::user()->name }}</a>
                                            <a class="dropdown-item" data-toggle="modal"
                                                data-target="#log-out-modal"><?php echo __('messages.logout'); ?></a>
                                        @else
                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                data-target="#login-modal"><?php echo __('messages.login'); ?></a>
                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                data-target="#create-account-modal"><?php echo __('messages.register'); ?></a>
                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                data-target="#for-password-modal"><?php echo __('messages.forget_password'); ?></a>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-nav">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                        <ul class="navbar-nav mr-auto">
                            @foreach ($categories as $category)

                                <li class="nav-item">
                                    <div class="dropdown site-dropdown">
                                        <a href="{{ url($category->category_name_en) }}"
                                            class="dropdown-btn dropdown-toggle">
                                            @if (!empty($category->subcategories[0]))
                                                @if (\App::getLocale() == 'en')
                                                    {{ $category->category_name_en }}
                                                @else
                                                    {{ $category->category_name_ar }}
                                                @endif
                                            @endif
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @foreach ($category->subcategories as $subcategory)
                                                <a class="dropdown-item"
                                                    onclick='view_item_list("{{ $subcategory->category_name }}","{{ $subcategory->sub_category_name }}")'>
                                                    @if (\App::getLocale() == 'en')
                                                        {{ $subcategory->sub_category_name_en }}
                                                    @else
                                                        {{ $subcategory->sub_category_name_ar }}
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- <div class="all-cat-dropdown">
                <div class="dropdown site-dropdown">
                  <button class="dropdown-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <?php echo __('messages.all_categories'); ?>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @foreach ($allcategory as $allcategories)
                    <a class="dropdown-item" href="{{ url('categorylist', base64_encode($allcategories->id)) }}"> @if (\App::getLocale() == 'en')
                      {{ $allcategories->category_name_en }}
@else
                      {{ $allcategories->category_name_ar }}
                      @endif</a>
                    @endforeach
                  </div>
                </div>
            </div> -->

                    <div class="all-cat-dropdown">
                        <div class="dropdown site-dropdown">
                            <button class="dropdown-btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" data-hover="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo __('messages.all_categories'); ?>
                            </button>
                            <ul class="dropdown-menu show" aria-labelledby="dropdownMenuButton">
                                @foreach ($categories as $category)

                                    <li class="dropdown-sub">
                                        @if (!empty($category->subcategories[0]))
                                            <a class="dropdown-item dropbtn"
                                                onclick="view_cat_item_list('{{ $category->category_name }}')">
                                                @if (\App::getLocale() == 'en')
                                                    {{ $category->category_name_en }}
                                                @else
                                                    {{ $category->category_name_ar }}
                                                @endif
                                        @endif
                                        </a>
                                        <div class="dropdown-content">
                                            @foreach ($category->subcategories as $subcategory)
                                                <a
                                                    onclick='view_item_list("{{ $subcategory->category_name }}","{{ $subcategory->sub_category_name }}")'>
                                                    @if (\App::getLocale() == 'en')
                                                        {{ $subcategory->sub_category_name_en }}
                                                    @else
                                                        {{ $subcategory->sub_category_name_ar }}
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="language-option">
                        @if (\App::getLocale() == 'en')
                            <a href="{{ url('/lang/ar') }}" class="dropdown-btn dropdown-toggle">
                                <figure><img src="{{ asset('assets/front-end/images/saudi.png') }}"></figure> العربية
                            </a>
                        @else
                            <a href="{{ url('/lang/en') }}" class="dropdown-btn dropdown-toggle">
                                <figure><img src="{{ asset('assets/front-end/images/flag.jpg') }}"></figure> English
                            </a>
                        @endif
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <!-- Header in -->
    <div class="site-wraper">
        @yield('content')
    </div>
    <footer>
        <div class="container-fluid footer-grt-bg">
            <div class="row">
                <div class="col-md-6">
                    <ul class="foot-cont">
                        <li>
                            <a href="javascript:;" class="footer-icons">
                                <i class="far fa-question-circle"></i>
                            </a>
                            <figcaption>
                                <h6><?php echo __('messages.help_center'); ?></h6>
                                <h5>{{ $global->supportemail }}</h5>
                            </figcaption>
                        </li>
                        <li>
                            <a href="javascript:;" class="footer-icons">
                                <i class="far fa-envelope"></i>
                            </a>
                            <figcaption>
                                <h6><?php echo __('messages.email_support'); ?></h6>
                                <h5>{{ $global->email }}</h5>
                            </figcaption>
                        </li>
                        <li>
                            <a href="javascript:;" class="footer-icons">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <figcaption>
                                <h6><?php echo __('messages.phone_support'); ?></h6>
                                <h5>{{ $global->mobile }}</h5>
                            </figcaption>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="detail-mail-bx">
                        <div class="detail-mail">
                            <h4><?php echo __('messages.get_latest_deals'); ?></h4>
                            <h6><?php echo __('messages.our_best_promotions'); ?></h6>
                        </div>
                        <form id="subscribe" method="POST" action="{{ url('subscribe_email') }}">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="subscribeemail" class="form-control"
                                    placeholder="<?php echo __('messages.email_address'); ?>">
                                @if ($errors->has('subscribeemail'))
                                    <span id="title-error"
                                        class="error text-danger">{{ $errors->first('subscribeemail') }}</span>
                                @endif
                                <button type="submit" class="btn btn-sub"><?php echo __('messages.subscribe'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container ptt-foot">
            <div class="row">
                <div class="col-md-4">
                    <div class="foot-details-logo">
                        <a href="javascript:;">
                            @if (\App::getLocale() == 'en')
                                <img
                                    src="{{ url('/') }}/public/images/globalsetting/{{ $global->footer_icon_en }}">
                            @else
                                <img
                                    src="{{ url('/') }}/public/images/globalsetting/{{ $global->footer_icon_ar }}">
                            @endif
                        </a>
                        @if (\App::getLocale() == 'en')
                            {!! $global->footer_description_en !!}
                        @else
                            {!! $global->footer_description_ar !!}
                        @endif
                    </div>
                    <div class="foot-social-icon">
                        <ul>
                            <li> <a href="{{ $global->instagram }}"><i class="ri-instagram-fill"></i></a> </li>
                            <li> <a href="{{ $global->twitter }}"><i class="ri-twitter-fill"></i></a> </li>
                            <!-- <li> <a href="{{ $global->facebook }}"><i class="ri-facebook-fill"></i></a> </li> -->
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="footer-head"><?php echo __('messages.information'); ?></h4>
                            <div class="foot-navbar">
                                <ul>
                                    <li> <a href="{{ url('/aboutus') }}"> <?php echo __('messages.about_us'); ?></a> </li>
                                    <li> <a href="{{ url('/terms') }}"> <?php echo __('messages.terms_of_use'); ?></a> </li>
                                    <li> <a href="{{ url('/privacy') }}"> <?php echo __('messages.privacy'); ?></a> </li>
                                    <li> <a href="{{ url('/bankaccountpayment') }}"> <?php echo __('messages.bank_account_and_payment'); ?> </a> </li>
                                    <li> <a href="{{ url('/shipping') }}"> <?php echo __('messages.shipping_delivery'); ?></a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="footer-head"><?php echo __('messages.customer_service'); ?></h4>
                            <div class="foot-navbar">
                                <ul>
                                    <li> <a href="https://api.whatsapp.com/send?phone={{ $global->mobile }}">
                                            <?php echo __('messages.contact_us'); ?></a> </li>
                                    <li> <a href="{{ url('returns') }}"> <?php echo __('messages.returns'); ?></a> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h4 class="footer-head"><?php echo __('messages.extras'); ?></h4>
                            <div class="foot-navbar">
                                <ul>
                                    <li> <a href="{{ url('brands') }}"> <?php echo __('messages.brands'); ?></a> </li>
                                    <li> <a href="{{ url('help_center') }}"><?php echo __('messages.help_center'); ?></a> </li>
                                    <!-- <li> <a href="javascript:;"> Affiliates</a> </li> -->
                                    <!-- <li> <a href="javascript:;"> Specials</a> </li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nx-foot-card">
                <div class="app-now-link">
                    <h4><?php echo __('messages.download_the_app_now'); ?></h4>
                    <ul>
                        <li>
                            <a href="{{ $global->playstoreurl }}">
                                <img src="{{ asset('assets/front-end/images/google-play.png') }}">
                            </a>
                        </li>
                        <li>
                            <a href="{{ $global->appstoreurl }}">
                                <img src="{{ asset('assets/front-end/images/app-store.png') }}">
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="pay-card-link">
                    <a href="#"><img src="{{ asset('assets/front-end/images/shop-cr-img.png') }}"></a>
                </div>
            </div>
        </div>
        <div class="foot-copy-right">
            @if (\App::getLocale() == 'en')
                <h6>{{ $global->copyright_en }}</h6>
            @else
                <h6>{{ $global->copyright_ar }}</h6>
            @endif
            <a class="partner-logo" href="#"><img src="{{ asset('assets/front-end/images/maroof.png') }}"></a>
        </div>
    </footer>
</div>

</body>

<div class="modal fade come-from-modal right" id="my-cart" tabindex="-1" role="dialog" aria-labelledby="my-cartLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <?php $product = Session::get('product'); ?>
                <?php if (!empty($product)) {
                    $productdatas = get_data($product);
                } ?>
                <h5 class="modal-title" id="my-cartLabel"><?php echo __('messages.my_cart'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-cart">
                <span id="cartship">
                    @if (isset($productdatas['ship']))
                        @if ($productdatas['ship'] > 0)
                            @if (\App::getLocale() == 'en')
                                <h6 class="sub-title-cart"><b>Add <span style=color:green;>SR
                                            {{ $productdatas['ship'] }}</span> To Get <span style=color:green;>Free
                                            Shipping</span></b></h6>
                            @else
                                <h6 class="sub-title-cart"><b>اضف <span style=color:green;>ريال
                                            {{ $productdatas['ship'] }}</span> لتحصل على <span style=color:green;>شحن
                                            مجاني</span></b></h6>

                            @endif
                        @else
                            <h6 class="sub-title-cart"><b><?php echo __('messages.eligible_to_free_shipping'); ?></b></h6>
                        @endif
                    @endif
                </span>
                @if (!empty($productdatas['data']))
                    @foreach ($productdatas['data'] as $firstkey => $cartproduct)
                        @foreach ($cartproduct as $key => $cartproductdata)
                            <div class="cart-cover">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="pro-info">
                                            <figure><img
                                                    src="{{ url('/') }}/public/product_images/{{ $cartproductdata['img'] }}">
                                            </figure>
                                            <figcaption>

                                                <h3>@if (\App::getLocale() == 'en'){{ $cartproductdata['name_en'] }}@else {{ $cartproductdata['name_ar'] }}@endif
                                                    @if ($cartproductdata['quantity'] == 1)
                                                        <span><?php echo __('messages.only'); ?> {{ $cartproductdata['quantity'] }}
                                                            <?php echo __('messages.left'); ?></span>
                                                    @endif
                                                </h3>

                                                @if (isset($cartproductdata['color']))
                                                    @if ($cartproductdata['color'] != 'nocolor')
                                                        <?php $productoptiondatas = getproductoptionsdetails($cartproductdata['color']); ?>
                                                        @if (!empty($productoptiondatas->color))
                                                            <h5>Colour - <span
                                                                    style="display: block;width: 22px;height: 22px;background:{{ $productoptiondatas->color }};border-radius: 4px;margin-left:10px;"></span>
                                                            </h5>
                                                        @endif
                                                        @if (!empty($productoptiondatas->optionname))
                                                            <h5>{{ $productoptiondatas->optionname }} -
                                                                {{ $productoptiondatas->optionvalue }} </h5>
                                                        @endif
                                                    @endif
                                                @endif
                                                <div class="pr-rate d-flex">
                                                    <h4>SR {{ $cartproductdata['price'] }} @if (!empty($cartproductdata['offer_price'])) <font>SR {{ $cartproductdata['offer_price'] }} </font>@endif
                                                    </h4>
                                                </div>
                                            </figcaption>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="quantity-box">
                                            @if (!empty($cartproductdata['discount_available']))
                                                <span
                                                    class="offer-here">{{ $cartproductdata['discount_available'] }}%
                                                    <?php echo __('messages.off'); ?></span>
                                            @endif
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-btn">
                                                        <button id="down" class="btn waves-effect waves-light"
                                                            onclick="decreasequantity('{{ $cartproductdata['id'] }}','{{ $cartproductdata['color'] }}');"><i
                                                                class="fas fa-minus"></i></button>
                                                    </div>
                                                    <input type="text"
                                                        id="{{ $cartproductdata['id'] }}{{ $cartproductdata['color'] }}"
                                                        class="form-control input-number"
                                                        value="{{ $cartproductdata['req_quantity'] }}" readonly>
                                                    <div class="input-group-btn">
                                                        <button id="up" class="btn waves-effect waves-light"
                                                            onclick="increasequantity('{{ $cartproductdata['id'] }}','{{ $cartproductdata['color'] }}');"><i
                                                                class="fas fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <ul class="cart-re-move">
                                            <li> <a
                                                    onclick="remove_from_cart('{{ $cartproductdata['id'] }}','{{ $cartproductdata['color'] }}','{{ $cartproductdata['name_en'] }}','{{ auth()->user() }}')"><i
                                                        class="ri-delete-bin-5-fill"></i><?php echo __('messages.remove'); ?></a></li>
                                            <li>
                                                @if (!empty(Auth::id()))
                                                    <a onclick="addtowishlist('{{ $cartproductdata['id'] }}');"><i
                                                            class="ri-heart-fill"></i><?php echo __('messages.move_to_wishlist'); ?></a>
                                                @else
                                                    <a href="javascript:;" data-toggle="modal" data-dismiss="modal"
                                                        data-target="#login-modal"><i
                                                            class="ri-heart-fill"></i><?php echo __('messages.move_to_wishlist'); ?></a>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @else

                    <div class="no-avilable-here">
                        <div class="no-avilable">
                            <figure><img src="{{ asset('assets/front-end/images/no-producat.svg') }}"></figure>
                            <figcaption>
                                <h4><?php echo __('messages.no_product_available'); ?></h4>
                            </figcaption>
                        </div>
                    </div>

                @endif
            </div>
            <div class="modal-footer items-box">
                <div class="items-bx">
                    <h6><?php echo __('messages.total'); ?> : <span id="cartcount_cart"><?php if (!empty($productcount)) {
    echo $productcount;
} else {
    echo 0;
} ?></span>
                        <?php echo __('messages.items'); ?> </h6>
                    <!-- <h2>SR 113</h2> -->
                </div>
                @if (!empty(Auth::id()))
                    @if (!empty($productcount) && $productcount > 0)
                        <button class="btn btn-coffee"
                            onclick="trackcheckout('{{ auth()->user() }}')"><?php echo __('messages.continue_to_checkout'); ?></button>
                    @else
                        <button type="button" class="btn btn-coffee" href="{{ url('checkoutaddress') }}"
                            disabled><?php echo __('messages.continue_to_checkout'); ?></button>
                    @endif
                @else
                    <a href="javascript:;" class="btn btn-coffee" data-toggle="modal" data-dismiss="modal"
                        data-target="#login-modal"><?php echo __('messages.continue_to_checkout'); ?></a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal login-modal-box fade" id="login-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <form id="login" action="{{ url('user.login') }}" method="POST">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!--  <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <a href="javascript:;" class="modal-logo">
                        <img src="{{ asset('assets/front-end/images/logo.svg') }}">
                    </a>
                    <div class="success-message" id="successlogin"></div>
                    <div class="login-head">
                        <h4><?php echo __('messages.welcome_back'); ?></h4>
                        <p><?php echo __('messages.sign_in_to_continue'); ?></p>
                    </div>
                    <div class="login-form">
                        <div class="form-group">
                            <input type="text" name="email" id="email" autocomplete="off"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="<?php echo __('messages.email_or_mobile_no'); ?>">
                            <div class="alert-message" id="emailError"></div>
                            <div id="error-caption">
                            </div>
                        </div>

                        <div class="form-group input-group">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror viewhidepassword"
                                placeholder="<?php echo __('messages.password'); ?>" autocomplete="off">
                            <div class="alert-message" id="passwordError"></div>
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2"><i class="ri-eye-fill"
                                        id="toggle2"></i></span>
                            </div>
                        </div>
                        <div id="error-caption1" style="margin-top: -15px;">
                        </div>
                        <div class="forgot-pass">
                            <a href="javascript:;" data-toggle="modal" data-dismiss="modal"
                                data-target="#for-password-modal" id="forgot-link"><?php echo __('messages.forget_password'); ?></a>
                        </div>
                        <button type="button" id="loginbutton" onclick="validateEmail();"
                            class="btn btn-black w-100"><?php echo __('messages.login'); ?></button>
                        <span class="new-ac-text pt-1"> <a href="javascript:;" id="register-link" data-toggle="modal"
                                data-target="#login-with-mobile" data-dismiss="modal"><?php echo __('messages.login_with_mobile'); ?></a></span>

                        <div class="or-divi">
                            <span> <strong> <?php echo __('messages.or'); ?> </strong> </span>
                        </div>
                        <div class="socail-login">
                            <a href="{{ url('login.facebook') }}"><img
                                    src="{{ asset('assets/front-end/images/fb.svg') }}"></a>
                            <a href="{{ url('login.google') }}"><img
                                    src="{{ asset('assets/front-end/images/google.svg') }}"></a>
                        </div>
                        <span class="new-ac-text"><?php echo __('messages.do_not_have_an_account'); ?> <a href="javascript:;" id="register-link"
                                data-toggle="modal" data-target="#create-account-modal"
                                data-dismiss="modal"><?php echo __('messages.please_register'); ?></a></span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- login modal -->

<!-- forgot password modal -->
<div class="modal login-modal-box fade" id="for-password-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <a href="javascript:;" class="modal-logo">
                    <img src="{{ asset('assets/front-end/images/logo.svg') }}">
                </a>
                <div class="success-message" id="successforget"></div>
                <div class="login-head">
                    <h4><?php echo __('messages.forget_password'); ?></h4>
                    <p><?php echo __('messages.enter_the_email'); ?></p>
                </div>
                <div class="login-form">
                    <div class="form-group">
                        <input type="text" name="forgetemail" id="forgetemail"
                            class="form-control @error('forgetemail') is-invalid @enderror"
                            placeholder="<?php echo __('messages.email_id'); ?>" autocomplete="off">
                        <div class="alert-message" id="emailErrorforget"></div>
                        <div id="error-caption2">
                        </div>
                    </div>

                    <button type="button" id="forgetbutton" onclick="validateforgotpassword();"
                        class="btn btn-black w-100"><?php echo __('messages.sendforget'); ?></button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- forgot password modal -->

<!-- logout  modal -->
<div class="modal login-modal-box fade" id="log-out-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <a href="javascript:;" class="modal-logo">
                    <img src="{{ asset('assets/front-end/images/logo.svg') }}">
                </a>
                <h5 style="margin-bottom: 55px;"><?php echo __('messages.are_you_sure_to_logout'); ?></h5>
                <div class="log-out-form">
                    <div class="d-flex">
                        <button type="button" data-dismiss="modal" class="btn btn-black"><?php echo __('messages.no'); ?></button>
                        <button type="button"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="btn btn-black"><?php echo __('messages.yes'); ?></button>
                        <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- logout  modal -->

<!-- logout  modal -->
<div class="modal login-modal-box fade" id="otp-verify-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <a href="javascript:;" class="modal-logo">
                    <img src="{{ asset('assets/front-end/images/logo.svg') }}">
                </a>
                <p><?php echo __('messages.enter_otp_verfiy'); ?></p>
                <input type="text" class="form-control" name="otp" id="otp" Placeholder="<?php echo __('messages.enter_otp'); ?>"
                    maxlength="4" minlength="4">
                <div id="error-caption10"> </div>
                <div class="log-out-form">
                    <div class="d-flex">
                        <button type="button" onclick="verifyotp();"
                            class="btn btn-black"><?php echo __('messages.submit_otp'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- logout  modal -->

<!-- logout  modal -->
<div class="modal login-modal-box fade" id="otp-verify-login-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <a href="javascript:;" class="modal-logo">
                    <img src="{{ asset('assets/front-end/images/logo.svg') }}">
                </a>
                <p>Enter Otp To Verify Mobile Number</p>
                <input type="text" class="form-control" name="otplogin" id="otplogin" Placeholder="Enter Otp"
                    maxlength="4" minlength="4">
                <div id="error-caption11"> </div>
                <div class="log-out-form">
                    <div class="d-flex">
                        <button type="button" onclick="verifyotplogin();"
                            class="btn btn-black"><?php echo __('messages.submit'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- logout  modal -->

<!--login with mobile-->
<div class="modal login-modal-box fade" id="login-with-mobile" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <a href="javascript:;" class="modal-logo">
                    <img src="{{ asset('assets/front-end/images/logo.svg') }}">
                </a>
                <p><?php echo __('messages.enter_mobile_number'); ?></p>
                <div class="form-group">
                    <div class="material-div mobile-img-text">
                        <div class="mobile-img-text-area"><img
                                src="{{ asset('assets/front-end/images/data-img-fl.png') }}">
                            <font>+966 |</font>
                        </div>
                        <input type="number" class="form-control @error('mobile') is-invalid @enderror"
                            name="mobilenumber" id="mobilenumber" Placeholder="5xxxxxxxx">
                    </div>
                </div>
                <div id="error-caption15"> </div>
                <div class="log-out-form">
                    <div class="d-flex">
                        <button type="button" onclick="loginwithmobile();"
                            class="btn btn-black"><?php echo __('messages.submit_otp'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!--login with mobile-->




<!-- Create Your Account modal -->
<div class="modal login-modal-box fade" id="create-account-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <form id="createuser" action="{{ url('user.register') }}" method="POST">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!--  <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5> -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <a href="javascript:;" class="modal-logo">
                        <img src="{{ asset('assets/front-end/images/logo.svg') }}">
                    </a>
                    <div class="success-message" id="success"></div>
                    <div class="login-head">
                        <h4><?php echo __('messages.create_your_account'); ?></h4>
                    </div>
                    <div class="login-form">
                        <div class="form-group">
                            <input type="text" name="fullname" id="fullname"
                                class="form-control @error('fullname') is-invalid @enderror"
                                placeholder="<?php echo __('messages.full_name'); ?>" autocomplete="off">
                            <div class="alert-message" id="nameError"></div>
                            <div id="error-caption3"> </div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="createemail" id="createemail"
                                class="form-control @error('createemail') is-invalid @enderror"
                                placeholder="<?php echo __('messages.email_address'); ?>" autocomplete="off">
                            <div class="alert-message" id="emailError1"></div>
                            <div id="error-caption4">
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="form-group">
                                <div class="material-div mobile-img-text">
                                    <div class="mobile-img-text-area"><img
                                            src="{{ asset('assets/front-end/images/data-img-fl.png') }}">
                                        <font>+966 |</font>
                                    </div>
                                    <input type="text" name="mobile" id="mobile"
                                        class="form-control @error('mobile') is-invalid @enderror"
                                        placeholder="5xxxxxxxx" autocomplete="off">
                                </div>
                            </div>
                            <div class="alert-message" id="mobileError"></div>
                            <div id="error-caption5">
                            </div>
                        </div>
                        <div class="form-group input-group">
                            <input type="password" name="createpassword" id="createpassword"
                                class="form-control @error('createpassword') is-invalid @enderror viewhidepassword"
                                placeholder="<?php echo __('messages.password'); ?>" autocomplete="off">
                            <div class="alert-message" id="passwordError"></div>
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2"><i id="toggle"
                                        class="ri-eye-fill"></i></span>
                            </div>
                        </div>
                        <div id="error-caption6" style="margin-top: -15px;">
                        </div>
                        <div class="forgot-pass">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customControlInline">
                                <label class="custom-control-label" for="customControlInline">
                                    <?php echo __('messages.by_signing_up_you_accept_all_our'); ?>
                                    <a href="{{ url('/terms') }}"><?php echo __('messages.terms_and_policies'); ?> </a>
                                </label>
                                <div id="error-caption7">
                                </div>
                            </div>
                            <button type="button" id="createbutton" onclick="validatecreateuser();"
                                class="btn btn-black w-100 create_user"><?php echo __('messages.submit'); ?></button>
                            <div class="new-ac-text center-pdd">
                                <span class="new-ac-text"><?php echo __('messages.do_not_have_an_account_login'); ?> <a href="javascript:;"
                                        data-dismiss="modal" data-toggle="modal" data-target="#login-modal"
                                        id="login-link"><?php echo __('messages.login'); ?></a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/front-end/js/jquery-3.3.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/front-end/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/front-end/js/mdb.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/front-end/js/owl.carousel.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="{{ asset('assets/plugins/notifications/js/customefront.js') }}"></script>
@if (\App::getLocale() == 'en')
    <script src="{{ asset('assets/plugins/notifications/js/customefrontvalidation.js') }}"></script>
@else
    <script src="{{ asset('assets/plugins/notifications/js/customefrontvalidationar.js') }}"></script>
@endif
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/gh/igorlino/elevatezoom-plus@1.2.3/src/jquery.ez-plus.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css"
    integrity="sha512-nNlU0WK2QfKsuEmdcTwkeh+lhGs6uyOxuUs+n+0oXSYDok5qy0EI0lt01ZynHq6+p/tbgpZ7P+yUb+r71wqdXg=="
    crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"
integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA=="
crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    $(window).scroll(function() {
        var body = $('body'),
            scroll = $(window).scrollTop();
        if (scroll >= 5) {
            body.addClass('fixed');
        } else {
            body.removeClass('fixed');
        }
    });
</script>
<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAlljhS4LLf_864PqbvXtDGXver091jLiI&callback=initMap&libraries=places&v=weekly"
async></script>


<script type="text/javascript">
    $(document).ready(function() {
        $("#search").autocomplete({
            source: basrurl + '/autocompleteajax',
            focus: function(event, ui) {
                //$( "#search" ).val( ui.item.title ); // uncomment this line if you want to select value to search box
                return false;
            },
            select: function(event, ui) {
                window.location.href = ui.item.url;
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            console.log(item.url);
            var inner_html = '<a href="' + item.url +
                '" ><div class="list_item_container"><div class="searchimage"><img src="https://www.letsbuysa.com/public/product_images/' +
                item.image + '" ></div><div class="labelpro"><h4>' + item.title + '</h4><span>' + "SAR " +
                item.price + '</span></div></div></a>';
            return $("<li></li>")
                .data("item.autocomplete", item)
                .append(inner_html)
                .appendTo(ul);
        };

        var render = $('#search').autocomplete('instance')._renderMenu;

        $('#search').autocomplete('instance')._renderMenu = function(ul, items) {

            var self = this;
            $.each(items, function(index, item) {
                self._renderItem(ul, item);
                if (index == items.length - 1)
                    ul.append(
                        '<li id="load_more" onclick="chaangeEvenet()" style="max-height:30px; text-align:center;"><h5><?php echo __('messages.loadmore'); ?><h5></li>'
                    );
            });
        };

    });
</script>
<script>
    function chaangeEvenet() {
        document.getElementById("productsearchform").submit();
    }
</script>


<script type="text/javascript">
    $('.banner-slider').owlCarousel({
        loop: true,
        nav: true,
        dot: true,
        mouseDrag: false,
        autoplay: true,
        smartSpeed: 3000,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });
</script>
<script type="text/javascript">
    $('.brand-logos-slider').owlCarousel({
        loop: false,
        margin: 20,
        nav: true,
        dot: false,
        mouseDrag: false,
        autoplay: true,
        smartSpeed: 3000,
        responsive: {
            0: {
                items: 3
            },
            480: {
                items: 3
            },
            600: {
                items: 4
            },
            768: {
                items: 5
            },
            1000: {
                items: 6
            }
        }
    });
</script>
<script type="text/javascript">
    $('.hot-today-slider, .hot-deals-slider, .trending-slider, .best-seller-slider, .new-arrivales-slider')
        .owlCarousel({
            loop: true,
            margin: 10,
            mouseDrag: false,
            nav: true,
            dots: false,
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                735: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        });
</script>
<script type="text/javascript">
    $('.customer-carousel').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        dot: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 3
            }
        }
    });
</script>
<script type="text/javascript">
    $('.hot-today-slider, .hot-deals-slider, .trending-slider, .best-seller-slider, .similar-products-slider')
        .owlCarousel({
            loop: true,
            margin: 10,
            mouseDrag: false,
            nav: true,
            dots: false,
            responsive: {
                0: {
                    items: 2
                },
                480: {
                    items: 3
                },
                680: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        });
</script>

<script type="text/javascript">
    $(function() {
        $('.slider-thumb').slick({
            autoplay: false,
            vertical: true,
            infinite: true,
            verticalSwiping: true,
            slidesPerRow: 4,
            slidesToShow: 4,
            asNavFor: '.slider-preview',
            focusOnSelect: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-up"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-down"></i></button>',
            responsive: [{
                    breakpoint: 769,
                    settings: {
                        vertical: false,
                    }
                },
                {
                    breakpoint: 479,
                    settings: {
                        vertical: false,
                        slidesPerRow: 3,
                        slidesToShow: 3,
                    }
                },
            ]
        });
        $('.slider-preview').slick({
            autoplay: false,
            vertical: true,
            infinite: true,
            slidesPerRow: 1,
            slidesToShow: 1,
            asNavFor: '.slider-thumb',
            arrows: false,
            draggable: false,
            responsive: [{
                breakpoint: 769,
                settings: {
                    vertical: false,
                    fade: true,
                }
            }, ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#toggle").click(function() {

            if ($("#createpassword").attr("type") == "password") {
                //Change type attribute
                $("#createpassword").attr("type", "text");
                $(this).removeClass('ri-eye-fill');
                $(this).addClass('ri-eye-off-fill');
            } else {
                //Change type attribute
                $("#createpassword").attr("type", "password");
                $(this).removeClass('ri-eye-off-fill');
                $(this).addClass('ri-eye-fill');

            }
        });

        $("#toggle2").click(function() {

            if ($("#password").attr("type") == "password") {
                //Change type attribute
                $("#password").attr("type", "text");
                $(this).removeClass('ri-eye-fill');
                $(this).addClass('ri-eye-off-fill');
            } else {
                //Change type attribute
                $("#password").attr("type", "password");
                $(this).removeClass('ri-eye-off-fill');
                $(this).addClass('ri-eye-fill');

            }
        });

    });
</script>
<script>
    function initMap() {
        const myLatlng = {
            lat: 24.7136,
            lng: 46.6753
        };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: myLatlng,
        });

        var input = document.getElementById('pac-input');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            $('#lat').val(place.geometry['location'].lat());
            $('#long').val(place.geometry['location'].lng());
            $('#fulladdress').val($("#pac-input").val());
            $('#locationtext').html($("#pac-input").val());

            map.setCenter(place.geometry['location']);

        });

        // Create the initial InfoWindow.
        let infoWindow = new google.maps.InfoWindow({
            content: "Choose Your Address",
            position: myLatlng,
        });
        infoWindow.open(map);
        // Configure the click listener.
        map.addListener("click", (mapsMouseEvent) => {
            // Close the current InfoWindow.
            infoWindow.close();
            // Create a new InfoWindow.
            infoWindow = new google.maps.InfoWindow({
                position: mapsMouseEvent.latLng,
            });
            infoWindow.setContent(
                JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)
            );
            infoWindow.open(map);
            var geocoder = new google.maps.Geocoder();
            var infowindow = new google.maps.InfoWindow();
            var data = mapsMouseEvent.latLng;
            var mylat = mapsMouseEvent.latLng.lat();
            var mylong = mapsMouseEvent.latLng.lat();
            geocodeLatLng(geocoder, map, infowindow, data, mylat, mylong);

        });


        const locationButton = document.createElement("button");
        locationButton.textContent = "Pan to Current Location";
        locationButton.classList.add("custom-map-control-button");
        locationButton.setAttribute("id", "serachcurrentlocation");
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
        locationButton.addEventListener("click", () => {
            // Try HTML5 geolocation.

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        var geocoder = new google.maps.Geocoder();
                        var infowindow = new google.maps.InfoWindow();
                        var data = pos;
                        var mylat = position.coords.latitude;
                        var mylong = position.coords.longitude;

                        geocodeLatLng(geocoder, map, infowindow, data, mylat, mylong);

                        map.setCenter(pos);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );

            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        });
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(
            browserHasGeolocation ?
            "Error: The Geolocation service failed." :
            "Error: Your browser doesn't support geolocation."
        );
        infoWindow.open(map);
    }

    function geocodeLatLng(geocoder, map, infowindow, data, mylat, mylong) {
        geocoder.geocode({
            location: data
        }, (results, status) => {
            if (status === "OK") {
                if (results[0]) {
                    map.setZoom(17);
                    const marker = new google.maps.Marker({
                        map: map,
                    });
                    $('#pac-input').val(results[0].formatted_address);
                    $('#fulladdress').val(results[0].formatted_address);
                    $('#lat').val(mylat);
                    $('#long').val(mylong);
                    $('#locationtext').html(results[0].formatted_address);

                    infowindow.setContent(results[0].formatted_address);
                } else {
                    window.alert("No results found");
                }
            } else {
                window.alert("Geocoder failed due to: " + status);
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $("#serachcurrentlocation").trigger('click');
        }, 4000);
    });
</script>
<script>
    $('#zoom_01').ezPlus();
    $('#zoom_02').ezPlus();
    $('#zoom_03').ezPlus();
    $('#zoom_04').ezPlus();
    $('#zoom_05').ezPlus();
    $('#zoom_06').ezPlus();
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".fancybox").fancybox();
    });
</script>
<script>
    $(document).ready(function() {
        var myproduct = $('#myproduct').val();
        var mycolor = $('#mycolor').val();
        if (myproduct != '' && mycolor != '') {
            colorselect(mycolor, myproduct);
        }
    });
</script>
<script type="text/javascript">
    $(window).on('load', function() {
        $('#offerbanner').modal('show');
    });
</script>
<script>
    $(document).ready(function() {
        $('#setlocationbutton').click(function() {
            var address = $('#pac-input').val();
            if (address != '') {
                $(".map-flex-tow").css("display", "block");
                $("#pac-input").hide();
            }
        });
    });

    $(document).on('click', '#map', function() {
        $(".map-flex-tow").css("display", "none");
        $("#pac-input").show();
    });
</script>
<script>
    $(document).on('click', '.address_select', function() {
        $(this).find('input').prop('checked', true)
        $("#address_id").val($(this).find('input').attr("id"));
    });
</script>
<script>
    $(document).ready('.address_select', function() {
        $("#address_id").val($(this).find('input').attr("id"));
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('#scroll').fadeIn();
            } else {
                $('#scroll').fadeOut();
            }
        });
    });


    $("#forgot-link").on("click", function() {
        $('#login-modal').modal('hide');
    });

    $("#register-link").on("click", function() {
        $('#login-modal').modal('hide');
    });

    $("#login-link").on("click", function() {
        $('#create-account-modal').modal('hide');
    });
</script>
<script>
    CKEDITOR.replace('order_cancel_reason');
</script>
<script>
    @jquery
    @toastr_js
    @toastr_render
</script>
<script>
    var basrurl = "<?= url('/') ?>";
</script>
@if (Session::has('remove'))
    <script>
        $('#my-cart').modal('show');
    </script>


@endif


</html>
