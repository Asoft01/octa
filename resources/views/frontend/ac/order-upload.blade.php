@extends('frontend.layouts.app')

@section('title', app_name())

@section('content')

	<div id="content-pro">

		<div class="container custom-gutters-pro">

			<div style="font-size: 16px; margin-bottom: 42px; padding-bottom: 6px; border-bottom: 1px solid #252525;">
				<a href="{{ route('frontend.user.order') }}"><i class="fas fa-filter" style="margin-right: 4px;"></i> Review options</a>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<i class="fas fa-file-upload" style="margin-right: 4px;"></i> <strong>Upload work</strong>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<span style="color: #3e3e3e"><i class="fas fa-credit-card" style="margin-right: 4px;"></i> Payment</span>
					<span style="margin-right: 8px; margin-left: 8px; color: #3e3e3e">></span>
				<span style="color: #3e3e3e"><i class="fas fa-check-circle" style="margin-right: 4px;"></i> Confirmation</span>
			</div>


			@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif

			<form method="post" action="{{ route('frontend.user.order.upload.post') }}">
			@csrf
			<h2>Upload your animation to review or video of your question to our expert</h2>
			<div class="dropzone" id="dropzone"></div>
			<div id="ntr" class="form-group" style="display: none; margin:0px;">
				<h2 style="margin-bottom: -4px; margin-top: 12px;">Select your skill level</h2>
				<p><i style="font-size: 12px; margin-top: -4px;">(this will not be posted publicly, it will aid the reviewer in guiding you)</i></p>
				<select id="level" name="level" required style="min-width: 240px; width: 31%; padding-left: 6px;">
					<option value="">Select your skill level</option>
					<option value="Novice">Novice</option>
					<option value="Beginner student">Beginner student</option>
					<option value="Intermediate student">Intermediate student</option>
					<option value="Advanced student">Advanced student</option>
					<option value="Junior professional">Junior professional</option>
					<option value="Mid level professional">Mid level professional</option>
					<option value="Senior professional">Senior professional</option>
				</select>
				
				<h2 style="margin-top: 24px;">Note to reviewer</h2>
				<textarea class="msgreviewer" placeholder="Write something to your reviewer that will help them understand the state of your work. Include what you think is already working and what you think you might need help with.&#10;&#10;Quick Examples:&#10;- This is a student assignment for a weight lift. It's currently in blocking and I like the major poses but I am struggling with transitions&#10;-This is a personal acting test in polish. The character just got fired from their job and is talking to their roomate. I'm looking for advice to push it more. The lipsync doesn't feel right yet.&#10;-I just started animating and I'm not sure what's working and what isn't reading at all&#10;-I've put X hours into this project" id="textarea" name="note">{{ $note }}</textarea>
				<div id="textarea_feedback" style="float: right; margin-top: -24px; font-size: 12px;">1000 characters remaining</div>
				<div class="clearfix"></div>

			</div>
			<div style="margin-top: 24px;width: 100%; text-align: center;">
				<button disabled id="payment" type="submit" style="width: 200px; margin: 0 auto;" class="btn">Next step</button>
			</div>

			<input type="hidden" id="videoToReview" name="videoToReview" value="" />
			<input type="hidden" id="mimeType" name="mimeType" value="" />
			<input type="hidden" id="size" name="size" value="" />
			<input type="hidden" id="fps" name="fps" value="" />
			</form>

			<div class="clearfix"></div>
		</div><!-- close .container -->
	</div><!-- close #content-pro -->


	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

@endsection

@push('before-styles')
	{{ style(mix_cdn('css/dz.min.css')) }}
@endpush

@push('after-scripts')
	{!! script(mix_cdn('js/dz.min.js')) !!}
	<script>
	var text_max = 1000;
	var isUploaded = false;

	Dropzone.prototype.defaultOptions.dictDefaultMessage = "Drop your video file here (.mp4 or .mov)";

	Dropzone.options.dropzone =
			{
				url: '<?php echo route('frontend.upload.file'); ?>',
				headers: {
					'x-csrf-token': '{{ csrf_token() }}',
					'Uploadpath': 'uploadReviews',
					'Uploadtype': 'video',
				},
				maxFilesize: 4096,
				createImageThumbnails: false,
				maxFiles:1,
				renameFile: function (file) {
					var dt = new Date();
					var time = dt.getTime();
					var safeString = file.name.replace(/(\.[\w\d_-]+)$/i, '_'+time+'$1');
					return safeString.replace(/\s+/g, '-').toLowerCase();
				},
				acceptedFiles: ".mp4,.mov",
				addRemoveLinks: true,
				timeout: 0,
				init: function() {
					this.on('addedfile', function(file) {
						if (this.files.length > 1) {
						this.removeFile(this.files[0]);
						}
					});
					this.on('removedfile', function(file) {
						isUploaded = false;
						$("#payment").prop('disabled', true);
					});

					// delivery already exist and we have a videoToReview show it
					<?php if(!empty($videoToReview)) { ?>
						var mockFile = {
							name: '{{ $videoToReview }}',
							size: '{{ $size }}',
							mimeType: '{{ $mimeType }}',
							fps: '{{ $fps }}',
							accepted: true            // required if using 'MaxFiles' option
						};
						this.files.push(mockFile);    // add to files array
						this.emit("addedfile", mockFile);
						$('.dz-progress').hide();
						this.emit("complete", mockFile);

						isUploaded = true;

						$("#videoToReview").val('{{ $videoToReview }}');
						$("#mimeType").val('{{ $mimeType }}');
						$("#fps").val('{{ $fps }}');
						$("#size").val('{{ $size }}');
						$("#level").val('{{ $level }}').change();
						$("#ntr").fadeIn();

						// recheck length of note
						var text_length = $('#textarea').val().length;
						var text_remaining = text_max - text_length;
						$('#textarea_feedback').html(text_remaining + ' characters remaining');
						if(text_remaining < 0) {
							$("#payment").prop('disabled', true);
						} else {
							if($("#level").val()) {
								$("#payment").prop('disabled', false);
							} else {
								$("#payment").prop('disabled', true);
							}
						}
					<?php } ?>
					/*
					let mockFile = { name: "Filename 2", size: 12345, type: "video/mp4" };
					this.displayExistingFile(mockFile, "https://cdn.agora.community/t.png");
					this.emit("complete", mockFile);*/

				},
				sending: function(file, xhr, formData) {
					$("#ntr").fadeIn();
					isUploaded = false;
				},
				success: function (file, response) {
					if(response.success) {
						isUploaded = true;
						$("#videoToReview").val(response.success[0].filename);
						$("#mimeType").val(response.success[0].mimeType);
						$("#size").val(response.success[0].size);
						$("#fps").val(response.success[0].fps);
						// recheck length of note
						var text_length = $('#textarea').val().length;
						var text_remaining = text_max - text_length;
						if(text_remaining < 0) {
							$("#payment").prop('disabled', true);
						} else {
							if($("#level").val()) {
								$("#payment").prop('disabled', false);
							} else {
								$("#payment").prop('disabled', true);
							}
						}
					}
				},
				error: function (file, response) {
					isUploaded = false;
					this.removeFile(file);
					alert(response);
					return false;
				}
			};
        $(document).ready(function() {

			//$('#textarea_feedback').html(text_max + ' characters remaining');

			$("#level").change(function() {
				if(isUploaded) {
						if($("#level").val()) {
								$("#payment").prop('disabled', false);
							} else {
								$("#payment").prop('disabled', true);
							}
					} else {
						$("#payment").prop('disabled', true);
					}

			});
			$('#textarea').keyup(function() {
				var text_length = $('#textarea').val().length;
				var text_remaining = text_max - text_length;
				if(text_remaining < 0) {
					$("#payment").prop('disabled', true);
				} else {
					if(isUploaded) {
						if($("#level").val()) {
								$("#payment").prop('disabled', false);
							} else {
								$("#payment").prop('disabled', true);
							}
					} else {
						$("#payment").prop('disabled', true);
					}
				}
				$('#textarea_feedback').html(text_remaining + ' characters remaining');
			});

        });
    </script>
@endpush
