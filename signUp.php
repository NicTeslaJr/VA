<?php
$webPageTitle = "Sign Up for VA";
$cssFileName = "formStyle.css";
$pageCode = 0;
$displayFooter = 0;
require("header.php");
?>


<?php require("includes/signUpScript.inc.php"); ?>
	<div class="signUpForm">
		<ul class="choiceTabs">
			<li class="tabOne" id="onTab">Sign Up</li>
			<a href="/logIn.php"><li class="tabTwo">Log In</li></a>
		</ul>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<input id="firstField" type="text" name="fname" placeholder="First Name" value="<?php echo isset($_POST['fname']) ? $_POST['fname'] : "" ?>">
			<input id="secondField" type="text" name="lname" placeholder="Last Name" value="<?php echo isset($_POST['lname']) ? $_POST['lname'] : "" ?>">
			<input type="text" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : "" ?>">
			<input type="password" name="passwd1" placeholder="Password" value="<?php echo isset($_POST['passwd1']) ? $_POST['passwd1'] : "" ?>">
			<input id="lastField" type="password" name="passwd2" placeholder="Confirm Password" value="<?php echo isset($_POST['passwd2']) ? $_POST['passwd2'] : "" ?>">
			<p><?php echo $err ?></p>
			<input id="btn" type="submit" name="submit" value="Submit">
		</form>
	</div>
	<!-- <div class="one">
		<ul>
			...
		</ul>
	</div>
	<div class="two"></div> -->
<?php
require("footer.php");
?>