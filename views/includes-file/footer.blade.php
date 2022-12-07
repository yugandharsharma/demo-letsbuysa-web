  <!--start overlay-->
	  <div class="overlay toggle-menu"></div>
	<!--end overlay-->
    </div>
    <!-- End container-fluid-->
    
    </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	
	<!--Start footer-->
	<footer class="footer">
      <div class="container">
        <div class="text-center">
          {{$global->copyright}}
        </div>
      </div>
    </footer>
	<!--End footer-->
	
	<!--start color switcher-->
   <div class="right-sidebar">
    <div class="switcher-icon">
      <i class="zmdi zmdi-settings zmdi-hc-spin"></i>
    </div>
    <div class="right-sidebar-content">
	
	
	 <p class="mb-0">Header Colors</p>
      <hr>
	  
	  <div class="mb-3">
	    <button type="button" id="default-header" class="btn btn-outline-primary">Default Header</button>
	  </div>
      
      <ul class="switcher">
        <li id="header1"></li>
        <li id="header2"></li>
        <li id="header3"></li>
        <li id="header4"></li>
        <li id="header5"></li>
        <li id="header6"></li>
      </ul>

      <p class="mb-0">Sidebar Colors</p>
      <hr>
	  
      <div class="mb-3">
	    <button type="button" id="default-sidebar" class="btn btn-outline-primary">Default Header</button>
	  </div>
	  
      <ul class="switcher">
        <li id="theme1"></li>
        <li id="theme2"></li>
        <li id="theme3"></li>
        <li id="theme4"></li>
        <li id="theme5"></li>
        <li id="theme6"></li>
      </ul>
      
     </div>
   </div>
  <!--end color switcher-->
  
  </div><!--End wrapper-->
    <script src="{{ asset('assets/js/index.js')}}"></script>

  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
  <script src="{{ asset('assets/js/popper.min.js')}}"></script>
  <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
	
 <!-- simplebar js -->
  <script src="{{ asset('assets/plugins/simplebar/js/simplebar.js')}}"></script>
  <!-- sidebar-menu js -->
  <script src="{{ asset('assets/js/sidebar-menu.js')}}"></script>
  <!-- loader scripts -->
  <script src="{{ asset('assets/js/jquery.loading-indicator.html')}}"></script>
  <!-- Custom scripts -->
  <script src="{{ asset('assets/js/app-script.js')}}"></script>
  <!-- Chart js -->
  
  <script src="{{ asset('assets/plugins/Chart.js/Chart.min.js')}}"></script>
  <!-- Vector map JavaScript -->
  <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
  <!-- Easy Pie Chart JS -->
  <script src="{{ asset('assets/plugins/jquery.easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
  <!-- Sparkline JS -->
  <script src="{{ asset('assets/plugins/sparkline-charts/jquery.sparkline.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/jquery-knob/excanvas.js')}}"></script>
  <script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datatable/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datatable/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datatable/js/buttons.bootstrap4.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datatable/js/jszip.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datatable/js/pdfmake.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datatable/js/vfs_fonts.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datatable/js/buttons.html5.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datatable/js/buttons.print.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datatable/js/buttons.colVis.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/switchery/js/switchery.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/notifications/js/notifications.min.js')}}"></script>
  <script src="{{ asset('assets/plugins/notifications/js/notification-custom-script.js')}}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>  
  <script src="{{ asset('assets/plugins/notifications/js/custome.js')}}"></script>
  <script src="{{ asset('assets/plugins/summernote/dist/summernote-bs4.min.js')}}"></script>
  <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/wysihtml5/0.3.0/wysihtml5.min.js"></script>
  <script src="{{ asset('assets/plugins/jquery-multi-select/jquery.multi-select.js')}}"></script>
  <script src="{{ asset('assets/plugins/jquery-multi-select/jquery.quicksearch.js')}}"></script>
   <script src="{{ asset('assets/plugins/select2/js/select2.min.js')}}"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
    <script>
        $(function() {
            $(".knob").knob();
        });
    </script>
  <script>var basrurl= "<?= url('/') ?>";</script>
  <!-- Index js -->
  
   


  

