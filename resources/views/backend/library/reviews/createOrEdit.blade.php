@extends('backend.layouts.app')

@section('title', 'Reviews | ' . isset($review) ? "Edit" : "Create")

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
    {{ html()->form('POST', route('admin.library.reviews.store'))->class('form-horizontal')->open() }}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            Reviews Management
                            <small class="text-muted">{{ isset($review) ? "Edit" : "Create" }}</small>
                        </h4>
                    </div>

                    {{-- DELETE --}}
                    @if(isset($review))
                        <div class="col-sm-7" style="text-align: right;">
                                <a href="{{ route('admin.library.reviews.delete', $review->id) }}" name="confirm_item" id="delete" class="btn btn-primary btn-danger">Delete</a>
                        </div>
                    @endif
                </div>

                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        
                    
                        {{-- DOMAIN --}}
                        <div class="form-group row">
                            {{ html()->label("Domain *")->class('col-md-2 form-control-label')->for('domain_id') }}
                            <div class="col-md-10">
                                {{ html()->select('domain_id', $domain)
                                    ->class('form-control select2')
                                    ->value(isset($content) ?  $content->domain_id : null)
                                    ->required() }}
                            </div>
                        </div>

                        @if(!isset($review))
                            {{-- ORDER ID --}}
                            <div class="form-group row">
                                {{ html()->label("Order ID")->class('col-md-2 form-control-label')->for('delivery_id') }}
                                <div class="col-md-10">
                                    {{ html()->select('delivery_id', $order)
                                    ->class('form-control select2')
                                    ->placeholder("Is this reviewer coming from an order? If yes enter order ID if not leave empty.")
                                    ->value(isset($content) ?  $content->delivery_id : null)
                                    }}
                                </div>
                            </div>
                        @elseif(isset($review) && !empty($content->delivery_id))
                            {{-- ORDER ID --}}
                            <div class="form-group row">
                                {{ html()->label("Order ID")->class('col-md-2 form-control-label')->for('delivery_id') }}
                                <div class="col-md-10">
                                    {{ html()->input('text', 'delivery_id')
                                    ->class('form-control')
                                    ->readonly()
                                    ->value(isset($content) ?  $content->delivery->order->uuid : null)
                                    }}
                                </div>
                            </div>
                        @endif

                        <div id="notFromOrderData">
                            {{-- REVIEWER --}}
                            <div class="form-group row">
                                {{ html()->label("Reviewer")->class('col-md-2 form-control-label')->for('mentor_id') }}
                                <div class="col-md-8">
                                    {{ html()->select('mentor_id', $reviewers)
                                        ->class('form-control select2empty')
                                        ->value(isset($review) ?  $review->mentor_id : null)
									}}
                                </div>
                                <div class="col-md-2">
                                    <a href="#">Add a reviewer</a>
                                </div>
                            </div>

                            {{-- IS LIVE --}}
                            <div class="form-group row">
                                {{ html()->label("Is it a LIVE review?")->class('col-md-2 form-control-label')->for('isLive') }}
                                <div class="col-md-10">
                                    {{ html()->checkbox('isLive')
                                    ->class('form-control')
                                    ->checked(isset($review->isLive) && $review->isLive == true ? 1 : 0)
                                    ->style('text-align: left; width: 35px;')
                                    ->value("true")
                                    }}
                                </div>
                            </div>

                        </div>

                        {{-- TAGS --}}
                        <div class="form-group row">
                            {{ html()->label("Tags")->class('col-md-2 form-control-label')->for('tags') }}
                            <div class="col-md-10">
                                {{ html()->multiselect('tags', $tag)
                                    ->class('form-control select2tag')
                                }}
                            </div>
                        </div>
                        
                        {{-- TITLE --}}
                        <div class="form-group row">
                            {{ html()->label("Title *")->class('col-md-2 form-control-label')->for('title') }}
                            <div class="col-md-10">
                                {{ html()->text('title')
                                    ->class('form-control')
                                    ->placeholder("Title")
                                    ->value(isset($content) ?  $content->title : null)
                                    ->attribute('maxlength', 191)
                                    ->required()
                                    ->autofocus() }}
                            </div>
                        </div>

                        {{-- SLUG --}}
                        <div class="form-group row">
                            {{ html()->label("Slug <i style='font-size: 12px;'>(automatically created if empty)</i>")->class('col-md-2 form-control-label')->for('slug') }}
                            <div class="col-md-10">
                                {{ html()->text('slug')
                                    ->class('form-control')
                                    ->value(isset($content) ?  $content->slug : null)
                                    ->attribute('maxlength', 191) }}
								<div id="slug-validation-feedback" class="invalid-feedback">This slug already exists.</div>
                            </div>
                        </div>
                        
                        {{-- TITLE COTD --}}
                        <div class="form-group row">
                        {{ html()->label("Title as content of the day")->class('col-md-2 form-control-label')->for('title_cotd') }}
                            <div class="col-md-10">
                                {{ html()->text('title_cotd')
                                    ->class('form-control')
                                    ->value(isset($content) ?  $content->title_cotd : null)
                                    ->placeholder("Title as content of the day")
                                    ->attribute('maxlength', 191) }}
                            </div>
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="form-group row">
                        {{ html()->label("Description *")->class('col-md-2 form-control-label')->for('description') }}
                            <div class="col-md-10">
                                {{ html()->textarea('description')
                                    ->class('form-control')
                                    ->value(isset($content) ?  $content->description : null)
                                    ->class('summernote') }}
                            </div>
                        </div>
                        {{-- DESCRIPTION COTD --}}
                        <div class="form-group row">
                        {{ html()->label("Description as content of the day")->class('col-md-2 form-control-label')->for('description_cotd') }}
                            <div class="col-md-10">
                                {{ html()->textarea('description_cotd')
                                    ->class('form-control')
                                    ->value(isset($content) ?  $content->description_cotd : null)
                                    ->class('summernote')
									->attribute('data-maxlength', 400) }}
                            </div>
                        </div>

                        {{-- DATE --}}
                        <div class="form-group row">
                            {{ html()->label("Display dates")->class('col-md-2 form-control-label') }}

                            <div class="col-md-10">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>In the library</th>
                                            <th>As content of the day</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                {{-- DISPLAY --}}
                                                <div class="form-group row">
                                                    {{ html()->label("Start date")->class('col-md-2 form-control-label')->for('display_start') }}
                                                    <div class="col-md-10">
                                                        {{ html()->text('display_start')
                                                            ->class('form-control')
                                                            ->style('background-color: white')
                                                            ->attribute('autocomplete', 'off')
                                                            ->value(isset($content) ?  $content->display_start : null)
                                                            ->class('datepicker') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    {{ html()->label("End date")->class('col-md-2 form-control-label')->for('display_end') }}
                                                    <div class="col-md-10">
                                                        {{ html()->text('display_end')
                                                            ->class('form-control')
                                                            ->style('background-color: white')
                                                            ->attribute('autocomplete', 'off')
                                                            ->value(isset($content) ? $content->display_end : null)
                                                            ->class('datepicker') }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{-- DISPLAY COTD--}}
                                                <div class="form-group row">
                                                    {{ html()->label("COTD date")->class('col-md-2 form-control-label')->for('cotd_start') }}
                                                    <div class="col-md-10">
                                                        {{ html()->text('cotd_start')
                                                            ->class('form-control')
                                                            ->style('background-color: white')
                                                            ->attribute('autocomplete', 'off')
                                                            ->value(isset($content) ?  $content->cotd_start : null)
                                                            ->class('datepicker') }}
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- THUMB --}}
                        <div class="form-group row">
                            {{ html()->label("Thumbnail (1280x720)<br /><span style='font-size: 9px;'>Automatically generated from poster if empty</span>")->class('col-md-2 form-control-label') }}
                            <div class="col-md-10">
                                @if(isset($review))
                                    <img src="{{ asset_cdn($review->thumb) }}" style="max-width: 200px;padding-bottom: 16px;" />
                                @endif
                                <div class="dropzone dzimage" id="thumb"></div>
                            </div>
                        </div>
                        
                        {{-- POSTER --}}
                        <div class="form-group row">
                            {{ html()->label("Poster (1280x720)")->class('col-md-2 form-control-label') }}
                            <div class="col-md-10">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>In the library *</th>
                                            <th>As content of the day</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-12">
                                                                @if(isset($review))
                                                                    <img src="{{ asset_cdn($review->poster) }}" style="max-width: 200px;padding-bottom: 16px;" />
                                                                @endif
                                                                <div class="dropzone dzimage" id="poster"></div>
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
                                                                @if(isset($review))
                                                                    @if($review->poster_cotd)
                                                                        <img src="{{ asset_cdn($review->poster_cotd) }}" style="max-width: 200px;padding-bottom: 16px;" />
                                                                    @else
                                                                        <div style="background-color: #f0f0f0; min-height: 112px; max-width: 200px; margin-bottom: 16px;"></div>
                                                                    @endif
                                                                @endif
                                                                <div class="dropzone dzimage" id="posterCOTD"></div>
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


                        {{-- VIDEO --}}
                        <div class="form-group row">
                            {{ html()->label("Video *")->class('col-md-2 form-control-label') }}
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
                                                                @if(isset($review))
                                                                    <video src="{{ asset_cdn($review->video) }}" style="max-width: 200px; padding-bottom: 16px;" controls /></video>
                                                                @endif
                                                                <div class="dropzone dzvideo" id="videoFull"></div>
                                                                </div>
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
                                                                @if(isset($review))
                                                                    <video src="{{ asset_cdn($review->preview_video) }}" style="max-width: 200px; padding-bottom: 16px;" controls /></video>
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

                         {{-- SYNCSKETCH --}}
                         <div class="form-group row">
                            {{ html()->label("SyncSketch URL<br /><i>Add /?offlineMode=1</i>")->class('col-md-2 form-control-label')->for('syncsketch') }}
                            <div class="col-md-10">
                                {{ html()->text('syncsketch')
                                    ->class('form-control')
                                    ->value(isset($review) ? $review->syncsketch : null) }}
                            </div>
                        </div>

                        {{-- LENGTH --}}
                        <div class="form-group row">
                            {{ html()->label("Length")->class('col-md-2 form-control-label')->for('length') }}
                            <div class="col-md-10">
                                {{ html()->time('length')
                                    ->class('form-control')
                                    ->class('datepickerTime')
                                    ->attribute('autocomplete', 'off')
                                    ->style('background-color: white')
                                    ->value(isset($review) ? $review->length : null) }}
                            </div>
                        </div>

                        {{-- CREATION DATE --}}
                        <div class="form-group row">
                            {{ html()->label("Creation date of content")->class('col-md-2 form-control-label')->for('releaseDate') }}
                            <div class="col-md-10">
                                {{ html()->text('releaseDate')
                                    ->class('form-control')
                                    ->style('background-color: white')
                                    ->attribute('autocomplete', 'off')
                                    ->value(isset($review) ? $review->releaseDate : null)
                                    ->class('datepickerDate') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.library.reviews'), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col text-right">
                        <button type="submit" id="submitform" class="btn btn-primary">{{ isset($review) ? "Edit" : "Create" }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{ html()->hidden('thumbFile', isset($review) ? basename($review->thumb) : null) }}
        {{ html()->hidden('thumbMimetype', '') }}
        {{ html()->hidden('thumbSize', '') }}

        {{ html()->hidden('posterFile', isset($review) ? basename($review->poster) : null) }}
        {{ html()->hidden('posterMimetype', '') }}
        {{ html()->hidden('posterSize', '') }}

        {{ html()->hidden('posterCOTDFile', isset($review) ? basename($review->poster_cotd) : null) }}
        {{ html()->hidden('posterCOTDMimetype', '') }}
        {{ html()->hidden('posterCOTDSize', '') }}

        {{ html()->hidden('videoFullFile', isset($review) ? basename($review->video) : null) }}
        {{ html()->hidden('videoFullMimetype', '') }}
        {{ html()->hidden('videoFullSize', '') }}
        {{ html()->hidden('videoFullFPS', '') }}

        {{ html()->hidden('videoPreviewFile', isset($review) ? basename($review->preview_video) : null) }}
        {{ html()->hidden('videoPreviewMimetype', '') }}
        {{ html()->hidden('videoPreviewSize', '') }}
        {{ html()->hidden('videoPreviewFPS', '') }}

        {{ html()->hidden('pathID', $nextId) }}

        {{-- EDITING MODE --}}
        @if(isset($review))
            {{ html()->hidden('reviewID', $review->id) }}
            {{ html()->hidden('contentID', $content->id) }}
        @endif

    {{ html()->form()->close() }}
@endsection

@push('after-styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    {{ style(mix_cdn('css/dz.min.css')) }}
    <style>
    .note-editable * {
        line-height: inherit!important;
        font-size: 1em !important;
    }
	select[required].select2-hidden-accessible {
		margin-top: 28px;
		margin-left: 9em;
	}
    </style>
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
                'uploadpath': 'reviews/{{ $nextId }}'
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
            maxFilesize: 16384,
            createImageThumbnails: false,
            maxFiles:1,
            autoProcessQueue: true,
            parallelUploads: 10,
            url: '<?php echo route('frontend.upload.file'); ?>',
            headers: {
                'x-csrf-token': '{{ csrf_token() }}',
                'uploadpath': 'reviews/{{ $nextId }}',
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


        $(document).ready(function() {

            // if delivery_id is provided hide fields
            $("#delivery_id").on("input", function(){    
                if($(this).val()) {
                    $("#notFromOrderData").hide();
                } else {
                    $("#notFromOrderData").show();
                }
            });

            @if(isset($review) && !empty($content->delivery->order->uuid))
                $("#notFromOrderData").hide();
            @endif

            // generic dropdown
            $('.select2').select2();
            $('.select2empty').select2({
                placeholder: "",
                allowClear: true
            });

            // tags dropdown
            var tags = $('#tags').select2({
                tags: true
            });
            @if(isset($content))
                var tagsID = @json($content->tags->pluck('id')->toArray());
                tags.val(tagsID).trigger("change");
            @endif

			// automatically slugify title
			$('#title, #slug').on('input', function(e) {
				axios.post('slug', {
					content: $(this).val(),
					generate: (this.id === 'title')
				})
				.then(function (response) {
					$('#slug').val(response.data.slug).toggleClass('is-invalid', response.data.exists);
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
                if($('#delivery_id').val() == "") {
                    var r = confirm("No ORDER ID specified! Are you sure?");
                    if (r != true) {
                        e.preventDefault();
                    }
                }
                if($("#posterFile").val() == "" || $("#videoFullFile").val() == "" || $("#videoPreviewFile").val() == "") {
                    alert("Upload the required files or wait for upload to finish");
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
