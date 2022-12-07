@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Customer Notification Send</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/customer')}}">Customer Notification Send</a></li>
            <li class="breadcrumb-item active" aria-current="page">Customer Notification Send</li>
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
              <form id="notification" action="" enctype="multipart/form-data" method="post">
              @csrf
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Personal Info
                </h4>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">User Group</label>
                  <div class="col-sm-10">
                   <select class="form-control multiple-select" multiple="multiple" id="user" name="user_group_id">
                        <option value="">Please Select User Group</option>
                       @foreach($user as $record)
                        <option value="{{$record->id}}">{{$record->email}}</option>
                       @endforeach
                    </select>
                    <input id="chkall" name ="chkall" type="checkbox" >Select All
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Full Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" maxlength="50" id="username" name="username">
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
                    <input type="email" class="form-control" id="email"maxlength="100" name="email">
                  </div>
                @error('email')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                <div class="form-footer">
                    <button type="submit" onclick="checkdata()" class="btn btn-success"><i class="fa fa-check-square-o"></i> SAVE</button>
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
$('.multiple-select').select2();
</script>
<script>
function checkdata()
{
  var select = $('input[name="chkall"]:checked').val();
  var user   = $('#user').val();
  if(typeof select != 'undefined')
  {
      alert();
   $( "#notification" ).submit();
  }
  else if(typeof user != 'null')
  {
   $( "#notification" ).submit();
  }
  else
  {
    alert('Please select User');
  }
  
}
</script>
 </body>
</html>
