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
                        <form action="{{ url('/account') }}" id="accountForm" name="accountForm" method="post">
                            {{ csrf_field() }}
                            <input id="name" name="name" type="text" placeholder="Full Name" value="{{ $userDetails->name }}"/>
                            <input id="address" name="address" type="text" placeholder="Address" value="{{ $userDetails->address }}"/>
                            <input id="city" name="city" type="text" placeholder="City" value="{{ $userDetails->city }}"/>
                            <input id="state" name="state" type="text" placeholder="State" value="{{ $userDetails->state }}"/>
                            <select name="country" id="country">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_code }}" @if($country->country_code == $userDetails->country) selected @endif>{{ $country->country_name }}</option>
                                @endforeach
                            </select>
                            <input id="pincode" name="pincode" type="text" placeholder="Pin Code" style="margin-top: 10px;" value="{{ $userDetails->pincode }}"/>
                            <input id="mobile" name="mobile" type="text" placeholder="Mobile" value="{{ $userDetails->mobile }}"/>
                            <button type="submit" class="btn btn-default">Update Profile</button>
                        </form>
                    </div>
                </div>
                <div class="col-sm-1">
                    <h2 class="or">OR</h2>
                </div>
                <div class="col-sm-4">
                    <div class="signup-form">
                        <h2>Update Password</h2>
                        <form action="{{ url('/update-user-pwd') }}" id="passwordForm" name="passwordForm" method="post">
                            {{ csrf_field() }}
                            <input id="current_pwd" name="current_pwd" type="password" placeholder="Current Password"/>
                            <span id="chkPwd"></span>
                            <input id="new_pwd" name="new_pwd" type="password" placeholder="New Password"/>
                            <input id="confirm_pwd" name="confirm_pwd" type="password" placeholder="Confirm New Password"/>
                            <button type="submit" class="btn btn-default">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section><!--/form-->

@endsection
