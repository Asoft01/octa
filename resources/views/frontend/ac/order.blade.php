@extends('frontend.layouts.app')

@section('title', app_name())

@section('content')

	<div id="content-pro">
		<div class="container custom-gutters-pro">

			<div style="font-size: 16px; margin-bottom: 42px; padding-bottom: 6px; border-bottom: 1px solid #252525;">
				<i class="fas fa-filter" style="margin-right: 4px;"></i> <strong>Review options</strong>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<span style="color: #3e3e3e"><i class="fas fa-file-upload" style="margin-right: 4px;"></i> Upload work</span>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<span style="color: #3e3e3e"><i class="fas fa-credit-card" style="margin-right: 4px;"></i> Payment</span>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<span style="color: #3e3e3e"><i class="fas fa-check-circle" style="margin-right: 4px;"></i> Confirmation</span>
			</div>


			<form action="{{ route('frontend.user.order.post') }}" method="post" id="order-form">
			@csrf
			@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif

			@if(Session::has('fail-slot-message'))
				<p class="alert alert-info">{{ Session::get('fail-slot-message') }}</p>
			@endif
			

			<div class="row mb-3">

				<div class="col col-12 col-md-6 col-lg-6">
					<h1>Our experts <span style="font-size: 16px; color: red; display: none" id="reviewererror">* Please select a reviewer</span></h1>
					<div class="form-group" style="margin: 0px;">
						<label for="message">Who would you like to be <strong>reviewed by</strong> or <strong>get advise</strong> from:</label>
						<br>
						<select class="formi" style="width: 97%; padding-left: 10px; font-size: 18px;" name="reviewer" id="reviewer">
							<option value="">Select a reviewer</option>

							@foreach($mentors->users->sortBy('first_name') as $mentor)
								@if($mentor->account->bookeduntil < now())
									<option value="{{ $mentor->account->slug }}"
									<?php
										if((!empty($reviewer_id) && $reviewer_id == $mentor->account->id) || (!empty(Request::get('reviewer') && Request::get('reviewer') == $mentor->account->slug))) {
											echo " selected";
										}
										?>>{{ $mentor->first_name }} {{ $mentor->last_name }}</option>
								@endif
							@endforeach
						</select>

						<div id="availability" style=" width: 96%; padding-right: 10px; text-align: right;">
							<i class="fas fa-clock" style="margin-right: 6px;"></i> Estimated delivery: <span id="msg"></span>
						</div>
					</div>
				</div>
				<div class="col col-12 col-md-6 col-lg-6">
				</div>
			</div>


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
			<h1 style="margin-top: 32px;">Review options <a href="{{ route('frontend.about', 'reviews') }}" style="vertical-align: middle; color: white;"><i class="fas fa-question-circle" style="padding-right: 12px;"></i></a><span style="font-size: 16px; color: red; display: none" id="pricingerror">* Please select a review option</span></h1>
			<div class="clearfix"></div>
			@if($freeMode)
				<div style="color: #ffb100;">For a limited period of time, we are offering free <strong>regular public</strong> reviews!<br /><strong>One review</strong> per user please.</div>
			@else
				{{--<div style="color: #ffb100;">The limited supply of free reviews have all been ordered!<br />But affordable reviews are still available at any time.</div>--}}
			@endif
			<div class="row">
				{{-- PRE-RECORDED --}}
				<div class="col col-12 col-md-6 col-lg-6 pricepadleft">
					<div class="pricing-plan-container" style="padding: 20px; padding-top: 40px; border-radius: 0; cursor: default;">
						<h1>Pre-recorded</h1>

						<div class="row">	
							<div class="col col-12 col-md-6 col-lg-6 priceseparation">
								<h3 class="public">{{--<i class="fas fa-question-circle" style="padding-right: 12px;">--}}</i>Public</h3>
								
								@foreach($prerecPublic as $pp)
									@if($freeMode)
										@if(substr($pp['min'],0,-3) == 15)
											<div class="pricing-plan-container selection" id="sprerecPublic{{ substr($pp['min'],0,-3) }}" data-priceid="{{ $pp['id'] }}" style="padding: 0px; padding-top: 20px; width: 90%; margin: 0 auto; margin-bottom: 20px; border: 1px solid #ffb100;">
												<h4 style="font-size: 30px;">{{ $pp['description'] }}</h4>
												<p style="font-size: 14px; text-align: center; margin-top: -28px; font-style: italic;">~{{ substr($pp['min'],0,-3) }} min</p>
												<h4 style="font-size:24px; margin-top: -24px;"><span style="font-size: 24px;text-decoration: line-through;" id="prerecPublic{{ substr($pp['min'],0,-3) }}">{{ $pp['symbol'] }}{{ substr($pp['price'],0,-3) }} {{ $pp['currency'] }}</span>&nbsp;<span style="font-size: 24px; font-weight: bold; color: #ffb100;">$0</span></h4>
											</div>
										@else
											<div class="pricing-plan-container selection" id="sprerecPublic{{ substr($pp['min'],0,-3) }}" data-priceid="{{ $pp['id'] }}" style="padding: 0px; padding-top: 20px; width: 90%; margin: 0 auto; margin-bottom: 20px;">
												<h4 style="font-size: 30px;">{{ $pp['description'] }}</h4>
												<p style="font-size: 14px; text-align: center; margin-top: -28px; font-style: italic;">~{{ substr($pp['min'],0,-3) }} min</p>
												<h4 style="font-size:24px; margin-top: -24px;" id="prerecPublic{{ substr($pp['min'],0,-3) }}">{{ $pp['symbol'] }}{{ substr($pp['price'],0,-3) }} {{ $pp['currency'] }}</h4>
											</div>
										@endif
									@else
										<div class="pricing-plan-container selection" id="sprerecPublic{{ substr($pp['min'],0,-3) }}" data-priceid="{{ $pp['id'] }}" style="padding: 0px; padding-top: 20px; width: 90%; margin: 0 auto; margin-bottom: 20px;">
											<h4 style="font-size: 30px;">{{ $pp['description'] }}</h4>
											<p style="font-size: 14px; text-align: center; margin-top: -28px; font-style: italic;">~{{ substr($pp['min'],0,-3) }} min</p>
											@if(!empty($pp['price']))
												<h4 style="font-size:24px; margin-top: -24px;" id="prerecPublic{{ substr($pp['min'],0,-3) }}">{{ $pp['symbol'] }}{{ substr($pp['price'],0,-3) }} {{ $pp['currency'] }}</h4>
											@else
												<h4 style="font-size:24px; margin-top: -24px;" id="prerecPublic{{ substr($pp['min'],0,-3) }}">&nbsp;</h4>
											@endif
										</div>
									@endif
								@endforeach


							</div>
							<div class="col col-12 col-md-6 col-lg-6">
								<h3 class="private">{{--<i class="fas fa-question-circle" style="padding-right: 12px;"></i>--}}Private</h3>
								@foreach($prerecPrivate as $pp)
									<div class="pricing-plan-container selection" id="sprerecPrivate{{ substr($pp['min'],0,-3) }}" data-priceid="{{ $pp['id'] }}" style="padding: 0px; padding-top: 20px; width: 90%; margin: 0 auto; margin-bottom: 20px;">
									<h4 style="font-size: 30px;">{{ $pp['description'] }}</h4>
										<p style="font-size: 14px; text-align: center; margin-top: -28px; font-style: italic;">~{{ substr($pp['min'],0,-3) }} min</p>
										@if(!empty($pp['price']))
											<h4 style="font-size:24px; margin-top: -24px;" id="prerecPrivate{{ substr($pp['min'],0,-3) }}">{{ $pp['symbol'] }}{{ substr($pp['price'],0,-3) }} {{ $pp['currency'] }}</h4>
										@else
											<h4 style="font-size:24px; margin-top: -24px;" id="prerecPrivate{{ substr($pp['min'],0,-3) }}">&nbsp;</h4>
										@endif
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
				<div class="col col-12 col-md-6 col-lg-6 pricepadright">
					
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
						
						{{--
						<ul style="margin-top: 24px;">
							<li>You will be connected with the reviewer</li>
							<li>Ask him anything, share your screen</li>
							<li>Choose who will do your review</li>
							<li>Choose the right timeframe</li>
						</ul>
						--}}
						</div>
					</div><!-- close .pricing-plan-container -->
					{{--TEMPCOMINGSOON--}}
					<div class="video-index-border-hover" style="pointer-events: all; text-align: center; z-index: 1000;">
					</div>
					<div class="video-index-border-hover" style="pointer-events: all; z-index:1001;">
						<h2 style="z-index: 1001; font-size: 38px; font-weight: 700; display: flex; justify-content: center; align-items: center; height: 100%;">Live reviews<br />coming soon</h2>
					</div>	
				</div><!-- close .col -->










				{{-- STREAM PRODUCTS --}}
				<?php /*
				@if(count($schedules)>0)


					<div class="col col-12 col-md-6 col-lg-6 pricepadleft">

						<div class="pricing-plan-container" style="padding: 20px; padding-top: 40px; border-radius: 0; cursor: default;">
							<div style="">
								<h4>Stream reviews</h4>

								<div class="row">
									<div class="col-12">
										<h5 style="font-size: 24px; margin-bottom: 0px;">Next stream review:</h5>
									</div>
									<div class="col-12 mb-2">
										@php $si=0; @endphp
										@php $fistname= null; @endphp
										@foreach($schedules as $sschedule)
											<?php
											if($si==0) {
												$firstname = $sschedule->reviewer->user->first_name;
											}
											?>
											@if($si==1)
												<p class="mb-0" style="margin-top: 12px;">Coming reviews:</p>
											@endif
											<p class="mb-0 {{$sschedule->isNextSchedule($sschedule->id)?'next-schedule':''}}">
												@if($si==0)<a href="{{ route('frontend.reviewer', $sschedule->reviewer->slug) }}">@endif{{ $sschedule->reviewer->user->first_name }} {{ $sschedule->reviewer->user->last_name }}@if($si==0)</a>@endif | <span class="schedule-duration">{{$sschedule->getAvailableTimeFormatted()}}</span>
											</p>
											@php $si++; @endphp
										@endforeach
									</div>
								</div>
								<div class="row mb-2">

											@foreach($streamPublic as $pp)
												<div class="col col-12 col-md-12 col-lg-12">
													<div class="pricing-plan-container stream-product selection" id="sstreamPublic{{ substr($pp['min'],0,-3) }}" data-priceid="{{ $pp['id'] }}" style="padding: 0px; padding-top: 20px; width: 95%; margin: 0 auto; margin-top: 20px; margin-bottom: 20px;">
														<h4 style="font-size: 30px;">{{ $pp['description'] }} from {{ $firstname }}</h4>
														<p style="font-size: 18px; text-align: center; margin-top: -28px; font-style: italic;">Tuesday 23rd March 05:00 PM - 06:00 PM (EDT)</p>
														<p style="font-size: 14px; text-align: center; margin-top: -28px; font-style: italic;">~{{ substr($pp['min'],0,-3) }} min</p>
														<h4 style="font-size:24px; margin-top: -24px;" id="streamPublic{{ substr($pp['min'],0,-3) }}">&nbsp;{{ $pp['symbol'] }}{{ substr($pp['price'],0,-3) }} {{ $pp['currency'] }}</h4>
													</div>
												</div>
											@endforeach

								</div>
							</div>
						</div><!-- close .pricing-plan-container -->

					</div><!-- close .col -->
				@endif
				*/ ?>


			</div>

			<div class="row">
				<div class="col col-12 col-md-12 col-lg-12" style="text-align: center;">
					<button type="submit" id="submit" style="width: 200px; margin: 0 auto;" class="btn">Next step</button>
				</div>
			</div>
				
			
			<input type="hidden" id="priceid" name="priceid" value="" required />
			</form>
			
			<div class="clearfix"></div>
		</div><!-- close .container -->
	</div><!-- close #content-pro -->


	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

@endsection

@push('after-styles')
@endpush

@push('after-scripts')
    <script>
        $(document).ready(function() {

			// selection checkmark and input
			$('.selection').on('click', function() {
				$(".selection").removeClass("selected");
					$(".fa-check").remove();

				
				// disable selection if price is na
				if($(this).hasClass("pricena")) {
					return 1;
				}

				if($(this).hasClass("selected")) {
					$(this).removeClass("selected");
					$(".fa-check").remove();
					$("#priceid").val("");
				} else {
					$(".selection").removeClass("selected");
					$(".fa-check").remove();
					$(this).addClass("selected");
					$(this).append('<i class="fas fa-check"></i>');
					$("#submit").prop('disabled', false);
					$("#priceid").val($(this).attr('data-priceid'));
				}
				//not needed?
				//getAvailability();
			});
			
			// validation very basic
			$("#order-form").on("submit", function(){
				if($("#reviewer").val() == "") {
					$("#reviewererror").show();
					$("#submit").prop('disabled', false);
					$('body,html').animate({ scrollTop: 0 , }, 30);
					return false;
				} else {
					$("#reviewererror").hide();
				}
				if($("#priceid").val() == "") {
					$("#pricingerror").show();
					$("#submit").prop('disabled', false);
					$('body,html').animate({ scrollTop: 0 , }, 30);
					return false;
				} else {
					$("#pricingerror").hide();
				}

				// be sure to use the .selection in the right currency
				$("#priceid").val($('.selected').attr('data-priceid'));
				
				return true;
			});


			// update price -- refactor
			$('#currency').change(function(){
				getPricesPerExperts();
			});
		 
			$("#reviewer").change(function() {

				var selected_value = $("#reviewer").val();
				if (selected_value) {
					getPricesPerExperts();

					/*
					// before we were using an alert to show the delay
					if ($.inArray(selected_value, Object.keys(delayed)) !== -1) {
						var mentor = delayed[selected_value]; // has "name", "delay.days", and "delay.human"
						Swal.fire({
							title: "Attention",
							text: "Be advised that a review from " + mentor.name + " may take about " + mentor.delay.human + " more than the expected delivery date to receive. Thanks for your understanding.",
							type: 'warning'
						});
					}*/

				} else { // empty pricing

					$("#submit").prop('disabled', true);

					$(".selection").removeClass("selected");
					$(".fa-check").remove();


					// update price
					$("#prerecPrivate15").html("&nbsp;");
					$("#prerecPrivate30").html("&nbsp;");
					$("#prerecPublic15").html("&nbsp;");
					$("#prerecPublic30").html("&nbsp;");

					$("#sprerecPublic15").addClass("pricena");
					$("#sprerecPublic15").css("opacity", "100%");
					$("#sprerecPublic30").addClass("pricena");
					$("#sprerecPublic30").css("opacity", "100%");
					$("#sprerecPrivate15").addClass("pricena");
					$("#sprerecPrivate15").css("opacity", "100%");
					$("#sprerecPrivate30").addClass("pricena");
					$("#sprerecPrivate30").css("opacity", "100%");

					// update data-priceid
					$("#sprerecPrivate15").attr("");
					$("#sprerecPrivate30").attr("");
					$("#sprerecPublic15").attr("");
					$("#sprerecPublic30").attr("");
				}
				getAvailability();
			});

			
			function getAvailability() {
				var selected_value = $("#reviewer").val();
				if (selected_value) {
					$.ajax({
						type:'GET',
						url:'/availabilityAjax/' + selected_value,
						success: function(data) {
							$("#availability").show();
							$("#msg").html(data.msg);
						}
					});
				} else {
					$("#msg").html("Select a reviewer");
				}
			}

			function getPricesPerExperts() {

				$(".selection").removeClass("selected");
				$(".fa-check").remove();
				$("#priceid").val("");

				
				$.ajax({
					type:'GET',
					url:'/currencyAjax/'+$('#currency').val()+'/'+$('#reviewer').val(),
					success:function(data) {

						// deal with currency symbols
						if($('#currency').val() == 3) {
							var cs = "€";
							var cn = "";
						} else if($('#currency').val() == 4) {
							var cs = "£";
							var cn = "";
						} else if($('#currency').val() == 5) {
							var cs = "₹";
							var cn = "";
						} else {
							var cs = "$";
							var cn = $('#currency option:selected').text();
						}

						// ------------------------
						// UPDATE PRICES
						// ------------------------
						

						// Pre-recorded Public 15min
						if(data[1]['prerecPublic'].hasOwnProperty("15")) {
							$("#sprerecPublic15").css("opacity", "100%");
							$("#sprerecPublic15").removeClass("pricena");
							$("#prerecPublic15").html(cs + data[1]['prerecPublic']['15']['price'].slice(0, -3) + " " + cn);
							// update data-priceid
							$("#sprerecPublic15").attr('data-priceid', data[1]['prerecPublic']['15']['id']);
						} else {
							$("#prerecPublic15").html("Not available");
							$("#sprerecPublic15").addClass("pricena");
							$("#sprerecPublic15").css("opacity", "50%");
							$("#sprerecPublic15").attr('data-priceid',"");
						}

						// Pre-recorded Public 30min
						if(data[1]['prerecPublic'].hasOwnProperty("30")) {
							$("#sprerecPublic30").css("opacity", "100%");
							$("#sprerecPublic30").removeClass("pricena");
							$("#prerecPublic30").html(cs + data[1]['prerecPublic']['30']['price'].slice(0, -3) + " " + cn);
							// update data-priceid
							$("#sprerecPublic30").attr('data-priceid', data[1]['prerecPublic']['30']['id']);
						} else {
							$("#prerecPublic30").html("Not available");
							$("#sprerecPublic30").addClass("pricena");
							$("#sprerecPublic30").css("opacity", "50%");
							$("#sprerecPublic30").attr('data-priceid',"");
						}


						// Pre-recorded Private 15min
						if(data[0]['prerecPrivate'].hasOwnProperty("15")) {
							$("#sprerecPrivate15").css("opacity", "100%");
							$("#sprerecPrivate15").removeClass("pricena");
							$("#prerecPrivate15").html(cs + data[0]['prerecPrivate']['15']['price'].slice(0, -3) + " " + cn);
							// update data-priceid
							$("#sprerecPrivate15").attr('data-priceid', data[0]['prerecPrivate']['15']['id']);
						} else {
							$("#sprerecPrivate15").css("opacity", "50%");
							$("#sprerecPrivate15").addClass("pricena");
							$("#prerecPrivate15").html("Not available");
							$("#sprerecPrivate15").attr('data-priceid',"");
						}

						// Pre-recorded Private 30min
						if(data[0]['prerecPrivate'].hasOwnProperty("30")) {
							$("#sprerecPrivate30").css("opacity", "100%");
							$("#sprerecPrivate30").removeClass("pricena");
							$("#prerecPrivate30").html(cs + data[0]['prerecPrivate']['30']['price'].slice(0, -3) + " " + cn);
							// update data-priceid
							$("#sprerecPrivate30").attr('data-priceid', data[0]['prerecPrivate']['30']['id']);
						} else {
							$("#sprerecPrivate30").css("opacity", "50%");
							$("#sprerecPrivate30").addClass("pricena");
							$("#prerecPrivate30").html("Not available");
							$("#sprerecPrivate30").attr('data-priceid',"");
						}
/*
						// Stream products
						if(typeof(data[4])!='undefined') {
                            $("#streamPublic15").html(cs + data[4]['streamPublic']['15']['price'].slice(0, -3) + " " + cn);
                            $("#streamPublic30").html(cs + data[4]['streamPublic']['30']['price'].slice(0, -3) + " " + cn);
                            $("#streamPublic15").attr('data-priceid', data[4]['streamPublic']['15']['id']);
                            $("#streamPublic30").attr('data-priceid', data[4]['streamPublic']['30']['id']);
                        }
                        if(typeof(data[5])!='undefined') {
                            $("#streamPrivate15").html(cs + data[5]['streamPrivate']['15']['price'].slice(0, -3) + " " + cn);
                            $("#streamPrivate30").html(cs + data[5]['streamPrivate']['30']['price'].slice(0, -3) + " " + cn);
                            $("#streamPrivate15").attr('data-priceid', data[5]['streamPrivate']['15']['id']);
                            $("#streamPrivate30").attr('data-priceid', data[5]['streamPrivate']['30']['id']);
                        }						
*/
						// update priceid input hidden
						$("#priceid").val($('.selected').attr('data-priceid'));

						// selection based on session
						<?php if(!empty($price_id)) { ?>

							if($('[data-priceid="<?=$price_id;?>"]').length) {
								$('[data-priceid="<?=$price_id;?>"]').addClass("selected");
								$('[data-priceid="<?=$price_id;?>"]').append('<i class="fas fa-check"></i>');
								$("#priceid").val(<?=$price_id;?>);
								$("#submit").prop('disabled', false);
							} else {
								$("#submit").prop('disabled', true);
							}
						<?php } ?>
					}
				});	
			};

			// Triggering the "change" event will show the delay warning popup, if applicable.
			if (_.includes(['navigate', 'unknown'], getNavigationType())) {
				$('#reviewer').trigger('change');
			} else {
				getPricesPerExperts();
				getAvailability();
			}


			function getNavigationType() {
				if (_.has(window, 'performance')) {
					// Using the experimental PerformanceNavigationTiming interface.
					// https://developer.mozilla.org/en-US/docs/Web/API/PerformanceNavigationTiming
					if (_.isFunction(window.performance.getEntriesByType)) {
						var entries = window.performance.getEntriesByType('navigation');
						if (entries.length > 0) {
							return entries[0].type;
						}
					}
					// Using the deprecated PerformanceNavigation interface.
					// https://developer.mozilla.org/en-US/docs/Web/API/PerformanceNavigation
					if (_.has(window.performance, 'navigation')) {
						var navigation = window.performance.navigation;
						switch(navigation.type) {
							case navigation.TYPE_BACK_FORWARD: return 'back_forward';
							case navigation.TYPE_NAVIGATE: return 'navigate';
							case navigation.TYPE_RELOAD: return 'reload';
						}
					}
				}
				return 'unknown';
			}

			
        });


    </script>
@endpush
