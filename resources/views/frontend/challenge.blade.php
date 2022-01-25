@extends('frontend.layouts.app')

@section('title', app_name() . ' | Live!')

@section('content')
@php
	// isset($stream) || $content = null;
	// isset($show_details) || $show_details = true;
	// isset($lazyload) || $lazyload = true;
	$image1 = \Twitch::resizeChannelImage("https://static-cdn.jtvnw.net/jtv_user_pictures/2083910d-2fe3-4a6f-adb8-3ff52cc7b92b-channel_offline_image-1920x1080.png", 320, 180);
	$image2 = \Twitch::resizeChannelImage("https://static-cdn.jtvnw.net/jtv_user_pictures/4a6a3851-cd90-4d4a-989b-f88e8afd9d10-channel_offline_image-1920x1080.jpeg", 320, 180);
	$image3 = \Twitch::resizeChannelImage("https://static-cdn.jtvnw.net/jtv_user_pictures/eb5c3250-8364-4e92-9720-f9d60f514003-channel_offline_image-1920x1080.jpg", 320, 180);
	$image4 = \Twitch::resizeChannelImage("https://static-cdn.jtvnw.net/jtv_user_pictures/50a75e3b-9c86-4ec6-93aa-88e9713b5a8a-channel_offline_image-1920x1080.jpeg", 320, 180);
	$image5 = \Twitch::resizeChannelImage("https://static-cdn.jtvnw.net/jtv_user_pictures/4a6a3851-cd90-4d4a-989b-f88e8afd9d10-channel_offline_image-1920x1080.jpeg", 320, 180);
	$image6 = \Twitch::resizeChannelImage("https://static-cdn.jtvnw.net/jtv_user_pictures/eb5c3250-8364-4e92-9720-f9d60f514003-channel_offline_image-1920x1080.jpg", 320, 180);
	$image7 = \Twitch::resizeChannelImage("https://static-cdn.jtvnw.net/jtv_user_pictures/4a6a3851-cd90-4d4a-989b-f88e8afd9d10-channel_offline_image-1920x1080.jpeg", 320, 180);
	$image8 = \Twitch::resizeChannelImage("https://static-cdn.jtvnw.net/jtv_user_pictures/2083910d-2fe3-4a6f-adb8-3ff52cc7b92b-channel_offline_image-1920x1080.png", 320, 180);
@endphp

<div class="row justify-content-center" style="margin-left: 0px; margin-right: 0px; padding-top: 40px; padding-bottom: 40px;">
	<div class="col col-sm-8 align-self-center">
		<h2 class="post-list-heading">Anime Challenge<span> </span></h2>
		<div class="row" style="margin-bottom: 30px">		
			<div class="col col-12">
				<video id="liveintro" style="margin-bottom: 32px; height: auto; width: 100%" preload="auto" poster="https://cdn.agora.community/live/live_poster.jpg " data-autoresize="fit" class="video-js vjs-default-skin vjs-big-play-centered" controls data-setup='{"fluid": true, "playbackRates": [0.5, 1, 1.5, 2]}'>
					<source src="https://cdn.agora.community/live/live.mp4" type="video/mp4">
				</video>
			</div>
			<div class="col col-12 col-md-6 col-lg-3 anime-challenge-col">
				<div class="embed-responsive-16by9">
					<img class="img-twitch position-relative" src="{{ $image1 }}" alt="Channel 1">
				</div>
			</div>

			<div class="col col-12 col-md-6 col-lg-3 anime-challenge-col">
				<div class="embed-responsive-16by9">
					<img class="img-twitch position-relative" src="{{ $image2 }}" alt="Channel 2">
				</div>
			</div>

			<div class="col col-12 col-md-6 col-lg-3 anime-challenge-col">
				<div class="embed-responsive-16by9">
					<img class="img-twitch position-relative" src="{{ $image3 }}" alt="Channel 3">
				</div>
			</div>

			<div class="col col-12 col-md-6 col-lg-3 anime-challenge-col">
				<div class="embed-responsive-16by9">
					<img class="img-twitch position-relative" src="{{ $image4 }}" alt="Channel 4">
				</div>
			</div>
		</div>

		<div class="row" style="margin-bottom: 30px;">		
			<div class="col col-12 col-md-6 col-lg-3">
				<div class="embed-responsive-16by9">
					<img class="img-twitch position-relative" src="{{ $image5 }}" alt="Channel 5">
				</div>
			</div>

			<div class="col col-12 col-md-6 col-lg-3">
				<div class="embed-responsive-16by9">
					<img class="img-twitch position-relative" src="{{ $image6 }}" alt="Channel 6">
				</div>
			</div>

			<div class="col col-12 col-md-6 col-lg-3">
				<div class="embed-responsive-16by9">
					<img class="img-twitch position-relative" src="{{ $image7 }}" alt="Channel 7">
				</div>
			</div>

			<div class="col col-12 col-md-6 col-lg-3">
				<div class="embed-responsive-16by9">
					<img class="img-twitch position-relative" src="{{ $image8 }}" alt="Channel 8">
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@push('after-styles')

@endpush

@push('after-scripts')
	<!-- Load the Twitch embed JavaScript file -->
	<script src="https://embed.twitch.tv/embed/v1.js"></script>

	 <!-- Create a Twitch.Embed object that will render within the "twitch-embed" element -->
	 <script type="text/javascript">
		
		/*new Twitch.Embed("twitch-embed", {
		  width: 854,
		  height: 480,
		  channel: "thegrefg",
		  // Only needed if this page is going to be embedded on other websites
		  parent: ["wip.agora.community", "agora.community"]
		});
		*/
	  </script>
	
	<script type="text/javascript">
		new Twitch.Player("twitch-embed", {
		  channel: "shivfps"
		});

		new Twitch.Player("twitch-embed-second", {
			channel: "tarik"
		});

		new Twitch.Player("twitch-embed-third", {
			channel: "xqcow"
		});
		
	</script>
	  
@endpush

