@extends('frontend.layouts.app')

@section('title', app_name() . ' | Watchlist' )

@section('content')
	<div id="content-pro">
		<div class="container custom-gutters-pro">

			<h2 class="post-list-heading">Watchlist</h2>

			<div class="row">
				@foreach($contents as $content)
					<div class="col col-12 col-md-6 col-lg-3" style="padding-right: 8px;">
						@render('frontend.includes.videoitem', [ 'content' => $content, 'button' => 'watchlist.remove', 'show_category' => true, 'lazyload' => false ])
					</div><!-- close .col -->
				@endforeach
				<div class="d-block-onlychild col col-12" style="display: none;">
					<div class="alert alert-secondary text-light text-center bg-transparent mt-2" role="alert">
						<span>You don't have any content in the watchlist.</span>
					</div>
				</div>
			</div><!-- close .row -->

			<div class="clearfix"></div>

		</div>
	</div>             

	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

@endsection

@push('after-styles')
<style>
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

		var route_watchlist_remove = "{{ rtrim(route('frontend.user.watchlist.remove', '#'), '#') }}";

		$('.watchlist-button-remove').on('click', function(e) {
			e.preventDefault();
			var content_id = $(this).attr('data-content-id');
			var $element = $(this).closest('.ac-video-index-container').closest('.col');
			axios.post(route_watchlist_remove + content_id)
			.then(function(response) {
				$element.remove();
			})
			.catch(function(error) {
				console.log(error);
			});
		});

		// A LA YOUTUBE replace img with muted version of video on hover... not working on mobile obviously
		$( ".replaceImgVid" ).hover(
			function() {
				var hv = $(this).find('video:first');
				hv[0].load();
				hv[0].play();
				$(".replaceImgVid").find('video:first').bind("playing", function() {
					$(this).parent().find('img:first').css("display", "none");
					$(this).css("display", "");
					
				});
			}, function() {
				$(this).find('img:first').css("display", "");
				var hv = $(this).find('video:first').css("display", "none");
				hv[0].pause();
			}
        );
    });
</script>
@endpush