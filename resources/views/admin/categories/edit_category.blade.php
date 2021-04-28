@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="/admin/view-categories">Categories</a> <a href="#" class="current">Edit Category</a> </div>
            <h1>Categories</h1>
        </div>
        <div class="container-fluid"><hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                            <h5>Edit Category</h5>
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
                            <form class="form-horizontal" method="post" action="{{ url('/admin/edit-category/' . $categoryDetails->id) }}" name="edit_category" id="edit_category" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="control-group">
                                    <label class="control-label">Category Name</label>
                                    <div class="controls">
                                        <input type="text" name="category_name" id="category_name" value="@if(!empty(old('category_name'))) {{ old('category_name') }} @else {{ $categoryDetails->name }} @endif">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Category Level</label>
                                    <div class="controls">
                                        <select name="parent_id" style="width: 220px;">
                                            <option value="0">Main Category</option>
                                            @foreach($levels as $val)
                                                <option value="{{ $val->id }}" @if(!empty(old('parent_id')) && old('parent_id') == $val->id) selected @else @if($categoryDetails->parent_id == $val->id) selected @endif @endif>{{ $val->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Description</label>
                                    <div class="controls">
                                        <textarea name="description" id="description">@if(!empty(old('description'))) {{ old('description') }} @else {{ $categoryDetails->description }} @endif</textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">URL</label>
                                    <div class="controls">
                                        <input type="text" name="url" id="url" value="@if(!empty(old('url'))) {{ old('url') }} @else {{ $categoryDetails->url }} @endif">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Meta Title</label>
                                    <div class="controls">
                                        <input type="text" name="meta_title" id="meta_title" value="@if(!empty(old('meta_title'))) {{ old('meta_title') }} @else {{ $categoryDetails->meta_title }} @endif">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Meta Description</label>
                                    <div class="controls">
                                        <input type="text" name="meta_description" id="meta_description" value="@if(!empty(old('meta_description'))) {{ old('meta_description') }} @else {{ $categoryDetails->meta_description }} @endif">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Meta Keywords</label>
                                    <div class="controls">
                                        <input type="text" name="meta_keywords" id="meta_keywords" value="@if(!empty(old('meta_keywords'))) {{ old('meta_keywords') }} @else {{ $categoryDetails->meta_keywords }} @endif">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Enable</label>
                                    <div class="controls">
                                        <input type="checkbox" name="status" id="status" @if(! ($errors->any() && is_null(old('status'))) && old('status', $categoryDetails->status)) checked @endif  value="1">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" value="Edit Category" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
