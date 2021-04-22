@extends('layouts.adminLayout.admin_design')
@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Users</a> <a href="#" class="current">View Users</a> </div>
            <h1>Users</h1>
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
                            <h5>View Orders</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Country</th>
                                        <th>Pincode</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr class="gradeX">
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>{{ $user->city }}</td>
                                            <td>{{ $user->state }}</td>
                                            <td>{{ $user->country }}</td>
                                            <td>{{ $user->pincode }}</td>
                                            <td>{{ $user->mobile }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->status == 1)
                                                    <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Active</button>
                                                @else
                                                    <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">In-Active</button>
                                                @endif
                                            </td>
                                            <td><?php echo date('d-M-Y h:i A', strtotime($user->created_at)); ?></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Country</th>
                                    <th>Pincode</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
