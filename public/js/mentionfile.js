$(document).ready(function($) {
  
 var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
 
 $.get(baseurl + "/allusers/" , function(udata) {
        // var users_data = [];
                
        //         for (var i = 0; i < data.length; i++) {
        //             users_data[i] = {};
        //             users_data[i].key = data[i].fname + " " + data[i].lname;
        //             users_data[i].value = data[i].fname + " " + data[i].lname;  
        //             users_data.push(users_data[i]);  
        //         }

             
                  
    $('#commentbody').mentiony({
        onDataRequest: function (mode, keyword, onDataRequestCompleteCallback) {
            
            var data = [];
            //var text ='<div class="avatar-circle" style="width:40px; height:40px; border-radius:50%;"><span class="initials">' +  udata[i].fname[0] + udata[i].lname[0]+'</span></div>'
            for (var i = 0; i < udata.length; i++) {
                    data[i] = {};
                    data[i].id = udata[i].id  
                    data[i].name = "@" + udata[i].fname + " " + udata[i].lname;
                    data[i]['avatar'] =  udata[i].avatar != null ?"/pms/uploads/avatars/"+ udata[i].avatar: "/pms/uploads/avatars/user.png"
                    data[i]['info'] = udata[i].position 
                    data[i]['href'] = "#"
                     
                    data.push(data[i]);  
         
                }
            data = jQuery.grep(data, function( item ) {
                return item.name.toLowerCase().indexOf(keyword.toLowerCase()) > -1;
            });

            // Call this to populate mention.
            onDataRequestCompleteCallback.call(this, data);
        },
        timeOut: 0,
        debug: 1,
    });
});


}); //end of jscript