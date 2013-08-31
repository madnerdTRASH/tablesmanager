/*

Alert Notification Manager
By Rémi Sarrailh Gplv3
Don't undestand something ? contact me at : maditnerd@gmail.com

*/

//Alert Notifications
function notify(message,type)
{
	//If the type specified doesn't exist then attribute an alert-success
	var error_class = "alert-success";
	
	//Define the color of the error message
	switch(type)
	{
		case "ok":
			 error_class = "alert-success"; //Green
			 break;

			 case "warning":
			error_class = "alert-warning"; //Yellow
			break;

			case "error":
			error_class = "alert-error"; //Red
			break;
		}

		$('#alert_zone').html("<div class='alert "+error_class+" fade in'><button type='button' class='close' data-dismiss='alert'>&times;</button><span id='alert-text'><h4>"+message+"</h4></span></div>");
		window.setTimeout(function() { $(".alert").alert('close'); }, 2000);
	}
