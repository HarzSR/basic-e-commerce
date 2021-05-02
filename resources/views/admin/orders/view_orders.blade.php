@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Orders</a> <a href="#" class="current">View Orders</a> </div>
            <h1>Orders</h1>
            @if(Session::has('flash_message_error'))
                <div class="alert alert-error alert-block">
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
        </div>
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>View Orders @if($orderCount != 0) - {{ $orderCount }} @endif</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Order Date</th>
                                        <th>Customer Name</th>
                                        <th>Customer Email</th>
                                        <th>Order Products</th>
                                        <th>Order Amount</th>
                                        <th>Order Status</th>
                                        <th>Payment Method</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr class="gradeX">
                                            <td>{{ $order->id }}</td>
                                            <td><?php echo date('d-M-Y h:i A', strtotime($order->created_at)); ?></td>
                                            <td>{{ $order->name }}</td>
                                            <td>{{ $order->user_email }}</td>
                                            <td>
                                                @foreach($order->orders as $product)
                                                    {{ $product->product_name }} | {{ $product->product_code }} | {{ $product->product_size }} | {{ $product->product_color }} | {{ $product->product_qty }}<br>
                                                @endforeach
                                            </td>
                                            <td>{{ $order->grand_total }}</td>
                                            <td>{{ $order->order_status }}</td>
                                            <td>{{ $order->payment_method }}</td>
                                            <td class="center">
                                                <a href="{{ url('/admin/view-order/' . $order->id) }}" class="btn btn-success btn-mini" title="View Description">View Order Details</a>
                                                <a href="{{ url('/admin/view-order-invoice/' . $order->id) }}" target="_blank" class="btn btn-primary btn-mini" title="View Description">View Order Invoice</a>
                                                <a href="{{ url('/admin/print-order-invoice/' . $order->id) }}" target="_blank" class="btn btn-info btn-mini" title="View Description">Print Order Invoice</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th>Order ID</th>
                                    <th>Order Date</th>
                                    <th>Customer Name</th>
                                    <th>Customer Email</th>
                                    <th>Order Products</th>
                                    <th>Order Amount</th>
                                    <th>Order Status</th>
                                    <th>Payment Method</th>
                                    <th>Actions</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
