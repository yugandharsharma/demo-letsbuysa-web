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
                @endif
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
                  <ul class="breadcrumb d-flex">
                    <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_address') ?></a></li>
                    <a href="{{url('addaddress')}}" type="button" class="btn btn-black"><?php echo  __('messages.add_new_address') ?></a>
                  </ul>
                </nav>
              </div>
              <div class="dash-credit-sec">
                    <div class="my-location-bx pt-0">
                          <div class="row">
                          @foreach($user_address as $address)
                            <div class="col-md-6">
                                <div class="add-address-sec">
                                  <div class="add-address-in">
                                    <h4>{{$address->fullname}}</h4>
                                    <h5>{{$address->fulladdress}}</h5>
                                    <h5>{{$address->mobile}}</h5>
                                  </div>

                                  <div class="cart-edit-sec">
                                    <div class="order-edit-in">
                                      <a href="{{url('editaddress',base64_encode($address->id))}}" class="btn btn-black"><?php echo  __('messages.edit') ?></a>
                                      <a data-toggle="modal" data-target="#log-out-modal" class="btn btn-coffee"><?php echo  __('messages.delete') ?></a>
                                    </div>
                                    @if($address->is_default == 1)
                                    <span class="default-box">Default</span>
                                    @endif
                                  </div>
                                </div>
                            </div>
                           @endforeach
                          </div>
                    </div>
              </div>
          </div>
        </div>
        <!-- ----inner-dashboard----- -->
    </div>
  </section>
  <!-- logout  modal -->
<div class="modal login-modal-box fade" id="log-out-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <a href="javascript:;" class="modal-logo">
          <img src="{{asset('assets/front-end/images/logo.svg')}}">
        </a>
          <h5 style="margin-bottom: 55px;">Are You sure You Want To Delete Address</h5>
        <div class="log-out-form">
           <div class="d-flex">
            <button type="button" data-dismiss="modal" class="btn btn-black">No</button>
            <a href="{{url('deleteaddress',base64_encode($address->id))}}"  class="btn btn-black">Yes</a>
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
@endsection
