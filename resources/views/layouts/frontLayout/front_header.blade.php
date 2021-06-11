<?php
    use App\Product;
    use App\Http\Controllers\Controller;

    $mainCategories = Controller::mainCategories();
    $cartCount = Product::cartCount();
?>

<header id="header"><!--header-->
    <div class="header_top"><!--header_top-->
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="contactinfo">
                        <ul class="nav nav-pills">
                            <li><a href="tel:+64224596236"><i class="fa fa-phone"></i> +64 22 459 6236</a></li>
                            <li><a href="mailto:hariharansmm@gmail.com"><i class="fa fa-envelope"></i> harihararnsmm@gmail.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="social-icons pull-right">
                        <ul class="nav navbar-nav">
                            {{-- <li><a href="{{ url('/') }}"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="{{ url('/') }}"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="{{ url('/') }}"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="{{ url('/') }}"><i class="fa fa-dribbble"></i></a></li>
                            <li><a href="{{ url('/') }}"><i class="fa fa-google-plus"></i></a></li> --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header_top-->

    <div class="header-middle"><!--header-middle-->
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="logo pull-left">
                        <a href="{{ url('/') }}"><img src="{{ asset('images/frontend_images/home/logo.png') }}" alt="" /></a>
                    </div>
                    <div class="btn-group pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                                India
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">New Zealand</a></li>
                                <li><a href="#">UK</a></li>
                                <li><a href="#">United States of America</a></li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                                &#8377; Rupees
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">&#36; New Zealand Dollar</a></li>
                                <li><a href="#">&#163; Pound Sterling</a></li>
                                <li><a href="#">&#36; United States Dollar</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="shop-menu pull-right">
                        <ul class="nav navbar-nav">
                            <li><a href="{{ url('/account') }}"><i class="fa fa-user"></i> Account</a></li>
                            <li><a href="{{ url('/wish-list') }}"><i class="fa fa-star"></i> Wishlist</a></li>
                            <li><a href="{{ url('/orders') }}"><i class="fa fa-dropbox"></i> Orders</a></li>
                            <li><a href="{{ url('/checkout') }}"><i class="fa fa-crosshairs"></i> Checkout</a></li>
                            <li><a href="{{ url('/cart') }}"><i class="fa fa-shopping-cart"></i> Cart @if($cartCount != 0)<span class="notify">{{ $cartCount }}</span>@endif</a></li>
                            @if(empty(Auth::check()))
                                <li><a href="{{ url('/login-register') }}"><i class="fa fa-sign-in"></i> Login</a></li>
                            @else
                                <li><a href="{{ url('/user-logout') }}">Logout &nbsp;<i class="fa fa-sign-out"></i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-middle-->

    <div class="header-bottom"><!--header-bottom-->
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="mainmenu pull-left">
                        <ul class="nav navbar-nav collapse navbar-collapse">
                            <li><a href="{{ url('/') }}" <?php if (url('/') == url()->current()) echo "class=\"active\"" ?>>Home</a></li>
                            <li class="dropdown"><a href="#" <?php if (preg_match("/products/i", url()->current())) echo "class=\"active\"" ?>>Categories<i class="fa fa-angle-down"></i></a>
                                <ul role="menu" class="sub-menu">
                                    @foreach($mainCategories as $mainCategory)
                                        @if($mainCategory->status == 1)
                                            <li><a href="{{ asset('/products/' . $mainCategory->url) }}">{{ $mainCategory->name }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                            <li><a href="{{ url('/page/contact') }}" <?php if (preg_match("/page\/contact/", url()->current())) echo "class=\"active\"" ?>>Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="search_box pull-right">
                        <form action="{{ url('/search-products') }}" method="post">
                            {{ csrf_field() }}
                            <input type="text" name="product" id="product" placeholder="Search"/>
                            <button type="submit" style="border: 0px; height: 33px; margin-left: -3px;">GO</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-bottom-->
</header>
