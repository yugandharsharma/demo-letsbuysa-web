@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Order View</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/orders')}}">Order Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order View</li>
         </ol>
     </div>
      <div class="col-sm-3">
       <a href="{{url('admin/orders')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
        <a href="{{route('invoice',base64_encode($data->id))}}" type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Invoice View</a>
         <button type="button"onclick="printdata()" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Print Order Detail</button>
       </a>
     </div>
     </div>
     <div id="printdiv">
       <div class="row" >
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Order Info
                </h4>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Order ID</label>
                  <div class="col-sm-10">
                    <p>{{$data->id}}</p>
                  </div>
                </div>
                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Customer Name</label>
                  <div class="col-sm-10">
                    <p>{{$data->username}}</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">Order Price</label>
                  <div class="col-sm-10">
                    <p>{{$data->price}}</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">Order Status</label>
                  <div class="col-sm-10">
                    <p>{{$data->order_status}}</p>
                  </div>
                </div>
                 <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">Coupon Name</label>
                  <div class="col-sm-10">
                    <p>@if(!empty($data->coupon_name)) {{$data->coupon_name}}  @else Coupon Not Applied @endif</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">Payment Mode</label>
                  <div class="col-sm-10">
                    <p>@if($data->payment_type==1) Cash On Delivery @elseif($data->payment_type ==2 || $data->payment_type ==4) Credit Card Or Debit Card @elseif($data->payment_type ==6) Apple Pay @else Bank Transfer @endif</p>
                  </div>
                </div>
            </div>
          </div>
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
                        <th>Product Name</th>
                        <th>Product ID</th>
                        <th>Quantity</th>
                        <th>Colour</th>
                        <th>Option Name</th>
                        <th>Option Value</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                  @if($order_details)
                    @php
                     $i=1;
                     @endphp
                    @foreach($order_details as $record)
                    <tr>
                      <?php $data = getproductoptionsdetails($record->color);?>
                        <td>{{$i}}</td>
                        <td><a href="{{ route('product.show',$record->product_id) }}">{{$record->product_name_en}}</a></td>
                        <td>{{$record->product_id}}</td>
                        <td>{{$record->quantity}}</td>
                        <td>{{$record->color1}}</td>
                        @if(!empty($data))
                        <?php $nameoption = getoptionnames($data->option_id);?>
                        @if(!empty($nameoption))
                        <td>{{$nameoption->name_en}}</td>
                        @else
                        <td></td>
                        @endif
                        <td>{{$data->optionvalue}}</td>
                        @else
                        <td></td>
                        <td></td>
                        @endif
                        <td>{{$record->price}}</td>
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
  </div>
@include('includes-file.footer')
 </body>
 <script>
function printdata()
{
        var printContents = document.getElementById('printdiv').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
}

</script>
</html>