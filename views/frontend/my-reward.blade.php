@extends('layouts.front-app')

@section('content')
<section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_rewards') ?></a></li>
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
                        <li class="nav-item">
                          <a class="nav-link" href="{{url('mygifts')}}">
                            <figure><i class="ri-gift-2-line"></i></figure>
                            <h6><?php echo  __('messages.my_gift') ?></h6>
                          </a>
                        </li>
                        <li class="nav-item active">
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
                          <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.my_rewards') ?></a></li>
                        </ul>
                      </nav>
                    </div>
                    <!-- -----my-reward------ -->
                    <div class="dash-credit-sec">
                      <div class="row">
                        <div class="col-md-6">
                            <div class="balance-box">
                                <h4><?php echo  __('messages.total_rewards') ?></h4>
                                <div class="balance-text">
                                  <h1>{{$reward}}</h1>
                                </div>
                                <div class="text-right">
                                  <button class="btn btn-black waves-effect waves-light" data-toggle="modal" data-target="#redeemvoucher"> <?php echo  __('messages.redeem_rewards');?></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                               <div class="transactions-box">
                                 <h4><?php echo  __('messages.latest_transaction');?></h4>
                                  <ul>
                                  @if(count($rewardredeem)>0)
                                       <div class="table-responsive">
                                    <table class="table">
                                      <thead>
                                        <tr>
                                          <th scope="col"><?php echo  __('messages.no');?></th>
                                          <th scope="col"><?php echo  __('messages.balance');?></th>
                                          <th scope="col"><?php echo  __('messages.type');?></th>
                                          <th scope="col"><?php echo  __('messages.date');?></th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                       @foreach($rewardredeem as $key => $recharge)
                                        <tr>
                                          <th scope="row">{{$key+1}}</th>
                                          <td>SR {{$recharge->reward}}</td>
                                          <td>@if($recharge->type==1) Credit @else Debit @endif</td>
                                          <td>{{$recharge->created_at}}</td>
                                        </tr>
                                       @endforeach
                                      </tbody>
                                    </table>
                                  </div>

                                  {!! $rewardredeem->links() !!}
                                  @else
                                      <li><h6><?php echo  __('messages.no_transaction');?> </h6></li>
                                  @endif
                                  </ul>
                               </div>
                            </div>
                      </div>
                    </div>
                    <!-- -----my-reward------ -->
                   
                </div>
              </div>
              <!-- ----inner-dashboard----- -->
          </div>

<!-- Modal -->

<div class="modal fade" id="redeemvoucher" tabindex="-1" role="dialog" aria-labelledby="addwalletTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo  __('messages.enter_reward_points');?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <form  id ="rewards" method="POST" action="{{url('rewardredeem')}}">
       @csrf
        <div class="modal-wallet">
          <div class="form-group" >
            <input type="number" class="form-control" name="rewards" placeholder="<?php echo  __('messages.enter_reward_points');?>">
          </div>
           @if($errors->has('rewards'))
                <span id="title-error" class="error text-danger">{{ $errors->first('rewards') }}</span>
           @endif
          <button type="submit" class="btn btn-black w-100"><?php echo  __('messages.redeem_rewards');?></button>
        </div>
       </form>
      </div>
    </div>
  </div>
</div>

        </section>
@endsection
