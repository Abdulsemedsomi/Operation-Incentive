@extends('layouts.simple')
@section('content')

<link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
<div class="container" style=" text-align: center;
padding: 40px 0;
background: #EBF0F5;">
            <div class="card" style="  background: white;
            padding: 60px;
            border-radius: 4px;
            box-shadow: 0 2px 3px #C8D0D8;
            display: inline-block;
            margin: 0 auto;">
            <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
              <i class="checkmark" style=" color: #9ABC66;
              font-size: 100px;
              line-height: 200px;
              margin-left:-15px;">✓</i>
            </div>
              <h1 style="color: #88B04B;
              font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
              font-weight: 900;
              font-size: 40px;
              margin-bottom: 10px;" >Success</h1> 
              <p style="color: #404F5E;
              font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
              font-size:20px;
              margin: 0;>Thank you for contacting us ;<br/> we'll be in touch shortly!</p>
            </div>
          </body>
</div>
@endsection
