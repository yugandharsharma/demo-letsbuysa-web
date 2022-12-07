@extends('layouts.front-app')

@section('content')

    <script src="https://eu-prod.oppwa.com/v1/paymentWidgets.js?checkoutId={{ $data }}">
    </script>

    <section class="bread-sec-nav">
        <div class="container">
            <nav class="breadcrumb-nav">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item bold"><a href="{{ url('/') }}"><?php echo __('messages.home'); ?></a></li>
                    <li class="breadcrumb-item bold"><a href="{{ url('/checkoutaddress') }}"><?php echo __('messages.check_out_address'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#"><?php echo __('messages.check_out_payment'); ?></a></li>
                </ul>
            </nav>
        </div>
    </section>

    <section class="next-dash check-dash">
        @php
            $locale = Config::get('app.locale');
        @endphp
        <input type="hidden" id="locale" value="{{ $locale }}">
        <div class="container">
            <ul class="progress-container">
                <div class="progress" id="progress"></div>
                <li class="active">
                    <span class="circle">1</span>
                    <h6><?php echo __('messages.address'); ?></h6>
                </li>
                <li class="active">
                    <span class="circle">2</span>
                    <h6><?php echo __('messages.payment'); ?></h6>
                </li>
                <li>
                    <span class="circle">3</span>
                    <h6><?php echo __('messages.pay_now'); ?></h6>
                </li>
            </ul>
            <div class="row">
                <div class="col-md-8">
                    <div class="pay-method-box">
                        <div class="payment-method-in">
                            <h4><?php echo __('messages.delivery_address'); ?></h4>
                            <h4>{{ $address->fullname }}</h4>
                            <h5>{{ $address->fulladdress }}</h5>
                            <h5>{{ $address->mobile }}</h5>
                        </div>
                        <span class="line-banner3 full-100"></span>
                        <div class="payment-method-in">
                            <h4><?php echo __('messages.payment_method'); ?></h4>
                            <div id="paymenthidden">
                                <ul class="pay_ment_check">
                                    @if ($global->tappayment == 1)
                                        <li class="d-flex">

                                            <input type="hidden" value="{{ $address_id }}" id=address_id>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio1"
                                                    onclick="checkdelivery(),getvalue(this); add_payment_info('{{ $address->fullname }}');"
                                                    name="paymenttype" value="1" class="custom-control-input"
                                                    {{ $types == 1 ? ' checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="customRadio1"><?php echo __('messages.credit_or_debit_card'); ?></label>
                                            </div>
                                            <div class="list-pay-card">
                                                <a href="javascript:;">
                                                    <img src="{{ asset('assets/front-end/images/visa.svg') }}">
                                                </a>
                                                <a href="javascript:;">
                                                    <img src="{{ asset('assets/front-end/images/mastercard.svg') }}">
                                                </a>
                                            </div>
                                        </li>
                                        @if ($types == 1)
                                            <form action="{{ url('hyperpay_payment/' . $data . '/' . $entityId) }}"
                                                class="paymentWidgets" data-brands="{{ $method }}"></form>
                                        @endif
                                        <li class="d-flex">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio15"
                                                    onclick="checkdelivery(),getvalue(this); add_payment_info('{{ $address->fullname }}');"
                                                    name="paymenttype" value="3" class="custom-control-input"
                                                    {{ $types == 3 ? ' checked' : '' }}>
                                                <label class="custom-control-label"
                                                    for="customRadio15"><?php echo __('messages.mada_payment'); ?></label>
                                            </div>
                                            <div class="list-pay-card">
                                                <a href="javascript:;">
                                                    <img src="{{ asset('assets/front-end/images/mada-logo.svg') }}">
                                                </a>
                                            </div>
                                        </li>
                                        @if ($types == 3)
                                            <form action="{{ url('hyperpay_payment/' . $data . '/' . $entityId) }}"
                                                class="paymentWidgets" data-brands="{{ $method }}"></form>
                                        @endif
                                    @endif
                                    <div style="display:none;">
                                        <input type="radio" id="customRadio3" onclick="checkdelivery();" name="paymenttype"
                                            value="3" class="custom-control-input">
                                    </div>
                                </ul>
                            </div>
                            <form method="post" id="paymentform" action="{{ url('order') }}">
                                @csrf
                                <ul class="pay_ment_check">
                                    <div id="paymenthidden1">
                                        @if ($global->codpayment == 1 && $addressCodExist == true)
                                            <li>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio2"
                                                        onclick="getvalue(this);checkdelivery(); add_payment_info('{{ $address->fullname }}');"
                                                        name="paymenttype" value="2" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="customRadio2"><?php echo __('messages.cash_on_delivery'); ?></label>
                                                    <p><?php echo __('messages.cash_on_delivery'); ?> ( {{ $delivery_charge }} SR
                                                        <?php echo __('messages.extra_fee'); ?>)
                                                    </p>
                                                    <div id="submit2">
                                                        <button style="margin-top: 10px;margin-left: -25px;" disabled
                                                            onclick="purchaseOrder()"
                                                            class="btn btn-black waves-effect waves-light paymentsubmit"><?php echo __('messages.pay_now'); ?></button>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                        @if ($global->bank_transfer == 1)
                                            <li>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio4"
                                                        onclick="checkdelivery(), getvalue(this); add_payment_info('{{ $address->fullname }}');"
                                                        name="paymenttype" value="5" class="custom-control-input">
                                                    <label class="custom-control-label"
                                                        for="customRadio4"><?php echo __('messages.bank_transfer'); ?></label>
                                                    <p>National Commercial Bank<br />IBAN (SA 09 1000 0013 8471 8800
                                                        6605)<br />_____________________________________________<br /><br />Al
                                                        Rajhi Bank<br />IBAN (SA 10 8000 0376 6080 1093 5845)</p>
                                                    <div id="submit5">
                                                        <button style="margin-top: 10px;margin-left: -25px;" disabled
                                                            onclick="purchaseOrder()"
                                                            class="btn btn-black waves-effect waves-light paymentsubmit"><?php echo __('messages.pay_now'); ?></button>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    </div>
                                    @if (isset($wallet) && !empty($wallet) && $wallet->amount > 0 && 0 != ($walletLessAmount > $wallet->amount ? 0 : $wallet->amount - $walletLessAmount))
                                        <li class="d-flex" id="walletdisable">
                                            <div class="custom-control custom-checkbox">
                                                <input type="radio" name="paymenttype" value="wallet" id="walletPaycheck"
                                                    style="display:none">
                                                <input type="checkbox" class="custom-control-input" name="wallet"
                                                    onclick="checkwallet(),walletPay();getvalue(this);" id="wallet"
                                                    value="{{ $wallet->amount }}">
                                                <label class="custom-control-label" for="wallet"><?php echo __('messages.use_wallet_balance'); ?> SR
                                                    {{ $walletLessAmount > $wallet->amount ? 0 : $wallet->amount - $walletLessAmount }}</label>
                                            </div>
                                            <div class="list-pay-card">
                                                <a href="javascript:;"><i class="ri-wallet-3-fill"></i></a>
                                            </div>
                                            <div id="submit6">
                                                <button style="margin-top: 10px;margin-left: -25px;" disabled
                                                    onclick="purchaseOrder()"
                                                    class="btn btn-black waves-effect waves-light paymentsubmit"><?php echo __('messages.pay_now'); ?></button>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                                <input type="hidden" value="{{ $order_price }}" name="order_price" id="order_price">
                                @error('paymenttype')
                                    <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </form>
                        </div>
                    </div>
                    <div class="payment-items">
                        <?php $productcount = getitemcount(); ?>
                        <h2><span id="totalquantityremain1"><?php if (!empty($productcount)) {
    echo $productcount;
} else {
    echo $productcount;
} ?></span> <?php echo __('messages.items'); ?></h2>
                        <?php $product = Session::get('product'); ?><?php if (!empty($product)) {
                            $productdatas = get_data($product);
                        } ?>
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
                                                        <h3>@if (\App::getLocale() == 'en'){{ $cartproductdata['name_en'] }}@else {{ $cartproductdata['name_ar'] }} @endif
                                                            @if ($cartproductdata['quantity'] == 1)
                                                                <span><?php echo __('messages.only'); ?>
                                                                    {{ $cartproductdata['quantity'] }}
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
                                                        <ul class="cart-re-move">
                                                            <li> <a
                                                                    href="{{ url('removecart', ['id' => $cartproductdata['id'], 'color' => $cartproductdata['color']]) }}"><i
                                                                        class="ri-delete-bin-5-fill"></i><?php echo __('messages.remove'); ?></a>
                                                            </li>
                                                            <li>
                                                                @if (!empty(Auth::id()))
                                                                    <a
                                                                        onclick="addtowishlist('{{ $cartproductdata['id'] }}');"><i
                                                                            class="ri-heart-fill"></i><?php echo __('messages.move_to_wishlist'); ?></a>
                                                                @else
                                                                    <a href="javascript:;" data-toggle="modal"
                                                                        data-dismiss="modal" data-target="#login-modal"><i
                                                                            class="ri-heart-fill"></i><?php echo __('messages.move_to_wishlist'); ?></a>
                                                                @endif
                                                            </li>
                                                        </ul>
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
                                                    @if (!empty($cartproductdata['offer_price']))
                                                        <div class="pr-rate d-flex pb-4 ">
                                                        @else
                                                            <div class="pr-rate d-flex pb-4 " id="offerclass">
                                                    @endif
                                                    <h4>SR {{ $cartproductdata['price'] }} @if (!empty($cartproductdata['offer_price'])) <font>SR {{ $cartproductdata['offer_price'] }} </font>@endif</h4>
                                                </div>
                                                <div class="d-flex">
                                                    <h6><?php echo __('messages.quantity'); ?></h6>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-btn">
                                                                <button id="down" class="btn waves-effect waves-light"
                                                                    onclick="decreasequantity('{{ $cartproductdata['id'] }}','{{ $cartproductdata['color'] }}');"><i
                                                                        class="fas fa-minus"></i></button>
                                                            </div>
                                                            <input type="text"
                                                                id="cart{{ $cartproductdata['id'] }}{{ $cartproductdata['color'] }}"
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
                                        </div>
                                    </div>
                    </div>
                    @endforeach
                    @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="coupon-bx">
                    <div id="discount_available">
                        <h3><?php echo __('messages.coupon_code'); ?></h3>
                        <div class="form-group d-flex">
                            <input type="text" name="coupan" id="coupan" class="form-control"
                                placeholder="<?php echo __('messages.coupon_code'); ?>">
                            <button type="button" onclick="checkcoupan();"
                                class="btn btn-black"><?php echo __('messages.apply'); ?></button>
                        </div>
                        <div class="form-group d-flex">
                            <p id="error-coupan"></p>
                        </div>
                        <span class="line-banner3 full-100"></span>
                    </div>

                    <!-- <div class="form-group d-flex">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <input type="text" name="" class="form-control" placeholder="Gift certificate code">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <button type="submit" class="btn btn-black">Apply</button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div> -->

                    <h3><?php echo __('messages.order_summary'); ?></h3>
                    <span class="line-banner3 full-100"></span>
                    <div class="order-info">
                        <h6><?php echo __('messages.items'); ?>:</h6>
                        <span id="totalquantityremain"><?php if (!empty($productcount)) {
    echo $productcount;
} else {
    echo $productcount;
} ?></span>
                    </div>
                    <div class="order-info">
                        <h6><?php echo __('messages.sub_total'); ?>:</h6>
                        <span id="total">SR {{ $order_price_subtotal }}</span>
                        <input type="hidden" value="{{ $order_price_subtotal }}" id=total_amount>
                    </div>
                    <div class="order-info" style="display:none" id="discountprice">
                        <div class="cash-On-delivery">
                            <h6><?php echo __('messages.discount_price'); ?>:</h6>
                            <span id="discount">SR 0</span>
                        </div>
                    </div>

                    <div class="order-info">
                        <h6><?php echo __('messages.shipping_charge'); ?>:</h6>
                        <span id="shipping">SR {{ $ship_charge }}</span>
                        <input type="hidden" value="{{ $ship_charge }}" id=ship_input>
                    </div>
                    @if (isset($discounted_price))
                        <div class="order-info" id="discountpricePay">
                            <h6><?php echo __('messages.discount_price'); ?>:</h6>
                            <span>SR {{ $discounted_price }}</span>
                        </div>
                    @endif
                    <div class="order-info" style="display:none;" id="deliveryblock">
                        <div class="cash-On-delivery">
                            <h6><?php echo __('messages.cash_on_delivery'); ?>:</h6>
                            <span id="delivery">SR {{ $delivery_charge }}</span>
                            <input type="hidden" value="{{ $delivery_charge }}" id=delivery_input>
                            <input type="hidden" value="{{ $delivery_charge }}" id=delivery_input2>
                        </div>
                    </div>
                    <span class="line-banner3 full-100"></span>
                    <div class="order-info bold-texts pb-0">
                        <h5><?php echo __('messages.total'); ?>:</h5>
                        <span id="d">SR {{ $order_price }}</span>
                        <input type="hidden" value="{{ $order_price }}" id=order_input>
                    </div>
                    <span class="line-banner3 full-100"></span>
                    <!-- <h6 class="sub-title-cart">Add <span>SR {{ $global->min_amount_shipping }}</span> To Get <span>Free Shipping</span> </h6> -->
                    {{-- <button disabled onclick="purchaseOrder()"
                        class="btn btn-black w-100 paymentsubmit"><?php echo __('messages.pay_now'); ?></button> --}}
                    <button hidden type="submit" id="submitPayForm" form="paymentform"></button>
                </div>
            </div>
        </div>
        </div>
    </section>
    <style>
        h4.payHead {
            font-family: Candara, Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
            font-size: 14px;
            color: #09f;
            border: 2px solid #09f;
            border-radius: 4px;
            padding: 5px 8px;
            cursor: pointer;
        }

        .wpwl-container {
            display: none;
        }

    </style>
    <script>
        function ashok() {
            console.log('sdd');
            let type = document.querySelector('input[name="paymenttype"]:checked').value;

            if (type == 1) {
                document.getElementById("walletdisable").style.visibility = "hidden";
            }
            if (type == 3 d) {
                document.getElementById("walletdisable").style.visibility = "hidden";
            }


        }
        ashok();
    </script>
    <script>
        /* create custom UI */
        function wrapElement(element) {
            var id = $(element).attr("id");
            var wrapId = "wrap_" + id;
            $(element).wrap('<div id="' + wrapId + '"></div>"');
            return $('#' + wrapId);
        }
        var methodMapping = {
            "card": " Click to pay with card",

        }
        var wpwlOptions = {
            style: "plain",
            onReady: function() {
                $('.wpwl-container').each(function() {
                    var id = $(this).attr("id");

                    if (id.search("card") == 0) {
                        wrapElement(this).hide().before("<h4 class='payHead'>" + methodMapping[id.substring(
                            0, id.lastIndexOf("_"))] + "</h4>");

                    }


                });

                $("h4").click(function() {
                    $(this).next().slideToggle();
                });



            }
        }
    </script>
    <script>
        function purchaseOrder() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });
            $.ajax({
                type: "POST",
                url: basrurl + "/order_purchase_info",
                data: $("#paymentform").serialize(),
                success: function(data) {
                    if (data.status == true) {
                        gtag("event", "purchase", {
                            currency: "SR",
                            Payment_Type: "T_12345",
                            value: data.order_price,
                            affiliation: "Google Store",
                            coupon: "SUMMER_FUN",
                            shipping: 3.33,
                            tax: 2.22,
                            items: [
                                data.message
                            ],
                        });
                    }
                    $("#submitPayForm").click();
                },
            });

        }
    </script>

    <script>
        var method = <?php echo json_encode($method); ?>;
        /* create custom UI */
        function wrapElement(element) {
            var id = $(element).attr("id");
            var wrapId = "wrap_" + id;
            $(element).wrap('<div id="' + wrapId + '"></div>"');

            return $('#' + wrapId);
        }

        var methodMapping = {
            "card": " Click to pay with card " + method,
            "directDebit": " Click to pay with direct debit",
            "prepayment-BOLETO": " Click to pay with Boleto",
            "prepayment-BARPAY": " Click to pay with Barpay",
            "onlineTransfer-IDEAL": " Click to pay with iDeal",
            "onlineTransfer-GIROPAY": " Click to pay with GiroPay",
            "invoice-INVOICE": " Click to pay with Invoice",
            "onlineTransfer-SOFORTUEBERWEISUNG": " Click to pay with SOFORT Uberweisung",
            "virtualAccount-PASTEANDPAY_V": " Click to pay with PASTEandPAY",
            "virtualAccount-VSTATION_V": " Click to pay with voucherstation",
            "virtualAccount-PAYPAL": " Click to pay with PayPal",
            "virtualAccount-UKASH": " Click to pay with Ukash",
            "virtualAccount-QOOQO": " Click to pay with QOOQO",
            "virtualAccount-KLARNA_INVOICE": " Click to pay with Klarna Invoice",
            "virtualAccount-KLARNA_INSTALLMENTS": " Click to pay with Klarna Installments",
            "cashOnDelivery": " Click to pay cash on delivery"
        }
        var wpwlOptions = {
            style: "plain",
            onReady: function() {
                $('.wpwl-container').each(function() {
                    var id = $(this).attr("id");
                    var type = $("input[name='paymenttype']:checked").val();
                    if (type == 1) {
                        wrapElement(this).hide().before("<h4 class='payHead'>" + methodMapping[id.substring(
                            0, id.lastIndexOf("_"))] + "</h4>");
                    } else {
                        wrapElement(this).hide().before("<h4 class='payHead'>" + methodMapping[id.substring(
                            0, id.lastIndexOf("_"))] + "</h4>");
                    }
                });
                $('.payHead').trigger('click');

                $("h4").click(function() {
                    $(this).next().slideToggle();
                });
                setTimeout(function() {
                    $('#discount_available').hide();
                    $('#customRadio4').attr('checked', false);
                    $('#customRadio2').attr('checked', false);
                    $('.payHead').trigger('click');
                    $("h4").text('');
                    var locale_val = $('#locale').val();
                    if (locale_val == "ar") {
                        $(".wpwl-label-brand").text('نوع البطاقة');
                        $(".wpwl-label-cardNumber").text('رقم البطاقة');
                        $(".wpwl-label-expiry").text('تاريخ الانتهاء');
                        $(".wpwl-label-cardHolder").text('الاسم على البطاقة');
                        $(".wpwl-button-pay").text('اتمام الطلب');
                    }
                    $('.wpwl-brand-VISA').hide();
                    $('.wpwl-brand-MADA').hide();
                    $('#submit' + 2).hide();
                    $('#submit' + 5).hide();
                    $('#submit6').hide();
                    $("h4").removeClass('payHead')
                }, 500);

            }
        }
    </script>
    <style>
        h4.payHead {
            font-family: Candara, Calibri, Segoe, "Segoe UI", Optima, Arial, sans-serif;
            font-size: 14px;
            color: #09f;
            border: 2px solid #09f;
            border-radius: 4px;
            padding: 5px 8px;
            cursor: pointer;
        }

        .wpwl-container {
            display: none;
        }

    </style>

    <script>
        function getvalue(el) {

            var type = $(el).attr('value');
            var id = $(el).attr('id');
            if (type != 1 && type != 3) {
                if (id != 'wallet') {
                    $('#customRadio1').attr('checked', false);
                    $('#customRadio15').attr('checked', false);
                    $('.wpwl-form').hide();
                    $('.paymentsubmit').removeAttr('disabled');
                    $('#submit2').hide();
                    $('#submit5').hide();
                    $('#discountpricePay').hide();
                    $('#discount_available').hide();
                    $('#submit' + type).show();
                }


            } else {
                var address_id = $('#address_id').val();
                $('.paymentsubmit').prop('disabled', true);
                $('#discount_available').show();
                if ($("#wallet").prop('checked') == true) {
                    var wallet = 1;
                } else {
                    var wallet = 0;
                }

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });
                $.ajax({
                    type: "POST",
                    url: basrurl + "/wallet_pay_store",
                    data: {
                        wallet: wallet
                    },
                    success: function(data) {

                        window.location.href = basrurl + "/checkoutpayment/" + address_id + '/' + type;

                    },
                });


            }

        }

        function walletPay() {

            // $('#submit2').hide();
            // $('#submit5').hide();
            // $('#submit6').hide();
            var Price = ($('#d').text().replace("SR ", ""));

            if ($('#wallet').prop('checked') == true && Price == "0.00") {
                $('#submit6').show();
                $('#discount_available').hide();
                $('#paymenthidden').hide();
                $('#paymenthidden1').hide();
                $('.paymentsubmit').removeAttr('disabled');
                $('#customRadio2').prop('checked', false);
                $('#customRadio4').prop('checked', false);
                $('#customRadio15').prop('checked', false);
                $('#submit2').hide();
                $('#submit5').hide();
                $('#walletPaycheck').prop('checked', true);

            } else if ($('#wallet').prop('checked') == true && Price != "0.00") {
                $('#discount_available').hide();

            } else {
                $('#discount_available').show();
                $('#submit6').hide();
                $('#paymenthidden').show();
                $('#paymenthidden1').show();
            }


            $('#customRadio1').attr('checked', false);
            $('#customRadio15').attr('checked', false);
            $('.wpwl-form').hide();
        }
    </script>

@endsection
