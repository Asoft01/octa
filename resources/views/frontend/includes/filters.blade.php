@php
	isset($heading)  || $heading  = 'Results';
	isset($action)   || $action   = route('frontend.filter');
	isset($filters)  || $filters  = [];
	isset($requires) || $requires = [];
@endphp
<div id="content-filters">
	<form id="form-filters" action="{{ $action }}" method="POST">
		@csrf
		@foreach($requires as $key => $values)
			@foreach(Arr::wrap($values) as $value)
				<input type="hidden" name="requires[{{ $key }}]" value="{{ $value }}">
			@endforeach
		@endforeach
		<div class="row justify-content-end">
			<div class="col-12 col-sm-auto">
				<div id="topbar-filters" class="d-flex flex-wrap justify-content-end bg-dark text-right">
					<div id="topbar-filters-buttons" class="d-flex flex-wrap flex-grow-1 justify-content-end text-right">
						@foreach($filters as $label => $collection)
							@unless(count($collection) <= 1)
								{{--<!-- The select2 plugin doesn't like the name "tags". -->--}}
								@php $name = ($label == 'tags') ? 'tagids' : $label; @endphp
								<button style="border-right: 1px dashed #414141 !important;" class="btn btn-lg bg-transparent flex-fill border-0 rounded-0 px-2 px-sm-4" type="button" data-toggle="collapse" data-target="#collapse-{{ $name }}" aria-controls="collapse-{{ $name }}" aria-expanded="false">
									<span class="text-uppercase">{{ Str::title($label) }}</span>
								</button>
							@endunless
						@endforeach
						@isset($sorting)
							<button class="btn btn-lg bg-transparent flex-fill border-0 rounded-0 px-2 px-sm-4" type="button" data-toggle="collapse" data-target="#collapse-order" aria-controls="collapse-order" aria-expanded="false">
								<span class="text-uppercase">Order</span>
							</button>
						@endisset
					</div>
					@if($search)
						<div id="topbar-filters-search" class="position-relative flex-grow-1">
							<input class="form-control rounded-0 bg-dark" type="text" name="search" placeholder="Search..." value="{{ Request::query('q', '') }}">
							<button class="btn bg-tansparent border-0 rounded-0" type="submit">
								<i class="fas fa-search mr-0" aria-hidden="true"></i>
							</button>
						</div>
					@endif
				</div>
			</div>
		</div>
		<div class="row justify-content-end">
			<div class="col-12 col-sm-11 col-md-9 col-lg-7 col-xl-6">
				<div class="position-relative main">
					<div class="form-filters-collapsing position-absolute w-100" style="z-index: 1000;">
						@foreach($filters as $label => $collection)
							@unless(count($collection) <= 1)
								@php
									$name = ($label == 'tags') ? 'tagids' : $label;
									$view = ($label == 'types') ? 'filter-type' : 'filter-select2'; 
								@endphp
								@render("frontend.includes.{$view}", compact('name', 'label', 'recommend', 'collection'))
							@endunless
						@endforeach
						@isset($sorting)
							@render('frontend.includes.filter-sorting', ['name' => 'order', 'collection' => $sorting])
						@endisset
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div id="results-header" class="row pt-3">
	<div class="col">
		<h2 class="post-list-heading">{{ $heading }}</h2>
	</div>
	<div id="results-info" class="col col-auto text-right pt-1" style="display: none;">
		<span id="results-total">0</span>
		<span>items</span>
	</div>
</div>
<div class="infinite-scrolling row"></div>
<div class="infinite-loading"></div>
<div class="clearfix"></div>

@push('after-scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script src="{{ url('js/jquery-infinite.js') }}"></script>
	<script src="{{ url('js/filters.js') }}"></script>
@endpush
