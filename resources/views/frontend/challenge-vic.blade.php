@extends('frontend.layouts.app')

@section('title', app_name() . ' | Live!')

@section('content')
	<div id="content-pro" style="padding-top: 0px; margin-top: 20px;">

		<div id="membership-plan-background" style="padding-top: 0; font-size: 1.1em; text-align: justify;">
			<div class="">
				<div class="container" style="text-align: initial !important;">

						@if($embed)
							<div id="twitch-embed"></div>
						@else
							<div id="twitch-embed"></div>
							<video id="liveintro" style="margin-bottom: 32px; height: auto; width: 100%" preload="auto" poster="https://cdn.agora.community/live/live_poster.jpg " data-autoresize="fit" class="video-js vjs-default-skin vjs-big-play-centered" controls data-setup='{"fluid": true, "playbackRates": [0.5, 1, 1.5, 2]}'>
								<source src="https://cdn.agora.community/live/live.mp4" type="video/mp4">
							</video>
						@endif
						
						@if($streams->isNotEmpty())
                            <div class="row my-3" style="margin-bottom: 30px;">	
                                @foreach ($streams as $stream)
                                    @php
                                        isset($show_details) || $show_details = true;
                                        isset($lazyload) || $lazyload = true;
                                        $image = \Twitch::resizeChannelImage($stream->thumbnail_url, 320, 180);
                                    @endphp
                                    <div class="col col-12 col-md-6 col-lg-3 mb-3">
                                        <div class="container-fluid" data-content-id="{{ $stream->id }}">
                                            <div class="ac-video-index-container">
                                                <a class="tcs" href="#{{ $stream->user_login }}" data-channel="{{ $stream->user_login }}">
                                                    <div class="ac-video-feaured-image">
                                                        <div class="embed-responsive embed-responsive-16by9">
                                                            <div class="embed-responsive-item">
                                                                @if($lazyload)
                                                                    <img class="img-twitch position-relative" src="{{ $image }}" alt="{{ $stream->user_name }} ({{ $stream->isStreaming ? 'Online' : 'Offline' }})">
                                                                @else
                                                                    <img class="img-twitch position-relative" src="{{ $image }}" alt="{{ $stream->user_name }} ({{ $stream->isStreaming ? 'Online' : 'Offline' }})">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($stream->isStreaming)
                                                        <div class="timeoverlay">
                                                            <i class="fas fa-users" aria-hidden="true" style="color: rgba(255, 255, 255, .5);"></i>
                                                            <span>{{ $stream->viewer_count }}</span>
                                                        </div>
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="progression-video-index-content h-auto">
                                                <div class="progression-video-index-table d-block">
                                                    <div class="progression-video-index-vertical-align">
                                                        @if($stream->isStreaming)
                                                            <h2 class="progression-video-title">
                                                                <a class="text-truncate d-block w-100 tcs" href="#{{ $stream->user_login }}" data-channel="{{ $stream->user_login }}">{{ $stream->title }}</a>
                                                            </h2>
                                                            <p class="mb-0"><a href="https://www.twitch.tv/{{ $stream->user_login }}" rel="external" target="_blank">{{ $stream->user_name }}</a></p>
                                                        @else
                                                            <h2 class="progression-video-title">
                                                                <a class="text-truncate d-block w-100" href="https://www.twitch.tv/{{ $stream->user_login }}" rel="external" target="_blank">{{ $stream->user_name }}</a>
                                                            </h2>
                                                            <p class="mb-0">Offline</p>
                                                        @endif
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach	
                            </div>
						@endif

						
						
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

	@if($flipdown)
		<link rel="stylesheet" href="{{ url('css/flipdown.min.css') }}">
		{{--<!-- <script>console.log("{{ now()->tz($tz)->diffInDays($promote->started_at->tz($tz)) }}");</script> -->--}}
	@endif
	<style>
		#flipdown .rotor-group-heading {
			visibility: hidden;
			pointer-events: none;
		}
	</style>

@endpush

@push('after-scripts')

	<script src="https://embed.twitch.tv/embed/v1.js"></script>
	<script>
		var twitchHeight = 480;
		var twitchPlayer = null;

		function createTwitchPlayer(channel) {
			return new Twitch.Embed("twitch-embed", {
				width: "100%",
				height: twitchHeight,
				channel: channel,
				parent: ["wip.agora.community", "agora.community"]
			});
		};

		$(".tcs").click(function(e) {
			e.preventDefault();
			if (twitchPlayer === null) {
				$body = $('body');
				$intro = $("#liveintro").hide().find('video').trigger('pause').end();
				twitchPlayer = createTwitchPlayer($(this).data('channel'));
				$body.scrollTop($body.scrollTop() - Math.min($intro.outerHeight() - twitchHeight, 0));
			} else {
				twitchPlayer.setChannel($(this).data('channel'));
			}
		});

		@if($embed)
			@if($ongoing && $exhibit)
				twitchPlayer = createTwitchPlayer("{{ $exhibit->user_login }}");
			@else
				twitchPlayer = createTwitchPlayer("{{ $streams->where('isStreaming', 1)->first()->user_login }}");
			@endif
		@endif
	</script>

	<script>
		$(document).ready(function() {
			$('.img-twitch').on('error', function() {
				var fallback = '{{ \Twitch::fallbackChannelImage() }}';
				if (this.src != fallback) {
					this.src = fallback;
				}
			});
		});
	</script>

	@if($flipdown)
		<script src="{{ url('js/flipdown.min.js') }}"></script>
		<script>
			$(document).ready(function() {
				var timeout = 500;
			});
		</script>
	@endif

@endpush

