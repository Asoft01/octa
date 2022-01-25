@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . $content->title)

@section('content')

	<div class="content-page{{ $playlist ? ' compact' : '' }}">
		<div class="content-media">
			@if($content->contentable_type == "MorphReview" && $content->contentable->syncsketch)
				<div id="vsyncsketch" class="content-syncsketch text-center mb-4">
					<button class="btn btn-slider-pro">
						<a href="{{ $content->contentable->syncsketch }}" class="" target="_blank" style="color: white;">
							<img src="{{ config('ac.CDN_MEDIA') . 'img/frontend/syncsketch.png' }}" style="max-width: 24px; vertical-align: middle; padding-top: 0px; margin-right: 8px;">
							<span>SyncSketch</span>
						</a>
					</button>
				</div>
			@endif
			<div class="content-media-container container d-lg-flex">
				<div class="content-player flex-grow-1 bg-black">
					@if($content->isPlaylist())
						@php $poster = $content->contentable->hasWatchedContents() ? $content->contentable->current->getImage() : $content->contentable->getImage(); @endphp
						<a class="d-block position-relative" href="{{ route('frontend.content', ['slug' => $content->contentable->current->slug, 'playlist' => $content->slug, 'autoplay' => true]) }}">
							<img id="playlist-current-item-poster" src="{{ $poster ? config('ac.CDN_MEDIA') . $poster : '' }}" alt="{{ $content->title }}">
							<div class="big-play-button position-absolute w-100 h-100" style="top: 0; pointer-events: none;"></div>
						</a>
					@elseif(strpos($content->contentable->video, 'https://') !== false)
						{{--<!-- SUPPORTING YOUTUBE AND VIMEO BASED ON URL -->--}}
						<div class="embed-responsive embed-responsive-16by9">
							<iframe width="1280" height="720" src="{{ $content->contentable->video }}?controls=1&title=0&byline=0&portrait=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					@else
						<video id="ac-video-player" style="width: 100%; height: auto;" poster="{{ !empty($content->contentable->poster) ? config('ac.CDN_MEDIA') . $content->contentable->poster : '' }}" data-autoresize="fit" controlsList="nodownload" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true, "playbackRates": [0.1, 0.5, 1, 1.5, 2]}'>
							<source src="{{ config('ac.CDN_MEDIA') . $content->contentable->video }}" type="video/mp4">
						</video>
					@endif
				</div>
				@if($playlist)
					@if($content->contentable_type == 'MorphPlaylist')
						@render('frontend.includes.playlist', ['playlist' => $playlist, 'current' => $playlist->contentable->current])
					@else
						@render('frontend.includes.playlist', ['playlist' => $playlist, 'current' => $content])
					@endif
				@endif
			</div>
		</div>
		<div class="content-metadata">
			<div class="content-metadata-container container d-md-flex">
				<div class="content-about order-1 order-sm-2">
					@if($content->contentable_type == "MorphAsset")
						<div class="float-right"><a href="{{ route('frontend.assets') }}">Back to assets</a></div>
					@endif
					<h1 class="video-post-heading-title" style="font-size: 36px;">{{ $content->title }}</h1>
					@if($content->tags)
						<ul class="list-inline pl-1 mb-4" style="user-select: none; margin-bottom: 1em !important;">
							@foreach($content->tags as $tag)
								<li class="list-inline-item mb-2">
									<a class="btn btn-tag" href="{{ route('frontend.tag', urlencode($tag->title)) }}">{{ $tag->title }}</a>
								</li>
							@endforeach
						</ul>
					@endif
					<ul class="list-inline mb-4" style="user-select: none;">
						@if(in_array($content->contentable_type, ['MorphAsset', 'MorphReview', 'MorphVideo', 'MorphPlaylist']))
							@php $favorite = Auth::check() ? $content->favorites()->where('user_id', Auth::id())->exists() : null; @endphp
							<li class="list-inline-item mb-2 mr-2 pr-1">
								<a href="#!" class="favorite-button-toggle wishlist-button-pro m-0" role="button"@auth data-action="{{ $favorite ? 'remove' : 'add' }}" @else data-toggle="modal" data-target="#LoginModal" @endauth>
									<span class="favorite-button-add {{ $favorite ? 'd-none' : '' }}"><i class="fas fa-plus-circle"></i>Favorites</span>
									<span class="favorite-button-remove {{ !$favorite ? 'd-none' : '' }}"><i class="fas fa-minus-circle"></i>Favorites</span>
								</a>
							</li>
						@endif
						@if(in_array($content->contentable_type, ['MorphReview', 'MorphVideo', 'MorphPlaylist']))
							@php $watchlist = Auth::check() ? $content->watchlistitems()->where('user_id', Auth::id())->exists() : null; @endphp
							<li class="list-inline-item mb-2 mr-2 pr-1">
								<a href="#!" class="watchlist-button-toggle wishlist-button-pro m-0" role="button"@auth data-action="{{ $watchlist ? 'remove' : 'add' }}" @else data-toggle="modal" data-target="#LoginModal" @endauth>
									<span class="watchlist-button-add {{ $watchlist ? 'd-none' : '' }}"><i class="fas fa-plus-circle"></i>Watchlist</span>
									<span class="watchlist-button-remove {{ !$watchlist ? 'd-none' : '' }}"><i class="fas fa-minus-circle"></i>Watchlist</span>
								</a>
							</li>
						@endif
						@if(in_array($content->contentable_type, ['MorphAsset', 'MorphReview', 'MorphVideo', 'MorphPlaylist']))
							@php $vote_state = Auth::check() ? Arr::get($content->votes()->where('user_id', Auth::id())->first(), 'state', 0) : 0; @endphp
							<li class="list-inline-item mb-2 mr-2 pr-1">
								<a href="#!" class="vote-button vote-button-up wishlist-button-pro m-0{{ $vote_state > 0 ? ' highlight' : '' }}" role="button"@auth data-action="vote" @else data-toggle="modal" data-target="#LoginModal" @endauth>
									<i class="fas fa-thumbs-up"></i>
									@if(config('ac.SHOW_CONTENT_VOTE_COUNT'))
										<span class="count">{{ $content->votes()->where('state', 1)->count() }}</span>
									@endif
								</a>
							</li>
							<li class="list-inline-item mb-2 mr-2 pr-1">
								<a href="#!" class="vote-button vote-button-down wishlist-button-pro m-0{{ $vote_state < 0 ? ' highlight' : '' }}" role="button"@auth data-action="vote" @else data-toggle="modal" data-target="#LoginModal" @endauth>
									<i class="fas fa-thumbs-down"></i>
									@if(config('ac.SHOW_CONTENT_VOTE_COUNT'))
										<span class="count">{{ $content->votes()->where('state', -1)->count() }}</span>
									@endif
								</a>
							</li>
						@endif
					</ul>
					<div id="vayvo-video-post-content" style="margin-bottom: -60px; overflow-wrap: break-word; word-wrap: break-word; -ms-word-break: break-all;">
						@if($content->contentable_type == "MorphAsset")
							@if($content->contentable->intro_video)
								<video style="width: 100%; margin-bottom: 20px;" src="{{ config('ac.CDN_MEDIA') }}{{ $content->contentable->intro_video }}" controls preload="none"@if($content->contentable->poster_intro) poster="{{ config('ac.CDN_MEDIA') }}{{ $content->contentable->poster_intro }}"@endif></video>
							@endif
							<div>
								<div class="grid" style="margin-left: 10px; margin-right: 10px;">
									<div class="grid-sizer"></div>
									<div class="previewanim{{ $content->id }}">
										@foreach($content->contentable->getMedia('images') as $media)
											<div class="grid-item">
												<a href="{{ config('ac.CDN_MEDIA') }}{{ $media->getCustomProperty('path') . $media->file_name }}" data-gall="myGallery<?php echo $content->id; ?>" class="venobox">
													<img src="{{ config('ac.CDN_MEDIA') }}{{ $media->getCustomProperty('path') . $media->file_name }}" style="max-height: 78px;object-fit: cover;object-position: 0% 25%;" />
												</a>
											</div>
										@endforeach
									</div>
								</div>
							</div>
							<div style="font-size: 28px; margin-top: 28px;">
								<a class="link-asset-download"@auth href="{{ config('ac.CDN_MEDIA') . $content->contentable->zip }}" @else href="{{ url()->current() }}" data-toggle="modal" data-target="#LoginModal" @endauth>
									<i class="fas fa-download mr-1"></i>
									<span>Download</span>
								</a>
							</div>
							<p>{!! $content->description !!}</p>
						@else
							<p>{!! $content->getDescription() !!}</p>
						@endif
						<div id="hyvor-talk-view" style="margin-left: -8px;"></div>
					</div>
				</div>
				@include('frontend.includes.content-sidebar')
			</div>
		</div>
	</div>

	{{--<!-- UP -->--}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

	{{--<!-- SHARING TOOLTIP -->--}}
	{{--<!-- @include('frontend.includes.sharing') -->--}}
	
@endsection

@push('after-styles')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/venobox/1.8.6/venobox.min.css" />
	<link href="{{ config('ac.CDN_MEDIA') . 'css/AC/sharing.css' }}" rel="stylesheet">
	<style>
		#video-more-like-this-details-section .item .progression-video-title {
			font-size: 16px;
		}
		#video-more-like-this-details-section .item .progression-video-index-vertical-align {
			margin-top: 8px;
			padding-bottom: 8px;
		}
		#video-more-like-this-details-section .item .progression-video-index-content p {
			font-size: 14px;
		}
		a.wishlist-button-pro.vote-button i:only-child {
			margin-right: 0;
		}
		a.vote-button.highlight {
			background: #22b2ee;
		}
		/* clear fix */
		.grid:after {
			content: '';
			display: block;
			clear: both;
		}

		/* ---- .grid-item ---- */
		.grid-sizer,
		.grid-item {
			width: 25%;
		}

		.grid-item {
			float: left;
		}

		.grid-item img {
			display: block;
			width: 100%;
		}
	</style>
@endpush

@push('after-scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/venobox/1.8.6/venobox.min.js"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
<script>
	$(document).ready(function() {
		function applyfx() {
			$('.venobox').venobox();
			var $grid = $('.grid').imagesLoaded(function() {
				$grid.masonry({
					itemSelector: '.grid-item',
					percentPosition: true,
					columnWidth: '.grid-sizer'
				}); 
				$grid.find('img').hover(
					function() { $(this).stop().animate({"opacity": "0.8"}, "fast"); },
					function() { $(this).stop().animate({"opacity": "1"}, "slow"); }
				);
			});
		}
		applyfx();
	});
</script>
<script async type="text/javascript" src="//talk.hyvor.com/web-api/embed"></script>
<script>
	var HYVOR_TALK_WEBSITE = 2362;
	var HYVOR_TALK_CONFIG = {
		sso: {
			hash: "{{ $hash }}",
			userData: "{{ $encodedUserData }}",
			loginURL: "https://agora.community/login",
			signupURL: "https://agora.community/register"
		},
		url: "https://agora.community/content/{{ $content->slug }}",
		id: "{{ $content->id }}"
	};
	var route = {
		favorite: {
			add: "{{ rtrim(route('frontend.user.favorite.add', '#'), '#') }}",
			remove: "{{ rtrim(route('frontend.user.favorite.remove', '#'), '#') }}"
		},
		watchlist: {
			add: "{{ rtrim(route('frontend.user.watchlist.add', '#'), '#') }}",
			remove: "{{ rtrim(route('frontend.user.watchlist.remove', '#'), '#') }}"
		},
		vote: "{{ route('frontend.user.vote.add') }}"
	};

	$( document ).ready(function() {

		@if($content->contentable->video && strpos($content->contentable->video, 'https://') !== true)
			
			{{--<!--
				 based on https://www.npmjs.com/package/videojs-playtime 
			-->--}}
			videojs.registerPlugin('videoPlayTime', function() {
				var previous, started, total = 0;
				this.on('timeupdate', function() {
					stopped = this.paused() || this.seeking() || this.scrubbing();
					if(this.currentTime() !== started && !stopped) {
						var now = new Date().getTime();
						previous = previous || now;
						total = total || 0;
						total += (now - previous);
						previous = now;
					}
					this.trigger('timeupdated');
				});
				this.on('pause', function() {
					previous = 0;
				});
				this.on('playing', function() {
					started = this.currentTime();
				});
				this.playTime = function() {
					return parseFloat((total / 1000).toFixed(6));
				};
			});

			// VIDEO-JS
			var player = videojs('ac-video-player');

			if (/Mobi|Android/i.test(navigator.userAgent)) {
				//player.controlBar.pictureInPictureToggle.hide();
				player.persistvolume({
					namespace: "video-player-volume"
				});

				player.framebyframe({
					fps: <?php if(!empty($content->contentable->fps)) { echo $content->contentable->fps; } else { echo "30"; } ?>,
					steps: [
						{ text: '+1', step: -1, index: 3 },
						{ text: '+1', step: 1, index: 4 },
					]
				});

			} else { 
				
				player.seekButtons({
				forward: 10,
				back: 10
				});

				player.persistvolume({
					namespace: "video-player-volume"
				});

				player.framebyframe({
					fps: <?php if(!empty($content->contentable->fps)) { echo $content->contentable->fps; } else { echo "30"; } ?>,
					steps: [
						{ text: '-1;', step: -1, index: 3 },
						{ text: '+1;', step: 1, index: 4 },
				]
				});
			}

			var query_params = Object.fromEntries( ( new URLSearchParams(window.location.search) ).entries() );
			var autoplay = query_params['autoplay'];

			@if(Auth::check() && $metric)
			
				@if(in_array($content->contentable_type, ['MorphReview', 'MorphVideo']))

					var previous_playtime = 0;
					var old_video_position = {{ intval($metric->video_position / 1000) }};
					var updateVideoMetrics = function() {

						player_ended = false;

						if (player.ended()) {
							player_ended = true;
							current_position = 0;
							increment_playtime = 0;
						} else {
							current_position = player.currentTime();
							current_playtime = player.playTime();
							increment_playtime = current_playtime - previous_playtime;
							previous_playtime = current_playtime;
						}

						axios.post('/metrics/video', {
							'metric_id': {{ $metric->id }},
							'playtime': increment_playtime * 1000,
							'position': current_position * 1000
						})
						.then(function() {
							if (player_ended) {
								nextVideo = $('.playlist').first().find('.playlist-item.active').next().find('.playlist-item-thumbnail');
								if (nextVideo.length) {
									window.location.href = nextVideo.prop('href');
								}
							}
						})
						.catch(function(error) {
							console.log(error);
						});

					};
					
					player.videoPlayTime();

					if (old_video_position > 0) {
						player.on('loadedmetadata', function() {
							this.ready(function() {
								if (old_video_position < this.duration()) {
									this.currentTime(old_video_position);
									this.hasStarted(true);
								}
								if (autoplay) {
									player.play();
								}
							});
						});
						player.one('timeupdate', function() {
							this.on('timeupdated', _.throttle(updateVideoMetrics, 2000));
						});
					}
					else {
						if (autoplay) {
							player.on('loadedmetadata', function() {
								this.ready(function() {
									player.play();
								});
							});
						}
						player.on('timeupdated', _.throttle(updateVideoMetrics, 2000));
					}
					
					player.on('ended', updateVideoMetrics);
					
				@elseif($content->contentable_type == 'MorphAsset')

					$('.link-asset-download').on('click', function(e) {
						axios.post('/metrics/asset', {
							'content_id': <?php echo e($content->id); ?>
						})
						.catch(function(error) {
							console.log(error);
						});
					});

				@endif

			@elseif(in_array($content->contentable_type, ['MorphReview', 'MorphVideo']))
				player.on('ended', function() {
					nextVideo = $('.playlist').first().find('.playlist-item.active').next().find('.playlist-item-thumbnail');
					if (nextVideo.length) {
						window.location.href = nextVideo.prop('href');
					}
				});
				if (autoplay) {
					player.on('loadedmetadata', function() {
						this.ready(function() {
							player.play();
						});
					});
				}
			@endif

			$(document).on('keydown', function(e) {
				if (e.keyCode === 32) {
					if (!$(e.target).is('input, select, textarea')) {
						e.preventDefault();
						$(player.el()).focus();
						if (player.paused()) {
							player.play();
						} else {
							player.pause();
						}
					}
				}
			});

		@endif

		// A LA YOUTUBE replace img with muted version of video on hover... not working on mobile obviously
		$( ".replaceImgVid" ).hover(
			function() {
				var hv = $(this).find('video:first');
				if(hv.length) {
					hv[0].load();
					hv[0].play();
					$(".replaceImgVid").find('video:first').bind("playing", function() {
						$(this).parent().find('img:first').css("display", "none");
						$(this).css("display", "");
						
					});
				}
			}, function() {
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
@auth
	<script>
		$(document).ready(function() {
			$('.favorite-button-toggle').on('click', function(e) {
				action = $(this).attr('data-action');
				if (action) {
					e.preventDefault();
					axios.post(route.favorite[action] + {{ $content->id }})
					.then(function(response) {
						old_action = response.data.action;
						new_action = old_action === 'add' ? 'remove' : 'add';
						$('.favorite-button-toggle').attr('data-action', new_action)
								.children('.favorite-button-' + old_action).addClass('d-none')
								.siblings('.favorite-button-' + new_action).removeClass('d-none');
					})
					.catch(function(error) {
						console.log(error);
					});
				}
			});

			$('.watchlist-button-toggle').on('click', function(e) {
				action = $(this).attr('data-action');
				if (action) {
					e.preventDefault();
					axios.post(route.watchlist[action] + {{ $content->id }})
					.then(function(response) {
						old_action = response.data.action;
						new_action = old_action === 'add' ? 'remove' : 'add';
						$('.watchlist-button-toggle').attr('data-action', new_action)
								.children('.watchlist-button-' + old_action).addClass('d-none')
								.siblings('.watchlist-button-' + new_action).removeClass('d-none');
					})
					.catch(function(error) {
						console.log(error);
					});
				}
			});

			$('.vote-button-up, .vote-button-down').on('click', function(e) {
				e.preventDefault();
				var new_state = 0;

				if (!$(this).hasClass('highlight')) {
					if ($(this).hasClass('vote-button-up')) {
						new_state += 1;
					}
					if ($(this).hasClass('vote-button-down')) {
						new_state += -1;
					}
				}

				axios.post(route.vote, {
					'content_id': {{ $content->id }},
					'state': new_state
				})
				.then(function(response) {
					switch(response.data.state) {
						case 1:
							$('.vote-button-up').addClass('highlight');
							$('.vote-button-down').removeClass('highlight');
							break;
						case -1:
							$('.vote-button-up').removeClass('highlight');
							$('.vote-button-down').addClass('highlight');
							break;
						default:
							$('.vote-button-up').removeClass('highlight');
							$('.vote-button-down').removeClass('highlight');
					}
					if (response.data.count !== null) {
						$('.vote-button-up .count').text(response.data.count.positive);
						$('.vote-button-down .count').text(response.data.count.negative);
					}
				})
				.catch(function(error) {
					console.log(error);
				});
			});
		});
	</script>
@endauth
@if($playlist)
	<script>
		function layoutCompact() {
			$container = $('.content-media-container').first();
			$playlist = $('.playlist').first();
			$heading = $playlist.find('.playlist-heading').first();

			aspect_ratio = 9 / 16;
			new_video_width = $container.width() - $playlist.outerWidth(true);
			new_video_height = new_video_width * aspect_ratio;
			list_group_height = new_video_height - $heading.outerHeight();

			$('.content-page').first().removeClass('theatre').addClass('compact');
			$playlist.clone()
				.find('.list-group').outerHeight(list_group_height).end()
				.width($playlist.width()).appendTo($container);
			$playlist.remove();
		};

		function resizePlaylist() {
			$container = $('.content-media-container').first();
			$playlist = $container.find('.playlist').first();
			if ($playlist.length) {
				if ($container.css('display') == 'flex') {
					list_group_height = $container.find('.content-player video, .content-player iframe, #playlist-current-item-poster').outerHeight() - $playlist.find('.playlist-heading').outerHeight();
					$playlist.find('.list-group').outerHeight(list_group_height);
				} else {
					$playlist.find('.list-group').attr('style', '');
				}
			}
		};

		$(document).ready(function() {
			$(window).on('resize', _.debounce(resizePlaylist, 10));
			resizePlaylist();
			setTimeout(resizePlaylist, 1000);
			setTimeout(resizePlaylist, 2000);
			setTimeout(resizePlaylist, 3000);
			//layoutCompact();
		});
	</script>
@endif

@endpush