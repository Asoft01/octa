@extends('frontend.layouts.app')

@section('title', app_name() . ' | Terms and conditions')

@section('content')

		
		<div id="content-pro" style="padding-top: 0px; margin-top: 20px; z-index: 999;">
			
			<div id="membership-plan-background"  style="padding-top: 0px; font-size: 1.1em; text-align: justify;">
	  	 		<div class="">
		  	 		<div class="container">
					  
					   <div style="text-align: right;font-size: 11px;">Version: {{ $terms->version }}</div>
					   {!! $terms->content !!}
						<div class="clearfix"></div>
						
					</div><!-- close .container -->
	  	 		</div><!-- close .membership-width-container -->
			</div><!-- close #membership-plan-background -->
		</div><!-- close #content-pro -->

		{{-- LOGIN --}}
		@include('frontend.includes.login')

		{{-- UP --}}
    	<a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
@endsection


@push('after-styles')
<style type="text/css">
            .lst-kix_list_4-1 > li {
                counter-increment: lst-ctn-kix_list_4-1;
            }
            ol.lst-kix_list_3-1 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-2 {
                list-style-type: none;
            }
            .lst-kix_list_3-1 > li {
                counter-increment: lst-ctn-kix_list_3-1;
            }
            ol.lst-kix_list_3-3 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-4.start {
                counter-reset: lst-ctn-kix_list_3-4 0;
            }
            .lst-kix_list_5-1 > li {
                counter-increment: lst-ctn-kix_list_5-1;
            }
            ol.lst-kix_list_3-4 {
                list-style-type: none;
            }
            .lst-kix_list_2-1 > li {
                counter-increment: lst-ctn-kix_list_2-1;
            }
            ol.lst-kix_list_3-0 {
                list-style-type: none;
            }
            .lst-kix_list_1-1 > li {
                counter-increment: lst-ctn-kix_list_1-1;
            }
            ol.lst-kix_list_2-6.start {
                counter-reset: lst-ctn-kix_list_2-6 0;
            }
            .lst-kix_list_3-0 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) ". ";
            }
            ol.lst-kix_list_3-1.start {
                counter-reset: lst-ctn-kix_list_3-1 0;
            }
            .lst-kix_list_3-1 > li:before {
                content: "" counter(lst-ctn-kix_list_3-1, decimal) ". ";
            }
            .lst-kix_list_3-2 > li:before {
                content: "" counter(lst-ctn-kix_list_3-2, lower-latin) ". ";
            }
            ol.lst-kix_list_1-8.start {
                counter-reset: lst-ctn-kix_list_1-8 0;
            }
            .lst-kix_list_4-0 > li {
                counter-increment: lst-ctn-kix_list_4-0;
            }
            .lst-kix_list_5-0 > li {
                counter-increment: lst-ctn-kix_list_5-0;
            }
            ol.lst-kix_list_2-3.start {
                counter-reset: lst-ctn-kix_list_2-3 0;
            }
            .lst-kix_list_3-5 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) "." counter(lst-ctn-kix_list_3-1, decimal) "." counter(lst-ctn-kix_list_3-2, lower-latin) "." counter(lst-ctn-kix_list_3-3, lower-roman) "."
                    counter(lst-ctn-kix_list_3-4, decimal) "." counter(lst-ctn-kix_list_3-5, decimal) ". ";
            }
            .lst-kix_list_3-4 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) "." counter(lst-ctn-kix_list_3-1, decimal) "." counter(lst-ctn-kix_list_3-2, lower-latin) "." counter(lst-ctn-kix_list_3-3, lower-roman) "."
                    counter(lst-ctn-kix_list_3-4, decimal) ". ";
            }
            ol.lst-kix_list_1-5.start {
                counter-reset: lst-ctn-kix_list_1-5 0;
            }
            .lst-kix_list_3-3 > li:before {
                content: "(" counter(lst-ctn-kix_list_3-3, lower-roman) ") ";
            }
            ol.lst-kix_list_3-5 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-6 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-7 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-8 {
                list-style-type: none;
            }
            .lst-kix_list_3-8 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) "." counter(lst-ctn-kix_list_3-1, decimal) "." counter(lst-ctn-kix_list_3-2, lower-latin) "." counter(lst-ctn-kix_list_3-3, lower-roman) "."
                    counter(lst-ctn-kix_list_3-4, decimal) "." counter(lst-ctn-kix_list_3-5, decimal) "." counter(lst-ctn-kix_list_3-6, decimal) "." counter(lst-ctn-kix_list_3-7, decimal) "." counter(lst-ctn-kix_list_3-8, decimal) ". ";
            }
            .lst-kix_list_2-0 > li {
                counter-increment: lst-ctn-kix_list_2-0;
            }
            ol.lst-kix_list_5-3.start {
                counter-reset: lst-ctn-kix_list_5-3 0;
            }
            .lst-kix_list_2-3 > li {
                counter-increment: lst-ctn-kix_list_2-3;
            }
            .lst-kix_list_3-6 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) "." counter(lst-ctn-kix_list_3-1, decimal) "." counter(lst-ctn-kix_list_3-2, lower-latin) "." counter(lst-ctn-kix_list_3-3, lower-roman) "."
                    counter(lst-ctn-kix_list_3-4, decimal) "." counter(lst-ctn-kix_list_3-5, decimal) "." counter(lst-ctn-kix_list_3-6, decimal) ". ";
            }
            .lst-kix_list_4-3 > li {
                counter-increment: lst-ctn-kix_list_4-3;
            }
            .lst-kix_list_3-7 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) "." counter(lst-ctn-kix_list_3-1, decimal) "." counter(lst-ctn-kix_list_3-2, lower-latin) "." counter(lst-ctn-kix_list_3-3, lower-roman) "."
                    counter(lst-ctn-kix_list_3-4, decimal) "." counter(lst-ctn-kix_list_3-5, decimal) "." counter(lst-ctn-kix_list_3-6, decimal) "." counter(lst-ctn-kix_list_3-7, decimal) ". ";
            }
            ol.lst-kix_list_4-5.start {
                counter-reset: lst-ctn-kix_list_4-5 0;
            }
            ol.lst-kix_list_5-0.start {
                counter-reset: lst-ctn-kix_list_5-0 0;
            }
            .lst-kix_list_1-2 > li {
                counter-increment: lst-ctn-kix_list_1-2;
            }
            ol.lst-kix_list_3-7.start {
                counter-reset: lst-ctn-kix_list_3-7 0;
            }
            .lst-kix_list_5-2 > li {
                counter-increment: lst-ctn-kix_list_5-2;
            }
            ol.lst-kix_list_4-2.start {
                counter-reset: lst-ctn-kix_list_4-2 0;
            }
            .lst-kix_list_3-2 > li {
                counter-increment: lst-ctn-kix_list_3-2;
            }
            ol.lst-kix_list_2-2 {
                list-style-type: none;
            }
            ol.lst-kix_list_2-3 {
                list-style-type: none;
            }
            .lst-kix_list_5-0 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) ". ";
            }
            ol.lst-kix_list_2-4 {
                list-style-type: none;
            }
            ol.lst-kix_list_2-5 {
                list-style-type: none;
            }
            .lst-kix_list_5-4 > li {
                counter-increment: lst-ctn-kix_list_5-4;
            }
            .lst-kix_list_1-4 > li {
                counter-increment: lst-ctn-kix_list_1-4;
            }
            .lst-kix_list_4-4 > li {
                counter-increment: lst-ctn-kix_list_4-4;
            }
            ol.lst-kix_list_2-0 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-6.start {
                counter-reset: lst-ctn-kix_list_1-6 0;
            }
            ol.lst-kix_list_2-1 {
                list-style-type: none;
            }
            .lst-kix_list_4-8 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) "." counter(lst-ctn-kix_list_4-1, decimal) "." counter(lst-ctn-kix_list_4-2, lower-latin) "." counter(lst-ctn-kix_list_4-3, lower-roman) "."
                    counter(lst-ctn-kix_list_4-4, decimal) "." counter(lst-ctn-kix_list_4-5, decimal) "." counter(lst-ctn-kix_list_4-6, decimal) "." counter(lst-ctn-kix_list_4-7, decimal) "." counter(lst-ctn-kix_list_4-8, decimal) ". ";
            }
            .lst-kix_list_5-3 > li:before {
                content: "(" counter(lst-ctn-kix_list_5-3, lower-roman) ") ";
            }
            .lst-kix_list_4-7 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) "." counter(lst-ctn-kix_list_4-1, decimal) "." counter(lst-ctn-kix_list_4-2, lower-latin) "." counter(lst-ctn-kix_list_4-3, lower-roman) "."
                    counter(lst-ctn-kix_list_4-4, decimal) "." counter(lst-ctn-kix_list_4-5, decimal) "." counter(lst-ctn-kix_list_4-6, decimal) "." counter(lst-ctn-kix_list_4-7, decimal) ". ";
            }
            .lst-kix_list_5-2 > li:before {
                content: "" counter(lst-ctn-kix_list_5-2, lower-latin) ". ";
            }
            .lst-kix_list_5-1 > li:before {
                content: "" counter(lst-ctn-kix_list_5-1, decimal) ". ";
            }
            .lst-kix_list_5-7 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) "." counter(lst-ctn-kix_list_5-1, decimal) "." counter(lst-ctn-kix_list_5-2, lower-latin) "." counter(lst-ctn-kix_list_5-3, lower-roman) "."
                    counter(lst-ctn-kix_list_5-4, decimal) "." counter(lst-ctn-kix_list_5-5, decimal) "." counter(lst-ctn-kix_list_5-6, decimal) "." counter(lst-ctn-kix_list_5-7, decimal) ". ";
            }
            ol.lst-kix_list_5-6.start {
                counter-reset: lst-ctn-kix_list_5-6 0;
            }
            .lst-kix_list_5-6 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) "." counter(lst-ctn-kix_list_5-1, decimal) "." counter(lst-ctn-kix_list_5-2, lower-latin) "." counter(lst-ctn-kix_list_5-3, lower-roman) "."
                    counter(lst-ctn-kix_list_5-4, decimal) "." counter(lst-ctn-kix_list_5-5, decimal) "." counter(lst-ctn-kix_list_5-6, decimal) ". ";
            }
            .lst-kix_list_5-8 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) "." counter(lst-ctn-kix_list_5-1, decimal) "." counter(lst-ctn-kix_list_5-2, lower-latin) "." counter(lst-ctn-kix_list_5-3, lower-roman) "."
                    counter(lst-ctn-kix_list_5-4, decimal) "." counter(lst-ctn-kix_list_5-5, decimal) "." counter(lst-ctn-kix_list_5-6, decimal) "." counter(lst-ctn-kix_list_5-7, decimal) "." counter(lst-ctn-kix_list_5-8, decimal) ". ";
            }
            ol.lst-kix_list_4-1.start {
                counter-reset: lst-ctn-kix_list_4-1 0;
            }
            ol.lst-kix_list_4-8.start {
                counter-reset: lst-ctn-kix_list_4-8 0;
            }
            ol.lst-kix_list_3-3.start {
                counter-reset: lst-ctn-kix_list_3-3 0;
            }
            .lst-kix_list_5-4 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) "." counter(lst-ctn-kix_list_5-1, decimal) "." counter(lst-ctn-kix_list_5-2, lower-latin) "." counter(lst-ctn-kix_list_5-3, lower-roman) "."
                    counter(lst-ctn-kix_list_5-4, decimal) ". ";
            }
            .lst-kix_list_5-5 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) "." counter(lst-ctn-kix_list_5-1, decimal) "." counter(lst-ctn-kix_list_5-2, lower-latin) "." counter(lst-ctn-kix_list_5-3, lower-roman) "."
                    counter(lst-ctn-kix_list_5-4, decimal) "." counter(lst-ctn-kix_list_5-5, decimal) ". ";
            }
            ol.lst-kix_list_2-6 {
                list-style-type: none;
            }
            ol.lst-kix_list_2-7 {
                list-style-type: none;
            }
            ol.lst-kix_list_2-8 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-0.start {
                counter-reset: lst-ctn-kix_list_1-0 0;
            }
            .lst-kix_list_3-0 > li {
                counter-increment: lst-ctn-kix_list_3-0;
            }
            .lst-kix_list_3-3 > li {
                counter-increment: lst-ctn-kix_list_3-3;
            }
            ol.lst-kix_list_4-0.start {
                counter-reset: lst-ctn-kix_list_4-0 0;
            }
            .lst-kix_list_3-6 > li {
                counter-increment: lst-ctn-kix_list_3-6;
            }
            .lst-kix_list_2-5 > li {
                counter-increment: lst-ctn-kix_list_2-5;
            }
            .lst-kix_list_2-8 > li {
                counter-increment: lst-ctn-kix_list_2-8;
            }
            ol.lst-kix_list_3-2.start {
                counter-reset: lst-ctn-kix_list_3-2 0;
            }
            ol.lst-kix_list_5-5.start {
                counter-reset: lst-ctn-kix_list_5-5 0;
            }
            .lst-kix_list_2-2 > li {
                counter-increment: lst-ctn-kix_list_2-2;
            }
            ol.lst-kix_list_2-4.start {
                counter-reset: lst-ctn-kix_list_2-4 0;
            }
            ol.lst-kix_list_4-7.start {
                counter-reset: lst-ctn-kix_list_4-7 0;
            }
            ol.lst-kix_list_1-3 {
                list-style-type: none;
            }
            ol.lst-kix_list_5-0 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-4 {
                list-style-type: none;
            }
            .lst-kix_list_2-6 > li:before {
                content: "" counter(lst-ctn-kix_list_2-6, decimal) ". ";
            }
            .lst-kix_list_2-7 > li:before {
                content: "" counter(lst-ctn-kix_list_2-7, lower-latin) ". ";
            }
            .lst-kix_list_2-7 > li {
                counter-increment: lst-ctn-kix_list_2-7;
            }
            .lst-kix_list_3-7 > li {
                counter-increment: lst-ctn-kix_list_3-7;
            }
            ol.lst-kix_list_5-1 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-5 {
                list-style-type: none;
            }
            ol.lst-kix_list_5-2 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-6 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-0 {
                list-style-type: none;
            }
            .lst-kix_list_2-4 > li:before {
                content: "" counter(lst-ctn-kix_list_2-4, lower-latin) ". ";
            }
            .lst-kix_list_2-5 > li:before {
                content: "" counter(lst-ctn-kix_list_2-5, lower-roman) ". ";
            }
            .lst-kix_list_2-8 > li:before {
                content: "" counter(lst-ctn-kix_list_2-8, lower-roman) ". ";
            }
            ol.lst-kix_list_1-1 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-2 {
                list-style-type: none;
            }
            ol.lst-kix_list_5-4.start {
                counter-reset: lst-ctn-kix_list_5-4 0;
            }
            ol.lst-kix_list_4-6.start {
                counter-reset: lst-ctn-kix_list_4-6 0;
            }
            ol.lst-kix_list_5-1.start {
                counter-reset: lst-ctn-kix_list_5-1 0;
            }
            ol.lst-kix_list_3-0.start {
                counter-reset: lst-ctn-kix_list_3-0 0;
            }
            ol.lst-kix_list_5-7 {
                list-style-type: none;
            }
            ol.lst-kix_list_5-8 {
                list-style-type: none;
            }
            .lst-kix_list_5-7 > li {
                counter-increment: lst-ctn-kix_list_5-7;
            }
            ol.lst-kix_list_4-3.start {
                counter-reset: lst-ctn-kix_list_4-3 0;
            }
            ol.lst-kix_list_5-3 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-7 {
                list-style-type: none;
            }
            .lst-kix_list_4-7 > li {
                counter-increment: lst-ctn-kix_list_4-7;
            }
            ol.lst-kix_list_5-4 {
                list-style-type: none;
            }
            .lst-kix_list_1-7 > li {
                counter-increment: lst-ctn-kix_list_1-7;
            }
            ol.lst-kix_list_1-8 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-8.start {
                counter-reset: lst-ctn-kix_list_3-8 0;
            }
            ol.lst-kix_list_5-5 {
                list-style-type: none;
            }
            ol.lst-kix_list_5-6 {
                list-style-type: none;
            }
            ol.lst-kix_list_2-5.start {
                counter-reset: lst-ctn-kix_list_2-5 0;
            }
            .lst-kix_list_5-8 > li {
                counter-increment: lst-ctn-kix_list_5-8;
            }
            .lst-kix_list_4-0 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) ". ";
            }
            .lst-kix_list_2-6 > li {
                counter-increment: lst-ctn-kix_list_2-6;
            }
            .lst-kix_list_3-8 > li {
                counter-increment: lst-ctn-kix_list_3-8;
            }
            .lst-kix_list_4-1 > li:before {
                content: "" counter(lst-ctn-kix_list_4-1, decimal) ". ";
            }
            .lst-kix_list_4-6 > li {
                counter-increment: lst-ctn-kix_list_4-6;
            }
            ol.lst-kix_list_1-7.start {
                counter-reset: lst-ctn-kix_list_1-7 0;
            }
            .lst-kix_list_4-4 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) "." counter(lst-ctn-kix_list_4-1, decimal) "." counter(lst-ctn-kix_list_4-2, lower-latin) "." counter(lst-ctn-kix_list_4-3, lower-roman) "."
                    counter(lst-ctn-kix_list_4-4, decimal) ". ";
            }
            ol.lst-kix_list_2-2.start {
                counter-reset: lst-ctn-kix_list_2-2 0;
            }
            .lst-kix_list_1-5 > li {
                counter-increment: lst-ctn-kix_list_1-5;
            }
            .lst-kix_list_4-3 > li:before {
                content: "(" counter(lst-ctn-kix_list_4-3, lower-roman) ") ";
            }
            .lst-kix_list_4-5 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) "." counter(lst-ctn-kix_list_4-1, decimal) "." counter(lst-ctn-kix_list_4-2, lower-latin) "." counter(lst-ctn-kix_list_4-3, lower-roman) "."
                    counter(lst-ctn-kix_list_4-4, decimal) "." counter(lst-ctn-kix_list_4-5, decimal) ". ";
            }
            .lst-kix_list_4-2 > li:before {
                content: "" counter(lst-ctn-kix_list_4-2, lower-latin) ". ";
            }
            .lst-kix_list_4-6 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) "." counter(lst-ctn-kix_list_4-1, decimal) "." counter(lst-ctn-kix_list_4-2, lower-latin) "." counter(lst-ctn-kix_list_4-3, lower-roman) "."
                    counter(lst-ctn-kix_list_4-4, decimal) "." counter(lst-ctn-kix_list_4-5, decimal) "." counter(lst-ctn-kix_list_4-6, decimal) ". ";
            }
            ol.lst-kix_list_5-7.start {
                counter-reset: lst-ctn-kix_list_5-7 0;
            }
            .lst-kix_list_1-8 > li {
                counter-increment: lst-ctn-kix_list_1-8;
            }
            ol.lst-kix_list_1-4.start {
                counter-reset: lst-ctn-kix_list_1-4 0;
            }
            .lst-kix_list_5-5 > li {
                counter-increment: lst-ctn-kix_list_5-5;
            }
            .lst-kix_list_3-5 > li {
                counter-increment: lst-ctn-kix_list_3-5;
            }
            ol.lst-kix_list_1-1.start {
                counter-reset: lst-ctn-kix_list_1-1 0;
            }
            ol.lst-kix_list_4-0 {
                list-style-type: none;
            }
            .lst-kix_list_3-4 > li {
                counter-increment: lst-ctn-kix_list_3-4;
            }
            ol.lst-kix_list_4-1 {
                list-style-type: none;
            }
            ol.lst-kix_list_4-4.start {
                counter-reset: lst-ctn-kix_list_4-4 0;
            }
            ol.lst-kix_list_4-2 {
                list-style-type: none;
            }
            ol.lst-kix_list_4-3 {
                list-style-type: none;
            }
            .lst-kix_list_2-4 > li {
                counter-increment: lst-ctn-kix_list_2-4;
            }
            ol.lst-kix_list_3-6.start {
                counter-reset: lst-ctn-kix_list_3-6 0;
            }
            .lst-kix_list_5-3 > li {
                counter-increment: lst-ctn-kix_list_5-3;
            }
            ol.lst-kix_list_1-3.start {
                counter-reset: lst-ctn-kix_list_1-3 0;
            }
            ol.lst-kix_list_2-8.start {
                counter-reset: lst-ctn-kix_list_2-8 0;
            }
            ol.lst-kix_list_1-2.start {
                counter-reset: lst-ctn-kix_list_1-2 0;
            }
            ol.lst-kix_list_4-8 {
                list-style-type: none;
            }
            .lst-kix_list_1-0 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) ". ";
            }
            ol.lst-kix_list_4-4 {
                list-style-type: none;
            }
            ol.lst-kix_list_4-5 {
                list-style-type: none;
            }
            .lst-kix_list_1-1 > li:before {
                content: "" counter(lst-ctn-kix_list_1-1, decimal) ". ";
            }
            .lst-kix_list_1-2 > li:before {
                content: "" counter(lst-ctn-kix_list_1-2, lower-latin) ". ";
            }
            ol.lst-kix_list_2-0.start {
                counter-reset: lst-ctn-kix_list_2-0 0;
            }
            ol.lst-kix_list_4-6 {
                list-style-type: none;
            }
            ol.lst-kix_list_4-7 {
                list-style-type: none;
            }
            .lst-kix_list_1-3 > li:before {
                content: "(" counter(lst-ctn-kix_list_1-3, lower-roman) ") ";
            }
            .lst-kix_list_1-4 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) "." counter(lst-ctn-kix_list_1-1, decimal) "." counter(lst-ctn-kix_list_1-2, lower-latin) "." counter(lst-ctn-kix_list_1-3, lower-roman) "."
                    counter(lst-ctn-kix_list_1-4, decimal) ". ";
            }
            ol.lst-kix_list_3-5.start {
                counter-reset: lst-ctn-kix_list_3-5 0;
            }
            .lst-kix_list_1-0 > li {
                counter-increment: lst-ctn-kix_list_1-0;
            }
            .lst-kix_list_4-8 > li {
                counter-increment: lst-ctn-kix_list_4-8;
            }
            .lst-kix_list_1-6 > li {
                counter-increment: lst-ctn-kix_list_1-6;
            }
            .lst-kix_list_1-7 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) "." counter(lst-ctn-kix_list_1-1, decimal) "." counter(lst-ctn-kix_list_1-2, lower-latin) "." counter(lst-ctn-kix_list_1-3, lower-roman) "."
                    counter(lst-ctn-kix_list_1-4, decimal) "." counter(lst-ctn-kix_list_1-5, decimal) "." counter(lst-ctn-kix_list_1-6, decimal) "." counter(lst-ctn-kix_list_1-7, decimal) ". ";
            }
            ol.lst-kix_list_5-8.start {
                counter-reset: lst-ctn-kix_list_5-8 0;
            }
            ol.lst-kix_list_2-7.start {
                counter-reset: lst-ctn-kix_list_2-7 0;
            }
            .lst-kix_list_1-3 > li {
                counter-increment: lst-ctn-kix_list_1-3;
            }
            .lst-kix_list_1-5 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) "." counter(lst-ctn-kix_list_1-1, decimal) "." counter(lst-ctn-kix_list_1-2, lower-latin) "." counter(lst-ctn-kix_list_1-3, lower-roman) "."
                    counter(lst-ctn-kix_list_1-4, decimal) "." counter(lst-ctn-kix_list_1-5, decimal) ". ";
            }
            .lst-kix_list_1-6 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) "." counter(lst-ctn-kix_list_1-1, decimal) "." counter(lst-ctn-kix_list_1-2, lower-latin) "." counter(lst-ctn-kix_list_1-3, lower-roman) "."
                    counter(lst-ctn-kix_list_1-4, decimal) "." counter(lst-ctn-kix_list_1-5, decimal) "." counter(lst-ctn-kix_list_1-6, decimal) ". ";
            }
            .lst-kix_list_5-6 > li {
                counter-increment: lst-ctn-kix_list_5-6;
            }
            .lst-kix_list_2-0 > li:before {
                content: "SCHEDULE " counter(lst-ctn-kix_list_2-0, upper-latin) "  ";
            }
            .lst-kix_list_2-1 > li:before {
                content: "" counter(lst-ctn-kix_list_2-1, lower-latin) ". ";
            }
            ol.lst-kix_list_2-1.start {
                counter-reset: lst-ctn-kix_list_2-1 0;
            }
            .lst-kix_list_4-5 > li {
                counter-increment: lst-ctn-kix_list_4-5;
            }
            .lst-kix_list_1-8 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) "." counter(lst-ctn-kix_list_1-1, decimal) "." counter(lst-ctn-kix_list_1-2, lower-latin) "." counter(lst-ctn-kix_list_1-3, lower-roman) "."
                    counter(lst-ctn-kix_list_1-4, decimal) "." counter(lst-ctn-kix_list_1-5, decimal) "." counter(lst-ctn-kix_list_1-6, decimal) "." counter(lst-ctn-kix_list_1-7, decimal) "." counter(lst-ctn-kix_list_1-8, decimal) ". ";
            }
            .lst-kix_list_2-2 > li:before {
                content: "" counter(lst-ctn-kix_list_2-2, lower-roman) ". ";
            }
            .lst-kix_list_2-3 > li:before {
                content: "" counter(lst-ctn-kix_list_2-3, decimal) ". ";
            }
            .lst-kix_list_4-2 > li {
                counter-increment: lst-ctn-kix_list_4-2;
            }
            ol.lst-kix_list_5-2.start {
                counter-reset: lst-ctn-kix_list_5-2 0;
            }
            ol {
                margin: 0;
                padding: 0;
            }
            table td,
            table th {
                padding: 0;
            }
            .c8 {
                -webkit-text-decoration-skip: none;
                color: #c4c4c5;
                font-weight: 700;
                text-decoration: underline;
                vertical-align: baseline;
                text-decoration-skip-ink: none;
                
                
                font-style: normal;
            }
            .c3 {
                margin-left: 18pt;
                padding-top: 0pt;
                padding-left: 4.7pt;
                padding-bottom: 11pt;
                
                page-break-after: avoid;
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .c7 {
                margin-left: 72pt;
                padding-top: 0pt;
                padding-left: 14.4pt;
                padding-bottom: 11pt;
                
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .c2 {
                margin-left: 40.7pt;
                padding-top: 0pt;
                padding-left: -1pt;
                padding-bottom: 11pt;
                
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .c4 {
                color: #c4c4c5;
                font-weight: 400;
                text-decoration: none;
                vertical-align: baseline;
                
                
                font-style: italic;
            }
            .c6 {
                color: #c4c4c5;
                font-weight: 700;
                text-decoration: none;
                vertical-align: baseline;
                
                
                font-style: normal;
            }
            .c0 {
                color: #c4c4c5;
                font-weight: 400;
                text-decoration: none;
                vertical-align: baseline;
                
                
                font-style: normal;
            }
            .c11 {
                padding-top: 0pt;
                padding-bottom: 6pt;
                
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .c1 {
                padding-top: 0pt;
                
                margin-bottom: -4px;
                orphans: 2;
                widows: 2;
                text-align: center;
            }
            .c9 {
                padding-top: 0pt;
                padding-bottom: 0pt;
                
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .c12 {
                background-color: #c4c4c5;
                max-width: 432pt;
                padding: 72pt 90pt 72pt 90pt;
            }
            .c5 {
                padding: 0;
                margin: 0;
            }
            .c10 {
                font-weight: 700;
            }
            .c13 {
                height: 10pt;
            }
            .title {
                padding-top: 24pt;
                color: #c4c4c5;
                font-weight: 700;
                font-size: 36pt;
                padding-bottom: 6pt;
                
                
                page-break-after: avoid;
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .subtitle {
                padding-top: 18pt;
                color: #c4c4c5;
                
                padding-bottom: 4pt;
                
                
                page-break-after: avoid;
                font-style: italic;
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            li {
                color: #c4c4c5;
                
                
            }
            
        </style>
@endpush
@push('after-scripts')
	<script>
        $(document).ready(function() {

			
        });
    </script>
@endpush