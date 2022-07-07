@extends('layouts.backend')

@section('content')

<br>

<div class="container" id="post-data" style="background-color:white; width:95%;">
      <div class="block-content">
            <h3 class="text-center"> <i class="si si-bell text-warning"></i> Unread Notifications</h3>
            <hr>
        </div>
     @include('includes.notification')
</div>
<div class="ajax-load text-center" style="display:none">
	<p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading...</p>
</div>
 <br>
 <h5 class="text-center"><a href="/pms/showReadNotifications">Show read notifications</a> <i class="fa fa-long-arrow-right"></i></h5>



<script type="text/javascript">
	var page = 1;
	$(window).scroll(function() {
	    if($(window).scrollTop() + $(window).height() >= $(document).height()) {
	        page++;
	        loadMoreData(page);
	    }
	});
	function loadMoreData(page){
	  $.ajax(
	        {
	            url: '?page=' + page,
	            type: "get",
	            beforeSend: function()
	            {
	                $('.ajax-load').show();
	            }
	        })
	        .done(function(data)
	        {
	            if(data.html == ""){
                    
	                $('.ajax-load').html("No more unread notifications found for you");
	                return;
	            }
	            $('.ajax-load').hide();
	            $("#post-data").append(data.html);
	        })
	        .fail(function(jqXHR, ajaxOptions, thrownError)
	        {
	              alert('server not responding...');
	        });
	}
</script>
@endsection