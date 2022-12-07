@include('includes-file.header')
@include('includes-file.sidebar')
<style>
.colorpicker-alpha{display:none !important; }
</style>
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Order Edit</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('admin/orders')}}">Order Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order Edit</li>
         </ol>
     </div>
     <div class="col-sm-3">
       <a href="{{url('admin/orders')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
        <div class="row" >
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Order Edit
                </h4>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Order ID</label>
                  <div class="col-sm-10">
                  <input type ="hidden" id="orderid" value="{{$data->id}}">
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
                    <select id="payment_mode" onchange="paymentchange();">
                        <option value="1"<?php if ($data->payment_type=='1') {echo 'selected';}?>>Cash On Delivery</option>
                        <option value="2"<?php if ($data->payment_type=='2'||$data->payment_type=='4') {echo 'selected';}?>>Credit/Debit/Mada Card</option>
                        <option value="3"<?php if ($data->payment_type=='3') {echo 'selected';}?>>Bank Transfer</option>
                        <option value="6"<?php if ($data->payment_type=='6') {echo 'selected';}?>>Apple Pay</option>
                    </select>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
        <div class="row" >
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Address Edit
                </h4>
                <form id="editaddressform" method="POST" action="{{route('address_update',base64_encode($address->id))}}">
                @csrf
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                   <input type ="text" name="fullname" class="form-control" value="{{$address->fullname}}">
                  </div>
                  @error('fullname')
                  <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Address</label>
                  <div class="col-sm-10">
                  <input type ="text" name="fulladdress" class="form-control" value="{{$address->fulladdress}}">
                  </div>
                  @error('fulladdress')
                  <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Mobile</label>
                  <div class="col-sm-10">
                   <input type ="text" name="mobile" class="form-control" value="{{$address->mobile}}">
                  </div>
                  @error('mobile')
                  <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Address Detail</label>
                  <div class="col-sm-10">
                   <input type ="text" name="address_details" class="form-control" value="{{$address->address_details}}">
                  </div>
                  @error('address_details')
                  <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
               <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> SAVE ADDRESS</button>
                </form>
            </div>
          </div>
        </div>
      </div>
      <input type ="hidden" id="colorinput" value ="">
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
                        <th>Options</th>
                        <th>Price</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                  @if($order_details)
                    @php
                     $i=1;
                     @endphp
                    @foreach($order_details as $record)
                    <?php $productcolors = getproductcolors($record->product_id)?>
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$record->product_name_en}}</td>
                        <td>{{$record->product_id}}</td>
                        <td> <button id="down" type="button" class="btn waves-effect waves-light" onclick="decreasequantity1('{{$record->product_id}}','{{$record->color}}');"><i class="fa fa-minus"></i></button>{{$record->quantity}}    <button id="up" type="button" class="btn waves-effect waves-light" onclick="increasequantity1('{{$record->product_id}}','{{$record->color}}');"><i class="fa fa-plus"></i></button></td>
                        <td>
                        @if(!empty($productcolors) && count($productcolors)>0)
                        <select id="colorchange{{$record->id}}" name="coloroption" onchange="colorchange('{{$record->id}}','{{$record->product_id}}');">
                        @foreach($productcolors as $colors)
                         <?php $productoptioncombination = getproductoptioncombinations($colors->option_id,$colors->option_value)?>
                        <option value="{{$colors->id}}"<?php if ($colors->id==$record->color) echo 'selected';?> >{{$colors->colorname}} {{$productoptioncombination['name']}} {{$productoptioncombination['value']}}</option>
                        @endforeach
                        </select>
                         @else
                        {{$record->colorname}}
                        @endif
                        </td>
                        <td>{{$record->price}}</td>
                        <td>
                        <a href="{{ url('/')}}/admin/orders/product/remove/{{$record->id}}">
                          <button onclick="return confirm('are you want to delete this record');" type="button" class="btn btn-danger waves-effect waves-light m-1"> <i class="fa fa-trash-o"></i> </button></a>
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
              </form>
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
  @if(session()->has('success'))
  <script>
  round_success_noti_customer_update();
  </script>
  @endif 
  <script>
  function paymentchange()
  {
     var payment_mode = document.getElementById("payment_mode").value;
     var order_id = document.getElementById("orderid").value;

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
       $.ajax({
                type: 'POST',
                url: basrurl + '/admin/orders/payement/edit',
                data: { payment_mode: payment_mode ,order_id:order_id},
                success: function (data) {

                    if (data.status == 1) {
                        toastr.success(data.success);
                    }
                    if (data.status == 2) {
                        toastr.error(data.success);
                    }
                }

            });
  } 
  function decreasequantity1(id,color)
  {
         var order_id = document.getElementById("orderid").value;

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
       });
       $.ajax({
                type: 'POST',
                url: basrurl + '/admin/orders/quantity/decrease',
                data: { productid: id ,color:color,order_id:order_id},
                success: function (data) {

                    if (data.status == 1) {
                        setTimeout(function () { location.reload(); }, 1000);
                        toastr.success(data.success);
                    }
                    if (data.status == 2) {
                        toastr.error(data.success);
                    }
                }

            });
    
  }

  function increasequantity1(id,color)
  {
         var order_id = document.getElementById("orderid").value;

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
       });
       $.ajax({
                type: 'POST',
                url: basrurl + '/admin/orders/quantity/increase',
                data: { productid: id ,color:color,order_id:order_id},
                success: function (data) {

                    if (data.status == 1) {
                        setTimeout(function () { location.reload(); }, 1000);
                        toastr.success(data.success);
                    }
                    if (data.status == 2) {
                        toastr.error(data.success);
                    }
                }

            });
    
  }
  
    function colorchange(id,product_id)
  {  

     var color =  document.getElementById("colorchange"+id).value;
     var order_id = document.getElementById("orderid").value;

      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
       $.ajax({
                type: 'POST',
                url: basrurl + '/admin/orders/product/changecolor',
                data: {id:id ,color: color ,order_id:order_id,product_id:product_id},
                success: function (data) {

                    if (data.status == 1) {
                      setTimeout(function () { location.reload(); }, 1000);
                        toastr.success(data.success);
                    }
                    if (data.status == 2) {
                        toastr.error(data.success);
                    }
                }

            });
  } 
   
  </script>
 </body>
</html>

      