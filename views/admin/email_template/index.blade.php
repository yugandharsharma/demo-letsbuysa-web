@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Email Template Tables</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Email Template List</li>
         </ol>
     </div>
     </div>

        <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Email Template List</div>
            <div class="card-body">
              <div class="table-responsive">
             <table id="list" class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Title Name English</th>
                        <th>Title Name Arabic</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @if($emailtemplate)
                    @php
                     $i=1;
                     @endphp
                    @foreach($emailtemplate as $record)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$record->title_en}}</td>
                        <td>{{$record->title_ar}}</td>
                        <td>
                            @if(auth()->user()->role === 1)
                          <a href="{{ url('/')}}/admin/emailtemplate/view/{{$record->id}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-eye"></i> </button></a>
                          <a href="{{ url('/')}}/admin/emailtemplate/edit/{{$record->id}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button></a>
                          @endif
                                @php $permission = permission(); @endphp

                                    @if(auth()->user()->role === 3 && isset($permission['email_template']) && $permission['email_template']['add'] === 1)
                                        <a href="{{ url('/')}}/admin/emailtemplate/view/{{$record->id}}">
                                            <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-eye"></i> </button></a>
                                    @endif
                                    @if(auth()->user()->role === 3 && isset($permission['email_template']) && $permission['email_template']['edit'] === 1)
                                            <a href="{{ url('/')}}/admin/emailtemplate/edit/{{$record->id}}">
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

  </script>
 </body>
</html>



