@include('includes-file.header')
@include('includes-file.sidebar')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="container-fluid">
            
            <div class="row pt-2 pb-2">
                <div class="col-sm-9">
                    <h4 class="page-title">User Reports</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User Reports</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-search-plus"></i> Search here</div>
                        <div class="card-body">
                            <form method="get">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->name}}" class="form-control" name="name" placeholder="Search by name">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->email}}" class="form-control" name="email" placeholder="Search by email">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->mobile}}" class="form-control" name="mobile" placeholder="Search by mobile">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->country}}" class="form-control" name="country" placeholder="Search by country">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->city}}" class="form-control" name="city" placeholder="Search by city">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <button type="submit" title="Search" class="btn btn-success btn-block">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <a title="Reset" href="{{ route('user_reports.listUserReport') }}" class="btn btn-success btn-block">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <?php
                                                    $filterData = "?name=".request()->name;
                                                    $filterData .= "&email=".request()->email;
                                                    $filterData .= "&mobile=".request()->mobile;
                                                    $filterData .= "&country=".request()->country;
                                                    $filterData .= "&city=".request()->city;
                                                ?>

                                                <a title="Download csv" href="{{ route('user_reports.exportUserReportData') .$filterData }}" class="btn btn-success btn-block">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-table"></i> User Reports List</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="list" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile number</th>
                                            <th>Country </th>
                                            <th>City</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($userReports)
                                            <?php
                                                $i = ($userReports->currentpage()-1)* $userReports->perpage() + 1;
                                            ?>
                                            @foreach($userReports as $record)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{$record->name}}</td>
                                                    <td>{{$record->email}}</td>
                                                    <td>{{$record->mobile}}</td>
                                                    <td>{{$record->country_name}}</td>
                                                    <td>{{$record->city}}</td>
                                                    <td>{{$record->created_at}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                {{ $userReports->appends(request()->except('page'))->links() }}
                            </div>
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
<style type="text/css">
    .pagination{ margin-top: 2rem;  }
</style>
</body>
</html>