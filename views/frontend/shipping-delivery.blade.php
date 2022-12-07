@extends('layouts.front-app')

@section('content')
        <section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.shipping_delivery') ?></a></li>
              </ul>
            </nav>
          </div>
        </section>
  <section class="in-banner-line">
           <div class="container">
              <div class="help-bx">
                @if((\App::getLocale() == 'en'))
                <h5>{{$shipping->title_en}}</h5>
                @else
                <h5>{{$shipping->title_ar}}</h5>
                @endif
                <span class="line-banner3"></span>
              </div>
           </div>
        </section>

        <section class="about-sec pdd-space">
          <div class="container">
            <div class="support-tabs pt-0">
              <ul class="tab-text-in pt-0">
                @if((\App::getLocale() == 'en'))
                  {!!$shipping->description_en!!}
                @else
                  {!!$shipping->description_ar!!}
                @endif
              </ul>
            </div>
          </div>
        </section>
@endsection
