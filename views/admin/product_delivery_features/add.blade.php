@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Product Delivery Features Add</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('admin/product_delivery_features')}}">Product Delivery Features Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Product Delivery Features Add</li>
         </ol>
     </div>
     <div class="col-sm-3">
       <a href="{{url('admin/product_delivery_features')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="productdeliveryfeatureadd" action="{{ route('product_delivery_add')}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Product Delivery Features Info
                </h4>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Title(English)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title_en') is-invalid @enderror" maxlength="150" id="title_en" name="title_en">
                  </div>
                </div>
                 @error('title_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Title(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('title_ar') is-invalid @enderror" maxlength="150" id="title_ar" name="title_ar">
                  </div>
                </div>
                 @error('title_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Image(English)</label>
                  <div class="col-sm-10">
                    <input type="file" class="form-control @error('image_en') is-invalid @enderror" maxlength="150" id="image_en" name="image_en" accept="image/*">
                  </div>
                </div>
                 @error('image_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                   <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Image(Arabic)</label>
                  <div class="col-sm-10">
                    <input type="file" class="form-control @error('image_ar') is-invalid @enderror" maxlength="150" id="image_ar" name="image_ar" accept="image/*">
                  </div>
                </div>
                 @error('image_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
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
                    <a href ="{{ url('admin/product_delivery_features')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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
 </body>
</html>

      