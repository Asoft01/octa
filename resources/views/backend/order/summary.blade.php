@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
    {{--@include('backend.auth.user.includes.breadcrumb-links')--}}
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Orders
                </h4>
            </div><!--col-->

            <div class="col-sm-7">
                
            </div><!--col-->
        </div><!--row-->
        <div style="margin-top: 12px; border:1px solid #333; padding: 10px; border-radius:10px;">
        <h3>Summary of sales</h3>
        <ul style="font-size: 18px;">
            <li>USD: ${{ $totalUSD }}</li>
            <li>CAD: ${{ $totalCAD }}</li>
        </ul>
        <h4>Summary of sales taxes in Canada</h4>
        <ul>
            <li>QST: ${{ $qst }}</li>
            <li>HST: ${{ $hst }}</li>
            <li>PST: ${{ $pst }}</li>
            <li>GST: ${{ $gst }}</li>
        </ul>
        <h3>Summary of orders</h3>
        <ul style="font-size: 18px;">
            <li>DONE: {{ $totalDONE }}</li>
            <li>TODO: {{ $totalTODO }}</li>
        </ul>
        </div>
    </div><!--card-body-->
</div><!--card-->
@endsection
