@extends('layouts.frontLayout.front_design')

@section('content')

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    @include('layouts.frontLayout.front_sidebar')
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="product-details"><!--product-details-->
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
                        <div class="col-sm-5">
                            <div class="view-product">
                                <div class="easyzoom easyzoom--overlay easyzoom--with-thumbnails">
                                    <a class="mainImageHref" href="{{ asset('images/backend_images/products/large/' . $productDetails->image) }}">
                                        <img class="mainImage" src="{{ asset('images/backend_images/products/small/' . $productDetails->image) }}" alt="" style="width: 100%"/>
                                    </a>
                                </div>
                            </div>
                            <div id="similar-product" class="carousel slide" data-ride="carousel">

                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                    <div class="item active thumbnails">
                                        @foreach($productAdditionalImages as $productAdditionalImage)
                                            <a class="changeImageHref" href="{{ asset('images/backend_images/products/large/' . $productAdditionalImage->image) }}" data-standard="{{ asset('images/backend_images/products/small/' . $productAdditionalImage->image) }}">
                                                <img class="changeImage" src="{{ asset('images/backend_images/products/small/' . $productAdditionalImage->image) }}" alt="" style="width: 80px">
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-sm-7">
                            <form action="{{ url('add-cart') }}" name="addToCartForm" id="addToCartForm" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="product_id" value="{{ $productDetails->id }}">
                                <input type="hidden" name="product_name" value="{{ $productDetails->product_name }}">
                                <input type="hidden" name="product_code" value="{{ $productDetails->product_code }}">
                                <input type="hidden" name="product_color" value="{{ $productDetails->product_color }}">
                                <input type="hidden" name="price" value="{{ $productDetails->price }}" id="hiddenPrice">
                                <div class="product-information"><!--/product-information-->
                                <img src="{{ asset('images/frontend_images/product-details/new.jpg') }}" class="newarrival" alt="" />
                                <h2>{{ $productDetails->product_name }}</h2>
                                <p>Web Code: {{ $productDetails->product_code }}</p>
                                <p>Select Size:&#8194;
                                    <select name="size" id="size" style="width: 150px" required>
                                        <option value="">Select</option>
                                        @foreach($productDetails->attributes as $productDetail)
                                            <option value="{{ $productDetails->id }}-{{ $productDetail->size }}">{{ $productDetail->size }}</option>
                                        @endforeach
                                    </select>
                                </p>
                                <img src="{{ asset('images/frontend_images/product-details/rating.png') }}" alt="" />
                                <span>
									<span id="getPrice">&#8377; {{ $productDetails->price }}</span>
									<label>Quantity:</label>
									<input type="number" name="quantity" id="quantity" value="1" />
                                    @if($total_stock > 0)
                                        <button type="submit" class="btn btn-fefault cart" id="cartButton">
                                            <i class="fa fa-shopping-cart"></i>
                                            Add to cart
                                        </button>
                                    @endif
								</span>
                                <p><b>Availability:</b>
                                    <span id="availability">
                                        @if($total_stock > 0)
                                            In Stock
                                        @else
                                            Out of Stock
                                        @endif
                                    </span>
                                </p>
                                <p><b>Condition:</b> New</p>
                                <a href=""><img src="{{ asset('images/frontend_images/product-details/share.png') }}" class="share img-responsive"  alt="" /></a>
                            </div><!--/product-information-->
                            </form>
                        </div>
                    </div><!--/product-details-->

                    <div class="category-tab shop-details-tab"><!--category-tab-->
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#description" data-toggle="tab">Description</a></li>
                                <li><a href="#care" data-toggle="tab">Materials & Care</a></li>
                                <li><a href="#delivery" data-toggle="tab">Delivery Options</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="description" >
                                <div class="col-sm-12">
                                    <p>{{ $productDetails->description }}</p>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="care" >
                                <div class="col-sm-12">
                                    <p>{{ $productDetails->care }}</p>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="delivery" >
                                <div class="col-sm-12">
                                    <p>Delivery Instructions</p>
                                </div>
                            </div>

                        </div>
                    </div><!--/category-tab-->

                    <div class="recommended_items"><!--recommended_items-->
                        <h2 class="title text-center">recommended items</h2>

                        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($relatedProducts->chunk(3) as $relatedProduct)
                                    <div class="item @if($loop->first) active @endif">
                                        @foreach($relatedProduct as $item)
                                            <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="{{ asset('images/backend_images/products/small/' . $item->image) }}" alt="" style="width: 150px"/>
                                                    <h2>&#8377; {{ $item->price }}</h2>
                                                    <p>{{ $item->price }}</p>
                                                    <a href="{{ url('product/' . $item->id) }}"><button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        @endforeach
                                </div>
                                @endforeach
                            </div>
                            <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                                <i class="fa fa-angle-left"></i>
                            </a>
                            <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </div>
                    </div><!--/recommended_items-->

                </div>
            </div>
        </div>
    </section>

@endsection
