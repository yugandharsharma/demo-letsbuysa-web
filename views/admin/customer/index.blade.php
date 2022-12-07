@include('includes-file.header')
@include('includes-file.sidebar')
<style>
.paging_simple_numbers ul{display:none !important;}
</style>
  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Customer content Tables</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Customer content List</li>
         </ol>
     </div>
     <div class="col-sm-3">

     </div>
     </div>

        <div class="row">
        <div class="col-lg-12">
        <form method="post" id="filterdata" action="{{route('customer_filter')}}">
            @csrf
                <div class="form-group row">
                  <div class="col-sm-3">
                    <p style="color: black;">Start Date</p>
                    <input type="text" maxlength="50" value="@if(!empty($start_date)){{$start_date}}@endif" placeholder="Select Start Date" class="form-control" id="start_date" name="start_date">
                  </div>
                  <div class="col-sm-3">
                    <p style="color: black;">End Date</p>
                    <input type="text" maxlength="50" value="@if(!empty($end_date)){{$end_date}}@endif" placeholder="Select End Date" class="form-control" id="end_date" name="end_date">
                  </div>
                  <div class="col-sm-3">
                    <p style="color: black;">Customer Name</p>
                    <input type="text" maxlength="50" value="@if(!empty($val)){{$val}}@endif" placeholder="Enter Name" class="form-control" id="name" name="name">
                  </div>
                   <div class="col-sm-3">
                    <p style="color: black;">Email</p>
                    <input type="text" maxlength="50" value="@if(!empty($email)){{$email}}@endif" placeholder="Enter Email" class="form-control" id="email" name="email">
                  </div>
                  </div>
                 <div class="form-group row">
                 <div class="col-sm-3">
                     <p style="color: black;">Status</p>
                    	<select class="form-control"  id="status" name="status">
                        <option value="1"<?php if (!empty($status) && $status=='1') {echo 'selected';}?>>Enabled</option>
                        <option value="2"<?php if (!empty($status) && $status=='2') {echo 'selected';}?>>Disabled</option>
                    </select>
                  </div>

                  <div class="col-sm-3">
                     <p style="color: black;">Approved</p>
                    	<select class="form-control" id="approved" name="approved">
                        <option value="0"<?php if (!empty($approved) && $approved=='0') {echo 'selected';}?>>No</option>
                        <option value="1"<?php if (!empty($approved) && $approved=='1') {echo 'selected';}?>>Yes</option>
                    </select>
                  </div>

                  <div class="col-sm-3">
                  </br>
                  <button type="button" onclick="CheckDate();" class="btn btn-info btn-lg btn-round waves-effect waves-light m-1">Search</button>
                  &nbsp;&nbsp;
                  <a href="{{url('/admin/customer')}}">
                  <button type="button" class="btn btn-info btn-lg btn-round waves-effect waves-light m-1">Reset</button>
                  </a>
                  </div>

                  <div class="col-sm-3">
                    </br>
                   <a href="{{url('admin/customer/add')}}">
                 <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Add New</button>
                  </a>
                  </div>

            </div>
          </form>
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Customer List</div>
            <div class="card-body">
              <div class="table-responsive">
             <table id="example" class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @if($customer)
                    @php
                     $i=1;
                     @endphp
                    @foreach($customer as $record)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$record->name}}</td>
                        <td>{{$record->email}}</td>
                        <td>@if($record->status == 1)Enabled @else Disabled @endif</td>
                        <td>{{$record->created_at}}</td>
                        <td>
                            @if(auth()->user()->role === 1)
                          <a href="{{ url('/')}}/admin/customer/delete/{{$record->id}}">
                          <button onclick="return confirm('are you want to delete this record');" type="button" class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button></a>
                          <a href="{{ url('/')}}/admin/customer/view/{{$record->id}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-eye"></i> </button></a>
                          <a href="{{ url('/')}}/admin/customer/edit/{{$record->id}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button></a>
                            @endif
                                @php $permission = permission(); @endphp

                                    @if(auth()->user()->role === 3 && isset($permission['customer']) && $permission['customer']['delete'] === 1)
                                        <a href="{{ url('/')}}/admin/customer/delete/{{$record->id}}">
                                            <button onclick="return confirm('are you want to delete this record');" type="button" class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button></a>
                                    @endif
                                    @if(auth()->user()->role === 3 && isset($permission['customer']) && $permission['customer']['edit'] === 1)
                                            <a href="{{ url('/')}}/admin/customer/edit/{{$record->id}}">
                                                <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button></a>
                                    @endif
                                        @if(auth()->user()->role === 3 && isset($permission['customer']) && $permission['customer']['add'] === 1)
                                            <a href="{{ url('/')}}/admin/customer/view/{{$record->id}}">
                                                <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-eye"></i> </button></a>
                                        @endif


                        </td>
                    </tr>
                     @php
                    $i++;
                    @endphp
                   @endforeach
                 @endif
                </tbody>
            </table>
            {{ $customer->appends(request()->except('page'))->links() }}
            </div>
            </div>
          </div>
        </div>
      </div>
  </div>
            </div>

@include('includes-file.footer')
  	<script>
     $(document).ready(function() {
      //Default data table
       $('#default-datatable').DataTable();


       var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ]
      } );

     table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );

      } );
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
      $('#list').DataTable({"order": [[ 5, "desc" ]]});
    });
    </script>
    <script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });
    function CheckDate(){
      var startdate=document.getElementById('start_date').value;
      var enddate=document.getElementById('end_date').value;
      var sd = Date.parse(startdate);
      var ed = Date.parse(enddate);
      if(ed<sd){
        alert('Reverse Date Formate Not Allowed');
        return false;
      }
      else{
        document.getElementById("filterdata").submit();
      }
    }
    $('#start_date').datepicker({
        autoclose: true,
        todayHighlight: true
      });
    $('#end_date').datepicker({
        autoclose: true,
        todayHighlight: true
      });
      var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
      $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
       });
  </script>
  @if(session()->has('info'))
  <script>
  round_info_noti_delete();
  </script>
  @endif
 </body>
</html>



