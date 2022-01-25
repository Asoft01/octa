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
        
        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>UUID</th>
                            <th>Order by</th>
                            <th>Reviewer</th>
                            <th>@lang('labels.backend.access.users.table.confirmed')</th>
                            <th>Amount</th>
                            <th>Item</th>
                            <th>Due date</th>
                            <th>Created</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->uuid }}</td>
                                <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                                <td>{{ $order->orderitem[0]->reviewer->user->first_name }} {{ $order->orderitem[0]->reviewer->user->last_name }}</td>
                                <td>{{ $order->status_order->acronym }}</td>
                                <td>${{ $order->amount_total }} {{ $order->currency }}</td>
                                <td>{{ $order->orderitem[0]->product->title }}</td>
                                <td>@if($order->delivery && $order->delivery->due_date) {{ $order->delivery->due_date->diffForHumans() }} @endif</td>
                                <td>{{ $order->created_at->diffForHumans() }}</td>
                                <td class="btn-td">
                                    <a href="{{ route('admin.auth.user.show', $order) }}" data-toggle="tooltip" data-placement="top" title="@lang('buttons.general.crud.view')" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    {!! $orders->total() !!} orders total
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {!! $orders->render() !!}
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection
