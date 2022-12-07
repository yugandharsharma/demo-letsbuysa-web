@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
  
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Email Template Edit</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/emailtemplate')}}">Email Template Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Email Template Edit</li>
         </ol>
     </div>
      <div class="col-sm-3">
       <a href="{{url('admin/emailtemplate')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
               <form id="editemailtemplate" action="{{ route('emailtemplateedit',$emailtemplate->id)}}" enctype="multipart/form-data" method="post">
              @csrf
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Title(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title_en') is-invalid @enderror" maxlength="100" id="title_en" name="title_en" value="{{$emailtemplate->title_en}}">
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
                    <input type="text" class="form-control @error('title_ar') is-invalid @enderror" maxlength="100" id="title_ar" name="title_ar" value="{{$emailtemplate->title_ar}}">
                  </div>
                </div>
                 @error('title_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Subject(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('subject_en') is-invalid @enderror" maxlength="100" id="subject_en" name="subject_en" value="{{$emailtemplate->subject_en}}">
                  </div>
                </div>
                 @error('subject_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Subject(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('subject_ar') is-invalid @enderror" maxlength="100" id="subject_ar" name="subject_ar" value="{{$emailtemplate->subject_ar}}">
                  </div>
                </div>
                 @error('subject_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(English)</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description_en') is-invalid @enderror" placeholder=" Description" id='editor1' name="description_en" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;">{{$emailtemplate->description_en}}</textarea>
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
                    <textarea class="textarea" class="form-control @error('description_ar') is-invalid @enderror" placeholder=" Description" id='editor2' name="description_ar" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;">{{$emailtemplate->description_ar}}</textarea>
                  </div>
                </div>
                 @error('description_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-footer">
                    <a href ="{{ url('admin/emailtemplate')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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

      