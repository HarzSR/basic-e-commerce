<?php
    $dataPoints = array(
        array("y" => 25, "label" => "Sunday"),
        array("y" => 15, "label" => "Monday"),
        array("y" => 25, "label" => "Tuesday"),
        array("y" => 5, "label" => "Wednesday"),
        array("y" => 10, "label" => "Thursday"),
        array("y" => 0, "label" => "Friday"),
        array("y" => 20, "label" => "Saturday")
    );
?>
<script>
    window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
            title: {
                text: "Push-ups Over a Week"
            },
            axisY: {
                title: "Number of Push-ups"
            },
            data: [{
                type: "line",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

    }
</script>

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
                            <h5>View Users - {{ $userCount }}</h5>
                            <a href="{{ url('/admin/export-users') }}" class="btn btn-success btn-mini" title="Export Users" style="float: right; margin-right: 7px; margin-top: 7px;">Export Users</a>
                        </div>
                        <div class="widget-content nopadding">
                            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
