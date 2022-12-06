<?php
session_start();

require('src/log.php');

if(isset($_SESSION['connect'])) {
	header('location: index.php');
	exit();
}

if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_two'])) {

	require("src/connect.php");

	$email        = htmlspecialchars($_POST['email']);
	$password     = htmlspecialchars($_POST['password']);
	$pass_confirm = htmlspecialchars($_POST['password_two']);

	if ($password != $pass_confirm) {
		header('Location: inscription.php?error=1&message=Vos mots de passe de sont pas identiques');
		exit();
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header('location: inscription.php?error=1&message=Votre adresse email est invalide.');
		exit();
	}

	$req = $db->prepare("SELECT count(*) as numberEmail FROM user WHERE email = ?");
	$req->execute(array($email));

	while($email_verification = $req->fetch()){

		if($email_verification['numberEmail'] != 0){

			header('location: inscription.php?error=1&message=Votre adresse email est déjà utilisée.');
			exit();

		}
	}
	
	$secret = sha1($email).time();
	$secret = sha1($secret).time();

	$password = "aq1".sha1($password."123")."25";

	function randLetter() {
		$int = rand(0,51);
		$a_z = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$rand_letter = $a_z[$int];
        return $rand_letter;
	}

	function randNumber() {
		$int = rand(0, 10);
        return $int;
	}

	$secretCode = sha1(randLetter().randLetter().randLetter().randNumber().randNumber());
	

	$req = $db->prepare("SELECT * FROM user ORDER BY id DESC LIMIT 1");
	$req->execute();

	while ($user = $req->fetch()) {
		$id_profile = $user['id_profile'];
		$int_profile = intval($id_profile);
		$result_profile = $int_profile += 1;


		$req = $db->prepare("INSERT INTO user(email, password, secret, secret_code, id_profile) VALUES(?,?,?,?,?)");
		$req->execute(array($email, $password, $secret, $secretCode, $result_profile));

		$req = $db->prepare("CREATE TABLE profile$result_profile (
			id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			`pseudo` TEXT NOT NULL,
			`url` TEXT NOT NULL,
			`watched` TEXT NULL)");
		$req->execute();
	}

	setcookie('secretCode', $secretCode, time()+3600*24, '/', '', false, false);

	header('location: index.php');
	exit();


}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Netflix</title>
	<link rel="stylesheet" type="text/css" href="design/style.css">
	<link rel="icon" type="image/pngn" href="img/favicon.png">
</head>
<body>
	<?php include('src/header.php'); ?>
	
	<section>
		<div id="login-body">
			<h1>S'inscrire</h1>

			<?php
			if(isset($_GET['error'])) {
				if(isset($_GET['message'])) {
					echo '<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
				}
			}else if(isset($_GET['success'])) {
				echo '<div class="alert success">Vous êtes désormais inscrit.<a href="index.php">Connectez-vous</a></div>';
			}
			?>

			<form method="post" action="inscription.php">
				<input type="email" name="email" placeholder="Votre adresse email" required />
				<input type="password" name="password" placeholder="Mot de passe" required />
				<input type="password" name="password_two" placeholder="Retapez votre mot de passe" required />
				<button type="submit">S'inscrire</button>
			</form>

			<p class="grey">Déjà sur Netflix ? <a href="index.php">Connectez-vous</a>.</p>
		</div>
	</section>

	<?php include('src/footer.php'); ?>
</body>
</html>