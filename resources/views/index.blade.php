@extends('layouts.frontLayout.front_design')

@section('content')

    <section id="slider"><!--slider-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach($banners as $key => $banner)
                                <li data-target="#slider-carousel" data-slide-to="{{ $key }}" @if($key == 0) class="active" @endif></li>
                            @endforeach
                        </ol>

                        <div class="carousel-inner">
                            {{-- <div class="item active">
                                <div class="col-sm-6">
                                    <h1><span>E</span>-SHOPPER</h1>
                                    <h2>Free E-Commerce Template</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                                    <button type="button" class="btn btn-default get">Get it now</button>
                                </div>
                                <div class="col-sm-6">
                                    <img src="{{ asset('images/frontend_images/home/girl1.jpg') }}" class="girl img-responsive" alt="" />
                                    <img src="{{ asset('images/frontend_images/home/pricing.png') }}"  class="pricing" alt="" />
                                </div>
                            </div>
                            <div class="item">
                                <div class="col-sm-6">
                                    <h1><span>E</span>-SHOPPER</h1>
                                    <h2>100% Responsive Design</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                                    <button type="button" class="btn btn-default get">Get it now</button>
                                </div>
                                <div class="col-sm-6">
                                    <img src="{{ asset('images/frontend_images/home/girl2.jpg') }}" class="girl img-responsive" alt="" />
                                    <img src="{{ asset('images/frontend_images/home/pricing.png') }}"  class="pricing" alt="" />
                                </div>
                            </div>

                            <div class="item">
                                <div class="col-sm-6">
                                    <h1><span>E</span>-SHOPPER</h1>
                                    <h2>Free Ecommerce Template</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                                    <button type="button" class="btn btn-default get">Get it now</button>
                                </div>
                                <div class="col-sm-6">
                                    <img src="{{ asset('images/frontend_images/home/girl3.jpg') }}" class="girl img-responsive" alt="" />
                                    <img src="{{ asset('images/frontend_images/home/pricing.png') }}" class="pricing" alt="" />
                                </div>
                            </div> --}}

                            @foreach($banners as $key => $banner)
                                <div class="item @if($key == 0) active @endif">
                                    <a href="{{ $banner->link }}"><img src="{{ asset('images/frontend_images/banners/' . $banner->image) }}" class="girl img-responsive" alt="{{ $banner->title }}" /></a>
                                </div>
                            @endforeach

                        </div>

                        <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    @include('layouts.frontLayout.front_sidebar')
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="features_items">
                        <h2 class="title text-center">Featured Items</h2>
                        @foreach($productsFeaturedAll as $product)
                            <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <img src="{{ asset('images/backend_images/products/small/' . $product->image   ) }}" alt="" />
                                        <h2>NZ$ {{ $product->price }}</h2>
                                        <p>{{ $product->product_name }}</p>
                                        <a href="{{ asset('/product/' . $product->id) }}" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>View Product</a>
                                    </div>
                                    {{-- <div class="product-overlay">
                                        <div class="overlay-content">
                                            <h2>NZ$ {{ $product->price }}</h2>
                                            <p>{{ $product->product_name }}</p>
                                            <p>{{ $product->description }}</p>
                                            <a href="{{ asset('/product/' . $product->id) }}" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
                                        <li><a href="#"><i class="fa fa-plus-square"></i>Add to compare</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-sm-3">
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="features_items">
                        <h2 class="title text-center">All Items</h2>
                        @foreach($productsAll as $product)
                            <div class="col-sm-4">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <img src="{{ asset('images/backend_images/products/small/' . $product->image   ) }}" alt="" />
                                            <h2>NZ$ {{ $product->price }}</h2>
                                            <p>{{ $product->product_name }}</p>
                                            <a href="{{ asset('/product/' . $product->id) }}" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>View Product</a>
                                        </div>
                                        {{-- <div class="product-overlay">
                                            <div class="overlay-content">
                                                <h2>NZ$ {{ $product->price }}</h2>
                                                <p>{{ $product->product_name }}</p>
                                                <p>{{ $product->description }}</p>
                                                <a href="{{ asset('/product/' . $product->id) }}" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="choose">
                                        <ul class="nav nav-pills nav-justified">
                                            <li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
                                            <li><a href="#"><i class="fa fa-plus-square"></i>Add to compare</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
