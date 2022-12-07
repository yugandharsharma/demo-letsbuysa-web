@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Product Review View</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/review')}}">Product Review Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Product Review View</li>
         </ol>
     </div>
      <div class="col-sm-3">
       <a href="{{url('admin/review')}}">
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
                   Review Info
                </h4>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Name</label>
                  <div class="col-sm-10">
                    <p>{{$review->productname}}</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">Rating</label>
                  <div class="col-sm-10">
                    <p>{{$review->rating}}</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-4" class="col-sm-2 col-form-label">Review</label>
                  <div class="col-sm-10">
                    <p>{{$review->review}}</p>
                  </div>
                </div>
                 
                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">User Name</label>
                  <div class="col-sm-10">
                    <p>@if(isset($review->name)){{$review->name}}@endif</p>
                  </div>
                </div>
               <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Images
                </h4>
                @if(!empty($review->images))
                <?php $reviewimages = explode(',',$review->images); ?>
                 @foreach($reviewimages as $image)                 
                  <div class="col-sm-10">
                    <figure><img src="{{ url('/') }}/public/reviewcomment/{{$image}}" height="100" width="150"></figure>
                  </div>
                  @endforeach
                @endif
            </div>
          </div>
        </div>
      </div>
  </div>
  </div>

@include('includes-file.footer')
 </body>
</html>