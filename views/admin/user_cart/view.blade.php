@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">User Cart View</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/user/cart')}}">User Cart Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">User Cart View</li>
         </ol>
     </div>
      <div class="col-sm-3">
       <a href="{{url('admin/user/cart')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                  User Cart Info
                </h4>
                <div class="table-responsive">
             <table id="list" class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Product Name</th>
                        <th>Color</th>
                        <th>Option Name</th>
                        <th>Option Value</th>
                        <th>Quantity </th>
                    </tr>
                </thead>
                <tbody>
                  @if($user)
                    @php
                     $i=1;
                     @endphp
                    @foreach($user as $record)
                    <tr>
                        <td>{{$i}}</td>
                        <td><a href="{{ url('/')}}/admin/product/show/{{$record['product_id']}}">{{$record['name']}}</a></td>
                        <td>{{$record['color']}}</td>
                        <td>{{$record['option_name']}}</td>
                        <td>{{$record['option_value']}}</td>
                        <td>{{$record['quantity']}}</td>
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
 </body>
</html>
