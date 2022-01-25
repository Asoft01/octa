@php isset($current) || $current == null; @endphp
<div class="playlist bg-dark text-light{{ isset($class) ? ' ' . $class : '' }}">
	<div class="playlist-heading">
		<h2 class="playlist-title h5"><a href="{{ route('frontend.playlist.show', ['slug' => $playlist->slug]) }}">{{ $playlist->title }}</a></h2>
		<p>
			@if($playlist->contentable->mentor)
				@if($playlist->contentable->mentor->url)
					<a href="{{ $playlist->contentable->mentor->url }}">{{ $playlist->contentable->mentor->full_name }}</a>
				@else
					<span>{{ $playlist->contentable->mentor->full_name }}</span>
				@endif
				<span class="mx-1">&#183;</span>
			@endif
			<span>{{ $playlist->contentable->contents->count() }} items</span>
		</p>
	</div>
	<div class="list-group scrollbar-custom">
		@foreach($playlist->contentable->contents->sortBy('pivot.display_order') as $item)
			@php
				$active = ($current->id == $item->id) ? ' active' : '';
				$category = $item->getCat();
				$duration = trim(durationHumanize($item->contentable->length));
				$position = optional($item->metrics->first())->video_position;
				$progress = $item->contentable->milliseconds ? round($position / $item->contentable->milliseconds * 100) : 0;
				$url  = route('frontend.content', ['slug' => $item->slug, 'playlist' => $playlist->slug, 'autoplay' => true]);
			@endphp
			<div class="playlist-item list-group-item{{ $active }}">
				<div class="d-flex w-100">
					<a class="playlist-item-thumbnail" href="{{ $url }}">
						<img src="{{ $item->getImageUrl() }}" alt="{{ $item->title }}">
						@if($duration != '0s')
							<div class="timeoverlay">{{ $duration }}</div>
						@endif
						@if($position)
							<div class="progress{{ ( $active && Route::currentRouteName() !== 'frontend.playlist.show' ) ? ' d-none' : '' }}">
								<div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						@endif
					</a>
					<div class="playlist-item-information ml-2">
						<h5 class="playlist-item-title h6"><a href="{{ $url }}">{{ $item->title }}</a></h5>
						{{--<!--
							@if($category)
								<p class="playlist-item-category">{!! $category !!}</p>
							@endif
						-->--}}
						@if($item->contentable->mentor)
								<p>{{ $item->contentable->mentor->full_name }}</p>
						@endif
					</div>
				</div>
			</div>
		@endforeach
	</div>
</div>