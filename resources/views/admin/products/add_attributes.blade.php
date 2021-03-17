@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="{{ url('/admin/view-products') }}">Products</a> <a href="#" class="current">Add Product Attribute</a> </div>
            <h1>Product Attributes</h1>
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
                            <h5>Add Product Attributes</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form enctype="multipart/form-data" class="form-horizontal" method="post" action="{{ url('/admin/add-attributes/' . $productDetails->id) }}" name="add_attribute" id="add_attribute">
                                {{ csrf_field() }}
                                <div class="control-group">
                                    <label class="control-label">Product Name</label>
                                    <label class="control-label"><strong>{{ $productDetails->product_name }}</strong></label>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Product Code</label>
                                    <label class="control-label"><strong>{{ $productDetails->product_code }}</strong></label>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Product Color</label>
                                    <label class="control-label"><strong>{{ $productDetails->product_color }}</strong></label>
                                </div>
                                <div class="control-group">
                                    <label class="control-label"></label>
                                    <div class="field_wrapper">
                                        <div>
                                            <input type="text" name="sku[]" id="sku" placeholder="SKU" style="width: 120px;" required>
                                            <input type="text" name="size[]" id="size" placeholder="Size" style="width: 120px;" required>
                                            <input type="text" name="price[]" id="price" placeholder="Price" style="width: 120px;" required>
                                            <input type="text" name="stock[]" id="stock" placeholder="Stock" style="width: 120px;" required>
                                            <a href="javascript:void(0);" class="add_button" title="Add Field">
                                                <!-- <img src="add_icon.png"> -->Add
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" value="Add Attributes" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                            <h5>View Attributes</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>Attribute ID</th>
                                    <th>SKU</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($productDetails['attributes'] as $attribute)
                                    <tr class="gradeX">
                                        <td>{{ $attribute->id }}</td>
                                        <td>{{ $attribute->sku }}</td>
                                        <td>{{ $attribute->size }}</td>
                                        <td>{{ $attribute->price }}</td>
                                        <td>{{ $attribute->stock }}</td>
                                        <td class="center">
                                            <!-- <a href="{{ $productDetails->id }}" data-toggle="modal" class="btn btn-success btn-mini">View</a>
                                            <a href="{{ url('/admin/edit-product/'.$productDetails->id) }}" class="btn btn-primary btn-mini">Edit</a>
                                            <a href="{{ url('/admin/add-attributes/'.$productDetails->id) }}" class="btn btn-warning btn-mini">Add</a>
                                            <a href="{{ url('/admin/delete-product/'.$productDetails->id) }}" class="btn btn-danger btn-mini delProduct" >Delete</a></td> -->
                                            <a rel="{{ $productDetails->id }}" rel1="delete-product" href="javascript:" class="btn btn-danger btn-mini deleteRecord" >Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <th>Attribute ID</th>
                                    <th>SKU</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Stock</th>
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
