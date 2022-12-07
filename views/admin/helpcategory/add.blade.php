@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Help Category content Add</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/help_category')}}">Help Category content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Help Category content Add</li>
         </ol>
     </div>
       <div class="col-sm-3">
       <a href="{{url('admin/help_category')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="addhelpcategory" action="{{ route('help_category_add')}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   General
                </h4>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Help Category Name(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" value="{{old('name_en')}}" maxlength="50" id="name_en" name="name_en" >
                  </div>
                </div>
                 @error('name_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Help Category Name(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" value="{{old('name_ar')}}" maxlength="50" id="name_ar" name="name_ar" >
                  </div>
                </div>
                 @error('name_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(English)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description_en') is-invalid @enderror" placeholder=" Description" id='editor1' name="description_en" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"value="{{old('description_en')}}"></textarea>
                  </div>
                </div>
                 @error('description_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror<div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(Arabic)</label>
                  <div class="col-sm-10">
                     <textarea class="textarea" class="form-control @error('description_ar') is-invalid @enderror" placeholder=" Description" id='editor2' name="description_ar" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"value="{{old('description_ar')}}"></textarea>
                  </div>
                </div>
                 @error('description_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
               

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image:(English)</label>
                  <div class="col-sm-10">
                    <input type="file" name="image_en" class="form-control @error('image_en') is-invalid @enderror" value="{{old('image_en')}}">
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
                  </div>
                @error('image_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                
                <div class="form-footer">
                    <a href ="{{ url('admin/help_category')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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
  <script> 
 CKEDITOR.replace('editor1');
 CKEDITOR.replace('editor2');
  </script>
 </body>
</html>

      