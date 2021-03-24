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
                        <div class="col-sm-5">
                            <div class="view-product">
                                <div class="easyzoom easyzoom--overlay easyzoom--with-thumbnails">
                                    <a href="{{ asset('images/backend_images/products/large/' . $productDetails->image) }}">
                                        <img class="mainImage" src="{{ asset('images/backend_images/products/medium/' . $productDetails->image) }}" alt="" style="width: 100%"/>
                                    </a>
                                </div>
                            </div>
                            <div id="similar-product" class="carousel slide" data-ride="carousel">

                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                    <div class="item active thumbnails">
                                        @foreach($productAdditionalImages as $productAdditionalImage)
                                            <a href="{{ asset('images/backend_images/products/large/' . $productAdditionalImage->image) }}" data-standard="{{ asset('images/backend_images/products/small/' . $productAdditionalImage->image) }}">
                                                <img class="changeImage" src="{{ asset('images/backend_images/products/small/' . $productAdditionalImage->image) }}" alt="" style="width: 80px">
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-sm-7">
                            <div class="product-information"><!--/product-information-->
                                <img src="{{ asset('images/frontend_images/product-details/new.jpg') }}" class="newarrival" alt="" />
                                <h2>{{ $productDetails->product_name }}</h2>
                                <p>Web Code: {{ $productDetails->product_code }}</p>
                                <p>Select Size:&#8194;
                                    <select name="size" id="size" style="width: 150px">
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
									<input type="text" value="1" />
									<button type="button" class="btn btn-fefault cart">
										<i class="fa fa-shopping-cart"></i>
										Add to cart
									</button>
								</span>
                                <p><b>Availability:</b> In Stock</p>
                                <p><b>Condition:</b> New</p>
                                <a href=""><img src="{{ asset('images/frontend_images/product-details/share.png') }}" class="share img-responsive"  alt="" /></a>
                            </div><!--/product-information-->
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
                                <div class="item active">
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="{{ asset('images/frontend_images/home/recommend1.jpg') }}" alt="" />
                                                    <h2>$56</h2>
                                                    <p>Easy Polo Black Edition</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="{{ asset('images/frontend_images/home/recommend2.jpg') }}" alt="" />
                                                    <h2>$56</h2>
                                                    <p>Easy Polo Black Edition</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="{{ asset('images/frontend_images/home/recommend3.jpg') }}" alt="" />
                                                    <h2>$56</h2>
                                                    <p>Easy Polo Black Edition</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="{{ asset('images/frontend_images/home/recommend1.jpg') }}" alt="" />
                                                    <h2>$56</h2>
                                                    <p>Easy Polo Black Edition</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="{{ asset('images/frontend_images/home/recommend2.jpg') }}" alt="" />
                                                    <h2>$56</h2>
                                                    <p>Easy Polo Black Edition</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="product-image-wrapper">
                                            <div class="single-products">
                                                <div class="productinfo text-center">
                                                    <img src="{{ asset('images/frontend_images/home/recommend3.jpg') }}" alt="" />
                                                    <h2>$56</h2>
                                                    <p>Easy Polo Black Edition</p>
                                                    <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
