@extends('frontend.layouts.app')

@section('title', app_name() . ' | Assets')

@section('content')

		<div id="content-pro" style="@if(!auth()->user())padding-top: 80px; margin-top:0px; z-index: 0; @else padding-top:0px; margin-top:20px; z-index: 999; @endif">
			
			<div id="membership-plan-background"  style="padding-top: 0px; font-size: 1.1em; text-align: justify;">
	  	 		<div class="">
		  	 		<div class="container">
                        
                        {{--<div style="text-align: center; margin-bottom: 20px; float: left; margin-right: 30px;"><img src="https://cdn.agora.community/assets/animchallenge.png"  style="width: 300px;"/></div>
                        <p style="padding-bottom: 24px;">
                            AnimChallenge is a monthly character animation challenge open to animators of all experience levels, the perfect opportunity for beginners and advanced animators alike to showcase their skills to the community. <a href="https://agora.studio" target="_blank">Agora.studio</a> is proud to be involved with this initiative and is happy to provide free original character rigs specificaly dedicated to all its participants. 
                            <span style="margin-left: 8px;"><a href="https://www.facebook.com/AnimChallenge/"><i class="fab fa-facebook-square fa-1x"></i> AnimChallenge on Facebook</a></span>
                        </p>--}}

                        {{-- LIST ASSETS --}}
                        @foreach($contents as $content)
                        <hr />
                        <div class="row">
                            <div class="col col-12 col-md-6 col-lg-6" style="padding-right: 18px;">
                                <h1>{{ $content->title }}</h1>
                                
                                @if($content->contentable->releaseDate)
                                    <div style="margin-top: -12px;">{{ $content->contentable->releaseDate->format('F Y')}}</div>
                                @endif
                                <div style="margin-top: 18px;">{!! $content->description !!}</div>
                                <div style="font-size: 24px;">
                                    @if(!auth()->user())
                                        <a href="{{ route('frontend.auth.login') }}" data-toggle="modal" data-target="#LoginModal"><i class="fas fa-download"></i>&nbsp;&nbsp;Register to download. It's free.</a>
                                    @else
                                        <a class="link-asset-download" data-contentid="{{ $content->id }}" href="{{ config('ac.CDN_MEDIA') }}{{ $content->contentable->zip }}"><i class="fas fa-download"></i>&nbsp;&nbsp;Download</a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col col-12 col-md-6 col-lg-6">
                                <div>
                                    @if($content->contentable->intro_video)
                                        <video style="width: 100%; padding: 10px;" src="{{ config('ac.CDN_MEDIA') }}{{ $content->contentable->intro_video }}" controls preload="none"@if($content->contentable->poster_intro) poster="{{ config('ac.CDN_MEDIA') }}{{ $content->contentable->poster_intro }}"@endif></video>
                                    @endif
                                    <video style="width: 100%; padding: 10px;" src="{{ config('ac.CDN_MEDIA') }}{{ $content->contentable->video }}" controls poster="{{ config('ac.CDN_MEDIA') }}{{ $content->contentable->poster }}" preload="none"></video>
                                    <div>
                                        <div class="grid" style="margin-left: 10px; margin-right: 10px;">
                                            <div class="grid-sizer"></div>
                                            <div class="previewanim{{ $content->id }}">
                                                @foreach($content->contentable->getMedia('images') as $media)
                                                    <div class="grid-item">
                                                        <a href="{{ config('ac.CDN_MEDIA') }}{{ $media->getCustomProperty('path') . $media->file_name }}" data-gall="myGallery<?php echo $content->id; ?>" class="venobox">
                                                            <img src="{{ config('ac.CDN_MEDIA') }}{{ $media->getCustomProperty('path') . $media->file_name }}" style="max-height: 78px;object-fit: cover;object-position: 0% 25%;" />
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

					</div>
	  	 		</div>
			</div>
		</div>
    
        {{-- HACK PLEASE CLEAN --}}
        <br /><br /><br /><br /><br /><br />
        
		{{-- UP --}}
        <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
        
        {{-- LOGIN --}}
        @include('frontend.includes.login')
        

@endsection

@push('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/venobox/1.8.6/venobox.min.css" />
<style>
    hr { 
        border-color: #3e3e3e;
        border-style: dashed;
        margin-top: 64px;
    }

    /* clear fix */
    .grid:after {
    content: '';
    display: block;
    clear: both;
    }

    /* ---- .grid-item ---- */
    .grid-sizer,
    .grid-item {
    width: 25%;
    }

    .grid-item {
    float: left;
    }

    .grid-item img {
    display: block;
    width: 100%;
    }

    video:focus {
    outline: none !important;
    }
</style>
@endpush
@push('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/venobox/1.8.6/venobox.min.js"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
<script>
$( document ).ready(function() {
    function applyfx() {
		$('.venobox').venobox();
				var $grid = $('.grid').imagesLoaded( function() {

					$grid.masonry({
						itemSelector: '.grid-item',
						percentPosition: true,
						columnWidth: '.grid-sizer'
					}); 
					$("img").hover(
					function() {
						$(this).stop().animate({"opacity": "0.8"}, "fast");
					},
					function() {
						$(this).stop().animate({"opacity": "1"}, "slow");
					});
				});

	}
	applyfx();
	
	$('.link-asset-download[data-contentid]').on('click', function(e) {
		axios.post('/metrics/asset', {
			'content_id': $(this).attr('data-contentid')
		})
		.catch(function(error) {
			console.log(error);
		});
	});
});
</script>
@endpush
