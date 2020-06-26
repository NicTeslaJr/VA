<?php

$statusMsg = "";
if(isset($_GET["sCode"])) {
	$statusCode = $_GET["sCode"];
	if($statusCode === "101") {
		$statusMsg = "You have Successfully Registered for VA<br>Please LogIn to continue<br><br>";
	} elseif($statusCode === "102") {
		$statusMsg = "You have Logged Out<br><br>";
	} elseif($statusCode === "103") {
		$statusMsg = "Your Session has expired<br>Please Login again<br><br>";
	} elseif($statusCode === "104") {
		$statusMsg = "Login to your account to continue<br><br>";
	} elseif($statusCode === "401") {
		$statusMsg = "Unauthorized Access<br><br>";
	}
}

require("dbconnect.inc.php");

$err = "";
$errFlag = $emailCheck = $successFlag = 0;
$email = "";
$tableName = "accounts";

function format_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if($_SERVER["REQUEST_METHOD"] === "POST") {

	if(!empty($_POST["email"])) {
		$email = format_input($_POST["email"]);
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			try {
				$chkEmail = $conn->prepare("SELECT * FROM $tableName WHERE EMail = :email;");
				$chkEmail->bindParam(":email", $email);
				$chkEmail->execute();
				if($chkEmail->rowCount() === 1) {
					$result = $chkEmail->fetch(PDO::FETCH_ASSOC);
					$emailCheck = 1;
				} else {
					$err = "Email address not found";
					$errFlag = 1;
				}
			} catch(PDOException $e) {
				$errFlag = 1;
				// echo $e;
			}
		} else {
			$err = "Invalid e-mail address";
			$errFlag = 1;
		}
	} else {
		$err = "E-mail address required";
		$errFlag = 1;
	}

	if($emailCheck === 1) {
		if(!empty($_POST["passwd"])) {
			if(password_verify($_POST["passwd"], $result["passHash"])) {
				// $err = "Password is valid";
				$conn = null;
				session_start();
				session_regenerate_id();
				$_SESSION["uid"] = $uid =  $result["ID"];
				$_SESSION["fname"] = $result["FName"];
				$_SESSION["fullName"] = $result["FName"]." ".$result["LName"];
				$_SESSION["start"] = time();
				$_SESSION["expire"] = $_SESSION["start"] + (15 * 60);
				session_commit();
				header("Location: /members/index.php?uid=$uid");
				exit();
			} else {
				$err = "Password is invalid";
				$errFlag = 1;
			}
		} else {
			$err = "Password required";
			$errFlag = 1;
		}
	}

}

$conn = null;

?>
