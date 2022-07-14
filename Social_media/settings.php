<?php 
include("include/header.php");
include("include/form_handler/settings_handler.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Profile</title>
	<style>
		.setting_column{
			min-height: 456px;
			height: auto;
			text-align: center;
			width:40%;
			background-color: #fff;
			color: #000;
		}
		@media screen and (max-width: 700px) {
		.setting_column{
			min-height: 465px;
			height: auto;
			text-align: center;
			width:100%;
			background-color: #fff;
			color: #000;
		}
	}
	</style>
</head>
<body>


<center>
<div class="setting_column">

	<h2>Account Settings</h2>
	<?php
	echo "<img src='" . $user['profile_pic'] ."' class='small_profile_pic'>";
	?>
	<br>
	<a href="upload.php">Upload new profile picture</a> <br><br><br>

	<hr><h3>Modify the values and click 'Update Details'</h3>

	<?php
	$user_data_query = mysqli_query($con, "SELECT first_name, last_name, email FROM users WHERE username='$userLoggedIn'");
	$row = mysqli_fetch_array($user_data_query);

	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$email = $row['email'];
	?>

	<form action="settings.php" method="POST">
		First Name: <input type="text" name="first_name" value="<?php echo $first_name; ?>" id="settings_input"><br>
		Last Name: <input type="text" name="last_name" value="<?php echo $last_name; ?>" id="settings_input"><br>
		Email: <input type="text" name="email" value="<?php echo $email; ?>" id="settings_input"><br>

		<?php echo $message; ?>

		<input type="submit" name="update_details" id="save_details" value="Update Details" class="info settings_submit"><br>
	</form><hr>

	<h3>Change Password</h3>
	<form action="settings.php" method="POST">
		Old Password: <input type="password" name="old_password" id="settings_input"><br>
		New Password: <input type="password" name="new_password_1" id="settings_input"><br>
		New Password Again: <input type="password" name="new_password_2" id="settings_input"><br>

		<?php echo $password_message; ?>

		<input type="submit" name="update_password" id="save_details" value="Update Password" class="info settings_submit"><br>
	</form><hr>

	<!--Bio Update starts Here!-->
	<h3>Please Update your bio from here</h3>
	<?php
	$user_bio_query = mysqli_query($con, "SELECT bio FROM bio WHERE username='$userLoggedIn'");
	$row = mysqli_fetch_array($user_bio_query);
	$bio = $row['bio'];
	?>
	<form action="settings.php" method="POST">
		<input name="bio_text" id="bio_text" value="<?php echo $bio;?>"></input><br><br>
		<input type="submit" name="update_bio"  value="Update Bio" class="info settings_submit"><br>
	</form>	<hr>

	<h3>Close Account</h3>
	<form action="settings.php" method="POST">
		<input type="submit" name="close_account" id="close_account" value="Close Account" class="danger settings_submit">
	</form>
</div>
</center>
</body>
</html>