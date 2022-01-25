{{--<!--
	expected $collection
	expected $name
	optional $label
-->--}}
@php
	isset($label) || $label = $name;
@endphp
<div id="collapse-{{ $name }}" class="collapse" data-parent="#form-filters">
	<div class="form-filters card card-body bg-dark border-dark rounded-0">
		<label class="form-control-label" for="{{ $name }}">{{ Str::title($label) }}</label>
		<div class="d-flex flex-wrap">
			@foreach($collection as $key => $value)
				<div class="btn-group-toggle flex-shink-0 m-1" data-toggle="buttons">
					<label class="btn btn-toggle text-nowrap w-100">
						<input type="checkbox" name="{{ $name }}[]" value="{{ $key }}">
						<span style="pointer-events: none;">{{ $value }}</span>
					</label>
				</div>
			@endforeach
		</div>
		<div class="form-buttons pt-3">
			<button class="btn btn-primary rounded px-3 py-2 mr-1" type="submit">Apply</button>
			<button class="btn btn-clear-type btn-outline-light rounded px-3 py-2" type="button">Clear</button>
		</div>
	</div>
</div>
