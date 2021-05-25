@extends('layouts.adminLayout.admin_design')

@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="{{ url('/admin/view-admins') }}">Admins/Sub-Admins</a> <a href="#" class="current">Edit Admin/Sub-Admin</a> </div>
            <h1>Admin/Sub-Admin</h1>
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
        </div>
        <div class="container-fluid"><hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                            <h5>Edit Admin/Sub-Admin</h5>
                        </div>
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="widget-content nopadding">
                            <form class="form-horizontal" method="post" action="{{ url('/admin/edit-admin/' . $adminDetails->id) }}" name="edit_admin" id="edit_admin" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="control-group">
                                    <label class="control-label">Type</label>
                                    <input type="hidden" name="type" id="type" value="{{ $adminDetails->type }}">
                                    <div class="controls">
                                        <select name="type" id="type" style="width: 220px;" disabled>
                                            <option value="">Select Type</option>
                                            <option value="Admin" @if(old('type') == "Admin" || $adminDetails->type == "Admin") Selected @endif>Admin</option>
                                            <option value="Sub Admin" @if(old('type') == "Sub Admin" || $adminDetails->type == "Sub Admin") Selected @endif>Sub Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Username</label>
                                    <div class="controls">
                                        <input type="hidden" name="username" id="username" value="{{ $adminDetails->username }}">
                                        <input type="text" name="username" id="username" value="@if(!empty(old('username'))) {{ old('username') }} @else {{ $adminDetails->username }} @endif" disabled>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Password</label>
                                    <div class="controls">
                                        <input type="password" name="password" id="password" value="@if(!empty(old('password'))) {{ old('password') }} @endif">
                                    </div>
                                </div>
                                <div class="control-group" name="access" id="access" @if(old('type') == "Admin" || empty(old('type') || $adminDetails->type == "Sub Admin")) hidden @endif>
                                    <label class="control-label">Access</label>
                                    <div class="controls" style="margin-top: 5px;">
                                        <input style="margin-top: -3px;" type="checkbox" name="categories_view_access" id="categories_view_access" @if(! ($errors->any() && is_null(old('categories_view_access'))) && old('categories_view_access', $adminDetails->categories_view_access)) checked @endif value="1"> View Categories &nbsp; &nbsp;
                                        <input style="margin-top: -3px;" type="checkbox" name="categories_edit_access" id="categories_edit_access" @if(! ($errors->any() && is_null(old('categories_edit_access'))) && old('categories_edit_access', $adminDetails->categories_edit_access)) checked @endif value="1"> View & Edit Categories &nbsp;&nbsp; &nbsp;&nbsp;
                                        <input style="margin-top: -3px;" type="checkbox" name="categories_full_access" id="categories_full_access" @if(! ($errors->any() && is_null(old('categories_full_access'))) && old('categories_full_access', $adminDetails->categories_full_access)) checked @endif value="1"> View, Edit and Delete Categories
                                    </div>
                                    <div class="controls" style="margin-top: 5px;">
                                        <input style="margin-top: -3px;" type="checkbox" name="products_view_access" id="products_view_access" @if(! ($errors->any() && is_null(old('products_view_access'))) && old('products_view_access', $adminDetails->products_view_access)) checked @endif value="1"> View Products &nbsp; &nbsp; &nbsp; &#8239;
                                        <input style="margin-top: -3px;" type="checkbox" name="products_edit_access" id="products_edit_access" @if(! ($errors->any() && is_null(old('products_edit_access'))) && old('products_edit_access', $adminDetails->products_edit_access)) checked @endif value="1"> View & Edit Products &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &#8239;
                                        <input style="margin-top: -3px;" type="checkbox" name="products_full_access" id="products_full_access" @if(! ($errors->any() && is_null(old('products_full_access'))) && old('products_full_access', $adminDetails->products_full_access)) checked @endif value="1"> View, Edit and Delete Products
                                    </div>
                                    <div class="controls" style="margin-top: 5px;">
                                        <input style="margin-top: -3px;" type="checkbox" name="orders_view_access" id="orders_view_access" @if(! ($errors->any() && is_null(old('orders_view_access'))) && old('orders_view_access', $adminDetails->orders_view_access)) checked @endif value="1"> View Orders &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &#8239;
                                        <input style="margin-top: -3px;" type="checkbox" name="orders_edit_access" id="orders_edit_access" @if(! ($errors->any() && is_null(old('orders_edit_access'))) && old('orders_edit_access', $adminDetails->orders_edit_access)) checked @endif value="1"> View & Edit Orders &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
                                        <input style="margin-top: -3px;" type="checkbox" name="orders_full_access" id="orders_full_access" @if(! ($errors->any() && is_null(old('orders_full_access'))) && old('orders_full_access', $adminDetails->orders_full_access)) checked @endif value="1"> View, Edit and Delete Orders

                                    </div>
                                    <div class="controls" style="margin-top: 5px;">
                                        <input style="margin-top: -3px;" type="checkbox" name="users_view_access" id="users_view_access" @if(! ($errors->any() && is_null(old('users_view_access'))) && old('users_view_access', $adminDetails->users_view_access)) checked @endif value="1"> View Users &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &#8239;
                                        <input style="margin-top: -3px;" type="checkbox" name="users_edit_access" id="users_edit_access" @if(! ($errors->any() && is_null(old('users_edit_access'))) && old('users_edit_access', $adminDetails->users_edit_access)) checked @endif value="1"> View & Edit Users &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &#8239;
                                        <input style="margin-top: -3px;" type="checkbox" name="users_full_access" id="users_full_access" @if(! ($errors->any() && is_null(old('users_full_access'))) && old('users_full_access', $adminDetails->users_full_access)) checked @endif value="1"> View, Edit and Delete Users
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Enable</label>
                                    <div class="controls">
                                        <input type="checkbox" name="status" id="status"@if(! ($errors->any() && is_null(old('status'))) && old('status', $adminDetails->status)) checked @endif value="1">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" value="Update Admin/Sub-Admin" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
