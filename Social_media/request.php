<?php
include_once("include/header.php"); 
include_once("include/classes/User.php");
include_once("include/classes/Post.php");
include_once("include/classes/Notification.php");

?>
<!DOCTYPE html>
<html>
<head>
	<title>Requests Status</title>
	<style>
		.request_column{
			min-height: 456px;
			height: auto;
			text-align: center;
			width:40%;
			background-color: #fff;
			color: #000;
		}
		@media screen and (max-width: 700px) {
		.request_column{
			min-height: 465px;
			height: auto;
			text-align: center;
			width:60%;
			background-color: #fff;
			color: #000;
		}
	}
		#accept_button{
			width: 20%;
			height: 28px;
			border-radius: 5px;
			margin: 5px;
			border:none;
			color:#fff;
			background-color:#1ACB23; 
		}
		@media screen and (max-width: 700px) {
		#accept_button{
			width: 30%;
			height: 28px;
			border-radius: 5px;
			font-size: 15px;
			margin: 5px;
			border:none;
			color:#fff;
			background-color:#1ACB23; 
		}
	}
		#ignore_button{
			width: 20%;
			height: 28px;
			border-radius: 5px;
			margin: 5px;
			border:none;
			color:#fff;
			background-color:#C3342C;
		}

			@media screen and (max-width: 700px) {
			#ignore_button{
			width: 30%;
			height: 28px;
			border-radius: 5px;
			font-size: 15px;
			margin: 5px;
			border:none;
			color:#fff;
			background-color:#C3342C;
		}
	}
	</style>
</head>
<body>

<center>
<div class="request_column" id="main_column">

	<h4>Friend Requests</h4>

	<?php  

	$query = mysqli_query($con, "SELECT * FROM friend_requests,users WHERE user_to='$userLoggedIn' AND 							user_from=username");
	if(mysqli_num_rows($query) == 0)
		echo "You have no friend requests at this time!";
	else {

		while($row = mysqli_fetch_array($query)) {
			$user_from = $row['user_from'];
			$user_pic = $row['profile_pic'];
			$user_from_obj = new User($con, $user_from);
			echo "<img src='$user_pic' style='height:50px; width:45px; border-radius:50%;'>";?> &nbsp;&nbsp;
			<?php
			echo  "<a href='$user_from'>" .$user_from_obj->getFirstAndLastName() . " </a>sent you a friend request!";
			

			$user_from_friend_array = $user_from_obj->getFriendArray();

			if(isset($_POST['accept_request' . $user_from ])) {
				$add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$user_from,') WHERE username='$userLoggedIn'");
				$add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$userLoggedIn,') WHERE username='$user_from'");

				$delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
				echo "You are now friends!";
				header("Location: request.php");
			}

			if(isset($_POST['ignore_request' . $user_from ])) {
				$delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
				echo "Request ignored!";
				header("Location: request.php");
			}

			?>
			<form action="request.php" method="POST">
				<input type="submit" name="accept_request<?php echo $user_from; ?>" id="accept_button" value="Accept">
				<input type="submit" name="ignore_request<?php echo $user_from; ?>" id="ignore_button" value="Ignore">
			</form>
			<hr>
			<?php


		}
	

	}

	?>


</div>
</center>
</body>
</html>