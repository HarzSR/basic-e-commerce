<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
    <ul>
        <li <?php if (preg_match("/dashboard/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/dashboard') }}"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Categories</span> <span class="label label-important">2</span></a>
            <ul <?php if (preg_match("/categor/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/add-category/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-category') }}">Add Category</a></li>
                <li <?php if (preg_match("/(view-categories|edit-category)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-categories') }}">View Categories</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Products</span> <span class="label label-important">2</span></a>
            <ul <?php if (preg_match("/(product|attribute|images)/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/add-product/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-product') }}">Add Product</a></li>
                <li <?php if (preg_match("/(view-products|edit-product|add-attribute|add-images)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-products') }}">View Products</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Coupons</span> <span class="label label-important">2</span></a>
            <ul <?php if (preg_match("/coupon/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/add-coupon/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-coupon') }}">Add Coupon</a></li>
                <li <?php if (preg_match("/(view-coupons|edit-coupon)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-coupons') }}">View Coupons</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Orders</span> <span class="label label-important">1</span></a>
            <ul <?php if (preg_match("/order/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/(view-orders|view-order)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-orders') }}">View Orders</a></li>
            </ul>
        </li>
        <li class="submenu"> <a href="#"><i class="icon icon-th-list"></i> <span>Banners</span> <span class="label label-important">2</span></a>
            <ul <?php if (preg_match("/banner/i", url()->current())) echo "style=\"display: block;\"" ?>>
                <li <?php if (preg_match("/add-banner/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/add-banner') }}">Add Banner</a></li>
                <li <?php if (preg_match("/(view-banners|edit-banner)/i", url()->current())) echo "class=\"active\"" ?>><a href="{{ url('/admin/view-banners') }}">View Banners</a></li>
            </ul>
        </li>
    </ul>
</div>
<!--sidebar-menu-->
