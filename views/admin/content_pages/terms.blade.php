@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Terms & Conditions Edit</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard')}}">Terms & Conditions Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Terms & Conditions Edit</li>
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
               <form id="editterms" action="{{ route('editterms')}}" enctype="multipart/form-data" method="post">
              @csrf
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title_en') is-invalid @enderror" maxlength="100" id="title_en" name="title_en" value="<?php if(isset($terms->title_en)) { echo $terms->title_en;}?>">
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
                    <input type="text" class="form-control @error('title_ar') is-invalid @enderror" maxlength="100" id="title_ar" name="title_ar" value="<?php if (isset($terms->title_ar)) {echo $terms->title_ar;}?>">
                  </div>
                </div>
                 @error('title_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title1(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title1_en') is-invalid @enderror" maxlength="100" id="title1_en" name="title1_en" value="<?php if(isset($terms->title1_en)) { echo $terms->title1_en;}?>">
                  </div>
                </div>
                 @error('title1_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title1(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title1_ar') is-invalid @enderror" maxlength="100" id="title1_ar" name="title1_ar" value="<?php if (isset($terms->title1_ar)) {echo $terms->title1_ar;}?>">
                  </div>
                </div>
                 @error('title1_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description1(English)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description1_en') is-invalid @enderror" placeholder=" Description" id='editor1' name="description1_en" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($terms->description1_en)) {echo $terms->description1_en;}?></textarea>
                  </div>
                </div>
                 @error('description1_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description1(Arabic)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description1_ar') is-invalid @enderror" placeholder=" Description" id='editor2' name="description1_ar" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($terms->description1_ar)) {echo $terms->description1_ar;}?></textarea>
                  </div>
                </div>
                 @error('description1_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title2(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title2_en') is-invalid @enderror" maxlength="100" id="title2_en" name="title2_en" value="<?php if(isset($terms->title2_en)) { echo $terms->title2_en;}?>">
                  </div>
                </div>
                 @error('title2_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title2(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title2_ar') is-invalid @enderror" maxlength="100" id="title2_ar" name="title2_ar" value="<?php if (isset($terms->title2_ar)) {echo $terms->title2_ar;}?>">
                  </div>
                </div>
                 @error('title2_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description2(English)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description2_en') is-invalid @enderror" placeholder=" Description" id='editor3' name="description2_en" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($terms->description2_en)) {echo $terms->description2_en;}?></textarea>
                  </div>
                </div>
                 @error('description2_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description2(Arabic)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description2_ar') is-invalid @enderror" placeholder=" Description" id='editor4' name="description2_ar" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($terms->description2_ar)) {echo $terms->description2_ar;}?></textarea>
                  </div>
                </div>
                 @error('description2_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title3(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title3_en') is-invalid @enderror" maxlength="100" id="title3_en" name="title3_en" value="<?php if(isset($terms->title3_en)) { echo $terms->title3_en;}?>">
                  </div>
                </div>
                 @error('title3_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title3(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title3_ar') is-invalid @enderror" maxlength="100" id="title3_ar" name="title3_ar" value="<?php if (isset($terms->title3_ar)) {echo $terms->title3_ar;}?>">
                  </div>
                </div>
                 @error('title3_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description3(English)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description3_en') is-invalid @enderror" placeholder=" Description" id='editor5' name="description3_en" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($terms->description3_en)) {echo $terms->description3_en;}?></textarea>
                  </div>
                </div>
                 @error('description3_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description3(Arabic)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description3_ar') is-invalid @enderror" placeholder=" Description" id='editor6' name="description3_ar" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($terms->description3_ar)) {echo $terms->description3_ar;}?></textarea>
                  </div>
                </div>
                 @error('description3_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title4(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title4_en') is-invalid @enderror" maxlength="100" id="title4_en" name="title4_en" value="<?php if(isset($terms->title4_en)) { echo $terms->title4_en;}?>">
                  </div>
                </div>
                 @error('title4_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title4(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title4_ar') is-invalid @enderror" maxlength="100" id="title4_ar" name="title4_ar" value="<?php if (isset($terms->title4_ar)) {echo $terms->title4_ar;}?>">
                  </div>
                </div>
                 @error('title4_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description4(English)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description4_en') is-invalid @enderror" placeholder=" Description" id='editor7' name="description4_en" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($terms->description4_en)) {echo $terms->description4_en;}?></textarea>
                  </div>
                </div>
                 @error('description4_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description4(Arabic)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description4_ar') is-invalid @enderror" placeholder=" Description" id='editor8' name="description4_ar" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($terms->description4_ar)) {echo $terms->description4_ar;}?></textarea>
                  </div>
                </div>
                 @error('description4_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title5(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title5_en') is-invalid @enderror" maxlength="100" id="title5_en" name="title5_en" value="<?php if(isset($terms->title5_en)) { echo $terms->title5_en;}?>">
                  </div>
                </div>
                 @error('title5_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title5(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title5_ar') is-invalid @enderror" maxlength="100" id="title5_ar" name="title5_ar" value="<?php if (isset($terms->title5_ar)) {echo $terms->title5_ar;}?>">
                  </div>
                </div>
                 @error('title5_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description5(English)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description5_en') is-invalid @enderror" placeholder=" Description" id='editor9' name="description5_en" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($terms->description5_en)) {echo $terms->description5_en;}?></textarea>
                  </div>
                </div>
                 @error('description5_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description5(Arabic)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description5_ar') is-invalid @enderror" placeholder=" Description" id='editor10' name="description5_ar" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($terms->description5_ar)) {echo $terms->description5_ar;}?></textarea>
                  </div>
                </div>
                 @error('description5_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror


                
                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Meta Tag Title(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('meta_tag_title_en') is-invalid @enderror" value="<?php if (isset($terms->meta_tag_title_en)) {echo $terms->meta_tag_title_en;}?>" maxlength="70" id="meta_tag_title_en" name="meta_tag_title_en" >
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
                    <input type="text" class="form-control @error('meta_tag_title_ar') is-invalid @enderror" maxlength="70" id="meta_tag_title_ar" name="meta_tag_title_ar" value="<?php if (isset($terms->meta_tag_title_ar)) {echo $terms->meta_tag_title_ar;}?>">
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
                    <input type="text" class="form-control @error('meta_tag_description_en') is-invalid @enderror" maxlength="250" id="meta_tag_description_en" name="meta_tag_description_en" value="<?php if (isset($terms->meta_tag_description_en)) {echo $terms->meta_tag_description_en;}?>">
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
                    <input type="text" class="form-control @error('meta_tag_description_ar') is-invalid @enderror" row='4' maxlength="250" id="meta_tag_description_ar" name="meta_tag_description_ar" value="<?php if (isset($terms->meta_tag_description_ar)) {echo $terms->meta_tag_description_ar;}?>">
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
                    <input type="text" class="form-control @error('meta_tag_keyword_en') is-invalid @enderror" maxlength="250" id="meta_tag_keyword_en" name="meta_tag_keyword_en" value="<?php if (isset($terms->meta_tag_keyword_en)) {echo $terms->meta_tag_keyword_en;}?>">
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
                    <input type="text" class="form-control @error('meta_tag_keyword_ar') is-invalid @enderror" maxlength="250" id="meta_tag_keyword_ar" name="meta_tag_keyword_ar" value="<?php if (isset($terms->meta_tag_keyword_ar)) {echo $terms->meta_tag_keyword_ar;}?>">
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
 CKEDITOR.replace('editor3');
 CKEDITOR.replace('editor4');
 CKEDITOR.replace('editor5');
 CKEDITOR.replace('editor6');
 CKEDITOR.replace('editor7');
 CKEDITOR.replace('editor8');
 CKEDITOR.replace('editor9');
 CKEDITOR.replace('editor10');
</script>
 </body>
</html>

      