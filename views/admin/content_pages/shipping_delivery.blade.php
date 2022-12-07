@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Shipping Delivery Edit</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard')}}">Shipping Delivery Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shipping Delivery Edit</li>
         </ol>
     </div>
      <div class="col-sm-3">
       <a href="{{ url('/admin/dashboard')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
               <form id="editshipping" action="{{ route('shipping_delivery')}}" enctype="multipart/form-data" method="post">
              @csrf
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title_en') is-invalid @enderror" maxlength="100" id="title_en" name="title_en" value="<?php if(isset($shipping->title_en)) { echo $shipping->title_en;}?>">
                  </div>
                </div>
                 @error('title_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title_ar') is-invalid @enderror" maxlength="100" id="title_ar" name="title_ar" value="<?php if (isset($shipping->title_ar)) {echo $shipping->title_ar;}?>">
                  </div>
                </div>
                 @error('title_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(English)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description_en') is-invalid @enderror" placeholder=" Description" id='editor1' name="description_en" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($shipping->description_en)) {echo $shipping->description_en;}?></textarea>
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
                    <textarea class="textarea" class="form-control @error('description_ar') is-invalid @enderror" placeholder=" Description" id='editor2' name="description_ar" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($shipping->description_ar)) {echo $shipping->description_ar;}?></textarea>
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
                    <input type="text" class="form-control @error('meta_tag_title_en') is-invalid @enderror" value="<?php if (isset($shipping->meta_tag_title_en)) {echo $shipping->meta_tag_title_en;}?>" maxlength="70" id="meta_tag_title_en" name="meta_tag_title_en" >
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
                    <input type="text" class="form-control @error('meta_tag_title_ar') is-invalid @enderror" maxlength="70" id="meta_tag_title_ar" name="meta_tag_title_ar" value="<?php if (isset($shipping->meta_tag_title_ar)) {echo $shipping->meta_tag_title_ar;}?>">
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
                    <input type="text" class="form-control @error('meta_tag_description_en') is-invalid @enderror" maxlength="250" id="meta_tag_description_en" name="meta_tag_description_en" value="<?php if (isset($shipping->meta_tag_description_en)) {echo $shipping->meta_tag_description_en;}?>">
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
                    <input type="text" class="form-control @error('meta_tag_description_ar') is-invalid @enderror" row='4' maxlength="250" id="meta_tag_description_ar" name="meta_tag_description_ar" value="<?php if (isset($shipping->meta_tag_description_ar)) {echo $shipping->meta_tag_description_ar;}?>">
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
                    <input type="text" class="form-control @error('meta_tag_keyword_en') is-invalid @enderror" maxlength="250" id="meta_tag_keyword_en" name="meta_tag_keyword_en" value="<?php if (isset($shipping->meta_tag_keyword_en)) {echo $shipping->meta_tag_keyword_en;}?>">
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
                    <input type="text" class="form-control @error('meta_tag_keyword_ar') is-invalid @enderror" maxlength="250" id="meta_tag_keyword_ar" name="meta_tag_keyword_ar" value="<?php if (isset($shipping->meta_tag_keyword_ar)) {echo $shipping->meta_tag_keyword_ar;}?>">
                  </div>
                </div>
                 @error('meta_tag_keyword_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-footer">
                    <a href ="{{ url('admin/content')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
                    <button type="submit" id="contentform" class="btn btn-success"><i class="fa fa-check-square-o"></i> SAVE</button>
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
 CKEDITOR.replace('editor1');
 CKEDITOR.replace('editor2');
</script>
 </body>
</html>

      