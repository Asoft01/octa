@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')
    <div id="landing-page-container">			
			
        <div class="progression-vertical-center-table">

            <div class="progression-vertical-center-text" style="vertical-align: unset;">
				<div class="textmarketing">
				<h1 style="">Welcome to Agora.Community</h1>
				<h2 style="">Direct and affordable access to experts from the animation&nbsp;industry.</h2>
				<h3 style="">Knowledge should be accessible to all. Agora.community is a digital hub for creatives, designed to entertain, inspire and educate by offering a vast library of <strong>free educational content</strong> and <strong>affordable private mentoring</strong>.</h3>
				</div>
				<a id="playvid" href="#">
					<span class="fa-stack" style="vertical-align: top; left: -18px;">
						<i class="fas fa-circle fa-stack-1x"  style="font-size: 72px; color: white;"></i>
						<i class="fas fa-play-circle fa-stack-1x fa-inverse" style="font-size: 72px; color: #22b2ee"></i>
					</span>
				</a>

				<a id="signinta" href="{{route('frontend.auth.login')}}" class="btn" data-toggle="modal" data-target="#LoginModal" style="display: none;">Sign in to access all the contents</a>

				@if(session()->get('flash_success'))
					<div class="alert alert-success" role="alert" style="display: table; width: 75%; height:100px; margin: 0 auto; margin-top: -70px;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 6px; top: 0px;">
							<span aria-hidden="true">&times;</span>
						</button>
						@if(is_array(json_decode(session()->get('flash_success'), true)))
							<p style="display: table-cell; vertical-align: middle;">
							{!! implode('', session()->get('flash_success')->all(':message<br/>')) !!}
							</p>
						@else
							<p style="display: table-cell; vertical-align: middle;">
							{!! session()->get('flash_success') !!}
							</p>
						@endif
					</div>
				@endif

				<section id="scrolldown" class="header-down-arrow">
					<a href="#"><img src="{{ config('ac.CDN_MEDIA') }}{{ 'img/downarrow.png' }}" width="50"></a>
				</section>

                
			</div><!-- close .progression-vertical-center-text -->
			
        </div><!-- close .progression-vertical-center-table -->
	
    </div><!-- close #landing-page-container -->
    
	<video id="background_video" loop muted playsinline></video>
	<video id="presentation_video" preload="none" src="{{ config('ac.CDN_MEDIA') }}agoracommunity.mp4" controls="true"></video>
    <div id="video_cover"></div>

	{{-- Our mentors --}}
	@if(count($mentors->users))
	<div id="content-pro">
		
		<div class="container custom-gutters-pro">
			
			<div style="height:15px;"></div>
			
			<h2 class="post-list-heading">Our experts<span>Get personalized review of your work</span></h2>
			

			<div class="ac-elementor-carousel-container ac-always-arrows-on">
				<div id="progression-video-carousel" class="allprogression-video-carousel owl-carousel progression-carousel-theme">
					
					@foreach ($mentors->users->shuffle() as $mentor)
						@render('frontend.includes.mentoritem', [ 'mentor' => $mentor, 'action' => 'frontend.auth.login' ])
					@endforeach
					
				</div><!-- close #progression-video-carousel - See /js/script.js file for options -->
			</div><!-- close .ac-elementor-carousel-container  -->

			<div class="clearfix"></div>
							
		</div><!-- close .container -->

	</div><!-- close #content-pro -->  
	@endif


	{{-- Last reviews --}}
	@if(count($lastreviews))
	<div id="content-pro">
		
		<div class="container custom-gutters-pro">
			
			<div style="height:15px;"></div>
			
			<h2 class="post-list-heading">Latest reviews<span>Learn from our experts</span></h2>
			

			<div class="ac-elementor-carousel-container ac-always-arrows-on">
				<div id="progression-video-carousel" class="owl-carousel progression-carousel-theme allprogression-video-carousel">
					
					@foreach ($lastreviews as $lastcontent)
						@render('frontend.includes.videoitem', [ 'content' => $lastcontent ])
					@endforeach					

					{{--DISCOVER MORE CONTENT --}}
					<div class="item">
						<div class="ac-video-index-container">
							<a href="{{ route('frontend.auth.login') }}" data-toggle="modal" data-target="#LoginModal">
								<div class="ac-video-feaured-image" style="-webkit-filter: blur(10px); -moz-filter: blur(10px); -o-filter: blur(10px); -ms-filter: blur(10px); filter: blur(10px);"><img src="{{ config('ac.CDN_MEDIA') }}videos/DavidGibson/Behemoth/t.jpg"></div>
								<div class="progression-video-index-content">
									<div class="progression-video-index-table">
										<div style="margin: 0; width: 70%; text-align: center; position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);">
											<span style="
											font-weight: bold;
											line-height: 3px !important;
											font-size: 18px;
											text-align: justify;">Sign in to access more&nbsp;reviews</span>
										</div><!-- close .progression-video-index-vertical-align -->
									</div><!-- close .progression-video-index-table -->
								</div><!-- close .progression-video-index-content -->
								<div class="video-index-border-hover"></div>
							
							</a>
						</div><!-- close .ac-video-index-container  -->
					</div><!-- close .item -->
					
				</div><!-- close #progression-video-carousel - See /js/script.js file for options -->
			</div><!-- close .ac-elementor-carousel-container  -->

			<div class="clearfix"></div>
							
		</div><!-- close .container -->

	</div><!-- close #content-pro -->
	@endif



    {{-- Last announcments --}}
	@if(count($lastannouncements))
	<div id="content-pro">
		
		<div class="container custom-gutters-pro">
			
			<div style="height:15px;"></div>
			
			<h2 class="post-list-heading">Latest announcements<span>Know more about what we've been cooking recently</span></h2>
			

			<div class="ac-elementor-carousel-container ac-always-arrows-on">
				<div id="progression-video-carousel" class="owl-carousel progression-carousel-theme allprogression-video-carousel">
					
				
					@foreach ($lastannouncements as $lastcontent)
						@render('frontend.includes.videoitem', [ 'content' => $lastcontent, 'show_details' => false ])
					@endforeach

					{{--DISCOVER MORE CONTENT --}}
					<div class="item">
						<div class="ac-video-index-container">
							<a href="{{ route('frontend.auth.login') }}" data-toggle="modal" data-target="#LoginModal">
								<div class="ac-video-feaured-image" style="-webkit-filter: blur(10px); -moz-filter: blur(10px); -o-filter: blur(10px); -ms-filter: blur(10px); filter: blur(10px);"><img src="{{ config('ac.CDN_MEDIA') }}videos/DavidGibson/Behemoth/t.jpg"></div>
								<div class="progression-video-index-content">
									<div class="progression-video-index-table">
										<div style="margin: 0; width: 70%; text-align: center; position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);">
											<span style="
											font-weight: bold;
											line-height: 3px !important;
											font-size: 18px;
											text-align: justify;">Sign in to access all&nbsp;contents</span>
										</div><!-- close .progression-video-index-vertical-align -->
									</div><!-- close .progression-video-index-table -->
								</div><!-- close .progression-video-index-content -->
								<div class="video-index-border-hover"></div>
							
							</a>
						</div><!-- close .ac-video-index-container  -->
					</div><!-- close .item -->
					
				</div><!-- close #progression-video-carousel - See /js/script.js file for options -->
			</div><!-- close .ac-elementor-carousel-container  -->

			<div class="clearfix"></div>
							
		</div><!-- close .container -->

	</div><!-- close #content-pro -->
	@endif


	{{-- Last assets --}}
	@if(count($assets))
	<div id="content-pro">
		
		<div class="container custom-gutters-pro">
			
			<div style="height:15px;"></div>
			
			<h2 class="post-list-heading"><a href="{{ route('frontend.assets') }}">Latest assets</a><span>Get free quality character rigs</span></h2>
			

			<div class="ac-elementor-carousel-container ac-always-arrows-on">
				<div id="progression-video-carousel" class="owl-carousel progression-carousel-theme allprogression-video-carousel">
					
				
					@foreach ($assets as $asset)
						@render('frontend.includes.videoitem', [ 'content' => $asset, 'show_details' => false, 'link' => route('frontend.assets') ])
					@endforeach

					
				</div><!-- close #progression-video-carousel - See /js/script.js file for options -->
			</div><!-- close .ac-elementor-carousel-container  -->

			<div class="clearfix"></div>
							
		</div><!-- close .container -->

	</div><!-- close #content-pro -->
	@endif
	

{{-- Last contents --}}
@if(count($lastcontents))
<div id="content-pro">
	
	<div class="container custom-gutters-pro">
		
		<div style="height:15px;"></div>
		
		<h2 class="post-list-heading">Latest library addition<span>We are uploading new quality content daily</span></h2>
		

		<div class="ac-elementor-carousel-container ac-always-arrows-on">
			<div id="progression-video-carousel" class="owl-carousel progression-carousel-theme allprogression-video-carousel">
				
			
				@foreach ($lastcontents as $lastcontent)
					@render('frontend.includes.videoitem', [ 'content' => $lastcontent, 'show_category' => true ])
				@endforeach

				{{--DISCOVER MORE CONTENT --}}
				<div class="item">
					<div class="ac-video-index-container">
						<a href="{{ route('frontend.auth.login') }}" data-toggle="modal" data-target="#LoginModal">
							<div class="ac-video-feaured-image" style="-webkit-filter: blur(10px); -moz-filter: blur(10px); -o-filter: blur(10px); -ms-filter: blur(10px); filter: blur(10px);"><img src="{{ config('ac.CDN_MEDIA') }}videos/DavidGibson/Behemoth/t.jpg"></div>
							<div class="progression-video-index-content">
								<div class="progression-video-index-table">
									<div style="margin: 0; width: 70%; text-align: center; position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);">
										<span style="
										font-weight: bold;
										line-height: 3px !important;
										font-size: 18px;
										text-align: justify;">Sign in to access our complete library of free&nbsp;content</span>
									</div><!-- close .progression-video-index-vertical-align -->
								</div><!-- close .progression-video-index-table -->
							</div><!-- close .progression-video-index-content -->
							<div class="video-index-border-hover"></div>
						
						</a>
					</div><!-- close .ac-video-index-container  -->
				</div><!-- close .item -->
				
			</div><!-- close #progression-video-carousel - See /js/script.js file for options -->
		</div><!-- close .ac-elementor-carousel-container  -->

		<div class="clearfix"></div>
						
	</div><!-- close .container -->

</div><!-- close #content-pro -->
@endif

     
	{{-- CTA --}}
	{{--
	<div id="content-pro">
		
		<div class="container custom-gutters-pro">
			
			<div style="height:20px;"></div>

			<div class="row">

				<div class="col col-12 col-md-4 col-lg-4" style="text-align: center;">
					
						<a  class="btn btn-slider-pro" href="{{ route('frontend.about') }}">
						<i class="fas fa-users" style="font-size: 24px; vertical-align: sub;"></i>What is agora.community?
						</a>
					
				</div><!-- close .col -->
				
				<div class="col col-12 col-md-4 col-lg-4" style="text-align: center;">
					
						<a  class="btn btn-slider-pro" href="{{ route('frontend.auth.login') }}" data-toggle="modal" data-target="#LoginModal">
						<i class="fas fa-sign-in-alt" style="font-size: 24px; vertical-align: sub;"></i>Sign in to get started
						</a>
					
				</div><!-- close .col -->
				
				<div class="col col-12 col-md-4 col-lg-4"  style="text-align: center;">
					
						<a  class="btn btn-slider-pro" href="{{ route('frontend.contact') }}">
						<i class="fas fa-envelope" style="font-size: 24px; vertical-align: sub;"></i>Contact us
						</a>

				</div><!-- close .col -->
				
			</div>
		</div>
	</div>
	--}}

	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>


	{{-- LOGIN --}}
	@include('frontend.includes.login')

	{{-- VIDEO PLAYER CLOSE --}}
	<span class="togglebutton" id="tb"><i class="fas fa-times-circle"></i></span>

@endsection

@push('after-styles')
<style>
	#content-pro {
		padding-top: 0;
	}
	#background_video {
		margin-top: -1px;
	}
	#landing-page-container {
		margin-bottom: 50px;
	}
	.embed-responsive-35by24::before {
		padding-top: 68.57%;
	}
</style>
@endpush

@push('after-scripts')
    <script>
	$( document ).ready(function() {


		// SCROLLDOWN
		var scrolldownVisible = true;
		window.onscroll = function() { // remove scolldown image when scrolling
			if ((document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) && scrolldownVisible == true) {
				$("#scrolldown").fadeOut();
				scrolldownVisible = false;
			}
		};
		$( "#scrolldown" ).click(function() { // scroll to cotd when click
			$([document.documentElement, document.body]).animate({
    	    	scrollTop: $("#cotd").offset().top
    		}, 500);
		});

		// BG VIDEO
		var bv = new Bideo();
		bv.init({
			// Video element
			videoEl: document.querySelector('#background_video'),
			// Container element
			container: document.querySelector('body'),
			// Resize
			resize: true,
			// autoplay: false,
			isMobile: window.matchMedia('(max-width: 768px)').matches,
			playButton: document.querySelector('#play'),
			pauseButton: document.querySelector('#pause'),
			// Array of objects containing the src and type
			// of different video formats to add
			src: [
				{
				src: '{{ config('ac.CDN_MEDIA') }}agoracommunity_loop2.mp4',
				type: 'video/mp4'
				}
			],
			// What to do once video loads (initial frame)
			onLoad: function () {
				document.querySelector('#video_cover').style.display = 'none';
			}
		});
		// force playing
		document.querySelector('#background_video').play();



		$( "#playvid" ).click(function() { // scroll to cotd when click
			$("#playvid").hide();
			$("#background_video").trigger("pause");
			$("#presentation_video").css("display", "initial");
			$("#presentation_video").trigger('play');
			$("#tb").show();
		});

		$( "#tb" ).click(function() { // scroll to cotd when click
			$("#presentation_video").css("display", "none");
			$("#presentation_video").trigger('pause');
			$("#background_video").trigger("play");
			$("#playvid").show();
			$("#tb").hide();
		});

		$('#presentation_video').on('ended',function(){
			$("#presentation_video").css("display", "none");
			$("#presentation_video").trigger('pause');
			$("#background_video").trigger("play");
			$("#tb").hide();
			$("#playvid").hide();
			$("#signinta").show();

		});
		

		// A LA YOUTUBE replace img with muted version of video on hover... not working on mobile obviously
		$( ".replaceImgVid" ).hover(
			function() {
				var hv = $(this).find('video:first');
				if(hv.length) {
					hv[0].load();
					hv[0].play();
					$(".replaceImgVid").find('video:first').bind("playing", function() {
						$(this).parent().find('img:first').css("display", "none");
						$(this).css("display", "");
						
					});
				}
			}, function() {
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