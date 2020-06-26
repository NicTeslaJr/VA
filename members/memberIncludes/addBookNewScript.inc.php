<?php

// $statusMsg = "";
// if(isset($_GET["aCode"])) {
// 	$aCode = $_GET["aCode"];
// 	if($aCode === "101") {
// 		$statusMsg = "Contact successfully added to your Address Book<br><br>";
// 	}
// }

require("../includes/dbconnect.inc.php");

$err = "";
$errFlag = $successFlag = 0;
$fullName = $email = $phone = "";

$addBookTable = "addbooks";

function format_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if($_SERVER["REQUEST_METHOD"] === "POST") {

	if(!empty($_POST["fullName"]) && !empty($_POST["email"]) && !empty($_POST["phone"])) {
		$fullName = format_input($_POST["fullName"]);
		$email = format_input($_POST["email"]);
		$phone = format_input($_POST["phone"]);
		if(!preg_match("/^[a-zA-Z. ]*$/", $fullName)) {
			$err = "Invalid Name";
			$errFlag = 1;
		}
		if(!preg_match("/^[0-9]{10}$/", $phone)) {
			$err = "Invalid Phone Number";
			$errFlag = 1;
		}
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$err = "Invalid Email ID";
			$errFlag = 1;
		}
	} else {
		$err = "All fields required.";
		$errFlag = 1;
	}

	if($errFlag === 0) {
		try {
			$checkStmt = $conn->prepare("SELECT addID FROM $addBookTable WHERE (EMail = :email OR Phone = :phone) AND ID = :id;");
			$checkStmt->bindParam(":email", $email);
			$checkStmt->bindParam(":phone", $phone);
			$checkStmt->bindParam(":id", $_SESSION["uid"]);
			$checkStmt->execute();
			if($checkStmt->rowCount() > 0) {
				$err = "Email or Phone already in your Address Book";
				$errFlag = 1;
			}
		} catch(PDOException $e) {
			// echo $e;
		}
	}

	if($errFlag === 0) {
		try {
			$addConStmt = $conn->prepare("INSERT INTO $addBookTable (ID, FullName, EMail, Phone) VALUES (:id, :fullName, :email, :phone);");
			$addConStmt->bindParam(":id", $_SESSION["uid"]);
			$addConStmt->bindParam(":fullName", $fullName);
			$addConStmt->bindParam(":email", $email);
			$addConStmt->bindParam(":phone", $phone);
			$addConStmt->execute();
			$successFlag = 1;
		} catch(PDOException $e) {
			// echo $e;
		}
	}

	if($successFlag === 1) {
		$conn = null;
		header('Location: addBook.php?uid='.$_SESSION["uid"]);
		// To use the below code, add the statusCode check to addBook.php, not addContact.php as I did above.
		// header('Location: addBook.php?uid='.$_SESSION["uid"].'&aCode=101');
		exit();
	}

}

$conn = null;

?>