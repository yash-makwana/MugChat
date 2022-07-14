<?php 
	include("include/header.php");
	 	if (isset($_SESSION['username'])) {
 		# code...
 		$userLoggedIn =$_SESSION['username'];
 		$user_details_query = mysqli_query($con,"SELECT * FROM users WHERE username='$userLoggedIn'");
 		$user_bio_query = mysqli_query($con,"SELECT * FROM bio WHERE username='$userLoggedIn'");
 		$user = mysqli_fetch_array($user_details_query);
 		$user_bio = mysqli_fetch_array($user_bio_query);

 		}
 		else{
 			header('Location: register.php');
 		}
 		$num_friends = ((substr_count($user['friend_array'], ","))-1);

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>Profile</title>
 	<link rel="stylesheet" type="text/css" href="assets/css/profile.css">
 </head>
 <body>		
 		
 			
 		<div class="main">
 			
 			<a href="#"><img src="<?php echo $user['profile_pic'];?>"></a><br>
 			<h1><?php 
 				echo $user['username'];
 			?></h1>	
 			
 			</div>
 			<div class="box_1">
 				<h2><?php 
 				echo $user['first_name']. " " . $user['last_name']; 
 				
 			?></h2>	
 				<p><?php
 				echo $user_bio['bio'];
 					?></p>
 				<a href="settings.php"><button class="btn btn-info">Edit Profile</button></a>
 			</div><br><br>

 			<div class="box_2">
 				<ul>
 					<li><a href="#post"><?php echo "Posts:-" . $user['num_posts']; ?></a></li>
 					<li><a href="#"><?php echo "Total Friends:-" . $num_friends; ?></a></li>
 					<li><a href="#post_friend"><?php echo "Total Likes:-" . $user['num_likes']; ?></a></li>
 				</ul>
 			</div><br><br>
 			<div class="posts_profile" id="post"> 
 			<div id="newsfeed_div">
					<div class="posts_area"></div>
					<center><img id="loading" src="assets/images/icons/loading.gif"></center>
      		</div>


 			<script>
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';
	var profileUsername = '<?php echo $userLoggedIn; ?>';

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
