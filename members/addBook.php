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
	<p id="heading">Your Address Book</p>
	<div id="addTable">
		<table class="fixed_header">
			<thead>
			<tr>
				<th>Name</th>
				<th>Address</th>
				<th>Phone Number</th>
			</tr>
			</thead>
			<?php

			require("../includes/dbconnect.inc.php");
			$addTable = "addbooks";
			try {
				$tableStmt = $conn->prepare("SELECT FullName, EMail, Phone FROM $addTable WHERE ID = :id ORDER BY FullName;");
				$tableStmt->bindParam(":id", $_SESSION["uid"]);
				$tableStmt->execute();
				if(($rows = $tableStmt->rowCount()) > 0) {
					$addresult = $tableStmt->fetchall(PDO::FETCH_ASSOC);
					echo '
					<tbody>
					';
					for($i = 0 ; $i < $rows ; $i++) {
						echo '
						<tr>
							<td>'.$addresult[$i]["FullName"].'</td>
							<td><a href="mailto:'.$addresult[$i]["EMail"].'">'.$addresult[$i]["EMail"].'</a></td>
							<td>'.$addresult[$i]["Phone"].'</td>
						</tr>
						';
					}
					echo '
					</tbody>
					';
				} else {
					echo '
					<tbody>
					<tr>
						<td></td>
						<td>- Your address book is empty -</td>
						<td></td>
					</tr>
					</tbody>
					';
				}
				$conn = null;
			} catch(PDOException $e) {
				// echo $e;
			}
			$conn = null;

			?>
		</table>
	</div>
	<div id="controls">
		<a href="addBookNew.php?uid=<?php echo $_SESSION['uid']; ?>">Add</a>&nbsp;&nbsp;|&nbsp;
		<a href="/underConstruction/">Delete</a>&nbsp;&nbsp;|&nbsp;
		<a href="/underConstruction/">Edit</a>
	</div>
</body>
</html>