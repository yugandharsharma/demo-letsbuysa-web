@extends('layouts.front-app')
@section('content')
 <section class="bread-sec-nav">
    <div class="container">
      <nav class="breadcrumb-nav">
        <ul class="breadcrumb">
          <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
          <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_address') ?></a></li>
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
                        <li class="nav-item">
                          <a class="nav-link" href="{{url('myaccount')}}">
                            <figure><img src="{{asset('assets/front-end/images/my-account.svg')}}"></figure>
                            <h6><?php echo  __('messages.my_account') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item ">
                          <a class="nav-link" href="{{url('myorder')}}">
                            <figure><img src="{{asset('assets/front-end/images/my-orders.svg')}}"></figure>
                            <h6><?php echo  __('messages.my_orders') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item active">
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
                      <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_address') ?></a></li>
                    </ul>
                  </nav>
                </div>
                <div class="dash-credit-sec">
                      <div class="main-card-in">
                        <div class="no-avilable">
                            <figure><img src="{{asset('assets/front-end/images/no-address.svg')}}"></figure>
                            <figcaption>
                                <h4><?php echo  __('messages.no_address_added_yet') ?></h4>
                                <a type="submit" href="{{url('addaddress')}}" class="btn btn-black"><?php echo  __('messages.add_address') ?></a>
                            </figcaption>
                        </div>
                      </div>
                </div>
            </div>
          </div>
          <!-- ----inner-dashboard----- -->
      </div>
    </section>
@endsection
