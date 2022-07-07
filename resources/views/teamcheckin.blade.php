<style>
    .cards {
        padding: 40px;
        font-size: 15px;
        background: #fff;
        max-width: 1000px;
        border-radius: 20px;
        margin: 60px auto;
        font-family: 'Roboto', sans-serif;
    }
    </style>

@extends('layouts.backend')
    @section('content')
    <div class="container">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="{{ route('myteams', $team->id) }}">{{$team->team_name}}</a>
         <a class="breadcrumb-item"  href="{{ route('teamcheckin', ['teamid'=>$team->id, 'sessionid'=>$session->id]) }}" >{{$session->session_name}} </a>
        <span class="breadcrumb-item active">Checkin</span>
    </nav>
</div>
    <div style = "margin-top:10px;" class="">
    <h1 class="font-w400 text-center">Automatic Check-ins</h1>
        <a style="color:#636b6f; text-decoration:none" href="{{ route('tweeklyplan', ['teamid'=>$team->id, 'sessionid'=>$session->id]) }}" onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); });">
        <div class="neocard shadow1" >
            <h2>Weekly Plan</h2>
            <p>What are your goals for the next week?</p>
        </div>
        </a>
         <a style="color:#636b6f; text-decoration:none" href="{{ route('tweeklyreport', ['teamid'=>$team->id, 'sessionid'=>$session->id]) }}"  onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); });">
        <div class="neocard shadow2">
            <h2>Weekly Report</h2>
            <p>Which of your planned goals did you achieve this week? How much is that in percentage? Which unplanned tasks did you deliver? Which goals did you fail to achieve? Why?</p>
        </div>
        </a>
         <a style="color:#636b6f; text-decoration:none" href="{{ route('tdailyplan', ['teamid'=>$team->id, 'sessionid'=>$session->id]) }}" onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); });">
        <div class="neocard shadow1">
            <h2>Daily Plan</h2>
            <p>What are your targets for tomorrow?</p>
        </div>
        </a>
         <a style="color:#636b6f; text-decoration:none" href="{{ route('tdailyreport', ['teamid'=>$team->id, 'sessionid'=>$session->id]) }}" onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); });">
        <div class="neocard shadow2">
            <h2>Daily Report</h2>
            <p>What did you work on today? Which challenges did affect your progress today?</p>
        </div>
        </a>
       

       

       

    </div>
    @endsection
