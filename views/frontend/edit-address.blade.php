@extends('layouts.front-app')

@section('content')
<section class="bread-sec-nav">
  <div class="container">
    <nav class="breadcrumb-nav">
      <ul class="breadcrumb">
        <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
        <li class="breadcrumb-item bold"><a href="{{url('/myaddress')}}"><?php echo  __('messages.my_address') ?></a></li>
        <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.edit_address') ?></a></li>
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
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_address') ?></a></li>
              </ul>
            </nav>
          </div>

          <div class="new-add-map">

          <div class="map-flex-row">
            <div class="map-flex-one">
              <input
                id="pac-input"
                class="form-control controls"
                type="text"
                placeholder="Choose Location From Map/Enter Location"
              />
              <div id="map"></div>
            </div>
            <div class="map-flex-tow" style="display: none;">
            <form id="add_address" method="POST" action ="{{url('editaddress',base64_encode($address->id))}}">
            @csrf
                <div class="account-form">
                    <div class="form-group">
                      <lable><?php echo  __('messages.full_name') ?></lable>
                      <input type="text" name="name" value="{{$address->fullname}}" class="form-control" placeholder="<?php echo  __('messages.full_name') ?>">
                    </div>
                    @if($errors->has('name'))
                        <span id="title-error" class="error text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <lable style ="font-size: 14px;font-weight: 600;color: #50525F;padding-bottom: 5px;display: block;"><?php echo  __('messages.telephone') ?></lable>
                    <div class="mobile-no-type">
                    <div class="form-group row">
                        <div class="col-sm-3 material-fl-bx">
                            <figure><img src="{{asset('assets/front-end/images/data-img-fl.png')}}"></figure> <font>+966 |</font>
                        </div>
                        <div class="col-sm-9 material-div">
                            <input type="text" name="mobile" value="{{$address->mobile}}" class="form-control" placeholder="5xxxxxxxx">
                        </div>

                    </div>
                        @if($errors->has('mobile'))
                        <span id="title-error" class="error text-danger">{{ $errors->first('mobile') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                      <lable><?php echo  __('messages.address_details') ?></lable>
                      <input type="text" name="address_details" value="{{$address->address_details}}" class="form-control" placeholder="Extra details, known streets etc">
                    </div>
                    <input type="hidden" name="lat" id="lat">
                    <input type="hidden" name="long" id="long">
                    <input type="hidden" name="fulladdress" id="fulladdress">
                <button type="type" href="javascript:;" type="button" id="addresssave" class="btn btn-black">Save</button>
                </div>
                </form>
            </div>
          </div>

            <div class="location-text text-right pt-4">
              <h6><i class="ri-map-pin-fill"></i> <span id ="locationtext"><span></h6>
              <button type="type" href="javascript:;" type="button" id="setlocationbutton" class="btn btn-black">Set location</button>
            </div>

        </div>
      </div>
    </div>
    <!-- ----inner-dashboard----- -->
</div>
</section>
@endsection
