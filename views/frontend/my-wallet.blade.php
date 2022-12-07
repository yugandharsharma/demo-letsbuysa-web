@extends('layouts.front-app')

@section('content')
<section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_wallet') ?></a></li>
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
                        <li class="nav-item">
                          <a class="nav-link" href="{{url('myaddress')}}">
                            <figure><img src="{{asset('assets/front-end/images/my-address.svg')}}"></figure>
                            <h6><?php echo  __('messages.my_address') ?></h6>
                          </a>
                        </li>
                         <li class="nav-item active">
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
                        <ul class="breadcrumb">
                          <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_wallet') ?></a></li>
                        </ul>
                      </nav>
                    </div>
                    <div class="dash-credit-sec">
                        <div class="row">
                            <div class="col-md-6">
                              <div class="balance-box">
                                  <h4><?php echo  __('messages.total_balance');?></h4>
                                  <div class="balance-text">
                                    <h1>SAR {{$amount}}</h1>
                                  </div>
                                  <div class="text-right">
                                    <button class="btn btn-black" data-toggle="modal" data-target="#addwallet"> <?php echo  __('messages.add_credit');?></button>
                                    <button class="btn btn-black" data-toggle="modal" data-target="#redeemvoucher"> <?php echo  __('messages.redeem_voucher');?></button>
                                  </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                               <div class="transactions-box">
                                 <h4><?php echo  __('messages.latest_transaction');?></h4>
                                  <ul>
                                  @if(count($walletrecharge)>0)
                                       <div class="table-responsive">
                                    <table class="table">
                                      <thead>
                                        <tr>
                                          <th scope="col"><?php echo  __('messages.no');?></th>
                                          <th scope="col"><?php echo  __('messages.balance');?></th>
                                          <th scope="col"><?php echo  __('messages.type');?></th>
                                          <th scope="col"><?php echo  __('messages.reason');?></th>
                                          <th scope="col"><?php echo  __('messages.date');?></th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                       @foreach($walletrecharge as $key => $recharge)
                                        <tr>
                                          <th scope="row">{{$key+1}}</th>
                                          <td>SR {{$recharge->amount}}</td>
                                          <td>@if($recharge->type==1) Credit @else Debit @endif</td>
                                          <td>{{$recharge->reason}}</td>
                                          <td>{{$recharge->created_at}}</td>
                                        </tr>
                                       @endforeach
                                      </tbody>
                                    </table>
                                  </div>

                                  {!! $walletrecharge->links() !!}
                                  @else
                                      <li><h6><?php echo  __('messages.no_transaction');?> </h6></li>
                                  @endif
                                  </ul>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <!-- ----inner-dashboard----- -->
          </div>

<!-- Modal -->
<div class="modal fade" id="addwallet" tabindex="-1" role="dialog" aria-labelledby="addwalletTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo  __('messages.add_wallet_amount');?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form  id ="recharge" method="POST" action="{{url('recharge')}}">
       @csrf
        <div class="modal-wallet">
          <ul class="st-ad-payments">
            <li>
              <div class="custom-control custom-radio">
                <input type="radio" id="visaRadio1" name="payment_type" value="visa" class="custom-control-input">
                <label class="custom-control-label" for="visaRadio1"><img src="https://newletsbuy.devtechnosys.info/assets/front-end/images/visa.svg"></label>
              </div>
            </li>
            <li>
              <div class="custom-control custom-radio">
                <input type="radio" id="mastercardRadio2" name="payment_type" value="master" class="custom-control-input">
                <label class="custom-control-label" for="mastercardRadio2"><img src="https://newletsbuy.devtechnosys.info/assets/front-end/images/mastercard.svg"></label>
              </div>
            </li>
            <li>
              <div class="custom-control custom-radio">
                <input type="radio" id="madacheck" name="payment_type" value="mada" class="custom-control-input">
                <label class="custom-control-label" for="madacheck"><img src="https://newletsbuy.devtechnosys.info/assets/front-end/images/mada-logo.svg"></label>
              </div>
            </li>
            @if($errors->has('payment_type'))
                <span id="title-error" class="error text-danger">{{ $errors->first('payment_type') }}</span>
            @endif
          </ul>

          <div class="form-group" style="margin-top: 28px;">
            <input type="text" class="form-control" name="amount" placeholder="<?php echo  __('messages.enter_amount');?>">
          </div>
           @if($errors->has('amount'))
                <span id="title-error" class="error text-danger">{{ $errors->first('amount') }}</span>
           @endif
          <button type="submit" class="btn btn-black w-100"><?php echo  __('messages.add');?></button>
        </div>
       </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="redeemvoucher" tabindex="-1" role="dialog" aria-labelledby="addwalletTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo  __('messages.enter_voucher_code');?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form  id ="voucher" method="POST" action="{{url('voucher')}}">
       @csrf
        <div class="modal-wallet">
          <div class="form-group" >
            <input type="text" class="form-control" name="voucher" placeholder="<?php echo  __('messages.enter_voucher_code');?>">
          </div>
           @if($errors->has('amount'))
                <span id="title-error" class="error text-danger">{{ $errors->first('amount') }}</span>
           @endif
          <button type="submit" class="btn btn-black w-100"><?php echo  __('messages.add');?></button>
        </div>
       </form>
      </div>
    </div>
  </div>
</div>

        </section>
@endsection
