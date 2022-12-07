@extends('layouts.front-app')

@section('content')
<style>
// doesnt work funnly on firefox or edge, need to fix

.range-slider {
  width: 100%;
  text-align: center;
  position: relative;
  .rangeValues {
    display: block;
  }
}

input[type=range] {
  -webkit-appearance: none;
  border: 1px solid white;
  width: 100%;
  position: absolute;
  left: 0;
}

input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 5px;
  background: #ddd;
  border: none;
  border-radius: 3px;

}

input[type=range]::-webkit-slider-thumb {
  -webkit-appearance: none;
  border: none;
  height: 16px;
  width: 16px;
  border-radius: 50%;
  background: #E6C3A3
;
  margin-top: -4px;
    cursor: pointer;
      position: relative;
    z-index: 1;
}

input[type=range]:focus {
  outline: none;
}

input[type=range]:focus::-webkit-slider-runnable-track {
  background: #ccc;
}

input[type=range]::-moz-range-track {
  width: 100%;
  height: 5px;
  background: #ddd;
  border: none;
  border-radius: 3px;
}

input[type=range]::-moz-range-thumb {
  border: none;
  height: 16px;
  width: 16px;
  border-radius: 50%;
  background: #21c1ff;

}


/*hide the outline behind the border*/

input[type=range]:-moz-focusring {
  outline: 1px solid white;
  outline-offset: -1px;
}

input[type=range]::-ms-track {
  width: 100%;
  height: 5px;
  /*remove bg colour from the track, we'll use ms-fill-lower and ms-fill-upper instead */
  background: transparent;
  /*leave room for the larger thumb to overflow with a transparent border */
  border-color: transparent;
  border-width: 6px 0;
  /*remove default tick marks*/
  color: transparent;
    z-index: -4;

}

input[type=range]::-ms-fill-lower {
  background: #777;
  border-radius: 10px;
}

input[type=range]::-ms-fill-upper {
  background: #ddd;
  border-radius: 10px;
}

input[type=range]::-ms-thumb {
  border: none;
  height: 16px;
  width: 16px;
  border-radius: 50%;
  background: #21c1ff;
}

.range-slider .rangeValues{display: block;margin-bottom: 10px;font-size: 14px;font-weight: 600;}

input[type=range]:focus::-ms-fill-lower {
  background: #888;
}

input[type=range]:focus::-ms-fill-upper {
  background: #ccc;
}

</style>
        <section class="bread-sec-nav">
          <div class="container">
            <nav class="breadcrumb-nav">
              <ul class="breadcrumb">
                <li class="breadcrumb-item bold"><a href="{{url('/')}}"><?php echo  __('messages.home') ?></a></li>
                @if(isset($cat_name))
                <li class="breadcrumb-item"><a href="#"><?php if(isset($cat_name)){ echo $cat_name;}?></a></li>
                @else
                <li class="breadcrumb-item"><a href="{{url('brands')}}"><?php echo __('messages.brands');?></a></li>
                @endif
                @if(isset($subcat_name))
                <li class="breadcrumb-item active" aria-current="page"><?php if(isset($subcat_name)){ echo $subcat_name;}?></li>
                @else
                @if(isset($brand_name))
                <li class="breadcrumb-item active" aria-current="page"><?php if(isset($brand_name)){ echo $brand_name;}?></li>
                @endif
                @endif
              </ul>
            </nav>
          </div>
        </section>

        <section class="product-pd pdd-lists">
          <div class="container">
              <div class="row">
                 <div class="col-md-2">
                    <div class="filter-sec">
                      <div class="filter-sec-in">
                        <div class="title-filter">
                            <h4><?php if(isset($subcat_name)){ echo $subcat_name;}?></h4>
                        </div>

                        <button class="btn btn-filter" type="button" data-toggle="collapse" data-target="#collapseExample-ft" aria-expanded="false" aria-controls="collapseExample-ft">
                          <i class="ri-filter-2-fill"></i>
                        </button>
                      </div>

                      <div class="collapse ft-show" id="collapseExample-ft">
                        <div class="card card-body">
                          <div class="filter-accordion">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
                              <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingregi">
                                  <h5 class="panel-title">
                                    <a role="button"  data-toggle="collapse" data-parent="#accordion" href="#collapseregi" aria-expanded="false" aria-controls="collapseregi">
                                        <?php echo  __('messages.all_categories') ?>
                                    </a>
                                  </h5>
                                </div>
                                <div id="collapseregi" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingregi">
                                  <div class="panel-body">
                                     <ul class="lk-list">
                                        @foreach($allcategory as $allcategories)
                                       <li><a href="{{url($allcategories->category_name)}}">@if((\App::getLocale() == 'en')){{$allcategories->category_name_en}}@else {{$allcategories->category_name_ar}} @endif</a></li>
                                        @endforeach
                                     </ul>
                                  </div>
                                </div>
                                <!-- line -->
                                 <span class="drop-line"></span>
                                <!-- line -->
                              </div>
                              @foreach($categories as $category)
                              <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading-{{$category->id}}">
                                  <h5 class="panel-title">
                                  <a role="button"  data-toggle="collapse" href="#collapse-securit{{$category->id}}" aria-expanded="false" aria-controls="collapse-securit{{$category->id}}">
                                   @if((\App::getLocale() == 'en')){{$category->category_name_en}}@else {{$category->category_name_ar}} @endif
                                  </a>
                                  </h5>
                                </div>
                                <div id="collapse-securit{{$category->id}}" class="panel-collapse collapse in<?php if(isset($categoryid)){if($categoryid ==$category->id) {echo ' show';}} ?>" role="tabpanel" aria-labelledby="heading-{{$category->id}}">
                                  <div class="panel-body">
                                    @foreach($category->subcategories as $subcategory)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name ="subcategories" <?php if(isset($subcat[0])){if(in_array($subcategory->id,explode(',',$subcat))){echo "checked";}}?> <?php if(isset($subcategoryid)){if($subcategory->id==$subcategoryid){echo "checked";}}?> value ="{{$subcategory->id}}" id="fabric{{$subcategory->id}}">
                                        <label class="custom-control-label" for="fabric{{$subcategory->id}}">@if((\App::getLocale() == 'en')){{$subcategory->sub_category_name_en}}@else {{$subcategory->sub_category_name_ar}} @endif</label>
                                    </div>
                                    @endforeach

                                  </div>
                                </div>
                              </div>
                              @endforeach
                             <div class="range-box">
                                  <p><?php echo  __('messages.price_range') ?></p>
                                  <div class="range-slider">
                                    <span class="rangeValues"></span>
                                    <input value="<?php if(isset($min)){ echo $min; } else{ echo $minprice; } ?>" min="<?php if(isset($minprice)){echo $minprice;}else{ echo $min;}?>" max="<?php if(isset($maxprice)){echo $maxprice;}else{ echo $max;}?>" step="5" type="range">
                                    <input value="<?php if(isset($max)){ echo $max; } else{ echo $maxprice; } ?>" min="<?php if(isset($minprice)){echo $minprice;}else{ echo $min;}?>" max="<?php if(isset($maxprice)){echo $maxprice;}else{ echo $max;}?>" step="5" type="range">
                                  </div>
                                  <button type="button" onclick ="search();"  class="btn btn-coffee"><?php echo  __('messages.search') ?></button>
                              </div>

                              <form id ="searchform" action ="{{url(\App::getLocale().'/searchfilter')}}" method ="post">
                                @csrf
                              <input type="hidden" name="subcategories[]" id ="subcategories" value ="">
                              <input type="hidden" name="max" id ="max" value ="">
                              <input type="hidden" name="min" id ="min" value ="">
                              </form>
                              <!-- <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading-8">
                                  <h5 class="panel-title">
                                  <a role="button"  data-toggle="collapse" href="#collapse-group" aria-expanded="false" aria-controls="collapse-group">
                                   Price
                                  </a>
                                  </h5>
                                </div>
                                <div id="collapse-group" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-8">
                                  <div class="panel-body price-points">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="color1">
                                        <label class="custom-control-label" for="color1">
                                           1 - 30 SR
                                        </label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="color2">
                                        <label class="custom-control-label" for="color2">
                                           31 - 60 SR
                                        </label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="color3">
                                        <label class="custom-control-label" for="color3">
                                          61 - 90 SR
                                        </label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="color4">
                                        <label class="custom-control-label" for="color4">
                                           91 - 120 SR
                                        </label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="color5">
                                        <label class="custom-control-label" for="color5">
                                           120 - 150 SR
                                        </label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="color6">
                                        <label class="custom-control-label" for="color6">
                                           More Than 150 SR
                                        </label>
                                    </div>
                                  </div>
                                </div>
                              </div> -->
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                 </div>
                 <div class="col-md-10">
                    <div class="default-filder-ck">
                     <!-- <div class="form-group">
                        <label>Sort By</label>
                        <select class="form-control">
                          <option selected="">Default</option>
                          <option value="1">Default</option>
                          <option value="2">Default</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Show</label>
                        <select class="form-control">
                          <option selected="">10</option>
                          <option value="1">11</option>
                          <option value="2">12</option>
                        </select>
                      </div> -->
                    </div>

                    <div class="row">
                        @if(!empty($products))
                        @foreach($products as $product)
                      <div class="col-md-3">
                          <div class="product-box">
                            <figure>
                          
                              <a onclick="view_item('{{$product->category_name}}','{{$product->sub_category_name}}','{{$product->seo_url}}')">
                                <img src="{{ url('/') }}/public/product_images/{{$product->img}}">
                              </a>
                                @if(!empty($product->discount_available))
                                <span>{{$product->discount_available}}% <?php echo  __('messages.off') ?></span>
                                @endif
                            </figure>
                            <figcaption>
                             
                               <span class="pro-brand"><a href="{{url('brandproducts',base64_encode($product->brand_id))}}">@if((\App::getLocale() == 'en')){{$product->brandname_en}}@else {{$product->brandname_ar}} @endif</a></span>
                              <h4 class="pro-name"><a onclick="view_item('{{$product->category_name}}','{{$product->sub_category_name}}','{{$product->seo_url}}')">@if((\App::getLocale() == 'en')){{$product->name_en}}@else {{$product->name_ar}} @endif</a></h4>
                                 @if(!empty($product->offer_price))
                                <div class="pr-rate d-flex ">
                                @else
                                <div class="pr-rate d-flex " id ="offerclass">
                                @endif
                                <h4>SR {{$product->price}} <font>@if(!empty($product->offer_price))SR {{$product->offer_price}}@endif</font></h4>
                                <?php $rating = checkrating($product->id); ?>
                                <div class="rat-pro">
                                  @if(isset($rating['avgrating']))
                                  @if(ceil($rating['avgrating']) == 5)
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  @endif
                                   @if(ceil($rating['avgrating']) == 4)
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill"></i>
                                  @endif
                                   @if(ceil($rating['avgrating']) == 3)
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill"></i>
                                  <i class="ri-star-s-fill"></i>
                                  @endif
                                   @if(ceil($rating['avgrating']) == 2)
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill"></i>
                                  <i class="ri-star-s-fill"></i>
                                  <i class="ri-star-s-fill"></i>
                                  @endif
                                   @if(ceil($rating['avgrating']) == 1)
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill"></i>
                                  <i class="ri-star-s-fill"></i>
                                  <i class="ri-star-s-fill"></i>
                                  <i class="ri-star-s-fill"></i>
                                  @endif
                                  @endif
                                </div>
                              </div>
                              <div class="pro-btns">
                                @if($product->stock_availabity ==1 && $product->quantity >0)
                                <?php $getcolorstatus = getcolor($product->id);?>
                                @if($getcolorstatus==0)
                                <button onclick="addtocart('{{$product->id}}');" id ="addcart{{$product->id}}" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i><font> <?php echo  __('messages.add_to_cart') ?></font></button>
                                @else
                                <a onclick="view_item('{{$product->category_name}}','{{$product->sub_category_name}}','{{$product->seo_url}}')" class="btn btn-border-brown"><i class="ri-shopping-cart-2-line"></i><font> <?php echo  __('messages.add_to_cart') ?></font></a>
                                @endif
                                <?php $iswishlist = getwishlist($product->id); ?>
                                <button id="btnTest" style="display:none;"data-toggle="modal" data-target="#my-cart">Go To Cart</button>
                                @if(!empty(Auth::id()))
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$product->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$product->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @else
                                <button class="btn btn-border-brown" disabled><i class="ri-shopping-cart-2-line"></i><font> <?php echo  __('messages.add_to_cart') ?></font></button>
                                @if(!empty(Auth::id()))
                                <?php $iswishlist = getwishlist($product->id); ?>
                                @if(!empty($iswishlist))
                                <button onclick="addtowishlist('{{$product->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-fill"></i></button>
                                @else
                                <button onclick="addtowishlist('{{$product->id}}');" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></button>
                                @endif
                                @else
                                <a href="javascript:;" data-toggle="modal" data-target="#login-modal" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                                @endif
                                @endif
                              </div>
                            </figcaption>
                            @if($product->stock_availabity ==2 || $product->quantity ==0)
                            <div class="pro-outof-stock">
                              <h6><?php echo  __('messages.out_of_stock') ?></h6>
                            </div>
                            @endif
                          </div>
                      </div>
                       @endforeach
                       @else
                      <div class="col-md-3">
                          <div class="product-box">
                            <figure>
                              <a href="javascript:;">
                                <img src="{{asset('assets/front-end/images/pro12.jpg')}}">
                              </a>
                              <span>20% <?php echo  __('messages.off') ?></span>
                            </figure>
                            <figcaption>
                              <span class="pro-brand"><a href="{{url('productdetails')}}">Brand name</a></span>
                              <h4 class="pro-name"><a href="{{url('productdetails')}}">Gehna Earring</a></h4>
                              <div class="pr-rate d-flex">
                                <h4>SR 30 <font>SR 22 </font></h4>
                                <div class="rat-pro">
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill active"></i>
                                  <i class="ri-star-s-fill"></i>
                                  <i class="ri-star-s-fill"></i>
                                </div>
                              </div>
                              <div class="pro-btns">
                                <a href="javascript:;" class="btn btn-border-brown waves-effect waves-light"><i class="ri-shopping-cart-2-line"></i> <?php echo  __('messages.add_to_cart') ?></a>
                                <a href="javascript:;" class="btn btn-border-black waves-effect waves-light"><i class="ri-heart-line"></i></a>
                              </div>
                            </figcaption>
                          </div>
                      </div>
                     @endif
                    </div>
                      {!! $products->links() !!}
                 </div> 
              </div>
          </div>  
        </section>
<script>
function getVals(){
  // Get slider values
  let parent = this.parentNode;
  let slides = parent.getElementsByTagName("input");
    let slide1 = parseFloat( slides[0].value );
    let slide2 = parseFloat( slides[1].value );
  // Neither slider will clip the other, so make sure we determine which is larger
  if( slide1 > slide2 ){ let tmp = slide2; slide2 = slide1; slide1 = tmp; }

  let displayElement = parent.getElementsByClassName("rangeValues")[0];
      displayElement.innerHTML = "SR" + slide1 + " - SR" + slide2;
    $("#min").val(slide1);
    $("#max").val(slide2);


}

window.onload = function(){
  // Initialize Sliders
  let sliderSections = document.getElementsByClassName("range-slider");
      for( let x = 0; x < sliderSections.length; x++ ){
        let sliders = sliderSections[x].getElementsByTagName("input");
        for( let y = 0; y < sliders.length; y++ ){
          if( sliders[y].type ==="range" ){
            sliders[y].oninput = getVals;
            // Manually trigger event first time to display values
            sliders[y].oninput();
          }
        }
      }
}
</script>

<script>

var subcategories = [];

function search()
{
var data = $("input:checkbox[name=subcategories]:checked").each(function () {
           subcategories.push($(this).val());
        });
$("#subcategories").val(subcategories);
document.getElementById("searchform").submit();

}
</script>
@endsection
