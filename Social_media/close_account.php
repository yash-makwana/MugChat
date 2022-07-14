<?php
include("include/header.php");

if(isset($_POST['cancel'])) {
	header("Location: settings.php");
}

if(isset($_POST['close_account'])) {
	$close_query = mysqli_query($con, "UPDATE users SET user_closed='yes' WHERE username='$userLoggedIn'");
	session_destroy();
	header("Location: register.php");
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Close Account</title>
	<style>
		.closed_column{
			min-height: 456px;
			height: auto;
			text-align: center;
			width:40%;
			font-size: 17px;
			font-weight: 50px;
			font-family: sans-serif;
			background-color: #fff;
			color: #000;
		}
		@media screen and (max-width: 700px) {
		.closed_column{
			min-height: 465px;
			height: auto;
			text-align: center;
			width:60%;
			background-color: #fff;
			color: #000;
		}
	}
	</style>
</head>
<body>

<center>
<div class="closed_column">

	<h4>Close Account</h4>

	Are you sure you want to close your account?<br><br>
	Closing your account will hide your profile and all your activity from other users.<br><br>
	You can re-open your account at any time by simply logging in.<br><br>

	<form action="close_account.php" method="POST">
		<input type="submit" name="close_account" id="close_account" value="Yes! Close it!" class="danger settings_submit">  &nbsp;&nbsp;&nbsp;
		<input type="submit" name="cancel" id="update_details" value="No way!" class="info settings_submit">
	</form>

</div>
</center>

</body>
</html>