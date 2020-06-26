<?php
// Session Validation and Expiration Check this should be on any page or html/php material where a authenticated member can go
session_start();
session_regenerate_id();
if(isset($_SESSION["uid"]) && ($_SESSION["uid"] === $_GET["uid"])) {
	if(time() > $_SESSION["expire"]) {
		session_unset();
		session_destroy();
		header("Location: /logIn.php?sCode=103");
		exit();
	}
} else {
	session_unset();
	session_destroy();
	header("Location: /logIn.php?sCode=104");
	exit();
}

$webPageTitle = $_SESSION["fname"]."@VA";
$cssFileName = "afterLogin.css";
$pageCode = 1;
$displayFooter = 0;
require("../header.php");
?>


	<div id="mainInfo">
		<div id="mainLeft">
			<p>What do you want to do today?</p>
			<ul>
				<a href="index.php?uid=<?php echo $_SESSION['uid']; ?>&amp;mCode=1"><li <?php echo ((isset($_GET['mCode']) && ($_GET['mCode'] === '1')) ? 'id="selectedLink"' : ""); ?>>&gt; Your Visiting Card</li></a>
				<a href="index.php?uid=<?php echo $_SESSION['uid']; ?>&amp;mCode=2"><li <?php echo ((isset($_GET["mCode"]) && ($_GET['mCode'] === '2')) ? 'id="selectedLink"' : ""); ?>>&gt; Your Address Book</li></a>
				<a href="index.php?uid=<?php echo $_SESSION['uid']; ?>&amp;mCode=3"><li <?php echo ((isset($_GET["mCode"]) && ($_GET["mCode"] === "3")) ? 'id="selectedLink"' : ""); ?>>&gt; Your Reminders</li></a>
			</ul>
		</div>
		<div id="mainRight">
			<?php
			if(isset($_GET["mCode"]) && $_GET["mCode"] === "1") {
				echo '
				<iframe src="visitCard.php?uid='.$_SESSION["uid"].'&amp;share=0" frameborder="0"></iframe>
				';
			} elseif(isset($_GET["mCode"]) && $_GET["mCode"] === "2") {
				echo '
				<iframe src="addBook.php?uid='.$_SESSION["uid"].'" frameborder="0"></iframe>
				';
			} elseif(isset($_GET["mCode"]) && $_GET["mCode"] === "3") {
				echo '
				<iframe src="/underConstruction/" frameborder="0"></iframe>
				';
			}
			?>
		</div>
	</div>
<?php
require("../footer.php");
?>