@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Coupons</a> <a href="#" class="current">View Coupons</a> </div>
            <h1>Coupons</h1>
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
                            <h5>View Coupons @if($couponCount != 0) - {{ $couponCount }} @endif</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>Coupon ID</th>
                                        <th>Code</th>
                                        <th>Discount Type</th>
                                        <th>Created Date</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($coupons as $coupon)
                                    <tr class="gradeX">
                                        <td>{{ $coupon->id }}</td>
                                        <td>{{ $coupon->coupon_code }}</td>
                                        <td>
                                            @if($coupon->amount_type == 'Percentage')
                                                {{ $coupon->amount }} %
                                            @else
                                                NZ$ {{ $coupon->amount }}
                                            @endif
                                        </td>
                                        <td><?php echo date('d-M-Y h:i A', strtotime($coupon->created_at)); ?></td>
                                        <td><?php echo date('d-M-Y h:i A', strtotime($coupon->expiry_date)); ?></td>
                                        <td>
                                            @if($coupon->status == 1)
                                                <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Active</button>
                                            @else
                                                <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">In-Active</button>
                                            @endif
                                        </td>
                                        <td class="center">
                                            <a href="{{ url('/admin/edit-coupon/' . $coupon->id) }}" class="btn btn-primary btn-mini" title="Edit coupon">Edit</a>
                                            <a rel="{{ $coupon->id }}" rel1="delete-coupon" rel2="coupon" href="javascript:" class="btn btn-danger btn-mini deleteRecord" title="Delete coupon">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <th>Coupon ID</th>
                                    <th>Code</th>
                                    <th>Discount Type</th>
                                    <th>Created Date</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
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
