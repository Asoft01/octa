@extends('frontend.layouts.app')

@section('title', app_name())

@section('content')

	<div id="content-pro">

		<div class="container custom-gutters-pro">

		<div style="font-size: 16px; margin-bottom: 42px; padding-bottom: 6px; border-bottom: 1px solid #252525;">
				<span style="color: #3e3e3e"><i class="fas fa-filter" style="margin-right: 4px;"></i> Review options</span>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<span style="color: #3e3e3e"><i class="fas fa-file-upload" style="margin-right: 4px;"></i> Upload work</span>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<span style="color: #3e3e3e"><i class="fas fa-credit-card" style="margin-right: 4px;"></i> Payment</span>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<i class="fas fa-check-circle" style="margin-right: 4px;"></i> <strong>Confirmation</strong>
			</div>

			<h1>Confirmation</h1>

			<div id="invoice" style="margin-top: 24px; border: solid 1px #ccc; padding: 22px;">
				<div id="print" style="float: right; cursor: pointer;"><i class="fas fa-print"></i></div>
				<strong>Agora-VFX Inc.</strong><br>
				7170 de Normenville<br>
				Montreal, Canada<br>
				H2R 2T8<br>
				<a href="mailto:sales@agora.studio">sales@agora.studio</a></p>

				<table style="width: 100%;">
					<tbody>
					<tr style="background-color: #252525;">
						<td>Order</td>
						<td>Date</td>
					</tr>
						<tr>
						<td>{{ $uuid }}</td>
						<td>{{ $orderdate}}</td>
					</tr>
					<tr style="background-color: #252525;">
						<td>Item</td>
						<td>Paid amount</td>
					</tr>
                    <tr>
					<td>{!! $item !!}</td>
					<td style="vertical-align: top;"><strong>{{ $symbol ?? '' }}{{ $price }} {{ $currency }}</strong></td>
					</tr>
                                       </tbody>
				</table>

			</div>

            <div>

                @if($meeting && $meeting['status']=='success')
                    <p></p>
                    <p>
                        Connect to the <a href="{{$meeting['meeting_url']}}">link</a> on {{ timezone()->convertToLocal(new \Carbon\Carbon($meeting['datetime'])) }}
                    </p>
                @endif
            </div>
			<div style="margin-top: 24px; font-size: 24px;">You will receive an invoice by email at <strong>{{ $useremail }}</strong></div>
			<div style="margin-top: 24px;">
			You can manage your review using the menu <i class="fas fa-angle-down"></i> and clicking on <a href="{{ route('frontend.user.manage') }}"><i class="fas fa-cogs"></i> Manage my reviews</a><br />
			For any concerns or questions, please <a href="{{route('frontend.contact')}}">contact us</a>.<br />
			Thank you for choosing Agora.community!</div>

			<div class="clearfix"></div>
		</div><!-- close .container -->
	</div><!-- close #content-pro -->

	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

@endsection

@push('before-styles')

@endpush

@push('after-scripts')
	{!! script(mix_cdn('js/printThis.js')) !!}
	<script>
        $(document).ready(function() {
			$("#print").on("click", function(){
				$("#invoice").printThis({
					header: "<h1>Agora.community - INVOICE</h1>"
				});
			});
        });
    </script>
@endpush
