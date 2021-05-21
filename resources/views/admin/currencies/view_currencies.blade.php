@extends('layouts.adminLayout.admin_design')

@section('content')

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">Currencies</a> <a href="#" class="current">View Currencies</a> </div>
            <h1>Currencies</h1>
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
                            <h5>View Currencies @if($currencyCount != 0) - {{ $currencyCount }} @endif</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Currency Code</th>
                                    <th>Exchange Rate</th>
                                    <th>Currency Status</th>
                                    <th>Update Date</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($currencies as $currency)
                                    <tr class="gradeX">
                                        <td>{{ $currency->id }}</td>
                                        <td>{{ $currency->currency_code }}</td>
                                        <td>{{ $currency->exchange_rate }}</td>
                                        <td>
                                            @if($currency->status == 1)
                                                <button class="btn btn-success btn-mini" title="Active" style="pointer-events: none; user-select: none;">Active</button>
                                            @else
                                                <button class="btn btn-danger btn-mini" title="In-Active" style="pointer-events: none; user-select: none;">In-Active</button>
                                            @endif
                                        </td>
                                        <td><?php echo date('d-M-Y h:i A', strtotime($currency->updated_at)); ?></td>
                                        <td>
                                            <a href="{{ url('/admin/edit-currency/'.$currency->id) }}" class="btn btn-primary btn-mini">Edit</a>
                                            {{-- <a href="{{ url('/admin/delete-category/'.$category->id) }}" class="btn btn-danger btn-mini delCategory">Delete</a> --}}
                                            <a rel="{{ $currency->id }}" rel1="delete-currency" rel2="Currency" href="javascript:" class="btn btn-danger btn-mini deleteRecord">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Currency Code</th>
                                        <th>Exchange Rate</th>
                                        <th>Currency Status</th>
                                        <th>Currency Date</th>
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
