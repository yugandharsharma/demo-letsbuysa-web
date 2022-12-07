@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Top-Brands content Edit</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('admin/top_brands')}}">Top-Brands content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Top-Brands content Edit</li>
         </ol>
     </div>
     <div class="col-sm-3">
       <a href="{{url('admin/top_brands')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="editofferbanner" action="{{ route('edit_topbrands',$banner->id)}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Top-Brands Info
                </h4>
                <!--<div class="form-group row">-->
                <!--  <label for="input-1" class="col-sm-2 col-form-label">Banner Name(English)</label>-->
                <!--  <div class="col-sm-10">-->
                <!--    <input type="text" class="form-control @error('name_en') is-invalid @enderror" maxlength="150" id="name_en" name="name_en" value="{{$banner->name_en}}">-->
                <!--  </div>-->
                <!--</div>-->
                <!-- @error('name_en')-->
                <!--<span class="invalid-feedback" style="color: red; display:block; padding-left:260px">-->
                <!--<strong>{{ $message }}</strong>-->
                <!--</span>-->
                <!--@enderror-->
                <!--<div class="form-group row">-->
                <!--  <label for="input-1" class="col-sm-2 col-form-label">Banner Name(Arabic)</label>-->
                <!--  <div class="col-sm-10">-->
                <!--    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" maxlength="150" id="name_ar" name="name_ar" value="{{$banner->name_ar}}">-->
                <!--  </div>-->
                <!--</div>-->
                <!--@error('name_ar')-->
                <!--<span class="invalid-feedback" style="color: red; display:block; padding-left:260px">-->
                <!--<strong>{{ $message }}</strong>-->
                <!--</span>-->
                <!--@enderror-->
               
                @error('status')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                 <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Image(English)</label>
                  <div class="col-sm-10">
                    <input type="file" class="form-control @error('image_en') is-invalid @enderror" maxlength="150" id="image_en" name="image_en" accept="image/*">
                    <img src="{{ url('/') }}/public/images/top-brands/{{$banner->image_en}}"  alt="User Image" width="150">
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
                    <img src="{{ url('/') }}/public/images/top-brands/{{$banner->image_ar}}"  alt="User Image" width="150">
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
                        <option value="1"<?php if ($banner->status=='1') {echo 'selected';}?>>Enabled</option>
                        <option value="2"<?php if ($banner->status=='2') {echo 'selected';}?>>Disabled</option>
                    </select>
                  </div>
                </div>
              
                <div class="form-footer">
                    <a href ="{{ url('admin/top_brands')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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
