<header id="masthead-pro" class="sticky-header">
    <div class="header-container">
        <h1>
            <a href="{{ route('frontend.home') }}" id="ac-logo">
                <img style="max-width: 38px; margin: 0px; margin-left: 12px; margin-right: 12px; margin-top: 20px;" src="{{ config('ac.CDN_MEDIA') }}img/agora_community_logo.png">
            </a>
        </h1>
        @auth
            <div id="header-user-profile">
                <div id="header-user-profile-click" class="noselect">
                    {{--<!--
                        <img src="https://via.placeholder.com/80x80">
                    -->--}}
                    <div id="header-username">{{ $logged_in_user->first_name }} {{ $logged_in_user->last_name }}</div><i class="fas fa-angle-down"></i>
                </div>
                <div id="header-user-profile-menu">
                    <ul>
                        <li><a href="{{ route('frontend.user.account') }}"><i class="fa fa-user-circle"></i>My Profile</a></li>
						@if(auth()->user()->account != null)
                            <li><a href="{{ route('frontend.user.publicinfo.edit') }}"><i class="far fa-calendar-alt"></i>My Public Info</a></li>
                        @endif
                        <li><a href="{{ route('frontend.user.favorites') }}"><i class="fa fa-star"></i>My Favorites</a></li>
                        <li><a href="{{ route('frontend.user.watchlist') }}"><i class="fa fa-clock"></i>My Watchlist</a></li>
                        <li><a href="{{ route('frontend.user.manage') }}"><i class="fas fa-cogs"></i>Manage my reviews</a></li>
                        @if(auth()->user()->hasRole('mentor'))
                            <li><a href="https://sites.google.com/agora.studio/agora-community/reviews" target="_blank"><i class="fa fa-book-reader"></i>Wiki</a></li>
                        @endif
                        @if(auth()->user()->can('view backend'))
							<li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-user-shield"></i>Administration</a></li>
                        @endif
                        <li><a href="https://www.instagram.com/agora.community/" target="_blank"><i class="fab fa-instagram"></i>Instagram</a></li>
                        <li><a href="https://www.facebook.com/Agoracommunity-106340684571716" target="_blank"><i class="fab fa-facebook"></i>Facebook</a></li>
                        <li><a href="https://www.linkedin.com/company/agora-community/" target="_blank"><i class="fab fa-linkedin"></i>Linkedin</a></li>
                        <li><a href="https://open.spotify.com/show/6BdS6KzMuK3zRREXAdZ2La" target="_blank"><i class="fab fa-spotify"></i>Spotify podcast</a>
                        <li><a href="https://podcasts.apple.com/us/podcast/agora-community/id1582711329" target="_blank"><i class="fas fa-podcast"></i>Apple podcast</a>
                        <li><a href="https://podcasts.google.com/feed/aHR0cHM6Ly9tZWRpYS5yc3MuY29tL2Fnb3JhY29tbXVuaXR5L2ZlZWQueG1s" target="_blank"><img src="{{ config('ac.CDN_MEDIA') }}{{ 'img/google_podcast.svg' }}" style="width: 12px; margin-left: 5px; color: white; filter: invert(100%) opacity(0.6); vertical-align: sub">Google podcast</a></li>
                        <li><a href="{{ route('frontend.auth.logout') }}"><i class="fa fa-power-off"></i>Log Out</a></li>
                    </ul>
                </div>
            </div>
        @endauth
        <nav id="site-navigation-pro">
            <ul class="sf-menu">
                <li class="normal-item-pro {{ active_class(Route::is('frontend.learn')) }}">
                    <a href="{{ route('frontend.learn') }}" id="ac-library-menu" class="nav-link {{ active_class(Route::is('frontend.learn')) }}"><i class="fas fa-desktop"></i>Library</a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is('frontend.assets')) }}">
                    <a href="{{ route('frontend.assets') }}" id="ac-asset-menu" class="nav-link {{ active_class(Route::is('frontend.assets')) }}"><i class="fas fa-cubes"></i>@lang('navs.frontend.assets')</a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is('frontend.live')) }}">
                    <a href="{{ route('frontend.live') }}" id="ac-live-menu" class="nav-link {{ active_class(Route::is('frontend.live')) }}"><i class="fas fa-camera{{ $isStreaming ? ' text-danger' : '' }}"></i>Live!</a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is('frontend.discord*')) }}">
                    @php
                        $discord_online_users = Cache::remember('discordusers', 14400, function() {
                            $raw_widget = file_get_contents('https://discordapp.com/api/servers/' . config('ac.DISCORD_SERVER_ID') . '/widget.json');
                            $discord_count = json_decode($raw_widget);
                            return isset($discord_count->presence_count) ? $discord_count->presence_count : 346;
                            // 346 is a random number... refacto to remove the count
                        });
                    @endphp
                    <a href="{{ route('frontend.discord') }}" class="nav-link {{ active_class(Route::is('frontend.discord*')) }}"><i class="fab fa-discord"></i>Discord <small style="font-size: 12px; font-weight: inherit; margin-left: 2px; padding: 2px;">[{{ $discord_online_users }} online]</small></a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is(['frontend.mentors', 'frontend.contributor', 'frontend.reviewer'])) }}">
                    <a href="{{ route('frontend.mentors') }}" class="nav-link {{ active_class(Route::is(['frontend.mentors', 'frontend.contributor', 'frontend.reviewer'])) }}"><i class="fas fa-users"></i>Our experts</a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is('frontend.user.order*')) }}">
                    <a href="{{ route('frontend.user.order') }}" class="nav-link {{ active_class(Route::is('frontend.user.order*')) }}" style="color: #22b2ee;"@guest data-toggle="modal" data-target="#LoginModal" @endguest><i class="fas fa-shopping-cart"></i>Order a review</a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is('frontend.about')) }}">
                    <a href="{{ route('frontend.about') }}" class="nav-link {{ active_class(Route::is('frontend.about')) }}"><i class="fas fa-question-circle"></i>About</a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is('frontend.challenge')) }}">
                    <a href="{{ route('frontend.challenge') }}" class="nav-link {{ active_class(Route::is('frontend.challenge')) }}"><i class="fas fa-question-circle"></i>Anime Challenge</a>
                </li>
                @guest
                    <li class="normal-item-pro {{ active_class(Route::is('frontend.auth.login')) }}">
                        <a href="{{ route('frontend.auth.login') }}" class="nav-link {{ active_class(Route::is('frontend.auth.login')) }}" data-toggle="modal" data-target="#LoginModal" data-login-reason=""><i class="fas fa-sign-in-alt"></i>@lang('navs.frontend.login')</a>
                    </li>
                @endguest
            </ul>
        </nav>
        <div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>
        <div id="library-header">
            <div class="container" style="margin-left: 81px;">
                <div id="library-header-filtering">
                    <div class="row" id="library-header-filtering-padding">
                        <div class="col col-12 col-md-3 col-lg-3">
                            <div class="dotted-dividers-pro">
                                <h4>Categories:</h4>
                                <ul>
                                    @foreach($categories as $cat)
                                        @if(count($cat->contents))
                                            <li><a href="{{ route('frontend.category', $cat->title) }}">{{ $cat->title }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col col-12 col-md-5 col-lg-5">
                            <div class="dotted-dividers-pro">
                                <h4 style="margin-bottom: 18px;">Quick search:</h4>
                                {{ html()->select('tags', $alltags)->class('select2tag')->placeholder('') }}
                                <h4 style="margin-top: 24px;">Popular tags:</h4>
                                <ul id="video-post-meta-list" style="margin-top: 12px;"> 
                                    @foreach($tags as $tag)
                                        <li id="video-post-meta-rating" style="padding-top: 8px;"><span><a href="{{ route('frontend.tag', urlencode(mb_strtolower($tag->title))) }}">{{ $tag->title }}</a></span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col col-12 col-md-4 col-lg-4">
                            <h4>Advanced search:</h4>
                            <input type="text" aria-label="Search" placeholder="Leave empty to search all contents" id="main-text-field" style="margin-top: 8px;">
                            <button class="btn searchc" style="margin-top: -16px;">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <nav id="mobile-navigation-pro">
        <ul id="mobile-menu-pro">
            <li><a href="{{ route('frontend.learn') }}" class="nav-link {{ active_class(Route::is('frontend.learn')) }}"><i class="fas fa-desktop"></i>Library</a></li>
            <li><a href="{{ route('frontend.assets') }}" class="nav-link {{ active_class(Route::is('frontend.assets')) }}"><i class="fas fa-cubes"></i>@lang('navs.frontend.assets')</a></li>
            <li><a href="{{ route('frontend.live') }}" class="nav-link {{ active_class(Route::is('frontend.live')) }}"><i class="fas fa-camera{{ $isStreaming ? ' text-danger' : '' }}"></i>Live!</a></li>
            {{--<!--
                <li><a href="{{ route('frontend.user.favorites') }}" class="nav-link {{ active_class(Route::is('frontend.user.favorites')) }}"><i class="fas fa-star"></i>Favorites</a></li>
                <li><a href="{{ route('frontend.user.watchlist') }}" class="nav-link {{ active_class(Route::is('frontend.user.watchlist')) }}"><i class="fas fa-clock"></i>Watchlist</a></li>
            -->--}}
            <li><a href="{{ route('frontend.discord') }}" class="nav-link {{ active_class(Route::is('frontend.discord*')) }}"><i class="fab fa-discord"></i>Discord</a></li>
            <li><a href="{{ route('frontend.mentors') }}" class="nav-link {{ active_class(Route::is(['frontend.mentors', 'frontend.contributor', 'frontend.reviewer'])) }}"><i class="fas fa-users"></i>Our experts</a></li>
            <li><a href="{{ route('frontend.user.order') }}" class="nav-link {{ active_class(Route::is('frontend.user.order*')) }}" style="color: #22b2ee;"@guest data-toggle="modal" data-target="#LoginModal" @endguest><i class="fas fa-shopping-cart"></i>Order a review</a></li>
            <li><a href="{{ route('frontend.about') }}" class="nav-link {{ active_class(Route::is('frontend.about')) }}"><i class="fas fa-question-circle"></i>About</a></li>   
            <li><a href="{{ route('frontend.challenge') }}" class="nav-link {{ active_class(Route::is('frontend.challenge')) }}"><i class="fas fa-question-circle"></i>Anime Challenge</a></li>          
            <li><a href="{{ route('frontend.search') }}" class="nav-link {{ active_class(Route::is('frontend.search*')) }}"><i class="fas fa-search"></i>Search</a></li>
        </ul>
        @guest
            <a href="{{ route('frontend.auth.login') }}" data-toggle="modal" data-target="#LoginModal" data-login-reason="" class="btn btn-mobile-pro btn-header-pro noselect">Login</a>
        @endguest
    </nav>
</header>
