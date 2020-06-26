<?php
// Session Validation and Expiration Check this should be on any page or html/php material where a authenticated member can go
session_start();
session_regenerate_id();
if(isset($_SESSION["uid"]) && ($_SESSION["uid"] === $_GET["uid"])) {
	if(time() > $_SESSION["expire"]) {
		session_unset();
		session_destroy();
		header("Location: ifUA.php");
		exit();
	}
} else {
	session_unset();
	session_destroy();
	header("Location: ifUA.php");
	exit();
}

$webPageTitle = "*this title should not be visible*";
$cssFileName = "formStyle.css";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo $webPageTitle; ?></title>
	<link rel="shortcut icon" href="/content/images/favicon.ico" type='image/x-icon'>
	<link rel="stylesheet" type="text/css" media="screen" href="/stylesheets/general.css">
	<link rel="stylesheet" type="text/css" media="screen" href="/stylesheets/<?php echo $cssFileName; ?>">
</head>
<body>
	<?php require("memberIncludes/visitCardNewScript.inc.php"); ?>
		<div class="signUpForm">
			<a href="visitCard.php?uid=<?php echo $_SESSION['uid']; ?>"><span id="backBtn">&lt; Back</span></a>
			<p style="padding-top: 25px; text-align: center; font-size: 1.75em; color: #F27830;">Create a New Visiting Card:</p><br>
			<!-- <p id="statusMsg"><?php //echo $statusMsg; ?></p> -->
			<!-- <ul class="choiceTabs">
				<a href="/signUp.php"><li class="tabOne">Sign Up</li></a>
				<li class="tabTwo" id="onTab">Log In</li>
			</ul> -->
			<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?uid=<?php echo $_SESSION['uid'] ?>">
				<input type="text" name="cName" placeholder="Company Name" value="<?php echo isset($_POST['cName']) ? $_POST['cName'] : "" ?>">
				<input type="text" name="fullName" placeholder="Name" value="<?php echo isset($_POST['fullName']) ? $_POST['fullName'] : "" ?>">
				<input type="text" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : "" ?>">
				<input type="text" name="phone" placeholder="+91 (Phone Number)" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : "" ?>">
				<input type="text" name="address1" placeholder="Address Line 1" value="<?php echo isset($_POST['address1']) ? $_POST['address1'] : "" ?>">
				<input type="text" name="address2" placeholder="Address Line 2" value="<?php echo isset($_POST['address2']) ? $_POST['address2'] : "" ?>">
				<p><?php echo $err ?></p>
				<input id="btn" type="submit" name="submit" value="Submit">
			</form>
		</div>
	<?php
	?>