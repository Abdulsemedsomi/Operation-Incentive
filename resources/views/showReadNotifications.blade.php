@extends('layouts.backend')

@section('content')
<br>
<div class="container" id="post-data" style="background-color:white; width:95%;">
     <div id="top" class="block-content">
            <h3 class="text-center"> <i class="si si-check"></i> Read Notifications</h3>
            <hr>
     </div>
    @include('includes.readNotifications')
</div>
<div class="ajax-load text-center" style="display:none">
	<p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading...</p>
</div>
 <br>
<h5 class="text-center"><a href="#top">Go to top</a> <i class="fa fa-long-arrow-up"></i></h5>
            
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
                    
	                $('.ajax-load').html("No more read notifications found for you");
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