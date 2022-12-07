@extends('layouts.front-app')

@section('content')
<section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_gift') ?></a></li>
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
                         <li class="nav-item">
                          <a class="nav-link" href="{{url('mywallet')}}">
                            <figure><i class="ri-wallet-3-line"></i></figure>
                            <h6><?php echo  __('messages.my_wallet') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item active">
                          <a class="nav-link" href="{{url('mygifts')}}">
                            <figure><i class="ri-gift-2-line"></i></figure>
                            <h6><?php echo  __('messages.my_gift') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="{{url('myrewards')}}">
                            <figure><i class="ri-medal-line"></i></figure>
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
                          <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_gift') ?></a></li>
                        </ul>
                      </nav>
                    </div>
                    
                    <!-- ------my-gift----- -->
                    <div class="dash-credit-sec">
                          
                          <div class="Gift a voucher-tab">
                               <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                  <li class="nav-item">
                                    <a class="nav-link active" id="pills-gift-tab" data-toggle="pill" href="#pills-gift" role="tab" aria-controls="pills-gift" aria-selected="true"><?php echo  __('messages.gift_a_voucher') ?></a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link" id="pills-giftsent-tab" data-toggle="pill" href="#pills-giftsent" role="tab" aria-controls="pills-giftsent" aria-selected="false"><?php echo  __('messages.sent_gifts') ?></a>
                                  </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                  <div class="tab-pane fade show active" id="pills-gift" role="tabpanel" aria-labelledby="pills-gift-tab">
                                    <form id='gift_voucher' method="POST" action="{{url('gift/send')}}">
                                      @csrf
                                      <div class="row">
                                          <div class="col-md-6">
                                              <div class="position-relative">
                                                  <figure><img src="{{ url('/') }}/public/images/globalsetting/{{$global->gift_voucher_image}}" alt="" class="rounded-8"></figure>
                                                  <p class="voucher-img-title"><span id="setamount">SAR 50<span></p>
                                              </div>

                                              <div class="voucher-section border p-3 rounded-8">
                                                <h5 class="mb-3 balance-title"><?php echo  __('messages.balance_value') ?><span class="text-danger">*</span></h5>
                                                <ul class="sar-nav-item">
                                                  <li>
                                                      <div class="custom-control custom-radio">
                                                        <input type="radio" id="customRadiosar50" onclick="checkgiftamount();" checked name="giftamount" value ='50' class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadiosar50">SAR 50</label>
                                                      </div>
                                                  </li>
                                                  <li>
                                                      <div class="custom-control custom-radio">
                                                        <input type="radio" id="customRadiosar100" onclick="checkgiftamount();" name="giftamount" value ='100' class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadiosar100">SAR 100</label>
                                                      </div>
                                                  </li>
                                                  <li>
                                                      <div class="custom-control custom-radio">
                                                        <input type="radio" id="customRadiosar200" onclick="checkgiftamount();" name="giftamount" value ='200' class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadiosar200">SAR 200</label>
                                                      </div>
                                                  </li>
                                                  <li>
                                                      <div class="custom-control custom-radio">
                                                        <input type="radio" id="customRadiosar500" onclick="checkgiftamount();" name="giftamount" value ='500' class="custom-control-input">
                                                        <label class="custom-control-label" for="customRadiosar500">SAR 500</label>
                                                      </div>
                                                  </li>
                                                </ul>
                                                <div class="form-group">
                                                    <input type="number" class="form-control" min='50' onkeyup="myFunction()" id="amount" name ="amount" value="50" placeholder="<?php echo  __('messages.enter_amount') ?>">
                                                 </div>
                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                            <div class="recipient-form">
                                                <h4><?php echo  __('messages.recipient_details') ?></h4>
                                                  <div class="form-group">
                                                     <lable><?php echo  __('messages.email_id') ?><span class="text-danger">*</span></lable>
                                                     <input type="text" class="form-control" name="recipient_email" placeholder="<?php echo  __('messages.please_enter_recipient_email') ?>">
                                                  </div>
                                                  <div class="form-group">
                                                     <lable><?php echo  __('messages.phonenumber') ?><span class="text-danger">*</span></lable>
                                                     <input type="number" class="form-control" name="recipient_phone" placeholder="<?php echo  __('messages.please_enter_recipient_phone') ?>">
                                                  </div>
                                                  <div class="form-group">
                                                     <lable><?php echo  __('messages.recipient_name') ?><span class="text-danger">*</span></lable>
                                                     <input type="text" class="form-control" name="recipient_name" placeholder="<?php echo  __('messages.please_enter_recipient_name') ?>">
                                                  </div>
                                                  <div class="form-group">
                                                     <lable><?php echo  __('messages.sender_name') ?><span class="text-danger">*</span></lable>
                                                     <input type="text" class="form-control" name="sender_name" placeholder="<?php echo  __('messages.sender_name') ?>">
                                                  </div>
                                                  <div class="form-group">
                                                     <lable><?php echo  __('messages.message') ?><span class="text-danger">*</span></lable>
                                                     <textarea type="text" class="form-control" name="message" placeholder="<?php echo  __('messages.message') ?>"></textarea>
                                                  </div>
                                                  <button type="submit" class="btn btn-black"><?php echo  __('messages.submit_gift') ?></button>
                                            </div>
                                          </div>
                                      </div>
                                    </form>
                                  </div>
                                  <div class="tab-pane fade" id="pills-giftsent" role="tabpanel" aria-labelledby="pills-giftsent-tab">

                                    <div class="tbl-giftsent">
                                      @if(count($gift_transactions)>0)
                                       <div class="table-responsive">
                                        <table class="table table-bordered">
                                          <thead>
                                            <tr>
                                              <th scope="col"><?php echo  __('messages.gift_no');?></th>
                                              <th scope="col"><?php echo  __('messages.gift_name');?></th>
                                              <th scope="col"><?php echo  __('messages.gift_email');?></th>
                                              <th scope="col"><?php echo  __('messages.gift_amount');?></th>
                                              <th scope="col"><?php echo  __('messages.date');?></th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          @foreach($gift_transactions as $key => $recharge)
                                            <tr>
                                              <th scope="row">{{$key+1}}</th>
                                              <td>{{$recharge->recipient_name}}</td>
                                              <td>{{$recharge->recipient_email}}</td>
                                              <td>SR {{$recharge->amount}}</td>
                                              <td>{{$recharge->created_at}}</td>
                                            </tr>
                                          @endforeach
                                          </tbody>
                                        </table>
                                       </div>
                                      {!! $gift_transactions->links() !!}
                                      @else
                                      <div class="no-data-center">
                                       <div class="no-data-section">
                                           <i class="ri-gift-2-line"></i>
                                           <h3><?php echo  __('messages.no_gift_sent');?> </h3>
                                           <p><?php echo  __('messages.you_have_no_gift_sent');?></p>
                                           <button type="button" onClick="window.location.reload();" class="btn btn-black"><?php echo  __('messages.send_your_first_gift');?></button>
                                       </div>
                                     </div>
                                      @endif
                                    </div>
                                    
                                  </div>
                                </div>
                          </div>

                    </div>
                    <!-- ------my-gift----- -->
                   
                </div>
              </div>
              <!-- ----inner-dashboard----- -->
          </div>

    <!-- Modal -->
    </section>
    <Script>
      function checkgiftamount() {
          var payment = $('input[name="giftamount"]:checked').val();
          $("#setamount").html("SR " + payment);
          $("#amount").val(payment);
        }
        function myFunction()
        {
          var payment = $('#amount').val();
          $("#setamount").html("SR " + payment);
        }
         
      
    </Script>
@endsection
