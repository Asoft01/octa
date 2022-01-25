@extends('frontend.layouts.app')

@section('title', app_name() . ' | Availability')

@section('content')


    <div class="row justify-content-center" style="padding-top: 40px; padding-bottom: 40px; margin-left: 0px; margin-right: 0px;">
        <div class="col col-sm-8 align-self-center">
			
			<h1>Wiki</h1>
            {!! $content !!}

        </div><!-- close .col -->
    </div><!-- close .row -->

    {{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
@endsection

@push('after-styles')
@endpush

@push('after-scripts')
@endpush