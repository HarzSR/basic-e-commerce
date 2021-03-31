<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
    <ul>
        <li class="{{ (request()->is('admin/dashboard')) ? 'active' : '' }}"><a href="{{ url('/admin/dashboard') }}"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
        <li class="submenu {{ (request()->is('admin/add-category')) ? 'active' : '' }} {{ (request()->is('admin/view-categories')) ? 'active' : '' }} {{ (request()->is('admin/edit-category/*')) ? 'active' : '' }}"> <a href="#"><i class="icon icon-th-list"></i> <span>Categories</span> <span class="label label-important">2</span></a>
            <ul>
                <li><a href="{{ url('/admin/add-category') }}">Add Category</a></li>
                <li><a href="{{ url('/admin/view-categories') }}">View Categories</a></li>
            </ul>
        </li>
        <li class="submenu {{ (request()->is('admin/add-product')) ? 'active' : '' }} {{ (request()->is('admin/view-products')) ? 'active' : '' }} {{ (request()->is('admin/edit-product/*')) ? 'active' : '' }} {{ (request()->is('admin/add-attributes/*')) ? 'active' : '' }} {{ (request()->is('admin/add-images/*')) ? 'active' : '' }}"> <a href="#"><i class="icon icon-th-list"></i> <span>Products</span> <span class="label label-important">2</span></a>
            <ul>
                <li><a href="{{ url('/admin/add-product') }}">Add Product</a></li>
                <li><a href="{{ url('/admin/view-products') }}">View Products</a></li>
            </ul>
        </li>
        <li class="submenu {{ (request()->is('admin/add-coupon')) ? 'active' : '' }} {{ (request()->is('admin/view-coupons')) ? 'active' : '' }}"> <a href="#"><i class="icon icon-th-list"></i> <span>Coupons</span> <span class="label label-important">2</span></a>
            <ul>
                <li><a href="{{ url('/admin/add-coupon') }}">Add Coupon</a></li>
                <li><a href="{{ url('/admin/view-coupons') }}">View Coupon</a></li>
            </ul>
        </li>
    </ul>
</div>
<!--sidebar-menu-->
