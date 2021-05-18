@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Shipping Charges</a> <a href="#" class="current">View Shipping Charges</a> </div>
            <h1>Shipping Charges</h1>
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
                            <h5>View Shipping Charges @if($shipping_charges_count != 0) - {{ $shipping_charges_count }} @endif</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Country Name</th>
                                        <th>Shipping Charges 0 to 500g</th>
                                        <th>Shipping Charges 501 to 1000g</th>
                                        <th>Shipping Charges 1001 to 2000g</th>
                                        <th>Shipping Charges 2001 to 5000g</th>
                                        <th>Updated At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shipping_charges as $shipping_charge)
                                        <tr class="gradeX">
                                            <td>{{ $shipping_charge->id }}</td>
                                            <td>{{ $shipping_charge->country }}</td>
                                            <td>{{ $shipping_charge->shipping_charges_0_500g }}</td>
                                            <td>{{ $shipping_charge->shipping_charges_501_1000g }}</td>
                                            <td>{{ $shipping_charge->shipping_charges_1001_2000g }}</td>
                                            <td>{{ $shipping_charge->shipping_charges_2001_5000g }}</td>
                                            <td><?php echo date('d-M-Y h:i A', strtotime($shipping_charge->updated_at)); ?></td>
                                            <td class="center">
                                                <a href="{{ url('/admin/edit-shipping-charge/' . $shipping_charge->id) }}" class="btn btn-primary btn-mini">Edit</a>
                                                {{-- <a rel="{{ $shipping_charge->id }}" rel1="delete-shipping-charge" rel2="Shipping Charge" href="javascript:" class="btn btn-danger btn-mini deleteRecord">Delete</a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Country Name</th>
                                        <th>Shipping Charges 0 to 500g</th>
                                        <th>Shipping Charges 501 to 1000g</th>
                                        <th>Shipping Charges 1001 to 2000g</th>
                                        <th>Shipping Charges 2001 to 5000g</th>
                                        <th>Updated At</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
