@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="{{ url('/admin/view-shipping-charges') }}">Shipping Charges</a> <a href="#" class="current">Edit Shipping Charge</a> </div>
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
        <div class="container-fluid"><hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                            <h5>Edit Shipping Charge</h5>
                        </div>
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="{{ url('/admin/edit-shipping-charge/' . $shipping_charge->id) }}" name="edit_shipping_charge" id="edit_shipping_charge" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="control-group">
                                    <input type="hidden" name="id" id="id" value="{{ $shipping_charge->id }}">
                                    <label class="control-label">Currency Code</label>
                                    <div class="controls">
                                        <input type="text" name="country" id="country" value="{{ $shipping_charge->country }}" readonly style="cursor: default;">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Exchange Rate</label>
                                    <div class="controls">
                                        <input type="text" autofocus name="shipping_charges" id="shipping_charges" value="@if(!empty(old('shipping_charges'))) {{ old('shipping_charges') }} @else {{ $shipping_charge->shipping_charges }} @endif">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" value="Update Shipping Charges" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
