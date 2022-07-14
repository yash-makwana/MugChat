 <?php 
 	require 'config/config.php';
 	include("include/classes/User.php");
	include("include/classes/Post.php");
	include("include/classes/Message.php");
	include("include/classes/Notification.php");
 	if (isset($_SESSION['username'])) {
 		# code...
 		$userLoggedIn =$_SESSION['username'];
 		$user_details_query = mysqli_query($con,"SELECT * from users where username='$userLoggedIn'");
 		$user = mysqli_fetch_array($user_details_query);
 		
 		
 	}
 	else
 	{
 		header('Location: register.php');
 	}


  ?>


 <!DOCTYPE html>
 <html>
 <head>
 	<title></title>
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> 
 	<script type="text/javascript" src="assets/js/bootstrap.js"></script>
 	<script type="text/javascript" src="assets/js/demo.js"></script>
 	<script type="text/javascript" src="assets/js/bootbox.min.js"></script>
 	<script type="text/javascript" src="assets/js/jcrop_bits.js"></script>
 	<script type="text/javascript" src="assets/js/jquery.Jcrop.js"></script>
 	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
 	<link rel="stylesheet" type="text/css" href="assets/css/jquery.Jcrop.css">
 	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
 	<link rel="stylesheet" type="text/css" href="assets/css/profile.css">
 	<link rel="icon" href="assets/images/icons/mc.png">
 
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 </head>
 <body style="background-color: #F7F7F7;">
 		<div class="top_bar">
 			<div class="logo">

 				<a href="index.php">MugChat</a>
 			</div>

 				<div class="search">

			<form action="search.php" method="GET" name="search_form">
				<input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input">

				<div class="button_holder">
					<img src="assets/images/icons/magnifying_glass.png">
				</div>

			</form>

			<div class="search_results">
			</div>

			<div class="search_results_footer_empty">
			</div>

		</div>


 			<nav>	

 					<?php
						//Unread messages 
						$messages = new Message($con, $userLoggedIn);
						$num_messages = $messages->getUnreadNumber();
					?>

					<?php
						//Unread Notification
						$notification = new Notification($con, $userLoggedIn);
						$num_notification = $notification->getUnreadNumber();
					?>

					<?php
						//Friend Request
						$user_obj = new User($con, $userLoggedIn);
						$num_req = $user_obj->getNumberOfFriendRequest();
					?>
 		
 		
 				
 				<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message');" title="Message"><i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
 					<?php
				if($num_messages > 0)
				 echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
				?>
 				</a>
 				<a href="" title="Profile Pic"><img src="<?php echo $user['profile_pic']?>" style="margin-bottom:15px;"></a>
 				<a href="">
 					<?php 
 					echo $user['first_name']. " " . $user['last_name'];
 					 ?>

 				</a>
 				<a href="include/handlers/logout.php" title="Logout"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
 			</nav>
 					<div class="dropdown_data_window" style="height:0px; border:none;"></div>
					<input type="hidden" id="dropdown_data_type" value="">
 			</div>

 			<script>
				var userLoggedIn = '<?php echo $userLoggedIn; ?>';

				$(document).ready(function() {

				$('.dropdown_data_window').scroll(function() {
				var inner_height = $('.dropdown_data_window').innerHeight(); //Div containing posts
				var scroll_top = $('.dropdown_data_window').scrollTop();
				var page = $('.dropdown_data_window').find('.nextPageDropdownData').val();
				var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

			  	//var scrollHeight, totalHeight;
   			 	//scrollHeight = document.body.scrollHeight;
    			//totalHeight = window.scrollY + window.innerHeight;


				if((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false') {

					var pageName;  	//holds the page name to send ajax request to
					var type = $('#dropdown_data_type').val();

					if (type =='notification')
						pageName = "ajax_load_notification.php"
					else if (type=='message') 
						pageName = "ajax_load_messages.php";

					var ajaxReq = $.ajax({
					url: "include/handlers/" + pageName,
					type: "POST",
					data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
					cache:false,

					success: function(response) {
						$('.dropdown_data_window').find('.nextPageDropdownData').remove(); //Removes current .nextpage 
						$('.dropdown_data_window').find('.noMoreDropdownData').remove(); //Removes current .nextpage 

						$('.dropdown_data_window').append(response);
					}
				});

			} //End if 

			return false;

		}); //End (window).scroll(function())


	});

	</script>


 				<div class="main_nav">
 					
 	 					<ul>
							  <li><a class="active" href="./index.php"><i class="fa fa-home fa-3x" aria-hidden="true"></i><br>Home</a></li>							  
							   <li><a href="upload_1.php"><i class="fa fa-plus fa-3x"></i><br>Upload</a></li>
							   <li><a href="request.php" title="Requests"><i class="fa fa-user-plus fa-3x" aria-hidden="true"></i>
 					<?php
				if($num_req > 0)
				 echo '<span class="request_badge" id="unread_requests">' . $num_req . '</span>';
				?><br>Requests</a></li>
							   <li><a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification');"><i class="fa fa-bell fa-3x" aria-hidden="true"></i>
					 					<?php
									if($num_notification > 0)
									 echo '<span class="badge_noti" id="unread_notification">' . $num_notification . '</span>';
									?><br>
					 				Notification</a></li>
							  <li><a href="profile_1.php" id="profilepage"><i class="fa fa-user fa-3x" aria-hidden="true"></i><br>Profile</a></li>
						</ul>
 				
 			</div>

 	

 </body>
 </html>



 