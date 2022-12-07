@include('includes-file.header')
@include('includes-file.sidebar')
<style>
    .paging_simple_numbers ul {
        display: none !important;
    }

</style>
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="container-fluid">
            <div class="row pt-2 pb-2">
                <div class="col-sm-9">
                    <h4 class="page-title">Product Content Tables</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Product content List</li>
                    </ol>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('product.create') }}">
                        <button type="button"
                            class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Add New</button>
                    </a>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    <form method="post" id="filterdata" action="{{ url('admin/product/search') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <p style="color: black;">Product Name</p>
                                <input type="text" maxlength="100" value="@if (!empty($val)){{ $val }}@endif"
                                    placeholder="Enter Product Name" class="form-control" id="name" name="name">
                            </div>
                            <div class="col-sm-4">
                                </br>
                                <button type="submit"
                                    class="btn btn-info btn-lg btn-round waves-effect waves-light m-1">Search</button>
                                &nbsp;&nbsp;
                                <a href="{{ url('/admin/product') }}">
                                    <button type="button"
                                        class="btn btn-info btn-lg btn-round waves-effect waves-light m-1">Reset</button>
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="card">
                        <div class="card-header"><i class="fa fa-table"></i> Product List
                            <!-- <a href="{{ Route('import') }}"><button class="btn btn-success" href="{{ Route('import') }}"><i class="fa fa-cloud-upload"></i>Import product</button></a>
                <a class="btn btn-warning" href="{{ Route('export') }}"><i class="fa fa-download"></i>Export product</a> -->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="list" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No <i class="fa fa-sort"></i></th>
                                            <th>Product Name English <i class="fa fa-sort"></th>
                                            <th>Product Name Arabic <i class="fa fa-sort"></th>
                                            <td>Stock Availabity <i class="fa fa-sort"></td>
                                            <td>Status</td>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($products)
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($products as $record)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $record->name_en }}</td>
                                                    <td>{{ $record->name_ar }}</td>
                                                    <td>@if ($record->stock_availabity == 1)In Stock @else Out Of Stock @endif</td>
                                                    <td>
                                                        <input type="checkbox" data-id="{{ $record->id }}"
                                                            name="status" class="js-switch"
                                                            {{ $record->status == 1 ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        @if (auth()->user()->role === 1)
                                                            <a
                                                                href="{{ url('/') }}/admin/product/destroy/{{ $record->id }}">
                                                                <button
                                                                    onclick="return confirm('are you want to delete this record');"
                                                                    type="button"
                                                                    class="btn btn-danger waves-effect waves-light m-1">
                                                                    <i class="fa fa-trash-o"></i> </button></a>


                                                            <a
                                                                href="{{ url('/') }}/admin/product/edit/{{ $record->id }}">
                                                                <button type="button"
                                                                    class="btn btn-success waves-effect waves-light m-1">
                                                                    <i class="fa fa-pencil"></i> </button></a>

                                                            <a
                                                                href="{{ url('/') }}/admin/product/show/{{ $record->id }}">
                                                                <button type="button"
                                                                    class="btn btn-success waves-effect waves-light m-1">
                                                                    <i class="fa fa-eye"></i> </button></a>
                                                        @endif
                                                        @php $permission = permission(); @endphp

                                                        @if (auth()->user()->role === 3 && isset($permission['products']) && $permission['products']['delete'] === 1)
                                                            <a
                                                                href="{{ url('/') }}/admin/product/destroy/{{ $record->id }}">
                                                                <button
                                                                    onclick="return confirm('are you want to delete this record');"
                                                                    type="button"
                                                                    class="btn btn-danger waves-effect waves-light m-1">
                                                                    <i class="fa fa-trash-o"></i> </button></a>
                                                        @endif
                                                        @if (auth()->user()->role === 3 && isset($permission['products']) && $permission['products']['edit'] === 1)
                                                            <a
                                                                href="{{ url('/') }}/admin/product/edit/{{ $record->id }}">
                                                                <button type="button"
                                                                    class="btn btn-success waves-effect waves-light m-1">
                                                                    <i class="fa fa-pencil"></i> </button></a>
                                                        @endif
                                                        @if (auth()->user()->role === 3 && isset($permission['products']) && $permission['products']['add'] === 1)
                                                            <a
                                                                href="{{ url('/') }}/admin/product/show/{{ $record->id }}">
                                                                <button type="button"
                                                                    class="btn btn-success waves-effect waves-light m-1">
                                                                    <i class="fa fa-eye"></i> </button></a>
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
                                {{ $products->appends(request()->except('page'))->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('includes-file.footer')
    @if (session()->has('info'))
        <script>
            round_info_noti_delete();
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            round_error_product();
        </script>
    @endif
    <script>
        $(document).ready(function() {
            //Default data table
            $('#default-datatable').DataTable();


            var table = $('#example').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
            });

            table.buttons().container()
                .appendTo('#example_wrapper .col-md-6:eq(0)');

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#list').DataTable({
                "order": [
                    [4, "desc"]
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".alert").slideDown(300).delay(5000).slideUp(300);
        });

        function CheckDate() {
            var startdate = document.getElementById('start_date').value;
            var enddate = document.getElementById('end_date').value;
            var sd = Date.parse(startdate);
            var ed = Date.parse(enddate);
            if (ed < sd) {
                alert('Reverse Date Formate Not Allowed');
                return false;
            } else {
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
    <!-- //status toggle script-->
    <script>
        let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

        elems.forEach(function(html) {
            let switchery = new Switchery(html, {
                size: 'small'
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.js-switch').change(function() {
                let status = $(this).prop('checked') === true ? 1 : 0;
                let productId = $(this).data('id');
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: '{{ route('product.update.status') }}',
                    data: {
                        'status': status,
                        'product_id': productId
                    },
                    success: function(data) {
                        //alert(data.status);
                        toastr.options.closeButton = true;
                        toastr.options.closeMethod = 'fadeOut';
                        toastr.options.closeDuration = 100;
                        toastr.success(data.message);
                    }
                });
            });
        });
    </script>


    </body>

    </html>
