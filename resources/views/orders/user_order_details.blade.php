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
                    <li><a href="{{ url('/orders') }}">Orders</a></li>
                    <li class="active">{{ $orderDetails->id }}</li>
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
            <div class="heading" align="center">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Product Size</th>
                        <th>Product Color</th>
                        <th>Product Price</th>
                        <th>Quantity</th>
                        <th>Sub Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orderDetails->orders as $order)
                        <tr>
                            <td>{{ $order->product_code }}</td>
                            <td>{{ $order->product_name }}</td>
                            <td>{{ $order->product_size }}</td>
                            <td>{{ $order->product_color }}</td>
                            <?php
                                $getCurrencyRates = Product::getCurrencyRates($order->product_price);
                            ?>
                            <td><span class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCurrencyRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCurrencyRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCurrencyRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCurrencyRates['NZD_Rate'] }} <br>">&#8377; {{ $order->product_price }}</span></td>
                            <td>{{ $order->product_qty }}</td>
                            <?php
                                $getTotalPrice = Product::getCurrencyRates(($order->product_price * $order->product_qty));
                            ?>
                            <td><span class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getTotalPrice['USD_Rate'] }} <br> GB&#xa3; {{ $getTotalPrice['GBP_Rate'] }} <br> EU&#x20AC; {{ $getTotalPrice['EUR_Rate'] }} <br> NZ&#x24; {{ $getTotalPrice['NZD_Rate'] }} <br>">&#8377; {{ $order->product_price * $order->product_qty }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Product Size</th>
                        <th>Product Color</th>
                        <th>Product Price</th>
                        <th>Quantity</th>
                        <th>Sub Total</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

@endsection
