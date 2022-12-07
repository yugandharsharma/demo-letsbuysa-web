@include('includes-file.header')
@include('includes-file.sidebar')
@php
    $permission = permission();
@endphp
      <!--Start Dashboard Content-->
      <div class="clearfix"></div>
@if(auth()->user()->role === 1)
  <div class="content-wrapper">
  <div class="container-fluid">
  <div class="row mt-3">
       <div class="col-12 col-lg-6 col-xl-3">
         <div class="card gradient-deepblue">
         <a href ="{{url('admin/orders')}}">
           <div class="card-body">
              <h5 class="text-white mb-0">{{$ordercount}} <span class="float-right"><i class="fa fa-shopping-cart"></i></span></h5>
                <div class="progress my-3" style="height:3px;">
                   <div class="progress-bar" style="width:55%"></div>
                </div>
              <p class="mb-0 text-white small-font">Total Completed Orders</p>
            </div>
            </a>
         </div>
       </div>
       <div class="col-12 col-lg-6 col-xl-3">
         <div class="card gradient-orange">
         <a href ="{{url('admin/customer')}}">
           <div class="card-body">
              <h5 class="text-white mb-0">{{$usercount}} <span class="float-right"><i class="fa fa-eye"></i></span></h5>
                <div class="progress my-3" style="height:3px;">
                   <div class="progress-bar" style="width:55%"></div>
                </div>
              <p class="mb-0 text-white small-font">Total Customers</p>
            </div>
            </a>
         </div>
       </div>
       <div class="col-12 col-lg-6 col-xl-3">
         <div class="card gradient-ohhappiness">
         <a href ="{{url('admin/transaction')}}">
            <div class="card-body">
              <h5 class="text-white mb-0">{{$totalprice}} <span class="float-right">SAR</span></h5>
                <div class="progress my-3" style="height:3px;">
                   <div class="progress-bar" style="width:55%"></div>
                </div>
              <p class="mb-0 text-white small-font">Total Orders Amount</p>
            </div>
            </a>
         </div>
       </div>
       <div class="col-12 col-lg-6 col-xl-3">
         <div class="card gradient-ibiza">
         <a href ="{{url('admin/product')}}">
            <div class="card-body">
              <h5 class="text-white mb-0">{{$totalproducts}} <span class="float-right"><i class="fa fa-envira"></i></span></h5>
                <div class="progress my-3" style="height:3px;">
                   <div class="progress-bar" style="width:55%"></div>
                </div>
              <p class="mb-0 text-white small-font">Total Products </p>
            </div>
            </a>
         </div>
       </div>
     </div><!--End Row-->
  <div class="row">
     <div class="col-12 col-lg-12 col-xl-12">
	    <div class="card">
		 <div class="card-header">Site Traffic
		 </div>
		 <div class="card-body">
		    <ul class="list-inline">
			  <li class="list-inline-item"><i class="fa fa-circle mr-2" style="color: #14abef"></i>New Users</li>
			</ul>
			<div class="chart-container-1">
		      <canvas id="chart1"></canvas>
			</div>
		 </div>

		 <!-- <div class="row m-0 row-group text-center border-top border-light-3">
		   <div class="col-12 col-lg-4">
		     <div class="p-3">
		       <h5 class="mb-0">45.87M</h5>
			   <small class="mb-0">Overall Visitor <span> <i class="fa fa-arrow-up"></i> 2.43%</span></small>
		     </div>
		   </div>
		   <div class="col-12 col-lg-4">
		     <div class="p-3">
		       <h5 class="mb-0">15:48</h5>
			   <small class="mb-0">Visitor Duration <span> <i class="fa fa-arrow-up"></i> 12.65%</span></small>
		     </div>
		   </div>
		   <div class="col-12 col-lg-4">
		     <div class="p-3">
		       <h5 class="mb-0">245.65</h5>
			   <small class="mb-0">Pages/Visit <span> <i class="fa fa-arrow-up"></i> 5.62%</span></small>
		     </div>
		   </div>
		 </div> -->

		</div>
	 </div>

     <div class="col-12 col-lg-4 col-xl-4" style="display:none;">
        <div class="card">
           <div class="card-header">Weekly sales
             <div class="card-action">
             <div class="dropdown">
             <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
              <i class="icon-options"></i>
             </a>
              <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="javascript:void();">Action</a>
              <a class="dropdown-item" href="javascript:void();">Another action</a>
              <a class="dropdown-item" href="javascript:void();">Something else here</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="javascript:void();">Separated link</a>
               </div>
              </div>
             </div>
           </div>
           <div class="card-body">
              <div class="chart-container-2">
                 <canvas id="chart2"></canvas>
			  </div>
           </div>
           <div class="table-responsive">
             <table class="table align-items-center">
               <tbody>
                 <tr>
                   <td><i class="fa fa-circle mr-2" style="color: #14abef"></i> Direct</td>
                   <td>$5856</td>
                   <td>+55%</td>
                 </tr>
                 <tr>
                   <td><i class="fa fa-circle mr-2" style="color: #02ba5a"></i>Affiliate</td>
                   <td>$2602</td>
                   <td>+25%</td>
                 </tr>
                 <tr>
                   <td><i class="fa fa-circle mr-2" style="color: #d13adf"></i>E-mail</td>
                   <td>$1802</td>
                   <td>+15%</td>
                 </tr>
                 <tr>
                   <td><i class="fa fa-circle mr-2" style="color: #fba540"></i>Other</td>
                   <td>$1105</td>
                   <td>+5%</td>
                 </tr>
               </tbody>
             </table>
           </div>
         </div>
     </div>
	</div><!--End Row-->
  <div class="row" style="display:none;">
	<div class="col-12 col-lg-6 col-xl-4">
	   <div class="card">
	     <div class="card-body">
		   <div class="media align-items-center">
		     <div class="w_chart easy-dash-chart1" data-percent="60">
			   <span class="w_percent"></span>
			 </div>
			 <div class="media-body ml-3">
			   <h6 class="mb-0">Facebook Followers</h6>
			   <small class="mb-0">22.14% <i class="fa fa-arrow-up"></i> Since Last Week</small>
			 </div>
			 <i class="fa fa-facebook text-facebook text-right fa-2x"></i>
		   </div>
		 </div>
	   </div>
	 </div>
	 <div class="col-12 col-lg-6 col-xl-4">
	   <div class="card">
	     <div class="card-body">
		   <div class="media align-items-center">
		     <div class="w_chart easy-dash-chart2" data-percent="65">
			   <span class="w_percent"></span>
			 </div>
			 <div class="media-body ml-3">
			   <h6 class="mb-0">Twitter Tweets</h6>
			   <small class="mb-0">32.15% <i class="fa fa-arrow-up"></i> Since Last Week</small>
			 </div>
			 <i class="fa fa-twitter text-twitter text-right fa-2x"></i>
		   </div>
		 </div>
	   </div>
	 </div>
	 <div class="col-12 col-lg-12 col-xl-4">
	   <div class="card">
	     <div class="card-body">
		   <div class="media align-items-center">
		     <div class="w_chart easy-dash-chart3" data-percent="75">
			   <span class="w_percent"></span>
			 </div>
			 <div class="media-body ml-3">
			   <h6 class="mb-0">Youtube Subscribers</h6>
			   <small class="mb-0">58.24% <i class="fa fa-arrow-up"></i> Since Last Week</small>
			 </div>
			 <i class="fa fa-youtube text-youtube fa-2x"></i>
		   </div>
		 </div>
	   </div>
	 </div>
	</div><!--End Row-->
  <div class="row" style="display:none;">
     <div class="col-12 col-lg-12 col-xl-6">
       <div class="card">
         <div class="card-header">World Selling Region
             <div class="card-action">
             <div class="dropdown">
             <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
              <i class="icon-options"></i>
             </a>
              <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="javascript:void();">Action</a>
              <a class="dropdown-item" href="javascript:void();">Another action</a>
              <a class="dropdown-item" href="javascript:void();">Something else here</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="javascript:void();">Separated link</a>
               </div>
              </div>
             </div>
           </div>
         <div class="card-body">
            <div id="dashboard-map" style="height: 275px;"></div>
         </div>
         <div class="table-responsive">
            <table class="table table-striped align-items-center">
               <thead>
                  <tr>
                      <th>Country</th>
                      <th>Income</th>
                      <th>Trend</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td><i class="flag-icon flag-icon-ca mr-2"></i> USA</td>
                      <td>4,586$</td>
                      <td><span id="trendchart1"></span></td>
                  </tr>
                  <tr>
                      <td><i class="flag-icon flag-icon-us mr-2"></i>Canada</td>
                      <td>2,089$</td>
                      <td><span id="trendchart2"></span></td>
                  </tr>

                  <tr>
                      <td><i class="flag-icon flag-icon-in mr-2"></i>India</td>
                      <td>3,039$</td>
                      <td><span id="trendchart3"></span></td>
                  </tr>

                  <tr>
                      <td><i class="flag-icon flag-icon-gb mr-2"></i>UK</td>
                      <td>2,309$</td>
                      <td><span id="trendchart4"></span></td>
                  </tr>

                  <tr>
                      <td><i class="flag-icon flag-icon-de mr-2"></i>Germany</td>
                      <td>7,209$</td>
                      <td><span id="trendchart5"></span></td>
                  </tr>

              </tbody>
          </table>
          </div>
       </div>
     </div>

	 <div class="col-12 col-lg-12 col-xl-6" style="display:none;">
        <div class="row">
		  <div class="col-12 col-lg-6">
		    <div class="card">
			 <div class="card-body">
				<p>Page Views</p>
				<h4 class="mb-0">8,293 <small class="small-font">5.2% <i class="zmdi zmdi-long-arrow-up"></i></small></h4>
			 </div>
			 <div class="chart-container-3">
			   <canvas id="chart3"></canvas>
			 </div>
		   </div>
		  </div>
		  <div class="col-12 col-lg-6">
		    <div class="card">
			 <div class="card-body">
				<p>Total Clicks</p>
				<h4 class="mb-0">7,493 <small class="small-font">1.4% <i class="zmdi zmdi-long-arrow-up"></i></small></h4>
			 </div>
			 <div class="chart-container-3">
			  <canvas id="chart4"></canvas>
			  </div>
		   </div>
		  </div>
		  <div class="col-12 col-lg-6">
		    <div class="card">
			 <div class="card-body text-center">
				<p class="mb-4">Total Downloads</p>
				<input class="knob" data-width="175" data-height="175" data-readOnly="true" data-thickness=".2" data-angleoffset="90" data-linecap="round" data-bgcolor="rgba(0, 0, 0, 0.08)" data-fgcolor="#843cf7" data-max="15000" value="8550"/>
				<hr>
				<p class="mb-0 small-font text-center">3.4% <i class="zmdi zmdi-long-arrow-up"></i> since yesterday</p>
			 </div>
		   </div>
		  </div>
		  <div class="col-12 col-lg-6">
		    <div class="card">
			 <div class="card-body">
				<p>Device Storage</p>
				<h4 class="mb-3">42620/50000</h4>
				<hr>
				<div class="progress-wrapper mb-4">
				   <p>Documents <span class="float-right">12GB</span></p>
                   <div class="progress" style="height:5px;">
                       <div class="progress-bar bg-success" style="width:80%"></div>
                   </div>
                </div>

				<div class="progress-wrapper mb-4">
				   <p>Images <span class="float-right">10GB</span></p>
                   <div class="progress" style="height:5px;">
                       <div class="progress-bar bg-danger" style="width:60%"></div>
                   </div>
                </div>

				<div class="progress-wrapper mb-4">
				    <p>Mails <span class="float-right">5GB</span></p>
                   <div class="progress" style="height:5px;">
                       <div class="progress-bar bg-primary" style="width:40%"></div>
                   </div>
                </div>

			 </div>
		   </div>
		  </div>
		</div>
	 </div>

  </div><!--End Row-->
  <div class="row" >
      <div class="col-12 col-lg-6 col-xl-4"style="display:none;">
        <div class="card">
           <div class="card-body">
             <p>Total Earning</p>
             <h4 class="mb-0">287,493$</h4>
             <small>1.4% <i class="zmdi zmdi-long-arrow-up"></i> Since Last Month</small>
             <hr>
             <p>Total Sales</p>
             <h4 class="mb-0">87,493</h4>
             <small>5.43% <i class="zmdi zmdi-long-arrow-up"></i> Since Last Month</small>
             <div class="mt-5">
              <div class="chart-container-4">
               <canvas id="chart5"></canvas>
			  </div>
            </div>
           </div>
        </div>

      </div>

      <div class="col-12 col-lg-12 col-xl-12">
         <div class="card">
           <div class="card-header">Customer Review
           </div>
           <ul class="list-group list-group-flush">
           @foreach($product_review as $review)
              <li class="list-group-item">
                <div class="media align-items-center">
                <div class="media-body ml-3">
                  <h6 class="mb-0">{{$review->productname}} <small class="ml-4">{{$review->created_at}}</small></h6>
                  <p class="mb-0 small-font">{{$review->name}} : {{$review->review}}.</p>
                </div>
                @if($review->rating ==5)
                <div class="star">
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star text-warning"></i>
                </div>
                @endif
                 @if($review->rating ==4)
                <div class="star">
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star"></i>
                </div>
                @endif
                 @if($review->rating ==3)
                <div class="star">
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star"></i>
                  <i class="fa fa-star"></i>
                </div>
                @endif
                 @if($review->rating ==2)
                <div class="star">
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star"></i>
                  <i class="fa fa-star"></i>
                  <i class="fa fa-star"></i>
                </div>
                @endif
                 @if($review->rating ==1)
                <div class="star">
                  <i class="fa fa-star text-warning"></i>
                  <i class="fa fa-star"></i>
                  <i class="fa fa-star"></i>
                  <i class="fa fa-star"></i>
                  <i class="fa fa-star"></i>
                </div>
                @endif
              </div>
              </li>
              @endforeach
            </ul>
         </div>
      </div>
    </div><!--End Row-->
  <div class="row">
	 <div class="col-12 col-lg-12">
	   <div class="card">
	     <div class="card-header border-0">Recent Order Tables
		  <!-- <div class="card-action">
             <div class="dropdown">
             <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
              <i class="icon-options"></i>
             </a>
              <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="javascript:void();">Action</a>
              <a class="dropdown-item" href="javascript:void();">Another action</a>
              <a class="dropdown-item" href="javascript:void();">Something else here</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="javascript:void();">Separated link</a>
               </div>
              </div>
             </div> -->
		 </div>
	       <div class="table-responsive">
                 <table class="table align-items-center table-flush">
                  <thead>
                   <tr>
                     <th>Order ID</th>
                     <th>User Name</th>
                     <th>Amount</th>
                     <th>Status</th>
                     <th>Date</th>
                   </tr>
                   </thead>
           <tbody><tr>
                    @foreach($latestorders as $orders)
                    <td>{{$orders->id}}</td>
                    <td>{{$orders->username}}</td>
                    <td>SR {{$orders->price}}</td>
                    <td>
                      <span class="badge-dot">
                        <i class="bg-danger"></i> {{$orders->statusname}}
                      </span>
                    </td>
                    <td>{{$orders->created_at}}</td>
                   </tr>
              @endforeach
                 </tbody></table>
               </div>
	   </div>
	 </div>
	</div><!--End Row-->
@endif
  @if(auth()->user()->role === 3 && isset($permission['dashboard']))
          <div class="content-wrapper">
              <div class="container-fluid">
                  <div class="row mt-3">
                      <div class="col-12 col-lg-6 col-xl-3">
                          <div class="card gradient-deepblue">
                              <a href ="{{url('admin/orders')}}">
                                  <div class="card-body">
                                      <h5 class="text-white mb-0">{{$ordercount}} <span class="float-right"><i class="fa fa-shopping-cart"></i></span></h5>
                                      <div class="progress my-3" style="height:3px;">
                                          <div class="progress-bar" style="width:55%"></div>
                                      </div>
                                      <p class="mb-0 text-white small-font">Total Completed Orders</p>
                                  </div>
                              </a>
                          </div>
                      </div>
                      <div class="col-12 col-lg-6 col-xl-3">
                          <div class="card gradient-orange">
                              <a href ="{{url('admin/customer')}}">
                                  <div class="card-body">
                                      <h5 class="text-white mb-0">{{$usercount}} <span class="float-right"><i class="fa fa-eye"></i></span></h5>
                                      <div class="progress my-3" style="height:3px;">
                                          <div class="progress-bar" style="width:55%"></div>
                                      </div>
                                      <p class="mb-0 text-white small-font">Total Customers</p>
                                  </div>
                              </a>
                          </div>
                      </div>
                      <div class="col-12 col-lg-6 col-xl-3">
                          <div class="card gradient-ohhappiness">
                              <a href ="{{url('admin/transaction')}}">
                                  <div class="card-body">
                                      <h5 class="text-white mb-0">{{$totalprice}} <span class="float-right">SAR</span></h5>
                                      <div class="progress my-3" style="height:3px;">
                                          <div class="progress-bar" style="width:55%"></div>
                                      </div>
                                      <p class="mb-0 text-white small-font">Total Orders Amount</p>
                                  </div>
                              </a>
                          </div>
                      </div>
                      <div class="col-12 col-lg-6 col-xl-3">
                          <div class="card gradient-ibiza">
                              <a href ="{{url('admin/product')}}">
                                  <div class="card-body">
                                      <h5 class="text-white mb-0">{{$totalproducts}} <span class="float-right"><i class="fa fa-envira"></i></span></h5>
                                      <div class="progress my-3" style="height:3px;">
                                          <div class="progress-bar" style="width:55%"></div>
                                      </div>
                                      <p class="mb-0 text-white small-font">Total Products </p>
                                  </div>
                              </a>
                          </div>
                      </div>
                  </div><!--End Row-->
                  <div class="row">
                      <div class="col-12 col-lg-12 col-xl-12">
                          <div class="card">
                              <div class="card-header">Site Traffic
                              </div>
                              <div class="card-body">
                                  <ul class="list-inline">
                                      <li class="list-inline-item"><i class="fa fa-circle mr-2" style="color: #14abef"></i>New Users</li>
                                  </ul>
                                  <div class="chart-container-1">
                                      <canvas id="chart1"></canvas>
                                  </div>
                              </div>

                              <!-- <div class="row m-0 row-group text-center border-top border-light-3">
                                <div class="col-12 col-lg-4">
                                  <div class="p-3">
                                    <h5 class="mb-0">45.87M</h5>
                                    <small class="mb-0">Overall Visitor <span> <i class="fa fa-arrow-up"></i> 2.43%</span></small>
                                  </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                  <div class="p-3">
                                    <h5 class="mb-0">15:48</h5>
                                    <small class="mb-0">Visitor Duration <span> <i class="fa fa-arrow-up"></i> 12.65%</span></small>
                                  </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                  <div class="p-3">
                                    <h5 class="mb-0">245.65</h5>
                                    <small class="mb-0">Pages/Visit <span> <i class="fa fa-arrow-up"></i> 5.62%</span></small>
                                  </div>
                                </div>
                              </div> -->

                          </div>
                      </div>

                      <div class="col-12 col-lg-4 col-xl-4" style="display:none;">
                          <div class="card">
                              <div class="card-header">Weekly sales
                                  <div class="card-action">
                                      <div class="dropdown">
                                          <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                                              <i class="icon-options"></i>
                                          </a>
                                          <div class="dropdown-menu dropdown-menu-right">
                                              <a class="dropdown-item" href="javascript:void();">Action</a>
                                              <a class="dropdown-item" href="javascript:void();">Another action</a>
                                              <a class="dropdown-item" href="javascript:void();">Something else here</a>
                                              <div class="dropdown-divider"></div>
                                              <a class="dropdown-item" href="javascript:void();">Separated link</a>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="card-body">
                                  <div class="chart-container-2">
                                      <canvas id="chart2"></canvas>
                                  </div>
                              </div>
                              <div class="table-responsive">
                                  <table class="table align-items-center">
                                      <tbody>
                                      <tr>
                                          <td><i class="fa fa-circle mr-2" style="color: #14abef"></i> Direct</td>
                                          <td>$5856</td>
                                          <td>+55%</td>
                                      </tr>
                                      <tr>
                                          <td><i class="fa fa-circle mr-2" style="color: #02ba5a"></i>Affiliate</td>
                                          <td>$2602</td>
                                          <td>+25%</td>
                                      </tr>
                                      <tr>
                                          <td><i class="fa fa-circle mr-2" style="color: #d13adf"></i>E-mail</td>
                                          <td>$1802</td>
                                          <td>+15%</td>
                                      </tr>
                                      <tr>
                                          <td><i class="fa fa-circle mr-2" style="color: #fba540"></i>Other</td>
                                          <td>$1105</td>
                                          <td>+5%</td>
                                      </tr>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div><!--End Row-->
                  <div class="row" style="display:none;">
                      <div class="col-12 col-lg-6 col-xl-4">
                          <div class="card">
                              <div class="card-body">
                                  <div class="media align-items-center">
                                      <div class="w_chart easy-dash-chart1" data-percent="60">
                                          <span class="w_percent"></span>
                                      </div>
                                      <div class="media-body ml-3">
                                          <h6 class="mb-0">Facebook Followers</h6>
                                          <small class="mb-0">22.14% <i class="fa fa-arrow-up"></i> Since Last Week</small>
                                      </div>
                                      <i class="fa fa-facebook text-facebook text-right fa-2x"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-12 col-lg-6 col-xl-4">
                          <div class="card">
                              <div class="card-body">
                                  <div class="media align-items-center">
                                      <div class="w_chart easy-dash-chart2" data-percent="65">
                                          <span class="w_percent"></span>
                                      </div>
                                      <div class="media-body ml-3">
                                          <h6 class="mb-0">Twitter Tweets</h6>
                                          <small class="mb-0">32.15% <i class="fa fa-arrow-up"></i> Since Last Week</small>
                                      </div>
                                      <i class="fa fa-twitter text-twitter text-right fa-2x"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-12 col-lg-12 col-xl-4">
                          <div class="card">
                              <div class="card-body">
                                  <div class="media align-items-center">
                                      <div class="w_chart easy-dash-chart3" data-percent="75">
                                          <span class="w_percent"></span>
                                      </div>
                                      <div class="media-body ml-3">
                                          <h6 class="mb-0">Youtube Subscribers</h6>
                                          <small class="mb-0">58.24% <i class="fa fa-arrow-up"></i> Since Last Week</small>
                                      </div>
                                      <i class="fa fa-youtube text-youtube fa-2x"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div><!--End Row-->
                  <div class="row" style="display:none;">
                      <div class="col-12 col-lg-12 col-xl-6">
                          <div class="card">
                              <div class="card-header">World Selling Region
                                  <div class="card-action">
                                      <div class="dropdown">
                                          <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                                              <i class="icon-options"></i>
                                          </a>
                                          <div class="dropdown-menu dropdown-menu-right">
                                              <a class="dropdown-item" href="javascript:void();">Action</a>
                                              <a class="dropdown-item" href="javascript:void();">Another action</a>
                                              <a class="dropdown-item" href="javascript:void();">Something else here</a>
                                              <div class="dropdown-divider"></div>
                                              <a class="dropdown-item" href="javascript:void();">Separated link</a>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="card-body">
                                  <div id="dashboard-map" style="height: 275px;"></div>
                              </div>
                              <div class="table-responsive">
                                  <table class="table table-striped align-items-center">
                                      <thead>
                                      <tr>
                                          <th>Country</th>
                                          <th>Income</th>
                                          <th>Trend</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <tr>
                                          <td><i class="flag-icon flag-icon-ca mr-2"></i> USA</td>
                                          <td>4,586$</td>
                                          <td><span id="trendchart1"></span></td>
                                      </tr>
                                      <tr>
                                          <td><i class="flag-icon flag-icon-us mr-2"></i>Canada</td>
                                          <td>2,089$</td>
                                          <td><span id="trendchart2"></span></td>
                                      </tr>

                                      <tr>
                                          <td><i class="flag-icon flag-icon-in mr-2"></i>India</td>
                                          <td>3,039$</td>
                                          <td><span id="trendchart3"></span></td>
                                      </tr>

                                      <tr>
                                          <td><i class="flag-icon flag-icon-gb mr-2"></i>UK</td>
                                          <td>2,309$</td>
                                          <td><span id="trendchart4"></span></td>
                                      </tr>

                                      <tr>
                                          <td><i class="flag-icon flag-icon-de mr-2"></i>Germany</td>
                                          <td>7,209$</td>
                                          <td><span id="trendchart5"></span></td>
                                      </tr>

                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>

                      <div class="col-12 col-lg-12 col-xl-6" style="display:none;">
                          <div class="row">
                              <div class="col-12 col-lg-6">
                                  <div class="card">
                                      <div class="card-body">
                                          <p>Page Views</p>
                                          <h4 class="mb-0">8,293 <small class="small-font">5.2% <i class="zmdi zmdi-long-arrow-up"></i></small></h4>
                                      </div>
                                      <div class="chart-container-3">
                                          <canvas id="chart3"></canvas>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-12 col-lg-6">
                                  <div class="card">
                                      <div class="card-body">
                                          <p>Total Clicks</p>
                                          <h4 class="mb-0">7,493 <small class="small-font">1.4% <i class="zmdi zmdi-long-arrow-up"></i></small></h4>
                                      </div>
                                      <div class="chart-container-3">
                                          <canvas id="chart4"></canvas>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-12 col-lg-6">
                                  <div class="card">
                                      <div class="card-body text-center">
                                          <p class="mb-4">Total Downloads</p>
                                          <input class="knob" data-width="175" data-height="175" data-readOnly="true" data-thickness=".2" data-angleoffset="90" data-linecap="round" data-bgcolor="rgba(0, 0, 0, 0.08)" data-fgcolor="#843cf7" data-max="15000" value="8550"/>
                                          <hr>
                                          <p class="mb-0 small-font text-center">3.4% <i class="zmdi zmdi-long-arrow-up"></i> since yesterday</p>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-12 col-lg-6">
                                  <div class="card">
                                      <div class="card-body">
                                          <p>Device Storage</p>
                                          <h4 class="mb-3">42620/50000</h4>
                                          <hr>
                                          <div class="progress-wrapper mb-4">
                                              <p>Documents <span class="float-right">12GB</span></p>
                                              <div class="progress" style="height:5px;">
                                                  <div class="progress-bar bg-success" style="width:80%"></div>
                                              </div>
                                          </div>

                                          <div class="progress-wrapper mb-4">
                                              <p>Images <span class="float-right">10GB</span></p>
                                              <div class="progress" style="height:5px;">
                                                  <div class="progress-bar bg-danger" style="width:60%"></div>
                                              </div>
                                          </div>

                                          <div class="progress-wrapper mb-4">
                                              <p>Mails <span class="float-right">5GB</span></p>
                                              <div class="progress" style="height:5px;">
                                                  <div class="progress-bar bg-primary" style="width:40%"></div>
                                              </div>
                                          </div>

                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                  </div><!--End Row-->
                  <div class="row" >
                      <div class="col-12 col-lg-6 col-xl-4"style="display:none;">
                          <div class="card">
                              <div class="card-body">
                                  <p>Total Earning</p>
                                  <h4 class="mb-0">287,493$</h4>
                                  <small>1.4% <i class="zmdi zmdi-long-arrow-up"></i> Since Last Month</small>
                                  <hr>
                                  <p>Total Sales</p>
                                  <h4 class="mb-0">87,493</h4>
                                  <small>5.43% <i class="zmdi zmdi-long-arrow-up"></i> Since Last Month</small>
                                  <div class="mt-5">
                                      <div class="chart-container-4">
                                          <canvas id="chart5"></canvas>
                                      </div>
                                  </div>
                              </div>
                          </div>

                      </div>

                      <div class="col-12 col-lg-12 col-xl-12">
                          <div class="card">
                              <div class="card-header">Customer Review
                              </div>
                              <ul class="list-group list-group-flush">
                                  @foreach($product_review as $review)
                                      <li class="list-group-item">
                                          <div class="media align-items-center">
                                              <div class="media-body ml-3">
                                                  <h6 class="mb-0">{{$review->productname}} <small class="ml-4">{{$review->created_at}}</small></h6>
                                                  <p class="mb-0 small-font">{{$review->name}} : {{$review->review}}.</p>
                                              </div>
                                              @if($review->rating ==5)
                                                  <div class="star">
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star text-warning"></i>
                                                  </div>
                                              @endif
                                              @if($review->rating ==4)
                                                  <div class="star">
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star"></i>
                                                  </div>
                                              @endif
                                              @if($review->rating ==3)
                                                  <div class="star">
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star"></i>
                                                      <i class="fa fa-star"></i>
                                                  </div>
                                              @endif
                                              @if($review->rating ==2)
                                                  <div class="star">
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star"></i>
                                                      <i class="fa fa-star"></i>
                                                      <i class="fa fa-star"></i>
                                                  </div>
                                              @endif
                                              @if($review->rating ==1)
                                                  <div class="star">
                                                      <i class="fa fa-star text-warning"></i>
                                                      <i class="fa fa-star"></i>
                                                      <i class="fa fa-star"></i>
                                                      <i class="fa fa-star"></i>
                                                      <i class="fa fa-star"></i>
                                                  </div>
                                              @endif
                                          </div>
                                      </li>
                                  @endforeach
                              </ul>
                          </div>
                      </div>
                  </div><!--End Row-->
                  <div class="row">
                      <div class="col-12 col-lg-12">
                          <div class="card">
                              <div class="card-header border-0">Recent Order Tables
                                  <!-- <div class="card-action">
                                     <div class="dropdown">
                                     <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                                      <i class="icon-options"></i>
                                     </a>
                                      <div class="dropdown-menu dropdown-menu-right">
                                      <a class="dropdown-item" href="javascript:void();">Action</a>
                                      <a class="dropdown-item" href="javascript:void();">Another action</a>
                                      <a class="dropdown-item" href="javascript:void();">Something else here</a>
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item" href="javascript:void();">Separated link</a>
                                       </div>
                                      </div>
                                     </div> -->
                              </div>
                              <div class="table-responsive">
                                  <table class="table align-items-center table-flush">
                                      <thead>
                                      <tr>
                                          <th>Order ID</th>
                                          <th>User Name</th>
                                          <th>Amount</th>
                                          <th>Status</th>
                                          <th>Date</th>
                                      </tr>
                                      </thead>
                                      <tbody><tr>
                                          @foreach($latestorders as $orders)
                                              <td>{{$orders->id}}</td>
                                              <td>{{$orders->username}}</td>
                                              <td>SR {{$orders->price}}</td>
                                              <td>
                      <span class="badge-dot">
                        <i class="bg-danger"></i> {{$orders->statusname}}
                      </span>
                                              </td>
                                              <td>{{$orders->created_at}}</td>
                                      </tr>
                                      @endforeach
                                      </tbody></table>
                              </div>
                          </div>
                      </div>
                  </div><!--End Row-->
  @endif
      <!--End Dashboard Content-->
@include('includes-file.footer')

<script>
$(function() {
    "use strict";

     // chart 1

		  var ctx = document.getElementById('chart1').getContext('2d');

			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct","Nov","Dec"],
					datasets: [{
						label: 'New Users',
						data: [<?=$monthdata;?>],
						backgroundColor: '#14abef',
						borderColor: "transparent",
						pointRadius :"0",
						borderWidth: 3
					}]
				},
			options: {
				maintainAspectRatio: false,
				legend: {
				  display: false,
				  labels: {
					fontColor: '#585757',
					boxWidth:40
				  }
				},
				tooltips: {
				  displayColors:false
				},
			  scales: {
				  xAxes: [{
					ticks: {
						beginAtZero:true,
						fontColor: '#585757'
					},
					gridLines: {
					  display: true ,
					  color: "rgba(0, 0, 0, 0.05)"
					},
				  }],
				   yAxes: [{
					ticks: {
						beginAtZero:true,
						fontColor: '#585757'
					},
					gridLines: {
					  display: true ,
					  color: "rgba(0, 0, 0, 0.05)"
					},
				  }]
				 }

			 }
      });
			});

</script>


