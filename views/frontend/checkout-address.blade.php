@extends('layouts.front-app')

@section('content')
    <section class="bread-sec-nav">
        <div class="container">
            <nav class="breadcrumb-nav">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item bold"><a href="{{ url('/') }}"><?php echo __('messages.home'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#"><?php echo __('messages.check_out_address'); ?></a></li>
                </ul>
            </nav>
        </div>
    </section>
    <section class="next-dash check-dash">
        <div class="container">
            <ul class="progress-container">
                <div class="progress" id="progress"></div>
                <li class="active">
                    <span class="circle">1</span>
                    <h6><?php echo __('messages.address'); ?></h6>
                </li>
                <li>
                    <span class="circle">2</span>
                    <h6><?php echo __('messages.payment'); ?></h6>
                </li>
                <li>
                    <span class="circle">3</span>
                    <h6><?php echo __('messages.order_placed'); ?></h6>
                </li>
            </ul>
            <div class="row">
                @foreach ($address as $record)
                    <div class="col-md-6 address_select">
                        <div class="add-address-sec d-flex">
                            <div class="add-address-in">
                                <h4>{{ $record->fullname }}</h4>
                                <h5>{{ $record->fulladdress }}</h5>
                                <h5>{{ $record->mobile }}</h5>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="{{ base64_encode($record->id) }}"
                                    onclick="addShippingInfo('{{ base64_encode($record->id) }}','{{ auth()->user() }}')"
                                    name="customRadio" class="custom-control-input">
                                <label class="custom-control-label" for="{{ base64_encode($record->id) }}"></label>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-12">
                    <form id="checkout_address" method="GET"
                        action="{{ url(app()->getLocale() . '/' . 'checkoutpayment') }}">
                        <div class="cart-edit-sec">
                            <div class="order-edit-in">
                                <a href="{{ url('/') }}" class="btn btn-black"><?php echo __('messages.back'); ?></a>
                                <button href="javascript:;" class="btn btn-black" data-toggle="modal"
                                    data-target="#my-address"><?php echo __('messages.add_new_address'); ?></button>
                            </div>
                            <input type="hidden" value="" name="address_id" id="address_id">
                            <button type="submit"
                                class="btn btn-coffee waves-effect waves-light"><?php echo __('messages.continue'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade come-from-modal right" id="my-address" tabindex="-1" role="dialog" aria-labelledby="my-cartLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    @if (!empty(Auth::id()))
                        <h5 class="modal-title" id="my-cartLabel"><?php echo __('messages.add_new_address'); ?></h5>
                    @else
                        <a href="javascript:;" class="modal-title" data-toggle="modal" data-dismiss="modal"
                            data-target="#login-modal"><?php echo __('messages.add_new_address'); ?></a>
                    @endif
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body cart-form">
                    <div class="login-form">
                        <div class="modal-inner-map">
                            <div id="map"></div>
                        </div>
                        <div class="location-text text-right pt-4">
                            <h6><i class="ri-map-pin-fill"></i> <span id="locationtext"><span></h6>
                        </div>
                        <form id="add_address" method="POST" action="{{ url('addaddress') }}">
                            @csrf
                            <div class="account-form">
                                <div class="form-group">
                                    <lable><?php echo __('messages.full_name'); ?></lable>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="<?php echo __('messages.full_name'); ?>">
                                </div>
                                @if ($errors->has('name'))
                                    <span id="title-error" class="error text-danger">{{ $errors->first('name') }}</span>
                                @endif
                                <lable
                                    style="font-size: 14px;font-weight: 600;color: #50525F;padding-bottom: 5px;display: block;">
                                    <?php echo __('messages.telephone'); ?></lable>
                                <div class="mobile-no-type">
                                    <div class="form-group row">
                                        <div class="col-sm-3 material-fl-bx">
                                            <figure><img src="{{ asset('assets/front-end/images/data-img-fl.png') }}">
                                            </figure>
                                            <font>+966 |</font>
                                        </div>
                                        <div class="col-sm-9 material-div">
                                            <input type="text" name="mobile" class="form-control" placeholder="5xxxxxxxx">
                                        </div>

                                    </div>
                                    @if ($errors->has('mobile'))
                                        <span id="title-error"
                                            class="error text-danger">{{ $errors->first('mobile') }}</span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <lable><?php echo __('messages.address_details'); ?></lable>
                                    <input type="text" name="address_details" class="form-control"
                                        placeholder="<?php echo __('messages.extradetails'); ?>">
                                </div>
                                <input type="hidden" name="lat" id="lat">
                                <input type="hidden" name="long" id="long">
                                <input type="hidden" name="fulladdress" id="fulladdress">
                                <button type="type" href="javascript:;" type="button" id="addresssave"
                                    class="btn btn-black"><?php echo __('messages.save'); ?></button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div> -->
            </div>
        </div>
    </div>

@endsection
