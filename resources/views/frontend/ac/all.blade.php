@extends('frontend.layouts.app')

@section('title', app_name() . ' | All contents' )

@section('content')
<div id="content-pro">
            <div class="container custom-gutters-pro">
			
				<h2 class="post-list-heading">All contents</h2>
				
				<div class="row">
                    @foreach($contents as $content)
                        <div class="col col-12 col-md-6 col-lg-3" style="padding-right: 8px;">
                            @render('frontend.includes.videoitem', [ 'content' => $content, 'lazyload' => false, 'show_category' => true ])
                        </div><!-- close .col -->
					@endforeach
					
				</div><!-- close .row -->
				
				<div class="clearfix"></div>

</div>
</div>             

{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

@endsection

@push('after-scripts')
<script>
	$( document ).ready(function() {

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