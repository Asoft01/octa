@extends('backend.layouts.app')

@section('title', 'Experts | ' . isset($account) ? "Edit" : "Create")

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')

    {{ html()->form('POST', route('admin.library.experts.store'))->id('experts-bo-form')->class('form-horizontal')->open() }}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            Experts Management
                            <small class="text-muted">{{ isset($account) ? "Edit" : "Create" }}</small>
                        </h4>
                    </div>

                    {{-- DELETE --}}
                    @if(isset($account))
                        <div class="col-sm-7" style="text-align: right;">
                                <a href="{{ route('admin.library.experts.delete', $account->id) }}" name="confirm_item" id="delete" class="btn btn-primary btn-danger">Delete</a>
                        </div>
                    @endif
                </div>

                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        
                        
                        {{-- FIRSTNAME --}}
                        <div class="form-group row">
                            {{ html()->label("First Name *")->class('col-md-2 form-control-label')->for('firstname') }}
                            <div class="col-md-10">
                                {{ html()->text('firstname')
                                    ->class('form-control')
                                    ->placeholder("First Name")
                                    ->value(isset($user) ?  $user->first_name : null)
                                    ->attribute('maxlength', 191)
                                    ->required()
                                    ->autofocus() }}
                            </div>
                        </div>
                       
                        {{-- LASTNAME --}}
                        <div class="form-group row">
                            {{ html()->label("Last Name *<small>(use _ if using only first name)</small>")->class('col-md-2 form-control-label')->for('lastname') }}
                            <div class="col-md-10">
                                {{ html()->text('lastname')
                                    ->class('form-control')
                                    ->placeholder("Last Name")
                                    ->value(isset($user) ?  $user->last_name : null)
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div>
                        </div>

                        {{-- SLUG --}}
                        <div class="form-group row">
                            {{ html()->label("Slug *")->class('col-md-2 form-control-label')->for('slug') }}
                            <div class="col-md-10">
                                {{ html()->text('slug')
									->value(isset($account) ? $account->slug : null)
									->attribute('maxlength', 191)
                                    ->class('form-control')
									->placeholder('Slug')
									->required() }}
								<div id="slug-validation-feedback" class="invalid-feedback">@lang('validation.unique', ['attribute' => 'slug'])</div>
                            </div>
                        </div>

                        {{-- EMAIL --}}
                        <div class="form-group row">
                            {{ html()->label("Email * <small>(unique in the system)</small>")->class('col-md-2 form-control-label')->for('email') }}
                            <div class="col-md-10">
                                {{ html()->email('email')
                                    ->class('form-control')
                                    ->placeholder("Email")
                                    ->value(isset($user) ?  $user->email : null)
                                    ->attribute('maxlength', 191)
                                    ->required()
                                     }}
                            </div>
                        </div>

						{{-- POSITION --}}
                        <div class="form-group row">
                            {{ html()->label("Position")->class('col-md-2 form-control-label')->for('position') }}
                            <div class="col-md-10">
                                {{ html()->text('position')
                                    ->class('form-control')
                                    ->placeholder("Position")
                                    ->value(isset($account) ? $account->position : null)
                                    ->attribute('maxlength', 191) }}
                            </div>
                        </div>

						{{-- CV --}}
                        <div class="form-group row">
                        {{ html()->label("CV")->class('col-md-2 form-control-label')->for('cv') }}
                            <div class="col-md-10">
                                {{ html()->textarea('cv')
                                    ->value(isset($account) ? $account->cv : null)
									->class('form-control summernote') }}
                            </div>
                        </div>

						{{-- Bio --}}
                        <div class="form-group row">
                        {{ html()->label("Bio")->class('col-md-2 form-control-label')->for('bio') }}
                            <div class="col-md-10">
                                {{ html()->textarea('bio')
                                    ->value(isset($account) ? $account->bio : null)
									->class('form-control summernote') }}
                            </div>
                        </div>

                        {{-- THUMB --}}
                        <div class="form-group row">
                            {{ html()->label("Photo * <small>(700x480)</small>")->class('col-md-2 form-control-label') }}
                            <div class="col-md-10">
                                @if(isset($account))
                                    <img src="{{ asset_cdn($account->photo) }}" style="max-width: 200px;padding-bottom: 16px;" />
                                @endif
                                <div class="dropzone dzimage" id="photo"></div>
                            </div>
                        </div>
                        
                        {{-- ICON --}}
                        <div class="form-group row">
                            {{ html()->label("Icon <small>(256x256)</small>")->class('col-md-2 form-control-label') }}
                            <div class="col-md-10">
                                @if(isset($account))
                                    <img src="{{ asset_cdn($account->icon) }}" style="max-width: 256;padding-bottom: 16px;" />
                                @endif
                                <div class="dropzone dzimage" id="icon"></div>
                            </div>
                        </div>

						{{-- POSTER --}}
                        <div class="form-group row">
                            {{ html()->label("Video Poster <small>(1280x720)</small>")->class('col-md-2 form-control-label') }}
                            <div class="col-md-10">
                                @if(isset($account))
                                    <img src="{{ asset_cdn($account->poster) }}" style="max-width: 200px;padding-bottom: 16px;" />
                                @endif
                                <div class="dropzone dzimage" id="poster"></div>
                            </div>
                        </div>

						{{-- VIDEO --}}
                        <div class="form-group row">
                            {{ html()->label("Video")->class('col-md-2 form-control-label') }}
                            <div class="col-md-10">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
											<tr>
												<th>Full length (.mp4 || .mp3)</th>
												<th>Preview (+-10 sec)</th>
											</tr>
                                        </thead>
                                        <tbody>
											<tr>
												<td>
													<div class="form-group row">
														<div class="col-md-12">
															<div class="form-group row">
																<div class="col-md-12">
																	@if(isset($account))
																		<video src="{{ asset_cdn($account->video) }}" style="max-width: 200px; padding-bottom: 16px;" controls /></video>
																	@endif
																	<div class="dropzone dzvideo" id="videoFull"></div>
																</div>
															</div>
														</div>
													</div>
												</td>
												<td>
													<div class="form-group row">
														<div class="col-md-12">
															<div class="form-group row">
																<div class="col-md-12">
																	@if(isset($account))
																		<video src="{{ asset_cdn($account->preview_video) }}" style="max-width: 200px; padding-bottom: 16px;" controls /></video>
																	@endif
																	<div class="dropzone dzvideo" id="videoPreview"></div>
																</div>
															</div>
														</div>
													</div>
												</td>
											</tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
					
						<hr class="border-secondary mb-4">
					
						{{-- ASANAGID --}}
                        <div class="form-group row">
                            {{ html()->label("Asana GID *")->class('col-md-2 form-control-label')->for('asanaGID') }}
                            <div class="col-md-10">
                                {{ html()->text('asanaGID')
                                    ->class('form-control')
									->attribute('maxlength', 191)
                                    ->placeholder("Asana GID")
                                    ->value(isset($account) ? $account->asanaGID : null)
                                    ->required() }}
                            </div>
                        </div>

						{{-- NEXTCLOUDUSERNAME --}}
                        <div class="form-group row">
                            {{ html()->label("Nextcloud Username *")->class('col-md-2 form-control-label')->for('nextcloudUsername') }}
                            <div class="col-md-10">
                                {{ html()->text('nextcloudUsername')
                                    ->class('form-control')
									->attribute('maxlength', 191)
                                    ->placeholder("Nextcloud Username")
                                    ->value(isset($account) ? $account->nextcloudUsername : null)
									->required() }}
                            </div>
                        </div>

                        <hr class="border-secondary mb-4">

						{{-- HOURSWEEK --}}
                        <div class="form-group row">
                            {{ html()->label("Hours * <small>(per week)</small>")->class('col-md-2 form-control-label')->for('hoursWeek') }}
                            <div class="col-md-10">
                                {{ html()->number('hoursWeek')
                                    ->class('form-control')
									->attributes(['min' => '0', 'step' => '1'])
                                    ->placeholder("Hours")
                                    ->value(isset($account) ? $account->hoursWeek : null)
                                    ->required() }}
                            </div>
                        </div>

						{{-- DELAY --}}
                        <div class="form-group row">
                            {{ html()->label("Delay <small>(days)</small>")->class('col-md-2 form-control-label')->for('delay') }}
                            <div class="col-md-10">
                                {{ html()->number('delay')
                                    ->class('form-control')
									->attributes(['min' => '0', 'step' => '1'])
                                    ->placeholder("Delay")
                                    ->value(isset($account) ?  $account->delay : null) }}
                            </div>
                        </div>

						{{-- BOOKEDUNTIL --}}
                        <div class="form-group row">
							{{ html()->label("Unavailable Until <small>(will be converted to utc)</small>")->class('col-md-2 form-control-label')->for('bookeduntil') }}
                            <div class="col-md-5">
								@php 
									if (isset($account)) {
										$bookeduntil = $account->bookeduntil ? $account->bookeduntil->tz($tz)->toDateString() : null;
									} else {
										$bookeduntil = null;
									}
								@endphp
								{{ html()->text('bookeduntil')
									->value($bookeduntil)
									->style('background-color: white;')
									->attribute('autocomplete', 'off')
									->class('form-control datepickerDate')
									->placeholder('Unavailable Until') }}
                            </div>
							<div class="col-md-5">
								{{ html()->select('timezone', $timezones)
									->value($tz)
                                    ->class('form-control select2')
                                    ->required() }}
                            </div>
                        </div>	
                        
                        <hr class="border-secondary mb-4">

                        {{-- PRICING --}}
                        <div class="form-group row">
                            {{ html()->label("Product Prices <small>(USD)</small>")->class('col-md-2 form-control-label') }}
                            <div class="col-md-10">
                                <div class="form-group row">
                                    @foreach($products as $id => $product)
                                        <div class="col-12 col-md-6 mb-2">
                                            {{ html()->label(optional($product->models->first())->title ?: $product->title)
                                                ->for("products-{$product->internal_id}")
                                                ->class('form-control-label') }}
                                            {{ html()->number("products[{$product->internal_id}]")
                                                ->id("products-{$product->internal_id}")
                                                ->class('form-control')
                                                ->attributes(['step' => '0.01', 'min' => '0'])
                                                ->placeholder($product->price)
                                                ->value(optional($product->models->first())->price ?: null) }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.library.contributors'), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col text-right">
                        <button type="submit" id="submitform" class="btn btn-primary">{{ isset($account) ? "Edit" : "Create" }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{ html()->hidden('photoFile', isset($account) ? basename($account->photo) : null) }}
        {{ html()->hidden('photoMimetype', '') }}
        {{ html()->hidden('photoSize', '') }}

        {{ html()->hidden('iconFile', isset($account) ? basename($account->icon) : null) }}
        {{ html()->hidden('iconMimetype', '') }}
        {{ html()->hidden('iconSize', '') }}
		
		{{ html()->hidden('posterFile', isset($account) ? basename($account->poster) : null) }}
        {{ html()->hidden('posterMimetype', '') }}
        {{ html()->hidden('posterSize', '') }}
		
		{{ html()->hidden('videoFullFile', isset($account) ? basename($account->video) : null) }}
        {{ html()->hidden('videoFullMimetype', '') }}
        {{ html()->hidden('videoFullSize', '') }}
        {{ html()->hidden('videoFullFPS', '') }}

        {{ html()->hidden('videoPreviewFile', isset($account) ? basename($account->preview_video) : null) }}
        {{ html()->hidden('videoPreviewMimetype', '') }}
        {{ html()->hidden('videoPreviewSize', '') }}
        {{ html()->hidden('videoPreviewFPS', '') }}

        @isset($account)
            {{ html()->hidden('account_id', $account->id) }}
            <!--{{ html()->hidden('user_id', $user->id) }}-->
        @endisset

    {{ html()->form()->close() }}

@endsection

@push('after-styles')
	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	{{ style(mix_cdn('css/dz.min.css')) }}
	{{ style(url('/css/backoffice-forms.css')) }}
@endpush

@push('after-scripts')
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	{!! script(mix_cdn('js/dz.min.js')) !!}

	<script>
		Dropzone.autoDiscover = false;

		// DZ for IMAGES
		$(".dzimage").dropzone({
			acceptedFiles: "image/*",
			maxFilesize: 4096,
			createImageThumbnails: true,
			maxFiles:1,
			autoProcessQueue: true,
			parallelUploads: 10,
			url: '<?php echo route('frontend.upload.file'); ?>',
			headers: {
				'x-csrf-token': '{{ csrf_token() }}',
				'uploadpath': 'photos'
			},
			renameFile: function (file) {
				var dt = new Date();
				var time = dt.getTime();
				var fe = file.name.split('.').pop();
				var safeString = file.name.replace(/\.[^/.]+$/, "");
				safeString = safeString.replace(/[^0-9a-zA-Z-]/g,  '').toLowerCase();
				return safeString+'_'+time+'.'+fe;
			},
			addRemoveLinks: true,
			timeout: 0,
			init: function() {
				this.on('addedfile', function(file) {
					if (this.files.length > 1) {
						this.removeFile(this.files[0]);
					}
				});
				this.on('removedfile', function(file) {
					$("#"+$(this.element).attr('id')+"File").val("");
					$("#"+$(this.element).attr('id')+"Mimetype").val("");
					$("#"+$(this.element).attr('id')+"Size").val("");
				});	
			},
			success: function (file, response) {
				if(response.success) {
					$("#"+$(this.element).attr('id')+"File").val(response.success[0].filename);
					$("#"+$(this.element).attr('id')+"Mimetype").val(response.success[0].mimeType);
					$("#"+$(this.element).attr('id')+"Size").val(response.success[0].size);
				}
			},
			error: function (file, response) {
				return false;
			}
		});

		// DZ for VIDEO
		$(".dzvideo").dropzone({
			acceptedFiles: "video/*,audio/mp3",
			maxFilesize: 4096,
			createImageThumbnails: false,
			maxFiles:1,
			autoProcessQueue: true,
			parallelUploads: 10,
			url: '<?php echo route('frontend.upload.file'); ?>',
			headers: {
				'x-csrf-token': '{{ csrf_token() }}',
				'uploadpath': 'videos',
				'uploadtype': 'video'
			},
			renameFile: function (file) {
				var dt = new Date();
				var time = dt.getTime();
				var fe = file.name.split('.').pop();
				var safeString = file.name.replace(/\.[^/.]+$/, "");
				safeString = safeString.replace(/[^0-9a-zA-Z-]/g,  '').toLowerCase();
				return safeString+'_'+time+'.'+fe;
			},
			addRemoveLinks: true,
			timeout: 0,
			init: function() {
				this.on('addedfile', function(file) {
					if (this.files.length > 1) {
						this.removeFile(this.files[0]);
					}
				});
				this.on('removedfile', function(file) {
					$("#"+$(this.element).attr('id')+"File").val("");
					$("#"+$(this.element).attr('id')+"Mimetype").val("");
					$("#"+$(this.element).attr('id')+"Size").val("");
					$("#"+$(this.element).attr('id')+"FPS").val("");
				});	
			},
			success: function (file, response) {
				if(response.success) {
					$("#"+$(this.element).attr('id')+"File").val(response.success[0].filename);
					$("#"+$(this.element).attr('id')+"Mimetype").val(response.success[0].mimeType);
					$("#"+$(this.element).attr('id')+"Size").val(response.success[0].size);
					$("#"+$(this.element).attr('id')+"FPS").val(response.success[0].fps);
				}
			},
			error: function (file, response) {
				return false;
			}
		});
	</script>
	<script>
		$(document).ready(function() {
			
			$('.select2').select2();
			$('.select2empty').select2({ allowClear: true, placeholder: '' });

			// automatically slugify firstname + lastname
			$('#firstname, #lastname, #slug').on('input', function() {
				var $id = $('#account_id');
				axios.post("{{ route('admin.library.experts.slug') }}", {
					model_id: $id.length ? $id.first().val() : null,
					first_name: $('#firstname').val(),
					last_name: $('#lastname').val(),
					slug: $('#slug').val(),
					generate: (this.id !== 'slug')
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
                    ['view', ['fullscreen', 'codeview', 'help']]
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
                allowInput: true
            });

			// VALIDATION
			$('#experts-bo-form').on('submit', function(e) {
				var unfinished = 0;
				$(".dzimage, .dzvideo").each(function(index, element) {
					unfinished += element.dropzone.getUploadingFiles().length;
				});
				if ($('input[name="photoFile"]').val().length === 0) {
					unfinished++;
				}
				if (unfinished > 0) {
					alert("Upload the required files or wait for upload(s) to finish.");
					e.preventDefault();
				}
			});

		});
	</script>

@endpush
