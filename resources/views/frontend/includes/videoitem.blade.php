<?php
	// isset($content) || $content = null;
	// isset($button) || $button = null;
	isset($show_details) || $show_details = true;
	isset($show_category) || $show_category = false;
	isset($lazyload) || $lazyload = true;
	isset($login) || $login = false;
	
	if ($content->isPlaylist()) {
		isset($link) || $link = $login ? route('frontend.auth.login') : route('frontend.playlist.show', $content->slug);
	} else {
		isset($link) || $link = $login ? route('frontend.auth.login') : route('frontend.content', $content->slug);
	}
	
	$duration = durationHumanize($content->contentable->length);
?>
<div class="item" data-content-id="{{ $content->id }}">
	<div class="ac-video-index-container{{ !empty($content->contentable->preview_video) ? ' replaceImgVid' : '' }}">
		<a href="{{ $link }}"@if($login) data-toggle="modal" data-target="#LoginModal" @endif>
			<div class="ac-video-feaured-image">
				<div class="embed-responsive embed-responsive-16by9">
					<div class="embed-responsive-item">
						@if(!empty($content->contentable->preview_video))
							<video src="{{ config('ac.CDN_MEDIA') . $content->contentable->preview_video }}" preload="none" muted class="position-absolute w-100 h-auto" style="z-index: 501; display: none;"></video>
						@endif
						@if(isset($content->categories) && Arr::get($content->categories, '0.id') == 10)
							<img src="{{ config('ac.CDN_MEDIA') . 'reviews/animation_review.png?v=1' }}" class="position-absolute" style="z-index: 500;">
						@endif
						@if($lazyload)
							<img class="owl-lazy position-relative" data-src="{{ $content->getImageUrl() }}">
						@else
							<img class="position-relative" src="{{ $content->getImageUrl() }}">
						@endif
						
					</div>
				</div>
			</div>
			@if($content->isPlaylist())
				<div class="videoitem-playlist-overlay">
					<div>{{ isset($content->contentable->contents_count) ? $content->contentable->contents_count : count($content->contentable->contents) }}</div>
					<div>
						<i class="fas fa-film fa-sm" aria-hidden="true"></i>
						<span class="sr-only">videos</span>
					</div>
				</div>
			@elseif($duration != '0s')
				<div class="timeoverlay">{{ $duration }}</div>
			@endif
		</a>
		@isset($button)
			@switch($button)
				@case('favorite.remove')
					<a href="#!" title="Remove from Favorites" data-action="remove" data-content-id="{{ $content->id }}" class="favorite-button-remove badge badge-danger position-absolute m-2"><i class="fas fa-times"></i></a>
					@break
				@case('watchlist.remove')
					<a href="#!" title="Remove from Watchlist" data-action="remove" data-content-id="{{ $content->id }}" class="watchlist-button-remove badge badge-danger position-absolute m-2"><i class="fas fa-times"></i></a>
					@break
			@endswitch
		@endisset
	</div>
	<div class="progression-video-index-content h-auto pb-4">
		<div class="progression-video-index-table">
			<div class="progression-video-index-vertical-align">
				<h2 class="progression-video-title">
					<a href="{{ $link }}"@if($login) data-toggle="modal" data-target="#LoginModal" @endif>{{ $content->title }}</a>
				</h2>
				@if($show_details)
					@if($content->contentable->mentor)
						<p class="text-white mb-0">
							@if($show_category)
								@php $cat = $content->getCat(); @endphp
								@if($cat)
									{!! $cat !!}
									<span>|</span>
								@endif
							@elseif($content->isReview())
								<span>Reviewed by:</span>
							@endif
							@if($content->contentable->mentor->slug && $content->contentable->mentor->user->hasRole('mentor'))
								<a href="{{ route('frontend.reviewer', $content->contentable->mentor->slug) }}">{{ $content->getContributor() }}</a>
							@elseif($content->contentable->mentor->slug && $content->contentable->mentor->user->hasRole('contributor'))
								<a href="{{ route('frontend.contributor', $content->contentable->mentor->slug) }}">{{ $content->getContributor() }}</a>
							@else
								<span>{{ $content->getContributor() }}</span>
							@endif
						</p>
					@elseif($show_category)
						<p class="text-white mb-0">{!! $content->getCat() !!}</p>
					@endif
				@endif
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>