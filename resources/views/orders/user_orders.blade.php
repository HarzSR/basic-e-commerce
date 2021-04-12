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
                                <td>NZ$ {{ $order->grand_total }}</td>
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
