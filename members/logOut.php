<?php
session_start();
if(!isset($_SESSION["uid"])) {
	session_unset();
	session_destroy();
	header("Location: /underConstruction/mohfail.jpg?Easter=Egg");
	exit();
}
session_unset();
session_destroy();
header("Location: /logIn.php?sCode=102");
exit();
?>