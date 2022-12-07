@extends('layouts.front-app')

@section('content')
         <section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.terms_of_use') ?></a></li>
              </ul>
            </nav>
          </div>
        </section>
       <section class="in-banner-line">
           <div class="container">
              <div class="help-bx">
                @if((\App::getLocale() == 'en'))
                <h5>{{$terms->title_en}}</h5>
                @else
                <h5>{{$terms->title_ar}}</h5>
                @endif
                <span class="line-banner3"></span>
              </div>
           </div>
        </section>

        <section class="about-sec pdd-space">
          <div class="container">
              <div class="support-tabs pt-0">
                 <!-- ----tabing-here---- -->
                  <div class="row">
                    <div class="col-md-3">
                      <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active show" id="user1-tab" data-toggle="pill" href="#user1" role="tab" aria-controls="user1" aria-selected="true">@if((\App::getLocale() == 'en')) {{$terms->title1_en}} @else {{$terms->title1_ar}} @endif</a>
                        <a class="nav-link" id="user2-tab" data-toggle="pill" href="#user2" role="tab" aria-controls="user2" aria-selected="false">@if((\App::getLocale() == 'en')) {{$terms->title2_en}} @else {{$terms->title2_ar}} @endif</a>
                        <a class="nav-link" id="user3-tab" data-toggle="pill" href="#user3" role="tab" aria-controls="user3" aria-selected="false">@if((\App::getLocale() == 'en')) {{$terms->title3_en}} @else {{$terms->title3_ar}} @endif</a>
                        <a class="nav-link" id="user4-tab" data-toggle="pill" href="#user4" role="tab" aria-controls="user4" aria-selected="false">@if((\App::getLocale() == 'en')) {{$terms->title4_en}} @else {{$terms->title4_ar}} @endif</a>
                        <a class="nav-link" id="user5-tab" data-toggle="pill" href="#user5" role="tab" aria-controls="user5" aria-selected="false">@if((\App::getLocale() == 'en')) {{$terms->title5_en}} @else {{$terms->title5_ar}} @endif</a>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade active show" id="user1" role="tabpanel" aria-labelledby="user1-tab">
                            <ul class="tab-text-in">
                                <li>
                                @if((\App::getLocale() == 'en'))
                                {!!$terms->description1_en!!}
                                @else
                                {!!$terms->description1_ar!!}
                                @endif
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="user2" role="tabpanel" aria-labelledby="user2-tab">
                              <ul class="tab-text-in">
                                <li>
                                  @if((\App::getLocale() == 'en'))
                                  {!!$terms->description2_en!!}
                                  @else
                                  {!!$terms->description2_ar!!}
                                  @endif
                                </li>
                              </ul>
                        </div>
                        <div class="tab-pane fade" id="user3" role="tabpanel" aria-labelledby="user3-tab">
                            <ul class="tab-text-in">
                                <li>
                                  @if((\App::getLocale() == 'en'))
                                  {!!$terms->description3_en!!}
                                  @else
                                  {!!$terms->description3_ar!!}
                                  @endif
                                </li>
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="user4" role="tabpanel" aria-labelledby="user4-tab">
                          <ul class="tab-text-in">
                                <li>
                                  @if((\App::getLocale() == 'en'))
                                  {!!$terms->description4_en!!}
                                  @else
                                  {!!$terms->description4_ar!!}
                                  @endif
                                </li>
                          </ul>
                        </div>
                        <div class="tab-pane fade" id="user5" role="tabpanel" aria-labelledby="user5-tab">
                            <ul class="tab-text-in">
                                <li>
                                  @if((\App::getLocale() == 'en'))
                                  {!!$terms->description5_en!!}
                                  @else
                                  {!!$terms->description5_ar!!}
                                  @endif
                                </li>
                            </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- ----tabing-here---- -->
              </div>
          </div>
        </section>
@endsection
