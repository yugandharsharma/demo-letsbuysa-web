@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Category content Edit</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/category')}}">Category content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Category content Edit</li>
         </ol>
     </div>
       <div class="col-sm-3">
       <a href="{{url('admin/category')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                   <form id="addcategory" action="{{ route('edit_category',$category->id)}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   General
                </h4>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Category Name(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('category_name_en') is-invalid @enderror" maxlength="50" id="category_name_en" name="category_name_en" value="{{$category->category_name_en}}">
                  </div>
                </div>
                 @error('category_name_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Category Name(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('category_name_ar') is-invalid @enderror" maxlength="50" id="category_name_ar" name="category_name_ar" value="{{$category->category_name_ar}}">
                  </div>
                </div>
                 @error('category_name_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(English)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description_en') is-invalid @enderror" placeholder=" Description" id='editor1' name="description_en" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;">{{$category->description_en}}</textarea>
                  </div>
                </div>
                 @error('description_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(Arabic)</label>
                  <div class="col-sm-10">
                     <textarea class="textarea" class="form-control @error('description_ar') is-invalid @enderror" placeholder=" Description" id='editor2' name="description_ar" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;">{{$category->description_ar}}</textarea>
                  </div>
                </div>
                 @error('description_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Title(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_title_en') is-invalid @enderror" maxlength="70" id="meta_tag_title_en" name="meta_tag_title_en" value="{{$category->meta_tag_title_en}}">
                  </div>
                </div>
                 @error('meta_tag_title_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Title(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_title_ar') is-invalid @enderror" maxlength="70" id="meta_tag_title_ar" name="meta_tag_title_ar" value="{{$category->meta_tag_title_ar}}">
                  </div>
                </div>
                 @error('meta_tag_title_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Description(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_description_en') is-invalid @enderror" maxlength="250" id="meta_tag_description_en" name="meta_tag_description_en" value="{{$category->meta_tag_description_en}}">
                  </div>
                </div>
                 @error('meta_tag_description_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Description(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_description_ar') is-invalid @enderror" row='4' maxlength="250" id="meta_tag_description_ar" name="meta_tag_description_ar" value="{{$category->meta_tag_description_ar}}">
                  </div>
                </div>
                 @error('meta_tag_description_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag keywords(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_keyword_en') is-invalid @enderror" maxlength="250" id="meta_tag_keyword_en" name="meta_tag_keyword_en" value="{{$category->meta_tag_keyword_en}}">
                  </div>
                </div>
                 @error('meta_tag_keyword_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag keywords(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_keyword_ar') is-invalid @enderror" maxlength="250" id="meta_tag_keyword_ar" name="meta_tag_keyword_ar" value="{{$category->meta_tag_keyword_ar}}">
                  </div>
                </div>
                 @error('meta_tag_keyword_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image:(English)</label>
                  <div class="col-sm-10">
                    <input type="file" name="image_en" class="form-control @error('image_en') is-invalid @enderror" value="{{old('image_en')}}">
                     <img src="{{ url('/') }}/public/images/category/{{$category->image_en}}"  alt="User Image" width="150">
                  </div>
                @error('image_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image:(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="file" name="image_ar" class="form-control @error('image_ar') is-invalid @enderror" value="{{old('image_ar')}}">
                     <img src="{{ url('/') }}/public/images/category/{{$category->image_ar}}"  alt="User Image" width="150">
                  </div>
                @error('image_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Header Image:(English)</label>
                  <div class="col-sm-10">
                    <input type="file" name="header_image_en" class="form-control @error('header_image_en') is-invalid @enderror" value="{{old('header_image_en')}}">
                     <img src="{{ url('/') }}/public/images/category/{{$category->header_image_en}}"  alt="User Image" width="150">
                  </div>
                @error('header_image_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Header Image:(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="file" name="header_image_ar" class="form-control @error('header_image_ar') is-invalid @enderror" value="{{old('header_image_ar')}}">
                     <img src="{{ url('/') }}/public/images/category/{{$category->header_image_ar}}"  alt="User Image" width="150">
                  </div>
                @error('header_image_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>


                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Footer Image:(English)</label>
                  <div class="col-sm-10">
                    <input type="file" name="footer_image_en" class="form-control @error('footer_image_en') is-invalid @enderror" value="{{old('footer_image_en')}}">
                     <img src="{{ url('/') }}/public/images/category/{{$category->footer_image_en}}"  alt="User Image" width="150">
                  </div>
                @error('footer_image_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Footer Image:(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="file" name="footer_image_ar" class="form-control @error('footer_image_ar') is-invalid @enderror" value="{{old('footer_image_ar')}}">
                     <img src="{{ url('/') }}/public/images/category/{{$category->footer_image_ar}}"  alt="User Image" width="150">
                  </div>
                @error('footer_image_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                
                <div class="form-footer">
                    <a href ="{{ url('admin/category')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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
 </body>
</html>

      