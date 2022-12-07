@include('includes-file.header')
@include('includes-file.sidebar')
<style>
.paging_simple_numbers ul{display:none !important;}
</style>
  <div class="clearfix"></div>

  <div class="content-wrapper">
    <div class="container-fluid">
     <div class="container-fluid">
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Product Review  Tables</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Product Review List</li>
         </ol>
     </div>
     </div>
        <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Product Review List</div>
            <div class="card-body">
              <div class="table-responsive">
             <table id="review-list" class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Product Name</th>
                        <th>User Name</th>
                        <th>Review</th>
                        <th>Rating</th>
                        <th>Approved</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  @if($review)
                    @php
                     $i=1;
                     @endphp
                    @foreach($review as $record)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$record->productname}}</td>
                        <td>{{$record->name}}</td>
                        <td><div style="display: block;width: 60px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">{{$record->review}}</div></td>
                        <td>{{$record->rating}}</td>
                         <!-- <td>
                            <input type="checkbox" data-id="{{ $record->id }}" name="approved" class="js-switch" {{ $record->approved == 1 ? 'checked' : '' }}>
                        </td> -->
                        <td>@if($record->approved)<div class="switchery-demo mt-3">
                                      <input type="checkbox" value="{{$record->approved}}" data-id="{{$record->id}}" name="approved" id="approved" checked class="js-switch" data-color="#02ba5a"/>
                            @else
                            <div class="switchery-demo mt-3">
                                      <input type="checkbox" value="{{$record->approved}}" data-id="{{$record->id}}" name="approved" id="approved" class="js-switch" data-color="#02ba5a"/>
                                    </div>
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()->role === 1)
                          <a href="{{ url('/')}}/admin/review/view/{{$record->id}}">
                          <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-eye"></i> </button></a>
                           @endif
                            @php $permission = permission(); @endphp

                                @if(auth()->user()->role === 3 && isset($permission['product_reviews']) && $permission['product_reviews']['add'] === 1)
                                    <a href="{{ url('/')}}/admin/review/view/{{$record->id}}">
                                        <button type="button" class="btn btn-success waves-effect waves-light m-1"> <i class="fa fa-eye"></i> </button></a>
                                @endif


                        </td>
                    </tr>
                     @php
                    $i++;
                    @endphp
                   @endforeach
                 @endif
                </tbody>
            </table>
            {{ $review->appends(request()->except('page'))->links() }}

            </div>
            </div>
          </div>
        </div>
      </div>
  </div>
            </div>

@include('includes-file.footer')
  @if(session()->has('info'))
  <script>
  round_info_noti_delete();
  </script>
  @endif
  	<script>
     $(document).ready(function() {
      //Default data table
       $('#default-datatable').DataTable();


       var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ]
      } );

     table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );

      } );
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
      $('#list').DataTable({"order": [[ 7, "desc" ]]});
    });
    </script>
    <script>
    $(document).ready(function(){
          $(".alert").slideDown(300).delay(5000).slideUp(300);
    });

       var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
      $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
       });
  </script>
  <script>
    $(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
    var radioswitch = function() {
        var bt = function() {
            $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioState")
            }), $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck")
            }), $(".radio-switch").on("switch-change", function() {
                $(".radio-switch").bootstrapSwitch("toggleRadioStateAllowUncheck", !1)
            })
        };
        return {
            init: function() {
                bt()
            }
        }
    }();
    $(document).ready(function() {
        radioswitch.init()
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
      $('#review-list').on('change', '#approved', function(){
        if (this.value == '1') {
          this.value = '0';
           updateSubCategoryStatus(this.value, $(this).attr("data-id"));
        } else {
          this.value = '1';
          updateSubCategoryStatus(this.value, $(this).attr("data-id"));
        }
      });
    });

    function updateSubCategoryStatus($status, $id){
      $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{ route('product_review_status') }}',
                data: {'status': $status, 'review_id': $id},
                success: function (data) {
                    //alert(data.status);
                    toastr.options.closeButton = true;
                    toastr.options.closeMethod = 'fadeOut';
                    toastr.options.closeDuration = 100;
                    toastr.success(data.message);
                }
            });
    }
  </script> 

 </body>
</html>



