@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Filter Add</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('admin/filter')}}">Filter Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Filter Add</li>
         </ol>
     </div>
     <div class="col-sm-3">
       <a href="{{url('admin/filter')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="filteradd" action="{{ route('filter_add')}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-heder text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Filter Info
                </h4>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Filter Name (English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" maxlength="150" id="name_en" name="name_en" required>
                  </div>
                </div>
                 @error('name_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Filter Name (Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" maxlength="150" id="name_ar" name="name_ar" required>
                  </div>
                </div>
                 @error('name_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror


                <div class="form-group row">
              <label for="input-1" class="col-sm-2 col-form-label" style="padding-left: 12px;">Filter Values</label>
                  <div class="col-sm-8">
             <div class="input-group control-group increment2" >
             <input type="text" name="filter_name_en[]" class="form-control" placeholder="Enter Filter Name English">
             <input type="text" name="filter_name_ar[]" class="form-control" placeholder="Enter Filter Name Arabic">

             <div class="input-group-btn"> 
             <button class="btn btn-success addpdf" type="button"><i class="glyphicon glyphicon-plus"></i>    Add</button>
             </div>
             </div>
             <div class="clone2 hide" style="display:none;">
              <div class="control-group input-group" style="margin-top:10px">
               <input type="text" name="filter_name_en[]" class="form-control" placeholder="Enter Filter Name English">
               <input type="text" name="filter_name_ar[]" class="form-control" placeholder="Enter Filter Name Arabic">
                <div class="input-group-btn"> 
                  <button class="btn btn-danger removepdf" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                </div>
              </div>
             </div>
             </div>
             </div>

                <div class="form-footer">
                    <a href ="{{ url('admin/filter')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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
 </body>
</html>

      