<?php
    use App\Product;
?>

@extends('layouts.frontLayout.front_design')

@section('content')

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
                    <?php
                        $getCartCurrencyRates = Product::getCurrencyRates(Session::get('grand_total'));
                    ?>
                    <p>Order ID #{{ Session::get('order_id') }} and Payable Amount is <span class="cart_total_price btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCartCurrencyRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCartCurrencyRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCartCurrencyRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCartCurrencyRates['NZD_Rate'] }} <br>">&#8377; {{ Session::get('grand_total') }}</span></p>
                </div>
                {{ Session::forget('order_id') }} {{ Session::forget('grand_total') }} {{ Session::forget('session_id') }} {{ Session::forget('couponCode') }} {{ Session::forget('couponAmount') }}
            @else
                <div class="heading" align="center">
                    <h3>No New Orders Placed</h3>
                    <p>Please check your <a href="{{ url('/orders') }}">Orders page</a> for Order Details.</p>
                </div>
            @endif
        </div>
    </section>

@endsection
