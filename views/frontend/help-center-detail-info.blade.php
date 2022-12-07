@extends('layouts.front-app')

@section('content')
<section class="help-bg">
        <div class="container-fluid">
             <div class="help-tetx-innre">
               <h2><?php echo  __('messages.ahlan_how_we_can_help') ?></h2>
              {{--  <div class="header-search">
                  <div class="header-search-in">
                    <input type="text" name="" placeholder="Search designs and products">
                    <button><i class="ri-search-line"></i></button>
                  </div>
                </div>--}}
             </div>
        </div>  
      </section>

      <section class="help-section">
        <div class="container">
              <ul class="about-help">
                <li>
                   <nav class="breadcrumb-list">
                      <ul class="breadcrumb">
                      @if((\App::getLocale() == 'en'))
                        <li class="breadcrumb-item"><a href="{{url('help_center')}}"><?php echo  __('messages.how_we_can_help_you') ?></a></li>
                        <li class="breadcrumb-item"><a href="{{url('helpsubcategory',$data->category_id)}}">{{$data->categoryname}}</a></li>
                        <li class="breadcrumb-item active">{{$data->title_en}}</li>
                      @else
                        <li class="breadcrumb-item"><a href="{{url('help_center')}}"><?php echo  __('messages.how_we_can_help_you') ?></a></li>
                        <li class="breadcrumb-item"><a href="{{url('helpsubcategory',$data->category_id)}}">{{$data->categoryname}}</a></li>
                        <li class="breadcrumb-item active">{{$data->title_ar}}</li>
                      @endif
                      </ul>
                    </nav>
                </li>
              </ul>
              
              <div class="detail-help-info">
                  <div class="row">
                       <div class="col-md-4">
                          <div class="title-help-list">
                            <h4><?php echo  __('messages.article_in_this_section') ?></h4>
                            <ul>
                              @foreach($all_articles as $article) 
                              <li><a href="{{url('helparticle',$article->id)}}{{'/'}}{{$article->subcategory_id}}">{{$article->title_en}}</a></li>
                              @endforeach
                            </ul>
                          </div>
                       </div>
                       <div class="col-md-8">
                            <div class="inner-info-text">
                              <div class="info-hp-head">
                                @if((\App::getLocale() == 'en'))
                                <h1>{{$data->title_en}}</h1>
                                @else
                                <h1>{{$data->title_ar}}</h1>
                                @endif
                                <span>Updated {{$data->updated_at}}</span>
                              </div>

                              <div class="semi-inner-info">
                              @if((\App::getLocale() == 'en'))
                              {!!$data->description_en!!}
                              @else 
                              {!!$data->description_ar!!}
                              @endif
                              </div>
                              <div class="helpful-btn">
                                <h5><?php echo  __('messages.was_this_article_helpful') ?></h5>
                                <div class="d-flex">
                                  <a href="{{url('article_review',$data->id)}}{{'/'}}1" class="btn btn-border-brown"><i class="ri-check-line"></i> Yes</a>
                                  <a href="{{url('article_review',$data->id)}}{{'/'}}0" class="btn btn-border-brown"><i class="ri-close-fill"></i> No</a>
                                </div>
                              </div>
                            </div>
                       </div>
                  </div>
              </div>
                
        </div>
      </section>
 
@endsection
