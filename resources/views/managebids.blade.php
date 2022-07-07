@extends('layouts.backend')
@section('content')
<div class="container">
    <div class="mt-50 mb-10 text-center">
        <button class="btn btn-rounded btn-outline-info min-width-125 float-right" data-toggle="modal" data-target="#addbid">Add Bid</button>
        <h2 class="font-w700 text-black mb-10">Bids</h2>
        <hr>
    </div>
</div>
<div class="container">
    @if($new->count()>0)
    <h4 class="font-w700 text-black mb-10">New</h4>
    <div class="row gutters-tiny">
    @foreach($new as $n)
        
        <div class="col-md-4">
            <div class="block bg-gd-sea">
                <a href="{{route('bids.show', $n->id)}}">
                <div class="block-content">
                
                    <p class="mt-5 text-center">
                        <i class="si si-trophy fa-3x text-white-op"></i>
                    </p>
                    <h5 class="font-w300 text-white text-center">{{$n->bid_name}}</h5>
                </div>
                </a>
            </div>
          </div>
     
     @endforeach
   
       
       
    </div>
     <hr>
     @endif
     @if($bids->count()>0)
     @foreach($sessions as $session)
      @if($bids->where('session_id',  $session->session_id )->first())
         <h4 class="font-w700 text-black mb-10">{{$session->session_name}}</h4>
    <div class="row gutters-tiny">
       
       
         @foreach($bids as $bid)
         @if($bid->session_id == $session->session_id)
        <div class="col-md-4">
            <div class="block bg-gd-sea">
                <a href="{{route('bids.show', $bid->id)}}">
                <div class="block-content">
                
                    <p class="mt-5 text-center">
                        <i class="si si-trophy fa-3x text-white-op"></i>
                    </p>
                    <h5 class="font-w300 text-white text-center">{{$bid->bid_name}}</h5>
                </div>
                </a>
            </div>
        </div>
        @endif
        @endforeach
       
    </div>
     @endif
     @endforeach
     @endif
</div>
@include('includes.add-bids')
@endsection
