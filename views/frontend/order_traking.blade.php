@extends('layouts.front-app')

@section('content')
    <section class="bread-sec-nav">
        <div class="container">
            <nav class="breadcrumb-nav">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item bold"><a href="{{ url('/') }}"><?php echo __('messages.home'); ?></a></li>
                    <li class="breadcrumb-item bold"><a href="{{ url('/myorder') }}"><?php echo __('messages.my_orders'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#"><?php echo __('messages.order_details'); ?></a></li>
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
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebar-menu"
                            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <!-- Collapsible content -->
                        <div class="collapse navbar-collapse" id="sidebar-menu">
                            <ul class="navbar-nav">
                                @if (!empty(Auth::id()))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('myaccount') }}">
                                            <figure><img src="{{ asset('assets/front-end/images/my-account.svg') }}">
                                            </figure>
                                            <h6><?php echo __('messages.my_account'); ?></h6>
                                        </a>
                                    </li>
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{ url('myorder') }}">
                                            <figure><img src="{{ asset('assets/front-end/images/my-orders.svg') }}">
                                            </figure>
                                            <h6><?php echo __('messages.my_orders'); ?></h6>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('myaddress') }}">
                                            <figure><img src="{{ asset('assets/front-end/images/my-address.svg') }}">
                                            </figure>
                                            <h6><?php echo __('messages.my_address'); ?></h6>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('mygifts') }}">
                                            <figure><i class="ri-gift-2-line"></i></figure>
                                            <h6><?php echo __('messages.my_gift'); ?></h6>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('myrewards') }}">
                                            <figure><i class="ri-wallet-3-line"></i></figure>
                                            <h6><?php echo __('messages.my_reward'); ?></h6>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('wishlist') }}">
                                        <figure><img src="{{ asset('assets/front-end/images/my-wishlist.svg') }}"></figure>
                                        <h6><?php echo __('messages.my_wishlist'); ?></h6>
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
                                <li class="breadcrumb-item"><a href="#"><?php echo __('messages.order_details'); ?></a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="dash-credit-sec">
                        <div class="row dash-od-detail">
                            <div class="col-md-8">
                                <div class="default-filder-ck w-flex">
                                    <h4><?php echo __('messages.order_details'); ?> #{{ $order->id }}</h4>
                                    <?php $is_shipped = isshipped($order->id); ?>
                                    @if ($is_shipped == 0)
                                        @if ($order->status != 6)
                                            <div class="btns-od-tow">
                                                <a data-toggle="modal" data-target="#log-out-modal"
                                                    class="btn btn-black"><?php echo __('messages.cancel'); ?></a>
                                            </div>
                                        @endif
                                    @endif
                                    <!-- <button type="button" class="btn btn-black" data-toggle="modal" data-target="#trakingmd">Traking</button> -->
                                    <a href="{{ url('reorder', $order->id) }}"
                                        class="btn btn-black ml-2"><?php echo __('messages.re_order'); ?></a>
                                    <a href="{{ url('invoice', base64_encode($order->id)) }}"
                                        class="btn btn-black ml-2"><?php echo __('messages.invoice_view'); ?></a>
                                </div>

                                <div class="order-tb-sec">
                                    <div class="order-summary-lists">
                                        <div class="od-summary-first">
                                            <div class="d-flex">
                                                <div class="order-id-text">
                                                    <h2><?php echo __('messages.order_number'); ?></h2>
                                                    <h3>30234012</h3>
                                                </div>
                                                <div class="order-id-text">
                                                    <h2><?php echo __('messages.date'); ?></h2>
                                                    <h3>{{ $order->created_at }}</h3>
                                                </div>
                                            </div>

                                            <ul class="progress-order-track">
                                                <div class="progress" id="progress"></div>
                                                @if ($order->status == 6)
                                                    <li class="padding">
                                                        <span class="circle-point"><i class="ri-check-fill"></i></span>
                                                        <h6><?php echo __('messages.under_processing'); ?></h6>
                                                    </li>
                                                    <li class="active bar-fill-none">
                                                        <span class="circle-point"><i class="ri-close-fill"></i></span>
                                                        <h6><?php echo __('messages.cancelled'); ?></h6>
                                                    </li>
                                                @else
                                                    @if (in_array(14, explode(',', $ordertrack)))
                                                        <li class="padding">
                                                        @else
                                                        <li>
                                                    @endif
                                                    <span class="circle-point"><i class="ri-check-fill"></i></span>
                                                    <h6><?php echo __('messages.under_processing'); ?></h6>
                                                    </li>

                                                    @if (in_array(11, explode(',', $ordertrack)))
                                                        <li class="padding">
                                                        @else
                                                        <li>
                                                    @endif
                                                    <span class="circle-point"><i class="ri-check-fill"></i></span>
                                                    <h6><?php echo __('messages.order_ready'); ?></h6>
                                                    </li>

                                                    @if (in_array(13, explode(',', $ordertrack)))
                                                        <li class="padding">
                                                        @else
                                                        <li>
                                                    @endif
                                                    <span class="circle-point"><i class="ri-check-fill"></i></span>
                                                    <h6><?php echo __('messages.shipped'); ?></h6>
                                                    </li>

                                                    @if (in_array(7, explode(',', $ordertrack)))
                                                        <li class="padding">
                                                        @else
                                                        <li>
                                                    @endif
                                                    <span class="circle-point"><i class="ri-check-fill"></i></span>
                                                    <h6><?php echo __('messages.delivered'); ?></h6>
                                                    </li>

                                                    @if (in_array(8, explode(',', $ordertrack)))
                                                        <li class="padding">
                                                        @else
                                                        <li>
                                                    @endif
                                                    <span class="circle-point"><i class="ri-check-fill"></i></span>
                                                    <h6><?php echo __('messages.complete'); ?></h6>
                                                    </li>
                                                @endif
                                            </ul>

                                            <ul>
                                                <li></li>
                                            </ul>
                                        </div>
                                        <!-- <div class="od-summary-first summary-first-tx">
                                                <h5>Order Delevery Time</h5>
                                                <h6>in 72 Hours</h6>
                                              </div> -->
                                        <div class="od-summary-first summary-first-tx">
                                            <h5><?php echo __('messages.address'); ?></h5>
                                            <h6>{{ $order->address }}</h6>
                                        </div>
                                        <div class="od-summary-first summary-first-tx">
                                            <h5><?php echo __('messages.payment_method'); ?></h5>
                                            <h6>
                                                @if ($order->payment_type == 1)
                                                    <?php echo __('messages.cash_on_delivery'); ?>
                                                @elseif($order->payment_type == 2 || $order->payment_type == 4)
                                                    <?php echo __('messages.credit_or_debit_card'); ?>
                                                @elseif($order->payment_type == 6) <?php echo __('messages.applepay'); ?>
                                                @elseif($order->payment_type == 10) <?php echo __('messages.paid_by_wallet'); ?>
                                                @else <?php echo __('messages.banktransfer'); ?> @endif
                                            </h6>
                                        </div>
                                        <div class="od-summary-second">
                                            <h6>{{ $order->itemcount }} <?php echo __('messages.items'); ?> - SAR {{ $order->price }}
                                            </h6>
                                            <ul>
                                                @foreach ($order_details as $details)
                                                    <li>
                                                        <figure><img
                                                                src="{{ url('/') }}/public/product_images/{{ $details->productimage }}">
                                                        </figure>
                                                        <figcaption>
                                                            @if (\App::getLocale() == 'en')
                                                                <h5>{{ $details->product_name_en }}</h5>
                                                            @else
                                                                <h5>{{ $details->product_name_ar }}</h5>
                                                            @endif
                                                            <h6>{{ $details->quantity }} * SAR {{ $details->price }}</h6>
                                                            <?php $productoptiondatas = getproductoptionsdetails($details->color); ?>

                                                            @if (!empty($productoptiondatas->color))
                                                                <h5 style="display: flex;">Colour - <span
                                                                        style="display: block;width: 22px;height: 22px;background:{{ $productoptiondatas->color }};border-radius: 4px;margin-left:10px;"></span>
                                                                </h5>
                                                            @endif
                                                            @if (!empty($productoptiondatas->optionname))
                                                                <h5>{{ $productoptiondatas->optionname }} -
                                                                    {{ $productoptiondatas->optionvalue }} </h5>
                                                            @endif
                                                        </figcaption>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="coupon-bx">
                                    <div class="default-filder-ck w-flex">
                                        <h4><?php echo __('messages.orders_summary'); ?></h4>
                                    </div>
                                    <span class="line-banner3 full-100"></span>
                                    <div class="order-info">
                                        <h6><?php echo __('messages.items'); ?>:</h6>
                                        <span>{{ $order->itemcount }}</span>
                                    </div>
                                    <div class="order-info">
                                        <h6><?php echo __('messages.sub_total'); ?>:</h6>
                                        <span>{{ $order->product_total_amount }}</span>
                                    </div>
                                    <div class="order-info">
                                        <h6><?php echo __('messages.discount'); ?>:</h6>
                                        <span>SR {{ $order->discount }}</span>
                                    </div>
                                    <div class="order-info">
                                        <h6><?php echo __('messages.shipping_charge'); ?>:</h6>
                                        <span>SR {{ $order->shipping_price }}</span>
                                    </div>
                                    @if ($order->delivery_price != 0)
                                        <div class="order-info">
                                            <h6><?php echo __('messages.cash_on_delivery'); ?>:</h6>
                                            <span>SR {{ $order->delivery_price }}</span>
                                        </div>
                                    @endif
                                    <span class="line-banner3 full-100"></span>
                                    <div class="order-info bold-texts pb-0">
                                        <h5><?php echo __('messages.total'); ?>:</h5>
                                        <span>SR {{ $order->price }}</span>
                                    </div>
                                    <span class="line-banner3 full-100"></span>
                                    <!-- <div class="order-issues">
                                          <h3>Do You have any issue with your order</h3>
                                          <ul>
                                            <li class="active"> <a href="javascript:;"><i class="ri-ticket-2-fill"></i> the are no tickets available</a> </li>
                                            <li> <a href="javascript:;"><i class="ri-add-circle-fill"></i> Add New tickets</a> </li>
                                            <li> <a href="javascript:;"><i class="ri-contacts-fill"></i> Contact customer care</a> </li>
                                          </ul>
                                        </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ----inner-dashboard----- -->
        </div>
    </section>
    <!-- order cancel -->
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
                    <form action="{{ url('order/cancel', base64_encode($order->id)) }}" id="order_cancel" method="post">
                        @csrf
                        <a href="javascript:;" class="modal-logo">
                            <img src="{{ asset('assets/front-end/images/logo.svg') }}">
                        </a>
                        <textarea id="cancel_reason" name="cancel_reason" placeholder="Please Enter Cancellation Reason"
                            rows="4" cols="40">
                        </textarea>
                        <div class="log-out-form">
                            <div class="d-flex">
                                <button type="button" data-dismiss="modal"
                                    class="btn btn-black"><?php echo __('messages.no'); ?></button>
                                <button type="submit" id="cancelorder" class="btn btn-black"><?php echo __('messages.yes'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- order cancel -->
    <!-- Modal -->
    <div class="modal fade" id="trakingmd" tabindex="-1" role="dialog" aria-labelledby="trakingmdTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Order Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="order-status-md">
                        <ul>
                            <li class="active">
                                <h6>Canceled 2019-10-23 17:21:33</h6>
                                <p>Your order has been cancelled</p>
                            </li>
                            <li>
                                <h6>In progress</h6>
                            </li>
                            <li>
                                <h6>Shipped</h6>
                            </li>
                            <li>
                                <h6>Delivered </h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
@endsection
