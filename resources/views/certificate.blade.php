<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>
    <body>
        @php
        $sign =  App\User::find($engagement->issuer)? (App\User::find($engagement->issuer)->signature != null? 'uploads/signature/' . App\User::find($engagement->issuer)->signature: 'images/Sign.png') :  'images/Sign.png';
        @endphp 
       <div class="container">
         <div>
           <img src="https://ienetworks.co/pms/images/cert.jpg" style="width: 100%;">
          </div> 
             <div style="color:#828687; margin-right:37%; margin-top:-61%;">
                <div style="font-family:'Lato', sans-serif; font-weight:300;">
                    <div style="font-weight:300; font-size:12px; margin-left:16%; width:85% ; margin-top:2% !important; position:absolute;"><p style="font-weight:500; font-size:13px;">{{App\Engagement::where('id',$engagement->engagement_id)->first()?App\Engagement::where('id',$engagement->engagement_id)->first()->Objective:"" }}</p> <p "font-weight:500; font-size:13px; margin-top:-5% !important;">{{$engagement->Reason != null? $engagement->Reason :"" }}</p></div>
                    
                    <p style="font-family: 'Dancing Script', cursive; font-weight:300; font-size:30px; width:75%; margin-left:20%; margin-top:35%; position:absolute;">{{App\User::find($engagement->issued_to)->fname . " ". App\User::find($engagement->issued_to)->lname}}</p>
                    <p style="position:absolute; font-weight:300; font-size:14px; margin-left:57%;  margin-top:68%;"> {{Carbon\Carbon::parse($engagement->created_at)->isoFormat('MMMM Do YYYY')}}</p>
                    <img src="{{$sign}}" style="position:absolute; margin-top:55%; margin-left:15%; height:4em; weight:4em;" alt="ie-logo">
                    <p style="font-family: 'Dancing Script', cursive; font-weight:300; font-size:13px; position:absolute; margin-top:70%; margin-left:33%; height:4em; weight:4em;">{{App\User::find($engagement->issuer)->fname . " ". App\User::find($engagement->issuer)->lname}}</p>
                </div>
          </div> 
      </div>
    </body>
</html>
