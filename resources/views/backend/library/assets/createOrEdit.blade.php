@extends('backend.layouts.app')

@section('title', 'Assets | ' . isset($asset) ? "Edit" : "Create")

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
    {{ html()->form('POST', route('admin.library.assets.store'))->class('form-horizontal')->open() }}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            Assets Management
                            <small class="text-muted">{{ isset($asset) ? "Edit" : "Create" }}</small>
                        </h4>
                    </div>

                    {{-- DELETE --}}
                    @if(isset($asset))
                        <div class="col-sm-7" style="text-align: right;">
                                <a href="{{ route('admin.library.assets.delete', $asset->id) }}" name="confirm_item" id="delete" class="btn btn-primary btn-danger">Delete</a>
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
                                    ->class('form-control')
                                    ->value(isset($content) ?  $content->domain_id : null)
                                    ->required() }}
                            </div>
                        </div>

						{{-- TAGS --}}
                        <div class="form-group row">
                            {{ html()->label("Tags")->class('col-md-2 form-control-label')->for('tags') }}
                            <div class="col-md-10">
                                {{ html()->multiselect('tags', $tag)
                                    ->class('form-control select2tag')
                                    ->value(isset($content) ?  $content->mentor_id : null)
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
                            {{ html()->label("Slug (automatically created if empty)")->class('col-md-2 form-control-label')->for('slug') }}
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
                                                            ->value(isset($content) ?  $content->display_start : null)
                                                            ->readonly()
                                                            ->class('datepicker') }}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    {{ html()->label("End date")->class('col-md-2 form-control-label')->for('display_end') }}
                                                    <div class="col-md-10">
                                                        {{ html()->text('display_end')
                                                            ->class('form-control')
                                                            ->style('background-color: white')
                                                            ->readonly()
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
                                                            ->readonly()
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
                                @if(isset($asset))
                                    <img src="{{ asset_cdn($asset->thumb) }}" style="max-width: 200px;padding-bottom: 16px;" />
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
                                                                @if(isset($asset))
                                                                    <img src="{{ asset_cdn($asset->poster) }}" style="max-width: 200px;padding-bottom: 16px;" />
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
                                                                @if(isset($asset))
                                                                    @if($asset->poster_cotd)
                                                                        <img src="{{ asset_cdn($asset->poster_cotd) }}" style="max-width: 200px;padding-bottom: 16px;" />
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

                        {{-- INTRO VIDEO --}}
                        <div class="form-group row">
                            {{ html()->label("Introduction video")->class('col-md-2 form-control-label') }}
                            <div class="col-md-10">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Poster</th>
                                            <th>Video</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-12">
                                                                @if(isset($asset))
                                                                    @if($asset->poster_intro)
                                                                        <img src="{{ asset_cdn($asset->poster_intro) }}" style="max-width: 200px;padding-bottom: 16px;" />
                                                                    @else
                                                                        <div style="background-color: #f0f0f0; min-height: 112px; max-width: 200px; margin-bottom: 16px;"></div>
                                                                    @endif
                                                                @endif
                                                                <div class="dropzone dzimage" id="posterIntro"></div>
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
                                                                @if(isset($asset))
                                                                    <video src="{{ asset_cdn($asset->intro_video) }}" style="max-width: 200px; padding-bottom: 16px;" controls /></video>
                                                                @endif
                                                                <div class="dropzone dzvideo" id="videoIntro"></div>
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
                                            <th>Full length*</th>
                                            <th>Preview (+-10 sec)*</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-12">
                                                                @if(isset($asset))
                                                                    <video src="{{ asset_cdn($asset->video) }}" style="max-width: 200px; padding-bottom: 16px;" controls /></video>
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
                                                                @if(isset($asset))
                                                                    <video src="{{ asset_cdn($asset->preview_video) }}" style="max-width: 200px; padding-bottom: 16px;" controls /></video>
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


                        {{-- ZIP --}}
                        <div class="form-group row">
                            {{ html()->label("Zip (asset) *")->class('col-md-2 form-control-label') }}
                            <div class="col-md-10">
                                @if(isset($asset))
                                    <a href="{{ asset_cdn($asset->zip) }}" target="_blank">Download</a>                                    
                                @endif
                                <div class="dropzone dzzip"></div>
                            </div>
                        </div>


                        {{-- GALLERY --}}
                        <div class="form-group row">
                            {{ html()->label("Images gallery")->class('col-md-2 form-control-label') }}
                            <div class="col-md-10">
                                @if(isset($asset))
                                    <div style="margin-bottom: 16px;">
                                    @foreach($asset->getMedia('images') as $media)
                                        <img src="{{ asset_cdn($media->getCustomProperty('path') . $media->file_name) }}" style="max-height: 78px;object-fit: cover;object-position: 0% 25%;" />    
                                    @endforeach                       
                                    </div>
                                @endif
                                <div class="dropzone dzgallery"></div>
                            </div>
                        </div>

                        {{-- CREATION DATE --}}
                        <div class="form-group row">
                            {{ html()->label("Creation date of content")->class('col-md-2 form-control-label')->for('releaseDate') }}
                            <div class="col-md-10">
                                {{ html()->text('releaseDate')
                                    ->class('form-control')
                                    ->style('background-color: white')
                                    ->readonly()
                                    ->value(isset($asset) ? $asset->releaseDate : null)
                                    ->class('datepicker') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.library.assets'), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col text-right">
                        <button type="submit" id="submitform" class="btn btn-primary">{{ isset($asset) ? "Edit" : "Create" }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{ html()->hidden('thumbFile', isset($asset) ? basename($asset->thumb) : null) }}
        {{ html()->hidden('thumbMimetype', '') }}
        {{ html()->hidden('thumbSize', '') }}

        {{ html()->hidden('posterFile', isset($asset) ? basename($asset->poster) : null) }}
        {{ html()->hidden('posterMimetype', '') }}
        {{ html()->hidden('posterSize', '') }}
        {{ html()->hidden('posterCOTDFile', isset($asset) ? basename($asset->poster_cotd) : null) }}
        {{ html()->hidden('posterCOTDMimetype', '') }}
        {{ html()->hidden('posterCOTDSize', '') }}

        {{ html()->hidden('posterIntroFile', isset($asset) ? basename($asset->poster_intro) : null) }}
        {{ html()->hidden('posterIntroMimetype', '') }}
        {{ html()->hidden('posterIntroSize', '') }}


        {{ html()->hidden('videoIntroFile', isset($asset) ? basename($asset->intro_video) : null) }}
        {{ html()->hidden('videoIntroMimetype', '') }}
        {{ html()->hidden('videoIntroSize', '') }}
        {{ html()->hidden('videoFullFile', isset($asset) ? basename($asset->video) : null) }}
        {{ html()->hidden('videoFullMimetype', '') }}
        {{ html()->hidden('videoFullSize', '') }}
        {{ html()->hidden('videoPreviewFile', isset($asset) ? basename($asset->preview_video) : null) }}
        {{ html()->hidden('videoPreviewMimetype', '') }}
        {{ html()->hidden('videoPreviewSize', '') }}

        {{ html()->hidden('zipFile', isset($asset) ? basename($asset->zip) : null) }}
        {{ html()->hidden('zipMimetype', '') }}
        {{ html()->hidden('zipSize', isset($asset) ? $asset->filesize : null) }}

        {{ html()->hidden('pathID', $nextId) }}
        @if(isset($asset))
            {{ html()->hidden('assetID', $asset->id) }}
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
            'uploadpath': 'assets/{{ $nextId }}'
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
        acceptedFiles: "video/*",
        maxFilesize: 16384,
        createImageThumbnails: false,
        maxFiles:1,
        autoProcessQueue: true,
        parallelUploads: 10,
        url: '<?php echo route('frontend.upload.file'); ?>',
        headers: {
            'x-csrf-token': '{{ csrf_token() }}',
            'uploadpath': 'assets/{{ $nextId }}'
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
    
    // DZ for ZIP
    $(".dzzip").dropzone({
        acceptedFiles: "zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed",
        maxFilesize: 16384,
        createImageThumbnails: false,
        maxFiles:1,
        autoProcessQueue: true,
        parallelUploads: 10,
        url: '<?php echo route('frontend.upload.file'); ?>',
        headers: {
            'x-csrf-token': '{{ csrf_token() }}',
            'uploadpath': 'assets/{{ $nextId }}'
        },
        renameFile: function (file) {
            var dt = new Date();
            var time = dt.getTime();
            var fe = file.name.split('.').pop();
            var safeString = file.name.replace(/\.[^/.]+$/, "");
            safeString = safeString.replace(/[^0-9a-zA-Z-]/g,  '').toLowerCase();
            return safeString+'_'+time+'.'+fe;
        },
        addRemoveLinks: false,
        timeout: 0,
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                    $("#zipFile").val("");
                    $("#zipMimetype").val("");
                    $("#zipSize").val("");
                }
            });
            this.on('removedfile', function(file) {
                $("#zipFile").val("");
                $("#zipMimetype").val("");
                $("#zipSize").val("");
            });	
        },
        success: function (file, response) {
            console.log(this.element);
            if(response.success) {
                $("#zipFile").val(response.success[0].filename);
                $("#zipMimetype").val(response.success[0].mimeType);
                $("#zipSize").val(response.success[0].size);
            }
        },
        error: function (file, response) {
            return false;
        }
    });

    // DZ for GALLERY
    $(".dzgallery").dropzone({
        acceptedFiles: "image/*",
        maxFilesize: 4096,
        createImageThumbnails: true,
        autoProcessQueue: true,
        parallelUploads: 10,
        url: '<?php echo route('frontend.upload.file'); ?>',
        headers: {
            'x-csrf-token': '{{ csrf_token() }}',
            'uploadpath': 'assets/{{ $nextId }}'
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
        success: function (file, response) {
            if(response.success) {
                $("<input>").attr({ name: "galleryFile[]", type: "hidden", value: response.success[0].filename }).appendTo("form");
                $("<input>").attr({ name: "galleryMimetype[]", type: "hidden", value: response.success[0].mimeType }).appendTo("form");
                $("<input>").attr({  name: "gallerySize[]", type: "hidden", value: response.success[0].size }).appendTo("form");
            }
        },
        error: function (file, response) {
            return false;
        }
    });


    $(document).ready(function() {

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

		// tags dropdown
		var tags = $('#tags').select2({
			tags: true
		});
		@if(isset($content))
			var tagsID = @json($content->tags->pluck('id')->toArray());
			tags.val(tagsID).trigger("change");
		@endif

        $(".datepicker").flatpickr({allowInput: true});

        // VALIDATION
        $('#submitform').on('click', function(e) {
            if($('#description').summernote('isEmpty')) {
                alert('Enter a description');
                e.preventDefault();
            }
            if($("#posterFile").val() == "" || $("#zipFile").val() == "" || $("#videoFullFile").val() == "" || $("#videoPreviewFile").val() == "") {
                alert("Upload the required files or wait for upload to finish");
                e.preventDefault();
            }
        });
    });
</script>
@endpush
