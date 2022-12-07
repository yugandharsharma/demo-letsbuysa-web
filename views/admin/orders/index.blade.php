@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Order content Tables</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order content List</li>
         </ol>
     </div>
     </div>
        <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Order List</div>
            <div class="card-body">
              <div class="table-responsive">
             <table id="list" class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Order ID</th>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Order Price</th>
                        <th>Payment Mode</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @if($orders)
                    @php
                     $i=1;
                     @endphp
                    @foreach($orders as $record)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$record->id}}</td>
                        <td>{{$record->user_id}}</td>
                        <td>{{$record->username}}</td>
                        <td>{{$record->price}}</td>
                        <td>@if($record->payment_type==1) Cash On Delivery @elseif($record->payment_type ==2 || $record->payment_type ==4) Credit Card Or Debit Card @elseif($record->payment_type ==6) Apple Pay @else Bank Transfer @endif</td>
                        <td>
                        <form action ="{{route('change_order_status',base64_encode($record->id))}}">
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" onchange="this.form.submit()">
                        @foreach($order_status as $status)
                        <option value="{{$status->id}}"<?php if ($status->id==$record->status) {echo 'selected';}?>>{{$status->status_name_en}}</option>
                        @endforeach
                        </select>
                        </form>
                        </td>
                        <td>
                            @if(auth()->user()->role === 1)
                          <a href="{{route('order_view', base64_encode($record->id))}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-eye"></i> </button></a>
                          <a href="{{route('order_edit', base64_encode($record->id))}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button></a>
                            @endif
                                @php $permission = permission(); @endphp

                                    @if(auth()->user()->role === 3 && isset($permission['orders']) && $permission['orders']['delete'] === 1)
                                        <a href="{{route('order_view', base64_encode($record->id))}}">
                                            <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-eye"></i> </button></a>
                                    @endif
                                    @if(auth()->user()->role === 3 && isset($permission['orders']) && $permission['orders']['edit'] === 1)
                                            <a href="{{route('order_edit', base64_encode($record->id))}}">
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
            {{ $orders->appends(request()->except('page'))->links() }}
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
      $('#list').DataTable({"order": [[ 8, "desc" ]]});
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
