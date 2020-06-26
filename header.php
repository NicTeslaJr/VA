<?php
	// header.php and footer.php must be required on all pages.
	// Variables:
	// 	$pageCode:
	// 		0 - Non-member pages
	// 		1 - Member pages
	// 		2 - Other pages
	// 	$displayFooter:
	// 		1 - yes
	// 		x - no
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

	<header>
		<a href="/"><img src="/content/images/logo.png">
		<h1 id="siteName">Visiting Cards and Address Book</h1></a>
		<nav>
			<ul><?php
				if($pageCode === 0) {
					echo '
				<a href="/underConstruction/"><li id="samePageLink">Other Projects</li></a>
				<a href="/underConstruction/"><li id="samePageLink">About</li></a>
				<a href="mailto:va.wtoep@gmail.com"><li id="samePageLink">Contact Us</li></a>
				<a href="/logIn.php"><li>LogIn</li></a>
				<a href="/signUp.php"><li>SignUp</li></a>
';
				} else if($pageCode === 1) {
					echo '
				<li id="samePageText">Hello, '.$_SESSION["fullName"].'</li>
				<li id="lobtnli">
					<form method="POST" action="logOut.php">
						<input id="lobtn" type="submit" value="LogOut">
					</form>
				</li>
';
				}
				?>
			</ul>
		</nav>
	</header>
	<hr>