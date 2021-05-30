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
                        <td class="total" style="text-align: center;">Total</td>
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
                                    <?php
                                        $product_price = Product::getProductPrice($cart->product_id, $cart->size);
                                        $getItemPrice = Product::getCurrencyRates($product_price);
                                    ?>
                                    <p class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getItemPrice['USD_Rate'] }} <br> GB&#xa3; {{ $getItemPrice['GBP_Rate'] }} <br> EU&#x20AC; {{ $getItemPrice['EUR_Rate'] }} <br> NZ&#x24; {{ $getItemPrice['NZD_Rate'] }} <br>">&#8377; {{ $product_price }}</p>
                                </td>
                                <td class="cart_quantity">
                                    <p>{{ $cart->quantity }}</p>
                                </td>
                                <td class="cart_total" style="text-align: right; padding-right: 150px;">
                                    <?php
                                        $getCartCurrencyRates = Product::getCurrencyRates($cart->quantity * $product_price);
                                    ?>
                                    <p class="cart_total_price btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCartCurrencyRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCartCurrencyRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCartCurrencyRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCartCurrencyRates['NZD_Rate'] }} <br>">&#8377; {{ $cart->quantity * $product_price }}</p>
                                </td>
                            </tr>
                            <?php $total_amount = $total_amount + ($product_price * $cart->quantity); ?>
                        @endforeach
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <td colspan="2">
                                <table class="table table-condensed total-result">
                                    <tr>
                                        <?php
                                            $getCartRates = Product::getCurrencyRates($total_amount);
                                        ?>
                                        <td>Cart Sub Total</td>
                                        <td style="text-align: right; padding-right: 150px;" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCartRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCartRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCartRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCartRates['NZD_Rate'] }} <br>">&#8377; <?php echo $total_amount; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Discount Amount</td>
                                        @if(!empty(Session::get('couponAmount')))
                                            <?php
                                                $getCouponRates = Product::getCurrencyRates(Session::get('couponAmount'));
                                            ?>
                                            <td style="text-align: right; padding-right: 150px;" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCouponRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCouponRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCouponRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCouponRates['NZD_Rate'] }} <br>">
                                                &#8377; {{ Session::get('couponAmount') }}
                                            </td>
                                        @else
                                            0
                                        @endif
                                    </tr>
                                    <tr class="shipping-cost">
                                        <td>Shipping Cost</td>
                                        <?php
                                            $getShippingRates = Product::getCurrencyRates($shippingCharges);
                                        ?>
                                        <td style="text-align: right; padding-right: 150px;" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getShippingRates['USD_Rate'] }} <br> GB&#xa3; {{ $getShippingRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getShippingRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getShippingRates['NZD_Rate'] }} <br>">
                                        &#8377; {{ intval($shippingCharges) }}
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td style="text-align: right; padding-right: 150px;">
                                            <?php
                                                if($total_amount - Session::get('couponAmount') > 0)
                                                    $grand_total = $total_amount - Session::get('couponAmount') + $shippingCharges;
                                                else
                                                    $grand_total = 0 + $shippingCharges;
                                                $getCurrencyRates = Product::getCurrencyRates($grand_total);
                                            ?>
                                            <span class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCurrencyRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCurrencyRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCurrencyRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCurrencyRates['NZD_Rate'] }} <br>">
                                                    &#8377; <?php echo $grand_total; ?>
                                            </span>
                                        </td>
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
                    @if($codPincodeCount > 0)
                        <span>
                            <label><input type="radio" name="payment_method" id="COD" value="COD" required><strong>  COD</strong></label>
                        </span>
                    @endif
                    @if($prepaidPincodeCount > 0)
                        <span>
                            <label><input type="radio" name="payment_method" id="Paypal" value="Paypal" required><strong>  Paypal</strong></label>
                        </span>
                    @endif
                    <span style="float: right; margin-top: -23px;">
                        <button type="submit" class="btn btn-primary" onclick="return selectPaymentMethod();">Place Order</button>
                    </span>
                </div>
            </form>
        </div>
    </section>

@endsection
