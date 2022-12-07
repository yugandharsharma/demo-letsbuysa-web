@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Customer content Add</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/customer')}}">Customer content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Customer content Add</li>
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
              <form id="addcustomer" action="{{ route('add_customer')}}" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Personal Info
                </h4>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">User Group</label>
                  <div class="col-sm-10">
                   <select class="form-control @error('user_group_id') is-invalid @enderror" id="user_group_id" name="user_group_id">
                        <option value="">Please Select User Group</option>
                       @foreach($user_group as $group)
                        <option value="{{$group->id}}">{{$group->title_en}}</option>
                       @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Full Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" maxlength="50" id="username" name="username" value="{{old('username')}}">
                  </div>
                  @error('username')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">E-mail</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" name="email" maxlength="100" value="{{old('email')}}">
                  </div>
                  @error('email')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Mobile Number</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" maxlength="15" id="telephone" name="telephone" value="{{old('telephone')}}">
                  </div>
                  @error('telephone')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="password" name="password" maxlength="50" value="{{old('password')}}">
                  </div>
                  @error('password')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Confirm Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="confirm" name="confirm" maxlength="50" value="{{old('confirm')}}">
                  </div>
                  @error('confirm')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
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
                 <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Approved</label>
                  <div class="col-sm-10">
                   <select class="form-control @error('approved') is-invalid @enderror" id="approved" name="approved">
                      <option value="0">No</option>
                      <option value="1">Yes</option>
                    </select>
                  </div>
                </div>

                  <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Address Info
                </h4>

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Address Label</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" maxlength="200" id="address_label" name="address_label" value="{{old('address_label')}}">
                  </div>
                  @error('address_label')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                 <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">Full Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" maxlength="50" id="fullname" name="fullname" value="{{old('fullname')}}">
                  </div>
                  @error('fullname')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Mobile Number</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" maxlength="15" id="telephone1" name="telephone1" value="{{old('telephone1')}}" >
                  </div>
                  @error('telephone1')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="addressdetail" class="col-sm-2 col-form-label">Address Details</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="address_details" name="address_details"maxlength="50" >
                  </div>
                @error('address_details')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <!-- <div class="form-group row">
                  <label for="city" class="col-sm-2 col-form-label">City</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" maxlength="50" id="city" name="city" value="{{old('city')}}">
                  </div>
                  @error('city')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div> -->
                 <!-- <div class="form-group row">
                  <label for="postcode" class="col-sm-2 col-form-label">Post/Zip Code</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" maxlength="50" id="postcode" name="postcode" value="{{old('postcode')}}">
                  </div>
                  @error('postcode')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div> -->
                 <!-- <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Country</label>
                  <div class="col-sm-10">
                  <select class="form-control" onclick="GetState(this.value);" name="country_id" required="">
									<option value="">Select Country</option>
									@foreach($country as $record)
									<option value="{{$record->country_id}}">{{$record->name}}</option>
									@endforeach
								</select>
                  </div>
                </div>  -->

                <!-- <div class="form-group row">
                  <label for="state" class="col-sm-2 col-form-label">State</label>
                  <div class="col-sm-10">
                  	<select class="form-control" id="state_id" name="state_id" required="">
									<option value="">Select State</option>
								</select>
                  </div>
                </div>  -->

                <div class="form-footer">
                    <a href ="{{ url('admin/customer')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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

<script>
function GetState($id){
	$.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'../../get-state/'+ $id, //the page containing php script
            type: "post", //request type,
            dataType: 'json',
            success:function(result){
              var option ='<option value="">Select State</option>';
              result.forEach(item => {
                option +='<option value='+ item.zone_id +'>'+ item.name +'</option>';
              });
              $("#state_id").html(option);
              console.log(option);
           }
         });
}
</script>
  @if(session()->has('success'))
  <script>
  round_success_noti_customer_create();
  </script>
  @endif
 </body>
</html>

