@extends('frontend.layouts.app')

@section('title', app_name() . ' | Discord')

@section('content')

		

		<div style="text-align: center;">
			<a style="font-size: 1.1em; margin: 0 auto; max-width: 300px; margin-top: 32px;" class="btn btn-block" href="https://discord.gg/9hJxMyR" target="_blank">	
				<i class="fab fa-discord"></i>
				<span>Join our discord server!</span>
			</a>
			<a href="https://discord.gg/9hJxMyR" target="_blank"><img src="https://cdn.agora.community/discord.jpg" style="width: 75%; margin-top: 32px; border: 12px solid #656565;" /></a>
			{{--
			<widgetbot
				server="{{ Config::get('ac.DISCORD_SERVER_ID') }}"
				channel="{{ Config::get('ac.DISCORD_CHANNEL_ID') }}"
				width="97%"
				height="600"
				id="widgetbot"
			></widgetbot>
			--}}
		</div>

		<div style="text-align: right;padding-right: 36px;padding-top: 16px;">
				
				{{--<img style="vertical-align: -webkit-baseline-middle; margin-top: -12px; margin-left: 16px;" alt="Discord" src="https://img.shields.io/discord/{{ Config::get('ac.DISCORD_SERVER_ID') }}?color=%2322b2ee&label=Discord&style=for-the-badge">--}}
			</div>

@endsection

@push('after-styles')
@endpush

@push('after-scripts')
	{{--
	<script>
		$(document).ready(function() {

			$(window).resize(function() {
				$('widgetbot').height($(window).height() - 248);
			});

			$(window).trigger('resize');
		});
	</script>
	<script src="https://cdn.jsdelivr.net/npm/@widgetbot/html-embed"></script>
	--}}
@endpush