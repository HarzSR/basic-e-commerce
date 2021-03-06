@extends('layouts.adminLayout.admin_design')

@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Categories</a> <a href="#" class="current">View Categories</a> </div>
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
                            <h5>View Categories @if($categoryCount != 0) - {{ $categoryCount }} @endif</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>Category ID</th>
                                        <th>Category Name</th>
                                        <th>Category Description</th>
                                        <th>Category Level</th>
                                        <th>Category URL</th>
                                        <th>Category Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr class="gradeX">
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->description }}</td>
                                        <td>
                                            @foreach($levels as $val)
                                                @if($val->id == $category->parent_id)
                                                    {{ $val->name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $category->url }}</td>
                                        <td>
                                            @if($category->status == 1)
                                                <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Active</button>
                                            @else
                                                <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">In-Active</button>
                                            @endif
                                        </td>
                                        <td class="center">
                                            @if(Session::get('adminDetails')['categories_edit_access'] == 1 || Session::get('adminDetails')['categories_full_access'] == 1)
                                                <a href="{{ url('/admin/edit-category/'.$category->id) }}" class="btn btn-primary btn-mini">Edit</a>
                                                @if(Session::get('adminDetails')['categories_full_access'] == 1)
                                                    {{-- <a href="{{ url('/admin/delete-category/'.$category->id) }}" class="btn btn-danger btn-mini delCategory">Delete</a> --}}
                                                    <a rel="{{ $category->id }}" rel1="delete-category" rel2="Category" href="javascript:" class="btn btn-danger btn-mini deleteRecord">Delete</a>
                                                @endif
                                            @else
                                                <button class="btn btn-primary btn-mini" title="No Permission Granted" style="pointer-events: none; user-select: none;">No Permission Granted</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Category ID</th>
                                        <th>Category Name</th>
                                        <th>Category Description</th>
                                        <th>Category Level</th>
                                        <th>Category URL</th>
                                        <th>Category Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
