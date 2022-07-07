
@extends('layouts.backend')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
<hr>
<div class="container">
     <div>
       <img src="https://ienetworks.co/pms/images/cert.jpg" style="width: 100%;">
      </div>
         <div style="color:#828687; margin-right:37%; margin-top:-25%;">
           <div style="font-family:'Lato', sans-serif; font-weight:300;" >
            <p class="mt-40" style="font-weight:400; font-size:14px; margin-left:16%;  margin-top:-57% !important; margin-bottom:20%;">{{App\Engagement::where('id',$engagement->engagement_id)->first()?App\Engagement::where('id',$engagement->engagement_id)->first()->Objective:"" }}</p>
            <p style="font-family: 'Dancing Script', cursive; font-weight:400; width:65%; font-size:10px; margin-left:10%;  margin-top:6% !important; margin-bottom:20%; position:absolute;">{{App\User::find($engagement->Reason)}}</p>
            <p style="font-family:'Dancing Script', cursive; font-weight:200; font-size:34px; margin-left:23%; margin-top:-12%">{{App\User::find($engagement->issued_to)->fname . " ". App\User::find($engagement->issued_to)->lname}}</p>
            <p style="font-weight:300; font-size:14px; margin-left:65%;  margin-top:17%;"> {{Carbon\Carbon::parse($engagement->created_at)->isoFormat('MMMM Do YYYY')}}</p>
            <img src="{{url('images/Sign.png')}}" style="margin-top:-24%; margin-left:18%; height:4em; weight:4em;"  alt="ie-logo">
          </div>

      </div>
  </div>
@endsection
