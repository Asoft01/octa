@extends('backend.layouts.app')

@section('title', 'Categories | ' . isset($category) ? "Edit" : "Create")

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
    {{ html()->form('POST', route('admin.library.categories.store'))->class('form-horizontal')->open() }}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            Categories Management
                            <small class="text-muted">{{ isset($category) ? "Edit" : "Create" }}</small>
                        </h4>
                    </div>

                    {{-- DELETE --}}
                    @if(isset($category))
                        <div class="col-sm-7" style="text-align: right;">
                                <a href="{{ route('admin.library.categories.delete', $category->id) }}" name="confirm_item" id="delete" class="btn btn-primary btn-danger">Delete</a>
                        </div>
                    @endif
                </div>

                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        
                        
                        {{-- TITLE --}}
                        <div class="form-group row">
                            {{ html()->label('Title *')->class('col-md-2 form-control-label')->for('title') }}
                            <div class="col-md-10">
                                {{ html()->text('title')
									->value(isset($category) ? $category->title : null)
									->attribute('maxlength', 191)
									->autofocus()
                                    ->class('form-control')
                                    ->placeholder('Title')
                                    ->required() }}
                            </div>
                        </div>
                        
                        {{-- SEQ --}}
                        <div class="form-group row">
                            {{ html()->label('Order *')->class('col-md-2 form-control-label')->for('seq') }}
                            <div class="col-md-10">
                                {{ html()->number('seq')
									->value(isset($category) ? $category->seq : null)
									->attributes(['min' => 0, 'step' => 1])
                                    ->class('form-control')
                                    ->placeholder('Order')
									->required() }}
                            </div>
                        </div>

                        {{-- DOMAINS --}}
                        <div class="form-group row">
                            {{ html()->label('Domains *')->class('col-md-2 form-control-label')->for('domain_ids') }}
                            <div class="col-md-10">
                                {{ html()->multiselect('domain_ids', $domains)
									->value($selected_domains)
									->attribute('data-placeholder', 'Domains')
									->class('form-control select2empty')
									->required() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.library.categories'), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col text-right">
                        <button type="submit" id="submitform" class="btn btn-primary">{{ isset($category) ? "Edit" : "Create" }}</button>
                    </div>
                </div>
            </div>
        </div>

     
        @if(isset($category))
            {{ html()->hidden('categoryID', $category->id) }}
        @endif

    {{ html()->form()->close() }}
@endsection

@push('after-styles')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css">
	<link rel="stylesheet" href="{{ url('/css/backoffice-forms.css') }}">
	<style>
		.main .select2-container--default {
			width: 100% !important;
		}
		.main .select2-container--default .select2-selection--multiple {
			padding-left: .4375rem; /* .75rem - 5px ~= .75rem - .3125rem = .4375rem */
			padding-left: calc(.75rem - 5px);
		}
	</style>
@endpush


@push('after-scripts')
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script>
		$(document).ready(function() {
			// SELECT2
			$('.select2').select2();
			$('.select2empty').select2({ allowClear: true, placeholder: '' });

			$('[required]').removeAttr('required');
			// VALIDATION
			$('#submitform').on('click', function(e) { return true; });
		});
	</script>
@endpush
