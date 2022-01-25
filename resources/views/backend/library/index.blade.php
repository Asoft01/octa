@extends('backend.layouts.app')

@section('title', app_name() . ' | BO - table')

@section('breadcrumb-links')
    {{--@include('backend.auth.user.includes.breadcrumb-links')--}}
@endsection

@section('content')
{{$dataTable->table()}}
<div style="margin-top: 20px;"></div>
@endsection

@push('after-scripts')
    {{$dataTable->scripts()}}
@endpush
