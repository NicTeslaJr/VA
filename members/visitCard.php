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

// No Cache for VCard image
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$webPageTitle = "*this title should not be visible*";
$cssFileName = "afterLoginRightiFrame.css";
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
	<?php
	require("../includes/dbconnect.inc.php");
	$visitTable = "visitingcards";
	$vCardCheck = 0;
	try {
		$cardStmt = $conn->prepare("SELECT source FROM $visitTable WHERE ID = :id");
		$cardStmt->bindParam(":id", $_SESSION["uid"]);
		$cardStmt->execute();
		if(($vCardCheck = $cardStmt->rowCount()) === 1) {
			$result = $cardStmt->fetch(PDO::FETCH_ASSOC);
			$imgSrc = $result["source"];
			$imgSrc;
		}
	} catch(PDOException $e) {
		// echo $e;
	}
	$conn = null;
	?>
	<p id="heading">Your Visiting Card</p><br>
	<img id="visitingCard" src="<?php echo isset($imgSrc) ? $imgSrc : ''; ?>" alt="- You have not created a Visiting Card yet -">
	<div id="controls">
		<a href="visitCardNew.php?uid=<?php echo $_SESSION['uid']; ?>">New</a>&nbsp;&nbsp;|&nbsp;
		<a href="visitCard.php?uid=<?php echo $_SESSION['uid']; ?>&amp;share=<?php echo ($_GET['share'] === '1') ? '0' : '1' ?>">Share</a>
	</div>
	<?php
	if(isset($_GET['share']) && $_GET['share'] === "1") {
		function format_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		$err = "";
		$sendFlag = 0;
		$shareEmail = isset($_POST['shareEmail']) ? $_POST['shareEmail'] : '';
		$shareEmail = format_input($shareEmail);
		if($vCardCheck === 1) {
			if(!empty($shareEmail)) {
				if(filter_var($shareEmail, FILTER_VALIDATE_EMAIL)) {
					$sendFlag = 1;
				} else {
					$err = "Invalid Email ID";
				}
			}
		} else {
			$err = "You have no visiting card to share";
		}
		if($sendFlag === 1) {
			$cardSrc ='*******/******/******/memberResources/VCards/'.$_SESSION["uid"].'.png';
			$comm = 'python2 "*****/******/members/memberTools/shareVC.py" "'.$shareEmail.'" "'.$cardSrc.'" \'<password-goes-here>\' "'.$_SESSION["fullName"].'"';
			shell_exec($comm);
			// Not actually an error
			$err = "Visiting Card Sent!";
		}
		echo '
		<div id="shareForm">
			<form method="POST" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'?uid='.$_SESSION["uid"].'&amp;share=1">
				<p>Enter an Email Address to which to send this visiting card:</p>
				<input type="text" name="shareEmail" placeholder="Email" value="'.$shareEmail.'">
				<input type="submit" id="sBtn" name="submit" value="Submit">
			</form>
			<p>'.$err.'</p>
		</div>
		';
	}

	?>
</body>
</html>
