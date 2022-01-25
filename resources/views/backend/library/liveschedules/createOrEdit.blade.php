@extends('backend.layouts.app')

@section('title', 'Live Schedules | ' . isset($model) ? "Edit" : "Create")

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
    {{ html()->form('POST', route('admin.library.schedules.store'))->class('form-horizontal')->open() }}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            Live Schedules Management
                            <small class="text-muted">{{ isset($model) ? "Edit" : "Create" }}</small>
                        </h4>
                    </div>

                    {{-- DELETE --}}
                    @if(isset($model))
                        <div class="col-sm-7" style="text-align: right;">
							<a href="{{ route('admin.library.schedules.delete', $model->id) }}" name="confirm_item" id="delete" class="btn btn-primary btn-danger">Delete</a>
                        </div>
                    @endif
                </div>

                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">

						{{-- EDITING MODE --}}
						@isset($model)
							{{ html()->hidden('model_id')->value($model->id)->readonly() }}
						@endisset

						{{-- CONTRIBUTOR --}}
						<div class="form-group row">
                            {{ html()->label("Contributor")->class('col-md-2 form-control-label')->for('account_id') }}
                            <div class="col-md-10">
                                {{ html()->select('account_id', $contributors)
                                    ->value(isset($model) ? $model->account_id : null)
									->attribute('data-placeholder', 'Contributor')
									->class('form-control select2empty')
									->placeholder('Contributor') }}
                            </div>
                        </div>

                        {{-- TITLE --}}
                        <div class="form-group row">
                            {{ html()->label("Title *")->class('col-md-2 form-control-label')->for('title') }}
                            <div class="col-md-10">
                                {{ html()->text('title')
									->value(isset($model) ? $model->title : null)
                                    ->attribute('maxlength', 191)
									->autofocus()
									->class('form-control')
                                    ->placeholder("Title")
                                    ->required() }}
                            </div>
                        </div>

                        {{-- SLUG --}}
                        <div class="form-group row">
                            {{ html()->label("Slug *")->class('col-md-2 form-control-label')->for('slug') }}
                            <div class="col-md-10">
                                {{ html()->text('slug')
									->value(isset($model) ? $model->slug : null)
									->attribute('maxlength', 191)
                                    ->class('form-control')
									->placeholder('Slug')
									->required() }}
								<div id="slug-validation-feedback" class="invalid-feedback">@lang('validation.unique', ['attribute' => 'slug'])</div>
                            </div>
                        </div>

						{{-- EXCERPT --}}
                        {{--
                        <div class="form-group row">
                        {{ html()->label("Excerpt *")->class('col-md-2 form-control-label')->for('excerpt') }}
                            <div class="col-md-10">
                                {{ html()->textarea('excerpt')
                                    ->value(isset($model) ? $model->excerpt : null)
									->class('form-control')
									->placeholder('Excerpt')
									->required() }}
                            </div>
                        </div>
                        --}}

                        {{-- DESCRIPTION --}}
                        <div class="form-group row">
                        {{ html()->label("Description *")->class('col-md-2 form-control-label')->for('description') }}
                            <div class="col-md-10">
                                {{ html()->textarea('description')
                                    ->value(isset($model) ? $model->description : null)
									->class('form-control summernote') }}
                            </div>
                        </div>
						
						{{-- EVENTDATETIME --}}
                        <div class="form-group row">
                        {{ html()->label("Datetime *<small>(will be converted to utc)</small>")->class('col-md-2 form-control-label')->for('eventDatetime') }}
                            <div class="col-md-5">
								{{ html()->text('eventDatetime')
									->value(isset($model) ? \Carbon\Carbon::parse($model->eventDatetime, 'UTC')->setTimezone(optional(Auth::user())->timezone ?: 'UTC') : null)
									->style('background-color: white;')
									->attribute('autocomplete', 'off')
									->class('form-control datepicker')
									->placeholder('Datetime')
									->required() }}
                            </div>
							<div class="col-md-5">
								{{ html()->select('timezone', $timezones)
									->value(optional(Auth::user())->timezone ?: 'UTC')
                                    ->class('form-control select2')
                                    ->required() }}
                            </div>
                        </div>
						
						{{-- EVENTDURATION --}}
                        <div class="form-group row">
                        {{ html()->label("Duration *<small>(in minutes)</small>")->class('col-md-2 form-control-label')->for('eventDuration') }}
                            <div class="col-md-10">
								{{ html()->number('eventDuration')
									->value(isset($model) ? $model->eventDuration : null)
									->attribute('min', '0')
									->attribute('step', '30')
									->class('form-control')
									->placeholder('Duration')
									->required() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.library.schedules'), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col text-right">
                        <button type="submit" id="submitform" class="btn btn-primary">{{ isset($model) ? "Edit" : "Create" }}</button>
                    </div>
                </div>
            </div>
        </div>

    {{ html()->form()->close() }}
@endsection

@push('after-styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <!--{{ style(mix_cdn('css/dz.min.css')) }}-->
	{{ style(url('/css/backoffice-forms.css')) }}
@endpush

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <!--{!! script(mix_cdn('js/dz.min.js')) !!}-->

    <script>
        $(document).ready(function() {

            // generic dropdown
			$('.select2').select2();
			$('.select2empty').select2({ allowClear: true, placeholder: '' });

			// automatically slugify title
			$('#title, #slug').on('input', function(e) {
				var $id = $('#model_id');
				axios.post("{{ route('admin.library.schedules.slug') }}", {
					id: $id.length ? $id.first().val() : null,
					content: $(this).val(),
					generate: (this.id === 'title')
				})
				.then(function (response) {
					if (response.data.valid) {
						$('#slug').val(response.data.slug).removeClass('is-invalid');
					} else {
						$('#slug').val(response.data.slug).addClass('is-invalid');
					}
				})
				.catch(function (error) {
					console.log(error);
				});
			});

            $('.summernote').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ],
                callbacks: {
					onInit: function () {
						limit = _.toSafeInteger(this.getAttribute('data-maxlength'));
						if(limit > 0) {
							count = limit - $(this.value).text().length;
							$output = $(this).next('.note-editor').find('.note-status-output');
							$('<span class="note-output-maxlength pull-right px-1">' + count + '</span>').appendTo($output).toggleClass('text-danger', count < 0);
							$(this).on('summernote.change', function(we, contents, $editable) {
								limit = _.toSafeInteger(we.currentTarget.getAttribute('data-maxlength'));
								count = limit - $editable.text().length;
								$editable.parent().siblings('.note-status-output').find('.note-output-maxlength').text(count).toggleClass('text-danger', count < 0);
							});
						}
					},
                    onPaste: function (e) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

                        e.preventDefault();

                        // Firefox fix
                        setTimeout(function () {
                            document.execCommand('insertText', false, bufferText);
                        }, 10);
                    }
                }
            });

            $(".datepicker").flatpickr({
                allowInput: true, 
                enableTime: true,
                defaultHour: 4,
                defaultMinute: 0
            });

            $(".datepickerTime").flatpickr({
                allowInput: true, 
                enableTime: true,
                noCalendar: true,
                enableSeconds: true,
                time_24hr: true,
                minuteIncrement: 1,
                defaultHour: 0,
                defaultMinute: 0
            });

            $(".datepickerDate").flatpickr({
                allowInput: true, 
            });

            // VALIDATION
            $('#submitform').on('click', function(e) {
                if($('#description').summernote('isEmpty')) {
                    alert('Enter a description');
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
