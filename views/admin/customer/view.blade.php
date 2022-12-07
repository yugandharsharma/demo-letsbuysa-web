@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Customer content View</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/customer')}}">Customer content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Customer content View</li>
         </ol>
     </div>
      <div class="col-sm-3">
       <a href="{{url('admin/customer')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Personal Info
                </h4>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Full Name</label>
                  <div class="col-sm-10">
                    <p>{{$customer->username}}</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">E-mail</label>
                  <div class="col-sm-10">
                    <p>{{$customer->email}}</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Mobile Number</label>
                  <div class="col-sm-10">
                    <p>{{$customer->mobile}}</p>
                  </div>
                </div>

                 <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Status</label>
                  <div class="col-sm-10">
                   @if($customer->status==1) Enabled @else Disabled @endif
                  </div>
                </div>
                 <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Approved</label>
                  <div class="col-sm-10">
                  @if($customer->approved==1) No @else Yes @endif
                  </div>
                </div>

                  <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Address Info
                </h4>

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Address Label</label>
                  <div class="col-sm-10">
                    <p>@if(isset($address->fulladdress)){{$address->fulladdress}}@endif</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">Full Name</label>
                  <div class="col-sm-10">
                    <p>@if(isset($address->fullname)){{$address->fullname}}@endif</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Mobile Number</label>
                  <div class="col-sm-10">
                    <p>@if(isset($address->mobile)){{$address->mobile}}@endif</p>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Address Details</label>
                  <div class="col-sm-10">
                    <p>@if(isset($address->address_details)){{$address->address_details}}@endif</p>
                  </div>
                </div>
                <!-- <div class="form-group row">
                  <label for="city" class="col-sm-2 col-form-label">City</label>
                  <div class="col-sm-10">
                    <p>@if(isset($address->city)){{$address->city}}@endif</p>
                  </div>
                </div>
                 <div class="form-group row">
                  <label for="postcode" class="col-sm-2 col-form-label">Post/Zip Code</label>
                  <div class="col-sm-10">
                    <p>@if(isset($address->postcode)){{$address->postcode}}@endif</p>
                  </div>
                </div>
                 <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Country</label>
                  <div class="col-sm-10">
                    <p>{{$country}}</p>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="state" class="col-sm-2 col-form-label">State</label>
                  <div class="col-sm-10">
                  	 <p>{{$zone}}</p>
                  </div>
                </div>  -->
            </div>
          </div>
        </div>
      </div>
  </div>
  </div>

@include('includes-file.footer')
 </body>
</html>
