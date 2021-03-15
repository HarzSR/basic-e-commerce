@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Products</a> <a href="#" class="current">View Product</a> </div>
            <h1>Categories</h1>
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
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>View Products</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Category Name</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Product Color</th>
                                    <th>Product Image</th>
                                    <th>Product Price</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr class="gradeX">
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->category_name }}</td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ $product->product_code }}</td>
                                        <td>{{ $product->product_color }}</td>
                                        <td>
                                            @if(!empty($product->image))
                                                <img src="{{ asset('/images/backend_images/products/small/' . $product->image) }}" style="width: 70px">
                                            @endif
                                        </td>
                                        <td>{{ $product->price }}</td>
                                        <td class="center">
                                            <a href="#myModal{{ $product->id }}" data-toggle="modal" class="btn btn-success btn-mini">View</a>
                                            <a href="{{ url('/admin/edit-product/'.$product->id) }}" class="btn btn-primary btn-mini">Edit</a>
                                            <a href="{{ url('/admin/delete-product/'.$product->id) }}" class="btn btn-danger btn-mini delProduct" >Delete</a></td>
                                    </tr>
                                    <div id="myModal{{ $product->id }}" class="modal hide">
                                        <div class="modal-header">
                                            <button data-dismiss="modal" class="close" type="button">x</button>
                                            <h3>{{ $product->product_name }} Full Description</h3>
                                        </div>
                                        <div class="modal-body">
                                            <p>Product ID: {{ $product->id }}</p>
                                            <p>Category ID: {{ $product->category_id }}</p>
                                            <p>Product Code: {{ $product->product_code }}</p>
                                            <p>Product Color: {{ $product->product_color }}</p>
                                            <p>Price: {{ $product->price }}</p>
                                            <p>Description: {{ $product->description }}</p>
                                            <p>
                                                @if(!empty($product->image))
                                                    <img src="{{ asset('/images/backend_images/products/small/' . $product->image) }}">
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <th>Product ID</th>
                                    <th>Category Name</th>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Product Color</th>
                                    <th>Product Image</th>
                                    <th>Product Price</th>
                                    <th>Actions</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
