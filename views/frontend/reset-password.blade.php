@extends('layouts.front-app')

@section('content')
    <section class="reset-sec pdd-space">
        <div class="container">
            <div class="reset-pass pt-0">
                <!-- ----password-here---- -->
                <div class="modal-reset text-center">
                    <a href="javascript:;" class="modal-logo">
                        <img src="{{ asset('assets/front-end/images/logo.svg') }}">
                    </a>
                    <div class="login-head">
                        <h4>Reset Password</h4>
                    </div>
                    <div class="login-form">
                        <form id="changepassword" action="{{ route('user.changepassword', $user_id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="New Password">
                                @if ($errors->has('password'))
                                    <span id="title-error"
                                        class="error text-danger">{{ $errors->first('password') }}</span>
                                @endif
                                <div id="error-caption">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="confirmpassword" id="confirmpassword"
                                        class="form-control" placeholder="Confirm Password">
                                    @if ($errors->has('confirmpassword'))
                                        <span id="title-error"
                                            class="error text-danger">{{ $errors->first('confirmpassword') }}</span>
                                    @endif
                                    <div id="error-caption1">
                                    </div>
                                    <button type="button" onclick="changePassword();"
                                        class="btn btn-black w-100 waves-effect waves-light">Submit</button>
                        </form>
                    </div>
                </div>
                <!-- ----password-here---- -->
            </div>
        </div>
    </section>

@endsection
