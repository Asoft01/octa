@extends('frontend.layouts.app')

@section('title', app_name() . ' | Library' )

@section('content')


	<div id="content-pro">
		
		<div class="container custom-gutters-pro">
			
			<div style="height:15px;"></div>
			
			
			

			<div class="row" id="library-header-filtering-padding">
				<div class="col col-12 col-md-3 col-lg-3" style="margin-right: 20px;">
					<div class="dotted-dividers-pro">
						<h4>Categories:</h4>
						<ul>
							<?php
							foreach($categories as $cat) {
								if(count($cat->contents)) {
							?>
							<li><a href="{{ route('frontend.category', $cat->title) }}"><?php echo $cat->title; ?></a></li>
							<?php } } ?>
						</ul>
						<div class="clearfix"></div>

					</div><!-- close .dotted-dividers-pro -->
				</div><!-- close .col -->


				<div class="col col-12 col-md-5 col-lg-5">
					{{--<div class="dotted-dividers-pro">--}}

					<h4 style="margin-bottom: 18px;">Quick search:</h4>
					{{ html()->select('tags', $alltags)
						->placeholder("")
						->style('width: 100%')
						->class('select2tag')
					}}

					<h4 style="margin-top: 24px;">Popular tags:</h4>
					<ul id="video-post-meta-list" style="margin-top: 12px;">
						@foreach($tags as $tag)
							<li id="video-post-meta-rating" style="padding-top: 8px;"><span><a href="{{ route('frontend.tag', urlencode($tag->title)) }}">{{ $tag->title }}</a></span></li>
						@endforeach
					</ul>
					</div><!-- close .dotted-dividers-pro -->
				</div><!-- close .col -->

				<div class="col col-12 col-md-4 col-lg-4">
					<h4>Advanced search:</h4>
					<input type="text" aria-label="Search" placeholder="Leave empty to search all contents" id="main-text-field" style="margin-top: 8px; color: white;">

					<button class="btn searchc" style="margin-top: -16px;">Search</button>
				</div><!-- close .col -->

{{--
				<div class="col col-12 col-md-4 col-lg-4">
					<h4>Search contents:</h4>
					<input type="text" placeholder="Tags, title, description, author..." aria-label="Search" id="main-text-field" style="margin-top: 8px;">

					<button class="btn" style="margin-top: -16px;">Search Videos</button>
				</div><!-- close .col -->
				--}}
			   
			</div><!-- close .row -->


			<div class="clearfix"></div>
							
		</div><!-- close .container -->

	</div><!-- close #content-pro -->

	{{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>

@endsection


@push('after-scripts')
@endpush