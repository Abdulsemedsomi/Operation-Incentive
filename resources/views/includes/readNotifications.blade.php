
@foreach ($notifications as $notification )
    <!--<div style="background-color:lightgray;">-->
    <!--    <a  href="{{$notification->data['link']}}" >{{$notification->data['message']}}</a>-->
    <!--    <a href="/pms/markAsRead/{{$notification->id}}" class="pull-right badge badge-pill badge-danger">-->
    <!--        Mark as unread</a>-->
    <!--</div><hr>-->
    
      <div>
        <a class="font-w400" href="{{$notification->data['link']}}"><div class="badge badge-pill badge-info"><i class="si si-bulb text-white"></i></div> {!!$notification->data['message']!!}
            <a href="/pms/markAsUnRead/{{$notification->id}}" class="pull-right badge badge-pill badge-danger">
                  Mark as unread 
            </a>
        </a>
     </div>
       <hr>
    
@endforeach