@include('includes-file.header')
@include('includes-file.sidebar')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="container-fluid">
            
            <div class="row pt-2 pb-2">
                <div class="col-sm-9">
                    <h4 class="page-title">Order Reports</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Order Reports</li>
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
                                        <input type="text" value="{{request()->user_name}}" class="form-control" name="user_name" placeholder="Search by user name">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->coupon_name}}" class="form-control" name="coupon_name" placeholder="Search by coupon name">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->address_city}}" class="form-control" name="address_city" placeholder="Search by City">
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->address_country}}" class="form-control" name="address_country" placeholder="Search by Country">
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->price}}" class="form-control" name="price" placeholder="Search by Paid Amount">
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->order_status_name}}" class="form-control" name="order_status_name" placeholder="Search by Order status">
                                    </div>
                                </div>
                                <br>

                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" value="{{request()->created_at}}" class="form-control" name="created_at" id="daterangepicker" placeholder="Search by date range">
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" name="payment_method">
                                            <option value="">Select payment method</option>
                                            <option value="1">Cash on delivery</option>
                                            <option value="2,4">Credit or Debit card</option>
                                            <option value="6">Apple pay</option>
                                            <option value="3">Bank transfer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <button type="submit" title="Search" class="btn btn-success btn-block">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <a title="Reset" href="{{ route('order_reports.listOrderReports') }}" class="btn btn-success btn-block">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <?php
                                                    $filterData = "?user_name=".request()->user_name;
                                                    $filterData .= "&coupon_name=".request()->coupon_name;
                                                ?>

                                                <a title="Download csv" href="{{ route('order_reports.exportOrderReportData') .$filterData }}" class="btn btn-success btn-block">
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
                        <div class="card-header"><i class="fa fa-table"></i> Order Reports List</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="list" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>User name</th>
                                            <th>Coupon name</th>
                                            <th>Product price</th>
                                            <th>Shipping</th>
                                            <th>Delivery</th>
                                            <th>Paid</th>
                                            <th>City</th>
                                            <th>Country</th>
                                            <th>Order status</th>
                                            <th>Payment type</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($orderReports)
                                            <?php
                                                $i = ($orderReports->currentpage()-1)* $orderReports->perpage() + 1;
                                            ?>
                                            @foreach($orderReports as $record)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{$record->user_name}}</td>
                                                    <td>{{$record->coupon_name}}</td>
                                                    <td>{{$record->product_total_amount}}</td>
                                                    <td>{{$record->shipping_price}}</td>
                                                    <td>{{$record->delivery_price}}</td>
                                                    <td>{{$record->price}}</td>
                                                    <td>{{$record->address_city}}</td>
                                                    <td>{{$record->address_country}}</td>
                                                    <td>{{$record->order_status_name}}</td>
                                                    <td>
                                                        @if($record->payment_type == 1) 
                                                            Cash on Delivery
                                                        @elseif($record->payment_type == 2 || $record->payment_type == 4)     
                                                            Credit or debit card
                                                        @elseif($record->payment_type == 6) 
                                                            Apple pay
                                                        @else 
                                                            Bank transfer
                                                        @endif
                                                    </td>
                                                    <td>{{$record->created_at}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                {{ $orderReports->appends(request()->except('page'))->links() }}
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
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}" />
<script src="{{ asset('assets/plugins/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
    // alert('wow');
    $('#daterangepicker').daterangepicker({
        autoUpdateInput: false,
    });
    $('#daterangepicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });
</script>