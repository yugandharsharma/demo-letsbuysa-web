<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from codervent.com/bulona/demo/authentication-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 Oct 2020 05:46:57 GMT -->
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content=""/>
  <meta name="author" content=""/>
  <title>Let's Buy</title>
  <!--favicon-->
  <link rel="icon" href="{{ asset('assets/images/favicon.ico')}}" type="image/x-icon">
  <!-- Bootstrap core CSS-->
  <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet"/>
  <!-- animate CSS-->
  <link href="{{ asset('assets/css/animate.css')}}" rel="stylesheet" type="text/css"/>
  <!-- Icons CSS-->
  <link href="{{ asset('assets/css/icons.css')}}" rel="stylesheet" type="text/css"/>
  <!-- Custom Style-->
  <link href="{{ asset('assets/css/app-style.css')}}" rel="stylesheet"/>
  
</head>

<body>

<!-- start loader -->
   <div id="pageloader-overlay" class="visible incoming"><div class="loader-wrapper-outer"><div class="loader-wrapper-inner" ><div class="loader"></div></div></div></div>
   <!-- end loader -->

<!-- Start wrapper-->
 <div id="wrapper">

 <div class="loader-wrapper"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>
	<div class="card card-authentication1 mx-auto my-5">
		<div class="card-body">
		 <div class="card-content p-2">
		 	<div class="text-center">
		 		<img src="assets/images/logo-icon.png" alt="logo icon">
		 	</div>
		  <div class="card-title text-uppercase text-center py-3">Update Password</div>
		    <form method="POST" action="{{route('update-password',$slug)}}">
              @csrf
			  <div class="form-group">
			  <label for="exampleInputUsername" class="sr-only">Password</label>
			   <div class="position-relative has-icon-right">
				  <input id="password" type="password"placeholder="Enter Password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="password" autofocus>
				  <div class="form-control-position">
					  <i class="icon-user"></i>
				  </div>
			   </div>
			  </div>
                <div class="form-group">
			  <label for="exampleInputUsername" class="sr-only">Confirm Password</label>
			   <div class="position-relative has-icon-right">
				  <input id="confirmpassword" type="password"placeholder="Enter Confirm Password" class="form-control @error('confirmpassword') is-invalid @enderror" name="confirmpassword" value="{{ old('confirmpassword') }}" required autocomplete="confirmpassword" autofocus>
				  <div class="form-control-position">
					  <i class="icon-user"></i>
				  </div>
			   </div>
			  </div>
			 <button type="submit" class="btn btn-primary btn-block">Submit</button>
			 </form>
		   </div>
		  </div>
	     </div>
    
     <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	

	
	</div><!--wrapper-->
	
  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
  <script src="{{ asset('assets/js/popper.min.js')}}"></script>
  <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
	
  <!-- sidebar-menu js -->
  <script src="{{ asset('assets/js/sidebar-menu.js')}}"></script>
  
  <!-- Custom scripts -->
  <script src="{{ asset('assets/js/app-script.js')}}"></script>
  
</body>

<!-- Mirrored from codervent.com/bulona/demo/authentication-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 28 Oct 2020 05:46:57 GMT -->
</html>
