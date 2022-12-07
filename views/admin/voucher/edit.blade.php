@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Voucher Edit</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/voucher')}}">Voucher Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Voucher Edit</li>
         </ol>
     </div>
      <div class="col-sm-3">
       <a href="{{url('admin/voucher')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
               <form id="addvoucher" action="{{ route('edit_voucher',$voucher->id)}}" enctype="multipart/form-data" method="post">
              @csrf
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Voucher Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('voucher_name') is-invalid @enderror" maxlength="100" id="voucher_name" name="voucher_name" value="{{$voucher->voucher_name}}">
                  </div>
                </div>
                 @error('voucher_name')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> Select User Email</label>
                  <div class="col-sm-10">
                  <select class="form-control single-select" name="user" value="{{old('user')}}">
                          @if(!empty($user))
                          @foreach($user as $key=>$value)
                          <option value="{{$value->id}}"<?php if ($voucher['user_id']==$value->id) {echo 'selected';}?>>{{$value->email}}</option>
                          @endforeach
                          @endif
                      </select>
                    </div>
                </div>

                <div class="form-group row">
                  <label for="input-1" data-toggle="tooltip" title="The code the customer enters to get the discount" class="col-sm-2 col-form-label">Code <i class="fa fa-question-circle" aria-hidden="true" style="color:aqua" ></i></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('code') is-invalid @enderror" maxlength="70" id="code" name="code" value="{{$voucher->code}}">
                  </div>
                </div>
                 @error('code')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Amount</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{$voucher->amount}}">
                  </div>
                </div>
                 @error('amount')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Start Date</label>
                  <div class="col-sm-10">
                    <input type="text" maxlength="50" value="@if(!empty($voucher->start_date)){{$voucher->start_date}}@endif" placeholder="Select Start Date" class="form-control" id="start_date" name="start_date">
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
                    <input type="text" maxlength="50" value="@if(!empty($voucher->end_date)){{$voucher->end_date}}@endif" placeholder="Select End Date" class="form-control" id="end_date" name="end_date">
                  </div>
                </div>
                 @error('end_date')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Uses Per voucher</label>
                  <div class="col-sm-10">
                    <input type="text" maxlength="50" value="@if(old('use_count')){{old('use_count')}}@else{{$voucher->use_count}}@endif" placeholder="Enter Uses Per voucher" class="form-control" id="use_count" name="use_count">
                  </div>
                </div>
                 @error('use_count')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Message</label>
                  <div class="col-sm-10">
                    <textarea class="textarea" class="form-control @error('message') is-invalid @enderror" placeholder=" Message" id='editor' name="message" style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($voucher->message)) {echo $voucher->message;}?></textarea>
                  </div>
                </div>
                 @error('message')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Status</label>
                  <div class="col-sm-10">
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="1"<?php if ($voucher['status']=='1') {echo 'selected';}?>>Enabled</option>
                        <option value="2"<?php if ($voucher['status']=='0') {echo 'selected';}?>>Disabled</option>
                    </select>
                  </div>
                </div>
                 @error('status')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror

                <div class="form-footer">
                    <a href ="{{ url('admin/voucher')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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
        <script>
 CKEDITOR.replace('editor');
</script>
 </body>
</html>

      