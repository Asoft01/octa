@php
	$details_condition_02 = $content->contentable_type !== 'MorphPlaylist' && ( $content->contentable->releaseDate || $content->contentable->length || $content->getCat() );
@endphp
@if($content->contentable->mentor || $details_condition_02 || !empty($similar))
	<div class="content-aside order-2 order-sm-1">
		<div class="details mr-4">
			@if($content->contentable->mentor || $details_condition_02)
				@if($content->contentable->mentor)
					@php
						$account_href = null;
						if ($content->contentable->mentor && $content->contentable->mentor->slug) {
							if ($content->contentable->mentor->user->hasRole('mentor')) {
								$account_href = route('frontend.reviewer', $content->contentable->mentor->slug);
							}
							else if ($content->contentable->mentor->user->hasRole('contributor')) {
								$account_href = route('frontend.contributor', $content->contentable->mentor->slug);
							}
						}
					@endphp

					<div class="content-sidebar-section video-sidebar-section-length">
						<h4 class="content-sidebar-sub-header">Author</h4>
						{!! $account_href ? '<a href="' . e($account_href) . '">' : '' !!}
							<div class="content-sidebar-short-description">{{ $content->getContributor() }}
								@if($content->contentable->mentor->photo)
									<br /><img src="{{ config('ac.CDN_MEDIA') }}{{ $content->contentable->mentor->photo }}">
								@endif
							</div>
						{!! $account_href ? '</a>' : '' !!}
					</div><!-- close .content-sidebar-section -->

				@endif

				@if($content->contentable_type !== 'MorphPlaylist')
					<div class="content-sidebar-section video-sidebar-section-length">
						@if($artist)
							<h4 class="content-sidebar-sub-header">Requested by</h4>
							<div class="content-sidebar-short-description" style="margin-top: -4px; margin-bottom: 6px;">{{ $artist->user->first_name }} {{ $artist->user->last_name }}<br /><img src="{{ config('ac.CDN_MEDIA') }}{{ "photos/" . $artist->photo }}"></div>
						@endif

						@if($content->contentable->releaseDate)
							<h4 class="content-sidebar-sub-header">Release Date</h4>
							<div class="content-sidebar-short-description" style="margin-top: -4px; margin-bottom: 6px;">{{ $content->contentable->releaseDate->format('F Y') }}</div>
						@endif

						@if($content->contentable->length)
							<h4 class="content-sidebar-sub-header">Duration</h4>
							<div class="content-sidebar-short-description" style="margin-top: -4px; margin-bottom: 6px;">{{ durationHumanize($content->contentable->length) }}</div>
						@endif

						@php $categories_html = $content->getCat(); @endphp
						@if($categories_html)
							<h4 class="content-sidebar-sub-header">{{ Str::contains($categories_html, '|') ? Str::plural('Category') : 'Category' }}</h4>
							<div class="content-sidebar-short-description" style="margin-top: -4px;">{!! $categories_html !!}</div>
						@endif
					</div><!-- close .content-sidebar-section -->
				@endif

				@if($content->contentable->mentor && $content->contentable->mentor->user->hasRole('mentor'))
					@if($content->contentable->mentor->bookeduntil > now())
						<div class="content-sidebar-section video-sidebar-section-length">
							<h4 class="content-sidebar-sub-header">Available</h4>
							{{ $content->contentable->mentor->bookeduntil->format('jS \\of F Y') }}
						</div><!-- close .content-sidebar-section -->
					@else
						<div class="content-sidebar-section video-sidebar-section-length text-center">
							<a class="btn btn-block" href="{{ route('frontend.user.order', ['reviewer' => $content->contentable->mentor->slug]) }}"@guest data-toggle="modal" data-target="#LoginModal" @endguest>	
								<i class="fas fa-shopping-cart"></i>
								<span>Order a review</span>
							</a>
						</div><!-- close .content-sidebar-section -->
					@endif
				@endif

				{{--<!--
					<div id="video-post-recent-reviews-sidebar">
						<h3 class="content-sidebar-reviews-header">Recent Reviews</h3>
						<ul class="sidebar-reviews-pro">
							<li>
								<div class="ac-sidebar-review-container">
									<div id="sidebar-review-number">5</div>
									<div id="sidebar-review-rating-container">
										<div class="average-rating-video-post">
											<div class="average-rating-video-empty">
												<span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span>
											</div>
											<div class="average-rating-overflow-width" style="width:100%;">
												<div class="average-rating-video-filled">
													<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>
												<div class="clearfix"></div>
												</div>
											</div>
										</div>
									</div>
									<h5 id="sidebar-review-author">Jane Doe</h5>
									<h6 id="sidebar-review-date">September 17, 2019</h6>
									<div class="sidebar-comment-exerpt">
										<div class="sidebar-comment-exerpt-text">Fantastic!</div>
									</div>
								</div>
							</li>
							<li>
								<div class="ac-sidebar-review-container">
									<div id="sidebar-review-rating-container">
										<div class="average-rating-video-post">
											<div class="average-rating-video-empty">
												<span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span>
											</div>
											<div class="average-rating-overflow-width" style="width:80%;">
												<div class="average-rating-video-filled">
													<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>
												<div class="clearfix"></div>
												</div>
											</div>
										</div>
									</div>
									<h5 id="sidebar-review-author">Laura Mills</h5>
									<h6 id="sidebar-review-date">February 16, 2019</h6>
									<div class="spoiler-review">Contains Spoiler</div>
									<div class="sidebar-comment-exerpt sidebar-excerpt-more-click">
										<div class="sidebar-comment-exerpt-text">I have been a cinema lover for years, read a lot of reviews on Vayvo . Lorem ipsum dolor sit...</div>
											<div class="read-more-comment-sidebar">Read more</div>
										</div>
									</div>
								</li>
							</ul>
						<div id="all-reviews-button-progression">See All Reviews</div>
					</div>
				-->--}}

			@endif

			{{--<!-- MORE LIKE THIS -->---}}
			@if(!empty($similar) && count($content->tags))
				<div id="video-more-like-this-details-section" style="{{ ( $content->contentable->mentor || $details_condition_02 ) ? 'margin-top: 32px;' : 'padding-top: 17px; border: none;' }}">
					<h3 id="more-videos-heading" style="margin-top: -22px;">More Like This</h3>
					<div class="row mx-0">
						@foreach($similar as $sc)
							<div class="col-12 px-0">
								@render('frontend.includes.videoitem', [ 'content' => $sc, 'show_category' => true, 'lazyload' => false ])
							</div>
						@endforeach
					</div>
					<div style="height:10px;"></div>
				</div><!-- close #video-more-like-this-details-section -->
				<div class="clearfix"></div>
			@endif
		</div>
	</div>
@endif
