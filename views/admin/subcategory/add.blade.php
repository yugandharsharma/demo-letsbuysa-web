@include('includes-file.header')
@include('includes-file.sidebar')
  <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Sub Category content Add</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/sub_category')}}">Sub Category content Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sub Category content Add</li>
         </ol>
     </div>
     <div class="col-sm-3">
       <a href="{{url('admin/sub_category')}}">
        <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
       </a>
     </div>
     </div>
       <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <form id="addsubcategory" action="{{ route('add_sub_category')}}" enctype="multipart/form-data" method="post">
              @csrf
                 <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Category Name<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" required="">
                    <option value="">Select category Name</option>
                    @foreach($category as $record)
                    <option value="{{$record->id}}">{{$record->category_name_en}}</option>
                    @endforeach
                  </select>
                  </div>
                </div>
                 @error('category_name_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label"> SubCategory Name(English)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('sub_category_name_en') is-invalid @enderror" maxlength="250" id="sub_category_name_en" name="sub_category_name_en" value="{{old('sub_category_name_en')}}">
                  </div>
                </div>
                 @error('sub_category_name_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">SubCategory Name(Arabic)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('sub_category_name_ar') is-invalid @enderror" maxlength="250" id="sub_category_name_ar" name="sub_category_name_ar" value="{{old('sub_category_name_ar')}}">
                  </div>
                </div>
                 @error('sub_category_name_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror


                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Product Filters</label>
                  <div class="col-sm-10">
                  <select class="form-control multiple-select" multiple="multiple" name="productfilters[]">
                          @if(!empty($filter))
                          @foreach($filter as $key=>$filtervalue)
                          <option value="{{$filtervalue->id}}">{{$filtervalue->filtername}} {{$filtervalue->filter_name_en}}</option>
                          @endforeach
                          @endif
                      </select>
                    </div>
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image:(English)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <input type="file" name="image_en" class="form-control @error('image_en') is-invalid @enderror" value="{{old('image_en')}}">
                  </div>
                @error('image_en')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>

                <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Image:(Arabic)<span style="color: red; font-size: 14px;">*</span></label>
                  <div class="col-sm-10">
                    <input type="file" name="image_ar" class="form-control @error('image_ar') is-invalid @enderror" value="{{old('image_ar')}}">
                  </div>
                @error('image_ar')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror
                </div>
                
                <div class="form-footer">
                    <a href ="{{ url('admin/sub_category')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
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

  @if(session()->has('success'))
  <script>
  round_success_noti_record_create();
  </script>
  @endif 
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
 </body>
</html>

      