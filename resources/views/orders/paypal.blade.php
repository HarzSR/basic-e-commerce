@extends('layouts.frontLayout.front_design')

@section('content')

    <?php use App\Order; ?>

    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Order Confirmation</li>
                </ol>
            </div>
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
    </section>

    <section id="do_action">
        <div class="container">
            @if(!empty(Session::has('order_id') && !empty(Session::has('grand_total'))))
                <div class="heading" align="center">
                    <h3>Your Order has been placed Successfully</h3>
                    <p>Order ID #{{ Session::get('order_id') }} and Payable Amount is NZ$ {{ Session::get('grand_total') }}</p>
                    <p>Please make payment via the following link</p>

                    <?php
                        $orderDetails = Order::getOrderDetails(Session::get('order_id'));
                        $getCountryCode = Order::getCountryCode($orderDetails->country);
                    ?>

                    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="cmd" value="_xclick">
                        <input type="hidden" name="business" value="sb-iyrkp5895451@business.example.com">
                        <input type="hidden" name="item_name" value="{{ Session::get('order_id') }}">
                        <INPUT TYPE="hidden" name="currency_code" value="NZD">
                        <input type="hidden" name="amount" value="{{ Session::get('grand_total') }}">
                        <input type="hidden" name="first_name" value="{{ $orderDetails->name }}">
                        <input type="hidden" name="last_name" value="{{ $orderDetails->name }}">
                        <input type="hidden" name="address1" value="{{ $orderDetails->address }}">
                        <input type="hidden" name="address2" value="{{ $orderDetails->address }}">
                        <input type="hidden" name="city" value="{{ $orderDetails->city }}">
                        <input type="hidden" name="state" value="{{ $orderDetails->state }}">
                        <input type="hidden" name="zip" value="{{ $orderDetails->pincode }}">
                        <input type="hidden" name="country" value="{{ $getCountryCode->country_code }}">
                        <input type="hidden" name="email" value="{{ $orderDetails->user_email }}">
                        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                    </form>
                </div>
            @else
                <div class="heading" align="center">
                    <h3>No New Orders Placed</h3>
                    <p>Please check your <a href="{{ url('/orders') }}">Orders page</a> for Order Details.</p>
                </div>
            @endif
        </div>
    </section>

@endsection
