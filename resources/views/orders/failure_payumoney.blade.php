@extends('layouts.frontLayout.front_design')

@section('content')

    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Order Failed</li>
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
                    <h3>Payment has been terminated</h3>
                    <p>Don't worry for safety, we have removed all the items to avoid double order. Please try again.</p>
                    <p>Also, no money has been deducted. So free feel to place again.</p>
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
