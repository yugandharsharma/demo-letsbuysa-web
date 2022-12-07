@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
  
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Email template view</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('emailtemplate')}}">Email template Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Email template View</li>
         </ol>
     </div>
       <div class="col-sm-3">
       <a href="{{url('admin/emailtemplate')}}">
        <button  class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                   <form id="showProduct" action="#" enctype="multipart/form-data" method="post">
              @csrf
              @method('PUT')

                <h4 class="form-header text-uppercase">
                  <i class="fa fa-user-circle-o"></i>
                   Email Template
                </h4>

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Title(English):</label>
                  <div class="col-sm-10">
                    {{$emailtemplate->title_en}}
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Title(Arabic):</label>
                  <div class="col-sm-10">
                   {{$emailtemplate->title_ar}}
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Subject(English):</label>
                  <div class="col-sm-10">
                   {{$emailtemplate->subject_en}}
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Subject(Arabic):</label>
                  <div class="col-sm-10">
                   {{$emailtemplate->subject_en}}
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(English):</label>
                  <div class="col-sm-10">
                   {!! $emailtemplate->description_en !!}
                  </div>
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Description(Arabic):</label>
                  <div class="col-sm-10">
                   {!! $emailtemplate->description_ar !!}
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

  @if(session()->has('success'))
  <script>
  round_success_noti_record_update();
  </script>
  @endif 
 </body>
</html>

      