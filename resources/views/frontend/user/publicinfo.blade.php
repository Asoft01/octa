@extends('frontend.layouts.app')

@section('title', app_name() . ' | Availability')

@section('content')

	<div id="flash" class="position-relative w-100">
		<div class="position-absolute w-100" style="left: 0;">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-12 col-sm-6">
						@if(session('success'))
							<div class="alert alert-success fade show" role="alert">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<h4 class="alert-heading">Success</h4>
								<p class="mb-0">{{ session('success') }}</p>
							</div>
						@endif
						@if($errors->any())
							<div class="alert alert-danger fade show" role="alert">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								@if($errors->count() == 1)
									<h4 class="alert-heading">Error</h4>
									<p class="mb-0">{{ $errors->first() }}</p>
								@else
									<h4 class="alert-heading">Errors</h4>
									<ul class="mb-0">
										@foreach($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								@endif
							</div>
						@endisset
					</div>
				</div>
			</div>
		</div>
	</div>

    <div class="row justify-content-center" style="padding-top: 40px; padding-bottom: 40px; margin-left: 0px; margin-right: 0px;">
        <div class="col col-sm-8 align-self-center">
			
			<div class="modal-dialog modal-dialog-centered modal-md mw-100">
				<div class="modal-content">
					<div class="modal-header-pro"><h2>Public Info</h2></div>
					<div class="modal-body-pro social-login-modal-body-pro">

						<form id="form-availability" method="POST" action=" {{ route('frontend.user.publicinfo.update') }}">
							@csrf

							@if($user->hasRole('mentor'))

								{{--<!-- HOURS PER WEEK -->--}}
								<div class="form-group">
									<label for="hoursWeek">
										<span>Minutes per week</span>
										@render('frontend.includes.help', ['title' => 'For example if you enter 30 the maximum reviews you can do per week is either 2 regular (15 minutes) or 1 long (30 minutes).'])
									</label>
									<input id="hoursWeek" name="hoursWeek" type="number" class="form-control" placeholder="Hours Per Week" min="0" step="1" value="{{ old('hoursWeek', $account->hoursWeek) }}">
								</div>

								{{--<!-- Unavailable UNTIL -->--}}
								<div class="form-group">
									<label for="bookeduntil">Unavailable until</label>
									@php $bookeduntil = $account->bookeduntil ? $account->bookeduntil->tz($tz)->toDateString() : null; @endphp
									<input id="bookeduntil" name="bookeduntil" type="text" class="form-control datepickerDate" placeholder="Unavailable until" value="{{ old('bookeduntil', $bookeduntil) }}">
								</div>

								{{--<!-- DELAY -->--}}
								<div class="form-group">
									<label for="delay">
										<span>Delay</span>
										@render('frontend.includes.help', ['title' => 'You can specify a delay in days. This delay will be added to the calculation of the due date.'])
									</label>
									<input id="delay" name="delay" type="number" class="form-control" placeholder="Delay" min="0" step="1" value="{{ old('delay', $account->delay ?: 0) }}">
								</div>

							@endif

							{{--<!-- POSITION -->--}}
							<div class="form-group">
								<label for="position">Position</label>
								<input id="position" name="position" type="text" class="form-control" placeholder="Position" value="{{ old('position', $account->position) }}">
							</div>

							{{--<!-- CV -->--}}
							<div class="form-group">
								<label for="cv">CV</label>
								<textarea id="cv" name="cv" class="form-control summernote">{!! old('cv', $account->cv) !!}</textarea>
							</div>

							{{--<!-- BIO -->--}}
							<div class="form-group">
								<label for="bio">Bio</label>
								<textarea id="bio" name="bio" class="form-control summernote">{!! old('bio', $account->bio) !!}</textarea>
							</div>

							{{--<!-- POSTER -->--}}
							<div class="form-group">
								<label for="poster">Video Poster (1280x720)</label>
								<input type="file" id="poster" name="poster" data-oldvalue="{{ old('poster') }}" data-filetype="image" data-uploadpath="{{ $account->id . '/photos' }}">
								<small class="form-text text-nowrap overflow-hidden mx-1">
									<span>Current:</span>
									@if($account->poster)
										<a class="font-italic" href="{{ config('ac.CDN_MEDIA') . $account->poster }}" target="_blank">{{ basename($account->poster) }}</a>
									@else
										<span class="font-italic">None</span>
									@endif
								</small>
							</div>

							{{--<!-- PREVIEW VIDEO -->--}}
							{{--
							<div class="form-group">
								<label for="preview_video">Preview Video</label>
								<input type="file" id="preview_video" name="preview_video" data-oldvalue="{{ old('preview_video') }}" data-filetype="video" data-uploadpath="{{ $account->id . '/videos' }}">
								<small class="form-text text-nowrap overflow-hidden mx-1">
									<span>Current:</span>
									@if($account->preview_video)
										<a class="font-italic" href="{{ config('ac.CDN_MEDIA') . $account->preview_video }}" target="_blank">{{ basename($account->preview_video) }}</a>
									@else
										<span class="font-italic">None</span>
									@endif
								</small>
							</div>
							--}}

							{{--<!-- VIDEO -->--}}
							<div class="form-group">
								<label for="video">Full Video (.mp4)</label>
								<input type="file" id="video" name="video" data-oldvalue="{{ old('video') }}" data-filetype="video" data-uploadpath="{{ $account->id . '/videos' }}">
								<small class="form-text text-nowrap overflow-hidden mx-1">
									<span>Current:</span>
									@if($account->video)
										<a class="font-italic" href="{{ config('ac.CDN_MEDIA') . $account->video }}" target="_blank">{{ basename($account->video) }}</a>
									@else
										<span class="font-italic">None</span>
									@endif
								</small>
							</div>

							{{--<!-- SUBMIT -->--}}
							<div class="form-group text-center">
								<button class="btn btn-success btn-sm w-25" type="submit" style="min-width: 82px;">Submit</button>
							</div>

						</form>

					</div><!-- close .modal-body -->
				</div><!-- close .modal-content -->
			</div><!-- close .modal-dialog -->

        </div><!-- close .col -->
    </div><!-- close .row -->

    {{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
@endsection

@push('after-styles')
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet" href="{{ url('css/jquery-uploader.css') }}">
	<style>
		#flash .alert {
			z-index: 1035;
		}
		#form-availability input.form-control[type=number] { 
			-moz-appearance: textfield;
			appearance: textfield;
		}
		#form-availability input.form-control[type=number]::-webkit-inner-spin-button,
		#form-availability input.form-control[type=number]::-webkit-outer-spin-button {
			-webkit-appearance: none; 
			margin: 0;
		}
		#form-availability input.form-control,
		#form-availability textarea.form-control {
			padding-top: 0.375rem;
			padding-bottom: 0.375rem;
		}
		#form-availability .form-group > input[type="file"] {
			display: block;
			padding: 0;
		}
	</style>
	<style>
		#form-availability .note-editor .btn {
			font-weight: 400;
			color: #c4c4c5;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
			background-color: transparent;
			border: 1px solid transparent;
			padding: 0.375rem 0.75rem;
			padding: .28rem .65rem;
			font-size: 13px;
			line-height: 1.5;
			border-radius: 0.25rem;
			border-color: #2b2938;
		}
		#form-availability .note-editor.note-frame {
			background-color: #161424;
			border-color: #2b2938;
			color: #c4c4c5;
		}
		#form-availability .note-editor .note-toolbar {
			border-top-left-radius: calc(0.25rem - 1px);
			border-top-right-radius: calc(0.25rem - 1px);
		}
		#form-availability .note-editor .note-toolbar .btn i {
			margin-right: 0;
		}
		#form-availability .note-editor .note-toolbar .btn.dropdown-toggle::after {
			margin-bottom: -2px;
		}
		#form-availability .note-editor .note-toolbar .btn.dropdown-toggle:not([aria-label="More Color"])::after {
			margin-left: 7px;
			margin-right: -3px;
		}
		#form-availability .note-editor .note-toolbar .note-dropdown-menu {
			background-color: #22202e;
			border-color: #2b2938;
		}
		#form-availability .note-editor .note-toolbar .note-dropdown-menu .dropdown-item {
			color: #c4c4c5;
		}
		#form-availability .note-editor.note-frame .note-statusbar {
			border-bottom-left-radius: calc(0.25rem - 1px);
			border-bottom-right-radius: calc(0.25rem - 1px);
		}
		#form-availability .note-editor.note-frame .note-statusbar .note-resizebar .note-icon-bar {
			border-top-color: rgba(255, 255, 255, .2);
		}
		#form-availability .note-editor .note-editable {
			background-color: #08070e;
			background-color: #161424;
		}
	</style>
@endpush

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script src="{{ url('js/jquery-uploader.js') }}"></script>
	<script>
		$(document).ready(function() {

			$('#flash .alert').delay(5000).queue(function() { $(this).alert('close'); });
			$('[data-toggle="tooltip"]').tooltip();
			$('.datepickerDate').flatpickr({ allowInput: true });

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
                    onPaste: function(e) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                        e.preventDefault();
                        setTimeout(function() { document.execCommand('insertText', false, bufferText); }, 10); // Firefox fix
                    }
                }
            });

			$('#form-availability input[type="file"]').each(function(index, element) {
				$(element).uploader({
					url: "{{ route('frontend.upload.file') }}",
					oldValue: $(element).attr('data-oldvalue'),
					headers: {
						'Content-Type': 'multipart/form-data',
						'Uploadtype': $(element).attr('data-filetype'),
						'Uploadpath': $(element).attr('data-uploadpath')
					}
				});
			});

		});
	</script>
@endpush