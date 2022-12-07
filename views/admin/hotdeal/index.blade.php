@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Hot-Deal content Tables</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Hot-Deal content List</li>
         </ol>
     </div>
     <div class="col-sm-3">
       <a href="{{url('admin/hot_deal/add')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Add New</button>
       </a>
     </div>
     </div>

        <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Hot-Deal List</div>
            <div class="card-body">
              <div class="table-responsive">
             <table id="list" class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Image (English)</th>
                        <th>Image (Arabic)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @if($banner)
                    @php
                     $i=1;
                     @endphp
                    @foreach($banner as $record)
                    <tr>
                        <td>{{$i}}</td>
                        <td><img src="{{ url('/') }}/public/images/hotdeal/{{$record->image_en}}"  alt="User Image" width="150"></td>
                        <td><img src="{{ url('/') }}/public/images/hotdeal/{{$record->image_ar}}"  alt="User Image" width="150"></td>
                        <td>@if($record->status == 1)Enabled @else Disabled @endif</td>
                        <td>
                            @if(auth()->user()->role === 1)
                          <a href="{{ url('/')}}/admin/hot_deal/delete/{{$record->id}}">
                          <button onclick="return confirm('are you want to delete this record');" type="button" class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button></a>
                          <a href="{{ url('/')}}/admin/hot_deal/edit/{{$record->id}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button></a>
                            @endif
                                @php $permission = permission(); @endphp

                                    @if(auth()->user()->role === 3 && isset($permission['hot_deals']) && $permission['hot_deals']['delete'] === 1)
                                        <a href="{{ url('/')}}/admin/hot_deal/delete/{{$record->id}}">
                                            <button onclick="return confirm('are you want to delete this record');" type="button" class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button></a>
                                    @endif
                                    @if(auth()->user()->role === 3 && isset($permission['hot_deals']) && $permission['hot_deals']['edit'] === 1)
                                            <a href="{{ url('/')}}/admin/hot_deal/edit/{{$record->id}}">
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
      $('#list').DataTable({"order": [[ 3, "desc" ]]});
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



