@extends('layouts.front-app')

@section('content')
 <section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_account') ?></a></li>
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
                        <li class="nav-item active">
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
                          <a class="nav-link" href="{{url('wishlist')}}">
                            <figure><img src="{{asset('assets/front-end/images/my-wishlist.svg')}}"></figure>
                            <h6><?php echo  __('messages.my_wishlist') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{url('mygifts')}}">
                            <figure><i class="ri-gift-2-line"></i></figure>
                            <h6><?php echo  __('messages.my_gift') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{url('myrewards')}}">
                            <figure><i class="ri-money-dollar-box-line"></i></figure>
                            <h6><?php echo  __('messages.my_reward') ?></h6>
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
                          <li class="breadcrumb-item"><a href="{{url('/myaccount')}}"><?php echo  __('messages.my_account') ?></a></li>
                        </ul>
                      </nav>
                    </div>
                    <div class="dash-credit-sec">
                      <form id ="myaccount" method="POST" action="{{url('myaccount')}}">
                      @csrf
                          <div class="main-card-in">
                            <div class="account-form">
                              <h6><?php echo  __('messages.edit_your_account_information') ?></h6>
                              <div class="form-group">
                                <lable><?php echo  __('messages.full_name') ?></lable>
                                <input type="text" name="name" value="<?php if(!empty($user->name)){echo $user->name;}?>" class="form-control" placeholder="Smith jhone">
                                @if($errors->has('name'))
                                    <span id="title-error" class="error text-danger">{{ $errors->first('name') }}</span>
                                @endif
                              </div>
                              <div class="form-group">
                                <lable><?php echo  __('messages.email_address') ?></lable>
                                <input type="text" name="email" value="<?php if(!empty($user->email)){echo $user->email;}?>" class="form-control" placeholder="smithjhone@gmail.com"readonly>
                                @if($errors->has('email'))
                                    <span id="title-error" class="error text-danger">{{ $errors->first('email') }}</span>
                                @endif
                              </div>
                              <div class="form-group">
                                <lable><?php echo  __('messages.phone_number') ?></lable>
                                <input type="number" name="mobile" value="<?php if(!empty($user->mobile)){echo $user->mobile;}?>" class="form-control" placeholder="98 2345 2345">
                                @if($errors->has('mobile'))
                                    <span id="title-error" class="error text-danger">{{ $errors->first('mobile') }}</span>
                                @endif
                              </div>
                              @if(!empty(Auth::id()))
                              <button type="submit" onclick="this.form.submit();this.disabled = true;" class="btn btn-black w-100"><?php echo  __('messages.update') ?></button>
                              @else
                              <a href="javascript:;" data-dismiss="modal" data-toggle="modal" data-target="#login-modal" class="btn btn-black w-100" id="login-link"><?php echo  __('messages.update') ?></a>
                              @endif
                            </div>
                          </div>
                      </form>
                    </div>
                </div>
              </div>
              <!-- ----inner-dashboard----- -->
          </div>
        </section>
@endsection
