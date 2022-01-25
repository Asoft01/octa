@extends('frontend.layouts.app')

@section('title', app_name() . ' | About')

@section('content')

		
		<div id="content-pro" style="padding-top: 0px; margin-top: 20px; z-index: 999;">
			
			<div id="membership-plan-background"  style="padding-top: 0px; font-size: 1.1em; text-align: justify;">
	  	 		<div class="">
		  	 		<div class="container">

					   <ul id="dashboard-sub-menu" class="tab-menu">
							<li style="width: 25%" id="about"><a href="#tab-1">About</a></li>
							<li style="width: 25%" id="tabreview"><a href="#tab-2" id="tabreviewc">Reviews</a></li>
							<li style="width: 25%"><a href="#tab-3">FAQ</a></li>
							<li style="width: 25%"><a href="#tab-4">Terms and conditions</a></li>
						</ul>




						<div id="tab-1" class="tab-content">
                            
							<video id="Video-Vayvo-Single" style="height:auto; width: 100%" preload="auto" poster="{{ config('ac.CDN_MEDIA') }}introvideo_p2.jpg" data-autoresize="fit" class="video-js vjs-default-skin vjs-big-play-centered" controls data-setup='{"fluid": true, "playbackRates": [0.5, 1, 1.5, 2]}'>
								<source src="{{ config('ac.CDN_MEDIA') }}agoracommunity.mp4" type="video/mp4">
							</video>
							<p style="margin-top: 32px;">Agora.Community is a digital hub whose purpose is to entertain, inspire, educate and provide opportunities to be mentored by industry veterans. Whether you call it education 2.0, the ‘free to play’ of education, or anything else, our aspiration is to make knowledge accessible to all and have fun.</p>
							<p>Throughout human history, knowledge has been shared from one generation to the next by means of mentorships. Eventually, academies were created where large groups could gather and learn from masters. Academies evolved into brick and mortar schools and universities, and the internet gave birth to online schools and removed geographic barriers for everyone.</p>
							<p>As the internet matured so did people’s appetite for information. Today we are consuming over 1 billion hours of content each day. All of the answers are on the internet, but due to the sheer volume of content it has become increasingly difficult to find quality material on any given topic. For example, 500 hours of video are uploaded to YouTube every single minute. Who has time to sift through it all?</p>
							<p>Enter, Agora.Community. We’ll scour the internet to gather all the quality educational content for you. We organize it with a tag-based search system in a free digital library where you can quickly and easily find what you need. While we’re at it, we’ll publish new and exclusive content from some of your favourite existing content creators as well as offer our own content created by our ever growing league of Agora.Community industry pros. Did we mention that access to this incredible online library is free?</p>
							<p>To complete your online learning experience, we are supporting all of this content by providing access to affordable personal reviews of your work by our veteran Agora.Community team members. If you choose to allow your review to be public, it will be added to the library so everyone can learn from the valuable feedback on your hard work.</p>
							<p>With our library of content growing daily, stay tuned for lots of exciting upcoming content including: special guest podcasts, discussion forums and even the live streaming of artist’s at work during production, just to name a few. Furthermore, in an effort to make your learning process even more fun and engaging, we’ll be adding a layer of gamification mechanics that will track your progress and reward you with special status and recognition on the Agora.Community platform. We can’t wait to watch this platform grow into the dynamic and diverse collection of artists that we know it is destined to become. Moreover, we look forward to growing with you through the experience of knowledge sharing.</p>
							<p>Welcome to the community.</p>
							<p>Sincerely,</br />
							The Agora Family</p>
						</div>

					   

						<div id="tab-2" class="tab-content">

							<h1>Review options</h1>
							<p>We are offering multiple review options. Here’s all you need to know to choose the review adapted to your specific needs.</p>

							<div class="row">
								<div class="col col-12 col-md-6 col-lg-6 pricepadleft" style="padding: 0px;">
									<div class="pricing-plan-container" style="padding: 20px; padding-top: 40px; border-radius: 0; cursor: default; min-height: 376px;">
									<h2>Pre-recorded</h2>
									<p>The reviewer selected will receive your order and perform the review shortly after. The time needed to receive your review will depend on the number of reviews already in his or her backlog. An estimate of time will be provided during the order process. Once completed, you will receive a notification and access to the video of the review. This option is ideal if the reviewer’s availability for live reviews doesn’t suit your own schedule.</p>
									</div>
								</div>
								<div class="col col-12 col-md-6 col-lg-6 pricepadright" style="padding: 0px;">
									<div class="pricing-plan-container" style="padding: 20px; padding-top: 40px; border-radius: 0; cursor: default; min-height: 376px;">
									<h2>Live (available soon)</h2>
									<p>You will be provided a schedule with each reviewer’s availability and requested to select a time slot. A direct link to the meeting will then be provided once you have completed payment. When that time of review comes, simply click on the link provided and you will be connected to your reviewer. Once the review is completed, the recording of the review will be provided. Live reviews provide the opportunity to interact directly with the reviewer, which is ideal to ask questions and discuss specific feedback.</p>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col col-12 col-md-6 col-lg-6 pricepadleft" style="padding: 0px;">
									<div class="pricing-plan-container" style="padding: 20px; padding-top: 40px; border-radius: 0; cursor: default; min-height: 256px;">
									<h2>Public</h2>
									<p>Public reviews will be automatically included in our library of content available to all users. Be advised that once you agree to make your review public, it will not be possible to make it private afterwards, although we will consider special circumstances.</p>
									</div>
								</div>
								<div class="col col-12 col-md-6 col-lg-6 pricepadright" style="padding: 0px;">
									<div class="pricing-plan-container" style="padding: 20px; padding-top: 40px; border-radius: 0; cursor: default; min-height: 256px;">
									<h2>Private</h2>
									<p>Private reviews will only show up in your own private page, no one else will have access to it.</p>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col col-12 col-md-6 col-lg-6 pricepadleft" style="padding: 0px;">
									<div class="pricing-plan-container" style="padding: 20px; padding-top: 40px; border-radius: 0; cursor: default; min-height: 236px;">
									<h2>Regular review</h2>
									<p>Around 15 minutes, those are great to receive general feedback on your animation and a few precise actionable suggestions to improve the quality of your animation overall.</p>
									</div>
								</div>
								<div class="col col-12 col-md-6 col-lg-6 pricepadright" style="padding: 0px;">
									<div class="pricing-plan-container" style="padding: 20px; padding-top: 40px; border-radius: 0; cursor: default; min-height: 236px;">
									<h2>Long review</h2>
									<p>Around 30 minutes, this format allows in-depth analysis and detailed feedback, while providing the opportunity to explore and discuss various ideas that will benefit your animation.</p>
									</div>
								</div>
							</div>
						
						
						
						

						{{--
						<h1 style="margin-top: 44px;">Library</h1>
						<p style="margin-top: -8px;">Free<br />
						Our ever-growing library of content is <strong>free for all</strong>.</p>
						--}}

							@if(!auth()->user())
                            <h1 style="margin-top: 48px;">Pricing</h1>
                            <p>Login to see the pricing for affordable personal reviews by our veteran Agora.Community team members.</p>
                            <?php /*
							<div class="form-group" style="float: right; margin-right: 0px;">
								<span style="margin-right: 6px;">Currency:</span>
								<select class="formi" style="padding-left: 10px; padding-right: 8px;font-size: 18px;" name="currency" id="currency">
									@foreach($currencies as $currency)
										<option value="{{ $currency->id }}"
										<?php
										if((!empty($currency_id) && $currency_id == $currency->id) || (!empty(Request::get('currency') && Request::get('currency') == $currency->id))) {
											echo " selected";
										}
										?>
										>{{ $currency->iso }}</option>
									@endforeach
								</select>
							</div>
							<h1 style="margin-top: 48px;">Pricing</h1>
							<div class="clearfix"></div>

							<div class="row">
									{{-- PRE-RECORDED --}}
									<div class="col col-12 col-md-6 col-lg-6" style="padding-right: 20px;">
										<div class="pricing-plan-container" style="padding: 20px; padding-top: 40px; border-radius: 0; cursor: default;">
											<h4>Pre-recorded</h4>
											<div class="row">	
												<div class="col col-12 col-md-6 col-lg-6 priceseparation">
													<h3 class="public">{{--<i class="fas fa-question-circle" style="padding-right: 12px;"></i>--}}Public</h3>
													
													@foreach($prerecPublic as $pp)
													<div class="pricing-plan-container selection" id="sprerecPublic{{ substr($pp['min'],0,-3) }}" data-priceid="{{ $pp['id'] }}" style="padding: 0px; padding-top: 20px; width: 90%; margin: 0 auto; margin-bottom: 20px;">
														<h4 style="font-size: 30px;">{{ $pp['description'] }}</h4>
														<p style="font-size: 14px; text-align: center; margin-top: -28px; font-style: italic;">~{{ substr($pp['min'],0,-3) }} min</p>
														<h4 style="font-size:24px; margin-top: -24px;" id="prerecPublic{{ substr($pp['min'],0,-3) }}">{{ $pp['symbol'] }}{{ substr($pp['price'],0,-3) }} {{ $pp['currency'] }}</h4>
													</div>
													@endforeach


												</div>
												<div class="col col-12 col-md-6 col-lg-6">
													<h3 class="private">{{--<i class="fas fa-question-circle" style="padding-right: 12px;"></i>--}}Private</h3>
													
													@foreach($prerecPrivate as $pp)
													<div class="pricing-plan-container selection" id="sprerecPrivate{{ substr($pp['min'],0,-3) }}" data-priceid="{{ $pp['id'] }}" style="padding: 0px; padding-top: 20px; width: 90%; margin: 0 auto; margin-bottom: 20px;">
													<h4 style="font-size: 30px;">{{ $pp['description'] }}</h4>
														<p style="font-size: 14px; text-align: center; margin-top: -28px; font-style: italic;">~{{ substr($pp['min'],0,-3) }} min</p>
														<h4 style="font-size:24px; margin-top: -24px;" id="prerecPrivate{{ substr($pp['min'],0,-3) }}">{{ $pp['symbol'] }}{{ substr($pp['price'],0,-3) }} {{ $pp['currency'] }}</h4>
													</div>
													@endforeach

												</div>
											</div>
											
											{{--
											<ul style="margin-top: 24px;">
												<li>Reviewer will record himself doing the review</li>
												<li>Choose who will do your review</li>
												<li>You have write directly to the reviewer</li>
												<li>We use syncsketch</li>
											</ul>
											--}}
										
										</div><!-- close .pricing-plan-container -->
									</div><!-- close .col -->
							
							
									{{-- LIVE --}}
									<div class="col col-12 col-md-6 col-lg-6" style="padding-left: 20px;">
										
										<div class="pricing-plan-container" style="padding: 20px; padding-top: 40px; border-radius: 0; cursor: default;">
										<div style="opacity: 0.05">
										<h4>Live</h4>
											<div class="row">	
												<div class="col col-12 col-md-6 col-lg-6 priceseparation">
													<h3 class="public">{{--<i class="fas fa-question-circle" style="padding-right: 12px;"></i>--}}Public</h3>
													
													@foreach($livePublic as $pp)
													<div class="pricing-plan-container selection" id="slivePublic{{ substr($pp['min'],0,-3) }}" data-priceid="{{ $pp['id'] }}" style="padding: 0px; padding-top: 20px; width: 90%; margin: 0 auto; margin-bottom: 20px;">
														<h4 style="font-size: 30px;">{{ $pp['description'] }}</h4>
														<p style="font-size: 14px; text-align: center; margin-top: -28px; font-style: italic;">~{{ substr($pp['min'],0,-3) }} min</p>
														<h4 style="font-size:24px; margin-top: -24px;" id="livePublic{{ substr($pp['min'],0,-3) }}">&nbsp;{{--{{ $pp['symbol'] }}{{ substr($pp['price'],0,-3) }} {{ $pp['currency'] }}--}}</h4>
													</div>
													@endforeach


												</div>
												<div class="col col-12 col-md-6 col-lg-6">
													<h3 class="private">{{--<i class="fas fa-question-circle" style="padding-right: 12px;"></i>--}}Private</h3>
													
													@foreach($livePrivate as $pp)
													<div class="pricing-plan-container selection" id="slivePrivate{{ substr($pp['min'],0,-3) }}" data-priceid="{{ $pp['id'] }}" style="padding: 0px; padding-top: 20px; width: 90%; margin: 0 auto; margin-bottom: 20px;">
														<h4 style="font-size: 30px;">{{ $pp['description'] }}</h4>
														<p style="font-size: 14px; text-align: center; margin-top: -28px; font-style: italic;">~{{ substr($pp['min'],0,-3) }} min</p>
														<h4 style="font-size:24px; margin-top: -24px;" id="livePrivate{{ substr($pp['min'],0,-3) }}">&nbsp;{{--{{ $pp['symbol'] }}{{ substr($pp['price'],0,-3) }} {{ $pp['currency'] }}--}}</h4>
													</div>
													@endforeach

												</div>
											</div>
											
									
											</div>
										</div><!-- close .pricing-plan-container -->
										{{--TEMPCOMINGSOON--}}
										<div class="video-index-border-hover" style="pointer-events: all; text-align: center; z-index: 1000;">
										</div>
										<div class="video-index-border-hover" style="pointer-events: all; z-index:1001;">
											<h2 style="z-index: 1001; font-size: 38px; font-weight: 700; display: flex; justify-content: center; align-items: center; height: 100%;">Live reviews<br />coming soon</h2>
										</div>	
									</div><!-- close .col -->
							</div>

							<div style="text-align: center;">
								<a href="{{route('frontend.user.order')}}" class="nav-link {{ active_class(Route::is('frontend.user.order*')) }}" style="margin-right: 0px; margin-top: 2px;">
									<span class="btn"><i class="fas fa-shopping-cart"></i> Order a review</span>
								</a>
								</div>

                            */ ?>
                            @endif




						</div>



						<div id="tab-3" class="tab-content" style="text-align: justify;">
							<h2>Can I provide suggestions for new content to be included in the library?</h2>
							<p>Of course! Our goal is to build a vast library of educational content over time and your suggestions are more than welcome. Click on the <a href="{{ route('frontend.contact') }}">Contact Us</a> button and let us know which kind of content you would like to watch; interviews, tutorials, workshop... wathever! We will consider each suggestion and establish priorities according to the feedback we receive.</p>
							<h2>How can I order a review</h2>
							<p>Simply click on the <a href="{{ route('frontend.user.order') }}">Order a review</a> button in the menu and follow the instructions. You need to register if you don't have an account already. We support linkedin and email registration.</p>
							<h2>What kind of work can be reviewed?</h2>
							<p>We will expand to various art forms and expertise over time, but at the moment we are only providing cg character animation reviews. Any type of animation at various stages of production can be reviewed. We are also open to not only provide reviews, but support and guidance if needed. Make sure to clearly indicate what feedback you are looking for when you submit the material to be reviewed during the ordering process.</p>
							<h2>Which reviewer should I choose?</h2>
							<p>We advise to choose the reviewer that has professional experience with the type of animation you are presenting. They are all experienced professional animators and educators, but if you are looking for quadruped locomotion feedback, it is advised to ask feedback from an animator with lots of VFX and professional quadruped experience. This is the reason we have instructors with varied animation background and are showcasing their personal animation showreel.</p>
							<h2>What's the price for a review?</h2>
							<p>The pricing of our different review format is indicated during the ordering process. In short, pre-recorded reviews are more affordable than live reviews, public reviews are more affordable then private reviews and normal 15 minutes reviews are more affordable then long 30 minutes reviews. This provides 6 combinations to fit your specific needs and budget.</p>
							<h2>What's the difference between a normal and long review?</h2>
							<p>Normal reviews last around 15 minutes. Those are great to get general feedback on your animation and a few precise actionable suggestions to improve the quality of your animation overall. Long reviews are 30 minutes long. This format allows in depth analysis and detailed feedback, while providing the opportunity to explore and discuss various ideas that will benefit your animation.</p>
							<h2>What's the difference between a live and pre-recorded review, and which one should I choose?</h2>
							<p>For now, we are only offering pre-recorded reviews, but live reviews will also be available soon. Pre-recorded reviews are slightly cheaper than live reviews and ideal if the reviewer's availability doesn't suit your own schedule. Live reviews will provide the opportunity to interact directly with the reviewer, which is ideal to ask questions along the way and discuss specific feedback.</p>
							<h2>What's the difference between a private and public review?</h2>
							<p>Private reviews will only show up in your own private page, no one else will have access to it. Public reviews that are slightly cheaper will be included in our library of content and will be available to all users. Be advised that once you agree to make your review public, it will not be possible to make it private afterwards, although we will consider it for special circumstances.</p>
							<h2>Can i have the same work reviewed more than once?</h2>
							<p>Yes, and perhaps this is the best way to benefit from this service. Getting feedback early in your workflow helps with general character performance choices, feedback on your blocking pass ensures strong posing and timing while feedback at the later stage of animation helps to achieve high quality polishing. Getting those multiple feedback from the same reviewer will definitely increase efficiency.</p>
							<h2>How long until I receive my review?</h2>
							<p>It depends on the number of reviews each reviewer can provide on a weekly basis and the number of reviews already in their backlog. During the order process, we indicate the time expected before receiving a review from each instructor.</p>
							<h2>Can i schedule a review in advance before i have the playblast?</h2>
							<p>We are planning to provide this option eventually, but at the moment, you need to submit the material to be reviewed during the ordering process.</p>
							<h2>Can i send an updated version of my work if it has not been reviewed yet?</h2>
							<p>This is another feature that will be implemented eventually, but for now we do not provide the opportunity to update the material provided for review once the order is placed.</p>
							<h2>How long will I have access to the review after i receive it?</h2>
							<p>Forever! That review is yours to keep. If you had chosen to make your review public, it will also remain in our library of reviews accessible to all users.</p>
							<h2>How will I receive my review?</h2>
							<p>You will receive a notification by email when your review is ready with a direct link to access it.</p>
						</div>

						<div id="tab-4" class="tab-content">
							<div style="text-align: right;font-size: 11px;">Version: {{ $terms->version }}</div>
							{!! $terms->content !!}
						</div>

						<div class="clearfix"></div>
					</div><!-- close .container -->
	  	 		</div><!-- close .membership-width-container -->
			</div><!-- close #membership-plan-background -->
		</div><!-- close #content-pro -->

		{{-- UP --}}
    	<a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

@endsection

@push('after-styles')
<style type="text/css">
            .lst-kix_list_4-1 > li {
                counter-increment: lst-ctn-kix_list_4-1;
            }
            ol.lst-kix_list_3-1 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-2 {
                list-style-type: none;
            }
            .lst-kix_list_3-1 > li {
                counter-increment: lst-ctn-kix_list_3-1;
            }
            ol.lst-kix_list_3-3 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-4.start {
                counter-reset: lst-ctn-kix_list_3-4 0;
            }
            .lst-kix_list_5-1 > li {
                counter-increment: lst-ctn-kix_list_5-1;
            }
            ol.lst-kix_list_3-4 {
                list-style-type: none;
            }
            .lst-kix_list_2-1 > li {
                counter-increment: lst-ctn-kix_list_2-1;
            }
            ol.lst-kix_list_3-0 {
                list-style-type: none;
            }
            .lst-kix_list_1-1 > li {
                counter-increment: lst-ctn-kix_list_1-1;
            }
            ol.lst-kix_list_2-6.start {
                counter-reset: lst-ctn-kix_list_2-6 0;
            }
            .lst-kix_list_3-0 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) ". ";
            }
            ol.lst-kix_list_3-1.start {
                counter-reset: lst-ctn-kix_list_3-1 0;
            }
            .lst-kix_list_3-1 > li:before {
                content: "" counter(lst-ctn-kix_list_3-1, decimal) ". ";
            }
            .lst-kix_list_3-2 > li:before {
                content: "" counter(lst-ctn-kix_list_3-2, lower-latin) ". ";
            }
            ol.lst-kix_list_1-8.start {
                counter-reset: lst-ctn-kix_list_1-8 0;
            }
            .lst-kix_list_4-0 > li {
                counter-increment: lst-ctn-kix_list_4-0;
            }
            .lst-kix_list_5-0 > li {
                counter-increment: lst-ctn-kix_list_5-0;
            }
            ol.lst-kix_list_2-3.start {
                counter-reset: lst-ctn-kix_list_2-3 0;
            }
            .lst-kix_list_3-5 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) "." counter(lst-ctn-kix_list_3-1, decimal) "." counter(lst-ctn-kix_list_3-2, lower-latin) "." counter(lst-ctn-kix_list_3-3, lower-roman) "."
                    counter(lst-ctn-kix_list_3-4, decimal) "." counter(lst-ctn-kix_list_3-5, decimal) ". ";
            }
            .lst-kix_list_3-4 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) "." counter(lst-ctn-kix_list_3-1, decimal) "." counter(lst-ctn-kix_list_3-2, lower-latin) "." counter(lst-ctn-kix_list_3-3, lower-roman) "."
                    counter(lst-ctn-kix_list_3-4, decimal) ". ";
            }
            ol.lst-kix_list_1-5.start {
                counter-reset: lst-ctn-kix_list_1-5 0;
            }
            .lst-kix_list_3-3 > li:before {
                content: "(" counter(lst-ctn-kix_list_3-3, lower-roman) ") ";
            }
            ol.lst-kix_list_3-5 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-6 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-7 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-8 {
                list-style-type: none;
            }
            .lst-kix_list_3-8 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) "." counter(lst-ctn-kix_list_3-1, decimal) "." counter(lst-ctn-kix_list_3-2, lower-latin) "." counter(lst-ctn-kix_list_3-3, lower-roman) "."
                    counter(lst-ctn-kix_list_3-4, decimal) "." counter(lst-ctn-kix_list_3-5, decimal) "." counter(lst-ctn-kix_list_3-6, decimal) "." counter(lst-ctn-kix_list_3-7, decimal) "." counter(lst-ctn-kix_list_3-8, decimal) ". ";
            }
            .lst-kix_list_2-0 > li {
                counter-increment: lst-ctn-kix_list_2-0;
            }
            ol.lst-kix_list_5-3.start {
                counter-reset: lst-ctn-kix_list_5-3 0;
            }
            .lst-kix_list_2-3 > li {
                counter-increment: lst-ctn-kix_list_2-3;
            }
            .lst-kix_list_3-6 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) "." counter(lst-ctn-kix_list_3-1, decimal) "." counter(lst-ctn-kix_list_3-2, lower-latin) "." counter(lst-ctn-kix_list_3-3, lower-roman) "."
                    counter(lst-ctn-kix_list_3-4, decimal) "." counter(lst-ctn-kix_list_3-5, decimal) "." counter(lst-ctn-kix_list_3-6, decimal) ". ";
            }
            .lst-kix_list_4-3 > li {
                counter-increment: lst-ctn-kix_list_4-3;
            }
            .lst-kix_list_3-7 > li:before {
                content: "" counter(lst-ctn-kix_list_3-0, decimal) "." counter(lst-ctn-kix_list_3-1, decimal) "." counter(lst-ctn-kix_list_3-2, lower-latin) "." counter(lst-ctn-kix_list_3-3, lower-roman) "."
                    counter(lst-ctn-kix_list_3-4, decimal) "." counter(lst-ctn-kix_list_3-5, decimal) "." counter(lst-ctn-kix_list_3-6, decimal) "." counter(lst-ctn-kix_list_3-7, decimal) ". ";
            }
            ol.lst-kix_list_4-5.start {
                counter-reset: lst-ctn-kix_list_4-5 0;
            }
            ol.lst-kix_list_5-0.start {
                counter-reset: lst-ctn-kix_list_5-0 0;
            }
            .lst-kix_list_1-2 > li {
                counter-increment: lst-ctn-kix_list_1-2;
            }
            ol.lst-kix_list_3-7.start {
                counter-reset: lst-ctn-kix_list_3-7 0;
            }
            .lst-kix_list_5-2 > li {
                counter-increment: lst-ctn-kix_list_5-2;
            }
            ol.lst-kix_list_4-2.start {
                counter-reset: lst-ctn-kix_list_4-2 0;
            }
            .lst-kix_list_3-2 > li {
                counter-increment: lst-ctn-kix_list_3-2;
            }
            ol.lst-kix_list_2-2 {
                list-style-type: none;
            }
            ol.lst-kix_list_2-3 {
                list-style-type: none;
            }
            .lst-kix_list_5-0 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) ". ";
            }
            ol.lst-kix_list_2-4 {
                list-style-type: none;
            }
            ol.lst-kix_list_2-5 {
                list-style-type: none;
            }
            .lst-kix_list_5-4 > li {
                counter-increment: lst-ctn-kix_list_5-4;
            }
            .lst-kix_list_1-4 > li {
                counter-increment: lst-ctn-kix_list_1-4;
            }
            .lst-kix_list_4-4 > li {
                counter-increment: lst-ctn-kix_list_4-4;
            }
            ol.lst-kix_list_2-0 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-6.start {
                counter-reset: lst-ctn-kix_list_1-6 0;
            }
            ol.lst-kix_list_2-1 {
                list-style-type: none;
            }
            .lst-kix_list_4-8 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) "." counter(lst-ctn-kix_list_4-1, decimal) "." counter(lst-ctn-kix_list_4-2, lower-latin) "." counter(lst-ctn-kix_list_4-3, lower-roman) "."
                    counter(lst-ctn-kix_list_4-4, decimal) "." counter(lst-ctn-kix_list_4-5, decimal) "." counter(lst-ctn-kix_list_4-6, decimal) "." counter(lst-ctn-kix_list_4-7, decimal) "." counter(lst-ctn-kix_list_4-8, decimal) ". ";
            }
            .lst-kix_list_5-3 > li:before {
                content: "(" counter(lst-ctn-kix_list_5-3, lower-roman) ") ";
            }
            .lst-kix_list_4-7 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) "." counter(lst-ctn-kix_list_4-1, decimal) "." counter(lst-ctn-kix_list_4-2, lower-latin) "." counter(lst-ctn-kix_list_4-3, lower-roman) "."
                    counter(lst-ctn-kix_list_4-4, decimal) "." counter(lst-ctn-kix_list_4-5, decimal) "." counter(lst-ctn-kix_list_4-6, decimal) "." counter(lst-ctn-kix_list_4-7, decimal) ". ";
            }
            .lst-kix_list_5-2 > li:before {
                content: "" counter(lst-ctn-kix_list_5-2, lower-latin) ". ";
            }
            .lst-kix_list_5-1 > li:before {
                content: "" counter(lst-ctn-kix_list_5-1, decimal) ". ";
            }
            .lst-kix_list_5-7 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) "." counter(lst-ctn-kix_list_5-1, decimal) "." counter(lst-ctn-kix_list_5-2, lower-latin) "." counter(lst-ctn-kix_list_5-3, lower-roman) "."
                    counter(lst-ctn-kix_list_5-4, decimal) "." counter(lst-ctn-kix_list_5-5, decimal) "." counter(lst-ctn-kix_list_5-6, decimal) "." counter(lst-ctn-kix_list_5-7, decimal) ". ";
            }
            ol.lst-kix_list_5-6.start {
                counter-reset: lst-ctn-kix_list_5-6 0;
            }
            .lst-kix_list_5-6 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) "." counter(lst-ctn-kix_list_5-1, decimal) "." counter(lst-ctn-kix_list_5-2, lower-latin) "." counter(lst-ctn-kix_list_5-3, lower-roman) "."
                    counter(lst-ctn-kix_list_5-4, decimal) "." counter(lst-ctn-kix_list_5-5, decimal) "." counter(lst-ctn-kix_list_5-6, decimal) ". ";
            }
            .lst-kix_list_5-8 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) "." counter(lst-ctn-kix_list_5-1, decimal) "." counter(lst-ctn-kix_list_5-2, lower-latin) "." counter(lst-ctn-kix_list_5-3, lower-roman) "."
                    counter(lst-ctn-kix_list_5-4, decimal) "." counter(lst-ctn-kix_list_5-5, decimal) "." counter(lst-ctn-kix_list_5-6, decimal) "." counter(lst-ctn-kix_list_5-7, decimal) "." counter(lst-ctn-kix_list_5-8, decimal) ". ";
            }
            ol.lst-kix_list_4-1.start {
                counter-reset: lst-ctn-kix_list_4-1 0;
            }
            ol.lst-kix_list_4-8.start {
                counter-reset: lst-ctn-kix_list_4-8 0;
            }
            ol.lst-kix_list_3-3.start {
                counter-reset: lst-ctn-kix_list_3-3 0;
            }
            .lst-kix_list_5-4 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) "." counter(lst-ctn-kix_list_5-1, decimal) "." counter(lst-ctn-kix_list_5-2, lower-latin) "." counter(lst-ctn-kix_list_5-3, lower-roman) "."
                    counter(lst-ctn-kix_list_5-4, decimal) ". ";
            }
            .lst-kix_list_5-5 > li:before {
                content: "" counter(lst-ctn-kix_list_5-0, decimal) "." counter(lst-ctn-kix_list_5-1, decimal) "." counter(lst-ctn-kix_list_5-2, lower-latin) "." counter(lst-ctn-kix_list_5-3, lower-roman) "."
                    counter(lst-ctn-kix_list_5-4, decimal) "." counter(lst-ctn-kix_list_5-5, decimal) ". ";
            }
            ol.lst-kix_list_2-6 {
                list-style-type: none;
            }
            ol.lst-kix_list_2-7 {
                list-style-type: none;
            }
            ol.lst-kix_list_2-8 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-0.start {
                counter-reset: lst-ctn-kix_list_1-0 0;
            }
            .lst-kix_list_3-0 > li {
                counter-increment: lst-ctn-kix_list_3-0;
            }
            .lst-kix_list_3-3 > li {
                counter-increment: lst-ctn-kix_list_3-3;
            }
            ol.lst-kix_list_4-0.start {
                counter-reset: lst-ctn-kix_list_4-0 0;
            }
            .lst-kix_list_3-6 > li {
                counter-increment: lst-ctn-kix_list_3-6;
            }
            .lst-kix_list_2-5 > li {
                counter-increment: lst-ctn-kix_list_2-5;
            }
            .lst-kix_list_2-8 > li {
                counter-increment: lst-ctn-kix_list_2-8;
            }
            ol.lst-kix_list_3-2.start {
                counter-reset: lst-ctn-kix_list_3-2 0;
            }
            ol.lst-kix_list_5-5.start {
                counter-reset: lst-ctn-kix_list_5-5 0;
            }
            .lst-kix_list_2-2 > li {
                counter-increment: lst-ctn-kix_list_2-2;
            }
            ol.lst-kix_list_2-4.start {
                counter-reset: lst-ctn-kix_list_2-4 0;
            }
            ol.lst-kix_list_4-7.start {
                counter-reset: lst-ctn-kix_list_4-7 0;
            }
            ol.lst-kix_list_1-3 {
                list-style-type: none;
            }
            ol.lst-kix_list_5-0 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-4 {
                list-style-type: none;
            }
            .lst-kix_list_2-6 > li:before {
                content: "" counter(lst-ctn-kix_list_2-6, decimal) ". ";
            }
            .lst-kix_list_2-7 > li:before {
                content: "" counter(lst-ctn-kix_list_2-7, lower-latin) ". ";
            }
            .lst-kix_list_2-7 > li {
                counter-increment: lst-ctn-kix_list_2-7;
            }
            .lst-kix_list_3-7 > li {
                counter-increment: lst-ctn-kix_list_3-7;
            }
            ol.lst-kix_list_5-1 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-5 {
                list-style-type: none;
            }
            ol.lst-kix_list_5-2 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-6 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-0 {
                list-style-type: none;
            }
            .lst-kix_list_2-4 > li:before {
                content: "" counter(lst-ctn-kix_list_2-4, lower-latin) ". ";
            }
            .lst-kix_list_2-5 > li:before {
                content: "" counter(lst-ctn-kix_list_2-5, lower-roman) ". ";
            }
            .lst-kix_list_2-8 > li:before {
                content: "" counter(lst-ctn-kix_list_2-8, lower-roman) ". ";
            }
            ol.lst-kix_list_1-1 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-2 {
                list-style-type: none;
            }
            ol.lst-kix_list_5-4.start {
                counter-reset: lst-ctn-kix_list_5-4 0;
            }
            ol.lst-kix_list_4-6.start {
                counter-reset: lst-ctn-kix_list_4-6 0;
            }
            ol.lst-kix_list_5-1.start {
                counter-reset: lst-ctn-kix_list_5-1 0;
            }
            ol.lst-kix_list_3-0.start {
                counter-reset: lst-ctn-kix_list_3-0 0;
            }
            ol.lst-kix_list_5-7 {
                list-style-type: none;
            }
            ol.lst-kix_list_5-8 {
                list-style-type: none;
            }
            .lst-kix_list_5-7 > li {
                counter-increment: lst-ctn-kix_list_5-7;
            }
            ol.lst-kix_list_4-3.start {
                counter-reset: lst-ctn-kix_list_4-3 0;
            }
            ol.lst-kix_list_5-3 {
                list-style-type: none;
            }
            ol.lst-kix_list_1-7 {
                list-style-type: none;
            }
            .lst-kix_list_4-7 > li {
                counter-increment: lst-ctn-kix_list_4-7;
            }
            ol.lst-kix_list_5-4 {
                list-style-type: none;
            }
            .lst-kix_list_1-7 > li {
                counter-increment: lst-ctn-kix_list_1-7;
            }
            ol.lst-kix_list_1-8 {
                list-style-type: none;
            }
            ol.lst-kix_list_3-8.start {
                counter-reset: lst-ctn-kix_list_3-8 0;
            }
            ol.lst-kix_list_5-5 {
                list-style-type: none;
            }
            ol.lst-kix_list_5-6 {
                list-style-type: none;
            }
            ol.lst-kix_list_2-5.start {
                counter-reset: lst-ctn-kix_list_2-5 0;
            }
            .lst-kix_list_5-8 > li {
                counter-increment: lst-ctn-kix_list_5-8;
            }
            .lst-kix_list_4-0 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) ". ";
            }
            .lst-kix_list_2-6 > li {
                counter-increment: lst-ctn-kix_list_2-6;
            }
            .lst-kix_list_3-8 > li {
                counter-increment: lst-ctn-kix_list_3-8;
            }
            .lst-kix_list_4-1 > li:before {
                content: "" counter(lst-ctn-kix_list_4-1, decimal) ". ";
            }
            .lst-kix_list_4-6 > li {
                counter-increment: lst-ctn-kix_list_4-6;
            }
            ol.lst-kix_list_1-7.start {
                counter-reset: lst-ctn-kix_list_1-7 0;
            }
            .lst-kix_list_4-4 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) "." counter(lst-ctn-kix_list_4-1, decimal) "." counter(lst-ctn-kix_list_4-2, lower-latin) "." counter(lst-ctn-kix_list_4-3, lower-roman) "."
                    counter(lst-ctn-kix_list_4-4, decimal) ". ";
            }
            ol.lst-kix_list_2-2.start {
                counter-reset: lst-ctn-kix_list_2-2 0;
            }
            .lst-kix_list_1-5 > li {
                counter-increment: lst-ctn-kix_list_1-5;
            }
            .lst-kix_list_4-3 > li:before {
                content: "(" counter(lst-ctn-kix_list_4-3, lower-roman) ") ";
            }
            .lst-kix_list_4-5 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) "." counter(lst-ctn-kix_list_4-1, decimal) "." counter(lst-ctn-kix_list_4-2, lower-latin) "." counter(lst-ctn-kix_list_4-3, lower-roman) "."
                    counter(lst-ctn-kix_list_4-4, decimal) "." counter(lst-ctn-kix_list_4-5, decimal) ". ";
            }
            .lst-kix_list_4-2 > li:before {
                content: "" counter(lst-ctn-kix_list_4-2, lower-latin) ". ";
            }
            .lst-kix_list_4-6 > li:before {
                content: "" counter(lst-ctn-kix_list_4-0, decimal) "." counter(lst-ctn-kix_list_4-1, decimal) "." counter(lst-ctn-kix_list_4-2, lower-latin) "." counter(lst-ctn-kix_list_4-3, lower-roman) "."
                    counter(lst-ctn-kix_list_4-4, decimal) "." counter(lst-ctn-kix_list_4-5, decimal) "." counter(lst-ctn-kix_list_4-6, decimal) ". ";
            }
            ol.lst-kix_list_5-7.start {
                counter-reset: lst-ctn-kix_list_5-7 0;
            }
            .lst-kix_list_1-8 > li {
                counter-increment: lst-ctn-kix_list_1-8;
            }
            ol.lst-kix_list_1-4.start {
                counter-reset: lst-ctn-kix_list_1-4 0;
            }
            .lst-kix_list_5-5 > li {
                counter-increment: lst-ctn-kix_list_5-5;
            }
            .lst-kix_list_3-5 > li {
                counter-increment: lst-ctn-kix_list_3-5;
            }
            ol.lst-kix_list_1-1.start {
                counter-reset: lst-ctn-kix_list_1-1 0;
            }
            ol.lst-kix_list_4-0 {
                list-style-type: none;
            }
            .lst-kix_list_3-4 > li {
                counter-increment: lst-ctn-kix_list_3-4;
            }
            ol.lst-kix_list_4-1 {
                list-style-type: none;
            }
            ol.lst-kix_list_4-4.start {
                counter-reset: lst-ctn-kix_list_4-4 0;
            }
            ol.lst-kix_list_4-2 {
                list-style-type: none;
            }
            ol.lst-kix_list_4-3 {
                list-style-type: none;
            }
            .lst-kix_list_2-4 > li {
                counter-increment: lst-ctn-kix_list_2-4;
            }
            ol.lst-kix_list_3-6.start {
                counter-reset: lst-ctn-kix_list_3-6 0;
            }
            .lst-kix_list_5-3 > li {
                counter-increment: lst-ctn-kix_list_5-3;
            }
            ol.lst-kix_list_1-3.start {
                counter-reset: lst-ctn-kix_list_1-3 0;
            }
            ol.lst-kix_list_2-8.start {
                counter-reset: lst-ctn-kix_list_2-8 0;
            }
            ol.lst-kix_list_1-2.start {
                counter-reset: lst-ctn-kix_list_1-2 0;
            }
            ol.lst-kix_list_4-8 {
                list-style-type: none;
            }
            .lst-kix_list_1-0 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) ". ";
            }
            ol.lst-kix_list_4-4 {
                list-style-type: none;
            }
            ol.lst-kix_list_4-5 {
                list-style-type: none;
            }
            .lst-kix_list_1-1 > li:before {
                content: "" counter(lst-ctn-kix_list_1-1, decimal) ". ";
            }
            .lst-kix_list_1-2 > li:before {
                content: "" counter(lst-ctn-kix_list_1-2, lower-latin) ". ";
            }
            ol.lst-kix_list_2-0.start {
                counter-reset: lst-ctn-kix_list_2-0 0;
            }
            ol.lst-kix_list_4-6 {
                list-style-type: none;
            }
            ol.lst-kix_list_4-7 {
                list-style-type: none;
            }
            .lst-kix_list_1-3 > li:before {
                content: "(" counter(lst-ctn-kix_list_1-3, lower-roman) ") ";
            }
            .lst-kix_list_1-4 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) "." counter(lst-ctn-kix_list_1-1, decimal) "." counter(lst-ctn-kix_list_1-2, lower-latin) "." counter(lst-ctn-kix_list_1-3, lower-roman) "."
                    counter(lst-ctn-kix_list_1-4, decimal) ". ";
            }
            ol.lst-kix_list_3-5.start {
                counter-reset: lst-ctn-kix_list_3-5 0;
            }
            .lst-kix_list_1-0 > li {
                counter-increment: lst-ctn-kix_list_1-0;
            }
            .lst-kix_list_4-8 > li {
                counter-increment: lst-ctn-kix_list_4-8;
            }
            .lst-kix_list_1-6 > li {
                counter-increment: lst-ctn-kix_list_1-6;
            }
            .lst-kix_list_1-7 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) "." counter(lst-ctn-kix_list_1-1, decimal) "." counter(lst-ctn-kix_list_1-2, lower-latin) "." counter(lst-ctn-kix_list_1-3, lower-roman) "."
                    counter(lst-ctn-kix_list_1-4, decimal) "." counter(lst-ctn-kix_list_1-5, decimal) "." counter(lst-ctn-kix_list_1-6, decimal) "." counter(lst-ctn-kix_list_1-7, decimal) ". ";
            }
            ol.lst-kix_list_5-8.start {
                counter-reset: lst-ctn-kix_list_5-8 0;
            }
            ol.lst-kix_list_2-7.start {
                counter-reset: lst-ctn-kix_list_2-7 0;
            }
            .lst-kix_list_1-3 > li {
                counter-increment: lst-ctn-kix_list_1-3;
            }
            .lst-kix_list_1-5 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) "." counter(lst-ctn-kix_list_1-1, decimal) "." counter(lst-ctn-kix_list_1-2, lower-latin) "." counter(lst-ctn-kix_list_1-3, lower-roman) "."
                    counter(lst-ctn-kix_list_1-4, decimal) "." counter(lst-ctn-kix_list_1-5, decimal) ". ";
            }
            .lst-kix_list_1-6 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) "." counter(lst-ctn-kix_list_1-1, decimal) "." counter(lst-ctn-kix_list_1-2, lower-latin) "." counter(lst-ctn-kix_list_1-3, lower-roman) "."
                    counter(lst-ctn-kix_list_1-4, decimal) "." counter(lst-ctn-kix_list_1-5, decimal) "." counter(lst-ctn-kix_list_1-6, decimal) ". ";
            }
            .lst-kix_list_5-6 > li {
                counter-increment: lst-ctn-kix_list_5-6;
            }
            .lst-kix_list_2-0 > li:before {
                content: "SCHEDULE " counter(lst-ctn-kix_list_2-0, upper-latin) "  ";
            }
            .lst-kix_list_2-1 > li:before {
                content: "" counter(lst-ctn-kix_list_2-1, lower-latin) ". ";
            }
            ol.lst-kix_list_2-1.start {
                counter-reset: lst-ctn-kix_list_2-1 0;
            }
            .lst-kix_list_4-5 > li {
                counter-increment: lst-ctn-kix_list_4-5;
            }
            .lst-kix_list_1-8 > li:before {
                content: "" counter(lst-ctn-kix_list_1-0, decimal) "." counter(lst-ctn-kix_list_1-1, decimal) "." counter(lst-ctn-kix_list_1-2, lower-latin) "." counter(lst-ctn-kix_list_1-3, lower-roman) "."
                    counter(lst-ctn-kix_list_1-4, decimal) "." counter(lst-ctn-kix_list_1-5, decimal) "." counter(lst-ctn-kix_list_1-6, decimal) "." counter(lst-ctn-kix_list_1-7, decimal) "." counter(lst-ctn-kix_list_1-8, decimal) ". ";
            }
            .lst-kix_list_2-2 > li:before {
                content: "" counter(lst-ctn-kix_list_2-2, lower-roman) ". ";
            }
            .lst-kix_list_2-3 > li:before {
                content: "" counter(lst-ctn-kix_list_2-3, decimal) ". ";
            }
            .lst-kix_list_4-2 > li {
                counter-increment: lst-ctn-kix_list_4-2;
            }
            ol.lst-kix_list_5-2.start {
                counter-reset: lst-ctn-kix_list_5-2 0;
            }
            ol {
                margin: 0;
                padding: 0;
            }
            table td,
            table th {
                padding: 0;
            }
            .c8 {
                -webkit-text-decoration-skip: none;
                color: #c4c4c5;
                font-weight: 700;
                text-decoration: underline;
                vertical-align: baseline;
                text-decoration-skip-ink: none;
                
                
                font-style: normal;
            }
            .c3 {
                margin-left: 18pt;
                padding-top: 0pt;
                padding-left: 4.7pt;
                padding-bottom: 11pt;
                
                page-break-after: avoid;
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .c7 {
                margin-left: 72pt;
                padding-top: 0pt;
                padding-left: 14.4pt;
                padding-bottom: 11pt;
                
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .c2 {
                margin-left: 40.7pt;
                padding-top: 0pt;
                padding-left: -1pt;
                padding-bottom: 11pt;
                
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .c4 {
                color: #c4c4c5;
                font-weight: 400;
                text-decoration: none;
                vertical-align: baseline;
                
                
                font-style: italic;
            }
            .c6 {
                color: #c4c4c5;
                font-weight: 700;
                text-decoration: none;
                vertical-align: baseline;
                
                
                font-style: normal;
            }
            .c0 {
                color: #c4c4c5;
                font-weight: 400;
                text-decoration: none;
                vertical-align: baseline;
                
                
                font-style: normal;
            }
            .c11 {
                padding-top: 0pt;
                padding-bottom: 6pt;
                
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .c1 {
                padding-top: 0pt;
                
                margin-bottom: -4px;
                orphans: 2;
                widows: 2;
                text-align: center;
            }
            .c9 {
                padding-top: 0pt;
                padding-bottom: 0pt;
                
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .c12 {
                background-color: #c4c4c5;
                max-width: 432pt;
                padding: 72pt 90pt 72pt 90pt;
            }
            .c5 {
                padding: 0;
                margin: 0;
            }
            .c10 {
                font-weight: 700;
            }
            .c13 {
                height: 10pt;
            }
            .title {
                padding-top: 24pt;
                color: #c4c4c5;
                font-weight: 700;
                font-size: 36pt;
                padding-bottom: 6pt;
                
                
                page-break-after: avoid;
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            .subtitle {
                padding-top: 18pt;
                color: #c4c4c5;
                
                padding-bottom: 4pt;
                
                
                page-break-after: avoid;
                font-style: italic;
                orphans: 2;
                widows: 2;
                text-align: justify;
            }
            li {
                color: #c4c4c5;
                
                
            }
            
        </style>
@endpush
@push('after-scripts')
	<script>
        $(document).ready(function() {

			/*
            $('.public').tooltipster({
				position: 'top',
				animation: 'fade',
				delay: 200,
				theme: 'tooltipster-default',
				contentAsHTML: true,
				content: $('<span>Public reviews are publishing on the platform<br />so that other can learn from it.</span>')
			});

			$('.private').tooltipster({
				position: 'top',
				animation: 'fade',
				delay: 200,
				theme: 'tooltipster-default',
				contentAsHTML: true,
				content: $('<span>Private reviews are not publish on the platform<br />you have access via your member section.</span>')
			});*/


			// update price -- refactor
			$('#currency').change(function(){
				$.ajax({
					type:'GET',
					url:'/currencyAjax/'+$('#currency').val(),
					success:function(data) {
						if($('#currency').val() == 3) {
							var cs = "€";
							var cn = "";
                        } else if($('#currency').val() == 4) {
							var cs = "£";
							var cn = "";
						} else {
							var cs = "$";
							var cn = $('#currency option:selected').text();
						}
						// update price
						$("#prerecPrivate15").html(cs + data[0]['prerecPrivate']['15']['price'].slice(0, -3) + " " + cn);
						$("#prerecPrivate30").html(cs + data[0]['prerecPrivate']['30']['price'].slice(0, -3) + " " + cn);
						$("#prerecPublic15").html(cs + data[1]['prerecPublic']['15']['price'].slice(0, -3) + " " + cn);
						$("#prerecPublic30").html(cs + data[1]['prerecPublic']['30']['price'].slice(0, -3) + " " + cn);

						/*
						$("#livePrivate15").html(cs + data[2]['livePrivate']['15']['price'].slice(0, -3) + " " + cn);
						$("#livePrivate30").html(cs + data[2]['livePrivate']['30']['price'].slice(0, -3) + " " + cn);
						$("#livePublic15").html(cs + data[3]['livePublic']['15']['price'].slice(0, -3) + " " + cn);
						$("#livePublic30").html(cs + data[3]['livePublic']['30']['price'].slice(0, -3) + " " + cn);
						*/

						// update data-priceid
						$("#sprerecPrivate15").attr('data-priceid', data[0]['prerecPrivate']['15']['id']);
						$("#sprerecPrivate30").attr('data-priceid', data[0]['prerecPrivate']['30']['id']);
						$("#sprerecPublic15").attr('data-priceid', data[1]['prerecPublic']['15']['id']);
						$("#sprerecPublic30").attr('data-priceid', data[1]['prerecPublic']['30']['id']);
						/*
						$("#slivePrivate15").attr('data-priceid', data[2]['livePrivate']['15']['id']);
						$("#slivePrivate30").attr('data-priceid', data[2]['livePrivate']['30']['id']);
						$("#slivePublic15").attr('data-priceid', data[3]['livePublic']['15']['id']);
						$("#slivePublic30").attr('data-priceid', data[3]['livePublic']['30']['id']);
						*/

						// update priceid input hidden
						$("#priceid").val($('.selected').attr('data-priceid'));
					}
				});	
			});

			// about?reviews
			var searchParams = new URLSearchParams(window.location.search);
			if(searchParams.has('reviews')) {
				$("#tabreview").addClass('current');
				$("#tabreview").parent().siblings().removeClass('current');
				$("#tab-1").hide();
				$("#tab-2").show();
				$('.tab-content').slice(2).hide();
			} else {
				$("#about").addClass('current');
				$('.tab-content').slice(1).hide();
			}

			//tabs
			$('.tab-menu li').eq(0).addClass('active');
			$('.tab-menu li a').click(function(e) {
				e.preventDefault();
				var content = $(this).attr('href');
				$(this).parent().addClass('current');
				$(this).parent().siblings().removeClass('current');
				$(content).show();
				$(content).siblings('.tab-content').hide();
			});




			
        });
    </script>
@endpush