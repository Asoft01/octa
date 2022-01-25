<!DOCTYPE html>
@langrtl
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endlangrtl
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', app_name())</title>
        <meta name="description" content="@yield('meta_description', 'Agora.Community is a digital hub whose purpose is to entertain, inspire, educate and provide opportunities to be mentored by industry veterans.')">
        <meta name="author" content="@yield('meta_author', 'Agora.studio')">
        @yield('meta')

        @stack('before-styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
        {{ style(mix_cdn('css/frontend.css')) }}
        {{ style(mix_cdn('css/all.css')) }}
		<style>
			#video-page-title-gradient-base { left: 0; }
			#library-header > .container { margin-left: 0 !important; }
			#library-header > .container > #library-header-filtering { padding-left: 81px; }
		</style>
        <link rel="stylesheet" href="//fonts.googleapis.com/css2?family=Fira+Sans+Condensed:wght@300;400;500;700&family=Lato:wght@300;400;700&display=swap">

        @stack('after-styles')
        <link rel="apple-touch-icon" sizes="180x180" href="{{ config('ac.CDN_MEDIA') }}img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ config('ac.CDN_MEDIA') }}img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ config('ac.CDN_MEDIA') }}img/favicon-16x16.png">
        <link rel="mask-icon" href="{{ config('ac.CDN_MEDIA') }}img/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body class="{{ is_model(request()->domain) ? 'domain-' . (str_replace('.', '-', optional(request()->domain)->slug) ?: 'unknown') : 'no-domain' }}">

        @include('frontend.includes.nav')

        @yield('content')

        {{-- FOOTER --}}
        <footer id="footer-pro" style="margin-top: 50px;">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="copyright-text-pro">
                            <span style="float: right;">
                                <a class="text-white" style="margin-right: 2px;" href="https://www.instagram.com/agora.community/" target="_blank"><i class="fab fa-instagram fa-fw"></i></a>
                                <a class="text-white" style="margin-right: 2px;" href="https://www.facebook.com/Agoracommunity-106340684571716" target="_blank"><i class="fab fa-facebook fa-fw"></i></a>
                                <a class="text-white" style="margin-right: 4px;" href="https://www.linkedin.com/company/agora-community/" target="_blank"><i class="fab fa-linkedin fa-fw"></i></a>
                                <a class="text-white" style="margin-right: 5px;" href="https://open.spotify.com/show/6BdS6KzMuK3zRREXAdZ2La" target="_blank"><i class="fab fa-spotify"></i></a>
                                <a class="text-white" style="margin-right: 5px;" href="https://podcasts.apple.com/us/podcast/agora-community/id1582711329" target="_blank"><i class="fas fa-podcast"></i></a>
                                <a class="text-white" href="https://podcasts.google.com/feed/aHR0cHM6Ly9tZWRpYS5yc3MuY29tL2Fnb3JhY29tbXVuaXR5L2ZlZWQueG1s" target="_blank"><img src="{{ config('ac.CDN_MEDIA') }}{{ 'img/google_podcast.svg' }}" style="width: 14px; color: white; filter: invert(100%); vertical-align: sub;"></a>
                            </span>
                            <span>&copy; All rights reserved. Made with love by <a href="https://agora.studio">Agora.studio</a></span>
                            <span class="noselect px-2">|</span>
                            @guest
                                <a href="{{route('frontend.about')}}" style="color: #22b2ee;">What is Agora.community?</a>
                                <span class="noselect px-2">|</span>
                                <a href="{{route('frontend.live')}}" style="color: #22b2ee;">Live!</a>
                                <span class="noselect px-2">|</span>
                                <a href="{{route('frontend.contact')}}" style="color: #22b2ee;">Contact us</a>
                                <span class="noselect px-2">|</span>
                                <a href="{{ route('frontend.auth.login') }}" style="color: #22b2ee;" data-toggle="modal" data-target="#LoginModal">Sign in</a>
                            @else
                                <a href="{{route('frontend.contact')}}" style="color: #22b2ee;">Contact us</a>
                            @endguest
                        </div>
                    </div><!-- close .col -->
                    {{--<!--
						@if(auth()->check())
							<div class="col-md-6">
								<div class="copyright-text-pro float-right">
									<a href="{{ route('frontend.about') }}" style="padding-right: 8px;"><i class="far fa-question-circle" style="padding-right: 4px;"></i> What is agora.community</a>
									|
									<a href="{{ route('frontend.contact') }}" style="padding-left: 8px;"><i class="far fa-envelope" style="padding-right: 4px;"></i> Contact us</a>
								</div>
							</div>
						@endif
                    -->--}}
					{{--<!--
						<div class="col-md">
							<ul class="social-icons-pro">
								<li class="facebook-color"><a href="http://facebook.com/progressionstudios" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
								<li class="twitter-color"><a href="http://twitter.com/Progression_S" target="_blank"><i class="fab fa-twitter"></i></a></li>
								<li class="instagram-color"><a href="http://instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a></li>
								<li class="youtube-color"><a href="http://youtube.com" target="_blank"><i class="fab fa-youtube"></i></a></li>
								<li class="vimeo-color"><a href="http://vimeo.com" target="_blank"><i class="fab fa-vimeo-v"></i></a></li>
							</ul>
						</div>
                    -->--}}
				</div><!-- close .row -->
			</div><!-- close .container -->
		</footer>

        {{--<!-- Login Modal -->--}}
        @includeWhen(Auth::guest(), 'frontend.includes.login')

        <!-- Scripts -->
        @stack('before-scripts')
        {!! script(mix_cdn('js/manifest.js')) !!}
        {!! script(mix_cdn('js/vendor.js')) !!}
        {!! script(mix_cdn('js/frontend.js')) !!}
        {!! script(mix_cdn('js/all.js')) !!}
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2tag').select2({
                    placeholder: 'Search or select an option'
                });

                $(".select2tag").on("select2:select", function(e) {
                    var data = e.params.data;
                    window.location.href = '/tag/' + encodeURIComponent(data.text);
                });

                $('#main-text-field').keypress(function (e) {
                    if (e.which == 13) {
                        location.href = "/all?q=" + $(this).val();
                    }
                });

                $(".searchc").on("click", function() {
                    location.href = "/all?q=" + $("#main-text-field").val();
                });

				$('#LoginModal').on('show.bs.modal', function(e) {
					var reason = $(e.relatedTarget).attr('data-login-reason');
					var replace = (typeof reason === 'string') ? reason : '@lang("strings.frontend.login_reason")';
					$(this).find('#login-reason').html(replace);
				});
            });
        </script>
        @stack('after-scripts')

        @include('includes.partials.ga')
    </body>
</html>
