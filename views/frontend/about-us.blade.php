@extends('layouts.front-app')

@section('content')
        <section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.about_us') ?></a></li>
              </ul>
            </nav>
          </div>
        </section>
        <section class="in-banner-line">
           <div class="container">
              <div class="help-bx">
                <h5>@if((\App::getLocale() == 'en')) {{$aboutus->title_en}} @else {{$aboutus->title_ar}} @endif</h5>
                <span class="line-banner3"></span>
              </div>
           </div>
        </section>

        <section class="about-sec pdd-space">
          <div class="container">
              <div class="row ab-info">
                <div class="col-md-5">
                @if((\App::getLocale() == 'en'))
                 <figure><img src="{{ url('/') }}/public/images/aboutus/{{$aboutus->image_en}}"></figure>
                @else
                 <figure><img src="{{ url('/') }}/public/images/aboutus/{{$aboutus->image_ar}}"></figure>
                @endif
                </div>
                <div class="col-md-7">
                @if((\App::getLocale() == 'en'))
                  {!!$aboutus->description1_en!!}
                @else
                  {!!$aboutus->description1_ar!!}
                @endif
                </div>
                <div class="col-md-12 pt-5">
                @if((\App::getLocale() == 'en'))
                  {!!$aboutus->description2_en!!}
                @else
                  {!!$aboutus->description2_ar!!}
                @endif
                </div>
              </div>
          </div>
        </section>
@endsection
