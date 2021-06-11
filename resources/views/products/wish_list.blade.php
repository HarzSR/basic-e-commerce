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
                    <li class="active">Wish List</li>
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
            @if(!empty($user_wishList) && count($user_wishList) > 0)
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
                            @foreach($user_wishList as $wishList)
                                <tr>
                                    <td class="cart_product">
                                        <a href="{{ url('product/' . $wishList->product_id) }}"><img src="{{ asset('images/backend_images/products/small/' . $wishList->image) }}" alt="" style="width: 50px"></a>
                                    </td>
                                    <td class="cart_description">
                                        <h4><a href="">{{ $wishList->product_name }}</a></h4>
                                        <p>Web ID: {{ $wishList->product_code }} | {{ $wishList->size }}</p>
                                    </td>
                                    <td class="cart_price">
                                        <?php
                                            $product_price = Product::getProductPrice($wishList->product_id, $wishList->size);
                                            $getItemPrice = Product::getCurrencyRates($product_price);
                                        ?>
                                        <p class="btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getItemPrice['USD_Rate'] }} <br> GB&#xa3; {{ $getItemPrice['GBP_Rate'] }} <br> EU&#x20AC; {{ $getItemPrice['EUR_Rate'] }} <br> NZ&#x24; {{ $getItemPrice['NZD_Rate'] }} <br>">&#8377; {{ $product_price }}</p>
                                    </td>
                                    <td class="cart_quantity">
                                        <div class="cart_quantity_button">
                                            @if($wishList->quantity > 1)
                                                <a class="cart_quantity_down" href="{{ url('cart/update-quantity/' . $wishList->id . '/-1') }}"> - </a>
                                            @endif
                                            <input class="cart_quantity_input" type="text" name="quantity" value="{{ $wishList->quantity }}" autocomplete="off" size="2">
                                            <a class="cart_quantity_up" href="{{ url('cart/update-quantity/' . $wishList->id . '/1') }}"> + </a>
                                        </div>
                                    </td>
                                    <td class="cart_total">
                                        <?php
                                            $getCartCurrencyRates = Product::getCurrencyRates($wishList->quantity * $product_price);
                                        ?>
                                        <p class="cart_total_price btn-secondary" data-toggle="tooltip" data-html="true" title="US&#x24; {{ $getCartCurrencyRates['USD_Rate'] }} <br> GB&#xa3; {{ $getCartCurrencyRates['GBP_Rate'] }} <br> EU&#x20AC; {{ $getCartCurrencyRates['EUR_Rate'] }} <br> NZ&#x24; {{ $getCartCurrencyRates['NZD_Rate'] }} <br>">&#8377; {{ $wishList->quantity * $product_price }}</p>
                                    </td>
                                    <td class="cart_delete">
                                        <a class="cart_quantity_delete" href="{{ url('cart/delete-product/' . $wishList->id) }}" onclick="return confirm('Would you like to delete {{ $wishList->product_name }} - {{ $wishList->size }}?')"><i class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                                <?php
                                    $total_amount = $total_amount + ($wishList->quantity * $product_price);
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
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
