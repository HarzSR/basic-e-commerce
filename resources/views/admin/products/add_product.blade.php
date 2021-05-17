@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Products</a> <a href="#" class="current">Add Product</a> </div>
            <h1>Product</h1>
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
                            <h5>Add Product</h5>
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
                            <form enctype="multipart/form-data" class="form-horizontal" method="post" action="{{ url('/admin/add-product') }}" name="add_product" id="add_product" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="control-group">
                                    <label class="control-label">Under Category</label>
                                    <div class="controls">
                                        <select name="category_id" id="category_id" style="width: 220px;">
                                            {{ print($categories_dropdown) }}
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Product Name</label>
                                    <div class="controls">
                                        <input type="text" name="product_name" id="product_name" value="{{ old('product_name') }}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Product Code</label>
                                    <div class="controls">
                                        <input type="text" name="product_code" id="product_code" value="{{ old('product_code') }}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Product Color</label>
                                    <div class="controls">
                                        <input type="text" name="product_color" id="product_color" value="{{ old('product_color') }}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Description</label>
                                    <div class="controls">
                                        <textarea name="description" id="description">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Materials & Care</label>
                                    <div class="controls">
                                        <textarea name="care" id="care">{{ old('care') }}</textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Product Sleeve</label>
                                    <div class="controls">
                                        <select name="sleeve" id="sleeve" class="form-control" style="width: 220px;">
                                            <option value="">Select</option>
                                            @foreach($sleeveArray as $sleeve)
                                                <option value="{{ $sleeve->description }}" @if(old('sleeve') == $sleeve->description) selected @endif>{{ $sleeve->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Product Pattern</label>
                                    <div class="controls">
                                        <select name="pattern" id="pattern" class="form-control" style="width: 220px;">
                                            <option value="">Select</option>
                                            @foreach($patternArray as $pattern)
                                                <option value="{{ $pattern->description }}" @if(old('pattern') == $pattern->description) selected @endif>{{ $pattern->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Price</label>
                                    <div class="controls">
                                        &#8377; <input type="text" name="price" id="price" value="{{ old('price') }}">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Weight</label>
                                    <div class="controls">
                                        <input type="text" name="weight" id="weight" value="{{ old('weight') }}"> gm(s)
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Image</label>
                                    <div class="controls">
                                        <input type="file" name="image" id="image">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Video</label>
                                    <div class="controls">
                                        <input type="file" name="video" id="video">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Feature Product</label>
                                    <div class="controls">
                                        <input type="checkbox" name="feature_item" id="feature_item" value="1">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Enable</label>
                                    <div class="controls">
                                        <input type="checkbox" name="status" id="status" value="1">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" value="Add Product" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
