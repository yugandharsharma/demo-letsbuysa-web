@include('includes-file.header')
@include('includes-file.sidebar')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Notification</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Notification</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="mail_marketing" action="{{ route('post_notification') }}"
                            enctype="multipart/form-data" method="post">
                            @csrf

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">From</label>
                                <div class="col-sm-10">
                                    <select class="form-control @error('from') is-invalid @enderror" id="from"
                                        name="from">
                                        <option value="1">LETS BUY</option>
                                    </select>
                                </div>
                            </div>
                            @error('from')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label" id="emails">Users</label>
                                <div class="col-sm-10" id="customer">
                                    <select class="form-control multiple-select" id="customermultiple" name="emails[]"
                                        multiple>
                                        @foreach ($user as $record)
                                            <option value="{{ $record->email }}">{{ $record->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('to')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror



                            {{-- <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Subject</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" value="{{old('subject')}}" maxlength="150" id="subject" name="subject">
                  </div>
                </div>
                 @error('subject')
                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                <strong>{{ $message }}</strong>
                </span>
                @enderror --}}
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" maxlength="150" id="title" name="title">
                                </div>
                            </div>
                            @error('subject')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Message</label>
                                <div class="col-sm-10">
                                    <textarea class="textarea" value="{{ old('description') }}"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder=" Description" id='editor1' name="description"
                                        style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                </div>
                            </div>
                            @error('description')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-footer">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> SEND
                                    NOTIFICATION</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes-file.footer')

@if (session()->has('success'))
    <script>
        Lobibox.notify('success', {
            pauseDelayOnHover: true,
            size: 'mini',
            rounded: true,
            icon: 'fa fa-check-circle',
            delayIndicator: false,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            msg: 'Notification Sent Successfully!'
        });
    </script>
@endif
@if (session()->has('error'))
    <script>
        Lobibox.notify('error', {
            pauseDelayOnHover: true,
            size: 'mini',
            rounded: true,
            icon: 'fa fa-check-circle',
            delayIndicator: false,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            msg: 'Please Select Email!'
        });
    </script>
@endif
<script>
    $(document).ready(function() {
        $('.multiple-select').select2();
        customers();

    });
</script>
<script>
    $('#editor1').summernote({
        height: 400,
        tabsize: 2
    });
</script>

</body>

</html>
