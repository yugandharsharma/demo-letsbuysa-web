@extends('layouts.front-app')

@section('content')
<section class="help-bg">
        <div class="container-fluid">
             <div class="help-tetx-innre">
               <h2><?php echo  __('messages.browsetopics') ?></h2>
                <div class="header-search">
                  <div class="header-search-in">
                  <form method="POST" action ="{{url('search_article_category')}}">
                  @csrf
                    <input type="text" name="name" placeholder="<?php echo  __('messages.helpsearch') ?>">
                    <button><i class="ri-search-line"></i></button>
                  </form>
                  </div>
                </div>
             </div>
        </div>  
      </section>

      <section class="help-section">
        <div class="container">
          <h3><?php echo  __('messages.browsetopics') ?></h3>
          <ul class="blocks-list">
            @foreach($category as $record)
            <li class="blocks-item">
              <a href="{{url('helpsubcategory',$record->id)}}">
                <div class="blocks-item-inner">
                @if((\App::getLocale() == 'en'))
                  <figure><img src="{{ url('/') }}/public/images/help/{{$record->image_en}}"></figure>
                  <figcaption>
                    <h4>{{$record->name_en}}</h4>
                    {!!$record->description_en!!}
                    <div class="blocks-item-count">
                      <span>{{$record->articlecount}}</span>
                      <h6><?php echo  __('messages.article') ?></h6>
                    </div>
                  </figcaption>
                @else
                  <figure><img src="{{ url('/') }}/public/images/help/{{$record->image_ar}}"></figure>
                  <figcaption>
                    <h4>{{$record->name_ar}}</h4>
                    {!!$record->description_ar!!}
                    <div class="blocks-item-count">
                      <span>{{$record->articlecount}}</span>
                      <h6><?php echo  __('messages.article') ?></h6>
                    </div>
                  </figcaption>
                @endif
                </div>
              </a>
            </li>
            @endforeach
          </ul>
        </div>
      </section>
@endsection
