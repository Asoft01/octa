<?php
	// isset($mentor) || $mentor = null;
	isset($date_format) || $date_format = 'jS \\of F Y';
	isset($lazyload) || $lazyload = true;
	isset($link) || $link = route('frontend.reviewer', ['slug' => $mentor->account->slug]);
	isset($action) || $action = 'frontend.user.order';
?>
<div class="item">
	<div class="ac-video-index-container{{ !empty($mentor->account->preview_video) ? ' replaceImgVid' : '' }}" data-name="{{ $mentor->account->slug }}">
		@if($link !== false)
			<a href="{{ $link }}">
		@endif
			<div class="ac-video-feaured-image">
				<div class="embed-responsive embed-responsive-35by24">
					<div class="embed-responsive-item">
						@if(!empty($mentor->account->preview_video))
							<video src="{{ config('ac.CDN_MEDIA') . $mentor->account->preview_video }}" preload="none" muted class="position-absolute align-middle w-100 h-auto" style="display: none;"></video>
						@endif
						@if($lazyload)
							<img class="owl-lazy" data-src="{{ config('ac.CDN_MEDIA') }}{{ empty($mentor->account->photo) ? 't.png' : $mentor->account->photo }}">
						@else
							<img src="{{ config('ac.CDN_MEDIA') }}{{ empty($mentor->account->photo) ? 't.png' : $mentor->account->photo }}">
						@endif
					</div>
				</div>
			</div>
		@if($link !== false)
			</a>
		@endif
	</div>
	<div class="progression-video-index-content h-auto pb-4">
		<div class="progression-video-index-table">
			<div class="progression-video-index-vertical-align">
				<h2 class="progression-video-title">
					@if($link !== false)
						<a href="{{ $link }}">{{ $mentor->full_name }}</a>
					@else
						{{ $mentor->full_name }}
					@endif
				</h2>
				<ul class="video-index-meta-taxonomy mb-0">
					@if(!empty($mentor->account->position))
						<li>{{ $mentor->account->position }}</li>
					@endif
				</ul>
				<!-- snip -->
				@if($action)
					<div class="metadata" style="margin-top: -6px;">
						@if($mentor->account->bookeduntil > now())
							<p class="mb-0">Available {{ $mentor->account->bookeduntil->format($date_format) }}</p>
						@else
							<a href="{{ route('frontend.user.order', ['reviewer' => $mentor->account->slug]) }}"@guest data-toggle="modal" data-target="#LoginModal" @endguest>Order a review</a>
						@endif
					</div>
				@endif
			</div>
		</div>
	</div>
	<!--<div class="video-index-border-hover" style="pointer-events: none;"></div>-->
</div>

