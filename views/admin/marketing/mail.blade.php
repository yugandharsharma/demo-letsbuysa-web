@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Mail</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mail</li>
         </ol>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="mail_marketing" action="{{ route('marketing_mail')}}" enctype="multipart/form-data" method="post">
              @csrf

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">From</label>
                  <div class="col-sm-10">
                    <select class="form-control @error('from') is-invalid @enderror" id="from" name="from">
                        <option value="1">LETS BUY</option>
                    </select>
                  </div>
                </div>
                 @error('from')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">To</label>
                  <div class="col-sm-10">
                   <select class="form-control @error('to') is-invalid @enderror" onchange="customers()" id="to" name="to" >
                        <option value="">Please Select Sender Address</option>
                        <option value="1">Customer</option>
                        <option value="2">Groups</option>
                    </select>
                  </div>
                </div> 
                @error('to')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror 

                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label" id="customerlable">Customers</label>
                  <div class="col-sm-10"id="customer">
                  <select class="form-control multiple-select" id="customermultiple" name="customer[]" multiple>
                    @foreach($emails as $record) 
                    <option value="{{$record->email}}">{{$record->email}}
                    </option>
                    @endforeach
                  </select>
                  </div>
                 </div> 

                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Subject</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" value="{{old('subject')}}" maxlength="150" id="subject" name="subject">
                  </div>
                </div>
                 @error('subject')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Message</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('description') is-invalid @enderror" placeholder=" Description" id='editor1' name="description" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                  </div>
                </div>
                 @error('description')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-footer">
                    <button type="submit"  class="btn btn-success"><i class="fa fa-check-square-o"></i> SEND EMAIL</button>
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
  round_success_noti_email_send();
  </script>
  @endif 
   <script>
        $(document).ready(function() {
            $('.multiple-select').select2();
            customers();

          });

    </script>
    <script> 
  $('#editor1').summernote({
      height: 400,
      tabsize: 2
  });
  </script>
<script>
function customers()
{
  if($('#to').val()=='1')
  {
   $('#customer').css('display','block');
   $('#customerlable').css('display','block');
   $('#customermultiple').attr('required', 'required');
  }
  else
  {
    $('#customer').css('display','none');
    $('#customerlable').css('display', 'none');
    $('#customermultiple').removeAttr('required');
  }

}
</script>
<script>
$('#mail_marketing').submit(function() {
    if($(this).valid()) 
    {
      $('#pageloader-overlay').css('display','block');
    }
});
</script>
 </body>
</html>

