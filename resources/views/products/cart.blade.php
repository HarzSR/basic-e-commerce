@extends('layouts.frontLayout.front_design')

@section('content')

    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
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
                                        <p>NZ$ {{ $cart->price }}</p>
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
                                        <p class="cart_total_price">NZ$ {{ $cart->quantity * $cart->price }}</p>
                                    </td>
                                    <td class="cart_delete">
                                        <a class="cart_quantity_delete" href="{{ url('cart/delete-product/' . $cart->id) }}" onclick="return confirm('Would you like to delete {{ $cart->product_name }} - {{ $cart->size }}?')"><i class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                                <?php $total_amount = $total_amount + ($cart->quantity * $cart->price); ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div>
                    <a href="{{ asset('/') }}" style="cursor: default"><img src="{{ asset('images/backend_images/empty-cart.png') }}" alt="" class="center-block"></a>
                    <div class="container text-center">
                        <div class="content-404">
                            <h2><a href="{{ asset('/') }}">Let's Shop for some goodies</a></h2>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section> <!--/#cart_items-->

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
                                    <li>Cart Sub Total <span>NZ$ <?php echo $total_amount; ?></span></li>
                                    <li>Discount <span>NZ$ <?php echo Session::get('couponAmount'); ?> | <a class="cart_quantity_delete" href="{{ url('cart/remove-coupon/') }}" onclick="return confirm('Would you like to remove Coupon?')"><i class="fa fa-times"></i></a></span></li>
                                    <li>Shipping Cost <span>Free</span></li>
                                    <li>Total <span>NZ$ <?php
                                            if ($total_amount - Session::get('couponAmount') < 0)
                                                echo '0';
                                            else
                                                echo $total_amount - Session::get('couponAmount'); ?>
                                        </span></li>
                                @else
                                    <li>Cart Sub Total <span>NZ$ <?php echo $total_amount; ?></span></li>
                                    <li>Shipping Cost <span>Free</span></li>
                                    <li>Total <span>NZ$ <?php echo $total_amount; ?></span></li>
                                @endif
                            </ul>
                            <a class="btn btn-default update" href="">Update</a>
                            <a class="btn btn-default check_out" href="{{ url('/checkout') }}">Check Out</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section><!--/#do_action-->

@endsection
