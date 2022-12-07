@extends('layouts.front-app')

@section('content')
        <section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo  __('messages.brands') ?></a></li>
              </ul>
            </nav>
          </div>
        </section>
        <section class="next-dash">
          <div class="container">
              <!-- ----inner-dashboard----- -->
              <div class="inner-dash-bord">
                <div class="dashboard-brands">
                  <div class="brands-section">
                    <h4><?php echo  __('messages.brands') ?></h4>
                    <!-- <ul class="bnav">
                      <li><a class="active" href="#brands1">a</a></li>
                      <li><a href="#brands2">b</a></li>
                      <li><a href="#brands3">c</a></li>
                      <li><a href="javascript:;">d</a></li>
                      <li><a href="javascript:;">e</a></li>
                      <li><a href="javascript:;">f</a></li>
                      <li><a href="javascript:;">g</a></li>
                      <li><a href="javascript:;">h</a></li>
                      <li><a href="javascript:;">i</a></li>
                      <li><a href="javascript:;">j</a></li>
                      <li><a href="javascript:;">k</a></li>
                      <li><a href="javascript:;">l</a></li>
                      <li><a href="javascript:;">m</a></li>
                      <li><a href="javascript:;">n</a></li>
                      <li><a href="javascript:;">o</a></li>
                      <li><a href="javascript:;">p</a></li>
                      <li><a href="javascript:;">q</a></li>
                      <li><a href="javascript:;">r</a></li>
                      <li><a href="javascript:;">s</a></li>
                      <li><a href="javascript:;">t</a></li>
                      <li><a href="javascript:;">u</a></li>
                      <li><a href="javascript:;">v</a></li>
                      <li><a href="javascript:;">w</a></li>
                      <li><a href="javascript:;">x</a></li>
                      <li><a href="javascript:;">y</a></li>
                      <li><a href="javascript:;">z</a></li>
                    </ul> -->
                  </div>
                  <div class="alert-brands">
                    <div class="list_brand" id="#brands1">
                      <!-- <h2>a</h2> -->
                      <ul>
                        @foreach($topbrands as $brands)
                        <li>
                          @if((\App::getLocale() == 'en'))
                          <a href="{{url('brandproducts',base64_encode($brands->id))}}">
                             <div class="image">
                              <img src="{{ url('/') }}/public/images/brands/{{$brands->image_en}}" alt="A BONNE" style="object-fit: contain; width:100px; height:100px;" >
                            </div>
                            <h4 class="title">{{$brands->name_en}}</h4>
                          </a>
                          @else
                          <a href="{{url('brandproducts',base64_encode($brands->id))}}">
                             <div class="image">
                              <img src="{{ url('/') }}/public/images/brands/{{$brands->image_ar}}" alt="A BONNE" style="object-fit: contain; width:100px; height:100px;" >
                            </div>
                            <h4 class="title">{{$brands->name_ar}}</h4>
                          </a>
                          @endif
                        </li>
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ----inner-dashboard----- -->
          </div>
        </section>
@endsection
