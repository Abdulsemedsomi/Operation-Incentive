<!doctype html>
<html lang="{{ config('app.locale') }}" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>IE Networks</title>

        <meta name="description" content="">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta http-equiv="pragma" content="no-cache" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Icons -->
        <link rel="shortcut icon" href="{{url('images/logos.png')}}"> 
        <link rel="icon" sizes="192x192" type="image/png" href="{{url('images/logos.png')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{url('images/logos.png')}}">
      
        <link href='{{ asset('css/dropdown.css')}}' rel='stylesheet' type='text/css'>
        <!-- Fonts and Styles -->
        <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
        <!-- Csutom styles  -->

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/jquery.mentiony.css') }}" />
        
        
        <style>
            #listt:hover {
            background-color: #b3cce6;
            }
            #listtt:hover{
                background-color: #ffffcc;
            }
        </style>

        <!-- Bootstrap core CSS -->
        <link href="https://emoji-css.afeld.me/emoji.css" rel="stylesheet">
        {{-- <script src="{{ asset('js/main.js') }}" defer></script> --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        

        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>


        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
        <script src="https://cdn.tiny.cloud/1/30zbqdsg0qxnt5lpry8qyz03n6n1i5v9bo49t217uxdtu34t/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        <link rel="stylesheet"  href="{{ asset('css/style.css?ver=2.4') }}" >
        <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
        <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" />-->

        @yield('css_before')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700">
        <link rel="stylesheet" id="css-main" href="{{ asset('/css/codebase.css') }}">

        <!-- You can include a specific file from public/css/themes/ folder to alter the default color theme of the template. eg: -->
       {{-- <link rel="stylesheet" id="css-theme" href="{{ asset('/css/themes/corporate.css') }}">  --}}
        @yield('css_after')
        <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

        <!-- Scripts -->
        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>
    </head>
    <body>

        <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-modern main-content-boxed sidebar-inverse">

            <nav id="sidebar">
                <!-- Sidebar Content -->
                <div class="sidebar-content">
                    <!-- Side Header -->
                    <div class="content-header content-header-fullrow px-15">
                        <!-- Mini Mode -->
                        <div class="content-header-section sidebar-mini-visible-b">
                            <!-- Logo -->
                            <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                                <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                            </span>
                            <!-- END Logo -->
                        </div>
                        <!-- END Mini Mode -->

                        <!-- Normal Mode -->
                        <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                            <!-- Close Sidebar, Visible only on mobile screens -->
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                            <!-- END Close Sidebar -->

                            <!-- Logo -->
                            <div class="content-header-item">
                                <a class="link-effect font-w700" href="{{ url('home') }}">
                                    <img class="img-avatar" src="{{url('images/logos.png')}}" alt="">
                                    <span class="font-size-xl text-dual-primary-dark">PMS</span><span class="font-size-xl text-primary"></span>
                                </a>
                            </div>
                            <!-- END Logo -->
                        </div>
                        <!-- END Normal Mode -->
                    </div>
                    <!-- END Side Header -->

                    <!-- Side User -->
                    <div class="content-side content-side-full content-side-user px-10 align-parent">
                        <!-- Visible only in mini mode -->
                        <div class="sidebar-mini-visible-b align-v animated fadeIn">
                            <img class="img-avatar img-avatar32" src="{{ asset('media/avatars/avatar15.jpg') }}" alt="">
                        </div>
                        <!-- END Visible only in mini mode -->

                        <!-- Visible only in normal mode -->
                        <div class="sidebar-mini-hidden-b text-center">
                            <div class="img-link" href="" data-toggle="modal" data-target="#modal-normal">
                                @if(Auth::user()->avatar == null)
                                    <div class="aacircle" style=" --avatar-size: 3rem; background-color: #{{Auth::user()->avatarcolor}} !important;">
                                        <span class="aainitials" >{{Auth::user()->fname[0] . Auth::user()->lname[0]}}</span>
                                    </div>
                                @else
                                    <img src="https://ienetworks.co/pms/uploads/avatars/{{ Auth::user()->avatar }}" style="width:3rem; height:3rem; border-radius:50%;">
                                @endif
                            </div>
                            <ul class="list-inline mt-10">
                                <li class="list-inline-item">
                                    <a class="link-effect text-dual-primary-dark font-size-sm font-w600 text-uppercase" href="" data-toggle="modal" data-target="#modal-normal">{{Auth::user()->fname}} {{Auth::user()->lname}}</a>
                                </li>
                                <li class="list-inline-item">
                                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                    <a class="link-effect text-dual-primary-dark" data-toggle="layout" data-action="sidebar_style_inverse_toggle" href="javascript:void(0)">
                                        <i class="si si-drop"></i>
                                    </a>
                                </li>
                                <br>
                            </ul>


                        </div>
                        <!-- END Visible only in normal mode -->
                    </div>
                    <!-- END Side User -->

                    <!-- Side Navigation -->
                    <div class="content-side content-side-full">
                        <ul class="nav-main">
                            <li>
                                <a href="{{ url('home') }}">
                                    <i class="si si-home text-info"></i><span class="sidebar-mini-hide">Home</span>
                                </a>
                            </li>

                            <li class="{{ request()->is('pages/*') ? ' open' : '' }}">
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-bulb text-info"></i><span class="sidebar-mini-hide">OKR</span></a>
                                <ul>
                                <?php
                                        $session = App\Session::where('status', 'Active')->first();
                                        ?>
                                   @if($session)
                                   <li>
                                        <a class="" href="{{ route('okr', $session->id)}}">{{$session->session_name}}</a>
                                        </li>
                                    @endif
                                        <li>
                                            <a href="{{ url('sessions') }}">Manage Sessions</a>
                                        </li>
                                </ul>
                            </li>
                            <li class="{{ request()->is('pages/*') ? ' open' : '' }}">
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-cup text-info"></i><span class="sidebar-mini-hide">Teams</span></a>
                                <ul>

                                <?php
                                    $message = "#";
                                    $team = App\Team::where('isActive', 1)->get();
                                    $driver = App\Team::where('team_name', "Drivers")->first();
                                    $teams = App\Teammember::where('user_id', Auth::user()->id)->where('teams.isActive', 1)->join('teams', 'teams.id', '=','teammembers.team_id')->orderby('team_name', 'asc')->select('teams.id', 'team_name')->get();
                                // $team = App\Team::where('parentteam', Auth::user()->team)->get();
                                // $allteams = App\Team::all();
                                // $teamid = App\Team::where('team_name', Auth::user()->team)->first()? App\Team::where('team_name', Auth::user()->team)->first()->id:0;
                                ?>
                                
                                @if(Gate::any(['newrole']))
                                    @foreach($teams as $t)

                                        <li>
                                            <a  href="{{ route('myteams', $t->id) }}" onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); });">{{$t->team_name}}</a>
                                        </li>

                                    @endforeach
                                @else
                                         @foreach($teams as $at)
                                             @if($at->team_name != "Drivers")
                                              
                                            
                                                <li>
                                                   
                                                    <a  href="{{ route('checkin', $at->id) }}">{{$at->team_name}}</a>
                                                  
                                                   
                                                </li>
                                            @endif
                                       

                                        @endforeach
                                        
                                @endif
                                @if(Auth::user()->id == 153 || Auth::user()->id == 191 || Auth::user()->id == 194)
                                  <li>
                                     <a  href="{{ route('drivers', $driver->id) }}">{{$driver->team_name}}</a>
                                </li>
                                @endif
                                       
                                </ul>
                            </li>
                            <li class="{{ request()->is('pages/*') ? ' open' : '' }}">
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-docs text-info"></i><span class="sidebar-mini-hide">General Reports</span></a>
                                <ul>


                                        <li>
                                            <a href="{{ route('showengagement') }}">
                                               <span class="sidebar-mini-hide">Engagement Report</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('kpireport') }}">
                                               <span class="sidebar-mini-hide">KPI Report</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('taskreportdisplay') }}">
                                               <span class="sidebar-mini-hide">Task Report</span>
                                            </a>
                                        </li>
                                       
                                        
                                </ul>
                            </li>
                          
                             <li>
                                <a href="{{ route('myprojects.index') }}">
                                    <i class="si si-briefcase text-info"></i><span class="sidebar-mini-hide">Projects</span>
                                </a>
                            </li>
                             @if(Gate::none(['otherhr', 'crud', 'visualization']))
                                          <li>
                                            <a href="{{ route('incentive') }}">
                                                <i class="si si-chart text-info"></i><span class="sidebar-mini-hide"> Incentive</span>
                                            </a>
                                        </li>
                            @endif

                           
                                 @if(Gate::any(['otherhr', 'visualization', 'manageprojects', 'kpimodule', 'engagement']))
                             
                            <li class="nav-main-heading">
                                <span class="sidebar-mini-visible">MR</span><span class="sidebar-mini-hidden">Management</span>
                            </li>
                            <li>

                                @if(Gate::any(['otherhr', 'kpimodule']))
                                <a href="{{ route('kpis.index') }}">
                                    <i class="si si-chart text-success"></i><span class="sidebar-mini-hide"> KPI</span>
                                </a>
                                @endif
                                @if(Gate::any(['otherhr', 'engagement']))
                                <a href="{{ url('engagement') }}">
                                    <i class="si si-note text-success"></i><span class="sidebar-mini-hide">Engagement</span>
                                </a>
                                 @endif
                                @if(Gate::any(['otherhr', 'manageprojects']))
                                   <a href="{{ route('projects.index') }}">
                                    <i class="si si-briefcase text-success"></i><span class="sidebar-mini-hide">Manage Projects</span>
                                </a>
                                 @endif
                                  @if(Gate::any(['otherhr', 'manageprojects']) || Auth::user()->id == 144)
                                   <a href="{{ route('bids.index') }}">
                                    <i class="si si-credit-card text-success"></i><span class="sidebar-mini-hide">Manage Bids</span>
                                </a>
                                 @endif
                                  @if(Gate::any(['otherhr', 'failure']))
                                   <a href="{{ route('failures.index') }}">
                                    <i class="si si-pie-chart text-success"></i><span class="sidebar-mini-hide">Failure targets</span>
                                </a>
                                 @endif
                                @if(Gate::any(['otherhr', 'visualization']))
                                <a class="{{ request()->is('dashboard') ? ' active' : '' }}" href="{{ url('visualization') }}">
                                    <i class="si si-graph text-success"></i><span class="sidebar-mini-hide">Visualization</span>
                                </a>
                                 @endif
                                 @if(Gate::any(['otherhr', 'crud', 'visualization']))
                                <a href="{{ route('incentive') }}">
                                    <i class="si si-chart text-success"></i><span class="sidebar-mini-hide"> Incentive</span>
                                </a>
                                @endif

                            </li>
                            @endif
                             @if(Gate::any(['crud']) || Auth::user()->id == 144 || Auth::user()->id == 187)
                            <li>
                                <a href="{{ route('companytargets.index') }}">
                                    <i class="si si-target text-success"></i><span class="sidebar-mini-hide"> Company Target</span>
                                </a>
                            </li>
                            @endif
                            @if (Auth::user()->id==1 || Auth::user()->id==170)
                            <li>
                                <a href="{{ url('cfrpage') }}">
                                    <i class="fa fa-handshake-o text-success"></i><span class="sidebar-mini-hide">CFR</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <!-- END Side Navigation -->
                </div>
                <!-- Sidebar Content -->
            </nav>
            <!-- END Sidebar -->

            <!-- Header -->
            <header id="page-header">
                <!-- Header Content -->
                <div class="content-header">
                    <!--<div class="alert alert-danger alert-dismissible fade show mb-5 col-md-10 text-center " ><strong>System under maintenance! Please come back later</strong></div>-->
                    <!-- Left Section -->
                    <div class="content-header-section">
                        <!-- Toggle Sidebar -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
                            <i class="fa fa-navicon"></i>
                        </button>
                        <!-- END Toggle Sidebar -->

                        <!-- Open Search Section -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        {{-- <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="header_search_on">
                            <i class="fa fa-search"></i>
                        </button> --}}
                        <!-- END Open Search Section -->

                        <!-- Layout Options (used just for demonstration) -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <div class="btn-group" role="group">

                            <div class="dropdown-menu min-width-300" aria-labelledby="page-header-options-dropdown">
                                <h5 class="h6 text-center py-10 mb-10 border-b text-uppercase">Settings</h5>
                                <h6 class="dropdown-header">Color Themes</h6>
                                <div class="row no-gutters text-center mb-5">
                                    <div class="col-2 mb-5">
                                        <a class="text-default" data-toggle="theme" data-theme="default" href="javascript:void(0)">
                                            <i class="fa fa-2x fa-circle"></i>
                                        </a>
                                    </div>
                                    <div class="col-2 mb-5">
                                        <a class="text-elegance" data-toggle="theme" data-theme="{{ mix('/css/themes/elegance.css') }}" href="javascript:void(0)">
                                            <i class="fa fa-2x fa-circle"></i>
                                        </a>
                                    </div>
                                    <div class="col-2 mb-5">
                                        <a class="text-pulse" data-toggle="theme" data-theme="{{ mix('/css/themes/pulse.css') }}" href="javascript:void(0)">
                                            <i class="fa fa-2x fa-circle"></i>
                                        </a>
                                    </div>
                                    <div class="col-2 mb-5">
                                        <a class="text-flat" data-toggle="theme" data-theme="{{ mix('/css/themes/flat.css') }}" href="javascript:void(0)">
                                            <i class="fa fa-2x fa-circle"></i>
                                        </a>
                                    </div>
                                    <div class="col-2 mb-5">
                                        <a class="text-corporate" data-toggle="theme" data-theme="{{ mix('/css/themes/corporate.css') }}" href="javascript:void(0)">
                                            <i class="fa fa-2x fa-circle"></i>
                                        </a>
                                    </div>
                                    <div class="col-2 mb-5">
                                        <a class="text-earth" data-toggle="theme" data-theme="{{ mix('/css/themes/earth.css') }}" href="javascript:void(0)">
                                            <i class="fa fa-2x fa-circle"></i>
                                        </a>
                                    </div>
                                </div>
                                <h6 class="dropdown-header">Header</h6>
                                <div class="row gutters-tiny text-center mb-5">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-sm btn-block btn-alt-secondary" data-toggle="layout" data-action="header_fixed_toggle">Fixed Mode</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-sm btn-block btn-alt-secondary d-none d-lg-block mb-10" data-toggle="layout" data-action="header_style_classic">Classic Style</button>
                                    </div>
                                </div>
                                <h6 class="dropdown-header">Sidebar</h6>
                                <div class="row gutters-tiny text-center mb-5">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10" data-toggle="layout" data-action="sidebar_style_inverse_off">Light</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10" data-toggle="layout" data-action="sidebar_style_inverse_on">Dark</button>
                                    </div>
                                </div>
                                <div class="d-none d-xl-block">
                                    <h6 class="dropdown-header">Main Content</h6>
                                    <button type="button" class="btn btn-sm btn-block btn-alt-secondary mb-10" data-toggle="layout" data-action="content_layout_toggle">Toggle Layout</button>
                                </div>
                            </div>
                        </div>
                        <!-- END Layout Options -->
                    </div>
                    <!-- END Left Section -->

                    <!-- Right Section -->
                    <div class="content-header-section">
                         <!-- Notifications -->
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-flag"></i>
                                <span id="counter" class="badge badge-danger badge-pill">
                                {{count(Auth::user()->unreadNotifications)}}
                                 </span>
                            </button>
                            <div id="notiGui" class="dropdown-menu dropdown-menu-right min-width-400" aria-labelledby="page-header-notifications">
                                <h5 style="padding-top:10px;display:inline-block;" class="h6 text-center py-10 mb-10 text-uppercase">Notifications</h5>
                                @if (Auth::user()->unreadNotifications->count() >= 2)
                                <p style="padding-top:10px;float: right;display:inline-block;" class="h6 text-center py-10 mb-10">
                                    <a class="h6 text-center py-10 mb-10 text-danger" href="/pms/markAllAsRead">Mark all read</a></p><br>
                                    
                                @endif
                                
                                <div class="dropdown-divider"></div>
                                <ul class="list-unstyled my-10" style="width:400px;">
                                    @if (Auth::user()->unreadNotifications->count() == 0)
                                        <li class="text-muted text-center mb-10">
                                            You have no unread notifications
                                        </li>
                                    @endif
                                    <?php
                                        $notifications = auth()->user()->unreadNotifications()->paginate(6);
                                    ?>
                                <!--<div class="block-content example-1 scrollbar-ripe-malinka">-->
                                    
                                    <ul class="list-group" style="width: 400px;">
                                        @foreach (Auth::user()->unreadNotifications()->paginate(10) as $notifications)
                                        <li id="listt" class="list-group-item list-group-item-default" 
                                            style="padding-top:0px;padding-bottom:10px; 
                                            border-radius:1%; border-style:none;">
                                            
                                        <div class="mark-as-read" data-id="{{$notifications->id}}" onclick="location.href='{{$notifications->data['link']}}';" style="cursor: pointer;" > 

                                            <a style="width:95%;float:left; text-decoration:none;color:black;">
                                                {!!$notifications->data['message']!!}
                                            </a>
                                            
                                            <small {{-- style="float: right;margin-right:8%;" --}}><b>{{$notifications->created_at}}</b></small>
                                            
                                        </div>
                                            <a href="#" style="position: sticky; top: -50px;float: right;display:inline-block;" class="mark-as-read text-danger" data-id="{{$notifications->id}}">
                                                <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                            </a>
                                            
                                        </li>
                                        @endforeach
                                        <div class="dropdown-divider"></div>
                                        @if(Auth::user()->unreadNotifications->count() >= 0 )
                                        <li>
                                            <a class="dropdown-item text-center mb-0" href="/pms/fetchNoti">
                                                <i class="fa fa-flag mr-5"></i> View All
                                            </a>
                                            {{-- <button type="button" class="btn btn-default btn-block">See more unread...</button> --}}
                                        </li>
                                        @endif
                                    </ul>
                                    
                                <!--</div>-->
                                    {{-- <div class="float-container">
                                        @foreach ($notifications as $notification )
                                        <a class="dropdown-item rounded" href="{{$notification->data['link']}}"
                                            style="overflow-wrap: break-word;margin-top:1%;background-color:rgb(73, 196, 196);
                                                margin-left:1%;margin-right:2%; font-size:15px; word-wrap: break-word;
                                                white-space: normal !important; width:90%;float: left;">{!!$notification->data['message']!!}
                                        
                                        </a>
                                        <a href="/markAsRead/{{$notification->id}}" data-id="{{$notification->id}}" style="float: left;" class="text-danger">
                                            <i class="fa fa-close" style="width:10px;height:10px;" aria-hidden="true"></i>
                                        </a>
                                        @endforeach
                                    </div> --}}
                                </ul>
                                
                                
                                {{-- <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-center mb-0" href="/fetchNoti">
                                    <i class="fa fa-flag mr-5"></i> View All
                                </a> --}}
                            </div>
                        </div>
                        <!-- END Notifications -->
                        <!-- User Dropdown -->
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user d-sm-none"></i>
                                <span class="d-none d-sm-inline-block">{{Auth::user()->fname}} {{Auth::user()->lname[0]}} .</span>
                                <i class="fa fa-angle-down ml-5"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                                <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase"></h5>
                                <a class="dropdown-item" href="{{ url('profile') }}">
                                    <i class="si si-user mr-5"></i> Profile
                                </a>
                                  @if(Gate::any(['newrole', 'crud']))
                                <div class="dropdown-divider"></div>

                                <!-- Toggle Side Overlay -->
                                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                              
                                <a class="dropdown-item" href="{{ url('settings') }}" data-toggle="layout" data-action="">
                                    <i class="si si-wrench mr-5"></i> Settings
                                </a>
                                @endif
                                <!-- END Side Overlay -->

                                <div class="dropdown-divider"></div>
                                
                                 <a class="dropdown-item" href="{{ url('faq') }}">
                                    <i class="si si-question mr-5"></i> FAQ & Tutorials
                                </a>
                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ url('support') }}">
                                    <i class="si si-support mr-5"></i> Support
                                </a>
                                <div class="dropdown-divider"></div>

                                <li  class="dropdown-item" ><a  href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                          document.getElementById('logout-form').submit();">
                                                           <i class="si si-logout mr-5"></i>
                                             {{ __('Sign Out') }}
                                         </a>

                                         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                             @csrf
                                         </form>
                                        </li>
                            </div>
                        </div>
                        <!-- END User Dropdown -->

                      
                       


                    </div>
                    <!-- END Right Section -->
                </div>
                <!-- END Header Content -->

                <!-- Header Search -->
                <div id="page-header-search" class="overlay-header">
                    <div class="content-header content-header-fullrow">
                        <form action="/dashboard" method="POST">
                            @csrf
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <!-- Close Search Section -->
                                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                                    <button type="button" class="btn btn-secondary" data-toggle="layout" data-action="header_search_off">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <!-- END Close Search Section -->
                                </div>
                                <input type="text" class="form-control" placeholder=" Search " id="page-header-search-input" name="page-header-search-input">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-secondary">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                   </div>
                </div>
                <!-- END Header Search -->

                <!-- Header Loader -->
                <!-- Please check out the Activity page under Elements category to see examples of showing/hiding it -->
                <div id="page-header-loader" class="overlay-header bg-primary">
                    <div class="content-header content-header-fullrow text-center">
                        <div class="content-header-item">
                            <i class="fa fa-sun-o fa-spin text-white"></i>
                        </div>
                    </div>
                </div>
                <!-- END Header Loader -->
            </header>
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">
                @yield('content')
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="opacity-0">
                <div class="content py-20 font-size-sm clearfix">
                    <div class="float-right">
                        <a class="font-w600" href="https://www.ienetworksolutions.com/" target="_blank">IE Networks</a> &copy; <span class="js-year-copy"></span>
                    </div>
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->


       <!-- Custom JS -->


            <!--<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>-->
            
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

         
            <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
           
            
           
    


            <script src="{{asset('js/custom.js?ver=6.3')}}"></script>
            <script src="{{asset('js/kpi.js')}}"></script>
            <script src="{{asset('js/jquery.nameBadges.js')}}"></script>
            <script src="{{ asset('js/jquery.mentiony.js') }}"></script>
            <script src="{{asset('js/mentionfile.js')}}"></script>


    

        <!-- Codebase Core JS -->


       <!--script to make notification panel to not close when clicked inside-->
        <script>
            $('#notiGui').bind('click', function (e) { e.stopPropagation() })
        </script>
        <!-- script for sending ajax request for mark as read notification -->
        <script>
            var _token = $('input[name="_token"]').val();
        
            function sendMarkRequest(id = null,_token){
                var _token = $('input[name="_token"]').val();
                //console.log(id);
                //console.log(_token);
                return $.ajax("{{ route ('admin.markNotification') }}", {
                    method:'POST',
                    data:{
                        _token:_token,
                        id:id
                    }
                });
            }
            $(function() {
            $('.mark-as-read').click(function(){
                var cou = document.getElementById('counter').innerHTML;
                let request = sendMarkRequest($(this).data('id'));
                request.done(()=>{
                    $(this).parents('li#listt').remove();
                    cou--;
                    document.getElementById('counter').innerHTML=cou;
                });
            });
            $('#mark-all').click(function(){
                let request = sendMarkRequest();
                request.done(() =>{
                    $('div.alert').remove();
                })
                });
            });   
        </script>

        <script src="{{ asset('js/codebase.app.js') }}"></script>

        <!-- Laravel Scaffolding JS -->
        <script src="{{ asset('js/laravel.app.js') }}"></script>
        <script src="{{asset('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('js/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('js/pages/be_tables_datatables.min.js')}}"></script>
<!--<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>-->

        
        {{-- <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js?lang=javascript&amp;skin=desert"></script> --}}

        <!-- Page JS Code -->
        {{-- <script src="assets/js/pages/be_comp_charts.min.js"></script> --}}

        <!-- Page JS Helpers (Easy Pie Chart Plugin) -->
        <script>jQuery(function(){ Codebase.helpers('easy-pie-chart'); });</script>

        @yield('js_after')
    </body>

</html>
