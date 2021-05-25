<?php
    use Illuminate\Support\Facades\Session;
?>

<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
    <ul>
        <li <?php if (preg_match("/dashboard/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/dashboard') }}"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
        <li class="submenu"> <a href="#"><i class="icon icon-qrcode"></i> <span>Admin</span> <span class="label label-important">1</span></a>
            <ul <?php if (preg_match("/(add-admin|view-admins|edit-admin)/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/add-admin/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-admin') }}">Add Admin/Sub-Admin</a></li>
                <li <?php if (preg_match("/(view-admins|edit-admin)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-admins') }}">View Admins/Sub-Admins</a></li>
            </ul>
        </li>
        @if(Session::get('adminDetails')['users_view_access'] == 1 || Session::get('adminDetails')['users_edit_access'] == 1 || Session::get('adminDetails')['users_full_access'] == 1)
        <li class="submenu"> <a href="#"><i class="icon icon-user"></i> <span>Users</span> <span class="label label-important">1</span></a>
            <ul <?php if (preg_match("/user/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/(view-users|view-user)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-users') }}">View Users</a></li>
            </ul>
        </li>
        @endif
        <li class="submenu"> <a href="#"><i class="icon icon-file-alt"></i> <span>CMS Pages</span> <span class="label label-important">2</span></a>
            <ul <?php if (preg_match("/cms-page/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/add-cms-page/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-cms-page') }}">Add CMS Page</a></li>
                <li <?php if (preg_match("/(view-cms-page|edit-cms-page)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-cms-pages') }}">View CMS Pages</a></li>
            </ul>
        </li>
        @if(Session::get('adminDetails')['categories_view_access'] == 1 || Session::get('adminDetails')['categories_edit_access'] == 1 || Session::get('adminDetails')['categories_full_access'] == 1)
        <li class="submenu"> <a href="#"><i class="icon icon-folder-open"></i> <span>Categories</span> <span class="label label-important">2</span></a>
            <ul <?php if (preg_match("/categor/i", url()->current())) echo "style=\"display: block;\"" ?>>
                @if(Session::get('adminDetails')['categories_edit_access'] == 1 || Session::get('adminDetails')['categories_full_access'] == 1)
                    <li <?php if (preg_match("/add-category/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-category') }}">Add Category</a></li>
                @endif
                @if(Session::get('adminDetails')['categories_view_access'] == 1 || Session::get('adminDetails')['categories_edit_access'] == 1 || Session::get('adminDetails')['categories_full_access'] == 1)
                    <li <?php if (preg_match("/(view-categories|edit-category)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-categories') }}">View Categories</a></li>
                @endif
            </ul>
        </li>
        @endif
        @if(Session::get('adminDetails')['products_view_access'] == 1 || Session::get('adminDetails')['products_edit_access'] == 1 || Session::get('adminDetails')['products_full_access'] == 1)
        <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>Products</span> <span class="label label-important">2</span></a>
            <ul <?php if (preg_match("/(product|attribute|images)/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/add-product/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-product') }}">Add Product</a></li>
                <li <?php if (preg_match("/(view-products|edit-product|add-attribute|add-images)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-products') }}">View Products</a></li>
            </ul>
        </li>
        @endif
        <li class="submenu"> <a href="#"><i class="icon icon-tags"></i> <span>Coupons</span> <span class="label label-important">2</span></a>
            <ul <?php if (preg_match("/coupon/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/add-coupon/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-coupon') }}">Add Coupon</a></li>
                <li <?php if (preg_match("/(view-coupons|edit-coupon)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-coupons') }}">View Coupons</a></li>
            </ul>
        </li>
        @if(Session::get('adminDetails')['orders_view_access'] == 1 || Session::get('adminDetails')['orders_edit_access'] == 1 || Session::get('adminDetails')['orders_full_access'] == 1)
        <li class="submenu"> <a href="#"><i class="icon icon-inbox"></i> <span>Orders</span> <span class="label label-important">1</span></a>
            <ul <?php if (preg_match("/order/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/(view-orders|view-order)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-orders') }}">View Orders</a></li>
            </ul>
        </li>
        @endif
        <li class="submenu"> <a href="#"><i class="icon icon-money"></i> <span>Currencies</span> <span class="label label-important">2</span></a>
            <ul <?php if (preg_match("/currenc/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/add-currency/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-currency') }}">Add Currency</a></li>
                <li <?php if (preg_match("/(view-currencies|edit-currency)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-currencies') }}">View Currencies</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-retweet"></i> <span>Shipping Charges</span> <span class="label label-important">1</span></a>
            <ul <?php if (preg_match("/shipping/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/(view-shipping-charges|edit-shipping-charge)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-shipping-charges') }}">View Shipping Charges</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-film"></i> <span>Banners</span> <span class="label label-important">2</span></a>
            <ul <?php if (preg_match("/banner/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/add-banner/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-banner') }}">Add Banner</a></li>
                <li <?php if (preg_match("/(view-banners|edit-banner)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-banners') }}">View Banners</a></li>
            </ul>
        </li>
        <li <?php if (preg_match("/settings/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/dashboard') }}"><i class="icon icon-cogs"></i> <span>Settings</span></a> </li>
    </ul>
</div>
<!--sidebar-menu-->
