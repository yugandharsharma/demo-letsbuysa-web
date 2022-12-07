@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Content View</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/customer')}}">Content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Content View</li>
         </ol>
     </div>
      <div class="col-sm-3">
       <a href="{{url('admin/content')}}">
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
                   Content Info
                </h4>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Title(English)</label>
                  <div class="col-sm-10">
                    <p>{{$content->title_en}}</p>
                  </div>
                </div>
                  <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Title(Arabic)</label>
                  <div class="col-sm-10">
                    <p>{{$content->title_ar}}</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">Description(English)</label>
                  <div class="col-sm-10">
                    <p>{{$content->description_en}}</p>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-3" class="col-sm-2 col-form-label">Description(Arabic)</label>
                  <div class="col-sm-10">
                    <p>{{$content->description_ar}}</p>
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
</html>