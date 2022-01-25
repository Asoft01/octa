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
							<div class="container custom-gutters-pro mt-3 mb-4">
								<div class="ac-elementor-carousel-container ac-always-arrows-on">
									<div class="owl-carousel twitch-stream-carousel">

										@foreach($streams as $stream)
											@render('frontend.includes.twitchitem', ['stream' => $stream])
										@endforeach

									</div><!-- close .owl-carousel -->
								</div><!-- close .ac-elementor-carousel-container -->
								<div class="clearfix"></div>
							</div><!-- close .container.custom-gutters-pro -->
							
							<h2 style="clear: both; margin-top: 8px;">Upcoming events</h2>
						@else
							<h2 style="clear: both; margin-top: 32px;">Upcoming events</h2>
						@endif

						@if($promote)
							<div class="w-100 text-center" style="margin-bottom: 18px; padding: 32px; padding-bottom: 8px; border: solid 1px #333; border-radius: 12px;">
								<h1 class="mb-0">{{ $promote->title }}</h1>
								<p class="lead mb-0">{{ $promote->started_at->tz($tz)->format('l jS F h:i a') }} &#8211; {{ $promote->ended_at->tz($tz)->format('h:i a (T)') }}</p>
								@if($flipdown)
									<div style="padding: 0 2px; margin: 0 -32px;">
										<div id="flipdown" class="flipdown user-select-none mx-auto my-0"></div>
										<form id="flipdown-form" class="d-none" action="{{ request()->url() }}" method="POST">
											@csrf
											<input class="d-none" name="flipdown-ended" type="hidden" value="1" readonly>
										</form>
									</div>
								@endif
								<div id="description" class="pt-4">
									<div class="text-reset">{!! $promote->description !!}</div>
								</div>
							</div>
						@endif

						@auth
							
							@if($schedules->isNotEmpty())
								<div id="schedule-accordion" class="accordion">
									
									<div style="float: right;">
										<img src="https://i.pinimg.com/originals/6e/09/90/6e099088b3deb805b68d83676af6f067.png" style="max-width: 36px;"> <a href="https://calendar.google.com/calendar/embed?src=c_r9up0g99i1dck6mdkb3cohe08k%40group.calendar.google.com&ctz={{ urlencode($tz) }}">Google calendar</a>
										<img src="https://cdn1.iconfinder.com/data/icons/appicns/513/appicns_iCal.png" style="max-width: 49px;padding-left: 12px;"> <a href="https://calendar.google.com/calendar/ical/c_r9up0g99i1dck6mdkb3cohe08k%40group.calendar.google.com/public/basic.ics">iCal</a>
									</div>
									
									@foreach($schedules as $schedule)
										@php
											$toggle = "schedule-button-collapse-{$loop->index}";
											$collapse = "schedule-collapse-{$loop->index}";
											$dropdown = "schedule-dropdown-{$loop->index}";
										@endphp
										<div>
											<div class="dropdown d-inline-block">
												<a id="{{ $dropdown }}" href="{{ route('frontend.live.invite', [ 'slug' => $schedule->slug, 'format' => 'download' ]) }}" role="button" data-toggle="dropdown" data-offset="testfunc" aria-haspopup="true" aria-expanded="false">
													<i class="far fa-calendar-plus fa-fw" aria-hidden="true"></i>
													<span>{{ $schedule->started_at->tz($tz)->format('l jS F h:i a') }} &#8211; {{ $schedule->ended_at->tz($tz)->format('h:i a (T)') }}</span>
												</a>
												<div class="dropdown-menu" aria-labelledby="{{ $dropdown }}">
													<div class="h6 dropdown-header">Add event to my calendar...</div>
													<a class="dropdown-item" href="{{ route('frontend.live.invite', [ 'slug' => $schedule->slug, 'format' => 'google' ]) }}" rel="external" target="_blank">
														<i class="fab fa-google fa-fw mr-1"></i>
														<span>Google</span>
													</a>
													{{--<!--
														<a class="dropdown-item" href="{{ route('frontend.live.invite', [ 'slug' => $schedule->slug, 'format' => 'download' ]) }}" type="text/calendar" download>
															<i class="fab fa-apple fa-fw mr-1"></i>
															<span>iOS</span>
														</a>
													-->--}}
													<a class="dropdown-item" href="{{ route('frontend.live.invite', [ 'slug' => $schedule->slug, 'format' => 'outlook' ]) }}" rel="external" target="_blank">
														<i class="fab fa-microsoft fa-fw mr-1"></i>
														<span>Outlook</span>
													</a>
													<a class="dropdown-item" href="{{ route('frontend.live.invite', [ 'slug' => $schedule->slug, 'format' => 'download' ]) }}" type="text/calendar" download>
														<i class="fas fa-file-download fa-fw mr-1"></i>
														<span>ICS (Apple)</span>
													</a>
												</div>
											</div>
											<span>|</span>
											<a id="{{ $toggle }}" type="button" href="#{{ $collapse }}" data-toggle="collapse" data-target="#{{ $collapse }}" aria-expanded="false" aria-controls="{{ $collapse }}">
												<span style="font-weight: bold;">{{ $schedule->title }}</span>
												<i class="fas fa-long-arrow-alt-down fa-sm fa-fw" aria-hidden="true"></i>
											</a>
										</div>
										<div id="{{ $collapse }}" class="collapse" aria-labelledby="{{ $toggle }}" data-parent="#schedule-accordion">
											<div class="text-reset">{!! $schedule->description !!}</div>
										</div>
									@endforeach
								</div>
							@else
								<span>There are no scheduled live events for now.</span>
							@endif
						@else
							<div class="pt-2 text-center">
								<a class="btn btn-primary btn-lg rounded-pill" href="{{ url()->current() }}" data-toggle="modal" data-target="#LoginModal">
									<span>Login to see our calendar of live events</span>
								</a>
							</div>
						@endauth
						
						@if($archive)
							<h2 style="margin-top: 32px;">Previous events</h2>
							<div class="infinite-scrolling row"></div>
							<div class="infinite-loading"></div>
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
		#schedule-accordion a[aria-expanded="true"] {
			color: #74d6ff;
		}
		#flipdown .rotor-group-heading {
			visibility: hidden;
			pointer-events: none;
		}
		.owl-disabled .owl-nav button,
		.owl-disabled .owl-item.cloned {
			visibility: hidden;
			pointer-events: none;
		}
		.owl-carousel .owl-nav.disabled,
		.owl-carousel .owl-dots.disabled {
			display: none !important;
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
			$('.twitch-stream-carousel')
				.on('resized.owl.carousel', function(e) {
					var $element = $(this);
					var owl = $element.data('owl.carousel');
					if (owl._items.length <= owl.settings.items) {
						if (!$element.hasClass('owl-disabled')) {
							owl.settings.loop = false;
							owl.settings.rewind = false;

							// See https://github.com/OwlCarousel2/OwlCarousel2/blob/develop/src/js/owl.carousel.js#L731
							owl.$element.removeClass(owl.options.dragClass);
							owl.$stage.off('mousedown.owl.core');
							//owl.$stage.off('dragstart.owl.core selectstart.owl.core');

							$element.trigger('to.owl.carousel', 0);
							$element.addClass('owl-disabled');
						}
					} else {
						if ($element.hasClass('owl-disabled')) {
							owl.settings.loop = owl.options.loop;
							owl.settings.rewind = owl.options.rewind;

							// See https://github.com/OwlCarousel2/OwlCarousel2/blob/develop/src/js/owl.carousel.js#L731
							if (owl.settings.mouseDrag) {
								owl.$element.addClass(owl.options.dragClass);
								owl.$stage.on('mousedown.owl.core', $.proxy(owl.onDragStart, owl));
								//owl.$stage.on('dragstart.owl.core selectstart.owl.core', function() { return false });
							}
							if (owl.settings.touchDrag){
								owl.$stage.on('touchstart.owl.core', $.proxy(owl.onDragStart, owl));
								owl.$stage.on('touchcancel.owl.core', $.proxy(owl.onDragEnd, owl));
							}

							$element.trigger('refresh.owl.carousel');
							$element.removeClass('owl-disabled');
						}
					}
				})
				.owlCarousel({
					margin:12,
					items:4,
					autoplay: false,
					lazyLoad: true,
					lazyLoadEager: 4,
					autoplayTimeout: 5000,
					nav: true,
					slideBy: 1,
					loop: true,
					rewind: true,
					dots: false,
					autoplayHoverPause: true,
					responsive : {
						0 : { items: 1 },
						768 : { items: 2 },
						1025 : { items: 4 }
					},
					onInitialized: function() {
						this.$element.addClass('progression-carousel-theme allprogression-video-carousel');
					}
				})
				.trigger('resized.owl.carousel');
		});
	</script>

	@if($flipdown)
		<script src="{{ url('js/flipdown.min.js') }}"></script>
		<script>
			$(document).ready(function() {
				var timeout = 500;
				var timestamp = {{ $promote->started_at->tz($tz)->timestamp }};
				var flipdown = new FlipDown(timestamp, {theme: 'light'}).start().ifEnded(function() {
					setTimeout(function() { $('#flipdown-form').submit(); }, timeout);
				});
			});
		</script>
	@endif

	@if($archive)
		<script src="{{ url('js/jquery-infinite.js') }}"></script>
		<script>
			$(document).ready(function() {
				$('.infinite-scrolling').infinite({
					loader: '.infinite-loading',
					params: {
						after: '</div>',
						before: '<div class="col col-12 col-md-6 col-lg-3" style="padding-right: 8px;">',
						show: 12
					},
					url: '{{ route("frontend.live.infinite") }}'
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
	@endif

	<script>
		$(document).ready(function() {
			$('#schedule-accordion .dropdown > a')
				.attr({ href: '#', title: 'Add event to my calendar...' })
				.dropdown({
					offset: function(position, element) {
						var mouseEvent = $(element).data('mouseEvent');
						if (typeof mouseEvent !== 'undefined' && mouseEvent !== null) {
							if (mouseEvent.pageX !== 0) {
								position.popper.left = mouseEvent.pageX - $(element).offset().left;
							} else {
								position.popper.left = position.reference.left;
							}
						}
						return position;
					}
				})
				.off('click.bs.dropdown')
				.on('click', function(event) {
					$(this).data('mouseEvent', event).dropdown('toggle');
					return false;
				});
		});
	</script>

@endpush

