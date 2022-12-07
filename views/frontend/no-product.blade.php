@extends('layouts.front-app')

@section('content')
    <section class="bread-sec-nav">
        <div class="container">
            <nav class="breadcrumb-nav">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item bold"><a href="{{ url('/') }}"><?php echo __('messages.home'); ?></a></li>
                    <li class="breadcrumb-item"><a href="#"><?php echo __('messages.products'); ?></a></li>
                </ul>
            </nav>
        </div>
    </section>
    <section class="next-dash">
        <div class="container">
            <div class="no-avilable">
                <figure><img src="{{ asset('assets/front-end/images/no-producat.svg') }}"></figure>
                <figcaption>
                    <h4><?php echo __('messages.no_product_available'); ?></h4>
                </figcaption>
            </div>
        </div>
    </section>
@endsection
