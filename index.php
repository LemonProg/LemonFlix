<?php
session_start();

require('src/log.php');
require('src/connect.php');

if(!empty($_SESSION['email_register']) && !empty($_SESSION['password_register'])) {
	$email 		= htmlspecialchars($_SESSION['email_register']);
	$password 	= htmlspecialchars($_SESSION['password_register']);

	$password = "aq1".sha1($password."123")."25";

	$req = $db->prepare("SELECT * FROM user WHERE email = ?");
	$req->execute(array($email));
	while($user = $req->fetch()){
		$_SESSION['connect']	 = 1;
		$_SESSION['email']   	 = $user['email'];
		$secretCode 			 = $user['secret_code'];

		if (isset($_POST['auto'])) {
			setcookie('auth', $user['secret'], time() + 364*24*3600, '/', null, false, true);
			setcookie('secretCode', $secretCode, time() + 364*24*3600, '/', null, false, true);
		}

		header('location: Netflix/main.php');
		exit();
	}
}

if(!empty($_POST['email']) && !empty($_POST['password'])){
	$email 		= htmlspecialchars($_POST['email']);
	$password 	= htmlspecialchars($_POST['password']);

	$password = "aq1".sha1($password."123")."25";

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header('location: inscription.php?error=1&message=Votre adresse email est invalide.');
		exit();
	}

	$req = $db->prepare("SELECT count(*) as numberEmail FROM user WHERE email = ?");
	$req->execute(array($email));

	while($email_verification = $req->fetch()){

		if($email_verification['numberEmail'] != 1){

			header('location: index.php?error=1&message=Impossible de vous authentifer.');
			exit();

		}
	}

	$req = $db->prepare("SELECT * FROM user WHERE email = ?");
	$req->execute(array($email));
	while($user = $req->fetch()){

		if($password == $user['password']){

			$_SESSION['connect']	 = 1;
			$_SESSION['email']   	 = $user['email'];
			$secretCode 			 = $user['secret_code'];

			if (isset($_POST['auto'])) {
				setcookie('auth', $user['secret'], time() + 364*24*3600, '/', null, false, true);
				setcookie('secretCode', $secretCode, time() + 364*24*3600, '/', null, false, true);
			}

			header('location: Netflix/main.php');
			exit();

		}
		else {

			header('location: index.php?error=1&message=Impossible de vous authentifier correctement.');
			exit();

		}
	}

}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Connection - LemonFlix</title>
	<link rel="stylesheet" type="text/css" href="design/style.css">
	<link rel="icon" type="image/pngn" href="img/favicon.png">
</head>
<body>

	<?php include('src/header.php'); ?>
	
	<section>
		<div id="login-body">
				<?php
					if (isset($_SESSION['connect'])) {
						$Code = htmlspecialchars($_COOKIE['secretCode']);
						header("location: Netflix/main.php");
					}else {?>
						
						<h1>S'identifier</h1>

						<?php if (isset($_GET['error'])) {
							if(isset($_GET['message'])) {
								echo '<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
							}
						} else if (isset($_GET['success'])) {
							echo '<div class="alert success">Vous êtes désormais connecté.</div>';
						}
						
						?>

						<form method="post" action="index.php">
							<input type="email" name="email" placeholder="Votre adresse email" required />
							<input type="password" name="password" placeholder="Mot de passe" required />
							<button type="submit">S'identifier</button>
							<label id="option"><input type="checkbox" name="auto" checked />Se souvenir de moi</label>
						</form>
					

						<p class="grey">Première visite sur Netflix ? <a href="inscription.php">Inscrivez-vous</a>.</p>
						
					<?php }?>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>