<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">
                @lang('menus.backend.sidebar.general')
            </li>
            <li class="nav-item">
                <a class="nav-link {{
                    active_class(Route::is('admin/dashboard'))
                }}" href="{{ route('admin.dashboard') }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    @lang('menus.backend.sidebar.dashboard')
                </a>
            </li>

            @if ($logged_in_user->isAdmin())
                <li class="nav-title">
                    @lang('menus.backend.sidebar.system')
                </li>

                <li class="nav-item nav-dropdown {{
                    active_class(Route::is('admin/auth*'), 'open')
                }}">
                    <a class="nav-link nav-dropdown-toggle {{
                        active_class(Route::is('admin/auth*'))
                    }}" href="#">
                        <i class="nav-icon far fa-user"></i>
                        @lang('menus.backend.access.title')

                        @if ($pending_approval > 0)
                            <span class="badge badge-danger">{{ $pending_approval }}</span>
                        @endif
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{
                                active_class(Route::is('admin/auth/user*'))
                            }}" href="{{ route('admin.auth.user.index') }}">
                                @lang('labels.backend.access.users.management')

                                @if ($pending_approval > 0)
                                    <span class="badge badge-danger">{{ $pending_approval }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{
                                active_class(Route::is('admin/auth/role*'))
                            }}" href="{{ route('admin.auth.role.index') }}">
                                @lang('labels.backend.access.roles.management')
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="divider"></li>
            @endif
                    
            {{-- LIBRARIAN --}}
            @if($logged_in_user->hasRole('librarian' ) || $logged_in_user->isAdmin())

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/library/contributors*'))
                    }}" href="{{ route('admin.library.contributors') }}">
                        <i class="nav-icon fas fa-hand-holding-medical"></i>
                        Contributors
                    </a>
                </li>
				<li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/library/experts*'))
                    }}" href="{{ route('admin.library.experts') }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        Experts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/library/schedules*'))
                    }}" href="{{ route('admin.library.schedules') }}">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        Live Schedule
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/library/categories*'))
                    }}" href="{{ route('admin.library.categories') }}">
                        <i class="nav-icon fas fa-database"></i>
                        Categories
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/library/tags*'))
                    }}" href="{{ route('admin.library.tags') }}">
                        <i class="nav-icon fas fa-hashtag"></i>
                        Tags
                    </a>
                </li>
                
                {{--
                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/dashboard'))
                    }}" href="{{ route('admin.dashboard') }}">
                        <i class="nav-icon fas fa-diagnoses"></i>
                        Reviewers
                    </a>
                </li>
                --}}

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/library/playlists*'))
                    }}" href="{{ route('admin.library.playlists') }}">
                        <i class="nav-icon fas fa-film"></i>
                        Playlists
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/library/videos*'))
                    }}" href="{{ route('admin.library.videos') }}">
                        <i class="nav-icon fas fa-video"></i>
                        Videos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/library/videos*'))
                    }}" href="{{ route('admin.library.videosnothumb') }}">
                        <i class="nav-icon fas fa-video"></i>
                        Videos - No Thumb
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/library/reviews'))
                    }}" href="{{ route('admin.library.reviews') }}">
                        <i class="nav-icon fas fa-eye"></i>
                        Reviews
                    </a>
                </li>

                
                

            @endif


            {{-- ASSET MANAGER --}}
            @if($logged_in_user->hasRole('assetmanager' ) || $logged_in_user->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{
                        active_class(Route::is('admin/library/assets*'))
                    }}" href="{{ route('admin.library.assets') }}">
                        <i class="nav-icon fas fa-cubes"></i>
                        Assets
                    </a>
                </li>
            @endif


            <li class="nav-item">
                <a class="nav-link {{
                    active_class(Route::is('admin/library/cotd'))
                }}" href="{{ route('admin.library.cotd') }}">
                    <i class="nav-icon fas fa-clock"></i>
                    COTD schedule
                </a>
            </li>


            {{-- ORDERS --}}
            @if($logged_in_user->hasRole('accountant' ) || $logged_in_user->isAdmin())

                <li class="nav-item nav-dropdown {{
                    active_class(Route::is('admin/order*'), 'open')
                }}">
                    <a class="nav-link nav-dropdown-toggle {{
                            active_class(Route::is('admin/order*'))
                        }}" href="#">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i> Orders
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/order*'))
                        }}" href="{{ route('admin.order.summary') }}">
                            <i class="fas fa-calculator" style="margin-left: 12px; margin-right: 8px;"></i> Summary
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/order*'))
                        }}" href="{{ route('admin.order') }}">
                            <i class="fas fa-check-square" style="margin-left: 12px; margin-right: 8px;"></i> Done
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{
                            active_class(Route::is('admin/order*'))
                        }}" href="{{ route('admin.order.todo') }}">
                            <i class="fas fa-clipboard-list" style="margin-left: 12px; margin-right: 8px;"></i> Todo
                            </a>
                        </li>
                    </ul>
                </li>
            @endif


            {{-- LOG VIEWER --}}
            @if ($logged_in_user->isAdmin())
            <li class="nav-item nav-dropdown {{
                    active_class(Route::is('admin/log-viewer*'), 'open')
                }}">
                <a class="nav-link nav-dropdown-toggle {{
                        active_class(Route::is('admin/log-viewer*'))
                    }}" href="#">
                    <i class="nav-icon fas fa-list"></i> @lang('menus.backend.log-viewer.main')
                </a>

                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link {{
                        active_class(Route::is('admin/log-viewer'))
                    }}" href="{{ route('log-viewer::dashboard') }}">
                            @lang('menus.backend.log-viewer.dashboard')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                        active_class(Route::is('admin/log-viewer/logs*'))
                    }}" href="{{ route('log-viewer::logs.list') }}">
                            @lang('menus.backend.log-viewer.logs')
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </nav>

    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div><!--sidebar-->
