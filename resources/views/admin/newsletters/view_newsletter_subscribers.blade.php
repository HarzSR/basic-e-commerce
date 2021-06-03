@extends('layouts.adminLayout.admin_design')

@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Newsletter Subscribers</a> <a href="#" class="current">View Newsletter Subscribers</a> </div>
            <h1>Newsletter Subscribers</h1>
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
                            <h5>View Newsletter Subscribers - {{ $subscriberCount }}</h5>
                            <a href="{{ url('/admin/export-newsletter-subscribers') }}" class="btn btn-success btn-mini" title="Export Subscribers" style="float: right; margin-right: 7px; margin-top: 7px;">Export Subscribers</a>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($subscribers as $subscriber)
                                    <tr class="gradeX">
                                        <td>{{ $subscriber->id }}</td>
                                        <td>{{ $subscriber->email }}</td>
                                        <td>
                                            @if($subscriber->status == 1)
                                                <a href="{{ url('admin/update-newsletter-status/' . $subscriber->id . '/0') }}"><button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Active</button></a>
                                            @else
                                                <a href="{{ url('admin/update-newsletter-status/' . $subscriber->id . '/1') }}"><button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">In-Active</button></a>
                                            @endif
                                        </td>
                                        <td><?php echo date('d-M-Y h:i A', strtotime($subscriber->created_at)); ?></td>
                                        <td><a rel="{{ $subscriber->id }}" rel1="delete-newsletter-subscriber" rel2="Subscriber" href="javascript:" class="btn btn-danger btn-mini deleteRecord" title="Delete Subscriber">Delete</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <th>User ID</th>
                                    <th>Email</th>
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
