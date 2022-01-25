@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . $title)

@section('content')

	<div id="content-pro" style="padding-top: 0px; margin-top: 20px; z-index: 999;">
		<div class="container">
			@if(isset($relatedtags) && $relatedtags->isNotEmpty())
				<div class="pb-2" style="margin-bottom: 24px;">
					<div style="padding-bottom: 4px;"><strong style="margin-left: 8px;">Related tags:</strong></div>
					<ul id="video-post-meta-list" class="m-0">
						@foreach($relatedtags as $tag_slug => $tag_title)
							<li id="video-post-meta-rating" style="line-height: 1.5; margin:0; margin-bottom: 10px; padding-left: 4px; padding-right: 4px;"><span style="font-size: 11px;"><a href="{{ route('frontend.tag', $tag_slug) }}">{{ $tag_title }}</a></span></li>
						@endforeach
					</ul>
					<div class="clearfix"></div>
				</div>
			@endif

			@php
				if (isset($use_categories) && $use_categories) {
					$filters['categories'] = $categories->pluck('title', 'id')->normalSort()->all();
					ksort($filters);
				}
				if (isset($use_alltags) && $use_alltags) {
					$filters['tags'] = collect($alltags)->normalSort()->all();
					ksort($filters);
				}
				isset($search) || $search = false;
				isset($recommend) || $recommend = [];
			@endphp
			@render('frontend.includes.filters', compact('filters', 'recommend', 'requires', 'sorting', 'heading', 'search'))
		</div>
	</div>    
	
	{{-- hack clean me --}}
	<br /><br /><br /><br /><br /><br /><br /><br />

	{{-- UP --}}
	<a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

@endsection

@push('after-scripts')

	{{--<!-- This isn't a video player page and should not have keyboard controls for videos. -->--}}
	<script>$(document).ready(function() { $(document).off('keyup'); });</script>

@endpush