@extends('layouts.front-app')

@section('content')
    <section class="bread-sec-nav">
        <div class="container">
            <nav class="breadcrumb-nav">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item bold"><a href="{{ url('/') }}"><?php

use Illuminate\Support\Facades\Session;

echo __('messages.home'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#"><?php echo __('messages.my_orders'); ?></a></li>
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
                                        <a class="nav-link" href="{{ url('mywallet') }}">
                                            <figure><i class="ri-wallet-3-line"></i></figure>
                                            <h6><?php echo __('messages.my_wallet'); ?></h6>
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
                                            <figure><i class="ri-money-dollar-box-line"></i></figure>
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
                                <li class="breadcrumb-item"><a href="#"><?php echo __('messages.my_orders'); ?></a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="dash-credit-sec">
                        <!-- <div class="default-filder-ck w-flex">
                                <h4>All Orders</h4>
                                <div class="form-group">
                                <label>Status</label>
                                <select class="form-control">
                                    <option selected="">All Orders</option>
                                    <option value="1">All Orders</option>
                                    <option value="2">All Orders</option>
                                </select>
                                </div>
                            </div> -->
                        <!-- ---table--- -->
                        <div class="order-tb-sec">
                            <div class="row">
                                @if ($myorderscount > 0)
                                    @foreach ($myorders as $record)
                                        <div class="col-md-12">
                                            <div class="order-summary-lists">
                                                <a href="{{ url('order/track', base64_encode($record->id)) }}">
                                                    <div class="od-summary-first">
                                                        <div class="order-id-text">
                                                            <h2># {{ $record->id }}</h2>
                                                            <h3>{{ $record->created_at }}</h3>
                                                        </div>
                                                        <?php $order_detail = get_order_details($record->id); ?>
                                                        <ul class="progress-order-track">
                                                            <div class="progress" id="progress"></div>
                                                            @if ($record->status == 6)
                                                                <li class="padding">
                                                                    <span class="circle-point"><i
                                                                            class="ri-check-fill"></i></span>
                                                                    <h6><?php echo __('messages.under_processing'); ?></h6>
                                                                </li>
                                                                <li class="active bar-fill-none">
                                                                    <span class="circle-point"><i
                                                                            class="ri-close-fill"></i></span>
                                                                    <h6><?php echo __('messages.cancelled'); ?></h6>
                                                                </li>
                                                            @else
                                                                @if (in_array(14, explode(',', $order_detail['order_track_data'])))
                                                                    <li class="padding">
                                                                    @else
                                                                    <li>
                                                                @endif
                                                                <span class="circle-point"><i
                                                                        class="ri-check-fill"></i></span>
                                                                <h6><?php echo __('messages.under_processing'); ?></h6>
                                                                </li>

                                                                @if (in_array(11, explode(',', $order_detail['order_track_data'])))
                                                                    <li class="padding">
                                                                    @else
                                                                    <li>
                                                                @endif
                                                                <span class="circle-point"><i
                                                                        class="ri-check-fill"></i></span>
                                                                <h6><?php echo __('messages.order_ready'); ?></h6>
                                                                </li>

                                                                @if (in_array(13, explode(',', $order_detail['order_track_data'])))
                                                                    <li class="padding">
                                                                    @else
                                                                    <li>
                                                                @endif
                                                                <span class="circle-point"><i
                                                                        class="ri-check-fill"></i></span>
                                                                <h6><?php echo __('messages.shipped'); ?></h6>
                                                                </li>

                                                                @if (in_array(7, explode(',', $order_detail['order_track_data'])))
                                                                    <li class="padding">
                                                                    @else
                                                                    <li>
                                                                @endif
                                                                <span class="circle-point"><i
                                                                        class="ri-check-fill"></i></span>
                                                                <h6><?php echo __('messages.delivered'); ?></h6>
                                                                </li>

                                                                @if (in_array(8, explode(',', $order_detail['order_track_data'])))
                                                                    <li class="padding">
                                                                    @else
                                                                    <li>
                                                                @endif
                                                                <span class="circle-point"><i
                                                                        class="ri-check-fill"></i></span>
                                                                <h6><?php echo __('messages.complete'); ?></h6>
                                                                </li>
                                                            @endif
                                                        </ul>

                                                        <ul>
                                                            <li></li>
                                                        </ul>
                                                    </div>
                                                    <div class="od-summary-second">
                                                        <h6>{{ $record->itemcount }} <?php echo __('messages.items'); ?> - SR
                                                            {{ $record->price }} </h6>
                                                        <ul>
                                                            @foreach ($order_detail['order_details'] as $details)
                                                                <li>
                                                                    <figure><img
                                                                            src="{{ url('/') }}/public/product_images/{{ $details->productimage }}">
                                                                    </figure>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="container">
                                        <div class="no-avilable">
                                            <figure><img src="{{ asset('assets/front-end/images/no-producat.svg') }}">
                                            </figure>
                                            <figcaption>
                                                <h4><?php echo __('messages.no_orders_available'); ?></h4>
                                            </figcaption>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- ---table--- -->
                        {!! $myorders->links() !!}
                    </div>
                </div>
            </div>
            <!-- ----inner-dashboard----- -->
        </div>
    </section>

    <script>
        window.onload = function() {


            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });
            $.ajax({
                type: "POST",
                url: basrurl + "/order_purchase_session",
                data: null,
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
                },
            });


        };
    </script>
@endsection
