@extends('layouts.adminLayout.admin_design')

@section('content')

    <!--main-container-part-->
    <div id="content">
        <!--breadcrumbs-->
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
        </div>
        <!--End-breadcrumbs-->

        @if(Session::has('flash_message_error'))
            <div class="alert alert-error alert-block">
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

        <!--Action boxes-->
        <div class="container-fluid">
            <div class="quick-actions_homepage">
                <ul class="quick-actions">
                    <li class="bg_lb"> <a href="{{ url('admin/dashboard') }}"> <i class="icon-dashboard"></i> @if(!empty($dashbaord)) <span class="label label-important">{{ $dashbaord }}</span> @endif My Dashboard </a> </li>
                    @if(Session::get('adminDetails')['categories_view_access'] == 1 || Session::get('adminDetails')['categories_edit_access'] == 1 || Session::get('adminDetails')['categories_full_access'] == 1)
                        <li class="bg_db"> <a href="{{ url('admin/view-categories') }}"> <i class="icon-inbox"> @if(!empty($categoryCount)) </i><span class="label label-success">{{ $categoryCount }}</span> @endif Categories </a> </li>
                    @endif
                    @if(Session::get('adminDetails')['products_view_access'] == 1 || Session::get('adminDetails')['products_edit_access'] == 1 || Session::get('adminDetails')['products_full_access'] == 1)
                        <li class="bg_lg"> <a href="{{ url('admin/view-products') }}"> <i class="icon-inbox"></i> @if(!empty($productCount)) <span class="label label-success">{{ $productCount }}</span> @endif Products </a> </li>
                    @endif
                    @if(Session::get('adminDetails')['orders_view_access'] == 1 || Session::get('adminDetails')['orders_edit_access'] == 1 || Session::get('adminDetails')['orders_full_access'] == 1)
                        <li class="bg_dg"> <a href="{{ url('admin/view-orders') }}"> <i class="icon-inbox"></i> @if(!empty($orderCount)) <span class="label label-success">{{ $orderCount }}</span> @endif Orders </a> </li>
                    @endif
                    @if(Session::get('adminDetails')['users_view_access'] == 1 || Session::get('adminDetails')['users_edit_access'] == 1 || Session::get('adminDetails')['users_full_access'] == 1)
                        <li class="bg_ly"> <a href="{{ url('admin/view-users') }}"> <i class="icon-inbox"></i> @if(!empty($userCount)) <span class="label label-success">{{ $userCount }}</span> @endif Users </a> </li>
                    @endif
                    <li class="bg_dy"> <a href="{{ url('admin/view-coupons') }}"> <i class="icon-inbox"></i> @if(!empty($couponCount)) <span class="label label-success">{{ $couponCount }}</span> @endif Coupons </a> </li>
                    <li class="bg_ls"> <a href="{{ url('admin/view-currencies') }}"> <i class="icon-inbox"></i> @if(!empty($currencyCount)) <span class="label label-success">{{ $currencyCount }}</span> @endif Currencies </a> </li>
                    <li class="bg_lo"> <a href="{{ url('admin/view-shipping-charges') }}"> <i class="icon-inbox"></i> @if(!empty($shippingCount)) <span class="label label-success">{{ $shippingCount }}</span> @endif Shipping Locations </a> </li>
                    <li class="bg_lr"> <a href="{{ url('admin/view-banners') }}"> <i class="icon-inbox"></i> @if(!empty($bannerCount)) <span class="label label-success">{{ $bannerCount }}</span> @endif Banners </a> </li>
                    {{-- <li class="bg_lv"> <a href="tables.html"> <i class="icon-th"></i> Tables</a> </li> --}}
                </ul>
            </div>
            <!--End-Action boxes-->

            <!--Chart-box-->
            <div class="row-fluid">
                <div class="widget-box">
                    <div class="widget-title bg_lg"><span class="icon"><i class="icon-signal"></i></span>
                        <h5>Site Analytics</h5>
                    </div>
                    <div class="widget-content" >
                        <div class="row-fluid">
                            <div class="span9">
                                <div class="chart"></div>
                            </div>
                            <div class="span3">
                                <ul class="site-stats">
                                    <li class="bg_lh"><i class="icon-user"></i> <strong>2540</strong> <small>Total Users</small></li>
                                    <li class="bg_lh"><i class="icon-plus"></i> <strong>120</strong> <small>New Users </small></li>
                                    <li class="bg_lh"><i class="icon-shopping-cart"></i> <strong>656</strong> <small>Total Shop</small></li>
                                    <li class="bg_lh"><i class="icon-tag"></i> <strong>9540</strong> <small>Total Orders</small></li>
                                    <li class="bg_lh"><i class="icon-repeat"></i> <strong>10</strong> <small>Pending Orders</small></li>
                                    <li class="bg_lh"><i class="icon-globe"></i> <strong>8540</strong> <small>Online Orders</small></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End-Chart-box-->
            <hr/>
            <div class="row-fluid">
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title bg_ly" data-toggle="collapse" href="#collapseG2"><span class="icon"><i class="icon-chevron-down"></i></span>
                            <h5>Latest Posts</h5>
                        </div>
                        <div class="widget-content nopadding collapse in" id="collapseG2">
                            <ul class="recent-posts">
                                <li>
                                    <div class="user-thumb"> <img width="40" height="40" alt="User" src="{{ asset('images/backend_images/demo/av1.jpg') }}"> </div>
                                    <div class="article-post"> <span class="user-info"> By: john Deo / Date: 2 Aug 2012 / Time:09:27 AM </span>
                                        <p><a href="#">This is a much longer one that will go on for a few lines.It has multiple paragraphs and is full of waffle to pad out the comment.</a> </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="user-thumb"> <img width="40" height="40" alt="User" src="{{ asset('images/backend_images/demo/av2.jpg') }}"> </div>
                                    <div class="article-post"> <span class="user-info"> By: john Deo / Date: 2 Aug 2012 / Time:09:27 AM </span>
                                        <p><a href="#">This is a much longer one that will go on for a few lines.It has multiple paragraphs and is full of waffle to pad out the comment.</a> </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="user-thumb"> <img width="40" height="40" alt="User" src="{{ asset('images/backend_images/demo/av4.jpg') }}"> </div>
                                    <div class="article-post"> <span class="user-info"> By: john Deo / Date: 2 Aug 2012 / Time:09:27 AM </span>
                                        <p><a href="#">This is a much longer one that will go on for a few lines.Itaffle to pad out the comment.</a> </p>
                                    </div>
                                <li>
                                    <button class="btn btn-warning btn-mini">View All</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
                            <h5>Progress Box</h5>
                        </div>
                        <div class="widget-content">
                            <ul class="unstyled">
                                <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> 81% Clicks <span class="pull-right strong">567</span>
                                    <div class="progress progress-striped ">
                                        <div style="width: 81%;" class="bar"></div>
                                    </div>
                                </li>
                                <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> 72% Uniquie Clicks <span class="pull-right strong">507</span>
                                    <div class="progress progress-success progress-striped ">
                                        <div style="width: 72%;" class="bar"></div>
                                    </div>
                                </li>
                                <li> <span class="icon24 icomoon-icon-arrow-down-2 red"></span> 53% Impressions <span class="pull-right strong">457</span>
                                    <div class="progress progress-warning progress-striped ">
                                        <div style="width: 53%;" class="bar"></div>
                                    </div>
                                </li>
                                <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> 3% Online Users <span class="pull-right strong">8</span>
                                    <div class="progress progress-danger progress-striped ">
                                        <div style="width: 3%;" class="bar"></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end-main-container-part-->

@endsection
