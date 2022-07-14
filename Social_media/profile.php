<?php 
	include("include/header.php");
	include_once("include/classes/User.php");
	include_once("include/classes/Post.php");
	include_once("include/classes/Message.php");
	include_once("include/classes/Notification.php");
	$message_obj = new Message($con,$userLoggedIn);
	$logged_in_user_obj = new User($con,$userLoggedIn);
	if (isset($_GET['profile_username'])) {
		$username = $_GET['profile_username'];
		$user_details_query= mysqli_query($con,"SELECT * FROM users WHERE username='$username'");
		$user_bio_query = mysqli_query($con,"SELECT * FROM bio WHERE username='$username'");
		$user_array = mysqli_fetch_array($user_details_query); 
		$user_bio = mysqli_fetch_array($user_bio_query);
		$num_friends = ((substr_count($user_array['friend_array'], ","))-1); 
	}
	if (isset($_POST['remove_friend'])) {
		$user = new User($con,$userLoggedIn);
		$user->removeFriend($username);
	}
	if (isset($_POST['add_friend'])) {
		$user = new User($con,$userLoggedIn);
		$user->sendRequest($username);
	}
	if (isset($_POST['respond_request'])) {
		header('Location:request.php');
	}

	if(isset($_POST['post_message'])) {
  if(isset($_POST['message_body'])) {
    $body = mysqli_real_escape_string($con, $_POST['message_body']);
    $date = date("Y-m-d H:i:s");
    $message_obj->sendMessage($username, $body, $date);
  }

  $link = '#profileTabs a[href="#messages_div"]';
  echo "<script> 
          $(function() {
              $('" . $link ."').tab('show');
          });
        </script>";

}
	
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>Profile</title>
 	<link rel="stylesheet" type="text/css" href="assets/css/profile.css">
 </head>
 <body>		
 		
 			
 		<div class="main">
 			
 			<a href="#"><img src="<?php echo $user_array['profile_pic'];?>"></a><br>
 			<h1><?php 
 				echo $user_array['username'];
 			?></h1>	
 			
 			</div>
 			<div class="box_1">
 				<h2><?php 
 				echo $user_array ['first_name']. " " . $user_array['last_name'];
 			?></h2>	
 			<p><?php
 				echo $user_bio['bio'];
 					?></p>
 				
 			</div><br><br>
 			<div class="box_2">
 				<ul>
 					<li><a href="#post_friend"><?php echo "Posts:-" . $user_array['num_posts']; ?></a></li>
 					<li><a href="#"><?php echo "Total Friends:-" . $num_friends; ?></a></li>
 					<li><a href="#">
 						<?php 
 						if($userLoggedIn != $username){
 							echo '<div class="profile_info_button">';
 							echo $logged_in_user_obj->getMutualFriends($username) . " Mutual Friends";
 							echo '</div>';
 						}

 					?>
 					</a></li>
 					<li><a href="#form">
 						<form action="<?php echo $username;?>" method="POST">
 							<?php 
								$profile_user_obj = new User($con,$username);
								if($profile_user_obj->isClosed()){
									header('Location: user_closed.php');
								}
								

								if ($userLoggedIn != $username){
									if ($logged_in_user_obj->isFriend($username)){
										echo "<input type='submit' name='remove_friend' class='danger' value='Remove Friend'><br>";
									}
									elseif ($logged_in_user_obj->didReceiveRequest($username)){
										echo "<input type='submit' name='respond_request' class='warning' value='Respond to Request'><br>";
									}
									elseif ($logged_in_user_obj->didSendRequest($username)){

										echo "<input type='submit' name='' class='default' value='Request Sent'><br>";
									}
									else
										echo "<input type='submit' name='add_friend' class='success' value='Add Friend'><br>";
									}

								?>
 						</form>
 					</a></li>

 				</ul>
 					
 			</div><br>

 			<div class="post_friend" style=" background-color: #fff;" id="post_friend">
 					<ul class="nav nav-tabs" role="tablist" id="profileTabs">
					      <li role="presentation" class="active"><a href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Posts</a></li>
					      <li role="presentation"><a href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab">Messages</a></li>
					</ul>
					<div class="tab-content">
						
	 					<div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
					        <div class="posts_area"></div>
					        <img id="loading" src="assets/images/icons/loading.gif">
      					</div>

      					    <div role="tabpanel" class="tab-pane fade" id="messages_div">
        						<?php  
        					

          					echo "<h4>You and <a href='" . $username ."'>" . $profile_user_obj->getFirstAndLastName() . "</a></h4><hr><br>";

          					echo "<div class='loaded_messages' id='scroll_messages'>";
            				echo $message_obj->getMessages($username);
          					echo "</div>";
        						?>
							    <div class="message_post">
         					<form action="" method="POST">
             				<textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>
             				<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>
          					</form>

        						</div>

						    <script>
						      var div = document.getElementById("scroll_messages");
						      div.scrollTop = div.scrollHeight;
						    </script>
						  </div>
					</div>
 			<script>
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';
	var profileUsername = '<?php echo $username; ?>';

	$(document).ready(function() {

		$('#loading').show();

		//Original ajax request for loading first posts 
		$.ajax({
			url: "include/handlers/ajax_load_profile_posts.php",
			type: "POST",
			data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
			cache:false,

			success: function(data) {
				$('#loading').hide();
				$('.posts_area').html(data);


			}
		});

		$(window).scroll(function() {
			var height = $('.posts_area').height(); //Div containing posts
			var scroll_top = $(this).scrollTop();
			var page = $('.posts_area').find('.nextPage').val();
			var noMorePosts = $('.posts_area').find('.noMorePosts').val();

			  var scrollHeight, totalHeight;
   			 scrollHeight = document.body.scrollHeight;
    		totalHeight = window.scrollY + window.innerHeight;


			if ((totalHeight >= scrollHeight) && noMorePosts == 'false') {
				$('#loading').show();
				var ajaxReq = $.ajax({
					url: "include/handlers/ajax_load_profile_posts.php",
					type: "POST",
					data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
					cache:false,

					success: function(response) {
						$('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
						$('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 

						$('#loading').hide();
						$('.posts_area').append(response);
					}
				});

			} //End if 

			return false;

		}); //End (window).scroll(function())


	});

			</script>
 		</div>
 </body>
 </html>