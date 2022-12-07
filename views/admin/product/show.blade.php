@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">product content Show</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.index')}}">product content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">product content Show</li>
         </ol>
     </div>
       <div class="col-sm-3">
       <a href="{{route('product.index')}}">
        <button  class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                   <form id="showProduct" action="#" enctype="multipart/form-data" method="post">
              @csrf
              @method('PUT')

                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Personal Info
                </h4>

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Name(English):</label>
                  <div class="col-sm-10">
                    {{$product->name_en}}
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Name(Arabic):</label>
                  <div class="col-sm-10">
                   {{$product->name_ar}}
                  </div>
            
                </div>
               

                <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Category Name(English):</label>
                      <div class="col-sm-10">
                         @foreach($category as $sie)
                          {{ $sie->category_name_en }}
                        @endforeach
                    
                     </div>
              </div>
               <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Sub Category Name(English):</label>
                      <div class="col-sm-10">
                      @foreach($sub_category as $si)
                        {{ $si->sub_category_name_en }}
                      @endforeach
                     </div>
              </div>
              <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Category Name(Arabic):</label>
                      <div class="col-sm-10">
                       @foreach($category as $si)
                          {{ $si->category_name_ar }}
                        @endforeach
                     </div>
              </div>
               <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Sub Category Name(Arabic)</label>
                      <div class="col-sm-10">
                         @foreach($sub_category as $si)
                        {{ $si->sub_category_name_ar }}
                       @endforeach
                     </div>
              </div>
             
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(English):</label>
                  <div class="col-sm-10">
                   {{ strip_tags($product->description_en)}}
                  </div>
                
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(Arabic):</label>
                  <div class="col-sm-10">
                    {{strip_tags($product->description_ar)}}
                  </div>
                
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Model Number(English):</label>
                  <div class="col-sm-10">
                    {{$product->model_en}}
                  </div>
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Model Number(Arabic):</label>
                  <div class="col-sm-10">
                   {{$product->model_ar}}
                  </div>
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product SKU(English):</label>
                  <div class="col-sm-10">
                    {{$product->sku_en}}
                  </div>
                </div> 

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product SKU(Arabic):</label>
                  <div class="col-sm-10">
                    {{$product->sku_ar}}
                  </div>
                 
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Quantity:</label>
                  <div class="col-sm-10"> 

                    {{$product->quantity}}
                  </div>
               
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Price:</label>
                  <div class="col-sm-10">
                    {{$product->price}}
                  </div>
               
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Dimensions(English)(L*W*H):</label>
                  <div class="col-sm-10"> 
                   {{   $product->dimension_en}}
                  </div>
                
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Dimensions(Arabic)(L*W*H):</label>
                  <div class="col-sm-10">
                   {{$product->dimension_ar}}
                  </div>
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Title(English):</label>
                  <div class="col-sm-10">
                   {{$product->meta_tag_title_en}}
                  </div>
                 
                </div>

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Title(Arabic):</label>
                  <div class="col-sm-10">
                   {{$product->meta_tag_title_ar}}
                  </div>
               
                </div>

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Description(English):</label>
                  <div class="col-sm-10">
                  {{$product->meta_tag_desc_en}}
                  </div>
                
                </div>

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Description(Arabic):</label>
                  <div class="col-sm-10">
                    {{$product->meta_tag_desc_ar}}
                  </div>
                
                </div>

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag keywords(English):</label>
                  <div class="col-sm-10">
                  {{$product->keywords_en}}
                  </div>
                 
                </div>

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag keywords(Arabic):</label>
                  <div class="col-sm-10">
                   {{$product->keywords_ar}}
                  </div>
                
                </div>

                 @if($product->img)
               <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image1:</label>
                  <div class="col-sm-10">
                    
                    <img src="{{ url('/') }}/public/product_images/{{$product->img}}"  height="100" width="150">
                    
                  </div>
             
                </div>
                 @endif
                @if($product->img)
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image2:</label>
                  <div class="col-sm-10">                    
                    <img src="{{ url('/') }}/public/product_images/{{$product->img1}}"  height="100" width="150">
                  </div>
                </div>
                 @endif
                 @if($product->img2)
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image3:</label>
                  <div class="col-sm-10">
                    
                    
                    <img src="{{ url('/') }}/public/product_images/{{$product->img2}}"  height="100" width="150">
                   
               </div>
                </div>
                 @endif
                 @if($product->img3)
                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image4:</label>
                  <div class="col-sm-10">
                   
                    
                    <img src="{{ url('/') }}/public/product_images/{{$product->img3}}"  height="100" width="150">
                  </div>
                </div>
                @endif
                 @if($product->img4)
                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image5:</label>
                  <div class="col-sm-10">
                   
                    
                    <img src="{{ url('/') }}/public/product_images/{{$product->img4}}"  height="100" width="150">
                  </div>
                </div>
                @endif
                 @if($product->img5)
                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image6:</label>
                  <div class="col-sm-10">
                   
                    
                    <img src="{{ url('/') }}/public/product_images/{{$product->img5}}"  height="100" width="150">
                  </div>
                </div>
                @endif
                 <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Stock Availabity:</label>
                  <div class="col-sm-10">
                   @if($product->stock_availabity == 1)In Stock @else Out Of Stock @endif
                  </div>
                </div>
               
              </form>
            </div>
          </div>
        </div>
      </div>
  </div>
  </div>

@include('includes-file.footer')

  @if(session()->has('success'))
  <script>
  round_success_noti_record_update();
  </script>
  @endif 
    <script> 
  $('#editor1').summernote({
      height: 400,
      tabsize: 2
  });
    $('#editor2').summernote({
      height: 400,
      tabsize: 2
  });
 </script>
 <script >
 function isNumberKey(evt)
{
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
   return true;
}
</script>

 </body>
</html>

      