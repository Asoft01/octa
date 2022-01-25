@php
	// isset($stream) || $content = null;
	isset($show_details) || $show_details = true;
	isset($lazyload) || $lazyload = true;
	$image = \Twitch::resizeChannelImage($stream->thumbnail_url, 320, 180);
@endphp
<div class="item" data-content-id="{{ $stream->id }}">
	<div class="ac-video-index-container">
		<a class="tcs" href="#{{ $stream->user_login }}" data-channel="{{ $stream->user_login }}">
			<div class="ac-video-feaured-image">
				<div class="embed-responsive embed-responsive-16by9">
					<div class="embed-responsive-item">
						@if($lazyload)
							<img class="img-twitch position-relative owl-lazy" data-src="{{ $image }}" alt="{{ $stream->user_name }} ({{ $stream->isStreaming ? 'Online' : 'Offline' }})">
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