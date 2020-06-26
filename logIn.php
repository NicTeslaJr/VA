<?php
$webPageTitle = "Login@VA";
$cssFileName = "formStyle.css";
$pageCode = 0;
$displayFooter = 0;
require("header.php");
?>


<?php require("includes/logInScript.inc.php"); ?>
	<div class="signUpForm">
		<p id="statusMsg"><?php echo $statusMsg; ?></p>
		<ul class="choiceTabs">
			<a href="/signUp.php"><li class="tabOne">Sign Up</li></a>
			<li class="tabTwo" id="onTab">Log In</li>
		</ul>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<input type="text" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : "" ?>">
			<input type="password" name="passwd" placeholder="Password">
			<p id="fpwd"><a href="">Forgot your password?</a></p>
			<p><?php echo $err ?></p>
			<input id="btn" type="submit" name="submit" value="Submit">
		</form>
	</div>
<?php
require("footer.php");
?>