@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Transaction Tables</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Transaction List</li>
         </ol>
     </div>
     </div>
        <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Transaction List</div>
            <div class="card-body">
              <div class="table-responsive">
             <table id="list" class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>User Name</th>
                        <th>User ID</th>
                        <th>Order ID</th>
                        <th>Charge ID</th>
                        <th>Payment Status</th>
                        <th>Currency</th>
                        <th>Amount</th>
                        <th>Track ID</th>
                        <th>Payment ID</th>
                        <th>Payment Method</th>
                        <th>Payment Type</th>
                        <th>Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                  @if($transaction)
                    @php
                     $i=1;
                     @endphp
                    @foreach($transaction as $record)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$record->username}}</td>
                        <td>{{$record->user_id}}</td>
                        <td>{{$record->order_id}}</td>
                        <td>{{$record->charge_id}}</td>
                        <td>{{$record->payment_status}}</td>
                        <td>{{$record->currency}}</td>
                        <td>{{$record->amount}}</td>
                        <td>{{$record->track_id}}</td>
                        <td>{{$record->payment_id}}</td>
                        <td>{{$record->payment_method}}</td>
                        <td>{{$record->payment_type}}</td>
                        <td>{{$record->created_at}}</td>
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
      $('#list').DataTable({"order": [[ 12, "desc" ]]});
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
