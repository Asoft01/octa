@extends('frontend.layouts.app')

@section('title', app_name())

@section('content')

	@if($account->video)
		<div id="video-page-title-pro" class="video-embed-height-adjust mb-0 p-4" style="background-image: url('');">
				<div id="video-embedded-container" class="pb-0" style="max-width: 1200px; padding-top: 0px;">
					<video id="Video-Vayvo-Single" style="height: auto; width: 100%" poster="{{ empty($account->poster) ? '' : config('ac.CDN_MEDIA') }}" data-autoresize="fit" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true, "playbackRates": [0.5, 1, 1.5, 2]}'>
						<source src="{{ config('ac.CDN_MEDIA') . $account->video }}" type="video/mp4">
					</video>
				</div><!-- clolse #video-embedded-container -->
				<!--<div id="video-page-title-gradient-base"></div>-->
		</div><!-- close #video-page-title-pro -->
	@endif

	<div id="content-pro" class="pt-0" style="{{ $account->video ? 'background: linear-gradient(to bottom, #303030 0px, #08070e 200px);' : '' }}">
		<div class="container">

			<div id="video-post-container">

				<a href="{{ route('frontend.index') }}" style="float: right;"><i class="fas fa-angle-left"></i> Back to home page</a>

				<h1 class="video-post-heading-title">{{ $account->getContributor() }}</h1>

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

				<div id="video-more-like-this-details-section" class="{{ ($account->bio || $account->cv) ? '' : 'border-top-0 pt-0 mt-0' }}" style="padding-bottom: 10px;">
					<h3 id="more-videos-heading">Videos by {{ $account->user->name }}</h3>
					<div class="infinite-scrolling row"></div>
					<div class="infinite-loading"></div>
				</div>

			</div><!-- close #video-post-container -->

			<div id="video-post-sidebar">

				<div class="content-sidebar-section video-sidebar-section-length">
					
					<div class="content-sidebar-short-description">
						@if($account->photo)
							<img src="{{ config('ac.CDN_MEDIA') }}{{ $account->photo }}" alt="{{ $account->user->name }}">
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
							<ul id="video-post-meta-list" style="margin-bottom: 24px; padding-top: 10px; padding-bottom: 26px; margin: 0px;">
								@foreach($account->languages as $lg)
									<li id="video-post-meta-rating"><span>{{ $lg->title }}</span></li>
								@endforeach
							</ul>
						</div>
					</div><!-- close .content-sidebar-section -->
				@endif
				
				<div class="clearfix"></div>

			</div><!-- close #video-post-sidebar -->
			
			<div class="clearfix"></div>

		</div><!-- close .container -->	
	</div><!-- close #content-pro -->


	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
	
@endsection

@push('after-scripts')

	<script src="{{ url('js/jquery-infinite.js') }}"></script>
	<script>
		$(document).ready(function() {
			$('.infinite-scrolling').infinite({
				loader: '.infinite-loading',
				params: {
					after: '</div>',
					before: '<div class="col col-12 col-md-6 col-lg-6">',
					show: 6
				},
				url: '{{ route("frontend.contributor.infinite", $account->slug) }}'
			});

			$('body').on('mouseenter', ".replaceImgVid", function() {
				var hv = $(this).find('video:first');
				if(hv.length) {
					hv[0].load();
					hv[0].play();
					$(".replaceImgVid").find('video:first').bind("playing", function() {
						$(this).parent().find('img:first').css("display", "none");
						$(this).css("display", "");

					});
				}
			});

			$('body').on('mouseleave', ".replaceImgVid", function() {
					var hv = $(this).find('video:first');
					if(hv.length) {
						$(this).find('img:first').css("display", "");
						hv.css("display", "none");
						hv[0].pause();
					}
				}
			);
		});
	</script>

@endpush