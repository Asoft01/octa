@extends('frontend.layouts.app')

@section('title', app_name())

@section('content')

	<div id="content-pro">

		<div class="container custom-gutters-pro">

			<div style="font-size: 16px; margin-bottom: 42px; padding-bottom: 6px; border-bottom: 1px solid #252525;">
				<a href="{{ route('frontend.user.order') }}"><i class="fas fa-filter" style="margin-right: 4px;"></i> Review options</a>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<a href="{{ route('frontend.user.order.upload') }}"><i class="fas fa-file-upload" style="margin-right: 4px;"></i> Upload work</a>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<i class="fas fa-credit-card" style="margin-right: 4px;"></i> <strong>Payment</strong>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<span style="color: #3e3e3e"><i class="fas fa-check-circle" style="margin-right: 4px;"></i> Confirmation</span>
			</div>

			<div class="row">

				{{-- DETAILS --}}
				<div class="col col-12 col-md-6 col-lg-6" style="padding-right: 20px;">

					<div class="pricing-plan-container" style="padding: 20px; border-radius: 10; cursor: default; min-height: @if($currency == "CAD") 608px; @else 310px @endif">
						
						@if(!$isFreeProduct)<span style="float: right; padding-bottom: 12px;"><a href="{{ route('frontend.user.order') }}">Change currency</a></span>@endif
						
						<h1>Details</h1>
						<p><span style="font-size: 24px;">{{ $type }} review</span><br />
						Due date: {{ $duedate }}<br />
                            @if($reviewer)
						Reviewer: {{ $reviewer }}<br />
                            @endif
						{{ $description }} <i>~{{ substr($length, 0, -3) }} minutes</i></br />
						Visibility: {{ $visibility }}</p>

						@if($currency == 'CAD')
						Before tax(es): ${{ $price }}
						<div id="HSTLine" class="display-none" style="display: none;"><label for="HSTtot" id="TotalHST">HST:</label><span> </span><span id="HSTtot"></span><span> = </span><label for="FedHSTPercent" id="FedHSTPercentLabel">Fed. (<span id="FedHSTPercent"></span>%):&nbsp;</label><span id="FedHST"></span><span> + </span><label for="ProvHSTPercent" id="ProvHSTPercentLabel">Prov. (<span id="ProvHSTPercent"></span>%):&nbsp;</label><span id="ProvHST"></span></div>
						<div id="GSTLine" class="display-none" style="display: none;"><label for="GSTtot" id="FedGST">GST (<span id="FedGSTPercent"></span>%):&nbsp;</label> <span id="GSTtot" class="display-none" style="display: none;"></span></div>
						<div id="PSTLine" class="display-none" style="display: none;"><label for="PSTtot" id="ProvPST" class="display-none" style="display: none;">PST (<span id="ProvPSTPercent"></span>%):&nbsp;</label> <label for="PSTtot" id="ProvQST" class="display-none" style="display: none;">QST (<span id="ProvQSTPercent"></span>%):&nbsp;</label><span id="PSTtot" class="display-none" style="display: none;"></span></div>
						<div id="TotalLine" class="display-none" style="display: none;"><label for="Tot" id="TotAfter" class="display-none" style="display: none;">Total after tax:&nbsp;</label></div>
						@endif

						@if($isFreeProduct)
							<h1><span style="text-decoration: line-through">{{ $symbol }}<span class="Tot">{{ $price }}</span> {{ $currency }}</span>&nbsp;$0</h1>
						@else
							<h1>{{ $symbol }}<span class="Tot">{{ $price }}</span> {{ $currency }}</h1>
						@endif


					</div>

				</div>

				{{-- PAY --}}
				<div class="col col-12 col-md-6 col-lg-6">
					<div class="pricing-plan-container" style="padding: 20px; border-radius: 10; cursor: default; min-height: @if($currency == "CAD") 608px; @else 329px @endif">

						@if($isFreeProduct)
							<form action="{{ route('frontend.user.order.payment.stripe') }}" method="post" id="payment-form">
								{{ csrf_field() }}
								<h1>Free public reviews</h1>
								<i>For a short period of time</i>

								<div style="text-align: center; margin-top: 30px; width: 100%;">
									<button id="bsubmit" style="width: 240px; margin: 0 auto;">
										<div class="spinner hidden" id="spinner"></div>
										<span id="button-text">Complete order</span>
									</button>
								</div>

								@if($termApprobation)
									<div style="text-align: right; margin-top: 60px;">
										<input type="checkbox" name="terms" value="{{ $termApprobation }}" id="terms" /> <label for="terms" style="vertical-align: baseline; margin-left: 4px;">I agree to the <a href="{{ route('frontend.terms') }}" target="_blank">terms and conditions</a></label>
									</div>
								@endif
							</form>
						@else
							<img src="{{ config('ac.CDN_MEDIA') }}powered_by_stripe.svg" style="position: absolute; bottom: 22px; right: 22px;">
							<h1>Payment</h1>

							@if($currency == "CAD")
							<div class="cell ac stripepay" id="ac-5">
								<form id="billing">
								<fieldset>

									<div class="row">
										<div class="field">
											<label for="name">Name</label>
											<input name="name" id="name" class="input" type="text" placeholder="" value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" required autocomplete="name">
										</div>
									</div>

									<div class="row">
										<div class="field">
											<label for="address-line1">Address</label>
											<input name="address-line1" id="address-line1" class="input" type="text" required autocomplete="address-line1" value="{{ auth()->user()->address }}">
										</div>
									</div>

									<div class="row">
										<div class="col col-12 col-md-6 col-lg-6">
											<label for="city">City</label>
											<input id="city" name="city" class="input" type="text" required autocomplete="address-level2" value="{{ auth()->user()->city }}">
										</div>
										<div class="col col-12 col-md-6 col-lg-6">
											<label for="province" id="vprovince">Province</label>
											<select id="province" style="background: transparent; color: white; border-bottom: 1px solid #39393d; width: 100%" name="province" autocomplete="address-level1" required>
												<option value="">Select...</option>
												<option value="ab"{{ auth()->user()->province == "ab" ? " selected" : "" }}>Alberta</option>
												<option value="bc"{{ auth()->user()->province == "bc" ? " selected" : "" }}>British Columbia</option>
												<option value="mb"{{ auth()->user()->province == "mb" ? " selected" : "" }}>Manitoba</option>
												<option value="nb"{{ auth()->user()->province == "nb" ? " selected" : "" }}>New Brunswick</option>
												<option value="nfl"{{ auth()->user()->province == "nfl" ? " selected" : "" }}>Newfoundland and Labrador</option>
												<option value="nt"{{ auth()->user()->province == "nt" ? " selected" : "" }}>Northwest Territories</option>
												<option value="ns"{{ auth()->user()->province == "ns" ? " selected" : "" }}>Nova Scotia</option>
												<option value="nvt"{{ auth()->user()->province == "nvt" ? " selected" : "" }}>Nunavut</option>
												<option value="on"{{ auth()->user()->province == "on" ? " selected" : "" }}>Ontario</option>
												<option value="pei"{{ auth()->user()->province == "pei" ? " selected" : "" }}>Prince Edward Island</option>
												<option value="qc"{{ auth()->user()->province == "qc" ? " selected" : "" }}>Quebec</option>
												<option value="sk"{{ auth()->user()->province == "sk" ? " selected" : "" }}>Saskatchewan</option>
												<option value="yk"{{ auth()->user()->province == "yk" ? " selected" : "" }}>Yukon</option>
											</select>
										</div>
									</div>

									<div class="row">
										<div class="col col-12 col-md-6 col-lg-6">
											<label for="postalcode" id="vpostalcode">Postal code</label>
											<input name="postalcode" id="postalcode" class="input empty" type="text" required autocomplete="postal-code" value="{{ auth()->user()->postalcode }}">
										</div>
										<div class="col col-12 col-md-6 col-lg-6">
											<label for="country">Country</label>
											<input name="country" id="country" class="input empty" type="text" value="Canada" readonly autocomplete="country-name">
										</div>
									</div>
								</fieldset>
								</form>
								<img src="{{ config('ac.CDN_MEDIA') }}powered_by_stripe.svg" style="position: absolute; bottom: 22px; right: 22px;">
							</div>
							@endif

							@if ($message = Session::get('fail-message'))
							<div class="custom-alerts alert alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
								{!! $message !!}
							</div>
							<?php Session::forget('fail-message');?>
							@endif

							<form action="{{ route('frontend.user.order.payment.stripe') }}" method="post" id="payment-form">
								{{ csrf_field() }}

								<div id="stripeform" style="margin-top: 22px;">

									<div id="card-element">
									<!-- A Stripe Element will be inserted here. -->
									</div>
									<button id="bsubmit">
										<div class="spinner hidden" id="spinner"></div>
										<span id="button-text">Pay {{ $symbol }}<span class="Tot">{{ $price }}</span> {{ $currency }}<i class="fas fa-lock" style="padding-left: 4px;"></i></span>
									</button>
									<div id="card-errors" role="alert"></div>
									@if($termApprobation)
										<div style="text-align: right; margin-top: 8px;">
											<input type="checkbox" name="terms" value="{{ $termApprobation }}" id="terms" /> <label for="terms" style="vertical-align: baseline; margin-left: 4px;">I agree to the <a href="{{ route('frontend.terms') }}" target="_blank">terms and conditions</a></label>
										</div>
									@endif
								</div>

							</form>
						@endif
					</div>

				<div class="clearfix"></div>
			</div>
		</div><!-- close .container -->
	</div><!-- close #content-pro -->

	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

@endsection

@push('before-styles')
<style>
#stripepay-paymentRequest {
  width: 100%;
  margin-bottom: 10px;
}

.ac.stripepay .row {
  display: -ms-flexbox;
  display: flex;
  margin: 0 0 10px;
}

.ac.stripepay .field {
  position: relative;
  width: 100%;
}

.ac.stripepay .field + .field {
  margin-left: 10px;
}

.ac.stripepay label {
  width: 100%;
  font-size: 13px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.ac.stripepay .input {
  width: 100%;
  color: #fff;
  background: transparent;
  padding: 5px 0 6px 0;
  border-bottom: 1px solid #39393d;

}

.ac.stripepay select {
  width: 100%;
  color: #fff;
  background: transparent;
  padding: 5px 0 6px 0;
  border-bottom: 1px solid #39393d;

}

.ac.stripepay .input::-webkit-input-placeholder {
  color: #fff;
}

.ac.stripepay .input::-moz-placeholder {
  color: #fff;
}

.ac.stripepay .input:-ms-input-placeholder {
  color: #fff;
}

.ac.stripepay input:-webkit-autofill,
.ac.stripepay select:-webkit-autofill {
  -webkit-text-fill-color: #ffffff;
  transition: background-color 100000000s;
  -webkit-animation: 1ms void-animation-out;
}

.ac.stripepay .StripeElement--webkit-autofill {
  background: transparent !important;
}

.ac.stripepay input,
.ac.stripepay button,
.ac.stripepay select {
  -webkit-animation: 1ms void-animation-out;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  outline: none;
  border-style: none;
  border-radius: 0;
}

.ac.stripepay select.input,
.ac.stripepay select:-webkit-autofill {
  background-image: url('data:image/svg+xml;utf8,<svg width="10px" height="5px" viewBox="0 0 10 5" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path fill="#fff" d="M5.35355339,4.64644661 L9.14644661,0.853553391 L9.14644661,0.853553391 C9.34170876,0.658291245 9.34170876,0.341708755 9.14644661,0.146446609 C9.05267842,0.0526784202 8.92550146,-2.43597394e-17 8.79289322,0 L1.20710678,0 L1.20710678,0 C0.930964406,5.07265313e-17 0.707106781,0.223857625 0.707106781,0.5 C0.707106781,0.632608245 0.759785201,0.759785201 0.853553391,0.853553391 L4.64644661,4.64644661 L4.64644661,4.64644661 C4.84170876,4.84170876 5.15829124,4.84170876 5.35355339,4.64644661 Z" id="shape"></path></svg>');
  background-position: 100%;
  background-size: 10px 5px;
  background-repeat: no-repeat;
  overflow: hidden;
  text-overflow: ellipsis;
  padding-right: 20px;
}

/* Buttons and links */
button {
  background: #22b2ee;
  color: #ffffff;
  font-family: Arial, sans-serif;
  border-radius: 0 0 4px 4px;
  border: 0;
  padding: 12px 16px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  display: block;
  transition: all 0.2s ease;
  box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
  width: 100%;
}
button:hover {
  filter: contrast(115%);
}
button:disabled {
  opacity: 0.5;
  cursor: default;
}
.result-message {
  line-height: 22px;
  font-size: 16px;
}

.result-message a {
  color: rgb(89, 111, 214);
  font-weight: 600;
  text-decoration: none;
}

.hidden {
  display: none;
}

#card-error {
  color: rgb(105, 115, 134);
  text-align: left;
  font-size: 13px;
  line-height: 17px;
  margin-top: 12px;
}

#card-element {
  border-radius: 4px 4px 0 0 ;
  padding: 12px;
  border: 1px solid rgba(50, 50, 93, 0.1);
  height: 44px;
  width: 100%;
  background: white;
}

#payment-request-button {
  margin-bottom: 32px;
}


/* spinner/processing state, errors */
.spinner,
.spinner:before,
.spinner:after {
  border-radius: 50%;
}
.spinner {
  color: #ffffff;
  font-size: 22px;
  text-indent: -99999px;
  margin: 0px auto;
  position: relative;
  width: 20px;
  height: 20px;
  box-shadow: inset 0 0 0 2px;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
}
.spinner:before,
.spinner:after {
  position: absolute;
  content: "";
}
.spinner:before {
  width: 10.4px;
  height: 20.4px;
  background: #22b2ee;
  border-radius: 20.4px 0 0 20.4px;
  top: -0.2px;
  left: -0.2px;
  -webkit-transform-origin: 10.4px 10.2px;
  transform-origin: 10.4px 10.2px;
  -webkit-animation: loading 2s infinite ease 1.5s;
  animation: loading 2s infinite ease 1.5s;
}
.spinner:after {
  width: 10.4px;
  height: 10.2px;
  background: #22b2ee;
  border-radius: 0 10.2px 10.2px 0;
  top: -0.1px;
  left: 10.2px;
  -webkit-transform-origin: 0px 10.2px;
  transform-origin: 0px 10.2px;
  -webkit-animation: loading 2s infinite ease;
  animation: loading 2s infinite ease;
}

@-webkit-keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
</style>
@endpush

@push('after-styles')
<style>
label {
	margin: 0px;
	margin-bottom: -6px;
}
</style>
@endpush

@push('after-scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>

// REAL PAYMENT WITH STRIPE
@if(!$isFreeProduct)
	@if($currency == "CAD")
	//................................................................
	//CANADA ONLY SALES TAXES
	//FINAL PRICE VALIDATION IS DONE IN BACK-END JUST SAYING...
	//................................................................
	function currencyFormat(num, sLocale){
		//alert ("in currencyFormat test is " + num.toFixed(2));
		var nFixed = num.toFixed(2);
		var str = nFixed.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
		if(str.indexOf(".") > 0) {
			parts = str.split(".");
			str = parts[0];
		}
		str = str.split("").reverse();
		for(var j = 0, len = str.length; j < len; j++) {
			if(str[j] != ",") {
				output.push(str[j]);
				if(i%3 == 0 && j < (len - 1)) {
					if(sLocale == "fr-CA")
						output.push(" ");
					else
						output.push(",");
				}
				i++;
			}
		}
		formatted = output.reverse().join("");
		var retVal = "";
		if(sLocale == "fr-CA")
			retVal = formatted + ((parts) ? "," + parts[1].substr(0, 2) : "") + " $";
		else
			retVal = "$" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : "");
		return retVal;
	};

	$("#province").change(function() {
		calcCADtaxes();
	});

	@if(auth()->user()->province)
		calcCADtaxes();
	@endif

	function calcCADtaxes() {
		$("#HSTLine").hide();
		$("#GSTLine").hide();
		$("#PSTLine").hide();
		$("#ProvQST").hide();

		var ProvOrTerr = $("#province").val();
		if(ProvOrTerr) {
			var amount = {{ $price }};
			var pstRate, pst = .00;
			var gstRate, gst = .00;
			var hstTot = .00;
			var tot = .00
			var hstLabel = false;

			// set default locale to English then check the lang attribute
			var sLocale = "en-CA";

			// Set tax rates for the selected province
			switch (ProvOrTerr) {
				case 'ab':
					gst = .05;
					break;
				case 'bc':
					gst = .05;
					pst = .07;
					break;
				case 'mb':
					gst = .05;
					pst = .07;
					break;
				case 'nb':
					gst = .05;
					pst = .10;
					hstLabel = true;
					break;
				case 'nfl':
					gst = .05;
					pst = .10;
					hstLabel = true;
					break;
				case 'nt':
					gst = .05;
					break;
				case 'ns':
					gst = .05;
					pst = .10;
					hstLabel = true;
					break;
				case 'nvt':
					gst = .05;
					break;
				case 'on':
					gst = .05;
					pst = .08;
					hstLabel = true;
					break;
				case 'pei':
					gst = .05;
					pst = .10;
					hstLabel = true;
					break;
				case 'qc':
					gst = .05;
					pst = .09975;
					break;
				case 'sk':
					gst = .05;
					pst = .06;
					break;
				case 'yk':
					gst = .05;
					break;
			}

			// Show the tax rates
			if (hstLabel) {
				$("#FedHSTPercent").text(Math.round(gst * 100));
				$("#ProvHSTPercent").text(Math.round(pst * 100));
				$("#HSTLine").show();
			}
			else if (ProvOrTerr === 'qc') {
				$("#FedGSTPercent").text(Math.round(gst * 100));
				$("#ProvQSTPercent").text(9.975);
				$("#GSTLine").show();
				$("#PSTLine").show();
				$("#ProvQST").show();
			}
			else {
				$("#FedGSTPercent").text(Math.round(gst * 100));
				$("#ProvPSTPercent").text(Math.round(pst * 100));
				$("#GSTLine").show();
				$("#PSTLine").show();
				$("#ProvPST").show();
			}

			gst = Number(Math.round(((amount*1) * gst)+'e2')+'e-2');
			pst = (amount*1) * pst;
			tot = (amount*1) + gst + pst;
			$("#TotAfter").show();

			// Return results of calculation
			pst = Number(Math.round(pst+'e2')+'e-2');
			tot = Number(Math.round(tot+'e2')+'e-2');

			if (hstLabel) {
				$("#FedHST").text(currencyFormat(gst, sLocale));
				$("#ProvHST").text(currencyFormat(pst, sLocale));
				$("#HSTtot").text(currencyFormat(gst + pst, sLocale));
				$("#TotalLine").show();
			}
			else {
				$("#GSTtot").text(currencyFormat(gst, sLocale));
				$("#PSTtot").text(currencyFormat(pst, sLocale));
				$("#PSTtot").show();
				$("#GSTtot").show();
				$("#TotalLine").show();
			}

			$(".Tot").text(tot.toFixed(2));
		}

	};
	@endif





	//........................................................................
	//STRIPE
	//........................................................................
	// Create a Stripe client.
	var stripe = Stripe('{{ config('stripe.stripe_key') }}');

	// Create an instance of Elements.
	var elements = stripe.elements();

	// Custom styling can be passed to options when creating an Element.
	// (Note that this demo uses a wider set of styles than the guide below.)
	var style = {
		base: {
			color: '#32325d',
			fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
			fontSmoothing: 'antialiased',
			fontSize: '16px',
			'::placeholder': {
				color: '#aab7c4'
			}
		},
		invalid: {
			color: '#fa755a',
			iconColor: '#fa755a'
		}
	};

	// Create an instance of the card Element.
	var card = elements.create('card',
	{
		style: style,
		hidePostalCode:true
	});

	// Add an instance of the card Element into the `card-element` <div>.
	card.mount('#card-element');

	// Handle real-time validation errors from the card Element.
	card.addEventListener('change', function(event) {
	var displayError = document.getElementById('card-errors');
	if (event.error) {
		displayError.textContent = event.error.message;
	} else {
		displayError.textContent = '';
	}
	});

	// Handle form submission.
	var form = document.getElementById('payment-form');
	var fv = true;
	form.addEventListener('submit', function(event) {
		event.preventDefault();
		@if($currency == "CAD")
			// validation
			var fv = document.getElementById('billing').checkValidity()
			document.getElementById('billing').reportValidity();

			// help card validation
			var options = {
				name: document.getElementById('name').value,
				address_country: "CA"
			};
		@else
			var options = {
			};
		@endif

		stripe.createToken(card, options).then(function(result) {
			if (result.error) {
				// Inform the user if there was an error.
				var errorElement = document.getElementById('card-errors');
				errorElement.textContent = result.error.message;
			} else {
				// Send the token to your server.
				if(fv) {
					stripeTokenHandler(result.token);
				}
			}
		});
	});

	// Submit the form with the token ID.
	function stripeTokenHandler(token) {
		loading(true);
		// Insert the token ID into the form so it gets submitted to the server
		var form = document.getElementById('payment-form');

		var hiddenInput = document.createElement('input');
		hiddenInput.setAttribute('type', 'hidden');
		hiddenInput.setAttribute('name', 'stripeToken');
		hiddenInput.setAttribute('value', token.id);
		form.appendChild(hiddenInput);

		var hiddenInputname = document.createElement('input');
		hiddenInputname.setAttribute('type', 'hidden');
		hiddenInputname.setAttribute('name', 'name');
		hiddenInputname.setAttribute('value', $("#name").val());
		form.appendChild(hiddenInputname);

		var hiddenInputadd = document.createElement('input');
		hiddenInputadd.setAttribute('type', 'hidden');
		hiddenInputadd.setAttribute('name', 'address');
		hiddenInputadd.setAttribute('value', $("#address-line1").val());
		form.appendChild(hiddenInputadd);

		var hiddenInputcity = document.createElement('input');
		hiddenInputcity.setAttribute('type', 'hidden');
		hiddenInputcity.setAttribute('name', 'city');
		hiddenInputcity.setAttribute('value', $("#city").val());
		form.appendChild(hiddenInputcity);

		var hiddenInputprovince = document.createElement('input');
		hiddenInputprovince.setAttribute('type', 'hidden');
		hiddenInputprovince.setAttribute('name', 'province');
		hiddenInputprovince.setAttribute('value', $("#province").val());
		form.appendChild(hiddenInputprovince);

		var hiddenInputpc = document.createElement('input');
		hiddenInputpc.setAttribute('type', 'hidden');
		hiddenInputpc.setAttribute('name', 'postalcode');
		hiddenInputpc.setAttribute('value', $("#postalcode").val());
		form.appendChild(hiddenInputpc);

		var hiddenInputcountry = document.createElement('input');
		hiddenInputcountry.setAttribute('type', 'hidden');
		hiddenInputcountry.setAttribute('name', 'country');
		hiddenInputcountry.setAttribute('value', $("#country").val());
		form.appendChild(hiddenInputcountry);

		// Submit the form
		form.submit();
	}

// FREEMODE
@else

	var form = document.getElementById('payment-form');
	form.addEventListener('submit', function(event) {
		loading(true);
		event.preventDefault();
		form.submit();
	});

@endif




// GENERIC STUFF
@if($termApprobation)

	$("#bsubmit").prop('disabled', true);

	$('#terms').click(function(){
		if ($('#terms').is(":checked")) {
			$("#bsubmit").prop('disabled', false);
		} else {
			$("#bsubmit").prop('disabled', true);
		}
	});
@endif

// Show a spinner on payment submission
var loading = function(isLoading) {
	if (isLoading) {
		// Disable the button and show a spinner
		$("#bsubmit").prop('disabled', true);
		$("#spinner").removeClass("hidden");
		$("#button-text").addClass("hidden");
		$("#button-lock").addClass("hidden");
	} else {
		$("#bsumbit").prop('disabled', false);
		$("#spinner").addClass("hidden");
		$("#button-lock").removeClass("hidden");
	}
};
</script>
@endpush
