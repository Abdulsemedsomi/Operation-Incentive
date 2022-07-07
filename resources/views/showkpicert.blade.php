@extends('layouts.backend')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
<hr>
<div class="container">
     <div>
          @php
    $sign =  App\User::find($engagement->issuer)? (App\User::find($engagement->issuer)->signature != null? 'https://ienetworks.co/pms/uploads/signature/' . App\User::find($engagement->issuer)->signature: 'images/Sign.png') :  'https://ienetworks.co/pms/images/Sign.png';
    @endphp 
       <img src="https://ienetworks.co/pms/images/cert.jpg" style="width: 100%;">
      </div>
         <div style="color:#828687; margin-right:37%; margin-top:-25%;">
           <div style="font-family:'Lato', sans-serif; font-weight:300;" >
            <p class="mt-40" style="font-weight:400; font-size:14px; margin-left:16%;  margin-top:-57% !important; margin-bottom:20%;">{{App\Filledkpilist::find($engagement->filledkpilistid)?App\Kpiform::find(App\Filledkpilist::find($engagement->filledkpilistid)->kpiform_id)->objective:"" }}</p>
           
            <p style="font-family:'Dancing Script', cursive; font-weight:200; font-size:34px; margin-left:23%; margin-top:-12%">{{App\User::find($engagement->issued_to)->fname . " ". App\User::find($engagement->issued_to)->lname}}</p>
            <p style="font-weight:300; font-size:14px; margin-left:65%;  margin-top:17%;"> {{Carbon\Carbon::parse($engagement->created_at)->isoFormat('MMMM Do YYYY')}}</p>
            <img src="{{$sign}}" style="margin-top:-24%; margin-left:18%; height:4em; weight:4em;"  alt="ie-logo">
          </div>

      </div>
  </div>
@endsection

