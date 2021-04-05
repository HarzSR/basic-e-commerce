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
                        <h2>Update Account</h2>
                        <form action="{{ url('/user-login') }}" id="loginForm" name="loginForm" method="post">
                            {{ csrf_field() }}
                            <input id="email" name="email" type="email" placeholder="Email Address" />
                            <input id="loginPassword" name="loginPassword" type="password" placeholder="Password"/>
                            <button type="submit" class="btn btn-default">Login</button>
                        </form>
                    </div><!--/login form-->
                </div>
                <div class="col-sm-1">
                    <h2 class="or">OR</h2>
                </div>
                <div class="col-sm-4">
                    <div class="signup-form">
                        <h2>Update Password</h2>
                        <form action="{{ url('/user-register') }}" id="registerForm" name="registerForm" method="post">
                            {{ csrf_field() }}
                            <input id="name" name="name" type="text" placeholder="Full Name"/>
                            <input id="email" name="email" type="email" placeholder="Email Address"/>
                            <input id="registerPassword" name="registerPassword" type="password" placeholder="Password"/>
                            <button type="submit" class="btn btn-default">Signup</button>
                        </form>
                    </div><!--/sign up form-->
                </div>
            </div>
        </div>
    </section><!--/form-->

@endsection
