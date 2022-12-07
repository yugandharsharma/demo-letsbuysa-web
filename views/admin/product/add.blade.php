@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Product Content Add</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.index')}}">Product Content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Product Content Add</li>
         </ol>
     </div>
       <div class="col-sm-3">
       <a href="{{route('product.index')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="addProduct" action="{{ route('product.store')}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   General
                </h4>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Name(English)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" value="{{old('name_en')}}" maxlength="200" id="name_en" name="name_en" required="">
                  </div>
                 @error('name_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Name(Arabic)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" value="{{old('name_ar')}}" maxlength="200" id="name_ar" name="name_ar" required="">
                  </div>
                 @error('name_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                  <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Category Name(English)<span style="color: red; font-size: 14px;">*</span></label>
                      <div class="col-sm-10">
                        <select class="form-control @error('category_id_en') is-invalid @enderror" onclick="Getsubcategory(this.value);" name="category_id_en" required="">
                        <option value="">Select category Name</option>
                        @foreach($category as $record)
                        <!-- <option value="{{$record->id}}">{{$record->category_name_en}}</option> -->
                        <option value="{{ $record->id }}" {{ old('category_id_en') == $record->id ? "selected" : "" }}>{{ $record->category_name_en}}</option> 
                        @endforeach
                      </select>
                    </div>
                 @error('category_name_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                 <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Sub Category Name(English)<span style="color: red; font-size: 14px;">*</span></label>
                      <div class="col-sm-10">
                        <select class="form-control @error('sub_category_id') is-invalid @enderror multiple-select" multiple="multiple" name="sub_category_id_en[]" id="sub_category_id_en" required="">
                      </select>
                    </div>
                 @error('sub_category_name_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

               <!--  <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Category Name(Arabic)<span style="color: red; font-size: 14px;">*</span></label>
                      <div class="col-sm-10">
                        <select class="form-control @error('category_id_ar') is-invalid @enderror" name="category_id_ar" id="category_id_ar" required="">
                        <option value="">Select category Name</option>
                        @foreach($category as $record)
                         <option value="{{ $record->id }}" {{ old('category_id_ar') == $record->id ? "selected" : "" }}>{{ $record->category_name_ar}}</option>
                        @endforeach
                      </select>
                    </div>
                 @error('category_name_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                 <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Sub Category Name(Arabic)<span style="color: red; font-size: 14px;">*</span></label>
                      <div class="col-sm-10">
                        <select class="form-control @error('sub_category_id_ar') is-invalid @enderror" name="sub_category_id_ar" id="sub_category_id_ar" required="">
                        <option value="">Select sub category Name</option>
                        @foreach($sub_category as $record)
                        <option value="{{ $record->id }}" {{ old('sub_category_id_ar') == $record->id ? "selected" : "" }}>{{ $record->sub_category_name_ar}}</option>
                        @endforeach
                      </select>
                    </div>
                 @error('sub_category_name_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div> -->
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(English)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description_en') is-invalid @enderror" placeholder=" Description" id='description_en' name="description_en" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"value="{{old('description_en')}}" required="">{{old('description_en')}}</textarea>
                  </div>
                 @error('description_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(Arabic)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                     <textarea class="textarea" class="form-control @error('description_ar') is-invalid @enderror" placeholder=" Description" id='description_ar' name="description_ar" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"value="{{old('description_ar')}}" required="">{{old('description_ar')}}</textarea>
                  </div>
                @error('description_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror 
                </div>

                <!-- <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Discount(%)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control @error('discount_available') is-invalid @enderror" value="{{old('discount_available')}}" maxlength="50" id="discount_available" name="discount_available">
                  </div>
                 @error('discount_available')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div> -->
                
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Model Number(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('model_en') is-invalid @enderror" value="{{old('model_en')}}" maxlength="50" id="model_en" name="model_en">
                  </div>
                 @error('model_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Model Number(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('model_ar') is-invalid @enderror" value="{{old('model_ar')}}" maxlength="50" id="model_ar" name="model_ar">
                  </div>
                 @error('model_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <!-- <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Serial Number</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror" value="{{old('serial_number')}}" maxlength="50" id="serial_number" name="serial_number" >
                  </div>
                 @error('serial_number')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Type</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('type') is-invalid @enderror" value="{{old('type')}}" maxlength="50" id="type" name="type" >
                  </div>
                 @error('type')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Material</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('material') is-invalid @enderror" value="{{old('material')}}" maxlength="50" id="material" name="material" >
                  </div>
                 @error('material')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div> -->

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Available</label>
                  <div class="col-sm-10">
                    <input type="text" maxlength="50" value="{{old('availabledate')}}" placeholder="Select Date" class="form-control" id="availabledate" name="availabledate">
                  </div>
                </div>
                 @error('availabledate')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product SKU(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('sku_en') is-invalid @enderror" value="{{old('sku_en')}}" maxlength="50" id="sku_en" name="sku_en">
                  </div>
                 @error('sku_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div> 
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product SKU(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('sku_ar') is-invalid @enderror" value="{{old('sku_ar')}}" maxlength="50" id="sku_ar" name="sku_ar" >
                  </div>
                 @error('sku_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Quantity<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10"> 

                    <input type="text" class="form-control @error('quantity') is-invalid @enderror" value="{{old('quantity')}}" maxlength="50" id="quantity" name="quantity" required=""onkeypress="return isNumberKey(event)">
                  </div>
                 @error('quantity')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <!-- <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Brand Name (English)</label>
                  <div class="col-sm-10"> 

                    <input type="text" class="form-control @error('brandname_en') is-invalid @enderror" value="{{old('brandname_en')}}" maxlength="50" id="brandname_en" name="brandname_en">
                  </div>
                 @error('brandname_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Brand Name (Arabic)</label>
                  <div class="col-sm-10"> 

                    <input type="text" class="form-control @error('brandname_ar') is-invalid @enderror" value="{{old('brandname_ar')}}" maxlength="50" id="brandname_ar" name="brandname_ar">
                  </div>
                 @error('brandname_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div> -->

                <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Brand</label>
                      <div class="col-sm-10">
                        <select class="form-control @error('brand') is-invalid @enderror" name="brand">
                        <option value="">Select Brand Name</option>
                        @foreach($brand as $record)
                        <!-- <option value="{{$record->id}}">{{$record->category_name_en}}</option> -->
                        <option value="{{ $record->id }}" >{{ $record->name_en}}</option> 
                        @endforeach
                      </select>
                    </div>
                 @error('brand')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Reward Points</label>
                  <div class="col-sm-10"> 

                    <input type="text" class="form-control @error('rewardpoints') is-invalid @enderror" value="{{old('rewardpoints')}}" maxlength="50" id="rewardpoints" name="rewardpoints">
                  </div>
                 @error('rewardpoints')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Offer Label (English)</label>
                  <div class="col-sm-10"> 

                    <input type="text" class="form-control @error('offer_label_en') is-invalid @enderror" value="{{old('offer_label_en')}}" maxlength="50" id="offer_label_en" name="offer_label_en">
                  </div>
                 @error('offer_label_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Offer Label (Arabic)</label>
                  <div class="col-sm-10"> 

                    <input type="text" class="form-control @error('offer_label_ar') is-invalid @enderror" value="{{old('offer_label_ar')}}" maxlength="50" id="offer_label_ar" name="offer_label_ar">
                  </div>
                 @error('offer_label_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Tax Class (English)</label>
                  <div class="col-sm-10"> 

                    <input type="text" class="form-control @error('tax_class_en') is-invalid @enderror" value="{{old('tax_class_en')}}" maxlength="50" id="tax_class_en" name="tax_class_en">
                  </div>
                 @error('tax_class_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Tax Class (Arabic)</label>
                  <div class="col-sm-10"> 

                    <input type="text" class="form-control @error('tax_class_ar') is-invalid @enderror" value="{{old('tax_class_ar')}}" maxlength="50" id="tax_class_ar" name="tax_class_ar">
                  </div>
                 @error('tax_class_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Seo Url<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10"> 

                    <input type="text" class="form-control @error('seo_url') is-invalid @enderror" value="{{old('seo_url')}}" maxlength="500" id="seo_url" name="seo_url">
                  </div>
                 @error('seo_url')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Base Price<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('price') is-invalid @enderror" value="{{old('price')}}" maxlength="50" id="price" name="price" required="" onkeypress="return isNumberKey(event)">
                  </div>
                  @error('price')
                  <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Offer Price</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('offer_price') is-invalid @enderror" value="{{old('offer_price')}}" maxlength="50" id="offer_price" name="offer_price"  onkeypress="return isNumberKey(event)">
                  </div>
                  @error('offer_price')
                  <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                 

                <!-- <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Dimensions(English)(L*W*H)</label>
                  <div class="col-sm-10"> 
                    <input type="text" class="form-control @error('dimension_en') is-invalid @enderror" value="{{old('dimension_en')}}" maxlength="50" id="dimension_en" name="dimension_en">
                  </div>
                 @error('dimension_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Dimensions(Arabic)(L*W*H)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('dimension_ar') is-invalid @enderror" value="{{old('dimension_ar')}}" maxlength="50" id="dimension_ar" name="dimension_ar">
                  </div>
                 @error('dimension_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Weight(Grams)</label>
                  <div class="col-sm-10"> 
                    <input type="number" class="form-control @error('weight') is-invalid @enderror" value="{{old('weight')}}" maxlength="50" id="weight" name="weight">
                  </div>
                 @error('weight')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div> -->

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Title(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_title_en') is-invalid @enderror" value="{{old('meta_tag_title_en')}}" maxlength="70" id="meta_tag_title_en" name="meta_tag_title_en" >
                  </div>
                 @error('meta_tag_title_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Title(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_title_ar') is-invalid @enderror" maxlength="70" id="meta_tag_title_ar" name="meta_tag_title_ar"value="{{old('meta_tag_title_ar')}}" >
                  </div>
                 @error('meta_tag_title_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Description(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_desc_en') is-invalid @enderror" maxlength="250" id="meta_tag_desc_en" name="meta_tag_desc_en"value="{{old('meta_tag_desc_en')}}" >
                  </div>
                 @error('meta_tag_desc_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Description(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_desc_ar') is-invalid @enderror" row='4' maxlength="250" id="meta_tag_desc_ar" name="meta_tag_desc_ar" value="{{old('meta_tag_desc_ar')}}" >
                  </div>
                 @error('meta_tag_desc_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag keywords(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('keywords_en') is-invalid @enderror" maxlength="250" id="keywords_en" name="keywords_en" value="{{old('keywords_en')}}">
                  </div>
                 @error('keywords_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag keywords(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('keywords_ar') is-invalid @enderror" maxlength="250" id="keywords_ar" name="keywords_ar" value="{{old('keywords_ar')}}">
                  </div>
                 @error('keywords_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image1:(Required)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <input type="file" name="img" class="form-control @error('img') is-invalid @enderror" value="{{old('img')}}"
                    required="" id="img">
                  </div>
                @error('img')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image2:(Required)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <input type="file" name="img1" class="form-control @error('img1') is-invalid @enderror" value="{{old('img1')}}" required="">
                  </div>
                @error('img1')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image3:(Optional)</label>
                  <div class="col-sm-10">
                    <input type="file" name="img2" class="form-control @error('img2') is-invalid @enderror" value="{{old('img2')}}">
                  </div>
                @error('img2')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image4:(Optional)</label>
                  <div class="col-sm-10">
                    <input type="file" name="img3" class="form-control @error('img3') is-invalid @enderror" value="{{old('img3')}}">
                  </div>
                @error('img3')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image5:(Optional)</label>
                  <div class="col-sm-10">
                    <input type="file" name="img4" class="form-control @error('img4') is-invalid @enderror" value="{{old('img4')}}">
                  </div>
                @error('img4')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image6:(Optional)</label>
                  <div class="col-sm-10">
                    <input type="file" name="img5" class="form-control @error('img5') is-invalid @enderror" value="{{old('img5')}}">
                  </div>
                @error('img5')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Related Products</label>
                  <div class="col-sm-10">
                  <select class="form-control multiple-select" multiple="multiple" name="relatedproducts[]">
                          @if(!empty($Products))
                          @foreach($Products as $key=>$value)
                          <option value="{{$value->id}}">{{$value->name_en}}</option>
                          @endforeach
                          @endif
                      </select>
                    </div>
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Filters</label>
                  <div class="col-sm-10">
                  <select class="form-control multiple-select" multiple="multiple" name="productfilters[]">
                          @if(!empty($filter))
                          @foreach($filter as $key=>$filtervalue)
                          <option value="{{$filtervalue->id}}">{{$filtervalue->filtername}} {{$filtervalue->filter_name_en}}</option>
                          @endforeach
                          @endif
                      </select>
                    </div>
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Delivery Features</label>
                  <div class="col-sm-10">
                  <select class="form-control multiple-select" multiple="multiple" name="deliveryfeatures[]">
                          @if(!empty($product_delivery_features))
                          @foreach($product_delivery_features as $key=>$value)
                          <option value="{{$value->id}}">{{$value->title_en}}</option>
                          @endforeach
                          @endif
                      </select>
                    </div>
                </div>

                <!-- <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Colors</label>
                  <div class="col-sm-10">
                  <select class="form-control multiple-select" multiple="multiple" name="colors[]">
                    @foreach($colors as $color)
                          <option value="{{$color->colorcode}}">{{$color->name}}</option>
                    @endforeach
                  </select>
                    </div>
                </div> -->

                <!-- <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Tax Class</label>
                  <div class="col-sm-10">
                   <select class="form-control @error('taxclass') is-invalid @enderror" id="taxclass" name="taxclass">
                        <option value="1">Taxable Goods</option>
                        <option value="2">Downloadable Products</option>
                    </select>
                  </div>
                </div> -->

                 <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Stock Availabity</label>
                  <div class="col-sm-10">
                   <select class="form-control @error('stock_availabity') is-invalid @enderror" id="stock_availabity" name="stock_availabity">
                        <option value="1">In Stock</option>
                        <option value="0">Out Of Stock</option>
                    </select>
                  </div>
                </div>

                <!-- <div class="form-group row">
              <label for="input-1" class="col-sm-2 col-form-label" style="padding-left: 12px;">Color Images & Quantity</label>
                  <div class="col-sm-8">
             <div class="input-group control-group increment2" >
             <input type="file" name="filename[]" class="form-control"accept=".jpg,.jpeg,.png">
             <input type="number" name="colorquantity[]" class="form-control"  placeholder="Enter Quantity">
             <input type="number" name="colorprice[]" value="0" class="form-control"  placeholder="Enter Price">
             <select class="form-control" name="colors[]" placeholder="Please Choose Color">
                        <option value="">Please Select color</option>
                  @foreach($colors as $color)
                        <option value="{{$color->colorcode}}">{{$color->name}}</option>
                  @endforeach
             </select>
             <div class="input-group-btn"> 
             <button class="btn btn-success addpdf" type="button"><i class="glyphicon glyphicon-plus"></i>    Add</button>
             </div>
             </div>
             <div class="clone2 hide" style="display:none;">
              <div class="control-group input-group" style="margin-top:10px">
                <input type="file" name="filename[]" class="form-control" accept=".jpg,.jpeg,.png">
                <input type="number" name="colorquantity[]" class="form-control" placeholder="Enter Quantity">
                <input type="number" name="colorprice[]" value="0" class="form-control"  placeholder="Enter Price">
                <select class="form-control" name="colors[]" placeholder="Please Choose Color">
                        <option value="">Please Select color</option>
                  @foreach($colors as $color)
                        <option value="{{$color->colorcode}}">{{$color->name}}</option>
                  @endforeach
                </select>
                <div class="input-group-btn"> 
                  <button class="btn btn-danger removepdf" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                </div>
              </div>
             </div>
             </div>
             </div> -->

               <div class="form-group row">
              <label for="input-1" class="col-sm-2 col-form-label"style="padding-left: 12px;">Attributes</label>
              <div class="col-sm-8">
		             <div class="input-group control-group increment1" >
                  <select class="form-control" name="attribute[]" placeholder="Please Choose Color">
                    <option value="">Please Select Attribute</option>
                  @foreach($attribute as $attr)
                    <option value="{{$attr->id}}">{{$attr->name_en}}</option>
                  @endforeach
                </select>
			           <input type="text" name="attributename_en[]" class="form-control" placeholder="Enter Attribute In English">
				         <input type="text" name="attributename_ar[]" class="form-control" placeholder="Enter Attribute In Arabic">
			           <div class="input-group-btn"> 
			           <button class="btn btn-success addvideo" type="button"><i class="glyphicon glyphicon-plus"></i>    Add</button>
			           </div>
			           </div>
				         <div class="clone1 hide" style="display:none;">
                  <div class="control-group input-group" style="margin-top:10px">
                   <select class="form-control" name="attribute[]" placeholder="Please Choose Color">
                    <option value="">Please Select Attribute</option>
                  @foreach($attribute as $attr)
                    <option value="{{$attr->id}}">{{$attr->name_en}}</option>
                  @endforeach
                </select>
                    <input type="text" name="attributename_en[]" class="form-control" placeholder="Enter Attribute In English">
				            <input type="text" name="attributename_ar[]" class="form-control" placeholder="Enter Attribute In Arabic">
				            <div class="input-group-btn"> 
				              <button class="btn btn-danger removevideo" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
				            </div>
				          </div>
				         </div>
		          </div>
		        </div>

             <div class="form-group row">
              <label for="input-1" class="col-sm-2 col-form-label"style="padding-left: 12px;">Options</label>
              <div class="col-sm-8">
		             <div class="input-group control-group increment3" id="testingclone" >
                  <select class="form-control" name="colors[]" placeholder="Please Choose Color">
                        <option value="">Please Select color</option>
                  @foreach($colors as $color)
                        <option value="{{$color->colorcode}}">{{$color->name}}</option>
                  @endforeach
                 </select>
                 <input type="file" name="filename[]" class="form-control"accept=".jpg,.jpeg,.png">
                  <select class="form-control" name="optionvalue[]" onclick="Getoption(this);" placeholder="Please Choose Color">
                    <option value="">Please Select Option</option>
                  @foreach($option as $optiondata)
                    <option value="{{$optiondata->id}}">{{$optiondata->name_en}}</option>
                  @endforeach
                  </select>
                    <select class="form-control optionselectbox @error('optionid') is-invalid @enderror" name="optionid[]" id="optionid">
                    <option value="">Select Option Value</option>
                    </select>
			           <input type="text" name="optionprice[]" class="form-control" placeholder="Enter Option Price">
				         <input type="text" name="optionquantity[]" class="form-control" placeholder="Enter Option Quantity">
			           <div class="input-group-btn"> 
			           <button class="btn btn-success addoption" type="button"><i class="glyphicon glyphicon-plus"></i>    Add</button>
			           </div>
			           </div>
				         <div class="clone3 hide" style="display:none;">
                  <div class="control-group input-group" style="margin-top:10px">
                   <select class="form-control" name="colors[]" placeholder="Please Choose Color">
                        <option value="">Please Select color</option>
                  @foreach($colors as $color)
                        <option value="{{$color->colorcode}}">{{$color->name}}</option>
                  @endforeach
                 </select>
                 <input type="file" name="filename[]" class="form-control"accept=".jpg,.jpeg,.png">
                  <select class="form-control" name="optionvalue[]" onclick="Getoption(this.value);" placeholder="Please Choose Color">
                    <option value="">Please Select Option</option>
                  @foreach($option as $optiondata)
                    <option value="{{$optiondata->id}}">{{$optiondata->name_en}}</option>
                  @endforeach
                  </select>
                    <select class="form-control @error('optionid') is-invalid @enderror " name="optionid[]" id="optionid">
                    <option value="">Select Option Value</option>
                    </select>
                    <input type="text" name="optionprice[]" class="form-control" placeholder="Enter Option Price">
				            <input type="text" name="optionquantity[]" class="form-control" placeholder="Enter Option Quantity">
				            <div class="input-group-btn"> 
				              <button class="btn btn-danger removeoption" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
				            </div>
				          </div>
				         </div>
		          </div>
		        </div>

                <div class="form-footer">
                    <!-- <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</button> -->
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> SAVE</button>
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
  round_success_noti_record_create();
  </script>
  @endif 
  @if(session()->has('Quantity'))
  <script>
  round_error_noti_quantity();
  </script>
  @endif 
  <script> 
  $('#description_en').summernote({
      height: 400,
      tabsize: 2
  });
    $('#description_ar').summernote({
      height: 400,
      tabsize: 2
  });
  </script>
  <script>
function Getsubcategory($id){ 
	$.ajax({ 
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'../../get-subcategory/'+ $id, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            success:function(result){ 
              var option ='';
              result.forEach(item => {
                option +='<option value='+ item.id +'>'+ item.sub_category_name_en +'</option>';
              });
              $("#sub_category_id_en").html(option);
              console.log(option);
           }
         });
}
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
 <script>
        $(document).ready(function() {
            $('.single-select').select2();
      
            $('.multiple-select').select2();

        //multiselect start

            $('#my_multi_select1').multiSelect();
            $('#my_multi_select2').multiSelect({
                selectableOptgroup: true
            });

            $('#my_multi_select3').multiSelect({
                selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                afterInit: function (ms) {
                    var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                        .on('keydown', function (e) {
                            if (e.which === 40) {
                                that.$selectableUl.focus();
                                return false;
                            }
                        });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                        .on('keydown', function (e) {
                            if (e.which == 40) {
                                that.$selectionUl.focus();
                                return false;
                            }
                        });
                },
                afterSelect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });

         $('.custom-header').multiSelect({
              selectableHeader: "<div class='custom-header'>Selectable items</div>",
              selectionHeader: "<div class='custom-header'>Selection items</div>",
              selectableFooter: "<div class='custom-header'>Selectable footer</div>",
              selectionFooter: "<div class='custom-header'>Selection footer</div>"
            });


          });

    </script>
    <script>
      $('#availabledate').datepicker({
        autoclose: true,
        todayHighlight: true,
        startDate: new Date()
      });
    </script>  
    <script type="text/javascript">
    $(document).ready(function() {
      $(".addpdf").click(function(){ 
          var html = $(".clone2").html();
          $(".increment2").after(html);
      });
      $("body").on("click",".removepdf",function(){ 
          $(this).parents(".control-group").remove();
      });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
      $(".addvideo").click(function(){ 
          var html = $(".clone1").html();
          $(".increment1").after(html);
      });
      $("body").on("click",".removevideo",function(){ 
          $(this).parents(".control-group").remove();
      });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
      var cloneCount =2;
      $(".addoption").click(function(){ 

     var clone=  $('#testingclone')
            .clone()
          .attr('id', 'testingclone-'+ cloneCount)
          .insertAfter($('[id^=testingclone]:last'));
   clone.find('#optionid').prop("id", "option" + cloneCount);
   clone.find('.addoption').text("Remove");
   clone.find('.addoption').prop("class", "btn btn-danger removeoption");
cloneCount++;
      });

      $("body").on("click",".removeoption",function(){ 
          $(this).parents(".control-group").remove();
      });
    });
</script>
 <script>
function Getoption($this){ 
   $id= $($this).val();
    var optionid = $($this).parent(".increment3").find('.optionselectbox').attr('id');
	$.ajax({ 
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'../../get-option/'+ $id, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            success:function(result){ 
              var option ='<option value="">Select Option Values</option>';
              result.forEach(item => {
                option +='<option value='+ item.id +'>'+ item.value +'</option>';
              });
              $("#"+optionid).html(option);
              console.log(option);
           }
         });
}
</script>
 </body>
</html>

      