@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="{{ url('/admin/view-cms-pages/') }}">CMS Pages</a> <a href="#" class="current">Edit CMS Page</a> </div>
            <h1>CMS Page</h1>
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
                            <h5>Edit CMS Page</h5>
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
                            <form class="form-horizontal" method="post" action="{{ url('/admin/edit-cms-page/' . $cmsPage->id) }}" name="edit_cms_page" id="edit_cms_page" novalidate="novalidate">
                                {{ csrf_field() }}
                                <div class="control-group">
                                    <label class="control-label">Title</label>
                                    <div class="controls">
                                        <input type="text" name="title" id="title" value="@if(!empty(old('title'))) {{ old('title') }} @else {{ $cmsPage->title }} @endif">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">CMS Page URL</label>
                                    <div class="controls">
                                        <input type="text" name="url" id="url" value="@if(!empty(old('url'))) {{ old('url') }} @else {{ $cmsPage->url }} @endif">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Description</label>
                                    <div class="controls">
                                        <textarea name="description" id="description">@if(!empty(old('description'))) {{ old('description') }} @else {{ $cmsPage->description }} @endif</textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Meta Title</label>
                                    <div class="controls">
                                        <input type="text" name="meta_title" id="meta_title" value="@if(!empty(old('meta_title'))) {{ old('meta_title') }} @else {{ $cmsPage->meta_title }} @endif">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Meta Description</label>
                                    <div class="controls">
                                        <input type="text" name="meta_description" id="meta_description" value="@if(!empty(old('meta_description'))) {{ old('meta_description') }} @else {{ $cmsPage->meta_description }} @endif">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Meta Keywords</label>
                                    <div class="controls">
                                        <input type="text" name="meta_keywords" id="meta_keywords" value="@if(!empty(old('meta_keywords'))) {{ old('meta_keywords') }} @else {{ $cmsPage->meta_keywords }} @endif">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Enable</label>
                                    <div class="controls">
                                        <input type="checkbox" name="status" id="status" @if(! ($errors->any() && is_null(old('status'))) && old('status', $cmsPage->status)) checked @endif value="1">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" value="Edit CMS Page" class="btn btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
