@guest
<header id="masthead-pro" class="landing-page-header" style="height: 72px !important;">
@else
<header id="masthead-pro" class="sticky-header">
@endguest
    <div class="header-container">
        
        @auth
            <h1><a href="{{ route('frontend.home') }}" id="ac-logo"><img style="max-width: 38px; margin: 0px; margin-left: 12px; margin-right: 12px; margin-top: 20px;" src="{{ config('ac.CDN_MEDIA') }}img/agora_community_logo.png"></a></h1>
        @else
            <h1><a href="{{ route('frontend.index') }}" id="ac-logo"><img style="max-width: 38px; margin: 0px; margin-left: 12px; margin-right: 12px; margin-top: 20px;" src="{{ config('ac.CDN_MEDIA') }}img/agora_community_logo.png"></a></h1>
        @endif
        

        @auth
            <div id="header-user-profile">
                <div id="header-user-profile-click" class="noselect">
                    {{--<img src="https://via.placeholder.com/80x80">--}}
                    <div id="header-username">{{ $logged_in_user->first_name }} {{ $logged_in_user->last_name }}</div><i class="fas fa-angle-down"></i>
                </div><!-- close #header-user-profile-click -->
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
                            <li><a href="{{ route('frontend.user.expertwiki') }}"><i class="fa fa-book-reader"></i>Wiki</a></li>
                        @endif
                        @if(auth()->user()->can('view backend'))
							<li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-user-shield"></i>Administration</a></li>
                        @endif
                        <li><a href="https://www.instagram.com/agora.community/"><i class="fab fa-instagram"></i>Instagram</a></li>
                        <li><a href="https://www.facebook.com/Agoracommunity-106340684571716"><i class="fab fa-facebook"></i>Facebook</a></li>
                        <li><a href="https://www.linkedin.com/company/agora-community/"><i class="fab fa-linkedin"></i>Linkedin</a></li>
                        <li><a href="{{ route('frontend.auth.logout') }}"><i class="fa fa-power-off"></i>Log Out</a></li>
                    </ul>
                </div><!-- close #header-user-profile-menu -->
            </div><!-- close #header-user-profile -->
        @endauth
        
        <nav id="site-navigation-pro">
            @guest
                <ul class="sf-menu">
                    <li class="normal-item-pro {{ active_class(Route::is('frontend.about')) }}">
                        <a href="{{route('frontend.about')}}" class="nav-link {{ active_class(Route::is('frontend.about')) }}"><i class="fas fa-question-circle"></i>What is agora.community?</a>
                    </li>
                    <li class="normal-item-pro {{ active_class(Route::is('frontend.live')) }}">
                        <a href="{{route('frontend.live')}}" class="nav-link {{ active_class(Route::is('frontend.live')) }}"><i class="fas fa-camera{{ $isStreaming ? ' text-danger' : '' }}"></i>Live!</a>
                    </li>
                    <li class="normal-item-pro {{ active_class(Route::is('frontend.contact')) }}">
                        <a href="{{route('frontend.contact')}}" class="nav-link {{ active_class(Route::is('frontend.contact')) }}"><i class="fas fa-envelope"></i>Contact us</a>
                    </li>
                    <li class="normal-item-pro {{ active_class(Route::is('frontend.contact')) }}">
                        <a href="{{ route('frontend.auth.login') }}" data-toggle="modal" data-target="#LoginModal"><i class="fas fa-sign-in-alt"></i> @lang('navs.frontend.login')</a>
                    </li>
                </ul>
            @else
            <ul class="sf-menu">
                <li class="normal-item-pro {{ active_class(Route::is('frontend.learn')) }}">
                    <a href="{{route('frontend.learn')}}" id="ac-library-menu" class="nav-link {{ active_class(Route::is('frontend.learn')) }}"><i class="fas fa-desktop"></i>Library</a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is('frontend.assets')) }}">
                    <a href="{{route('frontend.assets')}}" id="ac-asset-menu" class="nav-link {{ active_class(Route::is('frontend.assets')) }}"><i class="fas fa-cubes"></i>@lang('navs.frontend.assets')</a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is('frontend.live')) }}">
                    <a href="{{route('frontend.live')}}" id="ac-live-menu" class="nav-link {{ active_class(Route::is('frontend.live')) }}"><i class="fas fa-camera{{ $isStreaming ? ' text-danger' : '' }}"></i>Live!</a>
                </li>
                <li class="normal-item-pro">
                    <?php
                        $discord_online_users = Cache::remember('discordusers', 14400, function () {
                            $raw_widget = file_get_contents("https://discordapp.com/api/servers/" .  config('ac.DISCORD_SERVER_ID') . "/widget.json");
                            $discord_count = json_decode($raw_widget);
                            if(isset($discord_count->presence_count)) {
                                return $discord_count->presence_count;
                            } else {
                                return 346; //random number... refacto to remove the count
                            }
                        });
                    ?>
                    <a href="{{route('frontend.discord')}}" class="nav-link"><i class="fab fa-discord"></i>Discord <span style="padding: 2px; font-size: 12px; margin-left: 2px;">[{{ $discord_online_users }} online]</span></a>
                    
                </li>
                <li class="normal-item-pro {{ active_class(Route::is(['frontend.mentors', 'frontend.user.reviewer'])) }}">
                    <a href="{{route('frontend.mentors')}}" class="nav-link {{ active_class(Route::is('frontend.mentors')) }}"><i class="fas fa-users"></i>Our experts</a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is('frontend.user.order*')) }}">
                    <a href="{{route('frontend.user.order')}}" class="nav-link {{ active_class(Route::is('frontend.user.order*')) }}" style="color: #22b2ee">
                        <i class="fas fa-shopping-cart"></i> Order a review
                    </a>
                </li>
                <li class="normal-item-pro {{ active_class(Route::is('frontend.about')) }}">
                    <a href="{{route('frontend.about')}}" class="nav-link {{ active_class(Route::is('frontend.about')) }}"><i class="fas fa-question-circle"></i>About</a>
                </li>
                {{--
                <li class="normal-item-pro {{ active_class(Route::is('frontend.contact')) }}">
                    <a href="{{route('frontend.contact')}}" class="nav-link {{ active_class(Route::is('frontend.contact')) }}"><i class="fas fa-envelope"></i>Contact us</a>
                </li>        
                --}}                    
            </ul>
            
            @endauth
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
                                <div class="dotted-dividers-pro">

                                    <h4 style="margin-bottom: 18px;">Quick search:</h4>
                                    {{ html()->select('tags', $alltags)
                                        ->placeholder("")
                                        ->class('select2tag')
                                    }}

                                    <h4 style="margin-top: 24px;">Popular tags:</h4>
                                    <ul id="video-post-meta-list" style="margin-top: 12px;">
                                        @foreach($tags as $tag)
                                            <li id="video-post-meta-rating" style="padding-top: 8px;"><span><a href="{{ route('frontend.tag', urlencode(mb_strtolower($tag->title))) }}">{{ $tag->title }}</a></span></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div><!-- close .col -->


                            <div class="col col-12 col-md-4 col-lg-4">
                                <h4>Advanced search:</h4>
                                <input type="text" aria-label="Search" placeholder="Leave empty to search all contents" id="main-text-field" style="margin-top: 8px;">

                                <button class="btn searchc" style="margin-top: -16px;">Search</button>
                            </div><!-- close .col -->
                            
                           
                        </div><!-- close .row -->
                        
                    
                </div>
            </div>
        </div>
        

        <div class="clearfix"></div>
    </div><!-- close .header-container -->
    
    <nav id="mobile-navigation-pro">
        @auth
        <ul id="mobile-menu-pro">
            <li>
                <a href="{{route('frontend.learn')}}" class="nav-link {{ active_class(Route::is('frontend.learn')) }}"><i class="fas fa-desktop"></i>Library</a>
            </li>
            <li>
                <a href="{{route('frontend.assets')}}" class="nav-link {{ active_class(Route::is('frontend.assets')) }}"><i class="fas fa-cubes"></i>@lang('navs.frontend.assets')</a>
            </li>
            <li>
                <a href="{{route('frontend.live')}}" class="nav-link {{ active_class(Route::is('frontend.live')) }}"><i class="fas fa-camera{{ $isStreaming ? ' text-danger' : '' }}"></i>Live!</a>
            </li>
            <li>
                <a href="{{route('frontend.user.favorites')}}" class="nav-link {{ active_class(Route::is('frontend.user.favorites')) }}"><i class="fas fa-star"></i>Favorites</a>
            </li>
            <li>
                <a href="{{route('frontend.user.watchlist')}}" class="nav-link {{ active_class(Route::is('frontend.user.watchlist')) }}"><i class="fas fa-clock"></i>Watchlist</a>
            </li>
            <li>
                <a href="{{route('frontend.discord')}}" class="nav-link {{ active_class(Route::is('frontend.discord*')) }}"><i class="fab fa-discord"></i>Discord</a>
            </li>
            <li>
                <a href="{{route('frontend.mentors')}}" class="nav-link {{ active_class(Route::is(['frontend.mentors', 'frontend.user.reviewer'])) }}"><i class="fas fa-users"></i>Our experts</a>
            </li>
            <li>
                <a href="{{route('frontend.user.order')}}" class="nav-link {{ active_class(Route::is('frontend.user.order*')) }}"><i class="fas fa-shopping-cart"></i>Order a review</a>
            </li>
            <li>
                <a href="{{route('frontend.about')}}" class="nav-link {{ active_class(Route::is('frontend.about')) }}"><i class="fas fa-question-circle"></i>About</a>
            </li>
            {{--
            <li>
                <a href="{{route('frontend.contact')}}" class="nav-link {{ active_class(Route::is('frontend.contact')) }}"><i class="fas fa-envelope"></i>Contact us</a>
            </li>
            --}}            
            <li>
                <a href="{{route('frontend.search')}}" class="nav-link {{ active_class(Route::is('frontend.search*')) }}"><i class="fas fa-search"></i>Search</a>
            </li>
        </ul>
        @else

            <a href="{{ route('frontend.auth.login') }}" data-toggle="modal" data-target="#LoginModal"><button class="btn btn-mobile-pro btn-header-pro noselect">Login</button></a>
        
        @endauth
    </nav>
</header>
