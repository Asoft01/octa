@extends('backend.layouts.app')

@php $verb = isset($model) ? 'Edit' : 'Create'; @endphp

@section('title', 'Playlists | ' . $verb)

@section('breadcrumb-links')
	@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')

	{{ html()->form('POST', route('admin.library.playlists.store'))->id('playlists-bo-form')->class('form-horizontal')->open() }}
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-5">
						<h4 class="card-title mb-0">
							<span>Playlist Management</span>
							<small class="text-muted">{{ $verb }}</small>
						</h4>
					</div>
					{{-- DELETE --}}
					@if(isset($model))
						<div class="col-sm-7 text-right">
							<a href="{{ route('admin.library.playlists.delete', $model->id) }}" name="confirm_item" id="delete" class="btn btn-primary btn-danger">Delete</a>
						</div>
					@endif
				</div>
				<hr>
				<div class="row mt-4 mb-4">
					<div class="col">

						{{-- DOMAIN --}}
						<div class="form-group row">
							{{ html()->label('Domain *')->class('col-md-2 form-control-label')->for('domain_id') }}
							<div class="col-md-10">
								{{ html()->select('domain_id', $domains)
									->class('form-control select2empty')
									->required()
									->value(isset($model) ? $model->content->domain_id : null) }}
							</div>
						</div>

						{{-- CATEGORIES --}}
						<div class="form-group row">
							{{ html()->label('Category *')->class('col-md-2 form-control-label')->for('categories') }}
							<div class="col-md-10">
								{{ html()->select('categories', $categories)
									->class('form-control select2empty')
									->required()
									->value(isset($model) ? optional($model->content->categories->first())->id : null) }}
							</div>
						</div>

						{{-- TAGS --}}
						<div class="form-group row">
							{{ html()->label("Tags")->class('col-md-2 form-control-label')->for('tags') }}
							<div class="col-md-10">
								{{ html()->multiselect('tags', $tags)->class('form-control select2tag') }}
							</div>
						</div>

						{{-- TITLE --}}
						<div class="form-group row">
							{{ html()->label('Title *')->class('col-md-2 form-control-label')->for('title') }}
							<div class="col-md-10">
								{{ html()->text('title')
									->attribute('maxlength', 191)
									->autofocus()
									->class('form-control')
									->placeholder('Title')
									->required()
									->value(isset($model) ? $model->content->title : null) }}
							</div>
						</div>

						{{-- SLUG --}}
						<div class="form-group row">
							{{ html()->label('Slug * <small>(automatically created if empty)</small>')->class('col-md-2 form-control-label')->for('slug') }}
							<div class="col-md-10">
								{{ html()->text('slug')
									->attribute('maxlength', 191)
									->class('form-control')
									->placeholder('Slug')
									->required()
									->value(isset($model) ? $model->content->slug : null) }}
								<div id="slug-validation-feedback" class="invalid-feedback">@lang('validation.unique', ['attribute' => 'slug'])</div>
							</div>
						</div>

						{{-- TITLE COTD --}}
						<div class="form-group row">
						{{ html()->label('Title as content of the day')->class('col-md-2 form-control-label')->for('title_cotd') }}
							<div class="col-md-10">
								{{ html()->text('title_cotd')
									->attribute('maxlength', 191)
									->class('form-control')
									->placeholder('Title as content of the day')
									->value(isset($model->content) ? $model->content->title_cotd : null) }}
							</div>
						</div>

						{{-- DESCRIPTION --}}
						<div class="form-group row">
						{{ html()->label('Description *')->class('col-md-2 form-control-label')->for('description') }}
							<div class="col-md-10">
								{{ html()->textarea('description')
									->class('form-control summernote')
									->value(isset($model->content) ? $model->content->description : null) }}
							</div>
						</div>

                        {{-- DESCRIPTION COTD --}}
                        <div class="form-group row">
                        {{ html()->label('Description as content of the day')->class('col-md-2 form-control-label')->for('description_cotd') }}
                            <div class="col-md-10">
                                {{ html()->textarea('description_cotd')
									->attribute('data-maxlength', 400)
                                    ->class('form-control summernote')
                                    ->value(isset($model) ? $model->content->description_cotd : null) }}
                            </div>
                        </div>

						{{-- DATE --}}
						<div class="form-group row">
							{{ html()->label('Display dates')->class('col-md-2 form-control-label') }}
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
													<div class="form-group row">
														{{ html()->label('Start date')->class('col-md-2 form-control-label')->for('display_start') }}
														<div class="col-md-10">
															{{ html()->text('display_start')
																->attribute('autocomplete', 'off')
																->class('form-control datepicker')
																->style('background-color: white;')
																->value(isset($model->content) ? $model->content->display_start : null) }}
														</div>
													</div>
													<div class="form-group row">
														{{ html()->label('End date')->class('col-md-2 form-control-label')->for('display_end') }}
														<div class="col-md-10">
															{{ html()->text('display_end')
																->attribute('autocomplete', 'off')
																->class('form-control datepicker')
																->style('background-color: white;')
																->value(isset($model->content) ? $model->content->display_end : null) }}
														</div>
													</div>
												</td>
												<td>
													<div class="form-group row">
														{{ html()->label('COTD date')->class('col-md-2 form-control-label')->for('cotd_start') }}
														<div class="col-md-10">
															{{ html()->text('cotd_start')
																->attribute('autocomplete', 'off')
																->class('form-control datepicker')
																->style('background-color: white;')
																->value(isset($model->content) ? $model->content->cotd_start : null) }}
														</div>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="row border-top mb-4 mx-0"><!-- SEPARATOR --></div>

						{{-- USER --}}
						<div class="form-group row">
							{{ html()->label('User *')->class('col-md-2 form-control-label')->for('user_id') }}
							<div class="col-md-10">
								{{ html()->select('user_id', $users)
									->class('form-control select2empty')
									->required()
									->value(isset($model) ? $model->user_id : null) }}
							</div>
						</div>

						{{-- POSTER --}}
						<div class="form-group row">
							{{ html()->label('Poster <small>(1280x720)</small>')->class('col-md-2 form-control-label') }}
							<div class="col-md-10">
								@if(isset($model) && $model->poster)
									<img src="{{ config('ac.SIH') . config('ac.THUMB_RES') . $model->poster }}" style="max-width: 200px; padding-bottom: 16px;">
								@endif
								<div id="poster" class="dropzone dzimage"></div>
							</div>
						</div>

						<div class="row border-top mb-4 mx-0"><!-- SEPARATOR --></div>

						<div class="form-group row">
							{{ html()->label('Contents')->class('col-md-2 form-control-label') }}
							<div class="col-md-10">
								<div class="card rounded-0 px-0 mb-3">
									<div class="card-header rounded-0 d-flex align-items-center justify-content-between bg-secondary py-2">
										<span>Contents Datatable</span>
										<button class="btn btn-dark btn-sm ml-auto" style="opacity: .65;" type="button" data-toggle="collapse" data-target="#contents-datatable" aria-expanded="false" aria-controls="contents-datatable">Show/Hide</button>
									</div>
									<div id="contents-datatable" class="collapse">
										{!! $datatable->render('backend.library.datatable') !!}
									</div>
								</div>
								<div class="card rounded-0 mb-2">
									<div class="card-header rounded-0 bg-secondary">Playlist Contents</div>
									<ol id="playlist-contents" class="list-group list-group-flush">
										@if(isset($model))
											@foreach($model->contents->sortBy('pivot.display_order') as $content)
												<li class="list-group-item pl-3" data-content-id="{{ $content->id }}">
													<div class="d-flex align-items-center justify-content-between">
														<div><i class="fas fa-grip-vertical fa-fw text-secondary mr-2"></i><span>{{ $content->title }}</span></div>
														<button class="btn btn-sm btn-danger" title="Remove from Playlist"><i class="fas fa-times"></i></button>
													</div>
												</li>
											@endforeach
										@endif
									</ol>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<div class="card-footer clearfix">
				<div class="row">
					<div class="col">
						<a class="btn btn-danger" href="{{ route('admin.library.playlists') }}">@lang('buttons.general.cancel')</a>
					</div>
					<div class="col text-right">
						<button type="submit" id="submitform" class="btn btn-primary">{{ $verb }}</button>
					</div>
				</div>
			</div>
		</div>

		{{ html()->hidden('posterFile', isset($model) ? basename($model->poster) : null) }}
		{{ html()->hidden('posterMimetype', '') }}
		{{ html()->hidden('posterSize', '') }}

		{{ html()->hidden('contents', isset($model) ? $model->contents->sortBy('pivot.display_order')->implode('id', ',') : null) }}

		@isset($model)
			{{ html()->hidden('id', $model->id) }}
		@endisset

	{{ html()->form()->close() }}

@endsection

@push('after-styles')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css">
	{{ style(mix_cdn('css/dz.min.css')) }}
	{{ style(url('/css/backoffice-forms.css')) }}
	<style>
		#contents-datatable .dt-buttons {
			display: none;
		}
		#contents-datatable td {
			vertical-align: middle;
		}
		#contents-datatable .dataTables_filter > label {
			margin: .5rem 1rem;
			padding-top: .5rem;
		}
		#contents-datatable .dataTables_scrollHead {
			border-top: 1px solid #c8ced3 !important;
			border-bottom: 1px solid #c8ced3 !important;
		}
		#contents-datatable .dataTables_scrollHead table.dataTable {
			margin-top: 0 !important;
		}
		#contents-datatable .dataTables_scrollHead table.dataTable,
		#contents-datatable .dataTables_scrollHead table.dataTable th {
			border: 0;
		}
		#contents-datatable .dataTables_scrollBody table.dataTable tbody tr td:not(:first-child) {
			white-space: pre;
		}
		#contents-datatable .dataTables_empty {
			padding: .75rem !important;
		}
		#contents-datatable .dataTables_info {
			padding: .85em .75rem;
			border-top: 1px solid #c8ced3 !important;
		}
		#contents-datatable .dataTables_paginate {
			padding: 0 .75rem .5rem .75rem !important;
		}
		#playlist-contents {
			user-select: none;
		}
		#playlist-contents .ui-sortable-handle {
			cursor: move;
		}
		#playlist-contents .list-group-item.ui-sortable-placeholder {
			background-color: #fff3cd;
			visibility: visible !important;
		}
		#playlist-contents .list-group-item.ui-sortable-helper {
			border: 1px solid rgba(0, 0, 0, .125) !important;
			cursor: grabbing;
		}
	</style>
@endpush

@push('after-scripts')
	<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	{!! script(mix_cdn('js/dz.min.js')) !!}
	{!! $datatable->html()->parameters([ 'ajax.url' => route('admin.library.playlists.contents') ])->scripts() !!}
	<script>
		Dropzone.autoDiscover = false;
		$('.dzimage').dropzone({
			acceptedFiles: 'image/*',
			maxFilesize: 4096,
			createImageThumbnails: true,
			maxFiles:1,
			autoProcessQueue: true,
			parallelUploads: 10,
			url: '{{ route("frontend.upload.file") }}',
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
	</script>
	<script>
		$(document).ready(function() {
			$('.select2').select2();
			$('.select2empty').each(function() {
				if ($(this).find('[selected]').length == 0) {
					$(this).prepend('<option selected=""></option>');
				}
			}).select2({ allowClear: true, placeholder: '' });

			// tags dropdown
			var tags = $('#tags').select2({ tags: true });
			@if(isset($model->content))
				var tagsID = @json($model->content->tags->pluck('id')->toArray());
				tags.val(tagsID).trigger("change");
			@endif

			$('#contents-datatable')
				.on('shown.bs.collapse', function(e) {
					$(window).trigger('resize');
				})
				.on('click', 'td > a.btn[data-content-id]', function(e) {
					e.preventDefault();
					$elem = $(e.currentTarget);
					$list = $('#playlist-contents');
					id = $elem.attr('data-content-id');
					title = $elem.closest('tr').find('td:first').text();
					if ($list.find('li[data-content-id="' + id + '"]').length) {
						alert('The playlist already contains this content.');
					} else {
						$list.append([
							'<li class="list-group-item pl-3 ui-sortable-handle" data-content-id="' + id + '">',
								'<div class="d-flex align-items-center justify-content-between">',
									'<div><i class="fas fa-grip-vertical fa-fw text-secondary mr-2"></i><span>' + title + '</span></div>',
									'<button class="btn btn-sm btn-danger" title="Remove from Playlist"><i class="fas fa-times"></i></button>',
								'</div>',
							'</li>'
						].join(''));
					}
				});

			$('#playlist-contents').on('click', 'button', function(e) {
				e.preventDefault();
				$el = $(e.currentTarget);
				$el.closest('li').remove();
			}).sortable();

			// AUTO SLUGIFY TITLE
			$('#title, #slug').on('input', function(e) {
				axios.post('{{ route("admin.library.playlists.slug") }}', {
					content: $(this).val(),
					generate: (this.id === 'title')
				})
				.then(function (response) {
					exists = response.data.exists && response.data.slug !== '{{ isset($model) ? $model->content->slug : null }}';
					$('#slug').val(response.data.slug).toggleClass('is-invalid', exists);
				})
				.catch(function (error) {
					console.log(error);
				});
			});

			// SUMMERNOTE
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

			// DATEPICKER
			$(".datepicker").flatpickr({
				allowInput: true, 
				enableTime: true,
				defaultHour: 4,
				defaultMinute: 0
			});

			// VALIDATION
			$('#playlists-bo-form').on('submit', function(e) {
				var unfinished = 0;
				$('.dzimage, .dzvideo').each(function(index, element) {
					unfinished += element.dropzone.getUploadingFiles().length;
				});
				if (unfinished > 0) {
					alert("Upload the required files or wait for upload(s) to finish.");
					e.preventDefault();
				}

				$('input[name="contents"]').val(function() {
					var value = '';
					$('#playlist-contents li[data-content-id]').each(function() {
						value += $(this).attr('data-content-id') + ',';
					});
					return value.replace(/,\s*$/, '');
				});
			});
		});
	</script>
@endpush
