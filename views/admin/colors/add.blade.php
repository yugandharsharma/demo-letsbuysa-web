@include('includes-file.header')
@include('includes-file.sidebar')
<style>
.colorpicker-alpha{display:none !important; }
</style>
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Color Add</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('admin/color')}}">Color Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Color Add</li>
         </ol>
     </div>
     <div class="col-sm-3">
       <a href="{{url('admin/color')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="coloradd" action="{{ route('color_add')}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Color Info
                </h4>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Color Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" maxlength="150" id="name" name="name" required>
                  </div>
                </div>
                 @error('name')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                  <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Color Picker</label>
                  <div class="col-sm-10">
                  <div id="cp2" class="input-group colorpicker colorpicker-component"> 
                      <input type="text" value="#00AABB" name="colorcode" class="form-control" /> 
                      <span class="input-group-addon"><i></i></span> 
                    </div>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Status</label>
                  <div class="col-sm-10">
                   <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="1">Enabled</option>
                        <option value="2">Disabled</option>
                    </select>
                  </div>
                </div>

                <div class="form-footer">
                    <a href ="{{ url('admin/color')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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
  $('.colorpicker').colorpicker({});
</script>
 </body>
</html>

      