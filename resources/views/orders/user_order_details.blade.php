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
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orderDetails->orders as $order)
                        <tr>
                            <td>{{ $order->product_code }}</td>
                            <td>{{ $order->product_name }}</td>
                            <td>{{ $order->product_size }}</td>
                            <td>{{ $order->product_color }}</td>
                            <td>{{ $order->product_price }}</td>
                            <td>{{ $order->product_qty }}</td>
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
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>

@endsection
