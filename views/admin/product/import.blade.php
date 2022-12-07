@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Product Import</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.index')}}">Product Content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Product Content Import</li>
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
              <form id="ImportProduct"  action="{{ Route('product_import')}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Personal Info
                </h4>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Excel Sheet:(Required)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                <input type="file" name="file"class="form-control @error('file') is-invalid @enderror" id="file" required="">
                </div>
                @error('file')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-footer">
                    <!-- <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</button> -->
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> Import</button>
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
  round_success_noti_product_import();
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
<script >
 function isNumberKey(evt)
  {
     var charCode = (evt.which) ? evt.which : event.keyCode
     if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
     return true;
  }
</script>
 </body>
</html>

      