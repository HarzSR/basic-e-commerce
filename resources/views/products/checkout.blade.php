@extends('layouts.frontLayout.front_design')

@section('content')

    <section id="form" style="margin-top: 20px;"><!--form-->
        <div class="container">
            <form action="#">
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-1">
                        <div class="login-form">
                            <h2>Bill To</h2>
                            <div class="form-group">
                                <input type="text" name="billing_name" id="billing_name" placeholder="Billing Name" class="form-control" value="{{ $userDetails->name }}"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="billing_address" id="billing_address" placeholder="Billing Address" class="form-control" value="{{ $userDetails->address }}"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="billing_city" id="billing_city" placeholder="Billing City" class="form-control" value="{{ $userDetails->city }}"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="billing_state" id="billing_state" placeholder="Billing State" class="form-control" value="{{ $userDetails->state }}"/>
                            </div>
                            <div class="form-group">
                                <select name="billing_country" id="billing_country" class="form-control">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->country_name }}" @if($country->country_name == $userDetails->country) selected @endif>{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" name="billing_pincode" id="billing_pincode" placeholder="Billing Pincode" class="form-control" value="{{ $userDetails->pincode }}"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="billing_mobile" id="billing_mobile" placeholder="Mobile" class="form-control" value="{{ $userDetails->mobile }}"/>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="billToShip">
                                <label class="form-check-label" for="materialUnchecked">Shipping Details same as Billing Details</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <h2></h2>
                    </div>
                    <div class="col-sm-4">
                        <div class="signup-form">
                            <h2>Ship To</h2>
                            <div class="form-group">
                                <input type="text" name="shipping_name" id="shipping_name" placeholder="Shipping Name" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="shipping_address" id="shipping_address" placeholder="Shipping Address" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="shipping_city" id="shipping_city" placeholder="Shipping City" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="shipping_state" id="shipping_state" placeholder="Shipping State" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <select name="shipping_country" id="shipping_country" class="form-control">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->country_name }}">{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" name="shipping_pincode" id="shipping_pincode" placeholder="Shipping Pincode" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="shipping_mobile" id="shipping_mobile" placeholder="Mobile" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section><!--/form-->

@endsection
