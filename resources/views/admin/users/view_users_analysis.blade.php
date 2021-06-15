<?php

    $currentMonth = date('M');
    $lastMonth = date('M', strtotime("-1 Month"));
    $lastPreviousMonth = date('M', strtotime("-2 Month"));
    $lastFourMonth = date('M', strtotime("-3 Month"));
    $lastFiveMonth = date('M', strtotime("-4 Month"));

    $dataPoints = array(
        array("y" => $lastFiveMonthUsers, "label" => $lastFiveMonth),
        array("y" => $lastFourMonthUsers, "label" => $lastFourMonth),
        array("y" => $lastPreviousMonthUsers, "label" => $lastPreviousMonth),
        array("y" => $lastMonthUsers, "label" => $lastMonth),
        array("y" => $currentMonthUsers, "label" => $currentMonth)
    );
?>
<script>
    window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
            title: {
                text: "Users Reporting"
            },
            axisY: {
                title: "Number of Users"
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
                            <h5>Users Reporting - {{ $userCount }}</h5>
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
