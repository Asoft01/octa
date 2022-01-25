{{--<!--
	expected $name
	expected $collection
	optional $label
-->--}}
@php
	isset($label) || $label = $name;
	$original_name = ($name == 'tagids') ? 'tags' : $name;
@endphp
<div id="collapse-{{ $name }}" class="collapse" data-parent="#form-filters">
	<div class="form-filters form-filters-select2 card card-body rounded-0">
		<label class="form-control-label" for="{{ $name }}">{{ Str::title($label) }}</label>
		{{ html()->multiselect($name, $collection)->class('form-control select2filter')->attribute('aria-describedby', "{$name}-help") }}
		<small id="{{ $name }}-help" class="form-text text-muted">
			<span>Press enter to include {{ $label }}.</span>
			<span class="d-none d-sm-inline">Use up and down arrow keys to select {{ $label }}.</span>
		</small>
		<div id="{{ $name }}-results" class="select2-dropdown-parent"></div>
		@if(isset($recommend[$original_name]))
			<div class="select2-popular-options">
				<p class="mb-1">Popular {{ $label }}:</p>
				<div class="d-flex flex-wrap">
					@foreach($recommend[$original_name]->take(10) as $key)
						<button class="btn btn-toggle text-nowrap flex-shrink-0 m-1" type="button" data-select="{{ $key }}" data-target="#{{ $name }}" data-toggle="select">
							<span>{{ $collection[$key] }}</span>
						</button>
					@endforeach
				</div>
			</div>
		@endif
		<div class="form-buttons pt-3">
			<button class="btn btn-primary rounded px-3 py-2 mr-1" type="submit">Apply</button>
			<button class="btn btn-clear-select2 btn-outline-light rounded px-3 py-2" type="button">Clear</button>
		</div>
	</div>
</div>
