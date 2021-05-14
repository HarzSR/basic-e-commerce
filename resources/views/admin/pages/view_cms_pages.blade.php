@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">CMS Pages</a> <a href="#" class="current">View CMS Pages</a> </div>
            <h1>CMS Pages</h1>
            @if(Session::has('flash_message_error'))
                <div class="alert-error alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{!! session('flash_message_error') !!}</strong>
                </div>
            @endif
            @if(Session::has('flash_message_success'))
                <div class="alert-success alert-block">
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
                            <h5>View CMS Pages @if($cmsPageCount != 0) - {{ $cmsPageCount }} @endif</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>Page ID</th>
                                        <th>Title</th>
                                        <th>URL</th>
                                        <th>Status</th>
                                        <th>Created On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($cmsPages as $cmsPage)
                                    <tr class="gradeX">
                                        <td>{{ $cmsPage->id }}</td>
                                        <td>{{ $cmsPage->title }}</td>
                                        <td>{{ $cmsPage->url }}</td>
                                        <td>
                                            @if($cmsPage->status == 1)
                                                <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Active</button>
                                            @else
                                                <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">In-Active</button>
                                            @endif
                                        </td>
                                        <td><?php echo date('d-M-Y h:i A', strtotime($cmsPage->created_at)); ?></td>
                                        <td class="center">
                                            <a href="#myModal{{ $cmsPage->id }}" data-toggle="modal" class="btn btn-success btn-mini" title="View Description">View</a>
                                            <a href="{{ url('/admin/edit-cms-page/' . $cmsPage->id) }}" class="btn btn-primary btn-mini" title="Edit CMS Page">Edit</a>
                                            {{-- <a href="{{ url('/admin/delete-cms-age/' . $cmsPage->id) }}" class="btn btn-danger btn-mini delProduct" >Delete</a></td> --}}
                                            <a rel="{{ $cmsPage->id }}" rel1="delete-cms-page" rel2="CMS Page" href="javascript:" class="btn btn-danger btn-mini deleteRecord" title="Delete CMS Page">Delete</a>
                                        </td>
                                    </tr>
                                    <div id="myModal{{ $cmsPage->id }}" class="modal hide">
                                        <div class="modal-header">
                                            <button data-dismiss="modal" class="close" type="button">x</button>
                                            <h3>{{ $cmsPage->product_name }} Full Description</h3>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Page ID:</strong> {{ $cmsPage->id }}</p>
                                            <p><strong>Page Title:</strong> {{ $cmsPage->title }}</p>
                                            <p><strong>Page URL:</strong> /{{ $cmsPage->url }}/</p>
                                            <p><strong>Page Created </strong>On: <?php echo date('d-M-Y H:i A', strtotime($cmsPage->created_at)); ?></p>
                                            <p><strong>Page Update </strong>On: <?php echo date('d-M-Y H:i A', strtotime($cmsPage->updated_at)); ?></p>
                                            <p><strong>Page Status:</strong>
                                                @if($cmsPage->status == 1)
                                                    <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Active</button>
                                                @else
                                                    <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">In-Active</button>
                                                @endif
                                            </p>
                                            <p><strong>Page Description:</strong> {{ $cmsPage->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <th>Page ID</th>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Status</th>
                                    <th>Created On</th>
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
