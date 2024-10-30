
var $ = jQuery.noConflict();

function checkUserNameAvailability(urlpath,str)
{
		$.ajax({url: urlpath+'?&action=binarymlm_username'+'&q='+str, success: function(result){
	        	$('#check_user').html(result);
	    	}});  
}

function checkReferrerAvailability(urlpath,str)
{
		$.ajax({url: urlpath+'?&action=binarymlm_sponsor'+'&q='+str, success: function(result){
		        	$('#check_referrer').html(result);
		    	}});
}

function update_payment_status(urlpath, id,special,status)
{
		$.ajax({url: urlpath+'?do=statuschange'+'&action=binarymlm_paymentstatus'+'&userId='+id +'&status='+ status+'&special='+special, success: function(result){
        	$('#resultmsg_'+id).html(result);
    	}});
}


function setePinUser(urlpath, user_id, epin)
{ 
		$.ajax({url: urlpath+'?action=binarymlm_epinupdate'+'&userId='+user_id + '&epin=' + epin , success: function(result){
        	$('#epinmsg_'+user_id).html(result);
    	}});
}

function reset_all_data(urlpath){ 

if (confirm("Are you sure you wish to erase ALL MLM data. There is no undo for this action.")) {
    $.ajax({
        url: urlpath+'?action=binarymlm_resetdata',
        type: "POST",
        success: function(data) {
            $('#resetmessage').html(data);
        }
    });
    return false;
}
}

function withdrawal_init(){
    $.ajax({url: urlpath+'?action=withdrawal_init'+'&userId='+user_id + '&epin=' + epin , success: function(result){
            $('#epinmsg_'+user_id).html(result);
        }});
}

function checkePinAvailability(urlpath, str)
    {

        var iChars = "~`!@#$%^&*()+=[]\\\';,- ./{}|\":<>?abcdefghijklmnopqrstuvwxyz";
        for (var i = 0; i < str.length; i++)
        {
            if (iChars.indexOf(str.charAt(i)) != -1)
            {
                alert("Please enter Valid ePin.");
                document.getElementById('epin').value = '';
                document.getElementById('epin').focus();
                return false;
            }
        }
        $.ajax({url: urlpath+'?action=binarymlm_checkepin'+'&q='+str, success: function(result){
        	$('#check_epin').html(result);
    	}});

	}




