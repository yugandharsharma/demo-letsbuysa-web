@include('includes-file.header')
@include('includes-file.sidebar')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="container-fluid">
            <div class="row pt-2 pb-2">
                <div class="col-sm-9">
                    <h4 class="page-title">Sub Admin Management</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sub Admin Management List</li>
                    </ol>
                </div>
                <div class="col-sm-3">
                    <a href="{{route('sub_admin_management_add')}}">
                        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Add New</button>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-table"></i> Sub Admin Management List</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="list" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile Number</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($list as $key => $records)

                                            <tr>
                                                <td>{{++$key}}</td>
                                                <td>{{$records->name ?? ''}}</td>
                                                <td>{{$records->email ?? ''}}</td>
                                                <td>{{$records->mobile ?? ''}}</td>
                                                <td><label class="label {{$records->is_delete == 1 ? 'label-success' : 'label-danger'}}">{{$records->is_delete == 1 ? 'Store' : 'Deleted'}}</label></td>
                                                <td>
                                                     @if(auth()->user()->role === 1)
                                                    <a href="{{route('sub_admin_management_view', base64_encode($records->id))}}">
                                                        <button type="button" class="btn btn-info waves-effect waves-light m-1"> <i class="fa fa-eye"></i> </button>
                                                    </a>
                                                    <a href="{{route('sub_admin_management_edit', base64_encode($records->id))}}">
                                                        <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button>
                                                    </a>
                                                    @if($records->is_delete == 1)
                                                    <a href="{{route('sub_admin_management_delete', base64_encode($records->id))}}" onclick="return confirm('Are you sure you want to delete this data?')">
                                                        <button class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button>
                                                    </a>
                                                    @else
                                                        <a href="{{route('sub_admin_management_delete', base64_encode($records->id))}}" onclick="return confirm('Are you sure you want to activate this data?')">
                                                            <button class="btn btn-info waves-effect waves-light m-1"> <i class="fa fa-check-circle-o"></i> </button>
                                                        </a>
                                                    @endif
                                                    @endif
                                                    @php $permission = permission(); @endphp

                                                     @if(auth()->user()->role === 3 && isset($permission['subadmin']) && $permission['subadmin']['add'] === 1)
                                                       <a href="{{route('sub_admin_management_view', base64_encode($records->id))}}">
                                                        <button type="button" class="btn btn-info waves-effect waves-light m-1"> <i class="fa fa-eye"></i> </button>
                                                       </a>
                                                     @endif

                                                     @if(auth()->user()->role === 3 && isset($permission['subadmin']) && $permission['subadmin']['edit'] === 1)
                                                       <a href="{{route('sub_admin_management_edit', base64_encode($records->id))}}">
                                                        <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-pencil"></i> </button>
                                                    </a>
                                                     @endif

                                                     @if(auth()->user()->role === 3 && isset($permission['subadmin']) && $permission['subadmin']['delete'] === 1)
                                                       @if($records->is_delete == 1)
                                                    <a href="{{route('sub_admin_management_delete', base64_encode($records->id))}}" onclick="return confirm('Are you sure you want to delete this data?')">
                                                        <button class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button>
                                                    </a>
                                                    @else
                                                        <a href="{{route('sub_admin_management_delete', base64_encode($records->id))}}" onclick="return confirm('Are you sure you want to activate this data?')">
                                                            <button class="btn btn-info waves-effect waves-light m-1"> <i class="fa fa-check-circle-o"></i> </button>
                                                        </a>
                                                    @endif
                                                     @endif


                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse

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
    @if(session()->has('infoerror'))
        <script>
            round_info_noti_category_infoerror();
        </script>
    @endif
    @if(session()->has('infoerrorsubcategory'))
        <script>
            round_info_noti_category_subcategory_infoerror();
        </script>
    @endif
    <script>
        $(document).ready(function() {
            //Default data table
            $('#default-datatable').DataTable();

        } );
    </script>


    </body>
    </html>



