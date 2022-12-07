@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Order Status Edit</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('admin/order_status')}}">Order Status Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Status Edit</li>
         </ol>
     </div>
     <div class="col-sm-3">
       <a href="{{url('admin/order_status')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="addorderstatus" action="{{ route('order_status_edit',$orderstatus->id)}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Order Status Info
                </h4>
                
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Order Status Name(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('status_name_en') is-invalid @enderror" value="{{$orderstatus->status_name_en}}" maxlength="50" id="status_name_en" name="status_name_en" >
                  </div>
                </div>
                 @error('status_name_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Order Status Name(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('status_name_ar') is-invalid @enderror" value="{{$orderstatus->status_name_ar}}" maxlength="50" id="status_name_ar" name="status_name_ar" >
                  </div>
                </div>
                 @error('status_name_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-footer">
                    <a href ="{{ url('admin/order_status')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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
 </body>
</html>

      