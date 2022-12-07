@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Voucher content Tables</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Voucher content List</li>
         </ol>
     </div>
      @php $permission = permission(); @endphp
      @if(auth()->user()->role === 3 && isset($permission['gift']) && $permission['gift']['add'] === 1)
     <div class="col-sm-3">
       <a href="{{url('admin/voucher/add')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Add New</button>
       </a>
     </div>
     @endif
     @if(auth()->user()->role === 1)
     <div class="col-sm-3">
       <a href="{{url('admin/voucher/add')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Add New</button>
       </a>
     </div>
     @endif
     </div>
        <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Voucher List</div>
            <div class="card-body">
              <div class="table-responsive">
             <table id="list" class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Voucher Name</th>
                        <th>Voucher Code</th>
                        <th>Amount</th>
                        <th>Date Start</th>
                        <th>Date End</th>
                        <th>Is Used</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @if($voucher)
                    @php
                     $i=1;
                     @endphp
                    @foreach($voucher as $record)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$record->voucher_name}}</td>
                        <td>{{$record->code}}</td>
                        <td>{{$record->amount}}</td>
                        <td>{{$record->start_date}}</td>
                        <td>{{$record->end_date}}</td>
                        <td>@if($record->is_used==1) Yes @else No @endif</td>
                        <td>@if($record->status==1) Enabled @else Disabled @endif</td>
                        <td>
                          @if(auth()->user()->role === 1)
                          <a href="{{ url('/')}}/admin/voucher/delete/{{$record->id}}">
                          <button onclick="return confirm('are you want to delete this record');" type="button" class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button></a>
                          <a href="{{ url('/')}}/admin/voucher/edit/{{$record->id}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button></a>
                          <a href="{{ url('/')}}/admin/voucher/email/{{$record->id}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-envelope"></i> </button></a>
                          @endif

                           @php $permission = permission(); @endphp
                          @if(auth()->user()->role === 3 && isset($permission['gift']) && $permission['gift']['delete'] === 1)
                           <a href="{{ url('/')}}/admin/voucher/delete/{{$record->id}}">
                          <button onclick="return confirm('are you want to delete this record');" type="button" class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button></a>
                          @endif
                          @if(auth()->user()->role === 3 && isset($permission['gift']) && $permission['gift']['edit'] === 1)
                            <a href="{{ url('/')}}/admin/voucher/edit/{{$record->id}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button></a>
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
            </div>
            </div>
          </div>
        </div>
      </div>
  </div>
            </div>

@include('includes-file.footer')
  @if(session()->has('info'))
  <script>
  round_info_noti_delete();
  </script>
  @endif
  @if(session()->has('success'))
  <script>
  round_success_noti_mail();
  </script>
  @endif 
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
      $('#list').DataTable({"order": [[ 7, "desc" ]]});
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
 </body>
</html>
