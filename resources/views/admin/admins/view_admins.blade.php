@extends('layouts.adminLayout.admin_design')

@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Admins/Sub-Admins</a> <a href="#" class="current">View Admins/Sub-Admins</a> </div>
            <h1>Admins/Sub-Admins</h1>
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
                            <h5>View Admin/Sub-Admins - {{ $adminCount }}</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>User Name</th>
                                        <th>User Type</th>
                                        <th>Categories Access</th>
                                        <th>Products Access</th>
                                        <th>Orders Access</th>
                                        <th>User Access</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($admins as $admin)
                                    <tr class="gradeX">
                                        <td>{{ $admin->id }}</td>
                                        <td>{{ $admin->username }}</td>
                                        <td>{{ $admin->type }}</td>
                                        <td>
                                            @if($admin->categories_access == 1 || $admin->type == "Admin")
                                                <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Yes</button>
                                            @else
                                                <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">No</button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($admin->products_access == 1 || $admin->type == "Admin")
                                                <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Yes</button>
                                            @else
                                                <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">No</button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($admin->orders_access == 1 || $admin->type == "Admin")
                                                <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Yes</button>
                                            @else
                                                <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">No</button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($admin->users_access == 1 || $admin->type == "Admin")
                                                <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Yes</button>
                                            @else
                                                <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">No</button>
                                            @endif
                                        </td>
                                        <td>
                                            @if($admin->status == 1)
                                                <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Active</button>
                                            @else
                                                <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">In-Active</button>
                                            @endif
                                        </td>
                                        <td><?php echo date('d-M-Y h:i A', strtotime($admin->created_at)); ?></td>
                                        <td>
                                            <a href="{{ url('/admin/edit-admin/' . $admin->id) }}" class="btn btn-primary btn-mini" title="Edit Admin">Edit</a>
                                            {{-- <a href="{{ url('/admin/delete-admin' . $admin->id) }}" class="btn btn-danger btn-mini delProduct" >Delete</a></td> --}}
                                            @if($admin->id != 1)
                                                <a rel="{{ $admin->id }}" rel1="delete-admin" rel2="Admin/Sub-Admin" href="javascript:" class="btn btn-danger btn-mini deleteRecord" title="Delete Admin">Delete</a>
                                            @endif
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>User Type</th>
                                    <th>Categories Access</th>
                                    <th>Products Access</th>
                                    <th>Orders Access</th>
                                    <th>User Access</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
