@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . $title)

@section('content')

	<div id="content-pro" style="{{ Auth::check() ? 'padding-top: 0px; margin-top: 20px; z-index: 999;' : 'padding-top: 124px;' }}">
		<div class="container">
			<div class="row">
				<div class="col">
					<h2 class="post-list-heading">{{ isset($heading) ? $heading : $title }}</h2>
				</div>
				@isset($total)
					<div class="col col-auto text-right pt-1">
						<span>{{ is_string($total) ? $total : "{$total} items" }}</span>
					</div>
				@endisset
			</div>
			@if(isset($tags) && $tags->isNotEmpty())
				<div class="pb-2">
					<ul id="video-post-meta-list" class="m-0">
						<li>Related tags</li>
						@foreach($tags as $tag_slug => $tag_title)
							<li id="video-post-meta-rating"><span><a href="{{ route('frontend.tag', $tag_slug) }}">{{ $tag_title }}</a></span></li>
						@endforeach
					</ul>
					<div class="clearfix"></div>
				</div>
			@endif
			<div class="infinite-scrolling row"></div>
			<div class="infinite-loading"></div>
			<div class="clearfix"></div>
		</div>
	</div>             

	{{-- UP --}}
	<a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

	{{-- LOGIN --}}
	@includeWhen(Auth::guest(), 'frontend.includes.login')

@endsection

@push('after-styles')

	<style>
		ul#video-post-meta-list li { line-height: 2; }
		.infinite-scrolling.row { margin: 0 -8px; }
		.infinite-scrolling.row > .col { padding: 0 8px; }
	</style>

@endpush

@push('after-scripts')

	<script src="{{ url('js/jquery-infinite.js') }}"></script>
	<script>
		$(document).ready(function() {
			$('.infinite-scrolling')
				.infinite({
					loader: '.infinite-loading',
					params: {
						after: '</div>',
						before: '<div class="col col-12 col-md-6 col-lg-3">',
						show: 12
					},
					url: '{{ $infinite_url }}'
				})
				.on('mouseenter', '.replaceImgVid', function() {
					var $video = $(this).find('video:first');
					if ($video.length) {
						$video[0].load();
						$video[0].play();
						$video.bind('playing', function() {
							$(this).css('display', '').parent().find('img').css('display', 'none');
						});
					}
				})
				.on('mouseleave', '.replaceImgVid', function() {
					var $video = $(this).find('video:first');
					if ($video.length) {
						$(this).find('img').css('display', '');
						$video.css('display', 'none')[0].pause();
					}
				});
		});
	</script>

@endpush