@extends('layouts.front-app')

@section('content')
<section class="help-bg">
        <div class="container-fluid">
             <div class="help-tetx-innre">
               <h2><?php echo  __('messages.ahlan_how_we_can_help') ?></h2>
              {{--  <div class="header-search">
                  <div class="header-search-in">
                  <form method="POST" action ="{{url('search_article_subcategory',$category->id)}}">
                  @csrf
                    <input type="text" name="name" placeholder="Search designs and products">
                    <button><i class="ri-search-line"></i></button>
                  </form>
                  </div>--}}
                </div>
             </div>
        </div>  
      </section>

      <section class="help-section">
        <div class="container">
              <ul class="about-help">
                <li>
                   <nav class="breadcrumb-list">
                      <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('help_center')}}"><?php echo  __('messages.how_we_can_help_you') ?></a></li>
                        @if((\App::getLocale() == 'en'))
                        <li class="breadcrumb-item active">{{$category->name_en}}</li>
                        @else
                        <li class="breadcrumb-item active">{{$category->name_ar}}</li>
                        @endif
                      </ul>
                    </nav>
                </li>
              </ul>
              
              <div class="detail-helps">
                @if((\App::getLocale() == 'en'))
                <h3>{{$category->name_en}}</h3>
                @else
                <h3>{{$category->name_ar}}</h3>
                @endif
                <h6>{{$articlecount}} <?php echo  __('messages.article') ?> </h6>
                @if((\App::getLocale() == 'en'))
                <p>{!!$category->description_en!!}</p>
                @else
                <p>{!!$category->description_ar!!}</p>
                @endif
                @foreach($subcategory as $record)
                <div class="article-list">
                  @if(!empty($record->helpsubcategories) && count($record->helpsubcategories)>0)
                @if((\App::getLocale() == 'en'))
                <h4>{{$record->name_en}}</h4>
                @else
                <h4>{{$record->name_ar}}</h4>
                @endif
                  @endif
                  <ul>
                   @foreach($record->helpsubcategories as $helparticle) 
                    <li>
                       <a href="{{url('helparticle',$helparticle->id)}}{{'/'}}{{$helparticle->subcategory_id}}">    
                      @if((\App::getLocale() == 'en'))
                      {{$helparticle->title_en}}
                      @else
                      {{$helparticle->title_ar}}
                      @endif
                       </a>
                    </li>
                   @endforeach                    
                  </ul>
                </div>
                @endforeach
            

        </div>
      </section>
@endsection
