@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . $account->full_name)

@section('content')

	@if($account->video)
		<div id="video-page-title-pro" class="video-embed-height-adjust mb-0 p-4" style="background-image: url('');">
				<div id="video-embedded-container" class="pb-0" style="max-width: 1200px; padding-top: 0px;">
					<video id="Video-Vayvo-Single" style="height: auto; width: 100%;" poster="{{ $account->poster ? config('ac.CDN_MEDIA') . $account->poster : '' }}" data-autoresize="fit" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true, "playbackRates": [0.5, 1, 1.5, 2]}'>
						<source src="{{ config('ac.CDN_MEDIA') . $account->video }}" type="video/mp4">
					</video>
				</div><!-- clolse #video-embedded-container -->
				<!--<div id="video-page-title-gradient-base"></div>-->
		</div><!-- close #video-page-title-pro -->
	@endif

	<div id="content-pro" class="pt-0" style="{{ $account->video ? 'background: linear-gradient(to bottom, #303030 0px, #08070e 200px);' : '' }}">
		<div class="container">

			<div id="video-post-container">

				@if($account->user->hasRole('mentor'))
					<a href="{{ route('frontend.mentors') }}" style="float: right;"><i class="fas fa-angle-left"></i> Back to reviewers page</a>
				@else
					<a href="{{ route('frontend.index') }}" style="float: right;"><i class="fas fa-angle-left"></i> Back to home page</a>
				@endif

				<h1 class="video-post-heading-title">{{ $account->full_name }}</h1>

				@if($account->tags)
					<ul class="list-inline pl-1 mb-4" style="user-select: none;">
						@foreach($account->tags as $tag)
							<li class="list-inline-item mb-2">
								<a class="btn btn-tag" href="{{ route('frontend.tag', urlencode($tag->title)) }}">{{ $tag->title }}</a>
							</li>
						@endforeach
					</ul>
				@endif

				@if($account->bio || $account->cv)
					<div id="vayvo-video-post-content">
						@if($account->bio)
							<h2>Biography</h2>
							<p>{!! $account->bio !!}</p>
						@endif
						@if($account->cv)
							<h2>Work history</h2>
							<p>{!! $account->cv !!}</p>
						@endif
					</div><!-- #vayvo-video-post-content -->
				@endif

				@if(!empty($tabs))
					<div id="video-more-like-this-details-section" class="{{ ($account->bio || $account->cv) ? '' : 'border-top-0 pt-0 mt-0' }}" style="padding-bottom: 10px;">
						<h3 id="more-videos-heading">Content by {{ $account->full_name }}</h3>
						<div id="account-contents">
							<ul class="nav nav-pills nav-blocks nav-justified" id="tablist" role="tablist">
								@foreach($tabs as $tab)
									<li class="nav-item" role="presentation">
										<a class="{{ $loop->first ? 'nav-link active' : 'nav-link' }}" id="{{ 'tab-'.$tab['id'] }}" data-toggle="pill" href="{{ '#'.$tab['id'] }}" role="tab" aria-controls="{{ $tab['id'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $tab['title'] }}</a>
									</li>
								@endforeach
							</ul>
							<div class="tab-content">
								@foreach($tabs as $tab)
									<div class="{{ $loop->first ? 'tab-pane active' : 'tab-pane' }}" id="{{ $tab['id'] }}" role="tabpanel" aria-labelledby="{{ 'tab-'.$tab['id'] }}">
										<div class="infinite-scrolling row" data-url="{{ $tab['url'] }}"></div>
										<div class="infinite-loading"></div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				@endif

			</div><!-- close #video-post-container -->

			<div id="video-post-sidebar">

				<div class="content-sidebar-section video-sidebar-section-length">
					
					<div class="content-sidebar-short-description">
						<h4 class="content-sidebar-sub-header">Author</h4>
						{{ $account->full_name }}
						@if($account->photo)
							<img src="{{ config('ac.CDN_MEDIA') }}{{ $account->photo }}" alt="{{ $account->full_name }}">
						@endif
					</div>

				</div><!-- close .content-sidebar-section -->

				@if($account->position)
					<div class="content-sidebar-section video-sidebar-section-length">
						<h4 class="content-sidebar-sub-header">Current position</h4>
						<div class="content-sidebar-short-description">{{ $account->position }}</div>
					</div><!-- close .content-sidebar-section -->
				@endif

				@if(count($account->languages))
					<div class="content-sidebar-section video-sidebar-section-length" style="display: grid;">
						<h4 class="content-sidebar-sub-header">Spoken {{ Str::plural('language', count($account->languages)) }}:</h4>
						<div class="content-sidebar-short-description">
							<ul id="video-post-meta-list" class="m-0" style="padding-top: 10px; padding-bottom: 26px;">
								@foreach($account->languages as $lg)
									<li id="video-post-meta-rating"><span>{{ $lg->title }}</span></li>
								@endforeach
							</ul>
						</div>
					</div><!-- close .content-sidebar-section -->
				@endif
				
				@if($account->user->hasRole('mentor'))
					@if($account->bookeduntil > now())
						<div class="content-sidebar-section video-sidebar-section-length">
							<h4 class="content-sidebar-sub-header">Available</h4>
							{{ $account->bookeduntil->format('jS \\of F Y') }}
						</div><!-- close .content-sidebar-section -->
					@else
						<div class="content-sidebar-section video-sidebar-section-length text-center">
							<a class="btn btn-block" href="{{ route('frontend.user.order', ['reviewer' => $account->slug]) }}"@guest data-toggle="modal" data-target="#LoginModal" @endguest>	
								<i class="fas fa-shopping-cart"></i>
								<span>Order a review</span>
							</a>
						</div><!-- close .content-sidebar-section -->
					@endif
				@endif
				
				<div class="clearfix"></div>

			</div><!-- close #video-post-sidebar -->
			
			<div class="clearfix"></div>

		</div><!-- close .container -->	
	</div><!-- close #content-pro -->

	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
	
@endsection

@push('after-styles')

	<style>
		.nav.nav-pills.nav-blocks {
			border-bottom: 1px solid #212027;
			margin-bottom: 25px;
		}
		.nav.nav-pills.nav-blocks .nav-item .nav-link {
			background: none;
			border-radius: 0;
			border-top: 2px solid transparent;
			color: #fff;
			font-family: 'Fira Sans Condensed', sans-serif;
			font-size: 16px;
			font-weight: 500;
			padding-top: 22px;
			padding-bottom: 22px;
		}
		.nav.nav-pills.nav-blocks .nav-item .nav-link.active {
			background: rgba(255, 255, 255, .06);
		}
	</style>

@endpush

@push('after-scripts')

	<script src="{{ url('js/jquery-infinite.js') }}"></script>
	<script>
		$(document).ready(function() {
			$('#account-contents a[data-toggle="pill"]')
				.each(function(index, element) {
					var target = element.getAttribute('href');
					var $scrolling = $(target + ' .infinite-scrolling');
					$scrolling.infinite({
						loader: target + ' .infinite-loading',
						params: {
							after: '</div>',
							before: '<div class="col col-12 col-md-6 col-lg-6">',
							show: 6
						},
						paused: !$(element).hasClass('active'),
						url: $scrolling.attr('data-url')
					});
				})
				.on('hide.bs.tab', function(event) {
					var target = event.target.getAttribute('href');
					var $scrolling = $(target + ' .infinite-scrolling');
					$scrolling.data('infinite').pause();
				})
				.on('shown.bs.tab', function(event) {
					var target = event.target.getAttribute('href');
					var $scrolling = $(target + ' .infinite-scrolling');
					$scrolling.data('infinite').unpause().handleScroll();
				});

			$('#account-contents')
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