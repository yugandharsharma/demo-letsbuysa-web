@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Coupon Edit</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/coupon')}}">Coupon Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Coupon Edit</li>
         </ol>
     </div>
      <div class="col-sm-3">
       <a href="{{url('admin/coupon')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
               <form id="addcoupon" action="{{ route('edit_coupon',$coupon->id)}}" enctype="multipart/form-data" method="post">
              @csrf
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Coupon Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('coupon_name') is-invalid @enderror" maxlength="100" id="coupon_name" name="coupon_name" value="{{$coupon->coupon_name}}">
                  </div>
                </div>
                 @error('coupon_name')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" data-toggle="tooltip" title="The code the customer enters to get the discount" class="col-sm-2 col-form-label">Code <i class="fa fa-question-circle" aria-hidden="true" style="color:aqua" ></i></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('code') is-invalid @enderror" maxlength="70" id="code" name="code" value="{{$coupon->code}}">
                  </div>
                </div>
                 @error('code')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" data-toggle="tooltip" title="Percentage,Fixed Amount" class="col-sm-2 col-form-label">Type <i class="fa fa-question-circle" style="color:aqua"  aria-hidden="true"></i></label>
                  <div class="col-sm-10">
                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
                        <option value="1"<?php if ($coupon['type']=='1') {echo 'selected';}?>>Percentage</option>
                        <option value="2"<?php if ($coupon['type']=='2') {echo 'selected';}?>>Fixed Amount</option>
                    </select>
                  </div>
                </div>
                 @error('type')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Discount</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" value="{{$coupon->discount}}">
                  </div>
                </div>
                 @error('discount')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" data-toggle="tooltip" title="The Total Amount That Must Be Reached Before The Coupan Is Valid" class="col-sm-2 col-form-label">Total Amount <i class="fa fa-question-circle" aria-hidden="true" style="color:aqua" ></i></label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" value="{{$coupon->total_amount}}">
                  </div>
                </div>
                 @error('total_amount')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" data-toggle="tooltip" title="Choose specific products the coupan will apply to. Select no products to apply coupan to entire cart." class="col-sm-2 col-form-label">Subcategories <i class="fa fa-question-circle" aria-hidden="true" style="color:aqua" ></i></label>
                  <div class="col-sm-10">
                  <select class="form-control multiple-select" multiple="multiple" name="subcategories[]">
                          @if(!empty($Subcategory))
                          @foreach($Subcategory as $key=>$value)
                          <option value="{{$value->id}}" <?php if(in_array($value->id, explode(',',$coupon->subcategories))){echo 'selected';} ?>>Collect Of {{$value->sub_category_name_en}}</option>
                          @endforeach
                          @endif
                      </select>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="input-1" data-toggle="tooltip" title="Choose all products under selected category" class="col-sm-2 col-form-label">Categories <i class="fa fa-question-circle" aria-hidden="true" style="color:aqua" ></i></label>
                  <div class="col-sm-10">
                  <select class="form-control multiple-select" multiple="multiple" name="categories[]">
                          @if(!empty($Category))
                          @foreach($Category as $key=>$value)
                          <option value="{{$value->id}}"<?php if(in_array($value->id, explode(',',$coupon->categories))){echo 'selected';} ?>>{{$value->category_name_en}}</option>
                          @endforeach
                          @endif
                      </select>
                    </div>
                </div>
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Start Date</label>
                  <div class="col-sm-10">
                    <input type="text" maxlength="50" value="@if(!empty($coupon->start_date)){{$coupon->start_date}}@endif" placeholder="Select Start Date" class="form-control" id="start_date" name="start_date">
                  </div>
                </div>
                 @error('total_amount')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">End Date</label>
                  <div class="col-sm-10">
                    <input type="text" maxlength="50" value="@if(!empty($coupon->end_date)){{$coupon->end_date}}@endif" placeholder="Select End Date" class="form-control" id="end_date" name="end_date">
                  </div>
                </div>
                 @error('end_date')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Uses Per Coupon</label>
                  <div class="col-sm-10">
                    <input type="text" maxlength="50" value="@if(old('uses_per_coupon')){{old('uses_per_coupon')}}@else{{$coupon->uses_per_coupon}}@endif" placeholder="Enter Uses Per Customer" class="form-control" id="uses_per_coupon" name="uses_per_coupon">
                  </div>
                </div>
                 @error('uses_per_coupon')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Uses Per Customer</label>
                  <div class="col-sm-10">
                    <input type="text" maxlength="50" value="@if(old('uses_per_coupon')){{old('uses_per_customer')}}@else{{$coupon->uses_per_customer}}@endif" placeholder="Enter Uses Per Customer" class="form-control" id="uses_per_customer" name="uses_per_customer" value="1">
                  </div>
                </div>
                 @error('uses_per_customer')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" data-toggle="tooltip" title="Customer must be logged in to use the coupan" class="col-sm-2 col-form-label">Customer Login <i class="fa fa-question-circle" aria-hidden="true" style="color:aqua" ></i></label>
                  <div class="col-sm-10">
                    <select class="form-control @error('customerlogin') is-invalid @enderror" id="customerlogin" name="customerlogin">
                        <option value="1"<?php if ($coupon['customerlogin']=='1') {echo 'selected';}?>>Yes</option>
                        <option value="2"<?php if ($coupon['customerlogin']=='2') {echo 'selected';}?>>no</option>
                    </select>
                  </div>
                </div>
                 @error('customerlogin')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Status</label>
                  <div class="col-sm-10">
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="1"<?php if ($coupon['status']=='1') {echo 'selected';}?>>Enabled</option>
                        <option value="2"<?php if ($coupon['status']=='2') {echo 'selected';}?>>Disabled</option>
                    </select>
                  </div>
                </div>
                 @error('status')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-footer">
                    <a href ="{{ url('admin/coupon')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> SAVE</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
  </div>
  </div>

@include('includes-file.footer')
<script src="{{ asset('assets/plugins/jquery-multi-select/jquery.multi-select.js')}}"></script>
<script src="{{ asset('assets/plugins/jquery-multi-select/jquery.quicksearch.js')}}"></script>
  @if(session()->has('success'))
  <script>
  round_success_noti_record_update();
  </script>
  @endif 
   <script>
   function CheckDate(){
      var startdate=document.getElementById('start_date').value;
      var enddate=document.getElementById('end_date').value;
      var sd = Date.parse(startdate);
      var ed = Date.parse(enddate);
      if(ed<sd){
        alert('Reverse Date Formate Not Allowed');
        return false;
      }
      else{
        document.getElementById("filterdata").submit();
      }
    }
    $('#start_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        startDate: new Date()

      });
    $('#end_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        startDate: new Date()

      });
      </script>
<script>
        $(document).ready(function() {
            $('.single-select').select2();
      
            $('.multiple-select').select2();

        //multiselect start

            $('#my_multi_select1').multiSelect();
            $('#my_multi_select2').multiSelect({
                selectableOptgroup: true
            });

            $('#my_multi_select3').multiSelect({
                selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='search...'>",
                afterInit: function (ms) {
                    var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                        .on('keydown', function (e) {
                            if (e.which === 40) {
                                that.$selectableUl.focus();
                                return false;
                            }
                        });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                        .on('keydown', function (e) {
                            if (e.which == 40) {
                                that.$selectionUl.focus();
                                return false;
                            }
                        });
                },
                afterSelect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });

         $('.custom-header').multiSelect({
              selectableHeader: "<div class='custom-header'>Selectable items</div>",
              selectionHeader: "<div class='custom-header'>Selection items</div>",
              selectableFooter: "<div class='custom-header'>Selectable footer</div>",
              selectionFooter: "<div class='custom-header'>Selection footer</div>"
            });


          });

    </script>
    <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
    });
    </script>
 </body>
</html>

      