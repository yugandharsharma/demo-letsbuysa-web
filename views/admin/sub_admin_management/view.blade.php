@include('includes-file.header')
@include('includes-file.sidebar')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Sub Admin Management View</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('sub_admin_management_list')}}">Sub Admin Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sub Admin Management View</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <a href="{{route('sub_admin_management_list')}}">
                    <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="addsubadmin" action="{{route('sub_admin_management_view', base64_encode($view->id))}}" method="get">
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <p>{{$view->name ?? ''}}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <p>{{$view->email ?? ''}}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Mobile Number</label>
                                <div class="col-sm-10">
                                    <p>{{$view->mobile ?? ''}}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Permission</label>
                                <div class="col-sm-10">
                                    <table id="list" class="table table-sm">
                                        <thead>
                                        <tr>
                                            <th>Module Name</th>
                                            <th>Add</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($view->permission as $data)
                                            <tr>
                                                <td>
                                                    <label class="col-form-label">{{$data->module_name ?? ''}}</label>
                                                </td>
                                                <td>
                                                    <label class="col-form-label">{{$data->add === 1 ? 'Yes' : 'No'}}</label>
                                                </td>
                                                <td>
                                                    <label class="col-form-label">{{$data->edit === 1 ? 'Yes' : 'No'}}</label>
                                                </td>
                                                <td>
                                                    <label class="col-form-label">{{$data->delete === 1 ? 'Yes' : 'No'}}</label>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('includes-file.footer')
</body>
</html>
