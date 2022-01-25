@php
	isset($class) || $class = 'text-secondary';
	isset($icon) || $icon = 'fa-question';
	isset($size) || $size = '50%';
	isset($title) || $title = 'Tooltip';
@endphp
{{--
	<!-- Remember to add the following script to your template to enable Bootstrap tooltips. -->
	<script>$(document).ready(function() { $('[data-toggle="tooltip"]').tooltip(); });</script>
--}}
<span class="help {{ $class }}" data-toggle="tooltip" data-placement="top" title="{{ $title }}">
	<span class="fa-stack" style="{{ $size ? "font-size: {$size};" : '' }}" aria-hidden="true">
		<i class="far fa-circle fa-stack-2x" aria-hidden="true"></i>
		<i class="fas {{ $icon }} fa-stack-1x" aria-hidden="true"></i>
	</span>
	<span class="sr-only">({{ $title }})</span>
</span>