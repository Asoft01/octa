@extends('frontend.layouts.app')

@section('title', app_name() . ' | Favorites' )

@section('content')
	<div class="container custom-gutters-pro" style="padding-top: 50px;">
		<h2 class="post-list-heading">Favorites</h2>
	</div>
	<div id="favorites">
		{{-- Categories --}}
		@foreach($categories as $category)
			@if(count($category->contents))
				<div id="content-pro" class="pt-0">
					<div class="container custom-gutters-pro">
						<h2 class="post-list-heading"><a href="{{ route('frontend.category', $category->title) }}">{{ $category->title }} <i class="fas fa-external-link-alt" style="vertical-align: super; font-size: 12px; color: #22b2ee;"></i></a></h2>
						<div class="ac-elementor-carousel-container ac-always-arrows-on">
							<div class="owl-carousel favorites-video-carousel">

								@foreach($category->contents as $content)
									@render('frontend.includes.videoitem', [ 'content' => $content, 'button' => 'favorite.remove' ])
								@endforeach

							</div><!-- close #progression-video-carousel - See /js/script.js file for options -->
						</div><!-- close .ac-elementor-carousel-container  -->

						<div class="clearfix"></div>

					</div><!-- close .container -->

				</div><!-- close #content-pro -->
			@endif
		@endforeach
		<div class="d-block-onlychild container custom-gutters-pro" style="display: none;">
			<div class="col col-12">
				<div class="alert alert-secondary text-light text-center bg-transparent mt-2" role="alert">
					<span>You don't have any content in your favorites.</span>
				</div>
			</div>
		</div>
	</div>


	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

@endsection


@push('after-styles')
<style>
	.owl-disabled .owl-nav button,
	.owl-disabled .owl-item.cloned {
		visibility: hidden;
		pointer-events: none;
	}
	.d-block-onlychild:only-child {
		display: block !important;
	}
	.ac-video-index-container .favorite-button-remove,
	.ac-video-index-container .watchlist-button-remove {
		display: none;
		top: 0;
		right: 0;
		z-index: 501;
	}
	.ac-video-index-container:hover .favorite-button-remove,
	.ac-video-index-container:hover .watchlist-button-remove {
		display: inline-block;
	}
</style>
@endpush

@push('after-scripts')
<script>
	$( document ).ready(function() {

		var route_favorite_remove = "{{ rtrim(route('frontend.user.favorite.remove', '#'), '#') }}";

		$('.favorites-video-carousel')
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
		
		$('body').on('click', '.favorite-button-remove', function(e) {
			e.preventDefault();
			var content_id = $(this).attr('data-content-id');
			var carousel = $(this).closest('.allprogression-video-carousel');
			axios.post(route_favorite_remove + content_id)
			.then(function(response) {
				var items = carousel.data('owl.carousel')._items;
				if (items.length > 1) {
					for (i = 0; i < items.length; i++) {
						if (items[i].find('.item').attr('data-content-id') === content_id) {
							carousel.trigger('remove.owl.carousel', i);
							carousel.trigger('resized.owl.carousel');
							carousel.trigger('refresh.owl.carousel');
						}
					}
				} else {
					wrapper = carousel.closest('#content-pro');
					carousel.trigger('destroy.owl.carousel');
					wrapper.remove();
				}
			})
			.catch(function(error) {
				console.log(error);
			});
		});

		// A LA YOUTUBE replace img with muted version of video on hover... not working on mobile obviously
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