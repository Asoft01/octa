{{--<!--
	expected $name
	expected $collection
	optional $label
-->--}}
@php
	isset($label) || $label = $name;
@endphp
<div id="collapse-{{ $name }}" class="collapse" data-parent="#form-filters">
	<div class="form-filters card card-body bg-dark border-dark rounded-0">
		<label class="form-control-label" for="{{ $name }}">{{ Str::title($label) }}</label>
		<div class="d-flex flex-wrap">
			@foreach($collection as $text => $column)
				@php $active = ($column == 'ac_contents.display_start'); @endphp
				<div class="btn-group-toggle flex-shrink-0 m-1" data-toggle="direction-buttons">
					<label class="btn btn-toggle-direction text-nowrap{{ $active ? ' active reverse' : '' }}">
						<input type="checkbox" name="{{ $name }}" value="{{ $column }}"{{ $active ? ' checked' : '' }}>
						<input type="hidden" name="direction[{{ $column }}]" value="{{ $active ? 'desc' : 'asc' }}">
						<i class="fas {{ $active ? 'fa-arrow-up' : 'fa-minus' }} fa-fw mr-0" aria-hidden="true"></i>
						<span>{{ $text }}</span>
					</label>
				</div>
			@endforeach
		</div>
		<div class="form-buttons pt-3">
			<button class="btn btn-primary rounded px-3 py-2 mr-1" type="submit">Apply</button>
			<button class="btn btn-clear-sorting btn-outline-light rounded px-3 py-2" type="button">Clear</button>
		</div>
	</div>
</div>
