@extends('frontend.layouts.app')

@section('title', app_name() . ' | Live!')

@section('content')

<div class="row justify-content-center" style="margin-left: 0px; margin-right: 0px; padding-top: 40px; padding-bottom: 40px;">
	<div class="col col-sm-8 align-self-center">
		<h2 class="post-list-heading">Anime Challenge<span> </span></h2>
		<div class="row">		
			<div class="col col-12 col-md-6 col-lg-4" style="padding-bottom: 32px;">
				{{-- @render('frontend.includes.mentoritem', [ 'mentor' => $mentor, 'lazyload' => false ]) --}}
				<div id="twitch-embed"></div>
			</div>

			<div class="col col-12 col-md-6 col-lg-4" style="padding-bottom: 32px;">
				{{-- @render('frontend.includes.mentoritem', [ 'mentor' => $mentor, 'lazyload' => false ]) --}}
				<div id="twitch-embed-second"></div>
			</div>

			<div class="col col-12 col-md-6 col-lg-4" style="padding-bottom: 32px;">
				{{-- @render('frontend.includes.mentoritem', [ 'mentor' => $mentor, 'lazyload' => false ]) --}}
				<div id="twitch-embed-third"></div>
			</div>
		</div>

		<div class="row">		
			<div class="col col-12 col-md-6 col-lg-3" style="padding-bottom: 32px;">
				{{-- @render('frontend.includes.mentoritem', [ 'mentor' => $mentor, 'lazyload' => false ]) --}}
				<div id="twitch-embed"></div>
			</div>

			<div class="col col-12 col-md-6 col-lg-3" style="padding-bottom: 32px;">
				{{-- @render('frontend.includes.mentoritem', [ 'mentor' => $mentor, 'lazyload' => false ]) --}}
				<div id="twitch-embed-second"></div>
			</div>

			<div class="col col-12 col-md-6 col-lg-3" style="padding-bottom: 32px;">
				{{-- @render('frontend.includes.mentoritem', [ 'mentor' => $mentor, 'lazyload' => false ]) --}}
				<div id="twitch-embed-third"></div>
			</div>
		</div>
	</div>
</div>

@endsection

@push('after-styles')

@endpush

@push('after-scripts')
	<!-- Load the Twitch embed JavaScript file -->
	<script src="https://embed.twitch.tv/embed/v1.js"></script>

	 <!-- Create a Twitch.Embed object that will render within the "twitch-embed" element -->
	 <script type="text/javascript">
		
		/*new Twitch.Embed("twitch-embed", {
		  width: 854,
		  height: 480,
		  channel: "thegrefg",
		  // Only needed if this page is going to be embedded on other websites
		  parent: ["wip.agora.community", "agora.community"]
		});
		*/
	  </script>
	
	<script type="text/javascript">
		new Twitch.Player("twitch-embed", {
		  channel: "shivfps"
		});

		new Twitch.Player("twitch-embed-second", {
			channel: "tarik"
		});

		new Twitch.Player("twitch-embed-third", {
			channel: "xqcow"
		});
		
	</script>
	  
@endpush

