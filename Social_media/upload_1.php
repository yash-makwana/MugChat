<?php 
	include_once("include/header.php");
	include_once("include/classes/User.php");
	include_once("include/classes/Notification.php");

  

?>

<!DOCTYPE html>
<html>
<head>
	<title>Upload</title>
	<style>
		.upload_column{
			min-height: 470px;
			height: auto;
			text-align: center;

			width:40%;
			background-color: #fff;
			color: #000;
		}
		@media screen and (max-width: 700px) {
		.upload_column{
			min-height: 465px;
			height: auto;
			text-align: center;
			width:60%;
			background-color: #fff;
			color: #000;
		}
	}
		.upload_column input[type="submit"]{
			width: 10%;
			height: 40px;
			border: none;
			border-radius: 3px;
			background-color: #4CC82D;
			font-family: 'Bellota-BoldItalic', sans-serif;
			color: #fff;
			text-shadow: #73B6E2 0.5px 0.5px 0px;
			font-size: 90%;
			text-align: center;
			outline: 0;
			margin: 0
		}
		@media screen and (max-width: 700px) {
		.upload_column input[type="submit"]{
			width: 20%;
			height: 30px;
			font-size: 15px;
		}
	}
	</style>
</head>
<body>


<center>
	<div class="upload_column">
		
		<form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
		<input type="file" name="fileToUpload" id="fileToUpload">
        <textarea name="post_text" id="post_text" placeholder="Do you want to add caption? or want to say something without uploading a post?"></textarea>
			<center><input type="submit" name="post" id="post_button" value="Post"></center>
			</form>

		<hr>
		</center>
  </div>

  </body>
</html>