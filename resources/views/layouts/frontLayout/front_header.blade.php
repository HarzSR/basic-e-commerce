<?php
    use App\Http\Controllers\Controller;
    $mainCategories = Controller::mainCategories();
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
                                New Zealand
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">India</a></li>
                                <li><a href="#">UK</a></li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
                                &#36; New Zealand Dollar
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#">&#8377; Rupees</a></li>
                                <li><a href="#">&#163; Pound Sterling</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="shop-menu pull-right">
                        <ul class="nav navbar-nav">
                            <li><a href="/account"><i class="fa fa-user"></i> Account</a></li>
                            <li><a href="/wishlist"><i class="fa fa-star"></i> Wishlist</a></li>
                            <li><a href="/checkout"><i class="fa fa-crosshairs"></i> Checkout</a></li>
                            <li><a href="{{ url('/cart') }}"><i class="fa fa-shopping-cart"></i> Cart</a></li>
                            @if(empty(Auth::check()))
                                <li><a href="{{ url('/login-register') }}"><i class="fa fa-sign-in"></i> Login</a></li>
                            @else
                                <li><a href="{{ url('/user-logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
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
                            <li><a href="{{ url('/') }}" class="active">Home</a></li>
                            <li class="dropdown"><a href="#">Shop<i class="fa fa-angle-down"></i></a>
                                <ul role="menu" class="sub-menu">
                                    @foreach($mainCategories as $mainCategory)
                                        @if($mainCategory->status == 1)
                                            <li><a href="{{ asset('/products/' . $mainCategory->url) }}">{{ $mainCategory->name }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="search_box pull-right">
                        <input type="text" placeholder="Search"/>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-bottom-->
</header>
