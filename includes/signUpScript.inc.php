<?php

require("dbconnect.inc.php");

$err = "";
$errFlag = 0;
$finalEntryFlag = $successFlag = 0;

$fname = $lname = $email = "";
$passPlain = $passHash = "";

$tableName = "accounts";

function format_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if($_SERVER["REQUEST_METHOD"] === "POST") {

	if(!empty($_POST["fname"]) && !empty($_POST["lname"])) {
		$fname = format_input($_POST["fname"]);
		$lname = format_input($_POST["lname"]);
		if(!preg_match("/^[a-zA-Z]*$/", $fname)) {
			$err = "Invalid First Name";
			$errFlag = 1;
		}
		if(!preg_match("/^[a-zA-Z]*$/", $lname)) {
			$err = "Invalid Last Name";
			$errFlag = 1;
		}
	} else {
		$err = "All fields are required";
		$errFlag = 1;
	}

	if(!empty($_POST["email"])) {
		if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			$email = format_input($_POST["email"]);
		} else {
			$err = "Invalid e-mail address";
			$errFlag = 1;
		}
	} else {
		$err = "All fields are required";
		$errFlag = 1;
	}

	if(!empty($_POST["passwd1"]) || !empty($_POST["passwd2"])) {
		if($_POST["passwd1"] === $_POST["passwd2"]) {
			if(strlen($_POST["passwd1"]) < 7) {
				$err = "Password must be at least 7 characters long";
				$errFlag = 1;
			} else {
				// $passHash = password_hash($_POST["passwd1"], PASSWORD_ARGON2I);
				$passHash = password_hash($_POST["passwd1"], PASSWORD_DEFAULT);
			}
		} else {
			$err = "Passwords do not match";
			$errFlag = 1;
		}
	} else {
		$err = "All fields are required";
		$errFlag = 1;
	}

	if($errFlag === 0) {

		try {

			$checkStmt = $conn->prepare("SELECT SInator FROM $tableName WHERE EMail = :email;");
			$checkStmt->bindParam(":email", $email);
			$checkStmt->execute();

			if($checkStmt->rowCount() > 0) {
				$err = "E-mail address already registered";
				$errFlag = 1;
				$conn = null;
			} else {
				$finalEntryFlag = 1;
			}

		} catch(PDOException $e) {
			$errFlag = 1;
			// echo $e;
		}

	}

	if($finalEntryFlag === 1) {

		try {

			$insStmt = $conn->prepare("INSERT INTO $tableName (FName, LName, EMail, passHash) VALUES (:fname, :lname, :email, :passHash);");
			$insStmt->bindParam(":fname", $fname);
			$insStmt->bindParam(":lname", $lname);
			$insStmt->bindParam(":email", $email);
			$insStmt->bindParam(":passHash", $passHash);

			// The following isset condition is not required, as seen in the above "already-registered" check.
			if(isset($_POST["submit"])) {
				$insStmt->execute();
				$successFlag = 1;
				$conn = null;
			}

		} catch(PDOException $e) {
			$errFlag = 1;
			// echo $e;
		}

	}

	if($successFlag === 1) {
		header("Location: /logIn.php?sCode=101");
		exit();
	}

}

$conn = null;

?>
