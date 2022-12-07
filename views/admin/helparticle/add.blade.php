@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Help Article content Add</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/help_article')}}">Help Article content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Help Article content Add</li>
         </ol>
     </div>
       <div class="col-sm-3">
       <a href="{{url('admin/help_article')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="addhelparticle" action="{{ route('help_article_add')}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   General
                </h4>
                <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Help Category Name<span style="color: red; font-size: 14px;">*</span></label>
                      <div class="col-sm-10">
                        <select class="form-control @error('category_id') is-invalid @enderror" onclick="Getsubcategory(this.value);" name="category_id" required="">
                        <option value="">Select category Name</option>
                        @foreach($category as $record)
                        <option value="{{ $record->id }}" {{ old('category_id') == $record->id ? "selected" : "" }}>{{ $record->name_en}}</option> 
                        @endforeach
                      </select>
                    </div>
                 @error('category_id')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                    <label for="input-1" class="col-sm-2 col-form-label">Help Sub Category Name<span style="color: red; font-size: 14px;">*</span></label>
                      <div class="col-sm-10">
                        <select class="form-control @error('subcategory_id') is-invalid @enderror" name="subcategory_id" id="subcategory_id" required="">
                      </select>
                    </div>
                 @error('subcategory_id')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Help Article Name(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title_en') is-invalid @enderror" value="{{old('title_en')}}" maxlength="50" id="title_en" name="title_en" >
                  </div>
                </div>
                 @error('title_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Help Article Name(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title_ar') is-invalid @enderror" value="{{old('title_ar')}}" maxlength="50" id="title_ar" name="title_ar" >
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
               

                
                <div class="form-footer">
                    <a href ="{{ url('admin/help_article')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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
    <script>
function Getsubcategory($id){ 
	$.ajax({ 
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'../../get-helpsubcategory/'+ $id, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            success:function(result){ 
              var option ='';
              result.forEach(item => {
                option +='<option value='+ item.id +'>'+ item.name_en +'</option>';
              });
              $("#subcategory_id").html(option);
              console.log(option);
           }
         });
}
</script>
 </body>
</html>

      