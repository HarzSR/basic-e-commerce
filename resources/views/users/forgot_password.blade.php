@extends('layouts.frontLayout.front_design')

@section('content')

    <section id="form" style="margin-top: 20px;"><!--form-->
        <div class="container">
            <div class="row">
                @if(Session::has('flash_message_error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! session('flash_message_error') !!}</strong>
                    </div>
                @endif
                @if(Session::has('flash_message_success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! session('flash_message_success') !!}</strong>
                    </div>
                @endif
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="login-form">
                        <h2>Forgot Password</h2>
                        <form action="{{ url('/forgot-password') }}" id="forgotPasswordForm" name="forgotPasswordForm" method="post">
                            {{ csrf_field() }}
                            <input id="email" name="email" type="email" placeholder="Email Address" required/>
                            <button type="submit" class="btn btn-default" style="margin-top: 0px;">Verify & Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
