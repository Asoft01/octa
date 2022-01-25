@extends('frontend.layouts.app')

@section('title', app_name() . ' | Our reviewers' )

@section('content')

	<div class="row justify-content-center" style="margin-left: 0px; margin-right: 0px; padding-top: 40px; padding-bottom: 40px;">
        <div class="col col-sm-8 align-self-center">
            <h2 class="post-list-heading">Our experts<span> Get professional feedback from industry veterans</span></h2>
			<div class="row">
				@foreach ($mentors->users->shuffle() as $mentor)		
					<div class="col col-12 col-md-6 col-lg-4" style="padding-bottom: 32px;">
						@render('frontend.includes.mentoritem', [ 'mentor' => $mentor, 'lazyload' => false ])
					</div>
				@endforeach	
    		</div>
		</div>
	</div>

	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
	
@endsection

@push('after-styles')
<style>
	.embed-responsive-35by24::before {
		padding-top: 68.57%;
	}
</style>
@endpush

@push('after-scripts')
    <script>
	$( document ).ready(function() {
		// A LA YOUTUBE replace img with muted version of video on hover... not working on mobile obviously
		
		$( ".replaceImgVid" ).click(function() {
			window.location.href = '/reviewer/'+$(this).data("name");
		});

		// $( ".metadata").click(function() { event.stopPropagation();});

		
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
