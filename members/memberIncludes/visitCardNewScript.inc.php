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
$errFlag = $updateFlag = $successFlag = 0;
$cName = $fullName = $email = $phone = $address1 = $address2 = "";
$visitTable = "visitingcards";

function format_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if($_SERVER["REQUEST_METHOD"] === "POST") {

	if(!empty($_POST["cName"]) && !empty($_POST["fullName"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["address1"]) && !empty($_POST["address2"])) {
		$cName = format_input($_POST["cName"]);
		$fullName = format_input($_POST["fullName"]);
		$email = format_input($_POST["email"]);
		$phone = format_input($_POST["phone"]);
		$address1 = format_input($_POST["address1"]);
		$address2 = format_input($_POST["address2"]);
		if(!preg_match("/^[a-zA-Z. ]*$/", $cName)) {
			$err = "Invalid Company Name";
			$errFlag = 1;
		}
		if(!preg_match("/^[a-zA-Z. ]*$/", $fullName)) {
			$err = "Invalid Name";
			$errFlag = 1;
		}
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$err = "Invalid Email ID";
			$errFlag = 1;
		}
		if(!preg_match("/^[0-9]{10}$/", $phone)) {
			$err = "Invalid Phone Number";
			$errFlag = 1;
		}
	} else {
		$err = "All fields required.";
		$errFlag = 1;
	}

	if($errFlag === 0) {
		$imgSrc = 'memberResources/VCards/'.$_SESSION['uid'].'.png';
		$comm = 'python2 "/opt/lampp/htdocs/members/memberTools/visitCardGenerate.py" "'.$cName.'" "'.$fullName.'" "'.$email.'" "'.$phone.'" "'.$address1.'" "'.$address2.'" "'.$_SESSION['uid'].'"';
		shell_exec($comm);
	}

	if($errFlag === 0) {
		try {
			$vcCheckStmt = $conn->prepare("SELECT cardID FROM $visitTable WHERE ID = :id;");
			$vcCheckStmt->bindParam(":id", $_SESSION["uid"]);
			$vcCheckStmt->execute();
			if($vcCheckStmt->rowCount() > 0) {
				$updateFlag = 1;
			}
		} catch(PDOException $e) {
			// echo $e;
		}
	}

	if($updateFlag === 1) {
		try {
			$updateStmt = $conn->prepare("UPDATE $visitTable SET source = :imgSrc WHERE ID = :id;");
			$updateStmt->bindParam(":imgSrc", $imgSrc);
			$updateStmt->bindParam(":id", $_SESSION['uid']);
			$updateStmt->execute();
			$successFlag = 1;
		} catch(PDOException $e) {
			// echo $e;
		}
	}

	if($errFlag === 0 && $updateFlag === 0) {
		try {
			$insVCStmt = $conn->prepare("INSERT INTO $visitTable (ID, source) VALUES (:id, :imgSrc);");
			$insVCStmt->bindParam(":id", $_SESSION["uid"]);
			$insVCStmt->bindParam(":imgSrc", $imgSrc);
			$insVCStmt->execute();
			$successFlag = 1;
		} catch(PDOException $e) {
			// echo $e;
		}
	}

	if($successFlag === 1) {
		$conn = null;
		header('Location: visitCard.php?uid='.$_SESSION["uid"]);
		// To use the below code, add the statusCode check to addBook.php, not addContact.php as I did above.
		// header('Location: addBook.php?uid='.$_SESSION["uid"].'&aCode=101');
		exit();
	}

}

$conn = null;

?>