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
                    <li class="active">Orders</li>
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
                            <th>Order ID</th>
                            <th>Ordered Products</th>
                            <th>Payment Method</th>
                            <th>Shipping Charges</th>
                            <th>Grand Total</th>
                            <th>Created On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>
                                    @foreach($order->orders as $product)
                                        {{ $product->product_name }} | {{ $product->product_code }} | {{ $product->product_size }} | {{ $product->product_color }} <br>
                                    @endforeach
                                </td>
                                <td>{{ $order->payment_method }}</td>
                                <?php
                                    $getShippingRates = Product::getCurrencyRates($order->shipping_charges);
                                ?>
                                @if($order->shipping_charges != 0)
                                    <td><span class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getShippingRates['USD_Rate'] }} <br> GB&#xa3; {{ $getShippingRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getShippingRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getShippingRates['NZD_Rate'] }} <br>">&#8377; {{ $order->shipping_charges }}</span></td>
                                @else
                                    <td>Free</td>
                                @endif
                                <?php
                                    $getCurrencyRates = Product::getCurrencyRates($order->grand_total);
                                ?>
                                <td><span class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCurrencyRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCurrencyRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCurrencyRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCurrencyRates['NZD_Rate'] }} <br>">&#8377; {{ $order->grand_total }}</span></td>
                                <td>{{ date("F jS, Y h:m A", strtotime($order->created_at)) }}</td>
                                <td><a href="{{ url('/orders/' . $order->id) }}" class="btn btn-primary btn-mini" title="Edit Product" style="margin-top: 0px;">View Order</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Order ID</th>
                            <th>Ordered Products</th>
                            <th>Payment Method</th>
                            <th>Shipping Charges</th>
                            <th>Grand Total</th>
                            <th>Created On</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

@endsection
