<!DOCTYPE html>
<html lang="en">

<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>Performance Managment System</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <!-- Bootstrap core CSS -->

        <script src="{{ asset('js/main.js') }}" defer></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>



        <link rel="stylesheet"  href="{{ asset('css/style.css') }}" >

</head>

<body>



    <div class="page-wrapper chiller-theme toggled">
            <nav id="sidebar" class="sidebar-wrapper">
                <div class="sidebar-content">
                    <div id="toggle-sidebar">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <div class="sidebar-brand">
                        <a href="{{ url('home') }}">PMS</a>
                    </div>
                    <div class="sidebar-header">
                          <div class="user-info">
                            <span class="user-name">{{Auth::user()->fname}}
                                <strong>{{Auth::user()->lname}}</strong>
                            </span>
                            <span class="user-role">{{Auth::user()->position}}</span>
                        </div>
                    </div>
                    <!-- sidebar-header  -->

                    <!-- sidebar-search  -->
                    <div class="sidebar-menu">
                        <ul>
                            <li class="header-menu">
                                <span></span>
                            </li>
                            <li class="sidebar-dropdown">
                                <a href="{{ url('home') }}">

                                    <span>Dashboard</span>

                                </a>
                            </li>

                            <li class="sidebar-dropdown">
                                <a href="#">

                                    <span>OKR</span>
                                </a>
                                <div class="sidebar-submenu">
                                    <ul>
                                        <?php
                                        $sessions = App\Session::orderBy('id', 'DESC')->get()->take(3);
                                        ?>

                                        @foreach($sessions as $session)
                                        <li>
                                        <a class="" href="{{ route('okr', $session->id)}}">{{$session->session_name}}</a>
                                        </li>
                                        @endforeach
                                        <li>
                                            <a href="{{ url('sessions') }}">Manage Sessions</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="sidebar-dropdown">
                                <?php
                                    $message = "#";
                                $team = App\Team::where('parentteam', Auth::user()->team)->get();
                                $allteams = App\Team::all();
                                $teamid = App\Team::where('team_name', Auth::user()->team)->first()->id;
                                ?>

                                <a href="#">

                                    <span>My Teams</span>
                                </a>
                                <div class="sidebar-submenu">
                                    <ul>
                                        @cannot('crud')
                                        <li>
                                            <a href="{{ route('checkin', $teamid) }}">{{Auth::user()->team}}</a>
                                        </li>

                                        @foreach($team as $t)

                                        <li>
                                            <a  href="{{ route('checkin', $t->id) }}">{{$t->team_name}}</a>
                                        </li>

                                        @endforeach
                                        @endcannot
                                        @can('crud')

                                        @foreach($allteams as $at)

                                        <li>
                                            <a  href="{{ route('checkin', $at->id) }}">{{$at->team_name}}</a>
                                        </li>

                                        @endforeach
                                        @endcan

                                    </ul>
                                </div>



                            </li>
                            <li class="sidebar-dropdown">
                                <a href="#">
                                    <span>Settings</span>
                                </a>
                                <div class="sidebar-submenu">
                                    <ul>
                                        <li>
                                            <a href="{{ url('users') }}">Users</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('teams') }}">Teams</a>
                                        </li>
                                        <li class="sidebar-dropdown"><a href=""
                                            onclick="">
                                             {{ __('Profile') }}
                                         </a>
                                         </li>
                                        <li ><a  href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                          document.getElementById('logout-form').submit();">
                                             {{ __('Logout') }}
                                         </a>

                                         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                             @csrf
                                         </form>
                                        </li>
                                    </ul>
                                </div>
                            </li>



                        </ul>
                    </div>
                    <!-- sidebar-menu  -->
                </div>
                <!-- sidebar-content  -->

            </nav>
            <!-- sidebar-wrapper  -->
        <main class="page-content">

            @yield('content')

        </main>

        </div>

        <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>

        <script   src="{{asset('js/custom.js')}}"></script>
        {{-- <script   src="{{asset('js/addTask.js')}}"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        {{-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> --}}



        <script src="https://d3js.org/d3.v3.min.js"></script>

        <script src="{{ asset('js/tree.js') }}" defer></script>
</body>

</html>
