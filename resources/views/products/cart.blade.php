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
                    <li class="active">Shopping Cart</li>
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
            @if(!empty($userCart) && count($userCart) > 0)
                <div class="table-responsive cart_info">
                    <table class="table table-condensed">
                        <thead>
                            <tr class="cart_menu">
                                <td class="image">Item</td>
                                <td class="description"></td>
                                <td class="price">Price</td>
                                <td class="quantity">Quantity</td>
                                <td class="total">Total</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total_amount = 0; ?>
                            @foreach($userCart as $cart)
                                <tr>
                                    <td class="cart_product">
                                        <a href="{{ url('product/' . $cart->product_id) }}"><img src="{{ asset('images/backend_images/products/small/' . $cart->image) }}" alt="" style="width: 50px"></a>
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
                                        <div class="cart_quantity_button">
                                            @if($cart->quantity > 1)
                                                <a class="cart_quantity_down" href="{{ url('cart/update-quantity/' . $cart->id . '/-1') }}"> - </a>
                                            @endif
                                            <input class="cart_quantity_input" type="text" name="quantity" value="{{ $cart->quantity }}" autocomplete="off" size="2">
                                            <a class="cart_quantity_up" href="{{ url('cart/update-quantity/' . $cart->id . '/1') }}"> + </a>
                                        </div>
                                    </td>
                                    <td class="cart_total">
                                        <?php
                                            $getCartCurrencyRates = Product::getCurrencyRates($cart->quantity * $product_price);
                                        ?>
                                        <p class="cart_total_price btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCartCurrencyRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCartCurrencyRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCartCurrencyRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCartCurrencyRates['NZD_Rate'] }} <br>">&#8377; {{ $cart->quantity * $product_price }}</p>
                                    </td>
                                    <td>
                                        <form action="{{ url('/add-cart') }}" name="addToCartForm" id="addToCartForm" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="product_id" id="product_id" value="{{ $cart->product_id }}">
                                            <input type="hidden" name="product_name" id="product_name" value="{{ $cart->product_name }}">
                                            <input type="hidden" name="product_code" id="product_code" value="{{ $cart->product_code }}">
                                            <input type="hidden" name="product_color" id="product_color" value="{{ $cart->product_color }}">
                                            <input type="hidden" name="size" id="size" value="{{ $cart->product_id . '-' . $cart->size }}">
                                            <input type="hidden" name="quantity" id="quantity" value="{{ $cart->quantity }}">
                                            <input type="hidden" name="price" id="price" value="{{ $cart->quantity * $cart->price }}">
                                            <button type="submit" class="btn btn-fefault cart" id="wishListButton" name="wishListButton" value="Move Wish List" style="float: right; margin-bottom: 0px;">
                                                <i class="fa fa-shopping-cart"></i>
                                                Move to Wish List
                                            </button>
                                        </form>
                                    </td>
                                    <td class="cart_delete">
                                        <a class="cart_quantity_delete" href="{{ url('cart/delete-product/' . $cart->id) }}" onclick="return confirm('Would you like to delete {{ $cart->product_name }} - {{ $cart->size }}?')"><i class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                                <?php
                                    $total_amount = $total_amount + ($cart->quantity * $product_price);
                                ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div>
                    <a href="{{ url('/') }}" style="cursor: default"><img src="{{ asset('images/backend_images/empty-cart.png') }}" alt="" class="center-block"></a>
                    <div class="container text-center">
                        <div class="content-404">
                            <h2><a href="{{ asset('/') }}">Let's Shop for some goodies</a></h2>
                            <br>
                            <br>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section id="do_action">
        @if(!empty($userCart) && count($userCart) > 0)
            <div class="container">
                <div class="heading">
                    <h3>What would you like to do next?</h3>
                    <p>Choose if you have a coupon code that you would like to use.</p>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="chose_area">
                            <ul class="user_option">
                                <li>
                                    <form action="{{ url('cart/apply-coupon') }}" method="post">
                                        {{ csrf_field() }}
                                        <label>Use Coupon Code</label>
                                        <input type="text" name="coupon_code" id="coupon_code">
                                        <input type="submit" value="Apply" class="btn btn-success btn-mini">
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="total_area">
                            <ul>
                                @if(!empty(Session::get('couponAmount')))
                                    <?php
                                        $getCartRates = Product::getCurrencyRates($total_amount);
                                    ?>
                                    <li>Cart Sub Total <span class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCartRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCartRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCartRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCartRates['NZD_Rate'] }} <br>">&#8377; <?php echo $total_amount; ?></span></li>
                                    <?php
                                        $getCouponRates = Product::getCurrencyRates(Session::get('couponAmount'));
                                    ?>
                                    <li>Discount <span class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCouponRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCouponRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCouponRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCouponRates['NZD_Rate'] }} <br>">&#8377; <?php echo Session::get('couponAmount'); ?> | <a class="cart_quantity_delete" href="{{ url('cart/remove-coupon/') }}" onclick="return confirm('Would you like to remove Coupon?')"><i class="fa fa-times"></i></a></span></li>
                                    <li>Shipping Cost <span>Yet to Calculate</span></li>
                                    <?php
                                        if ($total_amount - Session::get('couponAmount') < 0)
                                            $total_amount = 0;
                                        else
                                            $total_amount = $total_amount - Session::get('couponAmount');
                                        $getCurrencyRates = Product::getCurrencyRates($total_amount);
                                    ?>
                                    <li>Total <span class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCurrencyRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCurrencyRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCurrencyRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCurrencyRates['NZD_Rate'] }} <br>">&#8377; <?php echo $total_amount; ?></span></li>
                                @else
                                    <?php
                                        $getCartRates = Product::getCurrencyRates($total_amount);
                                    ?>
                                    <li>Cart Sub Total <span class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCartRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCartRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCartRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCartRates['NZD_Rate'] }} <br>">&#8377; <?php echo $total_amount; ?></span></li>
                                    <li>Shipping Cost <span>Yet to Calculate</span></li>
                                    <?php
                                        $getCurrencyRates = Product::getCurrencyRates($total_amount);
                                    ?>
                                    <li>Total <span class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCurrencyRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCurrencyRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCurrencyRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCurrencyRates['NZD_Rate'] }} <br>">&#8377; <?php echo $total_amount; ?></span></li>
                                @endif
                            </ul>
                            {{-- <a class="btn btn-default update" href="">Update</a> --}}
                            <a class="btn btn-default check_out" href="{{ url('/checkout') }}">Check Out</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

@endsection
