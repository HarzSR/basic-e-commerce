@extends('layouts.frontLayout.front_design')

@section('content')

    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class="active">Order Review</li>
                </ol>
            </div>

            <div class="shopper-informations">
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
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-1">
                        <div class="login-form">
                            <h2>Billing Details</h2>
                            <div class="form-group">
                                {{ $userDetails->name }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->address }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->city }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->state }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->country }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->pincode }}
                            </div>
                            <div class="form-group">
                                {{ $userDetails->mobile }}
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
                                {{ $shippingDetails->name }}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->address }}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->city }}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->state }}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->country }}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->pincode }}
                            </div>
                            <div class="form-group">
                                {{ $shippingDetails->mobile }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="review-payment">
                <h2>Review & Payment</h2>
            </div>

            <div class="table-responsive cart_info">
                <table class="table table-condensed">
                    <thead>
                    <tr class="cart_menu">
                        <td class="image">Item</td>
                        <td class="description"></td>
                        <td class="price">Price</td>
                        <td class="quantity">Quantity</td>
                        <td class="total">Total</td>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $total_amount = 0;?>
                        @foreach($userCart as $cart)
                            <tr>
                                <td class="cart_product">
                                    <a href=""><img src="{{ asset('images/backend_images/products/small/' . $cart->image) }}" alt="" style="width: 80px;"></a>
                                </td>
                                <td class="cart_description">
                                    <h4><a href="">{{ $cart->product_name }}</a></h4>
                                    <p>Web ID: {{ $cart->product_code }} | {{ $cart->size }}</p>
                                </td>
                                <td class="cart_price">
                                    <p>NZ$ {{ $cart->price }}</p>
                                </td>
                                <td class="cart_quantity">
                                    <p>{{ $cart->quantity }}</p>
                                </td>
                                <td class="cart_total">
                                    <p class="cart_total_price">NZ$ {{ $cart->price * $cart->quantity }}</p>
                                </td>
                            </tr>
                            <?php $total_amount = $total_amount + ($cart->price * $cart->quantity); ?>
                        @endforeach
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <td colspan="2">
                                <table class="table table-condensed total-result">
                                    <tr>
                                        <td>Cart Sub Total</td>
                                        <td>NZ$ {{ $total_amount }}</td>
                                    </tr>
                                    <tr>
                                        <td>Discount Amount</td>
                                        <td>@if(!empty(Session::get('couponAmount')))NZ$ {{ Session::get('couponAmount') }} @else NZ$ 0 @endif</td>
                                    </tr>
                                    <tr class="shipping-cost">
                                        <td>Shipping Cost</td>
                                        <td>Free</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td><span>NZ$
                                                <?php
                                                    if($total_amount - Session::get('couponAmount') > 0)
                                                        $grand_total = $total_amount - Session::get('couponAmount');
                                                    else
                                                        $grand_total = 0;
                                                    echo $grand_total;
                                                    ?></span></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <form action="{{ url('/place-order') }}" name="paymentForm" id="paymentForm" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="grand_total" id="grand_total" value="{{ $grand_total }}">
                <div class="payment-options">
                    <span>
                        <label><strong>Select Payment Method : </strong></label>
                    </span>
                    <span>
                        <label><input type="radio" name="payment_method" id="COD" value="COD" required><strong>  COD</strong></label>
                    </span>
                    <span>
                        <label><input type="radio" name="payment_method" id="Paypal" value="Paypal" required><strong>  Paypal</strong></label>
                    </span>
                    <span style="float: right; margin-top: -23px;">
                        <button type="submit" class="btn btn-primary" onclick="return selectPaymentMethod();">Place Order</button>
                    </span>
                </div>
            </form>
        </div>
    </section>

@endsection
